<?php
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/bl/utilidades/captcha.class.php");
    
    $objCaptcha = new captcha();    
    $objCaptcha->genCaptcha();
?>