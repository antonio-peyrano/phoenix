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
 * Este modulo lleva el control de las propuestas de alta de usuarios en el sistema, para
 * ser atendidas por el administrador.
 */
    header('Content-Type: text/html; charset=UTF-8'); //Forzar la codificación a UTF-8.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.
    
    $valadmin = 0;
    
    if(isset($_GET['id']))
        {
            //Solo para el caso que el formulario sea invocado por un usuario externo.
            $parametro = $_GET['id'];        
            }
    else
        {
            //En el caso que el formulario sea invocado por un administrador.
            $parametro = -1;
            }
    
    
    if(isset($_GET['admin']))
        {
            //En caso que el formulario sea invocado por un administrador.
            $valadmin = $_GET['admin'];
            }
            
    function cargarPreguntas($Pregunta)
        {
            /*
             * Esta función carga las preguntas disponibles para interacción con el usuario.
             */
            echo '  <tr><td class="dgRowsaltTR">Pregunta: </td>
                        <td class="dgRowsnormTR">
                            <select id= "Pregunta">';
                                if("" == $Pregunta)
                                    {
            echo'                       <option value= "Seleccione" selected="selected">Seleccione</option>';                                        
                                        }
                                else
                                    {
            echo'                       <option value= "Seleccione">Seleccione</option>';                                        
                                        }                            
                                if("Su primera mascota" == $Pregunta)
                                    {                                        
            echo'                       <option value= "Su primera mascota" selected="selected">Su primera mascota</option>';                                        }
                                else
                                    {
            echo'                       <option value= "Su primera mascota">Su primera mascota</option>';                                        
                                        }            
                                if("Su comida favorita" == $Pregunta)
                                    {
            echo'                       <option value= "Su comida favorita" selected="selected">Su comida favorita</option>';                                        
                                        }
                                else
                                    {
            echo'                       <option value= "Su comida favorita">Su comida favorita</option>';                                        
                                        }            
                                if("Su pasatiempo favorito" == $Pregunta)
                                    {
            echo'                       <option value= "Su pasatiempo favorito" selected="selected">Su pasatiempo favorito</option>';                                        
                                        }
                                else
                                    {
            echo'                       <option value= "Su pasatiempo favorito">Su pasatiempo favorito</option>';                                        
                                        }
                               if("Su pelicula favorita" == $Pregunta)
                                    {
            echo'                       <option value= "Su pelicula favorita" selected="selected">Su pelicula favorita</option>';                                        
                                        }
                                else
                                    {
            echo'                       <option value= "Su pelicula favorita">Su pelicula favorita</option>';                                        
                                        }                                                    
            echo'           </select>
                        </td>
                    </tr>';             
            }
                
    function cargarNiveles()
        {
            /*
             * Esta función establece la carga del conjunto de registros de niveles.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idNivel, Nivel FROM catNiveles WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
            
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idUsrtmp, opUsrTemp.idNivel, Usuario, Clave, Correo, Pregunta, Respuesta, opUsrTemp.Status FROM opUsrTemp INNER JOIN catNiveles ON catNiveles.idNivel = opUsrTemp.idNivel WHERE idUsrtmp='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            } 
                        
    function controlVisual($parametro)
        {
            //Esta función muestra los controles que visualizara el usuario dependiendo de su nivel.
            if($parametro == 0)
                {
                    //Si el invocante es un usuario externo.
                    echo'<tr class= "dgTitles"><td colspan= "2"><a href="#"  data-toggle="tooltip" title="Enviar la solicitud de alta" onclick="enviarRegistro(\'../../../php/backend/usraltas/guardar.php\',\'?usuario=\'+document.getElementById(\'Usuario\').value.toString()+\'&clave=\'+document.getElementById(\'Clave\').value.toString()+\'&correo=\'+document.getElementById(\'Correo\').value.toString()+\'&pregunta=\'+document.getElementById(\'Pregunta\').value.toString()+\'&idnivel=\'+document.getElementById(\'idNivel\').value.toString()+\'&respuesta=\'+document.getElementById(\'Respuesta\').value.toString());"><img align= "right" src= "../../../img/enviar.png" width= "25" height= "25" alt= "Enviar" id= "btnEnviar"/></a></td></tr>';
                    }
            else
                {
                    //Si el invocante es el administrador.
                    echo'<tr class= "dgTitles"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/usraltas/busSolAltUsr.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#"  data-toggle="tooltip" title="Guardar" onclick="enviarRegistro(\'./php/backend/usuarios/guardar.php\',\'?usuario=\'+document.getElementById(\'Usuario\').value.toString()+\'&clave=\'+document.getElementById(\'Clave\').value.toString()+\'&idusrtmp=\'+document.getElementById(\'idUsrtmp\').value.toString()+\'&correo=\'+document.getElementById(\'Correo\').value.toString()+\'&pregunta=\'+document.getElementById(\'Pregunta\').value.toString()+\'&idnivel=\'+document.getElementById(\'idNivel\').value.toString()+\'&respuesta=\'+document.getElementById(\'Respuesta\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }            
            }
    
    $Registro = @mysql_fetch_array(cargarRegistro($parametro), MYSQL_ASSOC);//Llamada a la funci�n de carga de registro de usuario.
    
    $objCodificador= new codificador();
    
    $clavecod = $objCodificador->decrypt($Registro['Clave'],"ouroboros");
    
    echo'
        <html>
            <head>
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
                
                if($valadmin != 0)
                    {
    echo'               <link rel= "stylesheet" href= "./css/divLogin.css" title="style css" type="text/css" media="screen" charset="utf-8"></style>
                        <link rel= "stylesheet" href= "./css/dgstyle.css"></style>';                        
                        }
                else
                    {
    echo'               <link rel= "stylesheet" href= "../../../css/divLogin.css" title="style css" type="text/css" media="screen" charset="utf-8"></style>
                        <link rel= "stylesheet" href= "../../../css/dgstyle.css"></style>';                        
                        }
                                                
    echo'       <script type="text/javascript" src="../../../js/jsplug.js"></script>
                <script type="text/javascript" src="../../../js/usraltas/jsusraltas.js"></script>
            </head>
            <body>
                <br>
                <br>
                <br>
                <br>
                <br>
                <center>
                    <div style=display:none>
                        <input type= "text" id= "idUsrtmp" value="'.$Registro['idUsrtmp'].'">
                        <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                    </div>
                    <div id="infoSolicitud" style="width: 400px; height: 600px;">
                        <table class="dgTable">
                            <tr><th colspan= "2" class= "dgHeader">Petición de Registro de Usuario</th></tr>
                            <tr><td class="dgRowsaltTR">Usuario: </td><td class="dgRowsnormTR"><input id= "Usuario" type= "text" value= "'.$Registro['Usuario'].'"></td></tr>
                            <tr><td class="dgRowsaltTR">Clave: </td><td class="dgRowsnormTR"><input id= "Clave" type= "text" value= "'.$clavecod.'"></td></tr>
                            <tr><td class="dgRowsaltTR">Repita su clave: </td><td class="dgRowsnormTR"><input id= "ReClave" type= "text" value= "'.$clavecod.'"></td></tr>';
                            
                            cargarPreguntas($Registro['Pregunta']);
                            
            echo'           <tr><td class="dgRowsaltTR">Respuesta: </td><td class="dgRowsnormTR"><input id= "Respuesta" type= "text" value= "'.$Registro['Respuesta'].'"></td></tr>
                            <tr><td class="dgRowsaltTR">Correo: </td><td class="dgRowsnormTR"><input id= "Correo" type= "text" value= "'.$Registro['Correo'].'"></td></tr>
                            <tr><td class="dgRowsaltTR">Nivel:</td><td class="dgRowsnormTR"><select name= "idNivel" id= "idNivel" value= "'.$Registro['idNivel'].'">';

                            $subconsulta = cargarNiveles();
                            
            echo '              <option value=-1>Seleccione</option>';
                       
                                $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                
                                while($RegNiveles)
                                    {
                                        if($RegNiveles['idNivel']==$Registro['idNivel'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo '                              <option value='.$RegNiveles['idNivel'].' selected="selected">'.$RegNiveles['Nivel'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo '                              <option value='.$RegNiveles['idNivel'].'>'.$RegNiveles['Nivel'].'</option>';                                                
                                                }
                                        $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                        }
                                
            echo'               </select></td></tr>';
                                                                    
                            controlVisual($valadmin); //Llamada a la funcion de control de visualización.
                                                                                    
    echo'               </table>
                    </div>
                </center>
            </body>
        </html>
        ';
?>