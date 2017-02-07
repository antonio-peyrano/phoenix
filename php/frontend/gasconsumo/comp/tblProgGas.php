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

    /*
     * Este archivo contiene el constructor para el combobox de objetivos operativos a visualizar.
     */
    header('Content-Type: text/html; charset=iso-8859-1'); //Forzar la codificación a ISO-8859-1.
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.

    $idVehiculo = $_GET['id'];
    $idEntidad = '';
    $Optimo = 0; //El valor de limite superior para la eficacia.
    $Tolerable = 0; //El valor de limite inferior para la eficacia.
    $Periodo = ''; //El valor del periodo a visualizar.
    $rowBanderas = '';
        
    global $habcampos, $RegNiveles, $Registro, $nonhabilitado;
    global $username, $password, $servername, $dbname;
    
    function obtenerPerfilSys()
        {
            /*
             * Esta funci�n obtiene el perfil del sistema activo para el despliegue de la
             * informaci�n de la planeaci�n.
             */
            global $username, $password, $servername, $dbname;
            global $Periodo, $Optimo, $Tolerable;
         
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= "SELECT *FROM catConfiguraciones WHERE Status=0"; //Se establece el modelo de consulta de datos.
            $dsConfiguracion = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegConfiguracion = @mysql_fetch_array($dsConfiguracion, MYSQL_ASSOC);
         
            if($RegConfiguracion)
                {
                    //Si ha sido localizada una configuraci�n valida.
                    $Optimo = $RegConfiguracion['Optimo'];
                    $Tolerable = $RegConfiguracion['Tolerable'];
                    $Periodo = $RegConfiguracion['Periodo'];
                    }
            }
        
    function cargarBanderas($parametro, $mes)
        {
            /*
             * Esta funci�n carga la parte grafica que corresponde a las banderas de consumo.
             */
            global $Periodo, $Optimo, $Tolerable, $rowBanderas;
    
            if($parametro>=$Optimo)
                {
                    //Si el parametro recibido esta en el rango de medici�n optima.
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
            
    obtenerPerfilSys(); //Se cargan los parametros para control del sistema.
    
    //Se obtiene el id de la entidad apartir del id del vehiculo.        
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    $subconsulta='SELECT idEntidad FROM catVehiculos WHERE idVehiculo ='.$idVehiculo;
    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
    $Registro = @mysql_fetch_array($subdataset, MYSQL_ASSOC);
    $idEntidad = $Registro['idEntidad'];
    
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
                        
                $rowdata='<tr><td class= "queryTitles">Programaci�n</td>';
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
                        //Para el caso de una creaci�n de registro.
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
                        
                $rowdata='<tr><td class= "queryTitles">Ejecuci�n</td>';
                $count=1;
                $totEficacia=0;
                                    
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
                        //Para el caso de una creaci�n de registro.
                        $counter=1;
                                            
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
                $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEficGas WHERE status= 0 AND idEntidad= '.$idEntidad;
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
                        //Para el caso de una creaci�n de registro.
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
    echo '                  <tr><td class= "queryTitles">Estado</td>'.$rowBanderas.'</tr>            
                        </table>';
       
?>