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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_d_hconcepto.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	global $ls_sueint;
	$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));
	$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];
	$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];
	$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];
	$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
	$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];		
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
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

	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	unset($io_sno);
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
<title >Definici&oacute;n de Concepto</title>
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
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_hnomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_hnomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definición de Concepto </td>
        </tr>
        <tr>
          <td width="171" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodconc" type="text" id="txtcodconc" size="13" maxlength="10" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnomcon" type="text" id="txtnomcon" size="33" maxlength="30" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;itulo</div></td>
          <td colspan="3"><div align="left">
            <input name="txttitcon" type="text" id="txttitcon" size="90" maxlength="254" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Signo</div></td>
          <td colspan="3"><div align="left">
            <select name="cmbsigcon" id="cmbsigcon" disabled>
              <option value="" selected>--Seleccione Una--</option>
              <option value="A">Asignaci&oacute;n</option>
              <option value="D">Deducci&oacute;n</option>
              <option value="P">Aporte Patronal</option>
              <option value="R">Reporte</option>
              <option value="B">Reintegro Deducci&oacute;n</option>
              <option value="E">Reintegro Asignaci&oacute;n</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">F&oacute;rmula</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtforcon" type="text" id="txtforcon" size="90" maxlength="200" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Acumulado M&aacute;ximo </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtacumaxcon" type="text" id="txtacumaxcon" size="23" maxlength="20" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Valor M&iacute;nimo </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtvalmincon" type="text" id="txtvalmincon" size="23" maxlength="20" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Valor M&aacute;ximo </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtvalmaxcon" type="text" id="txtvalmaxcon" size="23" maxlength="20" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Condici&oacute;n</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtconcon" type="text" id="txtconcon" size="90" maxlength="200" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Aplica Impuesto Sobre Renta </div></td>
          <td width="30">
            <div align="left">
              <input name="chkaplisrcon" type="checkbox" disabled class="sin-borde" id="chkaplisrcon" value="1">
              </div></td><td width="261"><div align="right"><?php if ($ls_sueint==""){print "Pertenece al Sueldo Integral";}else{print "Pertenece a ". $ls_sueint;}?></div></td>
          <td width="188"><div align="left">
            <input name="chksueintcon" type="checkbox" disabled class="sin-borde" id="chksueintcon" value="1">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Aplicar a todo el personal </div></td>
          <td><div align="left"><input name="chkglocon" type="checkbox" disabled class="sin-borde" id="chkglocon" value="1"></div></td>
          <td><div align="right">Evaluar en Pren&oacute;mina </div></td>
          <td><div align="left"><input name="chkconprenom" type="checkbox" disabled class="sin-borde" id="chkconprenom" value="1"></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Integrar Program&aacute;tica Concepto</div></td>
          <td><div align="left"><input name="chkintprocon" type="checkbox" disabled class="sin-borde" id="chkintprocon" value="1"></div></td>
          <td><div align="right"><?php if ($ls_sueint==""){print "Pertenece al Sueldo Integral de Vacaciones";}else{print "Pertenece a ". $ls_sueint." de Vacaciones";}?></div></td>
          <td><div align="left"><input name="chksueintvaccon" type="checkbox" disabled class="sin-borde" id="chksueintvaccon" value="1"></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Aplica ARC </div></td>
          <td><div align="left">
            <input name="chkaplarccon" type="checkbox" class="sin-borde" id="chkaplarccon" value="1" disabled>
          </div></td>
          <td><div align="right">Contabilizaci&oacute;n por Proyecto </div></td>
          <td><input name="chkconprocon" type="checkbox" class="sin-borde" id="chkconprocon" disabled></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Integrar con Ingresos </div></td>
          <td><div align="left">
            <input name="chkintingcon" type="checkbox" class="sin-borde" id="chkintingcon" disabled>
          </div></td>
          <td><div align="right">Asignar a fideicomiso</div></td>
          <td><div align="left">
            <input name="chkasifidper" type="checkbox" class="sin-borde" id="chkasifidper" value="1" disabled>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">
            <div align="right">Frecuencia Variable </div>
          </div></td>
          <td ><div align="left">
            <input name="chkfrevarcon" type="checkbox" class="sin-borde" id="chkfrevarcon" value="1" disabled>
          </div></td>
		  <td><div align="right">Pertenece a Salario Normal</div></td>
				<td><input name="chkpersalnor" type="checkbox" class="sin-borde" id="chkchkpersalnor" value="1" disabled></td>
        </tr>
		<tr>
                <td height="22"><div align="right">Pertenece a Encargadur&iacute;a </div></td>
                <td><input name="chkperenc" type="checkbox" class="sin-borde" id="chkperenc" value="1" disabled></td>
				<td><div align="right">Resumen por Encargadur&iacute;a </div></td>
				<td><input name="chkaplresenc" type="checkbox" class="sin-borde" id="chkchkaplresenc" value="1"  disabled></td>
              </tr>
			  
			  <tr>
                <td height="22"><div align="right">Ente </div></td>
                <td  colspan="3"><input name="txt_codente" type="text" id="txt_codente" size="13" readonly>                 
                <input name="txt_ente" type="text" class="sin-borde" id="txt_ente"  size="53" readonly>                  </td>
              </tr>
			  
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="3"><div align="left"><?php print $ls_titulo; ?></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"><?php print $ls_nomestpro1;?></div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center"  size="<?php print $li_maxlen; ?>" maxlength="20" readonly>
            <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="53" readonly>
            <input name="txtestcla1" type="hidden" id="txtestcla1" size="2" value="<?php print $ls_estcla1;?>">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"><?php print $ls_nomestpro2;?></div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center"  size="<?php print $li_maxlen; ?>" maxlength="6" readonly>
            <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" size="53" readonly>
            <input name="txtestcla2" type="hidden" id="txtestcla2" size="2" value="<?php print $ls_estcla2;?>">
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"><?php print $ls_nomestpro3;?></div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro3" type="text" id="txtcodestpro3" style="text-align:center"  size="<?php print $li_maxlen; ?>" maxlength="3" readonly>
              <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" size="53" readonly>
              <input name="txtestcla3" type="hidden" id="txtestcla3" size="2" value="<?php print $ls_estcla3;?>">
          </div></td>
        </tr>
