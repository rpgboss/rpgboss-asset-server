<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 08:02
 */

class Model_User extends Orm\Model {

    protected static $_table_name = 'user';

    protected static $_primary_key = array('id');

    protected static $_properties = array(
        'id',
        'email',
        'username',
        'password',
        'login_hash',
        'group',
        'profile_fields',
        'admin',
        'activated',
        'activateHash',
        'created_at'
    );

    public function profile()
    {
        $result = json_encode(array());
        if($this->_data['profile_fields']!='') {
            $result =  \Fuel\Core\Format::forge($this->_data['profile_fields'], 'serialize')->to_json();
        }
        return json_decode($result,false);
    }
}