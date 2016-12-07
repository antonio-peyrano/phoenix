/*
 * Este archivo de script contiene las instrucciones y procedimiento para ejecución en cliente
 * sobre la interfaz del registro de recuperación de password por medio de solicitud.
 */

    function enviarRecordClave(url,parametro)
        {
	       /*
	        * Esta función valida que el correo sea valido para su envio.
	        */
            var error=0;
            
            if(document.getElementById("Correo").value.toString()=="")
                {
                    error+=1;
                    }
                    
                                        	
            if(error==0)
                {
                    /*
                     * En caso de obtenerse una transacción valida.
                     */
                     cargar(url,parametro,'infoRecordatorio');
                    }
	
            if(error>=1)
                {
		          /*
		           * En caso de ocurrir un error de validación, se notifica al usuario.
		           */
		          alert("Existen campos pendientes por completar");
		          }
        }