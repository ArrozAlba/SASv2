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
	$ls_ventanas="sigesp_spg_p_progrep_trim.php";

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
</script>
<title>Programaci&oacute;n de Reportes Trimestral</title>
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
  <table width="798" height="90" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="1007" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="798" height="40"></td>
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
      <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
    </tr>
    <tr>
      <td height="20" class="toolbar">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" class="toolbar"><img src="../shared/imagebank/tools20/espacio.gif" width="4" height="20"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
    </tr>
  </table>
  <p><?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("sigesp_spg_class_progrep.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	require_once("../shared/class_folder/grid_param.php");
	$io_include = new sigesp_include();
	$io_connect= $io_include->uf_conectar ();
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
	//Radio Button
	if  (array_key_exists("radiobutton",$_POST))
	{
	  $ls_distribucion=$_POST["radiobutton"];
	}
	else
	{
	  $ls_distribucion="";
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
  <table width="798" height="169" border="0" align="center">
    <tr>
      <td width="833"><table width="580" height="266" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="21" colspan="5" class="titulo-ventana"><div align="center">Programaci&oacute;n de Reporte Trimestral</div></td>
        </tr>
        <tr>
          <td height="18" colspan="5"><span class="Estilo2"></span></td>
        </tr>
        <tr>
      <?php
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
	  }
	?>
          <td width="144" height="21"><div align="right">Reporte</div></td>
          <td colspan="4"><select name="cmbrep" id="select2" onChange="uf_cargargrid()">
                <option value="0704" <?php  print $ej_f0704 ?>>Comparado - Forma0704</option>
                <option value="0705" <?php  print $ej_f0705 ?>>Comparado - Forma0705</option>
                <option value="0707" <?php  print $ej_f0707 ?>>Comparado - Forma0707</option>
                <option value="0402" <?php  print $ej_f0402 ?>>Comparado - Forma0402</option>
                <option value="0413" <?php  print $ej_f0413 ?>>Comparado - Forma0413</option>
                <option value="0414" <?php  print $ej_f0414 ?>>Comparado - Forma0414</option>
                <option value="0415" <?php  print $ej_f0415 ?>>Comparado - Forma0415</option>
                <option value="00005"<?php  print $ej_f00005 ?>>Comparado Flujo de Caja</option>
                <option value="0714" <?php  print $ej_f0714 ?>>Comparado Forma 0714</option>
                <option value="0503" <?php  print $ej_f0503 ?>>Comparado Forma0503</option>
                <option value="0514" <?php  print $ej_f0514 ?>>Comparado Forma0514</option>
                <option value="0516" <?php  print $ej_f0516 ?>>Comparado Forma0516</option>
                <option value="0517" <?php  print $ej_f0517 ?>>Comparado Forma0517</option>
                <option value="0518" <?php  print $ej_f0518 ?>>Comparado Forma0518</option>
            </select>
              <input name="botRecargar" type="button" class="boton" id="botRecargar" onClick="ue_recargar()" value="Recargar"></td>
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
	     $lb_valido=$io_class_progrep->uf_llenar_combo_estpro1($rs_SPG);
	    ?>
          <td height="20"><div align="right"><span class="Estilo3">
        <?php
		 if($lb_valido)
		 {		  
	        $lb_valido=$io_class_progrep->uf_llenar_combo_estpro2($ls_codestpro1,$rs_SPG2);
		 }	
		 if($li_estmodest==1)
		 {	

	     ?>
            </span><?php print $ls_NomEstPro1;?></div>
              <div align="left"></div></td>
          <td colspan="4">
            <input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="22" maxlength="20" readonly>
            <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
            <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1 ?>" size="45">
            <div align="right"></div>
            <div align="center"> </div></td>
        </tr>
        <tr class="formato-blanco">
          <td height="20"><div align="right"><?php print $ls_NomEstPro2;?></div></td>
          <td colspan="4"><input name="codestpro2" type="text" id="codestpro2" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="22" maxlength="6" readonly>
              <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
              <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php print $ls_denestpro2 ?>" size="45"></td>
          <?php 
			 if($lb_valido)
			 {		  
			    $lb_valido=$io_class_progrep->uf_llenar_combo_estpro3($ls_codestpro1,$ls_codestpro2,$rs_SPG3);
			 }	
		   ?>
        </tr>
        <tr class="formato-blanco">
          <td height="20"><div align="right"><?php print $ls_NomEstPro3;?></div></td>
          <td colspan="4"><input name="codestpro3" type="text" id="codestpro3" style="text-align:center"  value="<?php print $ls_codestpro3 ?>" size="22" maxlength="3" readonly>
              <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
              <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php print $ls_denestpro3 ?>" size="45"></td>
        </tr>
        <tr>
          <td height="20"><div align="right"><span class="Estilo3">
         <?php
		 }		  
		 if($lb_valido)
		 {		  
	        $lb_valido=$io_class_progrep->uf_llenar_combo_estpro2($ls_codestpro1,$rs_SPG2);
		 }
		 if($li_estmodest==2)
		 {	
	  ?>
            </span><?php print $ls_NomEstPro1;?></div>
              <div align="left"></div></td>
          <td colspan="4"><input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="5" maxlength="2" readonly>
              <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"> </a>
            <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1 ?>" size="45" readonly>          </td>
        </tr>
        <tr>
          <td height="20"><div align="right"><?php print $ls_NomEstPro2;?></div></td>
          <td colspan="4"><input name="codestpro2" type="text" id="codestpro2" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="5" maxlength="2" readonly>
              <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"> </a>
            <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php print $ls_denestpro2 ?>" size="45" readonly>          </td>
        </tr>
        <tr>
          <td height="20"><div align="right"><?php print $ls_NomEstPro3;?></div></td>
          <td colspan="4"><input name="codestpro3" type="text" id="codestpro3" style="text-align:center"  value="<?php print $ls_codestpro3 ?>" size="5" maxlength="2" readonly>
              <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"> </a>
            <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php print $ls_denestpro3 ?>" size="45" readonly>          </td>
        </tr>
        <tr>
          <td height="20"><div align="right"><?php print $ls_NomEstPro4;?></div></td>
          <td colspan="4"><input name="codestpro4" type="text" id="codestpro4" style="text-align:center"  value="<?php print $ls_codestpro4 ?>" size="5" maxlength="2" readonly>
              <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"> </a>
            <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" value="<?php print $ls_denestpro4 ?>" size="45" readonly>          </td>
        </tr>
        <tr>
          <td height="20"><div align="right"><?php print $ls_NomEstPro5;?></div></td>
          <td colspan="4"><input name="codestpro5" type="text" id="codestpro5" style="text-align:center"  value="<?php print $ls_codestpro5 ?>" size="5" maxlength="2" readonly>
              <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"> </a>
            <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" value="<?php print $ls_denestpro5 ?>" size="45" readonly>          </td>
          <?php  
		  }
		  ?>
        </tr>
        <tr>
          <td height="20"><div align="right">Distribuci&oacute;n</div></td>
			  <?Php 	 
				  if(($ls_distribucion=="N")||($ls_distribucion==""))
				  {
						$ls_ninguno="checked";		
						$ls_auto="";
						$ls_manual="";
				  }
				  elseif($ls_distribucion=="A")
				  {
						$ls_ninguno="";		
						$ls_auto="checked";
						$ls_manual="";
				  }
				  elseif($ls_distribucion=="M")
				  {
						$ls_ninguno="";		
						$ls_auto="";
						$ls_manual="checked";
				  }
		    ?>
          <td width="123"><input name="radiobutton" type="radio" value="N" <?php print $ls_ninguno ?>>
      Ninguno</td>
          <td width="113">
            <input name="radiobutton" type="radio" value="A" <?php print $ls_auto ?>>
      Automatico</td>
          <td width="76"><input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
      Manual </td>
          <td width="121"><a href="javascript:ue_distribuir();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Aceptar" width="15" height="15" border="0"></a></td>
        </tr>
        <tr>
          <td height="13">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td height="13" colspan="5"><div align="center">
            <?php	
 //Titulos de la tabla
 $title[1]="Cuenta";   $title[2]="Denominaci&oacute;n";  $title[3]="Asignaci&oacute;n"; $ls_nombre="grid_progrep_trim";  

if($ls_operacion == "")
{
   $li_total=0;
   $object="";
   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES TRIMESTRAL',$ls_nombre);  
}//$ls_operacion == ""

if ($ls_operacion == "RECARGAR")
{
   $ls_codrep=$_POST["cmbrep"];
   if(($ls_codrep=='00005')||($ls_codrep=='0714'))
   {
		$ls_codestpro1="00000000000000000000";
		$ls_codestpro2="000000";
		$ls_codestpro3="000";
   }
   else
   {
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codestpro3=$_POST["codestpro3"];
   }
   $li_estmodest  = $la_empresa["estmodest"];
   if($li_estmodest==1)
   {
		$ls_codestpro4="00";
		$ls_codestpro5="00";
   }
   else
   {
		$ls_codestpro4=$_POST["codestpro4"];
		$ls_codestpro5=$_POST["codestpro5"];
   }
   $lb_valido=$io_class_progrep->uf_prog_report_load_original($ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$la_seguridad);
   if(($lb_valido)&&($ls_codrep=='0714'))
   {
     $lb_valido=$io_class_progrep->uf_prog_report_load_data_0714($ls_codrep,$la_seguridad);		
   } 													  
   $li_total=0;
   $object="";
   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES ',$ls_nombre); 
   
}//operacion=="RECARGAR"

if ($ls_operacion=="CARGAR" )
{
     $la_empresa =  $_SESSION["la_empresa"];
     $ls_codrep=$_POST["cmbrep"];
     if(($ls_codrep=='00005')||($ls_codrep=='0714'))
     {
			$ls_codestpro1="00000000000000000000";
			$ls_codestpro2="000000";
			$ls_codestpro3="000";
     }
     else
     {
			$ls_codestpro1=$_POST["codestpro1"];
			$ls_codestpro2=$_POST["codestpro2"];
			$ls_codestpro3=$_POST["codestpro3"];
     }	
     $li_estmodest  = $la_empresa["estmodest"];
     if($li_estmodest==1)
     {
		$ls_codestpro4="00";
		$ls_codestpro5="00";
     }
     else
     {
		$ls_codestpro4=$_POST["codestpro4"];
		$ls_codestpro5=$_POST["codestpro5"];
     }
     $ls_modrep=3;
     $rs_load=$io_class_progrep->uf_prog_report_load_data($ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_modrep);
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
			$ls_modrep=$data["modrep"][$i];
			$ls_status=$data["status"][$i];
			$ls_referencia=$data["referencia"][$i];
			$ld_asignado=number_format($data["asignado"][$i],2,",",".");
			$ld_marzo=number_format($data["marzo"][$i],2,",",".");
			$ld_junio=number_format($data["junio"][$i],2,",",".");
			$ld_septiembre=number_format($data["septiembre"][$i],2,",",".");
			$ld_diciembre=number_format($data["diciembre"][$i],2,",",".");
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=ls_cuenta_hidden value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado style=text-align:right readonly>
				                <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
				
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde  size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
				                <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
		   }
	   }//for    
	   
	   $io_class_grid->makegrid($li_totnum,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);     
	  }//if
	  else
	  {
		   $li_total=0;
		   $object="";
		   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES TRIMESTRAL',$ls_nombre);  
	  }
 }//cargar
 
