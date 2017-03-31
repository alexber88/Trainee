<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 02.08.16
 * Time: 15:24
 */

class Alex_UpdatePrice_Model_Price
{
    protected $_strategy;

    public function __construct(Alex_UpdatePrice_Model_UpdateStrategy_UpdateInterface $strategy)
    {
        $this->_strategy = $strategy;
    }

    public function updatePrice($ids, $value)
    {
        $updated = 0;
        $helper = Mage::helper('alex_updateprice');
        $products = $this->getProductsCollection($ids);
        try
        {
            foreach($products as $product)
            {
                $newPrice = $this->_strategy->calculateNewPrice($product->getPrice(), $value);
                if($helper->isPositive($newPrice))
                {
                    $product->setPrice($newPrice);
                    $updated++;
                }
                else
                {
                    Mage::throwException($helper->__('Price can not be negative or null. Product ID: '.$product->getId()));
                    return false;
                }
            }
            $products->save();
            return $updated;
        }
        catch(Exception $e)
        {
            Mage::getSingleton('adminhtml/session')->addException($e, $e->getMessage());
        }

    }

    public function getProductsCollection($ids)
    {
        $products = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('price')
            ->addFieldToFilter('entity_id', ['in' => $ids]);
        return $products;
    }
}