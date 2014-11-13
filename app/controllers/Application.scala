package controllers

import anorm._ 
import anorm.SqlParser._
import play.api._
import play.api.mvc._
import play.api.db._
import play.api.Play.current

import java.util.Collections

class SlugName(name:String, slug:String) {}

object Application extends Controller {

  def index = Action {
	
	DB.withConnection { implicit connection =>
		// Create an SQL query
		val selectCategories = SQL("select id,name,slug from category")
		 
		val packages:Set[Any] = Set()

		// Transform the resulting Stream[Row] to a List[(String,String)]
		val countries = selectCategories().map( row => 
			row[String]("name") -> row[String]("slug")
		).toList

		
		var selectedPackages:anorm.SqlQuery = null
		var packageList:List[Any] = List();

		/*
		selectCategories().foreach { row =>
			var sqlQuery = "select `name`,`slug` from package WHERE `id`="+row[Int]("id")+" ORDER BY created_at DESC LIMIT 3"

			selectedPackages = SQL(sqlQuery)

			packageList = selectedPackages().map{ row2 => 

				Collections.addAll(packages,SlugName(row2[String]("name"), row2[String]("slug")))
			}

		}
		*/
		
		var counter:Iterator[Int] = Stream.iterate(0)(_ + 1).iterator;

		Ok(views.html.store(countries, packages,counter))
    }
  }

  def login = Action {
    Ok(views.html.login(""))
  }

}