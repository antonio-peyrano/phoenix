function abrirVentana()
	{
		document.getElementById("capaFondo").style.visibility="visible";
		document.getElementById("capaVentana").style.visibility="visible";
		document.formulario.bAceptar.focus();
		}
 
function cerrarVentana()
	{
		alert("1");
		document.getElementById("capaFondo").style.visibility="hidden";
		document.getElementById("capaVentana").style.visibility="hidden";
		document.formulario.bAceptar.blur();		
		}