<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr = $_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_documentos.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte   = $io_fun_banco->uf_select_config("SCB","REPORTE","LISTADO_MOVBCO","sigesp_scb_rpp_documentos_pdf.php","C");
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Documentos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../scb/js/ajax.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/sigesp_cat_ordenar.js"></script>
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
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
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
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" title="Excel" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?Php
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");

$io_grid    = new grid_param();
$io_conect  = new sigesp_include();
$con        = $io_conect->uf_conectar();
$io_sql     = new class_sql($con);
$io_msg     = new class_mensajes();
$io_funcion = new class_funciones();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion = $_POST["operacion"];
	$ls_codban    = $_POST["txtcodban"];;
	$ls_denban    = $_POST["txtdenban"];
	$ls_ctaban    = $_POST["txtcuenta"];
	$ls_denctaban = $_POST["txtdenominacion"];
	$ld_fecdes    = $_POST["txtfecdesde"];
	$ld_fechas    = $_POST["txtfechasta"];
    $ls_codope    = $_POST["cmboperacion"];
    $ls_conmov    = $_POST["txtconcepto"];
    $ls_estmov    = $_POST["cmbestmov"];
    $ls_orden     = $_POST["hidorden"];
    $li_totrows   = $_POST["hidtotrows"];
}
else
{
	$ls_operacion = "";	
	$ld_fecha     = date("d/m/Y");
	$ls_codban    = "";
	$ls_denban    = "";
	$ls_ctaban    = "";
	$ls_denctaban = "";
	$ld_fecdes    = $ld_fecha;
	$ld_fechas    = $ld_fecha;
    $ls_codope    = "T";
    $ls_conmov    = ""; 
    $ls_estmov    = "-";
    $ls_orden     = " M.numdoc ASC";
    $li_totrows   = 0;
}


