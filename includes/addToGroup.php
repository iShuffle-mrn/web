<?php 
    
    session_start();

	if (!isset($_SESSION['access_token'])) {
		header('Location: glogin/login.php');
		exit();
	}
    
    require_once "connectDB.php";

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $email = $_SESSION['email'];
    $course_id = $_GET['course_id'];
    $isAccepted = $_GET['isAccepted'];
    
    // get course name
    $checkCourseName = $mysqli->query("SELECT course_name FROM users_in_courses WHERE course_id='$course_id';"); 
    $courseName = $checkCourseName->fetch_assoc();
    $courseName = $courseName['course_name'];
    
    // if user pressed add
    if($isAccepted == 0){
        // add course to user
        $sql = "INSERT INTO users_in_courses (course_id, user_email, course_name) VALUES ($course_id, '$email', '$courseName')"; 
        $result = $mysqli->query($sql);
        
        // delete invitation
        $sql = "DELETE FROM invitations WHERE course_id = $course_id AND toUser='$email'"; 
        $result = $mysqli->query($sql);
    }

    // if user pressed reject
    else if($isAccepted == 1){
        // delete invitation
        $sql = "DELETE FROM invitations WHERE course_id = $course_id AND toUser='$email'"; 
        $result = $mysqli->query($sql);
    }
    
    // return to index
    header('Location: ../index.php');
    exit();

?>

