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
    			cargar('./php/frontend/consulplan/conObjOpe.php','?idobjest='+e.target.id.substring(15)+'&view=1','escritorio');
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
    			cargar('./php/frontend/consulplan/conEstOpe.php','?idobjope='+e.target.id.substring(15)+'&idobjest='+document.getElementById('idObjEst').value.toString()+'&view=1','escritorio');
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
    			cargar('./php/frontend/consulplan/conPrograma.php','?idestope='+e.target.id.substring(15)+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idobjest='+document.getElementById('idObjEst').value.toString()+'&view=1','escritorio');
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
    			cargar('./php/frontend/consulplan/conActividad.php','?idprograma='+e.target.id.substring(15)+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idobjest='+document.getElementById('idObjEst').value.toString()+'&view=1','escritorio');
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
    			cargar('./php/frontend/consulplan/conEjecuciones.php','?idactividad='+e.target.id.substring(15)+'&idprograma='+document.getElementById('idPrograma').value.toString()+'&idestope='+document.getElementById('idEstOpe').value.toString()+'&idobjope='+document.getElementById('idObjOpe').value.toString()+'&idobjest='+document.getElementById('idObjEst').value.toString()+'&view=1','escritorio');
    			}
    });                 
});