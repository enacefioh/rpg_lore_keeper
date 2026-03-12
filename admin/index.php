 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config.php';

if(!check_permision("diablo_oscuro_admin")){
	echo "No tienes permiso para estar aquí!";
	exit();
}

include 'header.php';


?>
<div class="card">
	<h1>Panel de Administración</h1>
</div>
<?php
include 'footer.php';
?>