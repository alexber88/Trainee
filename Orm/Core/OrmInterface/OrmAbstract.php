<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 01.07.16
 * Time: 10:47
 */



namespace Orm\Core\OrmInterface;
use Orm\Core\OrmInterface\OrmInterface;

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
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        foreach ($result as $field => $value)
        {
            $this->_data[$field] = $value;
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
     * Insert new row to the table
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
    }
}