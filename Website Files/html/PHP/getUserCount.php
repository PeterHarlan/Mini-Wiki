<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

session_start();

$count = 0;

if($dbConnection){
	$fakeNum = 1;
	
	//PREPARE
	$stmt = $conn->prepare("SELECT COUNT(*) FROM account WHERE 1 = ?");
	//BIND
	$stmt->bind_param("i", $fakeNum);
	//EXECUTE
	$stmt->execute();
	
	$col1 = null;
	$stmt->bind_result($col1);
	
	while ($stmt->fetch()) {
		$count = "{$col1}";
	}
	
	echo $count;
}
?>