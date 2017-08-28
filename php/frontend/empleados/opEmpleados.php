<?php
/*
 * Micrositio-Phoenix v1.0 Software para gestion de la planeacion operativa.
 * PHP v5
 * Autor: Prof. Jesus Antonio Peyrano Luna <antonio.peyrano@live.com.mx>
 * Nota aclaratoria: Este programa se distribuye bajo los terminos y disposiciones
 * definidos en la GPL v3.0, debidamente incluidos en el repositorio original.
 * Cualquier copia y/o redistribucion del presente, debe hacerse con una copia
 * adjunta de la licencia en todo momento.
 * Licencia: http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificacion a ISO-8859-1.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    
    if(!isset($_SESSION))
        {
            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
            session_name('phoenix');
            session_start();
            }
            
    $imgTitleURL = './img/menu/empleados.png';
    $Title = 'Empleados';    
    $Sufijo = "emp_";
    $habcampos = 'disabled= "disabled"';
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];

    
    function cargarPuestos($parametro)
        {
            /*
             * Esta funcion establece la carga del conjunto de registros de Puestos.
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
             * Esta funcion establece los parametros de carga del combobox de Puesto cuando
             * se ejecuta un proceso de edicion.
             */
            global $habcampos, $Registro;
    
            $subconsulta = cargarPuestos($parametro);
    
            echo'   <select name= "idPuesto" id= "idPuesto" '.$habcampos.' value= "'.$Registro['idPuesto'].'">
                        <option value=-1>Seleccione</option>';
    
            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
    
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
                    $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                    }
    
            echo' </select>';
            }
        
    function cargarColonias()
        {
            /*
             * Esta funcion establece la carga del conjunto de registros de colonias.
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
             * Esta funcion establece la carga del conjunto de registros de entidades.
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
             * Esta funcion establece la carga del conjunto de registros de entidades.
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
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM ((opEmpleados INNER JOIN catColonias ON catColonias.idColonia = opEmpleados.idColonia) INNER JOIN relUsrEmp ON relUsrEmp.idEmpleado = opEmpleados.idEmpleado) LEFT JOIN catUsuarios ON catUsuarios.idUsuario = relUsrEmp.idUsuario WHERE opEmpleados.idEmpleado='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
            
    $Registro = @mysqli_fetch_array(cargarRegistro($parametro),MYSQLI_ASSOC);//Llamada a la funci�n de carga de registro de usuario.

    function controlBotones($Width, $Height, $cntView)
        {
            /*
             * Esta funcion controla los botones que deberan verse en la pantalla deacuerdo con la accion solicitada por el
             * usuario en la ventana previa.
             * Si es una edicion, los botones borrar y guardar deben verse.
             * Si es una creacion solo el boton guardar debe visualizarse.
             */
            global $Sufijo;
            
            $botonera = '';
            $btnVolver_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/volver.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Volver" id="'.$Sufijo.'Volver" title= "Volver"/>';
            $btnBorrar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/erase.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Borrar" id="'.$Sufijo.'Borrar" title= "Borrar"/>';
            $btnGuardar_V =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$Sufijo.'Guardar" title= "Guardar"/>';
            $btnGuardar_H =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$Sufijo.'Guardar" title= "Guardar" style="display:none;"/>';
            $btnEditar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/edit.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Editar" id="'.$Sufijo.'Editar" title= "Editar"/>';            
            
            if(($cntView == 0)||($cntView == 2)||($cntView == 9))
                {
                    //CASO: CREACION O EDICION DE REGISTRO.
                    if($_SESSION['nivel'] == "Lector")
                        {
                            /*
                             * Si el usuario cuenta con un perfil de lector, se crea la referencia
                             * para el control de solo visualizacion.
                             */
                            $botonera .= $btnVolver_V;
                            }
                    else
                        {
                            if($_SESSION['nivel'] == "Administrador")
                                {
                                    $botonera .= $btnGuardar_V.$btnVolver_V;
                                    }
                            }                            
                    }
            else
                {
                    if($cntView == 1)
                        {
                            //CASO: VISUALIZACION CON OPCION PARA EDICION O BORRADO.
                            if($_SESSION['nivel'] == "Lector")
                                {
                                    /*
                                     * Si el usuario cuenta con un perfil de lector, se crea la referencia
                                     * para el control de solo visualizacion.
                                     */
                                    $botonera .= $btnVolver_V;
                                    }
                            else
                                {
                                    if($_SESSION['nivel'] == "Administrador")
                                        {
                                            $botonera .= $btnEditar_V.$btnBorrar_V.$btnGuardar_H.$btnVolver_V;
                                            }
                                    }
                            }
                    }
    
            return $botonera;
            }        
            
    function constructor()
        {
            /*
             * Esta funci�n establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod;
            global $imgTitleURL, $Title;
            global $cntview;
            
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
                            <div id="infoRegistro" class="operativo">
                                <div id="cabecera" class="cabecera-operativo">'.
                                    '<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                <div id="cuerpo" class="cuerpo-operativo">                                
                                    <table>
                                        <tr><td class="td-panel">Nombre: <input class="inputform" type= "text" required= "required" id= "Nombre" '.$habcampos.' value= "'.$Registro['Nombre'].'"></td><td class="td-panel">Paterno: <input class="inputform" type= "text" required= "required" id= "Paterno" '.$habcampos.' value= "'.$Registro['Paterno'].'"></td><td class="td-panel">Materno: <input class="inputform" type= "text" required= "required" id= "Materno" '.$habcampos.' value= "'.$Registro['Materno'].'"></td></tr>
                                        <tr><td colspan=3 class="td-panel">Calle: <input style="width:800px;" class="inputform" type= "text" required= "required" id= "Calle" '.$habcampos.' value= "'.$Registro['Calle'].'"></td></tr>';                                        
            echo'                       <tr>
                                            <td class="td-panel">Interior: <input style="width:100px;" class="inputform" type= "text" required= "required" id= "Nint" '.$habcampos.' value= "'.$Registro['Nint'].'"></td>
                                            <td class="td-panel">Exterior: <input style="width:100px;" class="inputform" type= "text" required= "required" id= "Next" '.$habcampos.' value= "'.$Registro['Next'].'"></td>
                                            <td class="td-panel">Colonia: <select class="inputform" name= "idColonia" id= "idColonia" '.$habcampos.' value= "'.$Registro['idColonia'].'">\'';
                                        $subconsulta = cargarColonias();
            echo'                           <option value=-1>Seleccione</option>';
                                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        while ($RegNiveles)
                                            {
                                                if($RegNiveles['idColonia']==$Registro['idColonia'])
                                                    {
                                                        //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                           <option value='.$RegNiveles['idColonia'].' selected="selected">'.$RegNiveles['Colonia'].'</option>';
                                                        }
                                                else
                                                    {
                                                        //En caso contrario se carga la etiqueta por default.
            echo'                           <option value='.$RegNiveles['idColonia'].'>'.$RegNiveles['Colonia'].'</option>';                                                
                                                        }
                                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                                }
                                
            echo'                           </select></td>
                                        </tr>';
            
            echo'                       <tr><td class="td-panel">Entidad: <select class="inputform" name= "idEntEmp" id= "idEntEmp" '.$habcampos.' value= "'.$Registro['idEntidad'].'">\'';
                                        $subconsulta = cargarEntidades();
            echo'                           <option value=-1>Seleccione</option>';
                                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        while ($RegNiveles)
                                            {
                                                if($RegNiveles['idEntidad']==$Registro['idEntidad'])
                                                    {
                                                        //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                           <option value='.$RegNiveles['idEntidad'].' selected="selected">'.$RegNiveles['Entidad'].'</option>';
                                                }
                                                else
                                                    {
                                                        //En caso contrario se carga la etiqueta por default.
            echo'                           <option value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'].'</option>';
                                                        }
                                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                                }
            
            echo'                           </select></td>';
                        
            echo'                       <td class="td-panel">Puesto: <div id="cbPuesto" border:none>';
                                        if($parametro == "-1")
                                            {
                                                /*
                                                 * Si la accion corresponde a la creacion de un registro nuevo,
                                                 * se establece el codigo actual.
                                                 */
            echo'                           <select class="inputform" id= "idPuesto" '.$habcampos.' ><option value=-1>Seleccione</option></select></div></td></tr>';
                                                }
                                        else
                                            {
                                                /*
                                                 * Si la accion ocurre para un registro existente,
                                                 * se preserva el codigo almacenado.
                                                 */
            echo                                constructorcbPuesto($Registro['idEntidad']).'</div></td></tr>';
                                                }
                                                    
            echo'                       <tr>
                                            <td class="td-panel">RFC: <input class="inputform" type= "text" required= "required" id= "RFC" '.$habcampos.' value= "'.$Registro['RFC'].'"></td>
                                            <td class="td-panel">CURP: <input class="inputform" type= "text" required= "required" id= "CURP" '.$habcampos.' value= "'.$Registro['CURP'].'"></td>
                                            <td class="td-panel">Telefono particular: <input class="inputform" type= "text" required= "required" id= "TelFijo" '.$habcampos.' value= "'.$Registro['TelFijo'].'"></td>
                                        </tr>
                                        <tr>
                                            <td class="td-panel">Telefono celular: <input class="inputform" type= "text" required= "required" id= "TelCel" '.$habcampos.' value= "'.$Registro['TelCel'].'"></td>';
            echo'                           <td class="td-panel">Usuario: <select class="inputform" name= "idUsuario" id= "idUsuario" '.$habcampos.' value= "'.$Registro['idUsuario'].'">\'';
                        
                                        $subconsulta = cargarUsuarios();
            echo'                           <option value=-1>Seleccione</option>';
                                        $RegUsuarios = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        while ($RegUsuarios)
                                            {
                                                if($RegUsuarios['idUsuario'] == $Registro['idUsuario'])
                                                    {
                                                        //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                           <option value='.$RegUsuarios['idUsuario'].' selected="selected">'.$RegUsuarios['Usuario'].'</option>';
                                                        }
                                                else
                                                    {
                                                        //En caso contrario se carga la etiqueta por default.
            echo'                           <option value='.$RegUsuarios['idUsuario'].'>'.$RegUsuarios['Usuario'].'</option>';
                                                        }
                                                $RegUsuarios = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                                }
            
            echo'                           </select></td></tr>
                                    </table>
                                </div>
                                <div id="pie" class="pie-operativo">'.
                                    controlBotones("32", "32", $cntview).                
                                '</div>    
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