function uf_load_documentos($as_codemp,$as_codban,$as_ctaban,$as_codope,$as_conmov,$as_fecdes,$as_fechas,$as_estmov,$as_orden,&$lb_valido)
{
  global $io_sql;
  global $io_funcion;
  
  $lb_valido = true;
  $ls_straux = "";
  $as_fecdes = $io_funcion->uf_convertirdatetobd($as_fecdes);
  $as_fechas = $io_funcion->uf_convertirdatetobd($as_fechas);
  
  if (!empty($as_codope) && $as_codope!='T')
  {
    $ls_straux = $ls_straux." AND M.codope='".$as_codope."'";
  }
  if (!empty($as_conmov))
  {
    $ls_straux = $ls_straux." AND M.conmov like '%".$as_conmov."%'";
  }
  if (!empty($as_fecdes) && !empty($as_fechas))
  {
    $ls_straux = $ls_straux." AND M.fecmov BETWEEN '".$as_fecdes."' AND '".$as_fechas."'";
  }
  if (!empty($as_estmov) && $as_estmov!='-')
  {
    $ls_straux = $ls_straux." AND M.estmov = '".$as_estmov."'";
  }
  
  if (empty($as_orden))
     {
	   $as_orden = " M.numdoc ASC";
	 }
  $ls_sql  = "SELECT M.codemp,M.codban,M.ctaban,M.numdoc,M.codope,M.estmov,M.cod_pro,M.ced_bene,M.tipo_destino,               ".
             "       M.nomproben as nombre,M.conmov,(M.monto - M.monret) as monto,M.fecmov,B.nomban as banco                  ".
             "  FROM scb_movbco M,scb_banco B, scb_ctabanco C                                                                 ".
             " WHERE M.codemp='".$as_codemp."' AND M.codban='".$as_codban."' AND M.ctaban='".$as_ctaban."' AND M.codope<>'OP' ".
			 "       $ls_straux AND M.codban=B.codban AND M.codemp=B.codemp AND M.codban=C.codban AND M.ctaban=C.ctaban       ".
			 " ORDER BY $as_orden ";//print $ls_sql;
  $rs_data = $io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
     }
  return $rs_data;
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
</div>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="535" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="62"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="4" align="center">Listado de Documentos </td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr style="visibility:hidden">
      <td height="13" colspan="4" style="text-align:left">Reporte en
        <select name="cmbbsf" id="cmbbsf">
          <option value="0" selected>Bs.</option>
          <option value="1">Bs.F.</option>
        </select>
        <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Banco</td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban ?>" size="10" readonly>
        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
        <input name="txtdenban" type="text" class="sin-borde" id="txtdenban" value="<?php print $ls_denban ?>" size="60" readonly>
        <span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
        </span></div></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta</td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_ctaban ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_denctaban ?>" size="45" maxlength="254" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Operaci&oacute;n</td>
      <td height="22" colspan="3" align="center"><div align="left">
          <?php 
		  $ls_selnc = "";
		  $ls_selnd = "";
		  $ls_selch = "";
		  $ls_selre = "";
		  $ls_selde = "";
		  switch ($ls_codope){
		    case 'NC':
			  $ls_selnc = "selected";
			break;
		    case 'ND':
			  $ls_selnd = "selected";
			break;
			case 'CH':
			  $ls_selch = "selected";
			break;
		    case 'RE':
			  $ls_selre = "selected";
			break;
		    case 'DE':
			  $ls_selde = "selected";
			break;
		  }
		?>
		  <select name="cmboperacion" id="cmboperacion">
            <option value="T">Todos</option>
            <option value="NC" <?php print $ls_selnc ?>>Notas de Cr&eacute;dito</option>
            <option value="ND" <?php print $ls_selnd ?>>Notas de D&eacute;bito</option>
            <option value="CH" <?php print $ls_selch ?>>Cheques</option>
            <option value="RE" <?php print $ls_selre ?>>Ret&iacute;ro</option>
            <option value="DP" <?php print $ls_selde ?>>Dep&oacute;sito</option>
          </select>
          <input name="txttipocuenta" type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
          <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg">
          <input name="txtdisponible" type="hidden" id="txtdisponible">
          <input type="hidden" name="hidorden" id="hidorden" value="<?php print $ls_orden?>"/>
     </div></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Concepto</td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtcodconcep" type="hidden" id="txtcodconcep">
        <input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_conmov ?>" size="66">
        <a href="javascript:cat_conceptos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a></div></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Desde</td>
      <td width="146" height="22" align="center"><div align="left">
        <input name="txtfecdesde" type="text" id="txtfecdesde"  style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print $ld_fecdes ?>" size="20" maxlength="10"  datepicker="true">
      </div></td>
      <td width="80" height="22" style="text-align:right">Hasta</td>
      <td width="245" height="22" align="center"><div align="left">
        <input name="txtfechasta" type="text" id="txtfechasta" style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print $ld_fechas ?>" size="20" maxlength="10"  datepicker="true">
      </div></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Estatus</td>
      <td height="22" align="center"><div align="left">
		<?php 
		  $ls_selcon = "";
		  $ls_selanu = "";
		  $ls_selori = "";
		  $ls_selnoc = "";
		  $ls_selsin = "";
		  switch ($ls_estmov){
		    case 'C':
			  $ls_selcon = "selected";
			break;
		    case 'A':
			  $ls_selanu = "selected";
			break;
			case 'O':
			  $ls_selori = "selected";
			break;
		    case 'N':
			  $ls_selnoc = "selected";
			break;
		    case 'L':
			  $ls_selsin = "selected";
			break;
		  }
		?>
		<select name="cmbestmov" id="cmbestmov">
          <option value="-">---seleccione---</option>
          <option value="C" <?php print $ls_selcon ?>>Contabilizado</option>
          <option value="A" <?php print $ls_selanu ?>>Anulado</option>
          <option value="O" <?php print $ls_selori ?>>Original</option>
          <option value="N" <?php print $ls_selnoc ?>>No Contabilizado</option>
          <option value="L" <?php print $ls_selsin ?>>Sin Registro</option>
        </select>
      </div></td>
      <td height="22" align="center">&nbsp;</td>
      <td height="22" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center">
      <p align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></p></td>
    </tr>
  </table>
 
</table>
</p>
<p align="center">
  <?php
