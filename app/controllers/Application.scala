package controllers

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

import scala.collection.mutable._

import models._

object Application extends Controller {

  def index = Action {
	
	DB.withConnection { implicit connection =>
		// Create an SQL query
		val selectCategories = SQL("select id,name,slug from category")
		 
		val packages:Map[String, MutableList[models.Category]] = Map()

		// Transform the resulting Stream[Row] to a List[(String,String)]
		val countries = selectCategories().map( row => 
			row[String]("name") -> row[String]("slug")
		).toList

		
		var selectedPackages:anorm.SqlQuery = null
		var packageList:Stream[Any] = Stream();

		var packagesContainer:MutableList[models.Category] = MutableList()

		
		selectCategories().foreach { row =>
			var sqlQuery = "select `id`,`name`,`slug` from package WHERE `id`="+row[Int]("id")+" AND `verified`=1 ORDER BY created_at DESC LIMIT 3"

			selectedPackages = SQL(sqlQuery)

			packagesContainer = MutableList()

			packageList = selectedPackages().map{ row2 => 

				packagesContainer += new models.Category(row2[String]("name"), row2[String]("slug"),row2[Int]("id"))
			}

			packages(row[String]("name")) = packagesContainer

		}

		Ok(views.html.store(countries, packages, new models.Category("","",0)))
    }
  }

  def forgot_pwd = Action {
    Ok(views.html.forgot_pwd(""))
  }

  def register = Action {
    Ok(views.html.register(""))
  }

  def login = Action {
    Ok(views.html.login(""))
  }

  def assetpackage(catslug:String,packageslug:String) = Action {

  	DB.withConnection { implicit connection =>

  		var dbCalls = new FrontendDbCalls()
  		var categories = dbCalls.GetCategories()

  		var sqlQuery2 = "select * from category WHERE `slug`=\""+catslug+"\";"

  		var currentCategory:models.Category = null
 			SQL(sqlQuery2)().map{ row2 => 

				currentCategory = new models.Category(row2[String]("name"), row2[String]("slug"),row2[Int]("id"))
			}

	  	var sqlQuery3 = "select * from package WHERE `slug`=\""+packageslug+"\" AND `verified`=1 AND `category_id`="+currentCategory.id

  		var currentPackage:AssetPackage = null
 			SQL(sqlQuery3)().map{ row2 => 

				currentPackage = new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"))
			}

  		Ok(views.html.assetpackage(categories,currentCategory,currentPackage))

  	}
  }

  def category(catslug: String) = Action {
  	DB.withConnection { implicit connection =>

  		var dbCalls = new FrontendDbCalls()
  		var categories = dbCalls.GetCategories()

  		var packagesContainer:MutableList[AssetPackage] = MutableList()

  		var sqlQuery2 = "select * from category WHERE `slug`=\""+catslug+"\";"

  		var currentCategory:models.Category = null
 			SQL(sqlQuery2)().map{ row2 => 

				currentCategory = new models.Category(row2[String]("name"), row2[String]("slug"),row2[Int]("id"))
			}

			var sqlQuery = "select * from package WHERE `category_id`="+currentCategory.id+" AND `verified`=1 ORDER BY created_at DESC LIMIT 3"
			var selectedPackages:anorm.SqlQuery = null
			var packageList:Stream[Any] = Stream();
			selectedPackages = SQL(sqlQuery)

			packagesContainer = MutableList()

			packageList = selectedPackages().map{ row2 => 

				packagesContainer += new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"))
			}

  		Ok(views.html.category(categories,packagesContainer,currentCategory))
  	}
  }

}