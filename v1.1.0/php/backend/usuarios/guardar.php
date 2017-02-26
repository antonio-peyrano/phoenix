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
     * para la edición de registro.
     */
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.

    global $username, $password, $servername, $dbname;
    
    $cntrlVar=0;    
    $idUsrtmp = -1;
    $idUsuario = null;
     
    if(isset($_GET['idusrtmp'])&&isset($_GET['usuario'])&&isset($_GET['clave'])&&isset($_GET['correo'])&&isset($_GET['pregunta'])&&isset($_GET['respuesta'])&&isset($_GET['idnivel'])&&isset($_GET['status']))
        {
            //Si la invocación se efectua desde el formulario de alta de usuarios externos.
            $idUsrtmp = $_GET['idusrtmp'];
            $Usuario = $_GET['usuario'];
            $Clave = $_GET['clave'];
            $Correo = $_GET['correo'];
            $Pregunta = $_GET['pregunta'];
            $Respuesta = $_GET['respuesta'];
            $idNivel = $_GET['idnivel'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)            
            }     
            
    if(isset($_GET['id'])&&isset($_GET['usuario'])&&isset($_GET['clave'])&&isset($_GET['correo'])&&isset($_GET['pregunta'])&&isset($_GET['respuesta'])&&isset($_GET['idnivel'])&&isset($_GET['status']))
        {
            //Si la invocación se efectua desde el formulario de alta de usuarios internos.
            $idUsuario = $_GET['id'];
            $Usuario = $_GET['usuario'];
            $Clave = $_GET['clave'];
            $Correo = $_GET['correo'];
            $Pregunta = $_GET['pregunta'];
            $Respuesta = $_GET['respuesta'];
            $idNivel = $_GET['idnivel'];
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
            $objCodificador=new codificador();
             
            if($idUsuario != null)
                {
                    /*
                     * En caso que la acción ejecutada sea una edición.
                     */
                    $clavecod = $objCodificador->encrypt($Clave, "ouroboros");
                    $consulta = 'UPDATE catUsuarios SET Usuario=\''.$Usuario.'\', Clave=\''.$clavecod.'\', Correo=\''.$Correo.'\', Pregunta=\''.$Pregunta.'\', Respuesta=\''.$Respuesta.'\', idNivel=\''.$idNivel.'\', Status=\''.$Status.'\' where idUsuario='.$idUsuario; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    }
            else
                {
                    /*
                     * En caso que la acción ejecutada sea una creación.
                     */
                    $clavecod = $objCodificador->encrypt($Clave, "ouroboros");
                    $consulta = 'INSERT INTO catUsuarios (Usuario, Clave, Correo, Pregunta, Respuesta, idNivel) VALUES ('.'\''.$Usuario.'\',\''.$clavecod.'\', \''.$Correo.'\', \''.$Pregunta.'\', \''.$Respuesta.'\', \''.$idNivel.'\')'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    if($idUsrtmp != -1)
                        {
                            //En caso que el registro para almacenar sea una solicitud de usuario externo, se procede a eliminar
                            //la solicitud procesada.
                            $consulta= 'UPDATE opUsrTemp SET Status=1 where idUsrtmp='.$idUsrtmp; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.    
                            }
                    }
                
            include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/usuarios/busUsuarios.php");
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