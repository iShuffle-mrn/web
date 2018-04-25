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
		<div id="uploadForm">
			<form action="uploadFile.php" method="post" enctype="multipart/form-data">
				<div id="rightForm">
					<p><input type="file" name="file" id="file" class="inputfile" accept=".pdf" required>
					    <label for="file"><span id="fileChange" class="span"><img id="formPic" class="pointer" src="../pic/formpic.png"></span></label>
						</p>
					<p><label>שם הקורס: <input type="text" name="course" required autocomplete="on"	></label></p>
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
					<p><label>מס' שאלות במבחן: <input type="number" name="numOfQuestions" min="6" max="35" required></label></p>
					<p><label>מס' תשובות לשאלה: <input type="number" name="numOfAnswers" min="3" max="5" required></label></p>
				</div>
				<div class="clear">
					<input type="submit" id="uploadButton" class="pointer" value="לחץ להעלאה" name="submit">
				</div>
			</form> 
		</div>
		<a href="../index.php" id="homeButton">למסך הראשי</a>
	</main>
	
	
	<!-- Script -->
	<script>
		document.getElementById("file").addEventListener('change', function(e)
			{
				var fileName = document.getElementById("file").value.split( '\\' ).pop();
					document.querySelector('.span').innerHTML = " <i class='fa fa-upload'></i>"+fileName;
                    document.getElementById("fileChange").classList.remove('span');
                    document.getElementById("fileChange").classList.add('newSpan');
			});
	</script>
</body>
</html>