<?php
include 'db_conn.php';
$title = $_REQUEST["q"];
$qry = "select id, title, image, authors, description, rating, genre, isbn, recommendations from books where title = ?";
if ($stmt = $con->prepare($qry)){
    $stmt->bind_param('s', $title);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $title, $image, $author, $description, $rating, $genre, $isbn, $recommendations);
    $stmt->fetch();
    $genres = str_replace('|', ',',$genre);
}
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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $title; ?></title>
    <link rel="stylesheet" href="style/book.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    function close_books(){
        document.getElementById('book_results').style.display = 'none';
    }
    </script>
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
        function home(){
            window.location.href = "home.php";
        }
    </script>
</head>

<body>
    <div id="top"><center><input placeholder="Search" type="text" id="bookSearch" onkeyup="lookup(this.value)"></center><span id="right"><button class="b-top" id="b-signup" onclick="home()">Home</button></span></div>
    <div id="book">
        <table>
            <tr style="height:15%; text-align: left;">
                <td style="width: 27%;"><img src="<?php print "$image";?>" width="240" height="300"></td>
                <td style="width: 40%; padding-left: 4%;" class="left">
                    <h1><?php print("$title");?></h1>
                    -<?php echo "$author";?>
                    <p>Genres</p><br>
                    <button id="add_to_wishlist" onclick="add_to_wishlist()">Wishlist</button>  <button id="add_to_reading" onclick="add_to_reading()">Reading</button>
                    <script>
                    function add_to_wishlist(){
                        str = "<?php print("$title");?>";
                        if (str.length == 0) {
                                return;
                        } else {
                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {
                                        txt = this.responseText;
                                        if(txt=="Added"){
                                            window.alert(str+" Added");
                                            document.getElementById("add_to_wishlist").style.display="none";
                                        }else{
                                            window.alert("Please log in first.");
                                        }
                                    }
                                }
                                xmlhttp.open("GET", "wishlist.php?q="+str, true);
                                xmlhttp.send();
                        }
                    }
                    function add_to_reading(){
                        str = "<?php print("$title");?>";
                        if (str.length == 0) {
                                return;
                        } else {
                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {
                                        txt = this.responseText;
                                        if(txt=="Added"){
                                            window.alert(str+" reading");
                                            document.getElementById("add_to_reading").style.display="none";
                                        }else{
                                            window.alert("Please log in first.");
                                        }
                                    }
                                }
                                xmlhttp.open("GET", "reading.php?q="+str, true);
                                xmlhttp.send();
                        }
                    }
                    </script>
                </td>
                <td style="padding-left: 10%;">
                    <?php print "<div class='rating_bar' style='background-color: lightgrey; width:160px; height: 5px;'><div style='background-color: $color; width:$rate%; height:100%;'></div></div><br>$rating";?><br><br><br>
                    <a href="http://www.pdfdrive.com/search?q=<?php print("$title");?>" target="_blank"><button id="download">Download</button>
                </td>
            </tr>
        </table>
        <p style="padding-top: 1%;">
        <h2>Description</h2>
        <?php print("$description");?></p>
    </div>

    <div id="book_results">
    </div>