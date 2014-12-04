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

import java.io.File

object Comment extends Controller {

	val commentForm = Form(
	  tuple(
	  	"rating" -> number,
	    "text" -> text
	  )
	)


	def addComment(packageid: Int, redirect:String) = AuthAction { implicit request =>
		
		// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser
		/////////////////

		if(!isAuthed) {
			Redirect("/")
		} else {

			val (rating, text) = commentForm.bindFromRequest.get

			var cuttedText = text.toString();
			if(cuttedText.size>255) {
				cuttedText = cuttedText.substring(0, 255)
			}

			var safeComment = Jsoup.clean(cuttedText, Whitelist.basic());

			DB.withConnection { implicit connection =>
				var query = "INSERT INTO `comment` VALUES(NULL,'"+user.id+"','"+packageid+"',"+rating+",'"+safeComment+"',NULL);"
				SQL(query).executeInsert()

				Redirect(redirect)
			}

		}

	}
}