<?php require "SQLqueries.php"; ?>
<?php
if(!isset($_SESSION)) {session_start();}

if($_SESSION["connectedAs"]=="organisateur"){//Check if it's an organizer
	if(isset($_GET["id_user"]) and isset($_GET["id_comp"]) and is_numeric($_GET["id_user"]) and is_numeric($_GET["id_comp"])){//Check if all info is given and avoid sql injections
		$row = mysqli_fetch_assoc(get_competition($_GET["id_comp"]));
		if($row["id_org"] == $_SESSION["id"]){//Check organizer credentials
			delete_participant($_GET["id_user"]);
		}
	}else{
		header("Location: competition.php");
	}
}

header("Location: competition.php?id_comp=".$_GET['id_comp']);
?>