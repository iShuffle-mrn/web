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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
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
        require_once "connectDB.php";
        $test_id= $_GET['test_id'];
        $course_name= $_GET['course_name'];

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
            <h1>מצב מבחן</h1>
            <p><b>מבחן בקורס: </b><?php echo $course_name ?></p>
            <p>מועד <?php echo $moed ?>', סמסטר <?php echo $semester ?>', <?php echo $year ?></p>

            <h4><b>השאלות:</b></h4>
            <div id="qNumbers"></div>
        </div>
		
        
        <div id="testForm">
			<form id="FormTest" method="post" action="testResults.php?test_id=<?php echo $test_id ?>&course_name=<?php echo $course_name ?>">
                <input type="hidden" name="numOfQuestions" value="<?php echo $numOfQuestions ?>">
                <input type="hidden" name="numOfAnswers" value="<?php echo $numOfAnswers ?>">
                    
        <?php
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

                if ($question == 1){
                    echo "<div id='question".$question."' dir='rtl'>";
                }
                else{
                    echo "<div id='question".$question."' style='display:none' dir='rtl'>";
                }

                echo "<h2 class='question'>שאלה מס' ".$question."</h2><h4><label for='question".$question."' dir='rtl'>";
                for($i=1;$i<=$size;$i++){
                    if (strpos($json_data['question'.$question][$i], '\\') !== false)
                        $json_data['question'.$question][$i]=str_replace('\\','',$json_data['question'.$question][$i]);
                     echo $json_data['question'.$question][$i];
                    echo " ";
                }

                if ($question == 1){
                    echo "</label></h4><div id='answers".$question."'>";
                }

                else{
                    echo "</label></h4><div id='answers".$question."' style='display:none'>";

                }

                for($answer=0;$answer<$numOfAnswers;$answer++){
                    echo "<p><label style='font-weight: normal;'><input type='radio' name='question".$question."' id='answer".$question."_".$answer."' value=".$answer.">";
                    for($j=0;$j<=sizeof($json_data['answer'.$question.'_'.$answer]);$j++){
                        if (strpos($json_data['answer'.$question.'_'.$answer][$j], '\\') !== false)
                            $json_data['answer'.$question.'_'.$answer][$j]=str_replace('\\','',$json_data['answer'.$question.'_'.$answer][$j]);
                        echo $json_data['answer'.$question.'_'.$answer][$j];
                        echo " ";
                    }
                        echo "</label></p>";
                }
                echo "</div>";
                echo "</div>";
            } 

            function randomizer($numOfAnswers) {
                //change it to the number of answers
                $x=rand(0,$numOfAnswers-1);
                return $x;
            }

        ?>
                
           </form>  
        
           <a id="next" href="#" onclick="next()"><i class="fa fa-angle-left"></i></a>
           <a id="prev" href="#" onclick="prev()" style="display:none"><i class="fa fa-angle-right"></i></a>
                
        </div>
         
        <div id="leftAside">
            <button id="startTimer" onClick="startTimer()"><img src="../pic/timer.png"><br>הפעל טיימר</button>
            <div id="clockElements">
                <div id="countdownExample">
                    <div class="values"></div>
                </div>
                <br><p id="testTime"></p>
            </div>
            
            <input id="finishButton" name="submit" type="submit" form="FormTest" value="סיים מבחן">
        </div>
		
	</main>
    <script>
        var numOfQuestions= <?php echo $numOfQuestions ?>;
        
        function startTimer(){
            swal("הזן את אורך המבחן בדקות:", {
              content: "input",
              button: "התחל!",
            })
            .then((minutes) => {
                if(minutes > 0){
                    swal("המבחן מתחיל", "","success",);

                    var timer = new Timer();
                    document.getElementById("startTimer").style.display="none";
                    document.getElementById("clockElements").style.display="block";
                    if ( $(window).width() > 700) {  
                        document.getElementById("finishButton").style.margin="65% auto 0";
                    }
                    document.getElementById("testTime").innerHTML="מתוך "+minutes+" דקות למבחן.";
                    timer.start({countdown: true, startValues: {seconds: minutes*60}});
                    $('#countdownExample .values').html(timer.getTimeValues().toString());
                    timer.addEventListener('secondsUpdated', function (e) {
                        $('#countdownExample .values').html(timer.getTimeValues().toString());
                    });
                    timer.addEventListener('targetAchieved', function (e) {
                        $('#countdownExample .values').html('המבחן הסתיים');
                    });
                }
                else{
                    swal("המספר אינו תקין", "","error",);
                }
            });
                        
        }
        

    </script>

</body>
</html>
