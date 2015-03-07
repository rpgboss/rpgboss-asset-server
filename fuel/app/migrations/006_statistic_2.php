<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 31.01.15
 * Time: 05:50
 */

namespace Fuel\Migrations;


class Statistic_2 {

    function up()
    {
        \DBUtil::add_fields('statistic', array(
            'package_id' => array('type' => 'int', 'constraint' => 5)
        ));
    }

    function down()
    {
        \DBUtil::drop_fields('statistic','package_id');
    }

}