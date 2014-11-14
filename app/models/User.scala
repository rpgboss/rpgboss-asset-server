package models

class User(val id:Int, val email:String, val display_name:String, val admin:Int, val session:String, val password:String, val activated:Int, val created_at:Option[java.util.Date]) {

}