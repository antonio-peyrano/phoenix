/**
 * Este archivo de script contiene los comandos de ejecuci�n para la interfaz de gesti�n del
 * cat�logo de evidencias en el sistema.
 */

function guardarEvidencia(url,parametro){
	/*
	 * Esta funci�n valida que los datos para ser almacenados en el registro sean correctos.
	 */
	var error= 0;
	var ruta= 0;
    
	if(document.getElementById("RutaURL").value.toString() == "")
		{
			//En caso de no ocurrir un error de validaci�n, se asigna el valor de paso.
			error= error+1;
			}
            
    if(document.getElementById("RutaURL").value.toString().indexOf("http://") == -1)
        {
            ruta += 1;
            }
            
    if(document.getElementById("RutaURL").value.toString().indexOf("https://") == -1)
        {
            ruta += 1;
            }
            
    if(document.getElementById("RutaURL").value.toString().indexOf("ftp://") == -1)
        {
            ruta += 1;
            }
            	
	if(error > 0)
		{
			/*
			 * En caso de ocurrir un error de validaci�n, se notifica al usuario.
			 */
        	bootbox.alert("Existen campos pendientes por completar");
			}

	if(ruta == 3)
		{
			/*
			 * En caso de ocurrir un error de validaci�n, se notifica al usuario.
			 */
        	bootbox.alert("No se ha proporcionado una ruta valida para la evidencia");
			}
                        
	if((error == 0)&&(ruta < 3))
		{
			/*
			 * En caso que la validaci�n de campos sea satisfactoria.
			 */
			cargar(url,parametro,'sandbox');
			}
	}
    
/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id delete_#
 * para ejecutar la acci�n de actualizaci�n sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "evi_delete")
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
		            					cargar('./php/backend/evidencias/borrar.php','?id='+e.target.id.substring(11)+'&idejecucion='+document.getElementById('idEjecucion').value.toString()+'&idactividad='+document.getElementById('idActividad').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString(),'sandbox');
		            					}			            					
		            			}
	            		});
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
					if(e.target.id == "evi_Previous_4")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/evidencias/catEvidencias.php','?idejecucion='+document.getElementById('idEjecucion').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'dataevidencias');
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
					if(e.target.id == "evi_Next_4")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/evidencias/catEvidencias.php','?idejecucion='+document.getElementById('idEjecucion').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'dataevidencias');
							}
					});                 
			});