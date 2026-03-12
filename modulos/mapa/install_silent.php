<?php
// Script de auto-instalación llamado desde bootstrap.php (no se llama por HTTP de forma directa)
// Herederá las variables $db_enarol y $mod_folder del propio bootstrap

try {
	$queries = [
		'CREATE TABLE IF NOT EXISTS "elementos_mapa" (
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
		'CREATE TABLE IF NOT EXISTS "marcadores_mapa" (
			"id"	INTEGER,
			"x"	INTEGER NOT NULL,
			"y"	INTEGER NOT NULL,
			"nombre"	TEXT NOT NULL,
			"html"	TEXT,
			PRIMARY KEY("id")
		)',
		'CREATE TABLE IF NOT EXISTS "titulos_mapa" (
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

    // Registrar módulo como instalado correctamente en la BBDD Global (core_settings)
    $stmt = $db_enarol->prepare("INSERT INTO core_settings (clave, valor) VALUES (?, ?)");
    $stmt->execute(["module_" . $mod_folder . "_installed", "1"]);

} catch (PDOException $e) {
	// Fallo silencioso en log (El framework luego manejará mejor los logs)
    error_log("Error auto-instalando módulo '" . $mod_folder . "': " . $e->getMessage());
}
?>
