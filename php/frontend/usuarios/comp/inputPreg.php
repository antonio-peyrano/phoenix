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

    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
        
    $Correo = '';
    $Pregunta = '';
    
    if(isset($_GET['correo']))
        {
            //Si el parametro de correo ha sido enviado por medio de la url.
            $Correo = $_GET['correo'];
            }
            
    function buscarPregunta($Parametro)
        {
            //Esta funci�n efectua el proceso de busqueda del usuario en la base de datos.
            //apartir del correo proporcionado y as� se obtiene su pregunta recordatorio.
            
            global $username, $password, $servername, $dbname;
            global $Pregunta;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM catUsuarios WHERE Correo=\''.$Parametro.'\''; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
            $Registro = @mysql_fetch_array($dataset, MYSQL_ASSOC);
            
            if($Registro)
                {
                    //Solo si existe un registro con el correo solicitado.
                    $Pregunta = $Registro['Pregunta'];                    
                    }  
            }
    buscarPregunta($Correo);        
    echo '<input type= "text" value="'.$Pregunta.'" id= "Pregunta"></input>';
?>