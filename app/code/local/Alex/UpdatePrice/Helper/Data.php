<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 29.07.16
 * Time: 11:36
 */

class Alex_UpdatePrice_Helper_Data extends Mage_Core_Helper_Abstract
{
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

    public function strategyExist($strategy)
    {
        $operations = Mage::getConfig()->getNode('global/price_mass_action/operations')->asArray();
        if(array_key_exists($strategy, $operations))
        {
            return true;
        }
        return false;
    }
}