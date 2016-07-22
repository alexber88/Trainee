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

        $this->_getController($file, $action, $params);

        $className = $file;

        $object = new $className;
        
        $viewFolder = "App/View/$this->_viewFolder/";

        $object->viewFileName = $viewFolder. $action.'.html';

        $action .= 'Action';

        $object->params = $params;

        if(is_callable([$object, $action]))
        {
//            call_user_func_array([$object, $action], $params);
            call_user_func([$object, $action]);
        }


    }

    private function _getController(&$file, &$action, &$params)
    {
        $this->_path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        $namespace = "\\App\\";

        $fileName = ucfirst(array_shift($this->_path));

        $controller = $fileName.'Controller';

        if(file_exists("App/Controller/$controller.php"))
        {
            $file = $namespace.$controller;
            $this->_viewFolder = $fileName;
        }
        else
        {
            $file = $namespace.'IndexController';
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
            $params = $this->_path;
        }

    }
}