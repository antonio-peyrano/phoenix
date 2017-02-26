/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de factores en el sistema.
 */

function guardarFactor(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	
	if(document.getElementById("Factor").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Tipo").value.toString() == "Seleccione")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("idCedula").value.toString() == "-1")
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
	
function habFactor()
	{
		/*
		 * Esta función habilita los controles del formulario de factor.
		 */

		document.getElementById('Factor').disabled = false;
		document.getElementById('Tipo').disabled = false;
		document.getElementById('idCedula').disabled = false;
		}

/*
 * El presente segmento de codigo evalua la accion de change sobre el elemento con el ID idObjEst
 * para ejecutar la acción de actualización sobre su dependencia.
 */
$(document).ready(function() {
    $("div").change(function(e){
    	e.stopPropagation();
    	if(e.target.id == "ffaidentidad")
    		{
    			//Se confirma la carga de actualizacion sobre Cedulas. 
    			cargar('./php/frontend/foda/factores/comp/cbCedulas.php','?id='+document.getElementById('ffaidentidad').value.toString(),'cbCedulas');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de change sobre el elemento con el ID idObjEst
 * para ejecutar la acción de actualización sobre su dependencia.
 */
$(document).ready(function() {
    $("div").change(function(e){
    	e.stopPropagation();
    	if(e.target.id == "ffacedula")
    		{
    			//Se confirma la carga de actualizacion sobre addFactor. 
    			cargar('./php/frontend/foda/factores/opQuickFactores.php','?idcedula='+document.getElementById('ffacedula').value.toString(),'addFactor');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de change sobre el elemento con el ID idObjEst
 * para ejecutar la acción de actualización sobre su dependencia.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id == "ffaquickAdd")
    		{
    			//Se confirma la carga para almacenamiento rapido.
    			if(document.getElementById('ffacedula').value.toString()!="-1")
    				{
    					//Si el usuario ha seleccionado un identificador de cedula previamente.
    					if((document.getElementById('Factor').value.toString()!="")&&(document.getElementById('Tipo').value.toString()!="-1"))
    						{
    							//Solo si el usuario ha proporcionado los datos completos para el alta rapida.
        						cargarSync('./php/backend/foda/factores/quickAdd/guardar.php','?id='+document.getElementById('idFactor').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&factor='+document.getElementById('Factor').value.toString()+'&tipo='+document.getElementById('Tipo').value.toString()+'&status='+document.getElementById('Status').value.toString(),'addFactor');
        						cargarSync('./php/frontend/foda/factores/catFactores.php','?ffacedula='+document.getElementById('ffacedula').value.toString(),'busRes');
    							}
    					else
    						{
    							//En caso de ocurrir un error, se notifica al usuario.
    							alert("Existen datos pendientes por introducir");
    							}
    					}
    			else
    				{
    					//Si el usuario no selecciono un identificador de cedula, se notifica del error.
    					alert("Debe seleccionar una cedula para agregar el factor");
    					}
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "ffa_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
				document.getElementById('pgcedula').value = document.getElementById('ffacedula').value.toString();
				document.getElementById('pgfactor').value = document.getElementById('ffafactor').value.toString();
				document.getElementById('pgtipo').value = document.getElementById('ffatipo').value.toString();
				document.getElementById('pgidentidad').value = document.getElementById('ffaidentidad').value.toString();
    			cargar('./php/frontend/foda/factores/catFactores.php','?ffacedula='+document.getElementById('ffacedula').value.toString()+'&ffafactor='+document.getElementById('ffafactor').value.toString()+'&ffatipo='+document.getElementById('ffatipo').value.toString()+'&ffaidentidad='+document.getElementById('ffaidentidad').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "ffa_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/foda/factores/borrar.php','?id='+e.target.id.substring(11),'escritorio');
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
    	if(e.target.id.substring(0,7) == "ffa_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/foda/factores/opFactores.php','?id=-1&ffacedula='+document.getElementById('ffacedula').value.toString()+'&view=0','escritorio');
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
    	if(e.target.id.substring(0,14) == "ffa_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/foda/factores/opFactores.php','?id='+e.target.id.substring(15)+'&view=1','escritorio');
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
    	if(e.target.id.substring(0,8) == "ffa_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/foda/factores/opFactores.php','?id='+e.target.id.substring(9)+'&view=0','escritorio');
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
					if(e.target.id == "ffa_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/foda/factores/catFactores.php','?ffacedula='+document.getElementById('pgcedula').value.toString()+'&ffafactor='+document.getElementById('pgfactor').value.toString()+'&ffatipo='+document.getElementById('pgtipo').value.toString()+'&ffaidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "ffa_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/foda/factores/catFactores.php','?ffacedula='+document.getElementById('pgcedula').value.toString()+'&ffafactor='+document.getElementById('pgfactor').value.toString()+'&ffatipo='+document.getElementById('pgtipo').value.toString()+'&ffaidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});