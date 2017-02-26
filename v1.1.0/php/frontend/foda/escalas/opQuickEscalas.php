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

    class quickEscalas
        {
            private $sufijo="fes";
            private $idCedula=0;
            
            function body()
                {

                    if(isset($_GET['idcedula']))
                        {
                            $this->idCedula = $_GET['idcedula'];    
                            }
                                                
                    $body = '   <body>
                                    <div id="datosEscala" style="display:none">
                                        <table class="queryTable">
                                            <tr><td class= "queryRowsnormTR">id Escala:</td><td><input type="text" id="idEscala"></td></tr>
                                            <tr><td class= "queryRowsnormTR">id Cedula:</td><td><input type="text" id="idCedula" value="'.$this->idCedula.'"></td></tr>
                                            <tr><td class= "queryRowsnormTR">Status:</td><td><input type="text" id="Status" value=0></td></tr>                    
                                        </table>                                    
                                    </div>
                                    <div id="quickEscalas">
                                        <table class="queryTable">
                                            <tr><th colspan= "3" align="center">ALTA RAPIDA DE ESCALA</th></tr>
                                            <tr><td class= "queryRowsnormTR">Escala:</td><td class= "queryRowsnormTR"><input type="text" id="Escala"></td><td rowspan= "2"><img id="'.$this->sufijo.'quickAdd" align= "left" src= "./img/grids/add.png" width= "25" height= "25" alt="Agregar"/></td></tr>
                                            <tr><td class= "queryRowsnormTR">Ponderacion:</td><td class= "queryRowsnormTR"><input type="text" id="Ponderacion"></td></tr> 
                                        </table>
                                    </div>
                                </body>';
                    return $body;
                    }
            }

    $objQuickEscalas = new quickEscalas();

    $html = '<html>'.$objQuickEscalas->body().'</html>';
    
    echo $html;
        
?>