<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';


if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}
$mapa_id = $_GET['mapa_id'] ?? 1;

enarol_mapa_eliminar_titulo($_GET['id']);
header('Location: ../titulos.php?mapa_id='.$mapa_id);

?>