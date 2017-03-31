<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 03.08.16
 * Time: 16:25
 */

class Alex_ShippingCost_Model_ShippingCost extends Mage_Sales_Model_Quote_Address_Total_Abstract
{

    private $_additionalShippingCost = 0;
    public function __construct()
    {
        $this->setCode('additional_shipping_cost');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        if (($address->getAddressType() == 'billing'))
        {
            return $this;
        }

        $cart = $address->getQuote();
        foreach ($cart->getAllItems() as $item)
        {
            $this->_additionalShippingCost += $item->getAdditionalShippingCost()*$item->getQty();
        }

        if ($this->_additionalShippingCost)
        {
            $this->_addAmount($this->_additionalShippingCost);
            $this->_addBaseAmount($this->_additionalShippingCost);
        }

        return $this;
    }

    public function getLabel()
    {
        return Mage::helper('shippingcost')->__('Additional Shipping Cost');
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if (($address->getAddressType() == 'billing'))
        {
            if ($this->_additionalShippingCost != 0)
            {
                $address->addTotal(array(

                    'code'  => $this->getCode(),
                    'title' => $this->getLabel(),
                    'value' => $this->_additionalShippingCost,
                ));
            }
        }

        return $this;
    }


}