<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 05.08.16
 * Time: 9:14
 */

class Alex_ShippingCost_Block_InvoiceTotals extends Mage_Sales_Block_Order_Invoice_Totals
{
    private $_additionalShippingCost = 0;

    protected function _initTotals()
    {
        parent::_initTotals();

        $order = $this->getOrder();
        foreach ($order->getAllItems() as $item)
        {
            $this->_additionalShippingCost += $item->getAdditionalShippingCost();
        }

        if ($this->_additionalShippingCost != 0)
        {
            $this->addTotal(new Varien_Object([
                'code'      => 'additional_shipping_cost',
                'value'     => $this->_additionalShippingCost,
                'base_value'=> $this->_additionalShippingCost,
                'label'     => $this->helper('shippingcost')->__('Shipping Cost'),
            ]));
        }

        return $this;
    }
}