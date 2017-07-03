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

    $habcampos = 'disabled= "disabled"';
    $imgTitleURL = './img/menu/estope.png';
    $Title = 'Estrategia Operativa';        
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $clavecod = '';   
    $now = time();
    $periodo = date("Y",$now);
    $regcount = 0;  

    function cargarObjEst()
        {
            /*
             * Esta función establece la carga del conjunto de registros de ObjEst.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idObjEst, CONCAT(Nomenclatura,\' \',ObjEst) AS CObjEst FROM catObjEst WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
            
    function cargarObjOpe($parametro)
        {
            /*
             * Esta función establece la carga del conjunto de registros de ObjOpe.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idObjOpe, CONCAT(Nomenclatura,\' \',ObjOpe) AS CObjOpe FROM catObjOpe WHERE Status=0 AND idObjEst='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }

    function constructorcb($parametro)
        {
            /*
             * Esta función establece los parametros de carga del combobox de obj ope cuando
             * se ejecuta un proceso de edición.
             */
            global $habcampos, $Registro;
            
            $subconsulta = cargarObjOpe($parametro);            
            
            echo' <select name= "idObjOpe" id= "idObjOpe" '.$habcampos.' value= "'.$Registro['idObjOpe'].'">
                  <option value=-1>Seleccione</option>';
            
            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
            
            while($RegNiveles)
                {
                    if($RegNiveles['idObjOpe']==$Registro['idObjOpe'])
                        {
                            //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo '          <option value='.$RegNiveles['idObjOpe'].' selected="selected">'.$RegNiveles['CObjOpe'].'</option>';
                            }
                    else
                        {
                            //En caso contrario se carga la etiqueta por default.
            echo '          <option value='.$RegNiveles['idObjOpe'].'>'.$RegNiveles['CObjOpe'].'</option>';
                            }
                    $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                    }
            
            echo' </select>';            
            }
            
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM catEstOpe WHERE idEstOpe='.$idRegistro; //Se establece el modelo de consulta de datos.
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
                    echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/estope/busEstOpe.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarEstOpe(\'./php/backend/estope/guardar.php\',\'?id=\'+document.getElementById(\'idEstOpe\').value.toString()+\'&idobjest=\'+document.getElementById(\'idObjEst\').value.toString()+\'&idobjope=\'+document.getElementById(\'idObjOpe\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&estope=\'+document.getElementById(\'EstOpe\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acción de visualización.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/estope/busEstOpe.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/estope/borrar.php\',\'?id=\'+document.getElementById(\'idObjOpe\').value.toString(),\'sandbox\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarEstOpe(\'./php/backend/estope/guardar.php\',\'?id=\'+document.getElementById(\'idEstOpe\').value.toString()+\'&idobjest=\'+document.getElementById(\'idObjEst\').value.toString()+\'&idobjope=\'+document.getElementById(\'idObjOpe\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&estope=\'+document.getElementById(\'EstOpe\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habEstOpe();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acción corresponda a la edición de un registro.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/estope/busEstOpe.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarEstOpe(\'./php/backend/estope/guardar.php\',\'?id=\'+document.getElementById(\'idEstOpe\').value.toString()+\'&idobjest=\'+document.getElementById(\'idObjEst\').value.toString()+\'&idobjope=\'+document.getElementById(\'idObjOpe\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&estope=\'+document.getElementById(\'EstOpe\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habEstOpe();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    }
            }
            
    function constructor()
        {
            /*
             * Esta función establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod, $periodo, $regcount, $habcampos;
            global $imgTitleURL, $Title;
            
            if($Registro['idEstOpe'] == null)
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
                                    <input type= "text" id= "idEstOpe" value="'.$Registro['idEstOpe'].'">
                                    <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                                </div>
                                <div id="infoRegistro" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                    <div id="cuerpo" class="cuerpo-operativo">                                
                                        <table>';
            
                                $subconsulta = cargarObjEst();
                                
            echo '                          <tr><td class="td-panel" width="100px">Objetivo Estrategico:</td><td><select name= "idObjEst" id= "idObjEst" '.$habcampos.' value= "'.$Registro['idObjEst'].'">              
                                                <option value=-1>Seleccione</option>';
            
                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                
                                while($RegNiveles)
                                    {
                                        if($RegNiveles['idObjEst']==$Registro['idObjEst'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo '                              <option value='.$RegNiveles['idObjEst'].' selected="selected">'.$RegNiveles['CObjEst'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo '                              <option value='.$RegNiveles['idObjEst'].'>'.$RegNiveles['CObjEst'].'</option>';
                                                }
                                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
            
            echo'                           </select></td></tr>
                                            <tr><td class="td-panel">Objetivo Operativo:</td><td><div id="cbObjOpe" border:none>';
            
                                if($parametro=="-1")
                                    {
                                        /*
                                         * Si la acción corresponde a la creacion de un registro nuevo,
                                         * se establece el codigo actual.
                                         */
            echo'                               <select id= "idObjOpe"><option value=-1>Seleccione</option></select></div></td></tr>';
                                        }
                                else
                                    {
                                        /*
                                         * Si la acción ocurre para un registro existente,
                                         * se preserva el codigo almacenado.
                                         */
            echo                                constructorcb($Registro['idObjEst']).'</div></td></tr>';
                                        }
                                        
                        
                                if($parametro=="-1")
                                    {
                                        /*
                                         * Si la acción corresponde a la creacion de un registro nuevo,
                                         * se establece el codigo actual.
                                         */
            echo'                           <tr><td class="td-panel" width="100px">Nomenclatura:</td><td><input type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= ""></td></tr>';  
                                        }
                                else
                                    {
                                        /*
                                         * Si la acción ocurre para un registro existente,
                                         * se preserva el codigo almacenado.
                                         */
            echo'                           <tr><td class="td-panel" width="100px">Nomenclatura:</td><td><input type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= "'.$Registro['Nomenclatura'].'"></td></tr>';
                                        }
                                                                          
            echo'                           <tr><td class="td-panel" width="100px">Estrategia Operativa:</td><td><input type= "text" required= "required" id= "EstOpe" '.$habcampos.' value= "'.$Registro['EstOpe'].'"></td></tr>';
            
                                if($parametro=="-1")
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