<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 11.07.16
 * Time: 22:39
 */

namespace System;


class View
{
    private $_layout = 'layout';
    private $_header = 'header';
    private $_footer = 'footer';

    public function render($viewFileName, $data = null)
    {
        $this->_getHeader();
        include 'App/View/'.$this->_getLayout().'.html';
        $this->_getFooter();

    }

    private function _getLayout()
    {
        return $this->_layout;
    }

    private function _getHeader()
    {
        include 'App/View/'.$this->_header.'.html';
    }
    
    private function _getFooter()
    {
        include 'App/View/'.$this->_footer.'.html';
    }

}