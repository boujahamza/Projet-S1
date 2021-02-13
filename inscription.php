<?php require "SQLqueries.php"; ?>
<?php       
            //CHECK IF ALREADY SIGNED IN
            if(!isset($_SESSION)) {session_start();}
            
            if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
	            $_SESSION["isConnected"] = "false";
            }

            if($_SESSION["isConnected"] == "true") {header("Location: index.php");}

			//Form handling
			$name = $email = $password = $password_confirm = "";
		    $password_confirm_err = "";
			$email_taken = "";

		
			$signupFailed = "false";
			if($_SERVER["REQUEST_METHOD"]=="POST"){
				$name = adapt($_POST["name"]);
				$email = adapt($_POST["email"]);
				$password = adapt($_POST["password"]);
				$password_confirm = adapt($_POST["password_confirm"]);

				if($password != $password_confirm){
					$password_confirm_err = "Vous avez saisie un mot de passe different!";
					$signupFailed = "true";
				}
				
				if($signupFailed == "false"){
    				$returned_code = signup($name,$email,$password);
    
    				if ($returned_code == 1) {
    					$email_taken = "Adress email deja utilis�e!";
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
                    <h2 class="title">formulaire d'inscription</h2>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Nom" name="name" required>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2 js-datepicker" type="email" placeholder="exemple@gmail.com" name="email" required>
                                </div>
                                <span class="error"><?php echo $email_taken?></span>
                            </div>
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2" type="password" placeholder="mot de passe" name="password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2" type="password" placeholder="confirmer mot de passe" name="password_confirm" required>
                                    </div>
                                    <span class="error"><?php echo $password_confirm_err?></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-t-30">
                            <button class="btn btn--radius btn--green" type="submit">S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="js/global.js"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>
</body>
</html>