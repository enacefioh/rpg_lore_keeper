<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';


if(!check_permision("diablo_oscuro_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}

enarol_mapa_eliminar_elemento($_GET['id']);
header('Location: ../elementos.php');

?>