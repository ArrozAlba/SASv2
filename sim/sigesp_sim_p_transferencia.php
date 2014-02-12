<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_p_transferencia.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // as_valor         //  nombre de la variable que desamos obtener
    // as_valordefecto  //  contenido de la variable
    // Description: Funci�n que obtiene el valor de una variable si viene de un submit
	//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor;
   }
   //--------------------------------------------------------------

   function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_seleccionarcombo
	//	Access:    public
	//	Arguments:
	//  as_valores // valores que puede tomar el combo
	//  as_seleccionado // item seleccionado
	//  aa_parametro  // arreglo de seleccionados
	//  li_total // total de elementos en el combo
	//	Description:  Esta funcion mantiene la seleccion de un combo despues de hacer un submit
	//
	//////////////////////////////////////////////////////////////////////////////
   		$la_valores = split("-",$as_valores);
		for($li_index=0;$li_index<$li_total;++$li_index)
		{
			if($la_valores[$li_index]==$as_seleccionado)
			{
				$aa_parametro[$li_index]=" selected";
			}
		}
   }
   //--------------------------------------------------------------

   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_agregarlineablanca
	//	Access:    public
	//	Arguments:
	//  aa_object // arreglo de titulos
	//  ai_totrows // ultima fila pintada en el grid
	//	Description:  Funcion que agrega una linea en blanco al final del grid
	//
	//////////////////////////////////////////////////////////////////////////////

		$aa_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."   type=text   id=txtdenart".$ai_totrows." class=sin-borde size=30 maxlength=60 readonly>".
								   "<input name=txtcodart".$ai_totrows."   type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=31 maxlength=30 onKeyUp='javascript: ue_validarcomillas(this);' readonly>".
								   "<a href='javascript: ue_catarticulo(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";

		$aa_object[$ai_totrows][2]="<input name=txtdenproveedor".$ai_totrows."   type=text id=txtdenproveedor".$ai_totrows."   class=sin-borde size=30 maxlength=35  readonly><input name=txtcodproveedor".$ai_totrows."   type=text id=txtcodproveedor".$ai_totrows."   class=sin-borde size=10 maxlength=12  readonly>";
		$aa_object[$ai_totrows][3]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:100px ' onChange='javascript:ue_montosfactura(".$ai_totrows.");'><option value=D>Detal</option><option value=M selected>Mayor</option></select></div><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."'>";
		$aa_object[$ai_totrows][4]="<input name=txtcantidad".$ai_totrows." type=text id=txtcantidad".$ai_totrows." class=sin-borde size=14 maxlength=12  onKeyPress=return(ue_formatonumero(this,'.',',',event))  onBlur='javascript:ue_montosfactura(".$ai_totrows.");'> <input name=txtexistencia".$ai_totrows."   type='hidden' id=txtexistencia".$ai_totrows." class=sin-borde size=10 maxlength=12  readonly>";
		$aa_object[$ai_totrows][5]="<input name=txtcosuni".$ai_totrows."   type=text id=txtcosuni".$ai_totrows."   class=sin-borde size=14 maxlength=15  readonly>";
		$aa_object[$ai_totrows][6]="<input name=txtcostot".$ai_totrows."   type=text id=txtcostot".$ai_totrows."   class=sin-borde size=14 maxlength=15  readonly>";

		$aa_object[$ai_totrows][7]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][8]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";//
   }
   	//--------------------------------------------------------------

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_numtra,$ld_fecemi,$ls_codusu,$ls_codalmori,$ls_codalmdes,$ls_nomalmori,$ls_nomalmdes,$ls_obstra,$ls_readonly;
		global $ls_status;
		$ls_numtra="";
		$ld_fecemi=date("d/m/Y ");
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_codalmori="";
		$ls_codalmdes="";
		$ls_nomalmori="";
		$ls_nomalmdes="";
		$ls_obstra="";
		$ls_readonly="true";
		$ls_status="";
   }

   function uf_obtenervalorunidad($li_i)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalorunidad
	//	Access:    public
	//	Arguments:
    // 				li_i         //  valor del
    // 				ls_valor     //  nombre de la variable que desamos obtener
    // Description: Funci�n que obtiene el contenido del combo cmbunidad o
	//				del campo txtunidad deacuerdo sea el caso
	//////////////////////////////////////////////////////////////////////////////
		if (array_key_exists("cmbunidad".$li_i,$_POST))
		{
			$ls_valor= $_POST["cmbunidad".$li_i];
		}
		else
		{
			$ls_valoraux= $_POST["txtcoduni".$li_i];
			if($ls_valoraux=="Mayor")
			{
				$ls_valor="M";
			}
			else
			{
				$ls_valor="D";
			}
		}
   		return $ls_valor;
   }

   //--------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Transferencia entre Almacenes</title>
<meta http-equiv="imagetoolbar" content="no">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function(){
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505;
		}
		if(window.event.keyCode == 505){ return false;}
		}
	}
</script>
<style type="text/css">
<!--
.Estilo1 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>

