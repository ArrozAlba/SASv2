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
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos();
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_r_bien_uniadm.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("sigesp_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new sigesp_saf_c_activo();
$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Bienes por Unidad Administrativa</title>
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
.Estilo2 {color: #6699CC}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40" class="cd-logo"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" title="Ayuda" height="20"></td>
  </tr>
</table>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="542" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="111"></td>
    </tr>
    <tr>
      <td colspan="3" align="center" class="titulo-ventana">Inventario de Bienes por Unidad Administrativa</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
       
        <tr>
          <td colspan="2"><strong> Activos </strong></td>
        </tr>
        <tr>
          <td width="89"><div align="right">Desde</div></td>
          <td width="420" height="22"><div align="left">
              <input name="txtcoddesde" type="text" id="txtcoddesde" size="21" maxlength="20"  style="text-align:center ">
              <a href="javascript:uf_catalogo_activo('txtcoddesde','txtdendesde');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
              <input name="txtdendesde" type="text" class="sin-borde" id="txtdendesde" size="40" readonly>
              <input name="txtseract" type="hidden" id="txtseract">
              <input name="txtideact" type="hidden" id="txtideact">
          </div>
            <div align="left"> </div></td>
        </tr>
        <tr>
          <td height="10"><div align="right"><span class="style1 style14">Hasta</span></div></td>
          <td height="22"><div align="left">
              <input name="txtcodhasta" type="text" id="txtcodhasta" size="21" maxlength="20"  style="text-align:center">
              <a href="javascript:uf_catalogo_activo('txtcodhasta','txtdenhasta');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
              <input name="txtdenhasta" type="text" class="sin-borde" id="txtdenhasta" size="40" readonly>
</div>
            <div align="left"> </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="right" class="style1 style14"></div>        <div align="right" class="style1 style14"></div>        <div align="left">
          <input name="hidunidad" type="hidden" id="hidunidad">
          <input name="hidstatus" type="hidden" id="hidstatus">
        </div></td>
    </tr>
    
    <tr>
      <td height="22" colspan="3" align="center"><table width="511" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><strong>Unidad Administrativa</strong></td>
          
        </tr>
       <tr class="formato-blanco">
            <td width="69" height="26"><div align="right">Desde</div></td>
                <td width="440" height="26" colspan="2"><label>
                  <input name="txtcoduni" type="text" id="txtcoduni"  size="10" maxlength="15" readonly>
                  <a href="javascript: ue_catalogo_unidad_administrativa();"><img src="../shared/imagebank/tools15/buscar.gif" alt=" " width="15" height="15" border="0"></a>
                  <input name="txtdenuni" type="text" class="sin-borde" id="txtdenuni" size="45" readonly>
            </label></td>
          </tr>
		   <tr class="formato-blanco">
            <td width="69" height="26"><div align="right">Hasta</div></td>
                <td width="440" height="26" colspan="2"><label>
                  <input name="txtcoduni2" type="text" id="txtcoduni2"  size="10" maxlength="15" readonly>
                  <a href="javascript: ue_catalogo_unidad_administrativa2();"><img src="../shared/imagebank/tools15/buscar.gif" alt=" " width="15" height="15" border="0"></a>
                  <input name="txtdenuni2" type="text" class="sin-borde" id="txtdenuni2" size="45" readonly>
            </label></td>
          </tr>
		  
      </table></td>
   
    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14"></div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
        <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          
          <tr>
            <td width="139" height="22"><div align="right">C&oacute;digo Activo
                    <input name="radioordenact" type="radio" class="sin-borde" value="radiobutton" checked>
              </div></td>
            <td width="370">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Denominaci&oacute;n Activo
                    <input name="radioordenact" type="radio" class="sin-borde" value="radiobutton">
              </div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    
    <tr>
      <td colspan="3" align="center">
        <div align="center">
          <p></p>
      </div></td>
    </tr>
  </table>
  <p align="center">&nbsp;</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function uf_resprimario(ls_destino)
{
	window.open("sigesp_saf_cat_personal.php?destino="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=580,height=400,left=120,top=70,location=no,resizable=yes");
}

function uf_catalogo_activo(ls_coddestino,ls_dendestino)
{
	window.open("sigesp_saf_cat_activo.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=580,height=400,left=120,top=70,location=no,resizable=yes");
}

function ue_catalogo_unidad_administrativa()
{
	f=document.form1;
	window.open("sigesp_saf_cat_unidad.php?tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
    //f.txtubigeo.disabled=true;	
}

function ue_catalogo_unidad_administrativa2()
{
	f=document.form1;
	window.open("sigesp_saf_cat_unidad.php?tipo=2","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
    //f.txtubigeo.disabled=true;	
}
	
function uf_mostrar_reporte()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_coddesde= f.txtcoddesde.value;
		ls_codhasta= f.txtcodhasta.value;
		ls_coduniadmdesde= f.txtcoduni.value;
		ls_coduniadmhasta= f.txtcoduni2.value;
		if (ls_coddesde >ls_codhasta )
		{
			alert ('Intervalo del Activos Erróneo');
		}
		else if (ls_coduniadmdesde > ls_coduniadmhasta)
		{
			alert ('Intervalo del Unidad Administrativas Erróneo');
		}
		else
		{
			if(f.radioordenact[0].checked)
			{
				li_ordenact=0;
			}
			else
			{
				li_ordenact=1;
			}
			window.open("reportes/sigesp_saf_rpp_bien_uniadm.php?ordenact="+li_ordenact+"&coddesde="+ls_coddesde+"&codhasta="+ls_codhasta+"&coduniadmdesde="+ls_coduniadmdesde+"&coduniadmhasta="+ls_coduniadmhasta,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
	}
	else
	{alert("No tiene permiso para realizar esta operación");}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que obtiene el valor de el radio button
//--------------------------------------------------------
   function actualizaValor(oRad)
   { 
    var i 
	f=document.form1;
    for (i=0;i<f.radiostatus.length;i++)
	{ 
       if (f.radiostatus[i].checked) 
          break; 
    } 
    valor= i;
	f.hidradio.value=i;
   } 



</script>
</html>