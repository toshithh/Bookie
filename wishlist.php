<?php
session_start();
if(!isset($_SESSION['loggedin'])){
    print("Please Login");
    exit();
}
include 'db_conn.php';
$title = $_REQUEST["q"];
$qry = "select id, title, image, authors, description, rating, genre, isbn, recommendations from books where title = ?";
if ($stmt = $con->prepare($qry)){
    $stmt->bind_param('s', $title);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $title, $image, $author, $description, $rating, $genre, $isbn, $recommendations);
    $stmt->fetch();
    $genres = explode('|',$genre);
    $genre = $genres[0];
    $db = $_SESSION['username'];
    $qry1 = "insert into $db.wishlist(title, authors, rating, genre, image)
    values(?, ?, ?, ?, ?);";
    if($stm = $con->prepare($qry1)){
        $stm->bind_param('sssss', $title, $author, $rating, $genre, $image);
        $stm->execute();
        print("Added");
    }
}
?>