<?php
	$today = getdate();
	if(!isset($_COOKIE["day"]) || $_COOKIE["day"] != $today["yday"]) {
?>

<html lang="en">

<head>
    <title>Daily Quiz Game</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		
	<script type="text/javascript">
		function makeRequest() {
			var httpRequest;

			if (window.XMLHttpRequest) {
				httpRequest = new XMLHttpRequest();
				if (httpRequest.overrideMimeType) {
					httpRequest.overrideMimeType('text/xml');
				}
			} else if (window.ActiveXObject) {
				try {
					httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try {
						httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e) {}
				}
			}
			if (!httpRequest) {
				alert('Cannot create an XMLHTTP instance.');
				return false;
			}

			// Create data here
			var data;
			var type = arguments[0];
			if (type == 1) {													// Select a question for the quiz
				data = "type=" + type;
				httpRequest.onreadystatechange = function() {
					if (httpRequest.readyState == 4) {
						if (httpRequest.status== 200) {
							var response = JSON.parse(httpRequest.responseText);
							DisplayQuestion(response);
						}
					}
				};
				
			} else if (type == 2) {												// Select the answer choices for the quiz
				var answer = arguments[1];
				data = "type=" + type + "&" + "answer=" + answer;
				httpRequest.onreadystatechange = function() {
					if (httpRequest.readyState == 4) {
						if (httpRequest.status == 200) {
							var response = JSON.parse(httpRequest.responseText);
							if(response != "End") {
								DisplayQuestion(response);
							} else {
								makeRequest(3);
							}
						}
					}
				};
				
			} else if (type == 3) {												// Shows statistics
				data = "type=" + type;
				httpRequest.onreadystatechange = function() {
					if (httpRequest.readyState == 4) {
						if (httpRequest.status == 200) {
							ClearForm();
							var response = JSON.parse(httpRequest.responseText);
							var userCorrect = response[0];
							var userTotal = response[1];
							var quizCorrect = response[2];
							var quizTotal = response[3];
							var numPeople = response[4];
							
							var output = "Thanks for taking today's quiz! You scored " + userCorrect + " out of " + userTotal + ".\n\nAltogether, " + numPeople + " have taken this quiz and scored " + quizCorrect + " out of " + quizTotal + ".";
							
							alert(output);
						}
					}
				};
			}
			
			httpRequest.open('POST', "scripts/process.php", true);
			httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			httpRequest.send(data);
		}
		
		function ClearForm() {
			document.getElementById("quiz").innerHTML = "";
		}
		
		function ShowStart() {
			var topic = document.createElement("label");
			topic.id = "topic";
			topic.textContent = "Click below to start today's quiz!";
			topic.setAttribute("class", "text-center");
			topic.setAttribute("style", "font-size:x-large;");
			document.getElementById("quiz").appendChild(topic);

			var start = document.createElement("input");
			start.type = "button";
			start.id = "start";
			start.className = "btn btn-default btn-lg center-block";
			start.onclick = function() {
				StartQuiz();
				return false;
			};
			start.value = "Start!";
			document.getElementById("quiz").appendChild(start);
		}

		function StartQuiz() {
			ClearForm();
			makeRequest(1);
		}
		
		function DisplayQuestion(response) {
			ClearForm();
			
			var questionText = response[0];
			var choice1 = response[1];
			var choice2 = response[2];
			var choice3 = response[3];
			var choice4 = response[4];

			var quiz = document.getElementById("quiz");

			var question = document.createElement("label");
			question.id = "question";
			question.innerHTML = questionText;
			quiz.appendChild(question);
			quiz.appendChild(document.createElement("br"));

			var answer1 = document.createElement("input");
			var label1 = document.createElement("label");
			answer1.type = "radio";
			answer1.name="answer";
			answer1.value = choice1;
			label1.innerHTML = choice1;
			label1.appendChild(answer1);
			quiz.appendChild(label1);
			quiz.appendChild(document.createElement("br"));

			var answer2 = document.createElement("input");
			var label2 = document.createElement("label");
			answer2.type = "radio";
			answer2.name="answer";
			answer2.value = choice2;
			label2.textContent = choice2;
			label2.appendChild(answer2);
			quiz.appendChild(label2);
			quiz.appendChild(document.createElement("br"));

			var answer3 = document.createElement("input");
			var label3 = document.createElement("label");
			answer3.type = "radio";
			answer3.name="answer";
			answer3.value = choice3;
			label3.textContent = choice3;
			label3.appendChild(answer3);
			quiz.appendChild(label3);
			quiz.appendChild(document.createElement("br"));

			var answer4 = document.createElement("input");
			var label4 = document.createElement("label");
			answer4.type = "radio";
			answer4.name="answer";
			answer4.value = choice4;
			label4.textContent = choice4;
			label4.appendChild(answer4);
			quiz.appendChild(label4);
			quiz.appendChild(document.createElement("br"));

			var submit = document.createElement("input");
			submit.type = "submit";
			submit.id = "submit";
			submit.value = "Submit!";
			submit.className = "btn btn-default btn-lg center-block";
			submit.onclick = function () {
				var chosen = document.querySelector('input[name = "answer"]:checked').value;
				makeRequest(2, chosen);
				return false;
			};
			quiz.appendChild(submit);
		}
		
	</script>

	<style>
		body {
			background-image: url("resources/background.png");
		}
	</style>
</head>
	
<?php } else {			
		echo '<script language="javascript">';
		echo 'alert("You have already taken the quiz today. Please return tomorrow to take another.")';
		echo '</script>';
	?>
		<head>
   		 <title>Daily Quiz Game</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	</head>
<?php	} ?>
	
<body>
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-12 text-center">
                <h1 class="page-header text-center" style="font-size:500%">Quiz of the Day</h1>
				<form id="quiz" name="quiz" class="center-block" style="font-size:x-large;"></form>
				<script type="text/javascript">
					ShowStart();
				</script>
			</div>
		</div>
    </div>
</body>

</html>