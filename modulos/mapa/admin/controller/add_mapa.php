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

if (!isset($_FILES['mapa_img']) || $_FILES['mapa_img']['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../index.php?err=Error eligiendo el archivo de imagen');
    exit();
}

// 1. Procesar archivo
$file_tmp = $_FILES['mapa_img']['tmp_name'];
$file_name_original = $_FILES['mapa_img']['name'];
$ext = strtolower(pathinfo($file_name_original, PATHINFO_EXTENSION));

// Validar extensión
if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
    header('Location: ../index.php?err=Formato no permitido. Solo JPG y PNG.');
    exit();
}

// Nombre único
$new_name = time() . "." . $ext;
$target_path = __DIR__ . "/../../res/" . $new_name;

if (move_uploaded_file($file_tmp, $target_path)) {
    // 2. Obtener dimensiones
    $ancho = 3000;
    $alto = 3000;
    $info = getimagesize($target_path);
    if ($info) {
        $ancho = $info[0];
        $alto = $info[1];
    }

    // 3. Calcular centro y valores iniciales
    $lat_inicial = $alto / 2;
    $lng_inicial = $ancho / 2;
    $zoom_inicial = 0;

    // 4. Guardar en BD
    $res = enarol_mapa_add_mapa($nombre, $new_name, $ancho, $alto, $zoom_inicial, $lat_inicial, $lng_inicial);

    header('Location: ../index.php?success=Mapa creado correctamente con imagen subida');
} else {
    header('Location: ../index.php?err=Error moviendo el archivo al servidor');
}
?>
