<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config.php';
include 'header.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "<div class='card'> No tienes permiso para configurar el sistema! </div>";
} else {
    // Si se ha enviado el formulario, guardamos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($_POST['settings'] as $clave => $valor) {
            $stmt = $db_enarol->prepare("INSERT INTO core_settings (clave, valor) VALUES (?, ?) ON CONFLICT(clave) DO UPDATE SET valor = excluded.valor");
            $stmt->execute([$clave, $valor]);
        }
        echo "<script>window.location.href='settings.php?success=Configuración actualizada';</script>";
        exit();
    }
?>
<div class="card">
    <h2 style="text-align:center;">Configuración General del Sistema</h2>
    <form method="post">
        <div class="form-group">
            <label>Nombre del Sitio / Partida:</label>
            <input type="text" name="settings[site_name]" value="<?php echo htmlspecialchars($CORE_SETTINGS['site_name'] ?? 'Terragon RPG'); ?>">
        </div>
        
        <div class="form-group">
            <label>Descripción de la Partida:</label>
            <textarea name="settings[site_description]"><?php echo htmlspecialchars($CORE_SETTINGS['site_description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Módulo de Inicio Predeterminado:</label>
            <select name="settings[default_module]">
                <?php foreach($MODULOS_INSTALADOS as $m): ?>
                    <option value="<?php echo $m['folder']; ?>" <?php echo ($CORE_SETTINGS['default_module'] ?? 'mapa') == $m['folder'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($m['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-top: 20px;">
            <input type="submit" value="Guardar Ajustes Globales" class="button">
        </div>
    </form>
</div>

<div class="card">
    <h3>Información de Módulos</h3>
    <table style="width:100%;">
        <thead>
            <tr>
                <th>Carpeta</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($MODULOS_INSTALADOS as $m): ?>
            <tr>
                <td><code>modulos/<?php echo $m['folder']; ?>/</code></td>
                <td><span class="badge" style="background: #ccffcc; color: #006600;">Instalado</span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
}
include 'footer.php';
?>
