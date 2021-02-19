<?php require "SQLqueries.php";?>
<?php
//CHECK IF ALREADY SIGNED IN
if(!isset($_SESSION)) {session_start();}
            
if(!(isset($_SESSION["isConnected"]) and $_SESSION["isConnected"] == "true")){
	$_SESSION["isConnected"] = "false";
}
if(!isset($_SESSION["connectedAs"])){
		$_SESSION["connectedAs"] = "none";
}

if($_SESSION["isConnected"] == "false"){header("Location: 'index.php'");}

if($_SESSION["connectedAs"]!="participant" or $_SESSION["isGuest"] == 1){
    //if not participant or is a guest
    header("Location: index.php");
}

$id_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $join_failed = false;

    if($_POST["guest"]=="false"){
        $id_comp = $_POST["id_comp"];

        if(empty($id_comp) or !is_numeric($id_comp)){
            $id_err= "Vous devez saisir un identificateur valide!";
            $join_failed = true;
        }

        if(!$join_failed){
            $returned = join_comp($_SESSION["id"],$id_comp);
            if($returned == 1){
                $id_err = "Cette competition a déjà le nombre maximal de participants!";
            }elseif($returned == 2){
                $id_err = "Cette competition n'éxiste pas!";
            }elseif($returned == 3){
                header("Location: competition.php");
            }
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

    <div id="titre-container">
        <b id="titre-page">REJOINDRE UNE COMPETITION</b><br>
        <b>(Vous allez quitter la competition courante)</b>
    </div>

    <div id="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="row" style="margin:auto;">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="id_comp"><b>Identificateur competition</b></label>
                        <input type="text" class="form-control" id="id_comp" name="id_comp" required>
                        <span class="error"><?php echo $id_err?></span>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
            <button type="submit" name="guest" value="false" class="btn btn-danger">Participer</button>
        </form>
    </div>

    <?php require 'footer.php';?>
</body>
<script>

</script>
</html>