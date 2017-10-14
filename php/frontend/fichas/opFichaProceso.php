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

/*
 * Este archivo contiene el codigo para construir la interfaz de ficha de proceso.
 */
    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.

    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.

    $Sufijo = "fsp_";
        
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
            $parametro = '-1'; //Se establece un valor por default, equivalente a creación de registro.
            }
            
    if(isset($_GET['view']))
        {
            /*
             * Si se declaro en la URL el control de visualización.
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
             * Esta función establece la busqueda de los datos correspondientes al ID proporcionado
             * regresando un dataset con el total de elementos que correspondieron al criterio proporcionado.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM opFichasProcesos WHERE Status=0 AND idFicha='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;            
            }
            
    function cargarProcesos()
        {
            /*
             * Esta funcion genera la carga de la tupla de datos
             * correspondiente al registro de procesos del sistema.
             */
            global $username, $password, $servername, $dbname;

            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT idProceso, Proceso FROM catProcesos WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }

    function cargarIndicadores($idProceso)
        {
            /*
             * Esta funcion genera la carga de la tupla de datos
             * correspondiente al registro de indicadores asociados al proceso en el sistema.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT * FROM (catIndicadores INNER JOIN relIndPro ON relIndPro.idIndicador = catIndicadores.idIndicador) WHERE relIndPro.idProceso ='.$idProceso.' AND catIndicadores.Status=0'; //Se establece el modelo de la consulta de datos.
            $dataset = $objConexion -> conectar($consulta);
            
            return $dataset;
            }            
    
    function constructorchkIndicadores($idRegistro, $dataSet)
        {
            /*
             * Esta funcion establece los parametros de carga del conjunto de checkbox asociados
             * al comportamiento de indicadores por proceso, en la interfaz del usuario.
             */
             global $habCampos, $Registro;
            
            if($idRegistro == -1)
                {
                    /*
                     * Si la operación solicitada es para la creación de un registro,
                     * se carga el listado sin marcar.
                     */ 
                                        
                    //Se construye un vector con los indicadores asociados al proceso.                    
                    $regIndicadores = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la función de carga de registros de procesos.
                    $template = '';
                                                   
                    while($regIndicadores)
                        {
                            //Mientras existan elementos en al tupla de datos.
                            $template.= '<input type="checkbox" class="check" id="idIndicador[]" name="idIndicador[]" '.$habCampos.' value='.$regIndicadores['idIndicador'].'>'.$regIndicadores['Indicador'];
                            $regIndicadores = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la función de carga de registros de procesos.
                            }
                    }
            else
                {
                    /*
                     * Si la operación solicitada es para editar el registro,
                     * se carga el listado con los elementos previamente marcados.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                        
                    $subconsulta = 'SELECT *FROM relIndFicha WHERE idFicha='.$idRegistro.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                    $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                    $vector = "";
                    
                    if($RegNiveles)
                        {
                            /*
                             * Si la lectura del registro no apunta a vacio, se agrega
                             * el id al vector.
                             */
                            $vector.=$RegNiveles['idIndicador'];
                            }
                    
                    $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                    
                    while ($RegNiveles)
                        {
                            /*
                             * Se hace un recorrido sobre el dataset para crear un vector que contenga
                             * los id de las entidades seleccionadas por el usuario previamente.
                             */
                            $vector.=','.$RegNiveles['idIndicador'];
                            $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                            }
                    
                    $tmparray=explode(',',$vector); //El vector resultante se convierte en un arreglo.
                    
                    //Se construye un vector con los indicadores asociados al proceso.                    
                    $regIndicadores = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Se ejecuta la lectura sobre la tupla de datos.
                    $template = '';
                    
                    while ($regIndicadores)
                        {
                            /*
                             * Mientras no se llegue al final de la colección, se procede a la lectura
                             * y generación del listado.
                             */
                            if(in_array($regIndicadores['idIndicador'], $tmparray,true))
                                {
                                    /*
                                     * En caso de tratarse de una opción previamente seleccionada por el usuario.
                                     */
                                    $template.= '<input type="checkbox" class="check" id="idIndicador[]" name="idIndicador[]" '.$habCampos.' value='.$regIndicadores['idIndicador'].' checked>'.$regIndicadores['Indicador'];
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario se agrega una entrada de formato convencional.
                                     */
                                    $template.= '<input type="checkbox" class="check" id="idIndicador[]" name="idIndicador[]" '.$habCampos.' value='.$regIndicadores['idIndicador'].'>'.$regIndicadores['Indicador'];
                                    }
                    
                            $regIndicadores = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Se ejecuta la lectura sobre la tupla de datos.
                            }                    
                    }                                
                return $template;
            }
        
    function contructcbProcesos($dataSet)
        {
            /*
             * Esta función establece los parametros de carga del combobox de procesos cuando
             * se ejecuta un proceso de edición.
             */
            global $habCampos, $Registro;            
            
            $template = '<select name= "idProceso" id= "idProceso" '.$habCampos.' value= "'.$Registro['idProceso'].'">
                            <option value=-1>Seleccione</option>';            
            
            $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la función de carga de registros de procesos.
            
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
                    $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la función de carga de registros de procesos.
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
                        
    $Registro = @mysqli_fetch_array(cargarRegistro($parametro),MYSQLI_ASSOC); //Llamada a la función de carga de registro de Ficha de Proceso.
    $habCampos = 'disabled= "disabled"'; //Se establece por default el bloque de campos.
    
    if($Registro['idFicha'] == null)
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
             * Esta función carga la estructura de la interfaz de usuario, asi como ejecuta las llamadas
             * a función de sus respectivos componentes.
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
                                        <input type= "text" id= "idFicha" value="'.$Registro['idFicha'].'">
                                        <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                                    </div>
                                    <table class="dgTable">
                                        <tr><th colspan= "3">Ficha de proceso</th></tr>
                                        <tr><th colspan= "2" class="dgRowsaltTR">SEGUIMIENTO Y MEDICION DE PROCESOS</th><th class="dgRowsaltTR">FICHA<center><input type= "text" name= "Clave" id= "Clave" disabled= "disabled" value="'.$Registro['Clave'].'"></center></th></tr>
                                        <tr><th class= "dgRowsaltTR">Ficha del Proceso</th><th class= "dgRowsaltTR">Edicion</th><th class="dgRowsaltTR">Revision</th></tr>
                                        <tr><td class= "dgRowsnormTR"><center>'.contructcbProcesos(cargarProcesos()).'</center></td><td class= "dgRowsnormTR"><center><input type= "text" name= "nEdicion" id= "nEdicion" disabled= "disabled" value="'.$nEdit.'"></center></td><td class="dgRowsnormTR"><center><input type= "text" name= "FechaEdicion" id= "FechaEdicion" value="'.$FechaEdicion.'" '.$habCampos.'></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Mision del Proceso</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="MisionProceso" id="MisionProceso" cols="137" rows="3"'.$habCampos.'>'.saltosLineaRev($Registro['MisionProceso']).'</textarea></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Actividades que forman el Proceso</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="Actividades" id="Actividades" cols="137" rows="3"'.$habCampos.'>'.saltosLineaRev($Registro['Actividades']).'</textarea></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Responsables del Proceso</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="Responsable" id="Responsable" cols="137" rows="3"'.$habCampos.'>'.saltosLineaRev($Registro['Responsable']).'</textarea></center></td></tr>
                                        <tr><th colspan= "1" class= "dgRowsaltTR">Entradas</th><th colspan= "2" class= "dgRowsaltTR">Salidas</th></tr>
                                        <tr><td colspan= "1" class= "dgRowsnormTR"><center><textarea name="Entrada" id="Entrada" cols="60" rows="4"'.$habCampos.'>'.saltosLineaRev($Registro['Entrada']).'</textarea></center></td><td colspan= "2" class= "dgRowsnormTR"><center><textarea name="Salida" id="Salida" cols="60" rows="4"'.$habCampos.'>'.saltosLineaRev($Registro['Salida']).'</textarea></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Procesos Relacionados</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="relProcesos" id="relProcesos" cols="137" rows="3"'.$habCampos.'>'.saltosLineaRev($Registro['relProcesos']).'</textarea></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Recursos/Necesidades</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="necRecursos" id="necRecursos" cols="137" rows="3"'.$habCampos.'>'.saltosLineaRev($Registro['necRecursos']).'</textarea></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Registros/Archivos</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="regArchivos" id="regArchivos" cols="137" rows="3"'.$habCampos.'>'.saltosLineaRev($Registro['regArchivos']).'</textarea></center></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Indicadores</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><div id="chkIndicadores">'.constructorchkIndicadores($parametro, cargarIndicadores($Registro['idProceso'])).'</div></td></tr>
                                        <tr><th colspan= "3" class= "dgRowsaltTR">Documentos Aplicables</th></tr>
                                        <tr><td colspan= "3" class= "dgRowsnormTR"><center><textarea name="docAplicables" id="docAplicables" cols="137" rows="3"'.$habCampos.'>'.saltosLineaRev($Registro['docAplicables']).'</textarea></center></td></tr>
                                        <tr class="dgHeader" style="text-align:right"><td colspan= "3">'.
                                        controlBotones("24", "24", $cntview)    
                                    .'</td></tr></table>
                                </body>
                            </html>';
            return $template;
            }

    echo constructor($Registro);
?>