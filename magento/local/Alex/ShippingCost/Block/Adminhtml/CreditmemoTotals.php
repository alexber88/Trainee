<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 04.08.16
 * Time: 17:24
 */

class Alex_ShippingCost_Block_Adminhtml_CreditmemoTotals extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */

    private $_additionalShippingCost = 0;

    protected function _initTotals()
    {
        parent::_initTotals();

        $order = $this->getOrder();
        foreach ($order->getAllItems() as $item)
        {
            $this->_additionalShippingCost += $item->getAdditionalShippingCost();
        }

        if ($this->_additionalShippingCost != 0) {
            $this->addTotalBefore(new Varien_Object([
                'code'      => 'alex_shippingcost',
                'value'     => $this->_additionalShippingCost,
                'base_value'=> $this->_additionalShippingCost,
                'label'     => $this->helper('shippingcost')->__('Shipping Cost'),
            ]));
        }

        return $this;
    }

}

