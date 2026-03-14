<?php
/**
 * Conexión centralizada a la base de datos.
 */

function conectar_bd($ruta) {
    try {
        $db = new PDO("sqlite:" . $ruta);
        // Habilitar excepciones para capturar errores de SQLite fácilmente
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Error crítico: No se pudo conectar a la base de datos Terragon: " . $e->getMessage());
    }
}
?>
