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
	require_once("class_funciones_banco.php");
	$io_fun_banco=new class_funciones_banco();
	$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_declaracionxml.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $io_fun_banco,$ls_operacion,$ls_codtipsol,$ld_fecregdes,$ld_fecreghas,$ld_fecaprord,$li_totrow;
		
		$ls_operacion=$io_fun_banco->uf_obteneroperacion();
		$ls_codtipsol="";
		$ld_fecregdes=date("01/m/Y");
		$ld_fecreghas=date("d/m/Y");
		$ld_fecaprord=date("d/m/Y");
		$li_totrow=0;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_totrow,$ls_tipope,$ld_fecaprosol;
		
		$li_totrow = $_POST["totrow"];
		$ls_tipope = $_POST["rdtipooperacion"];
		$ld_fecaprord  =$_POST["txtfecaprord"];
   }
   //--------------------------------------------------------------
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
<title >Declaracion de Salarios y Otras Remuneraciones</title>
<meta http-equiv="imagetoolbar" content="no"> 
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
-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 11px;
	color: #6699CC;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
-->
</style>
</head>
<body>
<?php
	$ls_operacion=$io_fun_banco->uf_obteneroperacion();
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
	require_once("sigesp_scb_c_transferencias.php");
	$io_reporte_scb=new sigesp_scb_c_transferencias($la_seguridad);
	switch ($ls_operacion) 
	{
		case "GENDISK":
			$ls_mesdes=$_POST["cmbmesdesde"];
			$ls_meshas=$_POST["cmbmeshasta"];
			$ls_year=$_POST["cmbyear"];
			$lb_valido=$io_reporte_scb->uf_declaracionxml($ls_mesdes,$ls_meshas,$ls_year,$la_seguridad);
			if($lb_valido)
			{
				$io_reporte_scb->io_mensajes->message("El xml fué generado");
			}
			else
			{
				$io_reporte_scb->io_mensajes->message("Ocurrio un error al generar el xml");
			}
			break;
	}
	unset($io_reporte_scb);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="titulo-catclaro">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="803" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="423" height="20" align="left" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
			  <td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema" align="left">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table>    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_buscar();"></a><a href="javascript: ue_gendisk();"><img src="../shared/imagebank/tools20/gendisk.jpg" alt="Generar" width="21" height="20" border="0" title="Generar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar();"><img src="../shared/imagebank/tools20/download.gif" alt="" width="20" height="20" border="0" title="Descargar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<input name="operacion" type="hidden" id="operacion">
<table width="578" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="561" colspan="2" class="titulo-ventana">Declaracion de Salarios y Otras Remuneraciones </td>
  </tr>
</table>
<table width="575" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="573"></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="33" colspan="3" align="center"><div align="left">
      <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="22" colspan="5"><strong>Fecha de Periodo </strong></td>
        </tr>
        <tr>
          <td width="136"><div align="right">Desde</div></td>
          <td width="101"><select name="cmbmesdesde" id="cmbmesdesde">
            <option value="01" selected>Enero</option>
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
          </select>          </td>
          <td width="42"><div align="right">Hasta</div></td>
          <td width="129"><div align="left">
            <select name="cmbmeshasta" id="cmbmeshasta">
              <option value="01" selected>Enero</option>
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
                        </select>
          </div></td>
          <td width="101">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div align="right">A&ntilde;o</div></td>
          <td><select name="cmbyear" id="cmbyear">
            <option value="2000">2000</option>
            <option value="2001">2001</option>
            <option value="2002">2002</option>
            <option value="2003">2003</option>
            <option value="2004">2004</option>
            <option value="2005">2005</option>
            <option value="2006">2006</option>
            <option value="2007">2007</option>
            <option value="2008">2008</option>
            <option value="2009" selected>2009</option>
            <option value="2010">2010</option>
            <option value="2011">2011</option>
            <option value="2012">2012</option>
            <option value="2013">2013</option>
            <option value="2014">2014</option>
            <option value="2015">2015</option>
            <option value="2016">2016</option>
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
          </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
</table>
<p align="center">

<div id="solicitudes" align="center"></div></p>
</form>   
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
	function ue_gendisk()
	{
		f=document.formulario;
		mes1=f.cmbmesdesde.value;
		mes2=f.cmbmeshasta.value;
		if(mes1>mes2)
		{
			alert("Los meses estan errados");
		}
		else
		{
			f.operacion.value="GENDISK";
			f.action="sigesp_scb_r_declaracionxml.php";
			f.submit();	  
		}
	}
	function ue_cerrar()
	{
		location.href = "sigespwindow_blank.php";
	}
function ue_descargar()
{
	f=document.formulario;
	pagina="sigesp_scb_cat_directorioxml.php?ruta=declaracion";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,left=50,top=50");  
}

</script> 
</html>