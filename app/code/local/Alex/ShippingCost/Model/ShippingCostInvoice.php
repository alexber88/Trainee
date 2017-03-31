<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 04.08.16
 * Time: 17:07
 */

class Alex_ShippingCost_Model_ShippingCostInvoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    private $_additionalShippingCost = 0;
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();
        foreach ($order->getAllItems() as $item)
        {
            $this->_additionalShippingCost += $item->getAdditionalShippingCost() * $item->getQtyInvoiced();
        }
        if ($this->_additionalShippingCost != 0)
        {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $this->_additionalShippingCost);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $this->_additionalShippingCost);
        }

        return $this;
    }
}