<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
/******************************************/
session_start();

if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }
  // $_SESSION["ls_codtienda"]='0001';
$la_datemp= $_SESSION["la_empresa"];
$ls_codtie= $_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Listado de Cartas Ordenes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.style6 {color: #000000}
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
	<tr>
		<td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
	</tr>
	<tr>
    <td width="486" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="292" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
	<tr>
		<td height="20" colspan="2" class="cd-menu">
			<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>		</td>
	</tr>
	<tr>
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
	</tr>
	<tr>
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?Php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_rep_cartaorden.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST))
	{

			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];

	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codcli=$_POST["txtcodcli"];
	$ls_nomcli=$_POST["txtrazcli"];
	$ls_denpro=$_POST["txtdenpro"];
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_fecemi2=$_POST["txtfecemi2"];
	$ls_dencla=$_POST["txtdencla"];//codigo del banco
	$ls_codban=$_POST["txtcodban"];
	$ls_opcion=$_POST["opcion"];
	$ls_orden=$_POST["combo_orden"];
	$ls_ordenarpor=$_POST["combo_ordenarpor"];

	$ls_tienda_desde = $_POST["txtcodtienda_desde"];
	$ls_tienda_hasta = $_POST["txtcodtienda_hasta"];

	$ls_fecemi=$io_funcion->uf_convertirdatetobd($ls_fecemi);
	$ls_fecemi2=$io_funcion->uf_convertirdatetobd($ls_fecemi2);
	if ($ls_fecemi=="")
	{
		$ls_fecemi="%/".$ls_fecemi."%";
		}

	if ($ls_fecemi2=="")
	{
		$ls_fecemi2="%/".$ls_fecemi2."%";
		}
	}
else
{
	$ls_operacion="";
	$ls_codcli="";
	$ls_nomcli="";
	$ls_denpro="";
	$ls_fecemi="%%";
	$ls_fecemi2="%%";
	$ls_orden="";
	$ls_ordenarpor="Null";
	$ls_dencla="";
	$ls_codban="";
	$ls_opcion="detalles";
	}
if($ls_operacion=="ue_actualizar_option")
	{
		  if ( $ls_opcion=="detalles"){
		  $ls_opcion="detalles";
		  $ls_ordenarpor="Null";
		  }else{
		  $ls_opcion="resumen";
		  $ls_ordenarpor="Null";
		  }


	}
