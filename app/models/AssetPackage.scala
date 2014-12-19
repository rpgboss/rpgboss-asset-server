package models
import scala.collection.mutable._ 
import play.api.db._
import anorm._ 
import anorm.SqlParser._
import play.api.Play.current

class AssetPackage(val name:String,val slug:String, val id:Int, val description:String, val url:String, val pictures:String, val verified:Int, val rejection_text:String, val version:String, val category_id:Int, val user_id:Int, val license:Int) {

	def getCategory:Category = {

			var category:Category = null
			DB.withConnection { implicit connection =>
				var sqlQuery5 = "select * from `category` WHERE `id`="+category_id

				SQL(sqlQuery5)().map{ row2 => 
					category = new models.Category(row2[String]("name"), row2[String]("slug"),row2[Int]("id"))
				}

				return category
			}

	}

	def getMainImage():String = {
		var images = this.pictures.split(",")

		if(images.size==1){
			return images(0)
		}

		return images(1)
	}

	def getAllImages():MutableList[String] = {
		var imagesSplit = this.pictures.split(",")
		var images:MutableList[String] = new MutableList[String]()
		imagesSplit.map { file =>
			if(file!="") {
				images += file
			}
		}

		return images
	}

}