/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * catálogo de factores en el sistema.
 */

function guardarEscala(url,parametro){
	/*
	 * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	
	if(document.getElementById("Escala").value.toString() == "")
		{
			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
			error= error+1;
			}
	
	if(document.getElementById("Ponderacion").value.toString() == "")
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
	
function habEscala()
	{
		/*
		 * Esta función habilita los controles del formulario de factor.
		 */

		document.getElementById('Escala').disabled = false;
		document.getElementById('Ponderacion').disabled = false;
		document.getElementById('idCedula').disabled = false;
		document.getElementById('fes_Guardar').style.display="block";
		document.getElementById('fes_Borrar').style.display="none";
		document.getElementById('fes_Editar').style.display="none";
		}

/*
 * El presente segmento de codigo evalua la accion de change sobre el elemento con el ID idObjEst
 * para ejecutar la acción de actualización sobre su dependencia.
 */
$(document).ready(function() {
    $("div").change(function(e){
    	e.stopPropagation();
    	if(e.target.id == "fesidentidad")
    		{
    			//Se confirma la carga de actualizacion sobre Cedulas. 
    			cargar('./php/frontend/foda/escalas/comp/cbCedulas.php','?id='+document.getElementById('fesidentidad').value.toString(),'cbCedulas');
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
    	if(e.target.id == "fescedula")
    		{
    			//Se confirma la carga de actualizacion sobre addFactor. 
    			cargar('./php/frontend/foda/escalas/opQuickEscalas.php','?idcedula='+document.getElementById('fescedula').value.toString(),'addEscala');
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
    	if(e.target.id == "fesquickAdd")
    		{
    			//Se confirma la carga para almacenamiento rapido.
    			if(document.getElementById('fescedula').value.toString()!="-1")
    				{
    					//Si el usuario ha seleccionado un identificador de cedula previamente.
    					if((document.getElementById('Escala').value.toString()!="")&&(document.getElementById('Ponderacion').value.toString()!=""))
    						{
    							//Solo si el usuario ha proporcionado los datos completos para el alta rapida.
        						cargarSync('./php/backend/foda/escalas/quickAdd/guardar.php','?id='+document.getElementById('idEscala').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&escala='+document.getElementById('Escala').value.toString()+'&ponderacion='+document.getElementById('Ponderacion').value.toString()+'&status='+document.getElementById('Status').value.toString(),'addEscala');
        						cargarSync('./php/frontend/foda/escalas/catEscalas.php','?fescedula='+document.getElementById('fescedula').value.toString(),'busRes');
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
    					alert("Debe seleccionar una cedula para agregar la escala");
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
    	if(e.target.id.substring(0,10) == "fes_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.    			
				document.getElementById('pgcedula').value = document.getElementById('fescedula').value.toString();
				document.getElementById('pgescala').value = document.getElementById('fesescala').value.toString();
				document.getElementById('pgponderacion').value = document.getElementById('fesponderacion').value.toString();
				document.getElementById('pgidentidad').value = document.getElementById('fesidentidad').value.toString();
    			cargar('./php/frontend/foda/escalas/catEscalas.php','?fescedula='+document.getElementById('fescedula').value.toString()+'&fesescala='+document.getElementById('fesescala').value.toString()+'&fesponderacion='+document.getElementById('fesponderacion').value.toString()+'&fesidentidad='+document.getElementById('fesidentidad').value.toString(),'busRes');
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
    	if(e.target.id.substring(0,10) == "fes_delete")
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
		            					cargar('./php/backend/foda/escalas/borrar.php','?id='+e.target.id.substring(11),'sandbox');
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
    	if(e.target.id.substring(0,7) == "fes_add")
    		{
    			//En caso de coincidir el id con la accion agregar.
    			cargar('./php/frontend/foda/escalas/opEscalas.php','?id=-1&fescedula='+document.getElementById('fescedula').value.toString()+'&view=0','sandbox');
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
    	if(e.target.id.substring(0,14) == "fes_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/foda/escalas/opEscalas.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
    	if(e.target.id.substring(0,8) == "fes_edit")
    		{
    			//En caso de coincidir el id con la accion editar.
    			cargar('./php/frontend/foda/escalas/opEscalas.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
					if(e.target.id == "fes_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
			    			cargar('./php/frontend/foda/escalas/catEscalas.php','?fescedula='+document.getElementById('pgcedula').value.toString()+'&fesescala='+document.getElementById('pgescala').value.toString()+'&fesponderacion='+document.getElementById('pgponderacion').value.toString()+'&fesidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "fes_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
			    			cargar('./php/frontend/foda/escalas/catEscalas.php','?fescedula='+document.getElementById('pgcedula').value.toString()+'&fesescala='+document.getElementById('pgescala').value.toString()+'&fesponderacion='+document.getElementById('pgponderacion').value.toString()+'&fesidentidad='+document.getElementById('pgidentidad').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
	    		        if(e.target.id == "fes_Volver")
	    		        	{
	    		            	//En caso de coincidir el id con la accion volver.
	    		        		cargar('./php/frontend/foda/escalas/busEscalas.php','','sandbox');
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
	    			    if(e.target.id == "fes_Borrar")
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
	    				            					cargar('./php/backend/foda/escalas/borrar.php','?id='+document.getElementById('idEscala').value.toString(),'sandbox');
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
	    				if(e.target.id == "fes_Guardar")
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
	    				            					guardarEscala('./php/backend/foda/escalas/guardar.php','?id='+document.getElementById('idEscala').value.toString()+'&escala='+document.getElementById('Escala').value.toString()+'&ponderacion='+document.getElementById('Ponderacion').value.toString()+'&idcedula='+document.getElementById('idCedula').value.toString()+'&status='+document.getElementById('Status').value.toString());
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
	    				if(e.target.id == "fes_Editar")
	    					{
	    				     	//En caso de coincidir el id con la accion edicion.
	    						habEscala();
	    						}
	    				});                 
				});