<body>
<table width="662" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Inventario</span></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con=    $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql= new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid=new grid_param();
	require_once("sigesp_sim_c_transferencia.php");
	$io_siv= new sigesp_sim_c_transferencia();
	require_once("sigesp_sim_c_articuloxalmacen.php");
	$io_art= new sigesp_sim_c_articuloxalmacen();
	require_once("sigesp_sim_c_articuloxalmacen_transf.php");
	$io_archivo= new sigesp_sim_c_articuloxalmacen_transf();
	require_once("sigesp_sim_c_movimientoinventario.php");
	$io_mov= new sigesp_sim_c_movimientoinventario();

	require_once("sigesp_sim_c_recepcion.php");
	$io_rec= new sigesp_sim_c_recepcion();
	//require_once("sigesp_sim_c_transferencia_transf.php");
	//$io_archivo= new sigesp_sim_c_transferencia_transf();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = uf_obtenervalor("totalfilas",1);

	$ls_titletable="Detalle de la Transferencia";
	$li_widthtable=750;
	$ls_nametable="grid";

	$lo_title[1]="Producto";
	$lo_title[2]="Proveedor";
	$lo_title[3]="Unidad de Medida";
	$lo_title[4]="Cantidad";
	$lo_title[5]="Costo Unitario";
	$lo_title[6]="Costo Total";
	$lo_title[7]="";
	$lo_title[8]="";
	//$lo_title[9]="Proveedor";

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_status=    $_POST["hidestatus"];
		if ($ls_status=="C")
		{
			$ls_readonly=$_POST["hidreadonly"];
			if (array_key_exists("catafilas",$_POST))
			{
				$li_catafilas=$_POST["catafilas"];
			}
			else
			{
			$li_catafilas="";
			}
		}
		else
		{
			$ls_status="";
		}

	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
		uf_agregarlineablanca($lo_object,1);
	}
	switch ($ls_operacion)
	{

		case "NUEVO":
			uf_agregarlineablanca($lo_object,1);
			uf_limpiarvariables();
			$li_totrows=1;

		break;
		case "GUARDAR":
			$ls_numtra=     $_POST["txtnumtra"];
			$ld_fecemi=     $_POST["txtfecemi"];
			$ls_codalmori=  $_POST["txtcodalm"];
			$ls_codalmdes=  $_POST["txtcodalmdes"];
			$ls_nomalmori=  $_POST["txtnomfisalm"];
			$ls_nomalmdes=  $_POST["txtnomfisdes"];
			$ls_obstra=     $_POST["txtobstra"];
			$ls_codtiend=substr($ls_codalmori,6,4);
		//	print $ls_numtra."--".$ls_codtiend;
			$ls_codtienddes=substr($ls_codalmdes,6,4);
			//print $ls_codtienddes;
			$io_sql->begin_transaction();

			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecemi);
			if($lb_valido)
			{
				$ld_fecemi=$io_func->uf_convertirdatetobd($ld_fecemi);
				if ($ls_status=="C")
				{
					$io_msg->message("La transferencia entre almacenes no debe ser modificada");
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,$li_totrows);
					uf_limpiarvariables();
				}
				else
				{
					$lb_encontrado=$io_siv->uf_sim_select_transferencia($ls_codemp,$ls_numtra,$ld_fecemi,$ls_codtiend);
					if ($lb_encontrado)
					{
						$io_msg->message("La transferencia entre almacenes ya existe");
					}
					else
					{
						//$io_sql->begin_transaction();
						$ls_nummov="";
						$lb_valido1=false;
						$lb_existencia=false;
						$lb_valido=$io_siv->uf_sim_insert_transferencia($ls_codemp,$ls_numtra,$ld_fecemi,$ls_codusu,$ls_codalmori,
																		$ls_codalmdes,$ls_obstra,$la_seguridad,$ls_codtiend);

						//$io_archivo->uf_sim_insert_transferencia_transf($ls_codemp,$ls_numtra,$ld_fecemi,$ls_codusu,$ls_codalmori,																	$ls_codalmdes,$ls_obstra,$la_seguridad);

						if ($lb_valido)
						{
							$ls_nummov=0;
							$ls_nomsol="Transferencia";

							$lb_valido=$io_mov->uf_sim_insert_movimiento($ls_nummov,$ld_fecemi,$ls_nomsol,$ls_codusu,$la_seguridad,$ls_codtiend);
												 
							if ($lb_valido)
							{
								for($li_i=1;$li_i<$li_totrows;$li_i++)
								{

									$li_unidad=   $_POST["hidunidad".$li_i];
									$ls_unidad=   $_POST["txtcoduni".$li_i];
									$ls_codart=   $_POST["txtcodart".$li_i];
									$ls_denart=   $_POST["txtdenart".$li_i];
									$li_cantidad= $_POST["txtcantidad".$li_i];
									$li_cosuni=   $_POST["txtcosuni".$li_i];
									$li_costot=   $_POST["txtcostot".$li_i];
									$li_cantidad=  str_replace(".","",$li_cantidad);
									$li_cantidad=  str_replace(",",".",$li_cantidad);
									$li_cosuni=    str_replace(".","",$li_cosuni);
									$li_cosuni=    str_replace(",",".",$li_cosuni);
									$li_costot=    str_replace(".","",$li_costot);
									$li_costot=    str_replace(",",".",$li_costot);
									$ls_denproveedor=$_POST["txtdenproveedor".$li_i];
									$ls_codproveedor=$_POST["txtcodproveedor".$li_i];
									$li_existencia=$_POST["txtexistencia".$li_i];


									$lo_object[$li_i][1]="<input name=txtdenart".$li_i."   type=text   id=txtdenart".$li_i."   class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
														 "<input name=txtcodart".$li_i."   type=hidden id=txtcodart".$li_i."   class=sin-borde size=21 maxlength=20 value='".$ls_codart."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>";
									$lo_object[$li_i][2]="<input name=txtdenproveedor".$li_i."   type=text   id=txtdenproveedor".$li_i."   class=sin-borde size=30 maxlength=35 value='".$ls_denproveedor."'><input name=txtcodproveedor".$li_i."   type=text   id=txtcodproveedor".$li_i."   class=sin-borde size=10 maxlength=12 value='".$ls_codproveedor."' >";
									$lo_object[$li_i][3]="<input name=txtcoduni".$li_i."   type=text   id=txtcoduni".$li_i."   class=sin-borde size=14 maxlength=12 value='".$ls_unidad."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>".
														 "<input name='hidunidad".$li_i."' type=hidden id=hidunidad".$li_i."   value='". $li_unidad ."'>";
									$lo_object[$li_i][4]="<input name=txtcantidad".$li_i." type=text   id=txtcantidad".$li_i." class=sin-borde size=14 maxlength=12 value='".number_format ($li_cantidad,2,",",".")."'  onKeyUp='javascript: ue_validarnumero(this);'  onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur='javascript: ue_montosfactura(".$li_i.");'> <input name=txtexistencia".$li_i." type='hidden'   id=txtexistencia".$li_i." class=sin-borde size=10 maxlength=12 value='".number_format ($li_existencia,2,",",".")."' >";
									$lo_object[$li_i][5]="<input name=txtcosuni".$li_i."   type=text   id=txtcosuni".$li_i."   class=sin-borde size=14 maxlength=15 value='".number_format ($li_cosuni,2,",",".")."' onKeyUp='javascript: ue_validarnumero(this);' >";
									$lo_object[$li_i][6]="<input name=txtcostot".$li_i."   type=text   id=txtcostot".$li_i."   class=sin-borde size=14 maxlength=15 value='".number_format ($li_costot,2,",",".")."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
									$lo_object[$li_i][7]="";
									$lo_object[$li_i][8]="";

									switch ($ls_unidad)
									{
										case "Mayor":
											$ls_coduni="M";
											$li_cantidad=($li_cantidad * $li_unidad);
											break;
										case "Detal":
											$ls_coduni="D";
											break;
									}

									$lb_valido=$io_art->uf_sim_chequear_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalmori,$li_cantidad,$ls_codproveedor,$ls_codtiend);
									if ($lb_valido)
									{
										$lb_valido=$io_siv->uf_sim_guardar_dt_transferencia($ls_codemp,$ls_numtra,$ld_fecemi,$ls_codart,$ls_coduni,$li_cantidad,$li_cosuni,$li_costot,
																							 $la_seguridad,$ls_codproveedor,$ls_codtiend);
																							 //$as_codemp,$as_numtra,$ad_fecemi,$as_codart,$as_codunimed,$ai_cantidad,$ai_cosuni,$ai_costot

										if ($lb_valido)
										{
											$lb_valido=$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalmori,$li_cantidad,$ls_codproveedor,$ls_codtiend,$la_seguridad);
											//$io_archivo->uf_sim_disminuir_articuloxalmacen_transf($ls_codemp,$ls_codart,$ls_codalmori,$li_cantidad,$la_seguridad);
											if ($lb_valido)
											{
												$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalmdes,$li_cantidad,$la_seguridad,$ls_codproveedor,$ls_codtienddes);
												//$io_archivo->uf_sim_aumentar_articuloxalmacen_transf($ls_codemp,$ls_codart,$ls_codalmdes,$li_cantidad,$la_seguridad);
												if($lb_valido)
												{
													/*$ls_opeinv="SAL";
													$ls_codprodoc="ALM";
													$ls_promov="TRA";
													$li_candesart=0;*/
													$lb_valido=$io_siv->uf_sim_procesar_dt_movimientotransferencia($ls_codemp,$ls_nummov,$ls_codart,
																												   $ls_codalmori,$ls_unidad,$li_cantidad,
																												   $li_cosuni,$ld_fecemi,$ls_numtra,
																												   $la_seguridad,$ls_codproveedor,$ls_codtiend);
													/*if($lb_valido)
													{
														$ls_opeinv="ENT";
														$ls_codprodoc="ALM";
														$ls_promov="TRA";
														$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecemi,
																										$ls_codart,$ls_codalmdes,$ls_opeinv,
																										$ls_codprodoc,$ls_numtra,$li_cantidad,
																										$li_cosuni,$ls_promov,$ls_numtra,
																										$li_cantidad,$ld_fecemi,$la_seguridad,$ls_codproveedor,$ls_codtienddes);
													}*/
												}
											}
										}
									}
									else
									{

											//print "PASEEEE";
											//exit;
											$io_sql->rollback();
/////** PENDIENTEEEEE  ***///
									}
								}// for

							if ($lb_valido)
							{
								if ($lb_valido)
								{
									$lb_valido=$io_mov->uf_sim_insert_movimiento($ls_nummov,$ld_fecemi,$ls_nomsol,$ls_codusu,$la_seguridad,$ls_codtienddes);

									for($li_i=1;$li_i<$li_totrows;$li_i++)
									{

										$li_unidad=   $_POST["hidunidad".$li_i];
										$ls_unidad=   $_POST["txtcoduni".$li_i];
										$ls_codart=   $_POST["txtcodart".$li_i];
										$ls_denart=   $_POST["txtdenart".$li_i];
										$li_cantidad= $_POST["txtcantidad".$li_i];
										$li_cosuni=   $_POST["txtcosuni".$li_i];
										$li_costot=   $_POST["txtcostot".$li_i];
										$li_cantidad=  str_replace(".","",$li_cantidad);
										$li_cantidad=  str_replace(",",".",$li_cantidad);
										$li_cosuni=    str_replace(".","",$li_cosuni);
										$li_cosuni=    str_replace(",",".",$li_cosuni);
										$li_costot=    str_replace(".","",$li_costot);
										$li_costot=    str_replace(",",".",$li_costot);
										$ls_denproveedor=$_POST["txtdenproveedor".$li_i];
										$ls_codproveedor=$_POST["txtcodproveedor".$li_i];
										$li_existencia=$_POST["txtexistencia".$li_i];
										$li_penart=0;

										$lo_object[$li_i][1]="<input name=txtdenart".$li_i."   type=text   id=txtdenart".$li_i."   class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
															 "<input name=txtcodart".$li_i."   type=hidden id=txtcodart".$li_i."   class=sin-borde size=21 maxlength=20 value='".$ls_codart."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>";
										$lo_object[$li_i][2]="<input name=txtdenproveedor".$li_i."   type=text   id=txtdenproveedor".$li_i."   class=sin-borde size=30 maxlength=35 value='".$ls_denproveedor."'><input name=txtcodproveedor".$li_i."   type=text   id=txtcodproveedor".$li_i."   class=sin-borde size=10 maxlength=12 value='".$ls_codproveedor."' >";
										$lo_object[$li_i][3]="<input name=txtcoduni".$li_i."   type=text   id=txtcoduni".$li_i."   class=sin-borde size=14 maxlength=12 value='".$ls_unidad."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>".
															 "<input name='hidunidad".$li_i."' type=hidden id=hidunidad".$li_i."   value='". $li_unidad ."'>";
										$lo_object[$li_i][4]="<input name=txtcantidad".$li_i." type=text   id=txtcantidad".$li_i." class=sin-borde size=14 maxlength=12 value='".number_format ($li_cantidad,2,",",".")."'  onKeyUp='javascript: ue_validarnumero(this);'  onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur='javascript: ue_montosfactura(".$li_i.");'> <input name=txtexistencia".$li_i." type='hidden'   id=txtexistencia".$li_i." class=sin-borde size=10 maxlength=12 value='".number_format ($li_existencia,2,",",".")."' >";
										$lo_object[$li_i][5]="<input name=txtcosuni".$li_i."   type=text   id=txtcosuni".$li_i."   class=sin-borde size=14 maxlength=15 value='".number_format ($li_cosuni,2,",",".")."' onKeyUp='javascript: ue_validarnumero(this);' >";
										$lo_object[$li_i][6]="<input name=txtcostot".$li_i."   type=text   id=txtcostot".$li_i."   class=sin-borde size=14 maxlength=15 value='".number_format ($li_costot,2,",",".")."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
										$lo_object[$li_i][7]="";
										$lo_object[$li_i][8]="";

										switch ($ls_unidad)
										{
											case "Mayor":
												$ls_coduni="M";
												$li_cantidad=($li_cantidad * $li_unidad);
												break;
											case "Detal":
												$ls_coduni="D";
												break;
										}

										$ls_opeinv="ENT";
										$ls_estpro=1;
										$ls_estrec=1;
										$lb_valido=$io_rec->uf_sim_insert_recepcion($ls_codemp,$ls_numtra,$ls_codproveedor,$ls_codalmdes,$ld_fecemi,"Recepcion de productos por transferencia ",
																	$ls_codusu,$ls_estpro,$ls_estrec,&$ls_numconrec,$ls_codtienddes,$la_seguridad);

										if($lb_valido)
										{

											$lb_valido=$io_rec->uf_sim_insert_dt_recepcion($ls_codemp,$ls_numtra,$ls_codart,$ls_coduni,$li_cantidad,
											$li_penart,$li_cosuni,$li_costot,$li_costot,$li_i,$li_cantidad,$ls_numconrec,$ls_codtienddes,
											$ls_codproveedor,$la_seguridad);


															$ls_codprodoc="ALM";
															$ls_promov="TRA";
															$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecemi,
																											$ls_codart,$ls_codalmdes,$ls_opeinv,
																											$ls_codprodoc,$ls_numtra,$li_cantidad,
																											$li_cosuni,$ls_promov,$ls_numtra,
																											$li_cantidad,$ld_fecemi,$la_seguridad,$ls_codtienddes,$ls_codproveedor);
																									
										}
										else
										{
											$io_sql->rollback();
										}
									}

								}
							}

							else
							{
								$io_sql->rollback();
							}
							}
						}
						$ld_fecemi=$io_func->uf_convertirfecmostrar($ld_fecemi);
						if($lb_valido)
						{
							$io_sql->commit();
							//$io_msg->message("El Numero de Movimiento correspondiente es: ".$ls_numtra);
							$io_msg->message("La transferencia entre almacenes ha sido procesada");
							$ls_status="C";
							$li_totrows=$li_totrows-1;

							print("<script language=JavaScript>");
							print("pagina='../sim/sigesp_sim_p_transferencia.php';");
		   					print(" location.href='../sfc/sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    				print("</script>");

/*							uf_agregarlineablanca($lo_object,1);
							uf_limpiarvariables();
							$li_totrows=1;
*/						}
						else
						{
							$io_sql->rollback();
							$li_totrows=1;
							uf_agregarlineablanca($lo_object,1);
							$io_msg->message("No se pudo procesar la transferencia entre almacenes");

						}
					}
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_limpiarvariables();
			}
			break;

		case "AGREGARDETALLE":
			$li_totrows=$li_totrows+1;
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_numtra=     $_POST["txtnumtra"];
				$ld_fecemi=     $_POST["txtfecemi"];
				$ls_codalmori=  $_POST["txtcodalm"];
				$ls_codalmdes=  $_POST["txtcodalmdes"];
				$ls_nomalmori=  $_POST["txtnomfisalm"];
				$ls_nomalmdes=  $_POST["txtnomfisdes"];
				$ls_obstra=     $_POST["txtobstra"];
				$ls_codart=     $_POST["txtcodart".$li_i];
				$ls_denart=     $_POST["txtdenart".$li_i];
				$ls_denproveedor=$_POST["txtdenproveedor".$li_i];
				$ls_codproveedor=$_POST["txtcodproveedor".$li_i];
				$ls_unidad= uf_obtenervalorunidad($li_i);
				$li_unidad=     $_POST["hidunidad".$li_i];
				$li_cantidad=   $_POST["txtcantidad".$li_i];
				$li_cosuni=     $_POST["txtcosuni".$li_i];
				$li_costot=     $_POST["txtcostot".$li_i];
				$li_existencia=   $_POST["txtexistencia".$li_i];



				switch ($ls_unidad)
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}


				$lo_object[$li_i][1]="<input name=txtdenart".$li_i."   type=text id=txtdenart".$li_i."   class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=21 maxlength=20 value='".$ls_codart."' onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catarticulo(".$li_i.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
				$lo_object[$li_i][2]="<input name=txtdenproveedor".$li_i."   type=text id=txtdenproveedor".$li_i."   class=sin-borde size=30 maxlength=35 value='".$ls_denproveedor."'> <input name=txtcodproveedor".$li_i."   type=text id=txtcodproveedor".$li_i."   class=sin-borde size=10 maxlength=12 value='".$ls_codproveedor."'>";
				$lo_object[$li_i][3]="<input name=txtcoduni".$li_i."   type=text id=txtcoduni".$li_i."   class=sin-borde size=14 maxlength=12 value='".$ls_unidadaux."' onKeyUp='javascript: ue_validarcomillas(this);' readonly><input name='hidunidad".$li_i."' type='hidden' id='hidunidad".$li_i."' value='". $li_unidad ."'>";
				$lo_object[$li_i][4]="<input name=txtcantidad".$li_i." type=text id=txtcantidad".$li_i." class=sin-borde size=14 maxlength=12 value='".$li_cantidad."' onBlur='javascript: ue_montosfactura(".$li_i.");'> <input name=txtexistencia".$li_i." type='hidden' id=txtexistencia".$li_i." class=sin-borde size=10 maxlength=12 value='".$li_existencia."' >";
				$lo_object[$li_i][5]="<input name=txtcosuni".$li_i."   type=text id=txtcosuni".$li_i."   class=sin-borde size=14 maxlength=15 value='".$li_cosuni."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_i][6]="<input name=txtcostot".$li_i."   type=text id=txtcostot".$li_i."   class=sin-borde size=14 maxlength=15 value='".$li_costot."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$lo_object[$li_i][7]="";
				$lo_object[$li_i][8]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
			$ls_numtra=     $_POST["txtnumtra"];
			$ld_fecemi=     $_POST["txtfecemi"];
			$ls_codalmori=  $_POST["txtcodalm"];
			$ls_codalmdes=  $_POST["txtcodalmdes"];
			$ls_nomalmori=  $_POST["txtnomfisalm"];
			$ls_nomalmdes=  $_POST["txtnomfisdes"];
			$ls_obstra=     $_POST["txtobstra"];

			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];

			$li_temp=0;

			for($li_t=1;$li_t<=$li_totrows;$li_t++)
			{
				if($li_t!=$li_rowdelete)
				{

					$li_temp=$li_temp+1;
					$ls_codart=     $_POST["txtcodart".$li_t];
					$ls_denart=     $_POST["txtdenart".$li_t];
					$ls_unidad=     $_POST["txtcoduni".$li_t];
					$li_unidad=     $_POST["hidunidad".$li_t];
					$li_cantidad=   $_POST["txtcantidad".$li_t];
					$li_cosuni=     $_POST["txtcosuni".$li_t];
					$li_costot=     $_POST["txtcostot".$li_t];
					$ls_denproveedor=$_POST["txtdenproveedor".$li_t];
				    $ls_codproveedor=$_POST["txtcodproveedor".$li_t];
				    $li_existencia=   $_POST["txtexistencia".$li_t];


					$lo_object[$li_temp][1]="<input name=txtdenart".$li_temp."   type=text id=txtdenart".$li_temp."   class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$li_temp." type=text id=txtcodart".$li_temp." class=sin-borde size=21 maxlength=20 value='".$ls_codart."' readonly><a href='javascript: ue_catarticulo(".$li_temp.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_temp][2]="<input name=txtdenproveedor".$li_temp."   type=text id=txtdenproveedor".$li_temp."   class=sin-borde size=30 maxlength=35 value='".$ls_denproveedor."' ><input name=txtcodproveedor".$li_temp."   type=text id=txtcodproveedor".$li_temp."   class=sin-borde size=10 maxlength=12 value='".$ls_codproveedor."' >";
					$lo_object[$li_temp][3]="<input name=txtcoduni".$li_temp."   type=text id=txtcoduni".$li_temp."   class=sin-borde size=14 maxlength=12 value='".$ls_unidad."'  readonly><input name='hidunidad".$li_temp."' type='hidden' id='hidunidad".$li_temp."' value='". $li_unidad ."'>";
					$lo_object[$li_temp][4]="<input name=txtcantidad".$li_temp." type=text id=txtcantidad".$li_temp." class=sin-borde size=14 maxlength=12 value='".$li_cantidad."' ><input name=txtexistencia".$li_temp." type='hidden' id=txtexistencia".$li_temp." class=sin-borde size=10 maxlength=12 value='".$li_existencia."' >";
					$lo_object[$li_temp][5]="<input name=txtcosuni".$li_temp."   type=text id=txtcosuni".$li_temp."   class=sin-borde size=14 maxlength=15 value='".$li_cosuni."' >";
					$lo_object[$li_temp][6]="<input name=txtcostot".$li_temp."   type=text id=txtcostot".$li_temp."   class=sin-borde size=14 maxlength=15 value='".$li_costot."'  readonly>";
					$lo_object[$li_temp][7]="";
					$lo_object[$li_temp][8]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
				}

			}

			if ($li_temp==0)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			else
			{
				uf_agregarlineablanca($lo_object,$li_temp+1);
			}


			break;

		case "BUSCARDETALLE":
			$ls_numtra=     $_POST["txtnumtra"];
			$ld_fecemi=     $_POST["txtfecemi"];
			$ls_codalmori=  $_POST["txtcodalm"];
			$ls_codalmdes=  $_POST["txtcodalmdes"];
			$ls_nomalmori=  $_POST["txtnomfisalm"];
			$ls_nomalmdes=  $_POST["txtnomfisdes"];
			$ls_obstra=     $_POST["txtobstra"];
			$ld_fecemiaux=$io_func->uf_convertirdatetobd($ld_fecemi);
			$ls_codtiend=substr($ls_codalmori,6,4);
			$lb_valido=$io_siv->uf_sim_obtener_dt_transferencia($ls_codemp,$ls_numtra,$ld_fecemiaux,$li_totrows,$lo_object,$ls_codtiend);
			break;

	}
