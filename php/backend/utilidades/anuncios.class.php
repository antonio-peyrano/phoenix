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

    class anuncios
        {
            function __construct()
                {
                    
                    }
                    
            function drawUI()
                {
                    echo    '   <html>
                                <head>
                                    <meta charset="utf-8">
                                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                    <script type="text/javascript" src="../../../js/jquery/jquery-1.9.1.min.js"></script>
                                    <script type="text/javascript" src="../../../js/jquery/jssor.slider.mini.js"></script>
                                    <link rel="stylesheet" href="../../../css/jssor.css"></style>
                                    <link rel="stylesheet" href="../../../css/dgstyle.css"></style>                                                                                                                                         
                                </head>
                                <body>
                                <br>
                                <br>                                
                                ';                                
                                ?>
                                <script languaje="javascript">
                                    jQuery(document).ready(function ($) 
                                        {            
                                            var jssor_1_options = 
                                                {
                                                    $AutoPlay: true,
                                                    $AutoPlaySteps: 1,
                                                    $SlideDuration: 160,
                                                    $SlideWidth: 808,
                                                    $SlideSpacing: 3,
                                                    $Cols: 1,
                                                    $ArrowNavigatorOptions: {
                                                    $Class: $JssorArrowNavigator$,
                                                    $Steps: 1
                                                    },
                                            $BulletNavigatorOptions: 
                                                {
                                                    $Class: $JssorBulletNavigator$,
                                                    $SpacingX: 1,
                                                    $SpacingY: 1
                                                    }
                                            };
            
                                    var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
            
                                    //responsive code begin
                                    //you can remove responsive code if you don't want the slider scales while window resizing
                                    function ScaleSlider()
                                        {
                                            var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                                            if (refSize)
                                                {
                                                    refSize = Math.min(refSize, 809);
                                                    jssor_1_slider.$ScaleWidth(refSize);
                                                    }
                                            else
                                                {
                                                    window.setTimeout(ScaleSlider, 30);
                                                    }
                                            }
                                    ScaleSlider();
                                    $(window).bind("load", ScaleSlider);
                                    $(window).bind("resize", ScaleSlider);
                                    $(window).bind("orientationchange", ScaleSlider);
                                    //responsive code end
                                    });
                                </script>            
                                <?php                                
                    echo    '   <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 809px; height: 550px; overflow: hidden; visibility: hidden;">
                                    <!-- Loading Screen -->
                                    <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                                        <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                                        <div style="position:absolute;display:block;background:url("../../img/slide/loading.gif") no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
                                    </div>
                                    <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 809px; height: 550px; overflow: hidden;">
                                        <div style="display: none;">
                                            <img data-u="image" src="../../../img/slide/005.png" />
                                        </div>
                                        <div style="display: none;">
                                            <img data-u="image" src="../../../img/slide/006.png" />
                                        </div>
                                        <div style="display: none;">
                                                    <img data-u="image" src="../../../img/slide/007.png" />
                                        </div>
                                        <div style="display: none;">
                                                    <img data-u="image" src="../../../img/slide/008.png" />
                                        </div>
                                    </div>
                                    <!-- Bullet Navigator -->
                                    <div data-u="navigator" class="jssorb03" style="bottom:10px;right:10px;">
                                        <!-- bullet navigator item prototype -->
                                        <div data-u="prototype" style="width:21px;height:21px;">
                                            <div data-u="numbertemplate"></div>
                                        </div>
                                    </div>
                                    <!-- Arrow Navigator -->
                                    <span data-u="arrowleft" class="jssora03l" style="top:0px;left:8px;width:55px;height:55px;" data-autocenter="2"></span>
                                    <span data-u="arrowright" class="jssora03r" style="top:0px;right:8px;width:55px;height:55px;" data-autocenter="2"></span>        
                                </div>
                                <br>
                                <table class="dgTable">
                                    <tr><th class= "dgTitles"><center><p><b>¿QUE ES PHOENIX?</b></p></center></th></tr>
                                    <tr><td class="dgRowsnormTR">
                                        <b>Phoenix es un sistema de gestion integral para la planeacion operativa de la organizacion, el cual le permite tener de una manera rapida y sencilla, la informacion referente a sus planes de trabajo. Facilitando el seguimiento del logro de las metas esperadas mediante la colaboracion sistemica de cada ente organizacional.                
                                    </td></tr>
                                </table>                                
                                </body>
                                </html>
                            ';
                    }
            }
?>