<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Libro de Ventas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<?php
require_once("../shared/class_folder/ddlb_meses.php");
$ddlb_mes   = new ddlb_meses();
if (array_key_exists("txtcodigo1",$_POST))
   {
     $ls_codprov1=$_POST["txtcodigo1"];	   
   }
else
   {
     $ls_codprov1="";
   }
if (array_key_exists("txtcodigo2",$_POST)) 
   {  
     $ls_codprov2 =$_POST["txtcodigo2"];	  
   }
else
   {
     $ls_codprov2="";
  }
if  (array_key_exists("radiocategoria",$_POST))
	{
	  $ls_tipo=$_POST["radiocategoria"];
	}
else
	{
	  $ls_tipo="P";
	}			
if	(array_key_exists("txtfechadesde",$_POST))
	{
	  $ls_fechadesde=$_POST["txtfechadesde"];
    }
else
	{
	  $ls_fechadesde="";
	}  
if	(array_key_exists("txtfechahasta",$_POST))
	{
	  $ls_fechahasta=$_POST["txtfechahasta"];
    }
else
	{
	  $ls_fechahasta="";
	}  	
if	(array_key_exists("txtnumrecdoc",$_POST))
	{
	  $ls_numrecdoc=$_POST["txtnumrecdoc"];
    }
else
	{
	  $ls_numrecdoc="";
	} 
if	(array_key_exists("totnum",$_POST))
	{
	  $li_total=$_POST["totnum"];
    }
else
	{
	  $li_total=0;
	}  
		
$arr_date = getdate();
$ls_ano   = $arr_date['year'];
$ls_mes   = $arr_date['mon'];
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="499" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="279" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_showouput();">
	<img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a>
	 </a><a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" width="20" height="20" border="0"></a>
	<img src="../shared/imagebank/tools20/ayuda.gif" alt="Eliminar" width="20" height="20" border="0"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
           <p>&nbsp;</p>
           <div align="center"></div>
<div align="center">
           <form name="form1" method="post" action="">
             <table width="496" border="0" cellpadding="0" cellspacing="0" class="contorno">
               <tr class="titulo-celdanew">
                 <td width="494" height="22" class="titulo-celdanew">Libro de Ventas</td>
               </tr>
               <tr>
                 <td height="22">&nbsp;</td>
               </tr>
               <tr>
                 <td height="22"><table width="465" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                   <tr>
                     <td colspan="4" style="text-align:left"><strong>Rango de Fechas </strong></td>
                   </tr>
                   <tr>
                     <td width="78" align="right">Mes</td>
                     <td width="134"><div align="left">
                       <?php $ddlb_mes->sel_mes($ls_mes); //Combo que contiene los meses del a&ntilde;o y retorna selecciona el que el ususario tenga acutalmente ?>
                     </div></td>
                     <td width="74" align="right">A&ntilde;o</td>
                     <td width="127"><div align="left">
                       <?php $ddlb_mes->sel_ano($ls_ano); //Combo que contiene los meses del a&ntilde;o y retorna selecciona el que el ususario tenga acutalmente ?>
                     </div></td>
                   </tr>
                 </table></td>
               </tr>
               <tr>
                 <td height="22">&nbsp;</td>
               </tr>
               <tr>
                 <td height="13"><div align="right"></div></td>
               </tr>
             </table>
             <p>&nbsp;</p>
             <p>
               <?php

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");



$io_in      = new sigesp_include();
$con        = $io_in->uf_conectar();
$io_ds      = new class_datastore();
$io_sql     = new class_sql($con);
$io_msg     = new class_mensajes();
$io_funcion = new class_funciones(); 
$la_emp     = $_SESSION["la_empresa"];
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
   }
else
   {
	 $ls_operacion="";	
   }


	  ?>
</body>
<script language="JavaScript">
function ue_showouput()
{
	f      = document.form1;
    ls_mes = f.mes.value;
	ls_ano = f.ano.value;
	pagina = "reportes/sigesp_sfc_rep_libroventa.php?hidmes="+ls_mes+"&hidano="+ls_ano;
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
}


function ue_openexcel()
{
	f=document.form1;
	
		ls_mes = f.mes.value;
		ls_ano = f.ano.value;
		//ls_mes=f.cmbmes.value;
		//ls_agno=f.txtperiodo.value;
		ls_periodo="01/"+ls_mes+"/"+ls_ano;
		
			pagina="reportes/sigesp_sfc_rep_libroventa_excel.php?hidmes="+ls_mes+"&hidano="+ls_ano;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
   		
}



</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>