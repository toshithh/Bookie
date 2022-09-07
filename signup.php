<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'db_conn.php';
    $errphone = $errpassword = $erremail = $errfirstname = $errlastname = $errgender = "";
	$phone = $password = $email = $firstname = $lastname = $gender = "";
    $firstname = $_POST["FirstName"];
    $lastname = $_POST["LastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $gender = $_POST["Gender"];
    $qry = "insert into users(FirstName, LastName, email, password, phone, gender)
            values(?, ?, ?, ?, ?, ?);";
    if($stmt = $con->prepare($qry)){
        $stmt->bind_param('ssssss', $firstname, $lastname, $email, $password, $phone, $gender);
        print('done');
        $stmt->execute();
        $stmt->fetch();
    }else{
        exit;
    }
    if($stmt = $con->prepare("create database ?;")){
        $stmt->bind_param('s', $email);
        $stmt->execute();
        print("Done");
    }else{
        print("error");
        exit;
    }
    header('Location: http://192.168.74.192/');
}
?>