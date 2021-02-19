<?php
require 'SQLqueries.php';



if(!isset($_GET["id_user"])){header("Location: index.php");}
if(!is_numeric($_GET["id_user"])){header("Location: competition.php");}
if(!isset($_SESSION)) {session_start();}

//Generate options for user and others for organizer
$user = get_participant($_GET["id_user"]);

$show = true;

//Check credentials
if($_SESSION["connectedAs"] == "participant"){
    $current_user = get_participant($_SESSION["id"]);
    if($current_user["id_comp"] != $user["id_comp"]){
        $show = false;
    }
}elseif($_SESSION["connectedAs"] == "organisateur"){
    $comp = mysqli_fetch_assoc(get_competition($user["id_comp"]));
    if($comp["id_org"] != $_SESSION["id"]){
        $show = false;
    }
}

if($show){
    echo '<span class="titre"><h1>'.$user["name"].'</h1></span><br>';
    echo '<h2>Identificateur: '.$_GET["id_user"].'</h2>';

    if($user["is_guest"] == 0){
        echo '<h2>Adresse email: <b>'.$user["email"].'</b></h2><br>';
    }else{
        echo '<h2>Cet utilisateur n\'a pas d\'adresse email</h2><br>';
    }
    echo '<b id="points">Points: '.$user["points"].'</b>';

    if($_SESSION["connectedAs"] == "organisateur"){
        echo '<br><button id="add" class="btn btn-danger" onclick="changer_points(1)">+</button> <button id="sub" class="btn btn-danger" onclick="changer_points(-1)">-</button><br><br>
        <button class="btn btn-danger" data-toggle="modal" data-target="#supprimer">Supprimer participant</button>';
    }
}
?>