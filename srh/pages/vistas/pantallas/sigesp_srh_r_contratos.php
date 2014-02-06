<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_r_contratos.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte Contrato de Personal</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo1 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>



</head>

<body >
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Recursos Humanos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
 <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  
  <tr>
   <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_print();"><img src="../../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
   <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_print_word();"><img src="../../../../shared/imagebank/tools20/word.JPG" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
   <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php

	

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		}

	
	
	//
?>

<p>&nbsp;</p>
<div align="center">
  
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Contrato de Personal</td>
        </tr>
		
        <tr>
          <td height="20" colspan="6" class="titulo-celdanew">Configuraci&oacute;n del Contrato </td>
          </tr>
        <tr>
          <td width="86" height="22"><div align="right"> C&oacute;digo </div></td>
          <td colspan="3"><div align="left"><input name="txtcodcont" type="text" id="txtcodcont"   size="5"   style="text-align:center" readonly ><a href="javascript:catalogo_defcontrato();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a> 
            <input name="txtdescont" type="text" id="txtdescont" size="60" readonly class="sin-borde"> </div>
			<input name="txtnomrtf" type="hidden" size="40" id="txtnomrtf"></td>
          </tr>
        <tr>
          <td height="22" colspan="6" class="titulo-celdanew">Intervalo Registro de Contrato</td>
          </tr>
         <tr>
          <td width="86" height="22"><div align="right"> Desde </div></td>
          <td width="176"><div align="left">
            <input name="txtnroregdes" type="text" id="txtnroregdes"   size="16"   style="text-align:center" readonly >
		    <a href="javascript:catalogo_contrato_desde();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip" /></a></td>
          <td width="197"><div align="left">Hasta 
            <input name="txtnroreghas" type="text" id="txtnroreghas"   size="16"   style="text-align:center" readonly >
            <a href="javascript:catalogo_contrato_hasta();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
          </div></td>
          <td width="83" colspan="3"><div align="left"></div></td>
        </tr>
       
      
       
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="4"> <div align="right"></div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</html>


<script language="javascript">

function ue_print()
{
	f=document.form1;
	codcont=f.txtcodcont.value;
    nroregdes=f.txtnroregdes.value;
	nroreghas=f.txtnroreghas.value;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		if (codcont!="")
		{
			if (nroregdes <= nroreghas)
			{
			
				 pagina="../../../reporte/sigesp_srh_rpp_contratos.php?codcont="+codcont+"&nroregdes="+nroregdes+"&nroreghas="+nroreghas+"";
				 window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");	
			}
			else
			{
				alert ('El rango de registros de Contrato está erroneo');
			}
		}
		else
		{
			alert ('Debe llenar la configuración del contrato para generar el reporte');
		}
   } //fin del if imprimir
   else		
   {
	 alert("No tiene permiso para realizar esta operación");
   }
	
} 

function ue_print_word ()
{
    f=document.form1;
	codcont=f.txtcodcont.value;
	nroregdes=f.txtnroregdes.value;
	nroreghas=f.txtnroreghas.value;
	nomrtf=f.txtnomrtf.value;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		if (codcont!="")
		{
			if (nomrtf !="")
			{
				if (nroregdes <= nroreghas)
				{
				
					 pagina="../../../reporte/sigesp_srh_rpp_contratos_word.php?codcont="+codcont+"&nroregdes="+nroregdes+"&nroreghas="+nroreghas+"";
					 window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");	
				}
				else
				{
					alert ('El rango de registros de Contrato está erroneo');
				}
			}
			else
			{
				alert ("La configuracion de contrato seleccionada no tiene archivo rtf asociado, y no se podrá generar el reporte. Seleccione otra configuración.");
			}
		}
		else
		{
			alert ('Debe llenar la configuración del contrato para generar el reporte');
		}
    } //fin del if imprimir
   else		
   {
	 alert("No tiene permiso para realizar esta operación");
   }
}



function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}



function catalogo_contrato_desde()
{
  f= document.form1;
  pagina="../catalogos/sigesp_srh_cat_contratos.php?valor_cat=0"+"&tipo=1";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
} 
function catalogo_contrato_hasta()
{
   f= document.form1;
   pagina="../catalogos/sigesp_srh_cat_contratos.php?valor_cat=0"+"&tipo=2";
   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
}

function catalogo_defcontrato ()
{
   f= document.form1;
   pagina="../catalogos/sigesp_srh_cat_defcontrato.php?valor_cat=0"+"&tipo=1";
   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
}



</script> 