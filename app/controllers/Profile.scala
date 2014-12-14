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

object Profile extends Controller {

	val profileForm = Form(
	  tuple(
	    "old_password" -> text,
	    "new_password" -> text
	  )
	)

  def save = AuthAction { implicit request =>
	  	// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			var redirectUrl = "/profile?messageType=2"

			DB.withConnection { implicit connection =>

				val (old_password, new_password) = profileForm.bindFromRequest.get

				var cf = Play.current.configuration
				var salt = cf.getString("login.salt").getOrElse("")

				val md = java.security.MessageDigest.getInstance("SHA-1")
				var hashpassword = md.digest( (old_password+salt).getBytes("UTF-8")).map("%02x".format(_)).mkString

				var sqlQuery2 = "select * from user WHERE `email`=\""+user.email+"\" AND `password`=\""+hashpassword+"\" AND `activated`=1;"
				SQL(sqlQuery2)().map { row =>

					if(new_password.size >= 6) {

						var new_hashpassword = md.digest( (new_password+salt).getBytes("UTF-8")).map("%02x".format(_)).mkString

						var sqlQuery3 = "UPDATE user SET `password`=\""+new_hashpassword+"\" WHERE `id`="+user.id+";"

						SQL(sqlQuery3).executeUpdate()

						redirectUrl = "/profile?messageType=1"

					} else {
						redirectUrl = "/profile?messageType=3"
					}

				}

			}



			Redirect(redirectUrl)
	  }
	}
}