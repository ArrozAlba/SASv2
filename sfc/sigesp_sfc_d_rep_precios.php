<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ROSMARY LINAREZ            */
/* REVISADO POR: ING. ZULHEYMAR RODRIGUEZ */
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Listado de Precios</title>
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
.Estilo1 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
	<tr>
		<td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
	</tr>
	<tr>
    <td width="490" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="288" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
	$ls_ventanas="sigesp_sfc_d_rep_precios.php";

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
	$ls_denpro="%/".$_POST["txtdenpro"]."%";
	$ls_dencla="%/".$_POST["txtdencla"]."%";
	$ls_estatus=$_POST["combo_estatus"];
	$ls_orden=$_POST["combo_orden"];
	$ls_ordenarpor=$_POST["combo_ordenarpor"];

	$ls_tienda_desde = $_POST["txtcodtienda_desde"];
	$ls_tienda_hasta = $_POST["txtcodtienda_hasta"];
	$ls_dentienda_desde = $_POST["txtdentienda_desde"];
	$ls_dentienda_hasta = $_POST["txtdentienda_hasta"];

	}
else
{
	$ls_operacion="";
	$ls_denpro="";
	$ls_orden="";
	$ls_estatus="Null";
	$ls_ordenarpor="Null";
	$ls_dencla="";

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
    <table width="518" height="215" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
		<tr>
			<td width="350" height="208"><div align="center">
				<table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
					<tr>
						<td colspan="5" class="titulo-ventana">Listado de Precios de Productos (Filtrar) </td>
					</tr>
					<tr>
						<td colspan="5" class="sin-borde">&nbsp;</td>
					</tr>
					<tr>
						<td width="143" ><div align="right"><span class="sin-borde">
						  <input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $ls_codemp?>">
						  <input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion?>">
						</span>Ordenar por
						</div>						</td>

						<td width="153" ><select name="combo_ordenarpor" size="1" >
                          <?php
							  if ($ls_ordenarpor=="Null")
							   {
							   ?>
                          <option value="Null" selected>Seleccione...</option>
                          <option value="cl.dencla">Clasificaci&oacute;n del Producto</option>
                          <option value="a.denart">Definici&oacute;n del Producto</option>
                          <?php
							   }
							  elseif ($ls_ordenarpor=="cl.dencla")
							   {
								?>
                          <option value="Null" >Seleccione...</option>
                          <option value="cl.dencla">Clasificaci&oacute;n del Producto</option>
                          <option value="a.denart">Definici&oacute;n del Producto</option>
                          <?php
							   }
							   elseif ($ls_ordenarpor=="a.denart")
							   {
								?>
                          <option value="Null" >Seleccione...</option>
                          <option value="cl.dencla">Clasificaci&oacute;n del Producto</option>
                          <option value="a.denart" selected>Definici&oacute;n del Producto</option>
                          <?php
							   }

							   ?>
                        </select></td>
						<td width="" ><p align="right">&nbsp;</p></td>
						<td  colspan="2" >
							Orden<select name="combo_orden" size="1">
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
		                <td height="22" align="right">Clasificaci&oacute;n</td>
		                <td colspan="4" ><input name="txtdencla" type="text" id="txtdencla" size="30">
		                <a href="javascript: ue_buscar_clasificacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>
					<tr>
		                <td height="27" align="right">Producto</td>
	                  <td colspan="4" ><input name="txtdenpro" type="text" id="txtdenpro" size="30">
		                <a href="javascript:ue_catproducto();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


		                <a href="javascript: ue_ver();"></a>

					 <tr>
			 <td width="143" height="25" ><div align="right">
                  <input name="estatus" type="hidden" id="estatus" value="<? print $ls_estatus?>">

                Existencia

                </div></td>
                <td width="153" ><p align="left">
                  <select name="combo_estatus" size="1">
                    <?php
				  if ($ls_estatus=="Null")
				   {
				   ?>
                    <option value="Null"  selected>Seleccione...</option>
                    <option value="N">Productos Sin Existencia</option>
                    <option value="C">Productos Con Existencia</option>

                    <?php
				   }
				  elseif($ls_estatus=="N")
				   {
				   ?>
                    <option value="Null"  selected>Seleccione...</option>
                    <option value="N">Productos Sin Existencia</option>
                    <option value="C">Productos Con Existencia</option>

                    <?php
				  }

				  elseif($ls_estatus=="C")
				   {
				   ?>
                    <option value="Null"  selected>Seleccione...</option>
                    <option value="N">Productos Sin Existencia</option>
                    <option value="C">Productos Con Existencia</option>

                    <?php
				  }

				  ?>
                  </select>

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

if($ls_operacion=="VER")
{
	$ls_operacion="";
	$ls_suiche=false;


	if ($ls_ordenarpor!="Null")
	{

		$ls_suiche=true;

		if($ls_estatus=="Null")
		{

		$ls_sql=1;


		}
		elseif($ls_estatus=="N")
		{

		$ls_sql=2;


		}
		elseif($ls_estatus=="C")
		{

			$ls_sql=3;

		}


	}
	else
	{

	if($ls_estatus=="Null")
		{


		$ls_sql=4;



		}
		elseif($ls_estatus=="N")
		{
		$ls_sql=5;


		//print $ls_sql;
		}
		elseif($ls_estatus=="C")
		{

		$ls_sql=6;

		}


	}
//print $ls_sql;



	?>
		<script language="JavaScript">

		var codtie="<?php print $ls_codtie; ?>";
		var codemp="<?php print $ls_codemp; ?>";
		if(codtie == '0001'){
			var ls_tienda_desde="<?php print $ls_tienda_desde; ?>";
			var ls_tienda_hasta="<?php print $ls_tienda_hasta; ?>";
		}else{
			var ls_tienda_desde=codtie;
			var ls_tienda_hasta=codtie;
		}


		var ls_sql="<?php print $ls_sql; ?>";

		var ordenpor="<?php print $ls_ordenarpor; ?>";
		var orden="<?php print $ls_orden; ?>";

		var dencla="<?php print $ls_dencla; ?>";
		var denpro="<?php print $ls_denpro; ?>";



		pagina="reportes/sigesp_sfc_rep_precios.php?sql="+encodeURIComponent(ls_sql)+"&codtie="+codtie+"&codemp="+codemp+"&ordenpor="+ordenpor+"&orden="+orden+"&dencla="+dencla+"&denpro="+denpro+"&codtienddesde="+ls_tienda_desde+"&codtiendhasta="+ls_tienda_hasta;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php

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

function ue_cargarproducto(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,codalm)
{
    f=document.form1;
	f.operacion.value="";

	f.txtdenpro.value=denpro;
}
  function ue_catproducto()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_producto.php";
	popupWin(pagina,"catalogo",580,300);
}
function ue_ver()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
if(li_imprimir==1)
{
	f.operacion.value="VER";
	f.action="sigesp_sfc_d_rep_precios.php";
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


}
function actualizar_combo()
{
	f=document.form1;
	f.combo_ordenarpor.value="VER";
	f.action="sigesp_sfc_d_rep_precios.php";
	f.submit();
}
function ue_buscar_clasificacion()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_clasificacion.php";
	popupWin(pagina,"catalogo",600,250);
}
function ue_cargarclasificacion(codcla,nomcla)
{
	f=document.form1;
	f.txtdencla.value=nomcla;
}




function ue_imprimir()
{
	f=document.form1;
	ls_codemp=f.txtcodemp.value;
	ls_denpro=f.txtdenpro.value;
	ls_dencla=f.txtdencla.value;
	ls_ordenapor=f.combo_ordenapor.value;
	ls_orden=f.combo_orden.value;

}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>