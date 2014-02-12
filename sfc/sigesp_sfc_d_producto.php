<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_conexion.php'";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Producto</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.Estilo1 {color: #6699CC}
</style>

</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="545" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="233" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><!-- a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a --><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();

$ls_empresa=$la_datemp["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_producto.php";

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

require_once("class_folder/sigesp_sfc_c_producto.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/evaluate_formula.php");
require_once("class_folder/sigesp_sfc_c_producto_transf.php");
require_once("../shared/class_folder/grid_param.php");

$io_funcsob =   new sigesp_sob_c_funciones_sob();
$io_producto = new sigesp_sfc_c_producto();
$io_datastore = new class_datastore();
$io_datastore2 = new class_datastore();
$io_datastore3 = new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$is_msg = new class_mensajes();
$io_evalform = new evaluate_formula();
$io_prodarchivo= new sigesp_sfc_c_producto_transf();
$io_include = new sigesp_include();
$io_connect = $io_include->uf_conectar();
$io_sql = new class_sql($io_connect);
$io_grid = new grid_param();
$ls_codemp = $la_datemp["codemp"];

/**************   GRID   PRODUCTOS   *******************/
$ls_tituloconcepto="Art&iacute;culos por Tienda";
$li_anchoconcepto=715;
$ls_nametable="Art&iacute;culos por Tienda";
$la_columconcepto[1]="";
$la_columconcepto[2]="Producto";
$la_columconcepto[3]="Unidad Suministro";
$la_columconcepto[4]="Cargo";
$la_columconcepto[5]="Porc. Gan";
$la_columconcepto[6]="Tip. Costo";
$la_columconcepto[7]="Pre/Uni";
$la_columconcepto[8]="Pre/Vta";
$la_columconcepto[9]="Pre/Vta 1";
$la_columconcepto[10]="Pre/Vta 2";
$la_columconcepto[11]="Pre/Vta 3";
$la_columconcepto[12]="Flete";
$la_columconcepto[13]="Stock. Min";
$la_columconcepto[14]="Stock. Max";
$la_columconcepto[15]="Pto. Reord";
$la_columconcepto[16]="Ult. Costo";
$la_columconcepto[17]="Costo Prom.";


if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_tiendadsd=$_POST["txttiendadsd"];
	$ls_nomtiendadsd=$_POST["txtnomtiendadsd"];
	$ls_tiendahst=$_POST["txttiendahst"];
	$ls_nomtiendahst=$_POST["txtnomtiendahst"];
	$ls_codartdsd=$_POST["txtcodartdsd"];
	$ls_denartdsd=$_POST["txtdenartdsd"];
	$ls_codarthst=$_POST["txtcodarthst"];
	$ls_denarthst=$_POST["txtdenarthst"];
}
else
{
	$ls_operacion='';
	$ls_tiendadsd='';
	$ls_nomtiendadsd='';
	$ls_tiendahst='';
	$ls_nomtiendahst='';
	$ls_codartdsd='';
	$ls_denartdsd='';
	$ls_codarthst='';
	$ls_denarthst='';
}

