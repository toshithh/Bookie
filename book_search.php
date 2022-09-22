<?php
    include 'db_conn.php';
    $q = $_REQUEST["q"];
    $q = str_replace('"', "'", $q);
    $hint = "";
    $qry = "SELECT * FROM books where title LIKE \"%$q%\"";
    
    print("<button id=\"book-close\" onclick='close_books()' title=\"close\">X</button>");
    if ($result = $con->query($qry)) {
        print("<table>");
        while ($row = $result->fetch_assoc()) {
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

            print("<tr style='height: 10%;'>
            <td class=\"book_img\"><a href=\"book.php?q=$name\"><img src=\"$image\" width='120' height='150'></a></td>
            <td class=\"book_name\"><a href=\"book.php?q=$name\"><h3>$name</a></h3>$genre[0]</td>
            <td class=\"rating\" style='padding-left:2%;'>
            <h4><u>Rating:</u></h4>
            <div class='rating_bar' style='background-color: lightgrey; width:160px; height: 5px;'><div style='background-color: $color; width:$rate%; height:100%;'></div></div><br>$rating</td></tr>");
        }
        $result->free();
        print("</table>");
    }else{
        print("");
    }
    

?>