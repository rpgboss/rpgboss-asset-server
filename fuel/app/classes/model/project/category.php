<?php
/**
 * Created by PhpStorm.
 * User: hendrikweiler
 * Date: 20.12.14
 * Time: 08:04
 */

class Model_Project_Category extends Orm\Model {

    protected static $_table_name = 'project_category';

    protected static $_primary_key = array('id');

    protected static $_properties = array(
        'id',
        'name',
        'slug'
    );


}