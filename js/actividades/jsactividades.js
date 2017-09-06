/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de actividades en el sistema.
 */

function guardarActividad(url,parametro)
	{
		/*
		 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
		 */
		var error= 0;
		var flgMonto= '';
		var montoTotal= parseFloat(document.getElementById("MontoAcumulado").value) + parseFloat(document.getElementById("Monto").value);
		var montoProg= parseFloat(document.getElementById("MontoPrograma").value);
	
		if(montoTotal>montoProg)
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				flgMonto= 'Error';
				}
	
		if(document.getElementById("idPrograma").value.toString() == "-1")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}
	
		if(document.getElementById("idUnidad").value.toString() == "-1")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}

		if(document.getElementById("Actividad").value.toString() == "")
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
                    	bootbox.alert("Existen campos pendientes por completar");
						}
				else
					{
						/*
						 * En caso que el monto este sobregirado.
						 */
                    	bootbox.alert("El monto propuesto a la actividad sobrepasa el asignado a programa");
						}			
				}
		else
			{
				/*
				 * En caso que la validación de campos sea satisfactoria.
				 */
				cargar(url,parametro,'sandbox');
				}
		}
	
function habActividad()
	{
		/*
		 * Esta función habilita los controles del formulario de programa.
		 */
		document.getElementById('idPrograma').disabled = false;
		document.getElementById('idUnidad').disabled = false;
		document.getElementById('Actividad').disabled = false;
		document.getElementById('Monto').disabled = false;
	
		document.getElementById('P_1').disabled = false;
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
		document.getElementById('P_12').disabled = false;

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
		
		document.getElementById('act_Guardar').style.display="block";
		document.getElementById('act_Borrar').style.display="none";
		document.getElementById('act_Editar').style.display="none";
		}

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */

$(document).ready(function()
	{
    	$("div").click(function(e)
    		{
    			e.stopPropagation();
    			if(e.target.id.substring(0,10) == "act_buscar")
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.  
    					cargar('./php/frontend/actividad/catActividad.php','?idprograma='+document.getElementById('idPrograma').value.toString(),'busRes');
    					}
    			});                 
		});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id delete_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function()
	{
    	$("div").click(function(e)
    		{
    			e.stopPropagation();
    			if(e.target.id.substring(0,10) == "act_delete")
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
				            					cargar('./php/backend/actividad/borrar.php','?id='+e.target.id.substring(11)+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&idview=3','sandbox');
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
$(document).ready(function()
	{
    	$("div").click(function(e)
    		{
    			e.stopPropagation();
    			if(e.target.id.substring(0,7) == "act_add")
    				{
    					//En caso de coincidir el id con la accion agregar.
    					cargar('./php/frontend/actividad/opActividad.php','?id=-1&view=0&idprograma='+document.getElementById('idPrograma').value.toString(),'sandbox');
    					}
    			});                 
		});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id visualizar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function()
	{
    	$("div").click(function(e)
    		{
    			e.stopPropagation();
    			if(e.target.id.substring(0,14) == "act_visualizar")
    				{
    					//En caso de coincidir el id con la accion visualizar.
    					cargar('./php/frontend/actividad/opActividad.php','?id='+e.target.id.substring(15)+'&view=1&idprograma='+document.getElementById('idPrograma').value.toString(),'sandbox');
    					}
    			});                 
		});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id edit_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function()
	{
    	$("div").click(function(e)
    		{
    			e.stopPropagation();
    			if(e.target.id.substring(0,8) == "act_edit")
    				{
    					//En caso de coincidir el id con la accion editar.
    					cargar('./php/frontend/actividad/opActividad.php','?id='+e.target.id.substring(9)+'&view=0&idprograma='+document.getElementById('idPrograma').value.toString(),'sandbox');
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
					if(e.target.id == "act_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/actividad/catActividad.php','?idprograma='+document.getElementById('idPrograma').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'datatareas');
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
					if(e.target.id == "act_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/actividad/catActividad.php','?idprograma='+document.getElementById('idPrograma').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'datatareas');
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
	    		        if(e.target.id == "act_Volver")
	    		        	{
	    		            	//En caso de coincidir el id con la accion volver.
	    		        		cargar('./php/frontend/programa/opPrograma.php','?id='+document.getElementById('idPrograma').value.toString()+'&view=1','sandbox');
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
	    			    if(e.target.id == "act_Borrar")
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
	    				            					cargar('./php/backend/actividad/borrar.php','?id='+document.getElementById('idActividad').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&view=3','sandbox');
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
	    				if(e.target.id == "act_Guardar")
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
	    				            					guardarActividad('./php/backend/actividad/guardar.php','?id='+document.getElementById('idActividad').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&idunidad='+document.getElementById('idUnidad').value.toString()+'&actividad='+document.getElementById('Actividad').value.toString()+'&monto='+document.getElementById('Monto').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&p_1='+document.getElementById('P_1').value.toString()+'&p_2='+document.getElementById('P_2').value.toString()+'&p_3='+document.getElementById('P_3').value.toString()+'&p_4='+document.getElementById('P_4').value.toString()+'&p_5='+document.getElementById('P_5').value.toString()+'&p_6='+document.getElementById('P_6').value.toString()+'&p_7='+document.getElementById('P_7').value.toString()+'&p_8='+document.getElementById('P_8').value.toString()+'&p_9='+document.getElementById('P_9').value.toString()+'&p_10='+document.getElementById('P_10').value.toString()+'&p_11='+document.getElementById('P_11').value.toString()+'&p_12='+document.getElementById('P_12').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&view=3');
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
	    				if(e.target.id == "act_Editar")
	    					{
	    				     	//En caso de coincidir el id con la accion edicion.
	    						habActividad();
	    						}
	    				});                 
				});	