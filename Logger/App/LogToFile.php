<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 10:06
 */
namespace Logger\App;
use Logger\Core\LoggerInterface\LoggerAbstract;


class LogToFile extends LoggerAbstract
{
    const LOG_FILE = 'logs.txt';
    protected function _writeMsg($message, $type)
    {
        if(!$message)
        {
            return "Error! Empty message<br/>";
        }
        $message = date('Y-m-d H:i:s').' - '.' '.$type.': '.$message."\r\n";
        if(file_put_contents(self::LOG_FILE, $message, FILE_APPEND))
        {
            return "Written $type to the file ".self::LOG_FILE."<br/>";
        }
        else
        {
            return "Error writing to file<br/>";
        }
    }
}