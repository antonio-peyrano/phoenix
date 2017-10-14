function guardarRSGR(url,parametro)
	{
		/*
		 * Esta funcion valida que los datos obtenidos mediante la interfaz, sean adecuados
		 * para su almacenamiento en la base de datos.
		 */
		var error= 0;
	
		if(document.getElementById("Nivel").value.toString() == "Seleccione")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}
				
		if(document.getElementById("nEdicion").value.toString() == "")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}
		
		if(document.getElementById("FechaEdicion").value.toString() == "")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}		
		
		if(document.getElementById("Supervisor").value.toString() == "")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}		
		
		if(document.getElementById("Causa").value.toString() == "")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}
		
		if(document.getElementById("Efecto").value.toString() == "")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}

		if(document.getElementById("Acciones").value.toString() == "")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}				
		
		if(document.getElementById("Riesgo").value.toString() == "")
			{
				//En caso de no ocurrir un error de validación, se asigna el valor de paso.
				error= error+1;
				}

		if(procesosSeleccionados() == 0)
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
				parametro = parametro.replace(/\n/g,"<br>"); //Se cambian los saltos de linea por el tag <br>.
				cargar(url,parametro,'sandbox');
				}		
		}

function procesosSeleccionados()
	{
		/*
		 * Esta función obtiene los procesos seleccionados.
		 */
		var checkboxes = $('.check');
		var temp = 0; 
 
		for (var x=0; x < checkboxes.length; x++) 
			{
				if (checkboxes[x].checked) 
					{
						temp += 1;
						}
				}
			
		return temp;
		}

function habRSGR()
	{
		/*
		 * Esta función habilita los controles del formulario de programa.
		 */
		var checkboxes = $('.check');
	
		for (var x=0; x < checkboxes.length; x++)
			{
				checkboxes[x].disabled = false;
				}
	
		document.getElementById('Nivel').disabled = false;
		document.getElementById('Clave').disabled = false;
		document.getElementById('nEdicion').disabled = false;
		document.getElementById('FechaEdicion').disabled = false;
		document.getElementById('Riesgo').disabled = false;
		document.getElementById('Supervisor').disabled = false;
		document.getElementById('Causa').disabled = false;
		document.getElementById('Efecto').disabled = false;
		document.getElementById('Acciones').disabled = false;
		document.getElementById('psr_Guardar').style.display="block";
		document.getElementById('psr_Borrar').style.display="none";
		document.getElementById('psr_Editar').style.display="none";
		}

function procesosid()
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

function nonprocesosid()
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
    	if(e.target.id.substring(0,10) == "psr_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado. 
				document.getElementById('pgclave').value = document.getElementById('nomclave').value.toString();
				document.getElementById('pgriesgo').value = document.getElementById('nomriesgo').value.toString();    		
    			cargar('./php/frontend/planesRSGR/catPlanRSGR.php','?nomclave='+document.getElementById('nomclave').value.toString()+'&nomriesgo='+document.getElementById('nomriesgo').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "psr_delete")
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
		            					cargar('./php/backend/planesRSGR/borrar.php','?id='+e.target.id.substring(11),'sandbox');
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
    	if(e.target.id.substring(0,7) == "psr_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/planesRSGR/opPlanRSGR.php','?id=-1&view=0','sandbox');
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
    	if(e.target.id.substring(0,14) == "psr_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/planesRSGR/opPlanRSGR.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
    	if(e.target.id.substring(0,8) == "psr_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/planesRSGR/opPlanRSGR.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
					if(e.target.id == "psr_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/planesRSGR/catPlanRSGR.php','?nomclave='+document.getElementById('pgclave').value.toString()+'&nomriesgo='+document.getElementById('pgriesgo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "psr_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/planesRSGR/catPlanRSGR.php','?nomclave='+document.getElementById('pgclave').value.toString()+'&nomriesgo='+document.getElementById('pgriesgo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
	    		        if(e.target.id == "psr_Volver")
	    		        	{
	    		            	//En caso de coincidir el id con la accion volver.
	    		        		cargar('./php/frontend/planesRSGR/busPlanRSGR.php','','sandbox');
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
	    			    if(e.target.id == "psr_Borrar")
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
	    				            					cargar('./php/backend/planesRSGR/borrar.php','?id='+document.getElementById('idPlanRSGR').value.toString(),'sandbox');
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
	    				if(e.target.id == "psr_Guardar")
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
	    				            					guardarRSGR('./php/backend/planesRSGR/guardar.php','?id='+document.getElementById('idPlanRSGR').value.toString()+'&nivel='+document.getElementById('Nivel').value.toString()+'&clave='+document.getElementById('Clave').value.toString()+'&nedicion='+document.getElementById('nEdicion').value.toString()+'&fechaedicion='+document.getElementById('FechaEdicion').value.toString()+'&riesgo='+document.getElementById('Riesgo').value.toString()+'&supervisor='+document.getElementById('Supervisor').value.toString()+'&causa='+document.getElementById('Causa').value.toString()+'&efecto='+document.getElementById('Efecto').value.toString()+'&acciones='+document.getElementById('Acciones').value.toString()+'&idprocesos='+procesosid()+'&nonidprocesos='+nonprocesosid()+'&status='+document.getElementById('Status').value.toString());
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
	    				if(e.target.id == "psr_Editar")
	    					{
	    				     	//En caso de coincidir el id con la accion edicion.
	    						document.getElementById("nEdicion").value = parseInt(document.getElementById("nEdicion").value.toString())+1;
	    				        habRSGR();
	    						}
	    				});                 
				});	