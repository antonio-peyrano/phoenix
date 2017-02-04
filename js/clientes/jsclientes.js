/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de clientes en el sistema.
 */

function guardarCliente(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	
	if(document.getElementById("Nombre").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Paterno").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Materno").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Calle").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}

	/*if(document.getElementById("Nint").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}*/

	if(document.getElementById("Next").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}

	if(document.getElementById("idColonia").value.toString() == "-1")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}
	
	if(document.getElementById("RFC").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}
	
	if(document.getElementById("CURP").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}

	if(document.getElementById("TelFijo").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}

	/*if(document.getElementById("TelCel").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}*/

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
	
function habCliente(){
	/*
	 * Esta función habilita los controles del formulario de cliente.
	 */

	document.getElementById('Nombre').disabled = false;
	document.getElementById('Paterno').disabled = false;
	document.getElementById('Materno').disabled = false;
	document.getElementById('Calle').disabled = false;
	document.getElementById('Nint').disabled = false;
	document.getElementById('Next').disabled = false;
	document.getElementById('idColonia').disabled = false;
	document.getElementById('RFC').disabled = false;
	document.getElementById('CURP').disabled = false;
	document.getElementById('TelFijo').disabled = false;
	document.getElementById('TelCel').disabled = false;
	}

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "cli_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
				document.getElementById('pgcliente').value = document.getElementById('nomcliente').value.toString();
				document.getElementById('pgpaterno').value = document.getElementById('patcliente').value.toString();
				document.getElementById('pgmaterno').value = document.getElementById('matcliente').value.toString();
				document.getElementById('pgcolonia').value = document.getElementById('colcliente').value.toString();
    			cargar('./php/frontend/clientes/catClientes.php','?nomcliente='+document.getElementById('nomcliente').value.toString()+'&patcliente='+document.getElementById('patcliente').value.toString()+'&matcliente='+document.getElementById('matcliente').value.toString()+'&colcliente='+document.getElementById('colcliente').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "cli_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/clientes/borrar.php','?id='+e.target.id.substring(11),'escritorio');
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
    	if(e.target.id.substring(0,7) == "cli_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/clientes/opClientes.php','?id=-1&view=0','escritorio');
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
    	if(e.target.id.substring(0,14) == "cli_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/clientes/opClientes.php','?id='+e.target.id.substring(15)+'&view=1','escritorio');
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
    	if(e.target.id.substring(0,8) == "cli_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/clientes/opClientes.php','?id='+e.target.id.substring(9)+'&view=0','escritorio');
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
					if(e.target.id == "cli_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/clientes/catClientes.php','?nomcliente='+document.getElementById('pgcliente').value.toString()+'&patcliente='+document.getElementById('pgpaterno').value.toString()+'&matcliente='+document.getElementById('pgmaterno').value.toString()+'&colcliente='+document.getElementById('pgcolonia').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "cli_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/clientes/catClientes.php','?nomcliente='+document.getElementById('pgcliente').value.toString()+'&patcliente='+document.getElementById('pgpaterno').value.toString()+'&matcliente='+document.getElementById('pgmaterno').value.toString()+'&colcliente='+document.getElementById('pgcolonia').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});