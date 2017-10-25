/*********************************************************************************************
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del *
 * catálogo de no conformidades en el sistema.                                                       *
 *********************************************************************************************/

    function guardaNoConformidad(url,parametro)
        {
            /*
             * Esta función valida que los datos para ser almacenados en el registro sean correctos.
             */	
            var error= 0;
		
            if(document.getElementById("Auditor").value.toString() == "")
                {
                    //En caso de no ocurrir un error de validación, se asigna el valor de paso.
                    error = error+1;			
                    }

            if(document.getElementById("fEmision").value.toString() == "")
            	{
                	//En caso de no ocurrir un error de validación, se asigna el valor de paso.
                	error = error+1;			
                	}

            if(document.getElementById("Observaciones").value.toString() == "")
        		{
            		//En caso de no ocurrir un error de validación, se asigna el valor de paso.
            		error = error+1;			
            		}

            if(document.getElementById("Tipo").value.toString() == "Seleccione")
    			{
        			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
        			error = error+1;			
        			}
            
            if(document.getElementById("Recomendaciones").value.toString() == "")
    			{
        			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
        			error = error+1;			
        			}
            
            if(document.getElementById("idFicha").value.toString() == "-1")
    			{
        			//En caso de no ocurrir un error de validación, se asigna el valor de paso.
        			error = error+1;			            	
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
    
    function habNoCoformidad()
		{
			/*
			 * Esta función habilita los controles del formulario de cedulas.
			 */
    		document.getElementById('idFicha').disabled = false;
    		document.getElementById('Auditor').disabled = false;
    		document.getElementById('Tipo').disabled = false;
			document.getElementById('Observaciones').disabled = false;
			document.getElementById('Recomendaciones').disabled = false;			
			document.getElementById('noc_Guardar').style.display="block";
			document.getElementById('noc_Borrar').style.display="none";
			document.getElementById('noc_Editar').style.display="none";
			}

    $(document).ready(function() {
        $("div").click(function(e){
        	e.stopPropagation();
        	if(e.target.id == "verArchivosNOC")
        		{
        			//Se confirma la carga de actualizacion sobre archivos adjuntos.
        			var w = 200;
        			var h = 100;
        			
        			var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        			var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
        			
        		    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        		    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        		    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        		    var top = ((height / 2) - (h / 2)) + dualScreenTop;
        		    
        			window.open('./php/frontend/main/subirArchivos.php?rutaadjuntos=docNOC_'+document.getElementById('idNoConformidad').value.toString()+'_'+document.getElementById('idFicha').value.toString()+'_'+document.getElementById('fEmision').value.toString().replace(/-/g,'_'), "Subir Archivos", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, toolbar=yes, tittlebar=no, width="+width.toString()+", height="+height.toString()+", top="+top.toString()+", left="+left.toString());    			
        			}
        });                 
    });
    
//DECLARACION DE FUNCIONES A EJECUTARSE SOBRE FORMULARIO DE CATALOGO.    
/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id.substring(0,10) == "noc_buscar")
        				{
        					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
        					document.getElementById('pgtipo').value = document.getElementById('bustipo').value.toString();
        					document.getElementById('pgfecha').value = document.getElementById('busfecha').value.toString();
        					document.getElementById('pgidproceso').value = document.getElementById('busidproceso').value.toString();
        					cargar('./php/frontend/noConformidades/catNoConformidad.php','?bustipo='+document.getElementById('bustipo').value.toString()+'&busfecha='+document.getElementById('busfecha').value.toString()+'&busidproceso='+document.getElementById('busidproceso').value.toString(),'busRes');
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
        			if(e.target.id.substring(0,10) == "noc_delete")
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
    				            					cargar('./php/backend/dal/noConformidades/dalNoConformidades.class.php','?id='+e.target.id.substring(11)+'&accion=EdRS','sandbox');
    				            					}			            					
    				            			}
    			            		});
        					}
        			});                 
    		});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id add_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
    $(document).ready(function()
    	{
        	$("div").click(function(e)
        		{
        			e.stopPropagation();
        			if(e.target.id.substring(0,7) == "noc_add")
        				{
        					//En caso de coincidir el id con la accion agregar.
        					cargar('./php/frontend/noConformidades/opNoConformidad.php','?id=-1&view=0','sandbox');
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
        			if(e.target.id.substring(0,14) == "noc_visualizar")
        				{
        					//En caso de coincidir el id con la accion visualizar.
        					cargar('./php/frontend/noConformidades/opNoConformidad.php','?id='+e.target.id.substring(15)+'&view=1','sandbox');
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
        			if(e.target.id.substring(0,8) == "noc_edit")
        				{
        					//En caso de coincidir el id con la accion editar.
        					cargar('./php/frontend/noConformidades/opNoConformidad.php','?id='+e.target.id.substring(9)+'&view=0','sandbox');
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
    				if(e.target.id == "noc_Previous_10")
    					{
    						//En caso de coincidir con el control de retroceso.
    						if((document.getElementById('pagina').value-1)!=0)
    							{
    								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
    								}							
    						cargar('./php/frontend/noConformidades/catNoConformidad.php','?bustipo='+document.getElementById('pgtipo').value.toString()+'&busfecha='+document.getElementById('pgfecha').value.toString()+'&busidproceso='+document.getElementById('pgidproceso').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    				if(e.target.id == "noc_Next_10")
    					{
    						//En caso de coincidir con el control de avance.
    						document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
    						cargar('./php/frontend/noConformidades/catNoConformidad.php','?bustipo='+document.getElementById('pgtipo').value.toString()+'&busfecha='+document.getElementById('pgfecha').value.toString()+'&busidproceso='+document.getElementById('pgidproceso').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
    		        if(e.target.id == "noc_Volver")
    		        	{
    		            	//En caso de coincidir el id con la accion volver.
    		            	cargar('./php/frontend/noConformidades/busNoConformidad.php','','sandbox');
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
    			    if(e.target.id == "noc_Borrar")
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
    				            					cargar('./php/backend/dal/noConformidades/dalNoConformidades.class.php','?id='+document.getElementById('idNoConformidad').value.toString()+'&accion=EdRS','sandbox');
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
    				if(e.target.id == "noc_Guardar")
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
    				            					guardaNoConformidad('./php/backend/dal/noConformidades/dalNoConformidades.class.php','?id='+document.getElementById('idNoConformidad').value.toString()+'&idficha='+document.getElementById('idFicha').value.toString()+'&femision='+document.getElementById('fEmision').value.toString()+'&auditor='+document.getElementById('Auditor').value.toString()+'&tipo='+document.getElementById('Tipo').value.toString()+'&observaciones='+document.getElementById('Observaciones').value.toString()+'&recomendaciones='+document.getElementById('Recomendaciones').value.toString()+'&status='+document.getElementById('Status').value.toString()+'&accion=CoER');
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
    				if(e.target.id == "noc_Editar")
    					{
    				     	//En caso de coincidir el id con la accion edicion.
    				        habNoCoformidad();
    						}
    				});                 
			});