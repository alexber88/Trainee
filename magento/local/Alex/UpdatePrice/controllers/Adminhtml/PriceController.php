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
        $helper = Mage::helper('alex_updateprice');
        $params = $this->getRequest()->getParams();

        $ids = $params['product'];
        $value = str_replace(',', '.', $params['value']);
        $method = $params['method'];

        if(empty($ids))
        {
            Mage::getSingleton('adminhtml/session')->addError($helper->__('Please select product(s)'));
        }
        elseif ($helper->checkValue($value))
        {
            if($helper->methodExist($method))
            {
                $model = Mage::getModel('updateprice/price');
                $updated = $model->updatePrice($ids, $method, $value);
                if($updated)
                {
                    Mage::getSingleton('adminhtml/session')->addSuccess($helper->__(
                        'Total of %d record(s) were updated.', $updated
                    ));
                }
            }
            else
            {
                Mage::getSingleton('adminhtml/session')->addError($helper->__("Method doesn't exist"));
            }
        }
        $this->_redirect('*/catalog_product/index');
    }
}