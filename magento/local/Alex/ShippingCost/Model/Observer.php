<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 04.08.16
 * Time: 10:12
 */

class Alex_ShippingCost_Model_Observer
{
    public function salesQuoteItemSetAdditionalShippingCost($observer)
    {
        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();
        if($quoteItem->getParentItemId() == null)
        {
            $additionalShippingCost = $product->getAdditionalShippingCost() * $quoteItem->getQty();
            $quoteItem->setAdditionalShippingCost($additionalShippingCost);
        }
    }

//    public function coreBlockAbstractToHtmlBefore($observer)
//    {
//        $block = $observer->getEvent()->getBlock();
//        if(get_class($block) == 'Mage_Sales_Block_Order_Totals'
//            && $block->getRequest()->getControllerName() == 'order')
//        {
//            $additionalShippingCost = 0;
//            $params = Mage::app()->getRequest()->getParams();
//            $orderId = $params['order_id'];
//            $values = Mage::getModel('sales/order_item')
//                ->getCollection()
//                ->addAttributeToSelect('additional_shipping_cost')
//                ->addFieldToFilter('order_id', ['eq'=>$orderId])
//                ->getColumnValues('additional_shipping_cost');
//            $additionalShippingCost = array_sum($values);
//
//            if ($additionalShippingCost != 0)
//            {
//                $block->addTotal(new Varien_Object([
//                    'code'      => 'alex_shippingcost',
//                    'value'     => $additionalShippingCost,
//                    'base_value'=> $additionalShippingCost,
//                    'label'     => Mage::helper('shippingcost')->__('Shipping Cost'),
//                ]));
//            }
//        }
//    }
}

