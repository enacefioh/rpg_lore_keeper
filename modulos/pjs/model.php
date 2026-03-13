<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function enarol_pjs_add_pj($nombre, $usuario_id, $descripcion, $trasfondo, $nombre_imagen){
	 global $db_enarol;

	try {
		// 1. Preparar la inserción
		$stmt = $db_enarol->prepare('INSERT INTO pjs (nombre,img,desc,texto,usuario) VALUES (?, ?, ?, ?, ?)');
		
		// 2. Ejecutar
		$stmt->execute([$nombre,$nombre_imagen, $descripcion, $trasfondo, $usuario_id]);

		return ['success' => true, 'id' => $db_enarol->lastInsertId()];

	} catch (PDOException $e) {
		// Error común: usuario o email ya existen (si tienes índices UNIQUE)
		return ['success' => false, 'error' => $e->getMessage()];
	}
	return true;
}

function enarol_pjs_get_pjs() { //DEVUELVE LA LISTA DE TODOS LOS PJS
	global $db_enarol;

	try {
		$sql = 'SELECT * FROM pjs;';

		$stmt = $db_enarol->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);

	} catch (PDOException $e) {
		error_log($e->getMessage());
		return [];
	}
}

function enarol_get_pj_by_id($id) { 
	 global $db_enarol;

	try {
		$stmt = $db_enarol->prepare('SELECT * FROM pjs WHERE id = ? LIMIT 1');
		$stmt->execute([$id]);
		
		return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

	} catch (PDOException $e) {
		error_log($e->getMessage());
		return null;
	} 
}

function enarol_pjs_modificar_pj($id, $nombre, $usuario_id, $descripcion, $trasfondo, $nombre_imagen){
	 global $db_enarol;

	try {
		// 1. Preparar la inserción
		$stmt = $db_enarol->prepare('UPDATE pjs SET nombre = ?, desc = ?, texto = ?, usuario = ?, img = ? WHERE id = ?');
		
		// 2. Ejecutar
		$stmt->execute([$nombre, $descripcion, $trasfondo, $usuario_id, $nombre_imagen, $id]);

		return ['success' => true, 'id' => $id];

	} catch (PDOException $e) {
		// Error común: usuario o email ya existen (si tienes índices UNIQUE)
		return ['success' => false, 'error' => $e->getMessage()];
	}
}

function enarol_pjs_get_gallery_images() {
    $dir = __DIR__ . '/res/pjs_img_library/';
    $json_path = $dir . 'info.json';
    
    $files = glob($dir . '*.{jpg,jpeg,png}', GLOB_BRACE);
    $rebuild = !file_exists($json_path);
    
    if (!$rebuild) {
        $json_time = filemtime($json_path);
        foreach ($files as $file) {
            if (filemtime($file) > $json_time) {
                $rebuild = true;
                break;
            }
        }
    }
    
    if ($rebuild) {
        $gallery = [];
        $i = 1;
        foreach ($files as $file) {
            $filename = basename($file);
            
            // Renombrar si no tiene el formato pj_xxx.jpg
            if (!preg_match('/^pj_\d+\./', $filename)) {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $new_name = "pj_" . str_pad($i, 3, '0', STR_PAD_LEFT) . "." . $ext;
                $new_path = $dir . $new_name;
                
                // Evitar colisiones si ya existe
                while(file_exists($new_path)) {
                    $i++;
                    $new_name = "pj_" . str_pad($i, 3, '0', STR_PAD_LEFT) . "." . $ext;
                    $new_path = $dir . $new_name;
                }
                
                rename($file, $new_path);
                $file = $new_path;
                $filename = $new_name;
            }
            
            // Extraer Tags IPTC
            $tags = [];
            getimagesize($file, $info);
            if (isset($info['APP13'])) {
                $iptc = iptcparse($info['APP13']);
                if (isset($iptc['2#025'])) {
                    foreach ($iptc['2#025'] as $tag) {
                        // Limpiar caracteres extraños (como el ? del principio)
                        $clean_tag = preg_replace('/^[^a-zA-Z0-9]+/', '', $tag);
                        if (!empty($clean_tag)) {
                            $tags[] = strtolower($clean_tag);
                        }
                    }
                }
            }
            
            $gallery[] = [
                'filename' => $filename,
                'tags' => array_unique($tags)
            ];
            $i++;
        }
        
        file_put_contents($json_path, json_encode($gallery, JSON_PRETTY_PRINT));
        return $gallery;
    }
    
    return json_decode(file_get_contents($json_path), true);
}

function enarol_pjs_get_random_gallery_image() {
    $gallery = enarol_pjs_get_gallery_images();
    if (empty($gallery)) return 'default.jpg';
    $random_key = array_rand($gallery);
    return $gallery[$random_key]['filename'];
}

function enarol_pjs_get_img_url($filename) {
    if (empty($filename)) return '../res/pjs_img_library/default.jpg';
    if (file_exists(__DIR__ . '/uploads/' . $filename)) {
        return '../uploads/' . $filename;
    }
    return '../res/pjs_img_library/' . $filename;
}

?>