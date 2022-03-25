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
     * Este es el archivo de configuraci�n principal del sistema, debe cargarse en cada modulo que requiera del uso
     * de las constantes predefinidas de ejecuci�n.
    */
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.
    
    /*
     * Para ejecuci�n en local quite las acotaciones de comentario.
     * Las cadenas asignadas a las variables deben estar encriptadas
     */

    $servername="";
    $dbname="";
    $username="";
    $password="";
    $SitioWeb="";
        
?>
