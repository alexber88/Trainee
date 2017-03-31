<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 16.08.16
 * Time: 13:02
 */

class Alex_Blog_Model_Observer
{
    public function addNodeToTopMenu(Varien_Event_Observer $observer)
    {
        $menu = $observer->getMenu();
        $tree = $menu->getTree();

        $node = new Varien_Data_Tree_Node(array(
            'name'   => 'Blog',
            'id'     => 'blog',
            'url'    => Mage::getUrl().'blog', // point somewhere
        ), 'id', $tree, $menu);

        $menu->addChild($node);

        // Children menu items
//        $collection = Mage::getResourceModel('catalog/category_collection')
//            ->setStore(Mage::app()->getStore())
//            ->addIsActiveFilter()
//            ->addNameToResult()
//            ->setPageSize(3);

//        foreach ($collection as $category) {
//            $tree = $node->getTree();
//            $data = array(
//                'name'   => $category->getName(),
//                'id'     => 'category-node-'.$category->getId(),
//                'url'    => $category->getUrl(),
//            );
//
//            $subNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
//            $node->addChild($subNode);
//        }
    }
}