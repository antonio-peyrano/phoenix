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
 * Este archivo contiene el constructor para el combobox de procesos a visualizar.
 */
    header('Content-Type: text/html; charset=UTF-8'); //Forzar la codificación a UTF-8.

    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    class cbCedulas
        {
            /*
             * Esta clase contiene los atributos y procedimientos para generar
             * el combobox de cedulas de evaluacion.
             */
            private $idEntidad = NULL;
            private $Sufijo = 'rsf_';
            
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene los parametros por medio de la URL
                     * y los filtra a los atributos de control.
                     */
                    if(isset($_GET['identidad'])){$this->idEntidad=$_GET['identidad'];}
                    }

            public function getCedulas()
                {
                    /*
                     * Esta funcion genera el codigo HTML correspondiente al combobox
                     * de cedulas de evaluacion.
                     */
                    
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta= 'SELECT idCedula, Folio FROM opCedulas WHERE Status=0 AND idEntidad='.$this->idEntidad; //Se establece el modelo de consulta de datos.
                    $dsCedulas = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    
                    $HTML = '   <select id="'.$this->Sufijo.'idCedula" name="'.$this->Sufijo.'idCedula" value="-1">
                                    <option value="-1">Seleccione</option>';
                    
                    $regCedulas = @mysql_fetch_array($dsCedulas, MYSQL_ASSOC);
                    
                    while($regCedulas)
                        {
                            /*
                             * Mientras que existan registros de entidades, se crearan las entradas de
                             * opciones en el control combobox idEntidad.
                             */
                            $HTML .='<option value="'.$regCedulas['idCedula'].'">'.$regCedulas['Folio'].'</option>' ;
                            $regCedulas = @mysql_fetch_array($dsCedulas, MYSQL_ASSOC);
                            }
                    
                    $HTML .= '</select>';
                    
                    return $HTML;
                    }                                

            public function getIDEntidad()
                {
                    //Esta funcion retorna el valor del atributo idEntidad.
                    return $this->idEntidad;
                    }                    
            }
            
    $objCbCedulas = new cbCedulas();
    
    echo $objCbCedulas->getCedulas(); 
?>