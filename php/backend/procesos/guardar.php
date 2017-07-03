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
    
    $cntrlVar=0;
    
    if(isset($_GET['id'])&&isset($_GET['proceso'])&&isset($_GET['identidad'])&&isset($_GET['nonidentidad'])&&isset($_GET['status']))
        {
            /*
             * Si las variables primarias de almacenamiento se obtuvieron correctamente de la URL,
             * se procede a asignarlas en el bloque previo de ejecucion.
             */            
            $idProceso = $_GET['id'];
            $Proceso = $_GET['proceso'];
            $idEntidad = $_GET['identidad'];
            $nonidEntidad = $_GET['nonidentidad'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)                        
            }
        
    function existencias($idRegEnt, $idRegPro)
        {
            /*
             * Esta función establece la busqueda para determinar si un registro ya existe en el sistema
             * con las condiciones proporcionadas.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT *FROM relEntPro WHERE idProceso='.$idRegPro.' AND idEntidad='.$idRegEnt; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
    
            if(!$Registro)
                {
                    /*
                     * En caso que el muestreo no arroje datos en la consulta.
                     */
                    return false;
                    }
    
            return true;
            }
        
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */    
            $temp = explode('%',$idEntidad); //Aqui se convierte el vector en un arreglo con los id seleccionados.
            $nontemp = explode('%',$nonidEntidad); //Aqui se convierte el vector en un arreglo con los id no seleccionados.
    
            if($idProceso != null)
                {
                    /*
                     * En caso que la acción ejecutada sea una edición.
                     */
                    $consulta = 'UPDATE catProcesos SET Proceso=\''.$Proceso.'\', Status=\''.$Status.'\' WHERE idProceso='.$idProceso; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crean los elementos de la relacion.
                    for($conteo=1; $conteo < count($temp); $conteo++)
                        {
                            if(!existencias($temp[$conteo], $idProceso))
                                {
                                    /*
                                     * En caso de no existir referencias previas, se crean en la entidad de las relaciones.
                                     */
                                    $consulta = 'INSERT INTO relEntPro (idEntidad, idProceso) VALUES ('.'\''.$temp[$conteo].'\',\''.$idProceso.'\')'; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            else
                                {
                                    /*
                                     * En caso de existir referencias previas, considerando que la relación fue eliminada previamente.
                                     */                            
                                    $consulta = 'UPDATE relEntPro SET Status= 0 WHERE idProceso='.$idProceso.' AND idEntidad='.$temp[$conteo]; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            }
                    
                    //Se eliminan los elementos de la relacion si fueron desmarcados.
                    for($conteo=1; $conteo < count($nontemp); $conteo++)
                        {
                            if(existencias($nontemp[$conteo], $idProceso))
                                {
                                    /*
                                     * En caso de existir referencias previas, se eliminan en la entidad de las relaciones.
                                     */
                                    $consulta = 'UPDATE relEntPro SET Status= 1 WHERE idProceso='.$idProceso.' AND idEntidad='.$nontemp[$conteo]; //Se establece el modelo de consulta de datos.                            
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            }                                
                    }
            else
                {
                    /*
                     * En caso que la acción ejecutada sea una creación.
                     */
                    $consulta = 'INSERT INTO catProcesos (Proceso) VALUES ('.'\''.$Proceso.'\')'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se busca el Proceso creado para obtener su id.
                    $consulta = 'SELECT *FROM catProcesos WHERE Proceso LIKE \'%'.$Proceso.'%\''; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
            
                    //Se crean los elementos de la relacion.
                    for($conteo=1; $conteo < count($temp); $conteo++)
                        {
                            $consulta = 'INSERT INTO relEntPro (idEntidad, idProceso) VALUES ('.'\''.$temp[$conteo].'\',\''.$Registro['idProceso'].'\')'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                            }            
                    }
              
            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/procesos/busProcesos.php");
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