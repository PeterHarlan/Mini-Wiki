<?php
//Need to take in page Subject and send it to page table
	//Updated page_id to auto increment as primary key
//Store subsection data (Topics, supporting topic, supporting content and tags) in subsection table.
//Added field for tags in db under page
//Made sec_id auto increment

require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Start a session that can hold session variables if they are successful.
session_start();

if(isset($_POST['pageSubject']) && isset($_POST['pageSupportingTopic']) && isset($_POST['pageSupportingContent']) && isset($_POST['pageSupportingTags']) && isset($_POST['pageID'])){
	$page_subject = $_POST['pageSubject'];
	$page_topic = $_POST['pageSupportingTopic'];
	$page_content = $_POST['pageSupportingContent'];
	$page_tags = $_POST['pageSupportingTags'];
	$user_id = "";
	$page_id = $_POST['pageID'];
	if($dbConnection){
		//The js already verifies the user is logged in, so we don't have to do it here too
		//Get the user id from session variables
		$user_id = $_SESSION['userid'];
		if(isset($user_id)){
			//They're logged in
			
			//DELETE PREVIOUS DATA -- Page table
			$stmt = $conn->prepare('DELETE FROM page WHERE page_id = ?');
			$stmt->bind_param('s', $page_id);
			$stmt->execute();
			
			//DELETE PREVIOUS DATA -- Subsection table
			$stmt = $conn->prepare('DELETE FROM subsection WHERE page_id = ?');
			$stmt->bind_param('s', $page_id);
			$stmt->execute();
			
			//**************************
			//Store information in the page table
			//PREPARE
			$stmt = $conn->prepare("INSERT INTO page (title, user_id, tags) VALUES (?, ?, ?)");
			//BIND
			$stmt->bind_param("sss", $page_subject, $user_id, $page_tags);
			//EXECUTE
			$stmt->execute();
			//**************************
			
			
			
			//**************************
			//get page_id so we can use to match it in the subsection table
			$stmt = $conn->prepare('SELECT * FROM page WHERE user_id = ? AND title = ?');
			$stmt->bind_param('ss', $user_id, $page_subject);
			$stmt->execute();
			
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$loginSuccessful=true;
				$page_id = $row["page_id"];
				break;
			}
			//**************************
			
			
			//**************************
			//Store information in subsection table
			//version is 0 beacuse this is create page
			$page_version = "0";
			$counter = 0;
			$arr1 = explode('1o0ajdksaxidgfau',$page_topic);
			$arr2 = explode('1o0ajdksaxidgfau',$page_content);
			foreach ($arr1 as $value) {
				if($value!=""){
					$sql = "INSERT INTO subsection (section_name, version, words, page_id) VALUES (?, ?, ?, ?)";
					//PREPARE
					$stmt = $conn->prepare($sql);
					//BIND
					$stmt->bind_param("ssss", $value, $page_version, $arr2[$counter], $page_id);
					//EXECUTE
					$stmt->execute();
					$counter++;
				}
			}
			
			//success
			echo $page_id;
			
			//**************************
		}else{
			echo "FAIL";
		}	
	}	
}
?>