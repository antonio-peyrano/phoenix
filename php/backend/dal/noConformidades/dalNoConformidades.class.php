<?php
/*
 * Citadel v1.0.0 Software para atencion ciudadana.
 * PHP v5
 * Autor: Prof. Jesus Antonio Peyrano Luna <antonio.peyrano@live.com.mx>
 * Nota aclaratoria: Este programa se distribuye bajo los terminos y disposiciones
 * definidos en la GPL v3.0, debidamente incluidos en el repositorio original.
 * Cualquier copia y/o redistribucion del presente, debe hacerse con una copia
 * adjunta de la licencia en todo momento.
 * Licencia: http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    
    class dalNoConformidades
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion de los datos
             * correspondientes a la entidad Cedulas.
             */
            private $Accion = '';
            private $cntrlVar = 0;
            private $idNoConformidad = NULL;
            private $idFicha = NULL;
            private $fEmision = '';
            private $Auditor = '';
            private $Tipo = '';
            private $Observaciones = '';
            private $Recomendaciones = '';
            private $Status = 0;
            private $cntView = 0;

            public function __construct()
                {
                    /*
                     * Este constructor obtiene y valida los datos ingresados por medio de la
                     * URL por parte del usuario.
                     */
                    $this->cntrlVar = 0;
                            
                    if(isset($_GET['id'])){$this->idNoConformidad = $_GET['id'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['idficha'])){$this->idFicha = $_GET['idficha'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['femision']))
                        {
                            date_default_timezone_set("America/Mexico_City");
                            $this->fEmision = date("Y/m/d",strtotime($_GET['femision']));
                            }
                    else{$this->cntrlVar+=1;} 
                    if(isset($_GET['auditor'])){$this->Auditor = $_GET['auditor'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['tipo'])){$this->Tipo = $_GET['tipo'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['observaciones'])){$this->Observaciones = $_GET['observaciones'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['recomendaciones'])){$this->Recomendaciones = $_GET['recomendaciones'];}else{$this->cntrlVar+=1;}                                       
                    if(isset($_GET['status'])){$this->Status = $_GET['status'];}else{$this->cntrlVar+=1;}
                    if(isset($_GET['view'])){$this->cntView = $_GET['view'];}
                    if(isset($_GET['accion'])){$this->Accion = $_GET['accion'];}
                    }

            public function almacenarParametros()
                {
                    /*
                     * Esta funcion almacena los parametros proporcionados via URL
                     * en la entidad de la base de datos.
                     */
                    if($this->cntrlVar == 0)
                        {
                            //SIN ERRORES EN EL PASO DE VALORES POR URL.
                            global $username, $password, $servername, $dbname;
                    
                            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.                            
                                                           
                            if($this->idNoConformidad != NULL)
                                {
                                    //EDICION DE REGISTRO                                    
                                    $consulta = 'UPDATE opNoConformidades SET Auditor=\''.$this->Auditor.'\', fEmision=\''.$this->fEmision.'\', Tipo=\''.$this->Tipo.'\', Observaciones=\''.$this->Observaciones.'\', Recomendaciones=\''.$this->Recomendaciones.'\', idFicha=\''.$this->idFicha.'\', Status=\''.$this->Status.'\' WHERE idNoConformidad='.$this->idNoConformidad; //Se establece el modelo de consulta de datos.
                                    $dsCedula = $objConexion->conectar($consulta); //Se ejecuta la consulta.                                        
                                    }
                            else
                                {
                                    //CREACION DE REGISTRO.                                    
                                    $consulta = 'INSERT INTO opNoConformidades (idFicha, fEmision, Auditor, Tipo, Observaciones, Recomendaciones) VALUES ('.'\''.$this->idFicha.'\',\''.$this->fEmision.'\',\''.$this->Auditor.'\',\''.$this->Tipo.'\',\''.$this->Observaciones.'\',\''.$this->Recomendaciones.'\')'; //Se establece el modelo de consulta de datos.
                                    $dsCedula = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                    
                                    }    
                            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/noConformidades/busNoConformidad.php");                                                    
                            }
                    else
                        {
                            //FALLO DE LA VALIDACION DE PARAMETROS POR URL.                            
                            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/notificaciones/ERROR405.php");
                            }
                    }

            public function eliminarParametros()
                {
                    /*
                     * Esta funcion ejecuta un borrado logico sobre el registro indicado
                     * por el usuario en su interaccion.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta= 'UPDATE opNoConformidades SET Status=1 where idNoConformidad='.$this->idNoConformidad; //Se establece el modelo de consulta de datos.
                    $dsCedula = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/noConformidades/busNoConformidad.php");
                    }
                    
            public function getAccion()
                {
                    /*
                     * Esta funcion retorna el valor obtenido por medio de la URL
                     * en respuesta a la accion del usuario.
                     */
                        return $this->Accion;
                    }                                       
            }
            
    $objdalNoConformidades = new dalNoConformidades();
            
    if($objdalNoConformidades->getAccion() == "CoER")
        {
            //CoER: CREACION o EDICION DE REGISTRO.
            $objdalNoConformidades->almacenarParametros();
            }
    else
        {
            if($objdalNoConformidades->getAccion() == "EdRS")
                {
                    //EdRS:ELIMINACION de REGISTRO EN SISTEMA.
                    $objdalNoConformidades->eliminarParametros();
                    }
            }            
?>