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
    
    echo '  <html>
                <header>
                    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
                    <link rel= "stylesheet" href= "../../../css/menuStyle.css" title="style css" type="text/css" media="screen" charset="utf-8"></style>
                    <link rel= "stylesheet" href= "../../../css/divLogin.css"></style>
                    <link rel= "stylesheet" href= "../../../css/dgstyle.css"></style>
                    <script type="text/javascript" src="../../../js/jquery/jsplug.js"></script>
                    <script type="text/javascript" src="../../../js/correo/jscorreo.js"></script>
                </header>
                <body>
                    <br>
                    <br>
                    <center>
                        <div id= "infoRecordatorio">
                            <table>
                                <tr><th colspan= "2" class= "dgHeader">RECUPERAR CONTRASE�A</th></tr>
                                <tr><td class= "dgRowsaltTR">Correo:</td><td class= "dgRowsnormTR"><input type= "text" id= "Correo" value= ""><a href= "#" data-toggle= "tooltip" title= "Buscar usuario" onclick="cargar(\'../../../php/frontend/usuarios/comp/inputPreg.php\',\'?correo=\'+document.getElementById(\'Correo\').value.toString(),\'divRes\');"><img src= "../../../img/grids/search.png" width= "25" height= "25" alt= "Buscar" id= "btnBuscar"></a></td></tr>
                                <tr><td class= "dgRowsaltTR">Pregunta:</td><td class= "dgRowsnormTR"><div id= "divRes"><input type= "text" id= "Pregunta" value= ""></div></td></tr>
                                <tr><td class= "dgRowsaltTR">Respuesta:</td><td class= "dgRowsnormTR"><input type= "text" id="Respuesta" value= ""></td></tr>
                                <tr><td colspan= "2" class= "dgFooter" align= "right"><a href= "#" data-toggle= "tooltip" title= "Enviar la solicitud de recordatorio" onclick="enviarRecordClave(\'../../../php/frontend/usuarios/opRecordmail.php\',\'?correo=\'+document.getElementById(\'Correo\').value.toString()+\'&pregunta=\'+document.getElementById(\'Pregunta\').value.toString()+\'&respuesta=\'+document.getElementById(\'Respuesta\').value.toString());"><img src= "../../../img/enviar.png" width= "35" height= "35" alt= "Enviar" id= "btnEnviar"></a></td></tr>
                            </table>
                        </div>
                    </center>                        
                </body>
            </html>';
?>