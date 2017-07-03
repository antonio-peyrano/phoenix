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
    
    if(isset($_GET['id'])&&isset($_GET['idobjest'])&&isset($_GET['idobjope'])&&isset($_GET['estope'])&&isset($_GET['nomenclatura'])&&isset($_GET['periodo'])&&isset($_GET['status']))
        {
            /*
             * En caso de no ocurrir un error con el paso de variables por
             * URL, se procede a su asignacion.
             */            
            $idEstOpe = $_GET['id'];
            $idObjEst = $_GET['idobjest'];
            $idObjOpe = $_GET['idobjope'];
            $EstOpe = $_GET['estope'];
            $Nomenclatura = $_GET['nomenclatura'];
            $Periodo = $_GET['periodo'];
            $Status = $_GET['status'];
            $cntrlVar=1; //Valor de control (1=Asignacion correcta /0=Asignacion incorrecta)
            }


    function calcularNomenclatura($parametro)
        {
            /*
             * Esta función establece el calculo de la nomenclatura del EstOpe a razón
             * de los elementos existentes asociados al ObjOpe.
             */
            global $username, $password, $servername, $dbname, $idObjEst, $idObjOpe, $Nomenclatura;
            
            $objAux = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            
            $consulta = "SELECT *FROM catEstOpe WHERE idObjOpe =".$parametro;
            $dataset = $objAux -> conectar($consulta);            
            $RowCount = mysqli_num_rows($dataset);
            
            $consulta = "SELECT Nomenclatura FROM catObjOpe WHERE idObjOpe =".$parametro;
            $dataset = $objAux -> conectar($consulta);
            $RowObjOpe = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
            
            $Nomenclatura = $RowObjOpe['Nomenclatura'].'.'.($RowCount + 1);
            }


    if($cntrlVar==1)
        {
            /*
             * Si la obtención de datos por medio del URL no arrojo errores de valor nulo,
             * se procede a la ejecucion del bloque de almacenamiento de datos.
             */            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
    
            if($idEstOpe != null)
                {
                    /*
                     * En caso que la acción ejecutada sea una edición.
                     */
                    $consulta = 'UPDATE catEstOpe SET Nomenclatura=\''.$Nomenclatura.'\', EstOpe=\''.$EstOpe.'\', idObjEst=\''.$idObjEst.'\', idObjOpe=\''.$idObjOpe.'\', Periodo=\''.$Periodo.'\', Status=\''.$Status.'\' WHERE idEstOpe='.$idEstOpe; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    }
            else
                {
                    /*
                     * En caso que la acción ejecutada sea una creación.
                     */
                    calcularNomenclatura($idObjOpe);
                    $consulta = 'INSERT INTO catEstOpe (idObjEst, idObjOpe, Nomenclatura, EstOpe, Periodo) VALUES ('.'\''.$idObjEst.'\',\''.$idObjOpe.'\',\''.$Nomenclatura.'\',\''.$EstOpe.'\', \''.$Periodo.'\')'; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se busca el la Est Ope para obtener su id.
                    $consulta = 'SELECT *FROM catEstOpe WHERE EstOpe LIKE \'%'.$EstOpe.'%\''; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                    $Registro = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
            
                    //Se crea la tupla vacia para el registro de valores programados.
                    $consulta = 'INSERT INTO opProgEst(idEstOpe, Periodo) VALUES ('.'\''.$Registro['idEstOpe'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
                    //Se crea la tupla vacia para el registro de valores ejecutados.
                    $consulta = 'INSERT INTO opEjecEst(idEstOpe, Periodo) VALUES ('.'\''.$Registro['idEstOpe'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.

                    //Se crea la tupla vacia para el registro de valores de las eficacias.
                    $consulta = 'INSERT INTO opEficEst(idEstOpe, Periodo) VALUES ('.'\''.$Registro['idEstOpe'].'\',\''.$Periodo.'\')';
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.                        
                    }
                   
            include_once($_SERVER['DOCUMENT_ROOT']."/phoenix/php/frontend/estope/busEstOpe.php");
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