if ($ls_operacion=="DISTRIBUIR" )
{
  $ls_opcion=$_POST["tipo"];
  if ($ls_opcion=="M")
  {
    $li_rows=$_POST["fila"];
    $ls_codrep=$_POST["cmbrep"];
    if(($ls_codrep=='00005')||($ls_codrep=='0714'))
	{
		$ls_codestpro1="00000000000000000000";
		$ls_codestpro2="000000";
		$ls_codestpro3="000";
	}
	else
	{
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codestpro3=$_POST["codestpro3"];
	}	
	$li_estmodest  = $la_empresa["estmodest"];
	if($li_estmodest==1)
	{
		$ls_codestpro4="00";
		$ls_codestpro5="00";
	}
	else
	{
		$ls_codestpro4=$_POST["codestpro4"];
		$ls_codestpro5=$_POST["codestpro5"];
	}
    $li_num=$_POST["li_totnum"];
    for($i=1;$i<=$li_num;$i++)
    {    
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_asignado =$_POST["txtAsignacion".$i];
		$ls_status=$_POST["status".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_referencia=$_POST["referencia".$i];
		$ld_marzo=$_POST["marzo".$i];
		$ld_junio=$_POST["junio".$i];
		$ld_septiembre=$_POST["septiembre".$i];
		$ld_diciembre=$_POST["diciembre".$i];
		$ls_cuenta_report=$ls_cuenta_hidden;
		if($li_rows==$i)
		{
			$li_nivel=0;
		    $lb_valido  = $io_class_progrep->uf_obtener_nivel_cta($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$li_nivel);//Obtiene nivel de la cta 
			$ls_cta_ceros = $int_spg->uf_spg_cuenta_sin_cero($ls_cuenta );  //devuelve la cta sin ceros
			$ar_cuenta = $io_class_progrep->uf_disable_cta_inferior($ls_cta_ceros,$ls_cuenta,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,0);  
			$li_total_cuenta=count($ar_cuenta);
			if($ls_codrep=='0714')
			{
				if(($ls_cuenta=='401010000')||($ls_cuenta=='401020000')||($ls_cuenta=='402010000')||($ls_cuenta=='402020000')||($ls_cuenta=='403010000')||
				   ($ls_cuenta=='403020000')||($ls_cuenta=='407010000')||($ls_cuenta=='407020000')||($ls_cuenta=='408010000')||($ls_cuenta=='408020000'))
				{
				  $ls_cuenta="''";
				} 
			}	
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			
			$ls_status_in="I"; 		$ls_distribuir=3;		$ls_modrep=3;
			$cero=0;               $ld_cero=number_format($cero,2,",",".");	
			$ld_asignado_cero=$ld_cero;	
			$ld_m3=$ld_cero;			$ld_m6=$ld_cero;
			$ld_m09=$ld_cero;	        $ld_m12=$ld_cero;
				
			for($li=1;$li<$li_total_cuenta;$li++)
			{
				$ls_cuenta=$ar_cuenta[$li]; 
				$ls_denominacion="";
				$lb_valido=$io_class_progrep->uf_select_denominacion($ls_cuenta,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denominacion);
				if($lb_valido)
				{
					$i=$i+1;
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul  size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status_in'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
					$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado_cero style=text-align:right readonly>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_m3'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_m6'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_m09'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_m12'>";
			    }//if
		   }//for
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		   if($ls_referencia!="")
		   {
				for($li=$li_rows-1;$li>=1;$li--)
				{
					$ls_cuenta_aux=$_POST["txtCuenta".$li];   
					$ls_denominacion_aux=$_POST["txtDenominacion".$li];
		            $ld_asignado_aux =$_POST["txtAsignacion".$li];
					$ls_status_aux=$_POST["status".$li];
					$ls_referencia_aux=$_POST["referencia".$li];
					$ls_distribuir_aux=$ls_distribuir;
					$ls_modrep_aux=$ls_modrep;
					$ld_marzo_aux=$_POST["marzo".$li];
					$ld_junio_aux=$_POST["junio".$li];
					$ld_septiembre_aux=$_POST["septiembre".$li];
					$ld_diciembre_aux=$_POST["diciembre".$li];
					if($ls_cuenta_aux==$ls_referencia)
					{						
						$ld_asignado=str_replace('.','',$ld_asignado);              $ld_asignado=str_replace(',','.',$ld_asignado);
						$ld_asignado_aux=str_replace('.','',$ld_asignado_aux);	    $ld_asignado_aux=str_replace(',','.',$ld_asignado_aux);
						$ld_marzo=str_replace('.','',$ld_marzo);		    		$ld_marzo=str_replace(',','.',$ld_marzo);
						$ld_marzo_aux=str_replace('.','',$ld_marzo_aux);		    $ld_marzo_aux=str_replace(',','.',$ld_marzo_aux);
						$ld_junio=str_replace('.','',$ld_junio);		    		$ld_junio=str_replace(',','.',$ld_junio);
						$ld_junio_aux=str_replace('.','',$ld_junio_aux);		    $ld_junio_aux=str_replace(',','.',$ld_junio_aux);
						$ld_septiembre=str_replace('.','',$ld_septiembre);			$ld_septiembre=str_replace(',','.',$ld_septiembre);
						$ld_septiembre_aux=str_replace('.','',$ld_septiembre_aux);	$ld_septiembre_aux=str_replace(',','.',$ld_septiembre_aux);
						$ld_diciembre=str_replace('.','',$ld_diciembre);			$ld_diciembre=str_replace(',','.',$ld_diciembre);
						$ld_diciembre_aux=str_replace('.','',$ld_diciembre_aux);	$ld_diciembre_aux=str_replace(',','.',$ld_diciembre_aux);
						
						$ld_asignado_aux=$ld_asignado_aux+$ld_asignado;
						$ld_marzo_aux=$ld_marzo_aux+$ld_marzo;
						$ld_junio_aux=$ld_junio_aux+$ld_junio;
						$ld_septiembre_aux=$ld_septiembre_aux+$ld_septiembre;
						$ld_diciembre_aux=$ld_diciembre_aux+$ld_diciembre;
						
						$ld_asignado_aux=number_format($ld_asignado_aux,2,",",".");
						$ld_asignado=number_format($ld_asignado,2,",",".");
						$ld_marzo=number_format($ld_marzo,2,",",".");
						$ld_marzo_aux=number_format($ld_marzo_aux,2,",",".");
						$ld_junio=number_format($ld_junio,2,",",".");
						$ld_junio_aux=number_format($ld_junio_aux,2,",",".");
						$ld_septiembre=number_format($ld_septiembre,2,",",".");
						$ld_septiembre_aux=number_format($ld_septiembre_aux,2,",",".");
						$ld_diciembre=number_format($ld_diciembre,2,",",".");
						$ld_diciembre_aux=number_format($ld_diciembre_aux,2,",",".");
						

						$object[$li][1]="<input type=text name=txtCuenta".$li." value=$ls_cuenta_aux class=sin-borde  size=10 readonly><input name=referencia".$li." type=hidden id=referencia value='$ls_referencia_aux'><input name=status".$li." type=hidden id=status value='$ls_status_aux'><input name=cuenta".$li." type=hidden id=cuenta value='$ls_cuenta_aux'><input name=distribuir".$li." type=hidden id=distribuir value='$ls_distribuir_aux'><input name=modrep".$li." type=hidden id=modrep value='$ls_modrep_aux'>";
						$object[$li][2]="<input type=text name=txtDenominacion".$li." value='$ls_denominacion_aux' size=105 class=sin-borde readonly >";
						$object[$li][3]="<input type=text name=txtAsignacion".$li." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado_aux  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$li.") style=text-align:right>
										<input name=marzo".$li." type=hidden id=marzo".$li." value='$ld_marzo_aux'><input name=junio".$li." type=hidden id=junio".$li." value='$ld_junio_aux'>
										<input name=septiembre".$li." type=hidden id=septiembre".$li." value='$ld_septiembre_aux'><input name=diciembre".$li." type=hidden id=diciembre".$li." value='$ld_diciembre_aux'>";
                        $ls_referencia=$ls_referencia_aux;				   
				   }//if
				}//for	
		   }//if				
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}//if   
		else
		{
			 if($ls_codrep=='0714')
			 {
				$ls_distribuir=3;			$ls_modrep=1;			
				if(($ls_cuenta_report=='401010000')||($ls_cuenta_report=='401020000')||($ls_cuenta_report=='402010000')||($ls_cuenta_report=='402020000')||($ls_cuenta_report=='403010000')||
				   ($ls_cuenta_report=='403020000')||($ls_cuenta_report=='407010000')||($ls_cuenta_report=='407020000')||($ls_cuenta_report=='408010000')||($ls_cuenta_report=='408020000'))
				{					
					$ls_cuenta="''";
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
					$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado style=text-align:right readonly>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
				  
				}
				else
				{ 
					$ls_cuenta=$ls_cuenta_report;
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
					$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado style=text-align:right readonly>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			   }	
			 }
			 else
			 {
				if($ls_status=="I")
				{
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
					$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado style=text-align:right readonly>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
				}
				else
				{
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde  size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
					$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			   }//else
			}//else	   
	    }//else
   }//for 
   $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre); 
}//if ($ls_opcion=="M")
  	
