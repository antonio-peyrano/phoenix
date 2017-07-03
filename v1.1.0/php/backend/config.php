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
     */

    $servername="397Y09vK3uXj";
    $dbname="4NjY5N7V2ObY4g==";
    $username="5d7k5g==";
    $password="1OXW3t7Qpqmm";
    $SitioWeb="http://localhost:8081/micrositio/index.php";
    /*
     * Para ejecucion en sitio quite las acotaciones de comentario.
     */
     
    /*
    $servername='5uDhpJ+andTo2OPd4eLWpKDS4tw=';
    $dbname='1aTUo6WaoaejqaDU39jF4eHi3OPe4Q==';
    $username='1aTUo6WaoaejqaA=';
    $password='1OXW3t7Qpqmm';
    $SitioWeb="http://peycom.byethost5.com/index.php";
    */
        
?>