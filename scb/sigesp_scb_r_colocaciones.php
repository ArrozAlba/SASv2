<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Estado de Cuenta Colocaciones</title>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?Php
require_once("../shared/class_folder/grid_param.php");
$io_grid=new grid_param();

require_once("../shared/class_folder/sigesp_include.php");
$sig_inc=new sigesp_include();
$con=$sig_inc->uf_conectar();



$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ld_fecha=$_POST["txtfecha"];
	$ls_codban=$_POST["txtcodban"];;
	$ls_denban=$_POST["txtnomban"];
	$ls_cuenta_banco=$_POST["txtcuenta"];
	$ls_dencuenta_banco=$_POST["txtdenctaban"];
	$ld_fecdesde=$_POST["txtfecdesde"];
	$ld_fechasta=$_POST["txtfechasta"];
}
else
{
	$ls_operacion="";	
	$ld_fecha=date("d/m/Y");
	$ls_codban="";
	$ls_denban="";
	$ls_cuenta_banco="";
	$ls_dencuenta_banco="";
	$ld_fecdesde=$ld_fecha;
	$ld_fechasta=$ld_fecha;
}


	

?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  
  <table width="535" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="57"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="13" colspan="4" align="center">Estado de Cuenta Colocaciones </td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Banco</div></td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" size="10" readonly>
        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
        <input name="txtdenban" type="text" id="txtdenban" size="51" class="sin-borde" readonly>
</div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Cuenta</div></td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="48" maxlength="254" readonly>
          <input name="txttipocuenta" type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
          <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>
          <input name="txtdisponible" type="hidden" id="txtdisponible" style="text-align:right" size="24" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Desde</div></td>
      <td width="151" height="22" align="center"><div align="left">
        <input name="txtfecdesde" type="text" id="txtfecdesde"  style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print $ld_fecdesde?>" size="24" maxlength="10"  datepicker="true">
      </div></td>
      <td width="80" align="center"><div align="right">Hasta</div></td>
      <td width="245" align="center"><div align="left">
        <input name="txtfechasta" type="text" id="txtfechasta" style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print $ld_fechasta?>"  datepicker="true">
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Ordenar </div>        <div align="left"></div></td>
      <td align="center"><div align="left">
        <select name="orden">
          <option value="D">Documento</option>
          <option value="F">Fecha</option>
          <option value="O">Operacion</option>
        </select>
</div></td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><div align="right">     <span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></div></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
        <p>&nbsp;</p>        </td>

    </tr>
  </table>
 
</table>
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_search()
{
  f=document.form1;
  ld_fecdesde= f.txtfecdesde.value;
  ld_fechasta= f.txtfechasta.value;
  ls_codban  = f.txtcodban.value;
  ls_ctaban  = f.txtcuenta.value;
  ls_orden=f.orden.value;
  if((ld_fecdesde!="")&&(ld_fechasta!="")&&(ls_codban!="")&&(ls_ctaban!=""))
  {
	//  ls_orden=f.orden.value; 
	   pagina="reportes/sigesp_scb_rpp_colocaciones_pdf.php?fecdes="+ld_fecdesde+"&fechas="+ld_fechasta+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&orden="+ls_orden;
	   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
   }
   else
   {
   	   alert("Seleccion los parametros de busqueda");
   }
}


function uf_catalogoprov()
{
    f=document.form1;
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
	f=document.form1;
			 
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
	   ls_denban=f.txtdenban.value;
	  	   if((ls_codban!=""))
		   {
			   pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&denban="+ls_denban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Seleccione el Banco");   
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
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
