<?php
use PHPUnit\Framework\TestCase;

class MapIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        global $db_enarol;
        
        // 1. Crear tabla central de ajustes (necesaria para el núcleo)
        $db_enarol->exec('CREATE TABLE IF NOT EXISTS "core_settings" ("clave" TEXT PRIMARY KEY, "valor" TEXT)');
        
        // 2. Ejecutar la instalación REAL de los módulos (Fuente de Verdad)
        $modulos = ['mapa', 'pjs'];
        foreach ($modulos as $mod_folder) {
            // Definimos la variable que espera el script de instalación
            $install_path = __DIR__ . "/../public/modulos/$mod_folder/install_silent.php";
            if (file_exists($install_path)) {
                require $install_path; 
            }
        }
        
        // 3. Limpiar datos previos del test
        $db_enarol->exec("DELETE FROM mapas");
        $db_enarol->exec("DELETE FROM marcadores_mapa");
    }

    public function testCompleteMapWorkflow()
    {
        // 1. Crear un mapa
        $resMap = enarol_mapa_add_mapa("Mapa de Test", "test.jpg");
        $this->assertTrue($resMap['success']);
        $mapId = $resMap['id'];

        // 2. Añadir un marcador
        $resMarker = enarol_mapa_add_marcador($mapId, "Punto Kilométrico 0", "Inicio del test", 100, 100);
        $this->assertTrue($resMarker['success']);

        // 3. Añadir un título
        $resTitle = enarol_mapa_add_titulo($mapId, "Reino de Pruebas", 0, 10, 1, 50, 50);
        $this->assertTrue($resTitle['success']);

        // 4. Añadir un elemento
        $resElem = enarol_mapa_add_elemento($mapId, "Cofre", 10, 10, 20, 20, "chest");
        $this->assertTrue($resElem['success']);

        // 5. Verificar que todo está en la base de datos
        $marcadores = enarol_mapa_get_marcadores($mapId);
        $this->assertCount(1, $marcadores);
        $this->assertEquals("Punto Kilométrico 0", $marcadores[0]['nombre']);
    }
}
