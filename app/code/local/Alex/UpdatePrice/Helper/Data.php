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
        return $price + ($price * $value)/100;
    }

    public function subtractPercent($price, $value)
    {
        return $price - ($price * $value)/100;
    }

    public function multiplication($price, $value)
    {
        return $price * $value;
    }

    public function checkValue($value)
    {
        if(!$value)
        {
            Mage::getSingleton('adminhtml/session')->addError($this->__("Value can't be empty or null"));
            return false;
        }
        elseif(!filter_var($value, FILTER_VALIDATE_FLOAT))
        {
            Mage::getSingleton('adminhtml/session')->addError($this->__("Value should be only numeric or double"));
            return false;
        }
        elseif($value < 0)
        {
            Mage::getSingleton('adminhtml/session')->addError($this->__("Value should be only positive"));
            return false;
        }
        
        return true;
    }

    public function isPositive($price)
    {
        return $price > 0;
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