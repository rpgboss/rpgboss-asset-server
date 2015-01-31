<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 13:31
 */

class Controller_Projectmanagement extends LayoutController
{

    private function string1024($str)
    {
        if (strlen($str) > 1024) {
            $str = str_split($str, 1024);
            $str = $str[0];
        }
        return $str;
    }

    public function before()
    {
        parent::before();
        $this->redirectIfNotLoggedIn();
        $this->data->leftcol = '';

        $userid = \Auth\Auth::get_user_id();
        $this->data->userid = $userid['1'];
        $this->data->userpanel = 4;

        $this->data->userprojects = Model_Project::find('all', array(
            'where' => array('user_id' => $this->data->userid)
        ));
    }


    public function action_remove_image()
    {
        $packageid = $this->param('projectid');
        $imagefile = $this->param('imagefile');

        $this->no_render();

        if(\Fuel\Core\File::exists(DOCROOT.'uploads2/'.$imagefile)) {
            \Fuel\Core\File::delete(DOCROOT.'uploads2/'.$imagefile);
            \Fuel\Core\File::delete(DOCROOT.'uploads2/'.str_replace('re_','re_th_',$imagefile));

            $package = Model_Project::find('first', array(
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

        return \Fuel\Core\Response::redirect('/projectmanagement/'.$packageid);
    }

    public function action_projects()
    {
        $data = array();
        $data['userprojects'] = $this->data->userprojects;
        $data['currentProject'] = null;

        $this->data->view = \Fuel\Core\View::forge('projectmanagement/list', $data);
    }

    public function action_project_edit()
    {
        $projectid = $this->param('projectid');

        $data = array();
        $data['categories'] = Model_Project_Category::find('all');
        $data['currentProject'] = Model_Project::find($projectid);

        $this->data->view = \Fuel\Core\View::forge('projectmanagement/edit', $data);
    }

    public function action_project_update()
    {

        $this->no_render();

        $name = \Fuel\Core\Input::post('name');
        $version = \Fuel\Core\Input::post('version');
        $url = \Fuel\Core\Input::post('download_link');
        $category_id = \Fuel\Core\Input::post('category_id');
        $description = \Fuel\Core\Input::post('text');

        if($name=='') $name = 'undefined';

        $package = Model_Project::find('first', array(
            'where' => array('id'=>$this->param('projectid'), 'user_id'=>$this->data->userid)
        ));
        $package->category_id = $category_id;
        $package->name = $name;
        $package->slug = \Fuel\Core\Inflector::friendly_title($name);
        $package->downloadlink = $url;
        $package->description = $this->string1024($description);
        $package->license = 0;
        $package->version = $version;
        $package->save();

// Custom configuration for this upload
        $config = array(
            'path' => DOCROOT.'uploads2',
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

            foreach(Upload::get_files() as $file) {

                switch($file['field']) {
                    case "image":
                        Image::load(DOCROOT.'uploads2/'.$file['saved_as'])->resize(640, null, true)->save_pa('re_');
                        Image::load(DOCROOT.'uploads2/'.$file['saved_as'])->crop_resize(180, 180)->save_pa('re_th_');
                        \Fuel\Core\File::delete(DOCROOT.'uploads2/'.$file['saved_as']);

                        $imagearray = \Fuel\Core\Format::forge($package->pictures,'json')->to_array();
                        $imagearray[] = 're_'.$file['saved_as'];

                        $package->pictures = \Fuel\Core\Format::forge($imagearray)->to_json();
                        $package->save();
                        break;
                    case "bigimage":
                        if(\Fuel\Core\File::exists(DOCROOT.'uploads2/'.$package->bigpicture)) {
                            \Fuel\Core\File::delete(DOCROOT.'uploads2/'.$package->bigpicture);
                        }
                        Image::load(DOCROOT.'uploads2/'.$file['saved_as'])->resize(1200, null, true)->save_pa('projectbig_');
                        Image::load(DOCROOT.'uploads2/'.$file['saved_as'])->crop_resize(100, 100)->save_pa('projectbig_th_');
                        \Fuel\Core\File::delete(DOCROOT.'uploads2/'.$file['saved_as']);

                        $package->bigpicture = "projectbig_" . $file['saved_as'];
                        $package->save();

                        break;
                }

            }


        }

        \Fuel\Core\Response::redirect('/projectmanagement/'.$package->id);
    }

    public function action_project_create()
    {
        $this->no_render();

        $name = \Fuel\Core\Input::post('name');

        if($name=='') return \Fuel\Core\Response::redirect('projectmanagement');

        $project = new Model_Project();
        $project->user_id = $this->data->userid;
        $project->category_id = 1;
        $project->name = $name;
        $project->slug = \Fuel\Core\Inflector::friendly_title($name);
        $project->downloadlink = "";
        $project->pictures = '';
        $project->description = "";
        $project->verified = 0;
        $project->license = 0;
        $project->rejection_text = '';
        $project->version = 0;
        $project->created_at = time();
        $project->style = '';
        $project->bigpicture = '';
        $project->save();

        $lastid = Model_Project::find('last')->id;

        return \Fuel\Core\Response::redirect('projectmanagement/'.$lastid);
    }

    public function action_delete_project()
    {
        $this->no_render();

        $package = Model_Project::find('first',array(
            'where' => array('id'=>$this->param('projectid'), 'user_id'=> $this->data->userid)
        ));

        if($package==null) return \Fuel\Core\Response::forge('');

        try {
            if($package->pictures != '') {
                $imagearray = \Fuel\Core\Format::forge($package->pictures,'json')->to_array();
                foreach($imagearray as $image) {
                    if(\Fuel\Core\File::exists(DOCROOT.'uploads2/'.$image)) {
                        \Fuel\Core\File::delete(DOCROOT.'uploads2/'.$image);
                        \Fuel\Core\File::delete(DOCROOT.'uploads2/'.str_replace('re_','re_th_',$image));
                    }
                }
            }
            if($package->bigpicture != '') {
                if(\Fuel\Core\File::exists(DOCROOT.'uploads2/'.$package->bigpicture)) {
                    \Fuel\Core\File::delete(DOCROOT.'uploads2/'.$package->bigpicture);
                    \Fuel\Core\File::delete(DOCROOT.'uploads2/'.str_replace('projectbig_','projectbig_th_',$package->bigpicture));
                }
            }
            $package->delete();
        } catch(Exception $e) {

        }

        //return \Fuel\Core\Response::forge("");
        return \Fuel\Core\Response::redirect('/projectmanagement');
    }

    public function action_update_image_order()
    {
        $packageid = $this->param('projectid');
        $imageorder = base64_decode( $this->param('imageorder') );

        $this->no_render();

        $package = Model_Project::find('first', array(
            'where' => array('id'=>$packageid, 'user_id'=>$this->data->userid)
        ));

        $package->pictures = $imageorder;
        $package->save();

        return \Fuel\Core\Response::forge('1');
    }

}