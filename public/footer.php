</main>
	<div id="menu-icon" onclick="toggleMenu()">
		<span></span>
		<span></span>
		<span></span>
	</div>

	<aside id="side-menu">
		<div class="menu-header">
			<h2>Menú</h2>
		</div>
		<nav>
		
			<?php if (isset($MODULOS_INSTALADOS) && is_array($MODULOS_INSTALADOS)): ?>
				<?php foreach($MODULOS_INSTALADOS as $mod): ?>
					<a class='item_menu' id='menu_<?php echo htmlspecialchars($mod['folder']); ?>' href="<?php echo $URL; ?>modulos/<?php echo htmlspecialchars($mod['folder']); ?>/">
						<?php echo $mod['icono'] . ' ' . htmlspecialchars($mod['nombre']); ?>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
			
			<?php
				if(check_permision($SLUG_UNICO_PARTIDA."_admin")){
					echo "<a  class='item_menu' href='".$URL."admin/'>⚙️Panel administrador</a>";
				}
			
			?>
		</nav>
	</aside>

	<div id="overlay" onclick="toggleMenu()"></div>
	
	<dialog id="custom-popup" class="modal">
		<div class="modal-content">
			<span class="close-button" onclick="closePopup()">&times;</span>
			<div id="popup-body">
				</div>
		</div>
	</dialog>

    
</body>
</html>
