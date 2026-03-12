<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';


if(!check_permision("diablo_oscuro_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}
print_r($_POST);
$res = enarol_mapa_add_marcador($_POST['nombre'], $_POST['desc'], $_POST['x'], $_POST['y']);

header('Location: ../marcadores.php');

?>