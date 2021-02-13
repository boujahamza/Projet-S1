<?php
require "SQLqueries.php";

if(!isset($_GET["id_comp"])){header("Location: index.php");}

$users = participants($_GET["id_comp"]);


if(mysqli_num_rows($users) > 0){
    //go through all participants
    while($row = mysqli_fetch_assoc($users)){
        if($row["has_access"] == 1){
            echo '<div id="'.$row["id_user"].'" class="item-participant border border-dark" onclick="select(this.id)">';
            if($row["is_guest"] == 0){
                echo '<img class="user-icon" src="images/user.png" title="Participant inscrit">';
            }
            echo '<h5>'.$row["name"].'</h5>           
            <b id="points-'.$row["id_user"].'">Points: '.$row["points"].'</b>     
            </div>';
        }
    }
}
?>