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
            <h4>ברוך/ה הבא/ה <?php echo $_SESSION['givenName'] ?> |  
            <a href="glogin/logout.php" id="signOutButton">החלף משתמש</a></h4>
        </div>
	</header>

	<!-- Main -->
	<main>
        
        <?php

        // Read JSON file
         $json = file_get_contents('../PDFconvertor/output2-json.json');


        //Decode JSON
         $json_data = json_decode($json,true);

         ?>
        
		<div id="aside">
            <h1>מצב מבחן</h1>
            <h4>השאלות:</h4>
            <div id="qNumbers">
     
            </div>
        </div>
        <div id="testForm">
			<form id=FormTest action="post">
				<?php
                //*****select from db
                    $numOfQuestions=20;
                    $numOfAnswers=4;
                    $wholeQuestion=1;
                    $QuestionOrAnswer=0;
                    $question=1;
                    $answer=0;

                
                echo "<div id='question".$question."'><h2>שאלה מספר ".$question."</h2><h4><label for='question".$question."'>".$json_data['question'.$question][1]."</label></h4><div id='answers".$question."'>";
                    for($answer=0;$answer<$numOfAnswers;$answer++){
                        echo "<p><input name='question".$question."' type='radio' id='answer".$question."_".$answer."' value='1'>".$json_data['answer'.$question.'_'.$answer][0]."</p>";
                    }
                echo "</div>";
                echo "</div>";
                    

                    for($question=2;$question<=$numOfQuestions;$question++){
                        
                        echo "<div id='question".$question."' style='display:none'><h2>שאלה מספר ".$question."</h2><h4><label for='question".$question."'>".$json_data['question'.$question][1]."</label></h4><div id='answers".$question."'style='display:none'>";
                        for($answer=0;$answer<$numOfAnswers;$answer++){
                        echo "<p><input name='question".$question."'type='radio' id='answer".$question."_".$answer."'value='1'>".$json_data['answer'.$question.'_'.$answer][0]."</p>";
                        }
                        echo "</div>";
                         echo "</div>";
                    } 
                    
                
                ?>

                <a id="next" href="#" onclick="next()"><i class="fa fa-angle-left"></i></a>
                <a id="prev" href="#" onclick="prev()" style="display:none"><i class="fa fa-angle-right"></i></a>
                <button id="done" type="submit" style="display:none">סיים מבחן</button>
                
            </form> 
		</div>
        <div>

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
  
        
    </script>

</body>
</html>
