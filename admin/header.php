 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body { font-family: sans-serif; margin: 0; display: flex; flex-direction: column; height: 100vh; background: #f4f4f4; }
            header { background: #333; color: white; padding: 1rem; text-align: center; font-weight: bold; }
            
            .container { display: flex; flex: 1; flex-direction: row; overflow: hidden; }
            
            /* Menú Lateral */
            nav { background: #2c3e50; color: white; width: 250px; flex-shrink: 0; display: flex; flex-direction: column; }
            nav a { color: white; padding: 15px; text-decoration: none; border-bottom: 1px solid #34495e; transition: 0.3s; }
            nav a:hover { background: #34495e; }
            
            /* Zona Central */
            main { flex: 1; padding: 20px; overflow-y: auto; box-sizing: border-box; }
            .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; overflow:auto; }

            /* Formularios Responsivos */
            .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
            label { margin-bottom: 5px; font-weight: bold; color: #555; }
            input, select, textarea { 
                padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; width: 100%; box-sizing: border-box; 
            }
            .button { background: #27ae60; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration:none; }
            .button:hover { background: #219150; }

            /* Ajustes Móvil */
            @media (max-width: 768px) {
                .container { flex-direction: column; }
                nav { width: 100%; flex-direction: row; flex-wrap: wrap; justify-content: space-around; }
                nav a { flex: 1; text-align: center; padding: 10px; font-size: 14px; }
                main { padding: 10px; }
            }
			/* Contenedor para scroll horizontal en móviles */
			.table-container { 
				width: 100%; 
				overflow-x: auto; 
				margin-top: 20px; 
				background: white; 
				border-radius: 8px;
			}

			table { 
				width: 100%; 
				border-collapse: collapse; 
				min-width: 600px; /* Asegura que en móvil no se amontone el texto */
			}

			th, td { 
				text-align: left; 
				padding: 12px 15px; 
				border-bottom: 1px solid #eee; 
			}

			th { 
				background: #f8f9fa; 
				color: #333; 
				text-transform: uppercase; 
				font-size: 12px; 
				letter-spacing: 1px; 
			}

			tr:hover { background: #f9f9f9; }

			/* Estilos para badges de grupos */
			.badge {
				background: #e1f5fe;
				color: #0288d1;
				padding: 4px 8px;
				border-radius: 4px;
				font-size: 12px;
				font-weight: bold;
				display: inline-block;
				margin: 2px;
			}

			/* Botones de acción en tabla */
			.btn-edit { background: #3498db; color: white; padding: 5px 10px; font-size: 12px; }
			.btn-delete { background: #e74c3c; color: white; padding: 5px 10px; font-size: 12px; margin-left: 5px; }

			/* Ajuste móvil: si la pantalla es muy pequeña, forzamos el scroll */
			@media (max-width: 768px) {
				th, td { padding: 10px; font-size: 14px; }
			}
			
			nav h2 {
				padding-left: 5px;
				margin: 3px;
				border-bottom: 2px solid #8BABB7;
			}
        </style>
		
		<link rel="stylesheet" href="../css/leaflet.css" />
		<script src="../js/leaflet.js"></script>
    </head>
    <body>

    <header>Administrador de configuración</header>
    <div class='container'>
        <nav>
		
			<?php
				$mods = glob(__DIR__ .'/../modulos/*', GLOB_ONLYDIR);
				foreach ($mods as $dir) {
					require	__DIR__ .'/../modulos/'. basename($dir).'/admin/menu.php';
				}
			?>
		</nav>
		

	<main id='content-area'>
	<?php
			if(isset($_GET['err'])){
				echo "<div class='card' style='text-align:center; color:red; background-color:#ffcccc;'><b>".$_GET['err']."</b></div>";
			}
			if(isset($_GET['success'])){
				echo "<div class='card' style='text-align:center; color:green; background-color:#ccffcc;'><b>".$_GET['success']."</b></div>";
			}
		?>