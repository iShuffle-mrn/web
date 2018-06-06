<?php
	session_start();

	if (!isset($_SESSION['access_token'])) {
		header('Location: glogin/login.php');
		exit();
	}
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);
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
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>

<!-- Page Content -->

	<!-- Header -->
	<header>
		<a href="index.php"><img id="logo" src="../pic/logo.png"></a>
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
    <?php
        if (isset($_GET['success'])){
            
            if($_GET['success'] == 0){
                echo '<script type="text/javascript">swal("המבחן הועלה בהצלחה", "", "success");</script>';
            }
            else if ($_GET['success'] == 1){
                echo '<script type="text/javascript">swal("קרתה שגיאה","אנא נסה להעלות את המבחן ידנית", "error");</script>';
            }
            else{
                echo '<script type="text/javascript">swal("אופס","העלאת את המבחן הזה כבר", "info");</script>';
            }
        }
    
    ?>
	
	<!-- Main -->
	<main>
        <a id="uploadTest" href="includes/pdfUpload.php" id="uploadLink"><i class="fa fa-plus"></i> הוסף מבחן חדש<img src="pic/exam.png"></a>
		
		<div id="content">
			
			<h2 class="myCourses">הקורסים שלי:</h2>
            <?php header('Content-Type: text/html; charset=utf-8'); 

            require_once "includes/connectDB.php";
            $email = $_SESSION['email'];

            // check for course invites            
            $invitations = $mysqli->query("SELECT * FROM invitations WHERE toUser='$email' ORDER BY course_id DESC;");            
            if ($invitations->num_rows>0){
                echo '<div id="invites">';
                while($row=$invitations->fetch_assoc()){
                    $fromUser = $row['fromUser'];
                    $course_id = $row['course_id'];
                    $checkCourseName = $mysqli->query("SELECT course_name FROM users_in_courses WHERE course_id='$course_id';"); 
                    $courseName = $checkCourseName->fetch_assoc();
                    $courseName = $courseName['course_name'];
                    $checkFromUserName = $mysqli->query("SELECT google_name FROM google_users WHERE google_email='$fromUser';"); 
                    $fromUserName = $checkFromUserName->fetch_assoc();
                    $fromUserName = $fromUserName['google_name'];
                    echo '<i class="fa fa-user-plus"></i><h4> הוזמנת ע"י '.$fromUserName.' לקורס '.$courseName.'. &emsp;<a href="includes/addToGroup.php?course_id='.$course_id.'&isAccepted=0">אשר</a> | <a href="includes/addToGroup.php?course_id='.$course_id.'&isAccepted=1">דחה</a></h4><br>';
                }

                echo '</div>';
            }

            echo '<div id="courses">';
                
            $result = $mysqli->query("SELECT course_name,course_id FROM users_in_courses WHERE user_email='$email' ORDER BY course_id DESC;");

        if ($result->num_rows>0){
            $i = 1;
            while($row=$result->fetch_assoc()){
                $course_id = $row['course_id'];
                $course_name = $row['course_name'];
                $tests_of_course = $mysqli->query("SELECT * FROM tests WHERE course_id='$course_id'");
                $num_of_tests = $tests_of_course->num_rows;
                echo '<div><button id="course" type="button" class="btn" data-toggle="collapse" data-target="#openCourse'.$i.'"><h3>'.$course_name.'</h3><span>&emsp;'.$num_of_tests;
                if ($num_of_tests == 1){
                    echo ' מבחן</span>';
                }
                else{
                    echo ' מבחנים</span>';
                }


                echo "<a class='removeMe' 
                onclick=\"swal({  title: 'האם אתה בטוח?',  text: 'בלחיצה על אישור תצא מהקורס לצמיתות',  icon: 'warning',  buttons: true, dangerMode: true,  buttons: ['השאר', 'צא'], }) .then((willDelete) => { if (willDelete) { swal('יצאת מהקורס בהצלחה', { icon: 'success', }); window.location.href = 'includes/removeMe.php?course_id=$course_id';  } });\"><img src='pic/cancel.png' title='צא מקורס זה'></a>";

                $people_in_course = $mysqli->query("SELECT * FROM users_in_courses WHERE course_id='$course_id'");
                $is_a_group = $people_in_course->num_rows;
                if ($is_a_group > 1){
                    echo  '<a class="group extra" href="includes/courseGroup.php?course_id='.$course_id.'&course_name='.$course_name.'">'.$is_a_group.' חברים</a><img class="groupPic" src="pic/group.png"></button>';
                }
                else{
                    echo '<a class="group" href="includes/courseGroup.php?course_id='.$course_id.'&course_name='.$course_name.'">+ הוסף חברים</a></button>';
                }

                echo '<div id="openCourse'.$i.'" class="openCourse collapse">';
                
                
                while($row=$tests_of_course->fetch_assoc()){
                    $test_id = $row['test_id'];
                    echo '<p>';
                    if ($is_a_group == 1){
                        echo "<span><a onclick=\"swal({  title: 'האם אתה בטוח?',  text: 'בלחיצה על אישור תמחק את המבחן לצמיתות',  icon: 'warning',  buttons: true, dangerMode: true,  buttons: ['ביטול', 'מחק'], }) .then((willDeleteTest) => { if (willDeleteTest) { swal('מחקת מבחן זה בהצלחה', { icon: 'success', }); window.location.href = 'includes/removeMe.php?course_id=$course_id&test_id=$test_id';  } });\"><img src='pic/removeTest.png' title='מחק מבחן' class='removeTest'></a></span>";
                    }
                    echo 'מועד '.$row['moed'].', סמסטר '.$row['semester'].', '.$row['year'];
                    echo '<span class="courseLinks"><a href="includes/exercise.php?test_id='.$row['test_id'].'&course_name='.$course_name.'" class="exercise">לתרגול עצמי</a> | <i class="fa fa-pencil"></i> <a href="includes/onlineTest.php?test_id='.$row['test_id'].'&course_name='.$course_name.'" class="onlineTest">למבחן מקוון</a></span>';
                    echo '</p>';
                }

                echo '</div>';
                $i++;
            }   
        }

        else{
            echo "<h4>עדיין לא העלאת מבחנים לאתר.</h4>";
        }             
    echo '</div>';
    exit();
?>
          
	</div>		
</main>

    

</body>
</html>
