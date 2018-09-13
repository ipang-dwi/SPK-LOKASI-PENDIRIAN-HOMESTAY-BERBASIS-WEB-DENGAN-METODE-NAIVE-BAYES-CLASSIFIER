<center>
<?php
	session_start();
	include('configdb.php');
	
	
	$jml_alt = jml_alt();
	$alt = get_alt();
	for($i=0;$i<$jml_alt;$i++){
		$r[$i]=bayes($alt[$i]);
	}
	print_ar($r);
	
function bayes(array $input){
	$k = jml_kriteria();
	$a = jml_aturan();
	$kri = get_kriteria();
	$atu = get_aturan();
	
	$at[1]=pilih('k1','dekat','k5','ya'); $at[2]=pilih('k1','sedang','k5','ya'); $at[3]=pilih('k1','jauh','k5','ya');
	$at[4]=pilih('k1','dekat','k5','tidak'); $at[5]=pilih('k1','sedang','k5','tidak'); $at[6]=pilih('k1','jauh','k5','tidak');
	$jat1=$at[1]+$at[2]+$at[3]; $jat2=$at[4]+$at[5]+$at[6];
	$prob[1]['dekat']['ya']=round(($at[1]/$jat1),4); $prob[1]['dekat']['tidak']=round(($at[4]/$jat2),4);
	$prob[1]['sedang']['ya']=round(($at[2]/$jat1),4); $prob[1]['sedang']['tidak']=round(($at[5]/$jat2),4);
	$prob[1]['jauh']['ya']=round(($at[3]/$jat1),4); $prob[1]['jauh']['tidak']=round(($at[6]/$jat2),4);
	$jat3=round($prob[1]['dekat']['ya']+$prob[1]['sedang']['ya']+$prob[1]['jauh']['ya']);
	$jat4=round($prob[1]['dekat']['tidak']+$prob[1]['sedang']['tidak']+$prob[1]['jauh']['tidak']);
	
	$at[1]=pilih('k2','murah','k5','ya'); $at[2]=pilih('k2','sedang','k5','ya'); $at[3]=pilih('k2','mahal','k5','ya');
	$at[4]=pilih('k2','murah','k5','tidak'); $at[5]=pilih('k2','sedang','k5','tidak'); $at[6]=pilih('k2','mahal','k5','tidak');
	$jat1=$at[1]+$at[2]+$at[3]; $jat2=$at[4]+$at[5]+$at[6];
	$prob[2]['murah']['ya']=round(($at[1]/$jat1),4); $prob[2]['murah']['tidak']=round(($at[4]/$jat2),4);
	$prob[2]['sedang']['ya']=round(($at[2]/$jat1),4); $prob[2]['sedang']['tidak']=round(($at[5]/$jat2),4);
	$prob[2]['mahal']['ya']=round(($at[3]/$jat1),4); $prob[2]['mahal']['tidak']=round(($at[6]/$jat2),4);
	$jat3=round($prob[2]['murah']['ya']+$prob[2]['sedang']['ya']+$prob[2]['mahal']['ya']);
	$jat4=round($prob[2]['murah']['tidak']+$prob[2]['sedang']['tidak']+$prob[2]['mahal']['tidak']);
	
	$at[1]=pilih('k3','kecil','k5','ya'); $at[2]=pilih('k3','sedang','k5','ya'); $at[3]=pilih('k3','besar','k5','ya');
	$at[4]=pilih('k3','kecil','k5','tidak'); $at[5]=pilih('k3','sedang','k5','tidak'); $at[6]=pilih('k3','besar','k5','tidak');
	$jat1=$at[1]+$at[2]+$at[3]; $jat2=$at[4]+$at[5]+$at[6];
	$prob[3]['kecil']['ya']=round(($at[1]/$jat1),4); $prob[3]['kecil']['tidak']=round(($at[4]/$jat2),4);
	$prob[3]['sedang']['ya']=round(($at[2]/$jat1),4); $prob[3]['sedang']['tidak']=round(($at[5]/$jat2),4);
	$prob[3]['besar']['ya']=round(($at[3]/$jat1),4); $prob[3]['besar']['tidak']=round(($at[6]/$jat2),4);
	$jat3=round($prob[3]['kecil']['ya']+$prob[3]['sedang']['ya']+$prob[3]['besar']['ya']);
	$jat4=round($prob[3]['kecil']['tidak']+$prob[3]['sedang']['tidak']+$prob[3]['besar']['tidak']);
	
	$at[1]=pilih('k4','ada','k5','ya'); $at[2]=pilih('k4','tidak','k5','ya');
	$at[4]=pilih('k4','ada','k5','tidak'); $at[5]=pilih('k4','tidak','k5','tidak');
	$jat1=$at[1]+$at[2]; $jat2=$at[4]+$at[5];
	$prob[4]['ada']['ya']=round(($at[1]/$jat1),4); $prob[4]['ada']['tidak']=round(($at[4]/$jat2),4);
	$prob[4]['tidak']['ya']=round(($at[2]/$jat1),4); $prob[4]['tidak']['tidak']=round(($at[5]/$jat2),4);
	$jat3=round($prob[4]['ada']['ya']+$prob[4]['tidak']['ya']);
	$jat4=round($prob[4]['ada']['tidak']+$prob[4]['tidak']['tidak']);
	
	$at[1]=pilih('k5','ya','k5','ya'); $at[2]=pilih('k5','tidak','k5','tidak');
	$prob[5]['ya']=round(($at[1]/$a),4); $prob[5]['tidak']=round(($at[2]/$a),4);
	
	$likeya=round(($prob[1][$input[0]]['ya']*$prob[2][$input[1]]['ya']*$prob[3][$input[2]]['ya']*$prob[4][$input[3]]['ya']*$prob[5]['ya']),4);
	$likeno=round(($prob[1][$input[0]]['tidak']*$prob[2][$input[1]]['tidak']*$prob[3][$input[2]]['tidak']*$prob[4][$input[3]]['tidak']*$prob[5]['tidak']),4);
	$like=$likeya+$likeno;
	$ya=$likeya/$like; $no=$likeno/$like;
	
	if($ya>$no){
		return array(round($ya,4),round($no,4),'ya');
	}
	else{ 
		return array(round($ya,4),round($no,4),'tidak');
	}
}
		
	function jml_kriteria(){	
		include 'configdb.php';
		$kriteria = $mysqli->query("select * from kriteria");
		return $kriteria->num_rows;
	}
	
	function jml_aturan(){	
		include 'configdb.php';
		$aturan = $mysqli->query("select * from aturan");
		return $aturan->num_rows;
	}
	
	function jml_alt(){	
		include 'configdb.php';
		$alternatif = $mysqli->query("select * from alternatif");
		return $alternatif->num_rows;
	}
	
	function get_kriteria(){
		include 'configdb.php';
		$kriteria = $mysqli->query("select * from kriteria");
		if(!$kriteria){
			echo $mysqli->connect_errno." - ".$mysqli->connect_error;
			exit();
		}
		$i=1;
		while ($row = $kriteria->fetch_assoc()) {
			@$kri[$i] = $row["kriteria"];
			$i++;
		}
		return $kri;
	}
	
	function get_aturan(){
		include 'configdb.php';
		$aturan = $mysqli->query("select * from aturan");
		if(!$aturan){
			echo $mysqli->connect_errno." - ".$mysqli->connect_error;
			exit();
		}
		$i=1;
		while ($row = $aturan->fetch_assoc()) {
			@$atu[$i][1] = $row["k1"];
			@$atu[$i][2] = $row["k2"];
			@$atu[$i][3] = $row["k3"];
			@$atu[$i][4] = $row["k4"];
			@$atu[$i][5] = $row["k5"];
			$i++;
		}
		return $atu;
	}
	
	function get_alt(){
		include 'configdb.php';
		$alternatif = $mysqli->query("select * from alternatif");
		if(!$alternatif){
			echo $mysqli->connect_errno." - ".$mysqli->connect_error;
			exit();
		}
		$i=0;
		while ($row = $alternatif->fetch_assoc()) {
			@$alt[$i][0] = $row["k1"];
			@$alt[$i][1] = $row["k2"];
			@$alt[$i][2] = $row["k3"];
			@$alt[$i][3] = $row["k4"];
			$i++;
		}
		return $alt;
	}
	
	function pilih($w,$x,$y,$z){
		include 'configdb.php';
		$pilih = $mysqli->query("select * from aturan where ".$w."='".$x."' AND ".$y."='".$z."'");
		return $pilih->num_rows;
	}
	
	function print_ar(array $x){	//just for print array
		echo "<pre>";
		print_r($x);
		echo "</pre></br>";
	}
?>