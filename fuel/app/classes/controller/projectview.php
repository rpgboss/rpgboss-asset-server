<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 13:31
 */

class Controller_Projectview extends LayoutController
{
    public function before(){
        parent::before();


        $this->data->contentCustomClass = "projectview";
    }

    public function action_view_category()
    {

        $slug = $this->param('catslug');

        if($slug=="home") {

            $data = array();

            $result = array();
            $data['categories'] = Model_Project_Category::find('all');
            foreach($data['categories']  as $cat ) {
                $packages = Model_Project::find('all', array(
                    'where' => array('verified'=>0, 'category_id'=>$cat->id),
                    'limit' => 3,
                    'order_by' => array('created_at'=>'DESC')
                ));
                $result[$cat->slug] = $packages;
            }
            $data['packages'] = $result;

            $this->data->view = View::forge('projectview/home', $data);

        } else {
            $data = array();
            $data['category'] = Model_Project_Category::find('first',array(
                'where' => array('slug'=>$slug)
            ));

            if($data['category']==null) {
                $this->data->view = View::forge('frontend/no_category');
            } else {

                $data['packages'] = Model_Project::find('all', array(
                    'where' => array('category_id'=>$data['category']->id, 'verified'=>0),
                    'order_by' => array('created_at'=>'DESC')
                ));

                $this->data->view = View::forge('projectview/category', $data);
            }
        }

    }

    public function action_view()
    {
        $this->data->leftcol = "";

        $projectid = $this->param('projectid');

        $data = array();
        $data['project'] = Model_Project::find($projectid);

        $this->data->view = \Fuel\Core\View::forge('projectview/view', $data);
    }
}