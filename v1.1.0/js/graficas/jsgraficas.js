/**
 * Este archivo de script contiene los comandos de ejecución para la interfaz de gestión del
 * graficas en el sistema.
 */

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").click(function(e){
    	e.stopPropagation();
    	if(e.target.id == "btnConsultar")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    			if(document.getElementById("critEntidad").checked)
    				{
    					cargarSync('./php/frontend/utilidades/grafica_barras.php','?periodo='+document.getElementById('Periodo').value.toString()+'&identidad='+document.getElementById('gridEntidad').value.toString(),'grafica_barras');
    					}
    			if(document.getElementById("critProceso").checked)
    				{
    					cargarSync('./php/frontend/utilidades/grafica_barras.php','?periodo='+document.getElementById('Periodo').value.toString()+'&idproceso='+document.getElementById('gridProceso').value.toString(),'grafica_barras');
    					}
    			}
    });                 
});

/*
 * El presente segmento de codigo evalua la accion de click sobre cualquier elemento con el id buscar_#
 * para ejecutar la acción de actualización sobre el registro de una rejilla de datos.
 */
$(document).ready(function() {
    $("div").change(function(e){
    	e.stopPropagation();
    	if(e.target.name == "criterios")
    		{
    			//Si el usuario confirma su solicitud de borrar el registro seleccionado.
    			if(e.target.value=="Entidad")
    				{
    					cargar('./php/frontend/utilidades/comp/cbEntidades.php','','tagCriterio');
    					}
    			if(e.target.value=="Proceso")
    				{
    					cargar('./php/frontend/utilidades/comp/cbProcesos.php','','tagCriterio');
    					}
    			}
    });                 
});