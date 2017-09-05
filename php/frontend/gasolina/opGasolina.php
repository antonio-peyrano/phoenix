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
    
    $imgTitleURL = './img/menu/gasolina.png';
    $Title = 'Gasolina';
    $Sufijo = "gas_";
    $parametro = $_GET['id'];
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
             * Esta funcion carga la parte grafica que corresponde a las banderas de consumo.
             */
            global $Periodo, $Optimo, $Tolerable, $rowBanderas;
            
            if($parametro>=$Optimo)
                {
                    //Si el parametro recibido esta en el rango de medicion optima.
                    $rowBanderas.='<td><center><img id="falla_'.$mes.'"align= "middle" src= "./img/banderas/falla.png" width= "25" height= "25" alt= "Falla" data-toggle="tooltip" title="Consumo critico"/></center></td>';
                    } 
                    
            if(($parametro>=$Tolerable)&&($parametro<$Optimo))
                {
                    //Si el parametro recibido esta dentro del rango tolerable.
                    $rowBanderas.='<td><center><img id="riesgo_'.$mes.'"align= "middle" src= "./img/banderas/riesgo.png" width= "25" height= "25" alt= "Riesgo" data-toggle="tooltip" title="Consumo en margen critico"/></center></td>';                    
                    }
                    
            if($parametro<$Tolerable)
                {
                    //Si el parametro recibido esta por debajo de lo tolerable.
                    $rowBanderas.='<td><center><img id="optimo_'.$mes.'"align= "middle" src= "./img/banderas/optimo.png" width= "25" height= "25" alt= "Optimo" data-toggle="tooltip" title="Consumo moderado"/></center></td>';
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
        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM (catEntidades INNER JOIN opProgGas ON catEntidades.idEntidad = opProgGas.idEntidad) WHERE opProgGas.idProgGas='.$idRegistro; //Se establece el modelo de consulta de datos.
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
    
    function controlVisual($idRegistro)
        {
            /*
             * Esta funcion controla los botones que deberan verse en la pantalla deacuerdo con la accion solicitada por el
             * usuario en la ventana previa. Si es una edicion, los botones borrar y guardar deben verse. Si es una creacion
             * solo el boton guardar debe visualizarse.
             */
            global $cntview;
            
            if($idRegistro == -1)
                {
                    //En caso que la accion corresponda a la creacion de un nuevo registro.
                    echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/gasolina/busGasolina.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarGasolina(\'./php/backend/gasolina/guardar.php\',\'?id=\'+document.getElementById(\'idProgGas\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&p_1=\'+document.getElementById(\'P_1\').value.toString()+\'&p_2=\'+document.getElementById(\'P_2\').value.toString()+\'&p_3=\'+document.getElementById(\'P_3\').value.toString()+\'&p_4=\'+document.getElementById(\'P_4\').value.toString()+\'&p_5=\'+document.getElementById(\'P_5\').value.toString()+\'&p_6=\'+document.getElementById(\'P_6\').value.toString()+\'&p_7=\'+document.getElementById(\'P_7\').value.toString()+\'&p_8=\'+document.getElementById(\'P_8\').value.toString()+\'&p_9=\'+document.getElementById(\'P_9\').value.toString()+\'&p_10=\'+document.getElementById(\'P_10\').value.toString()+\'&p_11=\'+document.getElementById(\'P_11\').value.toString()+\'&p_12=\'+document.getElementById(\'P_12\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString()+\'&view=3\');"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una accion de visualizacion.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/gasolina/busGasolina.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/gasolina/borrar.php\',\'?id=\'+document.getElementById(\'idProgGas\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString(),\'sandbox\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarGasolina(\'./php/backend/gasolina/guardar.php\',\'?id=\'+document.getElementById(\'idProgGas\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&p_1=\'+document.getElementById(\'P_1\').value.toString()+\'&p_2=\'+document.getElementById(\'P_2\').value.toString()+\'&p_3=\'+document.getElementById(\'P_3\').value.toString()+\'&p_4=\'+document.getElementById(\'P_4\').value.toString()+\'&p_5=\'+document.getElementById(\'P_5\').value.toString()+\'&p_6=\'+document.getElementById(\'P_6\').value.toString()+\'&p_7=\'+document.getElementById(\'P_7\').value.toString()+\'&p_8=\'+document.getElementById(\'P_8\').value.toString()+\'&p_9=\'+document.getElementById(\'P_9\').value.toString()+\'&p_10=\'+document.getElementById(\'P_10\').value.toString()+\'&p_11=\'+document.getElementById(\'P_11\').value.toString()+\'&p_12=\'+document.getElementById(\'P_12\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString()+\'&view=3\');"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habGasolina();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la accion corresponda a la edicion de un registro.
                            echo '<tr style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/gasolina/busGasolina.php\',\'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarGasolina(\'./php/backend/gasolina/guardar.php\',\'?id=\'+document.getElementById(\'idProgGas\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&p_1=\'+document.getElementById(\'P_1\').value.toString()+\'&p_2=\'+document.getElementById(\'P_2\').value.toString()+\'&p_3=\'+document.getElementById(\'P_3\').value.toString()+\'&p_4=\'+document.getElementById(\'P_4\').value.toString()+\'&p_5=\'+document.getElementById(\'P_5\').value.toString()+\'&p_6=\'+document.getElementById(\'P_6\').value.toString()+\'&p_7=\'+document.getElementById(\'P_7\').value.toString()+\'&p_8=\'+document.getElementById(\'P_8\').value.toString()+\'&p_9=\'+document.getElementById(\'P_9\').value.toString()+\'&p_10=\'+document.getElementById(\'P_10\').value.toString()+\'&p_11=\'+document.getElementById(\'P_11\').value.toString()+\'&p_12=\'+document.getElementById(\'P_12\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString()+\'&view=3\');"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habGasolina();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    }
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
            
            if(!empty($Registro['idProgGas']))
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
                    $activid= $Registro['idProgGas'];
                    }
            else
                {
                    //CASO: CREACION DE NUEVO REGISTRO.
                    $habcampos = '';
                    $activid= -1;
                    }                                            
                               
            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                            <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                        </head>
                        <body>
                            <div style=display:none>
                                <input type= "text" id= "idProgGas" value= "'.$Registro['idProgGas'].'">       
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                            <div id="infoRegistro" class="operativo">
                                <div id="cabecera" class="cabecera-operativo">'.
                                    '<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.
                                '</div>
                                <div id="cuerpo" class="cuerpo-operativo">                                
                                    <table>
                                        <tr><td class="td-panel" width="100px">Entidad:</td><td><select name= "idEntidad" id= "idEntidad" '.$habcampos.' value= "-1">';
                                
                                        $subconsulta = cargarEntidades();
                                
            echo '                          <option value=-1>Seleccione</option>';
            
                                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                
                                        while ($RegNiveles)
                                            {
                                                if($RegNiveles['idEntidad']==$Registro['idEntidad'])
                                                    {
                                                        /*
                                                         * En caso que se ejecute una accion de consulta, se obtiene la referencia seleccionada
                                                         * de la unidad.
                                                         */
            echo '                          <option value='.$RegNiveles['idEntidad'].' selected="selected">'.$RegNiveles['Entidad'].'</option>';
                                                        }
                                                else
                                                    {
                                                        /*
                                                         * En caso que se ejecute una accion de creacion de registro.
                                                         */
            echo'                           <option value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'].'</option>';
                                                        }
                                                
                                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                                }
            
            echo'                           </select></td></tr>';                                
                                                
                                        if($parametro=="-1")
                                            {
                                                /*
                                                 * Si la accion corresponde a la creacion de un registro nuevo,
                                                 * se establece el año actual.
                                                 */
            echo '                      <tr><td class="td-panel" width="100px">Periodo:</td><td><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$periodo.'"></td></tr>';
                                                }
                                        else
                                            {
                                                /*
                                                 * Si la accion ocurre para un registro existente,
                                                 * se preserva el año almacenado.
                                                 */
            echo '                      <tr><td class="td-panel" width="100px">Periodo:</td><td><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$Registro['Periodo'].'"></td></tr>';
                                                }                                        
                                                                                                        
            echo'                   </table>
                            <br>';

                            $nonhabilitado = 'disabled= "disabled"';
            
            echo'               <div id= "dataact">
                                    <table>
                                        <tr><th class="dgHeader-Planeacion" colspan= "14">Consumo Programado (En pesos)</th></tr>
                                        <tr><td></td><td class="dgDH-Planeacion">Enero</td><td class="dgDH-Planeacion">Febrero</td><td class="dgDH-Planeacion">Marzo</td><td class="dgDH-Planeacion">Abril</td><td class="dgDH-Planeacion">Mayo</td><td class="dgDH-Planeacion">Junio</td><td class="dgDH-Planeacion">Julio</td><td class="dgDH-Planeacion">Agosto</td><td class="dgDH-Planeacion">Septiembre</td><td class="dgDH-Planeacion">Octubre</td><td class="dgDH-Planeacion">Noviembre</td><td class="dgDH-Planeacion">Diciembre</td><td class="dgDH-Planeacion">Total</td></tr>';
            
                                        //Se procede con la carga de la programacion que corresponde al programa.
                                        $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                        $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opProgGas WHERE status= 0 AND idEntidad= '.$Registro['idEntidad'];
                                        $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                                        $dsCampos = $subdataset;                                    
                                        $RegAux = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                                    
                                        if($dsCampos)
                                            {
                                                $field = mysqli_fetch_field($dsCampos);
                                                }                                    
                                                            
                                        $rowdata='<tr><td class="dgTD-Planeacion">Programacion</td>';
                                        $count=1;
                                        $totEficacia=0.00;
                                    
                                        if($RegAux)
                                            {
                                                //Para el caso de una consulta de datos.
                                                while($field)
                                                    {
                                                        $rowdata.= '<td><input class="input-planeacion" type="text" '.$habcampos.' id="P_'.$count.'" value="'.$RegAux[$field->name].'"></input></td>';
                                                        $totEficacia += $RegAux[$field->name];
                                                        $field = mysqli_fetch_field($dsCampos);
                                                        $count += 1;
                                                        }
                            
                                                $rowdata.='<td><input class="input-planeacion" type="text" id="P_'.$count.'" value="'.$totEficacia.'"></input></td></tr>';
                                                }
                                        else
                                            {
                                                //Para el caso de una creacion de registro.
                                                $counter=1;
                                            
                                                while($counter <= 12)
                                                    {
                                                        //Mientras no se llegue al ciclo de doce meses.
                                                        $rowdata.= '<td><input class="input-planeacion" type="text" '.$habcampos.' id="P_'.$counter.'" value="0.00"></input></td>';
                                                        $counter += 1;
                                                        }
                                                $rowdata.='<td><input class="input-planeacion" type="text" id="P_'.$counter.'" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                                }
                                            
                                        echo $rowdata;
            
                                        //Se procede con la carga de la ejecucion que corresponde al programa.
                                        $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                        $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEjecGas WHERE status= 0 AND idEntidad= '.$Registro['idEntidad'];
                                        $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                                        $dsCampos = $subdataset;                                    
                                        $RegAux = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                                    
                                        if($dsCampos)
                                            {
                                                $field = mysqli_fetch_field($dsCampos);
                                                }                                                                        
                        
                                        $rowdata='<tr><td class="dgTD-Planeacion">Ejecucion</td>';
                                        $count=1;
                                        $totEficacia=0;
                                    
                                        if($RegAux)
                                            {
                                                //Para el caso de una consulta de datos.
                                                while($field)
                                                    {
                                                        $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="E_'.$count.'" value="'.$RegAux[$field->name].'"></input></td>';
                                                        $totEficacia += $RegAux[$field->name];
                                                        $field = mysqli_fetch_field($dsCampos);
                                                        $count += 1;
                                                        }
                            
                                                $rowdata.='<td><input class="input-planeacion" type="text" id="E_'.$count.'" value="'.$totEficacia.'"></input></td></tr>';                                            
                                                }
                                        else
                                            {
                                                //Para el caso de una creacion de registro.
                                                $counter=1;
                                            
                                                while($counter <= 12)
                                                    {
                                                        //Mientras no se llegue al ciclo de doce meses.
                                                        $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="E_'.$counter.'" value="0.00"></input></td>';
                                                        $counter += 1;
                                                        }
                                                $rowdata.='<td><input class="input-planeacion" type="text" id="E_'.$counter.'" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                                }                    

                                        echo $rowdata;
            
                                        //Se procede con la carga de la eficacia que corresponde al programa.
                                        $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                        $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEficGas WHERE status= 0 AND idEntidad= '.$Registro['idEntidad'];
                                        $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.                                                                               
                                        $dsCampos = $subdataset;                                    
                                        $RegAux = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);                                                                        
                                    
                                        if($dsCampos)
                                            {
                                                $field = mysqli_fetch_field($dsCampos);
                                                }                        
                                            
                                        $rowdata='<tr><td class="dgTD-Planeacion">Eficacia</td>';
                                        $count=1;
                                        $totEficacia=0;
                                    
                                        if($RegAux)
                                            {
                                                //Para el caso de una consulta de datos.
                                                while($field)
                                                    {
                                                        $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="Efic_'.$count.'" value="'.$RegAux[$field->name].'"></input></td>';
                                                        cargarBanderas($RegAux[$field->name], $count);//Se genera la fila de banderas.
                                                        $totEficacia += $RegAux[$field->name];
                                                        $field = mysqli_fetch_field($dsCampos);
                                                        $count += 1;
                                                        }
                                                $totEficacia = $totEficacia/12.00;
                                                $rowdata.='<td><input class="input-planeacion" type="text" id="Efic_'.$count.'" value="'.$totEficacia.'"></input></td></tr>';                                            
                                                }
                                        else
                                            {
                                                //Para el caso de una creacion de registro.
                                                $counter=1;
                                            
                                                while($counter <= 12)
                                                    {
                                                        //Mientras no se llegue al ciclo de doce meses.
                                                        $rowdata.= '<td><input class="input-planeacion" type="text" '.$nonhabilitado.' id="Efic_'.$counter.'" value="0.00"></input></td>';
                                                        cargarBanderas(0.00, $counter);//Se genera la fila de banderas.
                                                        $counter += 1;
                                                        }
                                                $rowdata.='<td><input class="input-planeacion" type="text" id="Efic_'.$counter.'" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                                }                    
                                            
                                    echo $rowdata;
            echo '                      <tr><td class="dgTD-Planeacion">Estado</td>'.$rowBanderas.'</tr>';
            
            echo'                   </table>
                                </div>
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