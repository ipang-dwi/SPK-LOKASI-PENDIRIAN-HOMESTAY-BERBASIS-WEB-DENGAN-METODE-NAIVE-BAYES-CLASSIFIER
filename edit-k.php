<?php
	include('configdb.php');
	$kriteria = $_POST['kriteria'];
	
	$result = $mysqli->query("UPDATE kriteria SET `kriteria` = '".$kriteria."' WHERE `id_kriteria` = ".$_GET['id'].";");
	if(!$result){
		echo $mysqli->connect_errno." - ".$mysqli->connect_error;
		exit();
	}
	else{
		header('Location: kriteria.php');
	}
?>