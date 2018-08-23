<?php
require_once $_SERVER['DOCUMENT_ROOT']."/PHP/sAuth/db_conn.php";
require_once $_SERVER['DOCUMENT_ROOT']."/VARS/vars_gen.php";

session_start();

$count = 0;

if($dbConnection){
	$fakeNum = 1;
	//get page_id so we can use to match it in the subsection table
	$stmt = $conn->prepare('SELECT * FROM page WHERE 1 = ?');
	$stmt->bind_param('i', $fakeNum);
	$stmt->execute();
	
	$top_page_name = "";
	$top_page_views = 0;
	
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {	
		$x = $row["views"];
		if($x>$top_page_views){
			$top_page_views = $x;
			$top_page_name = $row["title"];
		}	
	}
	
	echo $top_page_views."~split~".$top_page_name;
}
?>