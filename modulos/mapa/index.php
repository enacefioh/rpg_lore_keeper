<?php 
	/*
		Módulo Mapa Enarol
		V 26.2.221
	*/
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	require_once '../../config.php';
	$HEADERS_ADICIONALES = "<script src='js/leaflet.js'></script>";
	$HEADERS_ADICIONALES .= "<link rel='stylesheet' href='css/leaflet.css' />";
	$HEADERS_ADICIONALES .="<link rel='stylesheet' href='css/style.css' />";
	require '../../header.php';
?>

		<div id="map"></div>
		<script src="js/map.php" style='text/javascript'></script>
<?php

	require '../../footer.php';
?>	