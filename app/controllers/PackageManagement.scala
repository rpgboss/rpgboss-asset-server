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

object PackageManagement extends Controller {

	val packageForm = Form(
	  tuple(
	    "name" -> text,
	    "url" -> text,
	    "text" -> text,
	    "category_id" -> number,
	    "version" -> text
	  )
	)

	def editpackage(packageid:Int) = AuthAction(parse.multipartFormData) { implicit request =>
	  	// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			DB.withConnection { implicit connection =>

				val (name, url, text, category_id, version) = packageForm.bindFromRequest.get

				var dbCalls = new FrontendDbCalls()

				var cuttedText = text.toString();
				if(cuttedText.size>512) {
					cuttedText = cuttedText.substring(0, 512)
				}

	  		var slug = Util.slugify(name)
	  		var safeDescription = Jsoup.clean(cuttedText, Whitelist.basic());

	  		var imagefile = ""

			request.body.file("image").map { picture =>
			    import java.io.File
			    val filename = picture.filename
			    var x = filename.split('.')
			    var ext = x(x.size-1)
			    val contentType = picture.contentType
			    var cf = Play.current.configuration
			    imagefile = category_id+"-"+slug+"-"+packageid+"."+ext
			    var path = cf.getString("upload.path").getOrElse("")
			    picture.ref.moveTo(new File(path+"/"+imagefile))
			}.getOrElse {

			}

	  		var sqlQuery2 = "UPDATE package SET `category_id`="+category_id+" , `pictures`=\""+imagefile+"\",`name`='"+name+"', `version`='"+version+"' , `slug`='"+slug+"' ,`url`= '"+url+"',`description`='"+safeDescription+"' WHERE `id`="+packageid+";"

	  		SQL(sqlQuery2).executeUpdate()

				Redirect("/packagemanagement/"+packageid)

			}
		}
	}

	def requestapproval(packageid:Int) =  AuthAction { implicit request =>
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
				if(currentPackage.verified==2) {

					var sqlQuery2 = "UPDATE package SET `verified`=0 WHERE `id`=\""+packageid+"\";"
					SQL(sqlQuery2).executeUpdate()

				}

			}

			Redirect("/packagemanagement/"+packageid)
		}
	}

	def editindex(packageid:Int) = AuthAction { implicit request =>
	  	// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			DB.withConnection { implicit connection =>

				var dbCalls = new FrontendDbCalls()
	  		var categories = dbCalls.GetCategories()

	  		var currentPackage = dbCalls.GetPackageById(packageid)
	  		var mypackages = dbCalls.GetPackagesByUserId(user.id)

				Ok(views.html.pm_index(isAuthed, user, mypackages,categories,currentPackage))

			}
		}
	}

	def index = AuthAction { implicit request =>
	  // Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			DB.withConnection { implicit connection =>

				var dbCalls = new FrontendDbCalls()
	  		var categories = dbCalls.GetCategories()

				var mypackages = dbCalls.GetPackagesByUserId(user.id)
				var currentPackage = dbCalls.GetPackageById(0)

				Ok(views.html.pm_index(isAuthed, user, mypackages,categories,currentPackage))

			}
		}
	}

  def submit = AuthAction(parse.multipartFormData) { implicit request =>
	  	// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
		/////////////////

			var redirectUrl = "/packagemanagement"

			DB.withConnection { implicit connection =>

				val (name, url, text, category_id, version) = packageForm.bindFromRequest.get

				var slug = Util.slugify(name)

				var cuttedText = text.toString();
				if(cuttedText.size>512) {
					cuttedText = cuttedText.substring(0, 512)
				}

				var safeDescription = Jsoup.clean(cuttedText, Whitelist.basic());

				var sqlQuery2 = "INSERT INTO package values(NULL, "+category_id+", "+user.id+", '"+name+"','"+slug+"', '"+url+"','','"+safeDescription+"',0,'','"+version+"',NULL);"
				
				var rowid:Long = 0

				SQL(sqlQuery2).executeInsert().map(id => rowid = id)
				
				/*
				request.body.file("image").map { picture =>
				    import java.io.File
				    val filename = picture.filename
				    val contentType = picture.contentType
						var dir = new File(routes.Assets.at(s"uploads/package/"));
						dir.mkdir();
				    picture.ref.moveTo(new File(routes.Assets.at(s"uploads/package/")))
				}.getOrElse {

				}
				*/

				redirectUrl = "/packagemanagement/"+rowid.toString()

			}

			Redirect(redirectUrl)
	  }
	}
}