<?php
/*
 * Micrositio-Phoenix v1.0 Software para gestion de la planeación operativa.
 * PHP v5
 * Autor: Prof. Jesus Antonio Peyrano Luna <antonio.peyrano@live.com.mx>
 * Nota aclaratoria: Este programa se distribuye bajo los terminos y disposiciones
 * definidos en la GPL v3.0, debidamente incluidos en el repositorio original.
 * Cualquier copia y/o redistribucion del presente, debe hacerse con una copia
 * adjunta de la licencia en todo momento.
 * Licencia: http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

    header('Content-Type: text/html; charset=UTF-8'); //Forzar la codificación a UTF-8.
        
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
 
    $sufijo= "ind_";
    
    function cargarProcesos()
        {
            /*
             * Esta función establece la carga del conjunto de registros de procesos.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idProceso, Proceso FROM catProcesos WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
        
    echo '  <html>
                <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                <div id="paginado" style="display:none">
                    <input id="pagina" type="text" value="1">
                    <input id="pgindicador" type="text" value="">
                    <input id="pgnomenclatura" type="text" value="">
                    <input id="pgpercentil" type="text" value="">
                    <input id="pgidproceso" type="text" value="">
                </div>                
                <center><div id= "divbusqueda">
                    <form id="frmbusqueda" method="post" action="">
                        <table class="queryTable" colspan= "7">
                            <tr><td class= "queryRowsnormTR" width ="180">Por indicador: </td><td class= "queryRowsnormTR" width ="250"><input type= "text" id= "nomindicador"></td><td rowspan= "3"><img id="'.$sufijo.'buscar" align= "left" src= "./img/grids/view.png" width= "25" height= "25" alt="Buscar"/></td></tr>
                            <tr><td class= "queryRowsnormTR">Por nomenclatura: </td><td class= "queryRowsnormTR"><input type= "text" id= "indnomenclatura"></td><td></td></tr>
                            <tr><td class= "queryRowsnormTR">Por percentil: </td><td class= "queryRowsnormTR"><input type= "text" id= "indpercentil"></td><td></td></tr>
                            <tr><td class= "queryRowsnormTR">Por proceso: </td><td class= "queryRowsnormTR"><select name= "nomidproceso" id= "nomidproceso" value= "-1">';
                            $subconsulta = cargarProcesos();
    echo '                  <option value=-1>Seleccione</option>';
    
                            $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                            
                            while($RegNiveles)
                                {
    echo '                              <option value='.$RegNiveles['idProceso'].'>'.$RegNiveles['Proceso'].'</option>';
                                        $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                    }
                                    
    echo'               </select></td></tr>
                        </table>
                    </form>
                </div></center>';
    
    echo '<div id= "busRes">';
        include_once("catIndicadores.php");
    echo '</div>
          </html>';
    
?>