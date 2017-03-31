<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 03.08.16
 * Time: 17:39
 */

$widgetParameters = array(
    'page_size' => '3',
    'page_title' => 'Top Products Widget'
);

$instance = Mage::getModel('widget/widget_instance')->setData(array(
    'type' => 'productswidget/topthree',
    'package_theme' => 'new_package/new_theme',
    'title' => 'Top Three Products',
    'store_ids' => '0,1,2,3',
    'widget_parameters' => serialize($widgetParameters)))->save();