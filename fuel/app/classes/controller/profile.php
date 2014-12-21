<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 13:38
 */

class Controller_Profile extends \LayoutController
{

    public function before(){
        parent::before();

        $this->redirectIfNotLoggedIn();
        $this->data->leftcol = "";
    }

    public function action_show()
    {
        $this->data->userpanel = 2;
        $this->data->view = View::forge('profile/profile');
    }

    public function action_save()
    {
        $oldpwd = \Fuel\Core\Input::post('old_password');
        $newpwd = \Fuel\Core\Input::post('new_password');
        if(strlen($newpwd)>=6) {
            if(Auth::change_password($oldpwd,$newpwd)) {
                \Fuel\Core\Response::redirect('/profile?message=1');
            } else {
                \Fuel\Core\Response::redirect('/profile?message=2');
            }
        } else {
            \Fuel\Core\Response::redirect('/profile?message=3');
        }
    }

}
