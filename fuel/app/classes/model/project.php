<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 07:57
 */

class Model_Project extends \Orm\Model {

    protected static $_table_name = 'project';

    protected static $_primary_key = array('id');

    protected static $_properties = array(
        'id',
        'category_id',
        'user_id',
        'name',
        'slug',
        'downloadlink',
        'pictures',
        'description',
        'verified',
        'license',
        'rejection_text',
        'version',
        'created_at',
        'style',
        'bigpicture'
    );

    protected static $_has_one = array(
        'user' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false,
        ),
        'category' => array(
            'key_from' => 'category_id',
            'model_to' => 'Model_Project_Category',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );

    public function GetProjectLink()
    {
        return \Fuel\Core\Uri::create('project/'.$this->id.'/'.$this->slug);
    }

    public function getImageFromThumb($picture) {
        return str_replace("re_th_","re_",$picture);
    }

    public function GetImages($thumb=false)
    {
        $result = array();
        $pictures = $this->_data['pictures'];
        if($pictures!='') {
            $result = \Fuel\Core\Format::forge($pictures,'json')->to_array();
            if($thumb) {
                for($i=0; $i < count($result); $i++) {
                    $result[$i] = str_replace("re_","re_th_",$result[$i]);
                }
            }
        }
        return $result;
    }

    public function GetMainImage($thumb=false)
    {
        $picture = $this->_data['bigpicture'];
        if($thumb) {
            $picture = str_replace("projectbig_","projectbig_th_",$picture);
        }
        return $picture;
    }

}