if ($ls_opcion=="N")
{
    $li_rows=$_POST["fila"];
    $ls_codrep=$_POST["cmbrep"];
    if(($ls_codrep=='00005')||($ls_codrep=='0714'))
	{
		$ls_codestpro1="00000000000000000000";
		$ls_codestpro2="000000";
		$ls_codestpro3="000";
	}
	else
	{
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codestpro3=$_POST["codestpro3"];
	}	
	$li_estmodest  = $la_empresa["estmodest"];
	if($li_estmodest==1)
	{
		$ls_codestpro4="00";
		$ls_codestpro5="00";
	}
	else
	{
		$ls_codestpro4=$_POST["codestpro4"];
		$ls_codestpro5=$_POST["codestpro5"];
	}
    $li_num=$_POST["li_totnum"];
	for($i=1;$i<=$li_num;$i++)
    {      
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_cuenta_hidden=$_POST["cuenta".$i];
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_asignado =$_POST["txtAsignacion".$i];
		$ls_status=$_POST["status".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_referencia=$_POST["referencia".$i];
		$ld_marzo=$_POST["marzo".$i];
		$ld_junio=$_POST["junio".$i];
		$ld_septiembre=$_POST["septiembre".$i];
		$ld_diciembre=$_POST["diciembre".$i];
		$ls_cuenta_report=$ls_cuenta_hidden;
		
		if( $li_rows==$i)
		{
			$li_nivel=0;
			$lb_valido  = $io_class_progrep->uf_obtener_nivel_cta($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$li_nivel);//Obtiene nivel de la cta 
			$ls_cta_ceros = $int_spg->uf_spg_cuenta_sin_cero($ls_cuenta );  //devuelve la cta sin ceros
			$ar_cuenta = $io_class_progrep->uf_disable_cta_inferior($ls_cta_ceros,$ls_cuenta,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,0);  
			$li_total_cuenta=count($ar_cuenta);
			$ls_distribuir=1;		$ls_modrep=3;		$cero=0;
			$ld_cero=number_format($cero,2,",",".");	$ld_asignado=$ld_asignado;
			$ld_marzo=$ld_cero;			$ld_junio=$ld_cero;
			$ld_septiembre=$ld_cero;	$ld_diciembre=$ld_cero;
			if($ls_codrep=='0714')
			{
				if(($ls_cuenta=='401010000')||($ls_cuenta=='401020000')||($ls_cuenta=='402010000')||($ls_cuenta=='402020000')||($ls_cuenta=='403010000')||
				   ($ls_cuenta=='403020000')||($ls_cuenta=='407010000')||($ls_cuenta=='407020000')||($ls_cuenta=='408010000')||($ls_cuenta=='408020000'))
				{
				  $ls_cuenta="''";
				} 
			}	
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
	
			$cero=0	;			            $ld_cero=number_format($cero,2,",",".");		
			$ld_asignado_cero=$ld_cero;		$ls_status_in="I";
			$ld_m3=$ld_cero;			    $ld_m6=$ld_cero;
			$ld_m09=$ld_cero;	            $ld_m12=$ld_cero;
			for($li=1;$li<$li_total_cuenta;$li++)
			{			
				$ls_cuenta=$ar_cuenta[$li]; 
				$ls_denominacion="";
				$lb_valido=$io_class_progrep->uf_select_denominacion($ls_cuenta,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denominacion);
				if($lb_valido)
				{
					$i=$i+1;
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul  size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status_in'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
					$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado_cero  style=text-align:right readonly>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_m3'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_m6'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_m09'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_m12'>";
				}//if
		   }//for
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
	   if($ls_referencia!="")
	   {
			for($li=$li_rows-1;$li>=1;$li--)
			{
				//$ls_cuenta_aux=$_POST["txtCuenta".$li];  
				$ls_cuenta_hidden=$_POST["cuenta".$li];
				$ls_cuenta_aux=$ls_cuenta_hidden;  
				$ls_denominacion_aux=$_POST["txtDenominacion".$li];
				$ld_asignado_aux =$_POST["txtAsignacion".$li];
				$ls_status_aux=$_POST["status".$li];
				$ls_referencia_aux=$_POST["referencia".$li];
				$ls_distribuir_aux=$ls_distribuir;
				$ls_modrep_aux=$ls_modrep;
				$ld_marzo_aux=$_POST["marzo".$li];
				$ld_junio_aux=$_POST["junio".$li];
				$ld_septiembre_aux=$_POST["septiembre".$li];
				$ld_diciembre_aux=$_POST["diciembre".$li];
				if($ls_cuenta_aux==$ls_referencia)
				{						
					$ld_asignado=str_replace('.','',$ld_asignado);              $ld_asignado=str_replace(',','.',$ld_asignado);
					$ld_asignado_aux=str_replace('.','',$ld_asignado_aux);	    $ld_asignado_aux=str_replace(',','.',$ld_asignado_aux);
					$ld_marzo=str_replace('.','',$ld_marzo);		    		$ld_marzo=str_replace(',','.',$ld_marzo);
					$ld_marzo_aux=str_replace('.','',$ld_marzo_aux);		    $ld_marzo_aux=str_replace(',','.',$ld_marzo_aux);
					$ld_junio=str_replace('.','',$ld_junio);		    		$ld_junio=str_replace(',','.',$ld_junio);
					$ld_junio_aux=str_replace('.','',$ld_junio_aux);		    $ld_junio_aux=str_replace(',','.',$ld_junio_aux);
					$ld_septiembre=str_replace('.','',$ld_septiembre);			$ld_septiembre=str_replace(',','.',$ld_septiembre);
					$ld_septiembre_aux=str_replace('.','',$ld_septiembre_aux);	$ld_septiembre_aux=str_replace(',','.',$ld_septiembre_aux);
					$ld_diciembre=str_replace('.','',$ld_diciembre);			$ld_diciembre=str_replace(',','.',$ld_diciembre);
					$ld_diciembre_aux=str_replace('.','',$ld_diciembre_aux);	$ld_diciembre_aux=str_replace(',','.',$ld_diciembre_aux);
					
					$ld_asignado_aux=$ld_asignado_aux+$ld_asignado;
					$ld_marzo_aux=$ld_marzo_aux+$ld_marzo;
					$ld_junio_aux=$ld_junio_aux+$ld_junio;
					$ld_septiembre_aux=$ld_septiembre_aux+$ld_septiembre;
					$ld_diciembre_aux=$ld_diciembre_aux+$ld_diciembre;
					
					$ld_asignado_aux=number_format($ld_asignado_aux,2,",",".");
					$ld_asignado=number_format($ld_asignado,2,",",".");
					$ld_marzo=number_format($ld_marzo,2,",",".");
					$ld_marzo_aux=number_format($ld_marzo_aux,2,",",".");
					$ld_junio=number_format($ld_junio,2,",",".");
					$ld_junio_aux=number_format($ld_junio_aux,2,",",".");
					$ld_septiembre=number_format($ld_septiembre,2,",",".");
					$ld_septiembre_aux=number_format($ld_septiembre_aux,2,",",".");
					$ld_diciembre=number_format($ld_diciembre,2,",",".");
					$ld_diciembre_aux=number_format($ld_diciembre_aux,2,",",".");
					

					$object[$li][1]="<input type=text name=txtCuenta".$li." value=$ls_cuenta_aux class=sin-borde size=10 readonly ><input name=referencia".$li." type=hidden id=referencia value='$ls_referencia_aux'><input name=status".$li." type=hidden id=status value='$ls_status_aux'><input name=cuenta".$li." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$li." type=hidden id=distribuir value='$ls_distribuir_aux'><input name=modrep".$li." type=hidden id=modrep value='$ls_modrep_aux'>";
					$object[$li][2]="<input type=text name=txtDenominacion".$li." value='$ls_denominacion_aux' size=105 class=sin-borde readonly >";
					$object[$li][3]="<input type=text name=txtAsignacion".$li." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado_aux  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$li.") style=text-align:right>
									<input name=marzo".$li." type=hidden id=marzo".$li." value='$ld_marzo_aux'><input name=junio".$li." type=hidden id=junio".$li." value='$ld_junio_aux'>
									<input name=septiembre".$li." type=hidden id=septiembre".$li." value='$ld_septiembre_aux'><input name=diciembre".$li." type=hidden id=diciembre".$li." value='$ld_diciembre_aux'>";
					$ls_referencia=$ls_referencia_aux;				   
			   }//if
			}//for	
	   }//if				
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
	}//if
	else
	{
	 if($ls_codrep=='0714')
	 {
		$ls_distribuir=1;			$ls_modrep=1;			
		if(($ls_cuenta_report=='401010000')||($ls_cuenta_report=='401020000')||($ls_cuenta_report=='402010000')||($ls_cuenta_report=='402020000')||($ls_cuenta_report=='403010000')||
		   ($ls_cuenta_report=='403020000')||($ls_cuenta_report=='407010000')||($ls_cuenta_report=='407020000')||($ls_cuenta_report=='408010000')||($ls_cuenta_report=='408020000'))
		{					
			$ls_cuenta="''";
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado  style=text-align:right readonly>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
		  
		}
		else
		{ 
			$ls_cuenta=$ls_cuenta_report;
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
	   }	
	 }
	 else
	 {
		if($ls_status=="I")
		{
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado  style=text-align:right readonly>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
		}
		else
		{
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
	   }//else
	 }//else  
    }//else
  }//for    
  $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);
} //if ($ls_opcion=="N")	
  
