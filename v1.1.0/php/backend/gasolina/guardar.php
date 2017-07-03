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
     * Este modulo sirve como pasarela de ejecuci�n del comando guardar, cuando es ejecutado desde un formulario
     * para la edici�n de registro.
     */
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $cntrlParse=0;
    $cntrlVar=0;

    $Programacion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
    $Ejecucion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
    $Eficacia = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
        
    if(isset($_GET['id'])&&isset($_GET['identidad'])&&isset($_GET['periodo'])&&isset($_GET['status']))
        {
            /*
             * Si las variables primarias de almacenamiento se obtuvieron correctamente de la URL,
             * se procede a asignarlas en el bloque previo de ejecucion.
             */            
            $idProgGas = $_GET['id'];
            $idEntidad = $_GET['identidad'];
            $Periodo = $_GET['periodo'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)                        
            }

    for($counter=1;$counter<13;$counter++)
        {
            if(isset($_GET['p_'.$counter]))
                {
                    $cntrlParse+=1;
                    }
            }    
                               
    function obtainMes($Mes)
        {
            /*
             * Esta funci�n obtiene el nombre del mes apartir de su cardinal numerico.
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
        
    function updt_opEjecGasAnual()
        {
            /*
             * Esta funci�n establece el calculo ascendente para los porcentajes
             * de ejecucion de los niveles de la planeaci�n.
             */
            global $username, $password, $servername, $dbname;
            global $Programacion, $Ejecucion, $Eficacia;
            global $idEntidad, $idProgGas;
                   
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            /*
             * FASE 1: ACTUALIZACIÓN SOBRE EL NIVEL DE PROGRAMA.
             */
            
            //Se procede a recoger los datos almacenados en la programacion.
            $consulta = 'SELECT *FROM opProgGas WHERE idEntidad='.$idEntidad.' AND Status=0';
            $dsProgAct = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegProgAct = @mysql_fetch_array($dsProgAct,MYSQLI_ASSOC);
                    
            //Se procede a recoger los datos almacenados en la ejecucion.
            $consulta = 'SELECT *FROM opEjecGas WHERE idEntidad='.$idEntidad.' AND Status=0';
            $dsEjecAct = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegEjecAct = @mysql_fetch_array($dsEjecAct,MYSQLI_ASSOC);

            while($RegProgAct)
                {
                    //Recorrido secuencial sobre la colecci�n de tuplas para obtener los valores de Programaci�n.
                    $totProgramado = 0.00;
                            
                    for ($cont = 1; $cont <= 12; $cont++)
                        {
                            //Se obtiene la sumatoria de la programaci�n para la actividad seleccionada.
                            $totProgramado += $RegProgAct[obtainMes($cont)];
                            }
                    
                    for($cont = 1; $cont <= 12; $cont++)
                        {
                            //Se obtiene el porcentaje correspondiente a la programaci�n del i-esimo mes.
                            $Programacion[$cont-1] += $RegProgAct[obtainMes($cont)];
                            $Ejecucion[$cont-1] += $RegEjecAct[obtainMes($cont)];                            
                            //$Programacion[$cont-1] += ($RegProgAct[obtainMes($cont)]*100)/$totProgramado;
                            //$Ejecucion[$cont-1] += ($RegEjecAct[obtainMes($cont)]*100)/$totProgramado;
                            }
                                    
                    $RegEjecAct = @mysql_fetch_array($dsEjecAct,MYSQLI_ASSOC);
                    $RegProgAct = @mysql_fetch_array($dsProgAct,MYSQLI_ASSOC);
                    }
                                                
            for($cont = 0; $cont < 12; $cont++)
                {
                    //Se corrige los valores de programaci�n acorde a la cantidad de actividades computadas.
                            
                    if($Programacion[$cont] != 0)
                        {
                            //En caso de existir programaci�n, se procesa con normalidad.
                            $Eficacia[$cont] = ($Ejecucion[$cont]/$Programacion[$cont])*100.00;
                            }
                    else
                        {
                            //En caso de ser programacion igual a cero, se procesa como una sobrecarga. 
                            $Eficacia[$cont] = $Ejecucion[$cont]*100;
                            }
                            
                    //Se procede a actualizar el registro de programacion a nivel del programa.
                    $consulta = 'UPDATE opProgGas SET '.obtainMes($cont+1).'=\''.$Programacion[$cont].'\' WHERE idEntidad='.$idEntidad.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta

                    //Se procede a actualizar el registro de ejecucion a nivel del programa.
                    $consulta = 'UPDATE opEjecGas SET '.obtainMes($cont+1).'=\''.$Ejecucion[$cont].'\' WHERE idEntidad='.$idEntidad.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta

                    //Se procede a actualizar el registro de eficacia a nivel del programa.
                    $consulta = 'UPDATE opEficGas SET '.obtainMes($cont+1).'=\''.$Eficacia[$cont].'\' WHERE idEntidad='.$idEntidad.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta                                                        
                    }
            }

    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
    if(($cntrlParse==12)&&($cntrlVar==1))
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */
            $P1 = $_GET['p_1'];
            $P2 = $_GET['p_2'];
            $P3 = $_GET['p_3'];
            $P4 = $_GET['p_4'];
            $P5 = $_GET['p_5'];
            $P6 = $_GET['p_6'];
            $P7 = $_GET['p_7'];
            $P8 = $_GET['p_8'];
            $P9 = $_GET['p_9'];
            $P10 = $_GET['p_10'];
            $P11 = $_GET['p_11'];
            $P12 = $_GET['p_12'];
            
            if($idProgGas != null)
                {
                    /*
                     * En caso que la accion ejecutada sea una edicion.
                     */            
                    //Se actualiza la tupla para los valores de programaci�n de gasolina para la entidad.
                    $consulta = 'UPDATE opProgGas SET Enero=\''.$P1.'\', Febrero=\''.$P2.'\', Marzo=\''.$P3.'\', Abril=\''.$P4.'\', Mayo=\''.$P5.'\', Junio=\''.$P6.'\', Julio=\''.$P7.'\', Agosto=\''.$P8.'\', Septiembre=\''.$P9.'\', Octubre=\''.$P10.'\', Noviembre=\''.$P11.'\', Diciembre=\''.$P12.'\', Periodo=\''.$Periodo.'\', Status=\''.$Status.'\' WHERE idProgGas='.$idProgGas;
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    updt_opEjecGasAnual(); //Se llama a la funci�n del calculo global.            
                    }
            else
                {
                    /*
                     * En caso que la accion ejecutada sea una creacion.
                     */
                        
                    //Se crea la tupla vacia para el registro de valores programados.
                    $consulta = 'INSERT INTO opProgGas(idEntidad, Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre, Periodo) VALUES ('.'\''.$idEntidad.'\',\''.$P1.'\',\''.$P2.'\',\''.$P3.'\',\''.$P4.'\',\''.$P5.'\',\''.$P6.'\',\''.$P7.'\',\''.$P8.'\',\''.$P9.'\',\''.$P10.'\',\''.$P11.'\',\''.$P12.'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crea la tupla vacia para el registro de valores ejecutados.
                    $consulta = 'INSERT INTO opEjecGas(idEntidad, Periodo) VALUES ('.'\''.$idEntidad.'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.

                    //Se crea la tupla vacia para el registro de valores de las eficacias.
                    $consulta = 'INSERT INTO opEficGas(idEntidad, Periodo) VALUES ('.'\''.$idEntidad.'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                        
                    updt_opEjecGasAnual(); //Se llama a la funci�n del calculo global.
                    }

            //$_GET['view'] = 3; //Se establece la variable de control de visualizacion.
            //$_GET['id'] = $idProgGas; //Se establece la variable de control del id del programa.
    
            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/gasolina/busGasolina.php");
            }
    else
        {
            /*
             * En caso de ocurrir un error con la operatividad del sistema,
             * se despliega un mensaje al usuario.
             */
            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/main/errorSistema.php");
            }            
    ?>