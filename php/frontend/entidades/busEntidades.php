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

    class busEntidades
        {
            private $sufijo= "ent_";
    
            public function cargarTipos()
                {
                    /*
                     * Esta función establece la carga del conjunto de registros de tipos de entidades.
                     */
                    global $username, $password, $servername, $dbname;
    
                    $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta = 'SELECT idTEntidad, TEntidad FROM catTEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    return $dataset;
                    }
                    
            public function drawUI()
                {
                    echo '  <html>
                                <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                                <div id="paginado" style="display:none">
                                    <input id="pagina" type="text" value="1">
                                    <input id="pgentidad" type="text" value="">
                                    <input id="pgidtentidad" type="text" value="">
                                </div>
                                <center><div id= "divbusqueda">
                                    <form id="frmbusqueda" method="post" action="">
                                        <table class="queryTable" colspan= "7">
                                            <tr><td class= "queryRowsnormTR" width ="180">Por nombre de la entidad: </td><td class= "queryRowsnormTR" width ="250"><input type= "text" id= "nomentidad"></td><td rowspan= "2"><img id="'.$this->sufijo.'buscar" align= "left" src= "./img/grids/view.png" width= "25" height= "25" alt="Buscar"/></td></tr>
                                            <tr><td class= "queryRowsnormTR">Por tipo de la entidad: </td><td class= "queryRowsnormTR"><select name= "nomidtentidad" id= "nomidtentidad" value= "-1">';
                                            
                                            $subconsulta = $this->cargarTipos();
                    echo '                          <option value=-1>Seleccione</option>';
                    
                                            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                    
                                            while($RegNiveles)
                                                {
                    echo '                          <option value='.$RegNiveles['idTEntidad'].'>'.$RegNiveles['TEntidad'].'</option>';
                                                    $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                                    }
                    
                    echo'                   </select></td></tr>
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
            $Modulo = 'Entidades';
            
            if($objUsrCtrl->validarCredenciales($idUsuario, $Modulo)!='')
                {
                    /*
                     * Se valida que las credenciales autoricen la ejecucion del
                     * modulo solicitado.
                     */
                    $objBusEntidades = new busEntidades();
            
                    echo '  <html>
                                    <center>';
            
                    echo            $objBusEntidades->drawUI();
            
                    echo '          </center><br>';
            
                    echo '          <div id= "busRes">';
                                        include_once("catEntidades.php");
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