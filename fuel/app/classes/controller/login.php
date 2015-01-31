<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Login extends \LayoutController
{

    public function before(){
        parent::before();

        $this->data->leftcol = "";
    }

    public function action_login()
    {
        if(\Fuel\Core\Input::get('type')!='' && $this->data->isAuthed) {
            return \Fuel\Core\Response::redirect('/#'.\Fuel\Core\Input::get('type'));
        }

        $this->data->view = View::forge('login/login');
    }

    public function action_logout()
    {
        if($this->data->isAuthed) {
            \Auth\Auth::logout();
        }
        return Response::redirect('/');
    }

    public function action_attempt()
    {
        $username = Input::post('username');
        $password = \Fuel\Core\Input::post('password');
        if (!$this->data->isAuthed && Auth::login($username, $password))
        {
            if(\Auth\Auth::get('activated')=="0") {
                \Auth\Auth::logout();
                return Response::redirect('/login?error=2');
            }
            $hash ='';
            if(\Fuel\Core\Input::get('type')) {
                $hash = '#'.\Fuel\Core\Input::get('type');
            }
            return Response::redirect('/'.$hash);
        } else {
            return Response::redirect('/login?error=1');
        }
    }

}
