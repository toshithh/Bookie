<?php
$server = "localhost";
$user = "toshith";
$pass = "2619";
$dbname = "bookie";
$con = mysqli_connect($server, $user, $pass, $dbname);
if(mysqli_connect_error()){
    print("Error connecting to server");
    exit();
}
?>