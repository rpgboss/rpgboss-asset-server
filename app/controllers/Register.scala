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

			if(password == password_repeat && isValid(email)) {

				val md = java.security.MessageDigest.getInstance("SHA-1")
				var hashpassword = md.digest(password.getBytes("UTF-8")).map("%02x".format(_)).mkString

				var random_session = java.util.UUID.randomUUID().toString()

				var sqlQuery2 = "INSERT INTO user values(NULL, \""+email+"\", \""+display_name+"\", '"+hashpassword+"','"+random_session+"', 0,0,NULL);"
				SQL(sqlQuery2).executeInsert()
	    	

	    	Redirect("/register")

    	} else {
    		Redirect("/register?error=1")
    	}

  	}
  }
}