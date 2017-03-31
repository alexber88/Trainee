<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 28.07.16
 * Time: 17:06
 */

class Alex_Blog_Block_Adminhtml_Renderer_Status extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $status = $row->getStatus();
        if($status == 0)
        {
            return 'Disabled';
        }
        else
        {
            return 'Enabled';
        }
    }
}