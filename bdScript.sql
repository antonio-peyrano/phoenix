/*
Created		02/11/2015
Modified	23/11/2016
Project		Micrositio-Phoenix
Author		Prof. Jesus Antonio Peyrano Luna
Version		v1.0
Database	mySQL 5 
*/


Create table catUsuarios (
	idUsuario Int NOT NULL AUTO_INCREMENT,
	idNivel Int NOT NULL,
	Usuario Varchar(250) NOT NULL,
	Clave Varchar(250) NOT NULL,
	Correo Varchar(250) NOT NULL,
	Pregunta Varchar(250) NOT NULL,
	Respuesta Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxUsuario (idUsuario),
 Primary Key (idUsuario)) ENGINE = MyISAM;

Create table catEstOpe (
	idEstOpe Int NOT NULL AUTO_INCREMENT,
	idObjOpe Int NOT NULL,
	idObjEst Int NOT NULL,
	Nomenclatura Varchar(250) NOT NULL,
	EstOpe Varchar(250) NOT NULL,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEstOpe (idEstOpe),
 Primary Key (idEstOpe)) ENGINE = MyISAM;

Create table catIndicadores (
	idIndicador Int NOT NULL AUTO_INCREMENT,
	Nomenclatura Varchar(250) NOT NULL,
	Indicador Varchar(250) NOT NULL,
	Percentil Float(4,2) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxIndicador (idIndicador),
 Primary Key (idIndicador)) ENGINE = MyISAM;

Create table catProcesos (
	idProceso Int NOT NULL AUTO_INCREMENT,
	Proceso Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxProceso (idProceso),
 Primary Key (idProceso)) ENGINE = MyISAM;

Create table catTEntidades (
	idTEntidad Int NOT NULL AUTO_INCREMENT,
	TEntidad Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxTEntidad (idTEntidad),
 Primary Key (idTEntidad)) ENGINE = MyISAM;

Create table catEntidades (
	idEntidad Int NOT NULL AUTO_INCREMENT,
	idTEntidad Int NOT NULL,
	Entidad Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEntidad (idEntidad),
 Primary Key (idEntidad)) ENGINE = MyISAM;

Create table opProgramas (
	idPrograma Int NOT NULL AUTO_INCREMENT,
	idEntidad Int NOT NULL,
	idObjEst Int NOT NULL,
	idObjOpe Int NOT NULL,
	idEstOpe Int NOT NULL,
	idResponsable Int NOT NULL,
	idSubalterno Int NOT NULL,
	Nomenclatura Varchar(250) NOT NULL,
	Programa Varchar(250) NOT NULL,
	Monto Double(18,2) NOT NULL DEFAULT 0.00,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxPrograma (idPrograma),
 Primary Key (idPrograma)) ENGINE = MyISAM;

Create table relEntPro (
	idRelEntPro Int NOT NULL AUTO_INCREMENT,
	idProceso Int NOT NULL,
	idEntidad Int NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxRelEntPro (idRelEntPro),
 Primary Key (idRelEntPro)) ENGINE = MyISAM;

Create table relIndPro (
	idRelIndPro Int NOT NULL AUTO_INCREMENT,
	idIndicador Int NOT NULL,
	idProceso Int NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxRelIndPro (idRelIndPro),
 Primary Key (idRelIndPro)) ENGINE = MyISAM;

Create table opActividades (
	idActividad Int NOT NULL AUTO_INCREMENT,
	idPrograma Int NOT NULL,
	idUnidad Int NOT NULL,
	Actividad Varchar(250) NOT NULL,
	Monto Double(18,2) NOT NULL DEFAULT 0.00,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxActividad (idActividad),
 Primary Key (idActividad)) ENGINE = MyISAM;

Create table opEjecuciones (
	idEjecucion Int NOT NULL AUTO_INCREMENT,
	idActividad Int NOT NULL,
	Cantidad Float(12,4) NOT NULL,
	Mes Varchar(250) NOT NULL,
	Monto Double(18,2) NOT NULL,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEjecucion (idEjecucion),
 Primary Key (idEjecucion)) ENGINE = MyISAM;

