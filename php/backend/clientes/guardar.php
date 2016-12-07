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

    if(isset($_GET['id'])&&isset($_GET['nombre'])&&isset($_GET['paterno'])&&isset($_GET['materno'])&&isset($_GET['calle'])&&isset($_GET['nint'])&&isset($_GET['next'])&&isset($_GET['idcolonia'])&&isset($_GET['rfc'])&&isset($_GET['curp'])&&isset($_GET['telfijo'])&&isset($_GET['telcel'])&&isset($_GET['status']))
        {
            /*
             * Si las variables primarias de almacenamiento se obtuvieron correctamente de la URL,
             * se procede a asignarlas en el bloque previo de ejecucion.
             */            
            $idCliente = $_GET['id'];
            $Nombre = $_GET['nombre'];
            $Paterno = $_GET['paterno'];
            $Materno = $_GET['materno'];
            $Calle = $_GET['calle'];
            $Nint = $_GET['nint'];
            $Next = $_GET['next'];
            $idColonia = $_GET['idcolonia'];
            $RFC = $_GET['rfc'];
            $CURP = $_GET['curp'];
            $TelFijo = $_GET['telfijo'];
            $TelCel = $_GET['telcel'];
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
            
            if($idCliente != null)
                {
                    /*
                     * En caso que la acci�n ejecutada sea una edici�n.
                     */
                    $consulta = 'UPDATE opClientes SET Nombre=\''.$Nombre.'\', Paterno=\''.$Paterno.'\', Materno=\''.$Materno.'\', Calle=\''.$Calle.'\', Nint=\''.$Nint.'\', Next=\''.$Next.'\', idColonia=\''.$idColonia.'\', RFC=\''.$RFC.'\', CURP=\''.$CURP.'\', TelFijo=\''.$TelFijo.'\', TelCel=\''.$TelCel.'\', Status=\''.$Status.'\' WHERE idCliente='.$idCliente; //Se establece el modelo de consulta de datos.
                    }
            else
                {
                    /*
                     * En caso que la acci�n ejecutada sea una creaci�n.
                     */
                    $consulta = 'INSERT INTO opClientes (Nombre, Paterno, Materno, Calle, Nint, Next, idColonia, RFC, CURP, TelFijo, TelCel) VALUES ('.'\''.$Nombre.'\',\''.$Paterno.'\', \''.$Materno.'\', \''.$Calle.'\', \''.$Nint.'\', \''.$Next.'\', \''.$idColonia.'\', \''.$RFC.'\', \''.$CURP.'\', \''.$TelFijo.'\', \''.$TelCel.'\')'; //Se establece el modelo de consulta de datos.
                    }
            
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/clientes/busClientes.php");                        
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