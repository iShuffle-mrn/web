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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<!-- Local CSS -->
	<link rel="stylesheet" type="text/css" href="..\css\upload.css">
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
<body>

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
		<div id="uploadForm">
            <ul class="nav nav-tabs">
                <li><a href="pdfUpload.php">העלאת מבחן מקובץ</a></li>
                <li class="active"><a href="#">העלאת מבחן באופן ידני</a></li>
            </ul>
			<form action="manualFileUpload.php" method="post" enctype="multipart/form-data">
				<div id="rightForm">
                    <p>במידה ויש בעיה בהעלאת קובץ הבחינה, ניתן להזין אותו בצורה ידנית. <br><b>תחילה, מלאו את הפרטים הבאים:</b></p>
					<p><label>שם הקורס: <input list="courses" name="course" name="course" required></label></p>
                    <p><label>שנת הבחינה: <input type="number" name="year" placeholder="YYYY" min="2010" max="2020" required></label></p>
				</div>
				<div id="leftForm">
					<p><label>מועד: 
						<select id="moed" name="moed" required>
							<option>א</option>
							<option>ב</option>
							<option>ג</option>
						</select></label></p>
					<p><label>סמסטר:
						<select id="semester" name="semester" required>
							<option>א</option>
							<option>ב</option>
							<option>קיץ</option>
						</select></label></p>
					<p><label>מס' שאלות במבחן: <input type="number" name="numOfQuestions" min="1" max="35" required></label></p>
					<p><label>מס' תשובות לשאלה: <input type="number" name="numOfAnswers" min="2" max="5" required></label></p>
				</div>
				<div id="buttons" class="clear">
					<input type="submit" value="המשך להזנת המבחן" name="submit" onclick="setData()">
				</div>
			</form> 
		</div>
		
	</main>

    <script>

        function setData(){

            var numOfQuestions=document.getElementsByName('numOfQuestions');
            var numOfAnswers=document.getElementsByName('numOfAnswers');
            var course=document.getElementsByName('course');
            var semester=document.getElementsByName('semester');
            var moed=document.getElementsByName('moed');
            var year=document.getElementsByName('year');
            sessionStorage.setItem("numOfQuestions",numOfQuestions.value);
            sessionStorage.setItem("numOfAnswers",numOfAnswers.value);
            sessionStorage.setItem("course",course.value);
            sessionStorage.setItem("semester",semester.value);
            sessionStorage.setItem("moed",moed.value);
            sessionStorage.setItem("year",year.value);

        }



    </script>
	
</body>
</html>