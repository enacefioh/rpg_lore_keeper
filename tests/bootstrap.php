<?php
// Este archivo es el "arranque" de nuestros tests.
require_once __DIR__ . '/../vendor/autoload.php';

// Definimos el entorno de test: base de datos en RAM para velocidad y limpieza
$DATABASE_PATH = ':memory:';

// Configuración para el sistema de login (ena_login)
// Forzamos a que use una base de datos en memoria también
$DATABASE_LOGIN_PATH = ':memory:';
$ENA_LOGIN_PATH = __DIR__ . '/../.login/ena_login.php';

// Mock de la ruta de DB para ena_login ANTES de cargarlo
$DB_PATH = $DATABASE_LOGIN_PATH;

require_once $ENA_LOGIN_PATH;
// ena_login ya crea las tablas al ver que no existe el archivo (en este caso :memory:)
// pero como $DB_PATH es global, lo inyectamos.
global $db; // Conexión de ena_login

// Cargamos el motor de la base de datos de la app
require_once __DIR__ . '/../public/core/db.php';
global $db_enarol;
$db_enarol = conectar_bd($DATABASE_PATH);

// Cargamos los modelos para que estén disponibles en los tests
require_once __DIR__ . '/../public/modulos/pjs/model.php';
require_once __DIR__ . '/../public/modulos/mapa/model.php';
