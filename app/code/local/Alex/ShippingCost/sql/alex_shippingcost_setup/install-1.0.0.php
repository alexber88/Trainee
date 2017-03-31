<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 03.08.16
 * Time: 15:15
 */

$installer = $this;
$installer->startSetup();

$installer->addAttribute('catalog_product', "additional_shipping_cost", [
    'type'       => 'decimal',
    'input'      => 'text',
    'label'      => 'Additional shipping cost',
    'group' => 'Additional shipping cost',
    'visible' => 1,
    'required' => 0,
    'user_defined' => 0,
    'searchable' => 0,
    'filterable' => 0,
    'comparable' => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 0,
    'is_configurable' => 0,
    'unique' => 0,
    'global'     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ]
);

$installer->endSetup();