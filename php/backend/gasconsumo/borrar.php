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
     * Este modulo sirve como pasarela de ejecuci�n del comando eliminar, cuando es ejecutado desde un formulario
     * para la edici�n de registro.
     */
    $parametro = $_GET['id'];
    $idEjecGas = $_GET['idejecgas'];
    $idProgGas = $_GET['idproggas'];
        
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    
    global $username, $password, $servername, $dbname;
    
    $Programacion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
    $Ejecucion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
    $Eficacia = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
        
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
            global $idProgGas, $idEjecGas;
            
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            //Se procede a recoger la referencia de la programacion asociada a la actividad.
            $consulta = 'SELECT *FROM opProgGas WHERE idProgGas='.$idProgGas.' AND Status=0';
            $dsPrgGasUnit = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegPrgGasUnit = @mysql_fetch_array($dsPrgGasUnit, MYSQL_ASSOC);
            
            //Se procede a recoger la referencia de la ejecucion asociada a la actividad.
            $consulta = 'SELECT *FROM opEjecGas WHERE idEjecGas='.$idEjecGas.' AND Status=0';
            $dsEjcGasUnit = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegEjcGasUnit = @mysql_fetch_array($dsEjcGasUnit, MYSQL_ASSOC);
            
            if($RegPrgGasUnit)
                {
                    //Recorrido secuencial sobre la colecci�n de tuplas para obtener los valores de Programaci�n.
                    $totProgramado = 0.00;
            
                    for($cont = 1; $cont <= 12; $cont++)
                        {
                            //Se obtiene el porcentaje correspondiente a la programaci�n del i-esimo mes.
                            $Programacion[$cont-1] = $RegPrgGasUnit[obtainMes($cont)];
                            $Ejecucion[$cont-1] = $RegEjcGasUnit[obtainMes($cont)];
            
                            if($Programacion[$cont-1] != 0)
                                {
                                    //En caso de existir programaci�n, se procesa con normalidad.
                                    $Eficacia[$cont-1] = ($Ejecucion[$cont-1]/$Programacion[$cont-1])*100.00;
                                    }
                            else
                                {
                                    //En caso de ser programacion igual a cero, se procesa como una sobrecarga.
                                    $Eficacia[$cont-1] = $Ejecucion[$cont-1]*100;
                                    }
            
                            //Se procede a actualizar el registro de eficacia a nivel de la actividad.
                            $consulta = 'UPDATE opEficGas SET '.obtainMes($cont).'=\''.$Eficacia[$cont-1].'\' WHERE idEntidad='.$RegEjcGasUnit['idEntidad'].' AND Status=0'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta
                            }
                    }
            }
                                
    function updt_opEjecGas($Mes)
        {
            /*
             * Esta funci�n establece la actualizacion del registro de ejecuciones sobre la actividad, en
             * el bloque que corresponde al mes seleccionado.
             */
            global $username, $password, $servername, $dbname;
            global $idEjecGas;
            
            $cantTotal = 0;            
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            //Se procede a recoger los datos almacenados en la ejecucion.
            $consulta = 'SELECT Monto FROM opMovGas WHERE idEjecGas='.$idEjecGas.' AND Mes='.$Mes.' AND Status=0';
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $Registro = @mysql_fetch_array($dataset, MYSQL_ASSOC);
            
            while($Registro)
                {
                    //Mientras existan registros computables, se procede a la sumarizaci�n.
                    $cantTotal+= $Registro['Monto'];
                    $Registro = @mysql_fetch_array($dataset, MYSQL_ASSOC);
                    }                                
                                
            //Se procede a actualizar el registro de movimientos a nivel de la ejecucion programada.
            $consulta = 'UPDATE opEjecGas SET '.obtainMes($Mes).'=\''.$cantTotal.'\' WHERE idEjecGas='.$idEjecGas; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                                
            }
                                    
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
    //Obteniendo el mes al que corresponde la ejecucion para borrar, a fin
    //de ejecutar la correcci�n en la planeaci�n.
    $consulta= 'SELECT Mes FROM opMovGas WHERE idMovGas='.$parametro; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    $Registro = @mysql_fetch_array($dataset, MYSQL_ASSOC);
    $idMes = $Registro['Mes'];
    
    //Borrando el registro de consumo de gasolina.
    $consulta= 'UPDATE opMovGas SET Status=1 WHERE idMovGas='.$parametro; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    
    updt_opEjecGas($idMes); //Se llama la funci�n de actualizaci�n de ejecucion a nivel de actividad.
    updt_opEjecGasAnual();
    
    $_GET['view'] = 3; //Se establece la variable de control de visualización. 
    include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/gasconsumo/opGasConsumo.php");
    ?>