?>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{

	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="548" height="270" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
		<tr>
			<td width="506" height="258"><div align="center">
				<table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
					<tr>
						<td colspan="4" class="titulo-ventana">Listado de Cartas Ordenes (Filtrar) </td>
					</tr>
					<tr>
						<td colspan="4" class="sin-borde">&nbsp;</td>
					</tr>
					<tr>
						<td width="112" ><div align="right">
							<input name="txtcodcli" type="hidden" id="txtcodcli">
							<input name="txtcodban" type="hidden" id="txtcodban" >
							<input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $ls_codemp?>">
							<input name="operacion1" type="hidden" id="operacion1" value="<? print $ls_opcion?>">
							<input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion?>">
							Ordenar por
							</div>						</td>
						<?php
						 if ($ls_opcion=="detalles")
						 {?>
						<td width="134" ><p align="left">
						 <select name="combo_ordenarpor" size="1" >
                            <?php
							  if ($ls_ordenarpor=="Null")
							   {
							   ?>
                            <option value="c.cedcli" selected>Seleccione...</option>
                            <option value="c.cedcli">C&eacute;dula &oacute; rif</option>
                            <option value="n.fecnot">Fecha</option>
                            <option value="ip.codban">Banco</option>
							<option value="n.nro_documento">N&uacute;mero factura</option>
                            <?php
							   }
							  elseif ($ls_ordenarpor=="c.cedcli")
							   {
								?>
                            <option value="c.cedcli" >Seleccione...</option>
                            <option value="c.cedcli">C&eacute;dula &oacute; rif</option>
                            <option value="n.fecnot">Fecha</option>
                            <option value="ip.codban">Banco</option>
							<option value="n.nro_documento">N&uacute;mero factura</option>
                            <?php
							   }
							   elseif ($ls_ordenarpor=="n.fecnot")
							   {
								?>
                            <option value="c.cedcli" >Seleccione...</option>
                            <option value="c.cedcli">C&eacute;dula &oacute; rif</option>
                            <option value="n.fecnot">Fecha</option>
                            <option value="ip.codban">Banco</option>
							<option value="n.nro_documento">N&uacute;mero factura</option>
                            <?php
								}
							   elseif ($ls_ordenarpor=="ip.codban")
							   {
								?>
                            <option value="c.cedcli" >Seleccione...</option>
                            <option value="c.cedcli">C&eacute;dula &oacute; rif</option>
                            <option value="n.fecnot">Fecha</option>
                            <option value="ip.codban">Banco</option>
							<option value="n.nro_documento">N&uacute;mero factura</option>
                            <?php
							   }

							elseif ($ls_ordenarpor=="n.nro_documento")
						   {
						  ?>
							 <option value="c.cedcli" >Seleccione...</option>
                            <option value="c.cedcli">C&eacute;dula &oacute; rif</option>
                            <option value="n.fecnot">Fecha</option>
                            <option value="ip.codban">Banco</option>
							<option value="n.nro_documento" selected>N&uacute;mero factura</option>

							<?php
						   }?>
                          </select>
						</p>						</td>
						<td width="258" colspan="2" >
							Orden
							<select name="combo_orden" size="1">
							<?php
							  if ($ls_orden=="ASC")
							   {
							   ?>
			                  <option value="ASC" selected>ASC</option>
			                  <option value="DESC">DESC</option>
							  <?php
							   }
							  else
							   {
							   ?>
			                  <option value="ASC" >ASC</option>
			                  <option value="DESC" selected>DESC</option>
							  <?php
							  }
							  ?>
			                </select></td>
						<?php

						}//if detalles
						else
						{
						?>
						<td width="134" ><p align="left">
							<select name="combo_ordenarpor" size="1" >

							  <?php

							  if ($ls_ordenarpor=="Null")
							   {
							   ?>
								<option value="c.cedcli" selected>Seleccione...</option>
								<option value="c.cedcli">C&eacute;dula &oacute; rif</option>
								<option value="n.fecnot">Fecha</option>
								<option value="ip.codban">Banco</option>
								<option value="n.nro_documento">N&uacute;mero factura</option>

							  <?php
							   }
							  elseif ($ls_ordenarpor=="c.cedcli")
							   {
								?>
								<option value="c.cedcli" >Seleccione...</option>
								<option value="c.cedcli">C&eacute;dula &oacute; rif</option>
								<option value="n.fecnot">Fecha</option>
								<option value="ip.codban">Banco</option>
								<option value="n.nro_documento">N&uacute;mero factura</option>

							  <?php
							   }
							   elseif ($ls_ordenarpor=="n.fecnot")
							   {
								?>
								<option value="c.cedcli" >Seleccione...</option>
								<option value="c.cedcli">C&eacute;dula &oacute; rif</option>
								<option value="n.fecnot">Fecha</option>
								<option value="ip.codban">Banco</option>
								<option value="n.nro_documento">N&uacute;mero factura</option>

							  <?php
								}
							   elseif ($ls_ordenarpor=="ip.codban")
							   {
								?>
								<option value="c.cedcli" >Seleccione...</option>
								<option value="c.cedcli">C&eacute;dula &oacute; rif</option>
								<option value="n.fecnot">Fecha</option>
								<option value="ip.codban">Banco</option>
								<option value="n.nro_documento">N&uacute;mero factura</option>

								<?php
							    }
								elseif ($ls_ordenarpor=="n.nro_documento")
							   {
							  ?>
								 <option value="c.cedcli" >Seleccione...</option>
								<option value="c.cedcli">C&eacute;dula &oacute; rif</option>
								<option value="n.fecnot">Fecha</option>
								<option value="ip.codban">Banco</option>
								<option value="n.nro_documento" selected>N&uacute;mero factura</option>

								<?php
							   }?>
							   ?>
							</select>
							</p>						</td>
						<td width="258" colspan="2" >
							Orden
							<select name="combo_orden" size="1">
							<?php
							  if ($ls_orden=="ASC")
							   {
							   ?>
			                  <option value="ASC" selected>ASC</option>
			                  <option value="DESC">DESC</option>
							  <?php
							   }
							  else
							   {
							   ?>
			                  <option value="ASC" >ASC</option>
			                  <option value="DESC" selected>DESC</option>
							  <?php
							  }
							  ?>
			                </select>						</td>

						<?php
						}//else detalles
						?>
					</tr>

					<?php
					if ($ls_codtie == '0001') {

					?>

                   <input type="hidden" name="hdnagrotienda" value=""/>

					<tr>
		                <td height="22" align="right">Desde Unidad Operativa de Suministro:</td>
		                <td colspan="3" >
						<input name="txtdentienda_desde" type="text" id="txtdentienda_desde" size="30">
		                <input name="txtcodtienda_desde" type="hidden" id="txtcodtienda_desde" size="30">
		                <a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>

					<tr>
		                <td height="22" align="right">Hasta Unidad Operativa de Suministro:</td>
		                <td colspan="3" >
		                <input name="txtdentienda_hasta" type="text" id="txtdentienda_hasta" size="30">
		                <input name="txtcodtienda_hasta" type="hidden" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


					<?php
					}
					?>

					<tr>
		                <td height="22" align="right">Banco</td>
		                <td colspan="3" ><input name="txtdencla" type="text" id="txtdencla" size="30">
		                <a href="javascript: ue_buscar_banco();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


					<tr>
		                <td height="30" align="right">Cliente </td>
	                  <td colspan="3"><input name="txtrazcli" type="text" id="txtrazcli" size="30">
                      <a href="javascript: ue_buscar_cliente();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a><a href="javascript: ue_ver();"></a></td>
					</tr>

					<tr>
		                <td height="22" align="right">Fecha desde </td>
		                <td colspan="3"><input name="txtfecemi" type="text" id="txtfecemi"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"></td>
					</tr>
					<tr align="left">
		                <td height="23" align="right">Fecha hasta </td>
		                <td colspan="3"><input name="txtfecemi2" type="text" id="txtfecemi2"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true">
		                <a href="javascript: ue_ver();"></a></td>
					</tr>
					<tr>
						<td height="8">&nbsp;</td>
						<td colspan="3"><p>
							<label>
							<?php
							 if ($ls_opcion=='detalles')
							   {
							   ?>
			                   <input name="opcion" type="radio" value="detalles"  checked="checked" onClick="actualizar_option()">
			                    Cartas Ordenes Canceladas                  </label>
								 <label>
			                    <input name="opcion" type="radio" value="resumen"  onClick="actualizar_option()" >
			                    Cartas Ordenes por Cancelar                  </label>
								<?php
								}
								else
								{
								?>
								 <input name="opcion" type="radio" value="detalles"  onClick="actualizar_option()">
			                    Cartas Ordenes Canceladas                  </label>
			                  <label>
			                    <input name="opcion" type="radio" value="resumen"  checked="checked" onClick="actualizar_option()">
			                    Cartas Ordenes por Cancelar                  </label>
								<?php
								}
								?>
			                  <br>
			                </p>						</td>
					</tr>
	            </table>
				</div>
			</td>
		</tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
<?php

function sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,$alias_tabla,$ls_codtie) {

$add_sql = '';
if ($ls_tienda_desde=='') {

$add_sql = "$alias_tabla.codtiend='$ls_codtie'";

}else {

$add_sql = "$alias_tabla.codtiend  BETWEEN '$ls_tienda_desde' AND '$ls_tienda_hasta'";

}

return $add_sql;
}

