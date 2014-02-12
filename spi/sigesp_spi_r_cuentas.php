<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_ingreso.php");
$io_fun_ingreso=new class_funciones_ingreso();
$io_fun_ingreso->uf_load_seguridad("SPI","sigesp_spi_r_cuentas.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
<title>Listado de Cuentas Presupuestarias </title>
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
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo3 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
      <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Sistema de Presupuesto de Ingreso</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr> 
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a>
													 <img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?Php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];
$li_estpreing = $la_emp["estpreing"];
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
if (array_key_exists("txtfechas",$_POST)) 
   {
     $ldt_fechas=$_POST["txtfechas"];
   }
else
   {
     $ldt_fechas="";
   }

if	(array_key_exists("txtcuentades",$_POST))
	{
	  $ls_cuentades=$_POST["txtcuentades"];
    }
else
	{
	  $ls_cuentades="";
	}   
if	(array_key_exists("txtcuentahas",$_POST))
	{
	  $ls_cuentahas=$_POST["txtcuentahas"];
    }
else
	{
	  $ls_cuentahas="";
	} 

?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php 
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		$io_fun_ingreso->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
		unset($io_fun_ingreso);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
 if($li_estpreing=='1')
	 {
		$ls_mostrar_estruc = 'style="display:compact"';
	 }
	 else
	 { 
		$ls_mostrar_estruc = 'style="display:none"';
	 }
