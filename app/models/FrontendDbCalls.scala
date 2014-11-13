package models

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

import scala.collection.mutable._

class FrontendDbCalls {
	
	def GetCategories():List[(String,String)] = {
		DB.withConnection { implicit connection =>
			// Create an SQL query
			val selectCategories = SQL("select id,name,slug from category")

			val countries = selectCategories().map( row => 
				row[String]("name") -> row[String]("slug")
			).toList

			return countries
		}
	}

}