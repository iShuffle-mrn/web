<?php
session_start();
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);

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

    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="..\css\exercise.css">
    <link rel="stylesheet" type="text/css" href="..\css\mainStyle.css">

    <!-- Bootstap links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

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

    <?php
    $test_id= $_GET['test_id'];
    $course_name= $_GET['course_name'];

    require_once "connectDB.php";

    $sql = "SELECT * FROM tests WHERE test_id='$test_id'";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()) {
        $year = $row['year'];
        $moed = $row['moed'];
        $semester = $row['semester'];
        $test_directory = $row['test_directory'];
    }

    // Read JSON file
    $json = file_get_contents($test_directory);

    //Decode JSON
    $json_data = json_decode($json,true);


    //checking how many questions and answers in the test

    $search="question";
    $numOfQuestions = 0;
    foreach($json_data as $key=> $value){
        if(strstr($key,$search)){
            $numOfQuestions = $numOfQuestions+1;
        }
    }

    $search="answer1_";
    $numOfAnswers = 0;
    foreach($json_data as $key=> $value){
        if(strstr($key,$search)){
            $numOfAnswers = $numOfAnswers+1;
        }
    }
    ?>

    <div id="aside">
        <h1>תרגול עצמי</h1>
        <p><b>מבחן בקורס: </b><?php echo $course_name ?></p>
        <p>מועד <?php echo $moed ?>', סמסטר <?php echo $semester ?>', <?php echo $year ?></p>

        <h4><b>השאלות:</b></h4>
        <div id="qNumbers"></div>
    </div>

    <div id="testForm">
        <div id="test">

            <?php
            function checkGeneral($generalKey,$json_data){
                global $showInforForNext;
                global $generalCounter;
                if (array_key_exists('general_info'.$generalKey,$json_data)==1){
                    $showInforForNext=$json_data['general_info'.$generalKey][0];
                    $generalCounter=0;
                    return true;
                }
                else return false;
            }

            $generalKey=0;
            for($question=1;$question<=$numOfQuestions;$question++){

                if($json_data['flag'.$question][0]!='true'){
                    $randomNum=randomizer($numOfAnswers);
                    ${"correctAnswer".$question}=$randomNum;
                    echo "<input type='hidden' name='correctAnswer".$question."' value=".$randomNum.">";
                    $temp2=$json_data['answer'.$question.'_0'][0];
                    $json_data['answer'.$question.'_0'][0]=$json_data['answer'.$question.'_'.$randomNum][0];
                    $json_data['answer'.$question.'_'.$randomNum][0]=$temp2;
                }
                else{
                    $randomNum=0;
                }
                $size=sizeof($json_data['question'.$question]);

                if ($question==1){
                    echo "<div id='question".$question."' class='questionScroll' dir='rtl'>";
                }
                else{
                    echo "<div id='question".$question."' class='questionScroll' style='display:none' dir='rtl'>";
                }


                $sql = "SELECT * FROM comments WHERE test_id='$test_id' and question_num='$question'";
                $result = $mysqli->query($sql);
                $numOfComments = $result->num_rows;

                echo "<form id='FormExe$question' method='post')><h2 class='question'>שאלה מס' ".$question."</h2>";
                echo "<div class='showDis'>";

                echo "<img class='help' src='../pic/help.png' onclick=\"checkAnswer($question,$randomNum)\" title='בדוק תשובה'>";
                echo "<a href='#' onclick='showDiscussion($question)'><img src='../pic/chat.png' title='פתח דיון'>[ $numOfComments תגובות ]</a></div>";


                echo "<div class='forScroll'>";
                if ((checkGeneral($generalKey,$json_data))&&($question<=$showInforForNext+$generalKey)){
                    echo '<div>';
                    echo '<p class="w3-dropdown-hover">צפה בנתונים כלליים לשאלה ';
                    echo '<span class="w3-dropdown-content">';

                    for($i=1;$i<=sizeof($json_data['general_info'.$generalKey]);$i++){
                        echo $json_data['general_info'.$generalKey][$i];
                        echo " ";
                    }
                    echo "</span></p></div>";
                    if($question==$showInforForNext+$generalKey){
                        $generalKey+=$showInforForNext;
                    }


                }



                echo "<h4><label for='question".$question."' dir='rtl'>";
                for($i=1;$i<=$size;$i++){
                    echo $json_data['question'.$question][$i];
                    echo " ";
                }

                if ($question==1){
                    echo "</label></h4><div id='answers".$question."'>";
                }
                else{
                    echo "</label></h4><div id='answers".$question."' style='display:none'>";
                }


                for($answer=0;$answer<$numOfAnswers;$answer++){
                    echo "<p><label id='answer".$question."_".$answer."' style='font-weight: normal;'><input type='radio' name='question".$question."' id='answer".$question."_".$answer."' value=".$answer.">";
                    for($j=0;$j<=sizeof($json_data['answer'.$question.'_'.$answer]);$j++){
                        echo $json_data['answer'.$question.'_'.$answer][$j];
                        echo " ";
                    }
                    echo "</label></p>";
                }
                echo "</div></div>";


                echo "</form>";

                echo "<div id='discussionIframe".$question."' class='discussionIframe'>";


                echo "<h2 class='question'>שאלה מס' ".$question."</h2>";
                echo "<div class='hideDis'><p><a href='#' onclick='hideDiscussion($question)'><i class='fa fa-pencil'></i> חזרה לשאלה</a></p>";

                echo '</div><iframe src="discussion.php?test_id='.$test_id.'&question='.$question.'"></iframe>';

                echo '</div>';
                echo "</div>";
            }

            function randomizer($numOfAnswers) {
                $x=rand(0,$numOfAnswers-1);
                return $x;
            }

            function checkAnswer($i){

                if (isset($_POST['submit'.$i])) {
                    if(isset($_POST['correctAnswer'.$i]))
                        ${"correctAnswer".$i}=$_POST["correctAnswer".$i];
                    if (isset($_POST['question'.$i]))
                        ${"answerQuestion_".$i}=$_POST["question".$i];
                    else
                        ${"answerQuestion_".$i}=-1;

                }
                if(${"correctAnswer".$i}==${"answerQuestion_".$i})
                    echo "תשובתך נכונה";
                else
                    echo "תשובתך שגויה";
            }
            ?>

            <a id="next" href="#" onclick="next()"><i class="fa fa-angle-left"></i></a>
            <a id="prev" href="#" onclick="prev()" style="display:none"><i class="fa fa-angle-right"></i></a>

        </div>
    </div>


</main>

<script>
    var numOfQuestions= <?php echo $numOfQuestions ?>;
</script>

</body>
</html>
