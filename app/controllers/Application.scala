package controllers

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

object Application extends Controller {

  def index = Action {
	
	DB.withConnection { implicit connection =>
		// Create an SQL query
		val selectCategories = SQL("select id,name,slug from category")
		 
		val packages = Set()

		// Transform the resulting Stream[Row] to a List[(String,String)]
		val countries = selectCategories().map( row => 
			row[String]("name") -> row[String]("slug")
		).toList

		/*
		var selectedPackages:anorm.SqlQuery = null
		var packageList:List[(String,String)] = null;

		selectCategories().foreach( row =>
			selectedPackages = SQL("select name,slug from package WHERE `id`="+row[Int]("id")+" ORDER BY created_at DESC LIMIT 3")

			packageList = selectedPackages().map( row2 => 
				row2[String]("name") -> row2[String]("slug")
			).toList
			packages ++= packageList
		)
		*/

		Ok(views.html.store(countries))
    }
  }

  def login = Action {
    Ok(views.html.login(""))
  }

}