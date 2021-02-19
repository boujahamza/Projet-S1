<?php require "SQLqueries.php"; ?>
<?php

	if(!isset($_SESSION)) {session_start();}
            
	if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
		$_SESSION["isConnected"] = "false";
	}
	if(!isset($_SESSION["connectedAs"])){
		$_SESSION["connectedAs"] = "none";
	}

	//if not connected or not organizer, go to index
	if($_SESSION["isConnected"] == "false" or $_SESSION["connectedAs"] != "organisateur") {header("Location: index.php");}

	$modal_display = "false";
	$name_err = $password_err = $max_err = "";
	//Form validation
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		$create_failed = false;
		
		$name = adapt($_POST["name"]);
		$password = adapt($_POST["password"]);
		$max = adapt($_POST["max-users"]);

		if(empty($name)){
			$name_err = "Vous devez inserer un nom!";
			$create_failed = true;
		}elseif(strlen($name)>31){
			$name_err = "Nom trop long! (maximum 31 caractères)";
			$create_failed = "true";
		}elseif(!preg_match("/^[0-9a-zA-Z-' ]*$/",$name)) {
			//Use RegEx to check for characters in name
			$name_err = "Le nom peut seulement contenir des lettres, des nombres et des espaces!";
			$create_failed = true;
		}

		if(strlen($password)>255){
			$password_err = "Mot de passe trop long! (maximum 255 caractères)";
			$create_failed = "true";
		}elseif(!preg_match("/^[0-9a-zA-Z-' ]*$/",$password)) {
			//Use RegEx to check for characters in password (it's stored in plaintext)
			$password_err = "Le mot de passe peut seulement contenir des lettres, des nombres et des espaces!";
			$create_failed = true;
		}

		if(empty($max) or $max < 1){
			$max_err = "Nombre invalide!";
			$create_failed = true;
		}
			
		if($create_failed){
			$modal_display = "true";
		}else{
			create_competition($_SESSION["id"], $name, $max, $password);
			header("Location: mescompetitions.php");
		}
	}
?>

<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mes competitions</title>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="stylesheets/general.css">
	<link rel="stylesheet" href="stylesheets/mescompetitions.css">
</head>

<body>
	
    <!-- Importer la barre de navigation a partir d'un fichier PHP externe -->
    <?php require 'navbar.php';?>

    <div class="container-fluid">
		
		<div id="titre-container">
			<b id="titre">Les competitions de <b style="color:#532f35"><?php echo get_org_name($_SESSION["id"]);?></b></b><br>

			<!-- Boutton 'Modal' -->
			<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#formulaire">
				Nouvelle competition
			</button>
		</div>

		<?php require 'generer_liste_comp.php';?>

	</div>
		<!-- Modal - Supression competition -->
		<div class="modal fade" id="supprimer" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Voulez-vous vraiment supprimer cette competition?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">Non</button>
						<button type="button" class="btn btn-danger">Oui</button>
					</div>
				</div>
			</div>
		</div>

        <!-- Modal - formulaire creation -->
		<div class="modal fade" id="formulaire" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Nouvelle competition</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<!-- Formulaire de creation d'une competition -->
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
					
						<div class="modal-body">
							<div class="form-group">
								<label for="name"><b>Nom de la competition:</b></label><br>
								<input type="text" class="form-control" id="name" name="name" required>
								<span class="error"><?php echo $name_err;?></span>
							</div>

							<div class="form-group">
								<label for="password"><b>Mot de passe (Facultatif):</b></label>
								<input type="text" class="form-control" id="password-input" name="password">
								<span class="error"><?php echo $password_err;?></span>
							</div>

							<div class="form-group">
								<label for="max-users"><b>Nombre de participants max:</b></label><br>
								<input type="number" class="form-control" id="max-users" name="max-users" min="1" value="30" required>
								<span class="error"><?php echo $max_err?></span>
							</div>

						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">Fermer</button>
							<button type="submit" class="btn btn-danger">Créer une competition</button>
						</div>
					</form>				  
				</div>
			</div>
        </div>

    <?php require 'footer.php';?>
    
	<script>
	if(<?php echo $modal_display;?>){
		$(window).on('load', function() {
			$('#formulaire').modal('show');
		});
	}
	$('#supprimer').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget)
		var id_comp = button.data('id')
		// If necessary, you could initiate an AJAX request here
		var modal = $(this)
		modal.find('.btn-danger').attr('onclick', "location.href='supprimer.php?id_comp="+id_comp+"'")
	})
	</script>
</body>

</html>