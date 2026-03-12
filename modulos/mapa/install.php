<?php
require_once '../../config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $ENA_LOGIN_PATH;

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}


try{

	$queries = [
		'CREATE TABLE "elementos_mapa" (
			"id"	INTEGER,
			"x0"	INTEGER NOT NULL,
			"y0"	INTEGER NOT NULL,
			"x1"	INTEGER NOT NULL,
			"y1"	INTEGER NOT NULL,
			"zmin"	INTEGER DEFAULT 0,
			"zmax"	INTEGER DEFAULT 99,
			"icono"	TEXT NOT NULL,
			"nombre"	TEXT NOT NULL,
			PRIMARY KEY("id")
		)',
		'CREATE TABLE "marcadores_mapa" (
			"id"	INTEGER,
			"x"	INTEGER NOT NULL,
			"y"	INTEGER NOT NULL,
			"nombre"	TEXT NOT NULL,
			"html"	TEXT,
			PRIMARY KEY("id")
		)',
		'CREATE TABLE "titulos_mapa" (
			"id"	INTEGER,
			"x"	INTEGER NOT NULL,
			"y"	INTEGER NOT NULL,
			"zmin"	INTEGER DEFAULT 0,
			"zmax"	INTEGER DEFAULT 99,
			"tam"	INTEGER DEFAULT 3,
			"nombre"	TEXT NOT NULL,
			PRIMARY KEY("id")
		)'
	];

	foreach ($queries as $query) {
		$db_enarol->exec($query);
	}

//Añadir permiso $SLUG_UNICO_PARTIDA."_admin"; a bd de login

	header('Location: ../admin/index.php?success=Mapa%20instalado%20con%20éxito!');

} catch (PDOException $e) {
	header('Location: ../admin/index.php?error=Error%20instalando%20mapa');
}

	

?>