<?php require "SQLqueries.php"; ?>
<?php

	if(!isset($_SESSION)) {session_start();}
            
	if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
		$_SESSION["isConnected"] = "false";
	}
	if(!isset($_SESSION["connected_as"])){
		$_SESSION["connected_as"] = "none";
	}

	if($_SESSION["isConnected"] == "false" or $_SESSION["connected_as"] != "organisateur") {header("Location: index.php");}

	$modal_display = "false";
	$name_err = $pass_err = $max_err = "";
	//Form validation
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		$create_failed = false;
		
		$name = adapt($_POST["name"]);
		$password = adapt($_POST["password"]);
		$max = adapt($_POST["max-users"]);
		$type = $_POST["type"];

		if(empty($name)){
			$name_err = "Vous devez inserer un nom!";
			$create_failed = true;
		}
		if(empty($max) or $max < 1){
			$max_err = "Nombre invalide!";
			$create_failed = true;
		}
			
		if($create_failed){
			$modal_display = "true";
		}else{
			create_competition($_SESSION["id"], $name, $type, $max, $password);
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

		<?php require 'generer_liste.php';?>

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
						<button type="button" onclick="#" class="btn btn-danger">Oui</button>
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
							</div>

							<div class="form-group">
								<label for="max-users"><b>Maximum players:</b></label><br>
								<input type="number" class="form-control" id="max-users" name="max-users" min="1" value="30" required>
								<span class="error"><?php echo $max_err?></span>
							</div>

							<div class="form-group">
								<label for="type"><b>Type:</b></label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" id="type1" name="type" value="type1" checked>
									<label class="form-check-label" for="yes">Type 1</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" id="type2" name="type" value="type2">
									<label class="form-check-label" for="no">Type 2</label>
								</div>
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