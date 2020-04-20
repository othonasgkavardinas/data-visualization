<!DOCTYPE html>
<meta charset='utf-8'>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />


<?php

	function getJSArray($q, $attr) {
		$row = mysqli_fetch_assoc($q);

        	$result = "[\"" . $row[$attr] . "\"";
        	while ($row = mysqli_fetch_assoc($q))
                	$result = $result . ",\"" . $row[$attr] . "\"";
		$result = $result . "]";
		return $result;
	}

        $db = mysqli_connect("localhost", "root", "", "mydb")
                or die('Error connecting to MySQL server.');

        $p_countries = mysqli_query($db, "SELECT C_Name FROM Country")
                or die('Error quering database.');
        $p_years = mysqli_query($db, "SELECT Y_ID FROM Year")
                or die('Error quering database.');
	$p_measures = mysqli_query($db, "SELECT DISTINCT M_Category FROM Measure")
		or die('Error quering database.');

	$p_c = getJSArray($p_countries, 'C_Name');
	$p_y = getJSArray($p_years, 'Y_ID');
	$p_m = getJSArray($p_measures, 'M_Category');
	
	mysqli_close($db);
?>

<html>
<head>
        <title>Timeline</title>

	<script>

	var all_countries = <?php echo $p_c; ?>;
	var all_years = <?php echo $p_y; ?>;
	var all_measures = <?php echo $p_m; ?>;
	all_countries.sort();
	all_measures.sort();


        var countries_list = [];
	var measures_list = [];
	var from_year;
	var to_year;
	var five_years_flag;

	function addCountry() {
		var x = document.getElementById("mySelect1");
		var i = x.selectedIndex;
		if(!countries_list.includes(x.options[i].text)) {
                	countries_list.push(x.options[i].text);
                	document.getElementById("countries").innerHTML = countries_list;
                	document.getElementById("hiddenC").value = countries_list;
		}

        }
        function addMeasure() {
		var x = document.getElementById("mySelect2");
		var i = x.selectedIndex;
		if(!measures_list.includes(x.options[i].text)) {
                	measures_list.push(x.options[i].text);
                	document.getElementById("measures").innerHTML = measures_list;
                	document.getElementById("hiddenM").value = measures_list;
		}
        }
        function removeCountry() {
		if(document.getElementById("country_index").value == "")
                        countries_list.splice(0, 1);
                else
                        countries_list.splice(document.getElementById("country_index").value, 1);
                document.getElementById("countries").innerHTML = countries_list;
                document.getElementById("country_index").value = ""
                document.getElementById("hiddenC").value = countries_list;
        }
        function removeMeasure() {
		if(document.getElementById("measure_index").value == "")
                        measures_list.splice(0, 1);
                else
                        measures_list.splice(document.getElementById("measure_index").value, 1);
                document.getElementById("measures").innerHTML = measures_list;
                document.getElementById("measure_index").value = ""
                document.getElementById("hiddenM").value = measures_list;
        }

	function setStartYear() {
		var x = document.getElementById("mySelect3");
		var i = x.selectedIndex;
		from_year = x.options[i].text;
                document.getElementById("s_year").innerHTML = x.options[i].text;
                document.getElementById("hiddenSY").value = x.options[i].text;
	}

	function setEndYear() {
		var x = document.getElementById("mySelect4");
		var i = x.selectedIndex;
		end_year = x.options[i].text;
                document.getElementById("e_year").innerHTML = x.options[i].text;
                document.getElementById("hiddenEY").value =  x.options[i].text;
	}

	function setFiveYears() {
		var x = document.getElementById("mySelect5");
		var i = x.selectedIndex;
		if(x.options[i].text === "YES") {
                	document.getElementById("five_years").innerHTML = "per five years";
			document.getElementById("flag").value = "timeline_5";
		}
		else {
                	document.getElementById("five_years").innerHTML = "per year";
			document.getElementById("flag").value = "timeline";
		}
	}

        function resetData() {
                countries_list.splice(0, countries_list.length);
                measures_list.splice(0, measures_list.length);
                document.getElementById("countries").innerHTML = countries_list;
                document.getElementById("hiddenC").value = countries_list;
                document.getElementById("measures").innerHTML = measures_list;
                document.getElementById("hiddenM").value = measures_list;

                document.getElementById("s_year").innerHTML = all_years[0];
		document.getElementById("hiddenSY").value = all_years[0];
                document.getElementById("e_year").innerHTML = all_years[all_years.length-1];
		document.getElementById("hiddenEY").value = all_years[all_years.length-1];
                document.getElementById("five_years").innerHTML = "per year";
		document.getElementById("flag").value = "timeline";
	}

	</script>

	<script>
	function addOptions(list) {
		for(i=0; i<list.length; i++)
			document.write("<option>"+list[i]+"</option>");
	}

	</script>

