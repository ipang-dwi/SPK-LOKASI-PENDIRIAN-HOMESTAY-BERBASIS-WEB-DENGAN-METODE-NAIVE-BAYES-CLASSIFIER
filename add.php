<?php
	include('configdb.php');
	$k1 = $_POST['k1']; 
	$k2 = $_POST['k2'];
	$k3 = $_POST['k3'];
	$k4 = $_POST['k4'];
	$k5 = $_POST['k5'];
	
	$result = $mysqli->query("INSERT INTO `aturan` (`id_aturan`, `k1`, `k2`, `k3`, `k4`, `k5`) 
								VALUES (NULL, '".$k1."', '".$k2."', '".$k3."', '".$k4."', '".$k5."');");
	if(!$result){
		echo $mysqli->connect_errno." - ".$mysqli->connect_error;
		exit();
	}
	else{
		header('Location: aturan.php');
	}
?>