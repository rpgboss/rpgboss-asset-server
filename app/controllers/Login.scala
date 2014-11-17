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

object Login extends Controller {

	val loginForm = Form(
	  tuple(
	    "email" -> text,
	    "password" -> text
	  )
	)

	def logout = AuthAction { implicit request =>
	  	Auth.Logout(Auth.GetUser)

	  	Redirect("/").withCookies(Cookie("session", ""))
	}

	def attempt = AuthAction { implicit request =>

		DB.withConnection { implicit connection =>

			val (email, password) = loginForm.bindFromRequest.get

			val md = java.security.MessageDigest.getInstance("SHA-1")
			var hashpassword = md.digest(password.getBytes("UTF-8")).map("%02x".format(_)).mkString

			var sqlQuery2 = "select * from user WHERE `email`=\""+email+"\" AND `password`=\""+hashpassword+"\" AND `activated`=1;"

			var uuid="";

			var route = "/login?messageType=1"

			var currentUser:models.User = null
			SQL(sqlQuery2)().map { row2 => 

				
				currentUser = new models.User(
					row2[Int]("id"), 
					row2[String]("email"), 
					row2[String]("display_name"),
					row2[Int]("admin"),
					row2[String]("session"),
					row2[String]("password"),
					row2[Int]("activated"),
					row2[Option[java.util.Date]]("created_at")
				)

				uuid = java.util.UUID.randomUUID().toString()

		    var sqlQuery3 = "UPDATE user SET `session`=\""+uuid+"\" WHERE `id`=\""+row2[Int]("id")+"\";"
				SQL(sqlQuery3).executeUpdate()

				route = "/"
			}
			
			Redirect(route).withCookies(Cookie("session", uuid))

		}
	}

}