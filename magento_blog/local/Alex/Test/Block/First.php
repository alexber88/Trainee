<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 14.07.16
 * Time: 16:37
 */

class Alex_Test_Block_First extends Mage_Core_Block_Template
{
    public function getSmth()
    {
        $smth = Mage::getModel('test/first')->getSmth();
        return $smth;
    }

//    public function firstBlockSecondFunction()
//    {
//        return $
//    }
}