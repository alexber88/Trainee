<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 10:06
 */
namespace Core\LoggerApplication;
use Core\LoggerAbstract\LoggerAbstract;


class LogToFile extends LoggerAbstract
{
    protected function _writeMsg($message, $type)
    {
        if(!$message)
        {
            return "Error! Empty message<br/>";
        }
        $message = date('Y-m-d H:i:s').' - '.' '.$type.': '.$message."\r\n";
        if(file_put_contents($this->file, $message, FILE_APPEND))
        {
            return "Written $type to the file $this->file<br/>";
        }
        else
        {
            return "Error writing to file<br/>";
        }
    }
}