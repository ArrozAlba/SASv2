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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_d_hpersonalnomina.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$li_rac=$_SESSION["la_nomina"]["racnom"];
	$li_racobrnom=$_SESSION["la_nomina"]["racobrnom"];
	$li_subnomina=$_SESSION["la_nomina"]["subnom"];	
	$li_tipnom=$_SESSION["la_nomina"]["tipnom"];
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$li_implementarcodunirac=trim($io_sno->uf_select_config("SNO","CONFIG","CODIGO_UNICO_RAC","0","I"));
	global $ls_sueint;
	$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));
	unset($io_sno);
	unset($io_funciones);
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
<title >Asignaci&oacute;n de Personal a N&oacute;mina</title>
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
    <td height="136">      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-ventana">
        <td height="20" colspan="4" class="titulo-ventana">Asignaci&oacute;n de Personal a N&oacute;mina </td>
      </tr>
      <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de Personal </td>
      </tr>
      <tr>
        <td width="130" height="22"><div align="right">C&oacute;digo</div></td>
        <td colspan="3"><div align="left">
  <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10" readonly>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="txtestper" type="text" class="sin-borde2" id="txtestper" size="20" maxlength="20" readonly>
		  <input name="txtestencper" type="text" class="sin-borde2" id="txtestencper" size="20" maxlength="20" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre y Apellido </div></td>
        <td colspan="3"><div align="left">
          <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" size="90" maxlength="120" readonly>
        </div></td>
      </tr>
      <tr class="titulo-celdanew">
        <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Informaci&oacute;n de N&oacute;mina </div></td>
      </tr>
	  <?php 
		  if(($li_implementarcodunirac=="1")&&($li_rac=="1")) {?>
      <tr>
        <td height="22"><div align="right">C&oacute;digo RAC </div></td>
        <td colspan="3">
          <input name="txtcodunirac" type="text" id="txtcodunirac" size="12" maxlength="10" readonly>        </td>
      </tr>
	 <?php }
	 	   if($li_subnomina=="1") {?>	  
      <tr>
        <td height="22"><div align="right">Subn&oacute;mina</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodsubnom" type="text" id="txtcodsubnom" size="13" maxlength="10" readonly>
          <input name="txtdessubnom" type="text" class="sin-borde" id="txtdessubnom" size="63" maxlength="60" readonly>
        </div></td>
      </tr>
	 <?php }
	  	   if($li_rac=="0") {
	 ?>	  
      <tr>
        <td height="22"><div align="right">Cargo</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodcar" type="text" id="txtcodcar" value="" size="13" maxlength="10"  readonly>
          <a href="javascript: ue_buscarcargo();"></a>
          &nbsp;
            <input name="txtdescar" type="text" class="sin-borde" id="txtdescar" value="" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
	  <?php 
		 	   if(($li_tipnom=="3")||($li_tipnom=="4"))
			   {
		 ?>	  
      <tr>
        <td height="22"><div align="right">Clasificación Obrero</div></td>
        <td colspan="3"><div align="left">
          <input name="txtgrado" type="text" id="txtgrado" value="" size="13" maxlength="4"  readonly>
          <a href="javascript: ue_buscarclasificacionobrero();"></a>
        </div></td>
      </tr>

		<?php
		     }
		   }
	 	   else
		   {
				if(($li_racobrnom=='1')&&($li_racnom=='1'))
				{
			 ?>	         
					<tr>
					<td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
					<td colspan="3"><div align="left">
					  <input name="txtcodasicar" type="text" id="txtcodasicar" value="" size="10" maxlength="7"  readonly>
					  <a href="javascript: javascript: ue_buscarasignacioncargo();"></a>
					  <input name="txtdenasicar" type="text" class="sin-borde" id="txtdenasicar" value="" size="27" maxlength="24" readonly>
					</div></td>
					</tr>
					<tr>
					<td height="22"><div align="right">Clasificación Obrero</div></td>
					<td colspan="3"><div align="left">
					  <input name="txtgrado" type="text" id="txtgrado" value="" size="13" maxlength="4"  readonly>					 
					</div></td>
					</tr>
		 <?php 
				}
				else
				{
			?>
					<tr>
					<td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
					<td colspan="3"><div align="left">
					  <input name="txtcodasicar" type="text" id="txtcodasicar" value="" size="10" maxlength="7"  readonly>
					  <a href="javascript: ue_buscarasignacioncargo();"></a>
					  <input name="txtdenasicar" type="text" class="sin-borde" id="txtdenasicar" value="" size="27" maxlength="24" readonly>
					</div></td>
					</tr>
				   <tr>
				   <td height="22"><div align="right">Denominación del Cargo</div></td>
				   <td colspan="3"><div align="left">
				  <input name="txtdescasicar" type="text" id="txtdescasicar" value="" 
					size="50" maxlength="100" onKeyUp="javascript: ue_validarcomillas(this);">				
				   </div></td>
				  </tr>
			<?php
				 }
			?>
	  
	  <?php 
		if($li_tipnom!="3")
		 {
		   if ($li_tipnom!="4")
		   {
	 ?>	  
      <tr>
        <td height="22"><div align="right">Tabulador</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodtab" type="text" id="txtcodtab" size="25" maxlength="20" readonly>
          &nbsp;
            <input name="txtdestab" type="text" class="sin-borde" id="txtdestab" size="60" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Paso
        </div></td>
        <td width="141"><div align="left">
          <input name="txtcodpas" type="text" id="txtcodpas" size="18" maxlength="15" readonly>            
        </div></td>
        <td width="157"><div align="right">Grado
        </div></td>
        <td width="262"><div align="left">
          <input name="txtcodgra" type="text" id="txtcodgra" size="18" maxlength="15" readonly>
        </div></td>
      </tr>
	   <?php 
			 }// fin del if ($li_tipnom!="4")
		 }//fin del ($li_tipnom!="3")
		
		 else
		 {	   
	   ?>
	   	<tr>         
        <td height="22"><div align="right">Grado del Obrero</div></td>
        <td colspan="3"><div align="left">
          <input name="txtgrado" type="text" id="txtgrado" size="13" maxlength="4"  readonly>
        </div></td>
      </tr>  
	   <?php
	     }
		  }//fin del else 
	   ?>
        
        <td height="22"><div align="right">Sueldo</div></td>
        <td><div align="left">
          <input name="txtsueper" type="text" id="txtsueper" size="23" maxlength="20" style="text-align:right" readonly>
        </div></td>
        <td><div align="right">Compensacion</div></td>
        <td><div align="left">
          <input name="txtcompensacion" type="text" id="txtcompensacion" style="text-align:right" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Unidad Administrativa </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcoduniadm" type="text" id="txtcoduniadm" size="19" maxlength="16" readonly>	  
          <input name="txtdesuniadm" type="text" class="sin-borde" id="txtdesuniadm" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Departamneto</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcoddep" type="text" id="txtcoddep"  size="19" maxlength="16" readonly> 
          <input name="txtdendep" type="text" class="sin-borde" id="txtdendep"  size="65" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Dedicaci&oacute;n</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodded" type="text" id="txtcodded" size="6" maxlength="3" readonly>
          <input name="txtdesded" type="text" class="sin-borde" id="txtdesded" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo de Personal </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodtipper" type="text" id="txtcodtipper" size="7" maxlength="4" readonly>
          <input name="txtdestipper" type="text" class="sin-borde" id="txtdestipper" size="60" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Categor&iacute;a</div></td>
        <td colspan="3">
          
            <div align="left">
              <select name="cmbcatjub" id="cmbcatjub" disabled>
                <option value="000" selected>--Seleccione--</option>
                <option value="001">Docente</option>
                <option value="002">Administrativo</option>
                <option value="003">Obrero</option>
              </select>
            </div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Condici&oacute;n</div></td>
        <td colspan="3">
          
            <div align="left">
              <select name="cmbconjub" id="cmbconjub" disabled>
                <option value="0000" selected>--Seleccione--</option>
                <option value="0001">Jubilado</option>
                <option value="0002">Pensionado</option>
                <option value="0003">Sobreviviente</option>
              </select>
            </div></td>
        </tr>
      <tr>
        <td height="22"><div align="right"><?php if ($ls_sueint==""){print "Sueldo Integral";}else{print $ls_sueint;}?></div></td>
        <td>
            <div align="left">
              <input name="txtsueintper" type="text" id="txtsueintper" size="23" maxlength="20" style="text-align:right" readonly>        
                </div></td>
        <td><div align="right">Sueldo Promedio</div></td>
        <td><div align="left">
          <input name="txtsueproper" type="text" id="txtsueproper" size="23" maxlength="20" style="text-align:right" readonly>
        </div></td>
      </tr>
	   <tr>
        <td height="22"><div align="right">Salario Normal</div></td>
        <td colspan="3"><div align="left">
          <input name="txtsalnorper" type="text" id="txtsalnorper" size="12" maxlength="9"  readonly>
        </div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Horas</div></td>
        <td colspan="3"><div align="left">
          <input name="txthorper" type="text" id="txthorper" size="12" maxlength="9" readonly>
        </div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Fecha de Ingreso a la n&oacute;mina </div></td>
        <td><div align="left">
          <input name="txtfecingper" type="text" id="txtfecingper" size="13" maxlength="10" readonly>
        </div></td>
        <td><div align="right">Culminaci&oacute;n de Contrato </div></td>
        <td><div align="left">
          <input name="txtfecculcontr" type="text" id="txtfecculcontr" size="13" maxlength="10" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha de Ascenso </div></td>
        <td colspan="3"><input name="txtfecascper" type="text" id="txtfecascper" size="15" maxlength="10" readonly></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tabla de Vacaciones </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodtabvac" type="text" id="txtcodtabvac" size="5" maxlength="2" readonly>
          <input name="txtdentabvac" type="text" class="sin-borde" id="txtdentabvac" size="60" maxlength="120" readonly>
        </div></td>
      </tr>
		<tr>
        <td height="22"><div align="right">Clasificación de Viaticos</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodclavia" type="text" id="txcodclavia"  size="7" maxlength="1" readonly>
          <input name="txtdencat" type="text" class="sin-borde" id="txtdencat"  size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Escala Docente </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodescdoc" type="text" id="txcodescdoc" size="7" maxlength="4" readonly>
          <input name="txtdesescdoc" type="text" class="sin-borde" id="txtdesescdoc" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Clasificaci&oacute;n Docente </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodcladoc" type="text" id="txtcodcladoc" size="7" maxlength="4" readonly>
          <input name="txtdescladoc" type="text" class="sin-borde" id="txtdescladoc" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Ubicaci&oacute;n F&iacute;sica</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodubifis" type="text" id="txtcodubifis" size="7" maxlength="4" readonly>
          <input name="txtdesubifis" type="text" class="sin-borde" id="txtdesubifis" size="80" maxlength="100" readonly>
        </div></td>
      </tr>
	  
	   <tr>
        <td height="22"><div align="right">Fecha de Supensi&oacute;n</div></td>
        <td colspan="3"><input name="txtfecsusper" type="text" id="txtfecsusper"  size="15" maxlength="10" readonly></td>
      </tr>
	  
	    <tr>
        <td height="22"><div align="right">Fecha de Egreso</div></td>
        <td colspan="3"><input name="txtfecegrper" type="text" id="txtfecegrper"  size="15" maxlength="10" readonly></td>
      </tr>
	  
	  <tr>
        <td height="22"><div align="right">Observaci&oacute;n Egreso/Suspenci&oacute;n</div></td>
        <td colspan="3"><textarea name="txtobsegrper" cols="80" rows="3" id="txtobsegrper" onKeyUp="javascript: ue_validarcomillas(this);" readonly></textarea></td>
      </tr>
	  
	  
      <tr class="titulo-celdanew">
        <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Informaci&oacute;n de Pago</div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">
          <div align="right">Pago en Efectivo &oacute; Cheque </div>
        </div></td>
        <td>
          <div align="left">
            <input name="chkpagefeper" type="checkbox" class="sin-borde" id="chkpagefeper" value="1" disabled>
            </div></td>
        <td><div align="right">Pago por Banco </div></td>
        <td><div align="left">
          <input name="chkpagbanper" type="checkbox" class="sin-borde" id="chkpagbanper" value="1" disabled>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Pago por Taquilla</div></td>
        <td colspan="3"><input name="chkpagtaqper" type="checkbox" class="sin-borde" id="chkpagtaqper" disabled></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Abono</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcuecon" type="text" id="txtcuecon" size="28" maxlength="25" readonly>
          <input name="txtdencuecon" type="text" class="sin-borde" id="txtdencuecon" size="50" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Banco</div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodban" type="text" id="txtcodban" size="7" maxlength="4" readonly>
            <input name="txtnomban" type="text" class="sin-borde" id="txtnomban" size="50" readonly>
            </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Agencia</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodage" type="text" id="txtcodage" size="13" maxlength="10" readonly>
          <input name="txtnomage" type="text" class="sin-borde" id="txtnomage" size="50" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nro de Cuenta </div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodcueban" type="text" id="txtcodcueban" size="28" maxlength="25" readonly>
            </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo de Cuenta </div></td>
        <td colspan="3"><div align="left">
          <select name="cmbtipcuebanper" id="cmbtipcuebanper" disabled>
            <option value="" selected>--Seleccione Una--</option>
            <option value="A" >Ahorro</option>
            <option value="C" >Corriente</option>
            <option value="L" >Activos L&iacute;quidos</option>
          </select>
          
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cesta Ticket</div></td>
        <td colspan="3">
          <div align="left">
            <select name="cmbtipcestic" id="cmbtipcestic" disabled>
              <option value="" selected>--Seleccione Uno--</option>
              <option value="TA" >Tarjeta</option>
              <option value="TI" >Ticket</option>
            </select>
            </div></td>
      </tr>
      <tr>
        <td height="22" colspan="4" class="titulo-celdanew">Informaci&oacute;n de Pensiones </td>
        </tr>
      <tr>
        <td height="22"><div align="right">Sueldo B&aacute;sico </div></td>
        <td><div align="left">
          <input name="txtsuebasper" type="text" id="txtsuebasper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>
        </div></td>
        <td><div align="right">Prima Especial </div></td>
        <td><div align="left">
          <input name="txtpriespper" type="text" id="txtpriespper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>
		</div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Prima Transporte </div></td>
        <td><div align="left">
          <input name="txtpritraper" type="text" id="txtpritraper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>		
		</div></td>
        <td><div align="right">Prima Profesi&oacute;n </div></td>
        <td><div align="left">
          <input name="txtpriproper" type="text" id="txtpriproper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>		
		</div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Prima A&ntilde;os Servicio </div></td>
        <td><div align="left">
          <input name="txtprianoserper" type="text" id="txtprianoserper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>				
		</div></td>
        <td><div align="right">Sub total  </div></td>
        <td><div align="left">
          <input name="txtsubtotper" type="text" id="txtsubtotper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>				
		</div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Prima Descendencia </div></td>
        <td><div align="left">
          <input name="txtpridesper" type="text" id="txtpridesper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>				
		</div></td>
        <td><div align="right">Porcentaje de Pensi&oacute;n </div></td>
        <td><div align="left">
          <input name="txtporpenper" type="text" id="txtporpenper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>				
		</div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Prima No Ascenso </div></td>
        <td><div align="left">
            <input name="txtprinoascper" type="text" id="txtprinoascper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>
        </div></td>
        <td><div align="right">Monto de Pensi&oacute;n </div></td>
        <td><div align="left">
            <input name="txtmonpenper" type="text" id="txtmonpenper" size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Primera Remuneraci&oacute;n </div></td>
        <td><div align="left">
            <input name="txtprimrem" type="text" id="txtprimrem"  size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>
        </div></td>
        <td><div align="right">Segunda Remuneraci&oacute;n </div></td>
        <td><div align="left">
            <input name="txtsegrem" type="text" id="txtsegrem"  size="23" maxlength="20"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fé de Vida </div></td>
        <td><div align="left">
          <input name="txtfecvid" type="text" id="txtfecvid" size="15" maxlength="10"  readonly>
        </div></td>
       <td height="22"><div align="right">Tipo de Pension</div></td>
        <td colspan="3">
          <div align="left">
            <select name="cmbtippen" id="cmbtippen" disabled="disabled">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="1">SOBREVIVIENTE</option>
              <option value="2">NO GENERA</option>
            </select>
            </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3"><input name="rac" type="hidden" id="rac" value="<?php print $li_rac;?>">
          <input name="codunirac" type="hidden" id="codunirac" value="<?php print $li_implementarcodunirac;?>"></td>
      </tr>
    </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_sno_cat_hpersonalnomina.php?subnom=<?php print $li_subnomina;?>","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_cerrar()
{
	location.href = "sigespwindow_blank_hnomina.php";
}
</script>
</html>