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
	
function habEvaluacion()
	{
		/*
		 * Esta función habilita los controles del formulario de colonia.
		 */

		//document.getElementById('Factor').disabled = false;
		document.getElementById('idCedula').disabled = false;
		document.getElementById('idEntfoda').disabled = false;
		document.getElementById('fevempleado').disabled = false;
		document.getElementById('fev_Guardar').style.display="block";
		document.getElementById('fev_Borrar').style.display="none";
		document.getElementById('fev_Editar').style.display="none";
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
				document.getElementById('pgfolio').value = document.getElementById('fevfolio').value.toString();
				document.getElementById('pgempleado').value = document.getElementById('fevempleado').value.toString();
				document.getElementById('pgidentidad').value = document.getElementById('fevidentidad').value.toString();
				document.getElementById('pgfecha').value = document.getElementById('fevfecha').value.toString();
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
		            					cargar('./php/backend/foda/evaluaciones/borrar.php','?id='+e.target.id.substring(11),'sandbox');
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
    	if(e.target.id.substring(0,7) == "fev_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/foda/evaluaciones/opEvaluaciones.php','?id=-1&view=0','sandbox');
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
    			cargar('./php/frontend/foda/evaluaciones/opEvaluaciones.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
    			cargar('./php/frontend/foda/evaluaciones/opEvaluaciones.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
					if(e.target.id == "fev_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/foda/evaluaciones/catEvaluaciones.php','?fevfolio='+document.getElementById('pgfolio').value.toString()+'&fevidempleado='+document.getElementById('pgempleado').value.toString()+'&fevidentidad='+document.getElementById('pgidentidad').value.toString()+'&fevfecha='+document.getElementById('pgfecha').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "fev_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/foda/evaluaciones/catEvaluaciones.php','?fevfolio='+document.getElementById('pgfolio').value.toString()+'&fevidempleado='+document.getElementById('pgempleado').value.toString()+'&fevidentidad='+document.getElementById('pgidentidad').value.toString()+'&fevfecha='+document.getElementById('pgfecha').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
	    		        if(e.target.id == "fev_Volver")
	    		        	{
	    		            	//En caso de coincidir el id con la accion volver.
	    		        		cargar('./php/frontend/foda/evaluaciones/busEvaluaciones.php','','sandbox');
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
	    			    if(e.target.id == "fev_Borrar")
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
	    				            					cargar('./php/backend/foda/evaluaciones/borrar.php','?id='+document.getElementById('idEvaluacion').value.toString(),'sandbox');
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
	    				if(e.target.id == "fev_Guardar")
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
	    				            					guardarEvaluacion('./php/backend/foda/evaluaciones/guardar.php','?id='+document.getElementById('idEvaluacion').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&folio='+document.getElementById('Folio').value.toString()+'&idempleado='+document.getElementById('fevempleado').value.toString()+'&fecha='+document.getElementById('Fecha').value.toString()+'&status='+document.getElementById('Status').value.toString());
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
	    				if(e.target.id == "fev_Editar")
	    					{
	    				     	//En caso de coincidir el id con la accion edicion.
	    						habEvaluacion();
	    						}
	    				});                 
				});	