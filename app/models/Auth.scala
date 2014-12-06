package models

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

import models._

object Auth {

	private var User:models.User = null

	private var Authed:Boolean = false

	def GetUser:models.User = {
		return User;
	}

	def IsAuthed:Boolean = {
		return Authed
	}

	def Logout(user:models.User) = {
		DB.withConnection { implicit connection =>

			var uuid = java.util.UUID.randomUUID().toString()

			var sqlQuery2 = "UPDATE user SET `session`=\""+uuid+"\" WHERE `id`="+user.id+";"
			SQL(sqlQuery2).executeUpdate()

		}
	}

	def Check(session: String) = {
		var result = false

		var date = new java.util.Date()

		User = new models.User(0,"","",0,"","",0,"",Option(date))		

		DB.withConnection { implicit connection =>

			var sqlQuery2 = "select * from user WHERE `session`=\""+session+"\" AND `activated`=1;"

			SQL(sqlQuery2)().map { row2 =>
				result = true

				User = new models.User(
					row2[Int]("id"), 
					row2[String]("email"), 
					row2[String]("display_name"),
					row2[Int]("admin"),
					row2[String]("session"),
					row2[String]("password"),
					row2[Int]("activated"),
					row2[String]("activateHash"),
					row2[Option[java.util.Date]]("created_at")
				) 
			}
		}

		Authed = result
	}

}