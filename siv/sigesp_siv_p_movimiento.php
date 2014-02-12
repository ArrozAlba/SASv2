<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // as_valor         //  nombre de la variable que desamos obtener
    // as_valordefecto  //  contenido de la variable
    // Description: Función que obtiene el valor de una variable si viene de un submit
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
	//  as_valores      // valores que puede tomar el combo
	//  as_seleccionado // item seleccionado
	//  aa_parametro    // arreglo de seleccionados
	//  li_total        // total de elementos en el combo
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
		$aa_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows." type=text id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catarticulo(".$ai_totrows.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<input name=txtcodalm".$ai_totrows." type=text id=txtcodalm".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catalmacen(".$ai_totrows.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Almacen' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][3]="<select name=cmbopeinv".$ai_totrows."><option value=API>Apertura</option><option value=ENT>Entrada Inv.</option><option value=SAL>Salida Inv.</option><option value=AJE>Ajuste Ent.</option><option value=AJS>Ajuste Sal.</option></select>";
		$aa_object[$ai_totrows][4]="<select name=cmbcodprodoc".$ai_totrows."><option value=ORD>Orden de Comp.</option><option value=FAC>Factura</option><option value=NOE>Nota de Ent.</option></select>";
		$aa_object[$ai_totrows][5]="<input name=txtnumdoc".$ai_totrows." type=text id=txtnumdoc".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumerosinpunto(this);'>";
		$aa_object[$ai_totrows][6]="<input name=txtcanart".$ai_totrows." type=text id=txtcanart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][7]="<input name=txtcosart".$ai_totrows." type=text id=txtcosart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][8]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][9]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";			
   }
   	//--------------------------------------------------------------

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_nummov,$ld_fecmov,$ls_codusu,$ls_nomsol,$ls_readonly;
		
		$ls_nummov="";
		$ld_fecmov=date("d/m/Y h:i");
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_nomsol="";
		$ls_readonly="true";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Movimientos de Inventario</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="css/siv.css" rel="stylesheet" type="text/css">
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
.Estilo1 {font-size: 12px}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Inventario </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("sigesp_siv_c_movimiento.php");
	require_once("sigesp_siv_c_articuloxalmacen.php");
	$in_grid=new grid_param();
	$in=     new sigesp_include();
	$con=    $in->uf_conectar();
	$io_msg= new class_mensajes();
	$io_fun= new class_funciones_db($con);
	$io_func= new class_funciones();
	$io_sql= new class_sql($con);
	$io_siv= new sigesp_siv_c_movimiento();
	$io_art= new sigesp_siv_c_articuloxalmacen();

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SIV";
	$ls_ventanas="sigesp_siv_p_movimiento.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_count=count($arr);

	$li_totrows = uf_obtenervalor("totalfilas",1);

	$ls_titletable="Detalle del Movimiento";
	$li_widthtable=780;
	$ls_nametable="grid";
	$lo_title[1]="Artículo";
	$lo_title[2]="Almacen";
	$lo_title[3]="Operación";
	$lo_title[4]="Procedencia";
	$lo_title[5]="Documento";
	$lo_title[6]="Cantidad";
	$lo_title[7]="Costo";
	$lo_title[8]="";
	$lo_title[9]="";
	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_status=$_POST["hidestatus"];
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
		$ls_status="";
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
			$ls_nummov=$_POST["txtnummov"];
			$ld_fecmov=$_POST["txtfecmov"];
			$ls_nomsol=$_POST["txtnomsol"];
			$li_codusu=$_POST["txtcodusu"];
			$ld_fecmov=$io_func->uf_convertirdatetobd($ld_fecmov);
			if ($ls_status=="C")
			{
 				$lb_valido=$io_siv->uf_siv_update_movimiento($ls_nummov,$ld_fecmov,$ls_nomsol,$ls_codusu,$la_seguridad);
	
				if ($lb_valido)
				{
					for($li_i=1;$li_i<$li_totrows;$li_i++)
					{
						$ls_codart=   $_POST["txtcodart".$li_i];
						$ls_codalm=   $_POST["txtcodalm".$li_i];
						$ls_numdoc=   $_POST["txtnumdoc".$li_i];
						$li_canart=   $_POST["txtcanart".$li_i];
						$li_cosart=   $_POST["txtcosart".$li_i];
						$ls_opeinv=   $_POST["cmbopeinv".$li_i];
						$ls_codprodoc=$_POST["cmbcodprodoc".$li_i];
						
						$lb_existe=$io_siv->uf_siv_select_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecmov,$ls_codart,$ls_codalm,
																		$ls_opeinv,$ls_codprodoc,$ls_numdoc);
						if($lb_existe)
						{
							$lb_valido1=$io_siv->uf_siv_update_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecmov,$ls_codart,$ls_codalm,
																		 	 $ls_opeinv,$ls_codprodoc,$ls_numdoc,$li_canart,$li_cosart,
																		     $la_seguridad);
						}
						else
						{
							$lb_valido1=$io_siv->uf_siv_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecmov,$ls_codart,$ls_codalm,
																			 $ls_opeinv,$ls_codprodoc,$ls_numdoc,$li_canart,$li_cosart,
																		     $la_seguridad);

						}						
					}
					if($lb_valido1)
					{
						uf_agregarlineablanca($lo_object,1);
						uf_limpiarvariables();
						$li_totrows=1;
						$ls_status="";
						$ls_operacion="";
						$io_msg->message("El registro fue actualizado con exito");
					}
					else
					{
						$io_msg->message("El registro no pudo ser actualizado");
					}
				}
				else
				{
					$io_msg->message("El registro no pudo ser actualizado");
				}
			}
			else
			{
				$lb_encontrado=$io_siv->uf_siv_select_movimiento($ls_nummov,$ld_fecmov);
				if ($lb_encontrado)
				{
					$io_msg->message("Registro ya existe"); 
				}
				else
				{
					$ls_nummov="";					
					$lb_valido=$io_siv->uf_siv_insert_movimiento(&$ls_nummov,$ld_fecmov,$ls_nomsol,$ls_codusu,$la_seguridad);

					if ($lb_valido)
					{
						for($li_i=1;$li_i<$li_totrows;$li_i++)
						{
							$ls_codart=   $_POST["txtcodart".$li_i];
							$ls_codalm=   $_POST["txtcodalm".$li_i];
							$ls_numdoc=   $_POST["txtnumdoc".$li_i];
							$li_canart=   $_POST["txtcanart".$li_i];
							$li_cosart=   $_POST["txtcosart".$li_i];
							$ls_opeinv=   $_POST["cmbopeinv".$li_i];
							$ls_codprodoc=$_POST["cmbcodprodoc".$li_i];
							$ls_numdoc=$io_func->uf_cerosizquierda($ls_numdoc,15);

							$lb_valido=$io_siv->uf_siv_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecmov,$ls_codart,$ls_codalm,
																			 $ls_opeinv,$ls_codprodoc,$ls_numdoc,$li_canart,$li_cosart,
																		     $la_seguridad);
							if($lb_valido)
							{
								switch ($ls_opeinv) 
								{
									case "API":
										$lb_valido=$io_art->uf_siv_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																							 $li_canart,$la_seguridad);
										break;
									case "ENT":
										$lb_valido=$io_art->uf_siv_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																							 $li_canart,$la_seguridad);
										break;
									case "SAL":
										$lb_valido=$io_art->uf_siv_disminuir_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																							  $li_canart,$la_seguridad);
										break;
									case "AJE":
										$lb_valido=$io_art->uf_siv_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																							 $li_canart,$la_seguridad);
										break;
									case "AJS":
										$lb_valido=$io_art->uf_siv_disminuir_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																							  $li_canart,$la_seguridad);
										break;
								}
								if($lb_valido)
								{
									$lb_valido=$io_art->uf_siv_actualizar_cantidad_articulos($ls_codemp,$ls_codart,$la_seguridad);
								}

							}

						}
						
					}
					if($lb_valido)
					{
						$io_sql->commit();
						$io_msg->message("El Numero de Movimiento correspondiente es: ".$ls_nummov);
						$io_msg->message("El registro fue incluido con exito");
						uf_agregarlineablanca($lo_object,1);
						uf_limpiarvariables();
						$li_totrows=1;
					}
					else
					{
						$io_sql->rollback();
						$io_msg->message("No se pudo incluir el registro");
					}
				}
			}
			break;

		case "AGREGARDETALLE":
			$li_totrows=$li_totrows+1;
			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{	
				$la_codprodoc[0]="";
				$la_codprodoc[1]="";
				$la_codprodoc[2]="";
				$la_opeinv[0]="";
				$la_opeinv[1]="";
				$la_opeinv[2]="";
				$la_opeinv[3]="";
				$la_opeinv[4]="";
				$ls_nummov=   $_POST["txtnummov"];
				$ld_fecmov=   $_POST["txtfecmov"];
				$ls_nomsol=   $_POST["txtnomsol"];
				$li_codusu=   $_POST["txtcodusu"];
				$ls_codart=   $_POST["txtcodart".$li_i];
				$ls_codalm=   $_POST["txtcodalm".$li_i];
				$ls_numdoc=   $_POST["txtnumdoc".$li_i];
				$li_canart=   $_POST["txtcanart".$li_i];
				$li_cosart=   $_POST["txtcosart".$li_i];
				$ls_opeinv=   $_POST["cmbopeinv".$li_i];
				$ls_codprodoc=$_POST["cmbcodprodoc".$li_i];
				uf_seleccionarcombo("ORD-FAC-NOE",$ls_codprodoc,$la_codprodoc,3);
				uf_seleccionarcombo("API-ENT-SAL-AJE-AJS",$ls_opeinv,$la_opeinv,5);
				
				if (($ls_status=="C")&&($li_i<=$li_catafilas))
				{
					
					switch ($ls_opeinv) 
					{
						case "API":
							$ls_opeinvaux="Apertura";
							break;
						case "ENT":
							$ls_opeinvaux="Entrada Inv.";
							break;
						case "SAL":
							$ls_opeinvaux="Salida Inv.";
							break;
						case "AJE":
							$ls_opeinvaux="Ajuste Ent.";
							break;
						case "AJS":
							$ls_opeinvaux="Ajuste Sal.";
							break;
					}
					switch ($ls_codprodoc) 
					{
						case "ORD":
							$ls_codprodocaux="Orden de Comp.";
							break;
						case "FAC":
							$ls_codprodocaux="Factura";
							break;
						case "NOE":
							$ls_codprodocaux="Nota de Ent.";
							break;
					}
					$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
					$lo_object[$li_i][2]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_codalm."' readonly>";
					$lo_object[$li_i][3]="<input name=txtopeinv".$li_i." type=text id=txtopeinv".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_opeinvaux."' readonly><input name='cmbopeinv".$li_i."' type='hidden' id='cmbopeinv".$li_i."'value='".$ls_opeinv."'>";
					$lo_object[$li_i][4]="<input name=txtcodprodoc".$li_i." type=text id=txtcodprodoc".$li_i." class=sin-borde size=15 maxlength=15 value='".$ls_codprodocaux."' readonly><input name='cmbcodprodoc".$li_i."' type='hidden' id='cmbcodprodoc".$li_i."'value='".$ls_codprodoc."'>";
					$lo_object[$li_i][5]="<input name=txtnumdoc".$li_i." type=text id=txtnumdoc".$li_i." class=sin-borde size=14 maxlength=15 value='".$ls_numdoc."' readonly>";
					$lo_object[$li_i][6]="<input name=txtcanart".$li_i." type=text id=txtcanart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][7]="<input name=txtcosart".$li_i." type=text id=txtcosart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_cosart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][8]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_i][9]="";			
				}
				else
				{
					$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catarticulo(".$li_i.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_i][2]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_codalm."' onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catalmacen(".$li_i.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Almacen' width='18' height='18' border='0'></a>";
					$lo_object[$li_i][3]="<select name=cmbopeinv".$li_i."><option value=API ".$la_opeinv[0].">Apertura</option><option value=ENT ".$la_opeinv[1].">Entrada Inv.</option><option value=SAL ".$la_opeinv[2].">Salida Inv.</option><option value=AJE ".$la_opeinv[3].">Ajuste Ent.</option><option value=AJS ".$la_opeinv[4].">Ajuste Sal.</option></select>";
					$lo_object[$li_i][4]="<select name=cmbcodprodoc".$li_i."><option value=ORD  ".$la_codprodoc[0].">Orden de Comp.</option><option value=FAC ".$la_opeinv[1].">Factura</option><option value=NOE ".$la_codprodoc[2].">Nota de Ent.</option></select>";
					$lo_object[$li_i][5]="<input name=txtnumdoc".$li_i." type=text id=txtnumdoc".$li_i." class=sin-borde size=14 maxlength=15 value='".$ls_numdoc."' onKeyUp='javascript: ue_validarcomillas(this);'>";
					$lo_object[$li_i][6]="<input name=txtcanart".$li_i." type=text id=txtcanart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][7]="<input name=txtcosart".$li_i." type=text id=txtcosart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_cosart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][8]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";			
				}

			}
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_codart);
			break;

		case "ELIMINARDETALLE":
			$la_codprodoc[0]="";
			$la_codprodoc[1]="";
			$la_codprodoc[2]="";
			$la_opeinv[0]="";
			$la_opeinv[1]="";
			$la_opeinv[2]="";
			$la_opeinv[3]="";
			$la_opeinv[4]="";
			$ls_nummov=   $_POST["txtnummov"];
			$ld_fecmov=   $_POST["txtfecmov"];
			$ls_nomsol=   $_POST["txtnomsol"];
			$li_codusu=   $_POST["txtcodusu"];
			
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codart=   $_POST["txtcodart".$li_i];
					$ls_codalm=   $_POST["txtcodalm".$li_i];
					$ls_numdoc=   $_POST["txtnumdoc".$li_i];
					$li_canart=   $_POST["txtcanart".$li_i];
					$li_cosart=   $_POST["txtcosart".$li_i];
					$ls_opeinv=   $_POST["cmbopeinv".$li_i];
					$ls_codprodoc=$_POST["cmbcodprodoc".$li_i];
					uf_seleccionarcombo("ORD-FAC-NOE",$ls_codprodoc,$la_codprodoc,3);
					uf_seleccionarcombo("API-ENT-SAL-AJE-AJS",$ls_opeinv,$la_opeinv,5);
	
					$lo_object[$li_i][1]="<input name=txtcodart".$li_temp." type=text id=txtcodart".$li_temp." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catarticulo(".$li_temp.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_i][2]="<input name=txtcodalm".$li_temp." type=text id=txtcodalm".$li_temp." class=sin-borde size=12 maxlength=12 value='".$ls_codalm."' onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catalmacen(".$li_temp.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Almacen' width='18' height='18' border='0'></a>";
					$lo_object[$li_i][3]="<select name=cmbopeinv".$li_temp."><option value=API ".$la_opeinv[0].">Apertura</option><option value=ENT ".$la_opeinv[1].">Entrada Inv.</option><option value=SAL ".$la_opeinv[2].">Salida Inv.</option><option value=AJE ".$la_opeinv[3].">Ajuste Ent.</option><option value=AJS ".$la_opeinv[4].">Ajuste Sal.</option></select>";
					$lo_object[$li_i][4]="<select name=cmbcodprodoc".$li_temp."><option value=ORD  ".$la_codprodoc[0].">Orden de Comp.</option><option value=FAC ".$la_opeinv[1].">Factura</option><option value=NOE ".$la_codprodoc[2].">Nota de Ent.</option></select>";
					$lo_object[$li_i][5]="<input name=txtnumdoc".$li_temp." type=text id=txtnumdoc".$li_temp." class=sin-borde size=14 maxlength=15 value='".$ls_numdoc."' onKeyUp='javascript: ue_validarcomillas(this);'>";
					$lo_object[$li_i][6]="<input name=txtcanart".$li_temp." type=text id=txtcanart".$li_temp." class=sin-borde size=12 maxlength=12 value='".$li_cosart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][7]="<input name=txtcosart".$li_temp." type=text id=txtcosart".$li_temp." class=sin-borde size=12 maxlength=12 value='".$li_cosart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][8]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";			

				}
				else
				{
					$li_rowdelete= 0;
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
		case "BUSCARDETALLE":
			$ls_nummov=$_POST["txtnummov"];
			$ld_fecmov=$_POST["txtfecmov"];
			$ls_nomsol=$_POST["txtnomsol"];
			$li_codusu=$_POST["txtcodusu"];
			$data="";
			$li_totrows=0;
			$ld_fecmov1=$io_func->uf_convertirdatetobd($ld_fecmov);
			$lb_valido=$io_siv->uf_siv_obtener_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecmov1,&$data,&$li_totrows);
			if ($lb_valido)
			{
				$li_catafilas=$li_totrows;
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					$la_codprodoc[0]="";
					$la_codprodoc[1]="";
					$la_codprodoc[2]="";
					$la_opeinv[0]="";
					$la_opeinv[1]="";
					$la_opeinv[2]="";
					$la_opeinv[3]="";
					$la_opeinv[4]="";
					$ls_codart=   $data["codart"][$li_i];
					$ls_codalm=   $data["codalm"][$li_i];
					$ls_numdoc=   $data["numdoc"][$li_i];
					$li_canart=   $data["canart"][$li_i];
					$li_cosart=   $data["cosart"][$li_i];
					$ls_opeinv=   $data["opeinv"][$li_i];
					$ls_codprodoc=$data["codprodoc"][$li_i];
					switch ($ls_opeinv) 
					{
						case "API":
							$ls_opeinvaux="Apertura";
							break;
						case "ENT":
							$ls_opeinvaux="Entrada Inv.";
							break;
						case "SAL":
							$ls_opeinvaux="Salida Inv.";
							break;
						case "AJE":
							$ls_opeinvaux="Ajuste Ent.";
							break;
						case "AJS":
							$ls_opeinvaux="Ajuste Sal.";
							break;
					}
					switch ($ls_codprodoc) 
					{
						case "ORD":
							$ls_codprodocaux="Orden de Comp.";
							break;
						case "FAC":
							$ls_codprodocaux="Factura";
							break;
						case "NOE":
							$ls_codprodocaux="Nota de Ent.";
							break;
						case "ALM":
							$ls_codprodocaux="Almacén";
							break;
					}
//					uf_seleccionarcombo("ORD-FAC-NOE",$ls_codprodoc,$la_codprodoc,3);
//					uf_seleccionarcombo("API-ENT-SAL-AJE-AJS",$ls_opeinv,$la_opeinv,5);
//<a href='javascript: ue_catarticulo(".$li_i.");'><img src='imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>	
//<a href='javascript: ue_catalmacen(".$li_i.");'><img src='imagebank/tools20/buscar.gif' alt='Codigo de Almacen' width='18' height='18' border='0'></a>
//<select name=cmbopeinv".$li_i." disabled><option value=API ".$la_opeinv[0].">Apertura</option><option value=ENT ".$la_opeinv[1].">Entrada Inv.</option><option value=SAL ".$la_opeinv[2].">Salida Inv.</option><option value=AJE ".$la_opeinv[3].">Ajuste Ent.</option><option value=AJS ".$la_opeinv[4].">Ajuste Sal.</option></select>
//<select name=cmbcodprodoc".$li_i." disabled><option value=ORD  ".$la_codprodoc[0].">Orden de Comp.</option><option value=FAC ".$la_opeinv[1].">Factura</option><option value=NOE ".$la_codprodoc[2].">Nota de Ent.</option></select>

					$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
					$lo_object[$li_i][2]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_codalm."' readonly>";
					$lo_object[$li_i][3]="<input name=txtopeinv".$li_i." type=text id=txtopeinv".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_opeinvaux."' readonly><input name='cmbopeinv".$li_i."' type='hidden' id='cmbopeinv".$li_i."'value='".$ls_opeinv."'>";
					$lo_object[$li_i][4]="<input name=txtcodprodoc".$li_i." type=text id=txtcodprodoc".$li_i." class=sin-borde size=15 maxlength=15 value='".$ls_codprodocaux."' readonly><input name='cmbcodprodoc".$li_i."' type='hidden' id='cmbcodprodoc".$li_i."'value='".$ls_codprodoc."'>";
					$lo_object[$li_i][5]="<input name=txtnumdoc".$li_i." type=text id=txtnumdoc".$li_i." class=sin-borde size=14 maxlength=15 value='".$ls_numdoc."' readonly>";
					$lo_object[$li_i][6]="<input name=txtcanart".$li_i." type=text id=txtcanart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][7]="<input name=txtcosart".$li_i." type=text id=txtcosart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_cosart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][8]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_i][9]="";			
				}
				$li_totrows=$li_totrows+1;
				$lo_object[$li_totrows][1]="<input name=txtcodart".$li_totrows." type=text id=txtcodart".$li_totrows." class=sin-borde size=20 maxlength=20 onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catarticulo(".$li_totrows.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
				$lo_object[$li_totrows][2]="<input name=txtcodalm".$li_totrows." type=text id=txtcodalm".$li_totrows." class=sin-borde size=12 maxlength=12  onKeyUp='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_catalmacen(".$li_totrows.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Almacen' width='18' height='18' border='0'></a>";
				$lo_object[$li_totrows][3]="<select name=cmbopeinv".$li_totrows."><option value=API>Apertura</option><option value=ENT>Entrada Inv.</option><option value=SAL>Salida Inv.</option><option value=AJE>Ajuste Ent.</option><option value=AJS>Ajuste Sal.</option></select>";
				$lo_object[$li_totrows][4]="<select name=cmbcodprodoc".$li_totrows."><option value=ORD>Orden de Comp.</option><option value=FAC>Factura</option><option value=NOE>Nota de Ent.</option></select>";
				$lo_object[$li_totrows][5]="<input name=txtnumdoc".$li_totrows." type=text id=txtnumdoc".$li_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);'>";
				$lo_object[$li_totrows][6]="<input name=txtcanart".$li_totrows." type=text id=txtcanart".$li_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_totrows][7]="<input name=txtcosart".$li_totrows." type=text id=txtcosart".$li_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_totrows][8]="<a href=javascript:uf_agregar_dt(".$li_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
				$lo_object[$li_totrows][9]="<a href=javascript:uf_delete_dt(".$li_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";			
			}
			else
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}

			break;
			
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="767" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="669" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="755" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="620">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="744" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="4" class="titulo-ventana">Movimientos de Inventario </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="154" height="19">&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">C&oacute;digo</div></td>
                    <td width="434"><input name="txtnummov" type="text" id="txtnummov" value="<? print $ls_nummov?>" size="15" maxlength="15" readonly>
                        <input name="hidestatus" type="hidden" id="hidestatus" value="<? print $ls_status?>">
                        <input name="hidreadonly" type="hidden" id="hidreadonly">                        </td>
                    <td width="34">Fecha</td>
                    <td width="120"><input name="txtfecmov" type="text" id="txtfecmov" onKeyPress="ue_separadores(this,'/',patron,true);" value="<? print $ld_fecmov?>" size="12" maxlength="10" datepicker="true" ></td>
                  </tr>
                  <tr>
                    <td height="20"><div align="right">Nombre del Solicitante </div></td>
                    <td colspan="3"><input name="txtnomsol" type="text" id="txtnomsol" onKeyPress="javascript: ue_validarcomillas(this);" value="<? print $ls_nomsol?>" size="20" maxlength="60">
                    </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Usuario</div></td>
                    <td colspan="3"><input name="txtcodusu" type="text" id="txtcodusu" onKeyPress="javascript: ue_validarcomillas(this);" value="<? print $ls_codusu?>" size="20" maxlength="60" readonly></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="13">&nbsp;</td>
                    <td colspan="3"><input name="txtnomfisalm" type="hidden" id="txtnomfisalm">
                      <input name="txtdesalm" type="hidden" id="txtdesalm">
                      <input name="txttelalm" type="hidden" id="txttelalm">
                      <input name="txtubialm" type="hidden" id="txtubialm">
                      <input name="txtnomresalm" type="hidden" id="txtnomresalm">
                      <input name="txttelresalm" type="hidden" id="txttelresalm">
                      <input name="hidstatus" type="hidden" id="hidstatus"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="28" colspan="4"><p>
                      <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
                      </p>
                      <p>&nbsp;                      </p></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
              <input name="operacion" type="hidden" id="operacion">
                    <input name="totalfilas" type="hidden" id="totalfilas" value="<? print $li_totrows;?>">
                    <input name="filadelete" type="hidden" id="filadelete">
                    <input name="catafilas" type="hidden" id="catafilas" value="<? print $li_catafilas;?>">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones sobre el comprobante