if ($ls_opcion=="A")
{
    $li_rows=$_POST["fila"];
    $ls_codrep=$_POST["cmbrep"];
	$ls_codrep=$_POST["cmbrep"];
	if(($ls_codrep=='00005')||($ls_codrep=='0714'))
	{
		$ls_codestpro1="00000000000000000000";
		$ls_codestpro2="000000";
		$ls_codestpro3="000";
	}
	else
	{
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codestpro3=$_POST["codestpro3"];
	}
	$li_estmodest  = $la_empresa["estmodest"];
	if($li_estmodest==1)
	{
		$ls_codestpro4="00";
		$ls_codestpro5="00";
	}
	else
	{
		$ls_codestpro4=$_POST["codestpro4"];
		$ls_codestpro5=$_POST["codestpro5"];
	}
    $li_num=$_POST["li_totnum"];
    for($i=1;$i<=$li_num;$i++)
    {    
        $ls_cuenta=$_POST["txtCuenta".$i];
		$ls_cuenta_hidden=$_POST["cuenta".$i];
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_asignado =$_POST["txtAsignacion".$i];
		$ls_status=$_POST["status".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ls_referencia=$_POST["referencia".$i];
		$ld_marzo=$_POST["marzo".$i];
		$ld_junio=$_POST["junio".$i];
		$ld_septiembre=$_POST["septiembre".$i];
		$ld_diciembre=$_POST["diciembre".$i];
		$ls_cuenta_report=$ls_cuenta_hidden;
		if( $li_rows==$i)
		{
			$ls_cuenta=$ls_cuenta_hidden;
			$li_nivel=0;
			$lb_valido  = $io_class_progrep->uf_obtener_nivel_cta($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$li_nivel);//Obtiene nivel de la cta 
			$ls_cta_ceros = $int_spg->uf_spg_cuenta_sin_cero($ls_cuenta );  //devuelve la cta sin ceros
			$ar_cuenta = $io_class_progrep->uf_disable_cta_inferior($ls_cta_ceros,$ls_cuenta,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,0);  
			$li_total_cuenta=count($ar_cuenta);
			$ls_distribuir=2;		$ls_modrep=3;
			if($ls_codrep=='0714')
			{
				if(($ls_cuenta=='401010000')||($ls_cuenta=='401020000')||($ls_cuenta=='402010000')||($ls_cuenta=='402020000')||($ls_cuenta=='403010000')||
				   ($ls_cuenta=='403020000')||($ls_cuenta=='407010000')||($ls_cuenta=='407020000')||($ls_cuenta=='408010000')||($ls_cuenta=='408020000'))
				{
				  $ls_cuenta="''";
				} 
			}	
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
            
			$cero=0;                        $ld_cero=number_format($cero,2,",",".");		
			$ld_asignado_cero=$ld_cero;		$ls_status_in="I";
			$ld_m3=$ld_cero;			    $ld_m6=$ld_cero;
			$ld_m09=$ld_cero;		        $ld_m12=$ld_cero;
				
			for($li=1;$li<$li_total_cuenta;$li++)
			{
				$ls_cuenta=$ar_cuenta[$li]; 
				$ls_denominacion="";
				$lb_valido=$io_class_progrep->uf_select_denominacion($ls_cuenta,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denominacion);
				if($lb_valido)
				{
					$i=$i+1;
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul  size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status_in'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
					$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado_cero style=text-align:right readonly>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_m3'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_m6'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_m09'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_m12'>";
				}//if
		   }//for
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		   if($ls_referencia!="")
		   {
				for($li=$li_rows-1;$li>=1;$li--)
				{
					//$ls_cuenta_aux=$_POST["txtCuenta".$li];  
					$ls_cuenta_hidden=$_POST["cuenta".$li];
					$ls_cuenta_aux=$ls_cuenta_hidden;  
					$ls_denominacion_aux=$_POST["txtDenominacion".$li];
		            $ld_asignado_aux =$_POST["txtAsignacion".$li];
					$ls_status_aux=$_POST["status".$li];
					$ls_referencia_aux=$_POST["referencia".$li];
					$ls_distribuir_aux=$ls_distribuir;
					$ls_modrep_aux=$ls_modrep;
					$ld_marzo_aux=$_POST["marzo".$li];
					$ld_junio_aux=$_POST["junio".$li];
					$ld_septiembre_aux=$_POST["septiembre".$li];
					$ld_diciembre_aux=$_POST["diciembre".$li];
					if($ls_cuenta_aux==$ls_referencia)
					{						
						$ld_asignado=str_replace('.','',$ld_asignado);              $ld_asignado=str_replace(',','.',$ld_asignado);
						$ld_asignado_aux=str_replace('.','',$ld_asignado_aux);	    $ld_asignado_aux=str_replace(',','.',$ld_asignado_aux);
						$ld_marzo=str_replace('.','',$ld_marzo);		    		$ld_marzo=str_replace(',','.',$ld_marzo);
						$ld_marzo_aux=str_replace('.','',$ld_marzo_aux);		    $ld_marzo_aux=str_replace(',','.',$ld_marzo_aux);
						$ld_junio=str_replace('.','',$ld_junio);		    		$ld_junio=str_replace(',','.',$ld_junio);
						$ld_junio_aux=str_replace('.','',$ld_junio_aux);		    $ld_junio_aux=str_replace(',','.',$ld_junio_aux);
						$ld_septiembre=str_replace('.','',$ld_septiembre);			$ld_septiembre=str_replace(',','.',$ld_septiembre);
						$ld_septiembre_aux=str_replace('.','',$ld_septiembre_aux);	$ld_septiembre_aux=str_replace(',','.',$ld_septiembre_aux);
						$ld_diciembre=str_replace('.','',$ld_diciembre);			$ld_diciembre=str_replace(',','.',$ld_diciembre);
						$ld_diciembre_aux=str_replace('.','',$ld_diciembre_aux);	$ld_diciembre_aux=str_replace(',','.',$ld_diciembre_aux);
						
						$ld_asignado_aux=$ld_asignado_aux+$ld_asignado;
						$ld_marzo_aux=$ld_marzo_aux+$ld_marzo;
						$ld_junio_aux=$ld_junio_aux+$ld_junio;
						$ld_septiembre_aux=$ld_septiembre_aux+$ld_septiembre;
						$ld_diciembre_aux=$ld_diciembre_aux+$ld_diciembre;
						
						$ld_asignado_aux=number_format($ld_asignado_aux,2,",",".");
						$ld_asignado=number_format($ld_asignado,2,",",".");
						$ld_marzo=number_format($ld_marzo,2,",",".");
						$ld_marzo_aux=number_format($ld_marzo_aux,2,",",".");
						$ld_junio=number_format($ld_junio,2,",",".");
						$ld_junio_aux=number_format($ld_junio_aux,2,",",".");
						$ld_septiembre=number_format($ld_septiembre,2,",",".");
						$ld_septiembre_aux=number_format($ld_septiembre_aux,2,",",".");
						$ld_diciembre=number_format($ld_diciembre,2,",",".");
						$ld_diciembre_aux=number_format($ld_diciembre_aux,2,",",".");

						$object[$li][1]="<input type=text name=txtCuenta".$li." value=$ls_cuenta_aux class=sin-borde size=10 readonly><input name=referencia".$li." type=hidden id=referencia value='$ls_referencia_aux'><input name=status".$li." type=hidden id=status value='$ls_status_aux'><input name=cuenta".$li." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$li." type=hidden id=distribuir value='$ls_distribuir_aux'><input name=modrep".$li." type=hidden id=modrep value='$ls_modrep_aux'>";
						$object[$li][2]="<input type=text name=txtDenominacion".$li." value='$ls_denominacion_aux' size=105 class=sin-borde readonly >";
						$object[$li][3]="<input type=text name=txtAsignacion".$li." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado_aux  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$li.") style=text-align:right>
										<input name=marzo".$li." type=hidden id=marzo".$li." value='$ld_marzo_aux'><input name=junio".$li." type=hidden id=junio".$li." value='$ld_junio_aux'>
										<input name=septiembre".$li." type=hidden id=septiembre".$li." value='$ld_septiembre_aux'><input name=diciembre".$li." type=hidden id=diciembre".$li." value='$ld_diciembre_aux'>";
                        $ls_referencia=$ls_referencia_aux;				   
				   }//if
				}//for	
		   }//if				
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}//if
		else
		{
		   if($ls_codrep=='0714')
		   {
				$ls_distribuir=2;			$ls_modrep=1;			
				if(($ls_cuenta_report=='401010000')||($ls_cuenta_report=='401020000')||($ls_cuenta_report=='402010000')||($ls_cuenta_report=='402020000')||($ls_cuenta_report=='403010000')||
				   ($ls_cuenta_report=='403020000')||($ls_cuenta_report=='407010000')||($ls_cuenta_report=='407020000')||($ls_cuenta_report=='408010000')||($ls_cuenta_report=='408020000'))
				{					
					$ls_cuenta="''";
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
					$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado style=text-align:right readonly>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
				  
				}
				else
				{ 
					$ls_cuenta=$ls_cuenta_report;
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
					$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			   }	
			}
		    else
			{
					if($ls_status=="I")
					{
						$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
						$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
						$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado style=text-align:right readonly>
										<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
										<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
					}
					else
					{
						$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_hidden'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
						$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
						$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
										<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
										<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
				   }//else
			}//else  
	    }//else
    }//for 
    $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);   
 }//fin de automatico
}//DISTRIBUIR

