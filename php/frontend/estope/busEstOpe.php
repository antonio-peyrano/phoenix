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

    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/bl/utilidades/usrctrl.class.php"); //Se carga la referencia de clase para control de accesos.

    class busEstOpe
        {
            private $sufijo= "eop_";
            
            public function drawUI()
                {
                    echo '  <html>
                                <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                                <div id="paginado" style="display:none">
                                    <input id="pagina" type="text" value="1">
                                    <input id="pgestope" type="text" value="">
                                    <input id="pgestopenom" type="text" value="">
                                    <input id="pgperiodo" type="text" value="">
                                </div>
                                <center><div id= "divbusqueda">
                                    <form id="frmbusqueda" method="post" action="">
                                        <table class="queryTable" colspan= "3">
                                            <tr><td class= "queryRowsnormTR" width ="180">Por estrategia operativa: </td><td class= "queryRowsnormTR" width ="250"><input type= "text" id= "nomestope"></td><td rowspan= "3"><img id="'.$this->sufijo.'buscar" align= "left" src= "./img/grids/view.png" width= "25" height= "25" alt="Buscar"/></td></tr>
                                            <tr><td class= "queryRowsnormTR">Por nomenclatura: </td><td class= "queryRowsnormTR"><input type= "text" id= "estopenomenclatura"></td><td></td></tr>
                                            <tr><td class= "queryRowsnormTR">Por periodo: </td><td class= "queryRowsnormTR"><input type= "text" id= "estopeperiodo"></td><td></td></tr>
                                        </table>
                                    </form>
                                </div></center>';                    
                    }
        }

    $objUsrCtrl = new usrctrl();
        
    if($objUsrCtrl->getCredenciales())
        {
            /*
             * Se valida que el usuario tenga sus credenciales cargadas
             * previo login en el sistema.
             */
            $idUsuario = $objUsrCtrl->getidUsuario($_SESSION['usuario'], $_SESSION['clave']);
            $Modulo = 'Estrategia Operativa';
        
            if($objUsrCtrl->validarCredenciales($idUsuario, $Modulo)!='')
                {
                    /*
                     * Se valida que las credenciales autoricen la ejecucion del
                     * modulo solicitado.
                     */
                    $objBusEstOpe = new busEstOpe();
        
                    echo '  <html>
                                    <center>';
        
                    echo            $objBusEstOpe->drawUI();
        
                    echo '          </center><br>';
        
                    echo '          <div id= "busRes">';
                                        include_once("catEstOpe.php");
                    echo '          </div>
                            </html>';
                    }
            else
                {
                    /*
                     * En caso que no cuente con credenciales validas, el sistema impedira
                     * la brecha de seguridad.
                     */
                    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/notificaciones/noAutorizado.php");
                    }
            }
    else
        {
            /*
             * En caso que no cuente con credenciales validas, el sistema impedira
             * la brecha de seguridad.
             */
            include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/notificaciones/noAutorizado.php");
            }               
?>