<?php

class Alex_Blog_Block_Adminhtml_Blog_Post_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if (Mage::getSingleton('adminhtml/session')->getExampleData())
        {
            $data = Mage::getSingleton('adminhtml/session')->getExamplelData();
            Mage::getSingleton('adminhtml/session')->getExampleData(null);
        }
        elseif (Mage::registry('post_data'))
        {
            $data = Mage::registry('post_data');
        }
        else
        {
            $data = array();
        }

        if(Mage::registry('products'))
        {
            $products = Mage::registry('products');
        }
        else
        {
            $products = [];
        }

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('post_form', array(
            'legend' =>Mage::helper('alex_blog')->__('Post Information')
        ));
        $fieldset->addField('title', 'text', array(
            'label' 	=> Mage::helper('alex_blog')->__('Title'),
            'class' 	=> 'required-entry',
            'required'  => true,
            'name'  	=> 'title',
            'style' => 'width: 800px',
            'note' 	=> Mage::helper('alex_blog')->__('The title of the post.'),
        ));

        $fieldset->addField('content', 'textarea', array(
            'label' 	=> Mage::helper('alex_blog')->__('Content'),
            'class' 	=> 'required-entry',
            'required'  => true,
            'name'  	=> 'content',
            'style' => 'width: 800px',
            'note' 	=> Mage::helper('alex_blog')->__('The content of the post.'),
        ));

        $fieldset->addField('status', 'checkbox', array(
            'label' 	=> Mage::helper('alex_blog')->__('Visibile'),
            'name'  	=> 'status',
            'checked' => $data['status'] == 1 ? true : false,
            'note' 	=> Mage::helper('alex_blog')->__('The status of the post.'),
        ));

        
        $fieldset->addField('img', 'image', array(
            'label' 	=> Mage::helper('alex_blog')->__('Image'),
            'name'  	=> 'image',
            'value' => Mage::getBaseUrl('media').$data['img'],
            'note' 	=> Mage::helper('alex_blog')->__('The post image.')
        ));

        $fieldset->addField('product', 'multiselect', array(
            'label' 	=> Mage::helper('alex_blog')->__('Product'),
            'name'  	=> 'product[]',
            'values' => $products,
            'value' => [231, 232]
            //'note' 	=> Mage::helper('alex_blog')->__('The post image.')
        ));



//        $fieldset->addField('img', 'file', array(
//            'label' 	=> Mage::helper('alex_blog')->__('Image'),
//            'name'  	=> 'img',
//            'note' 	=> Mage::helper('alex_blog')->__('The post image.'),
//        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }
}
