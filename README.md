# Terragon - Compendio de Rol Modular

Terragon es un CMS ligero, rápido y modular escrito en PHP nativo orientado a servir de apoyo interactivo para campañas de juegos de rol de fantasía. 

## Funcionalidades principales

- **Arquitectura Profesional:** Estructura con carpeta pública (`public/`) para una seguridad de nivel profesional.
- **Mapa Interactivo:** Motor cartográfico basado en Leaflet con puntos de interés, títulos y elementos dinámicos.
- **Sistema de Módulos (Plug&Play):** Añade extensiones simplemente arrastrando carpetas en `/public/modulos/`.
- **Ingeniería de Calidad:** Batería de tests unitarios e integración incluidos con PHPUnit.
- **Cero Dependencias en Producción:** Todo el frontend corre con Vanilla JS y CSS flexbox.
- **Base de Datos embebida:** Utiliza SQLite nativo sin necesidad de servidores externos.

## Instalación en Producción 🚀

1. **Clona el repositorio** en tu servidor.
2. **Configura el Web Root:** Es un paso crítico. Debes apuntar la raíz de tu dominio a la carpeta `public/`.
3. **Configura la App:** Duplica `public/config.example.php` a `public/config.php` y ajusta tus URL.

## Guía de Desarrollo y Calidad 🛠️

Si quieres ampliar Terragon o ejecutar las pruebas automatizadas, necesitas **Composer**.

1. **Instalar Dependencias de Desarrollo:** 
   ```bash
   composer install
   ```
2. **Servidor Local Profesional:**
   Para que las rutas funcionen igual que en producción, ejecuta el servidor apuntando a `public`:
   ```bash
   php -S localhost:8000 -t public
   ```
3. **Ejecutar Tests Automatizados:**
   Usa PHPUnit para verificar que los cambios no han roto nada:
   ```bash
   ./vendor/bin/phpunit
   ```

## Estructura del Proyecto
- `public/`: Única carpeta accesible desde internet. Contiene scripts, estilos, core e imágenes.
- `public/core/`: Motor interno del sistema (Base de datos y cargador).
- `tests/`: Batería de pruebas automatizadas.
- `vendor/`: Librerías instaladas por Composer (no se suben a Git).