Create table opProgAct (
	idProgAct Int NOT NULL AUTO_INCREMENT,
	idActividad Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxProgAct (idProgAct),
 Primary Key (idProgAct)) ENGINE = MyISAM;

Create table opEjecAct (
	idEjecAct Int NOT NULL AUTO_INCREMENT,
	idActividad Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEjecAct (idEjecAct),
 Primary Key (idEjecAct)) ENGINE = MyISAM;

Create table opProgPro (
	idProgPro Int NOT NULL AUTO_INCREMENT,
	idPrograma Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxProgPro (idProgPro),
 Primary Key (idProgPro)) ENGINE = MyISAM;

Create table opEjecPro (
	idEjecPro Int NOT NULL AUTO_INCREMENT,
	idPrograma Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEjecPro (idEjecPro),
 Primary Key (idEjecPro)) ENGINE = MyISAM;

Create table opProgEst (
	idProgEst Int NOT NULL AUTO_INCREMENT,
	idEstOpe Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxProgEst (idProgEst),
 Primary Key (idProgEst)) ENGINE = MyISAM;

Create table opEjecEst (
	idEjecEst Int NOT NULL AUTO_INCREMENT,
	idEstOpe Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEjecEst (idEjecEst),
 Primary Key (idEjecEst)) ENGINE = MyISAM;

Create table catUnidades (
	idUnidad Int NOT NULL AUTO_INCREMENT,
	Nomenclatura Varchar(250) NOT NULL,
	Unidad Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxUnidades (idUnidad),
 Primary Key (idUnidad)) ENGINE = MyISAM;

Create table catNiveles (
	idNivel Int NOT NULL AUTO_INCREMENT,
	Nivel Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxNivel (idNivel),
 Primary Key (idNivel)) ENGINE = MyISAM;

Create table opUsrTemp (
	idUsrtmp Int NOT NULL AUTO_INCREMENT,
	idNivel Int NOT NULL DEFAULT 3,
	Usuario Varchar(250) NOT NULL,
	Clave Varchar(250) NOT NULL,
	Correo Varchar(250) NOT NULL,
	Pregunta Varchar(250) NOT NULL,
	Respuesta Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxUrstmp (idUsrtmp),
 Primary Key (idUsrtmp)) ENGINE = MyISAM;

Create table opClientes (
	idCliente Int NOT NULL AUTO_INCREMENT,
	idColonia Int NOT NULL,
	Paterno Varchar(250) NOT NULL,
	Materno Varchar(250) NOT NULL,
	Nombre Varchar(250) NOT NULL,
	Calle Varchar(250) NOT NULL,
	Nint Varchar(250),
	Next Varchar(250) NOT NULL,
	RFC Varchar(250) NOT NULL,
	CURP Varchar(250) NOT NULL,
	TelFijo Varchar(250) NOT NULL,
	TelCel Varchar(250),
	Status Int NOT NULL DEFAULT 0,
 Index idxCliente (idCliente),
 Primary Key (idCliente)) ENGINE = MyISAM;

Create table catColonias (
	idColonia Int NOT NULL AUTO_INCREMENT,
	Colonia Varchar(250) NOT NULL,
	CodigoPostal Varchar(250) NOT NULL,
	Ciudad Varchar(250) NOT NULL,
	Estado Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index IdxColonia (idColonia),
 Primary Key (idColonia)) ENGINE = MyISAM;

Create table opAtenciones (
	idAtencion Int NOT NULL AUTO_INCREMENT,
	Tipo Varchar(250) NOT NULL,
	Descripcion Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxAtencion (idAtencion),
 Primary Key (idAtencion)) ENGINE = MyISAM;

Create table catObjEst (
	idObjEst Int NOT NULL AUTO_INCREMENT,
	Nomenclatura Varchar(250) NOT NULL,
	ObjEst Varchar(250) NOT NULL,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxObjEst (idObjEst),
 Primary Key (idObjEst)) ENGINE = MyISAM;

Create table catObjOpe (
	idObjOpe Int NOT NULL AUTO_INCREMENT,
	idObjEst Int NOT NULL,
	Nomenclatura Varchar(250) NOT NULL,
	ObjOpe Varchar(250) NOT NULL,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxObjOpe (idObjOpe),
 Primary Key (idObjOpe)) ENGINE = MyISAM;

