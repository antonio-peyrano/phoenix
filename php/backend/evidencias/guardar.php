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
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $cntrlVar = 0;
    
    if(isset($_GET['idejecucion'])&&isset($_GET['idprograma'])&&isset($_GET['idactividad'])&&isset($_GET['rutaurl'])&&isset($_GET['status']))
        {
            /*
             * En caso de no ocurrir un error con el paso de variables por
             * URL, se procede a su asignacion.
             */            
            $idEjecucion = $_GET['idejecucion'];
            $idPrograma = $_GET['idprograma'];
            $idActividad = $_GET['idactividad'];
            $RutaURL = $_GET['rutaurl'];
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
    
            $consulta = 'INSERT INTO opEvidencias (idEjecucion, RutaURL) VALUES ('.'\''.$idEjecucion.'\',\''.$RutaURL.'\')'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.  
    
            $_GET['view'] = 3; //Se establece la variable de control de visualizaci�n.
            $_GET['id'] = $idEjecucion; //Se establece la variable de control del id del programa.
            $_GET['idprograma'] = $idPrograma;
            $_GET['idactividad'] = $idActividad;
          
            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/ejecuciones/opEjecucion.php");
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