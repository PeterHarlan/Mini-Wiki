<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

//Start a session that can hold login variables if they are successful.
session_start();

/*echo "<!DOCTYPE html>
	<html>
	<head>
	<title>Register</title>
	</head>
	<body>
	<form method='post' action='register.php'>
	  Firstname:<br>
	  <input type='text' name='fname'><br>
	  Lastname:<br>
	  <input type='text' name='lname'><br>
	  Email:<br>
	  <input type='text' name='email'><br>
	  Password:<br>
	  <input type='text' name='password'><br>
	  <input type='submit' value='Register'>
	</form>

	</body>
	</html>"*/;
if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['password'])){
	//Bind post variables to local variables
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$accExists = false;
	$accountLevel = 0;
	
	//Db connection exists in db_conn.php - this is a required file that connects to the DB
	if($dbConnection){
		//check if email already exists in the database
		$stmt = $conn->prepare('SELECT * FROM account WHERE email = ?');
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$accExists=true;
			break;
		}
		if($accExists){
			Echo "2";
		}else{
			//Create the account
			//Salt the password
			$password = $db_salt_1.$password.$db_salt_2;
			//RIPEMD Hash
			$password = hash('ripemd160', $password);
			//Insert into DB
			//prepare and bind
			$stmt = $conn->prepare("INSERT INTO account (first_name, last_name, email, user_level, password) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("sssss", $fname, $lname, $email, $accountLevel, $password);
			//execute
			$stmt->execute();
			
			
			//Get User ID from the db so we can auto login and store the id for page creation
			//in the session variables.
			//**************************
			$stmt = $conn->prepare('SELECT * FROM account WHERE email = ? AND password = ?');
			$stmt->bind_param('ss', $email, $password);
			$stmt->execute();
			
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$user_id = $row["user_id"];
				break;
			}
			//**************************
			$user_id = "";
			
			
			$_SESSION['userid'] = $user_id;
			$_SESSION['username'] = $email;
			$_SESSION['pass'] = $password;
			$_SESSION['acclv'] = $accountLevel;
			$_SESSION['name'] = $fname;
			
			echo "1";	
		}	
	}
}else{
	echo "3";
}

?>