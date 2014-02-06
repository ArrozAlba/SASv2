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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_d_asignacioncargo.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codasicar,$ls_denasicar,$ls_coduniadm,$ls_claasicar,$ls_codtab,$ls_codpas,$ls_codgra,$ls_codded,$ls_codtipper;
		global $li_numvacasicar,$li_numocuasicar,$li_rac,$ls_desnom,$ls_desded,$ls_destipper,$ls_desuniadm,$ls_destab,$li_monsalgra;
		global $li_moncomgra,$ls_operacion,$ls_existe,$io_fun_nomina,$ls_desper,$ls_modalidad,$ls_nomestpro1,$ls_nomestpro2,$ls_nomestpro3;
		global $ls_nomestpro4,$ls_nomestpro5,$ls_titulo,$ls_codestpro1,$ls_denestpro1,$ls_codestpro2,$ls_denestpro2,$ls_codestpro3;
		global $ls_denestpro3,$ls_codestpro4,$ls_denestpro4,$ls_codestpro5,$ls_denestpro5,$li_maxlen,$ls_coduniadmant;
		global $ls_codpasant,$ls_codgraant,$ls_codtabant,$ls_estcla;
		
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_codasicar="";
		$ls_denasicar="";
		$ls_coduniadm="";
		$ls_claasicar="";
		$ls_codtab="";
		$ls_codpas="";
		$ls_codgra="";
		$ls_codded="";
		$ls_codtipper="";			
		$li_numvacasicar=0;
		$li_numocuasicar=0;
		$ls_desded="";
		$ls_destipper="";
		$ls_desuniadm="";
		$ls_destab="";
		$li_monsalgra="0";
		$li_moncomgra="0";
		$ls_coduniadmant="";
		$ls_codpasant="";
		$ls_codgraant="";
		$ls_codtabant="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$li_rac=$_SESSION["la_nomina"]["racnom"];
		$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
		$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];		
		$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];		
		$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
		$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];		
		$ls_codestpro1="";
		$ls_denestpro1="";
		$ls_codestpro2="";
		$ls_denestpro2="";
		$ls_codestpro3="";
		$ls_denestpro3="";
		$ls_codestpro4="";
		$ls_denestpro4="";
		$ls_codestpro5="";
		$ls_denestpro5="";
		$ls_estcla="";
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria";
				$ls_codestpro4="00";
				$ls_codestpro5="00";
				$li_maxlen=25;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Programática";
				$li_maxlen=5;
				break;
		}
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codasicar, $ls_denasicar, $ls_coduniadm, $ls_claasicar, $ls_codtab, $ls_codpas, $ls_codgra, $ls_codded;
		global $ls_codtipper, $li_numvacasicar, $li_numocuasicar, $ls_desded, $ls_destipper, $ls_desuniadm, $ls_destab;
		global $ls_codestpro1, $ls_codestpro2, $ls_codestpro3, $ls_codestpro4, $ls_codestpro5, $ls_denestpro1;
		global $ls_denestpro2, $ls_denestpro3, $ls_denestpro4, $ls_denestpro5, $ls_codproasicar,$ls_coduniadmant;
		global $ls_codpasant, $ls_codgraant, $ls_codtabant, $li_monsalgra,$ls_estcla;
		
		$ls_codasicar=$_POST["txtcodasicar"];
		$ls_denasicar=$_POST["txtdenasicar"];
		$ls_coduniadm=$_POST["txtcoduniadm"];
		$ls_claasicar=$_POST["txtclaasicar"];
		$ls_codtab=$_POST["txtcodtab"];
		$ls_codpas=$_POST["txtcodpas"];
		$ls_codgra=$_POST["txtcodgra"];
		$ls_codded=$_POST["txtcodded"];
		$ls_codtipper=$_POST["txtcodtipper"];			
		$li_numvacasicar=$_POST["txtnumvacasicar"];
		$li_numocuasicar=$_POST["txtnumocuasicar"];
		$ls_desded=$_POST["txtdesded"];
		$ls_destipper=$_POST["txtdestipper"];
		$ls_desuniadm=$_POST["txtdesuniadm"];
		$ls_destab=$_POST["txtdestab"];
		$ls_codestpro1=$_POST["txtcodestpro1"];
		$ls_codestpro2=$_POST["txtcodestpro2"];
		$ls_codestpro3=$_POST["txtcodestpro3"];
		$ls_codestpro4=$_POST["txtcodestpro4"];
		$ls_codestpro5=$_POST["txtcodestpro5"];
		$ls_denestpro1=$_POST["txtdenestpro1"];
		$ls_denestpro2=$_POST["txtdenestpro2"];
		$ls_denestpro3=$_POST["txtdenestpro3"];
		$ls_denestpro4=$_POST["txtdenestpro4"];
		$ls_denestpro5=$_POST["txtdenestpro5"];
		$ls_estcla=$_POST["txtestcla3"];
		$li_monsalgra=$_POST["txtmonsalgra"];
		$ls_coduniadmant=$_POST["coduniadmant"];
		$ls_codpasant=$_POST["codpasant"];
		$ls_codgraant=$_POST["codgraant"];
		$ls_codtabant=$_POST["codtabant"];
		$ls_codproasicar=str_pad($ls_codestpro1,25,"0",0).str_pad($ls_codestpro2,25,"0",0).str_pad($ls_codestpro3,25,"0",0);
		$ls_codproasicar=$ls_codproasicar.str_pad($ls_codestpro4,25,"0",0).str_pad($ls_codestpro5,25,"0",0);
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
<title >Definici&oacute;n de Asignaci&oacute;n de Cargo</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("sigesp_sno_c_asignacioncargo.php");
	$io_asignacioncargo=new sigesp_sno_c_asignacioncargo();
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	uf_limpiarvariables();
	
	if($li_rac=="0")
	{
		print("<script language=JavaScript>");
		print(" alert('Esta definición esta desactiva para nóminas que NO utilizan RAC.');");
		print(" location.href='sigespwindow_blank_nomina.php'");
		print("</script>");
	}	
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_asignacioncargo->uf_guardar($ls_existe,$ls_codasicar,$ls_denasicar,$ls_coduniadm,$ls_claasicar,$ls_codtab,
													   $ls_codpas,$ls_codgra,$ls_codded,$ls_codtipper,$li_numvacasicar,$li_numocuasicar,
													   $ls_codproasicar,$ls_coduniadmant,$ls_codpasant,$ls_codgraant,$ls_codtabant,
													   $li_monsalgra,$ls_estcla,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_asignacioncargo->uf_delete_asignacioncargo($ls_codasicar,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
	}
	$io_asignacioncargo->uf_destructor();
	unset($io_asignacioncargo);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title='Nuevo' alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title='Eliminar' alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">      
	<p>&nbsp;</p>
	<table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-ventana">
        <td height="20" colspan="4" class="titulo-ventana">Definici&oacute;n de Asignaci&oacute;n de Cargo</td>
      </tr>
      <tr>
        <td width="124" height="22"><div align="right"></div></td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&oacute;digo</div></td>
        <td colspan="3">
            
              <div align="left">
                <input name="txtcodasicar" type="text" id="txtcodasicar" size="10" maxlength="7" value="<?php print $ls_codasicar;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,7);">
              </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="3">
            
              <div align="left">
                <input name="txtdenasicar" type="text" id="txtdenasicar" size="60" maxlength="100" value="<?php print $ls_denasicar;?>" onKeyUp="ue_validarcomillas(this);">
              </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Unidad Administrativa </div></td>
        <td colspan="3">
          
            <div align="left">
              <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm;?>" readonly>
              <a href="javascript: ue_buscarunidadadministrativa();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdesuniadm" type="hidden" class="sin-borde" id="txtdesuniadm" value="<?php print $ls_desuniadm;?>" size="65" maxlength="100" readonly>
              </div></td>
      		</tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td  colspan="3"><div align="left"><strong><?php print $ls_titulo; ?></strong></div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                <?php print $ls_nomestpro1;?>				
                </div></td>
                <td  colspan="3">	
				  <div align="left">
                  <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1;?>" size="<?php print $ls_loncodestpro1+10; ?>" maxlength="<?php print $ls_loncodestpro1+2;?>" readonly>
                  <a href="javascript:ue_estructura1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                  <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1;?>" size="53" readonly>			
                  <input name="txtestcla1" type="hidden" id="txtestcla1" value="<? print $ls_estcla ?>" size="2" >
                  </div>
              </td>
              </tr>
            <tr>
                <td height="22">
				<div align="right">
				<?php print $ls_nomestpro2;?>
			  </div>
			  </td>
                <td colspan="3">
				 <div align="left" >
                 <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print $ls_codestpro2 ; ?>" size="<?php print $ls_loncodestpro2+10; ?>" maxlength="<?php print $ls_loncodestpro2+2; ?>" readonly>
                 <a href="javascript:ue_estructura2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                 <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2 ; ?>" size="53" readonly>
                 <input name="txtestcla2" type="hidden" id="txtestcla2" value="<? print $ls_estcla ?>" size="2" >
                 </div>
				</td>
            </tr>
            <tr>
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro3; ?>
			  </div>
			  </td>
              <td colspan="3">
			    <div align="left">
                <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="<?php print $ls_codestpro3;?>" size="<?php print $ls_loncodestpro3+10; ?>" maxlength="<?php print $ls_loncodestpro3+10; ?>" readonly>
                <a href="javascript:ue_estructura3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3;?>" size="53" readonly>
                <input name="txtestcla3" type="hidden" id="txtestcla3" value="<? print $ls_estcla ?>" size="2" >
                </div></td>
            </tr>
<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>">
 				<input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="<?php print $ls_denestpro4;?>">
 				<input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>">
 				<input name="txtdenestpro5" type="hidden" id="txtdenestpro5" value="<?php print $ls_denestpro5;?>">
 				<input name="txtestcla4" type="hidden" id="txtestcla4" value="<? print $ls_estcla ?>" size="2" >
 				<input name="txtestcla5" type="hidden" id="txtestcla5" value="<? print $ls_estcla ?>" size="2" >
<?php }
	  else
	  {?>
            <tr>
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro4; ?>
			  </div>
			  </td>
              <td colspan="3">
			    <div align="left">
                <input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>" size="<?php print $ls_loncodestpro4+10; ?>" maxlength="<?php print $ls_loncodestpro4+2; ?>" readonly>
                <a href="javascript:ue_estructura4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4;?>" size="53" readonly>
                <input name="txtestcla4" type="hidden" id="txtestcla4" value="<? print $ls_estcla ?>" size="2" >
                </div></td>
            </tr>
            <tr colspan="3">
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro5; ?>
			  </div>
			  </td>
              <td colspan="3">
			    <div align="left">
                <input name="txtcodestpro5" type="text" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>" size="<?php print $ls_loncodestpro5+10; ?>" maxlength="<?php print $ls_loncodestpro2+2; ?>" readonly>
                <a href="javascript:ue_estructura5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5;?>" size="53" readonly>
                <input name="txtestcla5" type="hidden" id="txtestcla5" value="<? print $ls_estcla ?>" size="2" >
                </div></td>
            </tr>
<?php } ?>
      <tr>
        <td height="22"><div align="right">Clase de Cargo </div></td>
        <td colspan="3">
            
              <div align="left">
                <input name="txtclaasicar" type="text" id="txtclaasicar" value="<?php print $ls_claasicar;?>" size="16" maxlength="10" onKeyUp="ue_validarcomillas(this);">
              </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Dedicaci&oacute;n</div></td>
        <td colspan="3">
          
            <div align="left">
              <input name="txtcodded" type="text" id="txtcodded" value="<?php print $ls_codded;?>" size="6" maxlength="3" readonly>
              <a href="javascript: ue_buscardedicacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdesded" type="text" class="sin-borde" id="txtdesded" value="<?php print $ls_desded;?>" size="60" maxlength="100" readonly>
              </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo de Personal </div></td>
        <td colspan="3">
          
            <div align="left">
              <input name="txtcodtipper" type="text" id="txtcodtipper" size="7" maxlength="4" value="<?php print $ls_codtipper;?>" readonly>
              <a href="javascript: ue_buscartipopersonal();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdestipper" type="text" class="sin-borde" id="txtdestipper" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_destipper;?>" size="60" maxlength="100" readonly>
              </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tabulador</div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodtab" type="text" id="txtcodtab" value="<?php print $ls_codtab;?>" size="25" maxlength="20" readonly>
            <a href="javascript: ue_buscartabla();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdestab" type="text" class="sin-borde" id="txtdestab" value="<?php print $ls_destab;?>" size="55" maxlength="100" readonly>
            </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Grado
        </div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodgra" type="text" id="txtcodgra" value="<?php print $ls_codgra;?>" size="18" maxlength="15" readonly>
            <a href="javascript: ue_buscargrado();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Paso
        </div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodpas" type="text" id="txtcodpas" value="<?php print $ls_codpas;?>" size="18" maxlength="15" readonly>
            <a href="javascript: ue_buscargrado();"></a></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Salario</div></td>
        <td width="144">
          <div align="left">
            <input name="txtmonsalgra" type="text" id="txtmonsalgra" value="<?php print $li_monsalgra;?>" size="25" maxlength="20" style="text-align:right" readonly>
            </div></td>
        <td width="110"><div align="right">Compensaci&oacute;n</div></td>
        <td width="262"><div align="left">
          <input name="txtmoncomgra" type="text" id="txtmoncomgra" value="<?php print $li_moncomgra;?>" size="25" maxlength="20" style="text-align:right" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero de vacantes </div></td>
        <td colspan="3">
          
            <div align="left">
              <input name="txtnumvacasicar" type="text" id="txtnumvacasicar" value="<?php print $li_numvacasicar;?>" size="5" maxlength="10" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero de Ocupados </div></td>
        <td colspan="3">
          
            <div align="left">
              <input name="txtnumocuasicar" type="text" id="txtnumocuasicar" value="<?php print $li_numocuasicar;?>" size="5" maxlength="10" style="text-align:right" readonly>
              </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Disponibilidad</div></td>
        <td colspan="3"><div align="left">
          <input name="txtdisponasicar" type="text" id="txtdisponasicar" size="5" maxlength="10" style="text-align:right" readonly>
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="3"><div align="left">
              <input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
              <input name="rac" type="hidden" id="rac" value="<?php print $li_rac;?>">
              <input name="modalidad" type="hidden" id="modalidad" value="<?php print $ls_modalidad;?>">
              <input name="coduniadmant" type="hidden" id="coduniadmant" value="<?php print $ls_coduniadmant;?>">
              <input name="codpasant" type="hidden" id="codpasant" value="<?php print $ls_codpasant;?>">
              <input name="codgraant" type="hidden" id="codgraant" value="<?php print $ls_codgraant;?>">
              <input name="codtabant" type="hidden" id="codtabant" value="<?php print $ls_codtabant;?>">
        </div></td>
      </tr>
    </table>
	<p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
