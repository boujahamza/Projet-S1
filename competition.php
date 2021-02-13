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

if($_SESSION["isConnected"] == "false") {header("Location: index.php");}

if($_SESSION["connectedAs"] == "participant"){
    $row_user = get_participant($_SESSION["id"]);
    $_GET["id_comp"] = $row_user["id_comp"];
}

if((!isset($_GET["id_comp"]) and $_SESSION["connectedAs"] == "organisateur") or !is_numeric($_GET["id_comp"])){
    $_GET["id_comp"] = -1;
}

$dashboard_display = "block";
$comp_error = "";
if($_GET["id_comp"] == -1){
    //if not connected or accessed this file as organizer without specifying which competition 
    header("Location: mescompetitions.php");
}else{
    $comp = get_competition(htmlspecialchars($_GET["id_comp"]));
    $row_comp = mysqli_fetch_assoc($comp);

    if(mysqli_num_rows($comp) == 0){
        //If this id is not associated with any competition
        header("Location: introuvable.php");
    }elseif($_SESSION["connectedAs"] == "organisateur" and $row_comp["id_org"] != $_SESSION["id"]){
        //if organizer doesn't have access to this competition
        header("Location: introuvable.php");
    }elseif($_SESSION["connectedAs"] == "participant"){
        
        if($_GET["id_comp"] != $row_user["id_comp"]){
            //if user doesn't have access to this competition
            if($row_user["is_guest"] == 1){
                $_SESSION["id"] = -1;
                $_SESSION["isConnected"] = "false";
                $_SESSION["connectedAs"] = "none";
            }
            header("Location: introuvable.php");
        }
    }

}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Organisation d'une competition</title>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="stylesheets/general.css">
    <link rel="stylesheet" href="stylesheets/competition.css">

</head>

<body>

    <!-- Importer la barre de navigation a partir d'un fichier PHP externe -->
    <?php require 'navbar.php';?>

    <div class="container-fluid" style="display: <?php echo $dashboard_display;?>">

        <div class="titre-container">
            <b class="titre"><?php echo $row_comp["name"];?></b><br>
            <b>Identificateur: <?php echo $_GET["id_comp"];?></b><br>
            <b>Email de l'organisateur: <?php echo get_org_email($row_comp["id_org"]);?></b>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div id="top-bar-participants">
                    <h4><b>Participants: <span id="number"><?php echo mysqli_num_rows(participants($_GET["id_comp"]));?></span></b></h4>
                    <a href="#"><img class="link" id="show-button" src="images/show.png"></a>
                    <a href="#"><img class="link" id="refresh-button" src="images/refresh.png"></a>
                </div>
                <div class="overflow-auto" id="participants-container">
                    
                </div>
            </div>
            <div class="col-lg-9">
                <div id="top-bar-options">
                    <h2>Options</h2>
                </div>
                <div id="options-container" class="overflow-auto">
                    <div id="not-selected">
                        <h1>Aucun participant selectionné</h1>
                    </div>
                    <div id="selected" style="display:none">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - Supprimer participant -->
    <div class="modal fade" id="supprimer" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Voulez-vous vraiment supprimer ce participant?</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">Non</button>
					<button type="button" class="btn btn-danger" onclick="location.href='supprimer_participant.php?id_user='+current_selected+'&id_comp=<?php echo $_GET['id_comp'];?>'">Oui</button>
				</div>
			</div>
		</div>
	</div>

    <?php require 'footer.php';?>

</body>

<script>
    $(document).ready(function(){
        $.get(
            'generer_liste_participant.php',
            'id_comp='+<?php echo $_GET["id_comp"];?>,
            refresh,
            'text'
        );
    });

    $("#refresh-button").click(function(){
        $.get(
            'generer_liste_participant.php',
            'id_comp='+<?php echo $_GET["id_comp"];?>,
            refresh,
            'text'
        );
        $.get(
            'nombre_participant.php',
            'id_comp='+<?php echo $_GET["id_comp"];?>,
            updateNumber,
            'text'
        );
    });

    function changer_points(num){
        $.get(
            'changer_points.php',
            'id_user='+current_selected+'&num='+num,
            update,
            'text'
        );
    }

    
    function update(received){
        $("#points").html(received);
        $("#points-"+current_selected).html(received);
    }
    function refresh(received){
        $("#participants-container").html(received);
    }
    function updateNumber(received){
        $("#number").html(received);
    }

    
</script>
<script src="scripts/selection.js"></script>
</html>