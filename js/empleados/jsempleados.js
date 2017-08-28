/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de empleados en el sistema.
 */

function guardarEmpleado(url,parametro){
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
	
	if(document.getElementById("idEntEmp").value.toString() == "-1")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;		
			}
	
	if(document.getElementById("idPuesto").value.toString() == "-1")
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
        	bootbox.alert("Existen campos pendientes por completar");
			}
	else
		{
			/*
			 * En caso que la validación de campos sea satisfactoria.
			 */
			cargar(url,parametro,'sandbox');
			}
	}
	
function habEmpleado(){
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
	document.getElementById('idEntEmp').disabled = false;
	document.getElementById('idPuesto').disabled = false;
	document.getElementById('RFC').disabled = false;
	document.getElementById('CURP').disabled = false;
	document.getElementById('TelFijo').disabled = false;
	document.getElementById('TelCel').disabled = false;
	document.getElementById('idUsuario').disabled = false;	
	}

/*
 * El presente segmento de codigo evalua la accion de click sobre el combobox de Entidades
 * para ejecutar la acción de actualización sobre el combobox de Puestos.
 */
$(document).ready(function() {
    $("div").change(function(e){
    	e.stopPropagation();
    	if(e.target.id == "idEntEmp")
    		{
    			//Se confirma la carga de actualizacion sobre Puestos. 
    			cargar('./php/frontend/empleados/comp/cbPuesto.php','?id='+document.getElementById('idEntEmp').value.toString(),'cbPuesto');
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
    	if(e.target.id.substring(0,10) == "emp_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.  
				document.getElementById('pgempleado').value = document.getElementById('nomempleado').value.toString();
				document.getElementById('pgpaterno').value = document.getElementById('patempleado').value.toString();
				document.getElementById('pgmaterno').value = document.getElementById('matempleado').value.toString();
				document.getElementById('pgidentidad').value = document.getElementById('empidentidad').value.toString();
    			cargar('./php/frontend/empleados/catEmpleados.php','?nomempleado='+document.getElementById('nomempleado').value.toString()+'&patempleado='+document.getElementById('patempleado').value.toString()+'&matempleado='+document.getElementById('matempleado').value.toString()+'&empidentidad='+document.getElementById('empidentidad').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "emp_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
            	bootbox.confirm(
	            	{
		            	message: "¿Confirma que desea borrar el registro?",
		            	buttons: 
		            		{
		            			confirm: 
		            				{
		            					label: 'SI',
		            					className: 'btn-success'
		            					},
		            			cancel: 
		            				{
		            					label: 'NO',
		            					className: 'btn-danger'
		            					}
		            			},
		            	callback: function (result)
		            		{
		            			if(result)
		            				{
		            					//EL USUARIO DECIDE BORRAR EL REGISTRO.
		            					cargar('./php/backend/empleados/borrar.php','?id='+e.target.id.substring(11),'sandbox');
		            					}			            					
		            			}
	            		});
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
    	if(e.target.id.substring(0,7) == "emp_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/empleados/opEmpleados.php','?id=-1&view=0','sandbox');
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
    	if(e.target.id.substring(0,14) == "emp_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/empleados/opEmpleados.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
    	if(e.target.id.substring(0,8) == "emp_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/empleados/opEmpleados.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
					if(e.target.id == "emp_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
			    			cargar('./php/frontend/empleados/catEmpleados.php','?nomempleado='+document.getElementById('pgempleado').value.toString()+'&patempleado='+document.getElementById('pgpaterno').value.toString()+'&matempleado='+document.getElementById('pgmaterno').value.toString()+'&empidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "emp_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
			    			cargar('./php/frontend/empleados/catEmpleados.php','?nomempleado='+document.getElementById('pgempleado').value.toString()+'&patempleado='+document.getElementById('pgpaterno').value.toString()+'&matempleado='+document.getElementById('pgmaterno').value.toString()+'&empidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});
	
	//DECLARACION DE ACCIONES A EJECUTARSE SOBRE FORMULARIO OPERATIVO.
	/*
	 * El presente segmento de codigo evalua la accion de click sobre el elemento de retorno
	 * pulsado sobre el formulario operativo.
	 */
		$(document).ready(function()
			{
	    		$("div").click(function(e)
	    			{
	    		     	e.stopPropagation();
	    		        if(e.target.id == "emp_Volver")
	    		        	{
	    		            	//En caso de coincidir el id con la accion volver.
	    		        		cargar('./php/frontend/empleados/busEmpleados.php','','sandbox');
	    		            	}
	    				});                 
				});
	    		
	/*
	 * El presente segmento de codigo evalua la accion de click sobre el elemento de borrado
	 * pulsado sobre el formulario operativo.
	 */
		$(document).ready(function()
			{
	    		$("div").click(function(e)
	    			{
	    			 	e.stopPropagation();
	    			    if(e.target.id == "emp_Borrar")
	    			    	{
	    			         	//En caso de coincidir el id con la accion borrar.
	    			            bootbox.confirm(
	    			            	{
	    				            	message: "¿Confirma que desea borrar el registro?",
	    				            	buttons: 
	    				            		{
	    				            			confirm: 
	    				            				{
	    				            					label: 'SI',
	    				            					className: 'btn-success'
	    				            					},
	    				            			cancel: 
	    				            				{
	    				            					label: 'NO',
	    				            					className: 'btn-danger'
	    				            					}
	    				            			},
	    				            	callback: function (result)
	    				            		{
	    				            			if(result)
	    				            				{
	    				            					//EL USUARIO DECIDE BORRAR EL REGISTRO.
	    				            					cargar('./php/backend/empleados/borrar.php','?id='+document.getElementById('idEmpleado').value.toString(),'sandbox');
	    				            					}			            					
	    				            			}
	    			            		});
	    			    		}
	    				});                 
				});

	/*
	 * El presente segmento de codigo evalua la accion de click sobre el elemento de guardado
	 * pulsado sobre el formulario operativo.
	 */
		$(document).ready(function()
			{
	    		$("div").click(function(e)
	    			{
	    				e.stopPropagation();
	    				if(e.target.id == "emp_Guardar")
	    					{
	    				     	//En caso de coincidir el id con la accion guardar.
	    				        bootbox.confirm(
	    				        	{
	    				            	message: "¿Confirma que desea almacenar los cambios?",
	    				            	buttons: 
	    				            		{
	    				            			confirm: 
	    				            				{
	    				            					label: 'SI',
	    				            					className: 'btn-success'
	    				            					},
	    				            			cancel: 
	    				            				{
	    				            					label: 'NO',
	    				            					className: 'btn-danger'
	    				            					}
	    				            			},
	    				            	callback: function (result)
	    				            		{
	    				            			if(result)
	    				            				{
	    				            					//EL USUARIO DECIDE ALMACENAR LOS DATOS.
	    				            					guardarEmpleado('./php/backend/empleados/guardar.php','?id='+document.getElementById('idEmpleado').value.toString()+'&nombre='+document.getElementById('Nombre').value.toString()+'&paterno='+document.getElementById('Paterno').value.toString()+'&materno='+document.getElementById('Materno').value.toString()+'&calle='+document.getElementById('Calle').value.toString()+'&nint='+document.getElementById('Nint').value.toString()+'&next='+document.getElementById('Next').value.toString()+'&idcolonia='+document.getElementById('idColonia').value.toString()+'&identemp='+document.getElementById('idEntEmp').value.toString()+'&idpuesto='+document.getElementById('idPuesto').value.toString()+'&rfc='+document.getElementById('RFC').value.toString()+'&curp='+document.getElementById('CURP').value.toString()+'&telfijo='+document.getElementById('TelFijo').value.toString()+'&telcel='+document.getElementById('TelCel').value.toString()+'&idusuario='+document.getElementById('idUsuario').value.toString()+'&status='+document.getElementById('Status').value.toString());
	    				            					}			            					
	    				            			}
	    				        		});			        		
	    						}
	    				});                 
				});

	/*
	 * El presente segmento de codigo evalua la accion de click sobre el elemento de edicion
	 * pulsado sobre el formulario operativo.
	 */
		$(document).ready(function()
			{
	    		$("div").click(function(e)
	    			{
	    				e.stopPropagation();
	    				if(e.target.id == "emp_Editar")
	    					{
	    				     	//En caso de coincidir el id con la accion edicion.
	    				        habEmpleado();
	    						}
	    				});                 
				});	