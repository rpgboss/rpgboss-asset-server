<?php
/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Register extends \LayoutController
{


    public function action_register()
    {
        $data = array();
        $data['error'] = \Fuel\Core\Input::get('error');

        $this->data->leftcol = '';
        $this->data->view = View::forge('register/register', $data);
    }

    public function action_check_email()
    {
        $this->no_render();

        $email = base64_decode( $this->param('name') );

        $user = Model_User::find('first',array(
           'where' => array('email'=>$email)
        ));

        return \Fuel\Core\Response::forge($user==null ? '' : '1');
    }

    public function action_check_username()
    {
        $this->no_render();

        $email = base64_decode( $this->param('name') );

        $user = Model_User::find('first',array(
            'where' => array('username'=>$email)
        ));

        return \Fuel\Core\Response::forge($user==null ? '' : '1');
    }

    public function action_confirmation_notice()
    {
        $this->data->leftcol = '';
        $this->data->view = View::forge('register/confirmation_notice');
    }

    public function action_activate_account()
    {

        $hash = $this->param('activationkey');

        $user = Model_User::find('first',array(
            'where' => array('activateHash'=>$hash)
        ));

        $this->data->valid = false;
        if($user!=null) {
            $this->data->valid = true;
            $user->activated = 1;
            $user->activateHash = uniqid(rand()*12031023012);
            $user->save();
        }

        $this->data->leftcol = '';
        $this->data->view = View::forge('register/activate_account', $this->data);
    }

    public function action_attempt()
    {
        $this->no_render();

        $email = \Fuel\Core\Input::post('email');
        $username = \Fuel\Core\Input::post('username');
        $password = \Fuel\Core\Input::post('password');
        $password_repeat = \Fuel\Core\Input::post('password_repeat');
        $displayed_name = \Fuel\Core\Input::post('displayed_name');

        if(strlen($username)<6
            or strlen($password)<6
            or strlen($password_repeat)<6
            or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return \Fuel\Core\Response::redirect('/register?error=2');
        }

        if($password==$password_repeat) {
            $user = Model_User::find('first',array(
                'where' => array('email'=>$email)
            ));
            $user2 = Model_User::find('first',array(
                'where' => array('username'=>$username)
            ));


            if($user!=null) {
                return \Fuel\Core\Response::redirect('/register?error=3');
            } else {
                if($user2!=null) {
                    return \Fuel\Core\Response::redirect('/register?error=4');
                } else {
                    Auth::create_user(
                        $username,
                        $password,
                        $email,
                        0,
                        array(
                            'displayed_name' => $displayed_name,
                        )
                    );

                    $user = Model_User::find('last');
                    $user->activated = 0;
                    $user->activateHash = sha1(time().$email.$username.$displayed_name);
                    $user->save();

                    $data = array(
                        'activationlink' => \Fuel\Core\Uri::create('activate/account/'.$user->activateHash)
                    );
                    $this->mail->toUser($user, \Fuel\Core\View::forge('mail/mail_activation', $data),'RPGBOSS Asset Server - Activation of your account');

                    return \Fuel\Core\Response::redirect('/register/confirmation_notice');
                }
            }
        } else {
            return \Fuel\Core\Response::redirect('/register?error=1');
        }


    }

}
