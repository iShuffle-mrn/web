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
            <h4>ברוך/ה הבא/ה <?php echo $_SESSION['givenName'] ?> |  
            <a href="glogin/logout.php" id="signOutButton">החלף משתמש</a></h4>
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
				<div>
				  <button id="course" type="button" class="btn" data-toggle="collapse" data-target="#openCourse1"><h3>כלים משפטיים</h3>&emsp;1 מבחן</button>
				  <div id="openCourse1" class="openCourse collapse">
					<p> מועד א, 16.5.18 &emsp;&emsp;&emsp;<a ref="#" class="exercise">לתרגול עצמי</a> |  <i class="fa fa-pencil"></i> <a ref="#" class="onlineTest">למבחן מקוון</a></p>
					<p> מועד א, 16.5.18 &emsp;&emsp;&emsp;<a ref="#" class="exercise">לתרגול עצמי</a> |  <i class="fa fa-pencil"></i> <a ref="#" class="onlineTest">למבחן מקוון</a></p>
				  </div>
				</div>
				
				<div>
				  <button id="course" type="button" class="btn" data-toggle="collapse" data-target="#openCourse2"><h3>אבטחת מידע ממוחשב</h3>&emsp;1 מבחן</button>
				  <div id="openCourse2" class="openCourse collapse">
					<p> מועד א, 16.5.18 &emsp;&emsp;&emsp;<a ref="#" class="exercise">לתרגול עצמי</a> |  <i class="fa fa-pencil"></i> <a ref="#" class="onlineTest">למבחן מקוון</a></p>
					<p> מועד א, 16.5.18 &emsp;&emsp;&emsp;<a ref="#" class="exercise">לתרגול עצמי</a> |  <i class="fa fa-pencil"></i> <a ref="#" class="onlineTest">למבחן מקוון</a></p>
				  </div>
				</div>
				
				<div>
				  <button id="course" type="button" class="btn" data-toggle="collapse" data-target="#openCourse3"><h3>ניהול איכות תוכנה</h3>&emsp;1 מבחן</button>
				  <div id="openCourse3" class="openCourse collapse">
					<p> מועד א, 16.5.18 &emsp;&emsp;&emsp;<a ref="#" class="exercise">לתרגול עצמי</a> |  <i class="fa fa-pencil"></i> <a ref="#" class="onlineTest">למבחן מקוון</a></p>
					<p> מועד א, 16.5.18 &emsp;&emsp;&emsp;<a ref="#" class="exercise">לתרגול עצמי</a> |  <i class="fa fa-pencil"></i> <a ref="#" class="onlineTest">למבחן מקוון</a></p>
				  </div>
				</div>
				
			</div>
		</div>
	
	</main>

	<!-- Footer -->



</body>
</html>
