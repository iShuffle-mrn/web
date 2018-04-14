<?php header('Content-Type: text/html; charset=utf-8'); 

	$servername = "192.168.210.100";
	$username = "ronipe_ronipe";
	$password = "14021992";
	$dbname = "ronipe_ishuffle";

    //create connection
    $conn= new mysqli($servername,$username,$password,$dbname);
    //check connection
    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
 
  

	$course = $_POST['course'];
	$year = $_POST['year'];
	$moed = $_POST['moed'];
	$semester = $_POST['semester'];
	$numOfQuestions = $_POST['numOfQuestions'];
	$numOfAnswers = $_POST['numOfAnswers'];


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


$sql="INSERT INTO tests (course,year,moed,semester,numOfQuestions,numOfAnswers) VALUES ('".$course."','".$year."','".$moed."','".$semester."','".$numOfQuestions."','".$numOfAnswers."');";
    
    $result=$conn->query($sql);
    
    
    if ($result===TRUE){
        echo "Thank you for uploading the file <br>";
    }
    else{
        echo "Error: ". $sql. "<br>" . $conn->error;
    }
    
    $conn->close();

?>