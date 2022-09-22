<?php
$server = "localhost";
$user = "";
$pass = "";
$dbname = "";
$con = mysqli_connect($server, $user, $pass, $dbname);
if(mysqli_connect_error()){
    print("Error connecting to server");
    exit();
}
?>
