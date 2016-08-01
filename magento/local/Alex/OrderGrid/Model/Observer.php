<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 26.07.16
 * Time: 14:55
 */

class Alex_OrderGrid_Model_Observer {

    /**
     * Moved to Block class
     * @param Varien_Event_Observer $observer
     */
    public function coreBlockAbstractToHtmlBefore(Varien_Event_Observer $observer)
    {
        /** @var $block Mage_Core_Block_Abstract */
        $block = $observer->getEvent()->getBlock();
        if ($block->getId() == 'sales_order_grid') {

            $block->addColumnAfter(
                'shipping_description',
                array(
                    'header'   => Mage::helper('sales')->__('Shipping Method'),
                    'align'    => 'left',
                    'type'     => 'text',
                    'index'    => 'shipping_description',
                    'filter_condition_callback' => array($this, '_shippingFilter'),
                ),
                'shipping_name'
            );

            //similary you can addd new columns
            //...

            // Set the new columns order.. otherwise our column would be the last one
            $block->sortColumnsByOrder();
        }
    }

    /**
     * Moved to block class
     * @param Varien_Event_Observer $observer
     */
    public function salesOrderGridCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderGridCollection();

        $select = $collection->getSelect();
        $select->joinLeft(array('s' => 'sales_flat_order'), 's.entity_id=main_table.entity_id',array('s.shipping_description'));

    }

    public function _shippingFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue())
        {
            return $collection;
        }

        $collection->getSelect()
            ->where( "s.shipping_description like ?", "%$value%");
        return $collection;
    }
}