<?php

    class captcha
        {
            /*
             * Esta clase contiene los atributos y procedimientos para la generacion
             * de codigos de validacion captcha para controlar el spam.
             */
            private $img = NULL;
            private $captcha = NULL;
            private $width = '80';
            private $height = '25';            
            private $bgColor = NULL;
            private $imgTextColor = NULL;
            private $fontSize = 12;
            
            public function __construct()
                {
                    //Declaracion de funcion constructor (VACIO)
                    
                    $this->captcha = dechex(rand(1000,9999));
                    $this->captcha = strtoupper($this->captcha);
                    
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('ecole');
                            session_start();
                            $_SESSION['captcha'] = $this->captcha;
                            } 
                    }

            public function getCaptchaCode()
                {
                    /*
                     * Esta funcion retorna el valor del captcha almacenado
                     * en la sesion del usuario.
                     */
                    if(!isset($_SESSION))
                        {
                            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
                            session_name('ecole');
                            session_start();
                            }
                                           
                    return $_SESSION['captcha'];
                    }
                                        
            public function genCaptcha()
                {
                    /*
                     * Esta funcion crea el codigo captcha apartir de los parametros
                     * definidos en la interaccion del sistema despues de invocarse.
                     */
                    header("Content-type: image/png");
                    $this->img = imagecreate($this->width, $this->height);
                    $this->fondo = imagecolorallocate($this->img, 0, 0, 0);
                    $this->imgTextColor = imagecolorallocate($this->img, 255, 255, 255);
                    imagestring($this->img, $this->fontSize, 20, 5, $this->captcha, $this->imgTextColor);
                    imagepng($this->img);                                   
                    }                    
            }            
?>