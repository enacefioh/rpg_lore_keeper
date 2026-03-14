<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../../config.php';

$pj_id = $_POST['id'];
$pj = enarol_get_pj_by_id($pj_id);

if(!check_permision($SLUG_UNICO_PARTIDA."_admin") && $pj['usuario'] != $_SESSION['user_id']){
	echo "No tienes permiso para estar aquí!";
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 2. Recoger datos del formulario
    $descripcion = $_POST['desc'] ?? '';
    $trasfondo = $_POST['html'] ?? '';
    $nombre_imagen = $_POST['img'] ?? $pj['img']; 
    $img_type = $_POST['img_type'] ?? 'library';

    // Si es de la librería, la copiamos a uploads para que sea independiente
    if ($img_type == 'library' && $nombre_imagen != $pj['img']) {
        $source = __DIR__ . '/../../res/pjs_img_library/' . $nombre_imagen;
        $file_new_name = time() . "_ref_" . $nombre_imagen;
        if (file_exists($source)) {
            copy($source, '../../uploads/' . $file_new_name);
            $nombre_imagen = $file_new_name;
        }
    }

    // 4. Llamar a tu función del modelo
    $resultado = enarol_pjs_modificar_pj($id, $nombre, $usuario_id, $descripcion, $trasfondo, $nombre_imagen);

    // 5. Redirección o respuesta
    if ($resultado) {
        header("Location: ../personaje_editar.php?id=$id&success=PJ%20Modificado!");
    } else {
        header("Location: ../personaje_editar.php?id=$id&error=PJ%20NO%20Modificado!");
    }
    exit;
}
?>
