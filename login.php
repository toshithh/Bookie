<?php
session_start();
if(isset($_SESSION['loggedin'])){
    header('Location: http://192.168.74.192/home.php');
    exit;
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include 'db_conn.php';
    $email = $_POST['email'];
    $password = $_POST['password'];
    $qry = "SELECT  FirstName, LastName, password, gender, phone FROM users where email = ?;";
    if ($stmt = $con->prepare($qry)){
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($FirstName, $LastName, $cpass, $gender, $phone);
        $stmt->fetch();
        if($password===$cpass){
            session_regenerate_id();
            $_SESSION['loggedin'] = True;
            $_SESSION['fnm'] = $FirstName;
            $_SESSION['lnm'] = $LastName;
            $_SESSION['pswd'] = $cpass;
            $_SESSION['gender'] = $gender;
            $_SESSION['phone'] = $phone;
            $stmt->close();
            header('Location: http://192.168.74.192/home.php');
        }
        else{
            print("Incorrect username/password");
            print($cpass);
            header('Location: http://192.168.74.192/');
            exit;
        }
    }else{
        print("ERrOR");
        exit;
    }
}

?>