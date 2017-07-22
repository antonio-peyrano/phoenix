<?php
/*
 * Micrositio-Phoenix v1.0.0 Software base para desarrollo de sistemas.
 * PHP v5
 * Autor: Prof. Jesus Antonio Peyrano Luna <antonio.peyrano@live.com.mx>
 * Nota aclaratoria: Este programa se distribuye bajo los terminos y disposiciones
 * definidos en la GPL v3.0, debidamente incluidos en el repositorio original.
 * Cualquier copia y/o redistribucion del presente, debe hacerse con una copia
 * adjunta de la licencia en todo momento.
 * Licencia: http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/bl/main/subirArchivos.class.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php");
    
    function headHTML()
        {
            global $SitioWeb;
            
            $HEAD = '   <head>
                            <link rel="stylesheet" href="'.$SitioWeb.'css/uploadfile.css"></style>
                            <script type="text/javascript" src="'.$SitioWeb.'js/main/jsuploadfile.js"></script>
                        </head>';
            return $HEAD;
            }
            
    function drawUI()
        {
            global $SitioWeb;
            
            if(isset($_GET["rutaadjuntos"]))
                {
                    //Si el usuario ha especificado la ruta disponible por medio
                    //de la navegacion en el sistema
                    $sa = new subirArchivos($_GET["rutaadjuntos"]);
                    }
            else
                {
                    //En caso que el usuario no establesca la referencia de la ruta
                    //por medio de la navegacion.
                    $sa = new subirArchivos("");
                    }
                    
            $HTMLBODY = '
                            <body>
                                    <div id="Contenedor-Archivos">
                                        <form enctype="multipart/form-data" action="'.$SitioWeb.'php/backend/bl/main/subirArchivos.class.php?rutaadjuntos='.$_GET["rutaadjuntos"].'" name="formulario" method="post">
                                            <input type="file" name="archivo[]" id="archivo[]" multiple="multiple">'
                                            .$sa->genList().
                                            '
                                            <div id="Contenedor-Acciones">
                                                <input id="enviarSolicitud" name="enviarSolicitud" type="submit" value="Subir Archivo" />
                                            </div>
                                        </form>
                                    </div>                                    
                            </body>';
            
            return $HTMLBODY;
            }

    echo "<HTML>".headHTML().drawUI()."</HTML>";    
?>