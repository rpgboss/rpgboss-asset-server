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

	def GetDisplayName(name:String):Boolean = {

		var result:Boolean = false
		
		DB.withConnection { implicit connection =>

			val query = SQL("select id from `user` WHERE display_name=\""+name+"\";")
			query().foreach { row =>
				result = true
			}

			return result

		}
	}

	def GetEmail(email:String):Boolean = {

		var result:Boolean = false
		
		DB.withConnection { implicit connection =>

			val query = SQL("select id from `user` WHERE email=\""+email+"\";")
			query().foreach { row =>
				result = true
			}

			return result

		}
	}
	
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
				thepackage = new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"),row2[Int]("verified"),row2[String]("rejection_text"),row2[String]("version"),row2[Int]("category_id"),row2[Int]("user_id"),row2[Int]("license"))
			}
		}
		return thepackage
	}

	def GetUserById(id:Int):User = {
		var theUser:User = null
		DB.withConnection { implicit connection =>
			var sqlQuery5 = "select * from `user` WHERE `id`="+id

			SQL(sqlQuery5)().map{ row2 => 
				theUser = new User(row2[Int]("id"),row2[String]("email"),row2[String]("display_name"),row2[Int]("admin"),row2[String]("session"),row2[String]("password"),row2[Int]("activated"),row2[String]("activateHash"),row2[Option[java.util.Date]]("created_at")) 
			}

			return theUser
		}
	}

	def GetPackagesByUserId(id:Int, onlyVerified:Boolean=false):MutableList[AssetPackage] = {
		var mypackages:MutableList[AssetPackage] = MutableList()

		DB.withConnection { implicit connection =>
			var sqlQuery2 = ""
			if(onlyVerified) {
				sqlQuery2 = "SELECT * from package WHERE `user_id`="+id+" AND `verified`=1 ORDER BY `created_at` DESC;"
			}
			else {
				sqlQuery2 = "SELECT * from package WHERE `user_id`="+id+" ORDER BY `created_at` DESC;"
			}
			SQL(sqlQuery2)().foreach { row2 =>
				mypackages += new AssetPackage(row2[String]("name"), row2[String]("slug"),row2[Int]("id"),row2[String]("description"),row2[String]("url"),row2[String]("pictures"),row2[Int]("verified"),row2[String]("rejection_text"),row2[String]("version"),row2[Int]("category_id"),row2[Int]("user_id"),row2[Int]("license"))
			}
		}

		return mypackages
	}

}