<?php
session_start();
if(isset($_SESSION['loggedin'])){
    print("<script>window.location.href='home.php'; </script>");
    exit();
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'db_conn.php';
    $email = $_POST['email'];
    $password = $_POST['password'];
    $qry = "SELECT  FirstName, LastName, password, gender, phone, username FROM users where email = ?;";
    if ($stmt = $con->prepare($qry)){
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($FirstName, $LastName, $cpass, $gender, $phone, $username);
        $stmt->fetch();
        if($password===$cpass){
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
        else{
            print("Incorrect username/password");
            print("<script>window.location.href='index.html'; </script>");
            exit;
        }
    }else{
        print("ERrOR");
        exit;
    }
}

?>