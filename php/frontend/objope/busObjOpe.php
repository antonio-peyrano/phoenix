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
    
    $sufijo= "oop_";
    
    echo '  <html>
                <link rel= "stylesheet" href= "./css/queryStyle.css"></style>        
                <center><div id= "divbusqueda">
                    <form id="frmbusqueda" method="post" action="">
                        <table class="queryTable" colspan= "3">
                            <tr><td class= "queryRowsnormTR" width ="180">Por objetivo operativo: </td><td class= "queryRowsnormTR" width ="250"><input type= "text" id= "nomobjope"></td><td rowspan= "3"><img id="'.$sufijo.'buscar" align= "left" src= "./img/grids/view.png" width= "25" height= "25" alt="Buscar"/></td></tr>
                            <tr><td class= "queryRowsnormTR">Por nomenclatura: </td><td class= "queryRowsnormTR"><input type= "text" id= "objopenomenclatura"></td><td></td></tr>
                            <tr><td class= "queryRowsnormTR">Por periodo: </td><td class= "queryRowsnormTR"><input type= "text" id= "objopeperiodo"></td><td></td></tr>
                        </table>
                    </form>
                </div></center>';
    
    echo '<div id= "busRes">';
        include_once("catObjOpe.php");
    echo '</div>
          </html>';
    
?>