<?php


class Model_Utils_Mail {


    public function toUser(Model_User $user, $message, $title){

        $email = Email::forge();

        $email->from('assetserver@rpgboss.com', 'Asset Server');

        $email->to($user->email, $user->username);


        $email->subject($title);

        $email->html_body($message);

        try
        {
            $email->send();
        }
        catch(\EmailValidationFailedException $e)
        {
            // The validation failed
        }
        catch(\EmailSendingFailedException $e)
        {
            // The driver could not send the email
        }
    }


    public function toAllAdmins($message, $title)
    {
        $admins = Model_User::find('all',array(
            'where' => array('group'=>1, 'activated'=>1)
        ));

        foreach($admins as $admin) {
            $this->toUser($admin, $message, $title);
        }
    }

}