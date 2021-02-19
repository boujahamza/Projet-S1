<?php
	$comps = get_all_competitions($_SESSION["id"]);

	if(!isset($_GET["page"]) or !is_numeric($_GET["page"])){
		$page = 1;
	}else{
		$page = $_GET["page"];
	}

	//Calculating number of pages
	$num_pages = ceil(mysqli_num_rows($comps)/6);


	//Loop for skipping elements in other pages
	for($i=1; $i<$page; $i++){
		for($j=0; $j<6; $j++)	
			$row = mysqli_fetch_assoc($comps);
	}

	echo '<div class="row">';
	if(mysqli_num_rows($comps) > 0){
		//go through all competitions
		$i = 6;
		while($row = mysqli_fetch_assoc($comps) and $i != 0) {
			echo '
				<div class="col-md-4">
					<div class="competition">
						<h2><a href="competition.php?id_comp='.$row["id_comp"].'" style="text-decoration:none;color:black">'.$row["name"].'</a></h2><br>
						<span><b>Identificateur:</b> '.$row["id_comp"].'</span><br>
						<span><b>Participants:</b> '.mysqli_num_rows(participants($row["id_comp"])).'</span><br><br>
						<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimer" data-id="'.$row["id_comp"].'">
							Supprimer la competition
						</button>
					</div>
				</div>';
			$i--;
		}
	}
	echo '</div>';

	echo '<div id="page-container">';

	for($i=1; $i<=$num_pages; $i++){
		if($i==1)
			echo 'Page ';
		if($i==$page){
			echo '-	<a class="page-active" href="'.$_SERVER["PHP_SELF"].'?page='.$i.'" > '.$i.' </a>';
		}else{
			echo '-	<a class="page" href="'.$_SERVER["PHP_SELF"].'?page='.$i.'">'.$i.'</a>';
		}
	}
	echo '</div>';
?>