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
  		Ok(Ini.render(Auth.GetUser,List("session","password","activateHash","admin")))
  	} else {
  		Ok("")
  	}
  }

  def get_user_by_id(session:String, user_id:Int) = ApiAuthAction { implicit request =>

    var result = ""

    if(Auth.IsAuthed) {

      var dbCalls = new FrontendDbCalls()
      var user = dbCalls.GetUserById(user_id)

      if(user!=null) {
        result = Ini.render(user, List("session","password","activateHash","email","admin"))
      }

    }

    Ok(result)
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

  def get_packages_from_category(session:String,category_id:Int) = ApiAuthAction { implicit request =>

    var result = ""

    if(Auth.IsAuthed) {

      DB.withConnection { implicit connection =>

        var packagesContainer:MutableList[AssetPackage] = MutableList()

        var sqlQuery2 = "select * from category WHERE `id`=\""+category_id+"\";"

        var currentCategory:models.Category = null
        SQL(sqlQuery2)().map{ row2 => 

          currentCategory = new models.Category(row2[String]("name"), row2[String]("slug"),row2[Int]("id"))
        }

        if(currentCategory!=null) {

          var sqlQuery = "select * from package WHERE `category_id`="+currentCategory.id+" AND `verified`=1 ORDER BY created_at DESC"
          var selectedPackages:anorm.SqlQuery = null
          var packageList:Stream[Any] = Stream();
          selectedPackages = SQL(sqlQuery)

          packagesContainer = MutableList()

          packageList = selectedPackages().map{ row2 => 

            packagesContainer += new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"),row2[Int]("verified"),row2[String]("rejection_text"),row2[String]("version"),row2[Int]("category_id"),row2[Int]("user_id"))
          }

          result = Ini.renderList(packagesContainer.toList, List("rejection_text"))

        }

      }

    }

  	Ok(result)
  }

  def get_package(session:String,package_id:Int) = ApiAuthAction { implicit request =>

    var result = ""

    if(Auth.IsAuthed) {

      DB.withConnection { implicit connection =>

        var dbCalls = new FrontendDbCalls()

        var sqlQuery3 = "select * from package WHERE `id`=\""+package_id+"\" AND `verified`=1"

        var currentPackage:AssetPackage = null
        SQL(sqlQuery3)().map{ row2 => 

          currentPackage = new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"),row2[Int]("verified"),row2[String]("rejection_text"),row2[String]("version"),row2[Int]("category_id"),row2[Int]("user_id"))
        }

        if(currentPackage!=null) {

          var sqlQuery5 = "select * from `comment` WHERE `package_id`="+currentPackage.id+" ORDER BY created_at DESC"

          var allComments = SQL(sqlQuery5)

          var packageComments:MutableList[Comment] = MutableList()

          allComments.apply().foreach{ row2 => 

            var theComment = new models.Comment(row2[Int]("id"), row2[Int]("user_id"),row2[Int]("package_id"),row2[Int]("rating"),row2[String]("content"),row2[Option[java.util.Date]]("created_at"))
            theComment.SetUser(dbCalls.GetUserById(row2[Int]("user_id")))

            packageComments += theComment
          }

          result += "package=" + Ini.b64Encode( Ini.render(currentPackage, List("rejection_text")) ) + "\n"
          result += "comments=" + Ini.b64Encode( Ini.renderList(packageComments.toList,List(
            // Comments
            "user_id","id","package_id",
            // User
            "session","password","activateHash","email","admin")
          ) ) + "\n"

        }

      }

    }

    Ok(result)
  }

}