<?php
session_start();
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);

if (!isset($_SESSION['access_token'])) {
    header('Location: ../glogin/login.php');
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
    <link rel="stylesheet" type="text/css" href="..\css\manual.css">
    <link rel="stylesheet" type="text/css" href="..\css\mainStyle.css">

    <!-- Bootstap links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Font link -->
    <link href="https://fonts.googleapis.com/css?family=Heebo" rel="stylesheet">

    <!-- JS+JQ scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="node_modules\easytimer\dist\easytimer.min.js"></script>
    <script type="text/javascript" src="../js/test.js"></script>

</head>
<body onload="questionBar()">

<!-- Page Content -->

<!-- Header -->
<header>
    <a href="../index.php"><img id="logo" src="../pic/logo.png"></a>
    <p>סיוע בלמידה למבחנים רב- ברירתיים</p>
    <img id="tape" src="../pic/tape.png">
    <div id="welcome">
        <img id="profile" src="<?php echo $_SESSION['picture'] ?>">
        <?php
        if ($_SESSION['gender'] == "female"){
            echo '<h4>ברוכה הבאה '. $_SESSION['givenName'] .' | <a href="../glogin/logout.php" id="signOutButton">התנתקי</a></h4>';
        }
        else{
            echo '<h4>ברוך הבא '. $_SESSION['givenName'] .' | <a href="../glogin/logout.php" id="signOutButton">התנתק</a></h4>';
        }
        ?>
    </div>
    <a id="back" href="../index.php"><i class="fa fa-mail-reply"></i> בחזרה למסך הראשי</a>
</header>


<!-- Main -->
<main>

<?php header('Content-Type: text/html; charset=utf-8');   
    require_once "connectDB.php";

    $course_name = $_POST['course'];
    $year = $_POST['year'];
    $moed = $_POST['moed'];
    $semester = $_POST['semester'];
    $numOfQuestions=$_POST['numOfQuestions'];
    $numOfAnswers=$_POST['numOfAnswers'];
    $user_email = $_SESSION["email"]; 
    
    $sql = "SELECT * FROM tests WHERE year='$year' AND moed='$moed' and semester='$semester' and course_id= (SELECT course_id from users_in_courses WHERE user_email='$user_email' AND course_name='$course_name')";  
    $result = $mysqli->query($sql);

    if($result->num_rows > 0){
        header('Location: ../index.php?success=2');
    }
        
?>

    <div id="aside">
        <h1>הזנת מבחן</h1>
        <p><b>מבחן בקורס: </b><?php echo $course_name ?></p>
        <p>מועד <?php echo $moed ?>', סמסטר <?php echo $semester ?>', <?php echo $year ?></p>

        <h4><b>השאלות:</b></h4>
        <div id="qNumbers"></div>
    </div>

    <div id="testForm">
        <form action="uploadManual.php" id="FormTestManual" method="post">
            <input type="hidden" name="course" value='<?php echo $course_name ?>' >
            <input type="hidden" name="year" value='<?php echo $year ?>' >
            <input type="hidden" name="moed" value='<?php echo $moed ?>' >
            <input type="hidden" name="semester" value='<?php echo $semester ?>' >
            <input type="hidden" name="numOfQuestions" value='<?php echo $numOfQuestions ?>' >
            <input type="hidden" name="numOfAnswers" value='<?php echo $numOfAnswers ?>' >
    
<?php
    for($question=1;$question<=$numOfQuestions;$question++){
        if($question == 1)
            echo "<div id='question".$question."'>";

        else
            echo "<div id='question".$question."' style='display:none'>";

        echo "<h2 class='question'>שאלה מס' ".$question."</h2>";
        echo '<p class="qForm"><textarea name="question'.$question.'" rows="2"></textarea></p>';
        echo '<div class="aForm" id="answers'.$question.'">';

        $answer=0;
        echo '<p><label>תשובה נכונה: <textarea name="answer'.$question.'_'.$answer.'" rows="1"></textarea></label></p>';
        
        for($answer=1;$answer<$numOfAnswers;$answer++){
            echo '<p><label>תשובה: <textarea name="answer'.$question.'_'.$answer.'" rows="1"></textarea></label></p>';
        }

        echo "</div></div>";

    }
?>
            <a id="next" href="#" onclick="next()"><i class="fa fa-angle-left"></i></a>
            <a id="prev" href="#" onclick="prev()" style="display:none"><i class="fa fa-angle-right"></i></a>
        
        </form>
    </div>

    <div>
        <input id="finishButton" name="submitManual" type="submit" form="FormTestManual" value="העלה מבחן"/>
    </div>
    

</main>
<script>

    var numOfQuestions=<?php echo $numOfQuestions ?>;

</script>

</body>
</html>