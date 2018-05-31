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
    <link rel="stylesheet" type="text/css" href="..\css\discussion.css">
	
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
<body>

<!-- Page Content -->

	<!-- Main -->
	<main>
        
    <?php header('Content-Type: text/html; charset=utf-8'); 
        $test_id= $_GET['test_id'];
        $question= $_GET['question'];
        
        require_once "connectDB.php";
        
        $email = $_SESSION['email'];
        
        // first select rows of first level comments (answered_to is null)
        $sql = "SELECT * FROM comments WHERE test_id=$test_id and question_num=$question and answered_to is null ORDER BY comment_id DESC";   
        $comments = $mysqli->query($sql);

    ?>
        
        <div id="commentForm">
                <?php  
                       
                    // check if form was submitted
                    if(isset($_POST['addComment'])){ 
                        $addSubject = $_POST['comment']; 

                        // if form for second level was submitted - add the answered_to value
                        if(isset($_POST['comment_id'])){
                            $id = $_POST['comment_id'];
                            $sql = "INSERT INTO comments (answered_to, question_num, test_id, user_email, subject) VALUES ($id ,$question, $test_id, '$email', '$addSubject')"; 
                        }

                        // if the main form was submitted - insert the first level comment
                        else{
                          $sql = "INSERT INTO comments (question_num, test_id, user_email, subject) VALUES ($question, $test_id, '$email', '$addSubject')"; 
                        }

                          $result = $mysqli->query($sql);
                          header("Refresh:0");
                    }
                    
                    // check if pressFactor button was clicked
                    if (isset($_POST['pressFactor'])){
                        $sql = "UPDATE question_flags SET is_factor=0 WHERE test_id=$test_id and question=$question and user_email='$email'"; 
                        $result = $mysqli->query($sql);
                        header("Refresh:0");
                    }
                    
                    // check if noFactor button was clicked
                    if (isset($_POST['noFactor'])){
                        $sql = "SELECT * FROM question_flags WHERE test_id=$test_id and question=$question and user_email='$email'"; 
                        $result = $mysqli->query($sql);
                        
                        if ($result->num_rows == 0){
                            $sql = "INSERT INTO question_flags (test_id, question, user_email, is_factor) VALUES ($test_id, $question, '$email', 1)"; 
                            $result = $mysqli->query($sql);
                        }
                        
                        // if not pressed - press
                        else {
                            $userState = $result->fetch_assoc();
                            $sql = "UPDATE question_flags SET is_factor=1 WHERE test_id=$test_id and question=$question and user_email='$email'";  
                            $result = $mysqli->query($sql);
                        }
                        
                        header("Refresh:0");
                    }
            
                    // check if delete1 button was clicked
                    if (isset($_POST['delete1'])){
                        $id = $_POST['comment_id'];
                        $sql = "SELECT * FROM comments WHERE test_id=$test_id and question_num=$question and answered_to=$id";   
                        $moreComments = $mysqli->query($sql);
                        if ($moreComments->num_rows>0){
                            $sql = "UPDATE comments SET subject='הודעה זו נמחקה' WHERE comment_id=$id";  
                            $result = $mysqli->query($sql);
                        }
                        
                        else{
                            $sql = "DELETE FROM comments WHERE comment_id=$id"; 
                            $result = $mysqli->query($sql);
                        }
                        
                        header("Refresh:0");
                    }

                    // check if delete2 button was clicked
                    if (isset($_POST['delete2'])){
                        $id = $_POST['comment_id'];
                        $sql = "DELETE FROM comments WHERE comment_id=$id"; 
                        $result = $mysqli->query($sql);
                        
                        header("Refresh:0");
                    }

                    $sql = "SELECT * FROM question_flags WHERE test_id='$test_id' and question='$question' and user_email='$email'"; 
                    $userResult = $mysqli->query($sql);
                    $userChecked = $userResult->fetch_assoc();
                    $factor = false;
            
                    if ($userChecked['is_factor'] == 1){
                        echo '<form class="inlineForm" method="post">';
                        echo '<label class="factor"><img src="../pic/star.png"> ';
                        echo '<input type="submit" name="pressFactor" value="סימנת בתור פקטור"></label>';
                        echo '</form>';
                        $factor = true;
                    }
                        
                    else{
                        echo '<form class="inlineForm" method="post">';
                        echo '<label class="factor"><img src="../pic/star-nocolor.png"> ';
                        echo '<input type="submit" name="noFactor" value="סמן בתור פקטור"></label>';
                        echo '</form>';
                    }
            
                    $sql = "SELECT * FROM question_flags WHERE test_id='$test_id' and question='$question' and is_factor=1"; 
                    $otherResult = $mysqli->query($sql);
                    if ($factor)
                        $friends = $otherResult->num_rows-1;
                    else
                        $friends = $otherResult->num_rows;
                    if ($friends>0)
                        echo "<span>   $friends מחברייך סימנו כפקטור</span>";
            
                ?>

                <form method="post">
                    <label>הוסף תגובה: <input type="text" name="comment" required autocomplete="off"></label>
                    <input type="submit" name="addComment" value="שלח">
                </form>
            
        </div>

        <div id="discussion">

            <?php

                // if there are comments for this question
                if ($comments->num_rows>0){

                    //print every first level comment
                    while($row=$comments->fetch_assoc()){

                        $commenter = $row['user_email'];
                        $sql = "SELECT google_name, google_picture_link FROM google_users WHERE google_email='$commenter'";   
                        $whoIsCommenter = $mysqli->query($sql);
                        $commenter = $whoIsCommenter->fetch_assoc();

                        echo '<div class="comment"><img src="'.$commenter['google_picture_link'].'">';
                        echo '<div><p class="message"><span class="name">'.$commenter['google_name'].'</span><br>';
                        echo '<span class="subject">'.$row['subject'].'</span></p><br>';
                        echo '<p><span class="date">'.$row['date'].'</span> | <a href="#" id="'.$row['comment_id'].'" class="openForm">הגב</a>';
                        $comId = $row['comment_id'];
                        if ($row['user_email'] == $email){
                            echo ' | <form method="post" class="inlineForm"><input type="hidden" name="comment_id" value='.$comId.'><input type="submit" name="delete1" value="מחק" class="delete"></form>';
                        }
                        echo '</p></div></div>';

                        // check if this first level comment has second level comments
                        $commentId = $row['comment_id'];
                        $sql = "SELECT * FROM comments WHERE test_id=$test_id and question_num=$question and answered_to=$commentId";   
                        $moreComments = $mysqli->query($sql);

                        // create empty div for future comment form (with jquery)
                        echo '<div id="form'.$row['comment_id'].'"></div>';

                        // print each second level comment form this comment
                        if ($moreComments->num_rows>0){
                            while($row=$moreComments->fetch_assoc()){
                                $commenter = $row['user_email'];
                                $sql = "SELECT google_name, google_picture_link FROM google_users WHERE google_email='$commenter'";   
                                $whoIsCommenter = $mysqli->query($sql);
                                $commenter = $whoIsCommenter->fetch_assoc();

                                echo '<div class="comment answer"><img  src="'.$commenter['google_picture_link'].'">';
                                echo '<div><p class="message diffColor"><span class="name">'.$commenter['google_name'].'</span> <br>';
                                echo '<span class="subject">'.$row['subject'].'</span></p><br>';
                                echo '<p><span class="date answer2">'.$row['date'].'</span>';
                                $comId = $row['comment_id'];
                                if ($row['user_email'] == $email)
                                    echo ' | <form method="post" class="inlineForm"><input type="hidden" name="comment_id" value='.$comId.'><input type="submit" name="delete2" value="מחק" class="delete"></form>';
                                echo '</p></div></div>';

                            }
                        }
                    }
                }
            
                else{
                    echo 'תיהיה הראשון להגיב על שאלה זו.';
                }
            ?>
            
        </div>
                

	</main>
    
<script type="text/javascript">
    
    $(document).ready(function(){
        $(".openForm").click(function(){
            var HTML = '<form method="post"><input type="hidden" name="comment_id" value="'+this.id+'"><label>תגובה: <input type="text" name="comment" required autocomplete="off"></label><input type="submit" name="addComment" value="שלח"></form>';
            $("#form"+this.id).html(HTML);
        });
    });
</script>
    

</body>
</html>