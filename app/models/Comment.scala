package models

class Comment(val id:Int, val user_id:Int, val package_id:Int,val rating:Int,val content:String, val created_at:Option[java.util.Date]) {

	var User:User = null

	def SetUser(user:User) = {
		this.User = user
	}

}