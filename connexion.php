<?php require "SQLqueries.php"; ?>
<?php
            //CHECK IF ALREADY SIGNED IN
            if(!isset($_SESSION)) {session_start();}
            
            if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
	            $_SESSION["isConnected"] = "false";
            }
            if(!isset($_SESSION["connectedAs"])){
		        $_SESSION["connectedAs"] = "none";
	        }

            if($_SESSION["isConnected"] == "true") {header("Location: index.php");}

            //Participant or organizer?
            $as = "organisateur";
            if(isset($_GET["as"]) and $_GET["as"]=="participant"){
                $as = "participant";
            }

			//Form handling
			$email =  $password = "";
            $email_err = $password_err = "";
			$email_wrong = $password_wrong = "";

			$signinFailed = "false";
			if($_SERVER["REQUEST_METHOD"]=="POST"){
				$email = adapt($_POST["email"]);
				$password = adapt($_POST["password"]);
				
                if(empty($email)){
                    $email_err = "Vous devez saisir un email!";
                    $signinFailed = "true";
                }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $email_err = "Format email invalide!";
                    $signinFailed = "true";
                }

                if(empty($password)){
                    $password_err = "Vous devez saisir un mot de passe!";
                    $signinFailed = "true";
                }

				if($signinFailed == "false"){
                    if($as == "participant"){
                        //If trying to connect as participant
                        $returned_code = signin_participant($email,$password);
                        if($returned_code == 1){
                            $email_wrong = "Cet adress email n'est associee à aucun paricipant!";
                            $signinFailed = "true";
                        }
                        if ($returned_code == 2) {
                            $password_wrong = "Mot de passe incorrect!";
                            $signinFailed = "true";
                        }
                        if($returned_code == 3){
                            //echo "Successfully signed in! Redirecting...";
                            $_SESSION["isConnected"] = "true";
                            $_SESSION["id"] = get_participant_id(0,"",$email);
                            $_SESSION["connectedAs"] = "participant";
                            $_SESSION["isGuest"] = 0;
                            header("Location: competition.php");
                        }
                    }else{
                        //if trying to connect as organizer
                        $returned_code = signin($email,$password);
        
                        if($returned_code == 1){
                            $email_wrong = "Cet adress email n'est associee à aucun compte!";
                            $signinFailed = "true";
                        }
                        if ($returned_code == 2) {
                            $password_wrong = "Mot de passe incorrect!";
                            $signinFailed = "true";
                        }
                        if($returned_code == 3){
                            //echo "Successfully signed in! Redirecting...";
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

<title>Connexion</title>

<link href="stylesheets/connexion.css" rel="stylesheet" media="all">
</head>

<body>
    
    <h1 class="logo"><a href="index.php">GloryLine</a></h1>

    <div class="page-wrapper bg-red p-t-180 p-b-100">
        <div class="wrapper wrapper--w960">
            <div class="card card-2">
            <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Connexion <?php echo $as;?></h2>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?as=".$as;?>">
                        <div class="input-group">
                            <input class="input--style-2" type="email" placeholder="Adress email" name="email" required>
                            <?php if($signinFailed == "true"){echo '<span class="error">'.$email_wrong.'</span>';}?>
                            <?php if($signinFailed == "true"){echo '<span class="error">'.$email_err.'</span>';}?>
                        </div>

                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2" type="password" placeholder="mot de passe" name="password" required>
                                    <?php if($signinFailed == "true"){echo '<span class="error">'.$password_err.'</span>';}?>
                                    <?php if($signinFailed == "true"){echo '<span class="error">'.$password_wrong.'</span>';}?>
                                </div>
                            </div>
                        </div>

                        <div class="p-t-30">
                            <button class="btn btn--radius btn--green" type="submit">Se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>

</html>