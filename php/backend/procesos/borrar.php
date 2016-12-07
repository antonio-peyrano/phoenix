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

    /*
     * Este modulo sirve como pasarela de ejecución del comando eliminar, cuando es ejecutado desde un formulario
     * para la edición de registro.
     */
    $parametro = $_GET['id'];
    $listado = '0';
    
    if(isset($_GET['listado']))
        {
            /*
             * Si la solicitud ha sido enviada desde el listado de relaciones.
             */
            $listado = $_GET['listado'];
            }
                
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

    if($listado == '0')
        {
            /*
             * En caso que el origen no proceda del listado, se procede a borrar el puesto completo.
             */
            $consulta= 'UPDATE catProcesos SET Status=1 where idProceso='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    
            /*
             * Con las relaciones de las entidades.
             */
            $consulta= 'UPDATE relEntPro SET Status=1 where idProceso='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            }
    else
        {
            /*
             * En caso que el origen proceda del listado, se procede a borrar la relación seleccionada.
             */
            $consulta= 'UPDATE relEntPro SET Status=1 where idRelEntPro='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            }
                
    include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/procesos/busProcesos.php");
    ?>