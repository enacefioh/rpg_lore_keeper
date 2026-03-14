<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}

$id = $_POST['id'];
$nombre = $_POST['nombre'] ?? 'Sin nombre';
$img = $_POST['img'] ?? 'mapa.jpg';
$ancho = $_POST['ancho'] ?? 3000;
$alto = $_POST['alto'] ?? 3000;
$zoom = $_POST['zoom_inicial'] ?? 0;
$lat = $_POST['lat_inicial'] ?? 1500;
$lng = $_POST['lng_inicial'] ?? 1500;

$res = enarol_mapa_update_mapa($id, $nombre, $img, $ancho, $alto, $zoom, $lat, $lng);

header('Location: ../configurar.php?mapa_id='.$id.'&success=Configuración guardada');
?>
