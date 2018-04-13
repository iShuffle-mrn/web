<?php header('Content-Type: text/html; charset=utf-8'); 

	$db = mysql_connect('localhost','1','2');
	mysql_select_db('mydb',$db);
	mysql_query("SET NAMES 'utf8'",$db);

	$servername = "mtapanel.mtacloud.co.il";
	$username = "ronipe";
	$password = "XgU+2dSlXNJW";
	$dbname = "ronipe_ishuffle";

	$course = $_POST['course'];
	$year = $_POST['year'];
	$moed = $_POST['moed'];
	$semester = $_POST['semester'];
	$numOfQuestions = $_POST['numOfQuestions'];
	$numOfAnswers = $_POST['numOfAnswers'];

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$target_dir = "/home/ronipe/public_html/PDFconvert/";
	$target_file = $target_dir . basename($_FILES["file"]["name"]);
	$file_type=$_FILES['file']['type'];

	if ($file_type=="application/pdf") {
		
		$ext=explode('.',$_FILES['file']['name']);
		$extension = $ext[1];
		$newname='Input';
		$target_file=$target_dir.$newname.'.'.$extension;
		
		if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
		{
		echo "The file is uploaded";

		}
		else {
		echo "Problem uploading file";
		}
	}

	else {
	 echo "You may only upload PDFs.<br>";
	}

	$sql = "INSERT INTO tests (course, year, moed, semester, numOfQuestions, numOfAnswers)
	VALUES (.$course., .$year., .$moed., .$semester., .$numOfQuestions., .$numOfAnswers.)";

	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
?>