?>
  <table width="608" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-celdanew">Listado de Cuentas Presupuestarias </td>
    </tr>
  </table>
  <table width="605" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="299"></td>
    </tr>
    <tr>
      <td colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center" <? print $ls_mostrar_estruc ?>><div align="left">
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
        <table width="270" height="77" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
          <tr class="titulo-celda" <? print $ls_mostrar_estruc ?>>
            <td height="13" colspan="9" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Estructura Presupuestaria Desde </strong></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20" <? print $ls_mostrar_estruc ?>>
              <div align="right">
                <input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center">
                </div></td><td height="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td width="57" colspan="6"><a href="javascript:catalogo_estpro2();"></a><a href="javascript:catalogo_estpro3();"></a></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20">
              <div align="right">
                <input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center">
                </div></td><td height="20"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
            <td width="57" colspan="6"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td width="36"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td width="127" height="22"><div align="right">
                <input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center">
            </div></td>
            <td width="40" height="22"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td width="57" colspan="6"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
        </table>
      </div></td>
      <td width="298" align="center" <? print $ls_mostrar_estruc ?>><div align="left">
        <?php 
		  }
		 if($li_estmodest==1)
		 {
	   ?>
      </div>
        <table width="271" height="79" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
          <tr class="titulo-celda" <? print $ls_mostrar_estruc ?>>
            <td height="13" colspan="3" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Estructura Presupuestaria Hasta </strong></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td width="176" height="20"><div align="right">
                <input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center">
            </div></td>
            <td width="32"><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td width="61"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td height="20"><div align="right">
                <input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h  ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center">
            </div></td>
            <td><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td height="22"><div align="right">
                <input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center">
            </div></td>
            <td><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="2" align="center" <? print $ls_mostrar_estruc ?>><div align="left">
        <?php 
		  }
		 if($li_estmodest==2)
		 {
		?>
        <table width="266" height="117" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
          <tr class="titulo-celda" <? print $ls_mostrar_estruc ?>>
            <td height="13" colspan="4" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico Desde </strong></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20"><input name="codestpro12" type="text" id="codestpro12" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center">
              <a href="javascript:catalogo_estpro1();"></a></td>
            <td width="44" height="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20"><input name="codestpro22" type="text" id="codestpro22" value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center">
              <a href="javascript:catalogo_estpro2();"></a></td>
            <td height="20"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
            <td></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20"><input name="codestpro32" type="text" id="codestpro32" value="<?php print $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center">
              <a href="javascript:catalogo_estpro3();"></a></td>
            <td height="20"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20"><input name="codestpro4" type="text" id="codestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" style="text-align:center">
              <a href="javascript:catalogo_estpro4();"></a></td>
            <td height="20"><a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
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
      <td height="13" align="center" <? print $ls_mostrar_estruc ?>><div align="left">
        <?php 
		 if($li_estmodest==2)
		  {
		?>
        <table width="270" height="117" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
          <tr class="titulo-celda">
            <td height="13" colspan="4" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico Hasta </strong></td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20"><input name="codestpro1h2" type="text" id="codestpro1h2" value="<?php print $ls_codestpro1h ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center"></td>
            <td width="37" height="20"><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20"><input name="codestpro2h2" type="text" id="codestpro2h2" value="<?php print $ls_codestpro2h  ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center"></td>
            <td height="20"><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20"><input name="codestpro3h2" type="text" id="codestpro3h2" value="<?php print $ls_codestpro3h ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center"></td>
            <td height="20"><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td height="20"><input name="codestpro4h" type="text" id="codestpro4h" value="<?php print  $ls_codestpro4h ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" style="text-align:center"></td>
            <td height="20"><a href="javascript:catalogo_estprohas4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
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
      <td colspan="3" align="center">      <div align="left">
        <table width="559" height="39" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="5"><strong class="titulo-celdanew">Intervalo de Cuentas </strong></td>
            </tr>
          <tr>
            <td width="96" height="22"><div align="right"><span class="style1 style14">Desde</span></div></td>
            <td width="167"><div align="left">
              <input name="txtcuentades" readonly="true" type="text" id="txtcuentades" value="<?php print $ls_cuentades; ?>" style="text-align:center">
              <a href="javascript:catalogo_cuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
            <td width="94">
              <div align="right"></div>                <div align="right">Hasta</div></td>
            <td width="120"><input name="txtcuentahas" readonly="true" type="text" id="txtcuentahas" value="<?php print $ls_cuentahas; ?>" style="text-align:c"></td>
            <td width="80"><a href="javascript:catalogo_cuentahas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
          </tr>
        </table>
      </div></td>
    </tr>
    
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left"></div>        <div align="left"></div>        <div align="left" class="style14"></div>        
        <div align="center"></div>        <table width="559" border="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td colspan="4"><div align="center" class="titulo-celdanew"><strong>Cuentas Contables </strong></div></td>
          </tr>
          <tr>
            <td width="84" height="22"><div align="right">Desde</div></td>
            <td width="170"><div align="left">
                <input name="txtcuentadesde" type="text" id="txtcuentadesde"  style="text-align:center" size="22" readonly>
                <a href="javascript:cat_desde()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a> </div></td>
            <td width="88"><div align="right">Hasta</div></td>
            <td width="187"><div align="left">
                <input name="txtcuentahasta" type="text" id="txtcuentahasta" size="22" readonly>
                <a href="javascript:cat_hasta()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
          </tr>
                </table></td>
    </tr>
    <tr><?php
	$arr_emp=$_SESSION["la_empresa"];
	$ls_codemp=$arr_emp["codemp"];
	?>
      <td height="22" colspan="3" align="center"><div align="right"><a href="javascript: ue_showouput();">     <span class="Estilo1">
        <input name="estpreing"   type="hidden"   id="estpreing"   value="<?php print $li_estpreing;?>">
        <input name="estclades" type="hidden" id="estclades" value="<?php print $ls_estclades;?>">
        <input name="estclahas" type="hidden" id="estclahas" value="<?php print $ls_estclahas;?>">
        <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></a></div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total2" value="<?php print $totrow;?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

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

function ue_showouput()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	estpreing=f.estpreing.value;
	estmodest=f.estmodest.value;
	if(li_imprimir==1)
	{
	   if(estpreing==1)
		{
			codestpro1       = f.codestpro1.value;
			codestpro2       = f.codestpro2.value;
			codestpro3       = f.codestpro3.value;
			codestpro1h      = f.codestpro1h.value;
			codestpro2h      = f.codestpro2h.value;
			codestpro3h      = f.codestpro3h.value;
		}
		txtcuentades     = f.txtcuentades.value;
		txtcuentahas     = f.txtcuentahas.value;
		txtcuentadesde = f.txtcuentadesde.value;
		txtcuentahasta = f.txtcuentahasta.value;
		estclades=f.estclades.value;
	    estclahas=f.estclahas.value;
		estmodest=f.estmodest.value;	
	
	    if(estmodest==1)
		{
		   if(estpreing==1)
		   {
			  pagina="reportes/sigesp_spi_rpp_listado_cuentas.php?txtcuentades="+txtcuentades
					+"&txtcuentahas="+txtcuentahas+"&txtcuentadesde="+txtcuentadesde+"&txtcuentahasta="+txtcuentahasta
					+"&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro1h="+codestpro1h
					+"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h+"&estclades="+estclades+"&estclahas="+estclahas;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		   }
		   else
		   {
		   	  pagina="reportes/sigesp_spi_rpp_listado_cuentas.php?txtcuentades="+txtcuentades
					+"&txtcuentahas="+txtcuentahas+"&txtcuentadesde="+txtcuentadesde+"&txtcuentahasta="+txtcuentahasta;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		   }
		
		}
		else
		{
		   if(estpreing==1)
		   {
		    codestpro4  = f.codestpro4.value;
			codestpro5  = f.codestpro5.value;
			codestpro4h = f.codestpro4h.value;
			codestpro5h = f.codestpro5h.value;
			pagina="reportes/sigesp_spi_rpp_listado_cuentas.php?txtcuentades="+txtcuentades
					+"&txtcuentahas="+txtcuentahas+"&txtcuentadesde="+txtcuentadesde+"&txtcuentahasta="+txtcuentahasta
					+"&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro1h="+codestpro1h
					+"&codestpro2h="+codestpro2h+"&codestpro3h="+codestpro3h+"&codestpro4h="+codestpro4h+"&codestpro5h="+codestpro5h+"&txtcuentades="+txtcuentades
			        +"&estclades="+estclades+"&estclahas="+estclahas;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");

		   }
		   else
		   {
			pagina="reportes/sigesp_spi_rpp_listado_cuentas.php?txtcuentades="+txtcuentades+"&txtcuentahas="+txtcuentahas
			       +"&txtcuentadesde="+txtcuentadesde+"&txtcuentahasta="+txtcuentahasta;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		   }
		}
	}
	else
	{
       alert("No tiene permiso para realizar esta operacion");	
	}	
}

function catalogo_cuentas()
{
	   pagina="sigesp_cat_ctasrep.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_cuentahas()
{
	   pagina="sigesp_cat_ctasrephas.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}


//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}

function cat_desde()
{
	f=document.form1;
	window.open("sigesp_cat_ctasscg_parm.php?obj=txtcuentadesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function cat_hasta()
{
	f=document.form1;
	window.open("sigesp_cat_ctasscg_parm.php?obj=txtcuentahasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

/*function catalogo_estpro1()
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
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&tipo=rephas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=rephas"+"&estcla="+estcla;
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
				pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
				+"&tipo=rephas"+"&estcla="+estcla;
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
}*/

function catalogo_estpro1()
{
	   pagina="sigesp_spi_cat_public_estpro1.php?tipo=reporteacumdes";
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
			pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporteacumdes"+"&estcla="+estcla+"&tipo=reporteacumdes";
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
			pagina="sigesp_cat_estpro2.php?tipo=reporteacumdes"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporteacumdes"+"&estcla="+estcla;
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
			pagina="sigesp_spi_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporteacumdes"
			+"&estcla="+estcla;
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
				pagina="sigesp_cat_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla+"&tipo=reporteacumdes";
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
				pagina="sigesp_spi_cat_public_estpro3.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&tipo=reporteacumdes"
				+"&estcla="+estcla;
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
			pagina="sigesp_cat_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&estcla="+estcla+"&tipo=reporteacumdes";
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
			pagina="sigesp_spi_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3
			+"&tipo=reporteacumdes"+"&estcla="+estcla;
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
			pagina="sigesp_cat_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
			+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&estcla="+estcla+"&tipo=reporteacumdes";
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
			pagina="sigesp_spi_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporteacumdes"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}
function catalogo_estprohas1()
{
	   pagina="sigesp_spi_cat_public_estpro1.php?tipo=reporteacumhas";
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
			pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporteacumhas"+"&estcla="+estcla;
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
			pagina="sigesp_cat_estpro2.php?tipo=reporteacumhas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			if(codestpro1!="")
			{
				pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&tipo=reporteacumhas"+"&estcla="+estcla;
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
			pagina="sigesp_spi_cat_public_estpro3.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2
			                                 +"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=rephas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro2=='**')||(codestpro1=='**'))
		{
			if((codestpro2!="")&&(codestpro1!=""))
			{
				pagina="sigesp_cat_estpro3.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla;
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
				pagina="sigesp_spi_cat_public_estpro3.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&estcla="+estcla;
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
			pagina="sigesp_cat_estpro4.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2
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
			pagina="sigesp_spi_cat_public_estpro4.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3
			+"&tipo=reporteacumhas"+"&estcla="+estcla;
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
			pagina="sigesp_cat_estpro5.php?tipo=reporteacumhas&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3
			+"&codestpro4="+codestpro4+"&estcla="+estcla;
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
			pagina="sigesp_spi_cat_public_estpro5.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2
													 +"&codestpro3="+codestpro3+"&codestpro4="+codestpro4
													 +"&tipo=reporteacumhas"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione niveles anteriores");
		}
	}
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>