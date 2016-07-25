<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 21.07.16
 * Time: 18:07
 */

namespace Lib;

class Pagination
{
    public function createPages($config)
    {
        $pageCount = ceil($config['count']/$config['perPage']);

        $requestUri = $_SERVER['REQUEST_URI'];

        if($requestUri[strlen($requestUri)-1] != '/')
        {
            $requestUri .= '/';
        }

        if($config['currentPage'] > 1)
        {
            $requestUri = stristr($requestUri, 'page/'.$config['currentPage'].'/', TRUE);
        }

        $html = '<div class="pagination">';
        $prev = $config['currentPage'] - 1;
        if($prev > 0)
        {
            if($prev == 1)
            {
                $html .= '<span><a href="'.$requestUri.'"> &larr; </a></span>';
            }
            else
            {
                $html .= '<span><a href="'.$requestUri.'page/'.($config['currentPage']-1).'"> &larr; </a></span>';
            }

        }
        for($i = 1; $i<=$pageCount; $i++)
        {

            if($i == $config['currentPage'])
            {
                $html .= '<span>'.$i.'</span>';
            }
            elseif($i == 1)
            {
                $html .= '<span><a href="'.$requestUri.'">'.$i.'</a></span>';
            }
            else
            {
                $html .= '<span><a href="'.$requestUri.'page/'.$i.'">'.$i.'</a></span>';
            }

        }
        if($config['currentPage'] + 1 <= $pageCount)
        {
            $html .= '<span><a href="'.$requestUri.'page/'.($config['currentPage']+1).'"> &rarr; </a></span>';
        }
        $html .= '</div>';
        return $html;
    }
}