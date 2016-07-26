<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 15.07.16
 * Time: 12:37
 */

class Alex_Test_IndexController extends Mage_Core_Controller_Front_Action
{
    public function helloAction()
    {

        $this->loadLayout();
        $this->renderLayout();
    }
    public function indexAction()
    {

        $this->loadLayout();
        $this->renderLayout();
//        Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }
    public function secondAction()
    {

        $this->loadLayout();
        $this->renderLayout();
//        Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }
}