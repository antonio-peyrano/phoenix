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

/*
 * Este archivo contiene el codigo para construir la interfaz de ficha de proceso.
 */
    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificacion a ISO-8859-1.

    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.

    $Sufijo = "psr_";
        
    if(!isset($_SESSION))
        {
            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
            session_name('phoenix');
            session_start();
            }
        
    if(isset($_GET['id']))
        {
            //Se recibe el parametro enviado por la consulta URL.
            $parametro = $_GET['id']; //Se establece el valor por referencia del registro.
            }
    else
        {
            //En caso de no recibir parametro por la consulta URL.
            $parametro = '-1'; //Se establece un valor por default, equivalente a creacion de registro.
            }
            
    if(isset($_GET['view']))
        {
            /*
             * Si se declaro en la URL el control de visualizacion.
             */
            $cntview = $_GET['view'];
            }

    /*
     * Se crea un patron de referencia de datos correspondientes a la fecha y hora
     * para el registro.
     */
     $now = time(); //Se obtiene la referencia del tiempo actual del servidor.
     date_default_timezone_set("America/Mexico_City"); //Se establece el perfil del uso horario.     
     $FechaEdicion = date("d/m/Y",$now).' '.date("h:i:sa",$now); //Se obtiene la referencia compuesta de fecha y hora.

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
                                 
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta funcion establece la busqueda de los datos correspondientes al ID proporcionado
             * regresando un dataset con el total de elementos que correspondieron al criterio proporcionado.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM opPlanesRSGR WHERE Status=0 AND idPlanRSGR='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;            
            }
            
    function getNiveles($habCampos, $Registro)
        {
            /*
             * Esta funcion retorna el codigo HTML correspondiente al combobox del
             * listado de niveles.
             */
            $HTML = '<select name= "Nivel" id= "Nivel" '.$habCampos.' value= "'.$Registro['Nivel'].'"><option value="Seleccione">Seleccione</option>';

            if($Registro['Nivel']=="Alto"){$HTML .= '<option value = "Alto" selected="selected">Alto</option>';}
            else{$HTML .= '<option value = "Alto">Alto</option>';}
                
            if($Registro['Nivel']=="Medio"){$HTML .= '<option value = "Medio" selected="selected">Medio</option>';}
            else{$HTML .= '<option value = "Medio">Medio</option>';}

            if($Registro['Nivel']=="Bajo"){$HTML .= '<option value = "Bajo" selected="selected">Bajo</option>';}
            else{$HTML .= '<option value = "Bajo">Bajo</option>';}            

            return $HTML.'</select>';                                                    
            } 
            
    function cargarProcesos()
        {
            /*
             * Esta funcion genera la carga de la tupla de datos
             * correspondiente al registro de indicadores asociados al proceso en el sistema.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT idProceso, Proceso FROM catProcesos WHERE Status=0'; //Se establece el modelo de la consulta de datos.
            $dataset = $objConexion -> conectar($consulta);
            
            return $dataset;
            }            
    
    function constructorchkProcesos($idRegistro, $dataSet)
        {
            /*
             * Esta funcion establece los parametros de carga del conjunto de checkbox asociados
             * al comportamiento de indicadores por proceso, en la interfaz del usuario.
             */
             global $habCampos, $Registro;
            
            if($idRegistro == -1)
                {
                    /*
                     * Si la operacion solicitada es para la creacion de un registro,
                     * se carga el listado sin marcar.
                     */ 
                                        
                    //Se construye un vector con los indicadores asociados al proceso.                    
                    $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la funcion de carga de registros de procesos.
                    $template = '';
                                                   
                    while($regProcesos)
                        {
                            //Mientras existan elementos en al tupla de datos.
                            $template.= '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" '.$habCampos.' value='.$regProcesos['idProceso'].'>'.$regProcesos['Proceso'];
                            $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la funcion de carga de registros de procesos.
                            }
                    }
            else
                {
                    /*
                     * Si la operacion solicitada es para editar el registro,
                     * se carga el listado con los elementos previamente marcados.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                        
                    $subconsulta = 'SELECT *FROM relProRSGR WHERE idPlanRSGR='.$idRegistro.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                    $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                    $vector = "";
                    
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
                    
                    //Se construye un vector con los indicadores asociados al proceso.                    
                    $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Se ejecuta la lectura sobre la tupla de datos.
                    $template = '';
                    
                    while ($regProcesos)
                        {
                            /*
                             * Mientras no se llegue al final de la coleccion, se procede a la lectura
                             * y generacion del listado.
                             */
                            if(in_array($regProcesos['idProceso'], $tmparray,true))
                                {
                                    /*
                                     * En caso de tratarse de una opcion previamente seleccionada por el usuario.
                                     */
                                    $template.= '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" '.$habCampos.' value='.$regProcesos['idProceso'].' checked>'.$regProcesos['Proceso'];
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario se agrega una entrada de formato convencional.
                                     */
                                    $template.= '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" '.$habCampos.' value='.$regProcesos['idProceso'].'>'.$regProcesos['Proceso'];
                                    }
                    
                            $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Se ejecuta la lectura sobre la tupla de datos.
                            }                    
                    }                                
                return $template;
            }
        
    function contructcbProcesos($dataSet)
        {
            /*
             * Esta funcion establece los parametros de carga del combobox de procesos cuando
             * se ejecuta un proceso de edicion.
             */
            global $habCampos, $Registro;            
            
            $template = '<select name= "idProceso" id= "idProceso" '.$habCampos.' value= "'.$Registro['idProceso'].'">
                            <option value=-1>Seleccione</option>';            
            
            $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la funcion de carga de registros de procesos.
            
            while($regProcesos)
                {
                    if($regProcesos['idProceso'] == $Registro['idProceso'])
                        {
                            //En caso que el valor almacenado coincida con uno de los existentes en lista.
                            $template = $template. '<option value='.$regProcesos['idProceso'].' selected="selected">'.$regProcesos['Proceso'].'</option>';
                            }
                    else
                        {
                            //En caso contrario se carga la etiqueta por default.
                            $template = $template. '<option value='.$regProcesos['idProceso'].'>'.$regProcesos['Proceso'].'</option>';
                            }
                    $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la funcion de carga de registros de procesos.
                    }
            
            $template = $template. '</select>';
            
            return $template;
            }

    function saltosLineaRev($str) 
        {
            /*
             * Esta funcion toma los tag <br> dentro de la cadena recuperada y
             * los convierte a saltos de linea.
             */
            return str_replace(array("<br>"), "\n", $str);
            }
                        
    $Registro = @mysqli_fetch_array(cargarRegistro($parametro),MYSQLI_ASSOC); //Llamada a la funcion de carga de registro de Ficha de Proceso.
    $habCampos = 'disabled= "disabled"'; //Se establece por default el bloque de campos.
    
    if($Registro['idPlanRSGR'] == null)
        {
            //En caso que el registro sea de nueva creacion.
            $habCampos='';
            $nEdit=0;
            }
    else
        {
            //Para el caso de edicion.
            $nEdit=$Registro['nEdicion'];            
            }
        
    function constructor($Registro)
        {
            /*
             * Esta funcion carga la estructura de la interfaz de usuario, asi como ejecuta las llamadas
             * a funcion de sus respectivos componentes.
             */
            global $habCampos, $parametro, $FechaEdicion, $nEdit;
            global $cntview;
            
            $template = '   <html>
                                <head>
                                    <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                                    <link rel= "stylesheet" href= "./css/queryStyle.css"></style>                
                                </head>
                                <body>
                                    <div style=display:none>
                                        <input type= "text" id= "idPlanRSGR" value="'.$Registro['idPlanRSGR'].'">
                                        <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                                    </div>
                                    <table class="dgTable">
                                        <tr><th colspan= "3">Ficha para la Planificacion RSGR (Reduccion Supervision y Gestion del Riesgo)</th></tr>
                                        <tr><th colspan= "2" class="dgRowsaltTR">ESTRATEGIAS PARA LA GESTION DE RIESGO</th><th class="dgRowsaltTR">FICHA<center><input type= "text" name= "Clave" id= "Clave" disabled= "disabled" value="'.$Registro['Clave'].'"></center></th></tr>
                                        <tr><th class= "dgRowsaltTR">Nivel de impacto</th><th class= "dgRowsaltTR">Edicion</th><th class="dgRowsaltTR">Revision</th></tr>
                                        <tr><td class= "dgRowsnormTR"><center>'.getNiveles($habCampos, $Registro).'</center></td><td class= "dgRowsnormTR"><center><input type= "text" name= "nEdicion" id= "nEdicion" disabled= "disabled" value="'.$nEdit.'"></center></td><td class="dgRowsnormTR"><center><input type= "text" name= "FechaEdicion" id= "FechaEdicion" value="'.$FechaEdicion.'" '.$habCampos.'></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Riesgo</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="Riesgo" id="Riesgo" cols="137" rows="2"'.$habCampos.'>'.saltosLineaRev($Registro['Riesgo']).'</textarea></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Supervisor(es)</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="Supervisor" id="Supervisor" cols="137" rows="2"'.$habCampos.'>'.saltosLineaRev($Registro['Supervisor']).'</textarea></center></td></tr>
                                        <tr><th colspan= "1" class= "dgRowsaltTR">Causa(s)</th><th colspan= "2" class= "dgRowsaltTR">Efecto(s)</th></tr>
                                        <tr><td colspan= "1" class= "dgRowsnormTR"><center><textarea name="Causa" id="Causa" cols="60" rows="4"'.$habCampos.'>'.saltosLineaRev($Registro['Causa']).'</textarea></center></td><td colspan= "2" class= "dgRowsnormTR"><center><textarea name="Efecto" id="Efecto" cols="60" rows="4"'.$habCampos.'>'.saltosLineaRev($Registro['Efecto']).'</textarea></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Acciones</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="Acciones" id="Acciones" cols="137" rows="3"'.$habCampos.'>'.saltosLineaRev($Registro['Acciones']).'</textarea></center></td></tr>

                                        <tr><th colspan= "3" class= "dgRowsaltTR">Procesos</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><div id="chkProcesos">'.constructorchkProcesos($parametro, cargarProcesos()).'</div></td></tr>
                                        <tr class="dgHeader" style="text-align:right"><td colspan= "3">'.
                                    controlBotones("24", "24", $cntview)    
                                    .'</td></tr></table>
                                </body>
                            </html>';
            return $template;
            }

    echo constructor($Registro);
?>