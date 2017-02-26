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

    function tablaMensaje()
        {
            $tablaCuerpo='  <br><br><br>
                            <table class="dgTable">
                                <tr class="dgHeader" align= "center"><th>Error de Procesamiento de Datos</th></tr>
                                <tr class="dgRowsnormTR" align= "center"><td><b>Ocurrio un error de ejecucion dentro del sistema.<br>Contacte al administrador.<br><br>
                                                            <img align= "center" src= "./img/errorsys.png" width= "150" height= "150" alt= "ErrorSys" id= "imgError"/>
                                                         </td>
                                </tr>                
                            </table>';
            return $tablaCuerpo;
            }
        
    function constructor()
        {
            $bodyString='   <html>
                                <head>
                                    <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                                </head>
                                <body>'.
                                tablaMensaje()
                                .'</body>
                            </html>';
            return $bodyString;
            }
            
    echo constructor(); //Llamada a la funcion de creacion de interfaz.            
?>