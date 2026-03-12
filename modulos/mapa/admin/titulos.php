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
	<h3 style='text-align:center;'>Añadir nuevo título al mapa:</h3>
	<form id='form_add_titulo' method='post' action='controller/add_titulo.php'>
		<input id='input_nombre' type='text' name='nombre' placeholder="Nombre del lugar" />
		<table><tr>
				
			<td><select id='selector_tam' name='tam'><option value=1>Tamaño 1</option><option value=2>Tamaño 2</option><option value=3>Tamaño 3</option><option value=4>Tamaño 4</option><option value=5>Tamaño 5</option></select></td>	
			<td style='text-align:right;'>Zoom Min y Max:</td>	
			<td><input type='number' name='zmin' placeholder="0" /></td>	
			<td><input type='number' name='zmax' placeholder="99" /></td>	
		</tr></table>
		<input type='hidden' name='x' />
		<input type='hidden' name='y' />
		<input id='add_marcador' type='submit' value='Añadir' />
	</form>
	
	<script type='text/javascript'>
		
		$('#add_marcador').click(function(event){
			event.preventDefault();
			if($('input[name="nombre"]').val().length<1){
				alert("Escribe un nombre");
				return;
			}
			if(marcador === undefined){
				alert("Posiciona el marcador en el mapa primero.");
				return;
			}
			var pos = marcador.getLatLng();
			$('input[name="x"]').val(pos.lat);
			$('input[name="y"]').val(pos.lng);
			$('#form_add_titulo').submit();
			
		});
		
		var marcador;
		var icono_texto;
		
		map.on('click', function(e){
			var lat = e.latlng.lat.toFixed(0); 
			var lng = e.latlng.lng.toFixed(0);
			var tam = $('#selector_tam').val();
			var nombre = $('#input_nombre').val();
			let latn = +lat;
			let lngn = +lng;
			icono_texto = L.divIcon({
				html: "<div class='titulo"+tam+"' style='text-align:center;'>"+nombre+"</div>",
				iconSize: [600, 0],
				iconAnchor: [300, 0]
			});
			if(marcador != null){
				marcador.setIcon(icono_texto);
				marcador.setLatLng([latn, lngn]);				
			}else{
							
				marcador = L.marker([lat,lng], {icon: icono_texto}).addTo(map);
			}
			
		});
	</script>
</div>
<div class="card">
	<h3 style='text-align:center;'>Títulos añadidos: </h3>
	
	<?php
	 	$titulos = enarol_mapa_get_titulos();
		echo "<table style='width:90%; margin:auto;'>
		<thead>
			<tr>
				<th>Ubicación</th>
				<th>Titulo</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>";
		foreach ($titulos as $m) {
			echo '<tr id="titulo'.$m['id'].'">
				<td>[' . $m['x'].','. $m['y'].']</td>
				<td><b>' . htmlspecialchars($m['nombre'] ?? 'Sin nombre') . '</b></td>
				<td><a style="text-decoration:none;" href="controller\eliminar_titulo.php?id='.$m['id'].'" onclick="return confirm(\'¿Eliminar titulo '.$m['nombre'].'?\');">❌</a></td>
			</tr>
			<script type="text/javascript">
				$("#titulo'.$m['id'].'").click(function(){
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