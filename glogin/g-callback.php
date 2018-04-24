<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

	require_once "config.php";
    include_once 'User.php';

	if (isset($_GET['code'])) {
		$token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['access_token'] = $token;
    }
    else if (isset($_SESSION['access_token'])){
		$gClient->setAccessToken($_SESSION['access_token']);
    }
	else {
		header('Location: login.php');
		exit();
	}

	$oAuth = new Google_Service_Oauth2($gClient);
	$userData = $oAuth->userinfo_v2_me->get();

	$_SESSION['id'] = $userData['id'];
	$_SESSION['email'] = $userData['email'];
	$_SESSION['gender'] = $userData['gender'];
	$_SESSION['picture'] = $userData['picture'];
	$_SESSION['familyName'] = $userData['familyName'];
	$_SESSION['givenName'] = $userData['givenName'];
    
    $user = $oAuth->userinfo->get(); //get user info 
    
    ########## MySql details  #############
    $db_username = "root"; //Database Username
    $db_password = "Fss2d%^4D"; //Database Password
    $host_name = "localhost"; //Mysql Hostname
    $db_name = 'ishuffle'; //Database Name

    // connect to database
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    //check if user exist in database using COUNT
    $result = $mysqli->query("SELECT COUNT(google_email) as usercount FROM google_users WHERE google_email='$user->email'");
    $user_count = $result->fetch_object()->usercount; //will return 0 if user doesn't exist

    if($user_count == 0){ //if user not exist
        $statement = $mysqli->prepare("INSERT INTO google_users (google_name, google_email, google_link, google_picture_link) VALUES (?,?,?,?)");
        $statement->bind_param('ssss',  $user->name, $user->email, $user->link, $user->picture);
        $statement->execute();
        echo $mysqli->error;
    }


	header('Location: ../index.php');
	exit();
?>