if ($ls_operacion=="BUSCAR")
   {
	 $lb_valido = true;
	 $li_fila   = 0;
	 $rs_data   = uf_load_documentos($ls_codemp,$ls_codban,$ls_ctaban,$ls_codope,$ls_conmov,$ld_fecdes,$ld_fechas,$ls_estmov,$ls_orden,&$lb_valido);
	 if ($lb_valido)
	    {
	      $title[1] = "<a href=javascript:ue_ordenar('M.numdoc');><font color=#FFFFFF>Documento</font></a>"; 
		  $title[2] = "<a href=javascript:ue_ordenar('M.codope');><font color=#FFFFFF>Operacion</font>"; 
		  $title[3] = "<a href=javascript:ue_ordenar('nombre');><font color=#FFFFFF>Beneficiario</font></a>"; 
		  $title[4] = "<a href=javascript:ue_ordenar('M.fecmov');><font color=#FFFFFF>Fecha</font></a>"; 
		  $title[5] = "<font color=#FFFFFF>Monto</font>"; 
		  $ls_grid  = "grid_documentos";
		  $li_totrows = $io_sql->num_rows($rs_data);
		  if ($li_totrows>0)
		     {
		       while ($row=$io_sql->fetch_row($rs_data))
		             {
				       $li_fila++;
					   $ls_codemp = trim($row["codemp"]);
			           $ls_numdoc = trim($row["numdoc"]);
				   	   $ls_codope = $row["codope"];
				       $ls_estdoc = $row["estmov"];
				       $ls_codpro = $row["cod_pro"];
				       $ls_cedben = $row["ced_bene"];
				       $ls_tipdes = $row["tipo_destino"];
				       $ls_nombre = $row["nombre"];
				       $ls_conmov = $row["conmov"];
				       $ld_monmov = $row["monto"];
				       $ls_fecmov = $io_funcion->uf_formatovalidofecha($row["fecmov"]);
					   $ls_fecmov = $io_funcion->uf_convertirfecmostrar($ls_fecmov);
		               $object[$li_fila][1]="<input type=text      id=txtnumdoc".$li_fila."  name=txtnumdoc".$li_fila."  value='".$ls_numdoc."'  class=sin-borde  size=15  style=text-align:center readonly>";
					   $object[$li_fila][2]="<input type=text      id=txttipope".$li_fila."  name=txttipope".$li_fila."  value='".$ls_codope."'  class=sin-borde  size=5   style=text-align:center readonly>";
					   $object[$li_fila][3]="<input type=text      id=txtnombre".$li_fila."  name=txtnombre".$li_fila."  value='".$ls_nombre."'  class=sin-borde  size=40  style=text-align:left   readonly>"; 
					   $object[$li_fila][4]="<input type=text      id=txtfecha".$li_fila."   name=txtfecha".$li_fila."   value='".$ls_fecmov."'  class=sin-borde  size=8   style=text-align:center readonly>";
		               $object[$li_fila][5]="<input type=text      id=txtmonto".$li_fila."   name=txtmonto".$li_fila."   value='".number_format($ld_monmov,2,',','.')."'  class=sin-borde  size=20  style=text-align:right   readonly>";
				     }
		       $io_grid->make_gridScroll($li_fila,$title,$object,570,'Listado de Documentos',$ls_grid,200);
		  }
          else
	      {
	        $io_msg->message("No se han encontrado Documentos para este Criterio de Búsqueda !!!");
		  }
        }
   }    
?>
</p>
<div align="center">
  <input name="hidtotrows" type="hidden" id="hidtotrows" value="<?php print $li_totrows ?>">
</div>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f = document.form1;

function uf_catalogoprov()
{
    f.operacion.value="BUSCAR";
    pagina="sigesp_catdin_prove.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	if (cadena!="")
	{
		for (i=1;i<=total;i++)
		{
		   cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if (objeto=="txtcodprov1")
		{
		    document.form1.txtcodprov1.value=cadena;
		}
		else
		{
			document.form1.txtcodprov2.value=cadena;
		}  
     }
}

 function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);

			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
   }

	function catalogo_cuentabanco()
	 {
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_nomban=f.txtdenban.value;
	  	   if((ls_codban!=""))
		   {
			   pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=730,height=400,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Debe Seleccionar un Banco !!!");   
		   }
	  
	 }	
	 	 
	 function cat_bancos()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }

	function cat_conceptos()
	{
	   f=document.form1;
	   ls_codope=f.cmboperacion.value;
	   pagina="sigesp_cat_conceptos.php?codope="+ls_codope;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	}

