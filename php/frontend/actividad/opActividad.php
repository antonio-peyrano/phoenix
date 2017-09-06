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
    
    $imgTitleURL = './img/menu/programas.png';
    $Title = 'Actividad';
    $Sufijo = "act_";            
    $parametro = $_GET['id'];
    $idPrograma = $_GET['idprograma'];
    $cntview = $_GET['view'];
    $clavecod = '';   

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

    function getMes($Mes)
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
                    
    function controlMonto()
        {
            /*
             * Esta funcion obtiene el monto asignado al programa.
             */
            global $username, $password, $servername, $dbname;
            global $idPrograma;
    
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT Monto FROM opProgramas WHERE idPrograma='.$idPrograma; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    
            $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
    
            return $Registro['Monto'];
            }
        
    function sigmaMontos($idPrograma, $activid)
        {
            /*
             * Esta funcion obtiene la suma acumulada de montos de las
             * actividades.
             */
            global $username, $password, $servername, $dbname;
    
            $sumMonto = 0;
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            if($activid == -1)
                {
                    /*
                     * Si el proceso corresponde a un registro de nueva creacion.
                     */
                    $consulta = 'SELECT Monto FROM opActividades WHERE idPrograma='.$idPrograma; //Se establece el modelo de consulta de datos.
                    }
            else
                {
                    /*
                     * Si el proceso corresponde a un registro existente.
                     */
                    $consulta = 'SELECT Monto FROM opActividades WHERE idPrograma='.$idPrograma.' AND idActividad NOT LIKE '.$activid; //Se establece el modelo de consulta de datos.
                    }
                            
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    
            $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
    
            while($Registro)
                {
                    /*
                     * Mientras existan elementos en la conexion, se debe obtener el Monto y agregarlo
                     * a la sumatoria.
                     */
                    $sumMonto = $sumMonto + $Registro['Monto'];
                    $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                    }            
            return $sumMonto;
            }
        
    function cargarUnidades()
        {
            /*
             * Esta funcion establece la carga del conjunto de registros de unidades.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idUnidad, Unidad FROM catUnidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
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
            $consulta= 'SELECT *FROM opActividades WHERE idActividad='.$idRegistro; //Se establece el modelo de consulta de datos.
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
             * Esta funcion establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod;
            global $username, $password, $servername, $dbname, $cntview;           
            global $idPrograma, $periodo,$rowBanderas;
            global $imgTitleURL, $Title;
            global $cntview;
                        
            $habcampos = 'disabled= "disabled"';
            
            if($Registro['idActividad'] == null)
                {
                    //En caso que el registro sea de nueva creacion.
                    $habcampos='';        
                    $activid= -1;
                    }
            else
                {
                    //En caso contrario se asigan el id de la actividad al temporal.
                    $activid= $Registro['idActividad']; 
                    }
                    
            $MontoAcumulado = sigmaMontos($idPrograma,$activid);
            $MontoPrograma = controlMonto(); 
                               
            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                            <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                        </head>
                        <body>
                            <div style="display:none">
                                <input type= "text" id= "pagina" value= "1">
                                <input type= "text" id= "idActividad" value= "'.$Registro['idActividad'].'">
                                <input type= "text" id= "idPrograma" value= "'.$idPrograma.'">
                                <input type= "text" id= "MontoAcumulado" value= "'.$MontoAcumulado.'">
                                <input type= "text" id= "MontoPrograma" value= "'.$MontoPrograma.'">        
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                            <div id="infoRegistro" class="operativo" style="top:70%; left:50%;">
                                <div id="cabecera" class="cabecera-operativo">'.
                                    '<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.
                                '</div>
                                <div id="cuerpo" class="cuerpo-operativo">
                                    <center><table>
                                <tr><td class="td-panel" width="100px">Actividad: <input style="width:500px;" class="inputform" type= "text" required= "required" id= "Actividad" '.$habcampos.' value= "'.$Registro['Actividad'].'"></td>
                                    <td class="td-panel" width="100px">Unidad: <select style="width:300px;" class="inputform" name= "idUnidad" id= "idUnidad" '.$habcampos.' value= "-1">';
                                
                                $subconsulta = cargarUnidades();
                                
            echo '              <option value=-1>Seleccione</option>';
            
                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                while ($RegNiveles)
                                    {
                                        if($RegNiveles['idUnidad']==$Registro['idUnidad'])
                                            {
                                                /*
                                                 * En caso que se ejecute una accion de consulta, se obtiene la referencia seleccionada
                                                 * de la unidad.
                                                 */
            echo '                              <option value='.$RegNiveles['idUnidad'].' selected="selected">'.$RegNiveles['Unidad'].'</option>';
                                                }
                                        else
                                            {
                                                /*
                                                 * En caso que se ejecute una accion de creacion de registro.
                                                 */
            echo'                               <option value='.$RegNiveles['idUnidad'].'>'.$RegNiveles['Unidad'].'</option>';
                                                }
                                                
                                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
            
            echo'               </select></td></tr>';                                
                                                
            echo'               <tr><td class="td-panel" width="100px">Monto: <input style="width:120px; text-align:right" class="inputform" type= "text" required= "required" id= "Monto" '.$habcampos.' value= "'.$Registro['Monto'].'"></td>';

                                if($parametro=="-1")
                                    {
                                        /*
                                         * Si la accion corresponde a la creacion de un registro nuevo,
                                         * se establece el año actual.
                                         */
                                        echo '<td class="td-panel" width="100px">Periodo: <input style="width:60px;" class="inputform" type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$periodo.'"></td></tr>';
                                        }
                                else
                                    {
                                        /*
                                         * Si la accion ocurre para un registro existente,
                                         * se preserva el año almacenado.
                                         */
                                        echo '<td class="td-panel" width="100px">Periodo: <input style="width:60px;" class="inputform" type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$Registro['Periodo'].'"></td></tr>';
                                        }                                        
                                                                                                        
            echo'           </table></center>
                            <br>';

                            $nonhabilitado = 'disabled= "disabled"';
            
            echo'           <div id= "dataact">
                                <table>
                                    <tr><th class="dgHeader-Planeacion" colspan= "14">Datos de la Actividad</th></tr>
                                    <tr><td></td><td class="dgDH-Planeacion">Enero</td><td class="dgDH-Planeacion">Febrero</td><td class="dgDH-Planeacion">Marzo</td><td class="dgDH-Planeacion">Abril</td><td class="dgDH-Planeacion">Mayo</td><td class="dgDH-Planeacion">Junio</td><td class="dgDH-Planeacion">Julio</td><td class="dgDH-Planeacion">Agosto</td><td class="dgDH-Planeacion">Septiembre</td><td class="dgDH-Planeacion">Octubre</td><td class="dgDH-Planeacion">Noviembre</td><td class="dgDH-Planeacion">Diciembre</td><td class="dgDH-Planeacion">Total</td></tr>';
            
                                    //Se procede con la carga de la programacion que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opProgAct WHERE status= 0 AND idActividad= '.$Registro['idActividad'];
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
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$habcampos.' id="P_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = mysqli_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }
                            
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="P_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                            }
                                    else
                                        {
                                            //Para el caso de una creacion de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td><input type="text" '.$habcampos.' id="P_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="P_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }
                                            
                                    echo $rowdata;
            
                                    //Se procede con la carga de la ejecucion que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEjecAct WHERE status= 0 AND idActividad= '.$Registro['idActividad'];
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
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="E_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = mysqli_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }
                            
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="E_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                            
                                            }
                                    else
                                        {
                                            //Para el caso de una creacion de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="E_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="E_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }                    

                                    echo $rowdata;
            
                                    //Se procede con la carga de la eficacia que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEficAct WHERE status= 0 AND idActividad= '.$Registro['idActividad'];
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
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="Efic_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    cargarBanderas($RegAux[$field->name], $count);//Se genera la fila de banderas.
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = mysqli_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }
                                            $totEficacia = $totEficacia/12.00;
                                            cargarBanderas($totEficacia, $count);//Se genera la fila de banderas.
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="Efic_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                            
                                            }
                                    else
                                        {
                                            //Para el caso de una creacion de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="Efic_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    cargarBanderas(0.00, $counter);//Se genera la fila de banderas.
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td><input class="input-planeacion" type="text" id="Efic_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }                    
                                            
                                    echo $rowdata;
                                    echo '<tr><td class="dgDH-Planeacion">Estado</td>'.$rowBanderas.'</tr>';
            
            echo'               </table>
                            </div>';
                        
            echo'       
                            <br>
                            <div id= "dataejecuciones">';
            
                            $_GET['idactividad'] = $Registro['idActividad'];
                            
                            if($cntview == 3)
                                {
                                    /*
                                     * En caso que el invocador sea el formulario de actividades.
                                     */
                                    include_once("../../frontend/ejecuciones/catEjecucion.php");
                                    }
                            else
                                {
                                    /*
                                     * En caso que el invocador sea el formulario de programa.
                                     */
                                    include_once("../ejecuciones/catEjecucion.php");
                                    }
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