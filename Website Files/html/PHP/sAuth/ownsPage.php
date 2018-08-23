<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Start a session that can hold session variables if they are successful.
session_start();
if(isset($_POST['pageID'])){
	$pg_id = $_POST['pageID'];
	$sess_username = $_SESSION['username'];
	$loggedin_id = "";
	$pageOwner_id = "";
	
	//Get userID of logged in User
	$stmt = $conn->prepare('SELECT * FROM account WHERE email = ?');
	$stmt->bind_param('i', $sess_username);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
		$loggedin_id = $row["user_id"];
		break;
	}
	
	//Get userID of page Owner
	$stmt = $conn->prepare('SELECT * FROM page WHERE page_id = ?');
	$stmt->bind_param('i', $pg_id);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
		$pageOwner_id = $row["user_id"];
		break;
	}
	
	if(isset($loggedin_id) && isset($pageOwner_id))
	{
		if($loggedin_id == $pageOwner_id){
			//Logged in user owns the page
			echo "1";
		}else{
			echo "2";
		}
	}
}
?>