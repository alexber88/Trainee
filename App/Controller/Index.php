<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.07.16
 * Time: 10:15
 */

namespace App;
use System\AbstractController;
use Model\ModelUser;

class Index extends AbstractController
{

    public function index()
    {
        if(isset($_SESSION['id']))
        {
            header("Location: ".BASE_URL."/product");
        }
        else
        {
            $this->_view->render($this->viewFileName, 'Login page');
        }
    }
    
    public function login()
    {
        $email =  $_POST['email'];
        $password = $_POST['password'];
        if(isset($email, $password) && !empty($email) && !empty($password))
        {
            $modelUser = new ModelUser();
            $id = $modelUser->checkIfUserExist($email, $password);
            if($id)
            {
                $_SESSION['id'] = $id;
                header("Location: ".BASE_URL."/product");
            }
            else
            {
                $this->_view->render($this->viewFileName);
            }
        }

    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: ".BASE_URL);
    }

}