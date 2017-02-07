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

    $Optimo = 0; //El valor de limite superior para la eficacia.
    $Tolerable = 0; //El valor de limite inferior para la eficacia.
    $Periodo = ''; //El valor del periodo a visualizar.
    $rowBanderas = '';
    
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
    
    function cargarBanderas($parametro, $mes)
        {
            /*
             * Esta función carga la parte grafica que corresponde a las banderas de desempeÃ±o.
             */
            global $Periodo, $Optimo, $Tolerable, $rowBanderas;
            
            if($parametro>=$Optimo)
                {
                    //Si el parametro recibido esta en el rango de medición optima.
                    $rowBanderas.='<td><center><img id="optimo_'.$mes.'"align= "middle" src= "./img/banderas/optimo.png" width= "25" height= "25" alt= "Optimo" data-toggle="tooltip" title="Eficacia >='.$Optimo.'%"/></center></td>';
                    } 
                    
            if(($parametro>=$Tolerable)&&($parametro<$Optimo))
                {
                    //Si el parametro recibido esta dentro del rango tolerable.
                    $rowBanderas.='<td><center><img id="riesgo_'.$mes.'"align= "middle" src= "./img/banderas/riesgo.png" width= "25" height= "25" alt= "Riesgo" data-toggle="tooltip" title="Eficacia >='.$Tolerable.'%"/></center></td>';                    
                    }
                    
            if($parametro<$Tolerable)
                {
                    //Si el parametro recibido esta por debajo de lo tolerable.
                    $rowBanderas.='<td><center><img id="falla_'.$mes.'"align= "middle" src= "./img/banderas/falla.png" width= "25" height= "25" alt= "Falla" data-toggle="tooltip" title="Eficacia <'.$Tolerable.'%"/></center></td>';                    
                    }             
            }
                
    if(isset($_GET['view']))
        {
            /*
             * Si se declaro en la url el control de visualización.
             */
            $cntview = $_GET['view'];
            }
                
    $now = time();
    $periodo = date("Y",$now);
    $habcampos = 'disabled= "disabled"';

    function obtenerMes($Mes)
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
                        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM opProgramas WHERE idPrograma='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
            
    $Registro = @mysql_fetch_array(cargarRegistro($parametro), MYSQL_ASSOC);//Llamada a la funciï¿½n de carga de registro de usuario.

    function controlVisual($idRegistro)
        {
            /*
             * Esta función controla los botones que deberan verse en la pantalla deacuerdo con la acción solicitada por el
             * usuario en la ventana previa. Si es una edición, los botones borrar y guardar deben verse. Si es una creación
             * solo el boton guardar debe visualizarse.
             */
            global $cntview;
    
            if($idRegistro == -1)
                {
                    //En caso que la acción corresponda a la creación de un nuevo registro.
                    echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/programa/busPrograma.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="guardarPrograma(\'./php/backend/programa/guardar.php\',\'?id=\'+document.getElementById(\'idPrograma\').value.toString()+\'&idobjest=\'+document.getElementById(\'idObjEst\').value.toString()+\'&idobjope=\'+document.getElementById(\'idObjOpe\').value.toString()+\'&idestope=\'+document.getElementById(\'idEstOpe\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&programa=\'+document.getElementById(\'Programa\').value.toString()+\'&monto=\'+document.getElementById(\'Monto\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&idresponsable=\'+document.getElementById(\'idResponsable\').value.toString()+\'&idsubalterno=\'+document.getElementById(\'idSubalterno\').value.toString()+\'&idprocesos=\'+procesosid()+\'&nonidprocesos=\'+nonprocesosid()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a></td></tr>';
                    }
            else
                {
                    if(($cntview == 1)||($cntview == 3))
                        {
                            //En caso de procesarse como una acción de visualización.
                            echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/programa/busPrograma.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/></a><a href="#" onclick="cargar(\'./php/backend/programa/borrar.php\',\'?id=\'+document.getElementById(\'idPrograma\').value.toString(),\'escritorio\');"><img align= "right" src= "./img/grids/erase.png" width= "25" height= "25" alt= "Borrar" id= "btnBorrar"/></a><a href="#" onclick="guardarPrograma(\'./php/backend/programa/guardar.php\',\'?id=\'+document.getElementById(\'idPrograma\').value.toString()+\'&idobjest=\'+document.getElementById(\'idObjEst\').value.toString()+\'&idobjope=\'+document.getElementById(\'idObjOpe\').value.toString()+\'&idestope=\'+document.getElementById(\'idEstOpe\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&programa=\'+document.getElementById(\'Programa\').value.toString()+\'&monto=\'+document.getElementById(\'Monto\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&idresponsable=\'+document.getElementById(\'idResponsable\').value.toString()+\'&idsubalterno=\'+document.getElementById(\'idSubalterno\').value.toString()+\'&idprocesos=\'+procesosid()+\'&nonidprocesos=\'+nonprocesosid()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habPrograma();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                            }
                    else
                        {
                            if($cntview == 0)
                                {
                                    //En caso que la acción corresponda a la edición de un registro.
                                    echo '<tr class="dgHeader" style="text-align:right"><td colspan= "2"><a href="#" onclick="cargar(\'./php/frontend/programa/busPrograma.php\',\'\',\'escritorio\');"><img align= "right" src= "./img/grids/volver.png" width= "25" height= "25" alt= "Volver" id= "btnVolver"/><a href="#" onclick="guardarPrograma(\'./php/backend/programa/guardar.php\',\'?id=\'+document.getElementById(\'idPrograma\').value.toString()+\'&idobjest=\'+document.getElementById(\'idObjEst\').value.toString()+\'&idobjope=\'+document.getElementById(\'idObjOpe\').value.toString()+\'&idestope=\'+document.getElementById(\'idEstOpe\').value.toString()+\'&nomenclatura=\'+document.getElementById(\'Nomenclatura\').value.toString()+\'&programa=\'+document.getElementById(\'Programa\').value.toString()+\'&monto=\'+document.getElementById(\'Monto\').value.toString()+\'&identidad=\'+document.getElementById(\'idEntidad\').value.toString()+\'&idresponsable=\'+document.getElementById(\'idResponsable\').value.toString()+\'&idsubalterno=\'+document.getElementById(\'idSubalterno\').value.toString()+\'&idprocesos=\'+procesosid()+\'&nonidprocesos=\'+nonprocesosid()+\'&periodo=\'+document.getElementById(\'Periodo\').value.toString()+\'&status=\'+document.getElementById(\'Status\').value.toString());"><img align= "right" src= "./img/grids/save.png" width= "25" height= "25" alt= "Guardar" id= "btnGuardar"/></a><a href="#" onclick="habPrograma();"><img align= "right" src= "./img/grids/edit.png" width= "25" height= "25" alt= "Editar" id= "btnEditar"/></a></td></tr>';
                                    }
                            }
                    }
            }
        
    function cargarObjEst()
        {
            /*
             * Esta función establece la carga del conjunto de registros de ObjEst.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idObjEst, CONCAT(Nomenclatura,\' \',ObjEst) AS CObjEst FROM catObjEst WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
        
    function cargarObjOpe($parametro)
        {
            /*
             * Esta función establece la carga del conjunto de registros de ObjOpe.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idObjOpe, CONCAT(Nomenclatura,\' \',ObjOpe) AS CObjOpe FROM catObjOpe WHERE Status=0 AND idObjEst='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
       
    function constructorcb($parametro)
        {
            /*
             * Esta función establece los parametros de carga del combobox de obj ope cuando
             * se ejecuta un proceso de edición.
             */
            global $habcampos, $Registro;
    
            $subconsulta = cargarObjOpe($parametro);
    
            echo' <select name= "idObjOpe" id= "idObjOpe" '.$habcampos.' value= "'.$Registro['idObjOpe'].'">
                  <option value=-1>Seleccione</option>';
            
            $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
            
            while($RegNiveles)
                {
                    if($RegNiveles['idObjOpe']==$Registro['idObjOpe'])
                        {
                            //En caso que el valor almacenado coincida con uno de los existentes en lista.
                            echo '          <option value='.$RegNiveles['idObjOpe'].' selected="selected">'.$RegNiveles['CObjOpe'].'</option>';
                            }
                    else
                        {
                            //En caso contrario se carga la etiqueta por default.
                            echo '          <option value='.$RegNiveles['idObjOpe'].'>'.$RegNiveles['CObjOpe'].'</option>';
                            }
                    $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                    }
                    
            echo' </select>';
            }

    function cargarEstOpe($parametro)
        {
            /*
             * Esta función establece la carga del conjunto de registros de ObjOpe.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEstOpe, CONCAT(Nomenclatura,\' \',EstOpe) AS CEstOpe FROM catEstOpe WHERE Status=0 AND idObjOpe='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }            

    function constructorcbEstOpe($parametro)
        {
            /*
             * Esta función establece los parametros de carga del combobox de Est ope cuando
             * se ejecuta un proceso de edición.
             */
            global $habcampos, $Registro;
            
            $subconsulta = cargarEstOpe($parametro);
            
            echo' <select name= "idEstOpe" id= "idEstOpe" '.$habcampos.' value= "'.$Registro['idEstOpe'].'">
                  <option value=-1>Seleccione</option>';
            
            $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
            
            while($RegNiveles)
                {
                    if($RegNiveles['idEstOpe']==$Registro['idEstOpe'])
                        {
                            //En caso que el valor almacenado coincida con uno de los existentes en lista.
                            echo '          <option value='.$RegNiveles['idEstOpe'].' selected="selected">'.$RegNiveles['CEstOpe'].'</option>';
                            }
                    else
                        {
                            //En caso contrario se carga la etiqueta por default.
                            echo '          <option value='.$RegNiveles['idEstOpe'].'>'.$RegNiveles['CEstOpe'].'</option>';
                            }
                    $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                    }
                    
                    echo' </select>';
            }

    function cargarEmpleado($parametro)
        {
            /*
             * Esta función establece la carga del conjunto de registros de Empleados.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEmpleado, CONCAT(Nombre,\' \',Paterno,\' \',Materno) AS Empleado FROM opEmpleados WHERE Status=0 AND idEntidad='.$parametro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
                        
    function constructorcbResponsable($parametro)
        {
            /*
             * Esta función establece los parametros de carga del combobox de Responsable cuando
             * se ejecuta un proceso de edición.
             */
             global $habcampos, $Registro;
            
             $subconsulta = cargarEmpleado($parametro);
            
             echo' <select name= "idResponsable" id= "idResponsable" '.$habcampos.' value= "'.$Registro['idResponsable'].'">
                   <option value=-1>Seleccione</option>';
             
                $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                
                while($RegNiveles)
                    {
                        if($RegNiveles['idEmpleado']==$Registro['idResponsable'])
                            {
                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
                                echo '          <option value='.$RegNiveles['idEmpleado'].' selected="selected">'.$RegNiveles['Empleado'].'</option>';
                                }
                        else
                            {
                                //En caso contrario se carga la etiqueta por default.
                                echo '          <option value='.$RegNiveles['idEmpleado'].'>'.$RegNiveles['Empleado'].'</option>';
                                }
                        $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                        }
                echo' </select>';
            }

    function constructorcbSubalterno($parametro)
        {
            /*
             * Esta función establece los parametros de carga del combobox de Subalterno cuando
             * se ejecuta un proceso de edición.
             */
             global $habcampos, $Registro;
            
             $subconsulta = cargarEmpleado($parametro);
            
             echo' <select name= "idSubalterno" id= "idSubalterno" '.$habcampos.' value= "'.$Registro['idSubalterno'].'">
             <option value=-1>Seleccione</option>';
             
             $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
             
                while($RegNiveles)
                    {
                        if($RegNiveles['idEmpleado']==$Registro['idSubalterno'])
                            {
                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
                                echo '          <option value='.$RegNiveles['idEmpleado'].' selected="selected">'.$RegNiveles['Empleado'].'</option>';
                                }
                        else
                            {
                                //En caso contrario se carga la etiqueta por default.
                                echo '          <option value='.$RegNiveles['idEmpleado'].'>'.$RegNiveles['Empleado'].'</option>';
                                }
                        $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                        }
                        
                echo' </select>';
            }
                        
    function cargarEntidades()
        {
            /*
             * Esta función establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idEntidad, Entidad FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }

    function cargarProcesos($idRegistro, $idEntidad)
        {
            /*
             * Esta función establece la carga de un registro a partir de su identificador en la base de datos.
             */
             global $username, $password, $servername, $dbname, $habcampos;
            
             $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
             $consulta= 'SELECT *FROM (catProcesos INNER JOIN relEntPro ON relEntPro.idProceso = catProcesos.idProceso) INNER JOIN catEntidades ON catEntidades.idEntidad = relEntPro.idEntidad WHERE relEntPro.Status=0 AND relEntPro.idEntidad='.$idEntidad; //Se establece el modelo de consulta de datos.
             $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            
             if($idRegistro == -1)
                {
                    /*
                     * Si la operación solicitada es para la creación de un registro,
                     * se carga el listado sin marcar.
                     */
                    echo '<tr><td class="dgRowsaltTR" width="100px">Procesos:</td><td class="dgRowsnormTR"><div id="chkProcesos">';
                    }
             else
                {
                    /*
                     * Si la operación solicitada es para editar el registro,
                     * se carga el listado con los elementos previamente marcados.
                     */
                    echo '<tr><td class="dgRowsaltTR" width="100px">Procesos:</td><td class="dgRowsnormTR"><div id="chkProcesos">';
                    $subconsulta = 'SELECT *FROM relProgPro WHERE idPrograma='.$idRegistro.' AND Status=0'; //Se establece el modelo de consulta de datos.
                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                    $vector = "";
                    $RegNiveles = @mysql_fetch_array($subdataset, MYSQL_ASSOC);
            
                    if($RegNiveles)
                        {
                            /*
                             * Si la lectura del registro no apunta a vacio, se agrega
                             * el id al vector.
                             */
                            $vector.=$RegNiveles['idProceso'];
                            }
            
                    $RegNiveles = @mysql_fetch_array($subdataset, MYSQL_ASSOC);
            
                    while ($RegNiveles)
                        {
                            /*
                             * Se hace un recorrido sobre el dataset para crear un vector que contenga
                             * los id de las Procesoes seleccionadas por el usuario previamente.
                             */
                            $vector.=','.$RegNiveles['idProceso'];
                            $RegNiveles = @mysql_fetch_array($subdataset, MYSQL_ASSOC);
                            }
            
                    $tmparray=explode(',',$vector); //El vector resultante se convierte en un arreglo.
            
                    $RegNiveles = @mysql_fetch_array($dataset, MYSQL_ASSOC);
            
                    while($RegNiveles)
                        {
                            /*
                             * Mientras no se llegue al final de la colección, se procede a la lectura
                             * y generación del listado.
                             */
                            if(in_array($RegNiveles['idProceso'], $tmparray,true))
                                {
                                    /*
                                     * En caso de tratarse de una opción previamente seleccionada por el usuario.
                                     */
                                    echo '<br><input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" '.$habcampos.' value='.$RegNiveles['idProceso'].' checked>'.$RegNiveles['Proceso'];
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario se agrega una entrada de formato convencional.
                                     */
                                    echo '<br><input type="checkbox" class="check" id="idProceso[]" name="idProceso[]" '.$habcampos.' value='.$RegNiveles['idProceso'].'>'.$RegNiveles['Proceso'];
                                    }
            
                            $RegNiveles = @mysql_fetch_array($dataset, MYSQL_ASSOC);
                            }
                    }
            
             echo'</div></td></tr>';
             }
                        
    function constructor()
        {
            /*
             * Esta función establece los parametros para la creación de la interfaz de usuario.
             */
            
            global $Registro, $parametro, $clavecod, $habcampos, $periodo, $cntview;            
            global $username, $password, $servername, $dbname, $rowBanderas;
            
            if($Registro['idPrograma'] == null)
                {
                    //En caso que el registro sea de nueva creacion.
                    $habcampos='';
                    }
                        
            echo '  <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                            <link rel= "stylesheet" href= "./css/queryStyle.css"></style>
                            <script type="text/javascript" src= "./js/programas/jsprogramas.js"></script>
                        </head>
                        <body>
                            <div style="display:none">
                                <input type= "text" id= "pagina" value="1">
                                <input type= "text" id= "idPrograma" value="'.$Registro['idPrograma'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>                 
                            <div id= "infoProg" style= "width: 400px; "class="dgMainDiv">
                                <table class="dgTable">
                                    <tr><th colspan= "4" class= "dgHeader">Datos del Programa</th><tr>';

                                    $subconsulta = cargarObjEst();
                                    
            echo '                  <tr><td class="dgRowsaltTR" width="100px">Objetivo Estrategico:</td><td class="dgRowsnormTR"><select name= "idObjEst" id= "idObjEst" '.$habcampos.' value= "'.$Registro['idObjEst'].'">
                                    <option value=-1>Seleccione</option>';
            
                                    $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                    
                                    while($RegNiveles)
                                        {
                                            if($RegNiveles['idObjEst']==$Registro['idObjEst'])
                                                {
                                                    //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo '                                  <option value='.$RegNiveles['idObjEst'].' selected="selected">'.$RegNiveles['CObjEst'].'</option>';
                                                    }
                                            else
                                                {
                                                    //En caso contrario se carga la etiqueta por default.
            echo '                                  <option value='.$RegNiveles['idObjEst'].'>'.$RegNiveles['CObjEst'].'</option>';
                                                    }
                                            $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                            }
            
            echo'               </select></td></tr>            
                                <tr><td class="dgRowsaltTR">Objetivo Operativo:</td><td class= "dgRowsnormTR"><div id="cbObjOpe" border:none>';
            
                                    if($parametro=="-1")
                                        {
                                            /*
                                             * Si la acción corresponde a la creacion de un registro nuevo,
                                             * se establece el codigo actual.
                                             */
                                            echo'<select id= "idObjOpe"><option value=-1>Seleccione</option></select></div></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la acción ocurre para un registro existente,
                                             * se preserva el codigo almacenado.
                                             */
                                            echo constructorcb($Registro['idObjEst']).'</div></td></tr>';
                                            }
                                                        
            echo'                   <tr><td class="dgRowsaltTR">Est. Operativa:</td><td class= "dgRowsnormTR"><div id="cbEstOpe" border:none>';
            
                                    if($parametro=="-1")
                                        {
                                            /*
                                             * Si la acción corresponde a la creacion de un registro nuevo,
                                             * se establece el codigo actual.
                                             */
                                            echo'<select id= "idEstOpe"><option value=-1>Seleccione</option></select></div></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la acción ocurre para un registro existente,
                                             * se preserva el codigo almacenado.
                                             */
                                            echo constructorcbEstOpe($Registro['idObjOpe']).'</div></td></tr>';
                                            }
                        
                                    if($parametro=="-1")
                                        {
                                            /*
                                             * Si la acción corresponde a la creacion de un registro nuevo,
                                             * se establece el codigo actual.
                                             */
                                            echo'<tr><td class="dgRowsaltTR" width="100px">Nomenclatura:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= ""></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la acción ocurre para un registro existente,
                                             * se preserva el codigo almacenado.
                                             */
                                             echo'<tr><td class="dgRowsaltTR" width="100px">Nomenclatura:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= "'.$Registro['Nomenclatura'].'"></td></tr>';
                                            }
                                            
            echo'                   <tr><td class= "dgRowsaltTR">Programa:</td><td class= "dgRowsnormTR"><input type= "text" id= "Programa" '.$habcampos.' value= "'.$Registro['Programa'].'"><td></tr>
                                    <tr><td class= "dgRowsaltTR">Monto: $</td><td class= "dgRowsnormTR"><input type= "text" id= "Monto" '.$habcampos.' value= "'.$Registro['Monto'].'"><td></tr>';
            
            echo'                   <tr><td class= "dgRowsaltTR" width="100px">Entidades:</td><td class= "dgRowsnormTR"><select name= "idEntidad" id= "idEntidad" '.$habcampos.' value= "'.$Registro['idEntidad'].'">\'';
            
                                    $subconsulta = cargarEntidades();
                                    
            echo'                   <option value=-1>Seleccione</option>';
            
                                    $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                    
                                    while($RegNiveles)
                                        {
                                            if($RegNiveles['idEntidad']==$Registro['idEntidad'])
                                                {
                                                    //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                                   <option value='.$RegNiveles['idEntidad'].' selected="selected">'.$RegNiveles['Entidad'].'</option>';
                                                    }
                                            else
                                                {
                                                    //En caso contrario se carga la etiqueta por default.
            echo'                                   <option value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'].'</option>';
                                                    }
                                            $RegNiveles = @mysql_fetch_array($subconsulta, MYSQL_ASSOC);
                                            }
            
            echo'                   </select></td></tr>';
            
            echo'                   <tr><td class="dgRowsaltTR">Responsable:</td><td class= "dgRowsnormTR"><div id="cbResponsable" border:none>';
            
                                    if($parametro=="-1")
                                        {
                                            /*
                                             * Si la acción corresponde a la creacion de un registro nuevo,
                                             * se establece el codigo actual.
                                             */
                                            echo'<select id= "idResponsable"><option value=-1>Seleccione</option></select></div></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la acción ocurre para un registro existente,
                                             * se preserva el codigo almacenado.
                                             */
                                            echo constructorcbResponsable($Registro['idEntidad']).'</div></td></tr>';
                                            }            

            echo'                   <tr><td class="dgRowsaltTR">Auxiliar:</td><td class= "dgRowsnormTR"><div id="cbSubalterno" border:none>';
                                            
                                    if($parametro=="-1")
                                        {
                                            /*
                                             * Si la acción corresponde a la creacion de un registro nuevo,
                                             * se establece el codigo actual.
                                             */
                                             echo'<select id= "idSubalterno"><option value=-1>Seleccione</option></select></div></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la acción ocurre para un registro existente,
                                             * se preserva el codigo almacenado.
                                             */
                                             echo constructorcbSubalterno($Registro['idEntidad']).'</div></td></tr>';
                                            }
                                                                                        
            
                                    cargarProcesos($parametro, $Registro['idEntidad']);
                                    
                                    if($parametro=="-1")
                                        {
                                            /*
                                             * Si la acción corresponde a la creacion de un registro nuevo,
                                             * se establece el aÃ±o actual.
                                             */
                                            echo '<tr><td class="dgRowsaltTR" width="100px">Periodo:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$periodo.'"></td></tr>';
                                            }
                                    else
                                        {
                                            /*
                                             * Si la acción ocurre para un registro existente,
                                             * se preserva el aÃ±o almacenado.
                                             */
                                            echo '<tr><td class="dgRowsaltTR" width="100px">Periodo:</td><td class="dgRowsnormTR"><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$Registro['Periodo'].'"></td></tr>';
                                            }
                        
                                    controlVisual($parametro);
                                                                                    
            echo'                   </table>
                            </div>
                            <br>
                            <br>';
            
                            $nonhabilitado = 'disabled= "disabled"';
                            
            echo'           <div id= "dataprog" class="dgDivMain">
                                <table class= "queryTable">
                                    <tr><th colspan= "14" class= "queryHeader">Datos del Programa</th></tr>
                                    <tr><td></td><td class= "queryTitles">Enero</td><td class= "queryTitles">Febrero</td><td class= "queryTitles">Marzo</td><td class= "queryTitles">Abril</td><td class= "queryTitles">Mayo</td><td class= "queryTitles">Junio</td><td class= "queryTitles">Julio</td><td class= "queryTitles">Agosto</td><td class= "queryTitles">Septiembre</td><td class= "queryTitles">Octubre</td><td class= "queryTitles">Noviembre</td><td class= "queryTitles">Diciembre</td><td class= "queryTitles">Total</td></tr>';
                                    
                                    //Se procede con la carga de la programacion que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opProgPro WHERE status= 0 AND idPrograma= '.$Registro['idPrograma'];
                                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                                    $dsCampos = $subdataset;                                    
                                    $RegAux = @mysql_fetch_array($subdataset, MYSQL_ASSOC);                                    
                                    $field = @mysql_fetch_field($dsCampos);
                        
                                    $rowdata='<tr><td class= "queryTitles">Programación</td>';
                                    $count=1;
                                    $totEficacia=0.00;
                                    
                                    if($RegAux)
                                        {
                                            //Para el caso de una consulta de datos.
                                            while($field)
                                                {
                                                    $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="pr_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = @mysql_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }
                            
                                            $rowdata.='<td class="dgRowsnormTR"><input type="text" id="pr_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';
                                            }
                                    else
                                        {
                                            //Para el caso de una creación de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="pr_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td class="dgRowsnormTR"><input type="text" id="pr_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }
                                            
                                    echo $rowdata;
            
                                    //Se procede con la carga de la ejecucion que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEjecPro WHERE status= 0 AND idPrograma= '.$Registro['idPrograma'];
                                    $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                                    $dsCampos = $subdataset;                                    
                                    $RegAux = @mysql_fetch_array($subdataset, MYSQL_ASSOC);                                    
                                    $field = @mysql_fetch_field($dsCampos);
                        
                                    $rowdata='<tr><td class= "queryTitles">Ejecución</td>';
                                    $count=1;
                                    $totEficacia=0;
                                    
                                    if($RegAux)
                                        {
                                            //Para el caso de una consulta de datos.
                                            while($field)
                                                {
                                                    $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="ej_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = @mysql_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }
                            
                                            $rowdata.='<td class="dgRowsnormTR"><input type="text" id="ej_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                            
                                            }
                                    else
                                        {
                                            //Para el caso de una creación de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="ej_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td class="dgRowsnormTR"><input type="text" id="ej_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }                    

                                    echo $rowdata;
            
                                    //Se procede con la carga de la eficacia que corresponde al programa.
                                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                                    $subconsulta='SELECT  Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre FROM opEficPro WHERE status= 0 AND idPrograma= '.$Registro['idPrograma'];
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
                                                    $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="efic_'.$count.'" size="4" value="'.$RegAux[$field->name].'"></input></td>';
                                                    cargarBanderas($RegAux[$field->name], $count);//Se genera la fila de banderas.                                                    
                                                    $totEficacia += $RegAux[$field->name];
                                                    $field = @mysql_fetch_field($dsCampos);
                                                    $count += 1;
                                                    }
                                            $totEficacia = $totEficacia/12.00;
                                            $rowdata.='<td class="dgRowsnormTR"><input type="text" id="efic_'.$count.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                            
                                            }
                                    else
                                        {
                                            //Para el caso de una creación de registro.
                                            $counter=1;
                                            
                                            while($counter <= 12)
                                                {
                                                    //Mientras no se llegue al ciclo de doce meses.
                                                    $rowdata.= '<td class="dgRowsnormTR"><input type="text" '.$nonhabilitado.' id="efic_'.$counter.'" size="4" value="0.00"></input></td>';
                                                    cargarBanderas(0.00, $counter);//Se genera la fila de banderas.
                                                    $counter += 1;
                                                    }
                                            $rowdata.='<td class="dgRowsnormTR"><input type="text" id="efic_'.$counter.'" size="4" value="'.$totEficacia.'"></input></td></tr>';                                                    
                                            }                    
                                            
                                    echo $rowdata;
                                    echo '<tr><td class= "queryTitles">Estado</td>'.$rowBanderas.'</tr>';
                                                            
            echo'                   </table>
                            </div>';
            echo'       
                            <br>
                            <div id= "datatareas">';
                            $_GET['idprograma'] = $Registro['idPrograma'];

                            if($cntview == 3)
                                {
                                    /*
                                     * En caso que el invocador sea el formulario de actividades.
                                     */
                                    include_once("../../frontend/actividad/catActividad.php");
                                    }
                            else
                                {
                                    /*
                                     * En caso que el invocador sea el formulario de programa.
                                     */
                                    include_once("../actividad/catActividad.php");
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