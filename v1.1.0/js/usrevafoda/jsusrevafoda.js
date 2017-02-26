/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id Escala_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").change(function(e){
    	e.stopPropagation();      	    	
    	if(e.target.id.substring(0,7) == "Escala_")
    		{
    			var radio = $('.radio');
    			//En caso de coincidir el id con la accion visualizar.    		    			
    			if(document.getElementById("Res_"+e.target.id.substring(7)).value.toString() == "NP")    							
    				{    							
    					document.getElementById("Contestados").value = parseInt(document.getElementById("Contestados").value.toString()) + 1;
    					for (var x=0; x < radio.length; x++)
    						{
    							if((radio[x].id == e.target.id)&&(radio[x].checked))
    								{
    									document.getElementById("Res_"+e.target.id.substring(7)).value=radio[x].value;
    									}
    							}    					
    					}
    			else
    				{
						for (var x=0; x < radio.length; x++)
							{
								if((radio[x].id == e.target.id)&&(radio[x].checked))
									{
										document.getElementById("Res_"+e.target.id.substring(7)).value=radio[x].value;
										}
								}
    					}    							

    			}
    });                 
});

/*
 * Este segmento de codigo efectua el respaldo de los avances sobre la evaluacion contestada por el usuario.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();      	
    	if(e.target.id == "guardarEncuesta")
    		{
    			var txtfactores = $('.txtfactor');
    			var txtescalas = $('.txtescala');
    			var radio = $('.radio');
    			
    			var idfactores = "-1";
    			var idescalas = "-1";
    			
    			for(var x=0; x < txtfactores.length; x++)
    				{
    					if(document.getElementById("Res_"+(x+1)).value.toString()!="NP")
    						{
        						idfactores+='%'+txtfactores[x].value.toString();    						
    							}
    					}

    			for(var x=0; x < txtescalas.length; x++)
					{
						if(radio[x].checked)
							{
								idescalas+='%'+txtescalas[x].value.toString();
								}
						}
    			if(document.getElementById('Reactivos').value.toString()==document.getElementById('Contestados').value.toString())
    				{
    					cargar('./php/backend/foda/usrevafoda/Guardar.php','?idevaluacion='+document.getElementById('idEvaluacion').value.toString()+'&idfactor='+idfactores+'&idescala='+idescalas+'&status='+document.getElementById('Status').value.toString(),'escritorio');
    					}
    			else
    				{
    					cargar('./php/backend/foda/usrevafoda/quickGuardar.php','?idevaluacion='+document.getElementById('idEvaluacion').value.toString()+'&idfactor='+idfactores+'&idescala='+idescalas+'&status='+document.getElementById('Status').value.toString(),'escritorio');
    					}    			
    			}
    });                 
}); 

/*
 * Este segmento de codigo efectua el almacenamiento de la evaluacion contestada por el usuario.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();      	
    	if(e.target.id == "enviarEncuesta")
    		{
    			var txtfactores = $('.txtfactor');
    			var txtescalas = $('.txtescala');
    			var radio = $('.radio');
    			
    			var idfactores = "-1";
    			var idescalas = "-1";
    			
    			for(var x=0; x < txtfactores.length; x++)
    				{
    					idfactores+='%'+txtfactores[x].value.toString();
    					}

    			for(var x=0; x < txtescalas.length; x++)
					{
						if(radio[x].checked)
							{
								idescalas+='%'+txtescalas[x].value.toString();
								}
						}
    			if(document.getElementById('Reactivos').value.toString()==document.getElementById('Contestados').value.toString())
    				{
    					cargar('./php/backend/foda/usrevafoda/Guardar.php','?idevaluacion='+document.getElementById('idEvaluacion').value.toString()+'&idfactor='+idfactores+'&idescala='+idescalas+'&status='+document.getElementById('Status').value.toString(),'escritorio');
    					}
    			else
    				{
    					alert("Aun no se ha concluido con todos los reactivos de la evaluacion");
    					}    			
    			}
    });                 
}); 