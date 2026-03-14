 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../../config.php';
?>
        var map = L.map('map', {
            crs: L.CRS.Simple, // Crucial: le dice a Leaflet que el mundo es "plano"
			
            minZoom: -2
        });

        // 3. Define el tamaño de tu imagen y la ruta
        <?php 
            $mapa_id = $_GET['mapa_id'] ?? 1;
            $info_mapa = enarol_mapa_get_mapa($mapa_id);
            $ancho = $info_mapa['ancho'] ?? 3000;
            $alto = $info_mapa['alto'] ?? 3000;
            $img_file = $info_mapa['img'] ?? 'mapa.jpg';
            $zoom = $info_mapa['zoom_inicial'] ?? 0;
            $lat = $info_mapa['lat_inicial'] ?? 1500;
            $lng = $info_mapa['lng_inicial'] ?? 1500;
        ?>
        var bounds = [[0, 0], [<?php echo $alto; ?>, <?php echo $ancho; ?>]]; // [Alto, Ancho] en píxeles
        var image = L.imageOverlay('<?php echo $URL;?>modulos/mapa/res/<?php echo $img_file; ?>', bounds).addTo(map); 

        // Centrar el mapa en la imagen
        map.fitBounds(bounds);

        // 4. Ejemplo de marcador (Aquí es donde luego meterás el PHP de SQLite)
        // Imagina que Ciudad Masacre está en estas coordenadas
       /*  */
	   
	   var marker = L.icon({
			iconUrl: '<?php echo $URL; ?>modulos/mapa/res/marker.png',
			iconSize: [75, 75], // Tamaño en píxeles [ancho, alto]
			iconAnchor: [37, 75], // Punto del icono que se fija a la coordenada (la base)
			popupAnchor: [0, -75] // Donde aparecerá el bocadillo de texto
		});
	  
		<?php
			$elementos = enarol_mapa_get_elementos($mapa_id);
			foreach ($elementos as $e) {
				$x0 = $e['x0'] ?: 0;
				$y0 = $e['y0'] ?: 0;
				$x1 = $e['x1'] ?: 0;
				$y1 = $e['y1'] ?: 0;
				$id = $e['id'] ?: 0;
				$icono = $e['icono'] ?: "transparent.png";
				echo "var bounds_elem$id = [[$x0, $y0], [$x1, $y1]];";
				echo "L.imageOverlay('".$URL."modulos/mapa/res/items_mapa/$icono.png', bounds_elem$id).addTo(map);";
				
			}
			
			
			$marcadores = enarol_mapa_get_marcadores($mapa_id);
			foreach ($marcadores as $e) {
				$id = $e['id'] ?: 0;
				$x = $e['x'] ?: 0;
				$y = $e['y'] ?: 0;
				$nombre = $e['nombre'] ?: "???";
				$desc = $e['html'] ?: " ";
				
				echo "var marker$id = L.marker([$x,$y], {icon: marker}).addTo(map);";
				echo "marker$id.bindPopup('<b>$nombre</b><br>$desc');";
				
			}
			
			$titulos = enarol_mapa_get_titulos($mapa_id);
			foreach ($titulos as $t) {
				$id = $t['id'] ?: 0;
				$x = $t['x'] ?: 0;
				$y = $t['y'] ?: 0;
				$tam = $t['tam'] ?: 5;
				$nombre = $t['nombre'] ?: "???";
				
				echo 'var icono_texto'.$id.' = L.divIcon({
					html: "<div class=\'titulo'.$tam.'\' style=\'text-align:center;\'>'.$nombre.'</div>",
					iconSize: [600, 0],
					iconAnchor: [300, 0]
				});';
				
				echo "var marker$id = L.marker([$x,$y], {icon: icono_texto$id}).addTo(map);";
				
			}
			
			
			
		?>
		
		

		// Añadirlo al mapa
	
		map.setView([<?php echo $lat; ?>, <?php echo $lng; ?>], <?php echo $zoom; ?>);
        // Tip: Haz clic en el mapa para ver las coordenadas en la consola y saber dónde poner los puntos
        map.on('click', function(e) {
            console.log("Coordenadas: " + e.latlng.toString());
        });
		
		
		
	
