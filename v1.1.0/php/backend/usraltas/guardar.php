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
     * Este modulo sirve como pasarela de ejecución del comando guardar, cuando es ejecutado desde el formulario
     * para la solicitud de creación de usuario.
     */
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.

    global $username, $password, $servername, $dbname;
    
    $cntrlVar=0;
        
    if(isset($_GET['usuario'])&&isset($_GET['clave'])&&isset($_GET['correo'])&&isset($_GET['pregunta'])&&isset($_GET['respuesta'])&&isset($_GET['idnivel']))
        {
            /*
             * Si las variables primarias de almacenamiento se obtuvieron correctamente de la URL,
             * se procede a asignarlas en el bloque previo de ejecucion.
             */            
            $Usuario = $_GET['usuario'];
            $Clave = $_GET['clave'];
            $Correo = $_GET['correo'];
            $Pregunta = $_GET['pregunta'];
            $Respuesta = $_GET['respuesta'];
            $idNivel = $_GET['idnivel'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)                        
            }

    function msgEnvio()
        {
            //Esta función genera el HTML a visualizar en caso de envio.
            global $SitioWeb;
             
            echo '  <html>
                        <head>
                            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
                            <link rel="stylesheet" href="./css/menuStyle.css" title="style css" type="text/css" media="screen" charset="utf-8"></style>                        
                            <link rel= "stylesheet" href= "../../../css/divLogin.css"></style>
                            <link rel= "stylesheet" href= "../../../css/dgstyle.css"></style>
                        </head>
                        <body>
                            <center>
                                <div id="msgEnvio" style="width: 400px; height: 600px;">
                                    <table class="dgTable">
                                        <tr><th colspan= "2" class= "dgHeader"><b>ENVIO SATISFACTORIO</b></th></tr>
                                        <tr>
                                            <td class="dgRowsnormTR">
                                                    Su solicitud ha sido enviada al administrador del sistema<br>
                                                    Espere por la activación de su cuenta.<br><br>
                                                    <a href="'.$SitioWeb.'" target="_self"><b>Regresar</b></a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </center>
                        </body>
                    </html>';
            }

    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $objCodificador= new codificador();
            $clavecod = $objCodificador->encrypt($Clave, "ouroboros");
            $consulta = 'INSERT INTO opUsrTemp (Usuario, Clave, Correo, Pregunta, Respuesta, idNivel) VALUES ('.'\''.$Usuario.'\',\''.$clavecod.'\', \''.$Correo.'\', \''.$Pregunta.'\', \''.$Respuesta.'\', \''.$idNivel.'\')'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
            msgEnvio(); //Se despliega el mensaje de envio de solicitud.
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