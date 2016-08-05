<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 04.08.16
 * Time: 17:08
 */

class Alex_ShippingCost_Model_ShippingCostCreditmemo extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    private $_additionalShippingCost = 0;
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
        foreach ($order->getAllItems() as $item)
        {
            $this->_additionalShippingCost += $item->getAdditionalShippingCost();
        }
        if ($this->_additionalShippingCost != 0)
        {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $this->_additionalShippingCost);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $this->_additionalShippingCost);
        }

        return $this;
    }
}