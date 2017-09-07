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
        
    $parametro = $_GET['id'];

    $imgTitleURL = './img/menu/programas.png';
    $Title = 'Programacion';
    $Sufijo = "prg_";
    $Optimo = 0; //El valor de limite superior para la eficacia.
    $Tolerable = 0; //El valor de limite inferior para la eficacia.
    $Periodo = ''; //El valor del periodo a visualizar.
    $rowBanderas = '';
    
    function obtenerPerfilSys()
        {
            /*
             * Esta funcion obtiene el perfil del sistema activo para el despliegue de la
             * informacion de la planeacion.
             */
             global $username, $password, $servername, $dbname;
             global $Periodo, $Optimo, $Tolerable;
             
             $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
             $consulta= "SELECT *FROM catConfiguraciones WHERE Status=0"; //Se establece el modelo de consulta de datos.
             $dsConfiguracion = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
             $RegConfiguracion = @mysqli_fetch_array($dsConfiguracion,MYSQLI_ASSOC);
             
             if($RegConfiguracion)
                {
                    //Si ha sido localizada una configuracion valida.
                    $Optimo = $RegConfiguracion['Optimo'];
                    $Tolerable = $RegConfiguracion['Tolerable'];
                    $Periodo = $RegConfiguracion['Periodo'];
                    }
            }
    
    function cargarBanderas($parametro, $mes)
        {
            /*
             * Esta funcion carga la parte grafica que corresponde a las banderas de desempeño.
             */
            global $Periodo, $Optimo, $Tolerable, $rowBanderas;
            
            if($parametro>=$Optimo)
                {
                    //Si el parametro recibido esta en el rango de medicion optima.
                    $rowBanderas.='<td><center><img id="optimo_'.$mes.'"align= "middle" src= "./img/banderas/optimo.png" width= "25" height= "25" alt= "Optimo" data-toggle="tooltip" title="Eficacia >='.$Optimo.'%"/></center></td>';
                    } 
                    
            if(($parametro>=$Tolerable)&&($parametro<$Optimo))
                {
                    //Si el parametro recibido esta dentro del rango tolerable.
                    $rowBanderas.='<td><center><img id="riesgo_'.$mes.'"align= "middle" src= "./img/banderas/riesgo.png" width= "25" height= "25" alt= "Riesgo" data-toggle="tooltip" title="Eficacia >='.$Tolerable.'%"/></center></td>';                    
                    }
                    
            if($parametro<$Tolerable)
                {
                    //Si el parametro recibido esta por debajo de lo tolerable.
                    $rowBanderas.='<td><center><img id="falla_'.$mes.'"align= "middle" src= "./img/banderas/falla.png" width= "25" height= "25" alt= "Falla" data-toggle="tooltip" title="Eficacia <'.$Tolerable.'%"/></center></td>';                    
                    }             
            }
                
    if(isset($_GET['view']))
        {
            /*
             * Si se declaro en la url el control de visualizacion.
             */
            $cntview = $_GET['view'];
            }
                
    $now = time();
    $periodo = date("Y",$now);
    $habcampos = 'disabled= "disabled"';

    function obtenerMes($Mes)
        {
            /*
             * Esta funcion obtiene el nombre del mes apartir de su cardinal numerico.
             */
            if($Mes == "1")
                {
                    return "Enero";
                    }
            if($Mes == "2")
                {
                    return "Febrero";
                    }
            if($Mes == "3")
                {
                    return "Marzo";
                    }
            if($Mes == "4")
                {
                    return "Abril";
                    }
            if($Mes == "5")
                {
                    return "Mayo";
                    }
            if($Mes == "6")
                {
                    return "Junio";
                    }
            if($Mes == "7")
                {
                    return "Julio";
                    }
            if($Mes == "8")
                {
                    return "Agosto";
                    }
            if($Mes == "9")
                {
                    return "Septiembre";
                    }
            if($Mes == "10")
                {
                    return "Octubre";
                    }
            if($Mes == "11")
                {
                    return "Noviembre";
                    }
            if($Mes == "12")
                {
                    return "Diciembre";
                    }
            }
                        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM opProgramas WHERE idPrograma='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
            
    $Registro = @mysqli_fetch_array(cargarRegistro($parametro),MYSQLI_ASSOC);//Llamada a la funcion de carga de registro de usuario.

    function controlBotones($Width, $Height, $cntView)
        {
            /*
             * Esta funcion controla los botones que deberan verse en la pantalla deacuerdo con la accion solicitada por el
             * usuario en la ventana previa.
             * Si es una edicion, los botones borrar y guardar deben verse.
             * Si es una creacion solo el boton guardar debe visualizarse.
             */
            global $Sufijo, $parametro;
    
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
                            if(($_SESSION['nivel'] == "Administrador") || (validarUsuario($_SESSION['idusuario'], $parametro) > 0))
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
                                    if(($_SESSION['nivel'] == "Administrador") || (validarUsuario($_SESSION['idusuario'], $parametro) > 0))
                                        {
                                            $botonera .= $btnEditar_V.$btnBorrar_V.$btnGuardar_H.$btnVolver_V;
                                            }
                                    }
                            }
                    }
    
            return $botonera;
            }

    function validarUsuario($idUsuario, $idPrograma)
        {
            /*
             * Esta funcion valida que el usuario este definido como responsable
             * o auxiliar del proceso.
             */
            global $username, $password, $servername, $dbname;
            $validacion = 0;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $conResponsable = 'SELECT * FROM (catUsuarios INNER JOIN relUsrEmp ON catUsuarios.idUsuario = relUsrEmp.idUsuario) INNER JOIN opProgramas ON opProgramas.idResponsable = relUsrEmp.idEmpleado WHERE catUsuarios.idUsuario ='.$idUsuario.' AND opProgramas.idPrograma='.$idPrograma; //Se establece el modelo de consulta de datos.
            $dsResponsable = $objConexion -> conectar($conResponsable); //Se ejecuta la consulta.
            
            if(@mysqli_num_rows($dsResponsable)>0)
                {
                    $validacion +=1;
                    }
                    
            $conSubAlterno = 'SELECT * FROM (catUsuarios INNER JOIN relUsrEmp ON catUsuarios.idUsuario = relUsrEmp.idUsuario) INNER JOIN opProgramas ON opProgramas.idSubalterno = relUsrEmp.idEmpleado WHERE catUsuarios.idUsuario ='.$idUsuario.' AND opProgramas.idPrograma='.$idPrograma; //Se establece el modelo de consulta de datos.
            $dsSubAlterno = $objConexion -> conectar($conSubAlterno); //Se ejecuta la consulta.

            if(@mysqli_num_rows($dsSubAlterno)>0)
                {
                    $validacion +=1;
                    }
                        
            return $validacion;
            } 
                
    function cargarObjEst()
        {
            /*
             * Esta funcion establece la carga del conjunto de registros de ObjEst.
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
             * Esta funcion establece la carga del conjunto de registros de ObjOpe.
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
             * Esta funcion establece los parametros de carga del combobox de obj ope cuando
             * se ejecuta un proceso de edicion.
             */
            global $habcampos, $Registro;
    
            $subconsulta = cargarObjOpe($parametro);
    
            echo' <select style="width:300px;" class="inputform" name= "idObjOpe" id= "idObjOpe" '.$habcampos.' value= "'.$Registro['idObjOpe'].'">
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

    function cargarEstOpe($parametro)
        {
            /*
             * Esta funcion establece la carga del conjunto de registros de ObjOpe.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEstOpe, CONCAT(Nomenclatura,\' \',EstOpe) AS CEstOpe FROM catEstOpe WHERE Status=0 AND idObjOpe='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }            

    function constructorcbEstOpe($parametro)
        {
            /*
             * Esta funcion establece los parametros de carga del combobox de Est ope cuando
             * se ejecuta un proceso de edicion.
             */
            global $habcampos, $Registro;
            
            $subconsulta = cargarEstOpe($parametro);
            
            echo' <select style="width:300px;" class="inputform" name= "idEstOpe" id= "idEstOpe" '.$habcampos.' value= "'.$Registro['idEstOpe'].'">
                  <option value=-1>Seleccione</option>';
            
            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
            
            while($RegNiveles)
                {
                    if($RegNiveles['idEstOpe']==$Registro['idEstOpe'])
                        {
                            //En caso que el valor almacenado coincida con uno de los existentes en lista.
                            echo '          <option value='.$RegNiveles['idEstOpe'].' selected="selected">'.$RegNiveles['CEstOpe'].'</option>';
                            }
                    else
                        {
                            //En caso contrario se carga la etiqueta por default.
                            echo '          <option value='.$RegNiveles['idEstOpe'].'>'.$RegNiveles['CEstOpe'].'</option>';
                            }
                    $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                    }
                    
                    echo' </select>';
            }

    function cargarEmpleado($parametro)
        {
            /*
             * Esta funcion establece la carga del conjunto de registros de Empleados.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEmpleado, CONCAT(Nombre,\' \',Paterno,\' \',Materno) AS Empleado FROM opEmpleados WHERE Status=0 AND idEntidad='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
                        
    function constructorcbResponsable($parametro)
        {
            /*
             * Esta funcion establece los parametros de carga del combobox de Responsable cuando
             * se ejecuta un proceso de edicion.
             */
             global $habcampos, $Registro;
            
             $subconsulta = cargarEmpleado($parametro);
            
             echo' <select style="width:300px;" class="inputform" name= "idResponsable" id= "idResponsable" '.$habcampos.' value= "'.$Registro['idResponsable'].'">
                   <option value=-1>Seleccione</option>';
             
                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                
                while($RegNiveles)
                    {
                        if($RegNiveles['idEmpleado']==$Registro['idResponsable'])
                            {
                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
                                echo '          <option value='.$RegNiveles['idEmpleado'].' selected="selected">'.$RegNiveles['Empleado'].'</option>';
                                }
                        else
                            {
                                //En caso contrario se carga la etiqueta por default.
                                echo '          <option value='.$RegNiveles['idEmpleado'].'>'.$RegNiveles['Empleado'].'</option>';
                                }
                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                        }
                echo' </select>';
            }

    function constructorcbSubalterno($parametro)
        {
            /*
             * Esta funcion establece los parametros de carga del combobox de Subalterno cuando
             * se ejecuta un proceso de edicion.
             */
             global $habcampos, $Registro;
            
             $subconsulta = cargarEmpleado($parametro);
            
             echo' <select style="width:300px;" class="inputform" name= "idSubalterno" id= "idSubalterno" '.$habcampos.' value= "'.$Registro['idSubalterno'].'">
             <option value=-1>Seleccione</option>';
             
             $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
             
                while($RegNiveles)
                    {
                        if($RegNiveles['idEmpleado']==$Registro['idSubalterno'])
                            {
                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
                                echo '          <option value='.$RegNiveles['idEmpleado'].' selected="selected">'.$RegNiveles['Empleado'].'</option>';
                                }
                        else
                            {
                                //En caso contrario se carga la etiqueta por default.
                                echo '          <option value='.$RegNiveles['idEmpleado'].'>'.$RegNiveles['Empleado'].'</option>';
                                }
                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                        }
                        
                echo' </select>';
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

    function cargarProcesos($idRegistro, $idEntidad)
        {
            /*
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */
             global $username, $password, $servername, $dbname, $habcampos;
            
             $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
             $consulta= 'SELECT *FROM (catProcesos INNER JOIN relEntPro ON relEntPro.idProceso = catProcesos.idProceso) INNER JOIN catEntidades ON catEntidades.idEntidad = relEntPro.idEntidad WHERE relEntPro.Status=0 AND relEntPro.idEntidad='.$idEntidad; //Se establece el modelo de consulta de datos.
             $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
             if($idRegistro == -1)
                {
                    /*
                     * Si la operacion solicitada es para la creacion de un registro,
                     * se carga el listado sin marcar.
                     */
                    echo '<tr><td class="td-panel" width="100px">Procesos: <div id="chkProcesos" style="height:100px; overflow:scroll;">';
                    }
             else
                {
                    /*
                     * Si la operacion solicitada es para editar el registro,
                     * se carga el listado con los elementos previamente marcados.
                     */
                    echo '<tr><td class="td-panel" width="100px">Procesos: <div id="chkProcesos" style="height:100px; overflow:scroll;">';
                    $subconsulta = 'SELECT *FROM relProgPro WHERE idPrograma='.$idRegistro.' AND Status=0'; //Se establece el modelo de consulta de datos.
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
                             * los id de las Procesoes seleccionadas por el usuario previamente.
                             */
                            $vector.=','.$RegNiveles['idProceso'];
                            $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                            }
            
                    $tmparray=explode(',',$vector); //El vector resultante se convierte en un arreglo.
            
                    $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
            
                    while($RegNiveles)
                        {
                            /*
                             * Mientras no se llegue al final de la coleccion, se procede a la lectura
                             * y generacion del listado.
                             */
                            if(in_array($RegNiveles['idProceso'], $tmparray,true))
                                {
                                    /*
                                     * En caso de tratarse de una opcion previamente seleccionada por el usuario.
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
                        
    function constructor()
        {
            /*
             * Esta funcion establece los parametros para la creacion de la interfaz de usuario.
             */
            
            global $Registro, $parametro, $clavecod, $habcampos, $periodo, $cntview;            
            global $username, $password, $servername, $dbname, $rowBanderas;
            global $imgTitleURL, $Title;
            global $cntview;
            
            if($Registro['idPrograma'] == null)
                {
                    //En caso que el registro sea de nueva creacion.
                    $habcampos='';
                    }
                        
            echo '  <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                            <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                        </head>
                        <body>
                            <div style="display:none">
                                <input type= "text" id= "pagina" value="1">
                                <input type= "text" id= "idPrograma" value="'.$Registro['idPrograma'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>                 
                            <div id="infoRegistro" class="operativo" style="top:80%; left:50%;">
                                <div id="cabecera" class="cabecera-operativo">'.
                                    '<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.
                                '</div>
                                <div id="cuerpo" class="cuerpo-operativo">
                                    <center><table>';

                                        $subconsulta = cargarObjEst();
                                    
            echo '                      <tr><td class="td-panel" width="100px">Objetivo Estrategico: <select style="width:300px;" class="inputform" name= "idObjEst" id= "idObjEst" '.$habcampos.' value= "'.$Registro['idObjEst'].'">
                                            <option value=-1>Seleccione</option>';
            
                                            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                    
                                            while($RegNiveles)
                                                {
                                                    if($RegNiveles['idObjEst']==$Registro['idObjEst'])
                                                        {
                                                            //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo '                          <option value='.$RegNiveles['idObjEst'].' selected="selected">'.$RegNiveles['CObjEst'].'</option>';
                                                            }
                                                    else
                                                        {
                                                            //En caso contrario se carga la etiqueta por default.
            echo '                          <option value='.$RegNiveles['idObjEst'].'>'.$RegNiveles['CObjEst'].'</option>';
                                                    }
                                            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                            }
            
            echo'                           </select></td>            
                                        <td class="td-panel" width="100px">Objetivo Operativo: <div id="cbObjOpe" border:none>';
            
                                            if($parametro=="-1")
                                                {
                                                    /*
                                                     * Si la accion corresponde a la creacion de un registro nuevo,
                                                     * se establece el codigo actual.
                                                     */
            echo'                           <select style="width:300px;" class="inputform" id= "idObjOpe"><option value=-1>Seleccione</option></select></div></td>';
                                                    }
                                            else
                                                {
                                                    /*
                                                     * Si la accion ocurre para un registro existente,
                                                     * se preserva el codigo almacenado.
                                                     */
                                            echo constructorcb($Registro['idObjEst']).'</div></td>';
                                                    }
                                                        
            echo'                       <td class="td-panel" width="100px">Est. Operativa: <div id="cbEstOpe" border:none>';
            
                                            if($parametro=="-1")
                                                {
                                                    /*
                                                     * Si la accion corresponde a la creacion de un registro nuevo,
                                                     * se establece el codigo actual.
                                                     */
            echo'                           <select style="width:300px;" class="inputform" id= "idEstOpe"><option value=-1>Seleccione</option></select></div></td></tr>';
                                                    }
                                            else
                                                {
                                                    /*
                                                     * Si la accion ocurre para un registro existente,
                                                     * se preserva el codigo almacenado.
                                                     */
                                            echo constructorcbEstOpe($Registro['idObjOpe']).'</div></td></tr>';
                                                    }
                        
                                            if($parametro=="-1")
                                                {
                                                     /*
                                                      * Si la accion corresponde a la creacion de un registro nuevo,
                                                      * se establece el codigo actual.
                                                      */
            echo'                       <tr><td class="td-panel" width="100px">Nomenclatura: <input  style="width:80px;" class="inputform" type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= ""></td>';
                                                    }
                                            else
                                                {
                                                    /*
                                                     * Si la accion ocurre para un registro existente,
                                                     * se preserva el codigo almacenado.
                                                     */
            echo'                       <tr><td class="td-panel" width="100px">Nomenclatura: <input  style="width:100px;" class="inputform" type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= "'.$Registro['Nomenclatura'].'"></td>';
                                                    }
                                            
            echo'                       <td class="td-panel" width="100px">Programa: <input style="width:300px;" class="inputform" type= "text" id= "Programa" '.$habcampos.' value= "'.$Registro['Programa'].'"></td>
                                        <td class="td-panel" width="100px">Monto: $ <input style="width:200px;" class="inputform" type= "text" id= "Monto" '.$habcampos.' value= "'.$Registro['Monto'].'"></td></tr>';
            
            echo'                       <tr><td class="td-panel" width="100px">Entidades: <select style="width:300px;" class="inputform" name= "idEntidad" id= "idEntidad" '.$habcampos.' value= "'.$Registro['idEntidad'].'">\'';
            
                                            $subconsulta = cargarEntidades();
                                    
            echo'                           <option value=-1>Seleccione</option>';
            
                                            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                    
                                            while($RegNiveles)
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
            
            echo'                       <td class="td-panel" width="100px">Responsable: <div id="cbResponsable" border:none>';
            
                                        if($parametro=="-1")
                                            {
                                                /*
                                                 * Si la accion corresponde a la creacion de un registro nuevo,
                                                 * se establece el codigo actual.
                                                 */
            echo'                           <select style="width:300px;" class="inputform" id= "idResponsable"><option value=-1>Seleccione</option></select></div></td>';
                                                }
                                        else
                                            {
                                                /*
                                                 * Si la accion ocurre para un registro existente,
                                                 * se preserva el codigo almacenado.
                                                 */
                                            echo constructorcbResponsable($Registro['idEntidad']).'</div></td>';
                                                }            

            echo'                       <td class="td-panel" width="100px">Auxiliar: <div id="cbSubalterno" border:none>';
                                            
                                    if($parametro=="-1")
                                        {
                                            /*
                                             * Si la accion corresponde a la creacion de un registro nuevo,
                                             * se establece el codigo actual.
                                             */
                                             echo'<select style="width:300px;" class="inputform" id= "idSubalterno"><option value=-1>Seleccione</option></select></div></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la accion ocurre para un registro existente,
                                             * se preserva el codigo almacenado.
                                             */
                                             echo constructorcbSubalterno($Registro['idEntidad']).'</div></td></tr>';
                                            }
                                                                                        
            
                                    cargarProcesos($parametro, $Registro['idEntidad']);
                                    
                                    if($parametro=="-1")
                                        {
                                            /*
                                             * Si la accion corresponde a la creacion de un registro nuevo,
                                             * se establece el periodo actual.
                                             */
                                            echo '<tr><td class="td-panel" width="100px">Periodo: <input style="width:100px;" class="inputform" type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$periodo.'"></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la accion ocurre para un registro existente,
                                             * se preserva el periodo almacenado.
                                             */
                                            echo '<tr><td class="td-panel" width="100px">Periodo: <input style="width:100px;" class="inputform" type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$Registro['Periodo'].'"></td></tr>';
                                            }                        
                                                                                    
            echo'                   </table></center>
                            <br>
                            <br>';
            
                            $nonhabilitado = 'disabled= "disabled"';
                            
            echo'           <div id="dataprog">
                                <table>
                                    <tr><th class="dgHeader-Planeacion" colspan= "14">Datos del Programa</th></tr>
                                    <tr><td></td><td class="dgDH-Planeacion">Enero</td><td class="dgDH-Planeacion">Febrero</td><td class="dgDH-Planeacion">Marzo</td><td class="dgDH-Planeacion">Abril</td><td class="dgDH-Planeacion">Mayo</td><td class="dgDH-Planeacion">Junio</td><td class="dgDH-Planeacion">Julio</td><td class="dgDH-Planeacion">Agosto</td><td class="dgDH-Planeacion">Septiembre</td><td class="dgDH-Planeacion">Octubre</td><td class="dgDH-Planeacion">Noviembre</td><td class="dgDH-Planeacion">Diciembre</td><td class="dgDH-Planeacion">Total</td></tr>';
                                    
                                    //Se procede con la carga de la programacion que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opProgPro WHERE status= 0 AND idPrograma= '.$Registro['idPrograma'];
                                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                                    $dsCampos = $subdataset;                                    
                                    $RegAux = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);

                                    if($dsCampos)
                                        {
                                            $field = mysqli_fetch_field($dsCampos);
                                            }
                                            
                                    $rowdata='<tr><td class="dgDH-Planeacion">Programacion</td>';
                                    $count=1;
                                    $totEficacia=0.00;
                                    
                                    if($RegAux)
                                        {
                                            //Para el caso de una consulta de datos.
                                            while($field)
                                                {
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="pr_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = mysqli_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }
                            
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="pr_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                            }
                                    else
                                        {
                                            //Para el caso de una creacion de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="pr_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="pr_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }
                                            
                                    echo $rowdata;
            
                                    //Se procede con la carga de la ejecucion que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEjecPro WHERE status= 0 AND idPrograma= '.$Registro['idPrograma'];
                                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                                    $dsCampos = $subdataset;                                    
                                    $RegAux = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);

                                    if($dsCampos)
                                        {
                                            $field = mysqli_fetch_field($dsCampos);
                                            }
                                            
                                    $rowdata='<tr><td class="dgDH-Planeacion">Ejecucion</td>';
                                    $count=1;
                                    $totEficacia=0;
                                    
                                    if($RegAux)
                                        {
                                            //Para el caso de una consulta de datos.
                                            while($field)
                                                {
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="ej_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = mysqli_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }
                            
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="ej_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                            
                                            }
                                    else
                                        {
                                            //Para el caso de una creacion de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="ej_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="ej_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }                    

                                    echo $rowdata;
            
                                    //Se procede con la carga de la eficacia que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEficPro WHERE status= 0 AND idPrograma= '.$Registro['idPrograma'];
                                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                                    $dsCampos = $subdataset;                                    
                                    $RegAux = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);

                                    if($dsCampos)
                                        {
                                            $field = mysqli_fetch_field($dsCampos);
                                            }
                                            
                                    $rowdata='<tr><td class="dgDH-Planeacion">Eficacia</td>';
                                    $count=1;
                                    $totEficacia=0;
                                    
                                    if($RegAux)
                                        {
                                            //Para el caso de una consulta de datos.
                                            while($field)
                                                {
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="efic_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    cargarBanderas($RegAux[$field->name], $count);//Se genera la fila de banderas.                                                    
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = mysqli_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }                                            
                                            $totEficacia = $totEficacia/12.00;
                                            cargarBanderas($totEficacia, $count);//Se genera la fila de banderas.
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="efic_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                            
                                            }
                                    else
                                        {
                                            //Para el caso de una creacion de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="efic_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    cargarBanderas(0.00, $counter);//Se genera la fila de banderas.
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="efic_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }                    
                                            
                                    echo $rowdata;
                                    echo '<tr><td class="dgDH-Planeacion">Estado</td>'.$rowBanderas.'</tr>';
                                                            
            echo'                   </table>
                            </div>';
            echo'       
                            <br>
                            <div id= "datatareas">';
            
                            $_GET['idprograma'] = $Registro['idPrograma'];
                            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/actividad/catActividad.php");

            echo'           </div>
                                </div>
                                <div id="pie" class="pie-operativo">'.
                                    controlBotones("32", "32", $cntview).                
                                '</div>                
                        </body>                
                    </html>';
            }
            
    /*
     * Llamada a las funciones del constructor de interfaz. 
     */            
    obtenerPerfilSys();            
    constructor();
?>