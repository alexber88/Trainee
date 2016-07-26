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
    public $params;

    public function __construct()
    {
        session_start();
        $this->_view = new View();
    }

    abstract public function indexAction();

    protected function redirectIfSessionDoesntExist()
    {
        if(!isset($_SESSION['id']))
        {
            header("Location: ".BASE_URL);
        }
    }

    protected function _getParams()
    {
        $params = [];
        
        for($i=0; $i<count($this->params)-1; $i+=2)
        {
            $params[$this->params[$i]] = $this->params[$i+1];
        }

        return $params;
    }

    protected function _getSortingLinks($currentColumn, $currentOrder, $columnsArray)
    {
        $sortArr = [];
        if(in_array($currentColumn, $columnsArray))
        {
            if($currentOrder == 'asc')
            {
                $sortArr[$currentColumn]['href'] = 'order/desc/column/'.$currentColumn;
                $sortArr[$currentColumn]['arrow'] = '&#9660;';
            }
            else
            {
                $sortArr[$currentColumn]['href'] = 'order/asc/column/'.$currentColumn;
                $sortArr[$currentColumn]['arrow'] = '&#9650;';
            }
            unset($columnsArray[array_search($currentColumn, $columnsArray)]);
            foreach($columnsArray as $col)
            {
                $sortArr[$col]['href'] = 'order/asc/column/'.$col;
                $sortArr[$col]['arrow'] = '&#9650;';
            }
        }
        ksort($sortArr);

        return $sortArr;
    }

}