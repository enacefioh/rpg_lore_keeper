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

function enarol_pjs_modificar_pj($id, $nombre, $usuario_id, $descripcion, $trasfondo){
	 global $db_enarol;

	try {
		// 1. Preparar la inserción
		$stmt = $db_enarol->prepare('UPDATE pjs SET nombre = ?, desc = ?, texto = ?, usuario = ? WHERE id = ?');
		
		// 2. Ejecutar
		$stmt->execute([$nombre, $descripcion, $trasfondo, $usuario_id, $id]);

		return ['success' => true, 'id' => $id];

	} catch (PDOException $e) {
		// Error común: usuario o email ya existen (si tienes índices UNIQUE)
		return ['success' => false, 'error' => $e->getMessage()];
	}
	return true;
}

?>