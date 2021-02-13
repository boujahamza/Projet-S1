<?php
require 'SQLqueries.php';

if(!isset($_GET["id_user"])){header("Location: index.php");}
if(!isset($_SESSION)) {session_start();}

//On doit generer des options pour l'organisateur et d'autres pour les paricipants
$user = get_participant($_GET["id_user"]);

echo '<div class="titre"><h1>'.$user["name"].'</h1></div><br>';
echo '<h2>identificateur: '.$_GET["id_user"].'</h2>';

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
?>