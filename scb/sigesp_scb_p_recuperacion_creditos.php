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
$io_fun_scb->uf_load_seguridad("SCB","sigesp_scb_p_recuperacion_creditos.php",$ls_permisos,$la_seguridad,$la_permisos);
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}

function uf_limpiar_variables()
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_limpiar_variables.
	//		   Access: private
	//	  Description: Función que limpia todas las variables necesarias en la página
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 04/07/2007			Fecha Última Modificación : 07/07/2008. 
	////////////////////////////////////////////////////////////////////////////////////
    global $li_totrows,$ls_operacion,$la_rowgri,$la_object,$io_fun_scb,$ls_rutfil,$li_estciespg,$li_estciescg,$li_estciespi,$ls_fecmov;
 
    $li_totrows   = 1;	
	$ls_rutfil    = "../scc/cobranza/pendientes"; 
	$li_estciespg = $io_fun_scb->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
	if ($li_estciespi==1 || $li_estciescg==1)
	   {
	     $la_rowgri[1] = "<input name=chkall type=checkbox id=chkall value=1 class=sin-borde style=width:15px;height:15px onClick=javascript:uf_select_all(); disabled>";
	   }
	else
	   {
	     $la_rowgri[1] = "<input name=chkall type=checkbox id=chkall value=1 class=sin-borde style=width:15px;height:15px onClick=javascript:uf_select_all();>";
	   }
	$la_rowgri[2]  = "Documento";
	$la_rowgri[3]  = "Banco";
	$la_rowgri[4]  = "Cuenta Bancaria";
	$la_rowgri[5]  = "Operación";
	$ls_operacion  = $io_fun_scb->uf_obteneroperacion();
	$ls_fecmov     = date("d/m/Y");
	
	if ($ls_operacion=='NUEVO')
	   {
	     $la_object[$li_totrows][1] = "<input type=checkbox name=chk".$li_totrows."       id=chk".$li_totrows."       value=1  class=sin-borde disabled>";
	     $la_object[$li_totrows][2] = "<input type=text     name=txtnumdoc".$li_totrows." id=txtnumdoc".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=20 maxlength=15>";
	     $la_object[$li_totrows][3] = "<input type=text     name=txtcodban".$li_totrows." id=txtcodban".$li_totrows." value='' class=sin-borde readonly style=text-align:left    size=40 maxlength=254>";
	     $la_object[$li_totrows][4] = "<input type=text     name=txtctaban".$li_totrows." id=txtctaban".$li_totrows." value='' class=sin-borde readonly style=text-align:left    size=45 maxlength=254>";
	     $la_object[$li_totrows][5] = "<input type=text     name=txtcodope".$li_totrows." id=txtcodope".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=18 maxlength=18>
								       <input type=hidden   name=txtfilnam".$li_totrows." id=txtfilnam".$li_totrows." value=''>";		
       }
	if ($li_estciescg==1 || $li_estciespg==1 || $li_estciespi==1)
       {
	     require_once("../shared/class_folder/class_mensajes.php");
	     $io_msg = new class_mensajes();
	     $io_msg->message("Ya fué procesado el Cierre Presupuestario, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
         unset($io_msg);
       }
}

