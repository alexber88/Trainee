<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.07.16
 * Time: 9:27
 */

namespace App;
use System\AbstractController;
use Model\ModelProduct;
use Lib\Validation;
use Lib\Pagination;

class ProductController extends AbstractController
{
    private $_currentPage = 1;
    private $_column = 'name';
    private $_order = 'asc';
    private $_magentoUrl = 'http://magento1.com';
    const PER_PAGE = 20;

    public function indexAction()
    {
        $this->redirectIfSessionDoesntExist();
        $this->_view->render($this->viewFileName);
    }

    public function addAction()
    {
        $params = $this->_getParams();
        $str = "{$params['id']}, {$params['name']}; method - add";
        $this->_view->render($this->viewFileName, $str);
    }

    public function importAction()
    {
        $this->redirectIfSessionDoesntExist();

        if(isset($_POST['url']))
        {
            $_SESSION['state'] = 1;
            $this->_magentoUrl = $_POST['url'];
            $_SESSION['url'] = $_POST['url'];
        }
        elseif (isset($_SESSION['url']))
        {
            $this->_magentoUrl = $_SESSION['url'];
        }

        $model = new ModelProduct();
        $products = $model->getProductsFromMagento($this->_magentoUrl);

        foreach ($products as $product)
        {
            $model->saveProductFromMagento($product);
        }
        header('Location: '.BASE_URL.'/product/list');
    }

    public function listAction()
    {

        $this->redirectIfSessionDoesntExist();

        $params = $this->_getParams();

        $model = new ModelProduct();
        $count = $model->getCountOfProducts();

        if(isset($params['page']))
        {
            $this->_currentPage = $params['page'];
        }
        $offset = ($this->_currentPage-1)*self::PER_PAGE;

        $pagination = new Pagination();
        $config = [
            'currentPage' => $this->_currentPage,
            'perPage' => self::PER_PAGE,
            'count' => $count
        ];
        $pages = $pagination->createPages($config);

        $columns = ['name', 'price'];
        if(isset($params['order'], $params['column']))
        {
            $this->_column = $params['column'];
            $this->_order = $params['order'];
        }

        $sortArr = $this->_getSortingLinks($this->_column, $this->_order, $columns);

        $products = $model->getListOfProducts($this->_column, $this->_order, self::PER_PAGE, $offset);

        $data['products'] = $products;
        $data['sort'] = $sortArr;
        $data['pagination'] = $pages;

        $this->_view->render($this->viewFileName, $data);
    }

    public function editAction()
    {
        $this->redirectIfSessionDoesntExist();
        if(isset($_POST) && !empty($_POST))
        {
            $product = $_POST;

            $errors = $this->_validateFields($product);

            if(!empty($errors))
            {
                $data['errors'] = $errors;
                $data['product'] = $product;
            }
            else
            {
                $model = new ModelProduct();
                $model->saveEditedProduct($product);
                header('Location: '.BASE_URL.'/product/list');
            }

        }
        else
        {
            $params = $this->_getParams();
            $model = new ModelProduct();
            $product = $model->getProduct($params['id']);
        }

        $data['product'] = $product;
        $this->_view->render($this->viewFileName, $data);
    }

    private function _validateFields(&$product)
    {
        $validate = new Validation();

        $errors = [];
        if($validate->validateText($product['name']) !== true)
        {
            $errors['name'] = 'Error value';
        }
        if($validate->validateVarchar($product['sku']) !== true)
        {
            $errors['sku'] = 'Error value';
        }
        if($validate->validateStatus($product['is_saleable']) !== true)
        {
            $errors['is_saleable'] = 'only saleable or not';
        }
        if($validate->validateText($product['description']) !== true)
        {
            $errors['description'] = 'too much symbols';
        }
        if($validate->validatePrice($product['price']) !== true)
        {
            $errors['price'] = 'only numeric and positive';
        }
        return $errors;
    }

}