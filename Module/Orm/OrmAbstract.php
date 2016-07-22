<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 01.07.16
 * Time: 10:47
 */



namespace Orm;
use Orm\OrmInterface;

abstract class OrmAbstract implements OrmInterface
{
    protected $_data = [];
    private $_tableName;
    private $_connect;
    protected $_idField;

    /**
     * OrmAbstract constructor. Set DB connection, table name and the name of primary key field
     * @param \PDO $connect
     * @param string $tableName
     * @param string $idField
     */
    public function __construct(\PDO $connect, $tableName, $idField)
    {
        $this->_connect = $connect;
        $this->_tableName = $tableName;
        $this->_idField = $idField;
    }

    /**
     * @inheritdoc
     */
    public function load($id)
    {
        $this->_getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        if($this->_data[$this->_idField])
        {
            return $this->_data[$this->_idField];
        }
        else
        {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        if(isset($this->_data[$this->_idField]) && !empty($this->_data))
        {
            $this->_updateRow();
        }
        elseif(!isset($this->_data[$this->_idField]) && !empty($this->_data))
        {
            $this->_insertRow();
        }
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        if($this->_data[$this->_idField])
        {
            $this->_delById($this->_data[$this->_idField]);
        }
    }

    public function __call($name, $argument = null)
    {
        $method = substr($name, 0, 3);
        $fieldName = substr($name, 3);
        preg_match_all('/[A-Z][^A-Z]*/', $fieldName, $result);
        $matches = $result[0];
        $field = implode('_', array_map(function ($val){
            return strtolower($val);
        }, $matches));

        if($method == 'get')
        {
            if(array_key_exists($field, $this->_data))
            {
                return $this->_data[$field];
            }
        }
        elseif ($method == 'set' && count($argument) == 1)
        {
            $this->_data[$field] = $argument[0];
        }
    }


    /**
     * Find record in the table by id
     * @param $id int
     * @return void
     */
    private function _getById($id)
    {
        $query = "SELECT * FROM $this->_tableName WHERE $this->_idField = :id";
        $statement = $this->_connect->prepare($query);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        if($result = $statement->fetch(\PDO::FETCH_ASSOC))
        {
            foreach ($result as $field => $value)
            {
                $this->_data[$field] = $value;
            }
        }
    }

    /**
     * Delete record from the table by id
     * @param $id int
     * @return void
     */
    private function _delById($id)
    {
        $query = "DELETE FROM $this->_tableName WHERE $this->_idField = :id";
        $statement = $this->_connect->prepare($query);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $this->_data = [];
    }


    /**
     * Insert new row into the table
     *
     * @return void
     */
    private function _insertRow()
    {
        $fieldNames = array_keys($this->_data);
        $fields = implode(', ', $fieldNames);
        $values = implode(', ', array_map(function ($field){
            return ':'.$field;
        }, $fieldNames));
        $query = "INSERT INTO $this->_tableName($fields) VALUES($values)";
        $this->_prepareAndExecute($query);
        $this->_data[$this->_idField] = $this->_connect->lastInsertId();
    }

    /**
     * Update row in the table
     *
     * @return void
     */
    private function _updateRow()
    {
        $fieldNames = array_keys($this->_data);
        $values = implode(', ', array_map(function ($field){
            if($field != $this->_data[$this->_idField])
            {
                return $field. '= :'.$field;
            }

        }, $fieldNames));
        $query = "UPDATE $this->_tableName SET $values WHERE $this->_idField = :id";
        $this->_prepareAndExecute($query);
    }

    /**
     * Prepare and execute query
     *
     * @return void
     */
    private function _prepareAndExecute($query)
    {
        $statement = $this->_connect->prepare($query);
        foreach ($this->_data as $field => &$value)
        {
            $statement->bindParam(':'.$field, $value);
        }

        $statement->execute();
//        $arr = $statement->errorInfo();
//        print_r($arr);
//        die;
    }
}