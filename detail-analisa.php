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
	
	<!-- chart.js -->
	<script src="ui/js/Chart.Core.js"></script>
	<script src="ui/js/Chart.Doughnut.js"></script>

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
			  <li class="active"><a href="analisa.php">Analisa</a></li>
			</ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
		<ol class="breadcrumb">
		  <li><a href="index.php">Home</a></li>
		  <li><a href="analisa.php">Analisa</a></li>
		  <li class="active">Detail Analisa</li>
		</ol>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="panel panel-primary">
		  <!-- Default panel contents -->
		  <?php
									$result = $mysqli->query("select * from alternatif where id_alternatif = ".$_GET['id']."");
									if(!$result){
										echo $mysqli->connect_errno." - ".$mysqli->connect_error;
										exit();
									}
									while($row = $result->fetch_assoc()){
										$input = array ($row["k1"],$row["k2"],$row["k3"],$row["k4"]);
										$lokasi = $row['alternatif'];
									}
		  ?>
		  <div class="panel-heading">Detail Analisa Alternatif Lokasi <?php echo ucwords($lokasi);?></div>
		  <div class="panel-body">
			<center>
				<?php
					$k = jml_kriteria();
					$a = jml_aturan();
					$kri = get_kriteria();
					$atu = get_aturan();
					
					echo "<h5>Data Aturan</h5>"; //tabel aturan
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<tr class='info'>";
						for($i=1;$i<=$k;$i++){
							echo "<th>".ucwords($kri[$i])."</th>";
						}
					echo "</tr>";
					for($i=1;$i<=$a;$i++){
						echo "<tr>";
						for($j=1;$j<=$k;$j++){
							echo "<td>".ucwords($atu[$i][$j])."</td>";
						}
						echo "</tr>";
					}
					echo "</table>";
					//=================================================================================================================================================\\
					echo "</br>";
					$at[1]=pilih('k1','dekat','k5','ya'); $at[2]=pilih('k1','sedang','k5','ya'); $at[3]=pilih('k1','jauh','k5','ya');
					$at[4]=pilih('k1','dekat','k5','tidak'); $at[5]=pilih('k1','sedang','k5','tidak'); $at[6]=pilih('k1','jauh','k5','tidak');
					$jat1=$at[1]+$at[2]+$at[3]; $jat2=$at[4]+$at[5]+$at[6];
					$prob[1]['dekat']['ya']=round(($at[1]/$jat1),4); $prob[1]['dekat']['tidak']=round(($at[4]/$jat2),4);
					$prob[1]['sedang']['ya']=round(($at[2]/$jat1),4); $prob[1]['sedang']['tidak']=round(($at[5]/$jat2),4);
					$prob[1]['jauh']['ya']=round(($at[3]/$jat1),4); $prob[1]['jauh']['tidak']=round(($at[6]/$jat2),4);
					$jat3=round($prob[1]['dekat']['ya']+$prob[1]['sedang']['ya']+$prob[1]['jauh']['ya']);
					$jat4=round($prob[1]['dekat']['tidak']+$prob[1]['sedang']['tidak']+$prob[1]['jauh']['tidak']);
					echo "<h5>Nilai Atribut ".ucwords($kri[1])."</h5>"; //tabel atribut jarak
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<tr class='info'><th>".ucwords($kri[1])."</th><th>Ya</th><th>Tidak</th><th>Probabilitas Ya</th><th>Probabilitas Tidak</th></tr>";
					echo "<tr><td>Dekat</td><td>".$at[1]."</td><td>".$at[4]."</td><td>".$prob[1]['dekat']['ya']."</td><td>".$prob[1]['dekat']['tidak']."</td><tr>";
					echo "<tr><td>Sedang</td><td>".$at[2]."</td><td>".$at[5]."</td><td>".$prob[1]['sedang']['ya']."</td><td>".$prob[1]['sedang']['tidak']."</td><tr>";
					echo "<tr><td>Jauh</td><td>".$at[3]."</td><td>".$at[6]."</td><td>".$prob[1]['jauh']['ya']."</td><td>".$prob[1]['jauh']['tidak']."</td><tr>";
					echo "<tr><td>Jumlah</td><td>".$jat1."</td><td>".$jat2."</td><td>".$jat3."</td><td>".$jat4."</td><tr>";
					echo "</table>";
					//=================================================================================================================================================\\
					echo "</br>";
					$at[1]=pilih('k2','murah','k5','ya'); $at[2]=pilih('k2','sedang','k5','ya'); $at[3]=pilih('k2','mahal','k5','ya');
					$at[4]=pilih('k2','murah','k5','tidak'); $at[5]=pilih('k2','sedang','k5','tidak'); $at[6]=pilih('k2','mahal','k5','tidak');
					$jat1=$at[1]+$at[2]+$at[3]; $jat2=$at[4]+$at[5]+$at[6];
					$prob[2]['murah']['ya']=round(($at[1]/$jat1),4); $prob[2]['murah']['tidak']=round(($at[4]/$jat2),4);
					$prob[2]['sedang']['ya']=round(($at[2]/$jat1),4); $prob[2]['sedang']['tidak']=round(($at[5]/$jat2),4);
					$prob[2]['mahal']['ya']=round(($at[3]/$jat1),4); $prob[2]['mahal']['tidak']=round(($at[6]/$jat2),4);
					$jat3=round($prob[2]['murah']['ya']+$prob[2]['sedang']['ya']+$prob[2]['mahal']['ya']);
					$jat4=round($prob[2]['murah']['tidak']+$prob[2]['sedang']['tidak']+$prob[2]['mahal']['tidak']);
					echo "<h5>Nilai Atribut ".ucwords($kri[2])."</h5>"; //tabel atribut harga beli
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<tr class='info'><th>".ucwords($kri[2])."</th><th>Ya</th><th>Tidak</th><th>Probabilitas Ya</th><th>Probabilitas Tidak</th></tr>";
					echo "<tr><td>Murah</td><td>".$at[1]."</td><td>".$at[4]."</td><td>".$prob[2]['murah']['ya']."</td><td>".$prob[2]['murah']['tidak']."</td><tr>";
					echo "<tr><td>Sedang</td><td>".$at[2]."</td><td>".$at[5]."</td><td>".$prob[2]['sedang']['ya']."</td><td>".$prob[2]['sedang']['tidak']."</td><tr>";
					echo "<tr><td>Mahal</td><td>".$at[3]."</td><td>".$at[6]."</td><td>".$prob[2]['mahal']['ya']."</td><td>".$prob[2]['mahal']['tidak']."</td><tr>";
					echo "<tr><td>Jumlah</td><td>".$jat1."</td><td>".$jat2."</td><td>".$jat3."</td><td>".$jat4."</td><tr>";
					echo "</table>";
					//=================================================================================================================================================\\
					echo "</br>";
					$at[1]=pilih('k3','kecil','k5','ya'); $at[2]=pilih('k3','sedang','k5','ya'); $at[3]=pilih('k3','besar','k5','ya');
					$at[4]=pilih('k3','kecil','k5','tidak'); $at[5]=pilih('k3','sedang','k5','tidak'); $at[6]=pilih('k3','besar','k5','tidak');
					$jat1=$at[1]+$at[2]+$at[3]; $jat2=$at[4]+$at[5]+$at[6];
					$prob[3]['kecil']['ya']=round(($at[1]/$jat1),4); $prob[3]['kecil']['tidak']=round(($at[4]/$jat2),4);
					$prob[3]['sedang']['ya']=round(($at[2]/$jat1),4); $prob[3]['sedang']['tidak']=round(($at[5]/$jat2),4);
					$prob[3]['besar']['ya']=round(($at[3]/$jat1),4); $prob[3]['besar']['tidak']=round(($at[6]/$jat2),4);
					$jat3=round($prob[3]['kecil']['ya']+$prob[3]['sedang']['ya']+$prob[3]['besar']['ya']);
					$jat4=round($prob[3]['kecil']['tidak']+$prob[3]['sedang']['tidak']+$prob[3]['besar']['tidak']);
					echo "<h5>Nilai Atribut ".ucwords($kri[3])."</h5>"; //tabel atribut luas bangunan
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<tr class='info'><th>".ucwords($kri[3])."</th><th>Ya</th><th>Tidak</th><th>Probabilitas Ya</th><th>Probabilitas Tidak</th></tr>";
					echo "<tr><td>Kecil</td><td>".$at[1]."</td><td>".$at[4]."</td><td>".$prob[3]['kecil']['ya']."</td><td>".$prob[3]['kecil']['tidak']."</td><tr>";
					echo "<tr><td>Sedang</td><td>".$at[2]."</td><td>".$at[5]."</td><td>".$prob[3]['sedang']['ya']."</td><td>".$prob[3]['sedang']['tidak']."</td><tr>";
					echo "<tr><td>Besar</td><td>".$at[3]."</td><td>".$at[6]."</td><td>".$prob[3]['besar']['ya']."</td><td>".$prob[3]['besar']['tidak']."</td><tr>";
					echo "<tr><td>Jumlah</td><td>".$jat1."</td><td>".$jat2."</td><td>".$jat3."</td><td>".$jat4."</td><tr>";
					echo "</table>";
					//=================================================================================================================================================\\
					echo "</br>";
					$at[1]=pilih('k4','ada','k5','ya'); $at[2]=pilih('k4','tidak','k5','ya');
					$at[4]=pilih('k4','ada','k5','tidak'); $at[5]=pilih('k4','tidak','k5','tidak');
					$jat1=$at[1]+$at[2]; $jat2=$at[4]+$at[5];
					$prob[4]['ada']['ya']=round(($at[1]/$jat1),4); $prob[4]['ada']['tidak']=round(($at[4]/$jat2),4);
					$prob[4]['tidak']['ya']=round(($at[2]/$jat1),4); $prob[4]['tidak']['tidak']=round(($at[5]/$jat2),4);
					$jat3=round($prob[4]['ada']['ya']+$prob[4]['tidak']['ya']);
					$jat4=round($prob[4]['ada']['tidak']+$prob[4]['tidak']['tidak']);
					echo "<h5>Nilai Atribut ".ucwords($kri[4])."</h5>"; //tabel atribut angkutan umum
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<tr class='info'><th>".ucwords($kri[4])."</th><th>Ya</th><th>Tidak</th><th>Probabilitas Ya</th><th>Probabilitas Tidak</th></tr>";
					echo "<tr><td>Ada</td><td>".$at[1]."</td><td>".$at[4]."</td><td>".$prob[4]['ada']['ya']."</td><td>".$prob[4]['ada']['tidak']."</td><tr>";
					echo "<tr><td>Tidak</td><td>".$at[2]."</td><td>".$at[5]."</td><td>".$prob[4]['tidak']['ya']."</td><td>".$prob[4]['tidak']['tidak']."</td><tr>";
					echo "<tr><td>Jumlah</td><td>".$jat1."</td><td>".$jat2."</td><td>".$jat3."</td><td>".$jat4."</td><tr>";
					echo "</table>";
					//=================================================================================================================================================\\
					echo "</br>";
					$at[1]=pilih('k5','ya','k5','ya'); $at[2]=pilih('k5','tidak','k5','tidak');
					$prob[5]['ya']=round(($at[1]/$a),4); $prob[5]['tidak']=round(($at[2]/$a),4);
					echo "<h5>Nilai Atribut ".ucwords($kri[5])."</h5>"; //tabel atribut strategis
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<tr class='info'><th>".ucwords($kri[5])."</th><th>Ya</th><th>Tidak</th><th>Probabilitas Ya</th><th>Probabilitas Tidak</th></tr>";
					echo "<tr><td>Jumlah</td><td>".$at[1]."</td><td>".$at[2]."</td><td>".$prob[5]['ya']."</td><td>".$prob[5]['tidak']."</td><tr>";
					echo "</table>";
					//=================================================================================================================================================\\
					echo "</br>";
					$likeya=round(($prob[1][$input[0]]['ya']*$prob[2][$input[1]]['ya']*$prob[3][$input[2]]['ya']*$prob[4][$input[3]]['ya']*$prob[5]['ya']),4);
					$likeno=round(($prob[1][$input[0]]['tidak']*$prob[2][$input[1]]['tidak']*$prob[3][$input[2]]['tidak']*$prob[4][$input[3]]['tidak']*$prob[5]['tidak']),4);
					$like=$likeya+$likeno;
					$ya=$likeya/$like; $no=$likeno/$like;
					echo "<h5>Perhitungan Likehood</h5>";
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<tr class='info'><th></th><th>".ucwords($kri[1])."</th><th>".ucwords($kri[2])."</th><th>".ucwords($kri[3])."</th><th>".ucwords($kri[4])."</th><th>".ucwords($kri[5])."</th><th>Hasil</th></tr>";
					echo "<tr><td></td><td>".ucwords($input[0])."</td><td>".ucwords($input[1])."</td><td>".ucwords($input[2])."</td><td>".ucwords($input[3])."</td><td></td><td></td></tr>";
					echo "<tr><td>Likehood Ya</td><td>".$prob[1][$input[0]]['ya']."</td><td>".$prob[2][$input[1]]['ya']."</td><td>".$prob[3][$input[2]]['ya']."</td><td>".$prob[4][$input[3]]['ya']."</td><td>".$prob[5]['ya']."</td><td>".$likeya."</td></tr>";
					echo "<tr><td>Likehood Tidak</td><td>".$prob[1][$input[0]]['tidak']."</td><td>".$prob[2][$input[1]]['tidak']."</td><td>".$prob[3][$input[2]]['tidak']."</td><td>".$prob[4][$input[3]]['tidak']."</td><td>".$prob[5]['tidak']."</td><td>".$likeno."</td></tr>";
					echo "</table>";
					//=================================================================================================================================================\\
					echo "</br>";
					echo "<h5>Perhitungan Probabilitas Akhir</h5>";
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<tr><th class='info'>Probabilitas Ya</th><td>".round($ya,4)."</td></tr>";
					echo "<tr><th class='info'>Probabilitas Tidak</th><td>".round($no,4)."</td></tr>";
					echo "<tr><th class='info'>Karena Jumlah</th><td>".($ya+$no)."</td></tr>";
					echo "<tr><th class='info'>Maka</th><td>";
					if(($ya+$no)==1) echo "<div class='text-info'><b>Terbukti</b></div>";
					else "<div class='text-danger'><b>Tidak terbukti, terdapat kesalahan pada Data Training</b></div>";
					echo "</td></tr>";
					echo "</table>";
					echo "<div class='row'><div class='col-lg-4'></div>";
					echo "<div class='col-lg-4'><div id='canvas-holder'><canvas id='chart-area' width='100' height='100'/></div></div>"; //chart was here
					echo "<div class='col-lg-4'></div></div>";
					echo "</br>";
					if(($ya+$no)==1){
						if($ya>$no) echo "<div class='alert alert-info'>Berdasarkan perhitungan, maka Lokasi ".ucwords($lokasi)." tersebut strategis untuk pendirian Homestay.</div>";
						else echo "<div class='alert alert-danger'>Berdasarkan perhitungan, maka Lokasi ".ucwords($lokasi)." tersebut kurang strategis untuk pendirian Homestay.</div>";
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
			</center>
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
	<!--script src="ui/js/Chart.min.js"></script-->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="ui/js/ie10-viewport-bug-workaround.js"></script>
	<script>
		var doughnutData = [
				{
					value: <?php echo round($no,4);?>,
					label: "Probabilitas Tidak"
				},
				{
					value: <?php echo round($ya,4);?>,
					label: "Probabilitas Ya"
				}
			];
			window.onload = function(){
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : true});
			};
	</script>
</body></html>