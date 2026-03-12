<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';


if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}

$mapa_id = $_POST['mapa_id'] ?? 1;
$res = enarol_mapa_add_marcador($mapa_id, $_POST['nombre'], $_POST['desc'], $_POST['x'], $_POST['y']);

header('Location: ../marcadores.php?mapa_id='.$mapa_id);

?>