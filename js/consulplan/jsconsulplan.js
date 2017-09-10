/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión de
 * consulta sobre la planeación.
 */


/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id visualizar_#
 * para ejecutar la acción de visualización de los registros correspondientes a los Obj Ope.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,14) == "coe_visualizar")
    		{
    			//Se efectua el direccionamiento a la pagina de consulta de Obj. Ope., bajo el ID del ObjEst seleccionado.  
    			cargar('./php/frontend/consulplan/objope/conObjOpe.php','?idobjest='+e.target.id.substring(15)+'&view=1','sandbox');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id visualizar_#
 * para ejecutar la acción de visualización de los registros correspondientes a las Est Ope.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,14) == "coo_visualizar")
    		{
    			//Se efectua el direccionamiento a la pagina de consulta de Est. Ope., bajo el ID del ObjOpe seleccionado.  
    			cargar('./php/frontend/consulplan/estope/conEstOpe.php','?idobjope='+e.target.id.substring(15)+'&idobjest='+document.getElementById('idObjEst').value.toString()+'&view=1','sandbox');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id visualizar_#
 * para ejecutar la acción de visualización de los registros correspondientes a los programas.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,14) == "ceo_visualizar")
    		{
    			//Se efectua el direccionamiento a la pagina de consulta de Est. Ope., bajo el ID de la EstOpe seleccionada.  
    			cargar('./php/frontend/consulplan/programas/conPrograma.php','?idestope='+e.target.id.substring(15)+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idobjest='+document.getElementById('idObjEst').value.toString()+'&view=1','sandbox');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id visualizar_#
 * para ejecutar la acción de visualización de los registros correspondientes a las actividades.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,14) == "cpr_visualizar")
    		{
    			//Se efectua el direccionamiento a la pagina de consulta de Actividades, bajo el ID del Programa seleccionado.  
    			cargar('./php/frontend/consulplan/actividades/conActividad.php','?idprograma='+e.target.id.substring(15)+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idobjest='+document.getElementById('idObjEst').value.toString()+'&view=1','sandbox');
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id visualizar_#
 * para ejecutar la acción de visualización de los registros correspondientes a las ejecuciones.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id.substring(0,14) == "cac_visualizar")
    		{
    			//Se efectua el direccionamiento a la pagina de consulta de Ejecuciones, bajo el ID de la Actividad seleccionado.  
    			cargar('./php/frontend/consulplan/ejecuciones/conEjecuciones.php','?idactividad='+e.target.id.substring(15)+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idobjest='+document.getElementById('idObjEst').value.toString()+'&view=1','sandbox');
    			}
    });                 
});


/*
 * Codigo de control para la visualizacion de datos en los grids mediante paginacion.
 */

/*
 * El presente segmento de codigo evalua la accion de click sobre el elemento de retroceso de pagina
 * sobre el grid de datos.
 */
	$(document).ready(function()
		{
			$("div").click(function(e)
				{
					e.stopPropagation();
					if(e.target.id == "coo_Previous_10")
						{
							//En caso de coincidir con el control de retroceso.
							if((document.getElementById('pagina').value-1)!=0)
								{
									document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
									}							
							cargar('./php/frontend/consulplan/objope/lstObjOpe.php','?idobjest='+document.getElementById('idObjEst').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstObjOpe');
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
					if(e.target.id == "coo_Next_10")
						{
							//En caso de coincidir con el control de avance.
							document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
							cargar('./php/frontend/consulplan/objope/lstObjOpe.php','?idobjest='+document.getElementById('idObjEst').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstObjOpe');
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
						if(e.target.id == "ceo_Previous_10")
							{
								//En caso de coincidir con el control de retroceso.
								if((document.getElementById('pagina').value-1)!=0)
									{
										document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
										}							
								cargar('./php/frontend/consulplan/estope/lstEstOpe.php','?idobjope='+document.getElementById('idObjOpe').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstEstOpe');
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
						if(e.target.id == "ceo_Next_10")
							{
								//En caso de coincidir con el control de avance.
								document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
								cargar('./php/frontend/consulplan/estope/lstEstOpe.php','?idobjope='+document.getElementById('idObjOpe').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstEstOpe');
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
								if(e.target.id == "cpr_Previous_10")
									{
										//En caso de coincidir con el control de retroceso.
										if((document.getElementById('pagina').value-1)!=0)
											{
												document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
												}							
										cargar('./php/frontend/consulplan/programas/lstProgramas.php','?idobjest='+document.getElementById('idObjEst').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstProgramas');
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
								if(e.target.id == "cpr_Next_10")
									{
										//En caso de coincidir con el control de avance.
										document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
										cargar('./php/frontend/consulplan/programas/lstProgramas.php','?idobjest='+document.getElementById('idObjEst').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstProgramas');
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
									if(e.target.id == "cac_Previous_10")
										{
											//En caso de coincidir con el control de retroceso.
											if((document.getElementById('pagina').value-1)!=0)
												{
													document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
													}							
											cargar('./php/frontend/consulplan/actividades/lstActividades.php','?idobjest='+document.getElementById('idObjEst').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstActividades');
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
									if(e.target.id == "cac_Next_10")
										{
											//En caso de coincidir con el control de avance.
											document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
											cargar('./php/frontend/consulplan/actividades/lstActividades.php','?idobjest='+document.getElementById('idObjEst').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstActividades');
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
										if(e.target.id == "cej_Previous_10")
											{
												//En caso de coincidir con el control de retroceso.
												if((document.getElementById('pagina').value-1)!=0)
													{
														document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())-1;
														}							
												cargar('./php/frontend/consulplan/ejecuciones/lstEjecuciones.php','?idobjest='+document.getElementById('idObjEst').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&idactividad='+document.getElementById('idActividad').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstEjecuciones');
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
										if(e.target.id == "cej_Next_10")
											{
												//En caso de coincidir con el control de avance.
												document.getElementById('pagina').value = parseInt(document.getElementById('pagina').value.toString())+1;							
												cargar('./php/frontend/consulplan/ejecuciones/lstEjecuciones.php','?idobjest='+document.getElementById('idObjEst').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&idactividad='+document.getElementById('idActividad').value.toString()+'&periodo='+document.getElementById('Periodo').value.toString()+'&pagina='+document.getElementById('pagina').value.toString(),'divLstEjecuciones');
												}
										});                 
								});					