# Terragon - Compendio de Rol Modular

Terragon es un CMS ligero, rápido y modular escrito en PHP nativo orientado a servir de apoyo interactivo para campañas de juegos de rol de fantasía. 

## Funcionalidades principales

- **Mapa Interactivo:** Motor cartográfico basado en mapa libre con puntos de interés y marcadores.
- **Sistema de Módulos (Plug&Play):** Añade extensiones y utilidades simplemente arrastrando carpetas en `/modulos/`.
- **Cero dependencias pesadas:** Construido con Vanilla JS y CSS flexbox, sin jQuery.
- **Base de Datos embebida:** Utiliza SQLite nativo sin necesidad de conectarse a motores SQL externos.

## Instalación en 3 Pasos 🚀

El entorno está preparado para ejecutarse rápidamente desde cualquier servidor básico de PHP.

1. **Clona el repositorio** o sube los archivos a la carpeta pública de tu servidor.
2. **Duplica el `config.example.php`** y renómbralo a `config.php`. Ajusta las URL principales usando tu dominio (o `localhost`).
3. **Servidor Local (Para Desarrollo):**
   Si deseas probar el proyecto en tu ordenador sin configurar XAMPP, abre tu terminal en el directorio del proyecto y ejecuta:
   ```bash
   php -S 0.0.0.0:8000
   ```
   *(Esto hará la web accesible desde tu ordenador en `http://localhost:8000` y desde tu móvil usando tu IP local `http://192.168.1.x:8000`)*

## Desarrollo de Módulos
Terragon detecta automáticamente los módulos dentro de la carpeta `/modulos/`. 

Para crear un módulo nuevo, crea una carpeta y añade en su interior un archivo llamado `info.php` que devuelva un array con su nombre e icono. Automáticamente se unirá al menú de navegación principal.
```php
<?php
// Ejemplo info.php
return [
    'nombre' => 'Diario',
    'icono'  => '📖'
];
```
