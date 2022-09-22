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
    $username = str_replace("@", "", $email);
    $username = str_replace(".", "", $username);
    print($username);
    $qry = "insert into users(FirstName, LastName, email, password, phone, gender, username)
            values(?, ?, ?, ?, ?, ?, ?);";
    if($stmt = $con->prepare($qry)){
        $stmt->bind_param('sssssss', $firstname, $lastname, $email, $password, $phone, $gender, $username);
        print('done');
        $stmt->execute();
        $stmt->fetch();
    }else{
        exit;
    }
    if($stmt = $con->prepare("create database $username;")){
        $stmt->execute();
        print("Done");
    }else{
        print("error");
        exit;
    }
    if($stmt = $con->prepare("CREATE TABLE $username.recommendations( title varchar(255) unique, authors varchar(200), rating varchar(5), genre varchar(100), image varchar(255) );")){
        $stmt->execute();
    }
    if($stmt = $con->prepare("CREATE TABLE $username.wishlist( title varchar(255) unique, authors varchar(200), rating varchar(5), genre varchar(100), image varchar(255) );")){
        $stmt->execute();
    }
    if($stmt = $con->prepare("CREATE TABLE $username.reading( title varchar(255) unique, authors varchar(200), rating varchar(5), genre varchar(100), image varchar(255) );")){
        $stmt->execute();
    }
    session_regenerate_id();
    $_SESSION['loggedin'] = True;
    $_SESSION['fnm'] = $FirstName;
    $_SESSION['lnm'] = $LastName;
    $_SESSION['gender'] = $gender;
    $_SESSION['phone'] = $phone;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
    $stmt->close();
    print("<script>window.location.href='home.php'; </script>");
}
?>