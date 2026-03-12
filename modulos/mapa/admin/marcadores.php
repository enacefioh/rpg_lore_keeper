 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../config.php';



include '../../../admin/header.php';
if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "<div class='card'> No tienes permiso para modificar el mapa! </div>";
}else{
	
include 'card_mapa.php';


?>
<div class="card">
	<h3 style='text-align:center;'>Añadir nuevo marcador al mapa:</h3>
	<form id='form_add_marcador' method='post' action='controller/add_marcador.php'>
		<input type="hidden" name="mapa_id" value="<?php echo $_GET['mapa_id'] ?? 1; ?>" />
		<input type='text' name='nombre' placeholder="Nombre del lugar" />
		<textarea name='desc' placeholder="Descripción del lugar" ></textarea> 		
		<input type='hidden' name='x' />
		<input type='hidden' name='y' />
		<input id='add_marcador' type='submit' value='Añadir' />
	</form>
	
	<script type='text/javascript'>
		
		document.getElementById('add_marcador').addEventListener('click', function(event){
			event.preventDefault();
			if(document.querySelector('input[name="nombre"]').value.length < 1){
				alert("Escribe un nombre");
				return;
			}
			if(typeof marcador === 'undefined' || marcador === null){
				alert("Posiciona el marcador en el mapa primero.");
				return;
			}
			var pos = marcador.getLatLng();
			document.querySelector('input[name="x"]').value = pos.lat;
			document.querySelector('input[name="y"]').value = pos.lng;
			document.getElementById('form_add_marcador').submit();
			
		});
		
		var marcador;
		var icono_rojo = L.icon({
			iconUrl: '<?php echo $URL; ?>modulos/mapa/res/marker_seleccionado.png',
			iconSize: [75, 75], // Tamaño en píxeles [ancho, alto]
			iconAnchor: [37, 75], // Punto del icono que se fija a la coordenada (la base)
			popupAnchor: [0, -75] // Donde aparecerá el bocadillo de texto
		});
		
		map.on('click', function(e){
			var lat = e.latlng.lat.toFixed(0); 
			var lng = e.latlng.lng.toFixed(0);
			let latn = +lat;
			let lngn = +lng;
			if(marcador != null){
				marcador.setLatLng([latn, lngn]);				
			}else{
							
				marcador = L.marker([lat,lng], {icon: icono_rojo}).addTo(map);
			}
			
		});
	</script>
</div>
<div class="card">
	<h3 style='text-align:center;'>Elementos añadidos: </h3>
	
	<?php
		$mapa_id = $_GET['mapa_id'] ?? 1;
		$marcadores = enarol_mapa_get_marcadores($mapa_id);
		echo "<table style='width:90%; margin:auto;'>
		<thead>
			<tr>
				<th>Ubicación</th>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>";
		foreach ($marcadores as $m) {
			echo '<tr id="marcador'.$m['id'].'">
				<td>[' . $m['x'].','. $m['y'].']</td>
				<td><b>' . htmlspecialchars($m['nombre'] ?? 'Sin nombre') . '</b></td>
				<td>' . htmlspecialchars($m['html'] ?? 'Sin descripción') . '</td>
				<td><a style="text-decoration:none;" href="controller/eliminar_marcador.php?id='.$m['id'].'&mapa_id='.$mapa_id.'" onclick="return confirm(\'¿Eliminar marcador de '.$m['nombre'].'?\');">❌</a></td>
			</tr>
			<script type="text/javascript">
				document.getElementById("marcador'.$m['id'].'").addEventListener("click", function(){
						map.setView(L.latLng('. $m['x'].','. $m['y'].'));
				});
			</script>
			
			';
		}
		echo "</tbody></table>"; 
	?>
</div>
<?php
}
include '../../../admin/footer.php';
?>