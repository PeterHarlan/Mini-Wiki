<?php
//Start a session that can hold login variables if they are successful.
session_start();
			
$sess_username = $_SESSION['username'];
$sess_pass = $_SESSION['pass'];
$sess_acclv = $_SESSION['acclv'];
$sess_name = $_SESSION['name'];

//Use '~~' as a delimiter to deliver the session variables
echo $sess_username."~~".$sess_pass."~~".$sess_acclv."~~".$sess_name;
?>