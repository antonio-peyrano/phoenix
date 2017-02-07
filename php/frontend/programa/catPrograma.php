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
    session_start();
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/grid.class.php"); //Se carga la referencia a la clase para manejo de rejillas de datos.
    
    global $username, $password, $servername, $dbname;
    
    $condicionales = ''; //Variable de control de condiciones de clausula select.
    $sufijo= "prg_"; //Variable de control de sufijo de identificadores.
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
        
    if(isset($_GET['nomprograma']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $condicionales= 'Programa LIKE \'%'.$_GET['nomprograma'].'%\'';
            }

    if(isset($_GET['nomidentidad']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            if($_GET['nomidentidad']!="-1")
                {
                    if($condicionales!='')
                    {
                        //Si la variable de condicionales no esta vacia.
                        $condicionales= $condicionales.' AND ';
                    }
            
                    $condicionales= $condicionales.'opProgramas.idEntidad LIKE \'%'.$_GET['nomidentidad'].'%\'';
                }
            }
                        
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
    if($_SESSION['idEmpleado']!=0)
        {
            /*
             * En el caso que el usuario que inicio sesion, sea un empleado registrado,
             * las consultas sobre programas solo podran desplegar aquellos programas
             * en los que el usuario aparezca como responsable o auxiliar.
             */
            if($_SESSION['nivel'] == 'Administrador')
                {
                    /*
                     * En caso que el usuario sea un administrador asociado a un empleado registrado
                     * en el sistema.
                     */
                    if($condicionales=="")
                    {
                        //Cargar la cadena de consulta por default.
                        $consulta= "SELECT idPrograma, Nomenclatura, Programa, Entidad, opProgramas.Status FROM (opProgramas INNER JOIN catEntidades ON catEntidades.idEntidad = opProgramas.idEntidad) WHERE opProgramas.Status=0 ORDER BY idPrograma"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
                    }
                    else
                    {
                        //En caso de contar con el criterio de filtrado.
                        $consulta= "SELECT idPrograma, Nomenclatura, Programa, Entidad, opProgramas.Status FROM (opProgramas INNER JOIN catEntidades ON catEntidades.idEntidad = opProgramas.idEntidad) WHERE opProgramas.Status=0 AND " .$condicionales. " ORDER BY idPrograma"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
                    }                    
                    }
            else
                {
                    /*
                     * En el caso de tratarse de un usuario sin perfil de administrador.
                     */
                    if($condicionales=="")
                        {
                            //Cargar la cadena de consulta por default.
                            $consulta= "SELECT idPrograma, Nomenclatura, Programa, Entidad, opProgramas.Status FROM (opProgramas INNER JOIN catEntidades ON catEntidades.idEntidad = opProgramas.idEntidad) WHERE opProgramas.Status=0 AND (idResponsable=".$_SESSION['idEmpleado']." OR idSubalterno=".$_SESSION['idEmpleado'].") ORDER BY idPrograma"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
                            }
                    else
                        {
                            //En caso de contar con el criterio de filtrado.
                            $consulta= "SELECT idPrograma, Nomenclatura, Programa, Entidad, opProgramas.Status FROM (opProgramas INNER JOIN catEntidades ON catEntidades.idEntidad = opProgramas.idEntidad) WHERE opProgramas.Status=0 AND " .$condicionales. " AND (idResponsable=".$_SESSION['idEmpleado']." OR idSubalterno=".$_SESSION['idEmpleado'].") ORDER BY idPrograma"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
                            }                    
                    }            
            }
    else
        {
            /*
             * Considerando que el usuario no sea un empleado registrado, se establece la carga
             * convencional de los programas. Tomando anticipado que se trata de un administrador.
             */
            if($_SESSION['nivel'] == 'Administrador')
                {            
                    if($condicionales=="")
                        {
                            //Cargar la cadena de consulta por default.
                            $consulta= "SELECT idPrograma, Nomenclatura, Programa, Entidad, opProgramas.Status FROM (opProgramas INNER JOIN catEntidades ON catEntidades.idEntidad = opProgramas.idEntidad) WHERE opProgramas.Status=0 ORDER BY idPrograma"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
                            }
                    else
                        {
                            //En caso de contar con el criterio de filtrado.
                            $consulta= "SELECT idPrograma, Nomenclatura, Programa, Entidad, opProgramas.Status FROM (opProgramas INNER JOIN catEntidades ON catEntidades.idEntidad = opProgramas.idEntidad) WHERE opProgramas.Status=0 AND " .$condicionales. " ORDER BY idPrograma"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
                            }
                    }
            else
                {
                    $consulta= "SELECT idPrograma, Nomenclatura, Programa, Entidad, opProgramas.Status FROM (opProgramas INNER JOIN catEntidades ON catEntidades.idEntidad = opProgramas.idEntidad) WHERE opProgramas.Status=-1 ORDER BY idPrograma"." limit ".$Inicio.",".$DisplayRow; //Se establece el modelo de consulta de datos.
                    }                                
            }              
    
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
        
    $column_names= ""; //Variable de control para los nombres de columnas a mostrarse en el grid.
    
    function constructor($dataset,$sufijo)
        {        
            /* Esta función se encarga de crear el contenido HTML de la pagina
             * tal como lo visualizara el usuario en el navegador.
             */

            $objGrid = new myGrid($dataset, 'Catalogo de Programas', $sufijo, 'idPrograma');
                 
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

