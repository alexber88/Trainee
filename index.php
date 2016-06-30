<?php
error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');
require_once 'Autoload.php';

use Core\LoggerApplication\LogToFile;
use Core\LoggerApplication\LogToDB;

$logToFile = new LogToFile();
$logToFile->notice('this is notice', LogToFile::NOTICE);
$logToFile->warning('this is warning', LogToFile::WARNING);
$logToFile->error('this is error', LogToFile::ERROR);

$logToDb = new LogToDB();
$logToDb->notice('this is notice', LogToDB::NOTICE);
$logToDb->notice('this is warning', LogToDB::WARNING);
$logToDb->notice('this is error', LogToDB::ERROR);