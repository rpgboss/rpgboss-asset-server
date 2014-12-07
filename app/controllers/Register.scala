package controllers

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

import scala.collection.mutable._

import models._
import actions._

import play.api.data._
import play.api.data.Forms._

import play.api.libs.mailer._

object Register extends Controller {

	val registerForm = Form(
	  tuple(
	  	"email" -> text,
	    "password" -> text,
	    "password_repeat" -> text,
	    "display_name" -> text
	  )
	)

	def checkname(name:String) =  Action {
		var dbCalls = new FrontendDbCalls()
		var nameDoesExist = dbCalls.GetDisplayName(name);
		Ok(nameDoesExist.toString())
	}

	def checkemail(email:String) =  Action {
		var dbCalls = new FrontendDbCalls()
		var nameDoesExist = dbCalls.GetEmail(email);
		Ok(nameDoesExist.toString())
	}

  def index(error:Int) = AuthAction { implicit request =>

	  // Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser
		/////////////////

    Ok(views.html.register("",isAuthed, user,error))
  }

  def isValid(email: String): Boolean =
   if("""(?=[^\s]+)(?=(\w+)@([\w\.]+))""".r.findFirstIn(email) == None)false else true

  def attempt = AuthAction { implicit request =>

	  // Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser
		/////////////////

		DB.withConnection { implicit connection =>

			val (email, password, password_repeat, display_name) = registerForm.bindFromRequest.get

			if(password.size>6 && password_repeat.size>6 && email.size>6 && display_name.size > 3) {
				
				var dbCalls = new FrontendDbCalls()
				var nameDoesExist = dbCalls.GetDisplayName(display_name);
				var emailDoesExist = dbCalls.GetEmail(email);
				println(password)
				println(password_repeat)
				println(isValid(email))
				println(nameDoesExist==false)
				println(emailDoesExist==false)

				// Check all requirements for a successful register
				if(password == password_repeat && isValid(email) && nameDoesExist==false && emailDoesExist==false) {

					val md = java.security.MessageDigest.getInstance("SHA-1")
					var hashpassword = md.digest(password.getBytes("UTF-8")).map("%02x".format(_)).mkString

					var random_session = java.util.UUID.randomUUID().toString()
					var timestamp: Long = System.currentTimeMillis / 1000
					var random_hashcode = md.digest((email+timestamp.toString()).getBytes("UTF-8")).map("%02x".format(_)).mkString
					if(random_hashcode.size>32) random_hashcode = random_hashcode.substring(0, 32)

					var sqlQuery2 = "INSERT INTO user values(NULL, \""+email+"\", \""+display_name+"\", '"+hashpassword+"','"+random_session+"', 0,0,\""+random_hashcode+"\",NULL);"
					SQL(sqlQuery2).executeInsert()

					// Send confirmation mail
					val mail = MailerPlugin.email
					mail.setSubject("RPGBOSS Asset Server - Confirmation")
					mail.setRecipient(email)
					
					var host = request.host;
					var link = host + "/activate/account/" + random_hashcode

					mail.setFrom("RPGBOSS Asset Server <assetserver@rpgboss.com>")
					mail.sendHtml("<html><head></head><body><h1>Confirmation</h1><p>Click on <a href=\"http://"+link+"\">this link</a> to activate your Account.</p></body></html>" )

		    	Redirect("/register/confirmation_notice")

	    	} else {
	    		Redirect("/register?error=1")
	    	}

    	} else {
    		Redirect("/register?error=2")
    	}
  	}
  }

  def activate(activationkey: String) = AuthAction {

	  // Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser
		/////////////////

		DB.withConnection { implicit connection =>

  		var sqlQuery2 = "SELECT * FROM `user` WHERE `activateHash`=\""+activationkey+"\" AND `activated`=0;"
  		var dbCalls = new FrontendDbCalls()

  		var currentUser : User = null
  		var count = 0

  		SQL(sqlQuery2).apply().map { row:Row =>
  			currentUser = dbCalls.GetUserById(row[Int]("id"))
  			count = 1
  		}

  		if(currentUser!=null) {

				var timestamp: Long = System.currentTimeMillis / 1000
				var activation_random_hashcode = ("activated"+timestamp.toString())
				if(activation_random_hashcode.size>32) activation_random_hashcode = activation_random_hashcode.substring(0, 32)

	  		var sqlQuery3 = "UPDATE `user` SET `activateHash`=\""+activation_random_hashcode+"\",`activated`=1 WHERE `id`="+currentUser.id+";"
	  		SQL(sqlQuery3).executeUpdate()

  		}

  		Ok(views.html.activation_notice(activationkey,count, isAuthed, user))

  	}
  }

  def confirmation_notice = AuthAction {

	  // Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser
		/////////////////

  	Ok(views.html.confirmation_notice("", isAuthed, user))
  }
}