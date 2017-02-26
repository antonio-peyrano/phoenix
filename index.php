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
     *Este archivo contiene la declaraci�n de procedimientos para la creaci�n de la pagina INDEX.
     */
    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a UTF-8.     
    include_once("./php/backend/bl/main/index.class.php"); //Se carga el archivo de clase.
    $index = new index(); //Se crea la instancia de objeto index.
    echo $index->drawUI(); //Se ejecuta la funci�n para visualizar la UI.
?>