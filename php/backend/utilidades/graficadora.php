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

    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/jpgraph/src/jpgraph.php"); //Se carga la referencia a la clase base de graficador.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/jpgraph/src/jpgraph_bar.php"); //Se carga la referencia a la clase especifica de grafica de barras.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/dal/conectividad.class.php"); //Se carga la referencia a la clase de conectividad.
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/config.php"); //Se carga la referencia de los atributos de configuración.

    $Periodo = '';
    $idEntidad = '';
    $idProceso = '';
    
    if(isset($_GET['periodo']))
        {
            $Periodo = $_GET['periodo'];            
            }
            
    if(isset($_GET['identidad']))
        {
            $idEntidad = $_GET['identidad'];
            }                

    if(isset($_GET['idproceso']))
        {
            $idProceso = $_GET['idproceso'];
            }
                        
    $Identificador = '';
    $Programacion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
    $Ejecucion = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);    
    $Eficacia = array (0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
    $Leyendas = array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
    
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
        
    function obtenerEficacia($Periodo)
        {
            //Esta función obtiene el valor esperado de la eficacia por la entidad consultada.
            global $username, $password, $servername, $dbname;
            global $Programacion, $Ejecucion, $Eficacia;
            global $Identificador, $idEntidad, $idProceso;
            
            $consIdentificador = '';
            $idCampo = '';
            $consProgramacion = '';
            $consEjecucion = '';
            $conteo = 1;
            $progtot = 0;

            if($idEntidad!='')
                {
                    $consIdentificador = 'SELECT *FROM catEntidades WHERE idEntidad ='.$idEntidad;
                    $idCampo = 'Entidad';
                    $consProgramacion = 'SELECT *FROM (opProgramas INNER JOIN opProgPro ON opProgramas.idPrograma = opProgPro.idPrograma) WHERE opProgramas.idEntidad ='.$idEntidad.' AND opProgramas.Periodo='.$Periodo;
                    $consEjecucion = 'SELECT *FROM (opProgramas INNER JOIN opEjecPro ON opProgramas.idPrograma = opEjecPro.idPrograma) WHERE opProgramas.idEntidad ='.$idEntidad.' AND opProgramas.Periodo='.$Periodo;
                    }
                    
            if($idProceso!='')
                {
                    $consIdentificador = 'SELECT *FROM catProcesos WHERE idProceso ='.$idProceso;
                    $idCampo = 'Proceso';
                    $consProgramacion = 'SELECT *FROM ((opProgramas INNER JOIN opProgPro ON opProgramas.idPrograma = opProgPro.idPrograma) INNER JOIN relProgPro ON relProgPro.idPrograma = opProgramas.idPrograma) WHERE relProgPro.idProceso ='.$idProceso.' AND opProgramas.Periodo='.$Periodo;
                    $consEjecucion = 'SELECT *FROM ((opProgramas INNER JOIN opEjecPro ON opProgramas.idPrograma = opEjecPro.idPrograma) INNER JOIN relProgPro ON relProgPro.idPrograma = opProgramas.idPrograma) WHERE relProgPro.idProceso ='.$idProceso.' AND opProgramas.Periodo='.$Periodo;
                    }                    
                    
            //Se obtiene el referente del identificador a evaluar.
            $objConexion = new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $dsIdentificador = $objConexion -> conectar($consIdentificador); //Se ejecuta la consulta.
            $RegIdentificador = @mysqli_fetch_array($dsIdentificador,MYSQLI_ASSOC);

            $Identificador = $RegIdentificador[$idCampo];
            
            //Se obtienen los datos de la programacion.
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = $consProgramacion;
            $dsProgPro = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegProgPro = @mysqli_fetch_array($dsProgPro,MYSQLI_ASSOC);
            
            //Se obtienen los datos de la ejecucion.
            $objConexion= new mySQL_conexion($username, $password, $servername, $dbname); //Se crea el objeto de la clase a instanciar.
            $consulta = $consEjecucion;
            $dsEjecPro = $objConexion -> conectar($consulta); //Se ejecuta la consulta.
            $RegEjecPro = @mysqli_fetch_array($dsEjecPro,MYSQLI_ASSOC);
                        
            While($RegProgPro)
                {
                    //Mientras existan registros que coincidan con los criterios solicitados.
                    while($conteo <= 12)
                        {
                            //Se sumariza la programación del i-esimo mes en el arreglo de programa.
                            $Programacion[$conteo-1] += $RegProgPro[obtainMes($conteo)];
                            $Ejecucion[$conteo-1] += $RegEjecPro[obtainMes($conteo)];
                            $conteo += 1;
                            }
                            
                    $progtot += 1;//Se efectua el incremento sobre el conteo de programacion.
                    $RegEjecPro = @mysqli_fetch_array($dsEjecPro,MYSQLI_ASSOC);
                    $RegProgPro = @mysqli_fetch_array($dsProgPro,MYSQLI_ASSOC);
                    }
                    
            if($progtot>0)
                {
                    //Se efectua el ajuste de la programación.
                    $conteo = 0;//Se inicializa la variable de conteo de programacion.
                    
                    while($conteo < 12)
                        {
                            //Recorrido sobre la programación para el ajuste.
                            $Programacion[$conteo] = $Programacion[$conteo]/$progtot;
                            $Ejecucion[$conteo] = $Ejecucion[$conteo]/$progtot;
                            
                            if($Programacion[$conteo])
                                {
                                    //Se evalua que la programación no sea cero.
                                    $Eficacia[$conteo] = ($Ejecucion[$conteo]/$Programacion[$conteo])*100.00;
                                    }
                            else
                                {
                                    //En caso contrario se realiza el ajuste.
                                    $Eficacia[$conteo] = $Ejecucion[$conteo]*100.00;
                                    }
                                    
                            $conteo += 1;
                            }
                    }
            }
            
    function graficador($Eficacia)
        {
            /*
             * Esta funcion genera un grafico de barras apartir de un vector de datos provisto
             * por una consulta del usuario.
             */
            
            global $Identificador; //Variable global con el nombre de la entidad sobre la que se ejecuta la evaluacion.
            global $Leyendas; //Vector global para adquirir las leyendas a mostrar en cada elemento del grafico.
                                    
            $grafico = new Graph(1000,500); //Se crea la instancia del objeto de grafico.
            
            /*
             * El siguiente bloque de parametros, establece las caracteristicas que debe tener
             * el area del grafico y su respectivo formato de atributos.
             */
            $grafico->SetScale('textlin');
            $grafico->SetMargin(40,30,30,40);
            $grafico->xaxis->SetTickLabels($Leyendas); //Se establecen los rotulos para los elementos del eje X. (Ej. Ene, Feb, ...)
            $grafico->xaxis->SetFont(FF_VERDANA,FS_NORMAL,12); //Se establece la fuente, tipo y tamaño.
            $grafico->SetBackgroundGradient('darkblue','silver',2,BGRAD_FRAME);           
            $bplot = new BarPlot($Eficacia); //Se crea la instancia del objeto manejador de datos y se envia el vector de informacion al constructor.                        
            $bplot->SetFillColor('#479CC9'); //Configuramos color de las barras (Este se anula cuando hay degradado).
            $bplot->SetFillGradient('orange','darkred',GRAD_VER); //Se configura el relleno degradado.
            $grafico->Add($bplot); //Añadimos barra de datos al grafico.
            
            $bplot->value->Show(); //Se indica que se desea visualizar los valores numericos de cada categoria.
            
            // Configuracion de los titulos
            $grafico->title->Set('Eficacia de la planeacion para '. $Identificador);
            $grafico->xaxis->title->Set('Eficacia');
            $grafico->yaxis->title->Set('Porcentaje');
            
            $grafico->title->SetFont(FF_VERDANA,FS_BOLD);
            $grafico->title->SetColor('white');
            $grafico->yaxis->title->SetFont(FF_VERDANA,FS_BOLD);
            $grafico->xaxis->title->SetFont(FF_VERDANA,FS_BOLD);
                        
            $grafico->Stroke(); //Se muestra el grafico.            
            }
    
    obtenerEficacia($Periodo); //Llamada a la funcion para generar el vector de eficacias.
    graficador($Eficacia); //Llamada a la función de graficación.    
?>