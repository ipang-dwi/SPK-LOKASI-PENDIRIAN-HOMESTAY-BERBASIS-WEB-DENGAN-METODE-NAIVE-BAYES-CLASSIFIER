<?php
	include('configdb.php');
	
	$result = $mysqli->query("delete from aturan where id_aturan = ".$_GET['id'].";");
	if(!$result){
		echo $mysqli->connect_errno." - ".$mysqli->connect_error;
		exit();
	}
	else{
		header('Location: aturan.php');
	}
?>