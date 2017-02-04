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

    header('Content-Type: text/html; charset=UTF-8'); //Forzar la codificación a UTF-8.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/grid.class.php"); //Se carga la referencia a la clase para manejo de rejillas de datos.
    
    global $username, $password, $servername, $dbname;
    
    $condicionales = ''; //Variable de control de condiciones de clausula select.
    $sufijo= "emp_"; //Variable de control de sufijo de identificadores.
    $Inicio = 0;
    $Pagina = 0;
    $DisplayRow = 10;
    
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
        
    if(isset($_GET['nomempleado']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $condicionales= 'Nombre LIKE \'%'.$_GET['nomempleado'].'%\'';
            }

    if(isset($_GET['patempleado']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($condicionales!='')
                {
                    //Si la variable de condicionales no esta vacia.
                    $condicionales= $condicionales.' AND ';
                    }
            
            $condicionales= $condicionales.'Paterno LIKE \'%'.$_GET['patempleado'].'%\'';
            }

    if(isset($_GET['matempleado']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($condicionales!='')
                {
                    //Si la variable de condicionales no esta vacia.
                    $condicionales= $condicionales.' AND ';
                    }
            
            $condicionales= $condicionales.'Materno LIKE \'%'.$_GET['matempleado'].'%\'';
            }
            
    if(isset($_GET['empidentidad']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($_GET['empidentidad']!="-1")
                {
                    if($condicionales!='')
                        {
                            //Si la variable de condicionales no esta vacia.
                            $condicionales= $condicionales.' AND ';
                            }
            
                    $condicionales= $condicionales.'opEmpleados.idEntidad LIKE \'%'.$_GET['empidentidad'].'%\'';
                    }
            } 
       
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

    if($condicionales=="")
        {
            //Cargar la cadena de consulta por default.
            $consulta= "SELECT idEmpleado, CONCAT(Nombre, ' ',Paterno, ' ', Materno) AS Empleado, CONCAT(Calle, ' ', Nint, ' ', Next, ' ', Colonia) AS Direccion, Entidad, opEmpleados.Status FROM (opEmpleados INNER JOIN catColonias ON catColonias.idColonia = opEmpleados.idColonia) INNER JOIN catEntidades ON catEntidades.idEntidad = opEmpleados.idEntidad WHERE opEmpleados.Status=0"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
            }  
    else 
        {
            //En caso de contar con el criterio de filtrado.
            $consulta= "SELECT idEmpleado, CONCAT(Nombre, ' ',Paterno, ' ', Materno) AS Empleado, CONCAT(Calle, ' ', Nint, ' ', Next, ' ', Colonia) AS Direccion, Entidad, opEmpleados.Status FROM (opEmpleados INNER JOIN catColonias ON catColonias.idColonia = opEmpleados.idColonia) INNER JOIN catEntidades ON catEntidades.idEntidad = opEmpleados.idEntidad WHERE opEmpleados.Status=0 AND " .$condicionales." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
            }  
    
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
   
    $column_names= ""; //Variable de control para los nombres de columnas a mostrarse en el grid.
    

    function constructor($dataset,$sufijo)
        {        
            /* Esta funci�n se encarga de crear el contenido HTML de la pagina
             * tal como lo visualizara el usuario en el navegador.
             */
        
            $objGrid = new myGrid($dataset,'Catalogo de Empleados', $sufijo, 'idEmpleado');
                
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
                    </html>           
                ';
            }

    constructor($dataset,$sufijo); //Llamada a la funcion principal de la pagina.
?>

