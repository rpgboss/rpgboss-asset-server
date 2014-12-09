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

object ServeDynamicImage extends Controller {

	def serve(imagename: String) = Action { implicit request =>
		var cf = Play.current.configuration
		var path = cf.getString("upload.path").getOrElse("")

	    Ok.sendFile(
		    content = new java.io.File(path+"/"+imagename),
		    inline = true
		  )
	}
}