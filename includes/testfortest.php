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
                 

        
        // Read JSON file
        
         $json = file_get_contents('../PDFconvertor/tests/10.json');


        //Decode JSON
         $json_data = json_decode($json,true);
        $question=3;
            echo sizeof($json_data['question'.$question]);
    


                    
                
                ?>

		

