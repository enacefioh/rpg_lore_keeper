 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../config.php';



include '../../../admin/header.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "<div class='card'> No tienes permiso para modificar los personajes! </div>";
}else{

    $gallery = enarol_pjs_get_gallery_images();
    $random_img = enarol_pjs_get_random_gallery_image();
?>
<div class="card" style="max-width: 800px; margin: 20px auto;">
	<h3 style='text-align:center;'>Nuevo Personaje</h3>
	<form id='form_add_pj' method='post' action='controller/add_pj.php'>
        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 20px;">
            <!-- Retrato -->
            <div style="text-align:center;">
                <img id="pj_preview" src="<?php echo enarol_pjs_get_img_url($random_img); ?>" style="width: 140px; height: 140px; object-fit: cover; border-radius: 8px; border: 2px solid var(--accent-gold); background: white; cursor:pointer;" onclick="openGallery()">
                <button type="button" class="button" style="margin-top:10px; font-size: 0.8rem; padding: 5px 10px;" onclick="openGallery()">Cambiar 🎨</button>
                <input type="hidden" name="gallery_img" id="pj_img_input" value="<?php echo $random_img; ?>">
                <input type="hidden" name="img_type" id="pj_img_type" value="library">
            </div>

            <!-- Datos -->
            <div style="display:flex; flex-direction:column; gap: 10px;">
                <input type='text' name='nombre' placeholder="Nombre del héroe..." required style="font-size: 1.2rem; font-weight: bold;" />
                <select name='user'>
                <?php
                    $users = get_all_users();
                    foreach($users as $u){
                        $selected = ($u['id'] == $_SESSION['user_id']) ? 'selected' : '';
                        echo "<option value='".$u['id']."' $selected>Propietario: ".$u['username']."</option>";
                    }
                ?>
                </select>
                <input type="text" name='desc' placeholder="Breve epíteto (ej: El Guerrero de la Luz)" style="font-style: italic;" />
            </div>
        </div>
        
        <textarea name='html' placeholder="Escribe aquí su historia y trasfondo..." style="margin-top: 15px; height: 150px;" ></textarea> 		
		
		<div style="text-align:right; margin-top: 15px;">
            <input id='add_pj' type='submit' value='Crear Personaje ⚔️' class="button" style="padding: 12px 30px; font-weight: bold;" />
        </div>
	</form>
</div>

<?php 
    include 'modal_galeria.php'; 
?>



<div class="card">
	<h3 style='text-align:center;'>Personajes: </h3>
	
	<?php
		$pjs = enarol_pjs_get_pjs();
		echo "<table style='width:90%; margin:auto;'>
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Usuario</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>";
		foreach ($pjs as $p) {
			echo '<tr id="pj'.$p['id'].'">
				<td>'. $p['nombre'].'</td>
				<td>' . htmlspecialchars($p['desc']) . '</td>
				<td>' . htmlspecialchars($p['usuario']) . '</td>
				
				<td>
					<a style="text-decoration:none;" href="controller\eliminar_personaje.php?id='.$p['id'].'" onclick="return confirm(\'¿Eliminar '.$p['nombre'].'?\');">❌</a>
					<a style="text-decoration:none;" href="personaje_editar.php?id='.$p['id'].'">✍️</a>
				</td>
			</tr>
			
			';
		}
		echo "</tbody></table>";
	?>
</div>

<?php
}
include '../../../admin/footer.php';
?>