<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro4" type="hidden" id="txtcodestpro4">
 				<input name="txtdenestpro4" type="hidden" id="txtdenestpro4">
 				<input name="txtcodestpro5" type="hidden" id="txtcodestpro5">
 				<input name="txtdenestpro5" type="hidden" id="txtdenestpro5">
                <input name="txtestcla4" type="hidden" id="txtestcla4" size="2" value="<?php print $ls_estcla4;?>">
                <input name="txtestcla5" type="hidden" id="txtestcla5" size="2" value="<?php print $ls_estcla5;?>">
<?php }
	  else
	  {?>
            <tr>
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro4; ?>			  </div>			  </td>
              <td colspan="3">
			    <div align="left">
                <input name="txtcodestpro4" type="text" id="txtcodestpro4" size="<?php print $li_maxlen; ?>" maxlength="2" readonly>
                <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" size="53" readonly>
                <input name="txtestcla4" type="hidden" id="txtestcla4" size="2" value="<?php print $ls_estcla4;?>">
                </div></td>
            </tr>
            <tr colspan="3">
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro5; ?>			  </div>			  </td>
              <td colspan="3">
			    <div align="left">
                <input name="txtcodestpro5" type="text" id="txtcodestpro5" size="<?php print $li_maxlen; ?>" maxlength="2" readonly>
                <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5"  size="53" readonly>
                <input name="txtestcla5" type="hidden" id="txtestcla5" size="2" value="<?php print $ls_estcla5;?>">
                </div></td>
            </tr>
<?php } ?>

        <tr>
          <td height="22"><div align="right">Cuenta de Presupuesto </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcuepre" type="text" id="txtcuepre" size="28" maxlength="25" readonly>
            <input name="txtdencuepre" type="text" class="sin-borde" id="txtdencuepre" size="50" maxlength="100" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Contable </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcuecon" type="text" id="txtcuecon" size="28" maxlength="25" readonly>
            <input name="txtdencuecon" type="text" class="sin-borde" id="txtdencuecon" size="50" maxlength="100" readonly>
		  </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta de Ingreso </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcueingcon" type="text" id="txtcueingcon"  size="28" maxlength="25" readonly>
            <input name="txtdencueing" type="text" class="sin-borde" id="txtdencueing" size="50" maxlength="100" readonly>
		  </div>		  </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Porcentaje </div></td>
          <td colspan="3">
            <input name="txtporingcon" type="text" id="txtporingcon" size="5" maxlength="6" style="text-align:right" readonly>          </td>
        </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">Reportes</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Reportar Acumulado </div></td>
          <td colspan="3"><div align="left">
            <input name="chkrepacucon" type="checkbox" class="sin-borde" id="chkrepacucon" disabled>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Reportar Constante de Unidad </div></td>
          <td colspan="3"><div align="left">
                <input name="chkrepconsunicon" type="checkbox" class="sin-borde" id="chkrepconsunicon" value="1" disabled> 
            C&oacute;digo Constante 
            <input name="txtconsunicon" type="text" id="txtconsunicon" size="20" maxlength="10" readonly>          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Reportar en </div></td>
          <td colspan="3"><select name="cmbquirepcon" id="cmbquirepcon" disabled>
            <option value="-" selected>--Seleccione Una--</option>
            <option value="1">Primera Quincena</option>
            <option value="2">Segunda Quincena</option>
            <option value="3">Ambas Quincenas</option>
          </select> 
            (Solo para n&oacute;minas mensuales con divisi&oacute;n de concepto) </td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="right"></div>            
            <div align="left" class="titulo-celdanew">Aporte Patronal </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">
            <div align="right">Asignar al fideicomiso </div>
          </div></td>
          <td colspan="3"><div align="left">
            <input name="chkasifidpat" type="checkbox" class="sin-borde" id="chkasifidpat" value="1" disabled>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">F&oacute;rmula</div></td>
          <td colspan="3"><div align="left">
            <input name="txtforpatcon" type="text" id="txtforpatcon" size="90" maxlength="200" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Valor M&iacute;nimo </div></td>
          <td colspan="3"><div align="left">
            <input name="txtvalminpatcon" type="text" id="txtvalminpatcon" size="23" maxlength="20" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Valor M&aacute;ximo </div></td>
          <td colspan="3"><div align="left">
            <input name="txtvalmaxpatcon" type="text" id="txtvalmaxpatcon" size="23" maxlength="20" readonly>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Estad&iacute;stico</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcueprepat" type="text" id="txtcueprepat" size="28" maxlength="25" readonly>
            <input name="txtdencueprepat" type="text" class="sin-borde" id="txtdencueprepat" size="50" maxlength="100" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Contable </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcueconpat" type="text" id="txtcueconpat" size="28" maxlength="25" readonly>
            <input name="txtdencueconpat" type="text" class="sin-borde" id="txtdencueconpat" size="50" maxlength="100" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo de Reporte Personal </div></td>
          <td colspan="3"><div align="left">
            <input name="txttitretempcon" type="text" id="txttitretempcon" size="13" maxlength="10" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo de Reporte Patr&oacute;n </div></td>
          <td colspan="3"><div align="left">
            <input name="txttitretpatcon" type="text" id="txttitretpatcon" size="13" maxlength="10" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Destino de Contabilizaci&oacute;n </div></td>
          <td colspan="3"><div align="left">
              <select name="cmbdescon" id="cmbdescon" onChange="javascript: ue_limpiar();" disabled>
                <option value=" "> </option>
                <option value="P" >PROVEEDOR</option>
                <option value="B" >BENEFICIARIO</option>
              </select>
              <input name="txtcodproben" type="text" id="txtcodproben" size="15" maxlength="10" readonly>
              <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" size="50" maxlength="30" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="3"><div align="left"></div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_cerrar()
{
	location.href = "sigespwindow_blank_hnomina.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_sno_cat_hconcepto.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
</script> 
</html>