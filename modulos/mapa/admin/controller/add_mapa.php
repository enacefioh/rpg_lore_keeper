<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}

$nombre = $_POST['nombre'] ?? 'Nuevo Mapa';
$img = $_POST['img'] ?? 'mapa.jpg';

// Intentamos obtener dimensiones reales si el archivo existe
$path = __DIR__ . "/../../res/" . $img;
$ancho = 3000;
$alto = 3000;
if (file_exists($path)) {
    $info = getimagesize($path);
    if ($info) {
        $ancho = $info[0];
        $alto = $info[1];
    }
}

$res = enarol_mapa_add_mapa($nombre, $img, $ancho, $alto);

header('Location: ../index.php?success=Mapa creado correctamente');
?>
