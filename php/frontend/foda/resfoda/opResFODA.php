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
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    class opResFODA
        {
            /*
             * Esta clase contiene los atributos y procedimientos para generar la interfaz
             * de control de visualizacion de resultados de la evaluacion FODA.
             */
            private $idEntidad = 0;
            private $Sufijo = 'rsf_';
            
            public function __construct()
                {
                    //Declaracion de funcion constructor (VACIO)
                    }

            public function getCedulas()
                {
                    /*
                     * Esta funcion genera el codigo HTML correspondiente al combobox
                     * de cedulas de evaluacion.
                     */
                    
                    
                    $HTML = '   <select id="'.$this->Sufijo.'idCedula" name="'.$this->Sufijo.'idCedula" value="-1">
                                    <option value="-1">Seleccione</option>
                                </select>';
                    
                    return $HTML;
                    }
                                        
            public function getEntidades()
                {
                    
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta= 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dsEntidades = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    
                    $HTML = '<select name="'.$this->Sufijo.'idEntidad" id="'.$this->Sufijo.'idEntidad" value="-1">';
                    $HTML .= '<option value="-1">Seleccione</option>';
                    $HTML .= '<option value="-2">Global</option>';
                    
                    $regEntidades = @mysqli_fetch_array($dsEntidades,MYSQLI_ASSOC);
                    
                    while($regEntidades)
                        {
                            /*
                             * Mientras que existan registros de entidades, se crearan las entradas de
                             * opciones en el control combobox idEntidad.
                             */
                            $HTML .='<option value="'.$regEntidades['idEntidad'].'">'.$regEntidades['Entidad'].'</option>' ;
                            $regEntidades = @mysqli_fetch_array($dsEntidades,MYSQLI_ASSOC);
                            }
                            
                    $HTML .= '</select>';
                                                
                    return $HTML;
                    }
                                        
            public function drawUI()
                {
                    /*
                     * Esta funcion contiene el codigo HTML que sera adjuntado a la estructura
                     * de la pagina de visualizacion de resultados.
                     */
                    
                    $HTML = '   <div id="filtrado">
                                    <center>
                                        <table class= "queryTable">
                                            <tr><th colspan= "3" class= "queryHeader">Seleccione los criterios de consulta</th></tr>
                                            <tr><td class="queryRowsnormTR" width="250px">Seleccion la entidad evaluada:</td><td class= "queryRowsaltTR">'.$this->getEntidades().'</td><td rowspan="2" class="query"><img src="./img/grids/search.png" id="frf_filtro"></td></tr>
                                            <tr><td class="queryRowsnormTR" width="250px">Seleccione la cedula:</td><td class= "queryRowsaltTR"><div id="divCedulas">'.$this->getCedulas().'</div></td></tr>    
                                        </table>
                                    </center>
                                </div>
                                <br><br>
                                <center><div id= "divResFODA"></div></center>';
                    
                    return $HTML;
                    }                    
            }

    $objResFODA = new opResFODA();
    
    $HTML = '   <html>
                    <head>
                        <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                        <link rel= "stylesheet" href= "./css/dgStyle.css"></style>
                        <link rel="stylesheet" href="./css/foda.css"></style>
                    </head>
                    <body>'.$objResFODA->drawUI().
                    '</body>
                </html>';
    
    echo $HTML;               
?>