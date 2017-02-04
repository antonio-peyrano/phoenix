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
    
    global $username, $password, $servername, $dbname;
    
    $sufijo = "evi_"; //Variable de control de sufijo de identificadores.
    $condicionales = ''; //Variable de control de condiciones de clausula select.
                
    if(isset($_GET['idejecucion']))
        {
            /*
             * Si el archivo ha sido llamado como una referencia con parametros.
             */
            $condicionales= $condicionales.'idEjecucion ='.$_GET['idejecucion'];
            }
    else
        {
            /*
             * Si no existe parametro de actividad, se presupone que se ha invocado en la creaci�n de registro.
             */
            $condicionales= $condicionales.'idEjecucion =-1'; 
            }
       
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.

    if($condicionales == "")
        {
            //Cargar la cadena de consulta por default.
            $consulta= "SELECT idEvidencia, RutaURL, Status FROM opEvidencias WHERE Status=0"; //Se establece el modelo de consulta de datos.
            }  
    else 
        {
            //En caso de contar con el criterio de filtrado.
            $consulta= "SELECT idEvidencia, RutaURL, Status FROM opEvidencias WHERE Status=0 AND " .$condicionales; //Se establece el modelo de consulta de datos.
            }  

    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
        
    $column_names= ""; //Variable de control para los nombres de columnas a mostrarse en el grid.    
        
function dgDatos($dataset, $sufijo)
    {
        /*
         * Esta funci�n crea el codigo HTML correspondiente a la parte de informaci�n
         * obtenida en la consulta de datos en modo de una tabla.
         */
        //global $sufijo; //Acceso a la variable de sufijo.
        
        $count_row=1;//Se inicializa el contador de filas.
        $field = @mysql_fetch_field($dataset);
        $record = @mysql_fetch_array($dataset, MYSQL_ASSOC);
        
        while ($record) 
            {
                //Mientras existan elementos en la colecci�n de filas.
                
                /*Nota: El argumento MYSQL_ASSOC es necesario para visualizar unicamente un conjunto de elementos;
                /*de lo contrario, al ser omitido retornara valores duplicados.                
                 */
                
                if(($count_row % 2)==0)
                    {
                        //En caso de ser una fila de datos non, se propone el color base.
                        $rows='<tr class="dgRowsnormTR">';
                        }
                else
                    {
                        //En caso de ser una fila par, se propone el color alterno.
                        $rows='<tr class="dgRowsaltTR">';
                        }
                        
                $fldcount = 0;
                $display = 1;
                
                foreach ($record as $value)
                    {
                        //Para cada elemento en el arreglo, se dispone de una casilla en la
                        //tabla.
                        if(mysql_field_name($dataset, $fldcount) == "RutaURL")
                            {
                                $rows = $rows.'<td><a href="'.$value.'" target="_blank">'.$value.'</a></td>';
                                }
                        else
                            {
                                if($display == 1)
                                    {
                                        $rows = $rows.'<td style= "display:none">'.$value.'</td>';
                                        $display = 0;
                                        }
                                else
                                    {
                                        $rows = $rows."<td>".$value."</td>";
                                        }
                                }
                                
                        $fldcount+=1;
                        }
                        
                $rows = $rows.'<td id="reg_'.$record['idEvidencia'].'" width= "90"><a class="borrar" id="'.$sufijo.'delete'.$record['idEvidencia'].'" href="#"><img id="'.$sufijo.'delete_'.$record['idEvidencia'].'" align= "middle" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar"/></a></td></tr>';  
                echo $rows; //Se envia el codigo HTML generado.
                $count_row = $count_row + 1; //Se incrementa el contador de filas.
                $record = @mysql_fetch_array($dataset, MYSQL_ASSOC);
                }
                
        echo '<tr class= "dgTotRowsTR"><td alignt= "left" colspan= 7"></td></tr>';
        echo '<tr class= "dgPagRow"><td align= "left" colspan= "7">Visualizando ' .($count_row-1). ' registros</td></tr>';
        echo '</table>';
        echo '</div>';
        }

function dgCabeceras($dataset, $titulo, $sufijo)
    {
        /*
         * Esta funci�n crea el codigo HTML correspondiente a la parte de la cabecera de datos
         * obtenida en la consulta de datos en modo de una tabla.
         */
        
        //global $sufijo; //Acceso a la variable de sufijo.
        $columns='<th style="display:none">';
        
        $field = @mysql_fetch_field($dataset);
        $columns= $columns .$field->name."</th>";
        $field = @mysql_fetch_field($dataset);
        
        while ($field) 
            {                
                /*Para cada elemento de la colecci�n de nombres de campos,
                 *se crea la etiqueta correspondiente <TH> 
                 */
                $columns = $columns. "<th>".$field->name."</th>";
                $field = @mysql_fetch_field($dataset);
                }
                                
        echo '<div id= "dgDiv" class= "dgMainDiv">';        
        echo '      <table class="dgTable">'; //Se declara el codigo HTML para la tabla.
        echo '          <tr align= "center"><td colspan= 7 class= "dgHeader">'.$titulo.'</td></tr>';
        echo '          <tr class="dgTitles">'.$columns.'<th>Acciones</th></tr>'; //Se envia el codigo HTML de la fila de cabecera.
        }

function construcCatEvidencias($dataset, $sufijo)
    {        
        /* Esta funcion se encarga de crear el contenido HTML de la pagina
         * tal como lo visualizara el usuario en el navegador.
         */        
        echo'
                <html>
                    <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                    <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                    <head>          
                    </head>
                    <body>';
        
        dgCabeceras($dataset, 'Evidencias', $sufijo);//Funcion para crear la cabecera de columnas de datos.
        dgDatos($dataset, $sufijo); //Funcion para crear las filas de datos de la consulta.        
        echo'
                    </body>
                </html>           
            ';
        }

        construcCatEvidencias($dataset, $sufijo); //Llamada a la funcion principal de la pagina.
?>