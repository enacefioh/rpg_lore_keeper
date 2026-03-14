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
		'CREATE TABLE "pjs" (
			"id"	INTEGER,
			"nombre"	TEXT NOT NULL,
			"img"	TEXT,
			"desc"	TEXT,
			"texto"	TEXT,
			"usuario"	INTEGER,
			PRIMARY KEY("id")
		)'
	];

	foreach ($queries as $query) {
		$db_enarol->exec($query);
	}

//Añadir permiso $SLUG_UNICO_PARTIDA."_admin"; a bd de login

	header('Location: ../admin/index.php?success=Pjs%20instalado%20con%20éxito!');

} catch (PDOException $e) {
	header('Location: ../admin/index.php?error=Error%20instalando%20pjs');
}

	

?>
