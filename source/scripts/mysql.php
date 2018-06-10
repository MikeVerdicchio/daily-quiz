<?php
    // Database variables
	$servername = "quiz-db";
	$username = "quiz";
	$password = "quiz";
	$dbname = "quiz";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Unable to connect to database.");

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
    }
?>