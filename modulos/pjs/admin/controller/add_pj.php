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
    $nombre_imagen = null;

    // 3. Gestión de la imagen (Upload)
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $ruta_destino = '../uploads/'; // Asegúrate de que esta carpeta exista y tenga permisos
        
        // Limpiamos el nombre del archivo para evitar problemas
        $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $nombre_imagen = time() . "_" . preg_replace("/[^a-zA-Z0-9]/", "", $nombre) . "." . $extension;
        
        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
			$ruta_destino = '../../uploads/';
			$tmp_name = $_FILES['img']['tmp_name'];
			$extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
			$nombre_imagen = time() . "_" . preg_replace("/[^a-zA-Z0-9]/", "", $nombre) . "." . $extension;

			// 1. Obtener dimensiones actuales
			list($ancho_orig, $alto_orig) = getimagesize($tmp_name);
			$max_dim = 1000;

			if ($ancho_orig > $max_dim || $alto_orig > $max_dim) {
				// 2. Calcular nuevas dimensiones manteniendo la proporción
				$ratio = $ancho_orig / $alto_orig;
				if ($ratio > 1) {
					$nuevo_ancho = $max_dim;
					$nuevo_alto = $max_dim / $ratio;
				} else {
					$nuevo_alto = $max_dim;
					$nuevo_ancho = $max_dim * $ratio;
				}

				// 3. Crear lienzo y redimensionar según formato
				$lienzo = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
				
				// Cargar imagen origen según extensión
				if ($extension == 'png') {
					$origen = imagecreatefrompng($tmp_name);
					// Mantener transparencia del PNG
					imagealphablending($lienzo, false);
					imagesavealpha($lienzo, true);
				} else {
					$origen = imagecreatefromjpeg($tmp_name);
				}

				imagecopyresampled($lienzo, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho_orig, $alto_orig);

				// 4. Guardar imagen procesada
				if ($extension == 'png') {
					imagepng($lienzo, $ruta_destino . $nombre_imagen);
				} else {
					imagejpeg($lienzo, $ruta_destino . $nombre_imagen, 85); // 85 es calidad
				}
				
				imagedestroy($lienzo);
				imagedestroy($origen);
			} else {
				// Si es pequeña, mover directamente
				move_uploaded_file($tmp_name, $ruta_destino . $nombre_imagen);
			}
		}
    }

    // 4. Llamar a tu función del modelo
    // Asumo el orden de tus parámetros: add_pj($nombre, $usuario_id, $desc, $trasfondo, $img)
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