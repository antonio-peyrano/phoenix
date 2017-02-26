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

    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1. 
    
    $sufijo= "col_";
    
    echo '  <html>
                <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                <div id="paginado" style="display:none">
                    <input id="pagina" type="text" value="1">
                    <input id="pgcolonia" type="text" value="">
                    <input id="pgestado" type="text" value="">
                    <input id="pgciudad" type="text" value="">
                    <input id="pgcp" type="text" value="">
                </div>        
                <center><div id= "divbusqueda">
                    <form id="frmbusqueda" method="post" action="">
                        <table class="queryTable" colspan= "7">
                            <tr><td class= "queryRowsnormTR" width ="180">Por nombre de la colonia: </td><td class= "queryRowsnormTR" width ="250"><input type= "text" id= "nomcolonia"></td><td rowspan= "4"><img id="'.$sufijo.'buscar" align= "left" src= "./img/grids/view.png" width= "25" height= "25" alt="Buscar"/></td></tr>
                            <tr><td class= "queryRowsnormTR">Por codigo postal: </td><td class= "queryRowsnormTR"><input type= "text" id= "cpcolonia"></td><td></td></tr>
                            <tr><td class= "queryRowsnormTR">Por ciudad: </td><td class= "queryRowsnormTR"><input type= "text" id= "ciucolonia"></td><td></td></tr>
                            <tr><td class= "queryRowsnormTR">Por estado: </td><td class= "queryRowsnormTR"><input type= "text" id= "estcolonia"></td><td></td></tr>
                        </table>
                    </form>
                </div></center>';
    
    echo '<div id= "busRes">';
        include_once("catColonias.php");
    echo '</div>
          </html>';
    
?>