?>
<p>&nbsp;</p>
<div align="center">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="675" height="286" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="575" height="19"><div align="left">
<input name="operacion" type="hidden" id="operacion">
                    <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
                    <input name="filadelete" type="hidden" id="filadelete">
                    <input name="catafilas" type="hidden" id="catafilas" value="<?php print $li_catafilas;?>">
      </div></td>
    </tr>
    <tr>
      <td height="251"><table width="556" height="152" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="17" colspan="4" class="titulo-ventana">Transferencia entre Almacenes </td>
        </tr>
        <tr class="formato-blanco">
          <td width="121" height="20"><div align="right"></div></td>
          <td width="306" height="22"><div align="left">
              <input name="txtnumtra" type="hidden" id="txtnumtra" value="<?php print $ls_numtra?>" size="15" maxlength="15" readonly>
              <input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_status?>">
              <input name="hidreadonly" type="hidden" id="hidreadonly">
              <input name="txtcodusu" type="hidden" id="txtcodusu" onKeyPress="javascript: ue_validarcomillas(this);" value="<?php print $ls_codusu?>" size="20" maxlength="60" readonly>
</div></td>
          <td width="33" align="right">Fecha</td>
          <td width="126"><input name="txtfecemi" type="text" id="txtfecemi" style="text-align:center " onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecemi?>" size="17" maxlength="10" datepicker="true"></td>
        </tr>
        <tr class="formato-blanco">
          <td height="20"><div align="right">Almac&eacute;n Origen </div></td>
          <td height="22" colspan="3"><div align="left">
              <input name="txtcodalm" type="text" id="txtcodalm" value="<?php print $ls_codalmori?>" size="15" style="text-align:center " readonly>
              <a href="javascript: ue_buscarorigen();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtnomfisalm" type="text" class="sin-borde" id="txtnomfisalm3" value="<?php print $ls_nomalmori?>" size="60" readonly>
          </div></td>
        </tr>
        <tr class="formato-blanco">
          <td height="20"><div align="right">Almac&eacute;n Destino </div></td>
          <td height="22" colspan="3"><div align="left">
              <input name="txtcodalmdes" type="text" id="txtcodalmdes" value="<?php print $ls_codalmdes?>" size="15" style="text-align:center " readonly>
              <a href="javascript: ue_buscardestino();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtnomfisdes" type="text" class="sin-borde" id="txtnomfisdes3" value="<?php print $ls_nomalmdes?>" size="60" readonly>
          </div></td>
        </tr>
        <tr class="formato-blanco">
          <td height="16"><div align="right">Observaciones</div></td>
          <td colspan="3" rowspan="2"><div align="left">
            <textarea name="txtobstra" cols="78" rows="3" id="txtobstra" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz�����., ()@#!%/[]*-+_');"><?php print $ls_obstra?></textarea>
          </div></td>
        </tr>
        <tr class="formato-blanco">
          <td height="20">&nbsp;</td>
        </tr>
        <tr class="formato-blanco">
          <td height="13">&nbsp;</td>
          <td colspan="3">
            <input name="txtdesalm" type="hidden" id="txtdesalm4">
             <input name="txtcodtiend" type="hidden" id="txtcodtiend">
            <input name="txttelalm" type="hidden" id="txttelalm4">
            <input name="txtubialm" type="hidden" id="txtubialm4">
            <input name="txtnomresalm" type="hidden" id="txtnomresalm3">
            <input name="txttelresalm" type="hidden" id="txttelresalm4">
            <input name="hidstatus" type="hidden" id="hidstatus4">
            <input name="txtdenunimed" type="hidden" id="txtdenunimed">
            <input name="txtunidad" type="hidden" id="txtunidad">
            <input name="txtobsunimed" type="hidden" id="txtobsunimed">

             </td>


        </tr>
        <tr class="formato-blanco">
          <td height="20" colspan="4"><p>
              <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
          </p>              </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
    </tr>
  </table>
          </form>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones


function ue_chequear_existencia()
{

	f=document.form1;
	li_filas=f.totalfilas.value;

for(li_i=1;li_i<=li_filas-1;li_i++)
	{
	   if(eval("f.txtcantidad"+li_i+".value")=="")
		  {
		   ls_cantidad=0;
		  }
	   else
		  {

			ls_codart=   eval("f.txtcodart"+li_i+".value");
			ls_unidad=   eval("f.txtcoduni"+li_i+".value");
			ls_cantidad= parseFloat(uf_convertir_monto(eval("f.txtcantidad"+li_i+".value")));
			li_existencia= eval("f.txtexistencia"+li_i+".value");
			ls_cosuni=   eval("f.txtcosuni"+li_i+".value");
			ls_denproveedor=   eval("f.txtdenproveedor"+li_i+".value");
			ls_codproveedor=   eval("f.txtcodproveedor"+li_i+".value");

			if(ls_cantidad>li_existencia )
				   {
				    // alert("MAYOR1");

				     eval("f.txtcantidad"+li_i+".value=uf_convertir(li_existencia)");
					 //eval("f.txttotpro"+li_i+".value='0,00'");
					 alert("La cantidad a Transferir del Producto: "+eval("f.txtdenart"+li_i+".value")+" es mayor que la existencia: "+uf_convertir(li_existencia)+" del almacen seleccionado");


				}
		  }
	}
}



