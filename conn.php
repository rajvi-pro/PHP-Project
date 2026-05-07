<?php
	
	$server="localhost";
	$username="root";
	$password="";
	$database="internshop_db";
	
	$conn = mysqli_connect($server, $username, $password, $database);
	
	if(!$conn){
		die("connection failed");
	}
	
	$msg = '';

	function safePrint($str){
	 	return htmlentities($str);
	}

	
?>