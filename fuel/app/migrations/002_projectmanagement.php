<?php

namespace Fuel\Migrations;

include_once APPPATH . 'classes/model/category.php';
include_once APPPATH . 'classes/model/settings.php';
include_once APPPATH . 'classes/model/user.php';

use Auth\Auth;
use Model_Category;
use Model_Settings;
use Model_User;

class Projectmanagement
{

    function up()
    {
        \DBUtil::create_table('project', array(
            'id' => array('type' => 'int', 'constraint' => 5,'auto_increment' => true),
            'user_id' => array('type' => 'int', 'constraint' => 5),
            'name' => array('type' => 'varchar', 'constraint' => 80),
            'slug' => array('type' => 'varchar', 'constraint' => 80),
            'downloadlink' => array('type' => 'varchar', 'constraint' => 255),
            'pictures' => array('type' => 'text'),
            'description' => array('type' => 'text'),
            'license' => array('type' => 'int', 'constraint' => 1),
            'verified' => array('type' => 'int', 'constraint' => 1),
            'rejection_text' => array('type' => 'varchar', 'constraint' => 255),
            'version' => array('type' => 'varchar', 'constraint' => 30),
            'created_at' =>  array('type' => 'varchar', 'constraint' => 25),
        ), array('id'));


    }

    function down()
    {
        \DBUtil::drop_table('project');
    }
}