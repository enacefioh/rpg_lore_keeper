<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';


if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 2. Recoger datos del formulario
    $nombre = $_POST['nombre'] ?? 'Sin nombre';
    $usuario_id = $_POST['user'] ?? null;
    $descripcion = $_POST['desc'] ?? '';
    $trasfondo = $_POST['html'] ?? '';
    $nombre_imagen = $_POST['gallery_img'] ?? 'default.jpg';
    $img_type = $_POST['img_type'] ?? 'library';

    // Si es de la librería, la copiamos a uploads para que sea independiente
    if ($img_type == 'library') {
        $source = __DIR__ . '/../../res/pjs_img_library/' . $nombre_imagen;
        $file_new_name = time() . "_ref_" . $nombre_imagen;
        if (file_exists($source)) {
            copy($source, '../../uploads/' . $file_new_name);
            $nombre_imagen = $file_new_name;
        }
    }

    // 4. Llamar a tu función del modelo
    $resultado = enarol_pjs_add_pj($nombre, $usuario_id, $descripcion, $trasfondo, $nombre_imagen);

    // 5. Redirección o respuesta
    if ($resultado) {
        // Éxito: volvemos a la página del mapa o lista de pjs
        header('Location: ../personajes.php?success=PJ%20Creado!');
    } else {
        // Error
        header('Location: ../personajes.php?error=Error%20creando%20pj.');
    }
    exit;
}



;

?>