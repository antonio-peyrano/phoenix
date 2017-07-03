<?php
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    
    class opInputPregunta
        {
            private $Correo = '';
            private $Pregunta = '';
            
            public function __construct()
                {
                    /*
                     * Esta funcion constructor obtiene los datos proporcinados por la consulta URL
                     * y los transfiere a los atributos de la clase.
                     */
                    if(isset($_GET['correo'])){$this->Correo=$_GET['correo'];}
                    }
                    
            public function getPregunta()
                {
                    /*
                     * Esta funcion retorna el valor obtenido del atributo pregunta.
                     */
                    return $this->Pregunta;
                    }
                                        
            public function buscarPregunta()
                {
                    /*
                     * Esta funcion ejecuta la busqueda de la pregunta de seguridad asociada
                     * al correo proporcionado por el usuario.
                     */
                    global $username, $password, $servername, $dbname;                    
                    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT *FROM catUsuarios WHERE Correo=\''.$this->Correo.'\''; //Se establece el modelo de consulta de datos.                    
                    $dsUsuarios = $objConexion->conectar($consulta); //Se ejecuta la consulta.
                    
                    $RegUsuarios = @mysqli_fetch_array($dsUsuarios, MYSQLI_ASSOC);
                    
                    if($RegUsuarios)
                        {
                            //Solo si existe un registro con el correo solicitado.
                            $this->Pregunta = $RegUsuarios['Pregunta'];
                            }                    
                    }                    
            }


    $objOpInputPregunta = new opInputPregunta();    
    $objOpInputPregunta->buscarPregunta();
    echo '<input id="Pregunta" type="text" value="'.$objOpInputPregunta->getPregunta().'">';
?>