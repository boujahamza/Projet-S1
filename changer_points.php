<?php require "SQLqueries.php"; ?>
<?php
if(!isset($_SESSION)) {session_start();}

if($_SESSION["connectedAs"]=="organisateur"){
	if(isset($_GET["id_user"]) and isset($_GET["num"])){
		$user = get_participant($_GET["id_user"]);
		if(!($user["points"] == 0 and $_GET["num"] < 0)){
            add_points($_GET["id_user"],$_GET["id_comp"],$_GET["num"]);
            echo "Points: ".($user["points"] + $_GET["num"]);
        }else{
            echo "Points: ".$user["points"];
        }
	}
}
?>