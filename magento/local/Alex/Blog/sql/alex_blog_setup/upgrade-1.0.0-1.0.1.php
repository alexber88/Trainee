<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 22.07.16
 * Time: 17:09
 */

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('blog/blog_post'))
    ->addColumn('post_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'post_id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
    ), 'product_id');

$installer->getConnection()->createTable($table);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('blog/blog_post', 'product_id', 'blog/product', 'entity_id'),
    $installer->getTable('blog/blog_post'), 'product_id',
    $installer->getTable('blog/product'), 'entity_id');

$installer->getConnection()->addForeignKey(
    $installer->getFkName('blog/blog_post', 'post_id', 'blog/post', 'post_id'),
    $installer->getTable('blog/blog_post'), 'post_id',
    $installer->getTable('blog/post'), 'post_id');

$installer->endSetup();