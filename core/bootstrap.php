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

// 1.5. Configuración Central y Autogeneración del Núcleo
$db_enarol->exec('CREATE TABLE IF NOT EXISTS "core_settings" (
    "clave" TEXT NOT NULL,
    "valor" TEXT,
    PRIMARY KEY("clave")
)');

// Leer ajustes globales
$CORE_SETTINGS = [];
$res_settings = $db_enarol->query("SELECT * FROM core_settings");
if ($res_settings) {
    foreach ($res_settings->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $CORE_SETTINGS[$row['clave']] = $row['valor'];
    }
}

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
        
        // Cargar Metainfo y autoinstalar si es necesario
        $info_path = $dir . '/info.php';
        if (file_exists($info_path)) {
            $mod_info = require $info_path;
            if (is_array($mod_info)) {
               $mod_folder = basename($dir);
               $mod_info['folder'] = $mod_folder; // Guardar el nombre de la carpeta para URL
               
               // ¿Está instalado este módulo en BD?
               $is_installed_key = "module_" . $mod_folder . "_installed";
               if (!isset($CORE_SETTINGS[$is_installed_key])) {
                   // No está instalado. Ejecutamos silenciosamente su install.php si existe
                   $install_path = $dir . '/install_silent.php';
                   if (file_exists($install_path)) {
                       require_once $install_path; // install_silent debe encargarse de hacer INSERT en core_settings al acabar
                   } else {
                        // Temporal hook backward compatible
                        $install_path_old = $dir . '/install.php';
                        if(file_exists($install_path_old)){
                             // Por ahora no lo ejecutamos en automático porque tiene redirecciones y die()
                             // pero marcamos que existe
                        }
                   }
               }
               
               $MODULOS_INSTALADOS[] = $mod_info;
            }
        }
    }
}
?>
