<?php
	$db = mysqli_connect("localhost", "root", "", "mydb") 
		or die('Error connecting to MySQL server.');
?>


<!DOCTYPE html>
<html>
<head>
	<title>Server Work</title>
</head>

<body>


<?php 
	echo $_GET['countries']."<br>";
	echo $_GET['measures']."<br>";
	echo $_GET['start_year']."<br>";
	echo $_GET['end_year']."<br>";
	echo $_GET['flag']."<br>";
	$countries = explode(",", $_GET['countries']);
	$measures = explode(",", $_GET['measures']);
	$start_year = $_GET['start_year'];
	$end_year = $_GET['end_year'];
	$chart_type = $_GET['flag'];

	$f=fopen("res.json", "w");
	fwrite($f, "[");
	
	if(strcmp($chart_type, "timeline") == 0 || strcmp($chart_type, "bar_chart") ==0 || strcmp($chart_type, "scatter_plot") == 0) {

		for($x = 0; $x<count($countries); $x++) {
			for($y = 0; $y<count($measures); $y++) {
				$query = "SELECT Years_Y_ID, M_Category,"
				. "M_Value, M_Type, C_Name, Y_FiveYears "
				. "FROM Country, Measure, Year "
				. "WHERE C_ID = Countries_C_ID and Y_ID=Years_Y_ID and "
				. "C_Name='" . $countries[$x] . "' and M_Category='" 
				. $measures[$y] . "'"
				. " and Y_ID>=" .  $start_year . " and Y_ID<=" . $end_year;

			echo $query.'<br>';

			$meas = mysqli_query($db, $query)
				or die('Error quering database.');

			$m_arr = array();

			for($z = 0; $z<mysqli_num_rows($meas); $z++) {
				$m_arr[] = mysqli_fetch_assoc($meas);
			}

			$json_data = json_encode($m_arr);
			
			if ($x == 0 && $y == 0)
				fwrite($f, $json_data) or die("den ta katafera");
			else 
 				fwrite($f, ",".$json_data) or die("den ta katafera");
			}
 
		}
	}
	else {
		for($x = 0; $x<count($countries); $x++) {
			for($y = 0; $y<count($measures); $y++) {
				$query = "SELECT M_Category,"
				. " AVG(M_Value), C_Name, Y_FiveYears, ANY_VALUE(M_Type) "
				. "FROM Country, Measure, Year "
				. "WHERE C_ID=Countries_C_ID and Y_ID=Years_Y_ID and "
				. "C_Name='" . $countries[$x] . "' and M_Category='" 
				. $measures[$y] . "'"
				. " and Y_ID>=" .  $start_year . " and Y_ID<=" . $end_year
				. " GROUP BY Y_FiveYears ORDER BY Y_FiveYears";

			echo $query.'<br>';

			$meas = mysqli_query($db, $query)
				or die('Error quering database.');

			$m_arr = array();

			for($z = 0; $z<mysqli_num_rows($meas); $z++) {
				$m_arr[] = mysqli_fetch_assoc($meas);
			}

			$json_data = json_encode($m_arr);
			
			if ($x == 0 && $y == 0)
				fwrite($f, $json_data) or die("den ta katafera");
			else 
				fwrite($f, ",".$json_data) or die("den ta katafera");
			}
		}
	}
	 
	fwrite($f, "]");
	fclose($f);

	mysqli_close($db);
	
	if(strcmp($chart_type,"timeline")==0)
		echo "<script>location.replace('/myFiles/first_chart.html');</script>";
	else if(strcmp($chart_type,"bar_chart")==0)
		echo "<script>location.replace('/myFiles/second_chart.html');</script>";
	else if(strcmp($chart_type,"scatter_plot")==0)
		echo "<script>location.replace('/myFiles/third_chart.html');</script>";
	else if(strcmp($chart_type,"timeline_5")==0)
		echo "<script>location.replace('/myFiles/5_first_chart.html');</script>";
	else if(strcmp($chart_type,"bar_chart_5")==0)
		echo "<script>location.replace('/myFiles/5_second_chart.html');</script>";

?>

</body>
</html>
