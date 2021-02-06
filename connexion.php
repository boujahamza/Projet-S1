<?php require "SQLqueries.php"; ?>
<?php
            //CHECK IF ALREADY SIGNED IN
            if(!isset($_SESSION)) {session_start();}
            
            if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
	            $_SESSION["isConnected"] = "false";
            }
            if(!isset($_SESSION["connected_as"])){
		        $_SESSION["connected_as"] = "none";
	        }

            if($_SESSION["isConnected"] == "true") {header("Location: index.php");}

			//Form handling
			$email =  $password = "";
			$email_wrong = $password_wrong = "";

			$signinFailed = "false";
			if($_SERVER["REQUEST_METHOD"]=="POST"){
				$email = adapt($_POST["email"]);
				$password = adapt($_POST["password"]);
				
				if($signinFailed == "false"){
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
                        $_SESSION["connected_as"] = "organisateur";
    					header("Location: mescompetitions.php");
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
    
    <h1 class="logo"><a href="index.php">COMPANY NAME</a></h1>

    <div class="page-wrapper bg-red p-t-180 p-b-100">
        <div class="wrapper wrapper--w960">
            <div class="card card-2">
            <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Connexion</h2>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="input-group">
                            <input class="input--style-2" type="email" placeholder="Adress email" name="email" required>
                        </div>
                        <span class="error"><?php echo $email_wrong?></span>

                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2" type="password" placeholder="mot de passe" name="password" required>
                                </div>
                                <span class="error"><?php echo $password_wrong?></span>
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

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>
</body>
</html>