/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de filtrado
 * para el despliegue de resultados sobre una evaluacion FODA.
 */
	$(document).ready(function()
		{
			$("div").change(function(e)
				{    	
					e.stopPropagation();
					if(e.target.id == "rsf_idEntidad")
						{
							//Se confirma la carga de actualizacion sobre combobox de folios de cedulas.
							cargar('./php/frontend/foda/resfoda/comp/cbCedulas.php','?identidad='+document.getElementById('rsf_idEntidad').value.toString(),'divCedulas');
							}
					});                 
			});

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de filtrado
 * para el despliegue de resultados sobre una evaluacion FODA.
 */
	$(document).ready(function()
		{
			$("div").click(function(e)
				{    	
					e.stopPropagation();
					if(e.target.id == "frf_filtro")
						{
							//Se confirma la carga de actualizacion sobre combobox de folios de cedulas.
							cargar('./php/frontend/foda/resfoda/comp/tableFODA.php','?identidad='+document.getElementById('rsf_idEntidad').value.toString()+'&idcedula='+document.getElementById('rsf_idCedula').value.toString(),'divResFODA');
							}
					});                 
			});