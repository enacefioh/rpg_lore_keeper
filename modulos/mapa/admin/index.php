<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../config.php';
include '../../../admin/header.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "<div class='card'> No tienes permiso para gestionar mapas! </div>";
} else {
    $mapas = enarol_mapa_get_mapas();
?>
<div class="card">
    <h2 style="text-align:center;">Gestión de Mapas (Instancias)</h2>
    <p style="text-align:center; color: #666;">Selecciona el mapa que deseas editar o crea uno nuevo.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px;">
        <?php foreach($mapas as $m): ?>
        <div class="card" style="margin:0; border: 1px solid var(--accent-gold); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <img src="../res/<?php echo htmlspecialchars($m['img']); ?>" style="width:100%; height: 150px; object-fit: cover; border-radius: 8px;">
                <h3 style="margin: 10px 0;"><?php echo htmlspecialchars($m['nombre']); ?></h3>
                <small>Dimensiones: <?php echo $m['ancho']; ?>x<?php echo $m['alto']; ?> px</small>
            </div>
            
            <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 5px;">
                <a href="marcadores.php?mapa_id=<?php echo $m['id']; ?>" class="button" style="text-align:center; background: var(--accent-gold); color: #000; text-decoration: none; padding: 8px; border-radius: 4px;">Marcadores</a>
                <a href="titulos.php?mapa_id=<?php echo $m['id']; ?>" class="button" style="text-align:center; background: var(--accent-gold); color: #000; text-decoration: none; padding: 8px; border-radius: 4px;">Títulos</a>
                <a href="elementos.php?mapa_id=<?php echo $m['id']; ?>" class="button" style="text-align:center; background: var(--accent-gold); color: #000; text-decoration: none; padding: 8px; border-radius: 4px;">Elementos</a>
                <a href="configurar.php?mapa_id=<?php echo $m['id']; ?>" class="button" style="text-align:center; background: #3498db; color: white; text-decoration: none; padding: 8px; border-radius: 4px;">Configurar ⚙️</a>
                <a href="../index.php?mapa_id=<?php echo $m['id']; ?>" target="_blank" style="text-align:center; font-size: 0.8em; color: var(--accent-gold); margin-top: 5px;">👁️ Ver en vivo</a>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Card para añadir nuevo -->
        <div class="card" style="margin:0; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; min-height: 200px; cursor: pointer;" onclick="document.getElementById('modal_nuevo_mapa').showModal()">
            <div style="text-align:center;">
                <span style="font-size: 3rem; color: #ccc;">+</span>
                <p style="color: #666;">Crear Nuevo Mapa</p>
            </div>
        </div>
    </div>
</div>

<dialog id="modal_nuevo_mapa" class="card" style="width: 90%; max-width: 500px; padding: 20px; border: 1px solid var(--accent-gold);">
    <h3 style="text-align:center;">Nuevo Mapa</h3>
    <form method="post" action="controller/add_mapa.php" enctype="multipart/form-data">
        <label>Nombre del Mapa:</label>
        <input type="text" name="nombre" placeholder="Ej: Las Catacumbas" required style="width:100%; margin-bottom: 15px;">
        
        <label>Archivo de Imagen (JPG/PNG):</label>
        <input type="file" name="mapa_img" accept="image/jpeg,image/png" required style="width:100%; margin-bottom: 15px;">
        
        <p style="font-size: 0.8em; color: #888; margin-bottom: 15px;">* La imagen se subirá y procesará automáticamente para calcular sus dimensiones.</p>
        
        <div style="display: flex; gap: 10px;">
            <input type="submit" value="Crear Mapa" style="flex: 1;">
            <button type="button" onclick="document.getElementById('modal_nuevo_mapa').close()" style="flex: 1; background: #666;">Cancelar</button>
        </div>
    </form>
</dialog>

<?php
}
include '../../../admin/footer.php';
?>
