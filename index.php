<?php
error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');

use Config\Connection;
use System\Router;
use System\Registry;


//use Logger\LogToDB;
//use Logger\LogToFile;
define('DS', DIRECTORY_SEPARATOR);
define('BASE_URL', 'http://loggertest.com');
require_once 'Autoload.php';

$autoload = new Autoload();
$autoload->register();

$namespaces = [
    'System' => 'Module'.DS.'System',
    'App' => 'App'.DS.'Controller',
    'Model' => 'App'.DS.'Model',
    'Config' => 'Config',
    'Logger' => 'Module'.DS.'Logger'.DS.'Core',
    'Orm' => 'Module'.DS.'Orm',
    'Lib' => 'Lib'
];

foreach ($namespaces as $namespace => $path)
{
    $autoload->addNamespace($namespace, $path);
}

$connection = new Connection();
$db_connect = $connection->getConnection();

Registry::setProperty('db_connect', $db_connect);

$router = new Router();
$router->start();



//------------Launch LOGGER-----------------------
//
//$logToFile = new LogToFile();
//$logToFile->notice('this is notice', LogToFile::NOTICE);
//$logToFile->warning('this is warning', LogToFile::WARNING);
//$logToFile->error('this is error', LogToFile::ERROR);
////
//$logToDb = new LogToDB($connect->getConnection());
//$logToDb->notice('this is notice', LogToDB::NOTICE);
//$logToDb->notice('this is warning', LogToDB::WARNING);
//$logToDb->notice('this is error', LogToDB::ERROR);



//--------------Launch ORM--------------------------


//$user1 = new User($connect->getConnection());
//$user1->load(29);
////
//
//$user1->setName('xxxxx');
//$user1->setEmail('xxx@dxxx.com');
//$user1->save();
//echo $user1->getId();
//$user1->setName('John1');
//$user1->setEmail('john.doe@test.com');
//
//$user1->save(); // new row added in db.
//$user1->delete();
//$user1->save();
//echo $user1->getName().'<br/>';
//echo $user1->getEmail().'<br/>';
//$user1->setName('alex');
//echo $user1->getName();
//echo $user1->getEmail();
//$user1->setEmail('new@new.com');
//$user1->save();
//echo $user1->getId();