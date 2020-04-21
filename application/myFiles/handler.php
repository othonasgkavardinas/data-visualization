<?php

$graph = $_GET['graph'];

switch ($graph) {
	case 'timeline':
		header('Location: /myFiles/timeline.php');
		break;
	case 'bar_chart':
		header('Location: /myFiles/bar_chart.php');
		break;
	case 'scatter_plot':
		header('Location: /myFiles/scatter_plot.php');
}
?>
