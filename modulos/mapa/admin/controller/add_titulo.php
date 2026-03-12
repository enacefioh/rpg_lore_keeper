<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';


if(!check_permision("diablo_oscuro_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}
$res = enarol_mapa_add_titulo($_POST['nombre'], $_POST['zmin'], $_POST['zmax'], $_POST['tam'], $_POST['x'], $_POST['y']);
//print_r($_POST);
header('Location: ../titulos.php');

?>