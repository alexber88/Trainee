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
        $helper = Mage::helper('alex_updateprice');
        try
        {
            $products = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('price')
                ->addFieldToFilter('entity_id', ['in' => $ids]);
            foreach($products as $product)
            {
                $newPrice = call_user_func([$helper, $method], $product->getPrice(), $value);
                if($helper->isPositive($newPrice))
                {
                    $product->setPrice($newPrice);
                    $updated++;
                }
                else
                {
                    Mage::getSingleton('adminhtml/session')->addError($helper->__('Price can not be negative or null. Product ID: '.$product->getId()));
                    return false;
                }
            }
            $products->save();
            return $updated;
        }
        catch(Exception $e)
        {
            Mage::getSingleton('adminhtml/session')->addException($e, $helper->__('Error during product updating'));
        }

    }
}