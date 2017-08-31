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
    
    $imgTitleURL = './img/menu/factores.png';
    $Title = 'Factores';
    $Sufijo = "ffa_";
    $parametro = $_GET['id'];
    $cntview = $_GET['view'];
    
    function cargarCedulas()
        {
            /*
             * Esta funcion establece la carga del conjunto de registros de entidades.
             */
            global $username, $password, $servername, $dbname;
    
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT idCedula, Folio FROM opCedulas WHERE Status=0'; //Se establece el modelo de consulta de datos.
            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            return $dataset;
            }
        
    function cargarRegistro($idRegistro)
        {
            /*
             * Esta funcion establece la carga de un registro a partir de su identificador en la base de datos.
             */            
            global $username, $password, $servername, $dbname;
            
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta= 'SELECT *FROM opFactores WHERE idFactor='.$idRegistro; //Se establece el modelo de consulta de datos.
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
            global $Registro, $parametro, $clavecod;
            global $imgTitleURL, $Title;
            global $cntview;
            
            $habcampos = 'disabled= "disabled"';
            $idCedula = '';
            
            if(!empty($Registro['idFactor']))
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
                    $idCedula = $Registro['idCedula'];
                    }
            else
                {
                    //CASO: CREACION DE NUEVO REGISTRO.
                    $habcampos = '';
                    if(isset($_GET['ffacedula']))
                        {
                            $idCedula = $_GET['ffacedula'];
                            }
                    }           

            echo'
                    <html>
                        <head>
                            <link rel= "stylesheet" href= "./css/dgstyle.css"></style>
                        </head>
                        <body>                
                            <div style=display:none>
                                <input type= "text" id= "idFactor" value="'.$Registro['idFactor'].'">
                                <input type= "text" id= "Status" value="'.$Registro['Status'].'">    
                            </div>
                                <div id="infoRegistro" class="operativo">
                                    <div id="cabecera" class="cabecera-operativo">'
                                        .'<img align="middle" src="'.$imgTitleURL.'" width="32" height="32"/> '.$Title.' </div>
                                    <div id="cuerpo" class="cuerpo-operativo">                                
                                        <table>
                                <tr><td class="td-panel" width="100px">Factor:</td><td><input type= "text" required= "required" id= "Factor" '.$habcampos.' value= "'.$Registro['Factor'].'"></td></tr>
                                <tr><td class="td-panel" width="100px">Tipo:</td>
                                    <td>
                                        <select name="Tipo" id= "Tipo" '.$habcampos.' value= "'.$Registro['Tipo'].'">';
            
                                if($Registro['Tipo'] == "Interno")
                                    {
            echo'                           <option value= "Seleccione">Seleccione</option>
                                            <option value= "Interno" selected="selected">Interno</option>
                                            <option value= "Externo">Externo</option>';
                                        }
                                else
                                    {
                                            if($Registro['Tipo'] == "Externo")
                                                {
            echo'                                   <option value= "Seleccione">Seleccione</option>
                                                    <option value= "Interno">Interno</option>
                                                    <option value= "Externo" selected="selected">Externo</option>';                                                    
                                                    }
                                            else
                                                {
            echo'                                   <option value= "Seleccione" selected="selected">Seleccione</option>
                                                    <option value= "Interno">Interno</option>
                                                    <option value= "Externo">Externo</option>';                                                    
                                                    }                                        
                                        }   
                                                                             
            echo'                       </select>
                                    </td>
                                </tr>';
            
            echo'               <tr><td width="100px" class="td-panel">Cedula:</td><td><select name= "idCedula" id= "idCedula" '.$habcampos.' value= "'.$idCedula.'">';

                                $subconsulta = cargarCedulas();
                                
            echo'               <option value=-1>Seleccione</option>';
                                    
                                $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                
                                while ($RegNiveles)
                                    {
                                        //Se ejecuta un recorrido de comparacion para determinar el item seleccionado.                                                
                                        if($RegNiveles['idCedula'] == $idCedula)
                                            {
                                                //En caso que el valor almacenado coincida con uno de los existentes en lista.
            echo'                               <option value='.$RegNiveles['idCedula'].' selected="selected">'.$RegNiveles['Folio'].'</option>';
                                                }
                                        else
                                            {
                                                //En caso contrario se carga la etiqueta por default.
            echo'                               <option value='.$RegNiveles['idCedula'].'>'.$RegNiveles['Folio'].'</option>';
                                                }
                                                
                                        $RegNiveles = @mysqli_fetch_array($subconsulta,MYSQLI_ASSOC);
                                        }
            
            echo'               </select></td></tr>';
                        
                                
            echo'           </table>
                                    </div>
                                    <div id="pie" class="pie-operativo">'.
                                        controlBotones("32", "32", $cntview).                
                                '   </div>  
                        </body>
                    </html>';            
        } 

        /*
         * Llamada a las funciones del constructor de interfaz. 
         */
        constructor();
?>