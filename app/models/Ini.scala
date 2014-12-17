package models

import scala.collection.mutable._
import org.apache.commons.codec.binary.Base64

object Ini {
	def render(o: Any, ignoreFields:List[String]=List("")): String = {

	  var result = ""

	  var fieldsAsPairs = for (field <- o.getClass.getDeclaredFields) yield {
	    field.setAccessible(true)

	    var ignore = false
	    ignoreFields.foreach { fieldname =>
	    	if(fieldname==field.getName) {
	    		ignore = true
	    	}
	    }
	    if(!ignore) {
	    	var value = field.get(o)
	    	var matches = "models".r.findAllIn(value.getClass.getName)
	    	if(matches.size != 0) {
	    		value = b64Encode( render(value, ignoreFields) )
	    	}
	    	var matches2 = "Some".r.findAllIn(value.getClass.getName)
	    	if(matches2.size != 0) {
	    		println(value)
	    		value = value.asInstanceOf[Option[String]].getOrElse("")
	    	}
	    	
	    	result += field.getName + "=" + value + "\n"
	    }
	  }

	  return result
	}

	def b64Encode(data: String):String = {
		return Base64.encodeBase64String(data.getBytes())
	}

	def renderList(list:List[Any], ignoreFields:List[String]=List("")):String = {
		var result = ""
		var counter = 0
		list.foreach { obj:Any =>
			var ini = render(obj,ignoreFields)
			var b64data = b64Encode( ini )
			result += counter + "=" + b64data + "\n"
			counter+=1
		}

		return result
	}
}