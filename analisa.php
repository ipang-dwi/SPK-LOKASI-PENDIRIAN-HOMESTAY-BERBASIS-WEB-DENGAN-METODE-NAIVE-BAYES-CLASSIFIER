<?php
	session_start();
	include('configdb.php');
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title><?php echo $_SESSION['judul']." - ".$_SESSION['by'];?></title>
	
    <!-- Bootstrap core CSS -->
    <link href="ui/css/bootstrap.css" rel="stylesheet">
	<link href="ui/css/spacelab.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="ui/css/jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--script src="./index_files/ie-emulation-modes-warning.js"></script-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <style>
	body {
	padding-top: 10px; /* 10px to make the container go all the way to the bottom of the topbar */
	//background-image:url(img/bg_dotted.png);
  </style>
  
  <body>
	<div class="container">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo $_SESSION['judul'];?></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="index.php">Home</a></li>
              <li><a href="kriteria.php">Data Kriteria</a></li>
              <li><a href="aturan.php">Data Aturan</a></li>
			  <li><a href="alternatif.php">Alternatif Lokasi</a></li>
			  <li class="active"><a href="#">Analisa</a></li>
			</ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
		<ol class="breadcrumb">
		  <li><a href="index.php">Home</a></li>
		  <li class="active">Analisa</li>
		</ol>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="panel panel-primary">
		  <!-- Default panel contents -->
		  <div class="panel-heading">Analisa</div>
		  <div class="panel-body">
			<center>
			<h4>Hasil Analisa Lokasi Alternatif Homestay</h4>
			<div style="width: 50%">
				<canvas id="canvas" height="450" width="600"></canvas>
			</div>
						<?php
							$jml_alt = jml_alt();
							$alt = get_alt();
							$alt_name = get_alt_name();
							for($i=0;$i<$jml_alt;$i++){
								$r[$i]=bayes($alt[$i]);
							}
							uasort($r,'cmp');
							echo "<div class='alert alert-info'>".ucwords($alt_name[array_search((end($r)), $r)])." adalah lokasi paling strategis untuk Homestay.</div>";
							$kriteria = $mysqli->query("select * from kriteria");
							if(!$kriteria){
								echo $mysqli->connect_errno." - ".$mysqli->connect_error;
								exit();
							}
							$alternatif = $mysqli->query("select * from alternatif");
							if(!$alternatif){
								echo $mysqli->connect_errno." - ".$mysqli->connect_error;
								exit();
							}
							$i=0;
						?>
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>No.</th>
					<th>Alternatif</th>
				<?php
					$i = 1;
					while ($row = $kriteria->fetch_assoc()) {
						echo '<th>'.ucwords($row["kriteria"]).'</th>';
					}
				?>
					<th>Prob. Ya</th>
					<th>Prob. Tidak</th>
					<th>Opsi</th>
				</tr>
			</thead>
		 
			<tbody>
										<?php
												$i = 1;
												while ($row = $alternatif->fetch_assoc()) {
													echo '<tr>';
													echo '<td>'.$i++.'</td>';
													echo '<td>'.ucwords($row["alternatif"]).'</td>';
													echo '<td>'.ucwords($row["k1"]).'</td>';
													echo '<td>'.ucwords($row["k2"]).'</td>';
													echo '<td>'.ucwords($row["k3"]).'</td>';
													echo '<td>'.ucwords($row["k4"]).'</td>';
													if(($r[($i-2)][2])=='ya') echo '<td class="text-info"><b>';
													else echo '<td class="text-danger"><b>';
													echo ucwords($r[($i-2)][2]).'</b></td>';
													echo '<td>'.$r[($i-2)][0].'</td>';
													echo '<td>'.$r[($i-2)][1].'</td>';
													echo '<td><!--a href="#"><i class="fa fa-search"></i></a-->';
													?>
														  <a href="detail-analisa.php?id=<?php echo $row['id_alternatif'];?>" target=_blank class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Detail</a>
														  </td>
													<?php
													echo '</tr>';
												}
										?>
			</tbody>
			</table>
		  </div>
		  <div class="panel-footer"><?php echo $_SESSION['by'];?><div class="pull-right"></div></div>
		</div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="ui/js/jquery-1.10.2.min.js"></script>
	<script src="ui/js/bootstrap.min.js"></script>
	<script src="ui/js/bootswatch.js"></script>
	<script src="ui/js/Chart.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="ui/js/ie10-viewport-bug-workaround.js"></script>
	<!-- chart -->
	<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

	var barChartData = {
		labels : [
			<?php
				for($i=0;$i<$jml_alt;$i++){
					echo '"'.ucwords($alt_name[$i]).'",';
				}
			?>
		],
		datasets : [
			{
				fillColor : "rgba(0,255,255,0.75)",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(0,255,255,1)",
				highlightStroke : "rgba(151,187,205,1)",
				data : [
					<?php
						for($i=0;$i<$jml_alt;$i++){
							echo '"'.$r[$i][0].'",';
						}
					?>
					]
			},
			{
				fillColor : "rgba(255,0,0,0.75)",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(255,0,0,1)",
				highlightStroke: "rgba(220,220,220,1)",
				data : [
					<?php
						for($i=0;$i<$jml_alt;$i++){
							echo '"'.$r[$i][1].'",';
						}
					?>
					]
			}
		]

	}
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}
	</script>
<?php
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
	
	function cmp($a, $b) {
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
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
	
	function get_alt_name(){
		include 'configdb.php';
		$alternatif = $mysqli->query("select * from alternatif");
		if(!$alternatif){
			echo $mysqli->connect_errno." - ".$mysqli->connect_error;
			exit();
		}
		$i=0;
		while ($row = $alternatif->fetch_assoc()) {
			@$alt[$i] = $row["alternatif"];
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
</body></html>