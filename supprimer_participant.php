<?php require "SQLqueries.php"; ?>
<?php
if(!isset($_SESSION)) {session_start();}

if($_SESSION["connectedAs"]=="organisateur"){
	if(isset($_GET["id_user"]) and isset($_GET["id_comp"])){
		$row = mysqli_fetch_assoc(get_competition($_GET["id_comp"]));
		if($row["id_org"] == $_SESSION["id"]){
			delete_participant($_GET["id_user"]);
		}
	}
}

header("Location: competition.php?id_comp=".$_GET['id_comp']);
?>