/*
 * Este archivo de script contiene las instrucciones y procedimiento para ejecución en cliente
 * sobre la interfaz del registro de usuarios en sistema mediante solicitud.
 */

    function enviarRegistro(url,parametro)
        {
	       /*
	        * Esta función valida que los datos para ser almacenados en el registro sean correctos.
	        */
            var error=0;
            var errpsw=0;
            
            if(document.getElementById("Usuario").value.toString()=="")
                {
                    error+=1;
                    }
                    
            if(document.getElementById("Clave").value.toString()=="")
                {
                    error+=1;
                    }
                    
            if(document.getElementById("Correo").value.toString()=="")
                {
                    error+=1;
                    }
                    
            if(document.getElementById("Pregunta").value.toString()=="Seleccione")
                {
                    error+=1;
                    }
                    
            if(document.getElementById("Respuesta").value.toString()=="")
                {
                    error+=1;
					}
                    	
            if(document.getElementById("Clave").value.toString()!=document.getElementById("ReClave").value.toString())
                {
                    errpsw=1;
                    error+=1;
                    }
                    
            if(document.getElementById("idNivel").value.toString()=="-1")
                {
                    error+=1;
                    }                    
                                        	
            if(error==0)
                {
                    /*
                     * En caso de obtenerse una transacción valida.
                     */
                    cargar(url,parametro,'infoSolicitud');
                    }
	
            if(error>=1)
                {
		          /*
		           * En caso de ocurrir un error de validación, se notifica al usuario.
		           */
		          alert("Existen campos pendientes por completar");
		          }
	
	       if(errpsw==1)
                {
                    /*
                     * En caso que las claves proporcionadas no sean iguales.
                     */		
                    alert("Las claves proporcionadas no coinciden");
                    }
        }

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id delete_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,10) == "sau_delete")
    		{
    			//En caso de coincidir el id con la accion delete.
    			var respuesta;
    			respuesta = confirm("¿Esta seguro que desea eliminar el registro seleccionado?");
    			if(respuesta)
    				{
    					//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    					cargar('./php/backend/usraltas/borrar.php','?id='+e.target.id.substring(11),'escritorio');
    					} 		
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
    	if(e.target.id.substring(0,14) == "sau_visualizar")
    		{
    			//En caso de coincidir el id con la accion visualizar.
    			cargar('./php/frontend/usraltas/opSolAltUsr.php','?id='+e.target.id.substring(15)+'&admin=1&view=1','escritorio');
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
    	if(e.target.id.substring(0,10) == "sau_buscar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
				document.getElementById('pgusuario').value = document.getElementById('nomusuario').value.toString();
				document.getElementById('pgcorrusuario').value = document.getElementById('corrusuario').value.toString();    			
    			cargar('./php/frontend/usraltas/catSolAltUsr.php','?usrname='+document.getElementById('nomusuario').value.toString()+'&corusr='+document.getElementById('corrusuario').value.toString(),'busRes');
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
					if(e.target.id == "sau_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/usraltas/catSolAltUsr.php','?usrname='+document.getElementById('pgusuario').value.toString()+'&corusr='+document.getElementById('pgcorrusuario').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
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
					if(e.target.id == "sau_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/usraltas/catSolAltUsr.php','?usrname='+document.getElementById('pgusuario').value.toString()+'&corusr='+document.getElementById('pgcorrusuario').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'busRes');
							}
					});                 
			});