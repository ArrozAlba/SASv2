<?php
/***********************************************************************************
* @Clase para insertar los datos iniciales cuando no existen empresas registradas
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  28/08/2008
* @autor   Ing. Yesenia Moreno
* @descripcion  Se agregaron los eventos y las opciones de menu
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/mcd/sigesp_dao_mcd_empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_sistema.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_grupo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_evento.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');

class CargarDatos extends ADOdb_Active_Record
{
	public $valido;
	public $mensaje;

	function cargarDatos()
	{
	}
	
	function crearDatos()
	{
		$objEmpresa = new Empresa();
		$objUsuario = new Usuario();
		$objGrupo   = new Grupo();	
			
		// Insertar Empresa por defecto
		$objEmpresa->codempresa = '00001';
		$objEmpresa->nombre     = 'Empresa Prueba';
		$objEmpresa->insertarEmpresa();

		// Insertar Evento por defecto		
		$objEvento = new Evento();		
		$objEvento->codempresa = '00001';
		$objEvento->evento = 'INSERTAR';
		$objEvento->descripcion = 'Incluir un nuevo Registro';
		$objEvento->incluir();
		
		$objEvento = new Evento();		
		$objEvento->codempresa = '00001';
		$objEvento->evento = 'ELIMINAR';
		$objEvento->descripcion = 'Eliminar un Registro existente';
		$objEvento->incluir();
		
		$objEvento = new Evento();		
		$objEvento->codempresa = '00001';
		$objEvento->evento = 'MODIFICAR';
		$objEvento->descripcion = 'Actualizar un Registro existente';
		$objEvento->incluir();
		
		$objEvento = new Evento();		
		$objEvento->codempresa = '00001';
		$objEvento->evento = 'PROCESAR';
		$objEvento->descripcion = 'Procesar un Registro';
		$objEvento->incluir();
				
		$objEvento = new Evento();		
		$objEvento->codempresa = '00001';
		$objEvento->evento = 'REPORTAR';
		$objEvento->descripcion = 'Ejecución de Reporte';
		$objEvento->incluir();
		
		// Insertar Sistema por defecto
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MSG';
		$objSistema->nombre     = 'SEGURIDAD';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MCB';
		$objSistema->nombre     = 'CAJA Y BANCOS';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MCD';
		$objSistema->nombre     = 'CONFIGURACION Y DEFINICIONES DEL SISTEMA';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MCP';
		$objSistema->nombre     = 'CUENTAS POR PAGAR';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MCV';
		$objSistema->nombre     = 'VIATICOS';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MEP';
		$objSistema->nombre     = 'SOLICITUD DE EJECUCION PRESUPUESTARIA';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MFP';
		$objSistema->nombre     = 'FORMULACION DE PRESUPUESTO';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MHR';
		$objSistema->nombre     = 'HOJA DE RUTA';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MIV';
		$objSistema->nombre     = 'INVENTARIO';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MCG';
		$objSistema->nombre     = 'CONTABILIDAD PATRIMONIAL-FISCAL';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MMP';
		$objSistema->nombre     = 'MODIFICACIONES PRESUPUESTARIAS';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MNO';
		$objSistema->nombre     = 'NOMINA';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MOB';
		$objSistema->nombre     = 'OBRAS';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MOC';
		$objSistema->nombre     = 'COMPRAS';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MPB';
		$objSistema->nombre     = 'PROVEEDORES Y BENEFICIARIOS';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MPE';
		$objSistema->nombre     = 'PLANIFICACION ESTRATEGICA';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MPG';
		$objSistema->nombre     = 'CONTABILIDAD PRESUPUESTARIA DE GASTOS';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MPI';
		$objSistema->nombre     = 'CONTABILIDAD PRESUPUESTARIA DE INGRESOS';
		$objSistema->incluir();
		
		$objSistema = new Sistema();		
		$objSistema->codempresa = '00001';
		$objSistema->codsistema = 'MRH';
		$objSistema->nombre     = 'RECURSOS HUMANOS';
		$objSistema->incluir();	

		$codmenu=1;
		// Insertar Menu por defecto		
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Definiciones';
		$objMenu->nomfisico = '';
		$objMenu->codpadre = 0;
		$objMenu->nivel = 1;
		$objMenu->hijo = 1;
		$objMenu->marco = '';
		$objMenu->orden = 1;
		$objMenu->incluir();

		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Procesos';
		$objMenu->nomfisico = '';
		$objMenu->codpadre = 0;
		$objMenu->nivel = 1;
		$objMenu->hijo = 1;
		$objMenu->marco = '';
		$objMenu->orden = 2;
		$objMenu->incluir();

		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Reportes';
		$objMenu->nomfisico = '';
		$objMenu->codpadre = 0;
		$objMenu->nivel = 1;
		$objMenu->hijo = 1;
		$objMenu->marco = '';
		$objMenu->orden = 3;
		$objMenu->incluir();

		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Menu Principal';
		$objMenu->nomfisico = '';
		$objMenu->codpadre = 0;
		$objMenu->nivel = 1;
		$objMenu->hijo = 1;
		$objMenu->marco = '';
		$objMenu->orden = 4;
		$objMenu->incluir();
		
		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Grupo';
		$objMenu->nomfisico = 'sigesp_vis_msg_grupo.html';
		$objMenu->codpadre = 1;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = 'principal';
		$objMenu->orden = 1;
		$objMenu->incluir();
		
		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Usuario';
		$objMenu->nomfisico = 'sigesp_vis_msg_usuario.html';
		$objMenu->codpadre = 1;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = 'principal';
		$objMenu->orden = 2;
		$objMenu->incluir();
		
		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Sistema';
		$objMenu->nomfisico = 'sigesp_vis_msg_sistema.html';
		$objMenu->codpadre = 1;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = 'principal';
		$objMenu->orden = 3;
		$objMenu->incluir();				
		
		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Asignar Usuarios a Grupo';
		$objMenu->nomfisico = 'sigesp_vis_msg_usuariogrupo.html';
		$objMenu->codpadre = 2;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = 'principal';
		$objMenu->orden = 1;
		$objMenu->incluir();				

		$codmenu++;		
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Aplicar Perfil';
		$objMenu->nomfisico = 'sigesp_vis_msg_perfiles.html';
		$objMenu->codpadre = 2;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = 'principal';
		$objMenu->orden = 2;
		$objMenu->incluir();		

		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Cambio de Password';
		$objMenu->nomfisico = 'sigesp_vis_msg_cambiopassword.html';
		$objMenu->codpadre = 2;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = 'principal';
		$objMenu->orden = 3;
		$objMenu->incluir();				

		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Auditoria';
		$objMenu->nomfisico = 'sigesp_vis_msg_auditoria.html';
		$objMenu->codpadre = 3;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = 'principal';
		$objMenu->orden = 1;
		$objMenu->incluir();				

		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Permisos';
		$objMenu->nomfisico = 'sigesp_vis_msg_permisos.html';
		$objMenu->codpadre = 3;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = 'principal';
		$objMenu->orden = 2;
		$objMenu->incluir();				

		$codmenu++;
		$objMenu = new Menu();		
		$objMenu->codempresa = '00001';
		$objMenu->codmenu = $codmenu;
		$objMenu->codsistema = 'MSG';
		$objMenu->nomlogico = 'Volver';
		$objMenu->nomfisico = '../../desktop.html';
		$objMenu->codpadre = 4;
		$objMenu->nivel = 2;
		$objMenu->hijo = 0;
		$objMenu->marco = '_parent';
		$objMenu->orden = 1;
		$objMenu->incluir();				
		
		// Insertar Usuario defecto
		$objUsuario->codempresa    = '00001';
		$objUsuario->codusuario    = 'admin';
		$objUsuario->cedula        = '123';
		$objUsuario->nombre        = 'administrador';
		$objUsuario->apellido       = 'administrador';
		$objUsuario->password      = 'FKvX0oSRuHEz8xsIZVyVN6YLIwI';
		$objUsuario->telefono      = '';
		$objUsuario->email         = '';
		$objUsuario->estatus       = '1';
		$objUsuario->administrador = '1';
		$objUsuario->fecultingreso = '1900/01/01';
		$objUsuario->fecbloqueo    = '1900/01/01';
		$objUsuario->foto          = '';
		$objUsuario->nota          = 'usuario administrador de prueba';
		$objUsuario->insertarUsuario();
		
		$objUsuario->codempresa    = '00001';
		$objUsuario->codusuario    = '--------------------';
		$objUsuario->cedula        = '----------';
		$objUsuario->nombre        = '----------';
		$objUsuario->apellido       = '----------';
		$objUsuario->password      = '';
		$objUsuario->telefono      = '';
		$objUsuario->email         = '';
		$objUsuario->estatus       = '3';
		$objUsuario->administrador = '0';
		$objUsuario->fecultingreso = '1900/01/01';
		$objUsuario->fecbloqueo    = '1900/01/01';
		$objUsuario->foto          = '';
		$objUsuario->nota          = '';
		$objUsuario->incluir();
		
		// Insertar Grupo por defecto
		$objGrupo->codempresa = '00001';
		$objGrupo->codgrupo   = '-----';
		$objGrupo->nombre     = '-----';
		$objGrupo->nota       = '';	
		$objGrupo->seguridad  = false;	
		$objGrupo->incluir();	
		

		// Liberar de la memoria los objetos creados
		unset($objEmpresa);
		unset($objUsuario);
		unset($objGrupo);		
		unset($objSistema);		
		unset($objEvento);		
		unset($objMenu);		
	}
}
?>