if($ls_operacion=='NUEVO'){
	$ls_operacion='';
	$ls_tiendadsd='';
	$ls_nomtiendadsd='';
	$ls_tiendahst='';
	$ls_nomtiendahst='';
	$ls_codartdsd='';
	$ls_denartdsd='';
	$ls_codarthst='';
	$ls_denarthst='';

	$la_arrdataprod=$io_producto->uf_select_productostienda($ls_tiendadsd,$ls_tiendahst,$ls_codartdsd,$ls_codarthst);
}
elseif($ls_operacion=='BUSCARPROD'){

	$la_arrdataprod=$io_producto->uf_select_productostienda($ls_tiendadsd,$ls_tiendahst,$ls_codartdsd,$ls_codarthst);

	list($la_objectconcepto,$li_filasconcepto) = $la_arrdataprod;
	if($la_objectconcepto[1][2]=="")
	{
		$is_msg->message("No hay registros de productos");
	}

	//$ls_operacion='';
}elseif($ls_operacion=='GUARDAR'){

	$fila=$_POST["filaedit"];
	$li_codart=$_POST["txtcodart".$fila];
	$li_codtiend=$_POST["txtcodtiend".$fila];
	$li_codcar=$_POST["txtcodcar".$fila];
	$li_moncar=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtmoncar".$fila]),2,'.','');
	$li_porgan=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtporgan".$fila]),2,'.','');
	$li_cosfle=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtcosfle".$fila]),2,'.','');
	$li_preuni=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtpreuni".$fila]),2,'.','');
	$li_preven=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtpreven".$fila]),2,'.','');
	$li_preven1=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtpreven1".$fila]),2,'.','');
	$li_preven2=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtpreven2".$fila]),2,'.','');
	$li_preven3=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtpreven3".$fila]),2,'.','');
	$li_max=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtmax".$fila]),2,'.','');
	$li_min=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtmin".$fila]),2,'.','');
	$li_reoart=number_format($io_funcsob->uf_convertir_cadenanumero($_POST["txtreoart".$fila]),2,'.','');

	$ls_valido=$io_producto->uf_update_producto($li_codart,$li_codtiend,$li_codcar,$li_moncar,$li_porgan,$li_cosfle,$li_preuni,$li_preven,$li_preven1,$li_preven2,$li_preven3,$li_max,$li_min,$li_reoart,$la_seguridad);

	$ls_mensaje=$io_producto->io_msgc;
	$is_msg->message ($ls_mensaje);

	$ls_operacion='BUSCARPROD';
	$fila='';
	$li_codart='';
	$li_codtiend='';
	$li_codcar='';
	$li_moncar='';
	$li_porgan='';
	$li_cosfle='';
	$li_preuni='';
	$li_preven='';
	$li_preven1='';
	$li_preven2='';
	$li_preven3='';
	$li_max='';
	$li_min='';
	$li_reoart='';

	$la_arrdataprod=$io_producto->uf_select_productostienda($ls_tiendadsd,$ls_tiendahst,$ls_codartdsd,$ls_codarthst);

	list($la_objectconcepto,$li_filasconcepto) = $la_arrdataprod;
	if($la_objectconcepto[1][2]=="")
	{
		$is_msg->message("No hay registros de productos");
	}
}

