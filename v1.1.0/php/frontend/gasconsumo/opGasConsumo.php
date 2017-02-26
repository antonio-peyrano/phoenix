<?php
/*
 * Micrositio-Phoenix v1.0 Software para gestion de la planeacion operativa.
 * PHP v5
 * Autor: Prof. Jesus Antonio Peyrano Luna <antonio.peyrano@live.com.mx>
 * Nota aclaratoria: Este programa se distribuye bajo los terminos y disposiciones
 * definidos en la GPL v3.0, debidamente incluidos en el repositorio original.
 * Cualquier copia y/o redistribucion del presente, debe hacerse con una copia
 * adjunta de la licencia en todo momento.
 * Licencia: http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificacion a ISO-8859-1.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    $parametro = '';   
    $idVehiculo = '';
    $idEjecGas = '';
    $Optimo = 0; //El valor de limite superior para la eficacia.
    $Tolerable = 0; //El valor de limite inferior para la eficacia.
    $Periodo = ''; //El valor del periodo a visualizar.
    $rowBanderas = '';

    function cargarBanderas($parametro, $mes)
        {
            /*               
             *Esta función carga la parte grafica que corresponde a las banderas de consumo.
             */
            global $Periodo, $Optimo, $Tolerable, $rowBanderas;
    
            if($parametro>=$Optimo)
                {
                    //Si el parametro recibido esta en el rango de medición optima.
                    $rowBanderas.='<td><center><img id="falla_'.$mes.'"align= "middle" src= "./img/banderas/falla.png" width= "25" height= "25" alt= "Falla" data-toggle="tooltip" title="Consumo critico"/></center></td>';
                    }
    
            if(($parametro>=$Tolerable)&&($parametro<$Optimo))
                {
                    //Si el parametro recibido esta dentro del rango tolerable.
                    $rowBanderas.='<td><center><img id="riesgo_'.$mes.'"align= "middle" src= "./img/banderas/riesgo.png" width= "25" height= "25" alt= "Riesgo" data-toggle="tooltip" title="Consumo en margen critico"/></center></td>';
                    }
    
            if($parametro<$Tolerable)
                {
                    //Si el parametro recibido esta por debajo de lo tolerable.
                    $rowBanderas.='<td><center><img id="optimo_'.$mes.'"align= "middle" src= "./img/banderas/optimo.png" width= "25" height= "25" alt= "Optimo" data-toggle="tooltip" title="Consumo moderado"/></center></td>';
                    }
            }
        
    function obtenerPerfilSys()
        {
            /*
             * Esta función obtiene el perfil del sistema activo para el despliegue de la
             * información de la planeación.
             */
             global $username, $password, $servername, $dbname;
             global $Periodo, $Optimo, $Tolerable;
             
             $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
             $consulta= "SELECT *FROM catConfiguraciones WHERE Status=0"; //Se establece el modelo de consulta de datos.
             $dsConfiguracion = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
             $RegConfiguracion = @mysql_fetch_array($dsConfiguracion, MYSQL_ASSOC);
             
             if($RegConfiguracion)
                {
                    //Si ha sido localizada una configuración valida.
                    $Optimo = $RegConfiguracion['Optimo'];
                    $Tolerable = $RegConfiguracion['Tolerable'];
                    $Periodo = $RegConfiguracion['Periodo'];
                    }
            }
                        
    if(isset($_GET['view']))
        {
            /*
             * Si se declaro en la url el control de visualización.
             */
            $cntview = $_GET['view'];
            if(($cntview == '0')||($cntview == '3'))
                {
                    //Si la visualización esta por default, se asigna a parametro
                    //el id que corresponde.
                    $parametro = $_GET['idproggas'];
                    $idVehiculo = $_GET['idvehiculo'];
                    $idEjecGas = $_GET['idejecgas'];
                    }
            }
        
    $now = time();
    $periodo = date("Y",$now);

    function getMes($Mes)
        {
            /*
             * Esta función obtiene el nombre del mes apartir de su cardinal numerico.
             */
            if($Mes == "1")
                {
                    return "Enero";
                    }
            if($Mes == "2")
                {
                    return "Febrero";
                    }
            if($Mes == "3")
                {
                    return "Marzo";
                    }
            if($Mes == "4")
                {
                    return "Abril";
                    }
            if($Mes == "5")
                {
                    return "Mayo";
                    }
            if($Mes == "6")
                {
                    return "Junio";
                    }
            if($Mes == "7")
                {
                    return "Julio";
                    }
            if($Mes == "8")
                {
                    return "Agosto";
                    }
            if($Mes == "9")
                {
                    return "Septiembre";
                    }
            if($Mes == "10")
                {
                    return "Octubre";
                    }
            if($Mes == "11")
                {
                    return "Noviembre";
                    }
            if($Mes == "12")
                {
                    return "Diciembre";
                    }
            }
                                    
    function cargarVehiculos()
        {
            /*
             * Esta función establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idVehiculo, NumPlaca FROM catVehiculos WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }

    function cargarVehiculo($idRegistro)
        {
            /*
             * Esta función carga la información del vehiculo consultado, apartir del ID.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM (catVehiculos INNER JOIN catEntidades ON catEntidades.idEntidad = catVehiculos.idEntidad) WHERE idVehiculo='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }

    $RegVehiculo = @mysql_fetch_array(cargarVehiculo($idVehiculo), MYSQL_ASSOC);//Llamada a la función de carga de registro de usuario.            
                        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM (catEntidades INNER JOIN opProgGas ON catEntidades.idEntidad = opProgGas.idEntidad) WHERE opProgGas.idProgGas='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
            
    $Registro = @mysql_fetch_array(cargarRegistro($parametro), MYSQL_ASSOC);//Llamada a la función de carga de registro de usuario.
            
    function constructor()
        {
            /*
             * Esta función establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $RegVehiculo, $parametro, $clavecod;
            global $username, $password, $servername, $dbname, $cntview;           
            global $idEjecGas, $idVehiculo, $periodo,$rowBanderas;

            $idEntidad = $RegVehiculo['idEntidad'];
            $habcampos = 'disabled= "disabled"';

            if($Registro['idProgGas'] == null)
                {
                    //En caso que el registro sea de nueva creacion.      
                    $activid= -1;
                    }
            else
                {
                    //En caso contrario se asigan el id de la actividad al temporal.
                    $activid= $Registro['idProgGas']; 
                    }                    
                               
            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                            <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                        </head>
                        <body>
                            <div id= "divParametrica" style=display:none>
                                <input type= "text" id= "idProgGas" value= "'.$parametro.'"><br>
                                <input type= "text" id= "idEjecGas" value= "'.$idEjecGas.'"><br>
                                <input type= "text" id= "idVehicle" value= "'.$idVehiculo.'"><br>      
                            </div>                
                            <div id= "infoact">                                        
                            <table class= "dgTable">
                                <tr><th class="dgHeader" colspan= "2">Información General</th></tr>
                                <tr><td class="dgRowsaltTR"  width="100px">Numero de Placa:</td><td class="dgRowsnormTR" class= "queryRowsnormTR"><select name= "idVehiculo" id= "idVehiculo" value= "-1">';
                                
                                $subconsulta = cargarVehiculos();
                                
            echo '              <option value=-1>Seleccione</option>';
            
                                $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                
                                while ($RegNiveles)
                                    {
                                        if($RegNiveles['idVehiculo']==$idVehiculo)
                                            {
                                                /*
                                                 * En caso que se ejecute una acción de consulta, se obtiene la referencia seleccionada
                                                 * de la unidad.
                                                 */
            echo '                              <option value='.$RegNiveles['idVehiculo'].' selected="selected">'.$RegNiveles['NumPlaca'].'</option>';
                                                }
                                        else
                                            {
                                                /*
                                                 * En caso que se ejecute una acción de creacion de registro.
                                                 */

            echo'                               <option value='.$RegNiveles['idVehiculo'].'>'.$RegNiveles['NumPlaca'].'</option>';                                                        
                                                }
                                                
                                                $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                        }
            
            echo'               </select></td></tr>';                                
                                                

            echo'               <tr><td class="dgRowsaltTR" width="100px">Color:</td><td class="dgRowsnormTR"><div id="txtColor" border:none><input type= "text" required= "required" id= "Color" '.$habcampos.' value= "'.$RegVehiculo['Color'].'"></div></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Marca:</td><td class="dgRowsnormTR"><div id="txtMarca" border:none><input type= "text" required= "required" id= "Marca" '.$habcampos.' value= "'.$RegVehiculo['Marca'].'"></div></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Modelo:</td><td class="dgRowsnormTR"><div id="txtModelo" border:none><input type= "text" required= "required" id= "Modelo" '.$habcampos.' value= "'.$RegVehiculo['Modelo'].'"></div></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Entidad:</td><td class="dgRowsnormTR"><div id="txtEntidad" border:none><input type= "text" required= "required" id= "Entidad" '.$habcampos.' value= "'.$RegVehiculo['Entidad'].'"></div></td></tr>
                                <tr><td class="dgRowsaltTR" width="100px">Periodo:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$periodo.'"></td></tr>
                                ';
                                                                                                                                                
            echo'           </table>
                            </div>
                            <br>';

                            $nonhabilitado = 'disabled= "disabled"';
            
            echo'           <div id= "dataproggas">';
            
            echo'   <table class= "queryTable">
                        <tr><th colspan= "14" class= "queryHeader">Consumo Programado (En pesos)</th></tr>
                        <tr><td></td><td class= "queryTitles">Enero</td><td class= "queryTitles">Febrero</td><td class= "queryTitles">Marzo</td><td class= "queryTitles">Abril</td><td class= "queryTitles">Mayo</td><td class= "queryTitles">Junio</td><td class= "queryTitles">Julio</td><td class= "queryTitles">Agosto</td><td class= "queryTitles">Septiembre</td><td class= "queryTitles">Octubre</td><td class= "queryTitles">Noviembre</td><td class= "queryTitles">Diciembre</td><td class= "queryTitles">Total</td></tr>';
            
                        //Se procede con la carga de la programacion que corresponde al programa.
                        $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                        $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opProgGas WHERE status= 0 AND idEntidad= '.$idEntidad;
                        $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                        $dsCampos = $subdataset;
                        $RegAux = @mysql_fetch_array($subdataset, MYSQL_ASSOC);
                        $field = @mysql_fetch_field($dsCampos);
            
                        $rowdata= '<tr><td class= "queryTitles">Programación</td>';
                        $count=1;
                        $totEficacia=0.00;
            
                        if($RegAux)
                            {
                                //Para el caso de una consulta de datos.
                                while($field)
                                    {
                                        $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$habcampos.' id="P_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                        $totEficacia += $RegAux[$field->name];
                                        $field = @mysql_fetch_field($dsCampos);
                                        $count += 1;
                                        }
            
                                $rowdata.='<td class="dgRowsnormTR"><input type="text" id="P_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                }
                        else
                            {
                                //Para el caso de una creación de registro.
                                $counter=1;
            
                                while($counter <= 12)
                                    {
                                        //Mientras no se llegue al ciclo de doce meses.
                                        $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$habcampos.' id="P_'.$counter.'" size="4" value="0.00"></input></td>';
                                        $counter += 1;
                                        }
                                $rowdata.='<td class="dgRowsnormTR"><input type="text" id="P_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                }
            
            echo $rowdata;
            
                        //Se procede con la carga de la ejecucion que corresponde al programa.
                        $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                        $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEjecGas WHERE status= 0 AND idEntidad= '.$idEntidad;
                        $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                        $dsCampos = $subdataset;
                        $RegAux = @mysql_fetch_array($subdataset, MYSQL_ASSOC);
                        $field = @mysql_fetch_field($dsCampos);
            
                        $rowdata= '<tr><td class= "queryTitles">Ejecución</td>';
                        $count= 1;
                        $totEficacia= 0;
            
                        if($RegAux)
                            {
                                //Para el caso de una consulta de datos.
                                while($field)
                                    {
                                        $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="E_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                        $totEficacia += $RegAux[$field->name];
                                        $field = @mysql_fetch_field($dsCampos);
                                        $count += 1;
                                        }
            
                                $rowdata.='<td class="dgRowsnormTR"><input type="text" id="E_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                }
                        else
                            {
                                //Para el caso de una creación de registro.
                                $counter= 1;
            
                                while($counter <= 12)
                                    {
                                        //Mientras no se llegue al ciclo de doce meses.
                                        $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="E_'.$counter.'" size="4" value="0.00"></input></td>';
                                        $counter += 1;
                                        }
                                        
                                $rowdata.='<td class="dgRowsnormTR"><input type="text" id="E_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                }
            
            echo $rowdata;
            
                        //Se procede con la carga de la eficacia que corresponde al programa.
                        $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                        $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEficGas WHERE status= 0 AND idEntidad='.$idEntidad;
                        $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                        $dsCampos = $subdataset;
                        $RegAux = @mysql_fetch_array($subdataset, MYSQL_ASSOC);
                        $field = @mysql_fetch_field($dsCampos);
            
                        $rowdata='<tr><td class= "queryTitles">Eficacia</td>';
                        $count=1;
                        $totEficacia=0;
            
                        if($RegAux)
                            {
                                //Para el caso de una consulta de datos.
                                while($field)
                                    {
                                        $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="Efic_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                        cargarBanderas($RegAux[$field->name], $count);//Se genera la fila de banderas.
                                        $totEficacia += $RegAux[$field->name];
                                        $field = @mysql_fetch_field($dsCampos);
                                        $count += 1;
                                        }
                                        
                                $totEficacia = $totEficacia/12.00;
                                $rowdata.='<td class="dgRowsnormTR"><input type="text" id="Efic_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                }
                        else
                            {
                                //Para el caso de una creación de registro.
                                $counter=1;
            
                                while($counter <= 12)
                                    {
                                        //Mientras no se llegue al ciclo de doce meses.
                                        $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="Efic_'.$counter.'" size="4" value="0.00"></input></td>';
                                        cargarBanderas(0.00, $counter);//Se genera la fila de banderas.
                                        $counter += 1;
                                        }
                                        
                                $rowdata.='<td class="dgRowsnormTR"><input type="text" id="Efic_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                }
            
            echo $rowdata;
            echo '      <tr><td class= "queryTitles">Estado</td>'.$rowBanderas.'</tr>
                    </table>';
            
            echo' </div>';
            
            echo'       
                            <br>
                            <div id= "divConsumos">';
                                if($cntview == 3)
                                    {
                                        /*
                                         * En caso que el invocador sea el formulario de actividades.
                                         */
                                        include_once("../../frontend/gasconsumo/catEjecGas.php");
                                        }
                                else
                                    {
                                        /*
                                         * En caso que el invocador sea el formulario de programa.
                                         */
                                        include_once("../gasconsumo/catEjecGas.php");
                                        }                                                                        
            echo'           </div>              
                        </body>                
                    </html>';
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        obtenerPerfilSys();
        constructor();
?>