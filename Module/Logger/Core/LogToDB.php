<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 10:07
 */
namespace Logger;
use Logger\LoggerInterface\LoggerAbstract;


class LogToDB extends LoggerAbstract
{
    public function __construct(\PDO $connect)
    {
        if(!isset($this->connection))
        {
            $this->connection = $connect;
        }
    }

    protected function _writeMsg($message, $type)
    {
        if($message)
        {
            $statement = $this->connection->prepare("INSERT INTO `log` (`message`, `type`, `creation_date`) values (?, ?, ?)");
            $statement->execute([$message, $type, date('Y-m-d H:i:s')]);
        }
    }

    public function __destruct()
    {
        $this->connection = null;
    }
}