<?php
session_start();
if(!isset($_SESSION['loggedin'])){
    print("Please Login");
    exit();
}
include 'db_conn.php';
$title = $_REQUEST["q"];
$qry = "select id, title, image, authors, description, rating, genre, isbn, recommendations from books where title = ?";
$qry_ = "select id, image, authors, description, rating, genre from bookie.books where title = ?";
if ($stmt = $con->prepare($qry)){
    $stmt->bind_param('s', $title);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $title, $image, $author, $description, $rating, $genre, $isbn, $recommendations);
    $stmt->fetch();
    $genres = explode('|',$genre);
    $genre = $genres[0];
    $recommendations = explode("|", $recommendations);
    $db = $_SESSION['username'];
    $qry1 = "insert into $db.reading(title, authors, rating, genre, image)
    values(?, ?, ?, ?, ?);";
    if($stm = $con->prepare($qry1)){
        $stm->bind_param('sssss', $title, $author, $rating, $genre, $image);
        $stm->execute();
        print("Added");
        $stm->close();
    }
}
$j = sizeof($recommendations);
$i = 0;
while($i<$j){
    if ($stmt = $con->prepare($qry_)){
        $title = $recommendations[$i];
        $stmt->bind_param('s', $title);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $image, $author, $description, $rating, $genre);
        $stmt->fetch();
        $genres = explode('|',$genre);
        $genre = $genres[0];
        $qry1 = "insert into $db.recommendations(title, authors, rating, genre, image)
        values(?, ?, ?, ?, ?);";
        if($stm = $con->prepare($qry1)){
            $stm->bind_param('sssss', $title, $author, $rating, $genre, $image);
            $stm->execute();
            $stm->close();
        }
    }
    $i++;
}
?>