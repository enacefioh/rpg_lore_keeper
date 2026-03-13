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
            :root {
                --sidebar-width: 260px;
                --primary-color: #2c3e50;
                --sidebar-bg: #1a252f;
                --accent-gold: #ffc107;
                --header-bg: #333;
            }

            body { font-family: 'Segoe UI', Roboto, sans-serif; margin: 0; display: flex; flex-direction: column; min-height: 100vh; background: #f4f7f6; color: #333; }
            
            /* Header */
            header { 
                background: var(--header-bg); color: white; padding: 0.8rem 1rem; 
                display: flex; align-items: center; justify-content: space-between;
                position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
            .header-title { font-weight: bold; font-size: 1.1rem; }
            
            /* Menú Hamburguesa */
            .menu-toggle {
                display: none; background: none; border: none; color: white; 
                font-size: 1.5rem; cursor: pointer; padding: 5px;
            }

            .container { display: flex; flex: 1; min-height: 0; }
            
            /* Sidebar (Menú Lateral) */
            nav { 
                background: var(--sidebar-bg); color: white; width: var(--sidebar-width); 
                flex-shrink: 0; display: flex; flex-direction: column; 
                transition: transform 0.3s ease;
                border-right: 1px solid rgba(255,255,255,0.05);
            }
            nav a { 
                color: rgba(255,255,255,0.8); padding: 12px 20px; text-decoration: none; 
                border-left: 4px solid transparent; transition: 0.2s;
                font-size: 0.95rem; display: flex; align-items: center; gap: 10px;
            }
            nav a:hover, nav a.active { 
                background: rgba(255,255,255,0.05); color: white; border-left-color: var(--accent-gold);
            }
            nav h2 {
                font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px;
                color: rgba(255,255,255,0.4); padding: 25px 20px 10px 20px; margin: 0;
            }
            
            /* Zona Central */
            main { flex: 1; padding: 25px; overflow-y: auto; box-sizing: border-box; }
            .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #eef2f1; }

            /* Overlay para móviles */
            #sidebarOverlay {
                display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.5); z-index: 998; backdrop-filter: blur(2px);
            }

            /* Responsive */
            @media (max-width: 900px) {
                .menu-toggle { display: block; }
                nav { 
                    position: fixed; left: 0; top: 0; height: 100%; 
                    z-index: 999; transform: translateX(-100%);
                    box-shadow: 5px 0 15px rgba(0,0,0,0.3);
                }
                nav.open { transform: translateX(0); }
                main { padding: 15px; }
                #sidebarOverlay.show { display: block; }
            }

            /* Estilos generales reutilizados */
            .button { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 15px; text-decoration:none; display: inline-block; transition: 0.2s; }
            .button:hover { background: #219150; transform: translateY(-1px); }
            input, select, textarea { padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; width: 100%; box-sizing: border-box; background: #fff; }
            label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 0.9rem; }
            .form-group { margin-bottom: 20px; }
        </style>
    </head>
    <body>

    <header>
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="header-title">📜 Terragon Admin</div>
        <div style="width: 32px;" class="menu-toggle"></div> <!-- Spacer visual -->
    </header>

    <div id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class='container'>
        <nav id="sidebar">
            <h2 style="border:none; padding-bottom:5px;">Configuración</h2>
            <a href='<?php echo $URL;?>admin/settings.php' style="background: rgba(255,255,255,0.03);">⚙️ Ajustes Generales</a>
		
            <h2 style="border:none; padding-bottom:5px; margin-top:10px;">Módulos</h2>
			<?php
				$mods = glob(__DIR__ .'/../modulos/*', GLOB_ONLYDIR);
				foreach ($mods as $dir) {
					require	__DIR__ .'/../modulos/'. basename($dir).'/admin/menu.php';
				}
			?>
		</nav>

	<main id='content-area'>
        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            }
        </script>
	<?php
			if(isset($_GET['err'])){
				echo "<div class='card' style='text-align:center; color:#721c24; background-color:#f8d7da; border: 1px solid #f5c6cb;'><b>".$_GET['err']."</b></div>";
			}
			if(isset($_GET['success'])){
				echo "<div class='card' style='text-align:center; color:#155724; background-color:#d4edda; border: 1px solid #c3e6cb;'><b>".$_GET['success']."</b></div>";
			}
		?>