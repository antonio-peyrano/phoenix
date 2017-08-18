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
     * Este modulo sirve como pasarela de ejecución del comando guardar, cuando es ejecutado desde un formulario
     * para la edición de registro.
     */
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $cntrlVar = 0;
    
    if(isset($_GET['idevaluacion'])&&isset($_GET['idfactor'])&&isset($_GET['idescala']))
        {
            /*
             * En caso de no ocurrir un error con el paso de variables por
             * URL, se procede a su asignacion.
             */            
            $idEvaluacion = $_GET['idevaluacion'];
            $idFactor = $_GET['idfactor'];
            $tmpFactores = explode('%',$idFactor);
            $idEscala = $_GET['idescala'];
            $tmpEscalas = explode('%',$idEscala);
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)                    
            }

    function existencias($idFactor, $idEvaluacion)
        {
            global $username, $password, $servername, $dbname;
            
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            $consulta = "SELECT *FROM opResParFoda WHERE idEvaluacion=".$idEvaluacion." AND idFactor=".$idFactor;
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
            if($dataset)
                {
                    return mysqli_num_rows($dataset);
                    }
            else
                {
                    return 0;
                    }
            }
            
    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
            for($conteo = 1; $conteo < count($tmpFactores); $conteo++)
                {
                    /*
                     * Se ejecuta el proceso de almacenamiento de datos, validando
                     * que no existan referencias previas.
                     */
                    if(existencias($tmpFactores[$conteo], $idEvaluacion) == 0)
                        {
                            $consulta = 'INSERT INTO opResParFoda (idFactor, idEscala, idEvaluacion) VALUES ('.'\''.$tmpFactores[$conteo].'\',\''.$tmpEscalas[$conteo].'\',\''.$idEvaluacion.'\')'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                            }
                    else
                        {
                            $consulta = 'UPDATE opResParFoda SET idFactor=\''.$tmpFactores[$conteo].'\', idEscala=\''.$tmpEscalas[$conteo].'\', idEvaluacion=\''.$idEvaluacion.'\', Status=\''.$Status.'\' WHERE idEvaluacion='.$idEvaluacion.' AND idFactor='.$tmpFactores[$conteo]; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                            
                            }                            
                    } 
                                       
            $consulta = 'UPDATE opEvaluaciones SET Status=3 WHERE idEvaluacion='.$idEvaluacion; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    
            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/foda/usrevafoda/opUsrFODA.php");
            }
    else
        {
            /*
             * En caso de ocurrir un error con la operatividad del sistema,
             * se despliega un mensaje al usuario.
             */
             include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/main/errorSistema.php");
            }            
    ?>