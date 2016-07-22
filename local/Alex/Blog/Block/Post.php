<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 15.07.16
 * Time: 17:41
 */

class Alex_Blog_Block_Post extends Mage_Core_Block_Template
{
    public function getAllPosts()
    {
        $column = 'date';
        $order = 'asc';

        $columns = ['first_name', 'date'];

        $sortArr = [];

        $params = $this->getRequest()->getParams();

        if(isset($params['order'], $params['column']))
        {
            $column = $params['column'];
            $order = $params['order'];
        }

        if(in_array($column, $columns))
        {
            if($order == 'asc')
            {
                $sortArr[$column]['href'] = 'order/desc/column/'.$column;
                $sortArr[$column]['arrow'] = '&#9660;';
            }
            else
            {
                $sortArr[$column]['href'] = 'order/asc/column/'.$column;
                $sortArr[$column]['arrow'] = '&#9650;';
            }
            unset($columns[array_search($column, $columns)]);
            foreach($columns as $col)
            {
                $sortArr[$col]['href'] = 'order/asc/column/'.$col;
                $sortArr[$col]['arrow'] = '&#9650;';
            }
        }
        ksort($sortArr);

        $posts = Mage::getModel('blog/post')->getAllPosts($column, $order);
        return ['posts'=>$posts, 'sort' => $sortArr];
    }

    public function getPost()
    {
        $post = Mage::registry('post');
        return $post;
    }

}