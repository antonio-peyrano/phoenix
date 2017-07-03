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
    
    $imgTitleURL = './img/menu/config.png';
    $Title = 'Configuraciones';
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $now = time();
    $periodo = date("Y",$now);
        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM catConfiguraciones WHERE idConfiguracion='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
            
    $Registro = @mysqli_fetch_array(cargarRegistro($parametro),MYSQLI_ASSOC);//Llamada a la función de carga de registro de usuario.

    function controlVisual($idRegistro)
        {
            /*
             * Esta función controla los botones que deberan verse en la pantalla deacuerdo con la acción solicitada por el
             * usuario en la ventana previa. Si es una edición, los botones borrar y guardar deben verse. Si es una creación
             * solo el boton guardar debe visualizarse.
             */
            global $cntview;
            
            if($idRegistro == -1)
                {
                    //En caso que la acción corresponda a la creación de un nuevo registro.
                    echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/configuracion/busConfiguraciones.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarConfiguracion(\'./php/backend/configuracion/guardar.php\',\'?id=\'+document.getElementById(\'idConfiguracion\').value.toString()+\'&optimo=\'+document.getElementById(\'Optimo\').value.toString()+\'&tolerable=\'+document.getElementById(\'Tolerable\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acción de visualización.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/configuracion/busConfiguraciones.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/configuracion/borrar.php\',\'?id=\'+document.getElementById(\'idUnidad\').value.toString(),\'sandbox\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarConfiguracion(\'./php/backend/configuracion/guardar.php\',\'?id=\'+document.getElementById(\'idConfiguracion\').value.toString()+\'&optimo=\'+document.getElementById(\'Optimo\').value.toString()+\'&tolerable=\'+document.getElementById(\'Tolerable\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habConfiguracion();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acción corresponda a la edición de un registro.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/configuracion/busConfiguraciones.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarConfiguracion(\'./php/backend/configuracion/guardar.php\',\'?id=\'+document.getElementById(\'idConfiguracion\').value.toString()+\'&optimo=\'+document.getElementById(\'Optimo\').value.toString()+\'&tolerable=\'+document.getElementById(\'Tolerable\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habConfiguracion();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    }
            }
            
    function constructor()
        {
            /*
             * Esta función establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod, $periodo;
            global $imgTitleURL, $Title;
            
            $habcampos = 'disabled= "disabled"';
            
            if($Registro['idConfiguracion'] == null)
                {
                    //En caso que el registro sea de nueva creacion.
                    $habcampos='';        
                    }

            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        </head>
                        <body>
                            <div id="cntOperativo" class="cnt-operativo">
                                <div style=display:none>
                                    <input type= "text" id= "idConfiguracion" value="'.$Registro['idConfiguracion'].'">   
                                </div>                            
                                <div id="infoRegistro" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                    <div id="cuerpo" class="cuerpo-operativo">                                
                                        <table>
                                            <tr><td class="td-panel" width="100px">Valor Optimo Deseado:</td><td><input type= "text" required= "required" id= "Optimo" '.$habcampos.' value= "'.$Registro['Optimo'].'"></td></tr>
                                            <tr><td class="td-panel" width="100px">Valor Tolerable Esperado:</td><td><input type= "text" required= "required" id= "Tolerable" '.$habcampos.' value= "'.$Registro['Tolerable'].'"></td></tr>
                                            <tr><td class="td-panel" width="100px">Estatus:</td><td><select name= "Status" id= "Status" '.$habcampos.' value= "'.$Registro['Status'].'">
                                                <option value=-1>Seleccione</option>';
                                
                                            if($Registro['Status']=="0")
                                                {
            echo'                                   <option value=0 selected="selected">Activo</option>
                                                    <option value=1>Inactivo</option>';
                                                    }
                                            else
                                                {
            echo'                                   <option value=0>Activo</option>
                                                    <option value=1 selected="selected">Inactivo</option>';
                                                    }
                                                                            
            echo'                           </select></td></tr>';
                                    
                                    if($parametro == "-1")
                                        {
                                           /*
                                            * Si la acción corresponde a la creacion de un registro nuevo,
                                            * se establece el año actual.
                                            */
            echo '                          <tr><td class="td-panel" width="100px">Periodo:</td><td><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$periodo.'"></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la acción ocurre para un registro existente,
                                             * se preserva el año almacenado.
                                             */
            echo '                          <tr><td class="td-panel" width="100px">Periodo:</td><td><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$Registro['Periodo'].'"></td></tr>';
                                            }
                                                    
                                controlVisual($parametro);
            echo'                       </table>
                                    </div>
                                </div>
                            </div>  
                        </body>
                    </html>
                    ';            
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        constructor();
?>