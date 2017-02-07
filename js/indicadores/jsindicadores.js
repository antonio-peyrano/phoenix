/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de indicadores en el sistema.
 */

function guardarIndicador(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	
	if(document.getElementById("Indicador").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Nomenclatura").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Percentil").value.toString() == "")
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
	
function habIndicador()
	{
		/*
		 * Esta función habilita los controles del formulario de indicadores.
		 */
		var checkboxes = $('.check');
	
		for (var x=0; x < checkboxes.length; x++)
			{
				checkboxes[x].disabled = false;
				}
	
		document.getElementById('Indicador').disabled = false;
		document.getElementById('Nomenclatura').disabled = false;
		document.getElementById('Percentil').disabled = false;
		}

function getidsprocesos()
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

function getnonidprocesos()
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
    	if(e.target.id.substring(0,10) == "ind_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
				document.getElementById('pgindicador').value = document.getElementById('nomindicador').value.toString();
				document.getElementById('pgnomenclatura').value = document.getElementById('indnomenclatura').value.toString();
				document.getElementById('pgpercentil').value = document.getElementById('indpercentil').value.toString();
				document.getElementById('pgidproceso').value = document.getElementById('nomidproceso').value.toString();
    			cargar('./php/frontend/indicadores/catIndicadores.php','?nomindicador='+document.getElementById('nomindicador').value.toString()+'&indnomenclatura='+document.getElementById('indnomenclatura').value.toString()+'&indpercentil='+document.getElementById('indpercentil').value.toString()+'&nomidproceso='+document.getElementById('nomidproceso').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "ind_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/indicadores/borrar.php','?id='+e.target.id.substring(11)+'&listado=1','escritorio');
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
    	if(e.target.id.substring(0,7) == "ind_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/indicadores/opIndicadores.php','?id=-1&view=0','escritorio');
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
    	if(e.target.id.substring(0,14) == "ind_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/indicadores/opIndicadores.php','?id='+e.target.id.substring(15)+'&view=1','escritorio');
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
    	if(e.target.id.substring(0,8) == "ind_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/indicadores/opIndicadores.php','?id='+e.target.id.substring(9)+'&view=0','escritorio');
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
					if(e.target.id == "ind_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/indicadores/catIndicadores.php','?nomindicador='+document.getElementById('pgindicador').value.toString()+'&indnomenclatura='+document.getElementById('pgnomenclatura').value.toString()+'&indpercentil='+document.getElementById('pgpercentil').value.toString()+'&nomidproceso='+document.getElementById('pgidproceso').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "ind_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/indicadores/catIndicadores.php','?nomindicador='+document.getElementById('pgindicador').value.toString()+'&indnomenclatura='+document.getElementById('pgnomenclatura').value.toString()+'&indpercentil='+document.getElementById('pgpercentil').value.toString()+'&nomidproceso='+document.getElementById('pgidproceso').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});