if($ls_operacion=="VER")
{
	$ls_operacion="";
	$ls_suiche=false;

	if ($ls_ordenarpor!="null")
	{
	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{
		$ls_suiche=true;
		if ($ls_opcion=='detalles') //Cartas Ordenes canceladas
		{
		$ls_codcli=trim($ls_codcli);
		$ls_codban=trim($ls_codban);
			if (($ls_codcli=="") and ($ls_codban==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)." " .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' " .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad AND c.codemp=b.codemp AND c.codemp=e.codemp AND b.codemp=e.codemp ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
 //echo 'Hilo 1.1 <br>';
     		}
			elseif(($ls_codban!="") and ($ls_codcli==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND ip.codban='".$ls_codban."' AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac" .
						" AND b.codban=ip.codban AND b.codban='".$ls_codban."' AND e.id_entidad=ip.id_entidad " .
						" ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
 //echo 'Hilo 1.2 <br>';
			}

			elseif(($ls_codban=="") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND  substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.codcli=c.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst" .
						" AND n.nro_documento=ip.numfac AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad" .
						" ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
 //echo 'Hilo 1.3 <br>';

			}
			elseif(($ls_codban!=" ") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."'  AND n.codcli=c.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' AND ip.codcli=n.codcli AND" .
						" ip.codban='".$ls_codban."' AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."'" .
						" AND e.id_entidad=ip.id_entidad AND b.codban=ip.codban ORDER BY ".$ls_ordenarpor."  ".$ls_orden."; ";
 //echo 'Hilo 1.4 <br>';
			}
       /*     echo 'Hilo 1 <br>';
			print $ls_sql;*/
		}
		else  //Cartas Ordenes por Cancelar
		{

			$ls_codcli=trim($ls_codcli);
		$ls_codban=trim($ls_codban);
			if (($ls_codcli=="") and ($ls_codban==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
//echo 'Hilo 2.1 <br>';
     		}
			elseif(($ls_codban!="") and ($ls_codcli==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND ip.codban='".$ls_codban."' AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac" .
						" AND b.codban='".$ls_codban."' AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad" .
						" ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
//echo 'Hilo 2.2 <br>';
			}

			elseif(($ls_codban=="") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e " .
						"WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)." AND" .
						" n.codcli=c.codcli AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.numnot<>n.nro_documento AND n.tipnot='CXC'" .
						" AND n.estnota='P' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac" .
						" AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
//echo 'Hilo 2.3 <br>';

			}
			elseif(($ls_codban!=" ") and ($ls_codcli!="null"))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04' AND ip.codcli=n.codcli AND  ip.codban='".$ls_codban."'" .
						" AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."' AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad ORDER BY ".$ls_ordenarpor."  ".$ls_orden."; ";
	//echo 'Hilo 2.4 <br>';

			}

		/*echo 'Hilo 2 <br>';
		print $ls_sql;*/

		}//else Cartas Ordenes por Cancelar
	}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
		$ls_suiche=true;
		if ($ls_opcion=='detalles') //Cartas Ordenes canceladas
		{
		$ls_codcli=trim($ls_codcli);
		$ls_codban=trim($ls_codban);
			if (($ls_codcli=="") and ($ls_codban==""))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)." " .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad  AND (n.fecnot>='".$ls_fecemi."' AND n.fecnot<='".$ls_fecemi2."')" .
						"  ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
//echo 'Hilo 3.1 <br>';
     		}
			elseif(($ls_codban!="") and ($ls_codcli==""))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND ip.codban='".$ls_codban."' AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac" .
						" AND b.codban='".$ls_codban."' AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad AND" .
						" (n.fecnot>='".$ls_fecemi."' AND n.fecnot<='".$ls_fecemi2."') ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
//echo 'Hilo 3.2 <br>';
			}
/***********************************/
			elseif(($ls_codban=="") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)." " .
						" AND n.codcli=c.codcli AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst" .
						" AND n.nro_documento=ip.numfac AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad AND" .
						" (n.fecnot>='".$ls_fecemi."' AND n.fecnot<='".$ls_fecemi2."') ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
//echo 'Hilo 3.3 <br>';

			}
			elseif(($ls_codban!=" ") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' AND ip.codcli=n.codcli AND  ip.codban='".$ls_codban."'" .
						" AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."' AND e.id_entidad=ip.id_entidad" .
						" AND (n.fecnot>='".$ls_fecemi."' AND n.fecnot<='".$ls_fecemi2."')  ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";

		//echo 'Hilo 3.4 <br>';
			}


		///print $ls_ordenarpor;
			/*echo 'Hilo 3 <br>';
			print $ls_sql;*/
		}
		else //***************************Cartas Ordenes por Cancelar ***************************///
		{
			$ls_codcli=trim($ls_codcli);
			$ls_codban=trim($ls_codban);
			//print "CODIGO-".$ls_codcli.$ls_codban."-*-";
			if (($ls_codcli=="") and ($ls_codban==""))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND c.codcli=n.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad  AND (n.fecnot>='".$ls_fecemi."' AND n.fecnot<='".$ls_fecemi2."')" .
						" ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
//echo 'Hilo 4.1 <br>';
     		}
			elseif(($ls_codban!="") and ($ls_codcli==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND c.codcli=n.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND ip.codban='".$ls_codban."' AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac" .
						" AND b.codban='".$ls_codban."' AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad" .
						" AND (n.fecnot>='".$ls_fecemi."' AND n.fecnot<='".$ls_fecemi2."') ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";
//echo 'Hilo 4.2 <br>';
			}

			elseif(($ls_codban=="") and ($ls_codcli!="null"))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE  n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND  substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."'  AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND c.codcli=n.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst" .
						" AND n.nro_documento=ip.numfac AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad" .
						"  AND (n.fecnot>='".$ls_fecemi."' AND n.fecnot<='".$ls_fecemi2."')  ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";

//echo 'Hilo 4.3 <br>';
			}
			elseif(($ls_codban!=" ") and ($ls_codcli!="null"))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND c.codcli=n.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04' AND ip.codcli=n.codcli AND  ip.codban='".$ls_codban."'" .
						" AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."' AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad AND (n.fecnot>='".$ls_fecemi."' AND n.fecnot<='".$ls_fecemi2."')" .
						" ORDER BY ".$ls_ordenarpor." ".$ls_orden."; ";

	//	echo 'Hilo 4.4 <br>';
			}

		}
	/*echo 'Hilo 4 <br>';
	print $ls_sql;*/
	}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
	}
	}
	//////////////***********ordenado por null***************//////////////////////////////
	else
	{
	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{
		$ls_suiche=true;
		if ($ls_opcion=='detalles') //Cartas Ordenes canceladas
		{
		$ls_codcli=trim($ls_codcli);
		$ls_codban=trim($ls_codban);
			if (($ls_codcli=="") and ($ls_codban==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad ORDER BY n.fecnot ASC; ";
//echo 'Hilo 5 .1<br>';
     		}
			elseif(($ls_codban!="") and ($ls_codcli==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND ip.codban='".$ls_codban."' AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac" .
						" AND b.codban='".$ls_codban."' AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot ASC; ";
//echo 'Hilo 5.2 <br>';
			}

			elseif(($ls_codban=="") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.codcli=c.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst" .
						" AND n.nro_documento=ip.numfac AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot ASC; ";

//echo 'Hilo 5.3 <br>';
			}
			elseif(($ls_codban!=" ") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.codcli=c.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' AND ip.codcli=n.codcli AND  ip.codban='".$ls_codban."'" .
						" AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."' AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad ORDER BY n.fecnot  ASC; ";
	//echo 'Hilo 5.4 <br>';
			}


		//print $ls_ordenarpor;

			/*echo 'Hilo 5 <br>';
			print $ls_sql;*/
		}
	else  //*************************** Cartas Ordenes Por Cancelar ***************************///
		{

		$ls_codcli=trim($ls_codcli);
		$ls_codban=trim($ls_codban);
			if (($ls_codcli=="") and ($ls_codban==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad ORDER BY n.fecnot ASC; ";

     		}
			elseif(($ls_codban!="") and ($ls_codcli==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND c.codcli=n.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND ip.codban='".$ls_codban."' AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac" .
						" AND b.codban='".$ls_codban."' AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot ASC; ";

			}

			elseif(($ls_codban=="") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e " .
						"WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND c.codcli=n.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst" .
						" AND n.nro_documento=ip.numfac AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad " .
						" ORDER BY n.fecnot ASC; ";

			}
			elseif(($ls_codban!=" ") and ($ls_codcli!="null"))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND c.codcli=n.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04' AND ip.codcli=n.codcli AND ip.codban='".$ls_codban."'" .
						" AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."'" .
						" AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot  ASC; ";
			}
			}
		}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
		$ls_suiche=true;
		if ($ls_opcion=='detalles') //Cartas Ordenes Canceladas
		{

			if (($ls_codcli!="null") and ($ls_codban="null"))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND  substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND c.codcli=n.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst" .
						" AND n.nro_documento=ip.numfac AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot ASC; ";

     		}
			elseif(($ls_codban!="null") and ($ls_codcli="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND c.codcli=n.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."'" .
						" AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot ASC; ";

			}
			elseif(($ls_codban!="null") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND c.codcli=n.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst" .
						" AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."' AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad" .
						"  ORDER BY n.fecnot  ASC; ";
			}

			else
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='C' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban=ip.codban" .
						" AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad ORDER BY n.fecnot ASC;";


			}


			}
		else //Cartas Ordenes por Cancelar
			{

			$ls_codcli=trim($ls_codcli);
		$ls_codban=trim($ls_codban);
			if (($ls_codcli=="") and ($ls_codban==""))
			{

			/*
			 *
			 */	$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban=ip.codban" .
						" AND e.id_entidad=ip.id_entidad ORDER BY n.fecnot ".$ls_orden."; ";

     		}
			elseif(($ls_codban!="") and ($ls_codcli==""))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND n.codcli=c.codcli AND n.numnot<>n.nro_documento AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04'" .
						" AND ip.codcli=n.codcli AND ip.codban='".$ls_codban."' AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac" .
						" AND b.codban='".$ls_codban."' AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot ASC; ";

			}

			elseif(($ls_codban=="") and ($ls_codcli!="null"))
			{
				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND  substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.codcli=c.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04' AND ip.codcli=n.codcli AND n.numnot=ip.numinst" .
						" AND n.nro_documento=ip.numfac AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot ASC; ";

			}
			elseif(($ls_codban!=" ") and ($ls_codcli!="null"))
			{

				$ls_sql="SELECT n.codcli,n.numnot,n.tipnot,n.fecnot,n.estnota,n.nro_documento,n.monto,ip.codforpag,ip.codban,ip.id_entidad,c.razcli," .
						"c.cedcli,b.nomban,e.denominacion FROM sfc_nota n,sfc_instpago ip, sfc_cliente c,scb_banco b,sfc_entidadcrediticia e" .
						" WHERE n.codemp=ip.codemp AND n.codemp=c.codemp AND n.codemp=b.codemp AND n.codemp=e.codemp AND n.codemp='".$ls_codemp."'" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'n',$ls_codtie)."" .
						" AND substr(cast (n.codcli as char(30)),0,11) like '".$ls_codcli."' AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND n.codcli=c.codcli AND n.numnot<>n.nro_documento" .
						" AND n.tipnot='CXC' AND n.estnota='P' AND ip.codforpag='04' AND ip.codcli=n.codcli AND  ip.codban='".$ls_codban."'" .
						" AND n.numnot=ip.numinst AND n.nro_documento=ip.numfac AND b.codban='".$ls_codban."'" .
						" AND b.codban=ip.codban AND e.id_entidad=ip.id_entidad  ORDER BY n.fecnot  ASC; ";
			}


			}
		}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}

