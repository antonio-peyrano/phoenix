<?php
/*
 * Citadel v1.0.0 Software para atencion ciudadana.
 * PHP v5
 * Autor: Prof. Jesus Antonio Peyrano Luna <antonio.peyrano@live.com.mx>
 * Nota aclaratoria: Este programa se distribuye bajo los terminos y disposiciones
 * definidos en la GPL v3.0, debidamente incluidos en el repositorio original.
 * Cualquier copia y/o redistribucion del presente, debe hacerse con una copia
 * adjunta de la licencia en todo momento.
 * Licencia: http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/bl/noConformidades/noConformidades.class.php"); //Se carga la referencia a la clase para manejo de la entidad usuarios.
    
    class opNoConformidad
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el despliegue
             * de la interfaz del modulo de edicion de registro de no conformidades.
             */
            private $cntView = 0;
            private $idNoConformidad = 0;
            private $imgTitleURL = './img/menu/noconforme.png';
            private $Title = 'No Conformidad';
            private $Sufijo = "noc_";
                        
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene y evalua los parametros proporcionados
                     * por medio de la URL.
                     */
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['id'])){$this->idNoConformidad = $_GET['id'];}
                    }

            public function getSufijo()
                {
                    /*
                     * Esta funcion retorna el valor del sufijo.
                     */
                    return $this->Sufijo;
                    }
                    
            public function getView()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del modo de visualizacion
                     */
                    return $this->cntView;
                    }

            public function getID()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del ID de la no conformidad.
                     */
                    return $this->idNoConformidad;
                    }

            public function cargarProcesos()
                {
                    /*
                     * Esta funcion establece la carga del conjunto de registros de entidades.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idFicha, CONCAT(Clave,\'-\',Proceso) AS Proceso FROM (catProcesos INNER JOIN opFichasProcesos ON opFichasProcesos.idProceso = catProcesos.idProceso) WHERE opFichasProcesos.Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }

            public function getFecha($Registro)
                {
                    /*
                     * Esta funcion retorna el valor obtenido de fecha de registro.
                     */
                    $objNoConformidad = new noConformidades();
                    
                    if(!empty($Registro['fEmision']))
                        {
                            return '<td><input type= "text" class= "inputform" id= "fEmision" readonly required= "required" value= "'.$Registro['fEmision'].'"></td>';
                            }
                    else
                        {
                            return '<td><input type= "text" class= "inputform" id= "fEmision" readonly required= "required" value= "'.$objNoConformidad->getFecha().'"></td>';
                            }
                    }
                                        
            public function saltosLineaRev($str)
                {
                    /*
                     * Esta funcion toma los tag <br> dentro de la cadena recuperada y
                     * los convierte a saltos de linea.
                     */
                    return str_replace(array("<br>"), "\n", $str);
                    }
                    
            public function drawCBProcesos($Registro, $habilitador)
                {
                    /*
                     * Esta funcion crea el codigo HTML que corresponde al combobox de
                     * entidad.
                     */
                    $HTML = '<tr><td class="td-panel" width="100px">Proceso: </td><td><select name= "idFicha" id= "idFicha" value= "-1"'.$habilitador.'>
                                <option value=-1>Seleccione</option>';
                    
                    $subconsulta = $this->cargarProcesos();
                    $RegNoConformidad = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                                              
                    while($RegNoConformidad)
                        {
                            if($Registro['idFicha'] == $RegNoConformidad['idFicha'])
                                {
                                    //Si el item fue previamente marcado, se selecciona en el codigo.
                                    $HTML .= '<option value='.$RegNoConformidad['idFicha'].' selected>'.$RegNoConformidad['Proceso'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se escribe la secuencia base de codigo.
                                    $HTML .= '<option value='.$RegNoConformidad['idFicha'].'>'.$RegNoConformidad['Proceso'].'</option>';
                                    }                            
                            $RegNoConformidad = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
                    
                    $HTML .= '</select></td></tr>';
                    return $HTML;
                    }

            public function getTipo($habCampos, $Registro)
                {
                    /*
                     * Esta funcion retorna el codigo HTML correspondiente al combobox del
                     * listado de tipos.
                     */
                    $HTML = '<select name= "Tipo" id= "Tipo" '.$habCampos.' value= "'.$Registro['Tipo'].'"><option value="Seleccione">Seleccione</option>';
                        
                    if($Registro['Tipo']=="No Conformidad Mayor"){$HTML .= '<option value = "No Conformidad Mayor" selected="selected">No Conformidad Mayor</option>';}
                    else{$HTML .= '<option value = "No Conformidad Mayor">No Conformidad Mayor</option>';}
                        
                    if($Registro['Tipo']=="No Conformidad Menor"){$HTML .= '<option value = "No Conformidad Menor" selected="selected">No Conformidad Menor</option>';}
                    else{$HTML .= '<option value = "No Conformidad Menor">No Conformidad Menor</option>';}
                                                
                    return $HTML.'</select>';
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion crea el codigo HTML correspondiente a la interfaz de usuario.
                     */                    
                    
                    $objNoConformidad = new noConformidades();                    
                    
                    $RegNoConformidad = $objNoConformidad->getRegistro($this->getID());
                                                                                                   
                    if(!empty($RegNoConformidad['idNoConformidad']))
                        {
                            //CASO: VISUALIZACION DE REGISTRO PARA SU EDICION O BORRADO.
                            if($this->getView() == 1)
                                {
                                    //VISUALIZAR.
                                    $habCampos = 'disabled= "disabled"';
                                    }
                            else
                                {
                                    //EDICION.
                                    $habCampos = '';
                                    }                                                                
                            }
                    else
                        {
                            //CASO: CREACION DE NUEVO REGISTRO.
                            $habCampos = '';
                            }                                               
                            
                    $HTMLBody = '   <div id="cntOperativo">
                                            <div id="statsCliente" style="display:none">
                                                <table>
                                                    <tr><td>idCedula: </td><td><input id="idNoConformidad" type="text" value="'.$RegNoConformidad['idNoConformidad'].'"></td></tr>
                                                    <tr><td>Status: </td><td><input id="Status" type="text" value="'.$RegNoConformidad['Status'].'"></td></tr>
                                                </table>
                                            </div>
                                            <div id="infoRegistro" class="operativo">
                                                <div id="cabecera" class="cabecera-operativo">'
                                                    .'<img align="middle" src="'.$this->imgTitleURL.'" width="32" height="32"/> '.$this->Title.' </div>
                                                <div id="cuerpo" class="cuerpo-operativo">
                                                    <table>'
                                                        .$this->drawCBProcesos($RegNoConformidad, $habCampos).
                                                        '<tr><td class="td-panel" width="100px">Tipo:</td><td class="td-panel">'.$this->getTipo($habCampos, $RegNoConformidad).'</td></tr>
                                                        <tr><td class="td-panel" width="100px">Fecha:</td>'.$this->getFecha($RegNoConformidad).'</tr>
                                                        <tr><td class="td-panel" width="100px">Auditor:</td><td><input type= "text" id= "Auditor" required= "required" '.$habCampos.' value= "'.$RegNoConformidad['Auditor'].'"></td></tr>
                                                        <tr><th colspan= "2" class= "td-panel">Observaciones</th></tr>
                                                        <tr><td colspan= "2" class= "td-panel"><center><textarea name="Observaciones" id="Observaciones" cols="60" rows="7"'.$habCampos.'>'.$this->saltosLineaRev($RegNoConformidad['Observaciones']).'</textarea></center></td></tr>
                                                        <tr><th colspan= "2" class= "td-panel">Recomendaciones</th></tr>
                                                        <tr><td colspan= "2" class= "td-panel"><center><textarea name="Recomendaciones" id="Recomendaciones" cols="60" rows="7"'.$habCampos.'>'.$this->saltosLineaRev($RegNoConformidad['Recomendaciones']).'</textarea></center></td></tr>                                                        
                                                    </table>
                                                </div><center>
                                                <div id="adjuntosServidor">
                                                    <table class="queryTable">
                                                        <tr><td class= "queryRowsnormTR"><a href="#"id="verArchivosNOC">VER ARCHIVOS ADJUNTOS</a></td></tr>
                                                    </table>
                                                </div></center>
                                            <div id="pie" class="pie-operativo">'.
                                                $objNoConformidad->controlBotones("32", "32", $this->getView()).
                                            '</div>
                                    </div>';
                    return $HTMLBody;                                                
                    }                    
            }

    $objOpNoConformidad = new opNoConformidad();
    

    $HTML = '   <html>
                    <head>
                    </head>
                    <body>
                        <center>'.
                            $objOpNoConformidad->drawUI().
                        '</center>
                    </body>
                </html>';
                    
    echo $HTML;        
?>