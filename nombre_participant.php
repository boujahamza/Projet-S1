<?php
require 'SQLqueries.php';
echo mysqli_num_rows(participants($_GET["id_comp"]));
?>