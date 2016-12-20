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
    $sufijo= "fev_"; //Variable de control de sufijo de identificadores.
    
    if(isset($_GET['fevfolio']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $condicionales= 'opEvaluaciones.Folio LIKE \'%'.$_GET['fevfolio'].'%\'';
            }

    if(isset($_GET['fevfecha']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($condicionales!='')
                {
                    //Si la variable de condicionales no esta vacia.
                    $condicionales= $condicionales.' AND ';
                    }
            
            $condicionales= $condicionales.'opEvaluaciones.Fecha LIKE \'%'.$_GET['fevfecha'].'%\'';
            }

    if(isset($_GET['fevidentidad']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($_GET['fevidentidad']!="-1")
                {            
                    if($condicionales!='')
                        {
                            //Si la variable de condicionales no esta vacia.
                            $condicionales= $condicionales.' AND ';
                            }
            
                    $condicionales= $condicionales.'opCedulas.idEntidad LIKE \'%'.$_GET['fevidentidad'].'%\'';
                    }
            }

    if(isset($_GET['fevidempleado']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($_GET['fevidempleado']!="-1")
                {            
                    if($condicionales!='')
                        {
                            //Si la variable de condicionales no esta vacia.
                            $condicionales= $condicionales.' AND ';
                            }
            
                    $condicionales= $condicionales.'opEvaluaciones.idEmpleado LIKE \'%'.$_GET['fevidempleado'].'%\'';
                    }
            }
                        
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

    if($condicionales=="")
        {
            //Cargar la cadena de consulta por default.
            $consulta= "SELECT idEvaluacion, opEvaluaciones.Folio, CONCAT(Nombre,' ', Paterno,' ', Materno) AS Empleado, opEvaluaciones.Fecha, opEvaluaciones.Status FROM (((opEvaluaciones LEFT JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) LEFT JOIN catEntidades ON opCedulas.idEntidad = catEntidades.idEntidad) LEFT JOIN opEmpleados ON opEvaluaciones.idEmpleado = opEmpleados.idEmpleado) WHERE opEvaluaciones.Status=0"; //Se establece el modelo de consulta de datos.
            }  
    else 
        {
            //En caso de contar con el criterio de filtrado.
            $consulta= "SELECT idEvaluacion, opEvaluaciones.Folio, CONCAT(Nombre,' ', Paterno,' ', Materno) AS Empleado, opEvaluaciones.Fecha, opEvaluaciones.Status FROM (((opEvaluaciones LEFT JOIN opCedulas ON opEvaluaciones.idCedula = opCedulas.idCedula) LEFT JOIN catEntidades ON opCedulas.idEntidad = catEntidades.idEntidad) LEFT JOIN opEmpleados ON opEvaluaciones.idEmpleado = opEmpleados.idEmpleado) WHERE opEvaluaciones.Status=0 AND " .$condicionales; //Se establece el modelo de consulta de datos.
            }  
    
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
 
    $column_names= ""; //Variable de control para los nombres de columnas a mostrarse en el grid.
    
    function constructor($dataset,$sufijo)
        {        
            /* Esta función se encarga de crear el contenido HTML de la pagina
             * tal como lo visualizara el usuario en el navegador.
             */

            $objGrid = new myGrid($dataset, 'Catalogo de Evaluaciones', $sufijo, 'idEvaluacion');
                
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