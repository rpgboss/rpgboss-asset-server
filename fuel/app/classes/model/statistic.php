<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 08:02
 */

class Model_Statistic extends Orm\Model {

    protected static $_table_name = 'statistic';

    protected static $_primary_key = array('id');

    protected static $_properties = array(
        'id',
        'user_id',
        'package_id',
        'type',
        'created_at'
    );

}