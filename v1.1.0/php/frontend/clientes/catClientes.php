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

    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/grid.class.php"); //Se carga la referencia a la clase para manejo de rejillas de datos.
    
    global $username, $password, $servername, $dbname;
    
    $condicionales = ''; //Variable de control de condiciones de clausula select.
    $sufijo= "cli_"; //Variable de control de sufijo de identificadores.
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
        
    if(isset($_GET['nomcliente']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $condicionales= 'Nombre LIKE \'%'.$_GET['nomcliente'].'%\'';
            }

    if(isset($_GET['patcliente']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($condicionales!='')
                {
                    //Si la variable de condicionales no esta vacia.
                    $condicionales= $condicionales.' AND ';
                    }
            
            $condicionales= $condicionales.'Paterno LIKE \'%'.$_GET['patcliente'].'%\'';
            }

    if(isset($_GET['matcliente']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($condicionales!='')
                {
                    //Si la variable de condicionales no esta vacia.
                    $condicionales= $condicionales.' AND ';
                    }
            
            $condicionales= $condicionales.'Materno LIKE \'%'.$_GET['matcliente'].'%\'';
            }
            
    if(isset($_GET['colcliente']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($condicionales!='')
                {
                    //Si la variable de condicionales no esta vacia.
                    $condicionales= $condicionales.' AND ';
                    }
                    
            $condicionales= $condicionales.'Colonia LIKE \'%'.$_GET['colcliente'].'%\'';
            }
       
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

    if($condicionales=="")
        {
            //Cargar la cadena de consulta por default.
            $consulta= "SELECT idCliente, CONCAT(Nombre, ' ',Paterno, ' ', Materno) AS Cliente, CONCAT(Calle, ' ', Nint, ' ', Next, ' ', Colonia) AS Direccion, opClientes.Status FROM opClientes INNER JOIN catColonias ON catColonias.idColonia = opClientes.idColonia WHERE opClientes.Status=0"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
            }  
    else 
        {
            //En caso de contar con el criterio de filtrado.
            $consulta= "SELECT idCliente, CONCAT(Nombre, ' ',Paterno, ' ', Materno) AS Cliente, CONCAT(Calle, ' ', Nint, ' ', Next, ' Col ', Colonia) AS Direccion, opClientes.Status FROM opClientes INNER JOIN catColonias ON catColonias.idColonia = opClientes.idColonia WHERE opClientes.Status=0 AND " .$condicionales." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
            }  
    
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
        
    $column_names= ""; //Variable de control para los nombres de columnas a mostrarse en el grid.
    
    function constructor($dataset,$sufijo)
        {        
            /* Esta funci�n se encarga de crear el contenido HTML de la pagina
             * tal como lo visualizara el usuario en el navegador.
             */
        
            $objGrid = new myGrid($dataset, 'Catalogo de Clientes', $sufijo, 'idCliente');
        
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