if ($ls_operacion=="ELIMINAR")
{
   $li_rows=$_POST["fila"];
   $ls_codrep=$_POST["cmbrep"];
   if(($ls_codrep=='00005')||($ls_codrep=='0714'))
   {
		$ls_codestpro1="00000000000000000000";
		$ls_codestpro2="000000";
		$ls_codestpro3="000";
	}
	else
	{
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codestpro3=$_POST["codestpro3"];
	}	
	$li_estmodest  = $la_empresa["estmodest"];
	if($li_estmodest==1)
	{
		$ls_codestpro4="00";
		$ls_codestpro5="00";
	}
	else
	{
		$ls_codestpro4=$_POST["codestpro4"];
		$ls_codestpro5=$_POST["codestpro5"];
	}
    $li_num=$_POST["li_totnum"];
    for($i=1;$i<=$li_num;$i++)
    {    
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_asignado =$_POST["txtAsignacion".$i];
		$ls_status=$_POST["status".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_referencia=$_POST["referencia".$i];
		$ld_marzo=$_POST["marzo".$i];
		$ld_junio=$_POST["junio".$i];
		$ld_septiembre=$_POST["septiembre".$i];
		$ld_diciembre=$_POST["diciembre".$i];
	
		if($li_rows==$i)
		{	
		  if ($ld_asignado<>0)
		  {
		     $msg->message(" La Cuenta ".$ls_cuenta." Tiene Monto Asignado de ".$ld_asignado." No se puede eliminar.");  
		  }
		  else
		  {
		     $li_nivel_cta  = $class_progrep->uf_obt_nivel_cta($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5);	//Obtiene nivel de la cta 
			 $ls_cta_ceros = $class_progrep->uf_cuenta_sin_ceros($ls_cuenta );  //devuelve la cta sin ceros
			 $ls_sc_cuenta = $class_progrep->uf_disable_cta_inferior($ls_cta_ceros, $ls_cuenta, $ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,0);  
			 $total=count($ls_sc_cuenta);
		     for($li=1;$li<=$total;$li++)
			 {
	            $ls_cuenta=$ls_sc_cuenta[$li];		     
                $lb_valido=$class_progrep->uf_spg_delete_cuenta($ls_cuenta,$ls_codemp,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5);		  
			    $i=$i+1;
			}//for
		  }//else
        }//if
		else
		{
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul value=$ld_asignado style=text-align:right readonly>
				                <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde  size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
				                <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
		   }//else
	   }//else
   }//for 
   $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);   
}//fin de $ls_operacion=="ELIMINAR"

