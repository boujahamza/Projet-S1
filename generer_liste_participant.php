<?php
require "SQLqueries.php";

if(!isset($_GET["id_comp"])){header("Location: index.php");}

if(!is_numeric($_GET["id_comp"])){header("Location: competition.php");}

if(!isset($_SESSION)) {session_start();}

$users = participants($_GET["id_comp"]);

//Check credentials
$show = true;
if($_SESSION["connectedAs"] == "participant"){
    $current_user = get_participant($_SESSION["id"]);
    if($current_user["id_comp"] != $_GET["id_comp"]){
        $show = false;
    }
}elseif($_SESSION["connectedAs"] == "organisateur"){
    $comp = mysqli_fetch_assoc(get_competition($_GET["id_comp"]));
    if($comp["id_org"] != $_SESSION["id"]){
        $show = false;
    }
}

if($show and mysqli_num_rows($users) > 0){
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