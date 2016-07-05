<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 10:06
 */
namespace Logger;
use Logger\LoggerInterface\LoggerAbstract;


class LogToFile extends LoggerAbstract
{
    const LOG_FILE = 'logs.txt';
    protected function _writeMsg($message, $type)
    {
        if($message)
        {
            $message = date('Y-m-d H:i:s').' - '.' '.$type.': '.$message."\r\n";
            file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
        }
    }
}