?>
<p>&nbsp;</p>
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
{?>
	<script language=JavaScript>
		location.href='sigespwindow_blank.php'
	</script>
<? }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
	<table width="730" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    	<tr>
	        <td><div align="center">
	        	<table width="725" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
		        	<tr>
		            	<td colspan="6" class="titulo-ventana">Producto de Facturaci&oacute;n </td>
		            </tr>
		            <tr>
	                	<td colspan="6">
						<input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
						<input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus ?>">
						<input name="filaedit" type="hidden" id="filaedit" value="<? print $ls_filaedit ?>">
		                </td>
	              	</tr>
	              	<tr><td colspan="6">&nbsp;</td></tr>
	              	<tr>
	              		<td height="22" align="rigth"><div align="right"><span class="style2">Unidad Operativa de Suministro Desde: </span></div></td>
	              		<td><input type="text" name="txttiendadsd" id="txttiendadsd" value="<? print $ls_tiendadsd?>" size="10" maxlength="10"/>
	              		<a href="javascript:ue_cattienda('txttiendadsd');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
	              		<td><input type="text" name="txtnomtiendadsd" id="txtnomtiendadsd" value="<? print $ls_nomtiendadsd?>" size="15" class="sin-borde" readonly="true"/></td>
	              		<td height="22" align="right"><span class="style2">Unidad Operativa de Suministro Hasta: </span></td>
	              		<td><input type="text" name="txttiendahst" id="txttiendahst" value="<? print $ls_tiendahst?>" size="10" maxlength="10"/>
	              		<a href="javascript:ue_cattienda('txttiendahst');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
	              		<td><input type="text" name="txtnomtiendahst" id="txtnomtiendahst" value="<? print $ls_nomtiendahst?>" size="15" class="sin-borde" readonly="true"/></td>
	              	</tr>
	              	<tr><td colspan="6">&nbsp;</td></tr>
	              	<tr>
	              		<td height="22" align="rigth"><div align="right"><span class="style2">Producto Desde: </span></div></td>
	              		<td><input type="text" name="txtcodartdsd" id="txtcodartdsd" value="<? print $ls_codartdsd?>" size="15" readonly="true"/>
	              		<a href="javascript:ue_catart('txtcodartdsd');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
	              		<td><input type="text" name="txtdenartdsd" id="txtdenartdsd" value="<? print $ls_denartdsd?>" size="15" class="sin-borde" readonly="true"/></td>

	              		<td height="22" align="rigth"><div align="right"><span class="style2">Producto Hasta: </span></div></td>
	              		<td><input type="text" name="txtcodarthst" id="txtcodarthst" value="<? print $ls_codarthst?>" size="15" readonly="true"/>
	              		<a href="javascript:ue_catart('txtcodarthst');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
	              		<td><input type="text" name="txtdenarthst" id="txtdenarthst" value="<? print $ls_denarthst?>" size="15" class="sin-borde" readonly="true"/></td>
	              	</tr>
	              	<tr><td colspan="6">&nbsp;</td></tr>
	              	<tr>
	              		<td colspan="6" align="center"><input type="button" class="boton" name="buscar" id="buscar" value="Buscar"onClick="javascript: ue_buscarprod(this);" /></td>
	              	</tr>
	              	<tr><td colspan="6">&nbsp;</td></tr>
	              	<tr align="center" class="formato-blanco">
			          <td height="11" colspan="6">
			          	<?php
			          		if($ls_operacion != ''){
			          			$io_grid->make_gridScroll($li_filasconcepto,$la_columconcepto,$la_objectconcepto,$li_anchoconcepto,$ls_tituloconcepto,$ls_nametable,400);
			          		}
			          		$ls_operacion='';
			          	?>
			          </td>
			          <input name="filasconcepto" type="hidden" id="filasconcepto" value="<? print $li_filasconcepto;?>">
			        </tr>

	            </table>
	        </div>
	       </td>
	    </tr>
  </table>

</form>
</body>

<script language="JavaScript">

//////////////////////////////////////////////////////////
function ue_nuevo()
{
    f=document.form1;
	f.operacion.value="NUEVO";
	f.submit();
}
//////////////////////////////////////////////////////////
function ue_cattienda(caja)
{
    f=document.form1;
	f.operacion.value=caja;
	pagina="sigesp_cat_tienda.php";
	popupWin(pagina,"catalogo",600,250);
}

//////////////////////////////////////////////////////////
function ue_catart(caja)
{
	f=document.form1;
	f.operacion.value=caja;
	pagina="sigesp_cat_articulo.php";
	popupWin(pagina,"catalogo",600,250);
}

////////////////////////////////////////////////////////////
function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
{
	f=document.form1;
	caja=f.operacion.value;
	if(caja=='txttiendadsd'){
		f.txttiendadsd.value=codtie;
		f.txtnomtiendadsd.value=nomtie;
	}
	else
	{
		if(caja=='txttiendahst'){
			f.txttiendahst.value=codtie;
			f.txtnomtiendahst.value=nomtie;
		}
	}
	f.operacion.value='';

}

////////////////////////////////////////////////////////////
function ue_cargarcliente(codart,denart,cosart,cospro)
{
	f=document.form1;
	caja=f.operacion.value;
	if(caja=='txtcodartdsd'){
		f.txtcodartdsd.value=codart;
		f.txtdenartdsd.value=denart;
	}
	else
	{
		if(caja=='txtcodarthst'){
			f.txtcodarthst.value=codart;
			f.txtdenarthst.value=denart;
		}
	}
	f.operacion.value='';
}

