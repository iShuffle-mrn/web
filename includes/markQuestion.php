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
    $test_id = $_GET['test_id'];
    $question = $_GET['question'];

    // check user state
    $sql = "SELECT * FROM question_flags WHERE test_id=$test_id and question=$question and user_email='$email'"; 
    $result = $mysqli->query($sql);
    if ($result->num_rows>0)
        $userState = $result->fetch_assoc();

    // if not pressed
    if ($result->num_rows == 0){
        $sql = "INSERT INTO question_flags (test_id, question, user_email, is_factor) VALUES ($test_id, $question, '$email', 1)"; 
        $result = $mysqli->query($sql);
    }

    // if not pressed - press
    else if ($userState['is_factor'] == 0){
        $sql = "UPDATE question_flags SET is_factor=1 WHERE test_id=$test_id and question=$question and user_email='$email'";  
        $result = $mysqli->query($sql);
    }

    // if pressed - undo
    else {
        $sql = "UPDATE question_flags SET is_factor=0 WHERE test_id=$test_id and question=$question and user_email='$email'"; 
        $result = $mysqli->query($sql);
    }

    
    header("Location: discussion.php?test_id='$test_id'&question='$question'");
    exit();
?>