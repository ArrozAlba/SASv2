<?php 
session_start(); 
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPG";
	$ls_ventanas="sigesp_spg_p_progrep.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}
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
</script><title>Programacion de Reportes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="styleshee t" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="js/valida_tecla_grid.js"></script>
<style type="text/css">
<!--
.Estilo2 {font-size: 15px}
.Estilo3 {font-size: 11px}
-->
</style>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo4 {color: #6699CC}
-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
  <table width="798" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="1220" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="798" height="40"></td>
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
      <td height="20" class="toolbar">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" class="toolbar"><img src="../shared/imagebank/tools20/espacio.gif" width="4" height="20"><a href="javascript: ue_guardar();"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20"></td>
    </tr>
  </table>
  <p>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	require_once("sigesp_spg_class_progrep.php");
	require_once("../shared/class_folder/grid_param.php");
	$io_include = new sigesp_include();
	$io_connect= $io_include->uf_conectar();
	$io_sql=new class_sql($io_connect);
	$io_msg=new class_mensajes();
	$io_function=new class_funciones();
    $io_fecha=new class_fecha();
	$io_class_progrep=new sigesp_spg_class_progrep();
	$sig_int=new class_sigesp_int();
	$int_spg=new class_sigesp_int_spg();
	$ds_progrep=new class_datastore();
	$io_class_grid=new grid_param();

	if(array_key_exists("operacion",$_POST))
	{
	  $ls_operacion=$_POST["operacion"];
	}
	else
	{
	  $ls_operacion="";
	}
	
	if(array_key_exists("li_totnum",$_POST))
	{
	  $li_totnum=$_POST["li_totnum"];
	}
	else
	{
	  $li_totnum=0;
	}

	if(array_key_exists("radiobutton",$_POST))
	{
	  $ls_opcion=$_POST["radiobutton"];
	}
	else
	{
	  $ls_opcion="";
	}
	
	if (array_key_exists("txtDenominacion",$_POST))
	{
	  $ls_denominacion=$_POST["txtDenominacion"];
	}
	else
	{
	  $ls_denominacion="";
	}

	if	(array_key_exists("cmbrep",$_POST))
	{
	  $ls_codrep=$_POST["cmbrep"];
	}
	else
	{
	  $ls_codrep="0704";
	}

	if	(array_key_exists("codestpro1",$_POST))
	{
	  $ls_codestpro1=$_POST["codestpro1"];
	}
	else
	{
	  $ls_codestpro1="";
	}
	
	if	(array_key_exists("denestpro1",$_POST))
	{
	  $ls_denestpro1=$_POST["denestpro1"];
	}
	else
	{
	  $ls_denestpro1="";
	}	
	
	if	(array_key_exists("codestpro2",$_POST))
	{
	  $ls_codestpro2=$_POST["codestpro2"];
	}
	else
	{
	  $ls_codestpro2="";
	}
		
	if	(array_key_exists("denestpro2",$_POST))
	{
	  $ls_denestpro2=$_POST["denestpro2"];
	}
	else
	{
	  $ls_denestpro2="";
	}		
	
	if	(array_key_exists("codestpro3",$_POST))
	{
	  $ls_codestpro3=$_POST["codestpro3"];
	}
	else
	{
	  $ls_codestpro3="";
	}
	
	if	(array_key_exists("denestpro3",$_POST))
	{
	  $ls_denestpro3=$_POST["denestpro3"];
	}
	else
	{
	  $ls_denestpro3="";
	}			
	if	(array_key_exists("codestpro4",$_POST))
	{
	  $ls_codestpro4=$_POST["codestpro4"];
	}
	else
	{
	  $ls_codestpro4="";
	}
	
	if	(array_key_exists("denestpro4",$_POST))
	{
	  $ls_denestpro4=$_POST["denestpro4"];
	}
	else
	{
	  $ls_denestpro4="";
	}
	if	(array_key_exists("codestpro5",$_POST))
	{
	  $ls_codestpro5=$_POST["codestpro5"];
	}
	else
	{
	  $ls_codestpro5="";
	}
	
	if	(array_key_exists("denestpro5",$_POST))
	{
	  $ls_denestpro5=$_POST["denestpro5"];
	}
	else
	{
	  $ls_denestpro5="";
	}			
	if  (array_key_exists("estcla",$_POST))
	{
		$ls_estcla=$_POST["estcla"];
	}
	else
	{
		$ls_estcla="";
	}	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	}
	else
	{
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
 ?>
  </p>
  <table width="798" height="224" border="0" align="center">
    <tr>
      <td width="777"><table width="580" height="278" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
<tr>
              <td height="24" colspan="3" class="titulo-ventana"><div align="center">Programaci&oacute;n de Reporte </div></td>
            </tr>
            <tr>
              <td height="18" colspan="3"><span class="Estilo2"></span></td>
            </tr>
            <tr>
  <?php
	  if ($ls_codrep=='0406')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="selected";
	  	$ej_f0506="";
	  }
	  
	  if ($ls_codrep=='0704')
	  {
	    $ej_f0704="selected";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  
	  if ($ls_codrep=='0705')
	  {
	    $ej_f0704="";
		$ej_f0705="selected";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  
	  if ($ls_codrep=='0707')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="selected";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  
	  if ($ls_codrep=='0402')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="selected";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  
	  if ($ls_codrep=='0413')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="selected";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0414')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="selected";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";	
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0415')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="selected";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='00005')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="selected";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0714')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="selected";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0503')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="selected";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0516')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="selected";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";	
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0517')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="selected";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0518')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="selected";
		$ej_f0514="";
	  	$ej_f0406="";	
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0514')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="selected";
	  	$ej_f0406="";
	  	$ej_f0506="";
	  }
	  if ($ls_codrep=='0506')
	  {
	    $ej_f0704="";
		$ej_f0705="";
		$ej_f0707="";
		$ej_f0402="";
		$ej_f0413="";
		$ej_f0414="";
		$ej_f0415="";
		$ej_f00005="";
		$ej_f0714="";
		$ej_f0503="";
		$ej_f0516="";
		$ej_f0517="";
		$ej_f0518="";
		$ej_f0514="";
	  	$ej_f0406="";
	  	$ej_f0506="selected";
	  }
	?>
              <td width="143" height="21"><div align="right">Reporte</div></td>
              <td width="435" colspan="2"><select name="cmbrep" id="select" onChange="uf_cargargrid()">
                <option value="0704" <?php  print $ej_f0704 ?>>Comparado Forma 0704</option>
                <option value="0705" <?php  print $ej_f0705 ?>>Comparado Forma 0705</option>
                <option value="0707" <?php  print $ej_f0707 ?>>Comparado Forma 0707</option>
                <option value="0402" <?php  print $ej_f0402 ?>>Comparado Forma 0407</option>
                <option value="0413" <?php  print $ej_f0413 ?>>Comparado Forma 0405</option>
                <option value="0415" <?php  print $ej_f0415 ?>>Comparado Forma 0415</option>
                <option value="00005"<?php  print $ej_f00005 ?>>Comparado Flujo de Caja</option>
                <option value="0714" <?php  print $ej_f0714 ?>>Comparado Forma 0714</option>
                <option value="0503" <?php  print $ej_f0503 ?>>Comparado Forma0503</option>
                <option value="0514" <?php  print $ej_f0514 ?>>Comparado Forma0514</option>
                <option value="0516" <?php  print $ej_f0516 ?>>Comparado Forma0516</option>
                <option value="0517" <?php  print $ej_f0517 ?>>Comparado Forma0517</option>
                <option value="0518" <?php  print $ej_f0518 ?>>Comparado Forma0518</option>
                <option value="0406" <?php  print $ej_f0406 ?>>Comparado Estado Resultado Forma0406</option>
                <option value="0506" <?php  print $ej_f0506 ?>>Comparado Recursos Humanos Forma 0506</option>
			  </select>
              <input name="botRecargar" type="button" class="boton" id="botRecargar" onClick="ue_recargar()" value="Cargar"></td>
            </tr>
            <tr>
      <?php
         $la_empresa    =  $_SESSION["la_empresa"];
		 $li_estmodest  = $la_empresa["estmodest"];
		 $ls_NomEstPro1 = $la_empresa["nomestpro1"];
		 $ls_NomEstPro2 = $la_empresa["nomestpro2"];
		 $ls_NomEstPro3 = $la_empresa["nomestpro3"];
		 $ls_NomEstPro4 = $la_empresa["nomestpro4"];
		 $ls_NomEstPro5 = $la_empresa["nomestpro5"];

         $ls_loncodestpro1 = $la_empresa["loncodestpro1"]+10;
		 $ls_loncodestpro2 = $la_empresa["loncodestpro2"]+10;
		 $ls_loncodestpro3 = $la_empresa["loncodestpro3"]+10;
		 $ls_loncodestpro4 = $la_empresa["loncodestpro4"]+10;
		 $ls_loncodestpro5 = $la_empresa["loncodestpro5"]+10;
	  ?>
            <td height="20"><div align="right"><span class="Estilo3">
                </span><?php print $ls_NomEstPro1;?></div>
                  <div align="left"></div></td>
              <td colspan="2">
                <input name="codestpro1" type="text" id="codestpro12" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" readonly>
                <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
                <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1 ?>" size="45">
                <div align="right"></div>
                <div align="center"> </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="20"><div align="right"><?php print $ls_NomEstPro2;?></div></td>
              <td colspan="2"><input name="codestpro2" type="text" id="codestpro2" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" readonly>
                  <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
                  <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php print $ls_denestpro2 ?>" size="45"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="20"><div align="right"><?php print $ls_NomEstPro3;?></div></td>
              <td colspan="2"><input name="codestpro3" type="text" id="codestpro3" style="text-align:center"  value="<?php print $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" readonly>
                  <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
                  <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php print $ls_denestpro3 ?>" size="45"></td>
            </tr>
			  <?php
				 if($li_estmodest==2)
				 {	
			  ?>
            <tr>
              <td height="20"><div align="right"><?php print $ls_NomEstPro4;?></div></td>
              <td colspan="2"><input name="codestpro4" type="text" id="codestpro4" style="text-align:center"  value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" readonly>
              <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"></a><input name="denestpro4" type="text" class="sin-borde" id="denestpro4" value="<?php print $ls_denestpro4 ?>" size="45" readonly></td>
            </tr>
            <tr>
              <td height="20"><div align="right"><?php print $ls_NomEstPro5;?></div></td>
              <td colspan="2"><input name="codestpro5" type="text" id="codestpro5" style="text-align:center"  value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" readonly>
              <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"></a><input name="denestpro5" type="text" class="sin-borde" id="denestpro5" value="<?php print $ls_denestpro5 ?>" size="45" readonly></td>
            </tr>
			  <?php  
			  }
			  ?>
            <tr>
              <td height="13" colspan="3">&nbsp;</td>
            </tr>
