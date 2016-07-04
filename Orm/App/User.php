<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 01.07.16
 * Time: 15:52
 */

namespace Orm\App;

use Orm\Core\OrmInterface\OrmAbstract;

class User extends OrmAbstract
{
    private $_email;
    private $_name;
    private $_addDate;

    public function __construct(\PDO $connect)
    {
        parent::__construct($connect, 'user', 'id');
    }

    /**
     * Get user's name
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set user's name
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Get user's email
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Set user's email
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    protected function setProperties($result)
    {
        $this->_name = $result['name'];
        $this->_email = $result['email'];
        $this->_addDate = $result['add_date'];
    }
    
    protected function getFieldsAndValues()
    {

        if($this->_id)
        {
            $user = [$this->_idField => $this->_id];
        }
        else
        {
            $user = ['add_date' => date('Y-m-d H:i:s')];
        }

        $user['name'] = $this->_name;
        $user['email'] = $this->_email;
        
        return $user;
    }

}