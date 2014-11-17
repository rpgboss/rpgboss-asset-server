package actions;

import play.api.mvc.Results._
import play.api.mvc._
import scala.concurrent._

import models._

object AuthAction extends ActionBuilder[Request] {
  def invokeBlock[A](request: Request[A], block: (Request[A]) => Future[SimpleResult]) = {
  	var sessionCookie = request.cookies.get("session")
  	var sessionValue = ""
  	if(sessionCookie==None) {
  		sessionValue = ""
	} else {
		sessionValue = sessionCookie.get.value
	}

	Auth.Check(sessionValue)

    block(request)
  }
}