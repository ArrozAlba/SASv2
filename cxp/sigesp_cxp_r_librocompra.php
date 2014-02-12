<?php
	session_start();
	if (!array_key_exists("la_logusr",$_SESSION))
    {
	 	print "<script language=JavaScript>";
	 	print "location.href='sigesp_inicio_sesion.php'";
		print "</script>";		
    }
    $ls_logusr=$_SESSION["la_logusr"];
   	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_r_librocompra.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_cxp->uf_select_config("CXP","REPORTE","FORMATO_LIBRO_COMPRA","sigesp_cxp_rpp_librocompra.php","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Libro de Compras</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<?php
require_once("../shared/class_folder/ddlb_meses.php");
$ddlb_mes   = new ddlb_meses();
/*if (array_key_exists("txtcodigo1",$_POST))
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
	}  */
		
$arr_date = getdate();
$ls_ano   = $arr_date['year'];
$ls_mes   = $arr_date['mon'];
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
 <tr>
   <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="a" width="804" height="40"></td>
 </tr>
 <tr>
   <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	 <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar</td>
<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("d/m/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
				  <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
				  <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	    </table></td>
 </tr>
 <tr>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
 </tr>
 <tr>
   <td width="780" height="13" colspan="11" class="toolbar"></td>
 </tr>
 <tr>
   <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: uf_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
   <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"></a><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" title="Excel" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
   <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
   <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
   <td class="toolbar" width="25">&nbsp;</td>
   <td class="toolbar" width="25"><div align="center"></div></td>
   <td class="toolbar" width="25"><div align="center"></div></td>
   <td class="toolbar" width="25"><div align="center"></div></td>
   <td class="toolbar" width="25"><div align="center"></div></td>
   <td class="toolbar" width="25"><div align="center"></div></td>
   <td class="toolbar" width="530">&nbsp;</td>
 </tr>
</table>
   <p>&nbsp;</p>
<div align="center">
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
	 <table width="426" border="0" cellpadding="0" cellspacing="0" class="contorno">
	   <tr class="titulo-celdanew">
		 <td width="424" height="22" class="titulo-celdanew">Libro de Compras</td>
	   </tr>
	   <tr style="visibility:hidden">
		 <td height="22">Reporte en
           <select name="cmbbsf" id="cmbbsf">
             <option value="0" selected>Bs.</option>
             <option value="1">Bs.F.</option>
           </select></td>
	   </tr>
	   <tr>
		 <td height="22"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
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
	 <p>
	   <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">
	 </p>
</form>            
</div>
</body>
<script language="JavaScript">
function uf_imprimir()
{
	f = document.formulario;
	li_imprimir = f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_mes = f.mes.value;
		ls_agno = f.ano.value;
		tiporeporte=f.cmbbsf.value;
		formato=f.formato.value;
		pagina = "reportes/"+formato+"?mes="+ls_mes+"&agno="+ls_agno+"&tiporeporte="+tiporeporte;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	}
	else
	{alert("No tiene permiso para realizar esta operación");}
}

function ue_openexcel()
{
	f = document.formulario;
	li_imprimir = f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_mes = f.mes.value;
		ls_agno = f.ano.value;		
		formato=f.formato.value;
		pagina = "reportes/sigesp_cxp_rpp_librocompra_excel.php?mes="+ls_mes+"&agno="+ls_agno;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script>
</html>
