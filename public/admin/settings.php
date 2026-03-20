<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config.php';
include 'header.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "<div class='card'> No tienes permiso para configurar el sistema! </div>";
} else {
    // Si se ha enviado el formulario de ajustes generales, guardamos
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['settings'])) {
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
                        <?php echo htmlspecialchars($m['nombre'] ?? $m['folder']); ?>
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
        <h3>Participantes de la Partida</h3>
        <?php
        // 1. Asegurar que los permisos existen
        $perm_admin = $SLUG_UNICO_PARTIDA . "_admin";
        $perm_jugador = $SLUG_UNICO_PARTIDA . "_jugador";
        
        $admin_perm_id = get_permission_id($perm_admin);
        if ($admin_perm_id == -1) {
            create_perm($perm_admin, $SLUG_UNICO_PARTIDA, "general", "Admin de la partida $SLUG_UNICO_PARTIDA");
            $admin_perm_id = get_permission_id($perm_admin);
        }
        
        $jugador_perm_id = get_permission_id($perm_jugador);
        if ($jugador_perm_id == -1) {
            create_perm($perm_jugador, $SLUG_UNICO_PARTIDA, "general", "Jugador de la partida $SLUG_UNICO_PARTIDA");
            $jugador_perm_id = get_permission_id($perm_jugador);
        }

        // 2. Procesar Acciones de Participantes
        if (isset($_GET['action_user'])) {
            $u_id = (int)$_GET['user_id'];
            if ($_GET['action_user'] === 'remove') {
                $p_id = (int)$_GET['perm_id'];
                // Seguridad: solo si tiene permiso para gestionar esto
                if (is_user_in_group("administradores") || ($p_id == $jugador_perm_id && check_permision($perm_admin))) {
                    remove_perm_from_user($p_id, $u_id);
                    echo "<script>window.location.href='settings.php?success=Participante eliminado';</script>";
                    exit();
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_participant'])) {
            $u_id = (int)$_POST['user_id'];
            $rol = $_POST['rol']; // 'admin' o 'jugador'
            
            $target_perm_id = ($rol === 'admin') ? $admin_perm_id : $jugador_perm_id;
            
            // Jerarquía de permisos
            $can_add = false;
            if (is_user_in_group("administradores")) {
                $can_add = true; // El admin global puede todo
            } elseif ($rol === 'jugador' && check_permision($perm_admin)) {
                $can_add = true; // El admin de partida puede añadir jugadores
            }

            if ($can_add) {
                add_perm_to_user($target_perm_id, $u_id);
                echo "<script>window.location.href='settings.php?success=Participante añadido';</script>";
                exit();
            } else {
                echo "<div class='badge' style='background:#ffcccc; color:#cc0000;'>No tienes permiso para asignar este rol.</div>";
            }
        }

        // 3. Listar participantes
        $stmt_users = $db->prepare("
            SELECT u.id, u.username, p.permission, p.id as p_id
            FROM users u
            JOIN user_permissions up ON u.id = up.user_id
            JOIN permissions p ON up.permission_id = p.id
            WHERE p.permission IN (?, ?)
            ORDER BY u.username ASC
        ");
        $stmt_users->execute([$perm_admin, $perm_jugador]);
        $participantes = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <table style="width:100%; margin-top:10px;">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Rol en Partida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($participantes as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['username']); ?></td>
                    <td>
                        <span class="badge" style="background: <?php echo str_contains($p['permission'], '_admin') ? '#ffe0b2; color: #e65100;' : '#e1f5fe; color: #0288d1;'; ?>">
                            <?php echo str_contains($p['permission'], '_admin') ? 'Administrador' : 'Jugador'; ?>
                        </span>
                    </td>
                    <td>
                        <?php if (is_user_in_group("administradores") || (str_contains($p['permission'], '_jugador') && check_permision($perm_admin))): ?>
                            <a href="settings.php?action_user=remove&user_id=<?php echo $p['id']; ?>&perm_id=<?php echo $p['p_id']; ?>" 
                               class="button btn-delete" 
                               onclick="return confirm('¿Quitar a este usuario de la partida?')" 
                               style="background:#e74c3c;">Quitar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; if(empty($participantes)) echo "<tr><td colspan='3'>No hay participantes registrados.</td></tr>"; ?>
            </tbody>
        </table>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

        <h4>Añadir Nuevo Participante</h4>
        <form method="post" class="form-group" style="flex-direction: row; gap: 10px; align-items: flex-end;">
            <div style="flex:1;">
                <label>Usuario Global:</label>
                <select name="user_id" required>
                    <option value="">-- Seleccionar Usuario --</option>
                    <?php 
                    $todos_users = get_all_users();
                    foreach($todos_users as $tu): ?>
                        <option value="<?php echo $tu['id']; ?>"><?php echo htmlspecialchars($tu['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="width: 150px;">
                <label>Rol:</label>
                <select name="rol" required>
                    <option value="jugador">Jugador</option>
                    <?php if(is_user_in_group("administradores")): ?>
                        <option value="admin">Administrador</option>
                    <?php endif; ?>
                </select>
            </div>
            <input type="submit" name="add_participant" value="Añadir" class="button">
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
