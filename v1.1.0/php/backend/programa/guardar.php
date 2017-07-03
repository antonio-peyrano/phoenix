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
    
    $cntrlVar=0;
    
    if(isset($_GET['id'])&&isset($_GET['idobjest'])&&isset($_GET['idobjope'])&&isset($_GET['idestope'])&&isset($_GET['nomenclatura'])&&isset($_GET['programa'])&&isset($_GET['monto'])&&isset($_GET['identidad'])&&isset($_GET['idresponsable'])&&isset($_GET['idsubalterno'])&&isset($_GET['idprocesos'])&&isset($_GET['nonidprocesos'])&&isset($_GET['periodo'])&&isset($_GET['status']))
        {
            /*
             * Si las variables primarias de almacenamiento se obtuvieron correctamente de la URL,
             * se procede a asignarlas en el bloque previo de ejecucion.
             */            
            $idPrograma = $_GET['id'];
            $idObjEst = $_GET['idobjest'];
            $idObjOpe = $_GET['idobjope'];
            $idEstOpe = $_GET['idestope'];
            $Nomenclatura = $_GET['nomenclatura'];
            $Programa = $_GET['programa'];
            $Monto = $_GET['monto'];
            $idEntidad = $_GET['identidad'];
            $idResponsable = $_GET['idresponsable'];
            $idSubalterno = $_GET['idsubalterno'];
            $idProceso = $_GET['idprocesos'];
            $nonidProceso = $_GET['nonidprocesos'];
            $Periodo = $_GET['periodo'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)            
            }
        
    function calcularNomenclatura($parametro)
        {
            /*
             * Esta función establece el calculo de la nomenclatura del Programa a razón
             * de los elementos existentes asociados a la EstOpe.
             */
            global $username, $password, $servername, $dbname, $idObjEst, $idObjOpe, $Nomenclatura;
    
            $objAux = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
            $consulta = "SELECT *FROM opProgramas WHERE idEstOpe =".$parametro;
            $dataset = $objAux -> conectar($consulta);
            $RowCount = mysqli_num_rows($dataset);
    
            $consulta = "SELECT Nomenclatura FROM catEstOpe WHERE idEstOpe =".$parametro;
            $dataset = $objAux -> conectar($consulta);
            $RowObjOpe = @mysql_fetch_array($dataset,MYSQLI_ASSOC);
    
            $Nomenclatura = $RowObjOpe['Nomenclatura'].'.'.($RowCount + 1);
            }
        
    function existencias($idRegPro, $idRegProg)
        {
            /*
             * Esta función establece la busqueda para determinar si un registro ya existe en el sistema
             * con las condiciones proporcionadas.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = 'SELECT *FROM relProgPro WHERE idPrograma='.$idRegProg.' AND idProceso='.$idRegPro; //Se establece el modelo de consulta de datos.
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

    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            $temp = explode('%',$idProceso); //Aqui se convierte el vector en un arreglo con los id seleccionados.
            $nontemp = explode('%',$nonidProceso); //Aqui se convierte el vector en un arreglo con los id no seleccionados.
            
            if($idPrograma != null)
                {
                    /*
                     * En caso que la acción ejecutada sea una edición.
                     */
                    $consulta = 'UPDATE opProgramas SET Programa=\''.$Programa.'\', idObjEst=\''.$idObjEst.'\', idObjOpe=\''.$idObjOpe.'\', idEstOpe=\''.$idEstOpe.'\', Nomenclatura=\''.$Nomenclatura.'\', Monto=\''.$Monto.'\', idEntidad=\''.$idEntidad.'\', idResponsable=\''.$idResponsable.'\', idSubalterno=\''.$idSubalterno.'\', Status=\''.$Status.'\' WHERE idPrograma='.$idPrograma; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crean los elementos de la relacion.
                    for($conteo=1; $conteo < count($temp); $conteo++)
                        {
                            if(!existencias($temp[$conteo], $idPrograma))
                                {
                                    /*
                                     * En caso de no existir referencias previas, se crean en la entidad de las relaciones.
                                     */
                                    $consulta = 'INSERT INTO relProgPro (idProceso, idPrograma) VALUES ('.'\''.$temp[$conteo].'\',\''.$idPrograma.'\')'; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            else
                                {
                                    /*
                                     * En caso de existir referencias previas, considerando que la relación fue eliminada previamente.
                                     */
                                    $consulta = 'UPDATE relProgPro SET Status= 0 WHERE idPrograma='.$idPrograma.' AND idProceso='.$temp[$conteo]; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            }
            
                    //Se eliminan los elementos de la relacion si fueron desmarcados.
                    for($conteo=1; $conteo < count($nontemp); $conteo++)
                        {
                            if(existencias($nontemp[$conteo], $idPrograma))
                                {
                                    /*
                                     * En caso de existir referencias previas, se eliminan en la entidad de las relaciones.
                                     */
                                    $consulta = 'UPDATE relProgPro SET Status= 1 WHERE idPrograma='.$idPrograma.' AND idProceso='.$nontemp[$conteo]; //Se establece el modelo de consulta de datos.
                                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                    }
                            }
                    }
            else
                {
                    /*
                     * En caso que la acción ejecutada sea una creación.
                     */
                    calcularNomenclatura($idEstOpe);
                    $consulta = 'INSERT INTO opProgramas (idObjEst, idObjOpe, idEstOpe, Nomenclatura, Programa, Monto, idEntidad, idResponsable, idSubalterno, Periodo) VALUES ('.'\''.$idObjEst.'\',\''.$idObjOpe.'\',\''.$idEstOpe.'\',\''.$Nomenclatura.'\',\''.$Programa.'\',\''.$Monto.'\',\''.$idEntidad.'\',\''.$idResponsable.'\',\''.$idSubalterno.'\',\''.$Periodo.'\')'; //Se establece el modelo de consulta de datos.
            
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se busca el Proceso creado para obtener su id.
                    $consulta = 'SELECT *FROM opProgramas WHERE Programa LIKE \'%'.$Programa.'%\''; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $Registro = @mysql_fetch_array($dataset,MYSQLI_ASSOC);
            
                    //Se crean los elementos de la relacion.
                    for($conteo=1; $conteo < count($temp); $conteo++)
                        {
                            $consulta = 'INSERT INTO relProgPro (idProceso, idPrograma) VALUES ('.'\''.$temp[$conteo].'\',\''.$Registro['idPrograma'].'\')'; //Se establece el modelo de consulta de datos.
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                            }
            
                    //Se crea la tupla vacia para el registro de valores programados.
                    $consulta = 'INSERT INTO opProgPro(idPrograma, Periodo) VALUES ('.'\''.$Registro['idPrograma'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crea la tupla vacia para el registro de valores ejecutados.
                    $consulta = 'INSERT INTO opEjecPro(idPrograma, Periodo) VALUES ('.'\''.$Registro['idPrograma'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crea la tupla vacia para el registro de valores de las eficacias.
                    $consulta = 'INSERT INTO opEficPro(idPrograma, Periodo) VALUES ('.'\''.$Registro['idPrograma'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    }
                    
            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/programa/busPrograma.php");                                            
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