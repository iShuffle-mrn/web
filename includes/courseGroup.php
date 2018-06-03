<?php
	session_start();

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
	<link rel="stylesheet" type="text/css" href="..\css\courseGroup.css">
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
		<div id="groupBox">
            <?php
                $course_id= $_GET['course_id'];
                $course_name= $_GET['course_name'];
            
                require_once "connectDB.php";
            
            ?>
            <h3 id="courseTitle">קבוצת <?php echo $course_name ?></h3>
            
            <div id="friends">
                <h5>החברים שלי:</h5>
                <?php
                    $sql = "SELECT * FROM users_in_courses WHERE course_id='$user_email'"; 
                    $result = $mysqli->query($sql);
                
                    while($row = $result->fetch_assoc()) {
                        $user_email = $row['user_email'];
                        $sql = "SELECT * FROM google_users WHERE google_email='$user_email'"; 
                        $profile = $mysqli->query($sql);
                        while($row = $profile->fetch_assoc()) {
                            $pic = $row['google_picture_link'];
                            $name = $row['google_name'];
                        }
                        echo '<div class="profile"><img src="'.$pic.'"><br><p>'.$name.'</p></div>';
                    }
                    ?>
            </div>
            
            <div id="addFriendForm">
                <?php    
                    if(isset($_POST['addFriend'])){ //check if form was submitted
                        $toEmail = $_POST['email']; //get email
                        $fromEmail = $_SESSION['email'];
                        $sql = "SELECT * FROM google_users WHERE google_email='$user_email'"; 
                        $isNew = $mysqli->query($sql);
                                                
                        $sql = "SELECT * FROM users_in_courses WHERE course_id='$course_id' AND user_email='$toEmail'"; 
                        $result = $mysqli->query($sql);
                        
                        if ($result->num_rows>0){
                          $message = "המשתמש ".$toEmail." כבר משוייך לקורס זה.";
                        }
                        else{
                            $sql = "SELECT * FROM google_users WHERE google_email='$course_id' AND user_email='$toEmail'"; 
                            $result = $mysqli->query($sql);
                            
                            $sql = "INSERT INTO invitations (course_id, fromUser, toUser) VALUES ($course_id, '$fromEmail', '$toEmail')"; 
                            $result = $mysqli->query($sql);
                            if ($isNew->num_rows == 0){
                                $message = "המשתמש ".$toEmail." לא קיים במערכת, ההזמנה תופיע לו בעת התחברותו.";
                            }
                            $message = "המשתמש ".$email." הוזמן בהצלחה!";
                        }
                      
                    }    
                ?>
                <form method="post">
                    <p><label for="email">הזמן חבר לקורס: </label>
                    <input id="email" type="email" name="email" placeholder="כתובת המייל של החבר" required autocomplete="on">
                    <input type="submit" name="addFriend" value="הוסף"></p>
                    <?php echo $message; ?>
                </form>
                
            </div>
		</div>
<!--		<a href="../index.php" id="homeButton">למסך הראשי</a>-->
	</main>
    
    <script>
        $(document).ready(function(){
            $("#addprofile").click(function(){
                $("#friends").hide();
                $("#addFriendForm").show();
            });
        });
    </script>
	
</body>
</html>