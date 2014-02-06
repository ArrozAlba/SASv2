<?php
/***********************************************************************************
* @Clase para insertar los datos iniciales cuando no existen empresas registradas
* @fecha de creación: 30/09/2008
* @autor: Ing. Gusmary Balza
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_evento.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_grupo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistema.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_menu.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_derechosusuario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_permisosinternos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_pais.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_estado.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_municipio.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_parroquia.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_moneda.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/soc/sigesp_dao_soc_modalidadclausulas.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cxp/sigesp_dao_cxp_clasificador_rd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_banco_sigecof.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/rpc/sigesp_dao_rpc_tipo_organizacion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/rpc/sigesp_dao_rpc_especialidad.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/rpc/sigesp_dao_rpc_proveedor.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/rpc/sigesp_dao_rpc_beneficiario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_operacion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_fuentefinan.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_unidadadmin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spi/sigesp_dao_spi_operacion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_componente.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/scb/sigesp_dao_scb_concepto.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/scb/sigesp_dao_scb_tipocuenta.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/scb/sigesp_dao_scb_banco.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/scb/sigesp_dao_scb_cuentabanco.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/scb/sigesp_dao_scb_cartaorden.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_conservacion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_rotulacion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_causa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_situacioncontable.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_condicioncompra.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_grupo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_subgrupo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_seccion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/saf/sigesp_dao_saf_metodo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_dedicacion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_metodobanco.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_tipopersonal.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_tipopersonalsss.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sno/sigesp_dao_sno_rango.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/soc/sigesp_dao_soc_ordencompra.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/cfg/sigesp_dao_cfg_procedencia.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro1.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro2.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro3.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro4.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro5.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_dtfuentefin.php');

class CargarDatos extends ADOdb_Active_Record
{
	public $valido;
	public $mensaje;

	function cargarDatos()
	{				
		
	}
	
	function obtenerDatos()
	{
		$this->crearDatos(); //empresa,usuario,grupo,sistemas,eventos, menu
		$this->crearDatosSigesp(); 
		$this->crearDatosRpc();
		$this->crearDatosSpg();
		$this->crearDatosSoc();
		$this->crearDatosSpi();
		$this->crearDatosProcedencia();
		$this->crearDatosSaf();
		$this->crearDatosBanco();
		$this->crearDatosNomina();
		$this->crearDatosSnoComponente();
		$this->crearDatosSnoRango();		
	}
	
	function crearDatos()
	{
		$objEmpresa = new Empresa();
		$objUsuario = new Usuario();
		$objGrupo   = new Grupo();
		$objSistema = new Sistema();
		$objMenu    = new Menu();
		$objEvento = new Evento();	
		
		//Insertar Empresa por defecto
		$objEmpresa->codemp    = '0001';
		$objEmpresa->nombre    = 'SIGESP CA';
		$objEmpresa->titulo    = 'Sigesp CA';
		$objEmpresa->sigemp    = 'SIGESP';
		$objEmpresa->direccion = 'Urbanizacion Del Este';
		$objEmpresa->telemp    = '02512547643';
		$objEmpresa->faxemp    = '02512547643';
		$objEmpresa->email     = 'sigesp@gmail.com';
		$objEmpresa->website   = 'sigespweb@sigesp.com';
		$objEmpresa->m01       = 1;
		$objEmpresa->m02       = 1;
		$objEmpresa->m03       = 1;
		$objEmpresa->m04       = 1;
		$objEmpresa->m05       = 1;
		$objEmpresa->m06       = 1;
		$objEmpresa->m07       = 1;
		$objEmpresa->m08       = 1;
		$objEmpresa->m09       = 1;
		$objEmpresa->m10       = 1;
		$objEmpresa->m11       = 1;
		$objEmpresa->m12       = 1;
		$objEmpresa->periodo   = '1900-01-01';
		$objEmpresa->vali_nivel = 1;
		$objEmpresa->esttipcont = 1;
		$objEmpresa->formpre    = '999-99-99-99';
		$objEmpresa->formcont   = '999-99-99-99';
		$objEmpresa->formplan   = '999-99-99-99';
		$objEmpresa->formspi    = '999-99-99-99';
		$objEmpresa->activo     = '1';
		$objEmpresa->pasivo     = '2';
		$objEmpresa->ingreso    = '3';
		$objEmpresa->gasto      = '4';
		$objEmpresa->resultado  = '5';
		$objEmpresa->capital    = '7';
		$objEmpresa->c_resultad = '5010201000000';
		$objEmpresa->c_resultan = '5010201000000';
		$objEmpresa->orden_d    = '1';
		$objEmpresa->orden_h    = '2';
		$objEmpresa->soc_gastos = '10101010101';
		$objEmpresa->soc_servic = '10101010101';
		$objEmpresa->activo_h   = '11';
		$objEmpresa->pasivo_h   = '22';
		$objEmpresa->resultado_h = '12';
		$objEmpresa->ingreso_f   = '1';
		$objEmpresa->gasto_f     = '2';
		$objEmpresa->ingreso_p   = '2';
		$objEmpresa->gasto_p     = '1'; 
		$objEmpresa->logo        = '';
		$objEmpresa->numniv      = 3;
		$objEmpresa->nomestpro1  = 'Proyecto y/o Acciones Centralizadas';
		$objEmpresa->nomestpro2  = 'Acciones Especificas';
		$objEmpresa->nomestpro3  = 'Otros.';
		$objEmpresa->nomestpro4  = '';
		$objEmpresa->nomestpro5  = '';
		$objEmpresa->estvaltra   = 1;
		$objEmpresa->estmodape   = 0;
		$objEmpresa->estdesiva   = 0;
		$objEmpresa->estprecom   = 0;
		$objEmpresa->codorgsig   = '';
		$objEmpresa->salinipro   = 0;
		$objEmpresa->salinieje   = 0;
		$objEmpresa->numordcom   = 0;
		$objEmpresa->numordser   = 0;
		$objEmpresa->numsolpag   = 0;
		$objEmpresa->estmodest   = 1;
		$objEmpresa->numlicemp   = '0000000000000000000000000';
		$objEmpresa->modageret   = 'B';
		$objEmpresa->socbieser   = 1;
		$objEmpresa->concomiva   = '';
		$objEmpresa->estmodiva   = 0;
		$objEmpresa->cedben      = '';
		$objEmpresa->nomben      = '';
		$objEmpresa->scctaben    = '';
		$objEmpresa->diacadche   = '';
		$objEmpresa->nroivss     = '';
		$objEmpresa->nomrep      = '';
		$objEmpresa->cedrep      = '';
		$objEmpresa->telfrep     = '';
		$objEmpresa->cargorep    = '';
		$objEmpresa->estretiva   = 'B';
		$objEmpresa->confinstr   = 'N';		
		$objEmpresa->estmanant   = '0';	
		$objEmpresa->insertarEmpresa();
		
		//Insertar Usuario defecto
		$objUsuario->codemp   = '0001';
		$objUsuario->codusu   = 'sigesp';
		$objUsuario->cedusu   = '123';
		$objUsuario->nomusu   = 'administrador';
		$objUsuario->apeusu   = 'administrador';
		$objUsuario->pwdusu   = 'FKvX0oSRuHEz8xsIZVyVN6YLIwI';
		$objUsuario->telusu   = '';
		$objUsuario->email    = '';
		$objUsuario->nota     = 'usuario administrador de prueba';
		$objUsuario->actusu   = 1;
		$objUsuario->blkusu   = 0;
		$objUsuario->admusu   = 1;
		$objUsuario->ultingusu = '1900/01/01';
		$objUsuario->fecblousu = '1900/01/01';
		$objUsuario->fotousu   = '';		
		$objUsuario->incluir();
		
		$objUsuario = new Usuario();
		$objUsuario->codemp  = '0001';
		$objUsuario->codusu  = '--------------------';
		$objUsuario->cedusu  = '--------';
		$objUsuario->nomusu  = '----------';
		$objUsuario->apeusu  = '----------';
		$objUsuario->pwdusu  = '';
		$objUsuario->telusu  = '';
		$objUsuario->email   = '';
		$objUsuario->actusu  = 1;
		$objUsuario->blkusu  = 0;
		$objUsuario->admusu  = 1;
		$objUsuario->ultingusu = '1900/01/01';
		$objUsuario->fecblousu = '1900/01/01';
		$objUsuario->fotousu   = '';
		$objUsuario->nota      = '';
		$objUsuario->incluir();		
		
		// Insertar Grupo por defecto
		$objGrupo->codemp = '0001';
		$objGrupo->nomgru   = '-----';
		$objGrupo->nota       = '';	
		//$objGrupo->seguridad  = false;	
		$objGrupo->incluir();	
		
		// Insertar Sistema por defecto
		//Herramientas
		$objSistema->codsis = 'SSS';
		$objSistema->nomsis = 'Seguridad';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modseguridad.png';
	//	$objSistema->accsis = 'sss/sigespwindow_blank.php';
		$objSistema->accsis = 'vista/sss/sigesp_vis_sss_principal.html';
		$objSistema->tipsis = '4';
		$objSistema->ordsis = '1';
		$objSistema->incluir();		
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'APR';
		$objSistema->nomsis = 'Apertura';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modapertura.gif';
		$objSistema->accsis = '#';
		$objSistema->tipsis = '4';
		$objSistema->ordsis = '3';
		$objSistema->incluir();	

		$objSistema = new Sistema();
		$objSistema->codsis = 'INS';
		$objSistema->nomsis = 'Instala';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modinstala.png';
		$objSistema->accsis = 'ins/sigespwindow_blank.php';
		$objSistema->tipsis = '4';
		$objSistema->ordsis = '2';
		$objSistema->incluir();	

		//Personal
		$objSistema = new Sistema();
		$objSistema->codsis = 'SNR';
		$objSistema->nomsis = 'Nómina - Recursos Humanos';
		$objSistema->estsis = '0';
		$objSistema->imgsis = '';
		$objSistema->accsis = '#';
		$objSistema->tipsis = '3';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	

		$objSistema = new Sistema();
		$objSistema->codsis = 'SNO';
		$objSistema->nomsis = 'Nómina';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modnomina.gif';
		$objSistema->accsis = 'sno/sigespwindow_blank.php';
		$objSistema->tipsis = '3';
		$objSistema->ordsis = '2';
		$objSistema->incluir();	
			
		$objSistema = new Sistema();
		$objSistema->codsis = 'SRH';
		$objSistema->nomsis = 'Recursos Humanos';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modrecursoshumanos.png';
		$objSistema->accsis = 'srh/pages/vistas/pantallas/sigespwindow_blank.php';
		$objSistema->tipsis = '3';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	

		$objSistema->codsis = 'SPS';
		$objSistema->nomsis = 'Prestaciones Sociales';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modprestacionessociales.gif';
		$objSistema->accsis = '#';
		$objSistema->tipsis = '3';
		$objSistema->ordsis = '0';
		$objSistema->incluir();		
		
		//Auxiliares
		$objSistema = new Sistema();
		$objSistema->codsis = 'RPC';
		$objSistema->nomsis = 'Proveedores y Beneficiarios';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modproveedores.gif';
		$objSistema->accsis = 'rpc/sigespwindow_blank.php';
		$objSistema->tipsis = '2';
		$objSistema->ordsis = '1';
		$objSistema->incluir();	

		$objSistema = new Sistema();
		$objSistema->codsis = 'SIV';
		$objSistema->nomsis = 'Inventario';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modinventario.gif';
		$objSistema->accsis = 'siv/sigespwindow_blank.php';
		$objSistema->tipsis = '2';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SOB';
		$objSistema->nomsis = 'Obras';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modobras.gif';
		$objSistema->accsis = '#';
		$objSistema->tipsis = '2';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SCV';
		$objSistema->nomsis = 'Control de Viáticos';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modviatico.gif';
		$objSistema->accsis = 'cv/sigespwindow_blank.php';
		$objSistema->tipsis = '2';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SAF';
		$objSistema->nomsis = 'Activos Fijos';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modactivos.png';
		$objSistema->accsis = 'saf/sigespwindow_blank.php';
		$objSistema->tipsis = '2';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		//Administrativos
		$objSistema = new Sistema();
		$objSistema->codsis = 'CXP';
		$objSistema->nomsis = 'Cuentas por Pagar';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modcuentaspagar.gif';
		$objSistema->accsis = 'cxp/sigespwindow_blank.php';
		$objSistema->tipsis = '5';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SCB';
		$objSistema->nomsis = 'Banco';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modbanco.gif';
		$objSistema->accsis = 'scb/sigespwindow_blank.php';
		$objSistema->tipsis = '5';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SEP';
		$objSistema->nomsis = 'Solicitud de Ejecución Presupuestaria';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modsolicitudpresupuestaria.gif';
		$objSistema->accsis = 'sep/sigespwindow_blank.php';
		$objSistema->tipsis = '5';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SOC';
		$objSistema->nomsis = 'Ordenes de Compra';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modsolicitudpresupuestaria.gif';
		$objSistema->accsis = 'sep/sigespwindow_blank.php';
		$objSistema->tipsis = '5';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'MIS';
		$objSistema->nomsis = 'Integrador Sigesp';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modintegrador.png';
		$objSistema->accsis = 'mis/sigespwindow_blank.php';
		$objSistema->tipsis = '5';
		$objSistema->ordsis = '0';
		$objSistema->incluir();	
		
		//Principales
		$objSistema = new Sistema();
		$objSistema->codsis = 'CFG';
		$objSistema->nomsis = 'Configuración';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modconfiguracion.gif';
		$objSistema->accsis = 'cfg/index.php';
		$objSistema->tipsis = '1';
		$objSistema->ordsis = '1';
		$objSistema->incluir();	
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SCF';
		$objSistema->nomsis = 'Sistema de Contabilidad Fiscal';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modcontabilidadfiscal.gif';
		$objSistema->accsis = '#';
		$objSistema->tipsis = '1';
		$objSistema->ordsis = '0';
		$objSistema->incluir();
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SCG';
		$objSistema->nomsis = 'Sistema de Contabilidad General';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modcontabilidad.gif';
		$objSistema->accsis = '#';
		$objSistema->tipsis = '1';
		$objSistema->ordsis = '0';
		$objSistema->incluir();
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SPG';
		$objSistema->nomsis = 'Presupuesto de Gastos';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modpresupuestogasto.gif';
		$objSistema->accsis = 'cfg/index.php';
		$objSistema->tipsis = '1';
		$objSistema->ordsis = '0';
		$objSistema->incluir();
		
		$objSistema = new Sistema();
		$objSistema->codsis = 'SPI';
		$objSistema->nomsis = 'Presupuesto de Ingresos';
		$objSistema->estsis = '1';
		$objSistema->imgsis = 'sigesp_img_modpresupuestoingreso.gif';
		$objSistema->accsis = 'cfg/index.php';
		$objSistema->tipsis = '1';
		$objSistema->ordsis = '0';
		$objSistema->incluir();		
		
		//Insertar Menus
		//De seguridad
		$objMenu = new Menu();
		$objMenu->codmenu   = '1';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Definiciones';
		$objMenu->nomfisico = '';
		$objMenu->codpadre  = 0;
		$objMenu->nivel     = 1;
		$objMenu->hijo      = 1;
		$objMenu->marco     = '';
		$objMenu->orden     = 1;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo   = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '2';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Procesos';
		$objMenu->nomfisico = '';
		$objMenu->codpadre  = 0;
		$objMenu->nivel     = 1;
		$objMenu->hijo      = 1;
		$objMenu->marco     = '';
		$objMenu->orden     = 2;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '3';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Reportes';
		$objMenu->nomfisico = '';
		$objMenu->codpadre  = 0;
		$objMenu->nivel     = 1;
		$objMenu->hijo      = 1;
		$objMenu->marco     = '';
		$objMenu->orden     = 3;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '4';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Menú Principal';
		$objMenu->nomfisico = '';
		$objMenu->codpadre  = 0;
		$objMenu->nivel     = 1;
		$objMenu->hijo      = 1;
		$objMenu->marco     = '';
		$objMenu->orden     = 4;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '5';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Grupo';
		$objMenu->nomfisico = 'sigesp_vis_sss_grupo.html';
		$objMenu->codpadre  = 1;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 1;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '6';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Usuario';
		$objMenu->nomfisico = 'sigesp_vis_sss_usuario.html';
		$objMenu->codpadre  = 1;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 2;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '7';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Sistema';
		$objMenu->nomfisico = 'sigesp_vis_sss_sistema.html';
		$objMenu->codpadre  = 1;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 3;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '8';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Aplicar Perfil';
		$objMenu->nomfisico = 'sigesp_vis_sss_perfiles.html';
		$objMenu->codpadre  = 2;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 1;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '9';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Asignar Nóminas a Usuario';
		$objMenu->nomfisico = 'sigesp_vis_sss_nominasusuario.html';
		$objMenu->codpadre  = 2;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 2;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '10';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Asignar Presupuestos a Usuario';
		$objMenu->nomfisico = 'sigesp_vis_msg_presupuestosusuario.html';
		$objMenu->codpadre  = 2;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 3;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '11';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Asignar Unidades Ejecutoras a Usuario';
		$objMenu->nomfisico = 'sigesp_vis_sss_unidadesusuario.html';
		$objMenu->codpadre  = 2;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 4;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '12';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Asignar Constantes a Usuario';
		$objMenu->nomfisico = 'sigesp_vis_sss_constantesusuario.html';
		$objMenu->codpadre  = 2;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 5;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '13';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Asignar Personal a Usuario';
		$objMenu->nomfisico = 'sigesp_vis_sss_personalusuario.html';
		$objMenu->codpadre  = 2;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 6;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '14';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Transferir Usuario y Permisología';
		$objMenu->nomfisico = 'sigesp_vis_sss_transferirusuario.html';
		$objMenu->codpadre  = 2;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 7;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '15';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Cambio de Password';
		$objMenu->nomfisico = 'sigesp_vis_sss_cambiopassword.html';
		$objMenu->codpadre  = 2;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 8;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '16';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Auditoría';
		$objMenu->nomfisico = 'sigesp_vis_sss_auditoria.html';
		$objMenu->codpadre  = 3;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 1;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '17';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Permisos';
		$objMenu->nomfisico = 'sigesp_vis_sss_permisos.html';
		$objMenu->codpadre  = 3;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 2;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '18';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Traspasos';
		$objMenu->nomfisico = 'sigesp_vis_sss_traspasos.html';
		$objMenu->codpadre  = 3;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = 'principal';
		$objMenu->orden     = 3;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		$objMenu = new Menu();
		$objMenu->codmenu   = '19';
		$objMenu->codsis    = 'SSS';
		$objMenu->nomlogico = 'Volver';
		$objMenu->nomfisico = '../../desktop.html';
		$objMenu->codpadre  = 4;
		$objMenu->nivel     = 2;
		$objMenu->hijo      = 0;
		$objMenu->marco     = '_parent';
		$objMenu->orden     = 1;
		$objMenu->visible   = 1;
		$objMenu->enabled   = 1;
		$objMenu->leer      = 1;
		$objMenu->incluir   = 1;
		$objMenu->cambiar   = 1;
		$objMenu->eliminar  = 1;
		$objMenu->imprimir  = 1;
		$objMenu->administrativo = 1;
		$objMenu->anular         = 1;
		$objMenu->ejecutar       = 1;
		$objMenu->ayuda          = 1;
		$objMenu->cancelar       = 1;
		$objMenu->enviarcorreo    = 1;
		$objMenu->incluir();
		
		//Insertar permisos internos
		$objPermisosInt = new PermisosInternos();
		$objPermisosInt->codemp = '0001';
		$objPermisosInt->codusu = 'sigesp';
		$objPermisosInt->codsis = 'SSS';
		$objPermisosInt->codintper = '---------------------------------';
		$objPermisosInt->incluir();
		
		
		//Insertar derechos de usuario
		$objPermisos = new DerechosUsuario();
		$objPermisos->codemp = '0001';
		$objPermisos->codusu = 'sigesp';
		//$objPermisos->codsis = 'SSS';
		$objPermisos->codintper = '---------------------------------';
		$objPermisos->insertarPermisosGlobales();
		
		// Insertar Evento por defecto			
		$objEvento->evento = 'INSERTAR';
		$objEvento->deseve = 'Incluir un nuevo Registro';
		$objEvento->incluir();		
		
		$objEvento = new Evento();
		$objEvento->evento = 'ELIMINAR';
		$objEvento->deseve = 'Eliminar un Registro existente';
		$objEvento->incluir();		
			
		$objEvento = new Evento();
		$objEvento->evento = 'MODIFICAR';
		$objEvento->deseve = 'Actualizar un Registro existente';
		$objEvento->incluir();		
		
		$objEvento = new Evento();
		$objEvento->evento = 'PROCESAR';
		$objEvento->deseve = 'Procesar un Registro';
		$objEvento->incluir();				

		$objEvento = new Evento();
		$objEvento->evento = 'REPORTAR';
		$objEvento->deseve = 'Ejecución de Reporte';
		$objEvento->incluir();		

		$objEvento = new Evento();
		$objEvento->evento = 'CONSULTAR';
		$objEvento->deseve = 'Ejecución de consulta de cualquier registro';
		$objEvento->incluir();		
		
		//Liberar de la memoria los objetos creados
		unset($objEmpresa);
		unset($objUsuario);
		unset($objGrupo);	
		unset($objSistema);
		unset($objMenu);
		unset($objEvento);		
		
	}
	
	function crearDatosSigesp()
	{
		$objPais         = new Pais();
		$objEstado       = new Estado();
		$objMunicipio    = new Municipio();
		$objParroquia    = new Parroquia();
		$objMoneda       = new Moneda();
		$objModalidad    = new ModalClausula();
		$objClasificador = new ClasificadorRd();
		$objBancoSigecof = new BancoSigecof();
		
		//Insertar pais por defecto
		$objPais->codpai = '---';
		$objPais->despai = '---seleccione---';
		$objPais->incluir();
		
		//Insertar estado por defecto
		$objEstado->codpai = '---';
		$objEstado->codest = '---';
		$objEstado->desest = '---seleccione---';
		$objEstado->incluir();
		
		//Insertar municipio por defecto
		$objMunicipio->codpai = '---';
		$objMunicipio->codest = '---';
		$objMunicipio->codmun = '---';
		$objMunicipio->denmun = '---seleccione---';
		$objMunicipio->incluir();
		
		//Insertar parroquia por defecto
		$objParroquia->codpai = '---';
		$objParroquia->codest = '---';
		$objParroquia->codmun = '---';
		$objParroquia->codpar = '---';
		$objParroquia->denpar = '---seleccione---';
		$objParroquia->incluir();
		
		//Insertar moneda por defecto
		$objMoneda->codmon = '---';
		$objMoneda->denmon = '----seleccione----';
		$objMoneda->imamon = '------';
		$objMoneda->codpai = '---';
		$objMoneda->tascam = 0;
		$objMoneda->estmonpri = 0;
		$objMoneda->incluir();
		
		//insertar modalidad de cláusula por defecto
		$objModalidad->codemp = '0001';
		$objModalidad->codtipmod = '--';
		$objModalidad->denmodcla = '---seleccione---';
		$objModalidad->incluir();
		
		//Insertar clasificador de recepción de documentos por defecto
		$objClasificador->codcla    = '--';
		$objClasificador->dencla    = '---seleccione---';
		$objClasificador->sc_cuenta = '';
		$objClasificador->incluir();
		
		//Insertar banco sigecof por defecto
		$objBancoSigecof->codbansig = '---';
		$objBancoSigecof->denbansig = '---seleccione---';
		$objBancoSigecof->incluir();
		
		unset($objPais);
		unset($objEstado);
		unset($objMunicipio);
		unset($objParroquia);
		unset($objMoneda);
		unset($objModalidad);
		unset($objClasificador);
		unset($objBancoSigecof);		
	}	
	
	function crearDatosRpc()
	{
		$objTipoOrg      = new TipoOrganizacion();
		$objEspecialidad = new Especialidad();
		$objProveedor    = new Proveedor();
		$objBeneficiario = new Beneficiario();

		//Insertar tipo de organización por defecto
		$objTipoOrg->codtipoorg = '--';
		$objTipoOrg->dentipoorg = '---seleccione---';
		$objTipoOrg->incluir();
		
		//Insertar especialidad por defecto
		$objEspecialidad->codesp = '---';
		$objEspecialidad->denesp = '---seleccione---';
		$objEspecialidad->incluir();
		
		//Insertar proveedor por defecto
		$objProveedor->codemp = '0001';
		$objProveedor->cod_pro = '----------';
		$objProveedor->nompro = 'Ninguno';
		$objProveedor->dirpro = '-';
		$objProveedor->telpro = '';
		$objProveedor->faxpro = '';
		$objProveedor->nacpro = '';
		$objProveedor->rifpro = '';
		$objProveedor->nitpro = '';
		$objProveedor->fecreg = '1900-01-01';
		$objProveedor->capital = 0;
		$objProveedor->sc_cuenta = '';
		$objProveedor->obspro = '';
		$objProveedor->estpro = 1;
		$objProveedor->estcon = 0; 
		$objProveedor->estaso = 0;
		$objProveedor->ocei_fec_reg = '1900-01-01';
		$objProveedor->ocei_no_reg = '';
		$objProveedor->monmax = 0;
		$objProveedor->cedrep = '';
		$objProveedor->nomreppro = '';
		$objProveedor->emailrep = '';
		$objProveedor->carrep = '';
		$objProveedor->registro = '';
		$objProveedor->nro_reg = '';
		$objProveedor->tomo_reg = '';
		$objProveedor->folreg = '';
		$objProveedor->fecregmod = '1900-01-01';
		$objProveedor->regmod = '';
		$objProveedor->nummod = '';
		$objProveedor->tommod = '';
		$objProveedor->folmod = '';
		$objProveedor->inspector = 0;
		$objProveedor->foto = '';
		$objProveedor->codbansig = '---';
		$objProveedor->codban = '---';
		$objProveedor->codmon = '---';
		$objProveedor->codtipoorg = '--';
		$objProveedor->codesp = '---';
		$objProveedor->ctaban = '---';
		$objProveedor->numlic = '';
		$objProveedor->fenvenrnc = '1900-01-01'; 
		$objProveedor->numregsso = '';
		$objProveedor->fecvensso = '1900-01-01';
		$objProveedor->numregince = '';
		$objProveedor->fecvenince = '1900-01-01';
		$objProveedor->estprov    = 0;
		$objProveedor->pagweb     = '';
		$objProveedor->email      = '';
		$objProveedor->codpai     = '---';
		$objProveedor->codest     = '---';
		$objProveedor->codmun     = '---';
		$objProveedor->codpar     = '---';
		$objProveedor->graemp     = '';
		$objProveedor->tipconpro  = '';
		$objProveedor->sc_cuentarecdoc = '';
		$objProveedor->sc_ctaant       = '';
		$objProveedor->incluir();
		
		
		//Insertar beneficiario por defecto
		$objBeneficiario->codemp    = '0001';
		$objBeneficiario->ced_bene  = '----------';
		$objBeneficiario->codpai    = '---';
		$objBeneficiario->codest    = '---';
		$objBeneficiario->codmun    = '---';
		$objBeneficiario->codpar    = '---';
		$objBeneficiario->codtipcta = '';
		$objBeneficiario->rifben    = '';
		$objBeneficiario->nombene   = 'Beneficiario Nulo';
		$objBeneficiario->apebene   = '';
		$objBeneficiario->dirbene   = '';
		$objBeneficiario->telbene   = '';
		$objBeneficiario->celbene   = '';
		$objBeneficiario->email     = '';
		$objBeneficiario->sc_cuenta = '';
		$objBeneficiario->codbansig = '---';
		$objBeneficiario->codban    = '';
		$objBeneficiario->ctaban    = '';
		$objBeneficiario->foto      = '';
		$objBeneficiario->fecregben = '1900-01-01';
		$objBeneficiario->nacben    = '';
		$objBeneficiario->numpasben = '';
		$objBeneficiario->tipconben = '';
		$objBeneficiario->tipcuebanben = '';
		$objBeneficiario->sc_cuentarecdoc = '';
		$objBeneficiario->incluir();
		
		unset($objTipoOrg);
		unset($objEspecialidad);
		unset($objProveedor);
		unset($objBeneficiario);
		
	}
	
	function crearDatosSpg()
	{
		$objOperacion   = new Operacion();
		$objFuenteFinan = new FuenteFinanciamiento();
		$objUnidadAdmin = new UnidadAdministrativa();
		$objEst1 = new EstPro1();
		$objEst2 = new EstPro2();
		$objEst3 = new EstPro3();
		$objEst4 = new EstPro4();
		$objEst5 = new EstPro5();
		$objDtFuenteFin = new DtFuenteFin();
		
		//Insertar estructuras programaticas por defecto
		$objEst1->codemp     = '0001';
		$objEst1->codestpro1 = '-------------------------';
		$objEst1->estcla     = '-';
		$objEst1->denestpro1 = 'Ninguna';
		$objEst1->estint     = 0;
		$objEst1->sc_cuenta  = '-------------------------';
		$objEst1->incluir();
		
		$objEst2->codemp     = '0001';
		$objEst2->codestpro1 = '-------------------------';
		$objEst2->codestpro2 = '-------------------------';
		$objEst2->estcla     = '-';
		$objEst2->denestpro2 = 'Ninguna';
		$objEst2->incluir();
		
		$objEst3->codemp     = '0001';
		$objEst3->codestpro1 = '-------------------------';
		$objEst3->codestpro2 = '-------------------------';
		$objEst3->codestpro3 = '-------------------------';
		$objEst3->estcla     = '-';
		$objEst3->denestpro3 = 'Ninguna';
		$objEst3->incluir();
		
		$objEst4->codemp     = '0001';
		$objEst4->codestpro1 = '-------------------------';
		$objEst4->codestpro2 = '-------------------------';
		$objEst4->codestpro3 = '-------------------------';
		$objEst4->codestpro4 = '-------------------------';
		$objEst4->estcla     = '-';
		$objEst4->denestpro4 = 'Ninguna';
		$objEst4->incluir();
		
		$objEst5->codemp     = '0001';
		$objEst5->codestpro1 = '-------------------------';
		$objEst5->codestpro2 = '-------------------------';
		$objEst5->codestpro3 = '-------------------------';
		$objEst5->codestpro4 = '-------------------------';
		$objEst5->codestpro5 = '-------------------------';
		$objEst5->estcla     = '-';
		$objEst5->denestpro5 = 'Ninguna';
		$objEst5->incluir();	
	
		//Insertar fuentes de financiamiento
		$objFuenteFinan->codemp    = '0001';
		$objFuenteFinan->codfuefin = '--';
		$objFuenteFinan->denfuefin = '---seleccione---';
		$objFuenteFinan->expfuefin = '---';
		$objFuenteFinan->incluir();
		
		$objFuenteFinan = new FuenteFinanciamiento();
		$objFuenteFinan->codemp    = '0001';
		$objFuenteFinan->codfuefin = '01';
		$objFuenteFinan->denfuefin = 'Ingresos Ordinarios';
		$objFuenteFinan->expfuefin = '';
		$objFuenteFinan->incluir();	
		
		$objFuenteFinan = new FuenteFinanciamiento();
		$objFuenteFinan->codemp    = '0001';
		$objFuenteFinan->codfuefin = '02';
		$objFuenteFinan->denfuefin = 'Ingresos Extraordinarios';
		$objFuenteFinan->expfuefin = '';
		$objFuenteFinan->incluir();	

		$objFuenteFinan = new FuenteFinanciamiento();
		$objFuenteFinan->codemp    = '0001';
		$objFuenteFinan->codfuefin = '03';
		$objFuenteFinan->denfuefin = 'FIDES';
		$objFuenteFinan->expfuefin = '';
		$objFuenteFinan->incluir();	
		
		$objFuenteFinan = new FuenteFinanciamiento();
		$objFuenteFinan->codemp    = '0001';
		$objFuenteFinan->codfuefin = '04';
		$objFuenteFinan->denfuefin = 'LAEE';
		$objFuenteFinan->expfuefin = '';
		$objFuenteFinan->incluir();			
			
		//Insertar dt fuente de financiamiento
		$objDtFuenteFin->codemp     = '0001';
		$objDtFuenteFin->codfuefin  = '--';
		$objDtFuenteFin->codestpro1 = '-------------------------';
		$objDtFuenteFin->codestpro2 = '-------------------------';
		$objDtFuenteFin->codestpro3 = '-------------------------';
		$objDtFuenteFin->codestpro4 = '-------------------------';
		$objDtFuenteFin->codestpro5 = '-------------------------';
		$objDtFuenteFin->estcla     = '-';
		$objDtFuenteFin->incluir();
		
		//Insertar operaciones por defecto
		$objOperacion->operacion      = 'AAP';
		$objOperacion->denominacion   = 'ASIENTO DE APERTURA';
		$objOperacion->asignar        = 1;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 0;
		$objOperacion->causar         = 0;
		$objOperacion->pagar          = 0;
		$objOperacion->reservado      = 1;
		$objOperacion->incluir();
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'AU';
		$objOperacion->denominacion   = 'AUMENTO DE PARTIDA';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 1;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 0;
		$objOperacion->causar         = 0;
		$objOperacion->pagar          = 0;
		$objOperacion->reservado      = 1;
		$objOperacion->incluir();
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'DI';
		$objOperacion->denominacion   = 'DISMINUCION DE PARTIDA';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 1;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 0;
		$objOperacion->causar         = 0;
		$objOperacion->pagar          = 0;
		$objOperacion->reservado      = 1;
		$objOperacion->incluir();	
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'PC';
		$objOperacion->denominacion   = 'PRE-COMPROMISO';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 1;
		$objOperacion->comprometer    = 0;
		$objOperacion->causar         = 0;
		$objOperacion->pagar          = 0;
		$objOperacion->reservado      = 0;
		$objOperacion->incluir();
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'CS';
		$objOperacion->denominacion   = 'COMPROMISO SIMPLE';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 1;
		$objOperacion->causar         = 0;
		$objOperacion->pagar          = 0;
		$objOperacion->reservado      = 0;
		$objOperacion->incluir();
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'CG';
		$objOperacion->denominacion   = 'COMPROMISO Y GASTO CAUSADO';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 1;
		$objOperacion->causar         = 1;
		$objOperacion->pagar          = 0;
		$objOperacion->reservado      = 0;
		$objOperacion->incluir();
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'GC';
		$objOperacion->denominacion   = 'GASTO CAUSADO';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 0;
		$objOperacion->causar         = 1;
		$objOperacion->pagar          = 0;
		$objOperacion->reservado      = 0;
		$objOperacion->incluir();
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'CP';
		$objOperacion->denominacion   = 'GASTO CAUSADO Y PAGO';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 0;
		$objOperacion->causar         = 1;
		$objOperacion->pagar          = 1;
		$objOperacion->reservado      = 0;
		$objOperacion->incluir();
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'PG';
		$objOperacion->denominacion   = 'PAGO';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 0;
		$objOperacion->causar         = 0;
		$objOperacion->pagar          = 1;
		$objOperacion->reservado      = 0;
		$objOperacion->incluir();
		
		$objOperacion   = new Operacion();
		$objOperacion->operacion      = 'CCP';
		$objOperacion->denominacion   = 'COMPROMISO,CAUSADO Y PAGADO';
		$objOperacion->asignar        = 0;
		$objOperacion->aumento        = 0;
		$objOperacion->disminucion    = 0;
		$objOperacion->precomprometer = 0;
		$objOperacion->comprometer    = 1;
		$objOperacion->causar         = 1;
		$objOperacion->pagar          = 1;
		$objOperacion->reservado      = 0;
		$objOperacion->incluir();
				
		//Insertar unidad administrativa por defecto
		$objUnidadAdmin->codemp       = '0001';
		$objUnidadAdmin->coduniadm    = '----------';
		$objUnidadAdmin->coduac       = '';
		$objUnidadAdmin->denuniadm    = 'NINGUNA';
		$objUnidadAdmin->estemireq    = 0;
		$objUnidadAdmin->coduniadmsig = '';
		$objUnidadAdmin->incluir();	

		unset($objOperacion);
		unset($objFuenteFinan);
		unset($objUnidadAdmin);
		unset($objDtFuenteFin);
		unset($objEst1);
		unset($objEst2);
		unset($objEst3);
		unset($objEst3);
		unset($objEst4);
		unset($objEst5);
	}
	
	function crearDatosSoc() //DUDAS DE DATOS
	{
		$objOrdCompra = new OrdenCompra();
		
		$objOrdCompra->codemp    = '0001';
		$objOrdCompra->numordcom = '000000000000000';
		$objOrdCompra->estcondat = '-';
		$objOrdCompra->cod_pro   = '----------';
		$objOrdCompra->codmon    = '---';
		$objOrdCompra->codfuefin = '--';
		$objOrdCompra->codtipmod = '--';
		$objOrdCompra->fecordcom  = '1900-01-01';
		$objOrdCompra->estsegcom = 0;
		$objOrdCompra->porsegcom = 0.00;
		$objOrdCompra->monsegcom = 0.0000;
		$objOrdCompra->forpagcom = '';
		$objOrdCompra->estcom    = 1;
		$objOrdCompra->diaplacom = 0;
		$objOrdCompra->concom    = '';
		$objOrdCompra->obscom    = '';
		$objOrdCompra->monsubtotbie = 0.0000;
		$objOrdCompra->monsubtotser = 0.0000;
		$objOrdCompra->monsubtot    = 0.0000;
		$objOrdCompra->monbasimp    = 0.0000;
		$objOrdCompra->monimp       = 0.0000;
		$objOrdCompra->mondes       = 0.0000;
		$objOrdCompra->montot       = 0.0000;
		$objOrdCompra->estpenalm    = 0;
		$objOrdCompra->codpai       = '---';
		$objOrdCompra->codest       = '---';
		$objOrdCompra->codmun       = '---';
		$objOrdCompra->codpar       = '---';
		$objOrdCompra->lugentnomdep = '';
		$objOrdCompra->lugentdir    = '';
		$objOrdCompra->monant       = 0.0000;
		$objOrdCompra->estlugcom    = 0;
		$objOrdCompra->tascamordcom = 0.0000;
		$objOrdCompra->montotdiv    = 0.0000;
		$objOrdCompra->estapro		= 0;
		$objOrdCompra->fecaprord	= '1900-01-01';
		$objOrdCompra->codusuapr	= 'sigesp';
		$objOrdCompra->numpolcon	= '0';
		$objOrdCompra->coduniadm    = '----------';
		$objOrdCompra->codestpro1	= '-------------------------';
		$objOrdCompra->codestpro2	= '-------------------------';
		$objOrdCompra->codestpro3	= '-------------------------';
		$objOrdCompra->codestpro4	= '-------------------------';
		$objOrdCompra->codestpro5	= '-------------------------';
		$objOrdCompra->estcla		= '-';
		$objOrdCompra->obsordcom	= '';
		$objOrdCompra->fecent		= '1900-01-01';
		$objOrdCompra->fechaconta	= '1900-01-01';
		$objOrdCompra->fechaanula	= '1900-01-01';
		$objOrdCompra->uniejeaso	= '';
		$objOrdCompra->numanacot	= '';
		$objOrdCompra->fechentdesde	= '1900-01-01';
		$objOrdCompra->fechenthasta	= '1900-01-01';
		$objOrdCompra->tipbieordcom	= '-';
		$objOrdCompra->incluir();

		unset($objOrdCompra);
	}
	

	function crearDatosSpi()
	{
		$objOperacionSpi = new OperacionSpi();
		//Insertar operaciones de SPI por defecto
		$objOperacionSpi->operacion    = 'PRE';
		$objOperacionSpi->denominacion = 'PREVISTO';
		$objOperacionSpi->previsto     = 1;
		$objOperacionSpi->aumento      = 0;
		$objOperacionSpi->disminucion  = 0;
		$objOperacionSpi->devengado    = 0;
		$objOperacionSpi->cobrado      = 0;
		$objOperacionSpi->cobrado_ant  = 0;
		$objOperacionSpi->reservado    = 1;
		$objOperacionSpi->incluir();
		
		$objOperacionSpi = new OperacionSpi();
		$objOperacionSpi->operacion    = 'AU';
		$objOperacionSpi->denominacion = 'AUMENTO';
		$objOperacionSpi->previsto     = 0;
		$objOperacionSpi->aumento      = 1;
		$objOperacionSpi->disminucion  = 0;
		$objOperacionSpi->devengado    = 0;
		$objOperacionSpi->cobrado      = 0;
		$objOperacionSpi->cobrado_ant  = 0;
		$objOperacionSpi->reservado    = 1;
		$objOperacionSpi->incluir();
		
		$objOperacionSpi = new OperacionSpi();
		$objOperacionSpi->operacion    = 'DI';
		$objOperacionSpi->denominacion = 'DISMINUCION';
		$objOperacionSpi->previsto     = 0;
		$objOperacionSpi->aumento      = 0;
		$objOperacionSpi->disminucion  = 1;
		$objOperacionSpi->devengado    = 0;
		$objOperacionSpi->cobrado      = 0;
		$objOperacionSpi->cobrado_ant  = 0;
		$objOperacionSpi->reservado    = 1;
		$objOperacionSpi->incluir();
		
		$objOperacionSpi = new OperacionSpi();
		$objOperacionSpi->operacion    = 'DEV';
		$objOperacionSpi->denominacion = 'DEVENGADO';
		$objOperacionSpi->previsto     = 0;
		$objOperacionSpi->aumento      = 0;
		$objOperacionSpi->disminucion  = 0;
		$objOperacionSpi->devengado    = 1;
		$objOperacionSpi->cobrado      = 0;
		$objOperacionSpi->cobrado_ant  = 0;
		$objOperacionSpi->reservado    = 0;
		$objOperacionSpi->incluir();
		
		$objOperacionSpi = new OperacionSpi();
		$objOperacionSpi->operacion    = 'COB';
		$objOperacionSpi->denominacion = 'COBRADO';
		$objOperacionSpi->previsto     = 0;
		$objOperacionSpi->aumento      = 0;
		$objOperacionSpi->disminucion  = 0;
		$objOperacionSpi->devengado    = 0;
		$objOperacionSpi->cobrado      = 1;
		$objOperacionSpi->cobrado_ant  = 0;
		$objOperacionSpi->reservado    = 0;
		$objOperacionSpi->incluir();
		
		$objOperacionSpi = new OperacionSpi();
		$objOperacionSpi->operacion    = 'DC';
		$objOperacionSpi->denominacion = 'DEVENGADO Y COBRADO';
		$objOperacionSpi->previsto     = 0;
		$objOperacionSpi->aumento      = 0;
		$objOperacionSpi->disminucion  = 0;
		$objOperacionSpi->devengado    = 1;
		$objOperacionSpi->cobrado      = 1;
		$objOperacionSpi->cobrado_ant  = 0;
		$objOperacionSpi->reservado    = 0;
		$objOperacionSpi->incluir();
		
		unset($objOperacionSpi);
	}
		
	function crearDatosProcedencia()
	{
		$objProcedencia = new Procedencia();
		//Insertar procedencias por defecto
		$objProcedencia->procede = 'SCGCMP';
		$objProcedencia->codsis  = 'SCG';
		$objProcedencia->opeproc = 'CMP';
		$objProcedencia->desproc = 'Comprobante Contable';
		$objProcedencia->incluir();		
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCGAMP';
		$objProcedencia->codsis  = 'SCG';
		$objProcedencia->opeproc = 'AMP';
		$objProcedencia->desproc = 'Anulacion - Comprobante Contable';
		$objProcedencia->incluir();

		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCGAPR';
		$objProcedencia->codsis  = 'SCG';
		$objProcedencia->opeproc = 'APR';
		$objProcedencia->desproc = 'Comprobante de Apertura Contable';
		$objProcedencia->incluir();	

		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCGCIE';
		$objProcedencia->codsis  = 'SCG';
		$objProcedencia->opeproc = 'CIE';
		$objProcedencia->desproc = 'Comprobante de Cierre Contable';
		$objProcedencia->incluir();	
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPGCMP';
		$objProcedencia->codsis  = 'SPG';
		$objProcedencia->opeproc = 'CMP';
		$objProcedencia->desproc = 'Comprobante Presupuesto de Gastos';
		$objProcedencia->incluir();

		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPGAMP';
		$objProcedencia->codsis  = 'SPG';
		$objProcedencia->opeproc = 'AMP';
		$objProcedencia->desproc = 'Anulacion - Comprobante Presupuesto de Gastos';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPGAPR';
		$objProcedencia->codsis  = 'SPG';
		$objProcedencia->opeproc = 'APR';
		$objProcedencia->desproc = 'Apertura de cuentas Presupuesto de Gastos';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPGREC';
		$objProcedencia->codsis  = 'SPG';
		$objProcedencia->opeproc = 'REC';
		$objProcedencia->desproc = 'Rectificaciones al presupuesto';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPGTRA';
		$objProcedencia->codsis  = 'SPG';
		$objProcedencia->opeproc = 'TRA';
		$objProcedencia->desproc = 'Traspasos';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPGINS';
		$objProcedencia->codsis  = 'SPG';
		$objProcedencia->opeproc = 'INS';
		$objProcedencia->desproc = 'Insubsistencias';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPGCRA';
		$objProcedencia->codsis  = 'SPG';
		$objProcedencia->opeproc = 'CRA';
		$objProcedencia->desproc = 'Credito adicional';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPICMP';
		$objProcedencia->codsis  = 'SPI';
		$objProcedencia->opeproc = 'CMP';
		$objProcedencia->desproc = 'Comprobante Presupuesto de Ingreso';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPIAMP';
		$objProcedencia->codsis  = 'SPI';
		$objProcedencia->opeproc = 'AMP';
		$objProcedencia->desproc = 'Anulacion - Comprobante Presupuesto de Ingreso';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPIAPR';
		$objProcedencia->codsis  = 'SPI';
		$objProcedencia->opeproc = 'APR';
		$objProcedencia->desproc = 'Apertura de cuentas Presupuesto de Ingreso';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPIAUM';
		$objProcedencia->codsis  = 'SPI';
		$objProcedencia->opeproc = 'AUM';
		$objProcedencia->desproc = 'Aumento de Ingreso';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPIDIS';
		$objProcedencia->codsis  = 'SPI';
		$objProcedencia->opeproc = 'DIS';
		$objProcedencia->desproc = 'Disminucion de Ingreso';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCCPC';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'CPC';
		$objProcedencia->desproc = 'Precontabilizacion de Orden de Compra';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCCPA';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'CPA';
		$objProcedencia->desproc = 'Reverso-Precontabilizacion de Orden de Compra';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCCOC';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'COC';
		$objProcedencia->desproc = 'Contabilizacion de Orden de Compra';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCAOC';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'AOC';
		$objProcedencia->desproc = 'Anulacion de Orden de Compras';
		$objProcedencia->incluir();
		
		/*$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SPGAPR';
		$objProcedencia->codsis  = 'SPG';
		$objProcedencia->opeproc = 'APR';
		$objProcedencia->desproc = 'Apertura de cuentas Presupuesto de Gastos';
		$objProcedencia->incluir();*/
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCSPC';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'SPC';
		$objProcedencia->desproc = 'Precontabilizacion de Orden de Servicios';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCSPA';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'SPA';
		$objProcedencia->desproc = 'Reverso-Precontabilizacion de Orden de Servicios';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCCOS';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'COS';
		$objProcedencia->desproc = 'Contabilizacion de Orden de Servicios';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCAOS';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'AOS';
		$objProcedencia->desproc = 'Anulacion de Orden de Servicios';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCCND';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'CND';
		$objProcedencia->desproc = 'Nota de Despacho';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOCAND';
		$objProcedencia->codsis  = 'SOC';
		$objProcedencia->opeproc = 'AND';
		$objProcedencia->desproc = 'Anulacion de Nota de Despacho';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'CXPRCD';
		$objProcedencia->codsis  = 'CXP';
		$objProcedencia->opeproc = 'RCD';
		$objProcedencia->desproc = 'Recepciones de Documento';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'CXPSOP';
		$objProcedencia->codsis  = 'CXP';
		$objProcedencia->opeproc = 'SOP';
		$objProcedencia->desproc = 'Solicitud de Pago';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'CXPAOP';
		$objProcedencia->codsis  = 'CXP';
		$objProcedencia->opeproc = 'AOP';
		$objProcedencia->desproc = 'Anulacion Solicitud de Pago';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBCH';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BCH';
		$objProcedencia->desproc = 'Banco - emision de cheque';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBAH';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BAH';
		$objProcedencia->desproc = 'Banco - anulacion de cheque';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBOPD';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'OPD';
		$objProcedencia->desproc = 'Banco - Orden de Pago Directa';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBDP';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BDP';
		$objProcedencia->desproc = 'Banco - deposito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBAP';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BAP';
		$objProcedencia->desproc = 'Banco - anulacion de deposito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBRE';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BRE';
		$objProcedencia->desproc = 'Banco - retiro';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBAE';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BAE';
		$objProcedencia->desproc = 'Banco - anulacion de retiro';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBND';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BND';
		$objProcedencia->desproc = 'Banco - Nota de Debito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBAD';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BAD';
		$objProcedencia->desproc = 'Banco - anulacion de Nota de Debito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBNC';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BNC';
		$objProcedencia->desproc = 'Banco - Nota de Credito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBBAC';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'BAC';
		$objProcedencia->desproc = 'Banco - anulacion de Nota de Credito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCCH';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CCH';
		$objProcedencia->desproc = 'Colocacion - emision de cheque';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCAH';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CAH';
		$objProcedencia->desproc = 'Colocacion - anulacion de cheque';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCDP';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CDP';
		$objProcedencia->desproc = 'Colocacion - deposito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCAP';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CAP';
		$objProcedencia->desproc = 'Colocacion - anulacion de deposito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCRE';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CRE';
		$objProcedencia->desproc = 'Colocacion - retiro';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCAE';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CAE';
		$objProcedencia->desproc = 'Colocacion - anulacion de retiro';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCND';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CND';
		$objProcedencia->desproc = 'Colocacion - Nota de Debito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCAD';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CAD';
		$objProcedencia->desproc = 'Colocacion - anulacion de Nota de Debito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCNC';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CNC';
		$objProcedencia->desproc = 'Colocacion - Nota de Credito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBCAC';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'CAC';
		$objProcedencia->desproc = 'Colocacion - anulacion de Nota de Credito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBJCD';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'JCD';
		$objProcedencia->desproc = 'Caja x Debe';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBJAD';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'JAD';
		$objProcedencia->desproc = 'Anulacion Caja x Debe';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBJCH';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'JCH';
		$objProcedencia->desproc = 'Caja x Haber';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SCBJAH';
		$objProcedencia->codsis  = 'SCB';
		$objProcedencia->opeproc = 'JAH';
		$objProcedencia->desproc = 'Anulacion Caja x Haber';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SNOCNO';
		$objProcedencia->codsis  = 'SNO';
		$objProcedencia->opeproc = 'CNO';
		$objProcedencia->desproc = 'Nomina - Contabilizacion';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOBRCO';
		$objProcedencia->codsis  = 'SOB';
		$objProcedencia->opeproc = 'RCO';
		$objProcedencia->desproc = 'Registro de Contrato de Obras';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOBACO';
		$objProcedencia->codsis  = 'SOB';
		$objProcedencia->opeproc = 'ACO';
		$objProcedencia->desproc = 'Anulacion de Contrato de Obras';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFCIN';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'CIN';
		$objProcedencia->desproc = 'Contabilizar Incorporaciones';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFAIN';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'AIN';
		$objProcedencia->desproc = 'Anular Incorporaciones';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFCDN';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'CDN';
		$objProcedencia->desproc = 'Contabilizar Desincorporaciones';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFADN';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'ADN';
		$objProcedencia->desproc = 'Anular Desincorporaciones';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFCAJ';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'CAJ';
		$objProcedencia->desproc = 'Contabilizar Ajustes';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFAAJ';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'AAJ';
		$objProcedencia->desproc = 'Anular Ajustes';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFCDP';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'CDP';
		$objProcedencia->desproc = 'Contabilizar Depreciaciones';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFADP';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'ADP';
		$objProcedencia->desproc = 'Anular Depreciaciones';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SEPSPC';
		$objProcedencia->codsis  = 'SEP';
		$objProcedencia->opeproc = 'SPC';
		$objProcedencia->desproc = 'Precontabilizacion de Solicitud de Ejecucion Presupuestaria';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SEPSPA';
		$objProcedencia->codsis  = 'SEP';
		$objProcedencia->opeproc = 'SPA';
		$objProcedencia->desproc = 'Reverso-Precontabilizacion de Solicitud de Ejecucion Presupuestaria';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SAFDPR';
		$objProcedencia->codsis  = 'SAF';
		$objProcedencia->opeproc = 'DPR';
		$objProcedencia->desproc = 'Comprobante de Depreciación de Activos';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SEPRPC';
		$objProcedencia->codsis  = 'SEP';
		$objProcedencia->opeproc = 'SPR';
		$objProcedencia->desproc = 'Reverso Solicitud Ejecucion Presupuestaria al contabilizar OC/OS';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'CXPNOD';
		$objProcedencia->codsis  = 'CXP';
		$objProcedencia->opeproc = 'NOD';
		$objProcedencia->desproc = 'Cuentas por Pagar Nota de Débito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'CXPNOC';
		$objProcedencia->codsis  = 'CXP';
		$objProcedencia->opeproc = 'NOC';
		$objProcedencia->desproc = 'Cuentas por Pagar Nota de Crédito';
		$objProcedencia->incluir();
		
		$objProcedencia = new Procedencia();
		$objProcedencia->procede = 'SOBASI';
		$objProcedencia->codsis  = 'SOB';
		$objProcedencia->opeproc = 'ASI';
		$objProcedencia->desproc = 'Obras Asiganación';
		$objProcedencia->incluir();
		
		unset($objProcedencia);
	}
	
	function crearDatosSaf()
	{
		$objConservacion = new Conservacion();
		$objRotulacion   = new Rotulacion();
		$objCausas       = new Causa();
		$objSitContable  = new SituacionContable();
		$objCondCompra   = new CondicionCompra();
		$objGrupoSaf     = new GrupoSaf();
		$objSubgrupoSaf  = new SubgrupoSaf();
		$objSeccion      = new Seccion();
		$objMetodo       = new Metodo();
		
		//Insertar estado de conservacion por defecto
		$objConservacion->codconbie = '1';
		$objConservacion->denconbie = 'Muy Bueno';
		$objConservacion->desconbie = '';
		$objConservacion->incluir();
		
		$objConservacion = new Conservacion();
		$objConservacion->codconbie = '2';
		$objConservacion->denconbie = 'Bueno';
		$objConservacion->desconbie = '';
		$objConservacion->incluir();	
		
		$objConservacion = new Conservacion();
		$objConservacion->codconbie = '3';
		$objConservacion->denconbie = 'Regular';
		$objConservacion->desconbie = '';
		$objConservacion->incluir();
		
		$objConservacion = new Conservacion();
		$objConservacion->codconbie = '4';
		$objConservacion->denconbie = 'Malo';
		$objConservacion->desconbie = '';
		$objConservacion->incluir();
		
		$objConservacion = new Conservacion();
		$objConservacion->codconbie = '5';
		$objConservacion->denconbie = 'Muy Malo';
		$objConservacion->desconbie = '';
		$objConservacion->incluir();		
		
		//Insertar tipos de rotulación por defecto
		$objRotulacion->codrot = '1';
		$objRotulacion->denrot = 'De Rótulo flexibles autoadhesivos';
		$objRotulacion->emprot = '';
		$objRotulacion->incluir();
		
		$objRotulacion   = new Rotulacion();
		$objRotulacion->codrot = '2';
		$objRotulacion->denrot = 'Grabación por arenado';
		$objRotulacion->emprot = '';
		$objRotulacion->incluir();
		
		$objRotulacion   = new Rotulacion();
		$objRotulacion->codrot = '3';
		$objRotulacion->denrot = 'Pintado';
		$objRotulacion->emprot = '';
		$objRotulacion->incluir();
		
		$objRotulacion   = new Rotulacion();
		$objRotulacion->codrot = '4';
		$objRotulacion->denrot = 'Rótulo rigido';
		$objRotulacion->emprot = '';
		$objRotulacion->incluir();
		
		$objRotulacion   = new Rotulacion();
		$objRotulacion->codrot = '5';
		$objRotulacion->denrot = 'Herrete';
		$objRotulacion->emprot = '';
		$objRotulacion->incluir();
		
		//Insertar causas por defecto
		//SIGECOF
		$objCausas->codcau    = '001';
		$objCausas->dencau    = 'Compras';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 0;
		$objCausas->estafepre = 1;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '002';
		$objCausas->dencau    = 'Inventario Inicial';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '003';
		$objCausas->dencau    = 'Fabricación o Producción de Materiales y Bienes';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '004';
		$objCausas->dencau    = 'Omisión por Inventario Inicial';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '005';
		$objCausas->dencau    = 'Ingreso Provisional de bienes y materiales provenientes de programas especiales.';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '006';
		$objCausas->dencau    = 'Ingreso Definitivos de bienes y materiales provenientes de programas especiales.';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '007';
		$objCausas->dencau    = 'Devolución de bienes y materiales robados, hurtados o perdido.';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '008';
		$objCausas->dencau    = 'Aparición de Bienes y materiales desincorporados por causas imputables a funcionarios y a empleados.';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '009';
		$objCausas->dencau    = 'Nacimiento de Semovientes';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '010';
		$objCausas->dencau    = 'Incremento de Edad de Semovientes';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '011';
		$objCausas->dencau    = 'Donaciones';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '012';
		$objCausas->dencau    = 'Permuta';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '013';
		$objCausas->dencau    = 'Ingreso Provisional de Bienes dado en comodatos';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '014';
		$objCausas->dencau    = 'Ingreso definitivo de bienes datos en comodatos';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '015';
		$objCausas->dencau    = 'Herencia Vacante';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '016';
		$objCausas->dencau    = 'Decomiso de bienes y materiales';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '017';
		$objCausas->dencau    = 'Ingreso Provisional de bienes y materiales bajo guarda judicial.';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '018';
		$objCausas->dencau    = 'Ingreso Definitivo de Bienes y materiales que habian sido registrado provisionalmente bajo guarda judicial.';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '019';
		$objCausas->dencau    = 'Incorporación de Otros conceptos';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '020';
		$objCausas->dencau    = 'Recepción de Bienes o Materiales procedentes de almacenes de la administracion central';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '021';
		$objCausas->dencau    = 'Recepción de Bienes y Materiales de Otras dependencia del organismo ordenador de compromisos y pago';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '022';
		$objCausas->dencau    = 'Recepción de Bienes y Materiales de Otros Organismos del organismo ordenador de compromisos y pago';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '023';
		$objCausas->dencau    = 'Recepción de Bienes y Materiales  procedentes de otros organismos de la administración pública';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '024';
		$objCausas->dencau    = 'Devolución de Bienes Prestado a Contratistas';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '025';
		$objCausas->dencau    = 'Incorporación por Cambios de Grupo, cuenta y subcuentas';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '026';
		$objCausas->dencau    = 'Correcciones de Desincorporaciones';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '030';
		$objCausas->dencau    = 'Entrega de Bienes o Materiales por parte de almacenes';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '031';
		$objCausas->dencau    = 'Entrega de Bienes o Materiales a otras dependencias del organismo ordenador de compromisos y pagos';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '032';
		$objCausas->dencau    = 'Entrega de Bienes o Materiales a otras organismo ordenador de compromisos y pagos';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '033';
		$objCausas->dencau    = 'Entrega de Bienes o materiales a otros organismos de la Administración Pública Nacional';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '034';
		$objCausas->dencau    = 'Préstamos de bienes a contratistas';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '035';
		$objCausas->dencau    = 'Desincorporación por cambios de grupo, cuentas o cubcuentas';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '036';
		$objCausas->dencau    = 'Correciones de Incorporaciones';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '037';
		$objCausas->dencau    = 'Ajuste de Cambios del método de depreciación';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '038';
		$objCausas->dencau    = 'Otros descargos por reasignaciones';
		$objCausas->tipcau    = 'R';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '040';
		$objCausas->dencau    = 'Error de Incorporación de bienes de materiales';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '041';
		$objCausas->dencau    = 'Pase a situacion de desuso para reasignación, venta o disposición final';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '042';
		$objCausas->dencau    = 'Bienes o Materiales en custodia en el almacen';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '043';
		$objCausas->dencau    = 'Ventas';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '044';
		$objCausas->dencau    = 'Cesiones sin cargos a organismos del sector privado';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '045';
		$objCausas->dencau    = 'Cesiones sin cargos a los entes descentralizados territorialmente';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '046';
		$objCausas->dencau    = 'Perdida de bienes con formulación de cargos';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '047';
		$objCausas->dencau    = 'Robo hurto de bienes o materiales';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '048';
		$objCausas->dencau    = 'Otras perdidas de bienes o materiales no culposas';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '049';
		$objCausas->dencau    = 'Destrucción o incineración de bienes y materiales';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '050';
		$objCausas->dencau    = 'Desarme o desmantelamiento de bienes';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '051';
		$objCausas->dencau    = 'Inservibilidad';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '052';
		$objCausas->dencau    = 'Deterioro';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '053';
		$objCausas->dencau    = 'Demolición';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '054';
		$objCausas->dencau    = 'Muerte de semovimiente';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '055';
		$objCausas->dencau    = 'Desincorporación por cambio de edad de semovimiente';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '056';
		$objCausas->dencau    = 'Reclasificación de semovimiente como bienes de cambios';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '057';
		$objCausas->dencau    = 'Desincorporación por permuta';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '058';
		$objCausas->dencau    = 'Desincorporación por donación';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '059';
		$objCausas->dencau    = 'Desincorporación por otros conceptos';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '060';
		$objCausas->dencau    = 'Adiciones a Bienes';
		$objCausas->tipcau    = 'M';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '061';
		$objCausas->dencau    = 'Mejoras a Bienes';
		$objCausas->tipcau    = 'M';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '062';
		$objCausas->dencau    = 'Mayor costo de Bienes';
		$objCausas->tipcau    = 'M';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '063';
		$objCausas->dencau    = 'Reparaciones extraordinarias de los Bienes';
		$objCausas->tipcau    = 'M';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '064';
		$objCausas->dencau    = 'Correción de Errores';
		$objCausas->tipcau    = 'M';
		$objCausas->estcat    = 1;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();		
		
		//Contraloria General de la Republica
		$objCausas       = new Causa();
		$objCausas->codcau    = '001';
		$objCausas->dencau    = 'Incorporación por inventario inicial';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '002';
		$objCausas->dencau    = 'Incorporación por traspaso';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '003';
		$objCausas->dencau    = 'Incorporación por compras';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '004';
		$objCausas->dencau    = 'Incorporación por construcción de inmuebles';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '005';
		$objCausas->dencau    = 'Incorporación por adiciones y mejoras';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '006';
		$objCausas->dencau    = 'Incorporación por producción de elementos (muebles)';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '007';
		$objCausas->dencau    = 'Incorporación por suministro de bienes de otras entidades';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '008';
		$objCausas->dencau    = 'Incorporación por devolución de bienes prestados a contratistas';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '009';
		$objCausas->dencau    = 'Incorporación de semovientes';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '010';
		$objCausas->dencau    = 'Incorporación por reconstrucción de equipos';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '011';
		$objCausas->dencau    = 'Incorporación por donación';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '012';
		$objCausas->dencau    = 'Incorporación por permuta';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '013';
		$objCausas->dencau    = 'Incorporación por adscripción de bienes inmuebles';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '014';
		$objCausas->dencau    = 'Incorporación por omisión en inventario';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '016';
		$objCausas->dencau    = 'Incorporación por cambio de subgrupo';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '017';
		$objCausas->dencau    = 'Incorporación por correción de desincorporaciones';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '018';
		$objCausas->dencau    = 'Incorporación por otros conceptos';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '019';
		$objCausas->dencau    = 'Incorporación de muebles procedentes de los almacenes';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '020';
		$objCausas->dencau    = 'Incorporación por herencias vacantes';
		$objCausas->tipcau    = 'I';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '051';
		$objCausas->dencau    = 'Desincorporación por traspaso';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '052';
		$objCausas->dencau    = 'Desincorporación por venta';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '053';
		$objCausas->dencau    = 'Desincorporación por préstamos de bienes a contratistas';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '054';
		$objCausas->dencau    = 'Desincorporación por suministros de bienes a otras entidades';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '055';
		$objCausas->dencau    = 'Desincorporación por desarme';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '056';
		$objCausas->dencau    = 'Desincorporación por inservibilidad';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '057';
		$objCausas->dencau    = 'Desincorporación por deterioro';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '058';
		$objCausas->dencau    = 'Desincorporación por demolición';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '059';
		$objCausas->dencau    = 'Desincorporación de semovimientes';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '060';
		$objCausas->dencau    = 'Desincorporación por faltantes por investigar';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '061';
		$objCausas->dencau    = 'Desincorporación por permuta';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '062';
		$objCausas->dencau    = 'Desincorporación por donación';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '063';
		$objCausas->dencau    = 'Desincorporación por adscripción de bienes inmuebles';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '065';
		$objCausas->dencau    = 'Desincorporación por cambio de subgrupo';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '066';
		$objCausas->dencau    = 'Desincorporación por corrección de incorporaciones';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '067';
		$objCausas->dencau    = 'Desincorporación por otros conceptos';
		$objCausas->tipcau    = 'D';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		$objCausas       = new Causa();
		$objCausas->codcau    = '080';
		$objCausas->dencau    = 'Ajustes';
		$objCausas->tipcau    = 'A';
		$objCausas->estcat    = 2;
		$objCausas->estafecon = 1;
		$objCausas->estafepre = 0;
		$objCausas->expcau    =	'';	 
		$objCausas->incluir();
		
		//Insertar situacion contable por defecto
		$objSitContable->codsitcon = '1';
		$objSitContable->densitcon = 'Bienes Inmuebles';
		$objSitContable->expsitcon = 'Comprenden los bienes inmuebles patrimoniales del dominio privado de la Nación permanentes o para uso oficial o para cumplir finalidades específicas de servicio público';
		$objSitContable->incluir();	
		
		$objSitContable = new SituacionContable();
		$objSitContable->codsitcon = '2';
		$objSitContable->densitcon = 'Bienes en depósito';
		$objSitContable->expsitcon = 'Son aquellos bienes que se encuentran almacenados  y que por su naturaleza, uso o destino';
		$objSitContable->incluir();	
		
		$objSitContable = new SituacionContable();
		$objSitContable->codsitcon = '3';
		$objSitContable->densitcon = 'Bienes desafectados de uso';
		$objSitContable->expsitcon = 'Representan aquellos materiales y suministros que no estén en servicio de presentar avería total, es decir, por carecer de partes aprovechables y de valor de rescate que haga posible su desmantelamiento o venta. No incluye los bienes de uso cuya estructura esté conformada mayormente de elementosmetálicos, tales como vehiculos, sillas, etc. en virtud de que estos siempren tendrán valor de rescate como material de desecho. Tambien se incluyen aquellos materiales y suministros de uso dañados parcialmente, que no tengan partes utilizables, pero si posible valor de salvamento';
		$objSitContable->incluir();	
		
		$objSitContable = new SituacionContable();
		$objSitContable->codsitcon = '4';
		$objSitContable->densitcon = 'Materiales y suministros para la venta';
		$objSitContable->expsitcon = 'Comprende los materiales y suministros destinados para la venta que se efectúe conforme a las disposiciones de la Ley Orgánicaque Regula la Enejación de Bienes del Sector Público no Afectos a las Industrias Básicas';
		$objSitContable->incluir();	
		
		//Insertar condicion de compra por defecto
		$objCondCompra->codconcom = '01';
		$objCondCompra->denconcom = 'Costo, seguro y flete (CIF)';
		$objCondCompra->expconcom = 'Costo por concepto de gastos de seguro y de transporte, el cual debe ser cancelado por el traslado de la mercancia desde el lugar de la venta hasta el lugar de destino de la entidad que efectua la compra';
		$objCondCompra->incluir();
		
		$objCondCompra   = new CondicionCompra();
		$objCondCompra->codconcom = '02';
		$objCondCompra->denconcom = 'Libre a bordo puerto de embarque (FOB)';
		$objCondCompra->expconcom = 'Expresion utilizada en el comercio exterior y en los creditos documentarios. Significa que el vendedor debe entregar la mercancia, convenientemente embalada, a bordo de un navío, designado por el comprador, en el puerto de embarque a la fecha o el plazo convenido';
		$objCondCompra->incluir();
		
		$objCondCompra   = new CondicionCompra();
		$objCondCompra->codconcom = '03';
		$objCondCompra->denconcom = 'Libre puerto de embarque (FAS)';
		$objCondCompra->expconcom = 'Termino condicional empleado en el movimiento de exportacion';
		$objCondCompra->incluir();
		
		//Insertar grupos SAF por defecto
		$objGrupoSaf->codgru = '---';
		$objGrupoSaf->dengru = '---seleccione---';
		$objGrupoSaf->incluir();
		
		//Insertar subgrupos SAF por defecto
		$objSubgrupoSaf->codgru    = '---';
		$objSubgrupoSaf->codsubgru = '---';
		$objSubgrupoSaf->densubgru = '---seleccione---';
		$objSubgrupoSaf->incluir();
		
		//Insertar seccion por defecto
		$objSeccion->codgru    = '---';
		$objSeccion->codsubgru = '---';
		$objSeccion->codsec    = '---';
		$objSeccion->densec    = '---seleccione---';
		$objSeccion->incluir();
		
		//Insertar metodo por defecto
		$objMetodo->codmetdep = '001';
		$objMetodo->denmetdep = 'Linea Recta';
		$objMetodo->formetdep = '';
		$objMetodo->incluir();
		
		unset($objCausas);
		unset($objCondCompra);
		unset($objConservacion);
		unset($objGrupoSaf);
		unset($objMetodo);
		unset($objRotulacion);
		unset($objSeccion);
		unset($objSitContable);
		unset($objSubgrupoSaf);
	}
	
	function crearDatosBanco()
	{	
		$objConcepto  = new Concepto();
		$objTipoCta    = new TipoCuenta();
		$objBanco      = new Banco();
		$objCtaBanco   = new CuentaBanco();
		$objCartaOrden = new CartaOrden();
		
		//Insertar conceptos por defecto
		$objConcepto->codconmov = '---';
		$objConcepto->denconmov = 'Ninguno';
		$objConcepto->codope    = '--';
		$objConcepto->incluir();
		
		//Insertar tipo de cuenta por defecto
		$objTipoCta->codtipcta = '---';
		$objTipoCta->nomtipcta = 'Ninguno';
		$objTipoCta->incluir();
		
		//Insertar banco por defecto
		$objBanco->codemp    = '0001';
		$objBanco->codban    = '---';
		$objBanco->nomban    = 'Ninguno';
		$objBanco->dirban    = '';
		$objBanco->gerban    = '';
		$objBanco->telban    = '';
		$objBanco->conban    = '';
		$objBanco->movcon    = '';
		$objBanco->esttesnac = 0;
		$objBanco->codsudeban = '';
		$objBanco->incluir();
		
		//Insertar cuenta de banco por defecto
		$objCtaBanco->codemp     = '0001';
		$objCtaBanco->codban     = '---';
		$objCtaBanco->ctaban     = '-------------------------';
		$objCtaBanco->codtipcta  = '---';
		$objCtaBanco->ctabanext = '-------------------------';
		$objCtaBanco->dencta     = 'Ninguno';
		$objCtaBanco->sc_cuenta  = '';
		$objCtaBanco->fecapr     = '1900-01-01';
		$objCtaBanco->feccie     = '1900-01-01';
		$objCtaBanco->estact     = 0;
		$objCtaBanco->incluir();
		
		//Insertar carta orden por defecto
		$objCartaOrden->codemp = '0001';
		$objCartaOrden->codigo = '000';
		$objCartaOrden->encabezado = 'NULL';
		$objCartaOrden->cuerpo = 'NULL';
		$objCartaOrden->pie = 'NULL';
		$objCartaOrden->nombre = 'Cheque Voucher';
		$objCartaOrden->status = 1;
		$objCartaOrden->archrtf = '';
		$objCartaOrden->incluir();
		
		$objCartaOrden = new CartaOrden();
		$objCartaOrden->codemp = '0001';
		$objCartaOrden->codigo = '001';
		$objCartaOrden->encabezado = 'Dirigido a: ';
		$objCartaOrden->cuerpo = 'Nos dirigimos a ustedes en la oportunidad de saludarlos y a la vez solicitarles que transfieran de la Cuenta  @tipocuenta@  No  @cuenta@ a nombre de @empresa@  la cantidad de  @montoletras@  (Bs.  @monto@), a la cuenta que a continuacion se menciona:\r\n\r\n<b>CUENTA  @tipocuenta@ No @cuenta@  </b>\r\n\r\n<b>MONTO TOTAL A TRANSFERIR @monto@</b>\r\n';
		$objCartaOrden->pie = 'Agradeciendo de antemano su atencion, nos reiteramos de ustedes.';
		$objCartaOrden->nombre =  'Carta Orden Ejemplo 1';
		$objCartaOrden->status = 0;
		$objCartaOrden->archrtf = '';
		$objCartaOrden->incluir();	

		unset($objBanco);
		unset($objCartaOrden);
		unset($objConcepto);
		unset($objCtaBanco);
		unset($objTipoCta);
	}
	
	function crearDatosNomina()
	{
		$objMetodoBan   = new MetodoBanco();
		$objDedicacion  = new Dedicacion();
		$objTipPersonal = new TipoPersonal();
		$objTipPersSSS	= new TipoPersonalSSS();
				
		//Insertar metodos de banco por defecto
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0100';
		$objMetodoBan->desmet       = 'SIN METODO';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0101';
		$objMetodoBan->desmet       = 'BOD VIEJO';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0102';
		$objMetodoBan->desmet       = 'BOD NUEVO';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0103';
		$objMetodoBan->desmet       = 'BOD VERSION 3';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0104';
		$objMetodoBan->desmet       = 'CANARIAS';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0105';
		$objMetodoBan->desmet       = 'CARACAS';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0106';
		$objMetodoBan->desmet       = 'CARONI';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0107';
		$objMetodoBan->desmet       = 'V2_CARONI';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0108';
		$objMetodoBan->desmet       = 'CARIBE';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();		
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0109';
		$objMetodoBan->desmet       = 'CASA PROPIA';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0110';
		$objMetodoBan->desmet       = 'CASA PROPIA 2003';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0111';
		$objMetodoBan->desmet       = 'CENTRAL';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0112';
		$objMetodoBan->desmet       = 'CONFEDERADO';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0113';
		$objMetodoBan->desmet       = 'LARA';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0114';
		$objMetodoBan->desmet       = 'MERCANTIL';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0115';
		$objMetodoBan->desmet       = 'PROVINCIAL VIEJO';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0116';
		$objMetodoBan->desmet       = 'PROVINCIAL NUEVO';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0117';
		$objMetodoBan->desmet       = 'PROVINCIAL GUANARE';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0118';
		$objMetodoBan->desmet       = 'e-PROVINCIAL';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0119';
		$objMetodoBan->desmet       = 'e-PROVINCIAL_02';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0120';
		$objMetodoBan->desmet       = 'UNION';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0121';
		$objMetodoBan->desmet       = 'UNIBANCA';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0122';
		$objMetodoBan->desmet       = 'UNIBANCA_20_Digitos';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0123';
		$objMetodoBan->desmet       = 'VENEZUELA';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0125';
		$objMetodoBan->desmet       = 'INDUSTRIAL';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0126';
		$objMetodoBan->desmet       = 'DEL SUR E.A.P.';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0127';
		$objMetodoBan->desmet       = 'BANESCO';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0128';
		$objMetodoBan->desmet       = 'BANESCO_PAYMUL';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0129';
		$objMetodoBan->desmet       = 'BANFOANDES';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0130';
		$objMetodoBan->desmet       = 'SOFITASA';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0131';
		$objMetodoBan->desmet       = 'MI CASA';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0132';
		$objMetodoBan->desmet       = 'FONDO COMUN';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0133';
		$objMetodoBan->desmet       = 'EAP_MICASA';
		$objMetodoBan->tipmet       = '0';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0200';
		$objMetodoBan->desmet       = 'SIN METODO';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0201';
		$objMetodoBan->desmet       = 'VIVIENDA';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0202';
		$objMetodoBan->desmet       = 'CASA PROPIA';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0203';
		$objMetodoBan->desmet       = 'MERENAP';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0204';
		$objMetodoBan->desmet       = 'MIRANDA';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0205';
		$objMetodoBan->desmet       = 'FONDO MUTUAL HABITACIONAL';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0206';
		$objMetodoBan->desmet       = 'BANESCO';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0207';
		$objMetodoBan->desmet       = 'MI CASA EAP';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0208';
		$objMetodoBan->desmet       = 'CANARIAS';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0209';
		$objMetodoBan->desmet       = 'VENEZUELA';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0210';
		$objMetodoBan->desmet       = 'DELSUR';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0211';
		$objMetodoBan->desmet       = 'MERCANTIL';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0212';
		$objMetodoBan->desmet       = 'CENTRAL';
		$objMetodoBan->tipmet       = '1';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0300';
		$objMetodoBan->desmet       = 'SIN METODO';
		$objMetodoBan->tipmet       = '2';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0301';
		$objMetodoBan->desmet       = 'CARIBE';
		$objMetodoBan->tipmet       = '2';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0302';
		$objMetodoBan->desmet       = 'UNION';
		$objMetodoBan->tipmet       = '2';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0303';
		$objMetodoBan->desmet       = 'OCEPRE';
		$objMetodoBan->tipmet       = '2';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0304';
		$objMetodoBan->desmet       = 'MERCANTIL';
		$objMetodoBan->tipmet       = '2';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0305';
		$objMetodoBan->desmet       = 'VENEZOLANO DE CREDITO';
		$objMetodoBan->tipmet       = '2';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		$objMetodoBan   = new MetodoBanco();
		$objMetodoBan->codemp       = '0001';
		$objMetodoBan->codmet       = '0306';
		$objMetodoBan->desmet       = 'BANCO DE VENEZUELA';
		$objMetodoBan->tipmet       = '2';
		$objMetodoBan->codempnom    = '';
		$objMetodoBan->tipcuecrenom = '';
		$objMetodoBan->tipcuedebnom = '';
		$objMetodoBan->codofinom    = '';
		$objMetodoBan->debcuelph    = '';
		$objMetodoBan->codagelph    = '';
		$objMetodoBan->apaposlph    = '';
		$objMetodoBan->numplalph    = '';
		$objMetodoBan->numconlph    = '';
		$objMetodoBan->suclph       = '';
		$objMetodoBan->cuelph       = '';
		$objMetodoBan->grulph       = '';
		$objMetodoBan->subgrulph    = '';
		$objMetodoBan->conlph       = '';
		$objMetodoBan->numactlph    = '';
		$objMetodoBan->numofifps    = '';
		$objMetodoBan->confps       = '';
		$objMetodoBan->nroplafps    = '';
		$objMetodoBan->numconnom    = '';
		$objMetodoBan->pagtaqnom    = '';
		$objMetodoBan->nroref       = '';	
		$objMetodoBan->incluir();
		
		//Insertar tipos de dedicacion por defecto
		$objDedicacion->codemp = '0001';
		$objDedicacion->codded = '000';
		$objDedicacion->desded = 'Sin dedicación';
		$objDedicacion->incluir();
		
		$objDedicacion = New Dedicacion();
		$objDedicacion->codemp = '0001';
		$objDedicacion->codded = '100';
		$objDedicacion->desded = 'Personal Fijo Tiempo Completo';
		$objDedicacion->incluir();
		
		$objDedicacion = New Dedicacion();
		$objDedicacion->codemp = '0001';
		$objDedicacion->codded = '200';
		$objDedicacion->desded = 'Personal Fijo Tiempo Parcial';
		$objDedicacion->incluir();
		
		$objDedicacion = New Dedicacion();
		$objDedicacion->codemp = '0001';
		$objDedicacion->codded = '300';
		$objDedicacion->desded = 'Personal Contratado';
		$objDedicacion->incluir();
		
		//Insertar tipos de personal por defecto
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '000';
		$objTipPersonal->codtipper = '0000';
		$objTipPersonal->destipper = 'Sin tipo de personal';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '100';
		$objTipPersonal->codtipper = '0101';
		$objTipPersonal->destipper = 'Directivo';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '100';
		$objTipPersonal->codtipper = '0102';
		$objTipPersonal->destipper = 'Profesional y Técnico';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '100';
		$objTipPersonal->codtipper = '0103';
		$objTipPersonal->destipper = 'Personal Administrativo';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '100';
		$objTipPersonal->codtipper = '0104';
		$objTipPersonal->destipper = 'Personal Docente';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '100';
		$objTipPersonal->codtipper = '0105';
		$objTipPersonal->destipper = 'Personal de Investigación';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '100';
		$objTipPersonal->codtipper = '0106';
		$objTipPersonal->destipper = 'Personal Médico';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '100';
		$objTipPersonal->codtipper = '0107';
		$objTipPersonal->destipper = 'Personal Obrero';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '200';
		$objTipPersonal->codtipper = '0201';
		$objTipPersonal->destipper = 'Directivo';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '200';
		$objTipPersonal->codtipper = '0202';
		$objTipPersonal->destipper = 'Profesional y Técnico';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '200';
		$objTipPersonal->codtipper = '0203';
		$objTipPersonal->destipper = 'Personal Administrativo';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '200';
		$objTipPersonal->codtipper = '0204';
		$objTipPersonal->destipper = 'Personal Docente';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '200';
		$objTipPersonal->codtipper = '0205';
		$objTipPersonal->destipper = 'Personal de Investigación';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '200';
		$objTipPersonal->codtipper = '0206';
		$objTipPersonal->destipper = 'Personal Médico';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '200';
		$objTipPersonal->codtipper = '0207';
		$objTipPersonal->destipper = 'Personal Obrero';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '300';
		$objTipPersonal->codtipper = '0301';
		$objTipPersonal->destipper = 'Directivo';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '300';
		$objTipPersonal->codtipper = '0302';
		$objTipPersonal->destipper = 'Profesional y Técnico';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '300';
		$objTipPersonal->codtipper = '0303';
		$objTipPersonal->destipper = 'Personal Administrativo';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '300';
		$objTipPersonal->codtipper = '0304';
		$objTipPersonal->destipper = 'Personal Docente';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '300';
		$objTipPersonal->codtipper = '0305';
		$objTipPersonal->destipper = 'Personal de Investigación';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '300';
		$objTipPersonal->codtipper = '0306';
		$objTipPersonal->destipper = 'Personal Médico';
		$objTipPersonal->incluir();
		
		$objTipPersonal = new TipoPersonal();
		$objTipPersonal->codemp    = '0001';
		$objTipPersonal->codded    = '300';
		$objTipPersonal->codtipper = '0307';
		$objTipPersonal->destipper = 'Personal Obrero';
		$objTipPersonal->incluir();
		
		//Insertar tipo de personal SSS por defecto
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '-------';
		$objTipPersSSS->dentippersss = 'TODOS LOS EMPLEADOS';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000001';
		$objTipPersSSS->dentippersss = 'EMPLEADO FIJO';
		$objTipPersSSS->incluir();	

		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000002';
		$objTipPersSSS->dentippersss = 'EMPLEADO CONTRATADO';
		$objTipPersSSS->incluir();	
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000003';
		$objTipPersSSS->dentippersss = 'EMPLEADO SUPLENTE';
		$objTipPersSSS->incluir();	
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000004';
		$objTipPersSSS->dentippersss = 'OBRERO FIJO';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000005';
		$objTipPersSSS->dentippersss = 'OBRERO CONTRATADO';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000006';
		$objTipPersSSS->dentippersss = 'OBRERO SUPLENTE';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000007';
		$objTipPersSSS->dentippersss = 'DOCENTE FIJO';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000008';
		$objTipPersSSS->dentippersss = 'DOCENTE CONTRATADO';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000009';
		$objTipPersSSS->dentippersss = 'DOCENTE SUPLENTE';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000010';
		$objTipPersSSS->dentippersss = 'JUBILADO';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000011';
		$objTipPersSSS->dentippersss = 'COMISION DE SERVICIOS';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000012';
		$objTipPersSSS->dentippersss = 'LIBRE NOMBRAMIENTO';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000013';
		$objTipPersSSS->dentippersss = 'MILITAR';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000014';
		$objTipPersSSS->dentippersss = 'PENSIONADOS';
		$objTipPersSSS->incluir();
		
		$objTipPersSSS = new TipoPersonalSSS();
		$objTipPersSSS->codemp       = '0001';
		$objTipPersSSS->codtippersss = '0000015';
		$objTipPersSSS->dentippersss = 'SUPLENTE';
		$objTipPersSSS->incluir();
		
		unset($objDedicacion);
		unset($objMetodoBan);
		unset($objTipPersonal);
		unset($objTipPersSSS);
	}
	
	function crearDatosSnoComponente()
	{
		$objComponente = new Componente();	
		//Insertar componentes por defecto
		$objComponente->codemp = '0001';
		$objComponente->codcom = '0000000001';
		$objComponente->descom = 'COMPONENTE EJERCITO';
		$objComponente->incluir();

		$objComponente = new Componente();	
		$objComponente->codemp = '0001';
		$objComponente->codcom = '0000000002';
		$objComponente->descom = 'COMPONENTE ARMADA';
		$objComponente->incluir();

		$objComponente = new Componente();	
		$objComponente->codemp = '0001';
		$objComponente->codcom = '0000000003';
		$objComponente->descom = 'COMPONETE AVIACION';
		$objComponente->incluir();

		$objComponente = new Componente();	
		$objComponente->codemp = '0001';
		$objComponente->codcom = '0000000004';
		$objComponente->descom = 'COMPONENTE GUARDIA';
		$objComponente->incluir();
		
		$objComponente = new Componente();	
		$objComponente->codemp = '0001';
		$objComponente->codcom = '0000000005';
		$objComponente->descom = 'COMPONENTE RESERVA';
		$objComponente->incluir();
		
		$objComponente = new Componente();	
		$objComponente->codemp = '0001';
		$objComponente->codcom = '0000000006';
		$objComponente->descom = 'CADETES';
		$objComponente->incluir();
		
		$objComponente = new Componente();	
		$objComponente->codemp = '0001';
		$objComponente->codcom = '0000000007';
		$objComponente->descom = 'CADETES NAVAL';
		$objComponente->incluir();
		
		unset($objComponente);
	}
	
	function crearDatosSnoRango()
	{
		$objRango = new Rango();
		//Insertar rango por defecto
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000001';
		$objRango->desran = 'GENERAL EN JEFE';
		$objRango->codcat = '';
		$objRango->incluir();

		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000002';
		$objRango->desran = 'GENERAL DE DIVISION';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000003';
		$objRango->desran = 'GENERAL DE BRIGADA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000004';
		$objRango->desran = 'CORONEL';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000005';
		$objRango->desran = 'TENIENTE CORONEL';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000006';
		$objRango->desran = 'MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000008';
		$objRango->desran = 'TENIENTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000009';
		$objRango->desran = 'SUB TENIENTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000010';
		$objRango->desran = 'MAESTRO TECNICO SUPERVISOR';
		$objRango->codcat = '';
		$objRango->incluir();

		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000011';
		$objRango->desran = 'MAESTRO TECNICO MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000012';
		$objRango->desran = 'MAESTRO TECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000013';
		$objRango->desran = 'MAESTRO TECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000014';
		$objRango->desran = 'MAESTRO TECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000015';
		$objRango->desran = 'SARGENTO TECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000017';
		$objRango->desran = 'SARGENTO TECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000018';
		$objRango->desran = 'SARGENTO SUPERVISOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000019';
		$objRango->desran = 'SARGENTO AYUDANTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000020';
		$objRango->desran = 'SARGENTO MAYOR DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000021';
		$objRango->desran = 'SARGENTO MAYOR DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000022';
		$objRango->desran = 'SARGENTO MAYOR DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000023';
		$objRango->desran = 'SARGENTO PRIMERO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000024';
		$objRango->desran = 'SARGENTO SEGUNDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000025';
		$objRango->desran = 'CABO PRIMERO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000026';
		$objRango->desran = 'CABO SEGUNDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000027';
		$objRango->desran = 'DISTINGUIDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000001';
		$objRango->codran = '0000000028';
		$objRango->desran = 'SOLDADO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000001';
		$objRango->desran = 'ALMIRANTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000002';
		$objRango->desran = 'VICEALMIRANTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000003';
		$objRango->desran = 'CONTRAALMIRANTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000004';
		$objRango->desran = 'CAPITAN DE NAVIO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000005';
		$objRango->desran = 'CAPITAN DE FRAGATA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000006';
		$objRango->desran = 'CAPITAN DE CORBETA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000007';
		$objRango->desran = 'TENIENTE DE NAVIO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000008';
		$objRango->desran = 'TENIENTE DE FRAGATA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000009';
		$objRango->desran = 'ALFEREZ DE NAVIO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000010';
		$objRango->desran = 'MAESTRE SUPERVISOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000011';
		$objRango->desran = 'MAESTRE MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000013';
		$objRango->desran = 'MAESTRE AUXILIAR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000014';
		$objRango->desran = 'MAESTRE TECNICO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000016';
		$objRango->desran = 'MAESTRE DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000017';
		$objRango->desran = 'MAESTRE DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000018';
		$objRango->desran = 'SARGENTO SUPERVISOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000019';
		$objRango->desran = 'SARGENTO AYUDANTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000020';
		$objRango->desran = 'SARGENTO MAYOR DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000021';
		$objRango->desran = 'SARGENTO MAYOR DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000022';
		$objRango->desran = 'SARGENTO MAYOR DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000023';
		$objRango->desran = 'SARGENTO PRIMERO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000024';
		$objRango->desran = 'SARGENTO SEGUNDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000025';
		$objRango->desran = 'CABO PRIMERO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000026';
		$objRango->desran = 'CABO SEGUNDO';
		$objRango->codcat = '';
		$objRango->incluir();

		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000027';
		$objRango->desran = 'MARINERO DISTINGUIDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000002';
		$objRango->codran = '0000000028';
		$objRango->desran = 'MARINERO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000001';
		$objRango->desran = 'GENERAL EN JEFE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000002';
		$objRango->desran = 'GENERAL DE DIVISION';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000003';
		$objRango->desran = 'GENERAL DE BRIGADA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000004';
		$objRango->desran = 'CORONEL';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000005';
		$objRango->desran = 'TENIENTE CORONEL';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000006';
		$objRango->desran = 'MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000007';
		$objRango->desran = 'CAPITAN';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000008';
		$objRango->desran = 'TENIENTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000009';
		$objRango->desran = 'SUB TENIENTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000010';
		$objRango->desran = 'MAESTRO TECNICO SUPERVISOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000011';
		$objRango->desran = 'MAESTRO TECNICO MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000012';
		$objRango->desran = 'MAESTRO TECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000013';
		$objRango->desran = 'MAESTRO TECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000014';
		$objRango->desran = 'MAESTRO TECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000015';
		$objRango->desran = 'SARGENTO TECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000016';
		$objRango->desran = 'SARGENTO TECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000017';
		$objRango->desran = 'MAESTRO TECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000018';
		$objRango->desran = 'AEROTECNICO SUPERVISOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000019';
		$objRango->desran = 'AEROTECNICO AYUDANTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000020';
		$objRango->desran = 'AEROTECNICO MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000021';
		$objRango->desran = 'AEROTECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000022';
		$objRango->desran = 'AEROTECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000023';
		$objRango->desran = 'AEROTECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000024';
		$objRango->desran = 'AEROTECNICO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000025';
		$objRango->desran = 'CABO PRIMERO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000026';
		$objRango->desran = 'CABO SEGUNDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000027';
		$objRango->desran = 'DISTINGUIDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000003';
		$objRango->codran = '0000000028';
		$objRango->desran = 'SOLDADO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000001';
		$objRango->desran = 'GENERAL EN JEFE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000002';
		$objRango->desran = 'GENERAL DE DIVISION';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000003';
		$objRango->desran = 'GENERAL DE BRIGADA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000004';
		$objRango->desran = 'CORONEL';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000005';
		$objRango->desran = 'TENIENTE CORONEL';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000006';
		$objRango->desran = 'MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000007';
		$objRango->desran = 'CAPITAN';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000008';
		$objRango->desran = 'TENIENTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000009';
		$objRango->desran = 'SUB TENIENTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000010';
		$objRango->desran = 'MAESTRO TECNICO SUPERIOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000011';
		$objRango->desran = 'MAESTRO TECNICO MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000012';
		$objRango->desran = 'MAESTRO TECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000013';
		$objRango->desran = 'MAESTRO TECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000014';
		$objRango->desran = 'MAESTRO TECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000015';
		$objRango->desran = 'SARGENTO TECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000016';
		$objRango->desran = 'SARGENTO TECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000017';
		$objRango->desran = 'MAESTRO TECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000018';
		$objRango->desran = 'AEROTECNICO SUPERVISOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000019';
		$objRango->desran = 'AEROTECNICO AYUDANTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000020';
		$objRango->desran = 'AEROTECNICO MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000021';
		$objRango->desran = 'AEROTECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000022';
		$objRango->desran = 'AEROTECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000023';
		$objRango->desran = 'AEROTECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000024';
		$objRango->desran = 'AEROTECNICO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000025';
		$objRango->desran = 'CABO PRIMERO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000026';
		$objRango->desran = 'CABO SEGUNDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000027';
		$objRango->desran = 'DISTINGUIDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000004';
		$objRango->codran = '0000000028';
		$objRango->desran = 'SOLDADO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000001';
		$objRango->desran = 'GENERAL EN JEFE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000002';
		$objRango->desran = 'GENERAL DE DIVISION';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000003';
		$objRango->desran = 'GENERAL DE BRIGADA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000004';
		$objRango->desran = 'CORONEL';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000005';
		$objRango->desran = 'TENIENTE CORONEL';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000006';
		$objRango->desran = 'MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000007';
		$objRango->desran = 'CAPITAN';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000008';
		$objRango->desran = 'TENIENTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000009';
		$objRango->desran = 'SUB TENIENTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000010';
		$objRango->desran = 'MAESTRO TECNICO SUPERIOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000011';
		$objRango->desran = 'MAESTRO TECNICO MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000012';
		$objRango->desran = 'MAESTRO TECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000013';
		$objRango->desran = 'MAESTRO TECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000014';
		$objRango->desran = 'MAESTRO TECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000015';
		$objRango->desran = 'SARGENTO TECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000016';
		$objRango->desran = 'SARGENTO TECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000017';
		$objRango->desran = 'MAESTRO TECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000018';
		$objRango->desran = 'AEROTECNICO SUPERVISOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000019';
		$objRango->desran = 'AEROTECNICO AYUDANTE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000020';
		$objRango->desran = 'AEROTECNICO MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000021';
		$objRango->desran = 'AEROTECNICO DE PRIMERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000022';
		$objRango->desran = 'AEROTECNICO DE SEGUNDA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000023';
		$objRango->desran = 'AEROTECNICO DE TERCERA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000024';
		$objRango->desran = 'AEROTECNICO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000025';
		$objRango->desran = 'CABO PRIMERO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000026';
		$objRango->desran = 'CABO SEGUNDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000027';
		$objRango->desran = 'DISTINGUIDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000005';
		$objRango->codran = '0000000028';
		$objRango->desran = 'SOLDADO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000001';
		$objRango->desran = 'ALFEREZ MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000002';
		$objRango->desran = 'ALFEREZ AUXILIAR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000003';
		$objRango->desran = 'ALFEREZ';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000004';
		$objRango->desran = 'BRIGADIER MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000005';
		$objRango->desran = 'PRIMER BRIGADIER';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000006';
		$objRango->desran = 'BRIGADIER';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000007';
		$objRango->desran = 'SUB-BRIGADIER';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000008';
		$objRango->desran = 'DISTINGUIDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000006';
		$objRango->codran = '0000000009';
		$objRango->desran = 'CADETE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000001';
		$objRango->desran = 'GUARDIA MARINA MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000002';
		$objRango->desran = 'GUARDIA MARINA AUXILIAR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000003';
		$objRango->desran = 'GUARDIA MARINA';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000004';
		$objRango->desran = 'BRIGADIER MAYOR';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000005';
		$objRango->desran = 'PRIMER BRIGADIER';
		$objRango->codcat = '';
		$objRango->incluir();

		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000006';
		$objRango->desran = 'BRIGADIER';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000007';
		$objRango->desran = 'SUB-BRIGADIER';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000008';
		$objRango->desran = 'DISTINGUIDO';
		$objRango->codcat = '';
		$objRango->incluir();
		
		$objRango = new Rango();
		$objRango->codemp = '0001';
		$objRango->codcom = '0000000007';
		$objRango->codran = '0000000009';
		$objRango->desran = 'CADETE';
		$objRango->codcat = '';
		$objRango->incluir();
		
		unset($objRango);
	}
}
?>