function ue_cargarproducto(ls_codart,ls_denart,li_cosart,li_linea,li_unidad,ls_codtiend,ls_denproveedor,ls_codproveedor,li_existencia)
{
    f=document.form1;

	lb_existe=false;

	for(li_i=1;li_i<=f.totalfilas.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodart"+li_i+".value");
		ls_codprov=eval("f.txtcodproveedor"+li_i+".value");


		if((ls_codigo==ls_codart) && (ls_codprov==ls_codproveedor))
		{
			alert("La Cuenta ya ha sido cargada!!!");
			lb_existe=true;
		}
	}

	if(!lb_existe)
	{
		eval("f.txtcodart"+f.totalfilas.value+".value='"+ls_codart+"'");
		eval("f.txtdenart"+f.totalfilas.value+".value='"+ls_denart+"'");
		eval("f.txtcosuni"+f.totalfilas.value+".value='"+li_cosart+"'");
		eval("f.hidunidad"+f.totalfilas.value+".value='"+li_unidad+"'");
		eval("f.txtdenproveedor"+f.totalfilas.value+".value='"+ls_denproveedor+"'");
		eval("f.txtcodproveedor"+f.totalfilas.value+".value='"+ls_codproveedor+"'");
		eval("f.txtexistencia"+f.totalfilas.value+".value='"+li_existencia+"'");
	}

}

