<?php
/**
 * ARCHIVO DE CONFIGURACIÓN DE EJEMPLO
 * 
 * Instrucciones de instalación:
 * 1. Duplica este archivo y renómbralo a 'config.php'
 * 2. Rellena las constantes con los datos reales de tu entorno.
 * 
 * NOTA: ¡Nunca subas tu 'config.php' real al repositorio público!
 */

// Muestra errores en desarrollo (poner a 0 en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Rutas base (Asegúrate de que acaban en barra '/')
$URL = "http://localhost:8000/"; 
$SLUG_UNICO_PARTIDA = "terragon";

// Rutas físicas en el servidor
// Usa __DIR__ de forma relativa a este archivo para hacer el proyecto portable
$DATABASE_PATH = __DIR__ . "/terragon.sqlite"; 

// Ruta al sistema de login externo
// Ejemplo: __DIR__ ."/../../tools/ena_login.php";
$ENA_LOGIN_PATH = ""; 

// Configuración del Mapa
// Sustituir el mapa en res/imgs/mapa.jpg
$MAPA_WIDTH = 3000; // En px
$MAPA_HEIGHT = 3000; // En px

// -- A PARTIR DE AQUÍ NO TOCAR (Lógica de inicialización) --
require_once __DIR__ . '/core/bootstrap.php';
?>
