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
$res = enarol_mapa_add_titulo($mapa_id, $_POST['nombre'], $_POST['zmin'], $_POST['zmax'], $_POST['tam'], $_POST['x'], $_POST['y']);

header('Location: ../titulos.php?mapa_id='.$mapa_id);

?>
