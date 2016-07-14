<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.07.16
 * Time: 15:08
 */

namespace System;

use Config\Connection;

abstract class AbstractModel
{
    protected $_connection;
    public function __construct()
    {
        if(!$this->_connection)
        {
            $connect = new Connection();
            $this->_connection = $connect->getConnection();
        }
    }
}