<?php
require 'SQLqueries.php';
//CHECK IF ALREADY SIGNED IN
if(!isset($_SESSION)) {session_start();}
            
if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
	$_SESSION["isConnected"] = "false";
}
if(!isset($_SESSION["connected_as"])){
		$_SESSION["connected_as"] = "none";
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
</head>

<body>

    <!-- Importer la barre de navigation a partir d'un fichier PHP externe -->
    <?php require 'navbar.php';?>

    <div id="carouselExemple" class="carousel slide" data-ride="carousel" data-interval="3000">
        <ol class="carousel-indicators">
            <li data-target="#carouselExemple" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExemple" data-slide-to="1"></li>
            <li data-target="#carouselExemple" data-slide-to="2"></li>
        </ol>


        <div class="carousel-inner">

            <div class="carousel-item active ">
                <b class="carousel-title">BIENVENUE!</b>
                <div class="carousel-desc">Notre site vous offre des outils pour organiser un competition et d'inviter les gens à y participer.<br></div>
            </div>

            <div class="carousel-item">
                <b class="carousel-title">POUR ORGANISER:</b>
                <div class="carousel-desc">Connectez-vous en tant qu'organisateur et creer une competition dans le volet 'Mes competitions'.<br></div>
            </div>

            <div class="carousel-item">
                <b class="carousel-title">POUR PARTICIPER:</b>
                <div class="carousel-desc">Connectez-vous en tant que participant et rejoignez une competition grace à l'identificateur fournit par l'organisateur (Un mot de passe peut etre demande).<br></div>
            </div>

        </div>

        <a href="#carouselExemple" class="carousel-control-prev" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="ture"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a href="#carouselExemple" class="carousel-control-next" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

    </div>
    <script>
        $('.carousel').carousel({
            pause: "null"
        })
    </script>

    <div class="container-fluid">
        <div class="row" style="overflow:hidden;">
            <div class="col-lg-8">        
                <b class="titre_sectionne">La compétition: un moyen de se dépasser et de s'améliorer </b>
                <p> Vous avez l'esprit de concurrence et de compétition, alors voici l'opportunité, soyez le combattant de votre réussite ,osez le changement et participez. A en croire, la soif de la compétition anime chacun d'entre nous, entre autres elle constitue
                    un besoin :
                </p>

                <p>-besoin d'appartenance : c'est à dire un besoin de se sentir accepter par les autres</p>
                <p>-un besoin d'estime : soit une nécessité d'avoir la considération d'autrui.</p>

                <p>Alors prenez le courage d'agir , voire de se battre , pour satisfaire ces 2 besoins et ainsi d'être animé et reconnu pour ce qui vous êtes .</p>
            </div>
            <div class="col-lg-4" style="overflow:hidden;">
                <img src="images/acceuil_1.jpg" style="height:100%;max-height:500px;">
            </div>

        <div class="row" style="overflow:hidden;">
            <div class="col-lg-4" style="overflow:hidden;">
                <img src="images/acceuil_2.jpg" style="height:100%;max-height:500px;">
            </div>
            <div class="col-lg-8">
                <b class="titre_sectionne">Les conseils aux participants pour allier compétition et esprit d'équipe</b>
                <p> Voici quelques conseils aux managers pour que la compétition augmente la productivité au sein d’une équipe tout en conservant un environnement de travail sain et serein:
                <p> -Affirmez haut et fort les valeurs que votre équipe défend et prouvez-les au quotidien</p>
                <p>-Définissez l’objectif commun et repérez les profils « compétiteurs » </p>
                <p>-Dosez les périodes de challenge et de repos</p>
                <p> La compétition est bénéfique dans une équipe à condition d’y aller avec parcimonie et d’avoir insufflé un véritable esprit d’équipe en amont. Le plus important est de préserver la relation entre les collaborateurs pour leur donner envie de
                    se surpasser. À vous de mettre à profit vos meilleurs talents managériaux pour y arriver !
                </p>
            </div>
        </div>
    </div>

        
    <?php require 'footer.php';?>
</body>

<script>
    
</script>

</html>