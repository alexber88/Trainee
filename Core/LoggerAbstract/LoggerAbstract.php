<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 9:26
 */
namespace Core\LoggerAbstract;
use Core\LoggerInterface\LoggerInterface;

abstract class LoggerAbstract implements LoggerInterface
{
    public $file = 'Logs/Logs.txt';
    public $result = '';
    abstract protected function _writeMsg($message, $type);

    public function notice($message, $type)
    {
        $this->result = $this->_writeMsg($message, $type);
        echo $this->result;
    }

    public function warning($message, $type)
    {
        $this->result = $this->_writeMsg($message, $type);
        echo $this->result;
    }

    public function error($message, $type)
    {
        $this->result = $this->_writeMsg($message, $type);
        echo $this->result;
    }
}
