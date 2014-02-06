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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_vacacion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	global $ls_sueint;
	$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));
	unset($io_sno);
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$li_codvac,$ld_fecvenvac,$ld_fecdisvac,$ld_fecreivac,$ls_stavac,$li_diavac,$li_diaadivac;
		global $li_diabonvac,$li_diaadibon,$li_diapenvac,$li_diafer,$li_sabdom,$li_sueintvac,$li_sueintbonvac,$ls_obsvac;
		global $ls_diapag,$ls_pagcan,$li_dianorvac,$ls_persalvac,$ls_peringvac,$li_quisalvac,$li_quireivac,$la_stavac,$ls_existe;
		global $ls_operacion,$io_fun_nomina;
		global $ls_pagpersal,$li_diapervac,$ls_stavac;
		$ls_codper="";
		$ls_nomper="";
		$li_codvac=0;
		$ld_fecvenvac="dd/mm/aaaa";
		$ld_fecdisvac="dd/mm/aaaa";
		$ld_fecreivac="dd/mm/aaaa";
		$ls_stavac="";
		$li_diavac=0;
		$li_diaadivac=0;
		$li_diabonvac=0;
		$li_diaadibon=0;
		$li_diapenvac=0;
		$li_diafer=0;
		$li_sabdom=0;
		$li_sueintvac=0;
		$ls_pagpersal="";
		$li_diapervac=0;
		$li_sueintbonvac=0;
		$ls_obsvac="";
		$ls_diapag="";
		$ls_pagcan="";
		$li_dianorvac=0;
		$ls_peringvac="";
		$ls_persalvac="";
		$ls_stavac="";
		$li_quisalvac=0;
		$li_quireivac=0;
		$la_stavac[0]="";
		$la_stavac[1]="";		
		$la_stavac[2]="";
		$la_stavac[3]="";		
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();		
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
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
   		global $ls_codper, $ls_nomper, $li_codvac, $ld_fecvenvac, $ld_fecdisvac, $ld_fecreivac, $ls_stavac, $li_diavac, $li_diaadivac;
		global $li_diabonvac, $li_diaadibon, $li_diapenvac, $li_diafer, $li_sabdom, $li_sueintvac, $li_sueintbonvac, $ls_obsvac, $li_diapag;
		global $li_pagcan, $li_dianorvac,$ls_persalvac,$ls_peringvac,$li_quisalvac,$li_quireivac, $io_fun_nomina;
		global $ls_pagpersal,$li_diapervac;
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$li_codvac=$_POST["txtcodvac"];
		$ld_fecvenvac=$_POST["txtfecvenvac"];
		$ld_fecdisvac=$_POST["txtfecdisvac"];
		$ld_fecreivac=$_POST["txtfecreivac"];
		$ls_stavac=$_POST["cmbstavac"];		
		$li_diavac=$_POST["txtdiavac"];
		$li_diaadivac=$_POST["txtdiaadivac"];
		$li_diabonvac=$_POST["txtdiabonvac"];
		$li_diaadibon=$_POST["txtdiaadibon"];
		$li_diapenvac=$_POST["txtdiapenvac"];
		$li_diafer=$_POST["txtdiafer"];
		$li_sabdom=$_POST["txtsabdom"];
		$li_sueintvac=$_POST["txtsueintvac"];
		$li_sueintbonvac=$_POST["txtsueintbonvac"];
		$ls_obsvac=$_POST["txtobsvac"];
		$li_dianorvac=$_POST["txtdianorvac"];
		$ls_peringvac=$_POST["txtperingvac"];
		$ls_persalvac=$_POST["txtpersalvac"];
		$li_quisalvac=$_POST["txtquisalvac"];
		$li_quireivac=$_POST["txtquireivac"];	
		$li_diapervac=$_POST["txtdiapervac"];
	//	$ls_stavac=$_POST["txtstavac"];
		$li_diapag=$io_fun_nomina->uf_obtenervalor("chkdiapag","0");
		$li_pagcan=$io_fun_nomina->uf_obtenervalor("chkpagcan","0");
		$ls_pagpersal=$io_fun_nomina->uf_obtenervalor("chkpagpersal","0");
		
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
<title >Definici&oacute;n de Vacaci&oacute;n</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_sno_c_vacacion.php");
	$io_vacacion=new sigesp_sno_c_vacacion();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			break;
			
			
		

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_vacacion->uf_update_vacacion($ls_codper,$li_codvac,$ld_fecvenvac,$ld_fecdisvac,$ld_fecreivac,$ls_stavac,
															$li_diavac,$li_diaadivac,$li_diabonvac,$li_diaadibon,$li_diapenvac,$li_diafer,
															$li_sabdom,$li_sueintvac,$li_sueintbonvac,$ls_obsvac,$li_diapag,$li_pagcan,
															$li_dianorvac,$ls_peringvac,$ls_persalvac,$li_quisalvac,$li_quireivac,
															$ls_pagpersal,$la_seguridad);
				if($lb_valido)
				{
					uf_limpiarvariables();
					$ls_existe="FALSE";
					$ls_codper=$_POST["txtcodper"];
					$ls_nomper=$_POST["txtnomper"];
				}
				else
				{
					$io_fun_nomina->uf_seleccionarcombo("1-2-3-4",$ls_stavac,$la_stavac,4);
					if($li_diapag==1)
					{
						$ls_diapag="checked";
					}
					else
					{
						$ls_diapag="";
					}
					if($li_pagcan==1)
					{
						$ls_pagcan="checked";
					}
					else
					{
						$ls_pagcan="";
					}
				}
				break;
			
	}
	$io_vacacion->uf_destructor();
	unset($io_vacacion);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>
	 </td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_print();"></a><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_personal.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="650" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"><input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Vacaciones </td>
        </tr>
        <tr>
          <td width="136" height="22"><div align="right"></div></td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td width="157"><div align="left">
              <input name="txtcodvac" type="text" id="txtcodvac" value="<?php print $li_codvac;?>" size="13" maxlength="10">
          </div></td>
          <td width="153"><div align="right">Vencimiento</div></td>
          <td width="144"><div align="left">
            <input name="txtfecvenvac" type="text" id="txtfecvenvac" value="<?php print $ld_fecvenvac;?>" size="15" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado Actual </div></td>
          <td colspan="3"><div align="left">
              <select name="cmbstavac" id="cmbstavac">
                <option value="" selected>--Seleccione Una--</option>
                <option value="1" <?php print $la_stavac[0];?>>Vencidas</option>
                <option value="2" <?php print $la_stavac[1];?>>Programadas</option>
                <option value="3" <?php print $la_stavac[2];?>>En Vacaciones</option>
                <option value="4" <?php print $la_stavac[3];?>>Disfrutadas</option>
              </select>
              <input name="txtstavac" type="hidden" id="txtstavac" value="<?php print $ls_stavac;?>">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha Distrute </div></td>
          <td>
              <div align="left">
                <input name="txtfecdisvac" type="text" id="txtfecdisvac" value="<?php print $ld_fecdisvac;?>" size="15" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
              </div></td>
          <td><div align="right">Fecha Reingreso</div></td>
          <td><div align="left">
            <input name="txtfecreivac" type="text" id="txtfecreivac" value="<?php print $ld_fecreivac;?>" size="15" maxlength="10" datepicker="true" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;do de Salida </div></td>
          <td><div align="left">
            <input name="txtpersalvac" type="text" id="txtpersalvac" value="<?php print $ls_persalvac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
          <td><div align="right">Per&iacute;do de Reingreso </div></td>
          <td><div align="left">
            <input name="txtperingvac" type="text" id="txtperingvac" value="<?php print $ls_peringvac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Quincena de Salida </div></td>
          <td><div align="left">
            <input name="txtquisalvac" type="text" id="txtquisalvac" value="<?php print $li_quisalvac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
          <td><div align="right">Quincena de Reingreso</div></td>
          <td><div align="left">
            <input name="txtquireivac" type="text" id="txtquireivac" value="<?php print $li_quireivac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&ordm; D&iacute;as </div></td>
          <td>
              <div align="left">
                <input name="txtdiavac" type="text" id="txtdiavac" value="<?php print $li_diavac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td>
          <td><div align="right">N&ordm; D&iacute;as Adicionales </div></td>
          <td><div align="left">
            <input name="txtdiaadivac" type="text" id="txtdiaadivac" value="<?php print $li_diaadivac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&ordm; D&iacute;as Bono </div></td>
          <td>
              <div align="left">
                <input name="txtdiabonvac" type="text" id="txtdiabonvac" value="<?php print $li_diabonvac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td>
          <td><div align="right">N&ordm; D&iacute;as Adicionales Bono </div></td>
          <td><div align="left">
            <input name="txtdiaadibon" type="text" id="txtdiaadibon" value="<?php print $li_diaadibon;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&ordm; Feriados</div></td>
          <td>
              <div align="left">
                <input name="txtdiafer" type="text" id="txtdiafer" value="<?php print $li_diafer;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td>
          <td><div align="right">N&ordm; S&aacute;bados y Domingos </div></td>
          <td><div align="left">
            <input name="txtsabdom" type="text" id="txtsabdom" value="<?php print $li_sabdom;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&ordm; Pendientes </div></td>
          <td>
              <div align="left">
                <input name="txtdiapenvac" type="text" id="txtdiapenvac" value="<?php print $li_diapenvac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td>
			  <td><div align="right">Nº D&iacute;as de Permiso Descontables de Vacaciones </div></td>
          <td><div align="left">
            <input name="txtdiapervac" type="text" id="txtdiapervac" value="<?php print $li_diapervac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
          </div></td>
		  </tr>
		<tr>
          <td><div align="right">Total de D&iacute;as de Vacaciones </div></td>
          <td><div align="left">
            <input name="txtdianorvac" type="text" id="txtdianorvac" value="<?php print $li_dianorvac;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"><?php if ($ls_sueint==""){ print "Sueldo Integral Bono";}
		                                           else { print $ls_sueint." Bono";} ?></div></td>
          <td>
              <div align="left">
                <input name="txtsueintbonvac" type="text" id="txtsueintbonvac" value="<?php print $li_sueintbonvac;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
              </div></td>
          <td><div align="right"><?php if ($ls_sueint==""){ print "Sueldo Integral Vacaciones";}
		                                           else { print $ls_sueint." Vacaciones";} ?></div></td>
          <td><div align="left">
            <input name="txtsueintvac" type="text" id="txtsueintvac" value="<?php print $li_sueintvac;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">D&iacute;as Disfrutados </div></td>
          <td><div align="left">
            <input name="chkdiapag" type="checkbox" id="chkdiapag" value="1" <?php  print $ls_diapag;?>>
          </div></td>
          <td><div align="right">Vacaciones Canceladas </div></td>
          <td><div align="left">
            <input name="chkpagcan" type="checkbox" id="chkpagcan" value="1" <?php  print $ls_pagcan;?>>
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Pagar Vacaciones en el Periodo de Salida</div></td>
          <td><div align="left">
            <input name="chkpagpersal" type="checkbox" id="chkpagpersal" value="1" <?php print $ls_pagpersal;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td colspan="3"><div align="left">
            <textarea name="txtobsvac" cols="80" rows="3" id="txtobsvac" onKeyPress="javascript: ue_validarcomillas(this);" value="<?php print $ls_obsvac;?>"></textarea>
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"><input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php  print $ls_existe;?>"></td>
        </tr>
      </table>      
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	codper=ue_validarvacio(f.txtcodper.value);
	f.action="sigesp_snorh_d_personal.php?codper="+codper;
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		if(f.txtstavac.value=="1")
		{
			valido=true;
			codper=ue_validarvacio(f.txtcodper.value);
			codvac=ue_validarvacio(f.txtcodvac.value);
			stavac=ue_validarvacio(f.cmbstavac.value);
			fecvenvac=ue_validarvacio(f.txtfecvenvac.value);
			fecdisvac=ue_validarvacio(f.txtfecdisvac.value);
			fecreivac=ue_validarvacio(f.txtfecreivac.value);
			persalvac=ue_validarvacio(f.txtpersalvac.value);
			peringvac=ue_validarvacio(f.txtperingvac.value);
			if(!ue_comparar_fechas(fecvenvac,fecdisvac))
			{
				alert("La fecha de Disfrute es menor que la de Vencimiento.");
				valido=false;
			}
			if((!ue_comparar_fechas(fecdisvac,fecreivac))&&(valido))
			{
				alert("La fecha de Reingreso es menor que la de Disfrute.");
				valido=false;
			}
			if((persalvac>peringvac)&&(valido))
			{
				alert("El período de Salida no puede ser mayor que el de Reingreso.");
				valido=false;
			}
			if(valido)
			{
				if ((codper!="")&&(codvac!="")&&(stavac!=""))
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_snorh_d_vacacion.php";
					f.submit();
				}
				else
				{
					alert("Debe llenar todos los datos.");
				}
			}
		}
		else
		{
			alert("Si las vacaciones están programadas, en vacación ó disfrutadas no se puede hacer ningún cambio.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		codper = ue_validarvacio(f.txtcodper.value);
		window.open("sigesp_snorh_cat_vacacion.php?codper="+codper+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>