var cont = 0
function ue_cerrar_ventana()
{
for(m=1;m<=cont;m++)
	{
	if(eval('ventana' + m))
		{
		eval('ventana' + m + ".close()")
		}
	}
cont=0
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";
		f.action="sigesp_sno_d_asignacioncargo.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		codasicar=ue_validarvacio(f.txtcodasicar.value);
		denasicar=ue_validarvacio(f.txtdenasicar.value);
		coduniadm=ue_validarvacio(f.txtcoduniadm.value);
		claasicar=ue_validarvacio(f.txtclaasicar.value);
		codded=ue_validarvacio(f.txtcodded.value);
		codtipper=ue_validarvacio(f.txtcodtipper.value);
		codtab = ue_validarvacio(f.txtcodtab.value);
		codpas = ue_validarvacio(f.txtcodpas.value);
		codgra = ue_validarvacio(f.txtcodgra.value);
		numvacasicar=ue_validarvacio(f.txtnumvacasicar.value);
		numocuasicar=ue_validarvacio(f.txtnumocuasicar.value);
		rac = ue_validarvacio(f.rac.value);
		codestpro1=ue_validarvacio(f.txtcodestpro1.value);
		codestpro2=ue_validarvacio(f.txtcodestpro2.value);
		codestpro3=ue_validarvacio(f.txtcodestpro3.value);
		if(f.modalidad.value=="1")
		{
			codestpro4 = "00";
			codestpro5 = "00";
		}
		else
		{
			codestpro4 = ue_validarvacio(f.txtcodestpro4.value);
			codestpro5 = ue_validarvacio(f.txtcodestpro5.value);
		}
		if ((codasicar=="")||(denasicar=="")||(coduniadm="")||(claasicar="")||(codded="")||(codtipper=="")||(codtab=="")||(codpas=="")||
			(codgra=="")||(numvacasicar=="")||(numocuasicar=="")||(codestpro1=="")||(codestpro2=="")||(codestpro3=="")||(codestpro5=="")||(codestpro5==""))
		{
			alert("Debe llenar todos los datos.");
		}
		else
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sno_d_asignacioncargo.php";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			codasicar = ue_validarvacio(f.txtcodasicar.value);
			if (codasicar=="")
			{
				alert("Debe buscar el registro a eliminar.");
			}
			else
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_sno_d_asignacioncargo.php";
					f.submit();
				}
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
	ue_cerrar_ventana();
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	ue_cerrar_ventana();
	if (li_leer==1)
   	{
		cont++
		eval('ventana'+ cont + "=window.open('sigesp_sno_cat_asignacioncargo.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no')");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarunidadadministrativa()
{
	window.open("sigesp_snorh_cat_uni_ad.php?tipo=asignacioncargo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_estructura1()
{
	window.open("sigesp_snorh_cat_estpre1.php?tipo=asignacioncargo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}

function ue_estructura2()
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	estcla=f.txtestcla1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_snorh_cat_estpre2.php?tipo=asignacioncargo&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla1="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}

function ue_estructura3()
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	estcla=f.txtestcla2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_snorh_cat_estpre3.php?tipo=asignacioncargo&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla2="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura4()
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	estcla=f.txtestcla3.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!=""))
	{
    	pagina="sigesp_snorh_cat_estpre4.php?tipo=asignacioncargo&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla3="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura5()
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	codestpro4=f.txtcodestpro4.value;
	denestpro4=f.txtdenestpro4.value;
	estcla=f.txtestcla4.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!="")&&(codestpro4!="")&&(denestpro4!=""))
	{
    	pagina="sigesp_snorh_cat_estpre5.php?tipo=asignacioncargo&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&estcla4="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_buscardedicacion()
{
	window.open("sigesp_snorh_cat_dedicacion.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscartipopersonal()
{
	f=document.form1;
	codded = ue_validarvacio(f.txtcodded.value);
	if (codded!="")
	{
		window.open("sigesp_snorh_cat_tipopersonal.php?tipo=asignacion&codded="+codded+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe Seleccionar la dedicación");
	}
}

function ue_buscartabla()
{
	window.open("sigesp_sno_cat_tabulador.php?tipo=asignacioncargo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscargrado()
{
	f=document.form1;
	codtab = ue_validarvacio(f.txtcodtab.value);
	if (codtab!="")
	{
		window.open("sigesp_sno_cat_grado.php?tipo=asignacioncargo&tab="+codtab+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe Seleccionar la tabla");
	}
}
</script> 
</html>