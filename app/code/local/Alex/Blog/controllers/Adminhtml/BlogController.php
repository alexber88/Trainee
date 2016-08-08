<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 27.07.16
 * Time: 9:26
 */

class Alex_Blog_Adminhtml_BlogController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {

        $this->_title($this->__('Blog'))->_title($this->__('Alex Blog'));
        $this->loadLayout();
        $this->_setActiveMenu('Blog');
        $this->_addContent($this->getLayout()->createBlock('blog/adminhtml_blog_post'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('blog/adminhtml_blog_post_grid')->toHtml()
        );
    }

    public function exportAlexCsvAction()
    {
        $fileName = 'alex_blog.csv';
        $grid = $this->getLayout()->createBlock('blog/adminhtml_blog_post_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    public function exportAlexExcelAction()
    {
        $fileName = 'alex_blog.xml';
        $grid = $this->getLayout()->createBlock('blog/adminhtml_blog_post_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
    
    public function editAction()
    {
        $params = $this->getRequest()->getParams();
        if(isset($params['id']))
        {
            $model = Mage::getModel('blog/post');
            $post = $model->getPost($params['id']);
            Mage::register('post_data', $post);
            $products = $model->getProducts();
            Mage::register('products', $products);
        }

        $this->_title($this->__('Blog'))->_title($this->__('Edit Post'));
        $this->loadLayout();
        $this->_setActiveMenu('Blog');
        $this->_addContent($this->getLayout()->createBlock('blog/adminhtml_blog_post_edit'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function deleteAction()
    {
        $params = $this->getRequest()->getParams();
        if(isset($params['id']))
        {
            Mage::getModel('blog/post')->deletePost($params['id']);
        }
        $this->_forward('index');
    }

    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        $file = $_FILES;
        Mage::getModel('blog/post')->savePost($params, $file);
        if(isset($params['back']))
        {
            $this->_forward($params['back']);
        }
        else
        {
            $this->_forward('index');
        }
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('post_id');
        if(empty($ids))
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('alex_blog')->__('Please select post(s).'));
        }
        else
        {
            $model = Mage::getModel('blog/post');
            foreach ($ids as $id)
            {
                $model->load($id)->delete();
            }
        }
        $this->_forward('index');
    }

    public function massStatusAction()
    {
        $ids = $this->getRequest()->getParam('post_id');
        $status = $this->getRequest()->getParam('status');
        if(empty($ids))
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('alex_blog')->__('Please select post(s).'));
        }
        else
        {
            $model = Mage::getModel('blog/post');
            foreach ($ids as $id)
            {
                $post = $model->load($id);
                $post->setStatus($status);
                $post->save();
            }
        }
        $this->_forward('index');
    }
}