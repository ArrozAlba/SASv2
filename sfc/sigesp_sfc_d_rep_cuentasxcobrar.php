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
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
//print $ls_codtie;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Listado de Cuentas por Cobrar</title>
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
.Estilo2 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="505" height="20" class="descripcion_sistema  Estilo2">Sistema de Facturaci&oacute;n</td>
    <td width="273" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
	$ls_ventanas="sigesp_sfc_d_rep_cuentasxcobrar.php";

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
	$ls_codusu="%".$_POST["txtcodusu"]."%";
	$ls_codcli="%".$_POST["txtcodcli"]."%";
	$ls_razcli="%".$_POST["txtrazcli"]."%";
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_fecemi2=$_POST["txtfecemi2"];
	$ls_orden=$_POST["combo_orden"];
	$ls_ordenarpor=$_POST["combo_ordenarpor"];

	$ls_tienda_desde = $_POST["txtcodtienda_desde"];
	$ls_tienda_hasta = $_POST["txtcodtienda_hasta"];

	$ls_fecemi=$io_funcion->uf_convertirdatetobd($ls_fecemi);
	$ls_fecemi2=$io_funcion->uf_convertirdatetobd($ls_fecemi2);
	if ($ls_fecemi=="")
	{
	$ls_fecemi="".$ls_fecemi."";
	}

	if ($ls_fecemi2=="")
	{
	$ls_fecemi2="".$ls_fecemi2."";
	}
}
else
{
	$ls_operacion="";
	$ls_codusu="";
	$ls_codcli="";
	$ls_razcli="";
	$ls_fecemi="";
	$ls_fecemi2="";
	$ls_orden="";
	$ls_ordenarpor="Null";
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
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="258"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="3" class="titulo-ventana">Listado de Cuentas por Cobrar(Filtrar) </td>
              </tr>
              <tr>
                <td colspan="3" class="sin-borde">&nbsp;</td>
              </tr>
              <tr>
                <td width="143" ><div align="right">
                  <input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion?>">

                  Ordenar por</div></td>
                <td width="153" ><p align="left">
				<select name="combo_ordenarpor" size="1" >

				  <?php
				  if ($ls_ordenarpor=="Null")
				   {
				   ?>
				    <option value="Null" selected>Seleccione...</option>
                    <option value="c.cedcli">C&eacute;dula &oacute; rif</option>
					<option value="c.razcli">Raz&oacute;n Social</option>
                    <option value="f.fecemi">Fecha</option>
                    <option value="f.monto">Monto</option>
                    <option value="f.numfac">N&uacute;mero factura</option>
				  <?php
				   }
				  elseif ($ls_ordenarpor=="c.cedcli")
				   {
				    ?>
				    <option value="Null" >Seleccione...</option>
                    <option value="c.cedcli" selected >C&eacute;dula &oacute; rif</option>
					<option value="c.razcli">Raz&oacute;n Social</option>
                    <option value="f.fecemi">Fecha</option>
                    <option value="f.monto">Monto</option>
                    <option value="f.numfac">N&uacute;mero factura</option>
				  <?php
				   }
				   elseif ($ls_ordenarpor=="fecemi")
				   {
				    ?>
				    <option value="Null" >Seleccione...</option>
                    <option value="c.cedcli" >C&eacute;dula &oacute; rif</option>
					<option value="c.razcli">Raz&oacute;n Social</option>
                    <option value="f.fecemi" selected >Fecha</option>
                    <option value="f.monto">Monto</option>
                    <option value="f.numfac">N&uacute;mero factura</option>
				  <?php
				    }
				   elseif ($ls_ordenarpor=="monto")
				   {
				    ?>
				    <option value="Null" >Seleccione...</option>
                    <option value="c.cedcli" >C&eacute;dula &oacute; rif</option>
					<option value="c.razcli">Raz&oacute;n Social</option>
                    <option value="f.fecemi">Fecha</option>
                    <option value="f.monto"  selected >Monto</option>
                    <option value="f.numfac">N&uacute;mero factura</option>
				  <?php
				   }
				   elseif ($ls_ordenarpor=="numfac")
				   {
				  ?>
                    <option value="Null">Seleccione...</option>
                    <option value="c.cedcli">C&eacute;dula &oacute; rif</option>
					<option value="c.razcli">Raz&oacute;n Social</option>
                    <option value="f.fecemi">Fecha</option>
                    <option value="f.monto">Monto</option>
                    <option value="f.numfac" selected>N&uacute;mero factura</option>
					<?php
				   }
				   elseif ($ls_ordenarpor=="razcli")
				   {
				  ?>
                    <option value="Null">Seleccione...</option>
                    <option value="c.cedcli">C&eacute;dula &oacute; rif</option>
					<option value="c.razcli" selected="selected">Raz&oacute;n Social</option>
                    <option value="f.fecemi">Fecha</option>
                    <option value="f.monto">Monto</option>
                    <option value="f.numfac" selected>N&uacute;mero factura</option>
					<?php
				   }
				   else
				   {
				  ?>
				  <option value="Null" >Seleccione...</option>
                    <option value="c.cedcli" >C&eacute;dula &oacute; rif</option>
					<option value="c.razcli">Raz&oacute;n Social</option>
                    <option value="f.fecemi">Fecha</option>
                    <option value="f.monto" >Monto</option>
                    <option value="f.numfac">N&uacute;mero factura</option>
				 <?php
				   }
				 ?>
                  </select>
                Orden</p>
				</td>
                <td width="104" ><select name="combo_orden" size="1">
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
              </tr>

			<?php

              if ($ls_codtie == '0001')
               {

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
                <td height="22" align="right"><input name="txtcodcli" type="hidden" id="txtcodcli">
                Cliente </td>
                <td ><input name="txtrazcli" type="text" id="txtrazcli">
                <a href="javascript: ue_buscar_cliente();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
                <td ><a href="javascript: ue_ver();"></a></td>
              </tr>
              <tr>
                <td height="24" align="right">Fecha desde </td>
                <td colspan="2"><input name="txtfecemi" type="text" id="txtfecemi"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"></td>
              </tr>
              <tr align="left">
                <td height="23" align="right">Fecha hasta </td>
                <td colspan="2"><input name="txtfecemi2" type="text" id="txtfecemi2"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"></td>
              </tr>
              <tr>
                <td height="8">&nbsp;</td>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table>
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>

<?php
/************************************************************************************************************************/
/******************************  BUSCAR *********************************************************************************/
/************************************************************************************************************************/

function sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,$alias_tabla,$ls_codtie)
{

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

	if ($ls_fecemi=="" AND $ls_fecemi2=="") //si el usuario no coloca fecha
	{

	if ($ls_ordenarpor!="Null")
	{

	///////////existe cobro///////////////////
	$ls_cadena="SELECT SUM(cc.moncancel) as montos_cobrado,c.codcli,c.cedcli,c.razcli,f.numfac,f.montopar,f.fecemi ".
	           "FROM sfc_cliente c,sfc_factura f,sfc_cobro_cliente co,sfc_dt_cobrocliente cc ".
	           "WHERE c.codcli=co.codcli AND c.codcli=cc.codcli AND cc.codcli=f.codcli AND c.codemp=co.codemp" .
	           " AND c.codemp=cc.codemp AND f.codemp=co.codemp AND f.codemp=cc.codemp AND f.numfac=cc.numfac" .
	           " AND f.codtiend=co.codtiend AND f.codtiend=cc.codtiend AND co.codemp=cc.codemp AND co.codcli=cc.codcli AND co.numcob=cc.numcob" .
	           " AND co.codtiend=cc.codtiend AND f.estfaccon='P' AND (f.conpag='2' OR f.conpag='3') AND co.estcob<>'A' " .
	           " AND c.cedcli ilike '".$ls_razcli."' AND cc.tipcancel<>'n' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)."" .
	           " GROUP BY c.codcli,c.razcli,f.numfac,f.montopar,f.fecemi,c.cedcli " .
	           " ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

	//////////////existe factura(////////////////
	$ls_cadena2=" SELECT c.codcli,c.cedcli,c.razcli,f.numfac,substr(cast (f.fecemi as char(30)),0,11),f.monto,f.conpag,f.montoret,f.montopar ".
						" FROM sfc_cliente c,sfc_factura f ".
						" WHERE c.codcli=f.codcli AND c.codemp=f.codemp AND f.estfaccon='N' AND (f.estfaccon='P' or f.estfaccon='N') AND " .
						" c.cedcli ilike '".$ls_razcli."' AND (f.conpag='2' OR f.conpag='3') AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)."" .
						" ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

	/*print "1 <br>";
	print $ls_cadena."<br>";
	print $ls_cadena2."<br>";*/

	}
	else
	{
	$ls_cadena="SELECT SUM(cc.moncancel) as montos_cobrado,c.codcli,c.cedcli,c.razcli,f.numfac,f.montopar,f.fecemi ".
				"FROM sfc_cliente c,sfc_factura f,sfc_cobro_cliente co,sfc_dt_cobrocliente cc ".
	            "WHERE c.codcli=co.codcli AND c.codcli=cc.codcli AND cc.codcli=f.codcli AND c.codemp=co.codemp" .
	            " AND c.codemp=cc.codemp AND f.codemp=co.codemp AND f.codemp=cc.codemp AND f.numfac=cc.numfac" .
	            " AND f.codtiend=co.codtiend AND f.codtiend=cc.codtiend AND co.codemp=cc.codemp AND co.codcli=cc.codcli AND co.numcob=cc.numcob" .
	            " AND co.codtiend=cc.codtiend AND f.estfaccon='P' AND (f.conpag='2' OR f.conpag='3') AND co.estcob<>'A' " .
	            " AND c.cedcli ilike '".$ls_razcli."' AND cc.tipcancel<>'n' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)."" .
	            " GROUP BY c.codcli,c.razcli,f.numfac,f.montopar,f.fecemi,c.cedcli;";
	//////////////existe factura(////////////////
	$ls_cadena2=" SELECT c.codcli,c.cedcli,c.razcli,f.numfac,f.fecemi,f.monto,f.conpag,f.montoret,f.montopar ".
				" FROM sfc_cliente c,sfc_factura f ".
				" WHERE c.codcli=f.codcli AND c.codemp=f.codemp AND f.estfaccon='N' AND (f.estfaccon='P' or f.estfaccon='N') AND ".
				" c.cedcli ilike '".$ls_razcli."' AND (f.conpag='2' OR f.conpag='3') AND substr(cast (c.codcli as char(30)),0,11) like '".$ls_codcli."' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie).";";

	/*print "2 <br> 1---";
	print $ls_cadena."<br> 2--";
	print $ls_cadena2."<br>-----YAAAAAAAAAA----";*/

	}
	}
	else
	{

	if ($ls_ordenarpor!="Null")
	{
	$ls_cadena="SELECT SUM(co.moncob) as montos_cobrado,c.codcli,c.cedcli,c.razcli,f.numfac,f.montopar,f.fecemi ".
	           "FROM sfc_cliente c,sfc_factura f,sfc_cobro_cliente co,sfc_dt_cobrocliente cc ".
	            "WHERE c.codcli=co.codcli AND c.codcli=cc.codcli AND cc.codcli=f.codcli AND c.codemp=co.codemp" .
	            " AND c.codemp=cc.codemp AND f.codemp=co.codemp AND f.codemp=cc.codemp AND f.numfac=cc.numfac" .
	            " AND f.codtiend=co.codtiend AND f.codtiend=cc.codtiend AND co.codemp=cc.codemp AND co.codcli=cc.codcli AND co.numcob=cc.numcob" .
	            " AND co.codtiend=cc.codtiend AND f.estfaccon='P' AND (f.conpag='2' OR f.conpag='3') AND co.estcob<>'A' " .
	            " AND c.cedcli ilike '".$ls_razcli."' AND cc.tipcancel<>'n' AND (substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') " .
	             "GROUP BY c.codcli,c.razcli,f.numfac,f.montopar,f.fecemi,c.cedcli AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)."" .
	             " ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
			//   print $ls_cadena;

	$ls_cadena2=" SELECT c.codcli,c.cedcli,c.razcli,f.numfac,f.fecemi,f.monto,f.conpag,f.montoret,f.montopar ".
						" FROM sfc_cliente c,sfc_factura f ".
						" WHERE c.codcli=f.codcli AND c.codemp=f.codemp AND f.estfaccon='N' AND (f.conpag='2' OR f.conpag='3')" .
						" AND c.cedcli ilike '".$ls_razcli."' AND (substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."')" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

	/*print "3 <br>";
	print $ls_cadena."<br>";
	print $ls_cadena2."<br>";*/

	}
	else
	{
	$ls_cadena="SELECT SUM(co.moncob) as montos_cobrado,c.codcli,c.cedcli,c.razcli,f.numfac,f.montopar,f.fecemi ".
	           "FROM sfc_cliente c,sfc_factura f,sfc_cobro_cliente co,sfc_dt_cobrocliente cc ".
	            "WHERE c.codcli=co.codcli AND c.codcli=cc.codcli AND cc.codcli=f.codcli AND c.codemp=co.codemp" .
	            " AND c.codemp=cc.codemp AND f.codemp=co.codemp AND f.codemp=cc.codemp AND f.numfac=cc.numfac" .
	            " AND f.codtiend=co.codtiend AND f.codtiend=cc.codtiend AND co.codemp=cc.codemp AND co.codcli=cc.codcli AND co.numcob=cc.numcob" .
	            " AND co.codtiend=cc.codtiend AND f.estfaccon='P' AND (f.conpag='2' OR f.conpag='3') AND co.estcob<>'A' " .
	            " AND c.cedcli ilike '".$ls_razcli."' AND cc.tipcancel<>'n' AND (substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."')" .
	            " AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." " .
	            " GROUP BY c.codcli,c.razcli,f.numfac,f.montopar,f.fecemi,c.cedcli  ORDER BY f.numfac;";

	$ls_cadena2=" SELECT c.codcli,c.cedcli,c.razcli,f.numfac,f.fecemi,f.monto,f.conpag,f.montoret,f.montopar ".
						" FROM sfc_cliente c,sfc_factura f ".
						" WHERE c.codcli=f.codcli AND c.codemp=f.codemp AND f.estfaccon='N' AND (f.conpag='2' OR f.conpag='3')" .
						" AND c.cedcli ilike '".$ls_razcli."'  AND (substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."')" .
						" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." ORDER BY f.numfac;";

	/*print "4 <br>";
	print $ls_cadena."<br>";
	print $ls_cadena2."<br>";*/

	}


	}

    ?>

     <script language="JavaScript">
	var ls_fecemi="<?php print $ls_fecemi; ?>";
	var ls_fecemi2="<?php print $ls_fecemi2; ?>";
	var ls_orden="<?php print $ls_orden; ?>";
	var ls_ordenarpor="<?php print $ls_ordenarpor; ?>";
	var ls_cadena="<?php print $ls_cadena; ?>";
	var ls_cadena2="<?php print $ls_cadena2; ?>";
	   	//pagina="../sfc/reportes/documentos/sigesp_sfc_rep_cuentasxcobrar.php";
		pagina="reportes/sigesp_sfc_rep_cuentasxcobrar.php?fecemi2="+ls_fecemi2+"&fecemi="+ls_fecemi+"&ordenapor="+ls_ordenarpor+"&orden="+ls_orden+"&cadena="+encodeURIComponent(ls_cadena)+"&cadena2="+encodeURIComponent(ls_cadena2);
	  	popupWin(pagina,"catalogo",580,700);
     </script>
    <?PHP

}
?>
</body>
<!--************************************************************************************************************************/
/*************************** FUNCIONES DE JAVA SCRIPT **********************************************************************/
/*************************************************************************************************************************-->
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
  f.action="sigesp_sfc_d_rep_cuentasxcobrar.php";
  f.submit();
}
 else
	{alert("No tiene permiso para realizar esta operaci�n");}
}

function ue_cargarcajero(codusu,nomusu,codtie,nomtie)
{
		    f=document.form1;
			f.txtcodusu.value=codusu;
			/*f.txtnomusu.value=nomusu;*/
			/*f.txtcodtie.value=codtie;
			f.txtnomtie.value=nomtie;*/
}

