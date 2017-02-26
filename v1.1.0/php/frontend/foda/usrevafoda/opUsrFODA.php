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

    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    session_start(); //Recuperando la sesion para acceder a las variables de usuario.

    class usrFODA
        {
            private $idEmpleado='';
            private $idEvaluacion='';
            private $cuerpoEva='';
            
            function __construct()
                {
                    if(isset($_SESSION['idEmpleado']))
                        {
                            $this->idEmpleado=$_SESSION['idEmpleado'];
                            }
                    }

            function contestados($idEvaluacion)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $consulta = "SELECT *FROM opResParFoda WHERE idEvaluacion=".$idEvaluacion." AND Status=0";
                    $dataSet = $objConexion->conectar($consulta);
                    
                    return @mysql_num_rows($dataSet);
                    }
                                        
            function checksave($idEscala, $idFactor, $idEvaluacion)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $consulta = "SELECT *FROM opResParFoda WHERE idEscala=".$idEscala." AND idFactor=".$idFactor." AND idEvaluacion=".$idEvaluacion." AND Status=0";
                    $dataSet = $objConexion->conectar($consulta);
                    
                    if(@mysql_num_rows($dataSet) == 0)
                        {
                            return "";
                            }
                    else
                        {
                            return "checked";
                            }                    
                    }

            function checkResultado($idEscala, $idFactor, $idEvaluacion)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $consulta = "SELECT *FROM (opResParFoda INNER JOIN opEscalas ON opEscalas.idEscala = opResParFoda.idEscala) WHERE opResParFoda.idEscala=".$idEscala." AND opResParFoda.idFactor=".$idFactor." AND opResParFoda.idEvaluacion=".$idEvaluacion." AND opResParFoda.Status=0";
                    $dataSet = $objConexion->conectar($consulta);
                    $registro = @mysql_fetch_array($dataSet,MYSQL_ASSOC);
                    
                    if($registro)
                        {
                            return $registro['Ponderacion'];
                            }
                    else
                        {
                            return "NP";
                            }
                    }
                                        
            function contarColumnas($idEmpleado)
                {
                    global $username, $password, $servername, $dbname;
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $dataSetEscalas = $objConexion->conectar("SELECT opCedulas.Folio, idEscala, Escala FROM ((opEvaluaciones INNER JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) INNER JOIN opEscalas ON opEscalas.idCedula = opCedulas.idCedula) WHERE opEvaluaciones.Status = 0 AND idEmpleado=".$idEmpleado);
                    $conteo =  mysql_num_rows($dataSetEscalas);
                    return $conteo;
                    }
                            
            function constructEvaluacion($idEmpleado)
                {
                    global $username, $password, $servername, $dbname;
                    
                    $conteo = 0;
                    $conteoFactor = 0;
                    
                    /*
                     * Se ejecuta la conexion con la base de datos y posterior recoleccion
                     * de los items asociados al empleado.
                     */
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $dataSetFactores = $objConexion->conectar("SELECT opCedulas.Folio, idFactor, Factor FROM ((opEvaluaciones INNER JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) INNER JOIN opFactores ON opFactores.idCedula = opCedulas.idCedula) WHERE opEvaluaciones.Status = 0 AND idEmpleado=".$idEmpleado);
                    $rowSetFactores = @mysql_fetch_array($dataSetFactores,MYSQL_ASSOC);                    
                                        
                    $this->cuerpoEva = '    <table class="dgTable">
                                                <tr><th class= "dgHeader" colspan='.$this->contarColumnas($this->idEmpleado).'>'.$rowSetFactores['Folio'].'</th></tr>';
                    
                    while($rowSetFactores)
                        {
                            $conteoFactor+=1;
                            $this->cuerpoEva.='<tr class="dgRowsnormTR"><td style="display:none"><input type="text" class="txtfactor" id="idFactor_'.$conteoFactor.'" value="'.$rowSetFactores['idFactor'].'"></td><td colspan='.$this->contarColumnas($this->idEmpleado).'>'.$rowSetFactores['Factor'].'</td></tr>';
                            $this->cuerpoEva.='<tr class="dgRowsaltTR">';
                            
                            $dataSetEscalas = $objConexion->conectar("SELECT opCedulas.Folio, idEscala, Escala, Ponderacion FROM ((opEvaluaciones INNER JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) INNER JOIN opEscalas ON opEscalas.idCedula = opCedulas.idCedula) WHERE opEvaluaciones.Status = 0 AND idEmpleado=".$idEmpleado);
                            $rowSetEscalas = @mysql_fetch_array($dataSetEscalas,MYSQL_ASSOC);
                            
                            $conteo+=1;
                            $tmpidEscala=0;
                            
                            while($rowSetEscalas)
                                {
                                    $state = $this->checksave($rowSetEscalas['idEscala'], $rowSetFactores['idFactor'], $this->idEvaluacion);
                                    if($state == "checked")
                                        {
                                            $tmpidEscala = $rowSetEscalas['idEscala'];
                                            }
                                    $this->cuerpoEva.='<td><input type="text" style="display:none" class="txtescala" id="idEscala_'.$conteo.'" value="'.$rowSetEscalas['idEscala'].'"><input type="radio" class="radio" id="Escala_'.$conteo.'" name="Escala_'.$conteo.'" value="'.$rowSetEscalas['Ponderacion'].'"'.$state.'>'.$rowSetEscalas['Escala'].'</td>';
                                    $rowSetEscalas = @mysql_fetch_array($dataSetEscalas,MYSQL_ASSOC);
                                    }
                                    
                            $this->cuerpoEva.='<td style="display:none"><input type="text" id="Res_'.$conteoFactor.'" value="'.$this->checkResultado($tmpidEscala, $rowSetFactores['idFactor'], $this->idEvaluacion).'"></td></tr>';
                            $rowSetFactores = @mysql_fetch_array($dataSetFactores,MYSQL_ASSOC);
                            }                 
                               
                    echo $this->cuerpoEva.='<tr align="center"><td class="dgTotRowsTR" colspan='.$this->contarColumnas($this->idEmpleado).'><img id="guardarEncuesta" title="Guardar encuesta parcial" align= "middle" src= "./img/guardar_encuesta.png" width= "35" height= "35" alt= "Guardar"/><img id="enviarEncuesta" title="Enviar encuesta terminada" align= "middle" src= "./img/enviar_encuesta.png" width= "35" height= "35" alt= "Enviar"/></td></tr></table>';                            
                    }
                                        
            function head()
                {
                    echo '  <head>
                                <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                            </head>';         
                    }
                                        
            function body()
                {
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                    $dataSet = $objConexion->conectar("SELECT *FROM ((opEvaluaciones INNER JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) INNER JOIN opFactores ON opFactores.idCedula = opCedulas.idCedula) WHERE opEvaluaciones.Status = 0 AND opEvaluaciones.idEmpleado=".$this->idEmpleado);
                    $rowSet = @mysql_fetch_array($dataSet,MYSQL_ASSOC);
                    $conteo = @mysql_num_rows($dataSet);

                    $this->idEvaluacion = $rowSet['idEvaluacion'];
                    
                    echo '  <body>
                                <div id="control" style="display:none">
                                    <table>
                                        <tr><td>id Evaluacion: </td><td><input id="idEvaluacion" type="text" value="'.$this->idEvaluacion.'"></td></tr>
                                        <tr><td>id Empleado: </td><td><input id="idEmpleado" type="text" value="'.$this->idEmpleado.'"></td></tr>                                            
                                        <tr><td>Reactivos: </td><td><input id="Reactivos" type="text" value="'.$conteo.'"></td></tr>
                                        <tr><td>Contestados: </td><td><input id="Contestados" type="text" value="'.$this->contestados($this->idEvaluacion).'"></td></tr>
                                        <tr><td>Status: </td><td><input id="Status" type="text" value="'.$rowSet['Status'].'"></td></tr>                                            
                                    </table>
                                </div>
                                <div id="workbench">';
                                
                                if($this->idEmpleado == 0)
                                    {
                                        include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/main/notificacionFODA.php");
                                        }
                                else
                                    {
                                        $objConexion = new mySQL_conexion($username, $password, $servername, $dbname);
                                        $dataSet = $objConexion->conectar("SELECT *FROM opEvaluaciones WHERE Status = 0 AND idEmpleado=".$this->idEmpleado);
                                        $rowSet = @mysql_fetch_array($dataSet,MYSQL_ASSOC);
                                        
                                        if($rowSet)
                                            {
                                                $this->constructEvaluacion($this->idEmpleado);                                                
                                                }
                                        else
                                            {
                                                include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/main/notificacionFODA.php");
                                                }                                                
                                        }
                    
                    echo '      </div>
                            </body>';
                    }
            }
    
    $objusrFODA = new usrFODA();
    
    echo '  <html>';
                $objusrFODA->head();
                $objusrFODA->body();
    echo '  </html>';
                
?>