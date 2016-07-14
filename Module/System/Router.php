<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 11.07.16
 * Time: 22:09
 */

namespace System;

class Router
{
    private $_path;
    private $_viewFolder;

    public function start()
    {
        $args = [];

        $this->_getController($file, $action, $args);

        $className = $file;

        $object = new $className;
        
        $viewFolder = "App/View/$this->_viewFolder/";

        $object->viewFileName = $viewFolder. $action.'.html';

        if(is_callable([$object, $action]))
        {
            call_user_func_array([$object, $action], $args);
        }


    }

    private function _getController(&$file, &$action, &$args)
    {
        $this->_path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        $namespace = "\\App\\";

        $class = ucfirst(array_shift($this->_path));

        if(file_exists("App/Controller/$class.php"))
        {
            $file = $namespace.$class;
            $this->_viewFolder = $class;
        }
        else
        {
            $file = $namespace.'Index';
            $this->_viewFolder = 'Index';
        }

        if(isset($this->_path[0]))
        {
            $action = lcfirst(array_shift($this->_path));
        }
        else
        {
            $action = 'index';
        }

        if(!empty($this->_path))
        {
            $args = $this->_path;
        }

    }
}