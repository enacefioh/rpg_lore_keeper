
	function moverCarrusel(direccion) {
		const carrusel = document.getElementById('carrusel-pjs');
		const anchoPaso = carrusel.offsetWidth; // El ancho de un PJ completo
		
		carrusel.scrollBy({
			left: anchoPaso * direccion,
			behavior: 'smooth'
		});
	}
