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
	
	<!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="..\css\onlineTest.css">
	<link rel="stylesheet" type="text/css" href="..\css\testResults.css">
	<link rel="stylesheet" type="text/css" href="..\css\mainStyle.css">
	
	<!-- Bootstap links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    	
	<!-- Font link -->
	<link href="https://fonts.googleapis.com/css?family=Heebo" rel="stylesheet">
	
	<!-- JS+JQ scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/results.js"></script>
        
</head>
<body onload="score()">

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
        require_once "connectDB.php";
        $test_id= $_GET['test_id'];
        $course_name= $_GET['course_name'];
        $email = $_SESSION['email'];
        
        $sql = "SELECT * FROM tests WHERE test_id='$test_id'";   
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()) {
            $year = $row['year'];
            $moed = $row['moed'];
            $semester = $row['semester'];
            $test_directory = $row['test_directory'];
        }
        $score=0;
        
        

        if (isset($_POST['submit'])) {
            if(isset($_POST['numOfQuestions'])){
                $numOfQuestions = $_POST['numOfQuestions'];
            }
            if(isset($_POST['numOfAnswers'])){
                $numOfAnswers = $_POST['numOfAnswers'];
            }
            
            for($i=1;$i<=$numOfQuestions;$i++){
                if(isset($_POST['correctAnswer'.$i])){
                    ${"correctAnswer".$i}=$_POST["correctAnswer".$i];
                }

                if (isset($_POST['question'.$i])){
                    ${"answerQuestion_".$i}=$_POST["question".$i];

                }
                else{
                     ${"answerQuestion_".$i}=-1;
                }
            }
        }

        
        // Read JSON file
         $json = file_get_contents($test_directory);

        //Decode JSON
         $json_data = json_decode($json,true);
    
    ?>
        
        <div id="aside">
            <h1>תוצאות מבחן</h1>
            <p><b>מבחן בקורס: </b><?php echo $course_name ?></p>
            <p>מועד <?php echo $moed ?>', סמסטר <?php echo $semester ?>', <?php echo $year ?></p>

            <h4><b>השאלות:</b></h4>
            <div id="qNumbers">
            <?php
                $question=1;
                if ($numOfQuestions <= 20){
                    for($i=1;$i<=sqrt($numOfQuestions);$i++){
                        echo '<div class="qRow" id="qRow'.$i.'">';
                        for($j=0;$j<sqrt($numOfQuestions);$j++){
                            if (${"answerQuestion_".$question}==${"correctAnswer".$question}){
                                $backgroundColor='#9EE399';
                            }
                            else{
                                $backgroundColor='#E79F9E';
                            }
                            echo '<a href="#" class="qNum" id="qNum'.$question.'" onclick="goToQuestion(this)" style="background-color:'.$backgroundColor.'">'.$question.'</a>';
                            $question++;
                            
                            if ($question > $numOfQuestions){
                                break;
                            }
                        }
                        echo '</div>';
                    } 
                }
                else{
                    for($i=0;$i<ceil(sqrt($numOfQuestions));$i++){
                        echo '<div class="qRow" id="qRow'.$i.'">';
                        for($j=1;$j<=5;$j++){
                            if (${"answerQuestion_".$question}==${"correctAnswer".$question}){
                                $backgroundColor='#9EE399';
                            }
                            else{
                                $backgroundColor='#E79F9E';
                            }
                            echo '<a href="#" class="qNum" id="qNum'.$question.'" onclick="goToQuestion(this)" style="background-color:'.$backgroundColor.'">'.$question.'</a>';
                            $question++;
                            
                            if ($question > $numOfQuestions){
                                break;
                            }
                        }
                        echo '</div>';   
                    }
                }
            ?>
            </div>
        </div>
		
                    
        <div id="testForm">
        <?php
        
            for($question=1;$question<=$numOfQuestions;$question++){
                $temp2=$json_data['answer'.$question.'_0'][0]; //keeps answer a in temp
                $json_data['answer'.$question.'_0'][0]=$json_data['answer'.$question.'_'.${"correctAnswer".$question}][0];
                $json_data['answer'.$question.'_'.${"correctAnswer".$question}][0]= $temp2;
                $size=sizeof($json_data['question'.$question]);

                if ($question == 1){
                    echo "<div id='question".$question."' class='forScroll' dir='rtl'>";
                }
                else{
                    echo "<div id='question".$question."' class='forScroll' style='display:none' dir='rtl'>";
                }
                
                echo "<h2 class='question'>שאלה מס' ".$question;
                if (${"correctAnswer".$question}==${"answerQuestion_".$question}){
                    echo "<img id='statusImg' src='../pic/correct.png'>";
                    $score=$score+(100)/($numOfQuestions);
                    }
                else{
                    echo "<img id='statusImg' src='../pic/wrong.png'>";
                     }


                echo "</h2><div class='scrollDiv'>";
                if (${"answerQuestion_".$question}==-1)
                    echo "<h5>לא בחרת תשובה בשאלה זו</h5>";
                echo "<h4><label for='question".$question."' dir='rtl'>";

                    for($i=1;$i<=$size;$i++){
                         echo $json_data['question'.$question][$i];
                        echo " ";
                    }
                if ($question == 1){
                   echo "</label></h4><div id='answers".$question."'><ul class='ul'>";
                }
                else{
                   echo "</label></h4><div id='answers".$question."' style='display:none'><ul class='ul'>";
                }
                for($answer=0;$answer<$numOfAnswers;$answer++){
                    if ($answer==${"answerQuestion_".$question}){
                        if($answer==${"correctAnswer".$question}){
                            $answerStatus='correct-green';
                        }

                        else
                                $answerStatus='wrong-red';
                        }
                else{
                    if ($answer==${"correctAnswer".$question}){
                        $answerStatus='correct-green';
                    }

                    else
                        $answerStatus='none';
                    }

                    echo "<li><p id='".$answerStatus."'>";
                        for($j=0;$j<=sizeof($json_data['answer'.$question.'_'.$answer]);$j++){
                        echo $json_data['answer'.$question.'_'.$answer][$j];
                        echo " ";
                    }
                    echo "</p></li>";
                }
                
                echo "</ul>";
                echo "</div></div>";
                echo "</div>";
            }      
            
                $sql = "INSERT INTO scores (user, test, score) VALUES ('$email', $test_id, $score)";   
                $result = $mysqli->query($sql);
                    
            ?>
            <a id="next" href="#" onclick="next()"><i class="fa fa-angle-left"></i></a>
            <a id="prev" href="#" onclick="prev()" style="display:none"><i class="fa fa-angle-right"></i></a>
            
        </div>
       
        <div id="leftAside">
            <p id="score"></p>   
        </div>
   
		
	</main>
    <script>
        var numOfQuestions="<?php echo "$numOfQuestions"; ?>";
        
        function score(){
            var score= <?php echo $score ?>;
            document.getElementById('score').innerHTML="<b>ציון:</b> "+score;
            
        }
    </script>

</body>
</html>
