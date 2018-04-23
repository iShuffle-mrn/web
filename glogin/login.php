<?php
    require_once "config.php";

	if (isset($_SESSION['access_token'])) {
		header('Location: ../index.php');
		exit();
	}

	$loginURL = $gClient->createAuthUrl();
?>

<!DOCTYPE html>
<html>
<head>
	<title>iShuffle - למידה למבחנים רב ברירתיים</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Local CSS -->
	<link rel="stylesheet" type="text/css" href="..\css\signin.css">
	<link rel="stylesheet" type="text/css" href="..\css\mainStyle.css">
	
	<!-- Bootstap links -->
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
		<img src="../pic/logo.png">
		<p>סיוע בלמידה למבחנים רב- ברירתיים</p>
		<img id="tape" src="../pic/tape.png">
	</header>
	

	<!-- Main -->
	<main>
		<div id="connectionForm">
            <p>אנא התחבר לחשבון Google לצורך שימוש בשירותי האתר.</p>
            <button onclick="window.location = '<?php echo $loginURL ?>';">התחבר <img src="../pic/google_icon.png" width="50%"></button>
        </div>
	</main>
	

</body>
</html>
