/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de cedulas en el sistema.
 */

function guardarCedula(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	
	if(document.getElementById("Folio").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Fecha").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("idEntfoda").value.toString() == "-1")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Horizonte").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(error > 0)
		{
			/*
			 * En caso de ocurrir un error de validación, se notifica al usuario.
			 */
			alert("Existen campos pendientes por completar");
			}
	else
		{
			/*
			 * En caso que la validación de campos sea satisfactoria.
			 */
			cargar(url,parametro,'escritorio');
			}
	}
	
function habCedula()
	{
		/*
		 * Esta función habilita los controles del formulario de colonia.
		 */

		document.getElementById('idEntfoda').disabled = false;
		document.getElementById('Horizonte').disabled = false;
		}

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "fce_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
				document.getElementById('pgfolio').value = document.getElementById('fcefolio').value.toString();
				document.getElementById('pgfecha').value = document.getElementById('fcefecha').value.toString();
				document.getElementById('pgidentidad').value = document.getElementById('fceidentidad').value.toString();
    			cargar('./php/frontend/foda/cedulas/catCedulas.php','?fcefolio='+document.getElementById('fcefolio').value.toString()+'&fcefecha='+document.getElementById('fcefecha').value.toString()+'&fceidentidad='+document.getElementById('fceidentidad').value.toString(),'busRes');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id delete_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "fce_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/foda/cedulas/borrar.php','?id='+e.target.id.substring(11),'escritorio');
    					} 		
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id add
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,7) == "fce_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/foda/cedulas/opCedulas.php','?id=-1&view=0','escritorio');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id visualizar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,14) == "fce_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/foda/cedulas/opCedulas.php','?id='+e.target.id.substring(15)+'&view=1','escritorio');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id edit_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,8) == "fce_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/foda/cedulas/opCedulas.php','?id='+e.target.id.substring(9)+'&view=0','escritorio');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de retroceso de pagina
 * sobre el grid de datos.
 */
	$(document).ready(function()
		{
			$("div").click(function(e)
				{
					e.stopPropagation();
					if(e.target.id == "fce_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/foda/cedulas/catCedulas.php','?fcefolio='+document.getElementById('pgfolio').value.toString()+'&fcefecha='+document.getElementById('pgfecha').value.toString()+'&fceidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de avance de pagina
 * sobre el grid de datos.
 */
	$(document).ready(function()
		{
			$("div").click(function(e)
				{
					e.stopPropagation();
					if(e.target.id == "fce_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/foda/cedulas/catCedulas.php','?fcefolio='+document.getElementById('pgfolio').value.toString()+'&fcefecha='+document.getElementById('pgfecha').value.toString()+'&fceidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});