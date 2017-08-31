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
        
    $imgTitleURL = './img/menu/puestos.png';
    $Title = 'Puestos';
    $Sufijo = "pst_";
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    $habcampos = 'disabled= "disabled"';
    $clavecod = '';   
        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM (relEntPuesto INNER JOIN catPuestos ON catPuestos.idPuesto = relEntPuesto.idPuesto) INNER JOIN catEntidades ON catEntidades.idEntidad = relEntPuesto.idEntidad WHERE relEntPuesto.idRelEntPst='.$idRegistro; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;        
            }   

    function cargarEntidades($idRegistro, $idPuesto)
        {
            /*
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */
                global $username, $password, $servername, $dbname, $habcampos;
            
                $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                $consulta= 'SELECT *FROM catEntidades WHERE Status=0'; //Se establece el modelo de consulta de datos.
                $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                
                echo '<tr><td class="td-panel" width="100px">Entidades:</td><td><div id="idEntidadChk">';
                
                if($idRegistro == -1)
                    {
                        /*
                         * Si la operacion solicitada es para la creacion de un registro,
                         * se carga el listado sin marcar.
                         */
                        $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                        
                        while ($RegNiveles)
                            {
                                echo '<br><input type="checkbox" class="check" id="idEntidad[]" name="idEntidad[]" '.$habcampos.' value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'];
                                $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                                }
                        }
                else
                    {
                        /*
                         * Si la operacion solicitada es para editar el registro,
                         * se carga el listado con los elementos previamente marcados.
                         */
                        $subconsulta = 'SELECT *FROM relEntPuesto WHERE idPuesto='.$idPuesto.' AND Status=0'; //Se establece el modelo de consulta de datos.
                        $subdataset = $objConexion -> conectar($subconsulta); //Se ejecuta la consulta.
                        $vector = "";
                        $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                        
                        if($RegNiveles)
                            {
                                /*
                                 * Si la lectura del registro no apunta a vacio, se agrega
                                 * el id al vector.
                                 */
                                $vector.=$RegNiveles['idEntidad'];
                                }
                                
                        $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                        
                        while ($RegNiveles)
                            {
                                /*
                                 * Se hace un recorrido sobre el dataset para crear un vector que contenga
                                 * los id de las entidades seleccionadas por el usuario previamente.
                                 */
                                     $vector.=','.$RegNiveles['idEntidad'];
                                     $RegNiveles = @mysqli_fetch_array($subdataset,MYSQLI_ASSOC);
                                }                       
                        
                        $tmparray=explode(',',$vector); //El vector resultante se convierte en un arreglo.

                        $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                        
                        while ($RegNiveles)
                            {
                                /*
                                 * Mientras no se llegue al final de la coleccion, se procede a la lectura
                                 * y generacion del listado.
                                 */
                                if(in_array($RegNiveles['idEntidad'], $tmparray,true))
                                    {
                                        /*
                                         * En caso de tratarse de una opcion previamente seleccionada por el usuario.
                                         */
                                        echo '<br><input type="checkbox" class="check" id="idEntidad[]" name="idEntidad[]" '.$habcampos.' value='.$RegNiveles['idEntidad'].' checked>'.$RegNiveles['Entidad'];
                                        }
                                else
                                    {
                                        /*
                                         * En caso contrario se agrega una entrada de formato convencional.
                                         */
                                        echo '<br><input type="checkbox" class="check" id="idEntidad[]" name="idEntidad[]" '.$habcampos.' value='.$RegNiveles['idEntidad'].'>'.$RegNiveles['Entidad'];
                                        }
                                        
                                $RegNiveles = @mysqli_fetch_array($dataset,MYSQLI_ASSOC);
                                }
                        }                                                
                
                echo'</div></td></tr>';
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
            global $Registro, $parametro, $clavecod, $habcampos;
            global $imgTitleURL, $Title;
            global $cntview;
            
            if(!empty($Registro['idPuesto']))
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
                                <input type= "text" id= "idPuesto" value="'.$Registro['idPuesto'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                                <div id="infoRegistro" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                    <div id="cuerpo" class="cuerpo-operativo">                                
                                        <table>
                                <tr><td class="td-panel" width="100px">Puesto:</td><td><input type= "text" required= "required" id= "Puesto" '.$habcampos.' value= "'.$Registro['Puesto'].'"></td></tr>';
            
                                cargarEntidades($parametro, $Registro['idPuesto']);
                                
            echo'           </table>
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