function ue_catarticulo(li_linea)
{
	window.open("sigesp_catdinamic_articulom.php?linea="+li_linea+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_catalmacen(li_linea)
{
	window.open("sigesp_catdinamic_almacen.php?linea="+li_linea+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_movimiento.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_siv_p_movimiento.php";
	f.submit();
}
function uf_agregar_dt(li_row)
{


	f=document.form1;
	ls_codnewart=eval("f.txtcodart"+li_row+".value");
	ls_codnewalm=eval("f.txtcodalm"+li_row+".value");
	ls_codnewdoc=eval("f.txtnumdoc"+li_row+".value");
	ls_codnewope=eval("f.cmbopeinv"+li_row+".value");
	ls_codnewpro=eval("f.cmbcodprodoc"+li_row+".value");
	li_total=f.totalfilas.value;
	lb_valido=false;
	
	for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
	{
		ls_codidart=eval("f.txtcodart"+li_i+".value");
		ls_codidalm=eval("f.txtcodalm"+li_i+".value");
		ls_codiddoc=eval("f.txtnumdoc"+li_i+".value");
		ls_codopeinv=eval("f.cmbopeinv"+li_i+".value");
		ls_codprodoc=eval("f.cmbcodprodoc"+li_i+".value");
		if((ls_codidart==ls_codnewart)&&(ls_codidalm==ls_codnewalm)&&(ls_codiddoc==ls_codnewdoc)&&(ls_codopeinv==ls_codnewope)&&(ls_codprodoc==ls_codprodoc)&&(li_i!=li_row))
		{
			alert("El movimiento ya esta registrado");
			lb_valido=true;
		}
	}
	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_codart=ue_validarvacio(ls_codart);
	ls_codalm=eval("f.txtcodalm"+li_row+".value");
	ls_codalm=ue_validarvacio(ls_codalm);
	ls_docpro=eval("f.txtnumdoc"+li_row+".value");
	ls_docpro=ue_validarvacio(ls_docpro);
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_cosart=eval("f.txtcosart"+li_row+".value");
	ls_cosart=ue_validarvacio(ls_cosart);

	if((ls_codart=="")||(ls_codalm=="")||(ls_docpro=="")||(ls_canart=="")||(ls_cosart==""))
	{
		alert("Debe llenar todos los campos");
		lb_valido=true;
	}

	ls_fecmov=eval("f.txtfecmov.value");
	ls_fecmov=ue_validarvacio(ls_fecmov);
	ls_nomsol=eval("f.txtnomsol.value");
	ls_nomsol=ue_validarvacio(ls_nomsol);
	ls_codusu=eval("f.txtcodusu.value");
	ls_codusu=ue_validarvacio(ls_codusu);
	
	if((ls_fecmov=="")||(ls_nomsol=="")||(ls_codusu==""))
	{
		alert("Debe llenar los campos principales");
		lb_valido=true;
	}


	
	if(!lb_valido)
	{
		f.operacion.value="AGREGARDETALLE";
		f.action="sigesp_siv_p_movimiento.php";
		f.submit();
	}
}

function uf_delete_dt(li_row)
{
/*	f=document.form1;
	li_lappervac=eval("f.txtlappervac"+li_row+".value");
	li_lappervac=ue_validarvacio(li_lappervac);
	if(li_lappervac=="")
	{
		alert("la fila a eliminar no debe estar vacio el lapso");
	}
	else
	{*/
		if(confirm("¿Desea eliminar el Registro actual?"))
		{	f=document.form1;
			f.filadelete.value=li_row;
			f.operacion.value="ELIMINARDETALLE"
			f.action="sigesp_siv_p_movimiento.php";
			alert(f.filadelete.value);
			f.submit();
		}
//	}
}

function ue_guardar()
{
	f=document.form1;
	ls_fecmov=eval("f.txtfecmov.value");
	ls_fecmov=ue_validarvacio(ls_fecmov);
	ls_nomsol=eval("f.txtnomsol.value");
	ls_nomsol=ue_validarvacio(ls_nomsol);
	ls_codusu=eval("f.txtcodusu.value");
	ls_codusu=ue_validarvacio(ls_codusu);
	
	if ((ls_fecmov=="")||(ls_nomsol=="")||(ls_codusu==""))
	{
		alert("Debe llenar los campos principales");
	}
	else
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_siv_p_movimiento.php";
		f.submit();
	}
}

function ue_eliminar()
{
	if(confirm("¿Seguro desea eliminar el Usuario?"))
	{
		f=document.form1;
		f.operacion.value="ELIMINAR";
		f.action="sigesp_siv_p_tipoarticulo.php";
		f.submit();
	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que valida que no se incluyan comillas simples 
//	en los textos ya que dañana la consulta SQL
//--------------------------------------------------------
function ue_validarcomillas(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if(texto != "'")
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9")||(texto=="."))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumerosinpunto(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

//--------------------------------------------------------
//	Función que valida que el texto no esté vacio
//--------------------------------------------------------
function ue_validarvacio(valor)
{
	var texto;
	while(''+valor.charAt(0)==' ')
	{
		valor=valor.substring(1,valor.length)
	}
	texto = valor;
	return texto;
}

</script> 
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>