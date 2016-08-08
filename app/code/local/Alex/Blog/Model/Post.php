<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 15.07.16
 * Time: 17:42
 */

class Alex_Blog_Model_Post extends Mage_Core_Model_Abstract
{
    const POST_PRODUCT = 'blog_posts_product';
    private $_folder = 'uploads/';

    protected function _construct()
    {
        $this->_init('blog/post');
    }

    public function getAllPosts($column, $sort)
    {
        $posts = $this->getCollection()->setOrder($column, $sort);
        $posts->getSelect()
            ->join(['ce' => 'customer_entity'], 'customer_id = ce.entity_id', 'ce.email')
            ->joinLeft(['cev1' => 'customer_entity_varchar'], 'cev1.entity_id = ce.entity_id AND cev1.attribute_id = 5', 'cev1.value as first_name')
            ->joinLeft(['cev2' => 'customer_entity_varchar'], 'cev2.entity_id = ce.entity_id AND cev2.attribute_id = 7', 'cev2.value as last_name');
        return $posts;
    }

    public function getPost($id)
    {
        $post = $this->load($id)->getData();
        $post['products'] = [];

        $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect(['id', 'name', 'short_description', 'image', 'url_path', 'price', 'special_price']);
        $collection->getSelect()
            ->join(['pp' => self::POST_PRODUCT], 'entity_id = pp.product_id', 'pp.post_id')
            ->where('pp.post_id ='. $id);

        if($collection->getSize()){
            $post['products'] = $collection;
        }

        return $post;
    }
    
    public function savePost($params, $file = null)
    {
        $customerData = Mage::getSingleton('customer/session');
        if(!$params['id'])
        {
            $this->setCustomerId($customerData->getCustomer()->getId());
            $this->setDate(date('Y-m-d H:i:s'));
        }
        else
        {
            $this->load($params['id']);
        }

        $path = Mage::getBaseDir('media');

        if($img = $this->getImg())
        {
            $img = $path.'/'.$img;
        }

        if (isset($params['image']['delete']) && $img)
        {
            $this->setImg('');
            unlink($img);
        }
        elseif($name = $file['image']['name'])
        {
            if($img)
            {
                unlink($img);
            }
            $this->setImg($this->_folder.$this->_saveImage($name, $path.'/'.$this->_folder));
        }

        $this->setTitle($params['title']);
        $this->setContent($params['content']);

        if(isset($params['status']))
        {
            $this->setStatus(1);
        }
        else
        {
            $this->setStatus(0);
        }

        $this->save();
        $postId = $this->getId();

        $this->deletePostProducts($postId);
        if(!empty($params['product']))
        {
            $this->insertPostProduct($postId, $params['product']);
        }
    }
    
    public function deletePost($id)
    {
        $this->load($id);

        if($img = $this->getImg())
        {
            $img = Mage::getBaseDir('media').'/'.$this->_folder.$img;
            unlink($img);
        }

        $this->delete();
    }

    public function deletePostProducts($postId)
    {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $write->delete(self::POST_PRODUCT, "post_id=$postId");
    }

    public function insertPostProduct($postId, $products)
    {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $rows = [];
        foreach($products as $product)
        {
            $tmp = [];
            $tmp = ['post_id' => $postId, 'product_id' => $product];
            $rows[] = $tmp;
        }

        $write->insertMultiple(self::POST_PRODUCT,$rows);
    }

    public function getProducts()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect(['id', 'name']);
        $products = [];
        foreach($collection as $product)
        {
            $products[] = ['value'=> $product->getId(), 'label' => $product->getName()];
        }
        return $products;
    }

    public function getLastPosts()
    {
        $startDate = date('Y-m-d H:i:s', time()-86400);
        $endDate = date('Y-m-d H:i:s');
        $posts = $this->getCollection()
            ->addFieldToFilter('date',['from'=>$startDate, 'to'=>$endDate])
            ->setOrder('date', 'desc');
        return $posts;
    }

    private function _saveImage($name, $path)
    {
        $uploader = new Varien_File_Uploader('image');
        $uploader->setAllowedExtensions(array('jpg', 'jpeg'));
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);
        $uploader->save($path, $name);
        return $uploader->getUploadedFileName();
    }
}