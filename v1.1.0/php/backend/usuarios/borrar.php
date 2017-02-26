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
    $parametro = $_GET['id'];
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    
    global $username, $password, $servername, $dbname;
    
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    $consulta= 'UPDATE catUsuarios SET Status=1 where idUsuario='.$parametro; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
        
    include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/usuarios/busUsuarios.php");
    ?>