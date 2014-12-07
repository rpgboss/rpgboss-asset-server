package models

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

import scala.collection.mutable._

import models._
import play.api.libs.mailer._

class Mails {

	def OnAUser(user: User,mailtext:String, title:String) = {

			// Send confirmation mail
			val mail = MailerPlugin.email
			mail.setSubject(title)
			mail.setRecipient(user.email)

			mail.setFrom("RPGBOSS Asset Server <assetserver@rpgboss.com>")
			mail.sendHtml("<html><head></head><body>"+mailtext+"</body></html>" )

	}

	def OnAllAdmins(mailtext:String, title:String) = {
		
		DB.withConnection { implicit connection =>

			val query = SQL("select id from `user` WHERE `admin`=1;")
			var dbCalls = new FrontendDbCalls() 
			query().foreach { row =>


				var user = dbCalls.GetUserById(row[Int]("id"))

				// Send confirmation mail
				val mail = MailerPlugin.email
				mail.setSubject(title)
				mail.setRecipient(user.email)

				mail.setFrom("RPGBOSS Asset Server <assetserver@rpgboss.com>")
				mail.sendHtml("<html><head></head><body>"+mailtext+"</body></html>" )

			}

		}
	}

}