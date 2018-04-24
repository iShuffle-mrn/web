<?php
	session_start();

	if (!isset($_SESSION['access_token'])) {
		header('Location: glogin/login.php');
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>iShuffle - למידה למבחנים רב ברירתיים</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<!-- Local CSS -->
	<link rel="stylesheet" type="text/css" href="..\css\upload.css">
	<link rel="stylesheet" type="text/css" href="..\css\mainStyle.css">
	
	<!-- Bootstap links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
	<!-- Font link -->
	<link href="https://fonts.googleapis.com/css?family=Heebo" rel="stylesheet">
	
	<!-- JS+JQ scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
</head>
<body>

<!-- Page Content -->

	<!-- Header -->
	<header>
		<img id="logo" src="../pic/logo.png">
		<p>סיוע בלמידה למבחנים רב- ברירתיים</p>
		<img id="tape" src="../pic/tape.png">
        <div id="welcome">
            <img id="profile" src="<?php echo $_SESSION['picture'] ?>">
            <h4>ברוך/ה הבא/ה <?php echo $_SESSION['givenName'] ?> |  
            <a href="../glogin/logout.php" id="signOutButton">החלף משתמש</a></h4>
        </div>
	</header>
	

	<!-- Main -->
	<main>
		<div id="uploadForm">

<?php header('Content-Type: text/html; charset=utf-8'); 

	$servername = "localhost";
	$username = "root";
	$password = "Fss2d%^4D";
	$dbname = "ishuffle";

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


	$target_dir = "/var/www/html/web/PDFconvertor/";
	$target_file = $target_dir . basename($_FILES["file"]["name"]);
	$file_type=$_FILES['file']['type'];

	if ($file_type=="application/pdf") {
		
		$ext=explode('.',$_FILES['file']['name']);
		$extension = 'pdf';
		$newname='Input';
		$target_file=$target_dir.$newname.'.'.$extension;
		
		if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
		{
            echo "<center><h2>הקובץ הועלה בהצלחה.</h2></center>";
            $output = shell_exec('cd /var/www/html/web/PDFconvertor/; sh script1.sh');
		}
		else {
		echo "<center><h2>קרתה שגיאה. אנא נסה שנית..</h2></center>";
		}
	}

	else {
	 echo "You may only upload PDFs.<br>";
	}


$sql="INSERT INTO tests (course,year,moed,semester,numOfQuestions,numOfAnswers) VALUES ('".$course."','".$year."','".$moed."','".$semester."','".$numOfQuestions."','".$numOfAnswers."');";
    
    $result=$conn->query($sql);
    
    
    if ($result===TRUE){
        $last_id = $conn->insert_id;
        $file_name = $last_id.'.json';
        
//        $output = shell_exec('cd /var/www/html/web/PDFconvertor/tests/; mv outputjson.json '.$file_name);
//        $sql="INSERT INTO tests (test_directory) WHERE test_id=$last_id VALUES ('/var/www/html/web/PDFconvertor/tests/$file_name');";
//        $result=$conn->query($sql);
    }
    else{
        echo "Error: ". $sql. "<br>" . $conn->error;
    }
    
    $conn->close();

?>            

            </div>
		<a href="../index.php" id="homeButton">למסך הראשי</a>
	</main>
	
</body>
</html>