<tr>
              <td height="22" colspan="3"><div align="center">
 <?php	
 //Titulos de la tabla
 $title[1]="Cuenta";        $title[2]="Denominaci&oacute;n";  $title[3]="Asignaci&oacute;n"; 
 $title[4]="Distribucion";  $ls_nombre="grid_progrep";
if($ls_operacion == "")
{
   $li_total=0;
   $object="";
   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES ',$ls_nombre);  
}//$ls_operacion == ""

if($ls_operacion == "RECARGAR")
{
   $ls_codrep = $_POST["cmbrep"];
   $ls_codestpro1 = $_POST["codestpro1"];
   $ls_codestpro2 = $_POST["codestpro2"];
   $ls_codestpro3 = $_POST["codestpro3"];
   $li_estmodest = $la_empresa["estmodest"];
   if($li_estmodest==2)
   {
	    $ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro4=$io_function->uf_cerosizquierda($ls_codestpro4,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro5=$io_function->uf_cerosizquierda($ls_codestpro5,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
   }
   else
   {
	    $ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro4=$io_function->uf_cerosizquierda(0,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro5=$io_function->uf_cerosizquierda(0,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
   }
   $ls_estcla = $_POST["estcla"];   
   $lb_valido=$io_class_progrep->uf_prog_report_load_original(trim($ls_codrep),$ls_codestpro1,$ls_codestpro2,
                                                              $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
															  $la_seguridad,$ls_estcla);
   if(($lb_valido)&&($ls_codrep=='0714'))
   {
     $lb_valido=$io_class_progrep->uf_prog_report_load_data_0714($ls_codrep,$la_seguridad);		
   } 													  
   $ls_operacion="CARGAR" ;
}//operacion=="RECARGAR"

if($ls_operacion=="CARGAR")
{
	 $ls_codrep=$_POST["cmbrep"];
	 $ls_codestpro1=$_POST["codestpro1"];
	 $ls_codestpro2=$_POST["codestpro2"];
	 $ls_codestpro3=$_POST["codestpro3"];
	 $li_estmodest  = $la_empresa["estmodest"];
	 if($li_estmodest==2)
	 {
			$ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
			$ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
			$ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
			$ls_codestpro4=$io_function->uf_cerosizquierda($ls_codestpro4,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
			$ls_codestpro5=$io_function->uf_cerosizquierda($ls_codestpro5,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	 }
	 else
	 {
			$ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
			$ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
			$ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
			$ls_codestpro4=$io_function->uf_cerosizquierda(0,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
			$ls_codestpro5=$io_function->uf_cerosizquierda(0,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	 }
     $ls_modrep=1;
     $ls_estcla = $_POST["estcla"];   
     $rs_load=$io_class_progrep->uf_cargar_reporte($ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
	                                               $ls_codestpro4,$ls_codestpro5,$ls_modrep,$ls_estcla);
	 if($row=$io_sql->fetch_row($rs_load))
	 {
		$data=$io_sql->obtener_datos($rs_load);
		$ds_progrep->data=$data;
		$li_num=$ds_progrep->getRowCount("spg_cuenta");
		$li_totnum=$li_num;
		for($i=1;$i<=$li_num;$i++)
		{    
			$ls_cuenta=$data["spg_cuenta"][$i]; 
            $ls_cuenta_hidden=$data["spg_cuenta"][$i]; 
			if($ls_codrep=='0714')
			{
				if(($ls_cuenta=='401010000')||($ls_cuenta=='401020000')||($ls_cuenta=='402010000')||($ls_cuenta=='402020000')||($ls_cuenta=='403010000')||
				   ($ls_cuenta=='403020000')||($ls_cuenta=='407010000')||($ls_cuenta=='407020000')||($ls_cuenta=='408010000')||($ls_cuenta=='408020000'))
				{
				  $ls_cuenta="''";
				} 
			}	
			$ls_denominacion=$data["denominacion"][$i];
			$ls_distribuir=$data["distribuir"][$i];
			$ls_status=$data["status"][$i];
			$ls_referencia=$data["referencia"][$i];
			$ld_asignado=number_format($data["asignado"][$i],2,",",".");
			$ld_enero=number_format($data["enero"][$i],2,",",".");
			$ld_febrero=number_format($data["febrero"][$i],2,",",".");
			$ld_marzo=number_format($data["marzo"][$i],2,",",".");
			$ld_abril=number_format($data["abril"][$i],2,",",".");
			$ld_mayo=number_format($data["mayo"][$i],2,",",".");
			$ld_junio=number_format($data["junio"][$i],2,",",".");
			$ld_julio=number_format($data["julio"][$i],2,",",".");
			$ld_agosto=number_format($data["agosto"][$i],2,",",".");
			$ld_septiembre=number_format($data["septiembre"][$i],2,",",".");
			$ld_octubre=number_format($data["octubre"][$i],2,",",".");
			$ld_noviembre=number_format($data["noviembre"][$i],2,",",".");
			$ld_diciembre=number_format($data["diciembre"][$i],2,",",".");
			$ar_asignado[$i]=$ld_asignado;
			
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado style=text-align:right readonly><input name=enero".$i." type=hidden id=enero".$i." value='$ld_enero'>
								<input name=febrero".$i." type=hidden id=febrero".$i." value='$ld_febrero'><input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=abril".$i." type=hidden id=abril".$i." value='$ld_abril'><input name=mayo".$i." type=hidden id=mayo".$i." value='$ld_mayo'>
								<input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'><input name=julio".$i." type=hidden id=julio".$i." value='$ld_julio'><input name=agosto".$i." type=hidden id=agosto".$i." value='$ld_agosto'><input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'>
								<input name=octubre".$i." type=hidden id=octubre".$i." value='$ld_octubre'><input name=noviembre".$i." type=hidden id=noviembre".$i." value='$ld_noviembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			    $object[$i][4] ="<a href=javascript:ue_distribuir(".($i).");><img src=../shared/imagebank/tools15/aprobado.gif width=15 height=15 border=0 style=text-align:center alt=Distribucion></a>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right><input name=enero".$i." type=hidden id=enero".$i." value='$ld_enero'>
								<input name=febrero".$i." type=hidden id=febrero".$i." value='$ld_febrero'><input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=abril".$i." type=hidden id=abril".$i." value='$ld_abril'><input name=mayo".$i." type=hidden id=mayo".$i." value='$ld_mayo'>
								<input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'><input name=julio".$i." type=hidden id=julio".$i." value='$ld_julio'><input name=agosto".$i." type=hidden id=agosto".$i." value='$ld_agosto'><input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'>
								<input name=octubre".$i." type=hidden id=octubre".$i." value='$ld_octubre'><input name=noviembre".$i." type=hidden id=noviembre".$i." value='$ld_noviembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			    $object[$i][4] ="<a href=javascript:ue_distribuir(".($i).");><img src=../shared/imagebank/tools15/aprobado.gif width=15 height=15 border=0 style=text-align:center alt=Distribucion></a>";
		   }
	    }//for  
		$li_cont_sin_cero=0; 
        $li_cont_cero=0;
		for($i=1;($i<=$li_num);$i++)
		{
		  if($ar_asignado[$i]!=0)
		  {
		    $lb_valido=true;
			$li_cont_sin_cero=$li_cont_sin_cero+1;
		  }
		  else
		  {
		    $lb_valido=false;
			$li_cont_cero=$li_cont_cero+1;
		  }
		}
	    if($li_cont_cero!=$li_num)
		{ 
		  $io_class_grid->makegrid($li_totnum,$title,$object,800,'PROGRAMACION DE REPORTE',$ls_nombre);     
		}
		else
		{
		   $io_msg->message(" Debe hacer la distribucion de la apertura antes...."); 
		   $li_total=0;
		   $object="";
		   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES ',$ls_nombre);  
		}
	  }//if
	  else
	  {
	       $li_total=0;
		   $object="";
		   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES ',$ls_nombre);  
	  }
 }//cargar
 
?>
                <input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"]?>">
                <input name="li_totnum" type="hidden" id="li_totnum" value="<?php print $li_totnum; ?>">
                <input name="fila" type="hidden" id="fila">
                <input name="tipo" type="hidden" id="tipo">
				<input name="estclades" type="hidden" id="estclades">
                <a href="javascript: ue_showouput();"><span class="Estilo1">
                <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
                </span></a>
                <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla;?>">
              </div></td>
            </tr>
        </table>
      </td>
    </tr>
  </table>
  </form>
</body>
<script language="javascript">
function uf_cargargrid()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigesp_spg_p_progrep.php";
	f.submit();
}

function ue_recargar()
{
    f=document.form1;
    cmbrep        = f.cmbrep.value;
	ls_codestpro1 = f.codestpro1.value;
	ls_codestpro2 = f.codestpro2.value
	ls_codestpro3 = f.codestpro3.value
	if ((cmbrep!='00005') && (cmbrep!='0506')) 
	{	
	   lb_ok=true;
	}
	else
	{
	   lb_ok=false;
	}
	resp=confirm("Este proceso borrara todas las cuentas y las copiara del plan original(Todas las programatica). ¿ Esta seguro de proceder ?");
	if (resp==true)
    {
	   if((ls_codestpro1=='' || ls_codestpro2=='' || ls_codestpro3=='') && (lb_ok))
	   {
			 alert(" Por Favor Seleccione una Estructura Programatica....");
	   }
	   else
	   {
			f.operacion.value="RECARGAR";
			f.action="sigesp_spg_p_progrep.php";
			f.submit();
	  }	
   }
}

function ue_distribuir(li)
{
    var i ;
    f=document.form1;
    cmbrep=f.cmbrep.value;
	cuenta="cuenta"+li;
	ls_cuenta_aux=eval("f."+cuenta+".value");
	if ((cmbrep!='00005') && (cmbrep!='0506')) 
	{	
	   lb_ok=true;
	}
	else
	{
	   lb_ok=false;
	}
	if( (f.codestpro1.value=="" || f.codestpro2.value=="" || f.codestpro3.value=="") && (lb_ok) ) 
    {
	  alert(" Por Favor Seleccione una Estructura Programatica....");
    }
    else
    {
		    document.opcion = "A"; 
		    f=document.form1;
		    ls_distribuir=1;
		    distribuir="distribuir"+li;
		    eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
		    li_total=f.li_totnum.value;
		    opcion=document.opcion;
		    txtCuenta="txtCuenta"+li;
			ls_cuenta=eval("f."+txtCuenta+".value");
			txtDenominacion="txtDenominacion"+li;
			ls_denominacion=eval("f."+txtDenominacion+".value");
			txtAsignacion="txtAsignacion"+li;
			ld_asignado=eval("f."+txtAsignacion+".value");
			enero="enero"+li;
			ld_enero=eval("f."+enero+".value");
			febrero="febrero"+li;
			ld_febrero=eval("f."+febrero+".value");
			marzo="marzo"+li;
			ld_marzo=eval("f."+marzo+".value");
			abril="abril"+li;
			ld_abril=eval("f."+abril+".value");
			mayo="mayo"+li;
			ld_mayo=eval("f."+mayo+".value");
			junio="junio"+li;
			ld_junio=eval("f."+junio+".value");
			julio="julio"+li;
			ld_julio=eval("f."+julio+".value");
			agosto="agosto"+li;
			ld_agosto=eval("f."+agosto+".value");
			septiembre="septiembre"+li;
			ld_septiembre=eval("f."+septiembre+".value");
			octubre="octubre"+li;
			ld_octubre=eval("f."+octubre+".value");
			noviembre="noviembre"+li;
			ld_noviembre=eval("f."+noviembre+".value");
			diciembre="diciembre"+li;
			ld_diciembre=eval("f."+diciembre+".value");
			distribuir="distribuir"+li;
		    pagina="sigesp_spg_p_progrep_distribucion.php?fila="+li+"&txtAsignacion="+ld_asignado+"&enero="+ld_enero
					 +"&febrero="+ld_febrero+"&marzo="+ld_marzo+"&abril="+ld_abril+"&mayo="+ld_mayo+"&junio="+ld_junio+"&julio="+ld_julio
					 +"&agosto="+ld_agosto+"&septiembre="+ld_septiembre+"&octubre="+ld_octubre+"&noviembre="+ld_noviembre
					 +"&diciembre="+ld_diciembre+"&txtCuenta="+ls_cuenta+"&txtDenominacion="+ls_denominacion+"&tipo="+opcion;
		    window.open(pagina,"Asignación","menubar=no,toolbar=no,scrollbars=no,width=650,height=450,left=50,top=50,resizable=yes,location=no");
    }
}
function EvaluateText(cadena, obj)
{ 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f")
	{ 
     if (event.keyCode > 47 && event.keyCode < 58) 
      opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
      if (event.keyCode == 46) 
       opc = true; 
    } 
	 if (cadena == "%s") // toma numero y letras
     if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
      opc = true; 
	 if (cadena == "%c") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
      opc = true; 
    if(opc == false) 
     event.returnValue = false; 
   } 
function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;	
	estmodest=f.estmodest.value;
	estcla=f.estcla.value;
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
	estcla=f.estcla.value;
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
	estcla=f.estcla.value;
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
	estcla=f.estcla.value;
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

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}
//--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
    	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
    	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
     	fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
}
</script>
</html>