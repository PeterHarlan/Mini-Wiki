<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Start a session that can hold session variables if they are successful.
session_start();

$pg_id = 0;
$pg_title = "";
$Body = "";

if($dbConnection){
	if(isset($_GET['pageID'])){
		//Get page id from Get
		$pg_id = $_GET["pageID"];
		
		//Load and populate the page title
		$stmt = $conn->prepare('SELECT * FROM page WHERE page_id = ?');
		$stmt->bind_param('i', $pg_id);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$pg_title = $row["title"];
			break;
		}
		
		//Load and populate the body
		$stmt = $conn->prepare('SELECT * FROM subsection WHERE page_id = ?');
		$stmt->bind_param('i', $pg_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$count = 0;
		while ($row = $result->fetch_assoc()) {
			$count++;
			$value_Title = $row["section_name"];
			$value_Body = $row["words"];
			
			$topic_title = "<div class='card'>
							  <div class='card-header'>
								<a data-toggle='collapse' href='#card".$count."' class='heading mb-0'>".$value_Title."</a>
							  </div>
							</div>";
			$topic_body = "<div id='card".$count."' class='container collapse'>
							  <p class='paragraph'>".$value_Body."</p>
						   </div>";
						   
			//Add both to the generated body
			$Body .= $topic_title.$topic_body;
		}
		
		
		//Increase Page Count
		$stmt = $conn->prepare('UPDATE page SET views = views + 1 WHERE page_id = ?');
		$stmt->bind_param('i', $pg_id);
		$stmt->execute();
		
	}else{
		Echo "No Valid Page ID";
	}
}

echo"<!DOCTYPE html>
  <html lang='en'>
  <head>
  <!--Create custom javascripts for this page  - Includes login Ajax function-->
    <script src='js/login.js'></script>
    <script src='js/viewPage.js'></script>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Page Information</title>

    <!-- Bootstrap -->
    <link href='css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='css/font-awesome.min.css'>
  </head>

  <!--*********************************************-->
  <!--onload calls the java script when the webpage is loaded-->
  <body onload='loadFunctions()'>
      <!--Custom CSS-->
      <style type='text/css'> 

       /*Removes underline and set color to link as black*/
       a:link {color: black; text-decoration: none}      /* unvisited link */
       a:visited {color: black; text-decoration: none}   /* visited link */
       a:hover {color: black; text-decoration: none}     /* mouse over link */
       a:active {color: black; text-decoration: none} 
       .heading {
        font-size: 32px;
        font-style: italic;
        display: block;
      }
      .paragraph{
        text-align: justify; 
        /*Indent paragraph text*/
        text-indent: 3em
      }
      @media only screen and (max-width: 768px) {
    .box{
      /*background-color: lightblue;*/
      width: 60%
    }
    .tag{
      /*background-color: lightblue;*/
      width: 60%;
      display: none;
    }
  }

    </style>

    <!--Navigation-->
    <nav class='navbar navbar-light bg-faded'>
  <div class='form-inline'>
   <a class='navbar-brand tag' href='index.html''>WKU Wiki</a>
    <div>
      <form class='form-inline' name = 'search_box' method='get' action='searchResults.php'>
        <div class='input-group box ' name='search'>
          <input type='text' class='form-control' placeholder='Search' aria-describedby='basic-addon2' name='search' >
          <span class='input-group-btn'>
                <button class='btn btn-secondary' type='button' onclick='searchBarClicked()'><i class='fa fa-search' aria-hidden='true'></i></button>
              </span>
        </div>
      </form>
    </div>
    <div class='btn-group navbar-toggler-right'>
      <!--Button for dropdown-->
      
      <button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' id='btnHolderName'></button>
      <div class='dropdown-menu dropdown-menu-right'>
        <div class = 'mx-2' >
          <input  class='dropdown-item form-control' type='text' id='Email' placeholder='email'>
          <input  class='dropdown-item form-control' type='password' id='Password' placeholder='password' >
          <button class='dropdown-item ' type='button' id='LogIn' onclick='submitLogin()'>Log In</button>
          <button class='dropdown-item' type='button' id='SignUp' onclick='signUp()'>Sign Up</button>
          <button class='dropdown-item' type='button' id='CreatePage' onclick='createPageLink()' style='display: none;'>Create Page</button>

          <button class='dropdown-item example' type='button' id='EditPage' style='display: none;' onclick='redirEditPage()'>Edit Page</button>
          <button class='dropdown-item example' type='button' id='SignOut' style='display: none;' onclick='logOut()'>Sign Out</button>
        </div>
      </div>
    </div>
  </div>
</nav>

    <!-- 
    !!!Notes!!!
    Make sure you rename href in the header and the id in the body
    both 'xxx' should be replaced
    'Header Text' should be replaced by the name of the header

    This is the heading 
    <div class='card'>
      <div class='card-header'>
        <a data-toggle='collapse' href='#xxx' class='heading mb-0'>Header Text</a>
      </div>
    </div>

    Body Begins here
    <div id='xxx' class='container collapse'>
      <p class='paragraph'>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of 'de Finibus Bonorum et Malorum' (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, 'Lorem ipsum dolor sit amet..', comes from a line in section 1.10.32.</p>
    </div> 
    -->

    <!--Topic header-->
    <h1 id='pg_title'><b>".$pg_title."</b></h1>
    <div class='container mt-3'>
    </div>
	".$Body."<input type='hidden' id='pageIDHolder' value='".$pg_id."' /> 

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src='js/bootstrap.min.js'></script>
    

  </body>
  </html>";
?>