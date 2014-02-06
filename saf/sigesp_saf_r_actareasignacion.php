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
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_r_actareasignacion.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte=$io_fun_activo->uf_select_config("SAF","REPORTE","FORMATO_ACTAREASIGNACION","sigesp_saf_rpp_acta_reasignacion.php","C");
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
<title>Acta de Reasignaci&oacute;n</title>
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
.Estilo1 {color: #6699CC}
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
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
    <!-- <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> -->
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
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

require_once("sigesp_saf_c_comprobantes.php");
$io_saf=new sigesp_saf_c_comprobantes(); 

$ls_year=date("Y");
$ld_fecdes="01/01/".$ls_year;
$ld_fechas=date("d/m/Y");
$ls_codemp=$_SESSION["la_empresa"]["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
	$ls_cmpmov="";
	$ls_codres="";
	$ls_nomres="";
}
if($ls_operacion=="BUSCARDETALLE")
{
	$ls_cmpmov=$io_fun_activo->uf_obtenervalor("txtcmpmov","");
	$ls_codres=$io_fun_activo->uf_obtenervalor("txtcodres","");
	$ls_nomres=$io_fun_activo->uf_obtenervalor("txtnomres","");
	$rs_data=$io_saf->uf_saf_load_activomovimiento($ls_codemp,$ls_cmpmov);

}
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="547" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="543" colspan="2" class="titulo-ventana">Acta  de Reasignaci&oacute;n</td>
    </tr>
  </table>
  <table width="542" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="111"></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="512" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td style="display:none"><div align="right"><strong>Reporte en</strong></div></td>
          <td style="display:none"><div align="left">
              <select name="cmbbsf" id="cmbbsf">
                <option value="0" selected>Bs.</option>
                <option value="1">Bs.F.</option>
              </select>
          </div></td>
        </tr>
        <tr>
          <td width="176"><div align="right"><strong>Reasignaci&oacute;n</strong></div></td>
          <td width="334" height="20"><div align="left">
              <input name="txtcmpmov" type="text" id="txtcmpmov"  style="text-align:center " value="<?php print $ls_cmpmov  ?>" size="21" maxlength="20" readonly>
              <a href="javascript:uf_catalogoreasignacion();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtseract" type="hidden" id="txtseract">
              <input name="txtideact" type="hidden" id="txtideact">
          </div>
            <div align="left"> </div></td>
        </tr>
        <tr>
          <td height="28"><div align="right"><span class="style14 style1"><strong>Unidad Ejecutora </strong></span></div></td>
          <td height="28"><div align="left">
            <select name="cmbuniadm" id="cmbuniadm" style="size:150px">
            <?php
				while($row=$io_sql->fetch_row($rs_data))
				{
					$ls_coduniadm= $row["coduniadm"];
					$ls_denuniadm= $row["denuniadm"];
					print "<option value='$ls_coduniadm'>$ls_denuniadm</option>";
				} 
				if($rs_data=="")
				{print "<option value='---'>-Seleccione-</option>";}
	     ?>
            </select>
            </div>
            <div align="left"> </div></td>
        </tr>
        
      </table></td>
    </tr>
    
    
    <tr>
      <td height="32" colspan="3" align="center"><div align="right" class="style1 style14"></div>        <div align="right" class="style1 style14"></div>        <div align="left">
          <input name="hidunidad" type="hidden" id="hidunidad">
          <input name="hidstatus" type="hidden" id="hidstatus">
          <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>">
      </div></td>
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
function uf_catalogoreasignacion()
{
	ls_coddestino="reporte";
	window.open("sigesp_saf_cat_reasignaciones.php?coddestino="+ ls_coddestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=120,top=70,location=no,resizable=yes");
}
	
function uf_catalogoresponsable()
{
	window.open("sigesp_saf_cat_personal.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=120,top=70,location=no,resizable=yes");
}

function uf_catalogoresponsableuso()
{
	window.open("sigesp_saf_cat_responsableuso.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=120,top=70,location=no,resizable=yes");
}

function uf_mostrar_reporte(ls_reporte)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_cmpmov=    f.txtcmpmov.value;
		ls_coduniadm= f.cmbuniadm.value;
		//ls_codres=    f.txtcodres.value;
		//ls_codresuso= f.txtcodresuso.value;
		tipoformato = f.cmbbsf.value;
		if((ls_cmpmov!="")&&(ls_coduniadm!="---"))
		{
		  //window.open("reportes/"+ls_reporte+"?cmpmov="+ls_cmpmov+"&coduniadm="+ls_coduniadm+"&codres="+ls_codres+"&codresuso="+ls_codresuso+"&tipoformato="+tipoformato,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		   window.open("reportes/"+ls_reporte+"?cmpmov="+ls_cmpmov+"&coduniadm="+ls_coduniadm+"&tipoformato="+tipoformato,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{alert("Debe completar los datos del comprobante");}
	}
	else
	{alert("No tiene permiso para realizar esta operación");}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script>
</html>