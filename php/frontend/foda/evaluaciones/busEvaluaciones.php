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
    
    $sufijo= "fev_";

    function cargarEntidades()
        {
            /*
             * Esta función establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;            
            }
                        
    echo '  <html>
                <link rel= "stylesheet" href= "./css/queryStyle.css"></style>        
                <center><div id= "divbusqueda">
                    <form id="frmbusqueda" method="post" action="">
                        <table class="queryTable" colspan= "7">
                            <tr><td class= "queryRowsnormTR" width ="180">Por folio: </td><td class= "queryRowsnormTR" width ="250"><input type= "text" id= "fevfolio"></td><td rowspan= "2"><img id="'.$sufijo.'buscar" align= "left" src= "./img/grids/view.png" width= "25" height= "25" alt="Buscar"/></td></tr>
                            <tr><td class= "queryRowsnormTR">Por fecha: </td><td class= "queryRowsnormTR"><input type= "text" id= "fevfecha"></td></tr>
                            <tr><td class= "queryRowsnormTR">Por Entidad: </td>
                                <td class= "queryRowsnormTR">
                                    <select name= "fevidentidad" id= "fevidentidad" value= "-1">';
    
                            $subconsulta = cargarEntidades();
                            
    echo '                              <option value=-1>Seleccione</option>
                                        <option value=-2>Global</option>';
    
                            $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                            
                            while ($RegNiveles)
                                {
    echo '                              <option value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'].'</option>';
                                        $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                    }
                                    
    echo'                           </select>
                                </td>
                            </tr>
                            <tr><td class= "queryRowsnormTR">Por Empleado: </td>
                                <td class= "queryRowsnormTR">
                                    <div id="cbEmpleados">
                                        <select name= "fevempleado" id= "fevempleado" value= "-1">
                                            <option value=-1>Seleccione</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>                                
                        </table>
                    </form>
                </div></center>';
    
    echo '<div id= "busRes">';
        include_once("catEvaluaciones.php");
    echo '</div>
          </html>';
    
?>