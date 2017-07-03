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
     * Este archivo contiene el constructor para el combobox de objetivos operativos a visualizar.
     */
    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.

    $idVehiculo = $_GET['id'];
    
    global $habcampos, $RegNiveles, $Registro;
    
    function cargarIDProgGas($parametro)
        {
            /*
             * Esta funci�n establece la carga del conjunto de registros de id.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idProgGas FROM catVehiculos INNER JOIN opProgGas ON catVehiculos.idEntidad = opProgGas.idEntidad WHERE catVehiculos.Status=0 AND idVehiculo='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }

    function cargarIDEjecGas($parametro)
        {
            /*
             * Esta funci�n establece la carga del conjunto de registros de id.
             */
             global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEjecGas FROM catVehiculos INNER JOIN opEjecGas ON catVehiculos.idEntidad = opEjecGas.idEntidad WHERE catVehiculos.Status=0 AND idVehiculo='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
                        
    $subconsulta = cargarIDProgGas($idVehiculo);    
    $RegProgGas = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);

    $subconsulta = cargarIDEjecGas($idVehiculo);
    $RegEjecGas = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
        
    echo '  <input type= "text" id= "idProgGas" value= "'.$RegProgGas['idProgGas'].'"><br>
            <input type= "text" id= "idEjecGas" value= "'.$RegEjecGas['idEjecGas'].'"><br>';       
?>