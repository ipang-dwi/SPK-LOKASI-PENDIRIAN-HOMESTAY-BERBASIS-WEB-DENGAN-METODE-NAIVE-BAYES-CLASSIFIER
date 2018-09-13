<?php
	@session_start();
	$_SESSION['judul'] = 'SPK NBC HOMESTAY';
	$_SESSION['welcome'] = 'SISTEM PENGAMBIL KEPUTUSAN LOKASI PENDIRIAN HOMESTAY BERBASIS WEB DENGAN METODE NAIVE BAYES CLASSIFIER';
	$_SESSION['by'] = 'ShouldBe';
	$mysqli = new mysqli('localhost','root','1717','bayes');
	if($mysqli->connect_errno){
		echo $mysqli->connect_errno." - ".$mysqli->connect_error;
		exit();
	}
?>