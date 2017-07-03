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
     * Este modulo sirve como pasarela de ejecuci�n del comando eliminar, cuando es ejecutado desde un formulario
     * para la edici�n de registro.
     */
    $idProgGas = $_GET['id'];
    $idEntidad = $_GET['identidad'];
    $idEjecGas = 0;
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
        
    //Se eliminan las referencia de la programaci�n de gasto.
    $consulta= 'UPDATE opProgGas SET Status=1 where idProgGas='.$idProgGas; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.

    //Se procede a obtener el ID del registro de ejecucion de gastos.
    $consulta = 'SELECT idEjecGas FROM opEjecGas WHERE idEntidad='.$idEntidad.' AND Status=0';
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
    $idEjecGas = $Registro['idEjecGas'];

    //Se eliminan las referencias de consumos de gasolina.
    $consulta= 'UPDATE opMovGas SET Status=1 where idEjecGas='.$idEjecGas; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.

    //Se elimina la referencia de ejecuci�n.
    $consulta= 'UPDATE opEjecGas SET Status=1 where idEntidad='.$idEntidad; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.

    //Se elimina la referencia de eficacia de consumo.
    $consulta= 'UPDATE opEficGas SET Status=1 where idEntidad='.$idEntidad; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                   
    include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/gasolina/busGasolina.php");
    ?>