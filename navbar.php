<?php 
$display_participant_nav = "none";
$display_organizer_nav = "none";
$display_connexion_nav = "auto";
$display_notguest_nav = "auto";
if($_SESSION["isConnected"] == "true"){
    if($_SESSION["connectedAs"]=="organisateur"){
        $display_organizer_nav = "auto";
        $display_connexion_nav = "none";
    }elseif($_SESSION["connectedAs"]=="participant"){
        $display_connexion_nav = "none";
        $display_organizer_nav = "none";
        $display_participant_nav = "auto";
        $user = get_participant($_SESSION["id"]);
        if($user["is_guest"]==1){
            $display_notguest_nav = "none";
        }
    }
}
?>

<script>
$(document).ready(function(){
    $('.nav-link').each(function(){
        if($(this).attr('href') == "<?php echo $_SERVER["PHP_SELF"];?>"){
            $(this).addClass('active')
        }
    })
});
</script>

<div style="height:70px;"></div>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark shadow-lg p-3 mb-5" style="background-color:gray;">
    <a class="navbar-brand" href="index.php">COMPANY NAME</a>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/index.php">ACCUEIL <span class="sr-only">(current)</span></a>
            </li>
        </ul>

        <ul class="nav navbar-nav ml-right " style="display:<?php echo $display_connexion_nav;?>">
            <li class="nav-item">
                <a class="nav-link" href="/participer.php">PARTICIPER</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ORGANISER
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/connexion.php">CONNEXION</a>
                    <a class="dropdown-item" href="/inscription.php">INSCRIPTION</a>
                </div>
            </li>
        </ul>

        <ul class="nav navbar-nav ml-right " style="display:<?php echo $display_organizer_nav;?>">
            <li class="nav-item">
                <a class="nav-link" href="/mescompetitions.php">MES COMPETITIONS</a>
            </li>
            <li class="nav-item" style="float:right;">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#deconnexion">DECONNEXION</a>
            </li>
        </ul>

        <ul class="nav navbar-nav ml-right " style="display:<?php echo $display_participant_nav;?>">
            <li class="nav-item">
                <a class="nav-link" href="/competition.php">COMPETITION</a>
            </li>
            <li class="nav-item" style="display:<?php echo $display_notguest_nav;?>">
                <a class="nav-link" href="/rejoindre.php">REJOINDRE</a>
            </li>
            <li class="nav-item" style="float:right;">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#deconnexion">DECONNEXION</a>
            </li>
        </ul>

    </div>
</nav>

<div class="modal fade" id="deconnexion" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Voulez-vous vraiment vous deconnecter?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div class="modal-footer">
				<button type="button" class="btn btn-dark" data-dismiss="modal">Non</button>
				<button type="button" onclick="location.href='deconnecte.php'" class="btn btn-danger">Oui</button>
			</div>
        </div>
    </div>
</div>
