<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 04.08.16
 * Time: 17:24
 */

class Alex_ShippingCost_Block_Adminhtml_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals
{
    private $_additionalShippingCost = 0;

    protected function _initTotals()
    {
        parent::_initTotals();
        $order = $this->getOrder();
        foreach ($order->getAllItems() as $item)
        {
            $this->_additionalShippingCost += $item->getAdditionalShippingCost() * $item->getQtyOrdered();
        }

        if ($this->_additionalShippingCost != 0)
        {
            $this->addTotal(new Varien_Object([
                'code'      => 'additional_shipping_cost',
                'value'     => $this->_additionalShippingCost,
                'base_value'=> $this->_additionalShippingCost,
                'label'     => $this->helper('shippingcost')->__('Additional Shipping Cost'),
            ]));
        }

        return $this;
    }

}