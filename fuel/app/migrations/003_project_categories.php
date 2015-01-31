<?php

namespace Fuel\Migrations;

include_once APPPATH . 'classes/model/category.php';
include_once APPPATH . 'classes/model/settings.php';
include_once APPPATH . 'classes/model/user.php';
include_once APPPATH . 'classes/model/project/category.php';

use Auth\Auth;
use Model_Category;
use Model_Settings;
use Model_User;
use Model_Project_Category;

class Project_categories
{

    function up()
    {
        \DBUtil::drop_table('project_category');
        \DBUtil::create_table('project_category', array(
            'id' => array('type' => 'int', 'constraint' => 5,'auto_increment' => true),
            'name' => array('type' => 'varchar', 'constraint' => 80),
            'slug' => array('type' => 'varchar', 'constraint' => 80),
        ), array('id'));


        \DBUtil::add_fields('project', array(
            'category_id' => array('type' => 'int', 'constraint' => 5, 'default' => 1),
            'style' => array('type' => 'text'),
        ));

        $new = new Model_Project_Category();
        $new->name = 'Adventure';
        $new->slug = 'adventure';
        $new->save();

        $new = new Model_Project_Category();
        $new->name = 'Fan fiction';
        $new->slug = 'fan-fiction';
        $new->save();

        $new = new Model_Project_Category();
        $new->name = 'Horror';
        $new->slug = 'horror';
        $new->save();

        $new = new Model_Project_Category();
        $new->name = 'Humor';
        $new->slug = 'humor';
        $new->save();

        $new = new Model_Project_Category();
        $new->name = 'Science fiction';
        $new->slug = 'science-fiction';
        $new->save();

        $new = new Model_Project_Category();
        $new->name = 'Other';
        $new->slug = 'other';
        $new->save();
    }

    function down()
    {
        \DBUtil::drop_table('project_categories');
    }
}