/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de cedulas en el sistema.
 */

function guardarEvaluacion(url,parametro){
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
	
	if(document.getElementById("fevempleado").value.toString() == "-1")
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
	
function habEvaluacion()
	{
		/*
		 * Esta función habilita los controles del formulario de colonia.
		 */

		//document.getElementById('Factor').disabled = false;
		document.getElementById('idCedula').disabled = false;
		document.getElementById('idEntfoda').disabled = false;
		document.getElementById('fevempleado').disabled = false;
		}

/*
 * El presente segmento de codigo evalua la accion de change sobre el elemento con el ID fevidentidad
 * para ejecutar la acción de actualización sobre su dependencia.
 */
$(document).ready(function() {
    $("div").change(function(e){
    	e.stopPropagation();
    	if(e.target.id == "fevidentidad")
    		{
    			//Se confirma la carga de actualizacion sobre Cedulas.
    			if(document.getElementById('fevidentidad').value.toString()=="-2")
    				{
    					cargar('./php/frontend/foda/evaluaciones/comp/cbEmpSF.php','?id='+document.getElementById('fevidentidad').value.toString(),'cbEmpleados');
    					}
    			else
    				{
						cargar('./php/frontend/foda/evaluaciones/comp/cbEmpleados.php','?id='+document.getElementById('fevidentidad').value.toString(),'cbEmpleados');    			
    					}
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de change sobre el elemento con el ID idEntfoda
 * para ejecutar la acción de actualización sobre su dependencia.
 */
$(document).ready(function() {
    $("div").change(function(e){
    	e.stopPropagation();
    	if(e.target.id == "idEntfoda")
    		{
    			//Se confirma la carga de actualizacion sobre Cedulas.
    			if(document.getElementById('idEntfoda').value.toString()=="-2")
    				{
    					cargarSync('./php/frontend/foda/evaluaciones/comp/cbEmpSF.php','?id='+document.getElementById('idEntfoda').value.toString(),'cbEmpleados');
    					}
    			else
    				{
    					cargarSync('./php/frontend/foda/evaluaciones/comp/cbEmpleados.php','?id='+document.getElementById('idEntfoda').value.toString(),'cbEmpleados');
    					}    			
    			cargarSync('./php/frontend/foda/evaluaciones/comp/cbCedulas.php','?id='+document.getElementById('idEntfoda').value.toString(),'cbCedulas');
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
    	if(e.target.id.substring(0,10) == "fev_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.  
    			cargar('./php/frontend/foda/evaluaciones/catEvaluaciones.php','?fevfolio='+document.getElementById('fevfolio').value.toString()+'&fevidempleado='+document.getElementById('fevempleado').value.toString()+'&fevidentidad='+document.getElementById('fevidentidad').value.toString()+'&fevfecha='+document.getElementById('fevfecha').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "fev_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/foda/evaluaciones/borrar.php','?id='+e.target.id.substring(11),'escritorio');
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
    	if(e.target.id.substring(0,7) == "fev_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/foda/evaluaciones/opEvaluaciones.php','?id=-1&view=0','escritorio');
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
    	if(e.target.id.substring(0,14) == "fev_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/foda/evaluaciones/opEvaluaciones.php','?id='+e.target.id.substring(15)+'&view=1','escritorio');
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
    	if(e.target.id.substring(0,8) == "fev_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/foda/evaluaciones/opEvaluaciones.php','?id='+e.target.id.substring(9)+'&view=0','escritorio');
    			}
    });                 
});