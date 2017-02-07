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

    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.
    
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $clavecod = '';   

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
             * Esta funci�n establece la carga del conjunto de registros de niveles.
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
            $consulta= 'SELECT idUsuario, catUsuarios.idNivel, Usuario, Clave, Correo, Pregunta, Respuesta, catUsuarios.Status FROM catUsuarios INNER JOIN catNiveles ON catNiveles.idNivel = catUsuarios.idNivel WHERE idUsuario='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
            
    $Registro = @mysql_fetch_array(cargarRegistro($parametro), MYSQL_ASSOC);//Llamada a la funci�n de carga de registro de usuario.

    function controlVisual($idRegistro)
        {
            /*
             * Esta funci�n controla los botones que deberan verse en la pantalla deacuerdo con la acci�n solicitada por el
             * usuario en la ventana previa. Si es una edici�n, los botones borrar y guardar deben verse. Si es una creaci�n
             * solo el boton guardar debe visualizarse.
             */
            global $cntview;
            
            if($idRegistro == -1)
                {
                    //En caso que la acci�n corresponda a la creaci�n de un nuevo registro.
                    echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/usuarios/busUsuarios.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarUsuario(\'./php/backend/usuarios/guardar.php\',\'?id=\'+document.getElementById(\'idUsuario\').value.toString()+\'&usuario=\'+document.getElementById(\'Usuario\').value.toString()+\'&clave=\'+document.getElementById(\'Clave\').value.toString()+\'&correo=\'+document.getElementById(\'Correo\').value.toString()+\'&idnivel=\'+document.getElementById(\'idNivel\').value.toString()+\'&pregunta=\'+document.getElementById(\'Pregunta\').value.toString()+\'&respuesta=\'+document.getElementById(\'Respuesta\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acci�n de visualizaci�n.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/usuarios/busUsuarios.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/usuarios/borrar.php\',\'?id=\'+document.getElementById(\'idUsuario\').value.toString(),\'escritorio\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarUsuario(\'./php/backend/usuarios/guardar.php\',\'?id=\'+document.getElementById(\'idUsuario\').value.toString()+\'&usuario=\'+document.getElementById(\'Usuario\').value.toString()+\'&clave=\'+document.getElementById(\'Clave\').value.toString()+\'&correo=\'+document.getElementById(\'Correo\').value.toString()+\'&idnivel=\'+document.getElementById(\'idNivel\').value.toString()+\'&pregunta=\'+document.getElementById(\'Pregunta\').value.toString()+\'&respuesta=\'+document.getElementById(\'Respuesta\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habUsuario();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acci�n corresponda a la edici�n de un registro.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/usuarios/busUsuarios.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarUsuario(\'./php/backend/usuarios/guardar.php\',\'?id=\'+document.getElementById(\'idUsuario\').value.toString()+\'&usuario=\'+document.getElementById(\'Usuario\').value.toString()+\'&clave=\'+document.getElementById(\'Clave\').value.toString()+\'&correo=\'+document.getElementById(\'Correo\').value.toString()+\'&idnivel=\'+document.getElementById(\'idNivel\').value.toString()+\'&pregunta=\'+document.getElementById(\'Pregunta\').value.toString()+\'&respuesta=\'+document.getElementById(\'Respuesta\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habUsuario();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    }
            }
            
    function constructor()
        {
            /*
             * Esta funci�n establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod;
            
            $objCodificador = new codificador();
            $habcampos = 'disabled= "disabled"';
            $clavecod = $objCodificador->decrypt($Registro['Clave'],"ouroboros");
            
            if($Registro['idUsuario'] == null)
                {
                    //En caso que el registro sea de nueva creacion.
                    $habcampos='';        
                    }

            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        </head>
                        <body>
                            <div style=display:none>
                                <input type= "text" id= "idUsuario" value="'.$Registro['idUsuario'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                            <center>
                            <div id="infoUsuario" style="width: 400px; height: 600px;">                                
                            <table class="dgTable">
                                <tr><th class="dgHeader" colspan= 2">Usuario en el Sistema</th></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Usuario:</td><td class="dgRowsnormTR"><input type= "text" id= "Usuario" required= "required" '.$habcampos.' value= "'.$Registro['Usuario'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Clave:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Clave" '.$habcampos.' value= "'.$clavecod.'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Correo:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Correo" '.$habcampos.' value= "'.$Registro['Correo'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Nivel:</td><td class="dgRowsnormTR"><select name= "idNivel" id= "idNivel" '.$habcampos.' value= "'.$Registro['idNivel'].'">';
                                $subconsulta = cargarNiveles();
            echo '                              <option value=-1>Seleccione</option>';
                       
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
                                        
            echo'           </select></td></tr>';
                                                    
                            cargarPreguntas($Registro['Pregunta']);
                            
            echo'           <tr><td class="dgRowsaltTR">Respuesta: </td><td class="dgRowsnormTR"><input id= "Respuesta" type= "text" value= "'.$Registro['Respuesta'].'"></td></tr>';
                                controlVisual($parametro);
            echo'           </table>
                            </div> 
                            </center> 
                        </body>
                    </html>
                    ';            
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        constructor();
?>