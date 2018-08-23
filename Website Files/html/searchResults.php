<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Start a session that can hold session variables if they are successful.
session_start();

$body1 = "<!DOCTYPE html>
  <html lang='en'>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Results : ";
$body2 = "</title>

    <!-- Bootstrap -->
    <link href='css/bootstrap.min.css' rel='stylesheet'>
	<!-- Font Awesome-->
    <link rel='stylesheet' href='css/font-awesome.min.css'>
	<!--Create custom javascripts for this page  - Includes login Ajax function-->
	<script src='js/login.js'></script>
	<script src='js/searchResultsPage.js'></script>


  </head>

  <!--*********************************************-->
  <!--onload calls the java script when the webpage is loaded-->
  <body onload='loadFunctions()'>
      <!--Custom CSS-->
      <style type='text/css'> 
       /*Removes underline and set color to link as black*/
       a:link {color: #3FA0D6; text-decoration: none}      /* unvisited link */
       a:visited {color: black; text-decoration: none}   /* visited link */
       a:hover {color: #3FA0D6; text-decoration: none}     /* mouse over link */
       a:active {color: #3FA0D6; text-decoration: none} 
       .heading {
        font-size: 20px;
        font-weight: bold;
        display: block;
		
      }
      .paragraph{
        text-align: justify; 
        /*Indent paragraph text*/
        text-indent: 3em
      }
    </style>

    <!--Navigation-->
    <!--Add navbar-light bg-faded to make navbar visiable-->
    <nav class='navbar navbar-light bg-faded '>
      
    <div class='form-inline'>
    <!-- navBar Name-->
      <a class='navbar-brand tag' href='index.html''>WKU Wiki</a>
      <div class='btn-group navbar-toggler-right'>
        <!--Button for dropdown-->

        <button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' id='btnHolderName'><i class='fa fa-user-secret' aria-hidden='true'></i></button>
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
    
	<!-- Search Bar -->
    <div class='container mt-3'>
		<form name='search_box'>
			<!-- Center the childern -->
			<center>
				<!--Search Bar-->
				<div class='input-group box mt-3' name='search_box'>
				<input type='text' value='";
$body3 = 			"' id='searchBar' class='form-control' placeholder='Search' aria-describedby='basic-addon2' name='search' >
				  <span class='input-group-btn'>
                <button class='btn btn-secondary' type='button' onclick='searchBarClicked()''><i class='fa fa-search' aria-hidden='true'></i></button>
              </span>
				</div>
				
			</center>
			<span class = 'text-muted'><b>Search Results: </b>";
$body4 = 	" <b>Last Updated:</b> ";

$body5 = " </span>
			<span class = 'text-muted'></span>
        </form>
    </div>";
$body6	= "
	

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src='js/bootstrap.min.js'></script>

  </body>
  </html>";
  
$SQuery = "";
$info1 = "";
$info2 = "";
$info3 = "";
$info4 = "";
$info5 = "";
if(isset($_GET['search'])){
	$SQuery = $_GET['search'];
}

$info1 = $SQuery;
$info2 = $SQuery;
date_default_timezone_set("America/Chicago");
$info4 = date("F j, Y, h:i");

//**************************
//get page_id so we can use to match it in the subsection table
$count = 0;
$stmt = $conn->prepare("SELECT * FROM page WHERE title LIKE CONCAT('%',?,'%')");
$stmt->bind_param('s', $SQuery);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
	$count++;
	$descrip = "";
	$stmt2 = $conn->prepare('SELECT * FROM subsection WHERE page_id = ?');
	$stmt2->bind_param('s', $row["page_id"]);
	$stmt2->execute();
	$result2 = $stmt2->get_result();
	while ($row2 = $result2->fetch_assoc()) {
		$descrip = $row2["words"];
		break;
	}
	
	$info5 .= "<div class = 'container mt-3'><a href='viewPage.php?pageID=";
	$info5 .= $row["page_id"];
	$info5 .= "' class='heading mb-0'>";
	$info5 .= $row["title"];
	$info5 .= "</a><p>";
	$info5 .= $descrip;
	$info5 .= "</p></div>";
}
//**************************

$info3 = $count;

//Echo out all the parts
echo $body1.$info1.$body2.$info2.$body3.$info3.$body4.$info4.$body5.$info5.$body6;

?>