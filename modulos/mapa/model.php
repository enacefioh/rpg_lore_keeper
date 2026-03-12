<?php

/*function enarol_mapa_get_items_admin_menu(){
	return [
		'Marcadores' => $URL.'modulos/mapa/admin/marcadores.php', 
		'Elementos' =>  $URL.'modulos/mapa/admin/elementos.php'
	];
}*/

function enarol_mapa_get_marcadores() { //DEVUELVE LA LISTA DE TODOS LOS MARCADORES
    global $db_enarol;

    try {
        $sql = 'SELECT * FROM marcadores_mapa;';

        $stmt = $db_enarol->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
function enarol_mapa_get_titulos() { //DEVUELVE LA LISTA DE TODOS LOS MARCADORES
    global $db_enarol;

    try {
        $sql = 'SELECT * FROM titulos_mapa;';

        $stmt = $db_enarol->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
function enarol_mapa_get_elementos() { //DEVUELVE LA LISTA DE TODOS LOS ELEMENTOS
    global $db_enarol;

    try {
        $sql = 'SELECT * FROM elementos_mapa;';

        $stmt = $db_enarol->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}

function enarol_mapa_add_elemento($nombre, $x0, $y0, $x1, $y1, $icono){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('INSERT INTO elementos_mapa (x0, y0, x1, y1 , nombre, icono) VALUES (?, ?, ?, ?, ?, ?)');
        
        // 2. Ejecutar
        $stmt->execute([$x0, $y0, $x1, $y1, $nombre, $icono]);

        return ['success' => true, 'id' => $db_enarol->lastInsertId()];

    } catch (PDOException $e) {
        // Error común: usuario o email ya existen (si tienes índices UNIQUE)
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
function enarol_mapa_add_marcador($nombre, $desc, $x, $y){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('INSERT INTO marcadores_mapa (x, y, nombre, html) VALUES (?, ?, ?, ?)');
        
        // 2. Ejecutar
        $stmt->execute([$x, $y, $nombre, $desc]);

        return ['success' => true, 'id' => $db_enarol->lastInsertId()];

    } catch (PDOException $e) {
        // Error común: usuario o email ya existen (si tienes índices UNIQUE)
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
function enarol_mapa_add_titulo($nombre, $zmin, $zmax, $tam, $x, $y){
	 global $db_enarol;

    try {
        // 1. Preparar la inserción
        $stmt = $db_enarol->prepare('INSERT INTO titulos_mapa (x,y,zmin,zmax,nombre,tam) VALUES (?, ?, ?, ?, ?, ?)');
        
        // 2. Ejecutar
        $stmt->execute([$x, $y, $zmin, $zmax, $nombre, $tam]);

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





?>