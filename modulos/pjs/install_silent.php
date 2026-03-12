<?php
// Script de auto-instalación llamado desde bootstrap.php (no se llama por HTTP de forma directa)
// Herederá las variables $db_enarol y $mod_folder del propio bootstrap

try {
	$queries = [
		'CREATE TABLE IF NOT EXISTS "pjs" (
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

    // Registrar módulo como instalado correctamente en la BBDD Global (core_settings)
    $stmt = $db_enarol->prepare("INSERT INTO core_settings (clave, valor) VALUES (?, ?)");
    $stmt->execute(["module_" . $mod_folder . "_installed", "1"]);

} catch (PDOException $e) {
	// Fallo silencioso en log (El framework luego manejará mejor los logs)
    error_log("Error auto-instalando módulo '" . $mod_folder . "': " . $e->getMessage());
}
?>