</head>

<body>

        <h1><i>Timeline Graph</i></h1>
	<h2>Insert Countries</h2>
	<form>
	  <select id="mySelect1" size="8">
	    <script>addOptions(all_countries)</script>
	  </select>
	</form>
        <button onclick="addCountry()">Submit Country</button>
        <br><br>

	<h2>Insert Measures</h2>
	<form>
	  <select id="mySelect2" size="8">
	    <script>addOptions(all_measures)</script>
	  </select>
	</form>
        <button onclick="addMeasure()">Submit Measure</button>
        <br><br>

        <h2>Remove element by index</h2>
        <input type="text" id="country_index" name="country2" value=""><br>
        <button onclick="removeCountry()">Remove Country</button>
        <br><br>

        <input type="text" id="measure_index" name="measure2" value=""><br>
        <button onclick="removeMeasure()">Remove Measure</button>
        <br><br>


	<h2>Choose start year</h2>
	<form>
	  <select id="mySelect3" size="4">
	    <script>addOptions(all_years)</script>
	  </select>
	</form>
        <button onclick="setStartYear()">Submit</button>
	<br><br>


	<h2>Choose end year</h2>
	<form>
	  <select id="mySelect4" size="4">
	    <script>addOptions(all_years)</script>
	  </select>
	</form>
        <button onclick="setEndYear()">Submit</button>
        <br><br>

	<h2>Per five years?</h2>
	<form>
	  <select id="mySelect5" size="2">
		<option>YES</option>
		<option>NO</option>
	  </select>
	</form>
        <button onclick="setFiveYears()">Submit</button>
        <br><br>

        <br><br>


        <button onclick="resetData()">Reset</button>

        <br><br>

        <p style="display:inline;"><u>Countries:</u></p>
        <p id="countries" style="display:inline;"></p>
	<br><br>
        <p style="display:inline;"><u>Measures:</u></p>
	<p id="measures" style="display:inline;"></p>
	<br><br>
	<p style="display:inline;">from </p>
	<p id="s_year" style="display:inline;"><script>document.write(all_years[0]);</script></p>
	<p style="display:inline;"> to </p>
	<p id="e_year" style="display:inline;"><script>document.write(all_years[all_years.length-1]);</script></p>
	<p id="five_years" style="display:inline;">per year</p>
	<p style="display:inline;">.</p>

        <br><br>

        <form action="search.php" method="GET", enctype="utf-8">
                <input type="text" style="display:none" name="countries" id="hiddenC">
                <input type="text" style="display:none" name="measures" id="hiddenM">
                <input type="text" style="display:none" name="start_year" id="hiddenSY">
		<input type="text" style="display:none" name="end_year" id="hiddenEY">
		<input type="text" style="display:none" name="flag" id="flag">
                <input type="submit" value="Submit">
	</form>

	<br><br><br>

	<script>
	document.getElementById("hiddenSY").value = all_years[0];
        document.getElementById("e_year").innerHTML = all_years[all_years.length-1];
	document.getElementById("hiddenEY").value = all_years[all_years.length-1];
	document.getElementById("five_years").innerHTML = "per year";
	document.getElementById("flag").value = "timeline";
	</script>


</body>
</html>

