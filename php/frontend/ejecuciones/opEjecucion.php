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
    $Title = 'Ejecucion';
    $Sufijo = "ejc_";
    $parametro = $_GET['id'];
    $idPrograma = $_GET['idprograma'];
    $idActividad = $_GET['idactividad'];
    $cntview = $_GET['view'];
    $clavecod = '';   

    if(isset($_GET['view']))
        {
            /*
             * Si se declaro en la url el control de visualizacion.
             */
            $cntview = $_GET['view'];
            }
        
    $now = time();
    $periodo = date("Y",$now);

    function cargarMeses($Mes)
	   {
	   	   /*
            * Esta funcion carga los meses en el combobox para la seleccion.
            */
            echo '          <option value="-1">Seleccione</option>';
							if($Mes == "1")
								{
            echo '              		<option value="1" selected="selected">Enero</option>';
									}
							else
								{
            echo '              		<option value="1">Enero</option>';
									}
							if($Mes == "2")
								{
            echo '              		<option value="2" selected="selected">Febrero</option>';
									}
							else
								{
            echo '              		<option value="2">Febrero</option>';							
									}
							if($Mes == "3")
								{
            echo '              		<option value="3" selected="selected">Marzo</option>';
									}
							else
								{
            echo '              		<option value="3">Marzo</option>';							
									}
							if($Mes == "4")
								{
            echo '              		<option value="4" selected="selected">Abril</option>';
									}
							else
								{
            echo '              		<option value="4">Abril</option>';							
									}
							if($Mes == "5")
								{
            echo '              		<option value="5" selected="selected">Mayo</option>';
									}
							else
								{
            echo '              		<option value="5">Mayo</option>';									
									}
							if($Mes == "6")
								{
            echo '              		<option value="6" selected="selected">Junio</option>';
									}
							else
								{
    		echo '              		<option value="6">Junio</option>';									
									}
							if($Mes == "7")
								{
	       	echo '              		<option value="7" selected="selected">Julio</option>';
									}
							else
								{
    		echo '              		<option value="7">Julio</option>';							
									}
							if($Mes == "8")
								{
    		echo '              		<option value="8" selected="selected">Agosto</option>';
									}
							else
								{
    		echo '              		<option value="8">Agosto</option>';							
									}
							if($Mes == "9")
								{
    		echo '              		<option value="9" selected="selected">Septiembre</option>';
									}
							else
								{
    		echo '              		<option value="9">Septiembre</option>';							
									}
							if($Mes == "10")
								{
    		echo '              		<option value="10" selected="selected">Octubre</option>';
									}
							else
								{
            echo '              		<option value="10">Octubre</option>';							
									}
							if($Mes == "11")
								{
    		echo '              		<option value="11" selected="selected">Noviembre</option>';
									}
							else
								{
    		echo '              		<option value="11">Noviembre</option>';							
									}
							if($Mes == "12")
								{
    		echo '              		<option value="12" selected="selected">Diciembre</option>';
									}
							else
								{
    		echo '              		<option value="12">Diciembre</option>';							
									}
            }
                
    function controlMonto()
        {
            /*
             * Esta funcion obtiene el monto asignado a la actividad.
             */
            global $username, $password, $servername, $dbname;
            global $idActividad;
    
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT Monto FROM opActividades WHERE idActividad='.$idActividad; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    
            $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
    
            return $Registro['Monto'];
            }
        
    function sigmaMontos($idActividad, $ejecid)
        {
            /*
             * Esta funcion obtiene la suma acumulada de montos de las
             * actividades.
             */
            global $username, $password, $servername, $dbname;
    
            $sumMonto = 0;
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            if($ejecid == -1)
                {
                    /*
                     * Si el proceso corresponde a un registro de nueva creacion.
                     */
                    $consulta = 'SELECT Monto FROM opEjecuciones WHERE idActividad='.$idActividad; //Se establece el modelo de consulta de datos.
                    }
            else
                {
                    /*
                     * Si el proceso corresponde a un registro existente.
                     */
                    $consulta = 'SELECT Monto FROM opEjecuciones WHERE idActividad='.$idActividad.' AND idEjecucion NOT LIKE '.$ejecid; //Se establece el modelo de consulta de datos.
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
                
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM opEjecuciones WHERE idEjecucion='.$idRegistro; //Se establece el modelo de consulta de datos.
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
            global $idActividad, $idPrograma, $periodo;
            global $imgTitleURL, $Title;
            global $cntview;
            
            $habcampos = 'disabled= "disabled"';
            $ejecid = 0;
            
            if($Registro['idEjecucion'] == null)
                {
                    //En caso que el registro sea de nueva creacion.
                    $habcampos='';        
                    $activid= -1;
                    }
            else
                {
                    //En caso contrario se asigan el id de la actividad al temporal.
                    $ejecid= $Registro['idEjecucion']; 
                    }
                    
            $MontoAcumulado = sigmaMontos($idActividad,$ejecid);
            $MontoActividad = controlMonto(); 
                               
            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                            <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                        </head>
                        <body>
                            <div style=display:none>
                                <input type= "text" id= "pagina" value= "1">
                                <input type= "text" id= "idEjecucion" value= "'.$Registro['idEjecucion'].'">
                                <input type= "text" id= "idPrograma" value= "'.$idPrograma.'">
                                <input type= "text" id= "idActividad" value= "'.$idActividad.'">
                                <input type= "text" id= "MontoAcumulado" value= "'.$MontoAcumulado.'">
                                <input type= "text" id= "MontoActividad" value= "'.$MontoActividad.'">        
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                            <div id="infoRegistro" class="operativo">
                                <div id="cabecera" class="cabecera-operativo">'.
                                    '<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.
                                '</div>
                                <div id="cuerpo" class="cuerpo-operativo">
                                    <center><table>
                                        <tr><td class="td-panel" width="100px">Cantidad: <input style="width:100px;" class="inputform" type= "text" required= "required" id= "Cantidad" '.$habcampos.' value= "'.$Registro['Cantidad'].'"></td>
                                            <td class="td-panel" width="100px">Monto: <input style="width:100px;" class="inputform" type= "text" required= "required" id= "Monto" '.$habcampos.' value= "'.$Registro['Monto'].'"></td>
                                            <td class="td-panel" width="100px">Mes: <select style="width:200px;" class="inputform" name= "idMes" id= "idMes" '.$habcampos.' value= "-1">';
            
                                        cargarMeses($Registro['Mes']);
                                            
            echo'                       </select></td></tr>';                                
                                                
                                        if($parametro=="-1")
                                            {
                                                /*
                                                 * Si la accion corresponde a la creacion de un registro nuevo,
                                                 * se establece el año actual.
                                                 */
            echo '                      <tr><td class="td-panel" width="100px">Periodo: <input style="width:60px;" class="inputform" type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$periodo.'"></td></tr>';
                                                }
                                        else
                                            {
                                                /*
                                                 * Si la accion ocurre para un registro existente,
                                                 * se preserva el año almacenado.
                                                 */
            echo '                      <tr><td class="td-panel" width="100px">Periodo: <input style="width:60px;" class="inputform" type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$Registro['Periodo'].'"></td></tr>';
                                                }                                        
                                                                                                        
            echo'                   </table></center>';

            echo'       
                            <br>
                                <div id= "dataevidencias">
                                    <table class="queryTable">
                                        <tr><td class= "queryRowsnormTR">Ruta de evidencia: </td><td class= "queryRowsnormTR"><input type= "text" id= "RutaURL" value= ""></input></td><td><a href="#" onclick="guardarEvidencia(\'./php/backend/evidencias/guardar.php\',\'?idejecucion=\'+document.getElementById(\'idEjecucion\').value.toString()+\'&rutaurl=\'+document.getElementById(\'RutaURL\').value.toString()+\'&idprograma=\'+document.getElementById(\'idPrograma\').value.toString()+\'&idactividad=\'+document.getElementById(\'idActividad\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString()+\'&view=3\');"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td><tr>
                                    </table>
                            <br>';
            
                            $_GET['idejecucion'] = $Registro['idEjecucion'];

                            if($cntview == 3)
                                {
                                    /*
                                     * En caso que el invocador sea el formulario de actividades.
                                     */
                                    include_once("../../frontend/evidencias/catEvidencias.php");
                                    }
                            else
                                {
                                    /*
                                     * En caso que el invocador sea el formulario de programa.
                                     */
                                    include_once("../evidencias/catEvidencias.php");                                   
                                    }
                                                                                        
            echo'               </div>
                            <br>
                                <div id="adjuntosServidor">
                                    <table class="queryTable">
                                        <tr><td class= "queryRowsnormTR"><a href="#"id="verArchivos">VER ARCHIVOS ADJUNTOS</a></td></tr>
                                    </table>
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
        constructor();
?>