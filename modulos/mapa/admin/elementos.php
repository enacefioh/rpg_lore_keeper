 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../config.php';

function listarImagenesPng($directorio) {
    // Aseguramos que el directorio termine en /
    $directorio = rtrim($directorio, '/') . '/';
    $lista = [];

    // Buscamos todos los archivos .png (insensible a mayúsculas si el SO lo permite)
    $archivos = glob($directorio . "*.{png,PNG}", GLOB_BRACE);

    if ($archivos) {
        foreach ($archivos as $archivo) {
            // Obtener dimensiones: [0] es width, [1] es height
            $info = getimagesize($archivo);
            
            if ($info) {
                $lista[] = [
                    'nombre' => basename($archivo),
                    'width'  => $info[0],
                    'height' => $info[1]
                ];
            }
        }
    }

    return $lista;
}

include '../../../admin/header.php';
if(!check_permision($SLUG_UNICO_PARTIDA."_admin")){
	echo "<div class='card'> No tienes permiso para modificar el mapa! </div>";
}else{
	
	include 'card_mapa.php';

	?>
	<div class="card">
		<h3 style='text-align:center;'>Añadir nuevo elemento al mapa:</h3>
		<form id='form_add_elemento' method='post' action='controller/add_elemento.php'>
			
			<div style=" display: flex;  flex-wrap: nowrap;  overflow-x: auto;  overflow-y: hidden;  height: 10vh;  min-height: 60px; width: 100%;  gap: 10px; ">
				<style>
					.imagen_seleccionada{
						background-color: #ffaaaa;
					}
				</style>
			<?php
				$imgs = listarImagenesPng("../res/items_mapa");
				foreach ($imgs as $img){
					if($img['nombre'] != 'transparent.png'){
						echo "<img class='imagen_elemento' data-src='" . $img['nombre'] . "' style='height:100%;' src='../res/items_mapa/" . $img['nombre'] . "'>";
					}
					
				}
				//print_r($imgs);
			?>
			</div>
			<input type='text' name='nombre' placeholder="Nombre del lugar" /> 
			<button id='aumentar_tam' style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; width: 50%; box-sizing: border-box; float:left;">+</button>
			<button id='reducir_tam' style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; width: 50%; box-sizing: border-box;float:left;">-</button>
			<input type='hidden' name='x0' />
			<input type='hidden' name='y0' />
			<input type='hidden' name='x1' />
			<input type='hidden' name='y1' />
			<input type='hidden' name='icono' />
			<input id='add_elemento' type='submit' value='Añadir' />
		</form>
		
		<script type='text/javascript'>
			
			$('#aumentar_tam').click(function(event){
				event.preventDefault();
				if(elemento === null) return;
				bounds_elem = elemento.getBounds();
				let ancho = bounds_elem.getEast() - bounds_elem.getWest();
				let alto = bounds_elem.getNorth() - bounds_elem.getSouth();

				// 3. Calcular el 10% de margen
				let margenX = ancho * 0.10;
				let margenY = alto * 0.10;

				// 4. Crear los nuevos límites (restando a la base y sumando al tope)
				let nuevosBounds = [
					[bounds_elem.getSouth() - margenY, bounds_elem.getWest() - margenX], // Suroeste
					[bounds_elem.getNorth() + margenY, bounds_elem.getEast() + margenX]  // Noreste
				];
				elemento.setBounds(nuevosBounds);
				
			});
			$('#reducir_tam').click(function(event){
				event.preventDefault();
				if(elemento === null) return;
				bounds_elem = elemento.getBounds();
				let ancho = bounds_elem.getEast() - bounds_elem.getWest();
				let alto = bounds_elem.getNorth() - bounds_elem.getSouth();

				// 3. Calcular el 10% de margen
				let margenX = ancho * 0.10;
				let margenY = alto * 0.10;

				// 4. Crear los nuevos límites (restando a la base y sumando al tope)
				let nuevosBounds = [
					[Math.max(bounds_elem.getSouth() + margenY, 1), Math.max(bounds_elem.getWest() + margenX, 1)], // Suroeste
					[Math.max(bounds_elem.getNorth() - margenY, 1), Math.max(bounds_elem.getEast() - margenX, 1)]  // Noreste
				];
				elemento.setBounds(nuevosBounds);
				
			});
			
			$('#add_elemento').click(function(event){
				event.preventDefault();
				if($('input[name="nombre"]').val().length<1){
					alert("Escribe un nombre");
					return;
				}
				if(elemento === null || elemento === undefined){
					alert("Posiciona el elemento en el mapa primero.");
					return;
				}
				if(icono_seleccionado == 'transparent.png'){
					alert("Selecciona la imagen");
					return;
				}
				var bounds = elemento.getBounds();
				$('input[name="y0"]').val(bounds.getWest());
				$('input[name="x0"]').val(bounds.getNorth());
				$('input[name="y1"]').val(bounds.getEast());
				$('input[name="x1"]').val(bounds.getSouth());
				$('input[name="icono"]').val(icono_seleccionado.replace('.png', ''));
				$('#form_add_elemento').submit();
				
			});
			$('.imagen_elemento').click(function(){
				$('.imagen_seleccionada').removeClass('imagen_seleccionada');
				$(this).addClass('imagen_seleccionada');
				icono_seleccionado = $(this).attr('data-src');
				if(elemento != null){
					var bounds_elem = elemento.getBounds();
					elemento.remove();
					elemento = L.imageOverlay('../res/items_mapa/'+icono_seleccionado, bounds_elem);
					elemento.addTo(map);				
				}
			});
			var elemento;
			var icono_seleccionado = 'transparent.png';
			
			map.on('click', function(e){
				var lat = e.latlng.lat.toFixed(0); 
				var lng = e.latlng.lng.toFixed(0);
				let latn = +lat;
				let lngn = +lng;
				if(elemento != null){
					var bounds_elem = elemento.getBounds();
					let ancho = bounds_elem.getEast() - bounds_elem.getWest();
					let alto = bounds_elem.getNorth() - bounds_elem.getSouth();
					var nuevos_bounds = [[latn-ancho/2, lngn-ancho/2], [latn+ancho/2, lngn+ancho/2]];
					elemento.setBounds(nuevos_bounds);				
				}else{
					var bounds_elem = [[latn-25, lngn-25], [latn+25, lngn+25]];
					elemento = L.imageOverlay('../res/items_mapa/'+icono_seleccionado, bounds_elem);
					elemento.addTo(map);
				}
				
			});
		</script>
	</div>
	<div class="card">
		<h3 style='text-align:center;'>Elementos añadidos: </h3>
		
		<?php
			$elementos = enarol_mapa_get_elementos();
			echo "<table style='width:90%; margin:auto;'>
			<thead>
				<tr>
					<th>Ubicación</th>
					<th>Icono</th>
					<th>Nombre</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>";
			foreach ($elementos as $e) {
				echo '<tr id="elemento'.$e['id'].'">
					<td>[' . $e['x0'].'-'. $e['x1'].','. $e['y0'].'-'. $e['y1']. ']</td>
					<td><img style="height:50px;" src="'.$URL.'modulos/mapa/res/items_mapa/' . htmlspecialchars($e['icono']) . '.png"></td>
					<td>' . htmlspecialchars($e['nombre'] ?? 'Sin nombre') . '</td>
					<td><a style="text-decoration:none;" href="controller\eliminar_elemento.php?id='.$e['id'].'" onclick="return confirm(\'¿Eliminar '.$e['nombre'].'?\');">❌</a></td>
				</tr>
				<script type="text/javascript">
					$("#elemento'.$e['id'].'").click(function(){
							map.setView(L.latLng('. ($e['x0']/2+$e['x1']/2).','. ($e['y0']/2+$e['y1']/2).'));
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