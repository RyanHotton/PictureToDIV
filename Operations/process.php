<?php
	/*
		process.php - finishes processing unfinished images
		TO BE COMPLETED AT A LATER DATE
	*/
	// get Database class
	include_once "/Class/Credentials.class.php";
	// database class
	$database = new Credentials;
	// connect to database
	$database->connectDB();
	
	// finds the oldest unfinished image
	$query = "SELECT `id`, `name`, `source`, `height`, `width`, `status` FROM `pixels` WHERE `height` != `status` ORDER BY `id` ASC LIMIT 1";
	
	
	$database->closeDB();
?>