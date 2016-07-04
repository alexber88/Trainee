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
    private $_tableName;
    private $_connect;
    private $_deleted = FALSE;
    protected $_idField;
    protected $_id;
    
    public function __construct(\PDO $connect, $tableName, $idField)
    {
        $this->_connect = $connect;
        $this->_tableName = $tableName;
        $this->_idField = $idField;
    }

    /**
     * Set properties to object as table fields
     * @param $result array
     * @return void
     */
    abstract protected function setProperties($result);


    /**
     * Get properties, named as table fields, from the current object
     * @return array
     */
    abstract protected function getFieldsAndValues();


    /**
     * Find record in table by id
     * @param $id int
     * @return array
     */
    private function _getById($id)
    {
        $query = "SELECT * FROM $this->_tableName WHERE $this->_idField = :id";
        $statement = $this->_connect->prepare($query);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $this->_id = $result[$this->_idField];
        return $result;
    }


    /**
     * Delete record from table by id
     * @param $id int
     * @return void
     */
    private function _delById($id)
    {
        $query = "DELETE FROM $this->_tableName WHERE $this->_idField = :id";
        $statement = $this->_connect->prepare($query);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $this->_id = null;
        $this->_deleted = TRUE;
    }

    public function load($id)
    {
        $result = $this->_getById($id);
        $this->setProperties($result);
    }

    public function getId()
    {
        if($this->_id)
        {
            return $this->_id;
        }
        else
        {
            return null;
        }
    }

    public function save()
    {
        $entity = $this->getFieldsAndValues();
        $data = [];

        if(isset($entity[$this->_idField]) && $entity[$this->_idField] != null)
        {
            foreach ($entity as $field => $value)
            {
                $data[] = $field.' = :'.$field;
            }
            $values = implode(', ', $data);
            $query = "UPDATE $this->_tableName SET $values WHERE $this->_idField = :id";
        }
        elseif(!$this->_deleted)
        {
            $fields = implode(', ', array_keys($entity));
            foreach ($entity as $field => $value)
            {
                $data[] = ':'.$field;
            }
            $values = implode(', ', $data);
            $query = "INSERT INTO $this->_tableName($fields) VALUES($values)";
        }
        if(isset($query))
        {
            $statement = $this->_connect->prepare($query);
            foreach ($entity as $field => &$value)
            {
                $statement->bindParam(':'.$field, $value);
            }
            $statement->execute();
            if(!$this->_id)
            {
                $this->_id = $this->_connect->lastInsertId();
            }
        }

    }

    public function delete()
    {
        if($this->_id)
        {
            $this->_delById($this->_id);
        }
    }

}