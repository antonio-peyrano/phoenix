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
     * Este modulo sirve como pasarela de ejecuci�n del comando guardar, cuando es ejecutado desde un formulario
     * para la edici�n de registro.
     */
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $cntrlVar = 0;
        
    if(isset($_GET['id'])&&isset($_GET['optimo'])&&isset($_GET['tolerable'])&&isset($_GET['periodo'])&&isset($_GET['status']))
        {
            /*
             * Si las variables primarias de almacenamiento se obtuvieron correctamente de la URL,
             * se procede a asignarlas en el bloque previo de ejecucion.
             */            
            $idConfiguracion = $_GET['id'];
            $Optimo = $_GET['optimo'];
            $Tolerable = $_GET['tolerable'];
            $Periodo = $_GET['periodo'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)                        
            }


    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
            if($idConfiguracion != null)
                {
                    /*
                     * En caso que la acci�n ejecutada sea una edici�n.
                     */
                    $consulta = 'UPDATE catConfiguraciones SET Optimo=\''.$Optimo.'\', Tolerable=\''.$Tolerable.'\', Periodo=\''.$Periodo.'\', Status=\''.$Status.'\' WHERE idConfiguracion='.$idConfiguracion; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    if($Status==0)
                        {
                            /*
                             * Si el registro seleccionado para la configuracion se declaro como activo,
                             * se anulan todos los posibles registros de configuracion previamente activos.
                             */
                            $consulta='UPDATE catConfiguraciones SET Status=1 WHERE idConfiguracion<>'.$idConfiguracion;
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                                    
                            }
                    }
            else
                {
                    /*
                     * En caso que la acci�n ejecutada sea una creaci�n.
                     */
                    $consulta = 'INSERT INTO catConfiguraciones (Optimo, Tolerable, Periodo) VALUES ('.'\''.$Optimo.'\',\''.$Tolerable.'\',\''.$Periodo.'\')'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    $consulta='SELECT idConfiguracion FROM catConfiguraciones WHERE Optimo ='.$Optimo.' AND Tolerancia='.$Tolerable.' AND Periodo='.$Periodo;
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $Registro = @mysql_fetch_array($dataset, MYSQL_ASSOC); //Se obtienen los datos del registro manipulado recientemente.
            
                    if($Registro)
                        {
                            /*
                             * Con el identificador del registro, se procede a ejecutar la consulta de
                             * actualizacion sobre los registros de configuracion adyacentes para desactivarlos.
                             */
                            if($Registro['Status']==0)
                                {
                                    /*
                                     * Si el registro seleccionado para la configuracion se declaro como activo,
                                     * se anulan todos los posibles registros de configuracion previamente activos.
                                     */
                                    $consulta='UPDATE catConfiguraciones SET Status=1 WHERE idConfiguracion<>'.$Registro['idConfiguracion'];
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                                    
                                    }
                            }
                    }
                    
            include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/configuracion/busConfiguraciones.php");
            }
    else
        {
            /*
             * En caso de ocurrir un error con la operatividad del sistema,
             * se despliega un mensaje al usuario.
             */
            include_once($_SERVER['DOCUMENT_ROOT'].'/micrositio/php/frontend/main/errorSistema.php');
            }                        
    ?>