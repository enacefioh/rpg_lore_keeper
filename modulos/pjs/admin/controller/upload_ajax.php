<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo json_encode(['success' => false, 'error' => 'No tienes permiso']);
	exit();
}

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['img'])) {
    $nombre = $_POST['nombre'] ?? 'Upload';
    $ruta_destino = '../../uploads/';
    $tmp_name = $_FILES['img']['tmp_name'];
    $extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
    $nombre_imagen = time() . "_" . preg_replace("/[^a-zA-Z0-9]/", "", $nombre) . "." . $extension;

    // Procesamiento básico de redimensión (copiado de add_pj.php)
    list($ancho_orig, $alto_orig) = getimagesize($tmp_name);
    $max_dim = 1000;

    if ($ancho_orig > $max_dim || $alto_orig > $max_dim) {
        $ratio = $ancho_orig / $alto_orig;
        if ($ratio > 1) {
            $nuevo_ancho = $max_dim;
            $nuevo_alto = $max_dim / $ratio;
        } else {
            $nuevo_alto = $max_dim;
            $nuevo_ancho = $max_dim * $ratio;
        }

        $lienzo = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
        if ($extension == 'png') {
            $origen = imagecreatefrompng($tmp_name);
            imagealphablending($lienzo, false);
            imagesavealpha($lienzo, true);
        } else {
            $origen = imagecreatefromjpeg($tmp_name);
        }

        imagecopyresampled($lienzo, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho_orig, $alto_orig);

        if ($extension == 'png') {
            imagepng($lienzo, $ruta_destino . $nombre_imagen);
        } else {
            imagejpeg($lienzo, $ruta_destino . $nombre_imagen, 85);
        }
        
        imagedestroy($lienzo);
        imagedestroy($origen);
        $response = ['success' => true, 'filename' => $nombre_imagen];
    } else {
        if (move_uploaded_file($tmp_name, $ruta_destino . $nombre_imagen)) {
            $response = ['success' => true, 'filename' => $nombre_imagen];
        } else {
            $response = ['success' => false, 'error' => 'Error al mover el archivo'];
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
