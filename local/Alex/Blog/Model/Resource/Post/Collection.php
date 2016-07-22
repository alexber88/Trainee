<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 18.07.16
 * Time: 9:43
 */

class Alex_Blog_Model_Resource_Post_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('blog/post');
    }
}