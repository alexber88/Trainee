<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 03.08.16
 * Time: 17:39
 */

$installer = new Mage_Sales_Model_Resource_Setup('core_setup');

$installer->startSetup();

$entities = array(
    'quote_item',
    'order_item'
);
$options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'visible'  => true,
    'required' => false
);
foreach ($entities as $entity) {
    $installer->addAttribute($entity, 'additional_shipping_cost', $options);
}


$installer->endSetup();