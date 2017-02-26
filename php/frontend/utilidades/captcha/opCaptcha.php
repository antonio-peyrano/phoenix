<?php
    class opCaptcha
        {
            /*
             * Esta clase contiene los atributos y procedimientos para el paso
             * auxiliar del codigo HTML del captcha, haciendo uso de AJAX desde
             * el lado cliente.
             */
            public function captchaDraw()
                {
                    /*
                     * Esta funcion genera el codigo HTML que corresponde al
                     * captcha que se utiliza para evitar el spam.
                     */
                    $HTML = '<img id="captcha" align="middle" src="./php/frontend/utilidades/captcha/comp/captcha.php?'.rand().'" alt="CAPTCHA Image" width="150px" height="60px"/>';
            
                    return $HTML;
                    }            
            }

    $objOpCaptcha = new opCaptcha();
    echo $objOpCaptcha->captchaDraw();            
?>