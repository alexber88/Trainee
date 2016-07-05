<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 0:20
 */

class Manage
{
    public static function autoload($class)
    {
        $class = str_replace('\\', '/', $class);
        include $class . '.php';
    }
}

spl_autoload_register(['Manage', 'autoload']);