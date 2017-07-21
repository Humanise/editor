<?php
require_once("inc.php");

$host = Request::getString('databaseHost');
$name = Request::getString('databaseName');
$user = Request::getString('databaseUser');
$password = Request::getString('databasePassword');

if (!Database::testServerConnection($host,$user,$password)) {
  Response::sendObject(['server'=>false,'database'=>false]);
} else if (!Database::testDatabaseConnection($host,$user,$password,$name)) {
  Response::sendObject(['server'=>true,'database'=>false]);
} else {
  Response::sendObject(['server'=>true,'database'=>true]);
}
?>