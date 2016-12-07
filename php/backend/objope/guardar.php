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
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.

    global $username, $password, $servername, $dbname;
    
    $cntrlVar=0;
    
    if(isset($_GET['id'])&&isset($_GET['idobjest'])&&isset($_GET['objope'])&&isset($_GET['nomenclatura'])&&isset($_GET['periodo'])&&isset($_GET['status']))
        {
            /*
             * Si las variables primarias de almacenamiento se obtuvieron correctamente de la URL,
             * se procede a asignarlas en el bloque previo de ejecucion.
             */            
            $idObjOpe = $_GET['id'];
            $idObjEst = $_GET['idobjest'];
            $ObjOpe = $_GET['objope'];
            $Nomenclatura = $_GET['nomenclatura'];
            $Periodo = $_GET['periodo'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)                        
            }

    function calcularNomenclatura($parametro)
        {
            /*
             * Esta función establece el calculo de la nomenclatura del ObjOpe a razón
             * de los elementos existentes asociados al ObjEst.
             */
            global $username, $password, $servername, $dbname, $idObjEst, $Nomenclatura;
            $objAux = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = "SELECT *FROM catObjOpe WHERE idObjEst =".$parametro;
            $dataset = $objAux -> conectar($consulta);
            $RowCount = mysql_num_rows($dataset);
            $Nomenclatura = $idObjEst.'.'.($RowCount + 1);
            }
                    
    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */    
            if($idObjOpe != null)
                {
                    /*
                     * En caso que la acción ejecutada sea una edición.
                     */
                    $consulta = 'UPDATE catObjOpe SET Nomenclatura=\''.$Nomenclatura.'\', ObjOpe=\''.$ObjOpe.'\', idObjEst=\''.$idObjEst.'\', Periodo=\''.$Periodo.'\', Status=\''.$Status.'\' WHERE idObjOpe='.$idObjOpe; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    }
            else
                {
                    /*
                     * En caso que la acción ejecutada sea una creación.
                     */
                    calcularNomenclatura($idObjEst);
                    $consulta = 'INSERT INTO catObjOpe (idObjEst, Nomenclatura, ObjOpe, Periodo) VALUES ('.'\''.$idObjEst.'\',\''.$Nomenclatura.'\',\''.$ObjOpe.'\', \''.$Periodo.'\')'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se busca el la Est Ope para obtener su id.
                    $consulta = 'SELECT *FROM catObjOpe WHERE ObjOpe LIKE \'%'.$ObjOpe.'%\''; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $Registro = @mysql_fetch_array($dataset, MYSQL_ASSOC);
            
                    //Se crea la tupla vacia para el registro de valores programados.
                    $consulta = 'INSERT INTO opProgOO(idObjOpe, Periodo) VALUES ('.'\''.$Registro['idObjOpe'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crea la tupla vacia para el registro de valores ejecutados.
                    $consulta = 'INSERT INTO opEjecOO(idObjOpe, Periodo) VALUES ('.'\''.$Registro['idObjOpe'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crea la tupla vacia para el registro de valores de las eficacias.
                    $consulta = 'INSERT INTO opEficOO(idObjOpe, Periodo) VALUES ('.'\''.$Registro['idObjOpe'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                        
                    }
                    
            include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/objope/busObjOpe.php");
            }
    else
        {
            /*
             * En caso de ocurrir un error con la operatividad del sistema,
             * se despliega un mensaje al usuario.
             */
            include_once($_SERVER['DOCUMENT_ROOT']."/micrositio/php/frontend/main/errorSistema.php");
            }            
    ?>