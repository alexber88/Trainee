<?php

class Alex_Blog_Block_Adminhtml_Blog_Post_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'blog';
        $this->_controller = 'adminhtml_blog_post';
        $this->_mode = 'edit';
        $this->_removeButton('add');

        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

    }

    public function getHeaderText()
    {

        if (Mage::registry('post_data') && Mage::registry('post_data')['post_id'])
        {
            return Mage::helper('alex_blog')->__('Edit Post "%s"', $this->htmlEscape(Mage::registry('post_data')['title']));
        } else {
            return Mage::helper('alex_blog')->__('New Post');
        }
    }
}