Create table opEmpleados (
	idEmpleado Int NOT NULL AUTO_INCREMENT,
	idColonia Int NOT NULL,
	idEntidad Int NOT NULL,
	Paterno Varchar(250) NOT NULL,
	Materno Varchar(250) NOT NULL,
	Nombre Varchar(250) NOT NULL,
	Calle Varchar(250) NOT NULL,
	Nint Varchar(250),
	Next Varchar(250) NOT NULL,
	RFC Varchar(250) NOT NULL,
	CURP Varchar(250) NOT NULL,
	TelFijo Varchar(250) NOT NULL,
	TelCel Varchar(250),
	Status Int NOT NULL DEFAULT 0,
 Index idxEmpleado (idEmpleado),
 Primary Key (idEmpleado)) ENGINE = MyISAM;

Create table catPuestos (
	idPuesto Int NOT NULL AUTO_INCREMENT,
	Puesto Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxPuesto (idPuesto),
 Primary Key (idPuesto)) ENGINE = MyISAM;

Create table relEntPuesto (
	idRelEntPst Int NOT NULL AUTO_INCREMENT,
	idPuesto Int NOT NULL,
	idEntidad Int NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxRelEntPst (idRelEntPst),
 Primary Key (idRelEntPst)) ENGINE = MyISAM;

Create table relProgPro (
	idRelProgPro Int NOT NULL AUTO_INCREMENT,
	idPrograma Int NOT NULL,
	idProceso Int NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxRelProgPro (idRelProgPro),
 Primary Key (idRelProgPro)) ENGINE = MyISAM;

Create table opProgOE (
	idProgOE Int NOT NULL AUTO_INCREMENT,
	idObjEst Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxProgOE (idProgOE),
 Primary Key (idProgOE)) ENGINE = MyISAM;

Create table opEjecOE (
	idEjecOE Int NOT NULL AUTO_INCREMENT,
	idObjEst Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEjecOE (idEjecOE),
 Primary Key (idEjecOE)) ENGINE = MyISAM;

Create table opEjecOO (
	idEjecOO Int NOT NULL AUTO_INCREMENT,
	idObjOpe Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEjecOO (idEjecOO),
 Primary Key (idEjecOO)) ENGINE = MyISAM;

Create table opProgOO (
	idProgOO Int NOT NULL AUTO_INCREMENT,
	idObjOpe Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxProgOO (idProgOO),
 Primary Key (idProgOO)) ENGINE = MyISAM;

Create table opEficAct (
	idEficAct Int NOT NULL AUTO_INCREMENT,
	idActividad Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEficAct (idEficAct),
 Primary Key (idEficAct)) ENGINE = MyISAM;

Create table opEficPro (
	idEficPro Int NOT NULL AUTO_INCREMENT,
	idPrograma Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEficPro (idEficPro),
 Primary Key (idEficPro)) ENGINE = MyISAM;

Create table opEficEst (
	idEficEst Int NOT NULL AUTO_INCREMENT,
	idEstOpe Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEficEst (idEficEst),
 Primary Key (idEficEst)) ENGINE = MyISAM;

Create table opEficOE (
	idEficOE Int NOT NULL AUTO_INCREMENT,
	idObjEst Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEficOE (idEficOE),
 Primary Key (idEficOE)) ENGINE = MyISAM;

Create table opEficOO (
	idEficOO Int NOT NULL AUTO_INCREMENT,
	idObjOpe Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEficOO (idEficOO),
 Primary Key (idEficOO)) ENGINE = MyISAM;

Create table catConfiguraciones (
	idConfiguracion Int NOT NULL AUTO_INCREMENT,
	Optimo Double(8,2) NOT NULL DEFAULT 90.00,
	Tolerable Double(8,2) NOT NULL DEFAULT 80.00,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxConfiguracion (idConfiguracion),
 Primary Key (idConfiguracion)) ENGINE = MyISAM;

Create table opEvidencias (
	idEvidencia Int NOT NULL AUTO_INCREMENT,
	idEjecucion Int NOT NULL,
	RutaURL Varchar(250) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEvidencia (idEvidencia),
 Primary Key (idEvidencia)) ENGINE = MyISAM;

