<?php
use Auth\Auth;

/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 08:29
 */

class LayoutController extends \Fuel\Core\Controller {

    protected $mail;

    protected $data = null;

    protected $no_render = false;

    protected $main_template = false;

    protected $title = "RPGBOSS Asset Server";

    public function no_render()
    {
        $this->no_render = true;
    }

    public function redirectIfNotLoggedIn($path='/'){
        if(!$this->data->isAuthed) {
            \Fuel\Core\Response::redirect($path);
        }
    }

    public function redirectIfNotAdmin($path='/')
    {
        if($this->data->isAuthed) {
            if(Auth::get('group')==0) {
                \Fuel\Core\Response::redirect($path);
            }
        }
    }

    public function before() {
        parent::before();
        $this->data = new stdClass();
        $this->data->title = $this->title;

        $this->mail = new Model_Utils_Mail();

        $catslug = $this->param('catslug');

        $data = array();
        if($catslug==null) {
            $data['currentCategory'] = new Model_Category();
        } else {
            $data['currentCategory'] = Model_Category::find('first',array(
                'where' => array('slug'=>$catslug)
            ));
            if($data['currentCategory']==null) {
                $data['currentCategory'] = new Model_Category();
            }
        }
        if(preg_match("#project/category/home#i",\Fuel\Core\Uri::current())) {
            $data['currentProjectCategory'] = new Model_Project_Category();
            $data['currentProjectCategory']->slug = "home";

            $data['currentCategory']->slug = "undefined";
        } else {
            $data['currentProjectCategory'] = Model_Project_Category::find('first',array(
                'where' => array('slug'=>$catslug)
            ));
            if($data['currentProjectCategory']==null) {
                $data['currentProjectCategory'] = new Model_Project_Category();
                $data['currentProjectCategory']->slug = "home";
            }
            $data['currentCategory']->slug = "undefined";
        }

        $data["categories"] =	Model_Category::find("all");
        $data["projectcategories"] = Model_Project_Category::find("all");

        $this->data->contentCustomClass = '';

        $this->data->userpanel = 0;
        $this->data->leftcol = View::forge('layout/leftcol', $data);

        $this->data->isAuthed = false;
        if (Auth::check()) {
            $this->data->isAuthed = true;
        }
    }

    public function after($response)
    {
        if($this->no_render) return $response;
        parent::after($response);

        if($this->main_template != false)
        {
            $view = $this->main_template;
        } else {
            $view = "layout/layout";
        }

        return \Response::forge(\View::forge($view,$this->data));
    }

}