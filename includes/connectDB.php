<?php header('Content-Type: text/html; charset=utf-8'); 

   ########## MySql details  #############
    $db_username = "root"; //Database Username
    $db_password = "Fss2d%^4D"; //Database Password
    $host_name = "localhost"; //Mysql Hostname
    $db_name = 'ishuffle'; //Database Name

    // connect to database
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }
    $mysqli->set_charset("utf8");

?>