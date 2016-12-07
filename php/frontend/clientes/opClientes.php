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

    header('Content-Type: text/html; charset=UTF-8'); //Forzar la codificación a UTF-8.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    
    function cargarColonias()
        {
            /*
             * Esta función establece la carga del conjunto de registros de colonias.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idColonia, Colonia FROM catColonias WHERE Status=0'; //Se establece el modelo de consulta de datos.
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
            $consulta= 'SELECT *FROM opClientes INNER JOIN catColonias ON catColonias.idColonia = opClientes.idColonia WHERE idCliente='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
            
    $Registro = @mysql_fetch_array(cargarRegistro($parametro), MYSQL_ASSOC);//Llamada a la funci�n de carga de registro de usuario.

    function controlVisual($idRegistro)
        {
            /*
             * Esta funci�n controla los botones que deberan verse en la pantalla deacuerdo con la acci�n solicitada por el
             * usuario en la ventana previa. Si es una edici�n, los botones borrar y guardar deben verse. Si es una creaci�n
             * solo el boton guardar debe visualizarse.
             */
            global $cntview;
            
            if($idRegistro == -1)
                {
                    //En caso que la acci�n corresponda a la creaci�n de un nuevo registro.
                    echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/clientes/busClientes.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarCliente(\'./php/backend/clientes/guardar.php\',\'?id=\'+document.getElementById(\'idCliente\').value.toString()+\'&nombre=\'+document.getElementById(\'Nombre\').value.toString()+\'&paterno=\'+document.getElementById(\'Paterno\').value.toString()+\'&materno=\'+document.getElementById(\'Materno\').value.toString()+\'&calle=\'+document.getElementById(\'Calle\').value.toString()+\'&nint=\'+document.getElementById(\'Nint\').value.toString()+\'&next=\'+document.getElementById(\'Next\').value.toString()+\'&idcolonia=\'+document.getElementById(\'idColonia\').value.toString()+\'&rfc=\'+document.getElementById(\'RFC\').value.toString()+\'&curp=\'+document.getElementById(\'CURP\').value.toString()+\'&telfijo=\'+document.getElementById(\'TelFijo\').value.toString()+\'&telcel=\'+document.getElementById(\'TelCel\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acci�n de visualizaci�n.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/clientes/busClientes.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/clientes/borrar.php\',\'?id=\'+document.getElementById(\'idCliente\').value.toString(),\'escritorio\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarCliente(\'./php/backend/clientes/guardar.php\',\'?id=\'+document.getElementById(\'idCliente\').value.toString()+\'&nombre=\'+document.getElementById(\'Nombre\').value.toString()+\'&paterno=\'+document.getElementById(\'Paterno\').value.toString()+\'&materno=\'+document.getElementById(\'Materno\').value.toString()+\'&calle=\'+document.getElementById(\'Calle\').value.toString()+\'&nint=\'+document.getElementById(\'Nint\').value.toString()+\'&next=\'+document.getElementById(\'Next\').value.toString()+\'&idcolonia=\'+document.getElementById(\'idColonia\').value.toString()+\'&rfc=\'+document.getElementById(\'RFC\').value.toString()+\'&curp=\'+document.getElementById(\'CURP\').value.toString()+\'&telfijo=\'+document.getElementById(\'TelFijo\').value.toString()+\'&telcel=\'+document.getElementById(\'TelCel\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habCliente();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acci�n corresponda a la edici�n de un registro.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/clientes/busClientes.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarCliente(\'./php/backend/clientes/guardar.php\',\'?id=\'+document.getElementById(\'idCliente\').value.toString()+\'&nombre=\'+document.getElementById(\'Nombre\').value.toString()+\'&paterno=\'+document.getElementById(\'Paterno\').value.toString()+\'&materno=\'+document.getElementById(\'Materno\').value.toString()+\'&calle=\'+document.getElementById(\'Calle\').value.toString()+\'&nint=\'+document.getElementById(\'Nint\').value.toString()+\'&next=\'+document.getElementById(\'Next\').value.toString()+\'&idcolonia=\'+document.getElementById(\'idColonia\').value.toString()+\'&rfc=\'+document.getElementById(\'RFC\').value.toString()+\'&curp=\'+document.getElementById(\'CURP\').value.toString()+\'&telfijo=\'+document.getElementById(\'TelFijo\').value.toString()+\'&telcel=\'+document.getElementById(\'TelCel\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habCliente();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    }
            }
            
    function constructor()
        {
            /*
             * Esta funci�n establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod;

            $habcampos = 'disabled= "disabled"';
            
            if($Registro['idCliente'] == null)
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
                            <div style=display:none>
                                <input type= "text" id= "idCliente" value="'.$Registro['idCliente'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                            <center>
                            <div id="infoCliente" style="width: 400px; height: 600px;">                                
                            <table class="dgTable">
                                <tr><th class="dgHeader" colspan= 2">Cliente en el Sistema</th></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Nombre:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Nombre" '.$habcampos.' value= "'.$Registro['Nombre'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Paterno:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Paterno" '.$habcampos.' value= "'.$Registro['Paterno'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Materno:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Materno" '.$habcampos.' value= "'.$Registro['Materno'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Calle:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Calle" '.$habcampos.' value= "'.$Registro['Calle'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Interior:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Nint" '.$habcampos.' value= "'.$Registro['Nint'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Exterior:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Next" '.$habcampos.' value= "'.$Registro['Next'].'"></td></tr>                                        
                                <tr><td class="dgRowsaltTR" width="100px">Colonia:</td><td class="dgRowsnormTR"><select name= "idColonia" id= "idColonia" '.$habcampos.' value= "'.$Registro['idColonia'].'">';
                                $subconsulta = cargarColonias();
            echo '                              <option value=-1>Seleccione</option>';
                                $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                while ($RegNiveles)
                                    {
                                        if($RegNiveles['idColonia']==$Registro['idColonia'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo '                              <option value='.$RegNiveles['idColonia'].' selected="selected">'.$RegNiveles['Colonia'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo '                              <option value='.$RegNiveles['idColonia'].'>'.$RegNiveles['Colonia'].'</option>';                                                
                                                }
                                        $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                        }
                                
            echo'               </select></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">RFC:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "RFC" '.$habcampos.' value= "'.$Registro['RFC'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">CURP:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "CURP" '.$habcampos.' value= "'.$Registro['CURP'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Telefono particular:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "TelFijo" '.$habcampos.' value= "'.$Registro['TelFijo'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Telefono celular:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "TelCel" '.$habcampos.' value= "'.$Registro['TelCel'].'"></td></tr>                                                                                                            
                ';
                                controlVisual($parametro);
            echo'           </table>  
                        </body>
                    </html>
                    ';            
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        constructor();
?>