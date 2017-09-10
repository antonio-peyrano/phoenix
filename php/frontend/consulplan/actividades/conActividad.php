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

    global $username, $password, $servername, $dbname;

    $imgTitleURL = './img/menu/planeacion.png';
    $Title = 'Programa';    
    $condicionales = ''; //Variable de control de condiciones de clausula select.
    $sufijo = "cac_"; //Variable de control de sufijo de identificadores.
    $idPrograma = 0;
    $idEstOpe = 0;
    $idObjOpe = 0;
    $idObjEst = 0;

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
                
    if(isset($_GET['idprograma']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $condicionales = 'idPrograma='.$_GET['idprograma'];
            $idPrograma = $_GET['idprograma'];
            }
            
    if(isset($_GET['idestope']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $idEstOpe = $_GET['idestope'];
            }

    if(isset($_GET['idobjope']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $idObjOpe = $_GET['idobjope'];
            }

    if(isset($_GET['idobjest']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $idObjEst = $_GET['idobjest'];
            }
                                    
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    obtenerPerfilSys(); //Llamada a la funcion de obtencion de parametros.
    
    if($condicionales == "")
        {
            //Cargar la cadena de consulta por default.            
            $consulta= "SELECT idActividad, Unidad, Actividad, Monto, opActividades.Periodo, opActividades.Status  FROM (opActividades INNER JOIN catUnidades ON catUnidades.idUnidad = opActividades.idUnidad) WHERE opActividades.Status=0 AND opActividades.Periodo=".$Periodo." ORDER BY idActividad"; //Se establece el modelo de consulta de datos.
            }  
    else 
        {
            //En caso de contar con el criterio de filtrado.
            $consulta= "SELECT idActividad, Unidad, Actividad, Monto, opActividades.Periodo, opActividades.Status  FROM (opActividades INNER JOIN catUnidades ON catUnidades.idUnidad = opActividades.idUnidad) WHERE opActividades.Status=0 AND " .$condicionales. " AND opActividades.Periodo=".$Periodo." ORDER BY idActividad"; //Se establece el modelo de consulta de datos.
            }  
    
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
        
    $column_names = ""; //Variable de control para los nombres de columnas a mostrarse en el grid.
    
    function loadInfo($idRegistro, $titulo)        
        {
            /*
             * Esta funcion carga los datos correspondientes al registro Padre, generando
             * la UI que visualiza la informacion.
             */
             global $username, $password, $servername, $dbname;
             global $idObjEst, $idEstOpe, $idObjOpe, $idPrograma, $rowBanderas;
             global $imgTitleURL, $Title, $Periodo;
             
             /*
              * Obteniendo la informacion asociada al padre.
              */
             $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
             $consulta= "SELECT Nomenclatura, Programa, Monto, Periodo FROM opProgramas WHERE Status=0 AND idEstOpe=".$idRegistro; //Se establece el modelo de consulta de datos.
             $dsPadre = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
             $dsCampos = $dsPadre;
             $Registro = @mysqli_fetch_array($dsPadre,MYSQLI_ASSOC); //Se obtiene el registro que coincide con el criterio proporcionado.
                
             if($Registro)
                {
                    /*
                     * Si existe informacion para ser procesada.
                     */
                    $field = mysqli_fetch_field($dsCampos);
                    echo'<div id= "headinfo" style= "display:none">
                            <input type= "text" id= "pagina" value="1">
                            <input type="text" id="idObjEst" value='.$idObjEst.'></input>
                            <input type="text" id="idObjOpe" value='.$idObjOpe.'></input>
                            <input type="text" id="idEstOpe" value='.$idEstOpe.'></input>
                            <input type="text" id="idPrograma" value='.$idPrograma.'></input>
                            <input type= "text" id= "Periodo" value="'.$Periodo.'">    
                         </div>';
                    echo'<div id="infoRegistro" class="operativo" style="top:65%; left:50%;">
                                <div id="cabecera" class="cabecera-operativo">'.
                                    '<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.
                                '</div>
                                <div id="cuerpo" class="cuerpo-operativo">
                                    <center><table>';
                                while($field)
                                    {    
                    echo'               <tr colspan= 2><td class="td-panel" width="500px">'.$field->name.': '.$Registro[$field->name].'</td></tr>';
                                        $field = mysqli_fetch_field($dsCampos);
                                        }
                    echo'
                            </table><br>';
                    }
            /*
             * Obteniendo la informacion asociada de la programacion en planeacion.
             */                    
             $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
             $consulta= "SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opProgPro WHERE Status=0 AND idPrograma=".$idRegistro; //Se establece el modelo de consulta de datos.
             $dsPadre = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
             $dsTitulos = $dsPadre;
             $Registro = @mysqli_fetch_array($dsPadre,MYSQLI_ASSOC); //Se obtiene el registro que coincide con el criterio proporcionado.
             
             if($Registro)
                {
                    /*
                     * Si existe informacion para ser procesada.
                     */
                    $rowdata='<table><tr><th class="dgHeader-Planeacion" colspan= "14">Datos de la Programacion</th></tr>';
                    $field = mysqli_fetch_field($dsTitulos);
                    $rowdata.='<tr><td></td>';
                    
                    while($field)
                        {
                            $rowdata.= '<td class="dgDH-Planeacion">'.$field->name.'</td>';
                            $field = mysqli_fetch_field($dsTitulos);
                            }
                    
                    $rowdata.='<td class="dgDH-Planeacion">Total %</td>';
                            
                    $consulta= "SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opProgPro WHERE Status=0 AND idPrograma=".$idRegistro; //Se establece el modelo de consulta de datos.
                    $dsPadre = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $dsCampos = $dsPadre;
                                                
                    $field = mysqli_fetch_field($dsCampos);
                    $rowdata.='<tr><td class="dgDH-Planeacion">Programado</td>';
                    $count=1;
                    
                    $totProgramado=0;
                    
                    while($field)
                        {
                            $rowdata.= '<td class="dgRowsnormTR"><input type="text" id="pr_'.$count.'" size="4" value="'.$Registro[$field->name].'"></input></td>';
                            $totProgramado += $Registro[$field->name];
                            $field = mysqli_fetch_field($dsCampos);
                            $count += 1;          
                            }
                    $rowdata.='<td class="dgRowsnormTR"><input type="text" id="pr_'.$count.'" size="4" value="'.$totProgramado.'"></input></td></tr>';
                    echo $rowdata;
                    }               
            /*
             * Obteniendo la informacion asociada de la ejecucion en planeacion.
             */
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= "SELECT Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEjecPro WHERE Status=0 AND idPrograma=".$idRegistro; //Se establece el modelo de consulta de datos.
            $dsPadre = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $dsCampos = $dsPadre;
            $Registro = @mysqli_fetch_array($dsPadre,MYSQLI_ASSOC); //Se obtiene el registro que coincide con el criterio proporcionado.
                     
            if($Registro)
                {
                    /*
                     * Si existe informacion para ser procesada.
                     */
                    $field = mysqli_fetch_field($dsCampos);
                    $rowdata='<tr><td class="dgDH-Planeacion">Ejecutado</td>';
                    $count=1;
                    $totEjecutado=0;
                    
                    while($field)
                        {
                            $rowdata.= '<td class="dgRowsnormTR"><input type="text" id="ej_'.$count.'" size="4" value="'.$Registro[$field->name].'"></input></td>';
                            $totEjecutado += $Registro[$field->name];
                            $field = mysqli_fetch_field($dsCampos);
                            $count += 1;
                            }
                            
                    $rowdata.='<td class="dgRowsnormTR"><input type="text" id="ej_'.$count.'" size="4" value="'.$totEjecutado.'"></input></td></tr>';
                    echo $rowdata;
                    }
                    
            /*
             * Obteniendo la informacion asociada de la eficacia en planeacion.
             */
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= "SELECT Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEficPro WHERE Status=0 AND idPrograma=".$idRegistro; //Se establece el modelo de consulta de datos.
            $dsPadre = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $dsCampos = $dsPadre;
            $Registro = @mysqli_fetch_array($dsPadre,MYSQLI_ASSOC); //Se obtiene el registro que coincide con el criterio proporcionado.
                     
            if($Registro)
                {
                    /*
                     * Si existe informacion para ser procesada.
                     */
                    $field = mysqli_fetch_field($dsCampos);
                    $rowdata='<tr><td class="dgDH-Planeacion">Eficacia</td>';
                    $count=1;
                    $totEficacia=0;
                    
                    while($field)
                        {
                            $rowdata.= '<td class="dgRowsnormTR"><input type="text" id="efic_'.$count.'" size="4" value="'.$Registro[$field->name].'"></input></td>';
                            cargarBanderas($Registro[$field->name], $count);//Se genera la fila de banderas.                            
                            $totEficacia += $Registro[$field->name];
                            $field = mysqli_fetch_field($dsCampos);
                            $count += 1;
                            }
                    $totEficacia=$totEficacia/12.00;
                    cargarBanderas($totEficacia, $count);//Se genera la fila de banderas.
                    $rowdata.='<td class="dgRowsnormTR"><input type="text" id="efic_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                    echo $rowdata;
                    echo '<tr><td class="dgDH-Planeacion">Estado</td>'.$rowBanderas.'</tr></table><br>';                    
                    }                                                                       
            }
            
    function constructor($dataset)
        {        
            /* Esta funcion se encarga de crear el contenido HTML de la pagina
             * tal como lo visualizara el usuario en el navegador.
             */
            global $idObjEst, $idObjOpe, $idEstOpe, $idPrograma, $Periodo;
             
            echo'
                    <html>
                        <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                        <head>          
                        </head>
                        <body>';
            echo'       <center>';
            
                        loadInfo($idPrograma,'Informacion del Programa');
                        $_GET['periodo'] = $Periodo;
                        
            echo '<div id="divLstActividades">';                        
                    include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/consulplan/actividades/lstActividades.php");
            echo '</div>';
                                    
            echo'       <center>';
            echo'
                                </div>
                                <div id="pie" class="pie-operativo">
                                    <tr><td alignt= "left" colspan= 2"><a href="#" onclick="cargar(\'./php/frontend/consulplan/programas/conPrograma.php\',\'?idestope='.$idEstOpe.'&idobjope='.$idObjOpe.'&idobjest='.$idObjEst.'\',\'sandbox\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a></td></tr>
                                </div>
                            </div>
                        </body>
                    </html>';
            }
            
        constructor($dataset); //Llamada a la funcion principal de la pagina.
?>

