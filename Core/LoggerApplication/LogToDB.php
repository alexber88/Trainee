<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 10:07
 */
namespace Core\LoggerApplication;
use Core\LoggerAbstract\LoggerAbstract;
use Config\ConnectDB as ConnectDB;

class LogToDB extends LoggerAbstract
{
    public function __construct()
    {
        if(!isset($this->connection))
        {
            $connect = new ConnectDB();
            $this->connection = $connect->connection;
        }
    }

    protected function _writeMsg($message, $type)
    {
        if(!$message)
        {
            return "Error! Empty message<br/>";
        }
        $statement = $this->connection->prepare("INSERT INTO `log` (`message`, `type`, `creation_date`) values (?, ?, ?)");

        if($inserted = $statement->execute([$message, $type, date('Y-m-d H:i:s')]))
        {
            return "$type was added to DB<br/>";
        }
        else
        {
            return "Error writing $type to DB<br/>";
        }
    }
}