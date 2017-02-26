/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de ejecuciones en el sistema.
 */

function guardarEjecucion(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	var flgMonto= '';
	var montoTotal= parseFloat(document.getElementById("MontoAcumulado").value) + parseFloat(document.getElementById("Monto").value);
	var montoAct= parseFloat(document.getElementById("MontoActividad").value);
	
	if(montoTotal>montoAct)
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			flgMonto= 'Error';
			}
	
	if(document.getElementById("idActividad").value.toString() == "-1")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("idMes").value.toString() == "-1")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}

	if(document.getElementById("Cantidad").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}

	if(document.getElementById("Monto").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(error > 0)
		{
			/*
			 * En caso de ocurrir un error de validación, se notifica al usuario.
			 */
			if(flgMonto=='')
				{
					/*
					 * En caso que el monto no se sobregire.
					 */
					alert("Existen campos pendientes por completar");
					}
			else
				{
					/*
					 * En caso que el monto este sobregirado.
					 */
					alert("El monto de la ejecucion sobrepasa el destinado a la actividad");
					}			
			}
	else
		{
			/*
			 * En caso que la validación de campos sea satisfactoria.
			 */
			cargar(url,parametro,'escritorio');
			}
	}
	
function habEjecucion(){
	/*
	 * Esta función habilita los controles del formulario de ejecucion.
	 */
	document.getElementById('idActividad').disabled = false;
	document.getElementById('Cantidad').disabled = false;
	document.getElementById('Monto').disabled = false;
	document.getElementById('idMes').disabled = false;
	
	/*document.getElementById('P_1').disabled = false;
	document.getElementById('P_2').disabled = false;
	document.getElementById('P_3').disabled = false;
	document.getElementById('P_4').disabled = false;
	document.getElementById('P_5').disabled = false;
	document.getElementById('P_6').disabled = false;
	document.getElementById('P_7').disabled = false;
	document.getElementById('P_8').disabled = false;
	document.getElementById('P_9').disabled = false;
	document.getElementById('P_10').disabled = false;
	document.getElementById('P_11').disabled = false;
	document.getElementById('P_12').disabled = false;*/

	/*document.getElementById('E_1').disabled = false;
	document.getElementById('E_2').disabled = false;
	document.getElementById('E_3').disabled = false;
	document.getElementById('E_4').disabled = false;
	document.getElementById('E_5').disabled = false;
	document.getElementById('E_6').disabled = false;
	document.getElementById('E_7').disabled = false;
	document.getElementById('E_8').disabled = false;
	document.getElementById('E_9').disabled = false;
	document.getElementById('E_10').disabled = false;
	document.getElementById('E_11').disabled = false;
	document.getElementById('E_12').disabled = false;*/	
	}

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "ejc_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.  
    			cargar('./php/frontend/ejecuciones/catEjecucion.php','?idactividad='+document.getElementById('idActividad').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "ejc_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/ejecuciones/borrar.php','?id='+e.target.id.substring(11)+'&idactividad='+document.getElementById('idActividad').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&idview=3','escritorio');
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
    	if(e.target.id.substring(0,7) == "ejc_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/ejecuciones/opEjecucion.php','?id=-1&view=0&idactividad='+document.getElementById('idActividad').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString(),'escritorio');
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
    	if(e.target.id.substring(0,14) == "ejc_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/ejecuciones/opEjecucion.php','?id='+e.target.id.substring(15)+'&view=1&idactividad='+document.getElementById('idActividad').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString(),'escritorio');
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
    	if(e.target.id.substring(0,8) == "ejc_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/ejecuciones/opEjecucion.php','?id='+e.target.id.substring(9)+'&view=0&idactividad='+document.getElementById('idActividad').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString(),'escritorio');
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
					if(e.target.id == "ejc_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/ejecuciones/catEjecucion.php','?idactividad='+document.getElementById('idActividad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'dataejecuciones');
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
					if(e.target.id == "ejc_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/ejecuciones/catEjecucion.php','?idactividad='+document.getElementById('idActividad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'dataejecuciones');
							}
					});                 
			});