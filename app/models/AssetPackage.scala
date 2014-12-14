package models
import scala.collection.mutable._ 

class AssetPackage(val name:String,val slug:String, val id:Int, val description:String, val url:String, val pictures:String, val verified:Int, val rejection_text:String, val version:String, val category_id:Int, val user_id:Int) {

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