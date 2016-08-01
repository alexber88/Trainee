<?php

class Alex_ProductsWidget_Model_Observer
{
    public function addIsTopColumn(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
//        echo $block->getType();
//        die;
        if($block->getType() == 'adminhtml/catalog_product_grid')
        {

            $block->addColumnAfter(
                'is_top',
                array(
                    'header'   => Mage::helper('productswidget')->__('Is Top'),
                    'align'    => 'left',
                    'type'     => 'options',
                    'index'    => 'is_top',
                    'options'  => ['No', 'Yes'],
                    'renderer' => 'Alex_ProductsWidget_Block_Adminhtml_Renderer_IsTop'
                ),
                'is_top'
            );

            $block->sortColumnsByOrder();
        }
    }

    public function catalogProductCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        $observer->getCollection()->addAttributeToSelect('is_top');
    }
}