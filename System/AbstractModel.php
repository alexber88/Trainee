<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.07.16
 * Time: 15:08
 */

namespace System;

abstract class AbstractModel
{
    protected $_connection;
    public function __construct()
    {
        if($this->_connection == null)
        {
            $this->_connection = Registry::getProperty('db_connect');
        }
    }
}