function ue_buscar_cajero()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_cajero.php";
	popupWin(pagina,"catalogo",650,300);
	/*li_leer=f.leer.value;
	if(li_leer==1)
	{
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}*/
}

function ue_cargarcliente(codcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar)
{
			f=document.form1;
			//f.hidstatus.value="C"
			f.txtcodcli.value=codcli;
            f.txtrazcli.value=nomcli;
            /*f.txttelcli.value=telcli;
            f.txtdircli.value=dircli;
            f.txtcelcli.value=celcli;
            f.hidcodpai.value=codpai;
            f.hidcodest.value=codest;
			f.hidcodmun.value=codmun;
			f.hidcodpar.value=codpar;
			f.operacion.value="ue_cargarcliente";
			f.submit();*/
}

function ue_buscar_cliente()
{
        f=document.form1;
		f.operacion.value="";
		pagina="sigesp_cat_cliente.php";
		popupWin(pagina,"catalogo",600,250);

			/*li_leer=f.leer.value;
   		    if(li_leer==1)
			{
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}*/
}

function aceptar(codcla,nomcla)
{
    opener.ue_cargarclasificacion(codcla,nomcla);
	close();
}


  function actualizar_combo()
  {
   f=document.form1;
  f.combo_ordenarpor.value="VER";
  f.action="sigesp_sfc_d_rep_cuentasxcobrar.php";
  f.submit();

  }

</script>

<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
