<?php
	if(!isset($_SESSION)) {session_start();}
            
	if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
		$_SESSION["isConnected"] = "false";
	}
	

	$_SESSION["isConnected"] = "false";
	$_SESSION["id"] = -1;
	$_SESSION["connected_as"] = "none";
	header("Location: index.php");
?>