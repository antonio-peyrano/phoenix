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
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    $imgTitleURL = './img/menu/colonias.png';
    $Title = 'Colonias';
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $clavecod = '';   
        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM catColonias WHERE idColonia='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
            
    $Registro = @mysql_fetch_array(cargarRegistro($parametro), MYSQL_ASSOC);//Llamada a la función de carga de registro de usuario.

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
                    echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/colonias/busColonias.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarColonia(\'./php/backend/colonias/guardar.php\',\'?id=\'+document.getElementById(\'idColonia\').value.toString()+\'&colonia=\'+document.getElementById(\'Colonia\').value.toString()+\'&cp=\'+document.getElementById(\'CP\').value.toString()+\'&ciudad=\'+document.getElementById(\'Ciudad\').value.toString()+\'&estado=\'+document.getElementById(\'Estado\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acción de visualización.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/colonias/busColonias.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/colonias/borrar.php\',\'?id=\'+document.getElementById(\'idColonia\').value.toString(),\'sandbox\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarColonia(\'./php/backend/colonias/guardar.php\',\'?id=\'+document.getElementById(\'idColonia\').value.toString()+\'&colonia=\'+document.getElementById(\'Colonia\').value.toString()+\'&cp=\'+document.getElementById(\'CP\').value.toString()+\'&ciudad=\'+document.getElementById(\'Ciudad\').value.toString()+\'&estado=\'+document.getElementById(\'Estado\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habColonia();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acción corresponda a la edición de un registro.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/colonias/busColonias.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarColonia(\'./php/backend/colonias/guardar.php\',\'?id=\'+document.getElementById(\'idColonia\').value.toString()+\'&colonia=\'+document.getElementById(\'Colonia\').value.toString()+\'&cp=\'+document.getElementById(\'CP\').value.toString()+\'&ciudad=\'+document.getElementById(\'Ciudad\').value.toString()+\'&estado=\'+document.getElementById(\'Estado\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habColonia();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    }
            }
            
    function constructor()
        {
            /*
             * Esta función establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod;
            global $imgTitleURL, $Title;
            
            $habcampos = 'disabled= "disabled"';
            
            if($Registro['idColonia'] == null)
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
                                    <input type= "text" id= "idColonia" value="'.$Registro['idColonia'].'">
                                    <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                                </div>
                                <div id="infoRegistro" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                    <div id="cuerpo" class="cuerpo-operativo">                                
                                        <table>                                            
                                            <tr><td class="td-panel" width="100px">Colonia:</td><td><input type= "text" required= "required" id= "Colonia" '.$habcampos.' value= "'.$Registro['Colonia'].'"></td></tr>
                                            <tr><td class="td-panel" width="100px">C.P.:</td><td><input type= "text" required= "required" id= "CP" '.$habcampos.' value= "'.$Registro['CodigoPostal'].'"></td></tr>
                                            <tr><td class="td-panel" width="100px">Ciudad:</td><td><input type= "text" required= "required" id= "Ciudad" '.$habcampos.' value= "'.$Registro['Ciudad'].'"></td></tr>
                                            <tr><td class="td-panel" width="100px">Estado:</td><td><input type= "text" required= "required" id= "Estado" '.$habcampos.' value= "'.$Registro['Estado'].'"></td></tr>';
                                controlVisual($parametro);
            echo'           </table>
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