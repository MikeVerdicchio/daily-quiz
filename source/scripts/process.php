<?php
	// Script to process the submission and send the results back to the client
	session_start();
	$day = $_COOKIE['day'];

    require 'mysql.php';

	$type = $_POST["type"];
	if ($type == 1) {									// Initialize quiz with question and answers
		$result = mysqli_query($conn, "SELECT * from Topics order by rand('$day')");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['topic'] = $row['Topic'];
		$_SESSION['current'] = 1;
		$_SESSION['numCorrect'] = 0;
		$topic = $row['Topic'];
		$_SESSION['numquestions'] = $row['Num_Questions'];
		$_SESSION['quiz'] = $row['ID'];
		$array = array();
		
		
		$result = mysqli_query($conn, "SELECT ID, Question from Questions where Topic='$topic' order by rand('$day')");
		$row = mysqli_fetch_assoc($result);
		$question = $row['Question'];
		$id = $row['ID'];
		$_SESSION['currentQuestionID'] = $id;
		$result = mysqli_query($conn, "SELECT Answer from Answers where Question_ID='$id'");
		$array[0] = $question;
		$i = 1;
		while($row = mysqli_fetch_assoc($result)) {
			$array[$i] = $row['Answer'];
			$i++;
		}
		echo json_encode($array);
		
	} else if ($type == 2) {							// Check quiz answer
		$answer = $_POST['answer'];
		$topic = $_SESSION['topic'];
		$k = $_SESSION['currentQuestionID'];
		
		$result = mysqli_query($conn, "SELECT Answer_ID from Questions where ID='$k'");
		$row = mysqli_fetch_assoc($result);
		$answerid = $row['Answer_ID'];
		$result = mysqli_query($conn, "SELECT Answer from Answers where ID='$answerid'");
		$row = mysqli_fetch_assoc($result);
		$finalanswer = $row['Answer'];
		if($finalanswer == $answer) {
			$_SESSION['numCorrect']++;
		}
		
		$_SESSION['current']++;
		if($_SESSION['current'] > $_SESSION['numquestions']) {
			echo json_encode("End");
			return;
		}
	
		$array = array();
		$result = mysqli_query($conn, "SELECT ID, Question from Questions where Topic='$topic' order by rand('$day')");
		$row = mysqli_fetch_assoc($result);

		$j = 1;
		while ($j < $_SESSION['current']) {
			$row = mysqli_fetch_assoc($result);
			$j++;
		}
		
		$question = $row['Question'];
		$id = $row['ID'];
		$_SESSION['currentQuestionID'] = $id;
		$result = mysqli_query($conn, "SELECT Answer from Answers where Question_ID='$id'");
		$array[0] = $question;
		$i = 1;
		while($row = mysqli_fetch_assoc($result)) {
			$array[$i] = $row['Answer'];
			$i++;
		}
		
		echo json_encode($array);
		
	} else if ($type == 3) {							// Quiz statistics
		$id = $_SESSION['quiz'];
		$userCorrect = $_SESSION['numCorrect'];
		$total = $_SESSION['numquestions'];
		$result = mysqli_query($conn, "SELECT Correct, Total from Statistics where ID='$id'");
		$row = mysqli_fetch_assoc($result);		
		
		$tempCorrect = $row['Correct'];
		$tempTotal = $row['Total'];
		$tempCorrect += $userCorrect;
		$tempTotal += $total;
		$result = mysqli_query($conn, "Update Statistics set Correct='$tempCorrect' where ID='$id'");
		$result = mysqli_query($conn, "Update Statistics set Total='$tempTotal' where ID='$id'"); 
		
		$result = mysqli_query($conn, "SELECT Correct, Total from Statistics where ID='$id'");
		$row = mysqli_fetch_assoc($result);
		$totalCorrect = $row['Correct'];
		$total = $row['Total'];
		$numpeople = $total / $_SESSION['numquestions'];
		
		$array = array();
		$array[0] = $_SESSION['numCorrect'];
		$array[1] = $_SESSION['numquestions'];
		$array[2] = $totalCorrect;
		$array[3] = $total;
		$array[4] = $numpeople;
		
		echo json_encode($array);
	}
?>