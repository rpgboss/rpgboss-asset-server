<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 13:38
 */

class Controller_Adminpanel extends \LayoutController
{

    public function before(){
        parent::before();

        $this->redirectIfNotLoggedIn();
        $this->redirectIfNotAdmin();

        $userid = \Auth\Auth::get_user_id();
        $this->data->user = Model_User::find($userid['1']);

        $this->data->leftcol = "";
    }

    public function action_unapproved()
    {
        $this->data->userpanel = 3;

        $data = array();
        $this->data->activeIndex = 1;
        $data['adminpanel_leftcol'] = \Fuel\Core\View::forge('layout/adminpanel_leftcol', $this->data);
        $data['packages'] = Model_Package::find('all',array(
            'where' => array('verified'=>0)
        ));

        $this->data->view = View::forge('adminpanel/unapproved', $data);
    }

    public function action_unapproved_view_package()
    {
        $this->data->userpanel = 3;

        $data = array();
        $this->data->activeIndex = 1;
        $data['adminpanel_leftcol'] = \Fuel\Core\View::forge('layout/adminpanel_leftcol', $this->data);
        $data['currentPackage'] = Model_Package::find($this->param('packageid'));

        $this->data->view = View::forge('adminpanel/unapproved_overview', $data);
    }

    public function action_unapproved_approve_package()
    {
        $this->no_render();

        $packageid = $this->param('packageid');
        $package = Model_Package::find($packageid);

        if($package->verified==1) return \Fuel\Core\Response::forge('');

        $package->verified = 1;
        $package->save();

        $data = array(
            'package' => $package
        );
        $this->mail->toUser($package->user, \Fuel\Core\View::forge('mail/approve_package', $data),'RPGBOSS Asset Server - Package Approval - ' . $package->name);

        return \Fuel\Core\Response::redirect('adminpanel/unapproved');
    }

    public function action_unapproved_reject_package()
    {
        $this->no_render();

        $packageid = $this->param('packageid');
        $rejection_text = \Fuel\Core\Input::post('rejection_text');

        $package = Model_Package::find($packageid);

        if($package->verified==2 or $package->verified==1) return \Fuel\Core\Response::forge('');

        $package->verified = 2;
        $package->rejection_text = $rejection_text;
        $package->save();

        $data = array(
            'package' => $package
        );
        $this->mail->toUser($package->user, \Fuel\Core\View::forge('mail/reject_package', $data),'RPGBOSS Asset Server - Package Rejection - ' . $package->name);

        return \Fuel\Core\Response::redirect('adminpanel/unapproved');
    }

}