Create table catVehiculos (
	idVehiculo Int NOT NULL AUTO_INCREMENT,
	idEntidad Int NOT NULL,
	NumEconomico Varchar(50) NOT NULL,
	NumPlaca Varchar(50) NOT NULL,
	Color Varchar(70) NOT NULL,
	Marca Varchar(70) NOT NULL,
	Modelo Varchar(70) NOT NULL,
	TMotor Varchar(70) NOT NULL,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxVehiculo (idVehiculo),
 Primary Key (idVehiculo)) ENGINE = MyISAM;

Create table opProgGas (
	idProgGas Int NOT NULL AUTO_INCREMENT,
	idEntidad Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxProgGas (idProgGas),
 Primary Key (idProgGas)) ENGINE = MyISAM;

Create table opEjecGas (
	idEjecGas Int NOT NULL AUTO_INCREMENT,
	idEntidad Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEjecGas (idEjecGas),
 Primary Key (idEjecGas)) ENGINE = MyISAM;

Create table opEficGas (
	idEficGas Int NOT NULL AUTO_INCREMENT,
	idEntidad Int NOT NULL,
	Enero Float(18,4) NOT NULL DEFAULT 0,
	Febrero Float(18,4) NOT NULL DEFAULT 0,
	Marzo Float(18,4) NOT NULL DEFAULT 0,
	Abril Float(18,4) NOT NULL DEFAULT 0,
	Mayo Float(18,4) NOT NULL DEFAULT 0,
	Junio Float(18,4) NOT NULL DEFAULT 0,
	Julio Float(18,4) NOT NULL DEFAULT 0,
	Agosto Float(18,4) NOT NULL DEFAULT 0,
	Septiembre Float(18,4) NOT NULL DEFAULT 0,
	Octubre Float(18,4) NOT NULL DEFAULT 0,
	Noviembre Float(18,4) NOT NULL DEFAULT 0,
	Diciembre Float(18,4) NOT NULL DEFAULT 0,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxEficGas (idEficGas),
 Primary Key (idEficGas)) ENGINE = MyISAM;

Create table opMovGas (
	idMovGas Int NOT NULL AUTO_INCREMENT,
	idEjecGas Int NOT NULL,
	Cantidad Float(12,4) NOT NULL,
	Tiempo Varchar(50) NOT NULL,
	Mes Varchar(250) NOT NULL,
	Monto Double(18,2) NOT NULL,
	Periodo Varchar(4) NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxMovGas (idMovGas),
 Primary Key (idMovGas)) ENGINE = MyISAM;

Create table opFichasProcesos (
	idFicha Int NOT NULL AUTO_INCREMENT,
	idProceso Int NOT NULL,
	Clave Varchar(250) NOT NULL,
	nEdicion Int NOT NULL DEFAULT 0,
	FechaCreacion Varchar(50) NOT NULL,
	FechaEdicion Varchar(50),
	Actividades Varchar(1500) NOT NULL,
	Responsable Varchar(1500) NOT NULL,
	MisionProceso Varchar(1500) NOT NULL,
	Entrada Varchar(1500) NOT NULL,
	Salida Varchar(1500) NOT NULL,
	relProcesos Varchar(1500) NOT NULL,
	necRecursos Varchar(1500) NOT NULL,
	regArchivos Varchar(1500) NOT NULL,
	docAplicables Varchar(1500) NOT NULL,
	status Int NOT NULL DEFAULT 0,
 Index idxFichasProcesos (idFicha),
 Primary Key (idFicha)) ENGINE = MyISAM;

Create table relIndFicha (
	idRelIndFicha Int NOT NULL AUTO_INCREMENT,
	idIndicador Int NOT NULL,
	idFicha Int NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxRelIndFicha (idRelIndFicha),
 Primary Key (idRelIndFicha)) ENGINE = MyISAM;

Create table relUsrEmp (
	idRelUsrEmp Int NOT NULL AUTO_INCREMENT,
	idEmpleado Int NOT NULL,
	idUsuario Int NOT NULL,
	Status Int NOT NULL DEFAULT 0,
 Index idxRelUsrEmp (idRelUsrEmp),
 Primary Key (idRelUsrEmp)) ENGINE = MyISAM;