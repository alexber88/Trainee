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
                [
                    'header'   => Mage::helper('productswidget')->__('Is Top'),
                    'align'    => 'left',
                    'type'     => 'options',
                    'index'    => 'is_top',
                    'width'    => '50px',
                    'options'  => [0=>'No', 1=>'Yes'],
                    'renderer' => 'Alex_ProductsWidget_Block_Adminhtml_Renderer_IsTop',
                    'filter_condition_callback' => [$this, '_isTopFilter'],
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

    public function _isTopFilter($collection, $column)
    {
        if ($value = $column->getFilter()->getValue() === false)
        {
            return $collection;
        }

//        var_dump($value);
//        die;
        if(!$value)
        {
            $value = 0;
        }

        $collection->addAttributeToSelect('is_top')->addFieldToFilter( 'is_top', ['eq' => $value])->getSelect();
//        print_r((string)$collection->getSelect());
//        die;
        return $collection;
    }
}