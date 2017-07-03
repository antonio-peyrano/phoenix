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
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];

    function cargarPuestos($parametro)
        {
            /*
             * Esta función establece la carga del conjunto de registros de Puestos.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT * FROM ((catPuestos INNER JOIN relEntPuesto ON relEntPuesto.idPuesto = catPuestos.idPuesto) INNER JOIN catEntidades ON relEntPuesto.idEntidad = catEntidades.idEntidad) WHERE catPuestos.Status=0 AND relEntPuesto.idEntidad='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
        
    function constructorcbPuesto($parametro)
        {
            /*
             * Esta función establece los parametros de carga del combobox de Puesto cuando
             * se ejecuta un proceso de edición.
             */
            global $habcampos, $Registro;
    
            $subconsulta = cargarPuestos($parametro);
    
            echo'   <select name= "idPuesto" id= "idPuesto" '.$habcampos.' value= "'.$Registro['idPuesto'].'">
                        <option value=-1>Seleccione</option>';
    
            $RegNiveles = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
    
            while($RegNiveles)
                {
                    if($RegNiveles['idPuesto']==$Registro['idPuesto'])
                        {
                            //En caso que el valor almacenado coincida con uno de los existentes en lista.
                    echo '  <option value='.$RegNiveles['idPuesto'].' selected="selected">'.$RegNiveles['Puesto'].'</option>';
                            }
                    else
                        {
                            //En caso contrario se carga la etiqueta por default.
                    echo '  <option value='.$RegNiveles['idPuesto'].'>'.$RegNiveles['Puesto'].'</option>';
                            }
                    $RegNiveles = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
                    }
    
            echo' </select>';
            }
        
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

    function cargarEntidades()
        {
            /*
             * Esta función establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }

    function cargarUsuarios()
        {
            /*
             * Esta función establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idUsuario, Usuario FROM catUsuarios WHERE Status=0'; //Se establece el modelo de consulta de datos.
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
            $consulta= 'SELECT *FROM ((opEmpleados INNER JOIN catColonias ON catColonias.idColonia = opEmpleados.idColonia) INNER JOIN relUsrEmp ON relUsrEmp.idEmpleado = opEmpleados.idEmpleado) LEFT JOIN catUsuarios ON catUsuarios.idUsuario = relUsrEmp.idUsuario WHERE opEmpleados.idEmpleado='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
            
    $Registro = @mysql_fetch_array(cargarRegistro($parametro),MYSQLI_ASSOC);//Llamada a la funci�n de carga de registro de usuario.

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
                    echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/empleados/busEmpleados.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarEmpleado(\'./php/backend/empleados/guardar.php\',\'?id=\'+document.getElementById(\'idEmpleado\').value.toString()+\'&nombre=\'+document.getElementById(\'Nombre\').value.toString()+\'&paterno=\'+document.getElementById(\'Paterno\').value.toString()+\'&materno=\'+document.getElementById(\'Materno\').value.toString()+\'&calle=\'+document.getElementById(\'Calle\').value.toString()+\'&nint=\'+document.getElementById(\'Nint\').value.toString()+\'&next=\'+document.getElementById(\'Next\').value.toString()+\'&idcolonia=\'+document.getElementById(\'idColonia\').value.toString()+\'&identemp=\'+document.getElementById(\'idEntEmp\').value.toString()+\'&idpuesto=\'+document.getElementById(\'idPuesto\').value.toString()+\'&rfc=\'+document.getElementById(\'RFC\').value.toString()+\'&curp=\'+document.getElementById(\'CURP\').value.toString()+\'&telfijo=\'+document.getElementById(\'TelFijo\').value.toString()+\'&telcel=\'+document.getElementById(\'TelCel\').value.toString()+\'&idusuario=\'+document.getElementById(\'idUsuario\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acci�n de visualizaci�n.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/empleados/busEmpleados.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/empleados/borrar.php\',\'?id=\'+document.getElementById(\'idEmpleado\').value.toString(),\'escritorio\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarEmpleado(\'./php/backend/empleados/guardar.php\',\'?id=\'+document.getElementById(\'idEmpleado\').value.toString()+\'&nombre=\'+document.getElementById(\'Nombre\').value.toString()+\'&paterno=\'+document.getElementById(\'Paterno\').value.toString()+\'&materno=\'+document.getElementById(\'Materno\').value.toString()+\'&calle=\'+document.getElementById(\'Calle\').value.toString()+\'&nint=\'+document.getElementById(\'Nint\').value.toString()+\'&next=\'+document.getElementById(\'Next\').value.toString()+\'&idcolonia=\'+document.getElementById(\'idColonia\').value.toString()+\'&identemp=\'+document.getElementById(\'idEntEmp\').value.toString()+\'&idpuesto=\'+document.getElementById(\'idPuesto\').value.toString()+\'&rfc=\'+document.getElementById(\'RFC\').value.toString()+\'&curp=\'+document.getElementById(\'CURP\').value.toString()+\'&telfijo=\'+document.getElementById(\'TelFijo\').value.toString()+\'&telcel=\'+document.getElementById(\'TelCel\').value.toString()+\'&idusuario=\'+document.getElementById(\'idUsuario\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habEmpleado();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acci�n corresponda a la edici�n de un registro.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/empleados/busEmpleados.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarEmpleado(\'./php/backend/empleados/guardar.php\',\'?id=\'+document.getElementById(\'idEmpleado\').value.toString()+\'&nombre=\'+document.getElementById(\'Nombre\').value.toString()+\'&paterno=\'+document.getElementById(\'Paterno\').value.toString()+\'&materno=\'+document.getElementById(\'Materno\').value.toString()+\'&calle=\'+document.getElementById(\'Calle\').value.toString()+\'&nint=\'+document.getElementById(\'Nint\').value.toString()+\'&next=\'+document.getElementById(\'Next\').value.toString()+\'&idcolonia=\'+document.getElementById(\'idColonia\').value.toString()+\'&identemp=\'+document.getElementById(\'idEntEmp\').value.toString()+\'&idpuesto=\'+document.getElementById(\'idPuesto\').value.toString()+\'&rfc=\'+document.getElementById(\'RFC\').value.toString()+\'&curp=\'+document.getElementById(\'CURP\').value.toString()+\'&telfijo=\'+document.getElementById(\'TelFijo\').value.toString()+\'&telcel=\'+document.getElementById(\'TelCel\').value.toString()+\'&idusuario=\'+document.getElementById(\'idUsuario\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habEmpleado();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
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
            
            if($Registro['idEmpleado'] == null)
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
                                <input type= "text" id= "idEmpleado" value="'.$Registro['idEmpleado'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>                                
                            <center>
                            <div id="infoEmpleado" style="width: 400px; height: 600px;">
                            <table class="dgTable">
                                <tr><th class="dgHeader" colspan= 2">Empleado en el Sistema</th></tr>
                                <tr><td width="100px" class="dgRowsaltTR">Nombre:</td><td class="dgRowsnormTR" width="50px"><input type= "text" required= "required" id= "Nombre" '.$habcampos.' value= "'.$Registro['Nombre'].'"></td></tr>
                                <tr><td width="100px" class="dgRowsaltTR">Paterno:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Paterno" '.$habcampos.' value= "'.$Registro['Paterno'].'"></td></tr>
                                <tr><td width="100px" class="dgRowsaltTR">Materno:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Materno" '.$habcampos.' value= "'.$Registro['Materno'].'"></td></tr>
                                <tr><td width="100px" class="dgRowsaltTR">Calle:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Calle" '.$habcampos.' value= "'.$Registro['Calle'].'"></td></tr>
                                <tr><td width="100px" class="dgRowsaltTR">Interior:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Nint" '.$habcampos.' value= "'.$Registro['Nint'].'"></td></tr>
                                <tr><td width="100px" class="dgRowsaltTR">Exterior:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Next" '.$habcampos.' value= "'.$Registro['Next'].'"></td></tr>';                                        
            echo'               <tr><td width="100px" class="dgRowsaltTR">Colonia:</td><td class="dgRowsnormTR"><select name= "idColonia" id= "idColonia" '.$habcampos.' value= "'.$Registro['idColonia'].'">\'';
                                $subconsulta = cargarColonias();
            echo'                              <option value=-1>Seleccione</option>';
                                $RegNiveles = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
                                while ($RegNiveles)
                                    {
                                        if($RegNiveles['idColonia']==$Registro['idColonia'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                              <option value='.$RegNiveles['idColonia'].' selected="selected">'.$RegNiveles['Colonia'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo'                              <option value='.$RegNiveles['idColonia'].'>'.$RegNiveles['Colonia'].'</option>';                                                
                                                }
                                        $RegNiveles = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
                                
            echo'               </select></td></tr>';
            
            echo'               <tr><td width="100px" class="dgRowsaltTR">Entidad:</td><td class="dgRowsnormTR"><select name= "idEntEmp" id= "idEntEmp" '.$habcampos.' value= "'.$Registro['idEntidad'].'">\'';
                                $subconsulta = cargarEntidades();
            echo'                              <option value=-1>Seleccione</option>';
                                $RegNiveles = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
                                while ($RegNiveles)
                                    {
                                        if($RegNiveles['idEntidad']==$Registro['idEntidad'])
                                            {
                                               //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                              <option value='.$RegNiveles['idEntidad'].' selected="selected">'.$RegNiveles['Entidad'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
                    echo'                       <option value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'].'</option>';
                                                }
                                        $RegNiveles = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
            
            echo'               </select></td></tr>';
                        
            echo'               <tr><td width="100px" class="dgRowsaltTR">Puesto:</td><td class="dgRowsnormTR"><div id="cbPuesto" border:none>';
                                if($parametro=="-1")
                                    {
                                        /*
                                         * Si la acción corresponde a la creacion de un registro nuevo,
                                         * se establece el codigo actual.
                                         */
            echo'                       <select id= "idPuesto" '.$habcampos.' ><option value=-1>Seleccione</option></select></div></td></tr>';
                                        }
                                else
                                    {
                                        /*
                                         * Si la acción ocurre para un registro existente,
                                         * se preserva el codigo almacenado.
                                         */
            echo                        constructorcbPuesto($Registro['idEntidad']).'</div></td></tr>';
                                        }
                                                    
            echo'               <tr><td width="150px" class="dgRowsaltTR">RFC:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "RFC" '.$habcampos.' value= "'.$Registro['RFC'].'"></td></tr>
                                <tr><td width="100px" class="dgRowsaltTR">CURP:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "CURP" '.$habcampos.' value= "'.$Registro['CURP'].'"></td></tr>
                                <tr><td width="100px" class="dgRowsaltTR">Telefono particular:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "TelFijo" '.$habcampos.' value= "'.$Registro['TelFijo'].'"></td></tr>
                                <tr><td width="100px" class="dgRowsaltTR">Telefono celular:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "TelCel" '.$habcampos.' value= "'.$Registro['TelCel'].'"></td></tr>';
            echo'               <tr><td width="100px" class="dgRowsaltTR">Usuario:</td><td class="dgRowsnormTR"><select name= "idUsuario" id= "idUsuario" '.$habcampos.' value= "'.$Registro['idUsuario'].'">\'';
                        
                                $subconsulta = cargarUsuarios();
            echo'                              <option value=-1>Seleccione</option>';
                                $RegUsuarios = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
                                while ($RegUsuarios)
                                    {
                                        if($RegUsuarios['idUsuario']==$Registro['idUsuario'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                              <option value='.$RegUsuarios['idUsuario'].' selected="selected">'.$RegUsuarios['Usuario'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo'                               <option value='.$RegUsuarios['idUsuario'].'>'.$RegUsuarios['Usuario'].'</option>';
                                                }
                                        $RegUsuarios = @mysql_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
            
            echo'               </select></td></tr>';            
                                controlVisual($parametro);
            echo'           </table>
                            </div>
                            </center>  
                        </body>
                    </html>
                    ';            
        } 

    /*
     * Llamada a las funciones del constructor de interfaz. 
     */
    constructor();
?>