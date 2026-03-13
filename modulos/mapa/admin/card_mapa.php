<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}
?>

<link rel="stylesheet" href="<?php echo $URL; ?>css/leaflet.css" />
<script src="<?php echo $URL; ?>js/leaflet.js"></script>
<div class="card">
	<div id="map" style='width:100%; min-height: 40vh;'></div>
	<span id='info_zoom' >Zoom: 0</span>
</div>



<script src="../js/map.php?mapa_id=<?php echo $_GET['mapa_id'] ?? 1; ?>" type="text/javascript"></script>
<script type='text/javascript'>
	map.on('zoomend', function() {
		var nivelActual = map.getZoom();
		document.getElementById('info_zoom').innerHTML = "Zoom: "+nivelActual;
	
	});
</script>
<style>
.leaflet-div-icon {
    border: 0px !important;
}

.titulo1, .titulo2, .titulo3, .titulo4, .titulo5{
	color: #000099;
    font-weight: bold;
    text-shadow: 2px 2px 0px #fff;
}
.titulo1{
	
	font-size: clamp(2rem, 8vw, 4rem);
	
}
.titulo2{
	
	font-size: clamp(1.5rem, 6vw, 3rem);
}
.titulo3{	
	font-size: clamp(1rem, 4vw, 2rem);
}
.titulo4{
	font-size: clamp(0.75rem, 3vw, 1.5rem);
}
.titulo5{
	font-size: clamp(0.5rem, 2vw, 1rem);
}
</style>