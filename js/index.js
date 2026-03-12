window.$ = window.jQuery = jQuery;

function toggleMenu() {
    document.getElementById("side-menu").classList.toggle("active");
    document.getElementById("overlay").classList.toggle("active");
    
    // Opcional: animar las rayitas para que formen una X
    const spans = document.querySelectorAll("#menu-icon span");
    spans.forEach(span => span.classList.toggle("open"));
}


function openPopup(htmlContent) {
    document.getElementById("popup-body").innerHTML = htmlContent;
    document.getElementById("custom-popup").style.display = "block";
}

async function openPopupFile(fileName) {
    const popupBody = document.getElementById("popup-body");
    popupBody.innerHTML = "<p>Cargando pergamino...</p>"; // Feedback visual
    document.getElementById("custom-popup").style.display = "block";

    try {
        const response = await fetch(`content/${fileName}.html`); // Carpeta donde guardes tus html
        if (!response.ok) throw new Error('No se pudo cargar el archivo');
        
        const html = await response.text();
        popupBody.innerHTML = html;

        // Si el HTML cargado contiene scripts, hay que ejecutarlos manualmente
        const scripts = popupBody.querySelectorAll("script");
        scripts.forEach(oldScript => {
            const newScript = document.createElement("script");
            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });

    } catch (error) {
        popupBody.innerHTML = `<p style="color:red">Error: ${error.message}</p>`;
    }
}

function closePopup() {
    document.getElementById("custom-popup").style.display = "none";
}

// Cerrar si el usuario hace clic fuera de la caja negra
window.onclick = function(event) {
    let modal = document.getElementById("custom-popup");
    if (event.target == modal) {
        closePopup();
    }
}

$(document).ready(function(){
	$('#personajes').click( 
	function(){ 
		toggleMenu()
		openPopupFile('personajes');
	});
	
});
