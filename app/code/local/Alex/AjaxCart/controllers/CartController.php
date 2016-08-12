<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 10.08.16
 * Time: 10:17
 */


class Alex_AjaxCart_CartController extends Mage_Core_Controller_Front_Action
{

    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    protected function _getProduct($id)
    {
        return Mage::getModel('catalog/product')->load($id);
    }

    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    public function addAction()
    {
        $data = [];
        if (!$this->_validateFormKey()) {
            $data['status'] = 'error';
            $data['msg'] = 'Invalid form key';
            echo json_encode($data);
            exit;
        }
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();

        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_getProduct($params['product']);
            if(isset($params['related_product']))
            {
                $related = $params['related_product'];
            }


            /**
             * Check product availability
             */
            if (!$product) {
                $data['status'] = 'Error';
                $data['msg'] = $this->__('Unable to find Product ID');
                return;
            }

            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    $data['msg'] = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                    $data['status'] = 'success';

                    $this->loadLayout();
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                    $sidebar = $this->getLayout()->getBlock('minicart_head')->toHtml();
                    $data['toplink'] = $toplink;
                    $data['sidebar'] = $sidebar;

                }

            }
        } catch (Mage_Core_Exception $e) {
            $msg = '';
            if ($this->_getSession()->getUseNotice(true)) {
                $msg = $e->getMessage();
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $msg .= $message.'<br/>';
                }
            }
            $data['status'] = 'error';
            $data['msg'] = $msg;
        } catch (Exception $e) {
            $data['status'] = 'Error';
            $data['msg'] = $this->__('Cannot add the item to shopping cart.');
            Mage::logException($e);
        }
        echo json_encode($data);
        exit;
    }
}