function ue_catarticulo(li_linea)
{
	f=document.form1;
	ls_codalm=f.txtcodalm.value;
    if(f.txtobstra.value=="")
    {
    	alert("Debe indicar la Observaci�n de la Transferencia a Realizar!")
    }
	else
	{
		window.open("sigesp_catdinamic_articulot.php?linea="+li_linea+"&almacen="+ls_codalm+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=580,height=400,left=50,top=50,location=no,resizable=yes");

	}
}


function ue_buscarorigen()
{

	f=document.form1;
	codalmdes=f.txtcodalmdes.value;
	li_incluir=f.incluir.value;
		if(li_incluir==1)
		{
			window.open("sigesp_catdinamic_almacen.php?codalmdes="+codalmdes,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}

}

function ue_buscardestino()
{
	f=document.form1;
	codalmori=f.txtcodalm.value;
	//alert(codalmori);
		li_incluir=f.incluir.value;
		if(li_incluir==1)
		{
			window.open("sigesp_catdinamic_almacend.php?codalmori="+codalmori,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");

		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}

}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_catdinamic_transferencia.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="NUEVO";
		f.action="sigesp_sim_p_transferencia.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}

}


function uf_agregar_dt(li_row)
{

	f=document.form1;
	ls_codnewart=  eval("f.txtcodart"+li_row+".value");
	ls_codnewcan=  eval("f.txtcantidad"+li_row+".value");
	ls_codnewuni=  eval("f.txtcosuni"+li_row+".value");
	ls_codnewuni=  eval("f.cmbunidad"+li_row+".value");
	ls_dennewproveedor=  eval("f.txtdenproveedor"+li_row+".value");
	ls_codnewproveedor=  eval("f.txtcodproveedor"+li_row+".value");
	ls_existencianew=  eval("f.txtexistencia"+li_row+".value");

	li_total=f.totalfilas.value;
	lb_valido=false;
	if (ls_codnewuni=="M")
	{
		ls_codnewuni="Mayor";
	}
	else
	{
		ls_codnewuni="Detal";
	}

	for(li_i=1;li_i<li_total&&lb_valido!=true;li_i++)
	{
		ls_codart=   eval("f.txtcodart"+li_i+".value");
		ls_unidad=   eval("f.txtcoduni"+li_i+".value");
		ls_cantidad= eval("f.txtcantidad"+li_i+".value");
		li_existencia= eval("f.txtexistencia"+li_i+".value");
		ls_cosuni=   eval("f.txtcosuni"+li_i+".value");
		ls_denproveedor=   eval("f.txtdenproveedor"+li_i+".value");
		ls_codproveedor=   eval("f.txtcodproveedor"+li_i+".value");

		if((ls_codart==ls_codnewart)&&(ls_unidad==ls_codnewuni)&&(ls_codproveedor==ls_codnewproveedor)&&(li_row!=li_i))
		{
			alert("El Artículo ya esta Cargado");
			lb_valido=true;
		}


	}

	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_codart=ue_validarvacio(ls_codart);
	ls_cantidad=eval("f.txtcantidad"+li_row+".value");
	ls_cantidad=ue_validarvacio(ls_cantidad);
	ls_cosuni=eval("f.txtcosuni"+li_row+".value");
	ls_cosuni=ue_validarvacio(ls_cosuni);



	if((ls_codart=="")||(ls_cantidad=="")||(ls_cosuni==""))
	{
		alert("Debe llenar todos los campos");
		lb_valido=true;
	}

	ls_fecemi=eval("f.txtfecemi.value");
	ls_fecemi=ue_validarvacio(ls_fecemi);
	ls_almori=eval("f.txtcodalm.value");
	ls_almori=ue_validarvacio(ls_almori);
	ls_almdes=eval("f.txtcodalmdes.value");
	ls_almdes=ue_validarvacio(ls_almdes);

	if((ls_fecemi=="")||(ls_almori=="")||(ls_almdes==""))
	{
		alert("Debe llenar los campos principales");

		lb_valido=true;
	}


	if(!lb_valido)
	{
		f.operacion.value="AGREGARDETALLE";
		f.action="sigesp_sim_p_transferencia.php";
		f.submit();
	}

}

function uf_delete_dt(li_row)
{
	f=document.form1;

	li_fila=f.totalfilas.value;
	if(li_fila!=li_row)
	{
		if(confirm("�Desea eliminar el Registro actual?"))
		{
			f.filadelete.value=li_row;

			f.operacion.value="ELIMINARDETALLE"
			f.action="sigesp_sim_p_transferencia.php";
			f.submit();
		}
	}
}


function ue_guardar()
{
	f=document.form1;
	li_fila=f.totalfilas.value;
	ls_fecemi=eval("f.txtfecemi.value");
	ls_fecemi=ue_validarvacio(ls_fecemi);
	ls_almori=eval("f.txtcodalm.value");
	ls_almori=ue_validarvacio(ls_almori);
	ls_almdes=eval("f.txtcodalmdes.value");
	ls_almdes=ue_validarvacio(ls_almdes);

	if(li_fila<=1)
	{
		alert("La transferencia debe tener al menos 1 art�culo");
	}
	else
	{
		if ((ls_fecemi=="")||(ls_almori=="")||(ls_almdes==""))
		{
			alert("Debe llenar los campos principales");
		}
		else
		{
			if (ls_almori==ls_almdes)
			{
				alert("El almac�n de origen y el de destino son el mismo");
			}
			else
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_sim_p_transferencia.php";
				f.submit();
			}
		}
	}
}

function ue_imprimir()
{
	f=document.form1;
	numtra= f.txtnumtra.value;
	if(numtra!="")
	{
		codalmori= f.txtcodalm.value;
		codalmdes= f.txtcodalmdes.value;
		nomfisori= f.txtnomfisalm.value;
		nomfisdes= f.txtnomfisdes.value;
		obstra=    f.txtobstra.value;
		fecemi=    f.txtfecemi.value;
		window.open("reportes/sigesp_sim_rfs_transferencia.php?numtra="+numtra+"&fecemi="+fecemi+"&codalmori="+codalmori+"&tienori="+nomfisori+"&tiendes="+nomfisdes+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
	}
	else
	{
	alert("Debe existir un documento a imprimir");
	}
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

function ue_montosfactura(li_row)
{
//--------------------------------------------------------
//	Funci�n que calcula el monto total por articulo
//	multiplicando la cantidad de articulos a despachar por el costo
//  unitario de cada uno de ellos.
//--------------------------------------------------------

	f=document.form1;
	lb_valido=true;

	ls_unidad=eval("f.cmbunidad"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);
	li_unidad=eval("f.hidunidad"+li_row+".value");
	li_unidad=ue_validarvacio(li_unidad);
	li_canart=eval("f.txtcantidad"+li_row+".value");
	li_canart=ue_validarvacio(li_canart);

	li_preuniart=eval("f.txtcosuni"+li_row+".value");

	li_preuniart=ue_validarvacio(li_preuniart);

	li_existencia= eval("f.txtexistencia"+li_row+".value");


if (li_canart!="")
{
	li_existencia=uf_convertir(li_existencia);

	if(li_canart>li_existencia)
	{
		eval("f.txtcantidad"+li_row+".value=uf_convertir(li_existencia)");
		alert("La cantidad a Transferir del Producto: "+eval("f.txtdenart"+li_row+".value")+" es mayor que la existencia: "+uf_convertir(li_existencia)+" del almacen seleccionado");
	}

}

	if((li_canart!="")&&(li_preuniart!=""))
	{
		li_canart=   ue_formato_operaciones(li_canart);
		li_preuniart=   ue_formato_operaciones(li_preuniart);
		if(ls_unidad=="M")
		{
			li_canart=parseFloat(li_canart)*parseFloat(li_unidad);
			li_canart=String(li_canart);
		}

		li_montot=parseFloat(li_canart)*parseFloat(li_preuniart);
		li_montot=uf_convertir(li_montot);
		obj=eval("f.txtcostot"+li_row+"");
		if (li_canart=='')
		li_montot=0;

		obj.value=li_montot;

	}
}

//--------------------------------------------------------
//	Funci�n que coloca los separadores (/) de las fechas
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}


function currencyFormat(fld, milSep, decSep, e)
 {
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true; // Enter
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del
    key = String.fromCharCode(whichCode); // Get key value from key code
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key
    len = fld.value.length;
    for(i = 0; i < len; i++)
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
    aux = '';
    for(; i < len; i++)
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) fld.value = '';
    if (len == 1) fld.value = '0'+ decSep + '0' + aux;
    if (len == 2) fld.value = '0'+ decSep + aux;
    if (len > 2)
	{
     aux2 = '';
     for (j = 0, i = len - 3; i >= 0; i--)
	 {
      if (j == 3)
	  {
       aux2 += milSep;
       j = 0;
      }
      aux2 += aux.charAt(i);
      j++;
     }

     fld.value = '';
     len2 = aux2.length;
     for (i = len2 - 1; i >= 0; i--)
      fld.value += aux2.charAt(i);

     fld.value += decSep + aux.substr(len - 2, len);
    }
	//ue_chequear_existencia();

	return false;
}



</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
