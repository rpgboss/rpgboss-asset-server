<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 08:05
 */

class Model_Comment extends Orm\Model {

    protected static $_table_name = 'comment';

    protected static $_primary_key = array('id');

    protected static $_properties = array(
        'id',
        'user_id',
        'package_id',
        'rating',
        'content',
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
        'package' => array(
            'key_from' => 'package_id',
            'model_to' => 'Model_Package',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );
}