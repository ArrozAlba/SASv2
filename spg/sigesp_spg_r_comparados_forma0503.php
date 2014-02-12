<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>EJECUCION FINANCIERA MENSUAL DEL PRESUPUESTO DE GASTOS</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo4 {color: #6699CC}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo4">Contabilidad Presupuestaria de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
       <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0" title="Imprimir"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
  </tr>
</table>
  <?php
$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
if (array_key_exists("codestpro1",$_POST))
   {
     $ls_codestpro1=$_POST["codestpro1"];	   
   }
else
   {
     $ls_codestpro1="";
   }
if (array_key_exists("codestpro2",$_POST))
   {
    $ls_codestpro2=$_POST["codestpro2"];	   
   }
else
   {
     $ls_codestpro2="";
   }
if (array_key_exists("codestpro3",$_POST))
   {
     $ls_codestpro3=$_POST["codestpro3"];	   
   }
else
   {
     $ls_codestpro3="";
   }
if (array_key_exists("codestpro1h",$_POST))
   {
      $ls_codestpro1h=$_POST["codestpro1h"];	   
   }
else
   {
      $ls_codestpro1h="";
   }
if (array_key_exists("codestpro2h",$_POST))
   {
     $ls_codestpro2h=$_POST["codestpro2h"];	   
   }
else
   {
     $ls_codestpro2h="";
   }
if (array_key_exists("codestpro3h",$_POST))
   {
     $ls_codestpro3h=$_POST["codestpro3h"];	   
   }
else
   {
     $ls_codestpro3h="";
   }
if (array_key_exists("codestpro4",$_POST))
   {
     $ls_codestpro4=$_POST["codestpro4"];	   
   }
else
   {
     $ls_codestpro4="";
   }
if (array_key_exists("codestpro5",$_POST))
   {
     $ls_codestpro5=$_POST["codestpro5"];	   
   }
else
   {
     $ls_codestpro5="";
   }
if (array_key_exists("codestpro4h",$_POST))
   {
     $ls_codestpro4h=$_POST["codestpro4h"];	   
   }
else
   {
     $ls_codestpro4h="";
   }
if (array_key_exists("codestpro5h",$_POST))
   {
     $ls_codestpro5h=$_POST["codestpro5h"];	   
   }
else
   {
     $ls_codestpro5h="";
   }
if (array_key_exists("cmbmes",$_POST)) 
   {
     $ls_cmbmes=$_POST["cmbmes"];
   }
else
   {
     $ls_cmbmes="s1";
   }
if	(array_key_exists("cmbnivel",$_POST))
	{
	  $ls_cmbnivel=$_POST["cmbnivel"];
    }
else
	{
	  $ls_cmbnivel="s1";
	}
if  (array_key_exists("estclades",$_POST))
	{
	  $ls_estclades=$_POST["estclades"];
    }
else
	{
	  $ls_estclades="";
	}	
if  (array_key_exists("estclahas",$_POST))
	{
	  $ls_estclahas=$_POST["estclahas"];
    }
else
	{
	  $ls_estclahas="";
	}
if	(array_key_exists("txtcodfuefindes",$_POST))
	{
	  $ls_codfuefindes=$_POST["txtcodfuefindes"];
    }
else
	{
	  $ls_codfuefindes="";
	} 
	
if	(array_key_exists("txtcodfuefinhas",$_POST))
	{
	  $ls_codfuefinhas=$_POST["txtcodfuefinhas"];
    }
else
	{
	  $ls_codfuefinhas="";
	}   
	 
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="608" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">EJECUCION FINANCIERA MENSUAL DEL PRESUPUESTO DE GASTOS</td>
    </tr>
  </table>
  <table width="605" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="298"></td>
    </tr>
    <tr>
      <td width="301" height="17" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr style="display:none">
      <td height="17" colspan="3" align="center"><div align="left"><strong> Reporte en</strong>
              <select name="cmbbsf" id="cmbbsf">
                <option value="0" selected>Bs.</option>
                <option value="1">Bs.F.</option>
              </select>
      </div></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><div align="left">
          <?php 
		 $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		 $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"]+10;
		 $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"]+10;
		 $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"]+10;
		 $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"]+10;
		 $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"]+10;
		 if($li_estmodest==1)
		 {
	   ?>
          <table width="275" height="77" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
            <!--DWLayoutTable-->
            <tr class="titulo-celda">
              <td height="13" colspan="9" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Estructura Presupuestaria Desde </strong></td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><div align="left">
                  <input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center">
              </div></td>
              <td height="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
              <td width="70" colspan="6"><a href="javascript:catalogo_estpro2();"></a><a href="javascript:catalogo_estpro3();"></a></td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><div align="left">
                  <input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center">
              </div></td>
              <td height="20"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
              <td width="70" colspan="6"><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td width="36"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td width="127" height="22"><div align="right">
                  <input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center">
              </div></td>
              <td width="40" height="22"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td width="70" colspan="6"><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
          </table>
      </div></td>
      <td align="center"><div align="left">
          <?php 
		  }
		 if($li_estmodest==1)
		 {
	   ?>
          <table width="275" height="79" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
            <!--DWLayoutTable-->
            <tr class="titulo-celda">
              <td height="13" colspan="3" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Estructura Presupuestaria Hasta </strong></td>
            </tr>
            <tr class="formato-blanco">
              <td width="176" height="20"><div align="right">
                  <input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center">
              </div></td>
              <td width="32"><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
              <td width="65"><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="20"><div align="right">
                  <input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h  ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center">
              </div></td>
              <td><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">
                  <input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center">
              </div></td>
              <td><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td></td>
            </tr>
          </table>
      </div></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><div align="left">
          <?php 
		  }
		 if($li_estmodest==2)
		 {
		?>
          <table width="275" height="117" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
            <!--DWLayoutTable-->
            <tr class="titulo-celda">
              <td height="13" colspan="4" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico Desde </strong></td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><input name="codestpro12" type="text" id="codestpro12" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center">
                <a href="javascript:catalogo_estpro1();"></a></td>
              <td width="44" height="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
              <td></td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><input name="codestpro22" type="text" id="codestpro22" value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center">
                <a href="javascript:catalogo_estpro2();"></a></td>
              <td height="20"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
              <td></td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><input name="codestpro32" type="text" id="codestpro32" value="<?php print $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center">
                <a href="javascript:catalogo_estpro3();"></a></td>
              <td height="20"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td></td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><input name="codestpro4" type="text" id="codestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" style="text-align:center">
                <a href="javascript:catalogo_estpro4();"></a></td>
              <td height="20"><a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td></td>
            </tr>
            <tr class="formato-blanco">
              <td width="53"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td width="129" height="18"><input name="codestpro5" type="text" id="codestpro5" value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" style="text-align:center">
                <a href="javascript:catalogo_estpro5();"></a></td>
              <td height="22"><a href="javascript:catalogo_estpro5();"></a><a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a><a href="javascript:catalogo_estpro1();"></a></td>
              <td width="172"></td>
            </tr>
          </table>
        <p>
            <?php
		  }
		 ?>
          </p>
      </div></td>
      <td align="center"><div align="left">
          <?php 
		 if($li_estmodest==2)
		  {
		?>
          <table width="275" height="117" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
            <!--DWLayoutTable-->
            <tr class="titulo-celda">
              <td height="13" colspan="4" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico Hasta </strong></td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><input name="codestpro1h2" type="text" id="codestpro1h2" value="<?php print $ls_codestpro1h ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center"></td>
              <td width="37" height="20"><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><input name="codestpro2h2" type="text" id="codestpro2h2" value="<?php print $ls_codestpro2h  ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center"></td>
              <td height="20"><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><input name="codestpro3h2" type="text" id="codestpro3h2" value="<?php print $ls_codestpro3h ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center"></td>
              <td height="20"><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td height="20"><input name="codestpro4h" type="text" id="codestpro4h" value="<?php print  $ls_codestpro4h ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" style="text-align:center"></td>
              <td height="20"><a href="javascript:catalogo_estprohas4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td width="38"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td width="127" height="20"><input name="codestpro5h" type="text" id="codestpro5h" value="<?php print  $ls_codestpro5h ?>" size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" style="text-align:center"></td>
              <td height="22"><a href="javascript:catalogo_estprohas5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a><a href="javascript:catalogo_estprohas1();"></a></td>
              <td width="87"><a href="javascript:catalogo_estprohas5();"></a></td>
            </tr>
          </table>
        <p>
            <?php
		  }
		 ?>
          </p>
      </div></td>
    </tr>
    
    <tr>
      <td height="13" colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="left"><table width="550" height="39" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="5"><strong>Intervalo de Fuente de Financiamiento </strong></td>
        </tr>
        <tr>
          <td width="96" height="22"><div align="right"><span class="style1 style14">Desde</span></div></td>
          <td width="167"><div align="left">
              <input name="txtcodfuefindes" type="text" id="txtcodfuefindes"  style="text-align:center" value="<?php print $ls_codfuefindes; ?>" size="10" readonly>
          <a href="javascript:catalogo_fuefindes();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
          <td width="94"><div align="right">Hasta</div></td>
          <td width="120"><input name="txtcodfuefinhas" type="text" id="txtcodfuefinhas" style="text-align:center" value="<?php print $ls_codfuefinhas; ?>" size="10" readonly>
            <a href="javascript:catalogo_fuefinhas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
          <td width="80"><a href="javascript:catalogo_fuefinhas();"></a></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left"><table width="550" height="40" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="4"><strong class="titulo-celdanew">Seleccione  Mes y Nivel </strong></td>
          </tr>
          <tr>
            <td width="85" height="21"><div align="right">Mes</div></td>
            <td width="254" height="21"><select name="cmbmes" id="cmbmes">
                <option value="01">Enero</option>
                <option value="02">Febrero</option>
                <option value="03">Marzo</option>
                <option value="04">Abril</option>
                <option value="05">Mayo</option>
                <option value="06">Junio</option>
                <option value="07">Julio</option>
                <option value="08">Agosto</option>
                <option value="09">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select></td>
            <td width="41"><div align="right">Nivel</div></td>
            <td width="168"><div align="left">
                <select name="cmbnivel" id="cmbnivel">
                  <option value="s1">Seleccione un Nivel </option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                </select>
            </div></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="left"><strong><span class="style14"> </span></strong></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="right"><span class="Estilo1">
          <input name="estclades" type="hidden" id="estclades" value="<?php print $ls_estclades;?>">
          <input name="estclahas" type="hidden" id="estclahas" value="<?php print $ls_estclahas;?>">
          <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
          <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></div></td>
    </tr>
  </table>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_showouput()
{
	f=document.form1;
	codestpro1  = f.codestpro1.value;
	codestpro2  = f.codestpro2.value;
	codestpro3  = f.codestpro3.value;
	codestpro1h = f.codestpro1h.value;
	codestpro2h = f.codestpro2h.value;
	codestpro3h = f.codestpro3h.value;
	cmbnivel = f.cmbnivel.value;
	cmbmes = f.cmbmes.value;
	estmodest=f.estmodest.value;
	tipoformato=f.cmbbsf.value;
	txtcodfuefindes = f.txtcodfuefindes.value;
	txtcodfuefinhas = f.txtcodfuefinhas.value;
	estclades = f.estclades.value;
	estclahas = f.estclahas.value;
	if(estmodest==1)
	{
		if((cmbmes=="s1"))
		{
		  alert("Por Favor Seleccionar todos los parametros de busqueda");
		}
		else
		{
		   pagina="reportes/sigesp_spg_rpp_comp_ejec_finan_forma0503.php?cmbmes="+cmbmes+"&codestpro1="+codestpro1
		          +"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro1h="+codestpro1h
		          +"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h+"&cmbnivel="+cmbnivel+"&tipoformato="+tipoformato
				  +"&txtcodfuefindes="+txtcodfuefindes+"&txtcodfuefinhas="+txtcodfuefinhas
				  +"&estclades="+estclades+"&estclahas="+estclahas;
		   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
	}
	else
	{
		codestpro4  = f.codestpro4.value;
		codestpro5  = f.codestpro5.value;
		codestpro4h = f.codestpro4h.value;
		codestpro5h = f.codestpro5h.value;
		if((cmbmes=="s1"))
		{
		  alert("Por Favor Seleccionar todos los parametros de busqueda");
		}
		else
		{
		   pagina="reportes/sigesp_spg_rpp_comp_ejec_finan_forma0503.php?cmbmes="+cmbmes+"&codestpro1="+codestpro1
		   +"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5
		   +"&codestpro1h="+codestpro1h+"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h+"&codestpro4h="+codestpro4h
		   +"&codestpro5h="+codestpro5h+"&cmbnivel="+cmbnivel+"&tipoformato="+tipoformato+"&tipoformato="+tipoformato
		   +"&txtcodfuefindes="+txtcodfuefindes+"&txtcodfuefinhas="+txtcodfuefinhas
	       +"&estclades="+estclades+"&estclahas="+estclahas;
		   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
	}	
}

function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php?tipo=reporte";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;	
	estmodest=f.estmodest.value;
	estcla=f.estclades.value;
	if(estmodest==1)
	{
		if(codestpro1!="")
		{
			pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione nivel anterior");
		}
	}
	else
	{
		
		if(codestpro1=='**')
		{
			pagina="sigesp_cat_estpro2.php?tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte"+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	estmodest=f.estmodest.value;
	estcla=f.estclades.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_estpro3.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte"+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}
function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	estcla=f.estclades.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_estpro4.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	codestpro4=f.codestpro4.value;
	codestpro5=f.codestpro5.value;
	estcla=f.estclades.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_cat_estpro5.php?tipo=reporte&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporte"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}
/*function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	
	if(codestpro1!="")
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporte";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	estmodest=f.estmodest.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1!="")&&(codestpro2!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporte";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
              alert("Seleccione la Estructura nivel 2");
		}
	}	
}
function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
	{
		pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
		                                     +"&codestpro3="+codestpro3+"&tipo=reporte";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3 ");
	}
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	codestpro2=f.codestpro2.value;
	codestpro3=f.codestpro3.value;
	codestpro4=f.codestpro4.value;
	codestpro5=f.codestpro5.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5==""))
	{
		pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
		                                     +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
											 +"&tipo=reporte";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
			pagina="sigesp_cat_public_estprograma.php?tipo=reporte";
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}*/

function catalogo_estprohas1()
{
	   pagina="sigesp_cat_public_estpro1.php?tipo=rephas";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estprohas2()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	estmodest=f.estmodest.value;
	estcla=f.estclahas.value;
	if(estmodest==1)
	{
		if(codestpro1!="")
		{
			pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=rephas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione nivel anterior");
		}
	}
	else
	{
		if(codestpro1=='**')
		{
			pagina="sigesp_cat_estpro2.php?tipo=rephas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=rephas"+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione  nivel anterior");
			}
		}
	}	
}
function catalogo_estprohas3()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	estmodest=f.estmodest.value;
	estcla=f.estclahas.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=rephas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_estpro3.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}
		else
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=rephas"+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
			}
			else
			{
				alert("Seleccione niveles anteriores");
			}
		}	
	}	
}
function catalogo_estprohas4()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	estcla=f.estclahas.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_estpro4.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");

		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&tipo=rephas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");	
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}	
}
function catalogo_estprohas5()
{
	f=document.form1;
	codestpro1=f.codestpro1h.value;
	codestpro2=f.codestpro2h.value;
	codestpro3=f.codestpro3h.value;
	codestpro4=f.codestpro4h.value;
	codestpro5=f.codestpro5h.value;
	estcla=f.estclahas.value;
	if((codestpro2=='**')||(codestpro1=='**')||(codestpro3=='**')||(codestpro4=='**'))
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_cat_estpro5.php?tipo=rephas&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
	else
	{
		if((codestpro2!="")&&(codestpro1!="")&&(codestpro3!="")&&(codestpro4!=""))
		{
			pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=rephas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}
/*function catalogo_estprohas2()
{
	f=document.form1;
	codestpro1h=f.codestpro1h.value;
	if((codestpro1h!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1h+"&tipo=rephas";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estprohas3()
{
	f=document.form1;
	codestpro1h=f.codestpro1h.value;
	codestpro2h=f.codestpro2h.value;
	codestpro3h=f.codestpro3h.value;
	estmodest=f.estmodest.value;
	if(estmodest==1)
	{
		if((codestpro1h!="")&&(codestpro2h!="")&&(codestpro3h==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1h+"&codestpro2="+codestpro2h+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1h!="")&&(codestpro2h!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1h+"&codestpro2="+codestpro2h+"&tipo=rephas";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
            alert("Seleccione la Estructura nivel 2");
		}
	}	
}
function catalogo_estprohas4()
{
	f=document.form1;
	codestpro1h=f.codestpro1h.value;
	codestpro2h=f.codestpro2h.value;
	codestpro3h=f.codestpro3h.value;
	if((codestpro1h!="")&&(codestpro2h!="")&&(codestpro3h!=""))
	{
		pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1h+"&codestpro2="+codestpro2h
		                                     +"&codestpro3="+codestpro3h+"&tipo=rephas";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3 ");
	}
}
function catalogo_estprohas5()
{
	f=document.form1;
	codestpro1h=f.codestpro1h.value;
	codestpro2h=f.codestpro2h.value;
	codestpro3h=f.codestpro3h.value;
	codestpro4h=f.codestpro4h.value;
	codestpro5h=f.codestpro5h.value;
	if((codestpro1h!="")&&(codestpro2h!="")&&(codestpro3h!="")&&(codestpro4h!="")&&(codestpro5h==""))
	{
		pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1h+"&codestpro2="+codestpro2h
		                                     +"&codestpro3="+codestpro3h+"&codestpro4="+codestpro4h
											 +"&tipo=rephas";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="sigesp_cat_public_estprograma.php?tipo=rephas";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}*/
function catalogo_fuefindes()
{
    f=document.form1;
    pagina="sigesp_spg_cat_fuente.php?tipo=REPORTE_DESDE";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function catalogo_fuefinhas()
{
    f=document.form1;
    pagina="sigesp_spg_cat_fuente.php?tipo=REPORTE_HASTA";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}
</script>
</html>