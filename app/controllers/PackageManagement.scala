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

	def removefile(file:String, packageid:Int) = AuthAction {
	  	// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {
			var cf = Play.current.configuration
			var path = cf.getString("upload.path").getOrElse("")
			new File(path+"/"+file).delete()

			var dbCalls = new FrontendDbCalls()
			var currentPackage:AssetPackage = dbCalls.GetPackageById(packageid)
			var newPictures = currentPackage.pictures.replaceAll(","+file,"")

			DB.withConnection { implicit connection =>

	  			var sqlQuery2 = "UPDATE package SET `pictures`=\""+newPictures+"\" WHERE `id`="+packageid+";"

	  			SQL(sqlQuery2).executeUpdate()

				Redirect("/packagemanagement/"+packageid)
			}
		}
	}

	def updateimageorder(packageid:Int, imageorder:String) = AuthAction {
		
	  	// Authed
		var isAuthed = Auth.IsAuthed
		var user = Auth.GetUser

		if(!isAuthed) {
			Redirect("/")
		} else {

			DB.withConnection { implicit connection =>

	  			var sqlQuery2 = "UPDATE package SET `pictures`=\""+imageorder+"\" WHERE `id`="+packageid+";"

	  			SQL(sqlQuery2).executeUpdate()

	  			Ok("1")
			}

		}

	}

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

		  		var currentPackage:AssetPackage = dbCalls.GetPackageById(packageid)

		  		var imagefile = ""
		  		var images = ""

		  		var timestamp: Long = System.currentTimeMillis / 1000

				request.body.file("image").map { picture =>
				    import java.io.File
				    val filename = picture.filename
				    var x = filename.split('.')
				    var ext = x(x.size-1)
				    val contentType = picture.contentType
				    var cf = Play.current.configuration
				    imagefile = category_id+"-"+slug+"-"+packageid+"-"+timestamp+"."+ext
				    var path = cf.getString("upload.path").getOrElse("")
				    picture.ref.moveTo(new File(path+"/"+imagefile))
				    images = currentPackage.pictures+","+imagefile
				}.getOrElse {

				}

		  		var sqlQuery2 = "UPDATE package SET `category_id`="+category_id+" , `pictures`=\""+images+"\",`name`='"+name+"', `version`='"+version+"' , `slug`='"+slug+"' ,`url`= '"+url+"',`description`='"+safeDescription+"' WHERE `id`="+packageid+";"

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

					var host = request.host;
					var link = "http://" + host + "/adminpanel/unapproved/lookat/" + currentPackage.id

					var mails = new Mails()
					mails.OnAllAdmins("<p>An user requests approval for his package by an admin.</p><a href=\""+link+"\"></a>","RPGBOSS Asset Server - RequestApproval")

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

		  		var imagefile = ""

				request.body.file("image").map { picture =>
				    import java.io.File
				    val filename = picture.filename
				    var x = filename.split('.')
				    var ext = x(x.size-1)
				    val contentType = picture.contentType
				    var cf = Play.current.configuration
				    imagefile = category_id+"-"+slug+"-"+rowid+"."+ext
				    var path = cf.getString("upload.path").getOrElse("")
				    picture.ref.moveTo(new File(path+"/"+imagefile))
				}.getOrElse {

				}

					var host = request.host;
					var link = "http://" + host + "/adminpanel/unapproved/lookat/" + rowid

					var mails = new Mails()
					mails.OnAllAdmins("<p>An user submitted a package, it needs to be approved.</p><a href=\""+link+"\"></a>","RPGBOSS Asset Server - Submit Package")

		  		var sqlQuery3 = "UPDATE package SET `pictures`=\""+imagefile+"\" WHERE `id`="+rowid+";"

		  		SQL(sqlQuery3).executeUpdate()

				redirectUrl = "/packagemanagement/"+rowid.toString()

			}

			Redirect(redirectUrl)
	  }
	}
}