if ($ls_operacion=="GUARDAR" )
{
    $cont_insert=0;
    $li_rows=$_POST["fila"];
    $ls_codrep=$_POST["cmbrep"];
    if(($ls_codrep=='00005')||($ls_codrep=='0714'))
	{
		$ls_codestpro1="00000000000000000000";
		$ls_codestpro2="000000";
		$ls_codestpro3="000";
	}
	else
	{
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codestpro3=$_POST["codestpro3"];
	}
	$li_estmodest  = $la_empresa["estmodest"];
	if($li_estmodest==1)
	{
		$ls_codestpro4="00";
		$ls_codestpro5="00";
	}
	else
	{
		$ls_codestpro4=$_POST["codestpro4"];
		$ls_codestpro5=$_POST["codestpro5"];
		$ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestpro1,20);
		$ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestpro2,6);
		$ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestpro3,3);
		$ls_codestpro4=$io_function->uf_cerosizquierda($ls_codestpro4,2);
		$ls_codestpro5=$io_function->uf_cerosizquierda($ls_codestpro5,2);
	}
   $li_num=$_POST["li_totnum"];
   for($i=1;$i<=$li_num;$i++)
   { 
        $ls_cuenta=$_POST["txtCuenta".$i];   
	    $ls_denominacion=$_POST["txtDenominacion".$i];
	    $ld_asignado=trim($_POST["txtAsignacion".$i]);
		$ls_status=$_POST["status".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ls_referencia=$_POST["referencia".$i];
		$cero=0;
        $ld_cero=number_format($cero,2,",",".");		
	    $ld_enero=$ld_cero;
	    $ld_febrero=$ld_cero;
	    $ld_marzo=$_POST["marzo".$i];
	    $ld_abril=$ld_cero;
	    $ld_mayo=$ld_cero;
	    $ld_junio=$_POST["junio".$i];
	    $ld_julio=$ld_cero;
	    $ld_agosto=$ld_cero;
	    $ld_septiembre=$_POST["septiembre".$i];
	    $ld_octubre=$ld_cero;
	    $ld_noviembre=$ld_cero;
	    $ld_diciembre=$_POST["diciembre".$i];
		 
		 if($ls_codrep=='0714')
		 {  
			$ls_cuenta=$ls_cuenta_aux;
			if(($ls_cuenta_aux=='401010000')||($ls_cuenta_aux=='401020000')||($ls_cuenta_aux=='402010000')||($ls_cuenta_aux=='402020000')||($ls_cuenta_aux=='403010000')||
			   ($ls_cuenta_aux=='403020000')||($ls_cuenta_aux=='407010000')||($ls_cuenta_aux=='407020000')||($ls_cuenta_aux=='408010000')||($ls_cuenta_aux=='408020000'))
			{
			  $ls_cuenta="''";
			} 
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta_aux'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul  value=$ld_asignado  style=text-align:right readonly>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			$ls_cuenta=$ls_cuenta_aux;
		}
		else
		{
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=105>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul  value=$ld_asignado  style=text-align:right readonly>
								<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
								<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
		   }//else
		}//else   
		   
		$ld_asignado=str_replace('.','',$ld_asignado);    $ld_asignado=str_replace(',','.',$ld_asignado);  
		$ld_enero=str_replace('.','',$ld_enero);	      $ld_enero=str_replace(',','.',$ld_enero);
		$ld_febrero=str_replace('.','',$ld_febrero);	  $ld_febrero=str_replace(',','.',$ld_febrero);
		$ld_marzo=str_replace('.','',$ld_marzo);		  $ld_marzo=str_replace(',','.',$ld_marzo);
		$ld_abril=str_replace('.','',$ld_abril);		  $ld_abril=str_replace(',','.',$ld_abril);
		$ld_mayo=str_replace('.','',$ld_mayo);		      $ld_mayo=str_replace(',','.',$ld_mayo);
		$ld_junio=str_replace('.','',$ld_junio);		  $ld_junio=str_replace(',','.',$ld_junio);
		$ld_julio=str_replace('.','',$ld_julio);		  $ld_julio=str_replace(',','.',$ld_julio);
		$ld_agosto=str_replace('.','',$ld_agosto);		   $ld_agosto=str_replace(',','.',$ld_agosto);
		$ld_septiembre=str_replace('.','',$ld_septiembre); $ld_septiembre=str_replace(',','.',$ld_septiembre);
		$ld_octubre=str_replace('.','',$ld_octubre);	   $ld_octubre=str_replace(',','.',$ld_octubre);
		$ld_noviembre=str_replace('.','',$ld_noviembre);   $ld_noviembre=str_replace(',','.',$ld_noviembre);
		$ld_diciembre=str_replace('.','',$ld_diciembre);   $ld_diciembre=str_replace(',','.',$ld_diciembre);
			
	    $lb_valido=$io_class_progrep->uf_spg_guardar_programacion_reportes( $ls_status,$ld_asignado,$ls_distribuir,$ls_modrep,
		                                                   				    $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,
																			$ld_junio,$ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,
																			$ld_noviembre,$ld_diciembre,$ls_cuenta,$ls_codrep,
																			$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																			$ls_codestpro4,$ls_codestpro5);
	    if ($lb_valido)
	    {
		   $cont_insert=$cont_insert+1;
	    }
	 }//for 		
	 if($cont_insert==$li_num)
	 {
          $io_sql->begin_transaction();
		    //////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Guardar la programacion de reportes ";
			$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
											$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
											$la_seguridad["ventanas"],$ls_descripcion);
  		 /////////////////////////////////         SEGURIDAD               /////////////////////////////	
			
		 $io_sql->commit();
	     $io_msg->message(" Los Datos fueron guardados con  exito ");
	 }
	 else
	 {
	 	$io_sql->rollback();  
		$io_msg->message(" Error en los datos al guardar  ");
	 }	
  	 $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);
}//GUARDAR	
?>
            <input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"]?>">
            <input name="li_totnum" type="hidden" id="li_totnum" value="<?php print $li_totnum; ?>">
            <input name="fila" type="hidden" id="fila">
            <input name="tipo" type="hidden" id="tipo">
            <a href="javascript: ue_showouput();"><span class="Estilo1">
            <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
            </span></a></div></td>
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
	f.action="sigesp_spg_p_progrep_trim.php";
	f.submit();
}

