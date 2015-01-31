<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 31.01.15
 * Time: 05:50
 */

namespace Fuel\Migrations;


class Project_bigpicture {

    function up()
    {

        \DBUtil::add_fields('project', array(
            'bigpicture' => array('type' => 'varchar', 'constraint' => 255, 'default' => ""),
        ));
    }

    function down()
    {
        \DBUtil::drop_fields('project','bigpicture');
    }

}