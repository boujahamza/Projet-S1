<?php

$host = "localhost";
$username = "root";
$db_password = "";
$db_name = "boujastuff";

function adapt($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return($data);
}

function signup($name,$email,$password){
	//1 means email taken
	//2 means signup successful
	
	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}

	$password = password_hash($password, PASSWORD_DEFAULT);//Password hashing

	$query1 = "SELECT email FROM organizer";
	$query2 = "INSERT INTO organizer(name,email,password) VALUES ('" .$name. "','" .$email. "','" .$password. "')";

	$emails = mysqli_query($conn, $query1);
	

	if (mysqli_num_rows($emails) > 0) {
		// go through names of each organizer
		while($row = mysqli_fetch_assoc($emails)) {
			if($row["email"] == $email){
				return 1;
			}
		}
	}

	if(mysqli_query($conn,$query2)){
		mysqli_close($conn);
		return 2;
	}
	
	echo "Unexpected error";
	
}

function signin($email, $password){
	//1 means email doesn't exist
	//2 means password wrong
	//3 means signup successful

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}

	$query = "SELECT email,password FROM organizer";
	$email_password = mysqli_query($conn, $query);
	mysqli_close($conn);

	if (mysqli_num_rows($email_password) > 0) {
		// go through names of each organizer
		while($row = mysqli_fetch_assoc($email_password)) {
			if($row["email"] == $email){
				if(password_verify($password, $row["password"])){
					return 3;
				}else{
					return 2;
				}
			}
		}
		return 1;
	}else if(mysqli_num_rows($email_password) == 0){
		return 1;
	}

	echo "Unexpected error";
	
}

function signin_participant($email, $password){
	//1 means email doesn't exist
	//2 means password wrong
	//3 means signup successful

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}

	$query = "SELECT email,password FROM users";
	$email_password = mysqli_query($conn, $query);
	mysqli_close($conn);

	if (mysqli_num_rows($email_password) > 0) {
		// go through names of each organizer
		while($row = mysqli_fetch_assoc($email_password)) {
			if($row["email"] == $email){
				if(password_verify($password, $row["password"])){
					return 3;
				}else{
					return 2;
				}
			}
		}
		return 1;
	}else if(mysqli_num_rows($email_password) == 0){
		return 1;
	}

	echo "Unexpected error";
	
}

function get_org_id($email){
	//returns id associated with given email	

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$query = "SELECT id_org FROM organizer WHERE (email = '". $email . "')";
	$id = mysqli_query($conn, $query);
	mysqli_close($conn);

	$row = mysqli_fetch_assoc($id);
	return $row["id_org"];
}

function get_org_name($id){
	//returns name associated with given id	

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$query = "SELECT name FROM organizer WHERE (id_org = '". $id . "')";
	$name = mysqli_query($conn, $query);
	mysqli_close($conn);

	$row = mysqli_fetch_assoc($name);
	return $row["name"];
}

function get_org_email($id){
	//returns email associated with given id	

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$query = "SELECT email FROM organizer WHERE (id_org = '". $id . "')";
	$email = mysqli_query($conn, $query);
	mysqli_close($conn);

	$row = mysqli_fetch_assoc($email);
	return $row["email"];
}

function create_competition($id_org, $name, $max, $password){
	
	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);
	
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}


	//If given password is empty, don't request it from participant
	//No need for hashing I think
	$has_pass = 1;
	if(empty($password)){$has_pass = 0;}

	$query = "INSERT INTO competition(id_org, name, max_users, has_pass, password) 
	VALUES (".$id_org.",'".$name."',".$max.",".$has_pass.",'".$password."')";

	mysqli_query($conn, $query);
	mysqli_close($conn);
}

function get_all_competitions($id_org){
	//returns all competitions associated with id_org	

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$query = "SELECT * FROM competition WHERE (id_org = '". $id_org . "')";
	$comps = mysqli_query($conn, $query);
	mysqli_close($conn);

	return $comps;
}

function get_competition($id_comp){
	//returns competition associated with id_comp	

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$query = "SELECT * FROM competition WHERE (id_comp = '". $id_comp . "')";
	$comp = mysqli_query($conn, $query);
	mysqli_close($conn);

	return $comp;
}

function delete_competition($id_comp){
	//deletes competition associated with id_comp	

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	
	$query1 = "DELETE FROM users WHERE id_comp = '". $id_comp . "' and is_guest = 1";
	$query2 = "DELETE FROM competition WHERE (id_comp = '". $id_comp . "')";
	mysqli_query($conn, $query1);
	mysqli_query($conn, $query2);
	mysqli_close($conn);
}

function participants($id_comp){
	//returns all participants associated with id_comp (with has_access == 1)

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$query = "SELECT * FROM users WHERE (id_comp = '". $id_comp . "' AND has_access = 1)";
	$users = mysqli_query($conn, $query);
	mysqli_close($conn);

	return $users;
}