function ue_recargar()
{
    f=document.form1;
    resp=confirm("Este proceso borrara todas las cuentas y las copiara del plan original(Todas las programatica). ¿ Esta seguro de proceder ?");
	if (resp==true)
   {
		f.operacion.value="RECARGAR";
		f.action="sigesp_spg_p_progrep_trim.php";
		f.submit();
   }
}

function ue_distribuir()
{
   var i ;
   f=document.form1;
   li=f.fila.value;
   if((f.codestpro1.value=="")||(f.codestpro2.value=="")||(f.codestpro3.value==""))
   {
     alert(" Por Favor Seleccione una Estructura Programatica....");
   }
   else
   {
		for (i=0;i<f.radiobutton.length;i++)
		{ 
		   if (f.radiobutton[i].checked) 
			  break; 
		} 
		 document.opcion = f.radiobutton[i].value; 
		 if  (document.opcion=="M" ) 
		 {
		   if(li!="")
		   {
			 ls_distribuir=3;
			 opcion=document.opcion;
			 distribuir="distribuir"+li;
			 eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
			 txtAsignacion="txtAsignacion"+li;
			 ld_asignado=eval("f."+txtAsignacion+".value");
			 txtCuenta="txtCuenta"+li;
			 ls_cuenta=eval("f."+txtCuenta+".value");
			 txtDenominacion="txtDenominacion"+li;
			 ls_denominacion=eval("f."+txtDenominacion+".value");
			 marzo="marzo"+li;
			 ld_marzo=eval("f."+marzo+".value");
			 junio="junio"+li;
			 ld_junio=eval("f."+junio+".value");
			 septiembre="septiembre"+li;
			 ld_septiembre=eval("f."+septiembre+".value");
			 diciembre="diciembre"+li;
			 ld_diciembre=eval("f."+diciembre+".value");
			 if((ld_asignado=="0,00")||(ld_asignado=="0.00")||(ld_asignado=="0"))
			 {
			     alert("Monto Incorrecto...");
			 }
			 else
			 {	 
				 pagina="sigesp_spg_p_progrep_trim_distribucion.php?fila="+li+"&txtAsignacion="+ld_asignado+"&marzo="+ld_marzo
				         +"&junio="+ld_junio+"&septiembre="+ld_septiembre+"&diciembre="+ld_diciembre+"&txtCuenta="+ls_cuenta
						 +"&txtDenominacion="+ls_denominacion+"&tipo="+opcion;
				 window.open(pagina,"Asignación","menubar=no,toolbar=no,scrollbars=no,width=650,height=450,left=50,top=50,resizable=yes,location=no");
		     }
		  }
		  else
		  {
			 alert(" Por favor coloque el cursor sobre la fila  a editar  ");
		  }
	   }
		 
	   if (document.opcion=="A")
	   {
		   f=document.form1;
		   li=f.fila.value;
		   ls_distribuir=1;
		   distribuir="distribuir"+li;
		   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
		   li_total=f.li_totnum.value;
		   opcion=document.opcion;
		   if(li!="")
		   {
			   txtCuenta="txtCuenta"+li;
			   ls_cuenta=eval("f."+txtCuenta+".value");
			   txtDenominacion="txtDenominacion"+li;
			   ls_denominacion=eval("f."+txtDenominacion+".value");
			   txtAsignacion="txtAsignacion"+li;
			   ld_asignado=eval("f."+txtAsignacion+".value");
			   ld_asignado=uf_convertir_monto(ld_asignado);
			   ld_division=parseFloat((ld_asignado/4));
			   ld_division=redondear(ld_division,2);
			   ld_asignado=redondear(ld_asignado,2);
			   ld_suma_diciembre=redondear((ld_division*4),2);
			   ld_mes12=(ld_asignado-ld_suma_diciembre);
			   ld_mes12=redondear(ld_mes12,2);
			   if(ld_mes12>=0)
			   {
				ld_diciembre=ld_division+ld_mes12;
			   } 			
			   else//if(ld_mes12<0)
			   {
				ld_diciembre=ld_division+ld_mes12;
			   } 
			   ld_total=(ld_division*3);
			   ld_total_general=ld_total+ld_diciembre;
			   ld_total_general=redondear(ld_total_general,2);
			   ld_resto=(ld_asignado-ld_total_general);
			   ld_resto=redondear(ld_resto,2);
			   ld_diciembre=ld_diciembre+ld_resto;
			   ld_division=uf_convertir(ld_division);
			   ld_diciembre=uf_convertir(ld_diciembre);
			   distribuir="distribuir"+li;
			   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
			   m3="marzo"+li;
			   ld_marzo=eval("f."+m3+".value='"+ld_division+"'") ;
			   m6="junio"+li;
			   ld_junio=eval("f."+m6+".value='"+ld_division+"'") ;
			   m9="septiembre"+li;
			   ld_septiembre=eval("f."+m9+".value='"+ld_division+"'") ;
			   m12="diciembre"+li;
			   ld_diciembre=eval("f."+m12+".value='"+ld_diciembre+"'") ;
			   if((ld_asignado=="0,00")||(ld_asignado=="0.00")||(ld_asignado=="0"))
			   {
				 alert("Monto Incorrecto...");
			   }
			   else
			   {	 
					 pagina="sigesp_spg_p_progrep_trim_distribucion.php?fila="+li+"&txtAsignacion="+ld_asignado+"&marzo="+ld_marzo
							 +"&junio="+ld_junio+"&septiembre="+ld_septiembre+"&diciembre="+ld_diciembre+"&txtCuenta="+
							 ls_cuenta+"&txtDenominacion="+ls_denominacion+"&tipo="+opcion;
					 window.open(pagina,"Asignación","menubar=no,toolbar=no,scrollbars=no,width=650,height=450,left=50,top=50,resizable=yes,location=no");
		        }
			}
			else
			{
			 alert("Por favor coloque el cursor sobre la fila  a editar  ");
			}	 
	   }
	   
	   if (document.opcion=="N")
	   {

		   f=document.form1;
		   li=f.fila.value;
		   if(li!="")
		   {
			   ld_cero="0,00";
			   ls_distribuir=2;
			   distribuir="distribuir"+li;
			   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
			   txtAsignacion="txtAsignacion"+li;
			   ld_asignado=eval("f."+txtAsignacion+".value");
			   m3="marzo"+li;
			   ld_marzo=eval("f."+m3+".value='"+ld_cero+"'") ;
			   m6="junio"+li;
			   ld_junio=eval("f."+m6+".value='"+ld_cero+"'") ;
			   m9="septiembre"+li;
			   ld_septiembre=eval("f."+m9+".value='"+ld_cero+"'") ;
			   m12="diciembre"+li;
			   ld_diciembre=eval("f."+m12+".value='"+ld_cero+"'") ;
			   f.operacion.value="DISTRIBUIR";
			   f.tipo.value="N";
			   f.submit();
			}   
			else
			{
			 alert("Por favor coloque el cursor sobre la fila  a editar  ");
			}	 
	    }
    }
}

 function uf_calcular(obj)
  {  
	f=document.form1;
	ldec_temp1=obj.value;
	if(ldec_temp1=="")
	{
	  obj.value="0,00";
	  obj.focus();
	}
	var ld_asignado;
	li=f.fila.value; 	 

	txta="txtAsignacion"+li;
	ld_asignado=eval("f."+txta+".value");  
	ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
	
	txtm3="txtMarzo"+li;
	ld_m3=eval("f."+txtm3+".value");    
	ld_m3=parseFloat(uf_convertir_monto(ld_m3));

	txtm6="txtJunio"+li;
	ld_m6=eval("f."+txtm6+".value");
	ld_m6=parseFloat(uf_convertir_monto(ld_m6));

	txtm9="txtSeptiembre"+li;
	ld_m9=eval("f."+txtm9+".value");       
	ld_m9=parseFloat(uf_convertir_monto(ld_m9));

	txtm12="txtDiciembre"+li;
	ld_m12=eval("f."+txtm12+".value");       
	ld_m12=parseFloat(uf_convertir_monto(ld_m12));

	ld_total = parseFloat( ld_m3 +  ld_m6 + ld_m9 + ld_m12);
	ld_total=redondear(ld_total,2);
	if (ld_total>ld_asignado)
	{
	  alert(" La Distribución no cuadra con lo asignado. Por favor revise los montos ");
	  obj.focus();
	}	
	f.action="sigesp_spg_p_progrep_trim.php";
  }
