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

object PublicUser extends Controller {


	def index(userid: Int) = AuthAction { implicit request =>
		
		// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser
		/////////////////

		var dbCalls = new FrontendDbCalls()
		var publicuser = dbCalls.GetUserById(userid)
		var packages = dbCalls.GetPackagesByUserId(userid, true)		
		
		Ok(views.html.publicuser(publicuser, packages, isAuthed, user))

	}
}