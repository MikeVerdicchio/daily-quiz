<?php
    session_start();
    session_unset();
    session_destroy();

    require 'mysql.php';

	// Drop tables to start fresh
	$result = mysqli_query($conn, "DROP TABLE Questions");
	$result = mysqli_query($conn, "DROP TABLE Answers");
	$result = mysqli_query($conn, "DROP TABLE Topics");
	$result = mysqli_query($conn, "DROP TABLE Statistics");

	// Recreate tables
	$result = mysqli_query($conn, "CREATE TABLE Questions (ID integer primary key auto_increment, Question varchar(150) not null, Topic varchar(30) not null, Answer_ID integer not null)");
	$result = mysqli_query($conn, "CREATE TABLE Answers (ID integer primary key auto_increment, Question_ID integer, Answer varchar(100) not null)");
	$result = mysqli_query($conn, "CREATE TABLE Topics (ID integer primary key auto_increment, Topic varchar(30) not null, Num_Questions integer)");
	$result = mysqli_query($conn, "CREATE TABLE Statistics (ID integer primary key auto_increment, Topic varchar(30) not null, Correct integer, Total integer)");

	// Insert values into tables
	$file = fopen("../resources/quiz-questions.txt", "r");
	if (flock($file, LOCK_SH)) {
		$i = 1;
		while ($line = fgetss($file, 512)) {
			$data = explode("~", $line);
			$topic = $data[0];
			$question = $data[1];
			$quiz = explode("|", $data[2]);
						
			$chunk = current($quiz);
			while ($chunk) {
				$choice = explode("^", $chunk);
				$answer = $choice[0];
				$correct = $choice[1];
				$result = mysqli_query($conn, "INSERT INTO Answers (Answer, Question_ID) VALUES ('$answer', '$i')");
				if($correct == "Correct") {
					$id = mysqli_insert_id($conn);
					$result = mysqli_query($conn, "INSERT INTO Questions (Question, Topic, Answer_ID) VALUES ('$question', '$topic', '$id')");
				}
				$chunk = next($quiz);
			}
			$i++;
		}
	}
	flock($file, LOCK_UN);
	fclose($file);

	$file = fopen("../resources/quiz-topics.txt", "r");
	if (flock($file, LOCK_SH)) {
		while ($line = fgetss($file, 512)) {
			$data = explode("|", $line);
			$topic = $data[0];
			$number = $data[1];
			$result = mysqli_query($conn, "INSERT INTO Topics (Topic, Num_Questions) VALUES ('$topic', '$number')");
			$result = mysqli_query($conn, "INSERT INTO Statistics (Topic, Correct, Total) VALUES ('$topic', '0', '0')");
		}
	}
	flock($file, LOCK_UN);
	fclose($file);

	mysqli_close($conn);
	setcookie('day', '-1');
	
	// Forward on the quiz page
	header('Location: ../index.php');
?>