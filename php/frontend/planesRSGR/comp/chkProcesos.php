<?php
/*
 * Micrositio-Phoenix v1.0 Software para gestion de la planeacion operativa.
 * PHP v5
 * Autor: Prof. Jesus Antonio Peyrano Luna <antonio.peyrano@live.com.mx>
 * Nota aclaratoria: Este programa se distribuye bajo los terminos y disposiciones
 * definidos en la GPL v3.0, debidamente incluidos en el repositorio original.
 * Cualquier copia y/o redistribucion del presente, debe hacerse con una copia
 * adjunta de la licencia en todo momento.
 * Licencia: http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

    /*
     * Este archivo contiene el constructor para el combobox de objetivos operativos a visualizar.
     */

    header('Content-Type: text/html; charset=UTF-8'); //Forzar la codificacion a UTF-8.

    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.

    if(isset($_GET['id']))
        {
            //Se recibe el parametro enviado por la consulta URL.
            $idProceso = $_GET['id'];
            }
    else
        {
            //En caso de no recibir parametro por la consulta URL.
            $idProceso = '-1';
            }

    if(isset($_GET['parametro']))
        {
            //Se recibe el parametro enviado por la consulta URL.
            $idRegistro = $_GET['parametro'];            
            }
    else
        {
            //En caso de no recibir parametro por la consulta URL.
            $idRegistro = '-1';            
            }
                        
    function cargarProcesos()
        {
            /*
             * Esta funcion genera la carga de la tupla de datos
             * correspondiente a los procesos en sistema.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT * FROM catProcesos WHERE Status=0'; //Se establece el modelo de la consulta de datos.
            $dataset = $objConexion -> conectar($consulta);

            return $dataset;
            }

    function constructorchkProcesos($idRegistro, $dataSet)
        {
            /*
             * Esta funcion establece los parametros de carga del conjunto de checkbox
             * sobre procesos asociados al plan RSGR
             */
             global $habcampos, $Registro;
            
            if($idRegistro == -1)
                {
                    /*
                     * Si la operacion solicitada es para la creacion de un registro,
                     * se carga el listado sin marcar.
                     */ 
                                        
                    //Se construye un vector con los indicadores asociados al proceso.                    
                    $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la funcion de carga de registros de procesos.
                    $template = '';
                                                   
                    while($regProcesos)
                        {
                            //Mientras existan elementos en al tupla de datos.
                            $template.= '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" value='.$regProcesos['idProceso'].'>'.$regProcesos['Proceso'];
                            $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Llamada a la funcion de carga de registros de procesos.
                            }
                    }
            else
                {
                    /*
                     * Si la operacion solicitada es para editar el registro,
                     * se carga el listado con los elementos previamente marcados.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                        
                    $subconsulta = 'SELECT *FROM relProRSGR WHERE idPlanRSGR='.$idRegistro.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                    $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                    $vector = "";
                    
                    if($RegNiveles)
                        {
                            /*
                             * Si la lectura del registro no apunta a vacio, se agrega
                             * el id al vector.
                             */
                            $vector.=$RegNiveles['idProceso'];
                            }
                    
                    $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                    
                    while ($RegNiveles)
                        {
                            /*
                             * Se hace un recorrido sobre el dataset para crear un vector que contenga
                             * los id de las entidades seleccionadas por el usuario previamente.
                             */
                            $vector.=','.$RegNiveles['idProceso'];
                            $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                            }
                    
                    $tmparray=explode(',',$vector); //El vector resultante se convierte en un arreglo.
                    
                    //Se construye un vector con los indicadores asociados al proceso.                    
                    $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Se ejecuta la lectura sobre la tupla de datos.
                    $template = '';
                    
                    while ($regProcesos)
                        {
                            /*
                             * Mientras no se llegue al final de la coleccion, se procede a la lectura
                             * y generacion del listado.
                             */
                            if(in_array($regProcesos['idProceso'], $tmparray,true))
                                {
                                    /*
                                     * En caso de tratarse de una opcion previamente seleccionada por el usuario.
                                     */
                                    $template.= '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" value='.$regProcesos['idProceso'].' checked>'.$regProcesos['Proceso'];
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario se agrega una entrada de formato convencional.
                                     */
                                    $template.= '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" value='.$regProcesos['idProceso'].'>'.$regProcesos['Proceso'];
                                    }
                    
                            $regProcesos = @mysqli_fetch_array($dataSet,MYSQLI_ASSOC); //Se ejecuta la lectura sobre la tupla de datos.
                            }                    
                    }                                
                return $template;
            }
                        
    echo constructorchkIndicadores($idRegistro, cargarIndicadores($idProceso));
?>