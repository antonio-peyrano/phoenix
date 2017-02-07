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

    if(isset($_GET['id'])&&isset($_GET['identidad'])&&isset($_GET['numeconomico'])&&isset($_GET['numeroplaca'])&&isset($_GET['color'])&&isset($_GET['marca'])&&isset($_GET['modelo'])&&isset($_GET['tmotor'])&&isset($_GET['periodo'])&&isset($_GET['status']))
        {
            $idVehiculo = $_GET['id'];
            $idEntidad = $_GET['identidad'];
            $NumEconomico = $_GET['numeconomico'];
            $NumPlaca = $_GET['numeroplaca'];
            $Color = $_GET['color'];
            $Marca = $_GET['marca'];
            $Modelo = $_GET['modelo'];
            $TMotor = $_GET['tmotor'];
            $Periodo = $_GET['periodo'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)
            }
        
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */    
            if($idVehiculo != null)
                {
                    /*
                     * En caso que la acción ejecutada sea una edición.
                     */
                    $consulta = 'UPDATE catVehiculos SET NumEconomico=\''.$NumEconomico.'\', NumPlaca=\''.$NumPlaca.'\', Color=\''.$Color.'\', Marca=\''.$Marca.'\', Modelo=\''.$Modelo.'\', TMotor=\''.$TMotor.'\', Periodo=\''.$Periodo.'\', idEntidad=\''.$idEntidad.'\', Status=\''.$Status.'\' WHERE idVehiculo='.$idVehiculo; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    }
            else
                {
                    /*
                     * En caso que la acción ejecutada sea una creación.
                     */
                    $consulta = 'INSERT INTO catVehiculos (idEntidad, NumEconomico, NumPlaca, Color, Marca, Modelo, TMotor, Periodo) VALUES ('.'\''.$idEntidad.'\',\''.$NumEconomico.'\',\''.$NumPlaca.'\',\''.$Color.'\',\''.$Marca.'\',\''.$Modelo.'\',\''.$TMotor.'\', \''.$Periodo.'\')'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                                    
                    }
                   
            include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/vehiculos/busVehiculos.php");
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