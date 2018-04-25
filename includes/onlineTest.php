<!--

2 rows question
what if there ia an answer with two or 3 options?
-->
<?php
	session_start();
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);

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
	
</head>
<body onload="questionBar()">

<!-- Page Content -->

	<!-- Header -->
	<header>
		<img id="logo" src="../pic/logo.png">
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
	</header>

	<!-- Main -->
	<main>
        
    <?php
    $test_id= $_GET['test_id'];
    $course_name= $_GET['course_name'];

    $servername = "localhost";
    $username = "root";
    $password = "Fss2d%^4D";
    $dbname = "ishuffle";

    //create connection
    $conn= new mysqli($servername,$username,$password,$dbname);
    //check connection
    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
                 
    $sql = "SELECT * FROM tests WHERE test_id='$test_id'";   
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $year = $row['year'];
        $moed = $row['moed'];
        $semester = $row['semester'];
        $numOfQuestions = $row['numOfQuestions'];
        $numOfAnswers = $row['numOfAnswers'];
        $test_directory = $row['test_directory'];
    }
        
        // Read JSON file
         $json = file_get_contents($test_directory);

        //Decode JSON
         $json_data = json_decode($json,true);
    

		echo '<div id="aside"><h1>מצב מבחן</h1>';
            echo '<h5>'.$course_name.'</h5>';
            echo '<h5>מועד '. $moed.', סמסטר '.$semester.', '.$year.'</h5>';
        
            echo '<h4>השאלות:</h4>';
            echo '<div id="qNumbers">';
     
            echo '</div></div>';
        
        echo '<div id="testForm">';
			echo '<form id=FormTest action="post">';


                    $question=1;
                    $randomNum=randomizer();
                    $temp=$json_data['answer'.$question.'_0'][0]; //keeps answer a in temp
                    $json_data['answer'.$question.'_0'][0]=$json_data['answer'.$question.'_'.$randomNum][0];
                    $json_data['answer'.$question.'_'.$randomNum][0]= $temp;

                
                echo "<div id='question".$question."'><h2>שאלה מס' ".$question."</h2><h4><label for='question".$question."'>".$json_data['question'.$question][1]."</label></h4><div id='answers".$question."'>";
                    for($answer=0;$answer<$numOfAnswers;$answer++){
                        echo "<p><input name='question".$question."' type='radio' id='answer".$question."_".$answer."' value='1'>".$json_data['answer'.$question.'_'.$answer][0]."</p>";
                    }
                echo "</div>";
                echo "</div>";
                    

                    for($question=2;$question<=$numOfQuestions;$question++){
                         $randomNum2=randomizer();
                        $temp2=$json_data['answer'.$question.'_0'][0];
                        $json_data['answer'.$question.'_0'][0]=$json_data['answer'.$question.'_'.$randomNum2][0];
                        $json_data['answer'.$question.'_'.$randomNum2][0]=$temp2;

                        
                        echo "<div id='question".$question."' style='display:none'><h2>שאלה מס' ".$question."</h2><h4><label for='question".$question."'>".$json_data['question'.$question][1]."</label></h4><div id='answers".$question."'style='display:none'>";
                        for($answer=0;$answer<$numOfAnswers;$answer++){
                        echo "<p><input name='question".$question."'type='radio' id='answer".$question."_".$answer."'value='1'>".$json_data['answer'.$question.'_'.$answer][0]."</p>";
                        }
                        echo "</div>";
                         echo "</div>";
                    } 
                
                    function randomizer() {
                        //change it to the number of answers
                        $x=rand(0,3);
                        return $x;
                    }
                
                

                echo '<a id="next" href="#" onclick="next()"><i class="fa fa-angle-left"></i></a>';
                echo '<a id="prev" href="#" onclick="prev()" style="display:none"><i class="fa fa-angle-right"></i></a>';
                
            echo '</form></div>';
        ?>
        <div>
            <div id="countdownExample">
                <div class="values"></div>
            </div>
            <img id="clock" src="../pic/stopwatch.png">
            <a href="../index.php" id="homeButton">למסך הראשי</a>
        </div>
		
	</main>
    <script>
        var numOfQuestions=20;
        var curr=1;
        var question;
        function next(){
            checked(curr);
            document.getElementById('question'+curr).setAttribute('style','display:none');
            document.getElementById('answers'+curr).setAttribute('style','display:none');
            curr++;
            document.getElementById('question'+curr).setAttribute('style','display:block');
            document.getElementById('answers'+curr).setAttribute('style','display:block');
            document.getElementById('prev').setAttribute('style','display:block');
            if(curr==numOfQuestions){
                document.getElementById('next').setAttribute('style','display:none');
                document.getElementById('done').setAttribute('style','display:block');
            }
            if(curr!=numOfQuestions){
                document.getElementById('done').setAttribute('style','display:none');
            }
            checked(curr);
            
            
        }
        
        
        function prev(){
            checked(curr);
            document.getElementById('question'+curr).setAttribute('style','display:none');
            document.getElementById('answers'+curr).setAttribute('style','display:none');
            curr--;
            document.getElementById('question'+curr).setAttribute('style','display:block');
            document.getElementById('answers'+curr).setAttribute('style','display:block');
            if(curr==1){
                document.getElementById('prev').setAttribute('style','display:none');
                document.getElementById('next').setAttribute('style','display:block');
            }
            if(curr!=numOfQuestions){
                document.getElementById('next').setAttribute('style','display:block');
                document.getElementById('done').setAttribute('style','display:none');
            }
            
         checked(curr);   
        }
    

                    function questionBar(){
                    question=1;
                    for(var i=0;i<numOfQuestions/4;i++){
                        var div=document.createElement('div');
                        div.setAttribute('class','qRow');
                        div.setAttribute('id','qRow'+i);
                        document.getElementById('qNumbers').appendChild(div);
                        for(var j=1;j<=4;j++){
                            var a=document.createElement('a');
                            a.setAttribute('href','#');
                            a.setAttribute('class','qNum');
                            a.innerHTML=question;
                            a.setAttribute('id','qNum'+question);
                            a.setAttribute('onclick','goToQuestion(this)');
                            
                            document.getElementById('qRow'+i).appendChild(a);
                            question++;
                        }
                    }
                    }
        
        function goToQuestion(question){
            checked(curr);
            var x=question.innerHTML;
            document.getElementById('question'+curr).setAttribute('style','display:none');
            document.getElementById('answers'+curr).setAttribute('style','display:none');
            curr=x;
            document.getElementById('question'+curr).setAttribute('style','display:block');
            document.getElementById('answers'+curr).setAttribute('style','display:block');
            
            document.getElementById('prev').setAttribute('style','display:block');
            document.getElementById('next').setAttribute('style','display:block');
            document.getElementById('done').setAttribute('style','display:none');
            
        
            if(curr==1){
                document.getElementById('prev').setAttribute('style','display:block');
            }
            if(curr!=numOfQuestions){
                document.getElementById('next').setAttribute('style','display:block');
                document.getElementById('done').setAttribute('style','display:none');
                
            }
            if(curr==numOfQuestions){
                document.getElementById('next').setAttribute('style','display:none');
                document.getElementById('done').setAttribute('style','display:block');
                
            }
            checked(curr);
        }
        
        function checked(x){
            
            if(document.querySelector("input[name='question"+x+"']:checked") != null) {
               document.getElementById('qNum'+x).style.backgroundColor="#b6f3e8";
            }


        }
  
    var timer = new Timer();
    timer.start({countdown: true, startValues: {seconds: 1000}});
    $('#countdownExample .values').html(timer.getTimeValues().toString());
    timer.addEventListener('secondsUpdated', function (e) {
        $('#countdownExample .values').html(timer.getTimeValues().toString());
    });
    timer.addEventListener('targetAchieved', function (e) {
        $('#countdownExample .values').html('KABOOM!!');
    });
        
        
        
    
    </script>

</body>
</html>
