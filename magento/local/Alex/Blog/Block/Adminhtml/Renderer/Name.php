<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 27.07.16
 * Time: 11:40
 */

class Alex_Blog_Block_Adminhtml_Renderer_Name extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $row->getFirstName().' '.$row->getLastName();
    }
}