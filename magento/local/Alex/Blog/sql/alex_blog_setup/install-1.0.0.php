<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 19.07.16
 * Time: 17:19
 */

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('blog/post'))
    ->addColumn('post_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
        'index' => true
    ), 'Customer')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => false,
    ), 'Title')
    ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => false,
    ), 'Content')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
        'nullable'  => false,
    ), 'Content')
    ->addColumn('img', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => false,
        'default' => '',
    ), 'Image')
    ->addColumn('date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable'  => false,
    ), 'Date');
$installer->getConnection()->createTable($table);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('blog/post', 'customer_id', 'blog/customer', 'entity_id'),
    $installer->getTable('blog/post'), 'customer_id',
    $installer->getTable('blog/customer'), 'entity_id');

$installer->endSetup();