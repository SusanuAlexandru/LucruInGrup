<?php
require_once "./app/DatabaseConnectionInterface.php";
require_once "./app/ElevRepositoryInterface.php";
require_once "./app/DatabaseConnection.php";
require_once "./app/ElevRepository.php";

$config = require_once './config.php';
$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

$databaseConnection = new DatabaseConnection($dsn, $username, $password);
   
?>
