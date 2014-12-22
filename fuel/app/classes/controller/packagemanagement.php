<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 13:31
 */

class Controller_Packagemanagement extends LayoutController {

    public function before()
    {
        parent::before();
        $this->redirectIfNotLoggedIn();
        $this->data->leftcol = '';

        $userid = \Auth\Auth::get_user_id();
        $this->data->userid = $userid['1'];
        $this->data->userpanel = 1;

        $this->data->userpackages = Model_Package::find('all', array(
            'where' => array('user_id'=>$this->data->userid)
        ));
    }

    public function action_create_package()
    {
        $data = array();
        $data['userpackages'] = $this->data->userpackages;
        $data['categories'] = Model_Category::find("all");
        $data['currentPackage'] = null;

        $this->data->view = \Fuel\Core\View::forge('packagemanagement/package', $data);
    }

    public function action_edit_package()
    {
        $data = array();
        $data['userpackages'] = $this->data->userpackages;
        $data['categories'] = Model_Category::find("all");

        $data['currentPackage'] = Model_Package::find('first', array(
            'where' => array('id'=>$this->param('packageid'), 'user_id'=>$this->data->userid)
        ));

        if($data['currentPackage']==null) \Fuel\Core\Response::redirect('/');

        $this->data->view = \Fuel\Core\View::forge('packagemanagement/package', $data);
    }

    public function action_update_image_order()
    {
        $packageid = $this->param('packageid');
        $imageorder = base64_decode( $this->param('imageorder') );

        $this->no_render();

        $package = Model_Package::find('first', array(
            'where' => array('id'=>$packageid, 'user_id'=>$this->data->userid)
        ));
        $package->pictures = $imageorder;
        $package->save();

        return \Fuel\Core\Response::forge('1');
    }

    public function action_request_approval()
    {
        $packageid = $this->param('packageid');
        $package = Model_Package::find($this->param('packageid'));
        $package->verified = 0;
        $package->save();


        $data = array(
            'package' => $package
        );
        $this->mail->toAllAdmins(\Fuel\Core\View::forge('mail/request_approval', $data),'RPGBOSS Asset Server - Request Approval - ' . $package->name);

        return \Fuel\Core\Response::redirect('/packagemanagement/'.$packageid);
    }

    public function action_remove_image()
    {
        $packageid = $this->param('packageid');
        $imagefile = $this->param('imagefile');

        $this->no_render();

        if(\Fuel\Core\File::exists(DOCROOT.'uploads/'.$imagefile)) {
            \Fuel\Core\File::delete(DOCROOT.'uploads/'.$imagefile);
            \Fuel\Core\File::delete(DOCROOT.'uploads/'.str_replace('re_','re_th_',$imagefile));

            $package = Model_Package::find('first', array(
                'where' => array('id'=>$packageid, 'user_id'=>$this->data->userid)
            ));
            $images = \Fuel\Core\Format::forge($package->pictures, 'json')->to_array();
            $newarray= array();
            foreach($images as $image) {
                if($image!=$imagefile) {
                    $newarray[] = $image;
                }
            }
            $package->pictures = \Fuel\Core\Format::forge($newarray)->to_json();
            $package->save();
        }

        return \Fuel\Core\Response::redirect('/packagemanagement/'.$packageid);
    }

