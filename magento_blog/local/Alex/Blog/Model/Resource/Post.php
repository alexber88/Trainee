<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 17.07.16
 * Time: 20:13
 */

class Alex_Blog_Model_Resource_Post extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('blog/post', 'post_id');
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        $select->joinLeft(
            ['cev1' => 'customer_entity_varchar'],
            $this->getMainTable() . '.customer_id = cev1.entity_id AND cev1.attribute_id = 5 ',
            ['cev1.value as first_name'])
        ->joinLeft(
            ['cev2' => 'customer_entity_varchar'],
            $this->getMainTable() . '.customer_id = cev2.entity_id AND cev2.attribute_id = 7 ',
            ['cev2.value as last_name']);
        return $select;
    }
}