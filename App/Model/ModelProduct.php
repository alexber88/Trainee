<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.07.16
 * Time: 17:36
 */

namespace Model;
use System\AbstractModel;
use Orm\Model\Product;


class ModelProduct extends AbstractModel
{
    public function saveProductFromMagento($product)
    {

        $model = new Product($this->_connection);
        if($prodId = $this->checkIfSkuExist($product->sku))
        {
            $model->load($prodId);
        }
        $model->setEntityId($product->entity_id);
        $model->setSku($product->sku);
        $model->setDescription($product->description);
        $model->setName($product->name);
        $model->setPrice($product->regular_price_with_tax);
        $model->setIsSaleable($product->is_saleable ? $product->is_saleable : 0);
        $model->setLastImportDate(date('Y-m-d H:i:s'));

        $model->save();
    }

    public function saveEditedProduct($product)
    {
        $model = new Product($this->_connection);
        $model->load($product['id']);

        $model->setSku($product['sku']);
        $model->setDescription($product['description']);
        $model->setName($product['name']);
        $model->setPrice($product['price']);
        $model->setIsSaleable($product['is_saleable']);
        $model->save();
    }

    public function getListOfProducts($column, $order, $limit, $offset)
    {
        $products = [];
        $query = "SELECT id, entity_id, sku, description, name, price, is_saleable FROM product ORDER BY $column $order LIMIT $offset,$limit";
        $sth = $this->_connection->prepare($query);
        $sth->execute();
        $products = $sth->fetchAll(\PDO::FETCH_ASSOC);
        return $products;
    }

    public function getCountOfProducts()
    {
        $query = "SELECT COUNT(id) as count FROM product";
        $sth = $this->_connection->prepare($query);
        $sth->execute();
        $count = $sth->fetch(\PDO::FETCH_ASSOC);
        return $count['count'];
    }

    public function getProduct($id)
    {
        $model = new Product($this->_connection);
        $model->load($id);

        $product['id'] = $model->getId();
        $product['entity_id'] = $model->getEntityId();
        $product['sku'] = $model->getSku();
        $product['description'] = $model->getDescription();
        $product['name'] = $model->getName();
        $product['price'] = $model->getPrice();
        $product['is_saleable'] = $model->getIsSaleable();
        $product['last_import_date'] = $model->getLastImportDate();

        return $product;
    }

    public function getProductsFromMagento($magentoUrl)
    {
        $callbackUrl = BASE_URL."/product/import/";
        $temporaryCredentialsRequestUrl = $magentoUrl."/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
        $adminAuthorizationUrl = $magentoUrl.'/oauth/authorize';
        $accessTokenRequestUrl = $magentoUrl.'/oauth/token';
        $apiUrl = $magentoUrl.'/api/rest';
        $consumerKey = '7e3407909c8553ef3bc85b324113caba';
        $consumerSecret = 'c4fc9cf575afae8be7ed521c4fd844a9';

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

        } catch (\OAuthException $e) {
            print_r($e);
        }

        return $products;
    }
    
    private function checkIfSkuExist($sku)
    {
        $query = "SELECT id FROM product WHERE sku = :sku";
        $sth = $this->_connection->prepare($query);
        $sth->bindParam(':sku', $sku);
        $sth->execute();
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        return $res['id'];
    }
}