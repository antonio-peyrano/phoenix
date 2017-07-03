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

/***********************************************************************************************************
 * Archivo: conectividad.class.php * Descripcion: Clase que contiene el codigo para la creacion de objetos *
 *                                 * que permitan la manipulacion de registros en una base de datos mySQL  *
 ***********************************************************************************************************
 * Desarrollador: Mtro. Jesus Antonio Peyrano Luna * Ultima modificacion: 27/09/2016                       *
 ***********************************************************************************************************/
    include_once ($_SERVER['DOCUMENT_ROOT']."/phoenix/php/backend/utilidades/codificador.class.php"); //Se carga la referencia del codificador de cadenas.
    
	class mySQL_conexion
		{
			/*
			 * Esta clase tiene como funci�n establecer los parametros para la conexi�n con la base de datos
			 * de datos e interactuar con la informaci�n.
			 */
			 private $conexion = null; 		#Variable de control de conexi�n (True = Conexi�n OK / False = Falla).
			 private $bdConexion = null;	#Variable de control de base de datos (True = Localizada / False = Falla).
			 private $dataset = null;		#Variable de control para la tupla de datos.
			 
			 private $bdName = '';			#El nombre asignado de la base de datos a conectarse.
			 private $serverName = '';		#El nombre del servidor o su direccionamiento IP.
			 private $userName = '';		#El usuario con el que haremos conexi�n de la BD.
			 private $userPassword = '';	#La clave asignada del usuario para acceder a la BD.
			 
			function __construct($user, $pass, $server, $bd)
				{
					/*
					 * Esta funci�n inicializa los parametros para interactuar con las funciones de la clase.
					 */
				    $objCodificador = new codificador();
					$this->bdName = $objCodificador->decrypt($bd, "ouroboros"); #El nombre asignado de la base de datos a conectarse.
					$this->serverName = $objCodificador->decrypt($server, "ouroboros");	#El nombre del servidor o su direccionamiento IP.
					$this->userName = $objCodificador->decrypt($user, "ouroboros");		#El usuario con el que haremos conexi�n de la BD.
					$this->userPassword = $objCodificador->decrypt($pass, "ouroboros");	#La clave asignada del usuario para acceder a la BD. 
					}
					
			 function conectar($consulta)
				{
					/*
					 * Esta función ejecuta las instrucciones necesarias para conectar con la base de datos
					 * asi como obtener o modificar la informaci�n mediante la consulta sugerida.
					 */
					 
					$this->conexion = @mysql_connect($this->serverName, $this->userName, $this->userPassword);
					
					if(!$this->conexion)
						{
							//En caso de ocurrir un error con la entrada a la base de datos
							//se notifica al usuario por medio de un mensaje en pantalla.
							die('No pudo establecerse la conexión con la BD: ' . mysql_error());
							}
					else
						{
							//En caso de obtener una conexion satisfactoria con la base de datos
							//se procede a la ejecución de las instrucciones.
							$this->bdConexion = mysql_select_db($this->bdName, $this->conexion);
							
							if(!$this->bdConexion)
								{
									//En caso de ocurrir un error con la selecci�n de la base de datos
									//se notifica al usuario por medio de un mensaje en pantalla.								
									die ('No se puede usar '. $this->bdName .': '. mysql_error());
									}
							else
								{
									//En caso de obtener control de la base de datos.
									$this->dataset = @mysql_query($consulta, $this->conexion);
									}
							}

					/*mysql_free_result($this->dataset);*/
					mysql_close($this->conexion); //Se cierra la conexion con la base de datos.
					return $this->dataset;
					}
			}
?>