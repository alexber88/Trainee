<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.08.16
 * Time: 12:18
 */

class Alex_CategoryTree_Model_Tree extends Mage_Core_Model_Abstract
{
    const INCLUDE_IN_MENU = 1;
    const LEVEL = 1;
    protected $_model = null;

    public function getCategoriesArray($parentId)
    {
        $categories = [];
        $collection = $this->getCategoriesCollection($parentId);
        if($collection->getSize() > 0)
        {
            foreach($collection as $category)
            {
                $cat = [];
                if($category->getProductCount() != 0)
                {
                    $cat['name'] = $category->getName();
                    $cat['url'] = $category->getUrlPath();
                    $cat['parent_id'] = $category->getParentId();

                    $child = $this->getCategoriesArray($category->getId());
                    if(!empty($child))
                    {
                        $cat['children'] = $child;
                    }
                    $categories[] = $cat;
                }

            }
            return $categories;
        }
    }

    protected function getCategoriesCollection($parentId)
    {
        if($this->_model == null)
        {
            $this->_model = Mage::getModel('catalog/category');
        }
        $collection = $this->_model
            ->getCollection()
            ->addAttributeToSelect(['url_path', 'name', 'parent_id'])
            ->addAttributeToFilter('include_in_menu', ['eq' => self::INCLUDE_IN_MENU])
            ->addAttributeToFilter('level', ['gt' => self::LEVEL])
            ->addAttributeToFilter('parent_id', ['eq' => $parentId]);
        return $collection;
    }
}