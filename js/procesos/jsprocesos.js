/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de procesos en el sistema.
 */

function guardarProceso(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	
	if(document.getElementById("Proceso").value.toString() == "")
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
	
function habProceso()
	{
		/*
		 * Esta función habilita los controles del formulario de proceso.
		 */
		var checkboxes = $('.check');
		
		for (var x=0; x < checkboxes.length; x++)
			{
				checkboxes[x].disabled = false;
				}
		
		document.getElementById('Proceso').disabled = false;
		}

function vectorizacion()
{
	/*
	 * Esta función obtiene los id de los elementos seleccionados.
	 */
	var checkboxes = $('.check');
	var temp = '-1'; 
 
	for (var x=0; x < checkboxes.length; x++) 
		{
			if (checkboxes[x].checked) 
				{
					temp = temp + '%' + checkboxes[x].value.toString();
					}
			}
			
	return temp;
	}

function nonvectorizacion()
{
	/*
	 * Esta función obtiene los id de los elementos no seleccionados.
	 */
	var checkboxes = $('.check');
	var temp = '-1'; 
 
	for (var x=0; x < checkboxes.length; x++) 
		{
			if (!checkboxes[x].checked) 
				{
					temp = temp + '%' + checkboxes[x].value.toString();
					}
			}
			
	return temp;
	}

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "pro_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
				document.getElementById('pgproceso').value = document.getElementById('nomproceso').value.toString();
				document.getElementById('pgidentidad').value = document.getElementById('nomidentidad').value.toString();
    			cargar('./php/frontend/procesos/catProcesos.php','?nomproceso='+document.getElementById('nomproceso').value.toString()+'&nomidentidad='+document.getElementById('nomidentidad').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "pro_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/procesos/borrar.php','?id='+e.target.id.substring(11)+'&listado=1','escritorio');
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
    	if(e.target.id.substring(0,7) == "pro_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/procesos/opProcesos.php','?id=-1&view=0','escritorio');
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
    	if(e.target.id.substring(0,14) == "pro_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/procesos/opProcesos.php','?id='+e.target.id.substring(15)+'&view=1','escritorio');
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
    	if(e.target.id.substring(0,8) == "pro_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/procesos/opProcesos.php','?id='+e.target.id.substring(9)+'&view=0','escritorio');
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
					if(e.target.id == "pro_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
			    			cargar('./php/frontend/procesos/catProcesos.php','?nomproceso='+document.getElementById('pgproceso').value.toString()+'&nomidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "pro_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
			    			cargar('./php/frontend/procesos/catProcesos.php','?nomproceso='+document.getElementById('pgproceso').value.toString()+'&nomidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});