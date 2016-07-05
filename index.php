<?php
error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');
require_once 'Autoload.php';
use Config\Connection;
$connect = new Connection();
//------------Launch LOGGER-----------------------

//use Logger\App\LogToFile;
//use Logger\App\LogToDB;
//
//$logToFile = new LogToFile();
//$logToFile->notice('this is notice', LogToFile::NOTICE);
//$logToFile->warning('this is warning', LogToFile::WARNING);
//$logToFile->error('this is error', LogToFile::ERROR);
//
//$logToDb = new LogToDB($connect->connection);
//$logToDb->notice('this is notice', LogToDB::NOTICE);
//$logToDb->notice('this is warning', LogToDB::WARNING);
//$logToDb->notice('this is error', LogToDB::ERROR);



//--------------Launch ORM--------------------------

use Orm\App\User;

$user1 = new User($connect->connection);
$user1->load(17);
//

$user1->setName('wrwewrwrr');
$user1->setEmail('werwer@dfwfw.com');
$user1->save();
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



