/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de objope en el sistema.
 */

function guardarObjOpe(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;

	if(document.getElementById("idObjEst").value.toString() == "-1")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("ObjOpe").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Periodo").value.toString() == "")
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
	
function habObjOpe(){
	/*
	 * Esta función habilita los controles del formulario de colonia.
	 */

	/*document.getElementById('Nomenclatura').disabled = false;*/
	document.getElementById('idObjEst').disabled = false;
	/*document.getElementById('Periodo').disabled = false;*/
	}

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "oop_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
				document.getElementById('pgobjope').value = document.getElementById('nomobjope').value.toString();
				document.getElementById('pgnomenclatura').value = document.getElementById('objopenomenclatura').value.toString();
				document.getElementById('pgperiodo').value = document.getElementById('objopeperiodo').value.toString();
    			cargar('./php/frontend/objope/catObjOpe.php','?nomobjope='+document.getElementById('nomobjope').value.toString()+'&objopenomenclatura='+document.getElementById('objopenomenclatura').value.toString()+'&objopeperiodo='+document.getElementById('objopeperiodo').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "oop_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/objope/borrar.php','?id='+e.target.id.substring(11),'escritorio');
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
    	if(e.target.id.substring(0,7) == "oop_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/objope/opObjOpe.php','?id=-1&view=0','escritorio');
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
    	if(e.target.id.substring(0,14) == "oop_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/objope/opObjOpe.php','?id='+e.target.id.substring(15)+'&view=1','escritorio');
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
    	if(e.target.id.substring(0,8) == "oop_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/objope/opObjOpe.php','?id='+e.target.id.substring(9)+'&view=0','escritorio');
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
					if(e.target.id == "oop_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
			    			cargar('./php/frontend/objope/catObjOpe.php','?nomobjope='+document.getElementById('pgobjope').value.toString()+'&objopenomenclatura='+document.getElementById('pgnomenclatura').value.toString()+'&objopeperiodo='+document.getElementById('pgperiodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "oop_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
			    			cargar('./php/frontend/objope/catObjOpe.php','?nomobjope='+document.getElementById('pgobjope').value.toString()+'&objopenomenclatura='+document.getElementById('pgnomenclatura').value.toString()+'&objopeperiodo='+document.getElementById('pgperiodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});