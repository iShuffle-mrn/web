<?php header('Content-Type: text/html; charset=utf-8');
	session_start();

//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);


	if (!isset($_SESSION['access_token'])) {
		header('Location: glogin/login.php');
		exit();
	}
  
    
    $json_data=[];
    
    if(isset($_POST['submitManual'])) {
        $numOfQuestions=$_POST['numOfQuestions'];
        $numOfAnswers=$_POST['numOfAnswers'];
        for ($i = 1; $i <= $numOfQuestions; $i++) {
            ${"question" . $i} = $_POST['question'.$i];
            $json_data["question".$i][0]="שאלה מספר ".$i;
            $json_data["question".$i][1] = ${"question".$i};
            for ($j = 0; $j < $numOfAnswers; $j++) {
                ${"answer".$i."_".$j} = $_POST["answer".$i. "_". $j];
                $json_data["answer" . $i . "_" . $j][0] = ${"answer" . $i . "_" . $j};
            }
        }
    }
    
        
    echo '<div id="uploadForm">';    
    require_once "connectDB.php";             
 
	$course = $_POST['course'];
	$year = $_POST['year'];
	$moed = $_POST['moed'];
	$semester = $_POST['semester'];


    //writing into json file


    $json = json_encode($json_data);
    file_put_contents('/var/www/html/web/PDFconvertor/outputjson.json', $json);


    if (!empty($json_data)){
        $user_email = $_SESSION["email"]; 

        $sql = "SELECT * FROM users_in_courses WHERE user_email='$user_email' AND course_name='$course'";   
        $result = $mysqli->query($sql);

        if($result->num_rows == 0){ //if user not in course
            $sql1="INSERT INTO users_in_courses (course_name, user_email) VALUES ('$course', '$user_email');";
            $result=$mysqli->query($sql1);
            $last_course_id = $mysqli->insert_id;
        }
        
        else{
            while($row = $result->fetch_assoc()) {
                $last_course_id = $row['course_id'];
            }
        }

        $sql2="INSERT INTO tests (course_id,year,moed,semester) VALUES ('$last_course_id','$year','$moed','$semester');";

        $result=$mysqli->query($sql2);


        if ($result===TRUE){
            $last_id = $mysqli->insert_id;
            $file_name = $last_id.'.json';

            $output = shell_exec('cd /var/www/html/web/PDFconvertor/tests/; mv ../outputjson.json '.$file_name);
            $file_name = '/var/www/html/web/PDFconvertor/tests/'.$file_name;

            $sql="UPDATE tests set test_directory='$file_name' WHERE test_id=$last_id";
            $result=$mysqli->query($sql);
        }
        else{
            echo "Error: ". $sql. "<br>" . $mysqli->error;
        }
        header('Location: ../index.php?success=0');
    }
    else {
        header('Location: ../index.php?success=1');
    }

        
    $mysqli->close();

?>            
