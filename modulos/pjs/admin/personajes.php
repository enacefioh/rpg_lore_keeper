 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../config.php';



include '../../../admin/header.php';

if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "<div class='card'> No tienes permiso para modificar los personajes! </div>";
}else{

?>
<div class="card">
	<h3 style='text-align:center;'>Añadir nuevo pj:</h3>
	<form id='form_add_pj' method='post' action='controller/add_pj.php' enctype="multipart/form-data">
		<input type='text' name='nombre' placeholder="Nombre del personaje" />
		<select name='user'>
		<?php
			$users = get_all_users();
			foreach($users as $u){
				echo "<option value='".$u['id']."'>".$u['username']."</option>";
			}
		?>
		</select>
		<textarea name='desc' placeholder="Descripción corta del personaje" ></textarea>
		<textarea name='html' placeholder="Trasfondo del personaje" ></textarea> 		
		<input type='file' name='img' />
		<input id='add_pj' type='submit' value='Añadir' />
	</form>
	
	<script type='text/javascript'>
		
	</script>
</div>



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