    public function action_update_package()
    {
        $name = \Fuel\Core\Input::post('name');
        $version = \Fuel\Core\Input::post('version');
        $url = \Fuel\Core\Input::post('url');
        $license = \Fuel\Core\Input::post('license');
        $category_id = \Fuel\Core\Input::post('category_id');
        $description = \Fuel\Core\Input::post('text');

        if($name=='') $name = 'undefined';

        $package = Model_Package::find('first', array(
            'where' => array('id'=>$this->param('packageid'), 'user_id'=>$this->data->userid)
        ));
        $package->category_id = $category_id;
        $package->name = $name;
        $package->slug = \Fuel\Core\Inflector::friendly_title($name);
        $package->url = $url;
        $package->description = $description;
        $package->license = $license;
        $package->version = $version;
        $package->save();

// Custom configuration for this upload
        $config = array(
            'path' => DOCROOT.'uploads',
            'randomize' => true,

            'ext_whitelist' => array('jpg', 'jpeg', 'png'),
        );

// process the uploaded files in $_FILES
        Upload::process($config);

// if there are any valid files
        if (Upload::is_valid())
        {
            // save them according to the config
            Upload::save();

            $file = Upload::get_files(0);

            Image::load(DOCROOT.'uploads/'.$file['saved_as'])->resize(640, null, true)->save_pa('re_');
            Image::load(DOCROOT.'uploads/'.$file['saved_as'])->crop_resize(100, 100)->save_pa('re_th_');
            \Fuel\Core\File::delete(DOCROOT.'uploads/'.$file['saved_as']);

            $imagearray = \Fuel\Core\Format::forge($package->pictures,'json')->to_array();
            $imagearray[] = 're_'.$file['saved_as'];

            $package->pictures = \Fuel\Core\Format::forge($imagearray)->to_json();
            $package->save();



        }

        \Fuel\Core\Response::redirect('/packagemanagement/'.$package->id);
    }

    public function action_delete_package()
    {
        $this->no_render();

        $package = Model_Package::find('first',array(
            'where' => array('id'=>$this->param('packageid'), 'user_id'=> $this->data->userid)
        ));

        if($package==null) return \Fuel\Core\Response::forge('');

        try {
            $package->delete();
        } catch(Exception $e) {

        }


        return \Fuel\Core\Response::redirect('/packagemanagement');
    }

    public function action_submit_package()
    {
        $name = \Fuel\Core\Input::post('name');
        $version = \Fuel\Core\Input::post('version');
        $url = \Fuel\Core\Input::post('url');
        $license = \Fuel\Core\Input::post('license');
        $category_id = \Fuel\Core\Input::post('category_id');
        $description = \Fuel\Core\Input::post('text');

        if($name=='') $name = 'undefined';
        $userid = \Auth\Auth::get_user_id();

        $package = new Model_Package();
        $package->category_id = $category_id;
        $package->user_id = $userid['1'];
        $package->name = $name;
        $package->slug = \Fuel\Core\Inflector::friendly_title($name);
        $package->url = $url;
        $package->pictures = '';
        $package->description = $description;
        $package->verified = 0;
        $package->license = $license;
        $package->rejection_text = '';
        $package->version = $version;
        $package->created_at = time();
        $package->save();

        $lastid = Model_Package::find('last')->id;
        $lastpackage = Model_Package::find($lastid);

// Custom configuration for this upload
        $config = array(
            'path' => DOCROOT.'uploads',
            'randomize' => true,
            'ext_whitelist' => array('jpg', 'jpeg', 'png'),
        );

// process the uploaded files in $_FILES
        Upload::process($config);

// if there are any valid files
        if (Upload::is_valid())
        {
            // save them according to the config
            Upload::save();

            $file = Upload::get_files(0);

            $imagearray = array('re_'.$file['saved_as']);

            $lastpackage->pictures = \Fuel\Core\Format::forge($imagearray)->to_json();
            $lastpackage->save();

            Image::load(DOCROOT.'uploads/'.$file['saved_as'])->resize(640, null, true)->save_pa('re_');
            Image::load(DOCROOT.'uploads/'.$file['saved_as'])->crop_resize(100, 100)->save_pa('re_th_');
            \Fuel\Core\File::delete(DOCROOT.'uploads/'.$file['saved_as']);
        }

        $data = array(
            'package' => $lastpackage
        );
        $this->mail->toAllAdmins(\Fuel\Core\View::forge('mail/new_package', $data),'RPGBOSS Asset Server - A new Package submitted - ' . $lastpackage->name);

        \Fuel\Core\Response::redirect('/packagemanagement/'.$lastid);

    }
    
}