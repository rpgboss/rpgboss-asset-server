package actions;

import play.api.mvc.Results._
import play.api.mvc._
import scala.concurrent._

import models._

object ApiAuthAction extends ActionBuilder[Request] {
  def invokeBlock[A](request: Request[A], block: (Request[A]) => Future[Result]) = {
  	var sessionValue = request.getQueryString("session").getOrElse("")

	Auth.Check(sessionValue)
	
    block(request)
  }
}