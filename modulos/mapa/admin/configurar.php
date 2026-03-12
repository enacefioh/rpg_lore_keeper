<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../config.php';
include '../../../admin/header.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "<div class='card'> No tienes permiso para configurar el mapa! </div>";
} else {
    $mapa_id = $_GET['mapa_id'] ?? 1;
    $m = enarol_mapa_get_mapa($mapa_id);
    
    if(!$m) {
        echo "<div class='card'>Mapa no encontrado.</div>";
    } else {
?>
<div class="card">
    <h2 style="text-align:center;">Configuración de Mapa: <?php echo htmlspecialchars($m['nombre']); ?></h2>
    
    <form method="post" action="controller/update_mapa.php">
        <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
        
        <div class="form-group">
            <label>Nombre del Mapa:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($m['nombre']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Archivo de Imagen:</label>
            <input type="text" name="img" value="<?php echo htmlspecialchars($m['img']); ?>" required>
            <small>Ubicación: <code>modulos/mapa/res/<?php echo htmlspecialchars($m['img']); ?></code></small>
        </div>
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Ancho (px):</label>
                <input type="number" name="ancho" value="<?php echo $m['ancho']; ?>">
            </div>
            <div class="form-group">
                <label>Alto (px):</label>
                <input type="number" name="alto" value="<?php echo $m['alto']; ?>">
            </div>
        </div>
        
        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
        <h3>Vista Inicial Preestablecida</h3>
        <p style="font-size: 0.9em; color: #666;">Define cómo se verá el mapa nada más cargar para los jugadores.</p>
        
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Zoom Inicial:</label>
                <input type="number" name="zoom_inicial" value="<?php echo $m['zoom_inicial']; ?>" step="1">
            </div>
            <div class="form-group">
                <label>Latitud (Y):</label>
                <input type="number" name="lat_inicial" value="<?php echo $m['lat_inicial']; ?>" step="0.1">
            </div>
            <div class="form-group">
                <label>Longitud (X):</label>
                <input type="number" name="lng_inicial" value="<?php echo $m['lng_inicial']; ?>" step="0.1">
            </div>
        </div>
        
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <input type="submit" value="Guardar Cambios" class="button">
            <a href="index.php" class="button" style="background: #666;">Volver</a>
        </div>
    </form>
</div>

<!-- Mini visor para ayudar a calibrar coordenadas -->
<div class="card">
    <h3>Previsualización y Calibración</h3>
    <p style="font-size: 0.8em; color: #888;">Haz click en el mapa para capturar coordenadas de centro si quieres cambiarlas.</p>
    <div id="map" style="width:100%; height: 300px; border-radius: 8px;"></div>
    <p id="coordenadas_click" style="font-weight: bold; margin-top: 10px;"></p>
</div>

<script src="../js/map.php?mapa_id=<?php echo $mapa_id; ?>" type="text/javascript"></script>
<script>
    map.on('click', function(e) {
        let lat = e.latlng.lat.toFixed(1);
        let lng = e.latlng.lng.toFixed(1);
        let zoom = map.getZoom();
        
        document.getElementById('coordenadas_click').innerHTML = "Sugerencia: Lat: " + lat + ", Lng: " + lng + ", Zoom: " + zoom;
        
        // Auto-completar los campos si el usuario quiere
        if(confirm("¿Quieres fijar esta posición y zoom como inicio predeterminado?")) {
            document.querySelector('input[name="lat_inicial"]').value = lat;
            document.querySelector('input[name="lng_inicial"]').value = lng;
            document.querySelector('input[name="zoom_inicial"]').value = zoom;
        }
    });

    // Forzar la vista inicial guardada para verificarla
    map.setView([<?php echo $m['lat_inicial']; ?>, <?php echo $m['lng_inicial']; ?>], <?php echo $m['zoom_inicial']; ?>);
</script>

<?php
    }
}
include '../../../admin/footer.php';
?>
