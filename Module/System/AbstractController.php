<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 11.07.16
 * Time: 22:09
 */

namespace System;
use System\View;

abstract class AbstractController
{
    protected $_view;
    public $viewFileName;

    public function __construct()
    {
        session_start();
        $this->_view = new View();
    }

    abstract public function index();

    protected function redirectIfSessionDoesntExist()
    {
        if(!isset($_SESSION['id']))
        {
            header("Location: ".BASE_URL);
        }
    }

}