function uf_load_variables(&$la_object)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_variables
	//		   Access: private
	//	  Description: Función que carga todas las variables necesarias en la página
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 08/07/2007		   Fecha Última Modificación : 08/07/2007.
	//////////////////////////////////////////////////////////////////////////////
	
	global $li_totrows,$li_estciespg,$li_estciescg,$li_estciespi,$ls_fecmov;
	
	$li_totrows   = $_POST["hidtotrow"];
	$li_estciespg = $_POST["hidestciespg"];//Estatus del Cierre Presupuestario de Gasto.
	$li_estciespi = $_POST["hidestciespi"];//Estatus del Cierre Presupuestario de Ingresos.
    $li_estciescg = $_POST["hidestciescg"];//Estatus del Cierre Contable.
	$ls_fecmov    = $_POST["txtfecha"];//Fecha en la que pretende realizar el documento.
	
	for ($li_i=1;$li_i<=$li_totrows;$li_i++)
	    {
		  $ls_numdoc = $_POST["txtnumdoc".$li_i];
		  $ls_codban = $_POST["txtcodban".$li_i];
		  $ls_ctaban = $_POST["txtctaban".$li_i];
		  $ls_codope = $_POST["txtcodope".$li_i];
		  $ls_filnam = $_POST["txtfilnam".$li_i];
		  
		  $la_object[$li_totrows][1] = "<input type=checkbox name=chk".$li_totrows."       id=chk".$li_totrows."       value=1                class=sin-borde>";
	      $la_object[$li_totrows][2] = "<input type=text     name=txtnumdoc".$li_totrows." id=txtnumdoc".$li_totrows." value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center  size=20 maxlength=15>";
	      $la_object[$li_totrows][3] = "<input type=text     name=txtcodban".$li_totrows." id=txtcodban".$li_totrows." value='".$ls_codban."' class=sin-borde readonly style=text-align:left    size=40 maxlength=254>";
	      $la_object[$li_totrows][4] = "<input type=text     name=txtctaban".$li_totrows." id=txtctaban".$li_totrows." value='".$ls_ctaban."' class=sin-borde readonly style=text-align:left    size=45 maxlength=254>";
	      $la_object[$li_totrows][5] = "<input type=text     name=txtcodope".$li_totrows." id=txtcodope".$li_totrows." value='".$ls_codope."' class=sin-borde readonly style=text-align:center  size=18 maxlength=18>
							            <input type=hidden   name=txtfilnam".$li_totrows." id=txtfilnam".$li_totrows." value='".$ls_filnam."'>";		
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title>Cobranza - Recuperaci&oacute;n de Cr&eacute;ditos</title>
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
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
  <tr>
  <td width="780" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" width="780" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar_cobranza();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif"                 alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="705">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php
require_once("../shared/class_folder/grid_param.php");
require_once("class_folder/sigesp_scb_c_recuperacion_creditos.php");
$io_scb  = new sigesp_scb_c_recuperacion_creditos("../");
$io_grid = new grid_param();

uf_limpiar_variables();
switch ($ls_operacion){
  case 'CARGAR':
	$la_object = $io_scb->uf_load_movimientos_bancarios($ls_rutfil,$li_totrows);
  break;
  case 'PROCESAR':
    uf_load_variables($la_object);
	$lb_valido = $io_scb->uf_procesar_cobranza($ls_rutfil,$li_totrows,$la_seguridad);
	$la_object = $io_scb->uf_load_movimientos_bancarios($ls_rutfil,$li_totrows);
  break;
}
?>
<form id="sigesp_scb_p_recuperacion_creditos.php" name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scb->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scb);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="337" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4" class="titulo-ventana"><input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>" />
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>" />
      <input name="hidestciescg" type="hidden" id="hidestciescg" value="<?php echo $li_estciescg; ?>" />
      <input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion ?>" />
      Cobranza - Recuperaci&oacute;n de Cr&eacute;ditos
      <input name="txtfecha" type="hidden" id="txtfecha" value="<?php print $ls_fecmov; ?>" />
      <input name="hidmesabi" type="hidden" id="hidmesabi" value="true" /></td>
    </tr>
      <script language="javascript">uf_validar_estatus_mes();</script>
	<tr>
      <td width="70">&nbsp;</td>
      <td width="73">&nbsp;</td>
      <td width="77">&nbsp;</td>
      <td width="115">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="right"><a href="javascript:uf_load_movimientos_bancarios();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Cargar Documentos..." width="20" height="20" border="0" longdesc="Carga los documentos xml dispuestos a generar los Movimientos Bancarios..." />Cargar Documentos</a></div></td>
    </tr>
    <tr>
      <td colspan="4"><div align="center">
        <?php $io_grid->make_gridScroll($li_totrows,$la_rowgri,$la_object,762,'Cobranza',"grid_cobranza",100); ?>
        <input name="hidtotrow" type="hidden" id="hidtotrow" value="<?php echo $li_totrows ?>" />
      </div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
<script>
f = document.form1;
function uf_load_movimientos_bancarios()
{
  if (uf_evaluate_cierre('SPI'))
     {
	   li_leer = f.leer.value;
	   if (li_leer==1)
		  {
		    f.action = "sigesp_scb_p_recuperacion_creditos.php";
		    f.operacion.value = "CARGAR";
		    f.submit();	 
		  }
	   else
		  {
		    alert("No tiene permiso para realizar esta operación !!!");
		  }
     }  
}

function uf_select_all()
{
  if (uf_evaluate_cierre('SPI'))
     {
	   if (eval("f.chk1.disabled==false"))
		  {
		    li_totrows = f.hidtotrow.value;
		    if (f.chkall.checked)
			   {
			    lb_checked = true;
			   } 
		    else
			   {
				 lb_checked = false;
			   }
		   for (i=1;i<=li_totrows;i++)	
			   {
				 eval("f.chk"+i+".checked="+lb_checked);
			   }	 
		  }
     }
}

function ue_procesar_cobranza()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPI'))
		 {
		   li_ejecutar = f.ejecutar.value;
		   if (li_ejecutar==1)
			  {
				f.operacion.value = "PROCESAR";
				f.action = "sigesp_scb_p_recuperacion_creditos.php";
				f.submit();
			  }
		   else
			  {
				alert("No tiene permiso para realizar esta operación !!!");
			  }
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function uf_evaluate_cierre(as_tipafe)
{
  lb_valido = true;
  if (as_tipafe=='SPG' || as_tipafe=='SPI')
     {
       li_estciespg = f.hidestciespg.value;
       li_estciespi = f.hidestciespi.value;
	   if (li_estciespg==1 || li_estciespi==1)
		  {
		    lb_valido = false;
		    alert("Ya fué procesado el Cierre Presupuestario, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
		  }	   
	 }
  else
     {
	   if (as_tipafe=='SCG')
	      {
  		    li_estciescg = f.hidestciescg.value;
			if (li_estciescg==1)
			   {
			     lb_valido = false;
			     alert("Ya fué procesado el Cierre Contable, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
			   }
		  }
	 }
  return lb_valido
}
</script>
</html>
