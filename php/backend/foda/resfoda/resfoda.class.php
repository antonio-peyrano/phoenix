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

    header('Content-Type: text/html; charset=UTF-8'); //Forzar la codificación a UTF-8.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    class resFODA
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la gestion del
             * manejo de resultados de la evaluacion foda.
             */
            private $idEntidad = NULL;
            private $idCedula = NULL;
            
            private $totEvaluaciones = NULL;
            private $totFactores = NULL;
            
            private $Resultados = array();
            private $Fortalezas = array();
            private $Oportunidades = array();
            private $Debilidades = array();
            private $Amenazas = array();
                        
            public function __construct()
                {
                    //Definicion de constructor de clase (VACIO)
                    }

            public function setValues($idEntidad, $idCedula)
                {
                    /*
                     * Esta funcion asigna los valores obtenidos de la interaccion por URL
                     * a los atributos de la clase.
                     */
                    $this->idEntidad = $idEntidad;
                    $this->idCedula = $idCedula;
                    }

            public function getHorizonte()
                {
                    /*
                     * Esta funcion obtiene el valor de horizonte para ejecutar la
                     * evaluacion.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT Horizonte FROM opCedulas WHERE idCedula='.$this->idCedula;
                    $dsCedulas = $objConexion->conectar($consulta);
                    $regCedulas = @mysqli_fetch_array($dsCedulas,MYSQLI_ASSOC);
                    
                    if($regCedulas)
                        {                            
                            return $regCedulas['Horizonte'];
                            }
                    }
                                        
            public function getEvaluaciones()
                {
                    /*
                     * Esta funcion obtiene las evaluaciones en sistema que corresponden a la cedula
                     * evaluada.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idEvaluacion, idCedula FROM opEvaluaciones WHERE Status=3 AND idCedula='.$this->idCedula;
                    $dsEvaluaciones = $objConexion->conectar($consulta);
                    
                    /*
                     * Si la consulta encuentra datos de evaluaciones contestadas, procede
                     * a entregar el dataset correspondiente. Caso contrario, entrega un valor
                     * falso.
                     */
                    if($dsEvaluaciones)
                        {
                            $this->totEvaluaciones = mysqli_num_rows($dsEvaluaciones);
                            return $dsEvaluaciones;
                            }
                    }
                                        
            public function getFactores()
                {
                    /*
                     * Esta funcion obtiene los datos correspondientes a los factores asociados
                     * a la cedula.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.                   
                    $consulta = 'SELECT idFactor, Factor, Tipo FROM opFactores WHERE idCedula='.$this->idCedula;
                    $dsFactores = $objConexion->conectar($consulta);
                    
                    /*
                     * Si la consulta encuentra datos de evaluaciones contestadas, procede
                     * a entregar el dataset correspondiente. Caso contrario, entrega un valor
                     * falso.
                     */
                    if($dsFactores)
                        {
                            $this->totFactores = mysqli_num_rows($dsFactores);
                            return $dsFactores;
                            }                        
                    }

            public function getScore($idEvaluacion)
                {
                    /*
                     * Esta funcion obtiene los datos correspondientes a los resultados asociados
                     * a las evaluaciones.
                     */
                    global $username, $password, $servername, $dbname;
                    
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idEvaluacion, opResParFoda.idFactor, Tipo, Ponderacion FROM ((opResParFoda INNER JOIN opFactores ON opResParFoda.idFactor = opFactores.idFactor) INNER JOIN opEscalas ON opEscalas.idEscala = opResParFoda.idEscala) WHERE opResParFoda.idEvaluacion='.$idEvaluacion;
                    $dsResultados = $objConexion->conectar($consulta);
                    
                    /*
                     * Si la consulta encuentra datos de evaluaciones contestadas, procede
                     * a entregar el dataset correspondiente. Caso contrario, entrega un valor
                     * falso.
                     */
                    if($dsResultados)
                        {
                            return $dsResultados;
                            }
                    }

            public function genResultados()
                {
                    /*
                     * Esta funcion genera los resultados apartir de los datos obtenidos del score
                     * de las evaluaciones.
                     */
                    $dsEvaluaciones = $this->getEvaluaciones();
                    $dsFactores = $this->getFactores();                    
                    
                    $regEvaluaciones = @mysqli_fetch_array($dsEvaluaciones,MYSQLI_ASSOC);
                    
                    while($regEvaluaciones)
                        {
                            /*
                             * Se obtienen los datos correspondientes a la i-esima evaluacion.
                             */
                            $dsScore = $this->getScore($regEvaluaciones['idEvaluacion']);
                            $regScore = @mysqli_fetch_array($dsScore,MYSQLI_ASSOC);
                            
                            while($regScore)
                                {
                                    /*
                                     * Se obtienen los datos obtenidos para cada reactivo en las
                                     * evaluaciones en una sumatoria estratificada.
                                     */                                    
                                    $this->Resultados[] += $regScore['Ponderacion'];
                                    $regScore = @mysqli_fetch_array($dsScore,MYSQLI_ASSOC);
                                    }
                                    
                            $regEvaluaciones = @mysqli_fetch_array($dsEvaluaciones,MYSQLI_ASSOC);
                            }
                            
                    $idxFactor = 0;
                    
                    while($idxFactor<count($this->Resultados))                       
                        {   
                            /*
                             * Se corrigen los valores ajustandolos a la media aritmetica
                             * del total de evaluaciones.
                             */
                            $this->Resultados[$idxFactor] = $this->Resultados[$idxFactor]/$this->totEvaluaciones;
                            $idxFactor += 1;
                            }                                         
                    }
                                        
            public function genMatrix()
                {
                    /*
                     * Esta funcion genera los vectores que alimentaran la matriz FODA que
                     * se desplegara al usuario.
                     */                                        
                    $this->genResultados();
                    $dsFactores = $this->getFactores();
                    $Horizonte = $this->getHorizonte();

                    if($dsFactores)
                        {
                            //CASO: La consulta arrojo resultados validos.
                            $RegFactores = @mysqli_fetch_array($dsFactores,MYSQLI_ASSOC);
                            
                            $this->pondFortaleza = 0.0;
                            $this->pondOportunidad = 0.0;
                            $this->pondDebilidad = 0.0;
                            $this->pondAmenaza = 0.0;
                            $idxFactores = 0;
                                                       
                            while($RegFactores)
                                {
                                    //Mientras existan resultados disponibles para computar.
                                    if($RegFactores['Tipo'] == 'Interno')
                                        {
                                            if(count($this->Resultados)>0)
                                                {
                                                    if($this->Resultados[$idxFactores]>$Horizonte)
                                                        {
                                                            //CASO 1: EL FACTOR INTERNO SUPERO EL HORIZONTE.
                                                            $this->Fortalezas[] = $RegFactores['Factor'];
                                                            }
                                                    else
                                                        {
                                                            //CASO 2: EL FACTOR INTERNO NO SUPERO EL HORIZONTE.
                                                            $this->Debilidades[] = $RegFactores['Factor'];
                                                            }
                                                    }
                                            }

                                    if($RegFactores['Tipo'] == 'Externo')
                                        {
                                            if(count($this->Resultados)>0)
                                                {
                                                    if($this->Resultados[$idxFactores]>$Horizonte)
                                                        {
                                                            //CASO 1: EL FACTOR INTERNO SUPERO EL HORIZONTE.
                                                            $this->Oportunidades[] = $RegFactores['Factor'];
                                                            }
                                                    else
                                                        {
                                                            //CASO 2: EL FACTOR INTERNO NO SUPERO EL HORIZONTE.
                                                            $this->Amenazas[] = $RegFactores['Factor'];
                                                            }
                                                    }
                                            }                                            

                                    $idxFactores += 1;                                                    
                                    $RegFactores = @mysqli_fetch_array($dsFactores,MYSQLI_ASSOC);
                                    }
                            }
                    }                                        

            public function drawUI()
                {
                    /*
                     * Esta funcion genera el codigo HTML correspondiente a la
                     * matriz FODA que visualizara el usuario.
                     */
                    $HTML = '<table class="fodaTable">';
                    
                    $indexF = count($this->Fortalezas);
                    $indexO = count($this->Oportunidades);
                    $indexD = count($this->Debilidades);
                    $indexA = count($this->Amenazas);
                    
                    $HTML .= '<tr><td class="tdlblFortaleza">F</td><td class="dgRowsaltTR">';
                    for($item = 0; $item < $indexF; $item++)
                        {
                            $HTML .= $this->Fortalezas[$item].'<br>';
                            }
                            
                    $HTML .= '</td></tr>';
                    $HTML .= '<tr><td class="tdlblOportunidad">O</td><td class="dgRowsaltTR">';
                    for($item = 0; $item < $indexO; $item++)
                        {
                            $HTML .= $this->Oportunidades[$item].'<br>';
                            }
                            
                    $HTML .= '</td></tr>';
                    $HTML .= '<tr><td class="tdlblDebilidad">D</td><td class="dgRowsaltTR">';
                    for($item = 0; $item < $indexD; $item++)
                        {
                            $HTML .= $this->Debilidades[$item].'<br>';
                            }

                    $HTML .= '</td></tr>';
                    $HTML .= '<tr><td class="tdlblAmenaza">A</td><td class="dgRowsaltTR">';
                    for($item = 0; $item < $indexA; $item++)
                        {
                            $HTML .= $this->Amenazas[$item].'<br>';
                            }

                    $HTML .= '</td></tr>';
                    $HTML .= '</table>';

                    return $HTML;
                    }                    
            }
?>