function redondear(num, dec)
{ 
	num = parseFloat(num); 
	dec = parseFloat(dec); 
	dec = (!dec ? 2 : dec); 
	return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
}
function uf_formato(obj)
{
 ldec_temp1=obj.value;
 if((ldec_temp1=="")||(ldec_temp1==".")||(ldec_temp1==","))
 {
  ldec_temp1="0";
 }
 obj.value=uf_convertir(ldec_temp1);
}
function ue_guardar()
{
	f=document.form1;
	if(f.li_totnum.value==0)
	{
	  alert(" Debe tener al menos un registro cargado  ");
	}
	else
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_spg_p_progrep_trim.php";
		f.submit();
	}	
}

function ue_eliminarcuenta()
{
 f=document.form1;
 resp=confirm("¿Esta seguro de eliminar esta cuenta? ");
 if (resp==true)
 {
   f.operacion.value="ELIMINAR";
   f.action="sigesp_spg_p_progrep_trim.php";
   f.submit();
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
function uf_fila(i)
{
  f=document.form1;
  f.fila.value=i;
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
	denestpro1=f.denestpro1.value;
	
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
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
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	estmodest=f.estmodest.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3=="")&&(denestpro3==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&tipo=progrep";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=progrep";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&tipo=progrep";
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
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!=""))
	{
			pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&tipo=progrep";
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
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	codestpro4=f.codestpro4.value;
	denestpro4=f.denestpro4.value;
	codestpro5=f.codestpro5.value;
	denestpro5=f.denestpro5.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!="")&&
	   (codestpro4!="")&&(denestpro4!="")&&(codestpro5=="")&&(denestpro5==""))
	{
			pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4
					+"&denestpro4="+denestpro4+"&tipo=progrep";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
			pagina="sigesp_cat_public_estprograma.php?tipo=progrep";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
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

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}
</script>
</html>