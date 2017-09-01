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
    
    if(!isset($_SESSION))
        {
            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
            session_name('phoenix');
            session_start();
            } 
    
    $imgTitleURL = './img/menu/evaluacion.png';
    $Title = 'Evaluaciones';
    $Sufijo = "fev_";
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $habcampos = '';
        
    $now = time(); //Se obtiene la referencia del tiempo actual del servidor.
    date_default_timezone_set("America/Mexico_City"); //Se establece el perfil del uso horario.
    $FechaCreacion = date("d/m/Y",$now).' '.date("h:i:sa",$now); //Se obtiene la referencia compuesta de fecha y hora.

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

    function cargarCedulas($idEntidad)
        {
            /*
             * Esta función establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= "SELECT idCedula, Folio FROM opCedulas WHERE Status=0 AND idEntidad=".$idEntidad; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
                        
    function cargarEmpleados($idEntidad)
        {
            /*
             * Esta función establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= "SELECT idEmpleado, CONCAT(Nombre, ' ',Paterno, ' ', Materno) AS Empleado FROM opEmpleados WHERE Status=0 AND idEntidad=".$idEntidad; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }

    function cargarEmpNoFiltro()
        {
            /*
             * Esta función establece la carga del conjunto de registros de empleados sin filtrado.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= "SELECT idEmpleado, CONCAT(Nombre, ' ',Paterno, ' ', Materno) AS Empleado FROM opEmpleados WHERE Status=0"; //Se establece el modelo de consulta de datos.
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
            $consulta= 'SELECT idEvaluacion, opEvaluaciones.idCedula, opCedulas.idEntidad, opEvaluaciones.idEmpleado, opEvaluaciones.Folio, opEvaluaciones.Fecha, opEvaluaciones.Status FROM ((opEvaluaciones LEFT JOIN opCedulas ON opCedulas.idCedula = opEvaluaciones.idCedula) LEFT JOIN catEntidades ON opCedulas.idEntidad = catEntidades.idEntidad) WHERE opEvaluaciones.idEvaluacion='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
            
    $Registro = @mysqli_fetch_array(cargarRegistro($parametro),MYSQLI_ASSOC);//Llamada a la función de carga de registro de usuario.

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
    
    function generarFolio($parametro)
        {
            /*
             * Esta funcion genera un folio pseudo aleatorio sin repeticion, que utiliza la fecha y hora como
             * base del patron y un numero aleatorio como control de concurrencias.
             */
            
            $folio = "EVF";
            $parametro = str_replace("/","",$parametro);
            $parametro = str_replace(" ","-",$parametro);
            $parametro = str_replace(":","",$parametro);
            $parametro = str_replace("pm","",$parametro);
            $parametro = str_replace("am","",$parametro);
            $folio.= '-'.$parametro.'-'.mt_rand(1,255);
            
            return $folio;
            }
            
    function displayEmpleadoCF($Registro)
        {
            /*
             * Para el caso que se solicite un despliegue de empleados con filtrado
             * por entidad asociada.
             */
            global $habcampos;
            
            echo'   <tr><td width="100px" class="td-panel">Empleado:</td>
                        <td>
                            <div id="cbEmpleados">
                                <select name= "fevempleado" id= "fevempleado" '.$habcampos.' value= "'.$Registro['idEmpleado'].'">';
            
                    $subconsulta = cargarEmpleados($Registro['idEntidad']);
            
            echo'                   <option value=-1>Seleccione</option>';
            
                    $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
            
                    while ($RegNiveles)
                        {
                            //Se ejecuta un recorrido de comparacion para determinar el item seleccionado.
                            if($RegNiveles['idEmpleado']==$Registro['idEmpleado'])
                                {
                                    //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                   <option value='.$RegNiveles['idEmpleado'].' selected="selected">'.$RegNiveles['Empleado'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se carga la etiqueta por default.
            echo'                   <option value='.$RegNiveles['idEmpleado'].'>'.$RegNiveles['Empleado'].'</option>';
                                    }
            
                            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
            
            echo'               </select>
                            </div>
                        </td>
                    </tr>';            
            }

    function displayEmpleadoSF($Registro)
        {
            /*
             * Para el caso que se solicite un despliegue de empleados sin filtrado
             * por entidad asociada.
             */
            global $habcampos;
            
            echo'   <tr><td width="100px" class="td-panel">Empleado:</td>
                        <td>
                            <div id="cbEmpleados">
                                <select name= "fevempleado" id= "fevempleado" '.$habcampos.' value= "'.$Registro['idEmpleado'].'">';
            
                    $subconsulta = cargarEmpNoFiltro();
            
            echo'                   <option value=-1>Seleccione</option>';
            
                    $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
            
                    while ($RegNiveles)
                        {
                            //Se ejecuta un recorrido de comparacion para determinar el item seleccionado.
                            if($RegNiveles['idEmpleado']==$Registro['idEmpleado'])
                                {
                                    //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                   <option value='.$RegNiveles['idEmpleado'].' selected="selected">'.$RegNiveles['Empleado'].'</option>';
                                    }
                            else
                                {
                                    //En caso contrario se carga la etiqueta por default.
            echo'                   <option value='.$RegNiveles['idEmpleado'].'>'.$RegNiveles['Empleado'].'</option>';
                                    }
            
                            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                            }
            
            echo'               </select>
                            </div>
                        </td>
                    </tr>';
            }
                        
    function constructor()
        {
            /*
             * Esta función establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod, $FechaCreacion, $habcampos;
            global $imgTitleURL, $Title;
            global $cntview;
            
            $habcampos = 'disabled= "disabled"';
            $FolioCedula = "";
            
            if(!empty($Registro['idEvaluacion']))
                {
                    //CASO: VISUALIZACION DE REGISTRO PARA SU EDICION O BORRADO.
                    if($cntview == 1)
                        {
                            //VISUALIZAR.
                            $habcampos = 'disabled= "disabled"';
                            }
                    else
                        {
                            //EDICION.
                            $habcampos = '';
                            }
                            
                    $FolioCedula=$Registro['Folio'];
                    $FechaCreacion=$Registro['Fecha'];                                                                                            
                    }
            else
                {
                    //CASO: CREACION DE NUEVO REGISTRO.
                    $habcampos = '';
                    $FolioCedula = generarFolio($FechaCreacion);
                    }            

            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        </head>
                        <body>                
                            <div style=display:none>
                                <input type= "text" id= "idEvaluacion" value="'.$Registro['idEvaluacion'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                                <div id="infoRegistro" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                    <div id="cuerpo" class="cuerpo-operativo">                                
                                        <table>
                                <tr><td class="td-panel" width="100px">Folio:</td><td><input type= "text" required= "required" id= "Folio" '.$habcampos.' value= "'.$FolioCedula.'"></td></tr>
                                <tr><td class="td-panel" width="100px">Fecha:</td><td><input type= "text" required= "required" id= "Fecha" '.$habcampos.' value= "'.$FechaCreacion.'"></td></tr>';
            
            echo'               <tr><td width="100px" class="td-panel">Entidad:</td><td><select name= "idEntfoda" id= "idEntfoda" '.$habcampos.' value= "'.$Registro['idEntidad'].'">';

                                $subconsulta = cargarEntidades();
                                
            echo'               <option value=-1>Seleccione</option>';
            
                                if($Registro['idEntidad']==-2)
                                    {
                                        //En caso que el registro corresponda de una cedula global.
            echo'                       <option value=-2 selected="selected">Global</option>';
                                        }
                                else
                                    {
                                        //En caso que el registro corresponda a una cedula especifica.
            echo'                       <option value=-2>Global</option>';
                                        }            
            
                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                
                                while ($RegNiveles)
                                    {
                                        //Se ejecuta un recorrido de comparacion para determinar el item seleccionado.                                                
                                        if($RegNiveles['idEntidad']==$Registro['idEntidad'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                               <option value='.$RegNiveles['idEntidad'].' selected="selected">'.$RegNiveles['Entidad'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo'                               <option value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'].'</option>';
                                                }
                                                
                                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
            
            echo'               </select></td></tr>';

                                if($Registro['idEntidad']==-2)
                                    {
                                        displayEmpleadoSF($Registro);
                                        }
                                else
                                    {
                                        displayEmpleadoCF($Registro);
                                        }   

            echo'               <tr><td width="100px" class="td-panel" >Cedula:</td><td><div id="cbCedulas"><select name= "idCedula" id= "idCedula" '.$habcampos.' value= "'.$Registro['idCedula'].'">';
            
                                $subconsulta = cargarCedulas($Registro['idEntidad']);
            
            echo'               <option value=-1>Seleccione</option>';
            
                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
            
                                while ($RegNiveles)
                                    {
                                        //Se ejecuta un recorrido de comparacion para determinar el item seleccionado.
                                        if($RegNiveles['idCedula']==$Registro['idCedula'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                               <option value='.$RegNiveles['idCedula'].' selected="selected">'.$RegNiveles['Folio'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo'                               <option value='.$RegNiveles['idCedula'].'>'.$RegNiveles['Folio'].'</option>';
                                                }
            
                                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
            
            echo'               </select></div></td></tr>';
                        
                                
            echo'           </table>
                                     </div>
                                    <div id="pie" class="pie-operativo">'.
                                        controlBotones("32", "32", $cntview).                
                                '   </div>  
                        </body>
                    </html>';            
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        constructor();
?>