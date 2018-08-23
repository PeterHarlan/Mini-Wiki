<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

//Start a session that can hold login variables if they are successful.
session_start();


if(isset($_POST['email']) && isset($_POST['pass'])){
	//Bind post variables to local variables
	$email = $_POST['email'];
	$pass = $_POST['pass'];
	$accountLevel = 0;
	$loginSuccessful = false;
	$firstname = "";
	
	//Salt & hash inc password to compare to the password in DB
	$pass = $db_salt_1.$pass.$db_salt_2;
	$pass = hash('ripemd160', $pass);
	
	//Db connection exists in db_conn.php - this is a required file that connects to the DB
	if($dbConnection){
		//check if email already exists in the database
		$stmt = $conn->prepare('SELECT * FROM account WHERE email = ? AND password = ?');
		$stmt->bind_param('ss', $email, $pass);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$loginSuccessful=true;
			$accountLevel = $row["user_level"];
			$name = $row["first_name"];
			$userId = $row["user_id"];
			break;
		}
		
		if($loginSuccessful){
			//Post session variables
			$_SESSION['username'] = $email;
			$_SESSION['pass'] = $pass;
			$_SESSION['acclv'] = $accountLevel;
			$_SESSION['name'] = $name;
			$_SESSION['userid'] = $userId;
			echo "1";
		}else{
			//Login failed
			echo "2";
		}	
	}
}else{
	//Not all post variables present
	echo "3";
}

?>