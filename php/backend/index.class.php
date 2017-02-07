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

/********************************************************************************************************
 * Archivo: index.class.php  * Descripcion: Clase que contiene el codigo para la creacion del mainframe *
 *                           * donde se gestionan los recursos del sitio web                            *
 ********************************************************************************************************
 * Desarrollador: Mtro. Jesus Antonio Peyrano Luna * Ultima modificacion: 27/09/2016                    *
 ********************************************************************************************************/

    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/utilidades/codificador.class.php");
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/micrositio/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.
    
    class index
        {
                        
            function __construct()
                {
                    /*
                     * Esta función establece los elementos de carga inicial de la clase.
                     */
                    if (isset($_POST["usuario"])) 
                        {
                            //En caso que la variable de nombre de usuario sea obtenida por el evento POST
                            //se procede a validar al usuario para el ingreso al sistema.                            
                            $this->validar_usuario();                            
                            }                    
                    }

            public function headItem($class, $onClick, $imgURL, $caption)
                {
                    /*
                     * Esta funcion genera la linea HTML que corresponde a un elemento de la lista de
                     * menu en pantalla.
                     */
                    $item='<li class="'.$class.'"><a href="#" class="desplegable" onclick="'.$onClick.'"><img src="'.$imgURL.'" width="35" height="35">'.$caption.'</a>';
                    return $item;
                    }

            public function bodyItem($class, $onClick, $imgURL, $caption)
                {
                    /*
                     * Esta funcion genera la linea HTML que corresponde a un elemento de la lista de
                     * menu en pantalla.
                     */
                     $item='<li class="'.$class.'"><a href="#" onclick="'.$onClick.'"><img src="'.$imgURL.'" width="35" height="35">'.$caption.'</a></li>';
                     return $item;
                    }
                                        
            private function drawMenu()
                {
                    /*
                     * Esta función dibuja el menu de operaciones de la interfaz principal.
                     */
                     $usuario = ''; //Valor por default para usuario.
                     $nivel = ''; //Valor por default para clave.
                     
                     if(isset($_SESSION['usuario']))
                        {
                            //En caso que ya se cuente con un usuario valido para el sistema.
                            $usuario = $_SESSION['usuario'];
                            }

                     if(isset($_SESSION['nivel']))
                        {
                            //En caso que ya se cuente con un usuario valido para el sistema.
                            $nivel = $_SESSION['nivel'];
                            }

                     $menuBody = '<div id="menuLateral">';
                     
                     if($nivel == 'Administrador')
                        {
                            /*
                             * Si el nivel de permisos de usuario, corresponde al nivel de un administrador,
                             * se habilitan las opciones de configuracion del sistema, a nivel de catalogos
                             * generales.
                             */
                            
                            $adminConfig = '
                                            <ul class="navegador">'.
                                                $this->headItem("contexto_menu", "", "./img/config.png", "Parametros Generales").
                                                    '<ul class="subnavegador">'
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/configuracion/busConfiguraciones.php','','escritorio');", "./img/configsys.png", "Conf. Sistema")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/unidades/busUnidades.php','','escritorio');", "./img/unidades.png", "Unidades")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/colonias/busColonias.php','','escritorio');", "./img/colonias.png", "Colonias")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/usuarios/busUsuarios.php','','escritorio');", "./img/usuarios.png", "Usuarios")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/usraltas/busSolAltUsr.php','','escritorio');", "./img/usraltas.png", "Solicitudes de Usuario")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/empleados/busEmpleados.php','','escritorio');", "./img/empleados.png", "Empleados")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/clientes/busClientes.php','','escritorio');", "./img/clientes.png", "Clientes")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/entidades/busEntidades.php','','escritorio');", "./img/entidades.png", "Entidades")                                                                                                                
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/vehiculos/busVehiculos.php','','escritorio');", "./img/vehiculos.png", "Vehiculos")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/procesos/busProcesos.php','','escritorio');", "./img/procesos.png", "Procesos")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/indicadores/busIndicadores.php','','escritorio');", "./img/indicadores.png", "Indicadores")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/puestos/busPuestos.php','','escritorio');", "./img/puestos.png", "Puestos")                                                        
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/gasolina/busGasolina.php','','escritorio');", "./img/gasolina.png", "Gasolina").
                                                    '</ul>
                                                </li>
                                            </ul>
                                            ';
                            
                            $menuBody = $menuBody.$adminConfig;
                            
                            /*
                             * Si el nivel de permisos de usuario, corresponde al de un administrador,
                             * se habilitan las opciones de configuracion del sistema a nivel de planeacion.
                             */
                            $adminPlan = '
                                            <ul class="navegador">'.
                                                $this->headItem("contexto_menu", "", "./img/paramplan.png", "Parametros de Planeacion").                                                
                                                    '<ul class="subnavegador">'
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/fichas/busFichaProceso.php','','escritorio');", "./img/sgc.png", "Fichas de Proceso")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/objest/busObjEst.php','','escritorio');", "./img/objest.png", "Obj. Estrategico")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/objope/busObjOpe.php','','escritorio');", "./img/objope.png", "Obj. Operativo")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/estope/busEstOpe.php','','escritorio');", "./img/estope.png", "Est. Operativa")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/programa/busPrograma.php','','escritorio');", "./img/programas.png", "Programas").                                                  
                                                    '</ul>
                                                </li>    
                                            </ul>                                
                                            ';
                            
                            $menuBody = $menuBody.$adminPlan;
                            
                            $adminFODA = '
                                            <ul class="navegador">'.
                                                $this->headItem("contexto_menu", "", "./img/foda.gif", "F.O.D.A.").
                                                    '<ul class="subnavegador">'
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/foda/cedulas/busCedulas.php','','escritorio');", "./img/cedula.png", "Cedulas")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/foda/factores/busFactores.php','','escritorio');", "./img/factores.png", "Factores")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/foda/escalas/busEscalas.php','','escritorio');", "./img/escala.png", "Escalas")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/foda/evaluaciones/busEvaluaciones.php','','escritorio');", "./img/evaluacion.png", "Evaluaciones").                                
                                                    '</ul>
                                                </li>
                                            </ul>
                                            ';
                            
                            $menuBody = $menuBody.$adminFODA; 
                            }
                            
                     if(($nivel == 'Operador')||($nivel == 'Administrador'))
                        {
                            /*
                             * En caso que el usuario cuente con permisos de operador o de administrador,
                             * se habilitan las opciones correspondientes al manejo en productivo de la
                             * planeacion y el consumo de gasolina.
                             */
                            if($nivel == 'Operador')
                                {
                                    /*
                                     * Solo para el caso que el usuario sea operador, se permite la visualización
                                     * de la opción de carga y edición de programas.
                                     */
                                    $operProduccion = '
                                                        <ul class="navegador">'.
                                                            $this->headItem("contexto_menu", "", "./img/operaciones.png", "Operaciones").
                                                                '<ul class="subnavegador">'
                                                                    .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/programa/busPrograma.php','','escritorio');", "./img/programas.png", "Programas");                                    
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario, solo se carga el perfil general.
                                     */
                                    $operProduccion = '
                                                        <ul class="navegador">'.
                                                            $this->headItem("contexto_menu", "", "./img/operaciones.png", "Operaciones").
                                                                '<ul class="subnavegador">';
                                    }
                                    

                                    $operProduccion.=   $this->bodyItem("contexto_submenu", "cargar('./php/frontend/gasconsumo/opGasConsumo.php','','escritorio');", "./img/vehconsumo.png", "Consumo de Combustibles")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/consulplan/conObjEst.php','','escritorio');", "./img/planeacion.png", "Planeacion Estrategica")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/foda/usrevafoda/opUsrFODA.php','','escritorio');", "./img/foda.gif", "Evaluacion FODA")
                                                        .$this->bodyItem("contexto_submenu", "cargar('./php/frontend/foda/resfoda/opResFODA.php','','escritorio');", "./img/resfoda.png", "Resultados FODA").
                                                        '</ul>
                                                    </li>
                                                </ul>                                
                                                ';
                            
                            $menuBody = $menuBody.$operProduccion;
                            
                            }       

                     if(($nivel=='Lector')||($nivel=='Administrador'))
                        {
                            /*
                             * En caso que el usuario cuente con permisos de Lector o en su defecto sea un administrador del
                             * sistema.
                             */
                            if($nivel=='Lector')
                                {
                                    /*
                                     * Solo para el caso que el usuario sea lector, se permite la visualización
                                     * de la opción de consulta de programacion.
                                     */
                                    $lectorMenu = '
                                                    <ul class="navegador">'.
                                                    $this->bodyItem("contexto_menu", "cargar('./php/frontend/consulplan/conObjEst.php','','escritorio');", "./img/planeacion.png", "Planeacion Estrategica");
                                    }
                            else
                                {
                                    /*
                                     * En caso contrario, solo se carga el perfil general.
                                     */
                                    $lectorMenu = '
                                                    <ul class="navegador">
                                                    ';                                                                        
                                    }                                    

                            $lectorMenu.=           $this->bodyItem("contexto_menu", "cargar('./php/frontend/utilidades/graficos.php','','escritorio');", "./img/graficas.png", "Graficas").
                                                    '</ul>';
                            
                            $menuBody = $menuBody.$lectorMenu;
                            
                            }                            
                                   
                     $menu = '
                                    <ul class="navegador">
                                        <li class="contexto_menu"><a href="#" onclick=""><img src="./img/enviar.png" width="35" height="35"/>Contacto</a></li>
                                        <li class="contexto_menu"><a href="./index.php" onclick="cargar(\'./php/backend/logout.php\',\'\',\'Contenedor\');"><img src="./img/desligado.png" width="35" height="35"/>Cerrar Sesion</a></li>
                                    </ul>                         
                                    <br>
                                    <div id= "infousuario" class="infousuario">
                                        <table>
                                            <tr><td id= "tdUsuario" class= "tdInfoUsuario">Usuario: '.$usuario.'</td></tr>
                                            <tr><td id= "tdNivel" class= "tdInfoUsuario">Nivel: '.$nivel.'</td></tr>                                            
                                        </table>
                                    </div>
                                </div>                                
                                ';
                     
                     $menuBody = $menuBody.$menu;
                     
                     return $menuBody;                    
                    }
                                        
            private function infoHead()
                {
                    /*
                     * Esta funci�n contiene la informaci�n a incluir en la cabecera del html.
                     */
                     $head = '
                                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
                                <link rel="stylesheet" href="./css/menuStyle.css" title="style css" type="text/css" media="screen" charset="utf-8"></style>
                                <link rel="stylesheet" href="./css/divLogin.css"></style>
                                <link rel="stylesheet" href="./css/operativo.css"></style>
                                <link rel="stylesheet" href="./css/foda.css"></style>
                                <link rel="stylesheet" href="./css/jssor.css"></style>
                                <link rel="icon" type="image/png" href="./img/icologo.png" />
                                <title>Micrositio</title>
                                <script type="text/javascript" src="./js/jquery/jquery-1.9.1.js"></script>
                                <script type="text/javascript" src="./js/jquery/jquery-1.9.1.min.js"></script>
                                <script type="text/javascript" src="./js/jquery/jssor.slider.mini.js"></script>                                
                                <script type="text/javascript" src="./js/jquery/jsplug.js"></script>
                                <script type="text/javascript" src="./js/configuracion/jsconfiguracion.js"></script>
                                <script type="text/javascript" src="./js/puestos/jspuestos.js"></script> 
                                <script type="text/javascript" src="./js/usuarios/jsusuarios.js"></script>
                                <script type="text/javascript" src="./js/empleados/jsempleados.js"></script>        
                                <script type="text/javascript" src="./js/usraltas/jsusraltas.js"></script>
                                <script type="text/javascript" src="./js/clientes/jsclientes.js"></script>
                                <script type="text/javascript" src="./js/colonias/jscolonias.js"></script>
                                <script type="text/javascript" src="./js/entidades/jsentidades.js"></script>
                                <script type="text/javascript" src="./js/vehiculos/jsvehiculos.js"></script>
                                <script type="text/javascript" src="./js/gasolina/jsgasolina.js"></script>
                                <script type="text/javascript" src="./js/procesos/jsprocesos.js"></script>
                                <script type="text/javascript" src="./js/indicadores/jsindicadores.js"></script>
                                <script type="text/javascript" src="./js/unidades/jsunidades.js"></script>
                                <script type="text/javascript" src="./js/objest/jsobjest.js"></script>
                                <script type="text/javascript" src="./js/objope/jsobjope.js"></script>
                                <script type="text/javascript" src="./js/estope/jsestope.js"></script>
                                <script type="text/javascript" src="./js/programas/jsprogramas.js"></script>
                                <script type="text/javascript" src="./js/actividades/jsactividades.js"></script>
                                <script type="text/javascript" src="./js/ejecuciones/jsejecuciones.js"></script>
                                <script type="text/javascript" src="./js/evidencias/jsevidencias.js"></script>                                
                                <script type="text/javascript" src="./js/consulplan/jsconsulplan.js"></script>
                                <script type="text/javascript" src="./js/gasconsumo/jsgasconsumo.js"></script>
                                <script type="text/javascript" src="./js/graficas/jsgraficas.js"></script>
                                <script type="text/javascript" src="./js/fichas/jsfichas.js"></script>
                                <script type="text/javascript" src="./js/foda/cedulas/jscedulas.js"></script>
                                <script type="text/javascript" src="./js/foda/factores/jsfactores.js"></script>
                                <script type="text/javascript" src="./js/foda/escalas/jsescalas.js"></script>
                                <script type="text/javascript" src="./js/foda/evaluaciones/jsevaluaciones.js"></script>
                                <script type="text/javascript" src="./js/usrevafoda/jsusrevafoda.js"></script>
                                <script type="text/javascript" src="./js/foda/resultados/jsresfoda.js"></script>                                               
                                ';
                    return $head;                   
                    }

            private function infoBody()
                {
                    /*
                     * Esta funci�n contiene la informaci�n a incluir en el cuerpo del html.
                     */
                     $display = 'display:block'; //Por default si no existe usuario el sistema se bloquea.                     
                     
                     if(isset($_SESSION['usuario']))
                        {
                            //En caso que ya se cuente con un usuario valido para el sistema.
                            $display = 'display:none';
                            }
                            
                     $body = '
                                <div id= "Contenedor" class= "Contenedor">'.
                                    $this->drawMenu()
                                    .'<div class="Contenedor-Principal">
                                        <div class="Area-Deslizar"></div>
                                        <a href="#" data-toggle=".Contenedor" id="menuLateral-toggle">
                                            <span class="bar"></span>
                                            <span class="bar"></span>
                                            <span class="bar"></span>
                                        </a>        
                                        <div id="escritorio" class="content">
                                        <iframe src="./php/frontend/utilidades/anuncios.php" style="width: 100% !important; height: 100% !important" frameborder="0"></iframe>                                                                                        
                                        </div>
                                    </div>
                                </div>           
                                <section class="Contenedor_Seccion">
                                    <div id="logins" class= "login_div" style= "position:absolute; z-index:4; '.$display.'">
                                        <div id="login" class= "login"; z-index:5;>
                                            <h1 class="Cabecera-Login">.</h1>
                                            <form name = "login" action="" method="post">
                                                <p><input id="usuario" name="usuario" required="required" type="text" value="" placeholder="Usuario o Email"/></p>
                                                <p><input id="clave "name="clave" required="required" type="password" value="" placeholder="Contraseña"/></p>
                                                <button id="ingresar" class="button-blue"><img src="./img/login.png" width="35" height="35"/>Iniciar Sesion</button>
                                            </form>
                                            <div class="Ayuda-Login">
                                                <p>¿Aun no tiene una cuenta? <a href= "./php/frontend/usraltas/opSolAltUsr.php" target= "_self">De click aqui para registrarse.</a>.</p>
                                                <p>¿Olvido su clave? <a href= "./php/frontend/usuarios/opRecordar.php" target= "_self">De click aqui para recuperarla.</a>.</p>
                                            </div>
                                        </div>                               
                                    </div>
                                </section>
                                <footer>
                                    <div id ="informacion">
                                    <b>Software desarrollado por Peyrano Computacion</b>
                                    </div>
                                </footer>
                                ';
                     return $body;                    
                    }
                                                            
            public function drawUI()
                {
                    /*
                     * Esta funcion dibuja los elementos en pantalla que corresponden a la interfaz.
                     */
                     echo   '   <html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
                                <head>'.
                                $this->infoHead()
                                .'</head>
                                <body>'.
                                $this->infoBody()
                                .'
                                    <script type="text/javascript">
                                        document.oncontextmenu = function(){return true;}
                                    </script>                                    
                                    </body>                                
                                </html>
                            ';                    
                    } 

            function validar_usuario()
                {
                    //Almacenamos en variables ($nombre_variable), los datos obtenidos de los elementos del formulario de su
                    //atributo "name".
                    global $username, $password, $servername, $dbname;
            
                    $usuario = $_POST["usuario"];
                    $clave = $_POST["clave"];
                    $objCodificador = new codificador();
                    
                    $cadena_encriptada = $objCodificador->encrypt($clave,"ouroboros");
            
                    //Se ejecuta la consulta a la base de datos y se envia la o las filas resultantes al objeto auxiliar $dataset.
                    $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
                    $consulta= "SELECT * FROM catUsuarios INNER JOIN catNiveles ON catNiveles.idNivel = catUsuarios.idNivel WHERE Usuario = '$usuario' AND Clave = '$cadena_encriptada'"; //Se establece el modelo de consulta de datos.
                    $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                                                
                    //Se verifica si existen datos dentro de la consulta generada.
                    $row = @mysql_fetch_array($dataset, MYSQL_ASSOC);
            
                    if($row)
                        {
                            session_start(); //Se inicia la sesi�n del usuario en el sistema.
                            //Almacenamos el nombre de usuario en una variable de sesi�n usuario.
                            $_SESSION['usuario'] = $usuario;
                            $_SESSION['nivel'] = $row['Nivel'];
                            /*
                             * Se obtiene la referencia de empleado, en caso que la cuenta de usuario
                             * este asociada a un empleado registrado en sistema. Con la finalidad de
                             * controlar la visualización y edición de programas.
                             */ 
                            $consulta= 'SELECT *FROM ((opEmpleados INNER JOIN catColonias ON catColonias.idColonia = opEmpleados.idColonia) INNER JOIN relUsrEmp ON relUsrEmp.idEmpleado = opEmpleados.idEmpleado) LEFT JOIN catUsuarios ON catUsuarios.idUsuario = relUsrEmp.idUsuario WHERE catUsuarios.idUsuario='.$row['idUsuario'];
                            $dataset = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
                            $row = @mysql_fetch_array($dataset, MYSQL_ASSOC);
                            $_SESSION['idEmpleado'] = 0; //Valor por default, sera cero mientras que no se localice referencias de empleado.
                            
                            if($row)
                                {
                                    /*
                                     * En el caso que el usuario que ingreso al sistema, sea un empleado registrado,
                                     * se captura su id para la gestión de edición de programas.
                                     */
                                    $_SESSION['idEmpleado'] = $row['idEmpleado']; //Se obtiene el id del empleado.
                                    }                                                       
                            }
                    else
                        {
                            //En caso de no coincidir el usuario o contrase�a se notifica al usuario.
                            ?>
                            <script languaje="javascript">
                                alert("El nombre de usuario o clave es incorrecto!");
                                location.href = "./index.php";
                            </script>
                            <?php           
                            }                                               
                    }
            }
?>