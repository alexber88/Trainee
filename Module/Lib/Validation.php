<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 21.07.16
 * Time: 12:09
 */

namespace Lib;

class Validation
{
    public function validateText(&$text)
    {
        if($text == '')
        {
            return false;
        }
        $text = htmlspecialchars(trim($text));
        if($length = strlen($text) > 65535 || !$text)
        {
            return false;
        }
        return true;
    }

    public function validateVarchar(&$varchar)
    {
        if($varchar == '')
        {
            return false;
        }
        $varchar = htmlspecialchars(trim($varchar));
        if($length = strlen($varchar) > 255 || !$varchar)
        {
            return false;
        }
        return true;
    }

    public function validateStatus($status)
    {
        if(!preg_match('/^(0|1)$/', $status))
        {
            return false;
        }
        return true;
    }

    public function validatePrice(&$price)
    {
        $price = str_replace(' ', '', str_replace(',', '.', $price));
        if(!is_numeric($price))
        {
            return false;
        }
        elseif($price <= 0)
        {
            return false;
        }
        $price = (ceil($price*100))/100;
        return true;

    }

}