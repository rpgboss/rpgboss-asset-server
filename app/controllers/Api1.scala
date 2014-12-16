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

object Api1 extends Controller {

  def login(email:String, password:String) = Action { implicit request =>

	var cf = Play.current.configuration
	var salt = cf.getString("login.salt").getOrElse("")


	val md = java.security.MessageDigest.getInstance("SHA-1")
	var hashpassword = md.digest( (password+salt).getBytes("UTF-8")).map("%02x".format(_)).mkString

	var sqlQuery2 = "select * from user WHERE `email`=\""+email+"\" AND `password`=\""+hashpassword+"\" AND `activated`=1;"

	var uuid="";

	DB.withConnection { implicit connection =>

		SQL(sqlQuery2)().map { row2 => 

			uuid = java.util.UUID.randomUUID().toString()

		    var sqlQuery3 = "UPDATE user SET `session`=\""+uuid+"\" WHERE `id`=\""+row2[Int]("id")+"\";"
			SQL(sqlQuery3).executeUpdate()
			
		}

		Ok(uuid)

    }
  }

  def logout(session:String) = ApiAuthAction { implicit request =>
  	Auth.Logout(Auth.GetUser)
  	Ok("1")
  }

  def get_user(session:String) = ApiAuthAction { implicit request =>
  	if(Auth.IsAuthed) {
  		Ok(Ini.render(Auth.GetUser,List("session","password","activateHash")))
  	} else {
  		Ok("")
  	}
  }

  def get_categories(session:String) = ApiAuthAction { implicit request =>

	var dbCalls = new FrontendDbCalls()
	var categories = dbCalls.GetCategories()

  	if(Auth.IsAuthed) {
  		Ok(Ini.renderList(categories.toList))
  	} else {
  		Ok("")
  	}
  }

  def get_packages_from_category(session:String,category_id:Int) = Action { implicit request =>
  	Ok("")
  }

  def get_package(session:String,package_id:Int) = Action { implicit request =>
  	Ok("")
  }

}