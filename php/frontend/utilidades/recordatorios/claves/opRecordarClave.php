<?php
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/bl/utilidades/mail.class.php"); //Se carga la referencia del codificador de cadenas.
    
    class oprecordarclave
        {
            /*
             * Esta clase contiene los atributos y procedimientos para generar
             * la interfaz secundaria de recuperacion de claves.
             */
            private $Correo = '';
            private $Pregunta = '';
            private $Respuesta = '';
            
            public function __construct()
                {
                    /*
                     * Este constructor inicializa los atributos de la clase por medio
                     * de los datos obtenidos en la consulta por URL.
                     */
                    if(isset($_GET['correo'])){$this->Correo=$_GET['correo'];}
                    if(isset($_GET['pregunta'])){$this->Pregunta=$_GET['pregunta'];}
                    if(isset($_GET['respuesta'])){$this->Respuesta=$_GET['respuesta'];}
                    }
                    
            public function getCorreo()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de correo electronico.
                     */
                    return $this->Correo;
                    }

            public function getPregunta()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de pregunta.
                     */
                    return $this->Pregunta;
                    }
                    
            public function getRespuesta()
                {
                    /*
                     * Esta funcion retorna el valor obtenido de respuesta.
                     */
                    return $this->Respuesta;
                    }                                        
            }

    $objOpRecordarClave = new oprecordarclave();
    $objMail = new mail(); 

    $objMail->getParamRecordarClave($objOpRecordarClave->getCorreo(), $objOpRecordarClave->getPregunta(), $objOpRecordarClave->getRespuesta());
    $objMail->buscarUsuario();
    
    if((!empty($objMail->getUsuario()))&&(!empty($objMail->getClave())))
        {
            //VALIDACION DE USUARIO EN SISTEMA.
            echo $objMail->enviarCorreo();
            }
    else
        {
            //FALLA EN LA VALIDACION DEL USUARIO EN SISTEMA.
            echo $objMail->msgErrorValidacion($objOpRecordarClave->getCorreo());
            }
?>