<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 31.01.15
 * Time: 05:50
 */

namespace Fuel\Migrations;


class Statistic {

    function up()
    {
        \DBUtil::create_table('statistic', array(
            'id' => array('type' => 'int', 'constraint' => 5,'auto_increment' => true),
            'user_id' => array('type' => 'int', 'constraint' => 5),
            'type' => array('type' => 'varchar', 'constraint' => 80),
            'created_at' =>  array('type' => 'varchar', 'constraint' => 25),
        ), array('id'));
    }

    function down()
    {
        \DBUtil::drop_table("statistic");
    }

}