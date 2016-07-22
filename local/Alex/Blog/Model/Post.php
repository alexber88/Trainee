<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 15.07.16
 * Time: 17:42
 */

class Alex_Blog_Model_Post extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('blog/post');
    }

    public function getAllPosts($column, $sort)
    {
        $posts = $this->getCollection()->setOrder($column, $sort);
        $posts->getSelect()
            ->join(['ce' => 'customer_entity'], 'customer_id = ce.entity_id', 'ce.email')
            ->joinLeft(['cev1' => 'customer_entity_varchar'], 'cev1.entity_id = ce.entity_id AND cev1.attribute_id = 5', 'cev1.value as first_name')
            ->joinLeft(['cev2' => 'customer_entity_varchar'], 'cev2.entity_id = ce.entity_id AND cev2.attribute_id = 7', 'cev2.value as last_name');
        return $posts;
    }

    public function getPost($id)
    {
        $post = $this->load($id)->getData();
        return $post;
    }
}