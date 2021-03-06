<?php require 'SQLqueries.php';?>
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

if(!isset($_GET["guest"])){$_GET["guest"]=false;}

$name_err = $id_err = "";
$name_taken = "";
$max_users_err = "";
$no_such_comp_err = "";
if($_SERVER["REQUEST_METHOD"]=="POST") {
    
    $guest_join_failed = false;

    if($_POST["guest"]=="true"){
        $name = $_POST["name"];
        $id_comp = $_POST["id_comp"];

        if(empty($name)){
            $name_err= "Vous devez saisir un pseudonyme! <br>";
            $guest_join_failed = true;
        }elseif(strlen($name)>31){
            $name_err = "Pseudonyme trop long! (maximum 31 caractères) <br>";
            $guest_join_failed = "true";
        }elseif(!preg_match("/^[0-9a-zA-Z-' ]*$/",$name)) {
            //Use RegEx to check for characters in name
            $name_err = "Le pseudonyme peut seulement contenir des lettres, des nombres et des espaces! <br>";
            $guest_join_failed = "true";
        }
        if(empty($id_comp) or !is_numeric($id_comp)){
            $id_err= "Vous devez saisir un identificateur valide! <br>";
            $guest_join_failed = true;
        }

        if(!$guest_join_failed){
            $returned = add_participant($name, $id_comp, 1,"","");
            if($returned == 1){
                $name_err = "Ce pseudonyme existe déjà dans cette competition!";
            }elseif($returned == 3){
                $id_err = "Cette competition a déjà le nombre maximal de participants!";
            }elseif($returned == 4){
                $id_err = "Cette competition n'éxiste pas!";
            }elseif($returned == 5){
                $_SESSION["isConnected"] = "true";
                $_SESSION["id"] = get_participant_id(1, $name, "",$id_comp);
                $_SESSION["connectedAs"] = "participant";
                $_SESSION["isGuest"] = 1;
                header("Location: competition.php");
            }
        }    
    }
}

if($_GET["guest"]){
    $guest_form_display = "block";
    $choice_display = "none";
}else{
    $guest_form_display = "none";
    $choice_display = "flex";
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Organisation d'une competition</title>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="stylesheets/general.css">
    <link rel="stylesheet" href="stylesheets/participer.css">
</head>

<body>

    <!-- Importer la barre de navigation a partir d'un fichier PHP externe -->
    <?php require 'navbar.php';?>

    <div class="container-fluid">
        <div id="titre-container">
            <b class="titre">PARTICIPER A UNE COMPETITION</b>
        </div>

        <div class="row" style="display:<?php echo $choice_display;?>">
            <div class="col-md-6 choice" style="background-image: url(images/participer_1.jpg);">
                <a class="lien" href="inscription.php?as=participant">CREER UN COMPTE</a><br>
                <a class="lien" style="font-size: 25px;text-decoration:underline" href="connexion.php?as=participant">ou connecter-vous</a>
            </div>
            <div class="col-md-6 choice" style="background-image: url(images/participer_2.jpg);">
                <a class="lien" href="#" data-toggle="modal" data-target="#participer">PARTICIPER SANS VOUS CONNECTER</a>
            </div>
        </div>

        <!-- Formulaire participation sans connexion -->
        <div id="form-container" style="display:<?php echo $guest_form_display;?>">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>?guest=true" method="post">
                <div class="row" style="margin:auto;">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pseudonyme"><b>Pseudonyme</b></label>
                            <input type="text" class="form-control" id="pseudonyme" name="name" required>
                            <span class="error"><?php echo $name_err?></span>
                        </div>
                        <div class="form-group">
                            <label for="id_comp"><b>Identificateur competition</b></label>
                            <input type="text" class="form-control" id="id_comp" name="id_comp" required>
                            <span class="error"><?php echo $id_err?></span>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                <button type="submit" name="guest" value="true" class="btn btn-danger">Participer</button>
            </form>
        </div>

    </div>
 
    <!-- Modal - participer sans se connecter -->
    <div class="modal fade" id="participer" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Participer sans se connecter</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
                    Si vous créez un compte, les autres utilisateurs pourront communiquer avec vous par email. Voulez-vous continuer?
				</div>

				<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">Non</button>
						<button type="button" onclick="location.href='participer.php?guest=true'" class="btn btn-danger">Oui</button>
				</div>
			</div>
		</div>
    </div>

    <?php require 'footer.php';?>
</body>

</html>
