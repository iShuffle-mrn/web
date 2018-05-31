<?php 
    
    session_start();

	if (!isset($_SESSION['access_token'])) {
		header('Location: glogin/login.php');
		exit();
	}

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    require_once "connectDB.php";
   
    $email = $_SESSION['email'];
    $course_id = $_GET['course_id'];

    $sql = "DELETE FROM users_in_courses WHERE course_id='$course_id' AND user_email='$email'"; 
    $result = $mysqli->query($sql);
    
    // check if anyone left in the course
    $sql = "SELECT * FROM users_in_courses WHERE course_id='$course_id'"; 
    $result = $mysqli->query($sql);
    
    // in no one left - delete all tests
    if ($result->num_rows == 0){
        $sql = "DELETE FROM tests WHERE course_id='$course_id'"; 
        $result = $mysqli->query($sql);
    }
    
    header('Location: ../index.php');
    exit();
?>