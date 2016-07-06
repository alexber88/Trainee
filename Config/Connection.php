<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 01.07.16
 * Time: 9:55
 */


namespace Config;

class Connection
{
    private $_dsn = '';
    private $_user = '';
    private $_pass = '';

    private $_connection;


    public function __construct()
    {
        $dbInfo = $this->_getDbInfo();
        $this->_dsn = "mysql:dbname={$dbInfo['dbName']};host={$dbInfo['host']}";
        $this->_user = $dbInfo['user'];
        $this->_pass = $dbInfo['password'];
        try
        {
            if(!isset($this->_connection))
            {
                $this->_connection = new \PDO($this->_dsn, $this->_user, $this->_pass);
            }
        }
        catch (\PDOException $e)
        {
            echo 'Unable to connect: ' . $e->getMessage();
        }

    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function __destruct()
    {
        $this->_connection = NULL;
    }

    private function _getDbInfo()
    {
        return require_once 'db-info.php';
    }
}