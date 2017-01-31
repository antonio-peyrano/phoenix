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
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/foda/resfoda/resfoda.class.php");
    
    class tableFODA
        {
            private $idEntidad = NULL;
            private $idCedula = NULL;
            
            public function __construct()
                {
                    /*
                     * Defininicion de constructor que obtiene los valores de la URL y filtra
                     * a los atributos de control.
                     */
                    if(isset($_GET['identidad'])){$this->idEntidad = $_GET['identidad'];}
                    if(isset($_GET['idcedula'])){$this->idCedula = $_GET['idcedula'];}           
                    }
                    
            public function drawUI()
                {
                    /*
                     * Esta funcion genera el codigo HTML correspondiente a la interfaz
                     * de usuario.
                     */
                    $objResFODA = new resFODA();
                    $objResFODA->setValues($this->idEntidad, $this->idCedula);
                    $objResFODA->genMatrix();
                    
                    return $objResFODA->drawUI();
                    }                    
            }
            
    $objTableFODA = new tableFODA();
    echo $objTableFODA->drawUI();            
?>