//////////////////////////////////////////////////////////
function ue_buscarprod()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1){
		if(f.txttiendadsd.value=='' || f.txttiendahst.value==''){
			alert("Indique el Rango de Tiendas que quiere buscar!");
		}else{
			if(f.txtcodartdsd.value=='' || f.txtcodarthst.value==''){
				alert("Indique el Rango de Artículos que quiere buscar!");
			}else{
				f.operacion.value="BUSCARPROD";
				f.submit();
			}
		}
	}else{
		alert("No tiene permiso para realizar esta operación");
	}

}

//////////////////////////////////////////////////////////
function ue_editar(fila)
{
	f=document.form1;

	li_cambiar=f.cambiar.value;
	if(li_cambiar==1){
		f.filaedit.value=fila;
		codart=document.getElementById("txtcodart"+fila);
		denart=document.getElementById("txtdenart"+fila);
		codtienda=document.getElementById("txtcodtiend"+fila);
		dentienda=document.getElementById("txtdentiend"+fila);
		codcar=document.getElementById("txtcodcar"+fila);
		dencar=document.getElementById("txtdencar"+fila);
		moncar=document.getElementById("txtmoncar"+fila);
		porgan=document.getElementById("txtporgan"+fila);
		flete=document.getElementById("txtcosfle"+fila);
		preuni=document.getElementById("txtpreuni"+fila);
		preven=document.getElementById("txtpreven"+fila);
		preven1=document.getElementById("txtpreven1"+fila);
		preven2=document.getElementById("txtpreven2"+fila);
		preven3=document.getElementById("txtpreven3"+fila);
		min=document.getElementById("txtmin"+fila);
		max=document.getElementById("txtmax"+fila);
		reorden=document.getElementById("txtreoart"+fila);
		tipcost=document.getElementById("txttipcost"+fila);
		//alert(tipcost.value);
		ultcos=document.getElementById("txtultcos"+fila);
		cospro=document.getElementById("txtcospro"+fila);

		param="li_codart="+codart.value+"&li_denart="+escape(denart.value)+"&li_codtiend="+codtienda.value+"&li_dentiend="+dentienda.value+"&li_porgan="+porgan.value;
		param=param+"&li_flete="+flete.value+"&li_preuni="+preuni.value+"&li_codcar="+codcar.value+"&li_dencar="+dencar.value+"&li_moncar="+moncar.value;
		param=param+"&li_preven="+preven.value+"&li_preven1="+preven1.value+"&li_preven2="+preven2.value+"&li_preven3="+preven3.value+"&li_min="+min.value;
		param=param+"&li_max="+max.value+"&li_reorden="+reorden.value+"&li_tipcos="+tipcost.value+"&li_ultcos="+ultcos.value+"&li_cospro="+cospro.value;
		pagina="sigesp_cat_edit_producto.php?"+param;
		//alert(pagina);
		popupWin(pagina,"catproducto",700,500);

	}else{
		alert("No tiene permiso para realizar esta operación");
	}
}

function ue_guardar(codart,codtiend,porgan,flete,preuni,codcar,moncar,preven,preven1,preven2,preven3,max,min,reorden){

	f=document.form1;
	f.operacion.value='GUARDAR';
	fila=f.filaedit.value;
	document.getElementById("txtcodcar"+fila).value=codcar;
	document.getElementById("txtmoncar"+fila).value=moncar;
	document.getElementById("txtporgan"+fila).value=porgan;
	document.getElementById("txtcosfle"+fila).value=flete;
	document.getElementById("txtpreuni"+fila).value=preuni;
	document.getElementById("txtpreven"+fila).value=preven;
	document.getElementById("txtpreven1"+fila).value=preven1;
	document.getElementById("txtpreven2"+fila).value=preven2;
	document.getElementById("txtpreven3"+fila).value=preven3;
	document.getElementById("txtmax"+fila).value=max;
	document.getElementById("txtmin"+fila).value=min;
	document.getElementById("txtreoart"+fila).value=reorden;

	f.submit();

}

</script>
