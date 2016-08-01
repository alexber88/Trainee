<?php

class Alex_ProductsWidget_Block_Adminhtml_Renderer_IsTop extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $isTop = $row->getIsTop();
        if($isTop == 0)
        {
            return 'No';
        }
        else
        {
            return 'Yes';
        }
    }
}