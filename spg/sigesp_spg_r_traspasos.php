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
require_once("class_funciones_gasto.php");
$io_fun_gasto=new class_funciones_gasto();
$io_fun_gasto->uf_load_seguridad("SPG","sigesp_spg_r_traspasos.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte de Traspasos de Modificaciones Presupuestarias</title>
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
.Estilo1 {font-weight: bold}
.Estilo2 {color: #6699CC}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="4" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
      <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			      <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Presupuesto de Gasto  </td>
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
    <td height="13" colspan="7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td width="20" height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
    <td width="718" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php
 
  
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

require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();

$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}

if(array_key_exists("txtfecdesde",$_POST))
{
	$ld_fecdesde=$_POST["txtfecdesde"];
}
else
{
	$ld_fecdesde="";	
}

if(array_key_exists("txtfechasta",$_POST))
{
	$ld_fechasta=$_POST["txtfechasta"];
}
else
{
	$ld_fechasta="";	
}

if(array_key_exists("txtbddestino",$_POST))
{
	$ld_bddestino=$_POST["txtbddestino"];
}
else
{
	$ls_bddestino="";	
}

	if ($ls_operacion=="REPORT")
	{
		$ld_fecdesde=$_POST["txtfecdesde"];
		$ld_fechasta=$_POST["txtfechasta"];
		$ls_evento="REPORT";
		$ls_descripcion="Generó un reporte de Traspasos de Modificaciones Presupuestarias. Desde el  ". $ld_fecdesde ." hasta el ".$ld_fechasta;
		$lb_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
								$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
								$la_seguridad["ventanas"],$ls_descripcion);
	}

?>

</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_gasto->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_gasto);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="500" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="438" colspan="2" class="titulo-ventana">Reporte de Traspasos de Modificaciones Presupuestarias </td>
    </tr>
  </table>
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="498"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <div align="left"></div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">      <div align="left">
        <table width="487" border="0" align="center" class="formato-blanco">
          <tr>
            <td colspan="5"><strong>Criterio de Busqueda:</strong></td>
          </tr>
          <tr>
            <td height="22"><div align="left">Rango de Fecha:</div></td>
            <td><div align="right">Desde:</div></td>
            <td><input name="txtfecdesde" type="text" id="txtfecdesde" value="<?php print $ld_fecdesde?>" size="15" maxlength="10" onKeyPress="ue_solo_numeros(this,'/',patron,true)" datepicker="true"></td>
            <td><div align="right">Hasta:</div></td>
            <td><input name="txtfechasta" type="text" id="txtfechasta" value="<?php print $ld_fecdesde?>" size="15" maxlength="10" onKeyPress="ue_solo_numeros(this,'/',patron,true)" datepicker="true"></td>
          </tr>
          <tr>
            <td width="466" height="35" colspan="5"><div align="left"> Base de Datos Destino: 
                <input name="txtbddestino" type="text" id="txtbddestino" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_bddestino?>" size="35" maxlength="50" readonly="true" align="center">
              <a href="javascript: uf_catalogobd();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
            </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="32" colspan="3" align="center"><div align="right" class="style1 style14"></div>        <div align="right" class="style1 style14"></div>        <div align="left">
          <input name="hidstatus" type="hidden" id="hidstatus">
      </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left" class="style14"></div></td>
    </tr>
    <tr>
      <td height="24" colspan="3" align="center"><div align="right">
      <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>">
      </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

	function ue_mostrar_reporte()
	{
		f=document.form1;
		ld_fecdesde=f.txtfecdesde.value;
	    ld_fechasta=f.txtfechasta.value;
		ls_bddestino=f.txtbddestino.value;
		li_imprimir=f.imprimir.value;
		var valido = true;
		if(li_imprimir)
		{
			if((ld_fecdesde=="")||(ld_fechasta==""))
			{
				alert("Indique un rango de Fecha");
			}
			else
			{
			 valido = ue_comparar_intervalo();
			 if (valido)
			 {
			  window.open("reportes/sigesp_spg_rpp_traspasos.php?fecdesde="+ld_fecdesde+"&fechasta="+ld_fechasta+"&bddestino="+ls_bddestino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			 } 
			}
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

function ue_comparar_intervalo()
   { 

	f=document.form1;
   	ld_desde="f.txtfecdesde";
   	ld_hasta="f.txtfechasta";
	var valido = false; 
    var diad = f.txtfecdesde.value.substr(0, 2); 
    var mesd = f.txtfecdesde.value.substr(3, 2); 
    var anod = f.txtfecdesde.value.substr(6, 4); 
    var diah = f.txtfechasta.value.substr(0, 2); 
    var mesh = f.txtfechasta.value.substr(3, 2); 
    var anoh = f.txtfechasta.value.substr(6, 4); 
    
	if (anod < anoh)
	{
		 valido = true; 
	 }
    else 
	{ 
     if (anod == anoh)
	 { 
      if (mesd < mesh)
	  {
	   valido = true; 
	  }
      else 
	  { 
       if (mesd == mesh)
	   {
 		if (diad <= diah)
		{
		 valido = true; 
		}
	   }
      } 
     } 
    } 
    if (valido==false)
	{
		alert("El rango de fecha es invalido");
		f.txtfecdesde.value="";
		f.txtfechasta.value="";
		
	} 
	return valido;
   } 
function uf_catalogobd()
{
    f=document.form1;
    pagina="sigesp_spg_cat_consolidacion.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
