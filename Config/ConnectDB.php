<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 0:22
 */

namespace Config;

class ConnectDB
{
    private $_dbh = '';
    private $_user = '';
    private $_password = '';
    public function __construct()
    {
        $dbInfo = $this->getDatabaseInfo();

        $this->_dbh = 'mysql:dbname='.$dbInfo['dbName'].';host='.$dbInfo['host'];
        $this->_user = $dbInfo['user'];
        $this->_password = $dbInfo['password'];

        try
        {
            if(!isset($this->connection)){
                $this->connection = new \PDO($this->_dbh, $this->_user, $this->_password);
            }

        }
        catch (\PDOException $e)
        {
            echo 'Unable to connect to DB '. $e->getMessage();
        }

    }

    private function getDatabaseInfo()
    {
        $dbInfo = require_once 'Config/db-info.php';
        return $dbInfo;
    }
}