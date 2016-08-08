<?php

class Alex_ProductsWidget_Model_Observer
{
    public function addIsTopColumn(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if($block->getType() == 'adminhtml/catalog_product_grid')
        {

            $block->addColumnAfter(
                'is_top',
                [
                    'header'   => Mage::helper('productswidget')->__('Is Top'),
                    'align'    => 'left',
                    'type'     => 'options',
                    'index'    => 'is_top',
                    'width'    => '50px',
                    'options'  => [0=>'No', 1=>'Yes'],
                ],
                'status'
            );

            $block->sortColumnsByOrder();
        }
    }

    public function catalogProductCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        $observer->getCollection()->addAttributeToSelect('is_top');
    }
    
}