<?php
/**
 * BOOTSTRAP (Inicializador)
 * 
 * Este archivo se encarga de arrancar todo el sistema.
 * Es llamado automáticamente al final de config.php.
 */

// 1. Inicializar Base de Datos
require_once __DIR__ . '/db.php';
global $db_enarol; 
$db_enarol = conectar_bd($DATABASE_PATH);

// 2. Cargar Sistema de Login externo (EnaLogin)
if (!empty($ENA_LOGIN_PATH) && file_exists($ENA_LOGIN_PATH)) {
    require_once $ENA_LOGIN_PATH;
}

// 3. Auto-descubrir y cargar Módulos (Plug & Play)
// 1. Cargamos la lógica backend (model.php)
// 2. Cargamos los metadatos visuales del módulo (info.php) para crear el menú
$MODULOS_INSTALADOS = [];
$modulos = glob(__DIR__ . '/../modulos/*', GLOB_ONLYDIR);

if ($modulos !== false) {
    foreach ($modulos as $dir) {
        // Cargar Modelo
        $model_path = $dir . '/model.php';
        if (file_exists($model_path)) {
            require_once $model_path;
        }
        
        // Cargar Metainfo
        $info_path = $dir . '/info.php';
        if (file_exists($info_path)) {
            $mod_info = require $info_path;
            if (is_array($mod_info)) {
               $mod_info['folder'] = basename($dir); // Guardar el nombre de la carpeta para URL
               $MODULOS_INSTALADOS[] = $mod_info;
            }
        }
    }
}
?>
