<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.07.16
 * Time: 9:27
 */

namespace App;
use System\AbstractController;

class Product extends AbstractController
{

    public function index()
    {
        $this->redirectIfSessionDoesntExist();
        $this->_view->render($this->viewFileName);
    }

    public function add($word1, $word2)
    {
        $str = "$word1, $word2; method - add";
        $this->_view->render($this->viewFileName, $str);
    }

    public function import()
    {
        $this->redirectIfSessionDoesntExist();

        if(isset($_POST['url']))
        {
            $_SESSION['state'] = 1;
            $magento_url = $_POST['url'];
            $_SESSION['url'] = $_POST['url'];
        }
        elseif (isset($_SESSION['url']))
        {
            $magento_url = $_SESSION['url'];
        }
        else
        {
            $magento_url = 'http://magento1.com';
        }

        /**
         * Example of products list retrieve using Customer account via Magento REST API. OAuth authorization is used
         */

        $callbackUrl = BASE_URL."/product/import/";
        $temporaryCredentialsRequestUrl = $magento_url."/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
        $adminAuthorizationUrl = $magento_url.'/oauth/authorize';
        $accessTokenRequestUrl = $magento_url.'/oauth/token';
        $apiUrl = $magento_url.'/api/rest';
        $consumerKey = '7e3407909c8553ef3bc85b324113caba';
        $consumerSecret = 'c4fc9cf575afae8be7ed521c4fd844a9';

        //session_start();

        if (!isset($_GET['oauth_token']) && isset($_SESSION['state']) && $_SESSION['state'] == 1) {
            $_SESSION['state'] = 0;
        }

        try {
            $authType = ($_SESSION['state'] == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION : OAUTH_AUTH_TYPE_URI;
            $oauthClient = new \OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, $authType);
            $oauthClient->enableDebug();

            if (!isset($_GET['oauth_token']) && !$_SESSION['state']) {
                $requestToken = $oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
                $_SESSION['secret'] = $requestToken['oauth_token_secret'];
                $_SESSION['state'] = 1;
                header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' . $requestToken['oauth_token']);
                exit;
            } else if ($_SESSION['state'] == 1) {
                $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
                $accessToken = $oauthClient->getAccessToken($accessTokenRequestUrl);
                $_SESSION['state'] = 2;
                $_SESSION['token'] = $accessToken['oauth_token'];
                $_SESSION['secret'] = $accessToken['oauth_token_secret'];
                header('Location: ' . $callbackUrl);
                exit;
            } else {
                $oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);
                $limit = 100;
                $products = [];
                $previous = [];
                for($page = 1;;$page++)
                {
                    $productsList = null;
                    $resourceUrl = "$apiUrl/products?page=$page&limit=$limit";
                    $oauthClient->fetch($resourceUrl, array(), 'GET', array("Content-Type" => "application/json","Accept" => "*/*"));
                    $productsList = json_decode($oauthClient->getLastResponse());
                    foreach ($productsList as $entityId => $product)
                    {
                        $products[$entityId] = $product;
                    }
                    if(count((array)$productsList) < $limit || (count((array)$productsList) == count((array)$previous) && $previous == $productsList))
                    {
                        break;
                    }
                    $previous = $productsList;
                }
            }
            echo '<pre>';
            print_r($products);
            echo '</pre>';
        } catch (\OAuthException $e) {
            print_r($e);
        }
    }
}