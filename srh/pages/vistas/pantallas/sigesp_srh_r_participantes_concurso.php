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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_r_participantes_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reporte Listado de Ganadores Concurso</title>
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
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 12px;
	color: #6699CC;
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
<input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="5" class="titulo-ventana">Reporte Listado de Ganadores por Concurso </td>
        </tr>
		
        <tr>
          <td height="20" colspan="6" class="titulo-celdanew">Información de Concurso </td>
          </tr>
         <tr class="formato-blanco">
    <td width="103" height="29"><div align="right">C&oacute;digo</div></td>
    <td height="29" colspan="3"><input name="txtcodcon" type="text" id="txtcodcon"  size="11" maxlength="10"  readonly style="text-align:center ">
        <input name="hidstatus" type="hidden" id="hidstatus"> <a href="javascript:catalogo_concurso();"> <img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Concurso" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
        <input name="txtdescon" type="text" id="txtdescon"  size="50" maxlength="254"  readonly class="sin-borde"></td>
    <td width="31"  class="sin-borde"><div id="existe" class="letras-pequeÃ±as" style="display:none"> 
		</div></td>
  </tr>
  <tr>
          <td height="20" colspan="5" class="titulo-celdanew"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Concurso </div></td>
          <td colspan="4"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Personal</div></td>
          <td colspan="4"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
       
		<tr>
          <td height="22"><div align="right">Resultado del Concurso </div></td>
          <td colspan="4"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="4">
          </div></td>
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
	codcon=f.txtcodcon.value;
	if (codcon=='') {
	 alert ('Debe llenar el código de Concurso');
	}
	else{
		li_imprimir=f.imprimir.value;
		if(li_imprimir==1)
		{	
			 if(f.rdborden[0].checked)
					{
						orden="1";
					}
			if(f.rdborden[1].checked)
					{
						orden="2";
					}
			if(f.rdborden[2].checked)
					{
						orden="3";
					}
			
					 pagina="../../../reporte/sigesp_srh_rpp_listado_participantes_concurso.php?codcon="+codcon+"&orden="+orden+"";
					 window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");	
		}
		else
		{
			alert("No tiene permiso para realizar esta operación");
		}	
	}	
}



function catalogo_concurso()
{
    
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor_cat=1";
   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}



</script> 