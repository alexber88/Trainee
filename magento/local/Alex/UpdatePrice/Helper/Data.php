<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 29.07.16
 * Time: 11:36
 */

class Alex_UpdatePrice_Helper_Data extends Mage_Core_Helper_Abstract
{
    private $_methods = ['addition', 'subtraction', 'addPercent', 'subtractPercent', 'multiplication'];

    public function addition($price, $value)
    {
        return $price + $value;
    }

    public function subtraction($price, $value)
    {
        return $price - $value;
    }

    public function addPercent($price, $value)
    {
        return $price + round(($price * $value)/100, 2);
    }

    public function subtractPercent($price, $value)
    {
        return $price - round(($price * $value)/100, 2);
    }

    public function multiplication($price, $value)
    {
        return $price * $value;
    }

    public function checkValue($value)
    {
        if(!$value)
        {
            return false;
        }
        elseif(!filter_var($value, FILTER_VALIDATE_FLOAT))
        {
            return false;
        }
        elseif($value < 0.01)
        {
            return false;
        }
        
        return true;
    }

    public function isPositive($price)
    {
        if($price <= 0)
        {
            return false;
        }
        return true;
    }

    public function methodExist($method)
    {
        if(is_callable([$this, $method]) && in_array($method, $this->_methods))
        {
            return true;
        }
        return false;
    }
}