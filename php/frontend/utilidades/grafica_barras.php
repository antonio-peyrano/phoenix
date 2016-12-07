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

    $idEntidad = '';
    $idProceso = '';
    
    if(isset($_GET['periodo']))
        {
            $Periodo = $_GET['periodo'];
            }

    if(isset($_GET['identidad']))
        {
            $idEntidad = $_GET['identidad'];
            }

    if(isset($_GET['idproceso']))
        {
            $idProceso = $_GET['idproceso'];
            }

    if($idEntidad != '')
        {
            echo '<img src="./php/backend/utilidades/graficadora.php\?identidad='.$idEntidad.'&periodo='.$Periodo.'"/>';
            }

    if($idProceso != '')
        {
            echo '<img src="./php/backend/utilidades/graficadora.php\?idproceso='.$idProceso.'&periodo='.$Periodo.'"/>';
            }            
?>