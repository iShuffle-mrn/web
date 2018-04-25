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
	
	<!-- Local CSS -->
	<link rel="stylesheet" type="text/css" href="css\style.css">
	<link rel="stylesheet" type="text/css" href="css\mainStyle.css">
	
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
		<img id="logo" src="pic/logo.png">
		<p>סיוע בלמידה למבחנים רב- ברירתיים</p>
		<img id="tape" src="pic/tape.png">
        <div id="welcome">
            <img id="profile" src="<?php echo $_SESSION['picture'] ?>">
            <?php
                if ($_SESSION['gender'] == "female"){
                    echo '<h4>ברוכה הבאה '. $_SESSION['givenName'] .' | <a href="glogin/logout.php" id="signOutButton">התנתקי</a></h4>';
                }
                else{
                    echo '<h4>ברוך הבא '. $_SESSION['givenName'] .' | <a href="glogin/logout.php" id="signOutButton">התנתק</a></h4>';
                }
            ?>
        </div>
	</header>
	

	<!-- Main -->
	<main>
		<div id="uploadTest">
			<a href="includes/upload.php" id="uploadLink"><i class="fa fa-plus"></i> הוסף מבחן חדש</a>
			<img src="pic/exam.png">
		</div>
		
		<div id="content">
			
			<h2 class="myCourses">הקורסים שלי:</h2>
            <div id="courses">
                
<?php header('Content-Type: text/html; charset=utf-8'); 

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
$mysqli->set_charset("utf8");
       
$email = $_SESSION['email'];

//$result = $mysqli->query("SELECT COUNT(user_email) as coursecount FROM users_in_courses WHERE user_email='$email'");            
//$course_count = $result->fetch_object()->coursecount; //will return the amount of courses per user

$result = $mysqli->query("SELECT course_name,course_id FROM users_in_courses WHERE user_email='$email'");
                 
if ($result->num_rows>0){
    $i = 1;
    while($row=$result->fetch_assoc()){
        $course_id = $row['course_id'];
        $course_name = $row['course_name'];
        $tests_of_course = $mysqli->query("SELECT * FROM tests WHERE course_id='$course_id'");
        $num_of_tests = $tests_of_course->num_rows;
        echo '<div><button id="course" type="button" class="btn" data-toggle="collapse" data-target="#openCourse'.$i.'"><h3>'.$course_name.'</h3>&emsp;'.$num_of_tests.' מבחן</button>';
        echo '<div id="openCourse'.$i.'" class="openCourse collapse">';
        while($row=$tests_of_course->fetch_assoc()){
            echo '<p>מועד '.$row['moed'].', סמסטר '.$row['semester'].', '.$row['year'];
            echo '&emsp;&emsp;&emsp;<a href="includes/onlineTest.php?test_id='.$row['test_id'].'&course_name='.$course_name.'" class="exercise">לתרגול עצמי</a> |  ';
            echo '<i class="fa fa-pencil"></i> <a href="includes/onlineTest.php?test_id='.$row['test_id'].'&course_name='.$course_name.'" class="onlineTest">למבחן מקוון</a>';
            echo '</p>';
        }
        echo '</div></div>';
        $i++;
    }   
    echo '</div></div></main>';
}
else{
    echo "<h4>עדיין לא העלאת מבחנים לאתר.</h4>";
}             
     
exit();
?>
			

	<!-- Footer -->



</body>
</html>
