<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 02.08.16
 * Time: 15:24
 */

class Alex_UpdatePrice_Model_Price extends Mage_Core_Model_Abstract
{
    public function updatePrice($ids, $method, $value)
    {
        $updated = 0;
        $products = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('price')
            ->addFieldToFilter('entity_id', ['in' => $ids]);
        foreach($products as $product)
        {
            $newPrice = call_user_func([Mage::helper('alex_updateprice'), $method], $product->getPrice(), $value);
            if(Mage::helper('alex_updateprice')->isPositive($newPrice))
            {
                $product->setPrice($newPrice);
                $updated++;
            }
            else
            {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('alex_updateprice')->__('Price can not be negative or null. Product ID: '.$product->getId()));
                return false;
            }
        }
        $products->save();
        return $updated;
    }
}