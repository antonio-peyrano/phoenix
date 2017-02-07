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
    
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    
    function cargarCedulas()
        {
            /*
             * Esta función establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idCedula, Folio FROM opCedulas WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM opFactores WHERE idFactor='.$idRegistro; //Se establece el modelo de consulta de datos.
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
                    echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/foda/factores/busFactores.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarFactor(\'./php/backend/foda/factores/guardar.php\',\'?id=\'+document.getElementById(\'idFactor\').value.toString()+\'&factor=\'+document.getElementById(\'Factor\').value.toString()+\'&tipo=\'+document.getElementById(\'Tipo\').value.toString()+\'&idcedula=\'+document.getElementById(\'idCedula\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acción de visualización.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/foda/factores/busFactores.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/foda/factores/borrar.php\',\'?id=\'+document.getElementById(\'idFactor\').value.toString(),\'escritorio\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarFactor(\'./php/backend/foda/factores/guardar.php\',\'?id=\'+document.getElementById(\'idFactor\').value.toString()+\'&factor=\'+document.getElementById(\'Factor\').value.toString()+\'&tipo=\'+document.getElementById(\'Tipo\').value.toString()+\'&idcedula=\'+document.getElementById(\'idCedula\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());""><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habFactor();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acción corresponda a la edición de un registro.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/foda/factores/busFactores.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarFactor(\'./php/backend/foda/factores/guardar.php\',\'?id=\'+document.getElementById(\'idFactor\').value.toString()+\'&factor=\'+document.getElementById(\'Factor\').value.toString()+\'&tipo=\'+document.getElementById(\'Tipo\').value.toString()+\'&idcedula=\'+document.getElementById(\'idCedula\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habFactor();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
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

            $habcampos = 'disabled= "disabled"';
            $idCedula = '';
            
            if($Registro['idFactor'] == null)
                {
                    //En caso que el registro sea de nueva creacion.
                    $habcampos = '';
                    if(isset($_GET['ffacedula']))
                        {
                            $idCedula = $_GET['ffacedula'];
                            }        
                    }
            else
                {
                    //Si se trata de una edicion.
                    $idCedula = $Registro['idCedula'];
                    }                    

            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        </head>
                        <body>
                            <div style=display:none>
                                <input type= "text" id= "idFactor" value="'.$Registro['idFactor'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                            <center>
                            <div id="infoEmpleado" style="width: 400px; height: 600px;">                                                                    
                            <table class="dgTable">
                                <tr><th class="dgHeader" colspan= 2">Factor en el Sistema</th></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Factor:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Factor" '.$habcampos.' value= "'.$Registro['Factor'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Tipo:</td>
                                    <td class="dgRowsnormTR">
                                        <select name="Tipo" id= "Tipo" '.$habcampos.' value= "'.$Registro['Tipo'].'">';
            
                                if($Registro['Tipo'] == "Interno")
                                    {
            echo'                           <option value= "Seleccione">Seleccione</option>
                                            <option value= "Interno" selected="selected">Interno</option>
                                            <option value= "Externo">Externo</option>';
                                        }
                                else
                                    {
                                            if($Registro['Tipo'] == "Externo")
                                                {
            echo'                                   <option value= "Seleccione">Seleccione</option>
                                                    <option value= "Interno">Interno</option>
                                                    <option value= "Externo" selected="selected">Externo</option>';                                                    
                                                    }
                                            else
                                                {
            echo'                                   <option value= "Seleccione" selected="selected">Seleccione</option>
                                                    <option value= "Interno">Interno</option>
                                                    <option value= "Externo">Externo</option>';                                                    
                                                    }                                        
                                        }   
                                                                             
            echo'                       </select>
                                    </td>
                                </tr>';
            
            echo'               <tr><td width="100px" class="dgRowsaltTR">Cedula:</td><td class="dgRowsnormTR"><select name= "idCedula" id= "idCedula" '.$habcampos.' value= "'.$idCedula.'">';

                                $subconsulta = cargarCedulas();
                                
            echo'               <option value=-1>Seleccione</option>';
                                    
                                $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                
                                while ($RegNiveles)
                                    {
                                        //Se ejecuta un recorrido de comparacion para determinar el item seleccionado.                                                
                                        if($RegNiveles['idCedula'] == $idCedula)
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                               <option value='.$RegNiveles['idCedula'].' selected="selected">'.$RegNiveles['Folio'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo'                               <option value='.$RegNiveles['idCedula'].'>'.$RegNiveles['Folio'].'</option>';
                                                }
                                                
                                        $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                        }
            
            echo'               </select></td></tr>';
                        
                                controlVisual($parametro);
                                
            echo'           </table>
                            </div>
                            </center>  
                        </body>
                    </html>';            
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        constructor();
?>