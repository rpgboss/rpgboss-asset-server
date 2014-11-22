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

import util._

import org.jsoup._
import org.jsoup.safety._

object AdminPanel extends Controller {

	val rejectionForm = Form(
	    "rejection_text" -> text
	)

	def rejectpackage(packageid:Int) = AuthAction { implicit request =>
	  	// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			DB.withConnection { implicit connection =>

				val (rejection_text) = rejectionForm.bindFromRequest.get

				var cuttedText = rejection_text.toString();
				if(cuttedText.size>255) {
					cuttedText = cuttedText.substring(0, 255)
				}

		  		var safeRejectionText = Jsoup.clean(cuttedText, Whitelist.basic());

				var sqlQuery2 = "UPDATE package SET `verified`=2, `rejection_text`=\""+safeRejectionText+"\" WHERE `id`=\""+packageid+"\";"
				SQL(sqlQuery2).executeUpdate()

				Redirect("/adminpanel/unapproved")

			}
		}	
	}

	def approvepackage(packageid:Int) = AuthAction { implicit request =>
	  	// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			DB.withConnection { implicit connection =>

				var sqlQuery2 = "UPDATE package SET `verified`=1, `rejection_text`=\"\" WHERE `id`=\""+packageid+"\";"
				SQL(sqlQuery2).executeUpdate()

				Redirect("/adminpanel/unapproved")

			}
		}	
	}

	def lookat(packageid:Int) = AuthAction { implicit request =>

	  	// Authed
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

  def unapprovedindex = AuthAction { implicit request =>
	  	// Authed
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

					packages += new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"),row2[Int]("verified"),row2[String]("rejection_text"),row2[String]("version"),row2[Int]("category_id"),row2[Int]("user_id"))
				}

				Ok(views.html.adminpanel_unapproved(isAuthed, user, packages))
			}

  	}
	}
}