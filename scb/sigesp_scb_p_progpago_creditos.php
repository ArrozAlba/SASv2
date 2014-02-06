<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_scb = new class_funciones_banco();
$io_fun_scb->uf_load_seguridad("SCB","sigesp_scb_p_progpago_creditos.php",$ls_permisos,$la_seguridad,$la_permisos);

function uf_limpiar_variables()
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_limpiar_variables.
	//		   Access: private
	//	  Description: Función que limpia todas las variables necesarias en la página
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 04/07/2007			Fecha Última Modificación : 07/07/2008. 
	////////////////////////////////////////////////////////////////////////////////////
    global $li_totrows,$ls_operacion,$la_rowgri,$la_object,$io_fun_scb,$ls_rutfil;
    global $ls_cedben,$ls_nomben,$ls_numsol,$ld_monsol,$ls_fecliq,$ls_filnam,$li_filsel;
    
	$ls_cedben     = "";
	$ls_nomben     = "";
	$ls_numsol     = "";
	$ld_monsol     = "";
	$ls_fecliq     = "";
	$ls_filnam     = "";		
	$li_filsel     = 0;
	$li_totrows    = 1;	
	$ls_rutfil     = "../scc/IV/pendientes"; 
	$la_rowgri[1]  = "Beneficiario";
	$la_rowgri[2]  = "Banco";
	$la_rowgri[3]  = "Cuenta Bancaria";
	$la_rowgri[4]  = "Monto";
	$ls_operacion  = $io_fun_scb->uf_obteneroperacion();
	
	if ($ls_operacion=='NUEVO')
	   {
	     $la_object[$li_totrows][1] = "<input type=text     name=txtbenalt".$li_totrows." id=txtbenalt".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=20 maxlength=15>";
	     $la_object[$li_totrows][2] = "<input type=text     name=txtcodban".$li_totrows." id=txtcodban".$li_totrows." value='' class=sin-borde readonly style=text-align:left    size=40 maxlength=254>";
	     $la_object[$li_totrows][3] = "<input type=text     name=txtctaban".$li_totrows." id=txtctaban".$li_totrows." value='' class=sin-borde readonly style=text-align:left    size=45 maxlength=254>";
	     $la_object[$li_totrows][4] = "<input type=text     name=txtmonsol".$li_totrows." id=txtmonsol".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=18 maxlength=18>
								       <input type=hidden   name=txtfilnam".$li_totrows." id=txtfilnam".$li_totrows." value=''>";		
       }
}

function uf_load_variables()
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_variables
	//		   Access: private
	//	  Description: Función que carga todas las variables necesarias en la página
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 08/07/2007		   Fecha Última Modificación : 08/07/2007.
	//////////////////////////////////////////////////////////////////////////////
	
	global $li_totrows,$ls_filnam,$ls_numsol,$ls_cedben,$ld_monsol,$ls_fecliq,$ls_nomben;
	
	$ls_numsol  = $_POST["txtnumsol"];
	$ls_cedben  = $_POST["txtcedben"];
	$ls_nomben  = $_POST["txtnomben"];
	$ld_monsol  = $_POST["txtmonsol"];
	$ls_fecliq  = $_POST["txtfecliq"];
	$ls_filnam  = $_POST["hidfilnam"];
	$li_totrows = $_POST["hidtotrow"];	
}

