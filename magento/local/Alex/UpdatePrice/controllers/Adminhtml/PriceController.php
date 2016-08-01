<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 28.07.16
 * Time: 18:42
 */

class Alex_UpdatePrice_Adminhtml_PriceController extends Mage_Adminhtml_Controller_Action
{
    public function massUpdatePriceAction()
    {
        $params = $this->getRequest()->getParams();
        $ids = $params['product'];
        $value = str_replace(',', '.', $params['value']);
        $method = $params['method'];

        if(empty($ids))
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('alex_updateprice')->__('Please select product(s)'));
        }
        elseif (!$value || $value < 0 || !is_numeric($value))
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('alex_updateprice')->__('Please enter a valid value'));
        }
        else
        {
            if(is_callable([$this, $method]))
            {
                $model = Mage::getModel('catalog/product');
                foreach ($ids as $id)
                {
                    $product = $model->load($id);
                    $newPrice = call_user_func([$this, $method], $product->getPrice(), $value);
                    $product->setPrice($newPrice);
                    $product->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('alex_updateprice')->__(
                    'Total of %d record(s) were updated.', count($ids)
                ));
            }
        }
        $this->_redirect('*/catalog_product/index');
    }

    private function _addition($price, $value)
    {
        return $price + $value;
    }

    private function _subtraction($price, $value)
    {
        return $price - $value;
    }

    private function _addPercent($price, $value)
    {
        return $price + round(($price * $value)/100, 2);
    }

    private function _subtractPercent($price, $value)
    {
        return $price - round(($price * $value)/100, 2);
    }

    private function _multiplication($price, $value)
    {
        return $price * $value;
    }
}