function add_participant($name, $id_comp, $is_guest, $email, $password){
	//Adds participant to database
	//1 means name taken
	//2 means email taken
	//3 means competition has max
	//4 means no such competition
	//5 means creation successful

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	
	//Get targeted competition
	$comp = get_competition($id_comp);
	$row = mysqli_fetch_assoc($comp);

	
	if(mysqli_num_rows($comp) == 0){
		//if no competitions are returned
		mysqli_close($conn);
		return 4;
	}

	if($row["max_users"] == mysqli_num_rows(participants($id_comp))){
		//if competition has max users
		mysqli_close($conn);
		return 3;
	}

	$has_access = 0;
	if($row["has_pass"] == 0){
		$has_access = 1;
	}


	if($is_guest == 1){
		$query1 = "SELECT name,email FROM users WHERE id_comp = '". $id_comp ."' AND name = '". $name ."'";
	}else{
		$query1 = "SELECT email FROM users WHERE email = '".$email."'";
	}

	if($password != ""){
		$password = password_hash($password, PASSWORD_DEFAULT);//Password hashing
	}
	
	
	$query2 = "INSERT INTO users(id_comp,has_access,name,is_guest,password,email,points) 
	VALUES ('".$id_comp."','".$has_access."','".$name."','".$is_guest."','".$password."','".$email."',0)";

	$name_email = mysqli_query($conn, $query1);

	if(mysqli_num_rows($name_email) > 0){
		while($row = mysqli_fetch_assoc($name_email)){
			if($is_guest == 1 and $name == $row["name"]){
				mysqli_close($conn);
				return 1;
			}
			if($is_guest != 1 and $email == $row["email"]){
				mysqli_close($conn);
				return 2;
			}
		}
	}

	if(!mysqli_query($conn,$query2)){
		return 6;
	}

	mysqli_close($conn);
	return 5;
}

function get_participant($id_user) {
	//returns participant info based on id

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$query = "SELECT * FROM users WHERE id_user = '". $id_user . "'";

	$user = mysqli_query($conn, $query);
	mysqli_close($conn);

	$row = mysqli_fetch_assoc($user);
	return $row;
}

function get_participant_id($is_guest, $name, $email) {
	//returns id of participant
	//based on email or name depending on if guest or not

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	if($is_guest == 1){
		$query = "SELECT id_user FROM users WHERE name = '". $name . "' and is_guest = 1";
	}else{
		$query = "SELECT id_user FROM users WHERE email = '". $email . "' and is_guest = 0";
	}

	$id = mysqli_query($conn, $query);
	mysqli_close($conn);

	$row = mysqli_fetch_assoc($id);
	return $row["id_user"];
}

function delete_participant($id_user){
	//deletes participant associated with id_user	

	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$user = get_participant($id_user);

	if($user["is_guest"] == 1){
		$query = "DELETE FROM users WHERE id_user = ".$id_user;
	}else{
		$query = "UPDATE users SET id_comp=-1 WHERE id_user = ".$id_user;
	}

	mysqli_query($conn, $query);
	mysqli_close($conn);
}

function add_points($id_user,$id_comp,$num){
	//Add num amount of points to user of id id_user
	$num=adapt($num);
	$id_user=adapt($id_user);
	
	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	$user = get_participant($id_user);
	$points = $user["points"];
	$total = $points + $num;

	$query = "UPDATE users SET points = ".$total." WHERE id_user = ".$id_user;

	if($id_comp == $user["id_comp"]){
		mysqli_query($conn, $query);
	}
	mysqli_close($conn);
}

function join_comp($id_user,$id_comp){
	//change id_comp of user having id id_user
	//1 means competition has max
	//2 means no such competition
	//3 means join successful
	
	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	//Get targeted competition
	$comp = get_competition($id_comp);
	$row = mysqli_fetch_assoc($comp);

	if(mysqli_num_rows($comp) == 0 or $row["id_comp"] == -1){
		//if no competitions are returned
		mysqli_close($conn);
		return 2;
	}

	if($row["max_users"] == mysqli_num_rows(participants($id_comp))){
		//if competition has max users
		mysqli_close($conn);
		return 1;
	}


	$has_access = 0;
	if($row["has_pass"] == 0){
		$has_access = 1;
	}

	$query = "UPDATE users SET id_comp = ".$id_comp.", has_access = '".$has_access."', points = 0 WHERE id_user = ".$id_user;

	mysqli_query($conn, $query);
	mysqli_close($conn);
	return 3;
}

function check_password($password,$id_comp,$id_user){
	//check if competition password is correct
	//1 means no such comp
	//2 means wrong pass
	//3 means success
	
	global $host,$username,$db_password,$db_name;

	//Connecting to mysql database
	$conn = mysqli_connect($host,$username,$db_password,$db_name);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	
	//Get targeted competition
	$comp = get_competition($id_comp);
	$row = mysqli_fetch_assoc($comp);

	if(mysqli_num_rows($comp) == 0 or $row["id_comp"] == -1){
		//if no such comp
		mysqli_close($conn);
		return 1;
	}

	if($row["password"] != $password){
		//if wrong password
		mysqli_close($conn);
		return 2;
	}

	$query = "UPDATE users SET has_access = 1 WHERE id_user = ".$id_user;

	mysqli_query($conn, $query);
	mysqli_close($conn);
	return 3;
}
?>