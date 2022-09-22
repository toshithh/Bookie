<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookie</title>
    <link rel="stylesheet" href="style/home.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    function close_books(){
        document.getElementById('book_results').style.display = 'none';
    }
    </script>
    <?php
        session_start();
        if (!isset($_SESSION['loggedin'])) {
            print("<script>window.location.href='index.html'; </script>");
            exit();
        }
        $fnm = $_SESSION['fnm'];
        $lnm = $_SESSION['lnm'];
        $email = $_SESSION['email'];
        $gender = $_SESSION['gender'];
        $username = $_SESSION['username'];
        print "<script>";
        print "session_vars = ['$fnm', '$lnm', '$email', '$gender', '$username'];";
        print "</script>";
    ?>
    <script>
        function lookup(str){
            str=""+str;
            if (str.length == 0) {
                document.getElementById("book_results").style.display="none";
                return;
            } else if(str == null){
                document.getElementById("book_results").style.display="none";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        result = document.getElementById("book_results");
                        txt = this.responseText;
                        result.style.display="block";
                        if(txt){
                            result.innerHTML = txt;
                        }
                    }
                }
                xmlhttp.open("GET", "book_search.php?q="+str, true);
                xmlhttp.send();
            }
        }
    </script>
</head>

<body>
    <script>
        function logout(){
            window.location = "logout.php";
        }
    </script>
    <div id="top"><center><input placeholder="Search" type="text" id="bookSearch" onkeyup="lookup(this.value)"></center><span id="right"><button class="b-top" id="b-login">Profile</button>&#160; &#160;<button class="b-top" id="b-signup" onclick="window.location='logout.php'">Logout</button></span></div>
    
    <h1 id="title"></h1>
    <script>
        heading = "Hi "+session_vars[0];
        console.log(session_vars);
        x = 0;
        function wnm() {
            y = document.getElementById('title').textContent;
            if (x < heading.length) {
                document.getElementById("title").innerHTML += heading.charAt(x);
                x++;
                setTimeout(wnm, 300);
            }
        }
        window.onload = setTimeout(wnm,500);
    </script>

    <div id="main">
        <div id="my_reads" class="half">
            <h2 class="blue"><i>Continue Reading....</i></h2>
            <?php
            $server = "localhost";
            $user = "";
            $pass = "";
            $dbname = $username;
            $con = mysqli_connect($server, $user, $pass, $dbname);
            if(mysqli_connect_error()){
                print("Error connecting to server");
                exit();
            }
            $qry = "select * from reading";
            if($result = $con->query($qry)){
                print("<table>");
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $i = $i+1;
                    $book = $row['title'];
                    $author = $row['authors'];
                    echo "<tr style='color: gray;'>
                    <td class='sn'>$i.</td>
                    <td style='padding-left: 7%;' title='$author;width: 80%;'><a href='book.php?q=<?php print($book);>' style='color: gray; text-decoration:none;' target='_blank'>$book</td></tr>";
                }
                $result->free();
                print("</table>");
                if($i==0){
                    print("<p style='padding-left:10px;'>You aren't reading any books.</p>");
                }
            }
            ?>
        </div>
        <div id="wishlist" class="half">
            <h2 class="blue"><i>Next</i></h2>
            <?php
            $qry = "select * from wishlist";
            if($result = $con->query($qry)){
                print("<table>");
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $i = $i+1;
                    $book = $row['title'];
                    $author = $row['authors'];
                    echo "<tr style='color: gray;'>
                    <td class='sn'>$i.</td>
                    <td style='width: 80%;padding-left: 7%;' title='$author'><a href='book.php?q=<?php print($book);>' style='color: gray; text-decoration:none;' target='_blank'>$book</td></tr>";
                }
                $result->free();
                print("</table>");
                if($i==0){
                    print("<p style='padding-left:10px;'>Nothing in your wishlist yet.</p>");
                }
            }
            ?>
        </div>
        
        <div id="recommendations">
            <?php
            $qry = "select * from recommendations";
            if($result = $con->query($qry)){
                print("<table>");
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $i = $i+1;
                    $book_id = $row['id'];
                    $name = $row['title'];
                    $image = $row['image'];
                    $genre = $row['genre'];
                    $genre = explode("|", $genre);
                    $rating = $row['rating'];
                    if ($rating){
                        $rate = (float)$rating;
                        $rate = ($rate/5)*100;
                    }else{
                        $rate = 0;
                    }
                    if ($rate >79.0){
                        $color = "rgb(51, 255, 51)";
                    }else if($rate>50.0){
                        $color = "rgb(254, 221, 111)";
                    }else{
                        $color="red";
                    }
                    print("<tr style='padding: 3%;'>
                    <td class=\"book_img\"><img src=\"$image\" width='120' height='150'></td>
                    <td class=\"book_name\"><h3><a href=\"book.php?q=$name\">$name</a></h3>$genre[0]</td>
                    <td class=\"rating\" style='padding-left:2%;'>
                    <h4><u>Rating:</u></h4>
                    <div class='rating_bar' style='background-color: lightgrey; width:160px; height: 5px;'><div style='background-color: $color; width:$rate%; height:100%;'></div></div><br>$rating</td></tr>");
                }
                $result->free();
                print("</table>");
                if($i==0){
                    print("<p style='padding-left:10px;'>Nothing here mate.</p>");
                }
            }
            ?>
        </div>
    </div>
</div>
<div id="book_results">
</div>
</body>
