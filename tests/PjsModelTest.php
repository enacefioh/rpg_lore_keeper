<?php
use PHPUnit\Framework\TestCase;

class PjsModelTest extends TestCase
{
    /**
     * Este es nuestro primer test profesional.
     * Probaremos la función 'enarol_pjs_get_img_url' que creamos en la sesión anterior.
     */
    public function testGetImgUrlReturnsDefaultIfEmpty()
    {
        // CASO 1: Si le pasamos un nombre vacío, debería devolver el retrato por defecto.
        $resultado = enarol_pjs_get_img_url("");
        
        $this->assertEquals('../res/pjs_img_library/default.jpg', $resultado);
    }

    public function testGetImgUrlReturnsLibraryPathIfNotInUploads()
    {
        // CASO 2: Si el archivo NO existe en uploads, debería asumir que está en la librería.
        // Ojo: Esto depende de que el archivo no exista físicamente en la carpeta de tests.
        $resultado = enarol_pjs_get_img_url("archivo_fantasma.jpg");
        
        $this->assertEquals('../res/pjs_img_library/archivo_fantasma.jpg', $resultado);
    }
}
