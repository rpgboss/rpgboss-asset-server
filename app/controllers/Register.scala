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

  def index = AuthAction { implicit request =>

	  // Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser
		/////////////////

    Ok(views.html.register("",isAuthed, user))
  }

  def attempt = AuthAction { implicit request =>

	  // Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser
		/////////////////

		DB.withConnection { implicit connection =>

			val (email, password, password_repeat, display_name) = registerForm.bindFromRequest.get
    	

    	Redirect("/register")

  	}
  }
}