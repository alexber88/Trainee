
<?php

class Alex_Blog_Block_Adminhtml_Blog_Post extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'blog';
        $this->_controller = 'adminhtml_blog_post';
        $this->_headerText = Mage::helper('alex_blog')->__('Blog - Alex');
        parent::__construct();
        $this->_removeButton('add');
        //$this->_addButton('edit');
    }
}