function ue_search()
{
  li_leer = f.leer.value;
  if (li_leer==1)
	 {
       ls_codban = f.txtcodban.value;
       ls_ctaban = f.txtcuenta.value;
	   if (ls_codban=="" || ls_ctaban=="")
	      {
		    alert("Debe establecer el Código del Banco y Número de Cuenta Bancaria para realizar la Búsqueda !!!");  
		  }
       else
	      {
            f.operacion.value = "BUSCAR";
            f.action          = "sigesp_scb_r_documentos.php";
			f.submit();
		  }
	}
  else
	{
	  alert("No tiene permiso para realizar esta operación !!!");
	}
}

function ue_imprimir()
{
  li_imprimir 	 = f.ejecutar.value;
  ld_fecdesde 	 = f.txtfecdesde.value;
  ld_fechasta 	 = f.txtfechasta.value;
  ls_codope   	 = f.cmboperacion.value;
  ls_codban   	 = f.txtcodban.value;
  ls_nomban   	 = f.txtdenban.value;
  ls_ctaban   	 = f.txtcuenta.value;
  ls_concepto 	 = f.txtcodconcep.value;
  ls_orden    	 = f.hidorden.value;
  ls_estmov   	 = f.cmbestmov.value;
  ls_tiporeporte = f.cmbbsf.value;
  ls_reporte     = f.formato.value; 
  if (li_imprimir=='1')
     {
  	   ls_codban = f.txtcodban.value;
       ls_ctaban = f.txtcuenta.value;
	   if (ls_codban=="" || ls_ctaban=="" || ls_nomban=="")
	      {
		    alert("Debe establecer el Código del Banco y Número de Cuenta Bancaria para realizar la Búsqueda !!!");  
		  }
       else
	      {
 	        pagina="reportes/"+ls_reporte+"?fecdes="+ld_fecdesde+"&fechas="+ld_fechasta+"&codope="+ls_codope+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&codconcep="+ls_concepto+"&orden="+ls_orden+"&hidestmov="+ls_estmov+"&nomban="+ls_nomban+"&tiporeporte="+ls_tiporeporte;
	        window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		  }  
	 }
  else
	 {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}

function ue_openexcel()
{
  li_imprimir 	 = f.ejecutar.value;
  ld_fecdesde 	 = f.txtfecdesde.value;
  ld_fechasta 	 = f.txtfechasta.value;
  ls_codope      = f.cmboperacion.value;
  ls_codban      = f.txtcodban.value;
  ls_nomban      = f.txtdenban.value;
  ls_ctaban      = f.txtcuenta.value;
  ls_concepto    = f.txtcodconcep.value;
  ls_orden       = f.hidorden.value;
  ls_estmov      = f.cmbestmov.value;
  ls_tiporeporte = f.cmbbsf.value;
  if (li_imprimir=='1')
     {
  	   ls_codban = f.txtcodban.value;
       ls_ctaban = f.txtcuenta.value;
	   if (ls_codban=="" || ls_ctaban=="" || ls_nomban=="")
	      {
		    alert("Debe establecer el Código del Banco y Número de Cuenta Bancaria para realizar la Búsqueda !!!");  
		  }
       else
	      {
 	        pagina="reportes/sigesp_scb_rpp_documentos_excel.php?fecdes="+ld_fecdesde+"&fechas="+ld_fechasta+"&codope="+ls_codope+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&codconcep="+ls_concepto+"&orden="+ls_orden+"&hidestmov="+ls_estmov+"&nomban="+ls_nomban+"&tiporeporte="+ls_tiporeporte;
	        window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		  }  
	 }
  else
	 {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}	
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>