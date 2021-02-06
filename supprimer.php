<?php require "SQLqueries.php"; ?>
<?php
if(!isset($_SESSION)) {session_start();}
if(isset($_GET["id_comp"])){
	$row = mysqli_fetch_assoc(get_competition($_GET["id_comp"]));
	if($row["id_org"] == $_SESSION["id"]){
		delete_competition($_GET["id_comp"]);
	}
}

header("Location: mescompetitions.php");
?>