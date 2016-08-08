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
            $quoteItem->setAdditionalShippingCost($product->getAdditionalShippingCost());
        }
    }
}

