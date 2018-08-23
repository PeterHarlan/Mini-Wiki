<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Start a session that can hold session variables if they are successful.
session_start();

$userID = "";
$pgOwnerID="";
$foundUserID=false;
$everythingOK=false;

$body0 = "   <!DOCTYPE html>
				   <html lang='en'>
				   <head>
					<meta charset='utf-8'>
					<meta http-equiv='X-UA-Compatible' content='IE=edge'>
					<meta name='viewport' content='width=device-width, initial-scale=1'>
					<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
					<title>Edit Page</title>

					<!-- Bootstrap -->
					<link href='css/bootstrap.min.css' rel='stylesheet'>

					<!--Create custom javascripts for this page  - Includes createPage Ajax function-->
					<script src='js/login.js'></script>
					<script src='js/editpage.js'></script>
				  </head>

				  <!--*********************************************-->
				  <!--onload calls the java script when the webpage is loaded-->
				  <body onload='loadFunctions()'>
					<!--Custom CSS-->
					<!-- <style type='text/css'> </style> -->

					<!--Navigation-->
					<!--Add navbar-light bg-faded to make navbar visiable-->
					<nav class='navbar align-items-end navbar-light bg-faded'>
					  <!-- navBar Name-->
					  <!-- <a class='navbar-brand' href='#'>WKU Wiki</a> -->

					  <div class='btn-group ml-auto'>
						<!--Button for dropdown-->

						<button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' id='btnHolderName'></button>
						<div class='dropdown-menu dropdown-menu-right'>
						  <div class = 'mx-2' >
							<input  class='dropdown-item form-control' type='text' id='Email' placeholder='email'>
							<input  class='dropdown-item form-control' type='password' id='Password' placeholder='password' >
							<button class='dropdown-item ' type='button' id='LogIn' onclick='submitLogin()'>Log In</button>
							<button class='dropdown-item' type='button' id='SignUp' onclick='signUp()'>Sign Up</button>
							<button class='dropdown-item' type='button' id='CreatePage' onclick='createPageLink()' style='display: none;'>Create Page</button>
							<button class='dropdown-item example' type='button' id='SignOut' style='display: none;' onclick='logOut()'>Sign Out</button>
						  </div>
						</div>
					  </div>
					</nav>

					  <div class='container-fluid d-flex'>
						<!--Align the child container to the parent-->

						<div class='container mt-3' >
						  <form>
							<div class='form-group'>
							  <div class='row'>
								<div class='col col-sm-12'>
								  <div class='mt-3'><h1><b>Page Subject</b></h1></div>
								  <input type='text' class='form-control mb-2' id='pageSubject' placeholder='Page Subject' value='";
								  
$body1 = 						"'>
								</div>
								<button type='button' onClick='addField()' class='btn btn-light btn-sm float-right mt-1'><b> + </b></button>
								<div class='col-sm-12' id='pageFields'>";
								
$body2 = 						"</div>
								<div class='col-sm-12'>
								  <h2>Tags</h2>
								  <div class='form-group floating-label-form-group controls mt-2'>
									<textarea class='form-control' id='contentTags' placeholder='Relevant Tags'>";

$body3 = 							"</textarea>
									<small id='tagHelp' class='form-text text-muted'>Seperate relevant topics with a comma (i.e. 'building, Helm Library').</small>
								  </div>
								</div>
										
								<button type='button' onClick='submitPage()' class='btn btn-light btn-sm float-right mt-1'><b> Submit </b></button>
								<input type='hidden' id='hiddenStarterPHPCount' value='";
$body4 = 						"' />
								<input type='hidden' id='pageIDHolder' value='";
$body5 = "						 ' /> </div>
							</div>
						  </form>
						</div><!--container mt-3-->
					  </div>
					  
					<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
					<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
					<!-- Include all compiled plugins (below), or include individual files as needed -->
					<script src='js/bootstrap.min.js'></script>

				  </body>
				  </html>";

	if(isset($_GET['pageID'])){
		$pageID = $_GET['pageID'];
		$sess_username = $_SESSION['username'];
		if(isset($sess_username) && $sess_username != ""){
			//Logged in -- get the user's id
			$stmt = $conn->prepare('SELECT * FROM account WHERE email = ?');
			$stmt->bind_param('s', $sess_username);
			$stmt->execute();
			
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$userID = $row["user_id"];
				$foundUserID = true;
				break;
			}
			
			if($foundUserID){
				//Make sure it matches the page's owner's id
				$stmt = $conn->prepare('SELECT * FROM page WHERE page_id = ?');
				$stmt->bind_param('i', $pageID);
				$stmt->execute();
				
				$result = $stmt->get_result();
				while ($row = $result->fetch_assoc()) {
					if($row["user_id"]==$userID){
						$everythingOK=true;
					}
					break;
				}
				
				if($everythingOK){
					//The user ID and pageOnwerID matches -- the logged in user owns the page
					$pageSubject = "";
					$tags = "";
					
					//Get Page Subject && Tags
					$stmt = $conn->prepare('SELECT * FROM page WHERE page_id = ?');
					$stmt->bind_param('i', $pageID);
					$stmt->execute();
					
					$result = $stmt->get_result();
					while ($row = $result->fetch_assoc()) {
						$pageSubject = $row["title"];
						$tags = $row["tags"];
						echo $body0;
						echo $pageSubject;
						echo $body1;
						break;
					}
					
					//Load the Topics
					//Get Page Subject
					$x = 0;
					$stmt = $conn->prepare('SELECT * FROM subsection WHERE page_id = ?');
					$stmt->bind_param('i', $pageID);
					$stmt->execute();
					
					$result = $stmt->get_result();
					while ($row = $result->fetch_assoc()) {
						$x++;
						//$pageSubject = $row["title"];
						$newEntry = "<div class='col-sm-12 mt-3'>
								  <h2>Topic ".$x."</h2>
								  <input type='text' class='form-control' id='supportingTopic".$x."' value='".$row["section_name"]."' placeholder='Supporting Topic'>
								  <div class='form-group floating-label-form-group controls mt-2'>
									  <!-- <textarea rows='5' class='form-control' placeholder='Message' id='message' required data-validation-required-message='Please enter a message.'></textarea>
										<p class='help-block text-danger'></p> -->
									  <textarea class='form-control' id='supportingContent".$x."' placeholder='Supporting Content'>".$row["words"]."</textarea>
								  </div>
								</div>";
						echo $newEntry;
					}
					
					echo $body2;
					echo $tags;
					echo $body3;
					echo $x;
					echo $body4;
					echo $pageID;
					echo $body5;
				}else{
					//Invalid Owner
					header("Location: index.html"); /* Redirect browser */
					exit();
				}
			}else{
				//Not Logged in
				header("Location: index.html"); /* Redirect browser */
				exit();
			}
		}else{
			//Not Logged in
			header("Location: index.html"); /* Redirect browser */
			exit();
		}
	}else{
		echo "Invalid Page ID";
	}


?>