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

    require_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/mailsupport/class.phpmailer.php");
    require_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/mailsupport/class.phpmailer.php");
    require_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/mailsupport/class.smtp.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.    
    
    $correoElectronico = '';
    $Pregunta = '';
    $Respuesta = '';
    $Usuario = '';
    $Clave = '';
    
    if(isset($_GET['correo']))
        {
            //Si se ha enviado un valor por medio de la llamada con parametros.
            $correoElectronico = $_GET['correo'];
            }

    if(isset($_GET['pregunta']))
        {
            //Si se ha enviado un valor por medio de la llamada con parametros.
            $Pregunta = $_GET['pregunta'];
            }

    if(isset($_GET['respuesta']))
        {
            //Si se ha enviado un valor por medio de la llamada con parametros.
            $Respuesta = $_GET['respuesta'];
            }            
                
    function buscarUsuario($Correo, $Pregunta, $Respuesta)
        {
            //Esta funci�n efectua el proceso de busqueda del usuario en la base de datos.
            //apartir del correo proporcionado y as� enviar su usuario y clave.
            global $username, $password, $servername, $dbname;
            global $Usuario, $Clave;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM catUsuarios WHERE Correo=\''.$Correo.'\''.' AND Pregunta =\''.$Pregunta.'\' AND Respuesta=\''.$Respuesta.'\''; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
            $Registro = @mysql_fetch_array($dataset,MYSQLI_ASSOC);
            if($Registro)
                {
                    //Solo si existe un registro con el correo solicitado.
                    $Usuario = $Registro['Usuario'];
                    $Clave = $Registro['Clave'];                    
                    }              
            }
    
    function msgerrorValidacion($parametro)
        {
            //Esta funci�n genera el HTML a visualizar en caso de error de validaci�n
            //de correo.
            global $SitioWeb;
             
            echo '  <html>
                        <head>
                            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
                            <link rel="stylesheet" href="./css/menuStyle.css" title="style css" type="text/css" media="screen" charset="utf-8"></style>
                            <link rel= "stylesheet" href= "../../../css/dgstyle.css"></style>
                            <link rel= "stylesheet" href= "../../../css/divLogin.css"></style>
                            <link rel= "stylesheet" href= "../../../css/dgstyle.css"></style>
                        </head>
                        <body>
                            <center>
                                <div id="msgEnvio" style="width: 400px; height: 600px;">
                                    <table class="dgTable">
                                        <tr><th colspan= "2" class= "dgHeader"><b>ERROR DE VALIDACION</b></th></tr>
                                        <tr>
                                            <td class="dgRowsnormTR">
                                                    No existen datos de usuario asociados a la cuenta de correo <b>'.$parametro.'</b><br>
                                                    O posiblemente la respuesta a la pregunta no es la correcta.
                                                    Por favor de click en el enlace para regresar a la ventana principal.<br><br>
                                                    <a href="'.$SitioWeb.'" target="_self"><b>Regresar</b></a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </center>
                        </body>
                    </html>';            
            }
            
    function msgerrorEnvio()
        {
            //Esta funci�n genera el HTML a visualizar en caso de error en el envio.
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
                                        <tr><th colspan= "2" class= "dgHeader"><b>ERROR DE ENVIO</b></th></tr>
                                        <tr>
                                            <td class="dgRowsnormTR">
                                                    Ocurrio un error con el servicio de correo, por favor intente m�s tarde y<br>
                                                    de click en el enlace para regresar a la ventana principal.<br><br>
                                                    <a href="'.$SitioWeb.'" target="_self"><b>Regresar</b></a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </center>
                        </body>
                    </html>';             
            }

    function msgEnvio($parametro)
        {
            //Esta funci�n genera el hTML a visualizar en caso de envio.
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
                                                    Sus datos de usuario han sido enviados a la cuenta de correo <b>'.$parametro.'</b><br>
                                                    Por favor de click en el enlace para regresar a la ventana principal.<br><br>
                                                    <a href="'.$SitioWeb.'" target="_self"><b>Regresar</b></a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </center>
                        </body>
                    </html>';
            }
                                        
    function enviar_correo()
        {
            /*
             * Esta funci�n establece los parametros para el envio del correo de recordatorio
             * de clave para el usuario solicitante.
             */
            global $Usuario, $Clave, $correoElectronico;
            $objCodificador= new codificador();
                        
            $mail = new PHPMailer(); //Se declara la instancia de objeto de manejo de correo.
            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->SMTPAuth = true;
            //$mail->SMTPSecure = "ssl";
            $mail->Host = "smtp.gmail.com"; //servidor smtp
            $mail->Port = 587; //puerto smtp de gmail
            $mail->Username = 'tucorreo@domain.com';
            $mail->Password = 'tu clave';
            $mail->FromName = 'Soporte de Phoenix';
            $mail->From = 'tucorreo@domain.com';//email de remitente desde donde se env�a el correo.
            $mail->AddAddress($correoElectronico, $Usuario);//destinatario que va a recibir el correo
            //$mail->AddCC('x', 'Sxd');//env�a una copia del correo a la direcci�n especificada
            $mail->Subject = 'Recuperar contrase�a';
            $mail->AltBody = 'Estimado '.$Usuario;//cuerpo con texto plano

            $mail->MsgHTML('Estimado'.$Usuario.'<br/>'.'Su contrase�a en el sistema es: '.$objCodificador->decrypt($Clave, 'ouroboros').'<br/> Tenga un buen d�a <br/> Soporte de Phoenix');//cuerpo con html
            
            if(!$mail->Send())
                {
                    //finalmente enviamos el email.
                    msgerrorEnvio($mail->ErrorInfo); //En caso de ocurrir un error.
                    }
            else 
                {
                    msgEnvio($correoElectronico); //En caso de efectuarse el envio.
                    }
            }
            
    buscarUsuario($correoElectronico, $Pregunta, $Respuesta);//Llamada a la funci�n de busqueda de usuario por correo.
    
    if(($Usuario != '') && ($Clave != ''))
        {
            //Solo si se ha localizado un usuario y contrase�a validos.
            enviar_correo(); //Se ejecuta la funci�n de envio de correo.
            }
    else
        {
            //En caso que no se localice un usuario con el correo proporcionado.
            msgerrorValidacion($correoElectronico);
            }        
?>
