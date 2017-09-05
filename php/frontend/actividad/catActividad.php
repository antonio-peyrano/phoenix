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

    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/grid.class.php"); //Se carga la referencia a la clase para manejo de rejillas de datos.
    
    global $username, $password, $servername, $dbname;
    
    $sufijo = "act_"; //Variable de control de sufijo de identificadores.
    $condicionales = ''; //Variable de control de condiciones de clausula select.
    $Inicio = 0;
    $Pagina = 0;
    $DisplayRow = 4;
    
    if(isset($_GET['pagina']))
        {
            //Se proporciona referencia de pagina a mostrar.
            $Pagina = $_GET['pagina'];
            $Inicio = ($Pagina-1)*$DisplayRow;
            }
    else
        {
            //En caso de no ser proporcionada la pagina.
            $Inicio = 0;
            $Pagina = 1;
            }
                    
    if(isset($_GET['idprograma']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $condicionales= $condicionales.'idPrograma ='.$_GET['idprograma'];//'idPrograma =0';//
            }
    else
        {
            /*
             * Si no existe parametro de programa, se presupone que se ha invocado en la creación de registro.
             */
            $condicionales= $condicionales.'idPrograma =-1'; 
            }
       
    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
    if($condicionales=="")
        {
            //Cargar la cadena de consulta por default.
            $consulta= "SELECT idActividad, Actividad, Monto, Status FROM opActividades WHERE Status=0"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
            }  
    else 
        {
            //En caso de contar con el criterio de filtrado.
            $consulta= "SELECT idActividad, Actividad, Monto, Status FROM opActividades WHERE Status=0 AND " .$condicionales." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
            }  
    
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.    
    
    function construcCatActividad($dataset, $sufijo)
        {        
            /* Esta función se encarga de crear el contenido HTML de la pagina
             * tal como lo visualizara el usuario en el navegador.
             */ 
            
            $objGrid = new myGrid($dataset, 'Actividades', $sufijo, 'idActividad');
               
            echo'
                    <html>
                        <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        <head>          
                        </head>
                        <body>';
        
            echo $objGrid->headerTable();
            echo $objGrid->bodyTable();
                
            echo'
                        </body>
                    </html>';
        }

    construcCatActividad($dataset, $sufijo); //Llamada a la funcion principal de la pagina.
?>
