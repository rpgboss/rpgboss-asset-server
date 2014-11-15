package controllers

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

import scala.collection.mutable._

import models._

import play.api.data._
import play.api.data.Forms._

object AdminPanel extends Controller {

	val profileForm = Form(
	  tuple(
	    "old_password" -> text,
	    "new_password" -> text
	  )
	)

	def lookat(packageid:Int) = Action { implicit request =>

	  // Authed
	  Auth.Check(request.cookies.get("session").get.value)
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			DB.withConnection { implicit connection =>

				var dbCalls = new FrontendDbCalls()
				var currentPackage = dbCalls.GetPackageById(packageid)

				Ok(views.html.adminpanel_unapproved_lookat(isAuthed,user, currentPackage))
			}
		}
	}

  def unapprovedindex = Action { implicit request =>
	  // Authed
	  Auth.Check(request.cookies.get("session").get.value)
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			DB.withConnection { implicit connection =>

				var packages:MutableList[AssetPackage] = MutableList()

				var sqlQuery2 = "select * from package WHERE `verified`=0 ORDER BY `created_at` DESC;"
				SQL(sqlQuery2)().foreach { row2 =>

					packages += new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"),row2[Int]("verified"))
				}

				Ok(views.html.adminpanel_unapproved(isAuthed, user, packages))
			}

  	}
	}
}