package models

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

import scala.collection.mutable._

import models._

class FrontendDbCalls {
	
	def GetCategories():MutableList[Category] = {
		DB.withConnection { implicit connection =>
			// Create an SQL query
			val selectCategories = SQL("select id,name,slug from category")

			val countries:MutableList[Category] = MutableList()

			selectCategories().foreach( row => 
				countries += new Category(row[String]("name"),row[String]("slug"),row[Int]("id"))
			)

			return countries
		}
	}

	def GetPackageById(id:Int):AssetPackage = {

		var thepackage:AssetPackage = null

		DB.withConnection { implicit connection =>
			val query = SQL("select * from `package` WHERE id="+id+";")
			
			query().map { row2 =>
				thepackage = new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"),row2[Int]("verified"))
			}
		}
		return thepackage
	}

	def GetPackagesByUserId(id:Int):MutableList[AssetPackage] = {
		var mypackages:MutableList[AssetPackage] = MutableList()

		DB.withConnection { implicit connection =>
			var sqlQuery2 = "SELECT * from package WHERE `user_id`="+id+" ORDER BY `created_at` DESC;"
			SQL(sqlQuery2)().foreach { row2 =>
				mypackages += new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"),row2[Int]("verified"))
			}
		}

		return mypackages
	}

}