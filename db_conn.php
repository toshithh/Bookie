<?php
$server = "localhost";
$user = "Thadhjk";
$pass = "6wbwidh7";
$dbname = "bookie";
$con = mysqli_connect($server, $user, $pass, $dbname);
if(mysqli_connect_error()){
    print("Error connecting to server");
    exit();
}
?>
