<?php header('Content-Type: text/html; charset=utf-8');   
	session_start();

	if (!isset($_SESSION['access_token'])) {
		header('Location: glogin/login.php');
		exit();
	}
    
    echo '<div id="uploadForm">';    
    require_once "connectDB.php";             
 
	$course = $_POST['course'];
	$year = $_POST['year'];
	$moed = $_POST['moed'];
	$semester = $_POST['semester'];

	$target_dir = "/var/www/html/web/PDFconvertor/";
	$target_file = $target_dir . basename($_FILES["file"]["name"]);
		
    $ext=explode('.',$_FILES['file']['name']);
    $extension = 'pdf';
    $newname='Input';
    $target_file=$target_dir.$newname.'.'.$extension;

    if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
    {
        
        $output = shell_exec('cd /var/www/html/web/PDFconvertor/; sh script1.sh');
        
        // Read JSON file
        $json = file_get_contents('/var/www/html/web/PDFconvertor/outputjson.json');

        //Decode JSON
        $json_data = json_decode($json,true);
        
        if (!empty($json_data)){
            $user_email = $_SESSION["email"]; 

            $sql = "SELECT * FROM users_in_courses WHERE user_email='$user_email' AND course_name='$course'";   
            $result = $mysqli->query($sql);

            if($result->num_rows == 0){ //if user not exist
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
    }
    
	

    $mysqli->close();

?>            
