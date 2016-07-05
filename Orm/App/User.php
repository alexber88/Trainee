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
    public function __construct(\PDO $connect)
    {
        parent::__construct($connect, 'user', 'id');
        $this->_data['add_date'] = date('Y-m-d H:i:s');
    }

    /**
     * Get user's name
     * @return string
     */
    public function getName()
    {
        return $this->_data['name'];
    }

    /**
     * Set user's name
     * @param string $name
     */
    public function setName($name)
    {
        $this->_data['name'] = $name;
    }

    /**
     * Get user's email
     * @return string
     */
    public function getEmail()
    {
        return $this->_data['email'];
    }

    /**
     * Set user's email
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_data['email'] = $email;
    }
}