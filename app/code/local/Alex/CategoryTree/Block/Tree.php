<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.08.16
 * Time: 12:23
 */

class Alex_CategoryTree_Block_Tree extends Mage_Core_Block_Template
{
    const PARENT_CATEGORY = 2;

    public function getCategories()
    {
        $cats = Mage::getModel('categorytree/tree')->getCategoriesArray(self::PARENT_CATEGORY);
        return $this->categoriesToHtml($cats);
    }

    protected function categoriesToHtml($cats)
    {
        $html = '<ul>';
        foreach ($cats as $cat)
        {

            $html .= '<li><a '.($cat['parent_id'] == self::PARENT_CATEGORY ? "style='font-weight: bold;'" : "").' href="'.Mage::getBaseUrl().$cat['url'].'">'.$cat['name'].'</a>';

            if(isset($cat['children']))
            {
                $html .= $this->categoriesToHtml($cat['children']);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}