<?php 
	/*
		Módulo Mapa Enarol
		V 26.2.231
	*/
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	require_once '../../config.php';
	$HEADERS_ADICIONALES = "<script src='js/pjs.js'></script>";
	$HEADERS_ADICIONALES .= "<link rel='stylesheet' href='css/style.css' />";
	require '../../header.php';
	$personajes = enarol_pjs_get_pjs();
?>		
  <div id="wrapper-pjs">
    <button class="nav-btn prev" onclick="moverCarrusel(-1)">&#10094;</button>
    <button class="nav-btn next" onclick="moverCarrusel(1)">&#10095;</button>

    <div id="carrusel-pjs">
        <?php foreach($personajes as $pj): ?>
        <div class="pj-card">
            <div class="pj-foto-wrapper">
                <img src="uploads/<?php echo $pj['img']; ?>" alt="<?php echo $pj['nombre']; ?>" class="pj-img">
                <div class="pj-header">
                    <h2><?php echo $pj['nombre']; ?></h2>
                </div>
            </div>
            
            <div class="pj-info">
                <p class="pj-desc"><b><?php echo $pj['desc']; ?></b></p>
                <hr>
                <div class="pj-trasfondo">
                    <?php echo $pj['texto']; ?>
					<?php
						if(isset($pj['usuario']) && isset($_SESSION['user_id']) && $pj['usuario'] == $_SESSION['user_id']){
							echo "<div style='text-align:right; padding: 25px;'><i><a href='admin/personaje_editar.php?id=".$pj['id']."' style='text-decoration:none;'>✍️ Editar</a></i></div>";
						}
					?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
		
<?php

	require '../../footer.php';
?>	