/*echo 'Hilo 6 <br>';
print $ls_sql;*/

	if ($ls_suiche==true and $ls_opcion=='detalles') //envia datos al reporte detalles
	{

	?>
		<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		var ls_fecemi="<?php print $ls_fecemi; ?>";
		var ls_fecemi2="<?php print $ls_fecemi2; ?>";
		pagina="reportes/sigesp_sfc_rep_cartasordenes_canceladas.php?sql="+ls_sql+"&fecemi2="+ls_fecemi2+"&fecemi="+ls_fecemi;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php
		}
	else
	{
	//echo "<img src='Imagenes/demo.jpg' />";
	$ruta="../Imagenes/demo.jpg";
	?>
		<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		var ls_fecemi="<?php print $ls_fecemi; ?>";
		var ls_fecemi2="<?php print $ls_fecemi2; ?>";
		var ruta="<?php print $ruta; ?>";
		//alert (ruta);
		pagina="reportes/sigesp_sfc_rep_cartasordenes.php?sql="+ls_sql+"&fecemi="+ls_fecemi+"&fecemi2="+ls_fecemi2;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php
		}
	}




?>
</body>
<script language="JavaScript">

/************************* TIENDA***************************************/
function ue_buscar_tienda(intervalo)
{
	f=document.form1;
	if (intervalo == 'desde') {
	  f.hdnagrotienda.value='desde';
	  f.txtcodtienda_desde.value="";
	}else {
	  f.hdnagrotienda.value='hasta';
	  f.txtcodtienda_hasta.value="";
	}
	pagina="sigesp_cat_tienda.php";
	popupWin(pagina,"catalogo",600,250);
}


