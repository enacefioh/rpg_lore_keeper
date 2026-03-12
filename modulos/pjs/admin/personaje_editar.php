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
	<h3 style='text-align:center;'>Modificar Pj:</h3>
	<form id='form_add_pj' method='post' action='controller/modificar_pj.php'>
		<input type='hidden' name='id' value='<?php echo $pj['id']; ?>' />
		<input type='text' name='nombre' value='<?php echo $pj['nombre']; ?>' />
		<?php   if(check_permision($SLUG_UNICO_PARTIDA."_admin")){?>
		<select name='user'>
			<option value='<?php echo $pj['usuario'];?>'> <?php $u = get_user_by_id($pj['usuario']); echo $u['username']; ?></option>
		<?php
			$users = get_all_users();
			foreach($users as $u){
				echo "<option value='".$u['id']."'>".$u['username']."</option>";
			}
		?>
		</select>
		<?php }else{
			echo "<input type='hidden' name='user' value='".$pj['usuario']."' />";
		}			?>
		<textarea name='desc'><?php echo $pj['desc']; ?></textarea>
		<textarea name='html' style='height: 30vh;'><?php echo $pj['texto']; ?></textarea> 
		<input type='submit' value='Modificar' />
	</form>
	
	<script type='text/javascript'>
		
	</script>
</div>


<?php
}
include '../../../admin/footer.php';

?>