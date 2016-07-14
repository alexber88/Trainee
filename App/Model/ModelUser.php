<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 12.07.16
 * Time: 15:11
 */

namespace Model;

use System\AbstractModel;
//use Orm\Model\User;


class ModelUser extends AbstractModel
{
    public function checkIfUserExist($email, $password)
    {
        $password = sha1($password);
        $query = "SELECT id FROM `user` WHERE email = :email AND password = :pass";
        $sth = $this->_connection->prepare($query);
        $sth->bindParam(':email', $email);
        $sth->bindParam(':pass', $password);
        $sth->execute();
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        return $res['id'];
    }
}