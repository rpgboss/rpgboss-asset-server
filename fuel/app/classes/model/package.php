<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 07:57
 */

class Model_Package extends \Orm\Model {

    protected static $_table_name = 'package';

    protected static $_primary_key = array('id');

    protected static $_properties = array(
        'id',
        'category_id',
        'user_id',
        'name',
        'slug',
        'url',
        'pictures',
        'description',
        'verified',
        'license',
        'rejection_text',
        'version',
        'created_at'
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
            'model_to' => 'Model_Category',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );

    protected static $_has_many = array(
        'comments' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Comment',
            'key_to' => 'package_id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )

    );

    public function GetCategoryLink()
    {
        return \Fuel\Core\Uri::create('c/'.$this->category->slug);
    }

    public function GetStoreLink()
    {
        $data = $this->_data;
        return \Fuel\Core\Uri::create('c/'.$this->category->slug.'/'.$data['id'].'/'.$data['slug']);
    }

    public function GetImages()
    {
        $result = array();
        $pictures = $this->_data['pictures'];
        if($pictures!='') {
            $result = \Fuel\Core\Format::forge($pictures,'json')->to_array();
        }
        return $result;
    }

    public function GetMainImage()
    {
        $result = '';
        $images = $this->GetImages();
        if(count($images)>=1) {
            $result = $images[0];
        }
        return $result;
    }

}