function uf_load_grid($ai_totrows,&$la_object)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_grid
	//		   Access: private
	//	  Description: Función que carga todo el objeto grid.
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 08/07/2007		   Fecha Última Modificación : 08/07/2007.
	//////////////////////////////////////////////////////////////////////////////
	
	for ($li_i=1;$li_i<=$ai_totrows;$li_i++)
	    {
		  $ls_benalt = $_POST["txtbenalt".$li_i];//Nombre del Beneficiario Alterno.
		  $ls_codban = $_POST["txtcodban".$li_i];
		  $ls_nomban = $_POST["hidnomban".$li_i];
		  $ls_ctaban = $_POST["txtctaban".$li_i];
		  $ld_monsol = $_POST["txtmonsol".$li_i];
		  $ls_filnam = $_POST["txtfilnam".$li_i];
		  $ls_numban = $_POST["hidcodban".$li_i];
		  $ls_ctanum = $_POST["hidctaban".$li_i];
		  $ls_numsol = $_POST["hidnumsol".$li_i];
		  
	      $la_object[$li_i][1] = "<input type=text     name=txtbenalt".$li_i." id=txtbenalt".$li_i." value='".$ls_benalt."' class=sin-borde readonly style=text-align:left  size=20 maxlength=15>";
	      $la_object[$li_i][2] = "<a href=javascript:uf_catalogo_banco(".$li_i.")><img src=../shared/imagebank/tools15/buscar.gif title=Buscar Banco Liquidación  style=position:absolute border=0></a><input type=text     name=txtcodban".$li_i." id=txtcodban".$li_i." value='".$ls_codban."' title='".ltrim($ls_codban)."' class=sin-borde readonly style=text-align:left    size=40 maxlength=254>";
	      $la_object[$li_i][3] = "<a href=javascript:uf_catalogo_cuenta_banco(".$li_i.")><img src=../shared/imagebank/tools15/buscar.gif title=Buscar Cuenta Banco Liquidación style=position:absolute border=0></a><input type=text     name=txtctaban".$li_i." id=txtctaban".$li_i." value='".$ls_ctaban."' title='".ltrim($ls_ctaban)."' class=sin-borde readonly style=text-align:left    size=45 maxlength=254>";
	      $la_object[$li_i][4] = "<input type=text     name=txtmonsol".$li_i." id=txtmonsol".$li_i." value='".$ld_monsol."' class=sin-borde readonly style=text-align:right  size=18 maxlength=18>
							      <input type=hidden   name=txtfilnam".$li_i." id=txtfilnam".$li_i." value='".$ls_filnam."'>
								  <input type=hidden   name=hidcodban".$li_i." id=hidcodban".$li_i." value='".$ls_numban."'>
								  <input type=hidden   name=hidctaban".$li_i." id=hidctaban".$li_i." value='".$ls_ctanum."'>
								  <input type=hidden   name=hidnumsol".$li_i." id=hidnumsol".$li_i." value='".$ls_numsol."'>";		
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title>Cr&eacute;ditos - Programaci&oacute;n de Pagos</title>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo1 {color: #6699CC}
-->
</style></head>

<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="12" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
  <tr>
  <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>  </td>
  </tr>
  <tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="12" class="toolbar"></td>
  </tr>
  <tr>
    <td class="toolbar" width="20"><a href="javascript: uf_procesar_programacion();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Grabar" width="20" height="20" border="0" /></a></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="27"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="689">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php
require_once("../shared/class_folder/grid_param.php");
require_once("class_folder/sigesp_scb_c_progpago_creditos.php");
$io_scb  = new sigesp_scb_c_progpago_creditos("../");
$io_grid = new grid_param();

uf_limpiar_variables();
switch ($ls_operacion){
  case 'CARGAR_DT':
	uf_load_variables();
	$la_object = $io_scb->uf_load_detalles_desembolso($ls_rutfil.'/'.$ls_filnam,$li_totrows);
  break;
  case 'PROCESAR':
    uf_load_variables();
	uf_load_grid($li_totrows,$la_object);
	$lb_valido = $io_scb->uf_procesar_programacion($ls_rutfil.'/'.$ls_filnam,$li_totrows,$la_seguridad);
  break;
}
?>
<form id="sigesp_scb_p_progpago_creditos.php" name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scb->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scb);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="557" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4" class="titulo-ventana"><input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion ?>" />
      Cr&eacute;ditos - Programaci&oacute;n de Pagos 
      <input name="hidfilnam" type="hidden" id="hidfilnam" value="<?php echo $ls_filnam ?>" /></td>
    </tr>
    <tr>
      <td width="66">&nbsp;</td>
      <td width="107">&nbsp;</td>
      <td width="107">&nbsp;</td>
      <td width="85">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Nro Solicitud </td>
      <td height="22" colspan="2"><input name="txtnumsol" type="text" id="txtnumsol" value="<?php echo $ls_numsol ?>" size="20" maxlength="15" readonly style="text-align:center" /></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Beneficiario</td>
      <td height="22" colspan="3"><input name="txtcedben" type="text" id="txtcedben" value="<?php echo $ls_cedben ?>" size="15" maxlength="10" readonly style="text-align:center" /> <label>
        <input name="txtnomben" type="text" class="sin-borde" id="txtnomben" value="<?php echo $ls_nomben ?>" size="65" maxlength="254" readonly style="text-align:left" />
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Monto</td>
      <td height="22"><input name="txtmonsol" type="text" id="txtmonsol" value="<?php echo $ld_monsol ?>" size="20" readonly style="text-align:right"/></td>
      <td height="22" colspan="2" style="text-align:left">Fecha Programaci&oacute;n   <input name="txtfecliq" type="text" id="txtfecliq" style="text-align:center" value="<?php echo $ls_fecliq ?>" size="15" maxlength="10" readonly /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="right"><a href="javascript:uf_catalogo_solicitudes('<?php print $ls_rutfil ?>');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Cargar Solicitudes de Pago..." width="20" height="20" border="0" longdesc="Carga los documentos xml dispuestos a generar las Programaciones de Pago..." />Cargar Solicitudes</a></div></td>
    </tr>
    <tr>
      <td colspan="4"><div align="center">
        <input name="hidfilsel" type="hidden" id="hidfilsel" value="<?php echo $li_filsel ?>" />
        <?php $io_grid->make_gridScroll($li_totrows,$la_rowgri,$la_object,750,'Detalles Solicitud de Desembolso',"grid_solicitudes",100); ?>
        <input name="hidtotrow" type="hidden" id="hidtotrow" value="<?php echo $li_totrows ?>" />
      </div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
<script>
f = document.form1;
function uf_catalogo_solicitudes(as_rutfil){
	li_leer = f.leer.value;
	if (li_leer==1)
	   {
		 window.open("sigesp_scb_cat_solicitudes_desembolso.php?rutfil="+as_rutfil,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=400,left=50,top=50,location=no,resizable=no");
	   }
	else
	   {
		 alert("No tiene permiso para realizar esta operación !!!");
	   }
}

function uf_catalogo_banco(li_row){
  f.hidfilsel.value = li_row;
  window.open("sigesp_cat_bancos.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no,dependent=yes");
}

function uf_catalogo_cuenta_banco(li_row){
  f.hidfilsel.value = li_row;
  ls_desban = eval("f.txtcodban"+li_row+".title");
  ls_codban = ls_desban.substring(0,3);
  if (ls_codban!="")
	 {
	   ls_nomban = ls_desban.substring(6,254);
	   pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no,dependent=yes");
	 }
  else
	 {
	   alert("Seleccione el Banco !!!");   
	 }
}

function uf_procesar_programacion()
{
  lb_valido = true;
  li_totrows = f.hidtotrow.value;
  for (li_i=1;li_i<=li_totrows;li_i++)
      {
	    ls_codban = eval("f.txtcodban"+li_i+".value");
		ls_ctaban = eval("f.txtctaban"+li_i+".value");
		if (ls_codban=='' || ls_ctaban=='')
		   {
		     lb_valido = false;
			 alert("Debe asignar Banco y Cuenta Bancaria para todos los detalles !!!");
			 break;
		   }
	  }
  if (lb_valido)
     {
	   f.operacion.value = "PROCESAR";
	   f.action = "sigesp_scb_p_progpago_creditos.php";
	   f.submit();
	 }
}
</script>
</html>
