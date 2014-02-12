<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='index.php'";
	 print "</script>";		
   }
$ls_logusr = $_SESSION["la_logusr"];
require_once("class_folder/class_funciones_cxp.php");
$io_fun_cxp= new class_funciones_cxp();
$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_r_comp_ret_iva.php",$ls_permisos,$la_seguridad,$la_permisos);

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
<script language="javascript">
	if(document.all)
	{ 
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
.Estilo1 {font-size: 14px}
.Estilo2 {font-size: 11px}
.Estilo3 {font-size: 12px}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_openexcel();"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?php
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
	$ls_cheque    = $_POST["txtcheque"];
	$ls_orden     = $_POST["txtorden"];
	$ls_factura   = $_POST["txtfactura"];
	$ld_fecfac    = $_POST["txtfecfac"];
	$ls_comprobante= $_POST["txtcomp"];
 	$ls_probenf   = $_POST["txtprobenf"];
	$ls_nomprobenf= $_POST["txtnomprobenf"];
	$ls_rif       = $_POST["txtrif"];
	$ls_concepto  = $_POST["txtconcepto"];
	$ld_fecdes    = $_POST["txtfecdesde"];
 	$ld_fechas    = $_POST["txtfechasta"];
	$ls_periodo   = $_POST["txtperiodo"];
}
else
{
	$ls_operacion = "";	
	$ls_cheque    = "";
	$ls_orden     = "";
	$ls_factura   = "";
	$ld_fecfac    = "";
	$ls_comprobante= "";
 	$ls_probenf   = "";
	$ls_nomprobenf= "";
	$ls_rif       = "";
	$ls_concepto  = "";
	$ld_fecdes    = "";
 	$ld_fechas    = "";
	$ls_periodo   = "";
}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
</div>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="557" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="81"></td>
    </tr>
    <tr class="titulo-celdanew">
      <td height="22" colspan="5" align="center" class="titulo-celda Estilo2">Listado de Comprobantes de Retencion I.V.A </td>
    </tr>
    <tr class="sin-borde2">
      <td height="22" colspan="5" align="center"><div align="center"><span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
        <span class="Estilo3">Datos del Comprobante</span> </span></div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Num Comprob</div></td>
      <td height="22" colspan="4" align="center"><div align="left">
        <input name="txtcomp" type="text" id="txtcomp" style="text-align:center" value="<?php print $ls_comprobante; ?>" size="30" maxlength="25">
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Concepto</div></td>
      <td height="22" colspan="4" align="center"><div align="left">
          <input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_concepto; ?>" size="70">
      <a href="javascript:cat_conceptos();"></a></div></td>
    </tr>
    <tr>
      <td height="22" align="center"> <div align="right">Desde</div></td>
      <td width="147" height="22" align="center"><div align="left">
          <input name="txtfecdesde" type="text" id="txtfecdesde"  style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print $ld_fecdes; ?>" size="20" maxlength="10"  datepicker="true">
      </div></td>
      <td width="71" height="22" align="center"><div align="right">Hasta</div></td>
      <td width="256" height="22" colspan="2" align="center"><div align="left">
          <input name="txtfechasta" type="text" id="txtfechasta" style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print $ld_fechas; ?>" size="20" maxlength="10"  datepicker="true">
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Periodo </div></td>
      <td height="22" align="center"><div align="left">
          <input name="txtperiodo" type="text" id="txtperiodo"  style="text-align:center" value="<?php print $ls_periodo; ?>" size="20">
      </div></td>
      <td height="22" align="center"></td>
      <td height="22" colspan="2" align="center"><div align="left">
          <label></label>
      <a href="javascript:ue_search();"></td>
    </tr>
    <tr>
      <td height="13" align="center"><div align="right"></div></td>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr class="titulo-celdanew">
      <td height="13" colspan="5" align="center"><div align="right"></div></td>
    </tr>
    <tr class="sin-borde2">
      <td height="22" colspan="5" align="center"><div align="center"><span class="Estilo3">Datos del Proveedor</span></div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Proveedor
      </div></td>
      <td height="22" colspan="4" align="center"><div align="left"><a href="javascript:catalogo_cuentabanco();"></a>
        <input name="txtprobenf" type="text" id="txtprobenf"  style="text-align:center" value="<?php print $ls_probenf; ?>" size="70">
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Nombre</div></td>
      <td height="22" colspan="4" align="center"><div align="left">
        <input name="txtnomprobenf" type="text" id="txtnomprobenf"  style="text-align:center" value="<?php print $ls_nomprobenf; ?>" size="70">
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Rif</div></td>
      <td height="22" colspan="4" align="center"><div align="left">
        <input name="txtrif" type="text" id="txtrif"  style="text-align:center" value="<?php print $ls_rif; ?>" size="30">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="5" align="center">
      <p align="right"><a href="javascript:ue_search();"></a></p></td>
    </tr>
  </table>
 
</table>
</p>
<p align="center">  
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
			//alert(ls_long);


  //  return false; 
   }
//Catalogo de cuentas contables
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
	  li_imprimir  = f.ejecutar.value;
	  ls_numcom    = f.txtcomp.value;
	  ls_feccomdes = f.txtfecdesde.value;
	  ls_feccomhas = f.txtfechasta.value;
	  ls_perfiscal = f.txtperiodo.value;
	  ls_codsujret = f.txtprobenf.value;
	  ls_nomsujret = f.txtnomprobenf.value;
	  ls_rif       = f.txtrif.value;
	  ls_desope    = f.txtconcepto.value;
	  
	  if (li_imprimir=='1')
	  {
		pagina="reportes/sigesp_cxp_rpp_ret_iva.php?fecdesde="+ls_feccomdes+"&fechasta="+ls_feccomhas+"&comp="+ls_numcom+"&probenf="
		+ls_codsujret+"&nomprobenf="+ls_nomsujret+"&rif="+ls_rif+"&concepto="+ls_desope+"&periodo="+ls_perfiscal;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	  }
      else
	  {
	    alert("No tiene permiso para realizar esta operación !!!");
	  }
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>