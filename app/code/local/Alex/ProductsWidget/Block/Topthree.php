<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 29.07.16
 * Time: 15:05
 */

class Alex_ProductsWidget_Block_Topthree extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    protected $_title = '';
    protected $_size = '';

    protected function _toHtml()
    {
        $collection = Mage::getModel('widget/widget_instance')->getCollection()->addFieldToFilter('instance_type', ['eq'=>'productswidget/topthree']);
        $widgetParams = unserialize($collection->getData()[0]['widget_parameters']);
        $this->_title = $widgetParams['page_title'];
        $this->_size = $widgetParams['page_size'];
        $products = Mage::getModel('catalog/product')
            ->getResourceCollection()
            ->addAttributeToSelect(['small_image', 'url_path', 'name'])
            ->addFieldToFilter('is_top',['eq'=>1]);
        $products->getSelect()->order(new Zend_Db_Expr('RAND()'))->limit($this->_size,0);
        $this->setTopProducts($products);

        return parent::_toHtml();
    }
}