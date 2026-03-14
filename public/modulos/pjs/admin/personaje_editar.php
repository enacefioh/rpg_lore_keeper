 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../config.php';

if(!isset($_GET['id'])){
	header('Location: ..');
	exit();
}
$pj_id = $_GET['id'];
$pj = enarol_get_pj_by_id($pj_id);






include '../../../admin/header.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin") && $pj['usuario'] != $_SESSION['user_id']){
	echo "<div class='card'> No tienes permiso para editar este personaje! </div>";
}else{

?>
<div class="card">
	<form id='form_add_pj' method='post' action='controller/modificar_pj.php'>
		<input type='hidden' name='id' value='<?php echo $pj['id']; ?>' />
		
        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 20px; margin-bottom: 20px;">
            <!-- Retrato -->
            <div style="text-align:center;">
                <img id="pj_preview" src="<?php echo enarol_pjs_get_img_url($pj['img']); ?>" style="width: 140px; height: 140px; object-fit: cover; border-radius: 8px; border: 2px solid var(--accent-gold); background: white; cursor:pointer;" onclick="openGallery()">
                <button type="button" class="button" style="margin-top:10px; font-size: 0.8rem; padding: 5px 10px;" onclick="openGallery()">Cambiar 🎨</button>
                <input type="hidden" name="img" id="pj_img_input" value="<?php echo $pj['img']; ?>">
                <input type="hidden" name="img_type" id="pj_img_type" value="library">
            </div>

            <!-- Datos -->
            <div style="display:flex; flex-direction:column; gap: 10px;">
                <input type='text' name='nombre' value='<?php echo $pj['nombre']; ?>' style="font-size: 1.2rem; font-weight: bold;" />
                <?php if(check_permision($SLUG_UNICO_PARTIDA."_admin")){?>
                <select name='user'>
                    <option value='<?php echo $pj['usuario'];?>'>Propietario: <?php $u = get_user_by_id($pj['usuario']); echo $u['username']; ?></option>
                    <?php
                        $users = get_all_users();
                        foreach($users as $u){
                            echo "<option value='".$u['id']."'>".$u['username']."</option>";
                        }
                    ?>
                </select>
                <?php } else {
                    echo "<input type='hidden' name='user' value='".$pj['usuario']."' />";
                } ?>
                <input type="text" name='desc' value='<?php echo $pj['desc']; ?>' style="font-style: italic;" />
            </div>
        </div>

		<textarea name='html' style='height: 30vh;'><?php echo $pj['texto']; ?></textarea> 
		<div style="text-align:right; margin-top: 15px;">
            <input type='submit' value='Guardar Cambios 💾' class="button" style="padding: 12px 30px; font-weight: bold;" />
        </div>
	</form>
    
    <?php 
        $gallery = enarol_pjs_get_gallery_images();
        include 'modal_galeria.php'; 
    ?>
</div>


<?php
}
include '../../../admin/footer.php';

?>
