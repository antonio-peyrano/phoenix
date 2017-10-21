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
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.
    
    class noConformidades
        {
            /*
             * Esta clase contiene los atributos y procedimientos vinculados con el comportamiento
             * y funcionalidades de la interfaz del modulo de cedulas.
             */
            
            //ATRIBUTOS APLICABLES AL MODULO catNoConformidades.php
            private $Condicionales = "";
            private $Sufijo = "noc_";
            private $idProceso = NULL;
            private $Tipo = "";
            private $Fecha = "";
            //private $Horizonte = "";
            private $Consulta = "SELECT idNoConformidad, fEmision AS Fecha, Proceso, Auditor, Tipo, opNoConformidades.Status FROM (opNoConformidades INNER JOIN opFichasProcesos ON opNoConformidades.idFicha = opFichasProcesos.idFicha) INNER JOIN catProcesos ON catProcesos.idProceso = opFichasProcesos.idProceso WHERE opNoConformidades.Status=0";
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO catNoConformidades.php
            
            //ATRIBUTOS APLICABLES AL MODULO opNoConformidades.php
            private $idNoConformidad = NULL;
            private $cntView = 0;
            //FIN DE DECLARACION DE ATRIBUTOS APLICABLES AL MODULO opNoConformidades.php
            
            public function __construct()
                {
                    //Declaracion de constructor (VACIO)
                    $this->setFechaRegistro(); //Se genera la fecha de registro.                    
                    }

            //PROCEDIMIENTOS APLICABLES AL MODULO catNoConformidades.php
            public function getTipo()
                {
                    /*
                     * Esta funcion retorna el valor del folio de cedula.
                     */
                    return $this->Tipo;
                    }

            public function getFecha()
                {
                    /*
                     * Esta funcion retorna el valor de fecha.
                     */
                    return $this->Fecha;
                    }

            public function getidProceso()
                {
                    /*
                     * Esta funcion retorna el valor de ID de la Entidad asociada.
                     */
                    return $this->idProceso;
                    }
                                        
            public function getConsulta()
                {
                    /*
                     * Esta funcion retorna el valor de la cadena de consulta.
                     */
                    return $this->Consulta;
                    }

            public function getSufijo()
                {
                    /*
                     * Esta funcion retorna el valor de sufijo para la interfaz.
                     */
                    return $this->Sufijo;
                    }
                                
            public function setCatParametros($Tipo, $Fecha, $idProceso)
                {
                    /*
                     * Esta funcion obtiene de la interaccion del usuario, los parametros
                     * para establecer los criterios de busqueda.
                     */
                    $this->Tipo = $Tipo;
                    $this->Fecha = $Fecha;
                    $this->idProceso = $idProceso;
                    }  

            public function evaluaCondicion()
                {
                    /*
                     * Esta funcion evalua si la condicion de busqueda cumple con las caracteristica
                     * solicitadas por el usuario.
                     */
                    $this->Condicionales = "";
                    
                    if(!empty($this->getTipo()))
                        {
                            $this->Condicionales = ' AND Tipo LIKE \'%'.$this->getTipo().'%\'';
                            }
                            
                    if(!empty($this->getFecha()))
                        {
                            date_default_timezone_set("America/Mexico_City");
                            $this->Condicionales .= ' AND fEmision = \''.date("Y/m/d",strtotime($this->getFecha())).'\'';
                            }

                    if(!empty($this->getidProceso()))
                        {
                            if($this->getIDProceso()!="-1")
                                {                            
                                    $this->Condicionales .= ' AND opFichasProcesos.idProceso = \''.$this->getidProceso().'\'';
                                    }
                            }
                                                
                    return $this->Condicionales;                            
                    }                    
            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO catNoConformidades.php
            
            //PROCEDIMIENTOS APLICABLES AL MODULO opNoConformidades.php.
            public function setFechaRegistro()
                {
                    /*
                     * Esta funcion calcula la fecha en la que se da de alta el registro
                     */
                    $now = time(); //Se obtiene la referencia del tiempo actual del servidor.
                    date_default_timezone_set("America/Mexico_City"); //Se establece el perfil del uso horario.
                    $this->Fecha = date("Y/m/d",$now); //Se obtiene la referencia compuesta de fecha y hora.
                    }
                                
            public function getRegistro($idRegistro)
                {
                    /*
                     * Esta funcion obtiene el dataset del registro de cedula apartir del ID proporcionado.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $Consulta= 'SELECT idNoConformidad, idFicha, fEmision, Auditor, Tipo, Observaciones, Recomendaciones, Status FROM opNoConformidades WHERE idNoConformidad='.$idRegistro; //Se establece el modelo de consulta de datos.
                    $dsCedula = $objConexion -> conectar($Consulta); //Se ejecuta la consulta.
                    
                    $RegCedula = @mysqli_fetch_array($dsCedula,MYSQLI_ASSOC);//Llamada a la funcion de carga de registro de usuario.
                    return $RegCedula;
                    }                    
                    
            public function controlBotones($Width, $Height, $cntView)
                {
                    /*
                     * Esta funcion controla los botones que deberan verse en la pantalla deacuerdo con la accion solicitada por el
                     * usuario en la ventana previa.
                     * Si es una edicion, los botones borrar y guardar deben verse.
                     * Si es una creacion solo el boton guardar debe visualizarse.
                     */                    
                    
                    $botonera = '';
                    $btnVolver_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/volver.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Volver" id="'.$this->Sufijo.'Volver" title= "Volver"/>';
                    $btnBorrar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/erase.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Borrar" id="'.$this->Sufijo.'Borrar" title= "Borrar"/>';
                    $btnGuardar_V =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$this->Sufijo.'Guardar" title= "Guardar"/>';
                    $btnGuardar_H =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$this->Sufijo.'Guardar" title= "Guardar" style="display:none;"/>';
                    $btnEditar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/edit.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Editar" id="'.$this->Sufijo.'Editar" title= "Editar"/>';

                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('phoenix');
                            session_start();
                            }
                            
                    if(($cntView == 0)||($cntView == 2)||($cntView == 9))
                        {
                            //CASO: CREACION O EDICION DE REGISTRO.
                            if($_SESSION['nivel'] == "Lector")
                                {
                                    /*
                                     * Si el usuario cuenta con un perfil de lector, se crea la referencia
                                     * para el control de solo visualizacion.
                                     */
                                    $botonera .= $btnVolver_V;
                                    }
                            else
                                {
                                    if($_SESSION['nivel'] == "Administrador")
                                        {
                                            $botonera .= $btnGuardar_V.$btnVolver_V;
                                            }
                                    }
                            }
                    else
                        {
                            if($cntView == 1)
                                {
                                    //CASO: VISUALIZACION CON OPCION PARA EDICION O BORRADO.
                                    if($_SESSION['nivel'] == "Lector")
                                        {
                                            /*
                                             * Si el usuario cuenta con un perfil de lector, se crea la referencia
                                             * para el control de solo visualizacion.
                                             */
                                            $botonera .= $btnVolver_V;
                                            }
                                    else
                                        {
                                            if($_SESSION['nivel'] == "Administrador")
                                                {
                                                    $botonera .= $btnEditar_V.$btnBorrar_V.$btnGuardar_H.$btnVolver_V;
                                                    }
                                            }
                                    }
                            }
                            
                    return $botonera;
                    }                                

            //FIN DE DECLARACION DE PROCEDIMIENTOS APLICABLES AL MODULO opNoConformidades.php            
            }
?>