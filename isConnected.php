<?php
//CHECK IF ALREADY SIGNED IN
if(!isset($_SESSION)) {session_start();}
            
if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
	$_SESSION["isConnected"] = "false";
}
?>		