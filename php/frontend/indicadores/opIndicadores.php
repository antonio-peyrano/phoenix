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
    
    $imgTitleURL = './img/menu/indicadores.png';
    $Title = 'Indicadores';
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $habcampos = 'disabled= "disabled"';
    $clavecod = '';   
        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM (relIndPro INNER JOIN catProcesos ON catProcesos.idProceso = relIndPro.idProceso) INNER JOIN catIndicadores ON catIndicadores.idIndicador = relIndPro.idIndicador WHERE relIndPro.idRelIndPro='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   

    function cargarProcesos($idRegistro, $idIndicador)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */
            global $username, $password, $servername, $dbname, $habcampos;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM catProcesos WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
            echo '<tr><td class="td-panel" width="100px">Procesos:</td><td><div id="idProcesoChk">';
            
            if($idRegistro == -1)
                {
                    /*
                     * Si la operación solicitada es para la creación de un registro,
                     * se carga el listado sin marcar.
                     */
                    $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
            
                    while ($RegNiveles)
                        {
                            echo '<br><input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" '.$habcampos.' value='.$RegNiveles['idProceso'].'>'.$RegNiveles['Proceso'];
                            $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                            }
                    }
            else
                {
                    /*
                     * Si la operación solicitada es para editar el registro,
                     * se carga el listado con los elementos previamente marcados.
                     */
                    $subconsulta = 'SELECT *FROM relIndPro WHERE idIndicador='.$idIndicador.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                    $vector = "";
                    $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
            
                    if($RegNiveles)
                        {
                            /*
                             * Si la lectura del registro no apunta a vacio, se agrega
                             * el id al vector.
                             */
                            $vector.=$RegNiveles['idProceso'];
                            }
            
                    $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
            
                    while ($RegNiveles)
                        {
                            /*
                             * Se hace un recorrido sobre el dataset para crear un vector que contenga
                             * los id de las entidades seleccionadas por el usuario previamente.
                             */
                            $vector.=','.$RegNiveles['idProceso'];
                            $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                            }
            
                    $tmparray=explode(',',$vector); //El vector resultante se convierte en un arreglo.
            
                    $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
            
                    while ($RegNiveles)
                        {
                            /*
                             * Mientras no se llegue al final de la colección, se procede a la lectura
                             * y generación del listado.
                             */
                            if(in_array($RegNiveles['idProceso'], $tmparray,true))
                                {
                                    /*
                                     * En caso de tratarse de una opción previamente seleccionada por el usuario.
                                     */
                                    echo '<br><input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" '.$habcampos.' value='.$RegNiveles['idProceso'].' checked>'.$RegNiveles['Proceso'];
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario se agrega una entrada de formato convencional.
                                     */
                                    echo '<br><input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" '.$habcampos.' value='.$RegNiveles['idProceso'].'>'.$RegNiveles['Proceso'];
                                    }
            
                            $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                            }
                    }
            
            echo'</div></td></tr>';
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
                    echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/indicadores/busIndicadores.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarIndicador(\'./php/backend/indicadores/guardar.php\',\'?id=\'+document.getElementById(\'idIndicador\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&indicador=\'+document.getElementById(\'Indicador\').value.toString()+\'&percentil=\'+document.getElementById(\'Percentil\').value.toString()+\'&idproceso=\'+getidsprocesos()+\'&nonidproceso=\'+getnonidprocesos()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acción de visualización.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/indicadores/busIndicadores.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/indicadores/borrar.php\',\'?id=\'+document.getElementById(\'idIndicador\').value.toString(),\'sandbox\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarIndicador(\'./php/backend/indicadores/guardar.php\',\'?id=\'+document.getElementById(\'idIndicador\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&indicador=\'+document.getElementById(\'Indicador\').value.toString()+\'&percentil=\'+document.getElementById(\'Percentil\').value.toString()+\'&idproceso=\'+getidsprocesos()+\'&nonidproceso=\'+getnonidprocesos()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habIndicador();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acción corresponda a la edición de un registro.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/indicadores/busIndicadores.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarIndicador(\'./php/backend/indicadores/guardar.php\',\'?id=\'+document.getElementById(\'idIndicador\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&indicador=\'+document.getElementById(\'Indicador\').value.toString()+\'&percentil=\'+document.getElementById(\'Percentil\').value.toString()+\'&idproceso=\'+getidsprocesos()+\'&nonidproceso=\'+getnonidprocesos()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habIndicador();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    }
            }
            
    function constructor()
        {
            /*
             * Esta función establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod, $habcampos;
            global $imgTitleURL, $Title;
            
            if($Registro['idIndicador'] == null)
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
                                <input type= "text" id= "idIndicador" value="'.$Registro['idIndicador'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                                <div id="infoRegistro" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                    <div id="cuerpo" class="cuerpo-operativo">                                
                                        <table>
                                <tr><td class="td-panel" width="100px">Indicador:</td><td><input type= "text" required= "required" id= "Indicador" '.$habcampos.' value= "'.$Registro['Indicador'].'"></td></tr>
                                <tr><td class="td-panel" width="100px">Nomenclatura:</td><td><input type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= "'.$Registro['Nomenclatura'].'"></td></tr>
                                <tr><td class="td-panel" width="100px">Percentil:</td><td><input type= "text" required= "required" id= "Percentil" '.$habcampos.' value= "'.$Registro['Percentil'].'"></td></tr>';
            
                                cargarProcesos($parametro, $Registro['idIndicador']);
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