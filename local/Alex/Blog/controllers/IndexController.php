<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 15.07.16
 * Time: 17:47
 */

class Alex_Blog_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Blog'));
        $this->renderLayout();
//        Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }

    public function postAction()
    {
        $params = $this->getRequest()->getParams();
        $post = Mage::getModel('blog/post')->getPost($params['id']);
        Mage::register('post', $post);
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__($post['title']));
        $this->renderLayout();
    }

    public function saveAction()
    {
        $customerData = Mage::getSingleton('customer/session');
        $params = $this->getRequest()->getParams();
        if(!$customerData->isLoggedIn() || empty($params))
        {
            $this->_redirect('blog');
            return;
        }

        $model = Mage::getModel('blog/post');
        if(!$params['id'])
        {
            $model->setCustomerId($customerData->getCustomer()->getId());
            $model->setDate(date('Y-m-d H:i:s'));
        }
        else
        {
            $model->load($params['id']);
        }

        $path = Mage::getBaseDir('media').'/uploads';

        if($img = $model->getImg())
        {
            $img = $path.'/'.$img;
        }

        if ($params['del_img'] && $img)
        {
            $model->setImg('');
            unlink($img);
        }
        elseif($name = $_FILES['image']['name'])
        {
            if($img)
            {
                unlink($img);
            }
            $model->setImg($this->_saveImage($name, $path));
        }

        $model->setTitle($params['title']);
        $model->setContent($params['content']);

        $model->save();
        $this->_redirect('blog');
        return;
    }

    public function addAction()
    {
        if(!Mage::getSingleton('customer/session')->isLoggedIn())
        {
            $this->_redirect('blog');
            return;
        }
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Add'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $customerData = Mage::getSingleton('customer/session');
        if(!$customerData->isLoggedIn())
        {
            $this->_redirect('blog');
            return;
        }

        $params = $this->getRequest()->getParams();
        $post = Mage::getModel('blog/post')->getPost($params['id']);
        $customerId = $customerData->getCustomer()->getId();

        if($customerId != $post['customer_id'])
        {
            $this->_redirect('blog');
            return;
        }
        Mage::register('post', $post);

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Edit'));
        $this->renderLayout();
    }

    public function deleteAction()
    {
        $customerData = Mage::getSingleton('customer/session');
        if(!$customerData->isLoggedIn())
        {
            $this->_redirect('blog');
            return;
        }

        $params = $this->getRequest()->getParams();
        $customerId = $customerData->getCustomer()->getId();

        $model = Mage::getModel('blog/post');
        $model->load($params['id']);
        if($img = $model->getImg())
        {
            $img = $path = Mage::getBaseDir('media').'/uploads/'.$img;
            unlink($img);
        }
        if($customerId != $model->getCustomerId())
        {
            $this->_redirect('blog');
            return;
        }
        $model->delete();
        $this->_redirect('blog');
        return;
    }

    private function _saveImage($name, $path)
    {
//        chmod($path, 777);
        $uploader = new Varien_File_Uploader('image');
        $uploader->setAllowedExtensions(array('jpg', 'jpeg'));
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);
        $uploader->save($path, $name);
        return $uploader->getUploadedFileName();
    }

}