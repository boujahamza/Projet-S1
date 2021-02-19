<?php require "SQLqueries.php"; ?>
<?php       
            //CHECK IF ALREADY SIGNED IN
            if(!isset($_SESSION)) {session_start();}
            
            if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
	            $_SESSION["isConnected"] = "false";
            }

            if($_SESSION["isConnected"] == "true") {header("Location: index.php");}

            //Participant or organizer?
            $as = "organisateur";
            if(isset($_GET["as"]) and $_GET["as"]=="participant"){
                $as = "participant";
            }

			//Form handling
			$name = $email = $password = $password_confirm = "";
            $name_err = $email_err = $password_err =$id_err= "";
		    $password_confirm_err = "";
			$email_taken = "";

		
			$signupFailed = "false";

			if($_SERVER["REQUEST_METHOD"]=="POST"){
				$name = adapt($_POST["name"]);
				$email = adapt($_POST["email"]);
				$password = adapt($_POST["password"]);
				$password_confirm = adapt($_POST["password_confirm"]);
                if(isset($_POST["id_comp"]))
                    $id_comp = adapt($_POST["id_comp"]);
                
                if(empty($name)){
                    $name_err = "Vous devez saisir un nom!";
                    $signupFailed = "true";
                }elseif(strlen($name)>31){
                    $name_err = "Nom trop long! (maximum 31 caractères)";
                    $signupFailed = "true";
                }elseif(!preg_match("/^[0-9a-zA-Z-' ]*$/",$name)) {
                    //Use RegEx to check for characters in name
                    $name_err = "Le nom peut seulement contenir des lettres, des nombres et des espaces!";
                    $signupFailed = "true";
                }

                if(empty($email)){
                    $email_err = "Vous devez saisir un email!";
                    $signupFailed = "true";
                }elseif(strlen($email)>127){
                    $email_err = "Email trop long! (maximum 127 caractères)";
                    $signupFailed = "true";
                }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $email_err = "Format email invalide!";
                    $signupFailed = "true";
                }

                if(empty($password)){
                    $password_err = "Vous devez saisir un mot de passe!";
                    $signupFailed = "true";
                }elseif(strlen($password)>255){
                    $password_err = "Email trop long! (maximum 255 caractères)";
                    $signupFailed = "true";
                }

                if($password != $password_confirm and $signupFailed == "false"){
					$password_confirm_err = "Vous avez saisie un mot de passe different!";
					$signupFailed = "true";
				}

                if(empty($id_comp) or !is_numeric($id_comp)){
                    $id_err= "Vous devez saisir un identificateur valide! <br>";
                    $signup_failed = "true";
                }
				
				if($signupFailed == "false"){
                    if($as == "participant"){
                        $returned_code = add_participant($name,$id_comp,0,$email,$password);
        
                        if ($returned_code == 2) {
                            $email_taken = "Adress email déjà utilisée!";
                            $signupFailed = "true";
                        }elseif($returned_code == 3){
                            $id_err = "Cette competition a déjà le nombre maximal de participants!";
                            $signupFailed = "true";
                        }elseif($returned_code == 4){
                            $id_err = "Cette competition n'éxiste pas!";
                            $signupFailed = "true";
                        }elseif($returned_code == 5){
                            $_SESSION["isConnected"] = "true";
                            $_SESSION["id"] = get_participant_id(0,"",$email);
                            $_SESSION["connectedAs"] = "participant";
                            $_SESSION["isGuest"] = 0;
                            header("Location: competition.php");
                        }else{
                            $id_err = "Erreur imprevue!";
                            $signupFailed = "true";
                        }
                    }else{
                        $returned_code = signup($name,$email,$password);
        
                        if ($returned_code == 1) {
                            $email_taken = "Adress email déjà utilisée!";
                            $signupFailed = "true";
                        }
                        if($returned_code == 2){
                            $_SESSION["isConnected"] = "true";
                            $_SESSION["id"] = get_org_id($email);
                            $_SESSION["connectedAs"] = "organisateur";
                            header("Location: mescompetitions.php");
                        }
                    }
			    }
			}
?>

<!DOCTYPE html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>Inscription</title>

<link href="stylesheets/connexion.css" rel="stylesheet" media="all">
</head>

<body>
    
    <h1 class="logo"><a href="index.php">COMPANY NAME</a></h1>
    
    <div class="page-wrapper bg-red p-t-180 p-b-100 font-robo">
        <div class="wrapper wrapper--w960">
            <div class="card card-2">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Inscription <?php echo $as;?></h2>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?as=".$as;?>">
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Nom" name="name" required>
                            <?php if($signupFailed == "true"){echo '<span class="error">'.$name_err.'</span>';}?>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2 js-datepicker" type="email" placeholder="exemple@gmail.com" name="email" required>
                                    <?php if($signupFailed == "true"){echo '<span class="error">'.$email_err.'</span>';}?>
                                    <?php if($signupFailed == "true"){echo '<span class="error">'.$email_taken.'</span>';}?>
                                </div>
                            </div>
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2" type="password" placeholder="mot de passe" name="password" required>
                                        <?php if($signupFailed == "true"){echo '<span class="error">'.$password_err.'</span>';}?>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2" type="password" placeholder="confirmer mot de passe" name="password_confirm" required>
                                        <?php if($signupFailed == "true"){echo '<span class="error">'.$password_confirm_err.'</span>';}?>
                                    </div>
                                </div>
                            </div>
                            <?php if($as == "participant"){ echo
                            '<div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2" type="text" placeholder="identificateur de competition" name="id_comp" required>';}
                                        if($signupFailed == "true"){echo '<span class="error">'.$id_err.'</span>';}
                                    if($as == "participant"){ echo '</div>
                                </div>
                            </div>';}?>
                        </div>

                        <div class="p-t-30">
                            <button class="btn btn--radius btn--green" type="submit">S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>