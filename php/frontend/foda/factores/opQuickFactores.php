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

    class quickFactores
        {
            private $sufijo="ffa";
            private $idCedula=0;
            
            function body()
                {

                    if(isset($_GET['idcedula']))
                        {
                            $this->idCedula = $_GET['idcedula'];    
                            }
                                                
                    $body = '   <body>
                                    <div id="datosFactor" style="display:none">
                                        <table class="queryTable">
                                            <tr><td class= "queryRowsnormTR">id Factor:</td><td><input type="text" id="idFactor"></td></tr>
                                            <tr><td class= "queryRowsnormTR">id Cedula:</td><td><input type="text" id="idCedula" value="'.$this->idCedula.'"></td></tr>
                                            <tr><td class= "queryRowsnormTR">Status:</td><td><input type="text" id="Status" value=0></td></tr>                    
                                        </table>                                    
                                    </div>
                                    <div id="quickFactores">
                                        <table class="queryTable">
                                            <tr><th colspan= "3" align="center">ALTA RAPIDA DE FACTOR</th></tr>
                                            <tr><td class= "queryRowsnormTR">Factor:</td><td class= "queryRowsnormTR"><input type="text" id="Factor"></td><td rowspan= "2"><img id="'.$this->sufijo.'quickAdd" align= "left" src= "./img/grids/add.png" width= "25" height= "25" alt="Agregar"/></td></tr>
                                            <tr><td class= "queryRowsnormTR">Tipo:</td>
                                                <td class= "queryRowsnormTR">
                                                    <select name="Tipo" id="Tipo" value="Seleccione">
                                                        <option value="Seleccione">Seleccione</option>
                                                        <option value="Interno">Interno</option>
                                                        <option value="Externo">Externo</option>
                                                    </select>
                                                </td>
                                            </tr>                    
                                        </table>
                                    </div>
                                </body>';
                    return $body;
                    }
            }

    $objQuickFactores = new quickFactores();

    $html = '<html>'.$objQuickFactores->body().'</html>';
    
    echo $html;
        
?>