/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de colonias en el sistema.
 */

function guardarColonia(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	
	if(document.getElementById("Colonia").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("CP").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Ciudad").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Estado").value.toString() == "")
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
	
function habColonia(){
	/*
	 * Esta función habilita los controles del formulario de colonia.
	 */

	document.getElementById('Colonia').disabled = false;
	document.getElementById('CP').disabled = false;
	document.getElementById('Ciudad').disabled = false;
	document.getElementById('Estado').disabled = false;
	}

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "col_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    			document.getElementById('pgcolonia').value = document.getElementById('nomcolonia').value.toString();
    			document.getElementById('pgciudad').value = document.getElementById('ciucolonia').value.toString();
    			document.getElementById('pgcp').value = document.getElementById('cpcolonia').value.toString();
    			document.getElementById('pgestado').value = document.getElementById('estcolonia').value.toString();
    			cargar('./php/frontend/colonias/catColonias.php','?nomcolonia='+document.getElementById('nomcolonia').value.toString()+'&cpcolonia='+document.getElementById('cpcolonia').value.toString()+'&ciucolonia='+document.getElementById('ciucolonia').value.toString()+'&estcolonia='+document.getElementById('estcolonia').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "col_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/colonias/borrar.php','?id='+e.target.id.substring(11),'escritorio');
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
    	if(e.target.id.substring(0,7) == "col_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/colonias/opColonias.php','?id=-1&view=0','escritorio');
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
    	if(e.target.id.substring(0,14) == "col_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/colonias/opColonias.php','?id='+e.target.id.substring(15)+'&view=1','escritorio');
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
    	if(e.target.id.substring(0,8) == "col_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/colonias/opColonias.php','?id='+e.target.id.substring(9)+'&view=0','escritorio');
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
					if(e.target.id == "col_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/colonias/catColonias.php','?nomcolonia='+document.getElementById('pgcolonia').value.toString()+'&cpcolonia='+document.getElementById('pgcp').value.toString()+'&ciucolonia='+document.getElementById('pgciudad').value.toString()+'&estcolonia='+document.getElementById('pgestado').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "col_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/colonias/catColonias.php','?nomcolonia='+document.getElementById('pgcolonia').value.toString()+'&cpcolonia='+document.getElementById('pgcp').value.toString()+'&ciucolonia='+document.getElementById('pgciudad').value.toString()+'&estcolonia='+document.getElementById('pgestado').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});	