<?php

/*function enarol_mapa_get_items_admin_menu(){
	return [
		'Marcadores' => $URL.'modulos/mapa/admin/marcadores.php', 
		'Elementos' =>  $URL.'modulos/mapa/admin/elementos.php'
	];
}*/

function enarol_mapa_get_marcadores($mapa_id) { 
    global $db_enarol;

    try {
        $sql = 'SELECT * FROM marcadores_mapa WHERE mapa_id = ?;';

        $stmt = $db_enarol->prepare($sql);
        $stmt->execute([$mapa_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
function enarol_mapa_get_titulos($mapa_id) { 
    global $db_enarol;

    try {
        $sql = 'SELECT * FROM titulos_mapa WHERE mapa_id = ?;';

        $stmt = $db_enarol->prepare($sql);
        $stmt->execute([$mapa_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
function enarol_mapa_get_elementos($mapa_id) { 
    global $db_enarol;

    try {
        $sql = 'SELECT * FROM elementos_mapa WHERE mapa_id = ?;';

        $stmt = $db_enarol->prepare($sql);
        $stmt->execute([$mapa_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}

function enarol_mapa_add_elemento($mapa_id, $nombre, $x0, $y0, $x1, $y1, $icono){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('INSERT INTO elementos_mapa (mapa_id, x0, y0, x1, y1 , nombre, icono) VALUES (?, ?, ?, ?, ?, ?, ?)');
        
        // 2. Ejecutar
        $stmt->execute([$mapa_id, $x0, $y0, $x1, $y1, $nombre, $icono]);

        return ['success' => true, 'id' => $db_enarol->lastInsertId()];

    } catch (PDOException $e) {
        // Error común: usuario o email ya existen (si tienes índices UNIQUE)
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
function enarol_mapa_add_marcador($mapa_id, $nombre, $desc, $x, $y){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('INSERT INTO marcadores_mapa (mapa_id, x, y, nombre, html) VALUES (?, ?, ?, ?, ?)');
        
        // 2. Ejecutar
        $stmt->execute([$mapa_id, $x, $y, $nombre, $desc]);

        return ['success' => true, 'id' => $db_enarol->lastInsertId()];

    } catch (PDOException $e) {
        // Error común: usuario o email ya existen (si tienes índices UNIQUE)
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
function enarol_mapa_add_titulo($mapa_id, $nombre, $zmin, $zmax, $tam, $x, $y){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('INSERT INTO titulos_mapa (mapa_id, x,y,zmin,zmax,nombre,tam) VALUES (?, ?, ?, ?, ?, ?, ?)');
        
        // 2. Ejecutar
        $stmt->execute([$mapa_id, $x, $y, $zmin, $zmax, $nombre, $tam]);

        return ['success' => true, 'id' => $db_enarol->lastInsertId()];

    } catch (PDOException $e) {
        // Error común: usuario o email ya existen (si tienes índices UNIQUE)
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
function enarol_mapa_eliminar_elemento($id){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('DELETE FROM elementos_mapa WHERE id = ?');
        
        // 2. Ejecutar
        $stmt->execute([$id]);

        return ['success' => true];

    } catch (PDOException $e) {
        return ['success' => false];
    }
}
function enarol_mapa_eliminar_marcador($id){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('DELETE FROM marcadores_mapa WHERE id = ?');
        
        // 2. Ejecutar
        $stmt->execute([$id]);

        return ['success' => true];

    } catch (PDOException $e) {
        return ['success' => false];
    }
}
function enarol_mapa_eliminar_titulo($id){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('DELETE FROM titulos_mapa WHERE id = ?');
        
        // 2. Ejecutar
        $stmt->execute([$id]);

        return ['success' => true];

    } catch (PDOException $e) {
        return ['success' => false];
    }
}
function enarol_mapa_get_mapas() {
    global $db_enarol;
    try {
        $sql = 'SELECT * FROM mapas;';
        $stmt = $db_enarol->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}

function enarol_mapa_get_mapa($id) {
    global $db_enarol;
    try {
        $stmt = $db_enarol->prepare('SELECT * FROM mapas WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return null;
    }
}

function enarol_mapa_add_mapa($nombre, $img, $ancho = 3000, $alto = 3000, $zoom = 0, $lat = 1500, $lng = 1500) {
    global $db_enarol;
    try {
        $stmt = $db_enarol->prepare('INSERT INTO mapas (nombre, img, ancho, alto, zoom_inicial, lat_inicial, lng_inicial) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nombre, $img, $ancho, $alto, $zoom, $lat, $lng]);
        return ['success' => true, 'id' => $db_enarol->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function enarol_mapa_update_mapa($id, $nombre, $img, $ancho, $alto, $zoom, $lat, $lng) {
    global $db_enarol;
    try {
        $stmt = $db_enarol->prepare('UPDATE mapas SET nombre = ?, img = ?, ancho = ?, alto = ?, zoom_inicial = ?, lat_inicial = ?, lng_inicial = ? WHERE id = ?');
        $stmt->execute([$nombre, $img, $ancho, $alto, $zoom, $lat, $lng, $id]);
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>