function ue_cargartienda (codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
{

	f=document.form1;
	if (f.hdnagrotienda.value == 'desde') {
	 f.txtcodtienda_desde.value=codtie;
	 f.txtdentienda_desde.value=nomtie;
	}else {
     f.txtcodtienda_hasta.value=codtie;
     f.txtdentienda_hasta.value=nomtie;
	}


}

/************************* TIENDA***************************************/

function ue_ver()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
if(li_imprimir==1)
{
	f.operacion.value="VER";
	f.action="sigesp_sfc_d_rep_cartaorden.php";
	f.submit();
	}
 else
	{alert("No tiene permiso para realizar esta operaci�n");}
}

function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="";
	f.txtdencla.value="";
	f.txtdenpro.value="";
	f.txtrazcli.value="";
	f.txtfecemi.value="";
	f.txtfecemi2.value="";
}

function ue_buscar_banco()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_banco.php";
	popupWin(pagina,"catalogo",600,250);
}
function ue_cargarbanco(codban,nomban)
{
	f=document.form1;
	f.txtdencla.value=nomban;
	f.txtcodban.value=codban;
}


function ue_buscar_cliente()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_cliente.php";
	popupWin(pagina,"catalogo",600,250);
}
function ue_cargarcliente(codcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar)
{
	f=document.form1;
	f.txtcodcli.value=codcli;
	f.txtrazcli.value=nomcli;
	}

function actualizar_option()
{
			f=document.form1;
			f.operacion.value="ue_actualizar_option";
			f.action="sigesp_sfc_d_rep_cartaorden.php";
			 f.submit();

}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
