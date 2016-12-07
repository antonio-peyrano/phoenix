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
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/grid.class.php"); //Se carga la referencia a la clase para manejo de rejillas de datos.
    
    global $username, $password, $servername, $dbname;
    
    $sufijo = "mcs_"; //Variable de control de sufijo de identificadores.
    $condicionales = ''; //Variable de control de condiciones de clausula select.    
    
    if(isset($_GET['idejecgas']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if(!empty($_GET['idejecgas']))
                $condicionales= $condicionales.'idEjecGas ='.$_GET['idejecgas'];
            else
                $condicionales= $condicionales.'idEjecGas =-1';
            }
    else
        {
            /*
             * Si no existe parametro de actividad, se presupone que se ha invocado en la creaci�n de registro.
             */
            $condicionales= $condicionales.'idEjecGas =-1'; 
            }

    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

    if($condicionales == "")
        {
            //Cargar la cadena de consulta por default.
            $consulta= "SELECT idMovGas, Cantidad, Mes, Status FROM opMovGas WHERE Status=0"; //Se establece el modelo de consulta de datos.
            }  
    else 
        {
            //En caso de contar con el criterio de filtrado.
            $consulta= "SELECT idMovGas, Cantidad, Mes, Status FROM opMovGas WHERE Status=0 AND " .$condicionales; //Se establece el modelo de consulta de datos.
            }  
    
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
        
    $column_names= ""; //Variable de control para los nombres de columnas a mostrarse en el grid.    

    function obtenerMes($Mes)
        {
            /*
             * Esta funci�n obtiene el nombre del mes apartir de su cardinal numerico.
             */
            if($Mes == "1")
                {
                    return "Enero";
                    }
            if($Mes == "2")
                {
                    return "Febrero";
                    }
            if($Mes == "3")
                {
                    return "Marzo";
                    }
            if($Mes == "4")
                {
                    return "Abril";
                    }
            if($Mes == "5")
                {
                    return "Mayo";
                    }
            if($Mes == "6")
                {
                    return "Junio";
                    }
            if($Mes == "7")
                {
                    return "Julio";
                    }
            if($Mes == "8")
                {
                    return "Agosto";
                    }
            if($Mes == "9")
                {
                    return "Septiembre";
                    }
            if($Mes == "10")
                {
                    return "Octubre";
                    }
            if($Mes == "11")
                {
                    return "Noviembre";
                    }
            if($Mes == "12")
                {
                    return "Diciembre";
                    }
            }
        
    function construcCatEjecucion($dataset, $sufijo)
        {        
            /* Esta función se encarga de crear el contenido HTML de la pagina
             * tal como lo visualizara el usuario en el navegador.
             */

            $objGrid = new myGrid($dataset, 'Consumo de Gasolina', $sufijo, 'idMovGas');
                        
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

    construcCatEjecucion($dataset, $sufijo); //Llamada a la funcion principal de la pagina.
?>