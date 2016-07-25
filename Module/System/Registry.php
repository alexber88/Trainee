<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 25.07.16
 * Time: 14:11
 */

namespace System;

class Registry
{
    private static $_properties = [];

    public static function setProperty($name, $property)
    {
        self::$_properties[$name] = $property;
    }

    public static function getProperty($name)
    {
        if(isset(self::$_properties[$name]))
        {
            return self::$_properties[$name];
        }
        else
        {
            return null;
        }
    }

    public static function deleteProperty($name)
    {
        if(isset(self::$_properties[$name]))
        {
            unset(self::$_properties[$name]);
        }
    }
}