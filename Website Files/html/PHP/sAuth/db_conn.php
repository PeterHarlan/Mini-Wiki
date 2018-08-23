<?php
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_dbconnection.php";

$dbConnection = false;

// Create connection
$conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}else{
	$dbConnection = true;
}
	
?>