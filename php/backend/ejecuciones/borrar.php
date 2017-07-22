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
     * Este modulo sirve como pasarela de ejecución del comando eliminar, cuando es ejecutado desde un formulario
     * para la edición de registro.
     */
    $parametro = $_GET['id'];
    $idPrograma = $_GET['idprograma'];
    $idActividad = $_GET['idactividad'];
    
    $Programacion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
    $Ejecucion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);    
    $Eficacia = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
    
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuraci�n.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    
    global $username, $password, $servername, $dbname;
    
    function updt_opEjecActAnual()
        {
            /*
             * Esta función establece el calculo ascendente para los porcentajes
             * de ejecucion de los niveles de la planeación.
             */
            global $username, $password, $servername, $dbname;
            global $Programacion, $Ejecucion, $Eficacia;
            global $idPrograma, $idActividad;
            
            $idEstope = 0;
            $idObjOpe = 0;
            $idObjEst = 0;            
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            /*
             * FASE 0: ACTUALIZACIÓN SOBRE EL NIVEL DE ACTIVIDAD.
             */
            //Se procede a recoger la referencia de la programacion asociada a la actividad.
            $consulta = 'SELECT *FROM opProgAct WHERE idActividad='.$idActividad.' AND Status=0';
            $dsPrgActUnit = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegPrgActUnit = @mysqli_fetch_array($dsPrgActUnit,MYSQLI_ASSOC);
            
            //Se procede a recoger la referencia de la ejecucion asociada a la actividad.
            $consulta = 'SELECT *FROM opEjecAct WHERE idActividad='.$idActividad.' AND Status=0';
            $dsEjcActUnit = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegEjcActUnit = @mysqli_fetch_array($dsEjcActUnit,MYSQLI_ASSOC);

            if($RegPrgActUnit)
                {
                    //Recorrido secuencial sobre la colección de tuplas para obtener los valores de Programación.
                    $totProgramado = 0.00;
                                                
                    for($cont = 1; $cont <= 12; $cont++)
                        {
                            //Se obtiene el porcentaje correspondiente a la programación del i-esimo mes.
                            $Programacion[$cont-1] = $RegPrgActUnit[obtainMes($cont)];
                            $Ejecucion[$cont-1] = $RegEjcActUnit[obtainMes($cont)];
                            
                            if($Programacion[$cont-1] != 0)
                                {
                                    //En caso de existir programación, se procesa con normalidad.
                                    $Eficacia[$cont-1] = ($Ejecucion[$cont-1]/$Programacion[$cont-1])*100.00;
                                    }
                            else
                                {
                                    //En caso de ser programacion igual a cero, se procesa como una sobrecarga. 
                                    $Eficacia[$cont-1] = $Ejecucion[$cont-1]*100;
                                    }
                            
                            //Se procede a actualizar el registro de eficacia a nivel de la actividad.
                            $consulta = 'UPDATE opEficAct SET '.obtainMes($cont).'=\''.$Eficacia[$cont-1].'\' WHERE idActividad='.$idActividad.' AND Status=0'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta                                    
                            }                                                                                            
                    }
            
            $Programacion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
            $Ejecucion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);    
            $Eficacia = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
                                                                                     
            /*
             * FASE 1: ACTUALIZACIÓN SOBRE EL NIVEL DE PROGRAMA.
             */
            //Se procede a recoger la referencia de actividades asociadas al programa.
            $consulta = 'SELECT idActividad FROM opActividades WHERE idPrograma='.$idPrograma.' AND Status=0';
            $dsActividades = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegActividades = @mysqli_fetch_array($dsActividades,MYSQLI_ASSOC);
                                
            $countAct = 0; //Se incializa el conteo de actividades.
            
            while($RegActividades)
                {
                    /*
                     * Se recorre las actividades generadas para obtener sus valores de programación y ejecucion.
                     */                                       
                    
                    //Se procede a recoger los datos almacenados en la programacion.
                    $consulta = 'SELECT *FROM opProgAct WHERE idActividad='.$RegActividades['idActividad'].' AND Status=0';
                    $dsProgAct = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegProgAct = @mysqli_fetch_array($dsProgAct,MYSQLI_ASSOC);
                    
                    //Se procede a recoger los datos almacenados en la ejecucion.
                    $consulta = 'SELECT *FROM opEjecAct WHERE idActividad='.$RegActividades['idActividad'].' AND Status=0';
                    $dsEjecAct = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegEjecAct = @mysqli_fetch_array($dsEjecAct,MYSQLI_ASSOC);

                    while($RegProgAct)
                        {
                            //Recorrido secuencial sobre la colección de tuplas para obtener los valores de Programación.
                            $totProgramado = 0.00;
                            
                            for ($cont = 1; $cont <= 12; $cont++)
                                {
                                    //Se obtiene la sumatoria de la programación para la actividad seleccionada.
                                    $totProgramado += $RegProgAct[obtainMes($cont)];
                                    }
                    
                            for($cont = 1; $cont <= 12; $cont++)
                                {
                                    //Se obtiene el porcentaje correspondiente a la programación del i-esimo mes.
                                    $Programacion[$cont-1] += ($RegProgAct[obtainMes($cont)]*100)/$totProgramado;
                                    $Ejecucion[$cont-1] += ($RegEjecAct[obtainMes($cont)]*100)/$totProgramado;
                                    }
                                    
                            $RegEjecAct = @mysqli_fetch_array($dsEjecAct,MYSQLI_ASSOC);
                            $RegProgAct = @mysqli_fetch_array($dsProgAct,MYSQLI_ASSOC);
                            }
                            
                    $countAct += 1;        
                    $RegActividades = @mysqli_fetch_array($dsActividades,MYSQLI_ASSOC);
                    }
                    
            for($cont = 0; $cont < 12; $cont++)
                {
                    //Se corrige los valores de programación acorde a la cantidad de actividades computadas.
                    $Programacion[$cont] = $Programacion[$cont]/$countAct;
                    $Ejecucion[$cont] = $Ejecucion[$cont]/$countAct;
                            
                    if($Programacion[$cont] != 0)
                        {
                            //En caso de existir programación, se procesa con normalidad.
                            $Eficacia[$cont] = ($Ejecucion[$cont]/$Programacion[$cont])*100.00;
                            }
                    else
                        {
                            //En caso de ser programacion igual a cero, se procesa como una sobrecarga. 
                            $Eficacia[$cont] = $Ejecucion[$cont]*100;
                            }
                            
                    //Se procede a actualizar el registro de programacion a nivel del programa.
                    $consulta = 'UPDATE opProgPro SET '.obtainMes($cont+1).'=\''.$Programacion[$cont].'\' WHERE idPrograma='.$idPrograma.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta

                    //Se procede a actualizar el registro de ejecucion a nivel del programa.
                    $consulta = 'UPDATE opEjecPro SET '.obtainMes($cont+1).'=\''.$Ejecucion[$cont].'\' WHERE idPrograma='.$idPrograma.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta
                            
                    //Se procede a actualizar el registro de eficacia a nivel del programa.
                    $consulta = 'UPDATE opEficPro SET '.obtainMes($cont+1).'=\''.$Eficacia[$cont].'\' WHERE idPrograma='.$idPrograma.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta                                                        
                    }

            /*
             * FASE 2: ACTUALIZACIÓN SOBRE EL NIVEL DE EST OPE.
             * Se procede a obtener los valores para el nivel de Est. Ope, apartir de los valores de los programas.
             */
            $Programacion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
            $Ejecucion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
            $Eficacia = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
                            
            //Se procede a consultar el programa activo a fin de obtener la Est. Ope. asociada.
            $consulta = 'SELECT idEstOpe FROM opProgramas WHERE idPrograma='.$idPrograma.' AND Status=0';
            $dsProgramas = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegProgramas = @mysqli_fetch_array($dsProgramas,MYSQLI_ASSOC);
                            
            if($RegProgramas)
                {
                    //Se obtiene el id de la Est Ope, para procesar sus programas asociados.
                    $idEstOpe = $RegProgramas['idEstOpe'];
                    }

            //Se procede a obtener los programas asociados a la Est. Ope. adquirida.
            $consulta = 'SELECT idPrograma FROM opProgramas WHERE idEstOpe='.$idEstOpe.' AND Status=0';
            $dsProgramas = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegProgramas = @mysqli_fetch_array($dsProgramas,MYSQLI_ASSOC);
                    
            $countProg = 0; //Se inicializa el conteo de programas.                    
                    
            while($RegProgramas)
                {
                    /*
                     * Se recorren todos los programas bajo la Est. Ope.
                     */
                                                        
                    //Se procede a recoger los datos almacenados en la programacion.
                    $consulta = 'SELECT *FROM opProgPro WHERE idPrograma='.$RegProgramas['idPrograma'].' AND Status=0';
                    $dsProgPro = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegProgPro = @mysqli_fetch_array($dsProgPro,MYSQLI_ASSOC);
                            
                    //Se procede a recoger los datos almacenados en la ejecucion.
                    $consulta = 'SELECT *FROM opEjecPro WHERE idPrograma='.$RegProgramas['idPrograma'].' AND Status=0';
                    $dsEjecPro = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegEjecPro = @mysqli_fetch_array($dsEjecPro,MYSQLI_ASSOC);
                            
                    while($RegProgPro)
                        {
                            //Recorrido secuencial sobre la colección de tuplas para obtener los valores de Programación.                            
                            for($cont = 1; $cont <= 12; $cont++)
                                {
                                    //Se obtiene el porcentaje correspondiente a la programación del i-esimo mes.
                                    $Programacion[$cont-1] += $RegProgPro[obtainMes($cont)];
                                    $Ejecucion[$cont-1] += $RegEjecPro[obtainMes($cont)];                                    
                                    }
                            
                            $RegEjecPro = @mysqli_fetch_array($dsEjecPro,MYSQLI_ASSOC);
                            $RegProgPro = @mysqli_fetch_array($dsProgPro,MYSQLI_ASSOC);
                            }
                                                            
                    $countProg += 1;
                    $RegProgramas = @mysqli_fetch_array($dsProgramas,MYSQLI_ASSOC);
                    }
                            
            for($cont = 0; $cont < 12; $cont++)
                {
                    //Se corrige los valores de programación acorde a la cantidad de programas computados.
                    $Programacion[$cont] = $Programacion[$cont]/$countProg;
                    $Ejecucion[$cont] = $Ejecucion[$cont]/$countProg;
                            
                    if($Programacion[$cont] != 0)
                        {
                            //En caso de existir programación, se procesa con normalidad.
                            $Eficacia[$cont] = ($Ejecucion[$cont]/$Programacion[$cont])*100.00;
                            }
                    else
                        {
                            //En caso de ser programacion igual a cero, se procesa como una sobrecarga. 
                            $Eficacia[$cont] = $Ejecucion[$cont]*100;
                            }
                                                        
                    //Se procede a actualizar el registro de programacion a nivel de la Est. Ope.
                    $consulta = 'UPDATE opProgEst SET '.obtainMes($cont+1).'=\''.$Programacion[$cont].'\' WHERE idEstOpe='.$idEstOpe.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta
                            
                    //Se procede a actualizar el registro de ejecucion a nivel de la Est. Ope.
                    $consulta = 'UPDATE opEjecEst SET '.obtainMes($cont+1).'=\''.$Ejecucion[$cont].'\' WHERE idEstOpe='.$idEstOpe.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta
                    
                    //Se procede a actualizar el registro de eficacia a nivel de la Est. Ope.
                    $consulta = 'UPDATE opEficEst SET '.obtainMes($cont+1).'=\''.$Eficacia[$cont].'\' WHERE idEstOpe='.$idEstOpe.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta                    
                    } 
                                                                                                  
            /*
             * FASE 3: ACTUALIZACIÓN SOBRE EL NIVEL DE OBJ OPE.
             * Se procede a obtener los valores para el nivel de Obj. Ope, apartir de los valores de las Est Ope.
             */
            $Programacion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
            $Ejecucion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
            $Eficacia = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
                            
            //Se procede a consultar la Est Ope activa a fin de obtener el Obj. Ope. asociado.
            $consulta = 'SELECT idObjOpe FROM catEstOpe WHERE idEstOpe='.$idEstOpe.' AND Status=0';
            $dsProgramas = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegEstOpe = @mysqli_fetch_array($dsProgramas,MYSQLI_ASSOC);
                            
            if($RegEstOpe)
                {
                    //Se obtiene el id de la Obj Ope, para procesar sus Est Ope asociados.
                    $idObjOpe = $RegEstOpe['idObjOpe'];
                    }
                            
            //Se procede a obtener los programas asociados a la Est. Ope. adquirida.
            $consulta = 'SELECT idEstOpe FROM catEstOpe WHERE idObjOpe='.$idObjOpe.' AND Status=0';
            $dsProgramas = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegEstOpe = @mysqli_fetch_array($dsProgramas,MYSQLI_ASSOC);
                            
            $countEstOpe = 0; //Se inicializa el conteo de programas.
                            
            while($RegEstOpe)
                {
                    /*
                     * Se recorren todos los programas bajo el Obj. Ope.
                     */
                            
                    //Se procede a recoger los datos almacenados en la programacion.
                    $consulta = 'SELECT *FROM opProgEst WHERE idEstOpe='.$RegEstOpe['idEstOpe'].' AND Status=0';
                    $dsProgEst = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegProgEst = @mysqli_fetch_array($dsProgEst,MYSQLI_ASSOC);
                            
                    //Se procede a recoger los datos almacenados en la ejecucion.
                    $consulta = 'SELECT *FROM opEjecEst WHERE idEstOpe='.$RegEstOpe['idEstOpe'].' AND Status=0';
                    $dsEjecEst = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegEjecEst = @mysqli_fetch_array($dsEjecEst,MYSQLI_ASSOC);
                            
                    while($RegProgEst)
                        {
                            //Recorrido secuencial sobre la colección de tuplas para obtener los valores de Programación.                            
                            for($cont = 1; $cont <= 12; $cont++)
                                {
                                    //Se obtiene el porcentaje correspondiente a la programación del i-esimo mes.
                                    $Programacion[$cont-1] += $RegProgEst[obtainMes($cont)];
                                    $Ejecucion[$cont-1] += $RegEjecEst[obtainMes($cont)];
                                    }
                            
                            $RegEjecEst = @mysqli_fetch_array($dsEjecEst,MYSQLI_ASSOC);
                            $RegProgEst = @mysqli_fetch_array($dsProgEst,MYSQLI_ASSOC);
                            }
                            
                    $countEstOpe += 1;
                    $RegEstOpe = @mysqli_fetch_array($dsProgramas,MYSQLI_ASSOC);
                    }
                            
            for($cont = 0; $cont < 12; $cont++)
                {
                    //Se corrige los valores de programación acorde a la cantidad de las Est Ope computadas.
                    $Programacion[$cont] = $Programacion[$cont]/$countEstOpe;
                    $Ejecucion[$cont] = $Ejecucion[$cont]/$countEstOpe;
                            
                    if($Programacion[$cont] != 0)
                        {
                            //En caso de existir programación, se procesa con normalidad.
                            $Eficacia[$cont] = ($Ejecucion[$cont]/$Programacion[$cont])*100.00;
                            }
                    else
                        {
                            //En caso de ser programacion igual a cero, se procesa como una sobrecarga. 
                            $Eficacia[$cont] = $Ejecucion[$cont]*100;
                            }
                            
                    //Se procede a actualizar el registro de programacion a nivel del Obj. Ope.
                    $consulta = 'UPDATE opProgOO SET '.obtainMes($cont+1).'=\''.$Programacion[$cont].'\' WHERE idObjOpe='.$idObjOpe.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta
                            
                    //Se procede a actualizar el registro de ejecucion a nivel del Obj. Ope.
                    $consulta = 'UPDATE opEjecOO SET '.obtainMes($cont+1).'=\''.$Ejecucion[$cont].'\' WHERE idObjOpe='.$idObjOpe.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta
                    
                    //Se procede a actualizar el registro de eficacia a nivel del Obj. Ope.
                    $consulta = 'UPDATE opEficOO SET '.obtainMes($cont+1).'=\''.$Eficacia[$cont].'\' WHERE idObjOpe='.$idObjOpe.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta
                    }
                            
            /*
             * FASE 4: ACTUALIZACIÓN SOBRE EL NIVEL DE OBJ EST.
             * Se procede a obtener los valores para el nivel de Obj. Est, apartir de los valores de las Obj Ope.
             */
            $Programacion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
            $Ejecucion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
            $Eficacia = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
                            
            //Se procede a consultar el programa activo a fin de obtener el Obj. Est. asociado.
            $consulta = 'SELECT idObjEst FROM catObjOpe WHERE idObjOpe='.$idObjOpe.' AND Status=0';
            $dsObjOpe = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegObjOpe = @mysqli_fetch_array($dsObjOpe,MYSQLI_ASSOC);
                            
            if($RegObjOpe)
                {
                    //Se obtiene el id del Obj Est, para procesar sus Obj Ope asociados.
                    $idObjEst = $RegObjOpe['idObjEst'];
                    }
                            
            //Se procede a obtener los Obj Ope asociados al Obj. Est. adquirido.
            $consulta = 'SELECT idObjOpe FROM catObjOpe WHERE idObjEst='.$idObjEst.' AND Status=0';
            $dsObjOpe = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegObjOpe = @mysqli_fetch_array($dsObjOpe,MYSQLI_ASSOC);
                            
            $countObjOpe = 0; //Se inicializa el conteo de Obj Ope.
                            
            while($RegObjOpe)
                {
                    /*
                     * Se recorren todos los Obj Ope bajo el Obj Est.
                     */
                            
                    //Se procede a recoger los datos almacenados en la programacion.
                    $consulta = 'SELECT *FROM opProgOO WHERE idObjOpe='.$RegObjOpe['idObjOpe'].' AND Status=0';
                    $dsProgOO = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegProgOO = @mysqli_fetch_array($dsProgOO,MYSQLI_ASSOC);
                            
                    //Se procede a recoger los datos almacenados en la ejecucion.
                    $consulta = 'SELECT *FROM opEjecOO WHERE idObjOpe='.$RegObjOpe['idObjOpe'].' AND Status=0';
                    $dsEjecOO = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $RegEjecOO = @mysqli_fetch_array($dsEjecOO,MYSQLI_ASSOC);
                            
                    while($RegProgOO)
                        {
                            //Recorrido secuencial sobre la colección de tuplas para obtener los valores de Programación.
                            
                            for($cont = 1; $cont <= 12; $cont++)
                                {
                                    //Se obtiene el porcentaje correspondiente a la programación del i-esimo mes.
                                    $Programacion[$cont-1] += $RegProgOO[obtainMes($cont)];
                                    $Ejecucion[$cont-1] += $RegEjecOO[obtainMes($cont)];
                                    }
                            
                            $RegEjecOO = @mysqli_fetch_array($dsEjecOO,MYSQLI_ASSOC);
                            $RegProgOO = @mysqli_fetch_array($dsProgOO,MYSQLI_ASSOC);
                            }
                            
                            $countObjOpe += 1;
                            $RegObjOpe = @mysqli_fetch_array($dsObjOpe,MYSQLI_ASSOC);
                            }
                            
                    for($cont = 0; $cont < 12; $cont++)
                        {
                            //Se corrige los valores de programación acorde a la cantidad de Obj Ope computados.
                            $Programacion[$cont] = $Programacion[$cont]/$countObjOpe;
                            $Ejecucion[$cont] = $Ejecucion[$cont]/$countObjOpe;
                            
                            if($Programacion[$cont] != 0)
                                {
                                    //En caso de existir programación, se procesa con normalidad.
                                    $Eficacia[$cont] = ($Ejecucion[$cont]/$Programacion[$cont])*100.00;
                                    }
                            else
                                {
                                    //En caso de ser programacion igual a cero, se procesa como una sobrecarga. 
                                    $Eficacia[$cont] = $Ejecucion[$cont]*100;
                                    }
                            
                            //Se procede a actualizar el registro de programacion a nivel del Obj. Est.
                            $consulta = 'UPDATE opProgOE SET '.obtainMes($cont+1).'=\''.$Programacion[$cont].'\' WHERE idObjEst='.$idObjEst.' AND Status=0'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta
                            
                            //Se procede a actualizar el registro de ejecucion a nivel del Obj. Est.
                            $consulta = 'UPDATE opEjecOE SET '.obtainMes($cont+1).'=\''.$Ejecucion[$cont].'\' WHERE idObjEst='.$idObjEst.' AND Status=0'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta

                            //Se procede a actualizar el registro de eficacias a nivel del Obj. Est.
                            $consulta = 'UPDATE opEficOE SET '.obtainMes($cont+1).'=\''.$Eficacia[$cont].'\' WHERE idObjEst='.$idObjEst.' AND Status=0'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta                            
                            }                                                                                                                                                                                                                                         
            }

    function obtainMes($Mes)
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
            
    function updt_opEjecAct($Mes)
        {
            /*
             * Esta función establece la actualizacion del registro de ejecuciones sobre la actividad, en
             * el bloque que corresponde al mes seleccionado.
             */
            global $username, $password, $servername, $dbname;
            global $idActividad;
            
            $cantTotal = 0;            
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            //Se procede a recoger los datos almacenados en la ejecucion.
            $consulta = 'SELECT Cantidad FROM opEjecuciones WHERE idActividad='.$idActividad.' AND Mes='.$Mes.' AND Status=0';
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
            
            while($Registro)
                {
                    //Mientras existan registros computables, se procede a la sumarización.
                    $cantTotal+= $Registro['Cantidad'];
                    $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                    }                                
                                
            //Se procede a actualizar el registro de ejecuciones a nivel de la actividad.
            $consulta = 'UPDATE opEjecAct SET '.obtainMes($Mes).'=\''.$cantTotal.'\' WHERE idActividad='.$idActividad; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                                
            }
                                    
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
    //Obteniendo el mes al que corresponde la ejecucion para borrar, a fin
    //de ejecutar la corrección en la planeación.
    $consulta= 'SELECT Mes FROM opEjecuciones WHERE idEjecucion='.$parametro; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
    $idMes = $Registro['Mes'];
    
    //Borrando el registro de ejecución.
    $consulta= 'UPDATE opEjecuciones SET Status=1 WHERE idEjecucion='.$parametro; //Se establece el modelo de consulta de datos.
    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
    
    updt_opEjecAct($idMes); //Se llama la función de actualización de ejecucion a nivel de actividad.
    updt_opEjecActAnual(); //Llamada a la función del calculo de programación.
        
    $_GET['view'] = 3; //Se establece la variable de control de visualización. 
    include_once($_SERVER['DOCUMENT_ROOT'].'/phoenix/php/frontend/actividad/opActividad.php');
    ?>