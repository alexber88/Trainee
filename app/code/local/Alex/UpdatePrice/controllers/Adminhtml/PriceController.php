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
        $strategy = $params['strategy'];

        if(empty($ids))
        {
            Mage::getSingleton('adminhtml/session')->addError($helper->__('Please select product(s)'));
        }
        elseif ($helper->checkValue($value))
        {
            if($helper->strategyExist($strategy))
            {
                $class = Mage::getConfig()->getNode('global/price_mass_action/operations/'.$strategy.'/class')->asArray();
                $model = Mage::getModel('updateprice/price', new $class);
                $updated = $model->updatePrice($ids, $value);
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