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
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $cntrlVar=0;

    if(isset($_GET['id'])&&isset($_GET['indicador'])&&isset($_GET['nomenclatura'])&&isset($_GET['percentil'])&&isset($_GET['idproceso'])&&isset($_GET['nonidproceso'])&&isset($_GET['status']))
        {
            /*
             * Si las variables primarias de almacenamiento se obtuvieron correctamente de la URL,
             * se procede a asignarlas en el bloque previo de ejecucion.
             */            
            $idIndicador = $_GET['id'];
            $Indicador = $_GET['indicador'];
            $Nomenclatura = $_GET['nomenclatura'];
            $Percentil = $_GET['percentil'];
            $idProceso = $_GET['idproceso'];
            $nonidProceso = $_GET['nonidproceso'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)            
            }

    function existencias($idRegPro, $idRegInd)
        {
            /*
             * Esta función establece la busqueda para determinar si un registro ya existe en el sistema
             * con las condiciones proporcionadas.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT *FROM relIndPro WHERE idIndicador='.$idRegInd.' AND idProceso='.$idRegPro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $Registro = @mysql_fetch_array($dataset, MYSQL_ASSOC);
    
            if(!$Registro)
                {
                    /*
                     * En caso que el muestreo no arroje datos en la consulta.
                     */
                    return false;
                    }
    
            return true;
            }

    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
            $temp = explode('%',$idProceso); //Aqui se convierte el vector en un arreglo con los id seleccionados.
            $nontemp = explode('%',$nonidProceso); //Aqui se convierte el vector en un arreglo con los id no seleccionados.
        
            if($idIndicador != null)
                {
                    /*
                     * En caso que la acción ejecutada sea una edición.
                     */
                    $consulta = 'UPDATE catIndicadores SET Indicador=\''.$Indicador.'\', Nomenclatura=\''.$Nomenclatura.'\', Percentil=\''.$Percentil.'\', Status=\''.$Status.'\' WHERE idIndicador='.$idIndicador; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crean los elementos de la relacion.
                    for($conteo=1; $conteo < count($temp); $conteo++)
                        {
                            if(!existencias($temp[$conteo], $idIndicador))
                                {
                                    /*
                                     * En caso de no existir referencias previas, se crean en la entidad de las relaciones.
                                     */
                                    $consulta = 'INSERT INTO relIndPro (idProceso, idIndicador) VALUES ('.'\''.$temp[$conteo].'\',\''.$idIndicador.'\')'; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            else
                                {
                                    /*
                                     * En caso de existir referencias previas, considerando que la relación fue eliminada previamente.
                                     */                            
                                    $consulta = 'UPDATE relIndPro SET Status= 0 WHERE idIndicador='.$idIndicador.' AND idProceso='.$temp[$conteo]; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            }
                    
                    //Se eliminan los elementos de la relacion si fueron desmarcados.
                    for($conteo=1; $conteo < count($nontemp); $conteo++)
                        {
                            if(existencias($nontemp[$conteo], $idIndicador))
                                {
                                    /*
                                     * En caso de existir referencias previas, se eliminan en la entidad de las relaciones.
                                     */
                                    $consulta = 'UPDATE relIndPro SET Status= 1 WHERE idIndicador='.$idIndicador.' AND idProceso='.$nontemp[$conteo]; //Se establece el modelo de consulta de datos.                            
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            }                                
                    }
            else
                {
                    /*
                     * En caso que la acción ejecutada sea una creación.
                     */
                    $consulta = 'INSERT INTO catIndicadores (Indicador, Nomenclatura, Percentil) VALUES ('.'\''.$Indicador.'\',\''.$Nomenclatura.'\',\''.$Percentil.'\')'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se busca el Indicador creado para obtener su id.
                    $consulta = 'SELECT *FROM catIndicadores WHERE Indicador LIKE \'%'.$Indicador.'%\''; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $Registro = @mysql_fetch_array($dataset, MYSQL_ASSOC);
            
                    //Se crean los elementos de la relacion.
                    for($conteo=1; $conteo < count($temp); $conteo++)
                        {
                            $consulta = 'INSERT INTO relIndPro (idProceso, idIndicador) VALUES ('.'\''.$temp[$conteo].'\',\''.$Registro['idIndicador'].'\')'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                            }            
                    } 
            
            include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/indicadores/busIndicadores.php");
            }
    else
        {
            /*
             * En caso de ocurrir un error con la operatividad del sistema,
             * se despliega un mensaje al usuario.
             */
             include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/main/errorSistema.php");
            }
    ?>