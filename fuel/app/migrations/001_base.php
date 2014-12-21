<?php

namespace Fuel\Migrations;

include_once APPPATH . 'classes/model/category.php';
include_once APPPATH . 'classes/model/settings.php';
include_once APPPATH . 'classes/model/user.php';

use Auth\Auth;
use Model_Category;
use Model_Settings;
use Model_User;

class Base
{

    function up()
    {
        \DBUtil::create_table('user', array(
            'id' => array('type' => 'int', 'constraint' => 5,'auto_increment' => true),
            'email' => array('type' => 'varchar', 'constraint' => 100),
            'username' => array('type' => 'varchar', 'constraint' => 100),
            'password' => array('type' => 'varchar', 'constraint' => 100),
            'login_hash' => array('type' => 'varchar', 'constraint' => 255),
            'last_login' => array('type' => 'varchar', 'constraint' => 25),
            'group' => array('type' => 'int', 'constraint' => 1),
            'profile_fields' => array('type' => 'text'),
            'admin' => array('type' => 'varchar', 'constraint' => 100),
            'activated' => array('type' => 'varchar', 'constraint' => 100),
            'activateHash' => array('type' => 'varchar', 'constraint' => 100),
            'created_at' => array('type' => 'varchar', 'constraint' => 25),
            'updated_at' => array('type' => 'varchar', 'constraint' => 25),
        ), array('id'));

        Auth::create_user(
            'info',
            'test',
            'info@rpgboss.com',
            1,
            array(
                'displayed_name' => 'The Admin',
            )
        );

        $last = Model_User::find('last');
        $last->activated = 1;
        $last->save();

        \DBUtil::create_table('category', array(
            'id' => array('type' => 'int', 'constraint' => 5,'auto_increment' => true),
            'name' => array('type' => 'varchar', 'constraint' => 80),
            'slug' => array('type' => 'varchar', 'constraint' => 80),
        ), array('id'));

        $new = new Model_Category();
        $new->name = 'Animation';
        $new->slug = 'animation';
        $new->save();

        $new2 = new Model_Category();
        $new2->name = 'Battle Background';
        $new2->slug = 'battle-background';
        $new2->save();

        $new3 = new Model_Category();
        $new3->name = 'Battler';
        $new3->slug = 'battler';
        $new3->save();

        $new4 = new Model_Category();
        $new4->name = 'Music';
        $new4->slug = 'music';
        $new4->save();

        $new7 = new Model_Category();
        $new7->name = 'Picture';
        $new7->slug = 'picture';
        $new7->save();

        $new8 = new Model_Category();
        $new8->name = 'Script';
        $new8->slug = 'script';
        $new8->save();

        $new9 = new Model_Category();
        $new9->name = 'Sound';
        $new9->slug = 'sound';
        $new9->save();

        $new10 = new Model_Category();
        $new10->name = 'Spriteset';
        $new10->slug = 'spriteset';
        $new10->save();

        $new11 = new Model_Category();
        $new11->name = 'Tileset';
        $new11->slug = 'tileset';
        $new11->save();

        $new12 = new Model_Category();
        $new12->name = 'Windowskin';
        $new12->slug = 'windowskin';
        $new12->save();

        $new13 = new Model_Category();
        $new13->name = 'Project';
        $new13->slug = 'project';
        $new13->save();

        $new16 = new Model_Category();
        $new16->name = 'Titlescreen';
        $new16->slug = 'titlescreen';
        $new16->save();

        \DBUtil::create_table('settings', array(
            'id' => array('type' => 'int', 'constraint' => 5,'auto_increment' => true),
            'key' => array('type' => 'varchar', 'constraint' => 80),
            'value' => array('type' => 'varchar', 'constraint' => 255),
        ), array('id'));

        $setting = new Model_Settings();
        $setting->key = 'LoggedInToDownload';
        $setting->value = '1';
        $setting->save();

        $setting2 = new Model_Settings();
        $setting2->key = 'PackagesNeedToBeVerifiedByAdmin';
        $setting2->value = '1';
        $setting2->save();

        \DBUtil::create_table('package', array(
            'id' => array('type' => 'int', 'constraint' => 5,'auto_increment' => true),
            'category_id' => array('type' => 'int', 'constraint' => 5),
            'user_id' => array('type' => 'int', 'constraint' => 5),
            'name' => array('type' => 'varchar', 'constraint' => 80),
            'slug' => array('type' => 'varchar', 'constraint' => 80),
            'url' => array('type' => 'varchar', 'constraint' => 255),
            'pictures' => array('type' => 'text'),
            'description' => array('type' => 'text'),
            'license' => array('type' => 'int', 'constraint' => 1),
            'verified' => array('type' => 'int', 'constraint' => 1),
            'rejection_text' => array('type' => 'varchar', 'constraint' => 255),
            'version' => array('type' => 'varchar', 'constraint' => 30),
            'created_at' =>  array('type' => 'varchar', 'constraint' => 25),
        ), array('id'));

        \DBUtil::create_table('comment', array(
            'id' => array('type' => 'int', 'constraint' => 5,'auto_increment' => true),
            'user_id' => array('type' => 'int', 'constraint' => 5),
            'package_id' => array('type' => 'int', 'constraint' => 5),
            'rating' => array('type' => 'int', 'constraint' => 1),
            'content' => array('type' => 'text'),
            'created_at' =>  array('type' => 'varchar', 'constraint' => 25),
        ), array('id'));
    }

    function down()
    {
        \DBUtil::drop_table('user');
        \DBUtil::drop_table('category');
        \DBUtil::drop_table('settings');
        \DBUtil::drop_table('package');
        \DBUtil::drop_table('comment');
    }
}