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
     * Este modulo sirve como pasarela de ejecución del comando guardar, cuando es ejecutado desde un formulario
     * para la edición de registro.
     */
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $cntrlVar = 0;
        
    if(isset($_GET['id'])&&isset($_GET['idproceso'])&&isset($_GET['clave'])&&isset($_GET['nedicion'])&&isset($_GET['fechaedicion'])&&isset($_GET['actividades'])&&isset($_GET['responsable'])&&isset($_GET['misionproceso'])&&isset($_GET['entrada'])&&isset($_GET['salida'])&&isset($_GET['relprocesos'])&&isset($_GET['necrecursos'])&&isset($_GET['regarchivos'])&&isset($_GET['docaplicables'])&&isset($_GET['idindicadores'])&&isset($_GET['nonidindicadores'])&&isset($_GET['status']))
        {
            /*
             * En caso de no ocurrir un error con el paso de variables por
             * URL, se procede a su asignacion.
             */            
            $idFicha = $_GET['id'];
            $idProceso = $_GET['idproceso'];
            $Clave = $_GET['clave'];
            $nEdicion = $_GET['nedicion'];
            $FechaEdicion = $_GET['fechaedicion'];
            $Actividades = $_GET['actividades'];
            $Responsable = $_GET['responsable'];
            $MisionProceso = $_GET['misionproceso'];
            $Entrada = $_GET['entrada'];
            $Salida = $_GET['salida'];
            $relProcesos = $_GET['relprocesos'];
            $necRecursos = $_GET['necrecursos'];
            $regArchivos = $_GET['regarchivos'];
            $docAplicables = $_GET['docaplicables'];
            $idIndicador = $_GET['idindicadores'];
            $nonidIndicador = $_GET['nonidindicadores'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)                        
            }
                
    function calcularClave()
        {
            /*
             * Esta función establece el calculo de la clave de la ficha a razón
             * de los elementos existentes.
             */
            global $username, $password, $servername, $dbname, $idObjEst, $idObjOpe, $Clave, $periodo;
    
            $objAux = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
            $consulta = "SELECT *FROM opFichasProcesos";
            $dataset = $objAux -> conectar($consulta);
            $RowCount = mysqli_num_rows($dataset);
    
            if($Clave=="")
                {
                    //Si se trata de un nuevo registro, se genera una clave artificial nueva.
                    $Clave = 'FSMP'.'-'.$periodo.'-'.($RowCount + 1);
                    }            
            }
        
    function existencias($idRegInd, $idRegFicha)
        {
            /*
             * Esta función establece la busqueda para determinar si un registro ya existe en el sistema
             * con las condiciones proporcionadas.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT *FROM relIndFicha WHERE idFicha='.$idRegFicha.' AND idIndicador='.$idRegInd; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $Registro = @mysql_fetch_array($dataset,MYSQLI_ASSOC);
    
            if(!$Registro)
                {
                    /*
                     * En caso que el muestreo no arroje datos en la consulta.
                     */
                    return false;
                    }
    
            return true;
            }
        
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */
            
            $temp = explode('%',$idIndicador); //Aqui se convierte el vector en un arreglo con los id seleccionados.
            $nontemp = explode('%',$nonidIndicador); //Aqui se convierte el vector en un arreglo con los id no seleccionados.

            /*
             * Se crea un patron de referencia de datos correspondientes a la fecha y hora
             * para el registro.
             */
            $now = time(); //Se obtiene la referencia del tiempo actual del servidor.
            date_default_timezone_set("America/Mexico_City"); //Se establece el perfil del uso horario.
            $periodo = date("Y",$now); //Se obtiene la referencia del año.
            $FechaCreacion = date("d/m/Y",$now).' '.date("h:i:sa",$now); //Se obtiene la referencia compuesta de fecha y hora.

            if($idFicha != null)
                {
                    /*
                     * En caso que la acción ejecutada sea una edición.
                     */
                    $consulta = 'UPDATE opFichasProcesos SET Clave=\''.$Clave.'\', idProceso=\''.$idProceso.'\', nEdicion=\''.$nEdicion.'\', FechaEdicion=\''.$FechaEdicion.'\', Actividades=\''.$Actividades.'\', Responsable=\''.$Responsable.'\', MisionProceso=\''.$MisionProceso.'\', Entrada=\''.$Entrada.'\', Salida=\''.$Salida.'\', relProcesos=\''.$relProcesos.'\', necRecursos=\''.$necRecursos.'\', regArchivos=\''.$regArchivos.'\', docAplicables=\''.$docAplicables.'\', Status=\''.$Status.'\' WHERE idFicha='.$idFicha; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crean los elementos de la relacion.
                    for($conteo=1; $conteo < count($temp); $conteo++)
                        {
                            if(!existencias($temp[$conteo], $idFicha))
                                {
                                    /*
                                     * En caso de no existir referencias previas, se crean en la entidad de las relaciones.
                                     */
                                    $consulta = 'INSERT INTO relIndFicha (idIndicador, idFicha) VALUES ('.'\''.$temp[$conteo].'\',\''.$idFicha.'\')'; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            else
                                {
                                    /*
                                     * En caso de existir referencias previas, considerando que la relación fue eliminada previamente.
                                     */
                                    $consulta = 'UPDATE relIndFicha SET Status= 0 WHERE idFicha='.$idFicha.' AND idIndicador='.$temp[$conteo]; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            }
            
                    //Se eliminan los elementos de la relacion si fueron desmarcados.
                    for($conteo=1; $conteo < count($nontemp); $conteo++)
                        {
                            if(existencias($nontemp[$conteo], $idFicha))
                                {
                                    /*
                                     * En caso de existir referencias previas, se eliminan en la entidad de las relaciones.
                                     */
                                    $consulta = 'UPDATE relIndFicha SET Status= 1 WHERE idFicha='.$idFicha.' AND idIndicador='.$nontemp[$conteo]; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            }
                    }
            else
                {
                    /*
                     * En caso que la acción ejecutada sea una creación.
                     */
                    calcularClave();
                    $consulta = 'INSERT INTO opFichasProcesos (idProceso, Clave, nEdicion, FechaCreacion, FechaEdicion, Actividades, Responsable, MisionProceso, Entrada, Salida, relProcesos, necRecursos, regArchivos, docAplicables) VALUES ('.'\''.$idProceso.'\',\''.$Clave.'\',\''.$nEdicion.'\',CAST(\''.$FechaCreacion.'\' AS char), CAST(\''.$FechaEdicion.'\' AS char),\''.$Actividades.'\',\''.$Responsable.'\',\''.$MisionProceso.'\',\''.$Entrada.'\',\''.$Salida.'\',\''.$relProcesos.'\',\''.$necRecursos.'\',\''.$regArchivos.'\',\''.$docAplicables.'\')'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se busca la ficha creada para obtener su id.
                    $consulta = 'SELECT *FROM opFichasProcesos WHERE Clave LIKE \'%'.$Clave.'%\''; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $Registro = @mysql_fetch_array($dataset,MYSQLI_ASSOC);
            
                    //Se crean los elementos de la relacion.
                    for($conteo=1; $conteo < count($temp); $conteo++)
                        {
                            $consulta = 'INSERT INTO relIndFicha (idIndicador, idFicha) VALUES ('.'\''.$temp[$conteo].'\',\''.$Registro['idFicha'].'\')'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                            }
                    }
            
            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/fichas/busFichaProceso.php");            
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