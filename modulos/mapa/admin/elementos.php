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
			<input type="hidden" name="mapa_id" value="<?php echo $_GET['mapa_id'] ?? 1; ?>" />
			
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
			
			document.getElementById('aumentar_tam').addEventListener('click', function(event){
				event.preventDefault();
				if(typeof elemento === 'undefined' || elemento === null) return;
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
			document.getElementById('reducir_tam').addEventListener('click', function(event){
				event.preventDefault();
				if(typeof elemento === 'undefined' || elemento === null) return;
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
			
			document.getElementById('add_elemento').addEventListener('click', function(event){
				event.preventDefault();
				if(document.querySelector('input[name="nombre"]').value.length < 1){
					alert("Escribe un nombre");
					return;
				}
				if(typeof elemento === 'undefined' || elemento === null){
					alert("Posiciona el elemento en el mapa primero.");
					return;
				}
				if(icono_seleccionado == 'transparent.png'){
					alert("Selecciona la imagen");
					return;
				}
				var bounds = elemento.getBounds();
				document.querySelector('input[name="y0"]').value = bounds.getWest();
				document.querySelector('input[name="x0"]').value = bounds.getNorth();
				document.querySelector('input[name="y1"]').value = bounds.getEast();
				document.querySelector('input[name="x1"]').value = bounds.getSouth();
				document.querySelector('input[name="icono"]').value = icono_seleccionado.replace('.png', '');
				document.getElementById('form_add_elemento').submit();
				
			});
			document.querySelectorAll('.imagen_elemento').forEach(function(img) {
				img.addEventListener('click', function() {
					document.querySelectorAll('.imagen_seleccionada').forEach(function(el) {
						el.classList.remove('imagen_seleccionada');
					});
					this.classList.add('imagen_seleccionada');
					icono_seleccionado = this.getAttribute('data-src');
					if(typeof elemento !== 'undefined' && elemento !== null){
						var bounds_elem = elemento.getBounds();
						elemento.remove();
						elemento = L.imageOverlay('../res/items_mapa/'+icono_seleccionado, bounds_elem);
						elemento.addTo(map);				
					}
				});
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
			$mapa_id = $_GET['mapa_id'] ?? 1;
			$elementos = enarol_mapa_get_elementos($mapa_id);
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
					<td><a style="text-decoration:none;" href="controller/eliminar_elemento.php?id='.$e['id'].'&mapa_id='.$mapa_id.'" onclick="return confirm(\'¿Eliminar '.$e['nombre'].'?\');">❌</a></td>
				</tr>
				<script type="text/javascript">
					document.getElementById("elemento'.$e['id'].'").addEventListener("click", function(){
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