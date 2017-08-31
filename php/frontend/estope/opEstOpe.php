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
     
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuracion.

    if(!isset($_SESSION))
        {
            //En caso de no existir el array de variables, se infiere que la sesion no fue iniciada.
            session_name('phoenix');
            session_start();
            } 
    
    $habcampos = 'disabled= "disabled"';
    $imgTitleURL = './img/menu/estope.png';
    $Title = 'Estrategia Operativa';
    $Sufijo = "eop_";
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $clavecod = '';   
    $now = time();
    $periodo = date("Y",$now);
    $regcount = 0;  

    function cargarObjEst()
        {
            /*
             * Esta funcion establece la carga del conjunto de registros de ObjEst.
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
             * Esta funcion establece la carga del conjunto de registros de ObjOpe.
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
             * Esta funcion establece los parametros de carga del combobox de obj ope cuando
             * se ejecuta un proceso de edicion.
             */
            global $habcampos, $Registro;
            
            $subconsulta = cargarObjOpe($parametro);            
            
            echo' <select name= "idObjOpe" id= "idObjOpe" '.$habcampos.' value= "'.$Registro['idObjOpe'].'">
                  <option value=-1>Seleccione</option>';
            
            $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
            
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
                    $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                    }
            
            echo' </select>';            
            }
            
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM catEstOpe WHERE idEstOpe='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   
                               
    $Registro = @mysqli_fetch_array(cargarRegistro($parametro),MYSQLI_ASSOC);//Llamada a la funcion de carga de registro de usuario.

    function controlBotones($Width, $Height, $cntView)
        {
            /*
             * Esta funcion controla los botones que deberan verse en la pantalla deacuerdo con la accion solicitada por el
             * usuario en la ventana previa.
             * Si es una edicion, los botones borrar y guardar deben verse.
             * Si es una creacion solo el boton guardar debe visualizarse.
             */
            global $Sufijo;
    
            $botonera = '';
            $btnVolver_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/volver.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Volver" id="'.$Sufijo.'Volver" title= "Volver"/>';
            $btnBorrar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/erase.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Borrar" id="'.$Sufijo.'Borrar" title= "Borrar"/>';
            $btnGuardar_V =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$Sufijo.'Guardar" title= "Guardar"/>';
            $btnGuardar_H =   '<img align= "right" class="btnConfirm" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/save.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Guardar" id="'.$Sufijo.'Guardar" title= "Guardar" style="display:none;"/>';
            $btnEditar_V =    '<img align= "right" onmouseover="bigImg(this)" onmouseout="normalImg(this)" src= "./img/grids/edit.png" width= "'.$Width.'" height= "'.$Height.'" alt= "Editar" id="'.$Sufijo.'Editar" title= "Editar"/>';
    
            if(($cntView == 0)||($cntView == 2)||($cntView == 9))
                {
                    //CASO: CREACION O EDICION DE REGISTRO.
                    if($_SESSION['nivel'] == "Lector")
                        {
                            /*  
                             * Si el usuario cuenta con un perfil de lector, se crea la referencia
                             * para el control de solo visualizacion.
                             */
                            $botonera .= $btnVolver_V;
                            }
                    else
                        {
                            if($_SESSION['nivel'] == "Administrador")
                                {
                                    $botonera .= $btnGuardar_V.$btnVolver_V;
                                    }
                            }
                    }
            else
                {
                    if($cntView == 1)
                        {
                            //CASO: VISUALIZACION CON OPCION PARA EDICION O BORRADO.
                            if($_SESSION['nivel'] == "Lector")
                                {
                                    /*
                                     * Si el usuario cuenta con un perfil de lector, se crea la referencia
                                     * para el control de solo visualizacion.
                                     */
                                    $botonera .= $btnVolver_V;
                                    }
                            else
                                {
                                    if($_SESSION['nivel'] == "Administrador")
                                        {
                                            $botonera .= $btnEditar_V.$btnBorrar_V.$btnGuardar_H.$btnVolver_V;
                                            }
                                    }
                            }
                    }
    
            return $botonera;
            }
            
    function constructor()
        {
            /*
             * Esta funcion establece el contenido HTML del formulario
             * en la carga del modulo.
             */
            global $Registro, $parametro, $clavecod, $periodo, $regcount, $habcampos;
            global $imgTitleURL, $Title;
            global $cntview;
            
            if(!empty($Registro['idEstOpe']))
                {
                    //CASO: VISUALIZACION DE REGISTRO PARA SU EDICION O BORRADO.
                    if($cntview == 1)
                        {
                            //VISUALIZAR.
                            $habcampos = 'disabled= "disabled"';
                            }
                    else
                        {
                            //EDICION.
                            $habcampos = '';
                            }                                                                
                    }
            else
                {
                    //CASO: CREACION DE NUEVO REGISTRO.
                    $habcampos = '';
                    }            

            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        </head>
                        <body>
                                <div style=display:none>
                                    <input type= "text" id= "idEstOpe" value="'.$Registro['idEstOpe'].'">
                                    <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                                </div>
                                <div id="infoRegistro" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                    <div id="cuerpo" class="cuerpo-operativo">                                
                                        <table>';
            
                                $subconsulta = cargarObjEst();
                                
            echo '                          <tr><td class="td-panel" width="100px">Objetivo Estrategico:</td><td><select name= "idObjEst" id= "idObjEst" '.$habcampos.' value= "'.$Registro['idObjEst'].'">              
                                                <option value=-1>Seleccione</option>';
            
                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                
                                while($RegNiveles)
                                    {
                                        if($RegNiveles['idObjEst']==$Registro['idObjEst'])
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo '                              <option value='.$RegNiveles['idObjEst'].' selected="selected">'.$RegNiveles['CObjEst'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo '                              <option value='.$RegNiveles['idObjEst'].'>'.$RegNiveles['CObjEst'].'</option>';
                                                }
                                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
            
            echo'                           </select></td></tr>
                                            <tr><td class="td-panel">Objetivo Operativo:</td><td><div id="cbObjOpe" border:none>';
            
                                if($parametro=="-1")
                                    {
                                        /*
                                         * Si la accion corresponde a la creacion de un registro nuevo,
                                         * se establece el codigo actual.
                                         */
            echo'                               <select id= "idObjOpe"><option value=-1>Seleccione</option></select></div></td></tr>';
                                        }
                                else
                                    {
                                        /*
                                         * Si la accion ocurre para un registro existente,
                                         * se preserva el codigo almacenado.
                                         */
            echo                                constructorcb($Registro['idObjEst']).'</div></td></tr>';
                                        }
                                        
                        
                                if($parametro=="-1")
                                    {
                                        /*
                                         * Si la accion corresponde a la creacion de un registro nuevo,
                                         * se establece el codigo actual.
                                         */
            echo'                           <tr><td class="td-panel" width="100px">Nomenclatura:</td><td><input type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= ""></td></tr>';  
                                        }
                                else
                                    {
                                        /*
                                         * Si la accion ocurre para un registro existente,
                                         * se preserva el codigo almacenado.
                                         */
            echo'                           <tr><td class="td-panel" width="100px">Nomenclatura:</td><td><input type= "text" required= "required" id= "Nomenclatura" '.$habcampos.' value= "'.$Registro['Nomenclatura'].'"></td></tr>';
                                        }
                                                                          
            echo'                           <tr><td class="td-panel" width="100px">Estrategia Operativa:</td><td><input type= "text" required= "required" id= "EstOpe" '.$habcampos.' value= "'.$Registro['EstOpe'].'"></td></tr>';
            
                                if($parametro=="-1")
                                    {
                                        /*
                                         * Si la accion corresponde a la creacion de un registro nuevo,
                                         * se establece el año actual.
                                         */
            echo '                          <tr><td class="td-panel" width="100px">Periodo:</td><td><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$periodo.'"></td></tr>';
                                        }
                                else
                                    {
                                        /*
                                         * Si la accion ocurre para un registro existente,
                                         * se preserva el año almacenado.
                                         */                              
            echo '                          <tr><td class="td-panel" width="100px">Periodo:</td><td><input type= "text" required= "required" id= "Periodo" '.$habcampos.' value= "'.$Registro['Periodo'].'"></td></tr>';                                               
                                        }
                                
            echo'                       </table>
                                    </div>
                                    <div id="pie" class="pie-operativo">'.
                                        controlBotones("32", "32", $cntview).                
                                '   </div>
                        </body>
                    </html>
                    ';            
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        constructor();
?>