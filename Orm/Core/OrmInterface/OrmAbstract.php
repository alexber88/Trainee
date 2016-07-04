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
    private $_idField;
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

        if(isset($entity[$this->_idField]) && $entity[$this->_idField] != null)
        {

            $id = $entity[$this->_idField];
            unset($entity[$this->_idField]);
            $data = [];
            foreach ($entity as $field => $value)
            {
                $data[] = $field.' = ?';
            }
            $values = implode(', ', $data);
            $query = "UPDATE $this->_tableName SET $values WHERE $this->_idField = ?";
            $statement = $this->_connect->prepare($query);
            $i = 1;
            foreach ($entity as &$value)
            {
                $statement->bindParam($i, $value);
                $i++;
            }
            $statement->bindParam($i, $id);
            $statement->execute();

        }
        else
        {
            $fields = implode(', ', array_keys($entity));
            $values = implode(', ', array_fill(0, count($entity), '?'));
            $query = "INSERT INTO $this->_tableName($fields) VALUES($values)";
            $statement = $this->_connect->prepare($query);
            $i = 1;
            foreach ($entity as &$value)
            {
                $statement->bindParam($i, $value);
                $i++;
            }

            $statement->execute();

            $this->_id = $this->_connect->lastInsertId();
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