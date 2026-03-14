<?php
// Este archivo es el "arranque" de nuestros tests.
require_once __DIR__ . '/../vendor/autoload.php';

// Definimos el entorno de test: base de datos en RAM para velocidad y limpieza
$DATABASE_PATH = ':memory:';

// Cargamos el motor de la base de datos
require_once __DIR__ . '/../core/db.php';
global $db_enarol;
$db_enarol = conectar_bd($DATABASE_PATH);

// Cargamos los modelos para que estén disponibles en los tests
require_once __DIR__ . '/../public/modulos/pjs/model.php';
require_once __DIR__ . '/../public/modulos/mapa/model.php';
