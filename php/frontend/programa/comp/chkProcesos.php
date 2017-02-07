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

    /*
     * Este archivo contiene el constructor para el combobox de objetivos operativos a visualizar.
     */

    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.

    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.

    $idEntidad = $_GET['identidad'];
    $idPrograma = $_GET['idprograma'];
    
    global $habcampos, $RegNiveles, $Registro;
        
    function cargarProcesos($idRegistro, $idEntidad)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */
            global $username, $password, $servername, $dbname;

            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM (catProcesos INNER JOIN relEntPro ON relEntPro.idProceso = catProcesos.idProceso) INNER JOIN catEntidades ON catEntidades.idEntidad = relEntPro.idEntidad WHERE relEntPro.Status=0 AND relEntPro.idEntidad='.$idEntidad;
            //$consulta= 'SELECT *FROM (relEntPro INNER JOIN catProcesos ON catProcesos.idProceso = relEntPro.idProceso) INNER JOIN catEntidades ON catEntidades.idEntidad = relEntPro.idEntidad WHERE relEntPro.Status=0 AND relEntPro.idEntidad='.$idEntidad; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.

            if($idRegistro == -1)
                {
                    /*
                     * Si la operación solicitada es para la creación de un registro,
                     * se carga el listado sin marcar.
                     */
                    $RegNiveles = @mysql_fetch_array($dataset, MYSQL_ASSOC);

                    while ($RegNiveles)
                        {
                            echo '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" value='.$RegNiveles['idProceso'].'>'.$RegNiveles['Proceso'];
                            $RegNiveles = @mysql_fetch_array($dataset, MYSQL_ASSOC);
                            }
                    }
            else
                {
                    /*
                     * Si la operación solicitada es para editar el registro,
                     * se carga el listado con los elementos previamente marcados.
                     */
                    $subconsulta = 'SELECT *FROM relProgPro WHERE idPrograma='.$idRegistro.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                    $vector = "";
                    $RegNiveles = @mysql_fetch_array($subdataset, MYSQL_ASSOC);

                    if($RegNiveles)
                        {
                            /*
                             * Si la lectura del registro no apunta a vacio, se agrega
                             * el id al vector.
                             */
                            $vector.=$RegNiveles['idProceso'];
                            }

                    $RegNiveles = @mysql_fetch_array($subdataset, MYSQL_ASSOC);

                    while ($RegNiveles)
                        {
                            /*
                             * Se hace un recorrido sobre el dataset para crear un vector que contenga
                             * los id de las entidades seleccionadas por el usuario previamente.
                             */
                            $vector.=','.$RegNiveles['idProceso'];
                            $RegNiveles = @mysql_fetch_array($subdataset, MYSQL_ASSOC);
                            }

                    $tmparray=explode(',',$vector); //El vector resultante se convierte en un arreglo.

                    $RegNiveles = @mysql_fetch_array($dataset, MYSQL_ASSOC);

                    while ($RegNiveles)
                        {
                            /*
                             * Mientras no se llegue al final de la colección, se procede a la lectura
                             * y generación del listado.
                             */
                            if(in_array($RegNiveles['idProceso'], $tmparray,true))
                                {
                                    /*
                                     * En caso de tratarse de una opción previamente seleccionada por el usuario.
                                     */
                                    echo '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" value='.$RegNiveles['idProceso'].' checked>'.$RegNiveles['Proceso'];
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario se agrega una entrada de formato convencional.
                                     */
                                    echo '<input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" value='.$RegNiveles['idProceso'].'>'.$RegNiveles['Proceso'];
                                    }

                            $RegNiveles = @mysql_fetch_array($dataset, MYSQL_ASSOC);
                            }
                    }

            echo'</div></td></tr>';
            }
            
    cargarProcesos($idPrograma, $idEntidad);
?>