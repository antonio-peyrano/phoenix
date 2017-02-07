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
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $clavecod = '';   
    $now = time();
    $periodo = date("Y",$now);
    $regcount = 0;  
      
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta funciÃ³n establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM catVehiculos WHERE idVehiculo='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   

    function cargarEntidades()
        {
            /*
             * Esta funci�n establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
                        
    $Registro = @mysql_fetch_array(cargarRegistro($parametro), MYSQL_ASSOC);//Llamada a la funciÃ³n de carga de registro de usuario.

    function controlVisual($idRegistro)
        {
            /*
             * Esta funciÃ³n controla los botones que deberan verse en la pantalla deacuerdo con la acciÃ³n solicitada por el
             * usuario en la ventana previa. Si es una ediciÃ³n, los botones borrar y guardar deben verse. Si es una creaciÃ³n
             * solo el boton guardar debe visualizarse.
             */
            global $cntview;
            
            if($idRegistro == -1)
                {
                    //En caso que la acciÃ³n corresponda a la creaciÃ³n de un nuevo registro.
                    echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/vehiculos/busVehiculos.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarVehiculos(\'./php/backend/vehiculos/guardar.php\',\'?id=\'+document.getElementById(\'idVehiculo\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&numeconomico=\'+document.getElementById(\'NumEconomico\').value.toString()+\'&numeroplaca=\'+document.getElementById(\'NumeroPlaca\').value.toString()+\'&color=\'+document.getElementById(\'Color\').value.toString()+\'&marca=\'+document.getElementById(\'Marca\').value.toString()+\'&modelo=\'+document.getElementById(\'Modelo\').value.toString()+\'&tmotor=\'+document.getElementById(\'TMotor\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else 
                {
                    if($cntview == 1)
                        {
                            //En caso de procesarse como una acciÃ³n de visualizaciÃ³n.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/vehiculos/busVehiculos.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/vehiculos/borrar.php\',\'?id=\'+document.getElementById(\'idVehiculo\').value.toString(),\'escritorio\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarVehiculos(\'./php/backend/vehiculos/guardar.php\',\'?id=\'+document.getElementById(\'idVehiculo\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&numeconomico=\'+document.getElementById(\'NumEconomico\').value.toString()+\'&numeroplaca=\'+document.getElementById(\'NumeroPlaca\').value.toString()+\'&color=\'+document.getElementById(\'Color\').value.toString()+\'&marca=\'+document.getElementById(\'Marca\').value.toString()+\'&modelo=\'+document.getElementById(\'Modelo\').value.toString()+\'&tmotor=\'+document.getElementById(\'TMotor\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habVehiculos();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            //En caso que la acciÃ³n corresponda a la ediciÃ³n de un registro.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/vehiculos/busVehiculos.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarVehiculos(\'./php/backend/vehiculos/guardar.php\',\'?id=\'+document.getElementById(\'idVehiculo\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&numeconomico=\'+document.getElementById(\'NumEconomico\').value.toString()+\'&numeroplaca=\'+document.getElementById(\'NumeroPlaca\').value.toString()+\'&color=\'+document.getElementById(\'Color\').value.toString()+\'&marca=\'+document.getElementById(\'Marca\').value.toString()+\'&modelo=\'+document.getElementById(\'Modelo\').value.toString()+\'&tmotor=\'+document.getElementById(\'TMotor\').value.toString()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habVehiculos();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    }
            }
            
    function constructor()
        {
            /*
             * Esta funciÃ³n establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod, $periodo, $regcount;

            $habcampos = 'disabled= "disabled"';
            
            if($Registro['idVehiculo'] == null)
                {
                    //En caso que el registro sea de nueva creacion.
                    $habcampos='';        
                    }

            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        </head>
                        <body>
                            <div style=display:none>
                                <input type= "text" id= "idVehiculo" value="'.$Registro['idVehiculo'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                            <center>
                            <div id="infoVehiculo" style="width: 400px; height: 600px;">                                                                    
                            <table class="dgTable">
                                <tr><th class="dgHeader" colspan= 2">Vehiculo en el Sistema</th></tr>';
                                                                          
            echo'               <tr><td class="dgRowsaltTR" width="100px">Numero Economico:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "NumEconomico" '.$habcampos.' value= "'.$Registro['NumEconomico'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Numero de Placa:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "NumeroPlaca" '.$habcampos.' value= "'.$Registro['NumPlaca'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Color:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Color" '.$habcampos.' value= "'.$Registro['Color'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Marca:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Marca" '.$habcampos.' value= "'.$Registro['Marca'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Modelo:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Modelo" '.$habcampos.' value= "'.$Registro['Modelo'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Tipo de Motor:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "TMotor" '.$habcampos.' value= "'.$Registro['TMotor'].'"></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Entidad:</td><td class="dgRowsnormTR"><select name= "idEntidad" id= "idEntidad" '.$habcampos.' value= "'.$Registro['idEntidad'].'">';
                                $subconsulta = cargarEntidades();
            echo '                              <option value=-1>Seleccione</option>';
                       
                                $RegEntidades = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                
                                while($RegEntidades)
                                    {
                                        if($RegEntidades['idEntidad']==$Registro['idEntidad'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo '                              <option value='.$RegEntidades['idEntidad'].' selected="selected">'.$RegEntidades['Entidad'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo '                              <option value='.$RegEntidades['idEntidad'].'>'.$RegEntidades['Entidad'].'</option>';                                                
                                                }
                                        $RegEntidades = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                        }                                    
            
            echo'            
                                <tr><td class="dgRowsaltTR" width="100px">Periodo:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$Registro['Periodo'].'"></td></tr>';
                                controlVisual($parametro);
            echo'           </table>
                            </div>
                            </center>  
                        </body>
                    </html>
                    ';            
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        constructor();
?>