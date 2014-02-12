<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../../../../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("../../../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../../../');	
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_personal.php",$ls_permisos,$la_seguridad,$la_permisos);
	//--------------------------------------------------------------------------------------------------------------
	 
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Personal</title>

<link rel="stylesheet" type="text/css" href="../../resources/css/ext-all.css" />
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_personal.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>

<style type="text/css">

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
	color: #006699;}

.Estilo2 {
	font-size: 12px;
	color: #6699CC;
	font-family: Georgia, "Times New Roman", Times, serif;
}
</style>
</head>

<body class=" yui-skin-sam" onLoad="ue_inicializar();">

<?php 
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" cellpadding="0" cellspacing="0">
			<td width="432" height="20" align="left" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Recursos Humanos</td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
			<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p><div id="mostrar" align="center"></div></p>

<form action="" method="post" enctype="multipart/form-data" name="form1">
<?php

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

<input name="hidguardar" type="hidden" id="hidguardar" value="">
<input name="hidguardar_est" type="hidden" id="hidguardar_est" value="insertar">
<input name="hidguardar_trab" type="hidden" id="hidguardar_trab" value="insertar">
<input name="hidguardar_fam" type="hidden" id="hidguardar_fam" value="insertar">
<input name="hidguardar_per" type="hidden" id="hidguardar_per" value="insertar">
<input name="hidguardar_deducc" type="hidden" id="hidguardar_deducc" value="insertar">
<input name="hidguardar_deduccfam" type="hidden" id="hidguardar_deduccfam" value="insertar">
<input name="hidguardar_ben" type="hidden" id="hidguardar_ben" value="insertar">
<input name="hidguardar_prem" type="hidden" id="hidguardar_prem" value="insertar">
<input name="hidcontrolper" type="hidden" id="hidcontrolper" value="">
<input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
<input name="hidcontrol3" type="hidden" id="hidcontrol3" value="">
<input name="hidcontrolcar" type="hidden" id="hidcontrolcar" value="1">
<input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="C" readonly>
<table width="823" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
<td width="823">

  
  <div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Datos Personales</em></a></li>
        <li class="desabled"><a href="#tab2"><em>Estudios</em></a></li>
        <li><a href="#tab3"><em>Experiencias</em></a></li>
        <li><a href="#tab4"><em>Familiares</em></a></li>       
        <li><a href="#tab6"><em>Beneficiarios</em></a></li>
		<li><a href="#tab7"><em>Permisos</em></a></li>
		<li><a href="#tab8"><em>Deducciones</em></a></li>
		<li><a href="#tab9"><em>Premiaciones</em></a></li>
    </ul>            
    <div class="yui-content">
        <div><p><table width="769" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="3" class="titulo-ventana">Definici&oacute;n de Personal </td>
        </tr>
        <tr>
          <td height="20" colspan="3" class="titulo-celdanew">Informaci&oacute;n</td>
        </tr>
        <tr class="formato-blanco">
          <td height="20" colspan="3"><div align="center">Los Campos en (*) son necesarios para el Expediente del Personal</div></td>
        </tr>
        <tr>
          <td width="206" height="22"><div align="right">(*) C&oacute;digo</div></td>
          <td width="403"><div align="left">
              <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10"   onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,10);ue_chequear_codper()">
              <input name="txtestper" type="text" class="sin-borde2" id="txtestper" style="text-align: center"  readonly> <input name="hidstatus" type="hidden" id="hidstatus">
          </div></td>
          <td width="154" rowspan="7"><div align="center"><img id="foto" name="foto" src="../../../fotos/silueta.jpg" width="150" height="200" ></div></td>
        </tr>
		 <tr>
          <td height="20"><div align="right">N&uacute;mero de Expediente </div></td>
          <td height="20" colspan="2"><label>
          <input name="txtnumexpper" type="text" id="txtnumexpper" size="23" maxlength="20" onKeyUp="javascript: ue_validarcomillas(this);">
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) C&eacute;dula</div></td>
          <td><div align="left">
              <input name="txtcedper" type="text" id="txtcedper" size="13" maxlength="8"  onKeyUp="javascript: ue_validarnumero(this)" onBlur="javascript: ue_chequear_cedper();">
			<a href="javascript: ue_buscar_solicitud_empleo();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar Solicitud de Empleo</a>
          </div> </td> 
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Nombre </div></td>
          <td><div align="left">
              <input name="txtnomper" type="text" id="txtnomper" size="63" maxlength="60"  onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Apellido</div></td>
          <td><div align="left">
              <input name="txtapeper" type="text" id="txtapeper"  size="63" maxlength="60" onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
		 <tr>
          <td height="20"><div align="right">(*) Tipo de Personal </div></td>
          <td height="20" colspan="2"><label>
            <input name="txtcodtippersss" type="text" id="txtcodtippersss"  size="15" maxlength="10"  readonly>
           <a href="javascript: ue_buscartipopersonalsss();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
            
            <input name="txtdestippersss" type="text" class="sin-borde" id="txtdestippersss"  size="45" maxlength="50" readonly>
            </label></td>
        </tr>
		<tr>
        <td height="22"><div align="right">Cargo Original</div></td>
        <td colspan="3"><div align="left">
            <input name="txtcarantper" type="text" id="txtcarantper"  size="60"  maxlength="100"  onKeyUp="javascript: ue_validarcomillas(this);">		
        </div></td>
      </tr>
		 <tr>
        <td height="22"><div align="right">Cargo Actual</div></td>
        <td colspan="3"><div align="left">
            <input name="txtcaract" type="text" id="txtcaract"  size="60"  readonly>
        </div></td>
      </tr>
	   
	  <tr>
        <td height="22"><div align="right">Unidad Administrativa Actual</div></td>
        <td colspan="3"><div align="left">
            <input name="txtuniadm" type="text" id="txtuniadm"   size="60"  readonly>
        </div></td>
      </tr>
	  <tr class="formato-blanco">
                <td height="20"><div align="right">Gerencia</div></td>
              <td height="28" colspan="2"><input name="txtcodger" type="text" id="txtcodger"  size="16"  readonly>
                <a href="javascript:catalogo_gerencia();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo Departamento" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
                  <input name="txtdenuger" type="text" class="sin-borde" id="txtdenger" size="57"  readonly>
                </td>               
	  </tr>
	  <tr>
          <td height="20"><div align="right">Unidad VIPLADIN </div></td>
          <td height="20" colspan="2"><label>
            <input name="txtcodunivi" type="text" id="txtcodunivi"  size="15" maxlength="15" readonly>
           <a href="javascript: ue_BuscarUnidadVipladin();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
            <input name="txtdenunivi" type="text" class="sin-borde" id="txtdenunivi"  size="65" maxlength="50" readonly>
            </label></td>
        </tr>
		
		<tr>
          <td height="20"><div align="right">C&oacute;digo del Organigrama</div></td>
          <td height="20" colspan="2"><label>
            <input name="txtcodorg" type="text" id="txtcodorg"  size="15" maxlength="10" readonly>
           <a href="javascript: ue_catalogo_organigrama();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
             <input name="txtdesorg" type="text" class="sin-borde" id="txtdesorg"  size="65"  readonly>
               </label></td>
        </tr>
		<tr>
          <td height="20"><div align="right">&nbsp;</div></td>
          <td height="20" colspan="2">
           <a href="javascript: ue_consultar_ubicacion_fisica();">Consultar Ubicaci&oacute;n F&iacute;sica seg&uacute;n Organigrama</a>           </td>
        </tr>
		
		
		<tr>
          <td height="20" colspan="3" class="titulo-celdanew"><div align="center">Tiempo de Servicio en la Instituci&oacute;n</div></td>
        </tr>
		<tr>
          <td height="22"><div align="right"> A&ntilde;os</div></td>
          <td colspan="2"><div align="left">
            <input name="txtano" type="text" id="txtano" value="0" size="5" maxlength="2" style="text-align:right" readonly>
          </div></td>
        </tr> 
		<tr>
          <td height="22"><div align="right">Meses</div></td>
          <td colspan="2"><div align="left">
            <input name="txtmes" type="text" id="txtmes" value="0" size="5" maxlength="2" style="text-align:right" readonly>
          </div></td>
        </tr> 
		<tr>
          <td height="22"><div align="right">D&iacute;as</div></td>
          <td colspan="2"><div align="left">
            <input name="txtdia" type="text" id="txtdia" value="0" size="5" maxlength="2" style="text-align:right"readonly>
          </div></td>
        </tr> 
		
	    <tr>
          <td height="20" colspan="3" class="titulo-celdanew"><div align="center">Fechas de Ingreso y Egreso</div></td>
        </tr>
		  <tr>
          <td height="22"><div align="right">(*) Fecha de Ingreso a la Administraci&oacute;n P&uacute;blica </div></td>
          <td colspan="2">
           <input type="text" name="txtfecingadmpub" id="txtfecingadmpub" size="11" readonly>
  
  <input name="reset" type="reset" onclick="return showCalendar('txtfecingadmpub', '%d/%m/%Y');" value=" ... " />          </td>
        </tr>
          <tr>
          <td height="22"><div align="right"> (*) A&ntilde;os de Servicio Previo </div></td>
          <td colspan="2"><div align="left">
            <input name="txtanoservpreper" type="text" id="txtanoservpreper" value="0" size="5" maxlength="2" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
          </tr>
        <tr>
         <td height="22"><div align="right"> A&ntilde;os de Servicio Previo a la Adm. Pub. <br>
          (Empleado Fijo)</div></td>
          <td colspan="2"><div align="left">
            <input name="txtanoservprefijo" type="text" id="txtanoservprefijo" value="0" size="5" maxlength="2" style="text-align:right"  onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"> A&ntilde;os de Servicio Previo a la Adm. Pub. <br>
          (Empleado Contrato)</div></td>
          <td colspan="2"><div align="left">
            <input name="txtanoservprecont" type="text" id="txtanoservprecont" value="0" size="5" maxlength="2" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>  
		<tr>
          <td height="22"><div align="right"> A&ntilde;os de Servicio Personal Obrero</div></td>
          <td colspan="2"><div align="left">
            <input name="txtanoperobr" type="text" id="txtanoperobr" value="0" size="5" maxlength="2" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>  
		 
        <tr>
          <td height="22"><div align="right">(*) Fecha de Ingreso a la Instituci&oacute;n</div></td>
          <td colspan="2"> <input type="text" name="txtfecingper" id="txtfecingper" size="11" readonly>
  
  <input name="reset" type="reset" onclick="return showCalendar('txtfecingper', '%d/%m/%Y');" value=" ... " /></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de LOSSFAN</div></td>
          <td colspan="2"> <input type="text" name="txtfecleypen" id="txtfecleypen" size="11" readonly>
  
  <input name="reset" type="reset" onclick="return showCalendar('txtfecleypen', '%d/%m/%Y');" value=" ... " /></td>
        </tr>
		 <tr>
          <td height="22"><div align="right">Fecha de Egreso de la Instituci&oacute;n </div></td>
          <td colspan="2"> <input type="text" name="txtfecegrper" id="txtfecegrper" size="11" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Causa de Egreso</div></td>
          <td colspan="2"><div align="left"><input type="text" name="cmbcauegrper" id="cmbcauegrper" size="30" readonly>          
          </div></td>
        </tr>
		 <tr>
          <td height="22"><div align="right">Situaci&oacute;n del Personal </div></td>
          <td colspan="2"><div align="left">
              <select name="cmbsituacion" id="cmbsituacion">
                <option value="" selected>--Seleccione--</option>
                <option value="1" >Ninguno</option>
                <option value="2" >Fallecido</option>
                <option value="3"  >Pensionado</option>
                <option value="4"  >Jubilado</option> 
				<option value="5"  >Retiro</option>                 
              </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de la Situaci&oacute;n </div></td>
          <td colspan="2"><div align="left">
		   <input type="text" name="txtfecsitu" id="txtfecsitu" size="11" readonly>
  
  <input name="reset" type="reset" onclick="return showCalendar('txtfecsitu', '%d/%m/%Y');" value=" ... " />
              
          </div></td>
        </tr>
		 <tr>
          <td height="22"><div align="right">Causales</div></td>
          <td height="20" colspan="2"><div align="left">
            <input name="txtcodcausa" type="text" id="txtcodcausa"  size="5" maxlength="15" onKeyUp="" readonly>
            <a href="javascript: ue_buscarcausa();"><img id="causa" src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" ></a>&nbsp;
            <input name="txtdencausa" type="text" class="sin-borde" id="txtdencausa"  size="60" maxlength="50" readonly>
</div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Fecha de Jubilaci&oacute;n </div></td>
          <td colspan="2"> <input type="text" name="txtfecjubper" id="txtfecjubper" size="11" readonly>
  
  <input name="reset" type="reset" onclick="return showCalendar('txtfecjubper', '%d/%m/%Y');" value=" ... " /></td>
        </tr>
        <tr>
          <td><div align="right">Observaci&oacute;n de Egreso </div></td>
          <td colspan="2" ><div align="left">
              <textarea name="txtobsegrper" cols="80" rows="3" id="txtobsegrper" onKeyUp="javascript: ue_validarcomillas(this);"></textarea>
          </div></td>
        </tr>
		 <tr>
          <td height="22"><div align="right">Fecha de Reingreso a la Instituci&oacute;n </div></td>
          <td colspan="2"> <input type="text" name="txtfecreingper" id="txtfecreingper" size="11" readonly>
  
  <input name="reset" type="reset" onclick="return showCalendar('txtfecreingper', '%d/%m/%Y');" value=" ... " /></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Fecha F&eacute; de Vida </div></td>
          <td colspan="2">
           <input type="text" name="txtfecfevid" id="txtfecfevid" size="11" readonly>
  
  <input name="reset" type="reset" onclick="return showCalendar('txtfecfevid', '%d/%m/%Y');" value=" ... " />          </td>
        </tr>
		
		  <tr>
          <td height="20" colspan="3" class="titulo-celdanew"><div align="center">Datos Personales</div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">(*) Profesi&oacute;n</div></td>
          <td colspan="2"><div align="left">
              <input name="txtcodpro" type="text" id="txtcodpro"  size="6" maxlength="3">
              <a href="javascript: ue_buscarprofesion();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdespro" type="text" class="sin-borde" id="txtdespro"  size="60" maxlength="120" readonly>
          </div></td>
        </tr>
		 <tr>
          <td height="22"><div align="right">(*) Nivel Acad&eacute;mico </div></td>
          <td colspan="2"><div align="left">
              <select name="cmbnivacaper" id="cmbnivacaper">
              <option value="null">--Seleccione--</option>
              <option value="1">Primaria</option>
              <option value="2">Bachiller</option>
		      <option value="3">T&eacute;cnico Superior</option>
              <option value="4">Universitario</option>
			  <option value="5">Maestr&iacute;a</option>
              <option value="6">Postgrado</option>
			  <option value="7">Doctorado</option>
            </select>
              
          </div></td>
        </tr>
		 <tr>
          <td height="22"><div align="right">(*) Nacionalidad</div></td>
          <td colspan="2"><div align="left">
              <select name="cmbnacper" id="cmbnacper">
                <option value="null">--Seleccione--</option>
              <option value="V">Venezolano</option>
              <option value="E">Extrajero</option>
              </select>
          </div></td>
        </tr>
      
        <tr>
          <td height="22"><div align="right">(*) Fecha de Nacimiento</div></td>
          <td>
           <input name="txtfecnacper" type="text" id="txtfecnacper"  size="18" style="text-align:justify"  readonly> <input name="reset" type="reset" onClick="return showCalendar('txtfecnacper', '%d/%m/%Y');" value=" ... " />          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Estado Civil</div></td>
          <td><div align="left">
              <select name="cmbedocivper" id="cmbedocivper">
              <option value="null">--Seleccione--</option>
              <option value="S"> Soltero </option>
              <option value="C"> Casado </option>
              <option value="V"> Viudo </option>
              <option value="D">Divorciado </option>
              <option value="O">Concubino </option>
          </select>
          </div></td>
        </tr>
		 <tr>
          <td height="22"><div align="right">(*) Pa&iacute;s</div></td>
          <td colspan="2"><div align="left"> <select name="cmbcodpai" id="cmbcodpai" style="width:145px" onChange="ue_CambioPais();">
                  <option value="null">Seleccione un Pais</option>
               </select>  <input name="hidcodest"  type="hidden" id="hidcodest"  value=""></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Estado</div></td>
          <td colspan="2" ><div align="left">
          <select name="cmbcodest" id="cmbcodest" style="width:145px" onChange="ue_CambioEstado()" onclick="ue_valida_combopais();">
                  <option value="null">Seleccione un Estado</option>
               </select>
               </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Municipio</div></td>
          <td colspan="2"><div align="left">  <select name="cmbcodmun" id="cmbcodmun" style="width:145px" onChange="ue_CambioMunicipio()"  onclick="ue_valida_cmbcodmun();">
                  <option value="null">Seleccione un Municipio</option> 
               </select> </div> <input name="hidcodmun"  type="hidden" id="hidcodmun"  value=""><input name="hidcodmun2"  type="hidden" id="hidcodmun2"  value=""></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Parroquia</div></td>
          <td colspan="2"><div align="left">  <select name="cmbcodpar" id="cmbcodpar" style="width:145px" onclick="ue_valida_cmbcodpar();">
                  <option value="null">Seleccione un Parroquia</option> 
               </select> 
          </div> <input name="hidcodpar"  type="hidden" id="hidcodpar"  value=""><input name="hidcodpar2"  type="hidden" id="hidcodpar2"  value=""></td>
        </tr>    

		  <tr>
          <td height="22"><div align="right">(*) Direcci&oacute;n</div></td>
          <td><div align="left">
              <input name="txtdirper" type="text" id="txtdirper"  size="63" maxlength="250" onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tel&eacute;fono de Habitaci&oacute;n</div></td>
          <td colspan="2"><div align="left">
              <input name="txttelhabper" type="text" id="txttelhabper"  size="18" maxlength="15" onKeyUp="javascript: ue_validartelefono(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tel&eacute;fono M&oacute;vil </div></td>
          <td colspan="2"><div align="left">
              <input name="txttelmovper" type="text" id="txttelmovper" size="18" maxlength="15" onKeyUp="javascript: ue_validartelefono(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Correo Electr&oacute;nico </div></td>
          <td colspan="2"><div align="left">
              <input name="txtcoreleper" type="text" id="txtcoreleper"  size="63" maxlength="100">
          </div></td>
        </tr>
       
        <tr>
          <td height="22"><div align="right">(*) G&eacute;nero</div></td>
          <td colspan="2"><div align="left">
              <select name="cmbsexper" id="cmbsexper">
              <option value="null">--Seleccione--</option>
              <option value="F">Femenino</option>
              <option value="M">Masculino</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estatura</div></td>
          <td colspan="2"><div align="left">
              <input name="txtestaper" type="text" id="txtestaper"  size="8"  style="text-align:left" onKeyPress='return validarreal2(event,this);'>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Peso</div></td>
          <td colspan="2"><div align="left">
              <input name="txtpesper" type="text" id="txtpesper"  size="8"  style="text-align:left" onKeyPress='return validarreal2(event,this);'>
          </div></td>
        </tr>
		 <tr>
          <td height="22">&nbsp;</td>          
          <td><fieldset><legend>Tallas</legend> 
              	<label>
              	<div align="left">Camisa   
              	  <input name="txttalcamper" type="text" id="txttalcamper" value="" size="8" maxlength="5" style="text-align:right">
              	</div>
              	</label>
              	<label>
              	<br>
              	<div align="left">Pantal&oacute;n
              	  <input name="txttalpanper" type="text" id="txttalpanper" value="" size="8" maxlength="5" style="text-align:right">
              	</div></label>
			  	<label>
			  	<br>
			  	<div align="left">Zapatos  
			  	  <input name="txttalzapper" type="text" id="txttalzapper" value=""  size="8" maxlength="5" style="text-align:right"  onKeyPress='return validarreal2(event,this);'>
			  	</div></label>
			  </fieldset></td>
        </tr>
        <tr>
          <td height="20"><div align="right">HCM (Poliza de Maternidad) </div></td>
          <td height="20" colspan="2"><input name="chkhcmper" type="checkbox" class="sin-borde" id="chkhcmper"  ></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Tipo de Sangre </div></td>
          <td height="20" colspan="2"><div align="left">
              <label></label>
              <label>
              <input name="txttipsanper" type="text" id="txttipsanper"  size="15" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">
              </label>
          </div></td>
        </tr> 
      
      
        <tr>
          <td height="22"><div align="right"> (*) N&uacute;mero de Hijos</div></td>
          <td colspan="2"><div align="left">
              <input name="txtnumhijper" type="text" id="txtnumhijper"  value="0" size="5" maxlength="2" style="text-align:left" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Conyuge Trabaja </div></td>
          <td colspan="2"><div align="left">
              <select name="cmbcontraper" id="cmbcontraper">
                <option value="null" selected>--Seleccione--</option>
                <option value="0" >Si</option>
                <option value="1" >No</option>
              </select>
          </div></td>
        </tr>
		  <tr>
          <td height="22"><div align="right">C&eacute;dula del Beneficiario </div></td>
          <td colspan="2"><div align="left">
              <input name="txtcedbenper" type="text" id="txtcedbenper"  size="11" maxlength="8" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>      
        <tr>
          <td height="20"><div align="right">Centro M&eacute;dico IVSS </div></td>
          <td height="20" colspan="2"><div align="left">
              <select name="cmbcenmedper" id="cmbcenmedper">
                <option value="null" selected>--Seleccione--</option>
                <option value="A01">AV PPAL SAN JOSE MARACAY</option>
                <option value="A02">C AYACUCHO STA ROSA MARACAY </option>
                <option value="A04">AV UNIVERSIDAD EL LIMON</option>
                <option value="A10">URB LAS MERCEDES LA VICTORIA</option>
                <option value="A15">CTRO CAGUA ULT TRANS CORINSA</option>
                <option value="A20">HOSP JOSE VARGAS CALLE OVALLES</option>
                <option value="B01">PASEO MENESES CDAD BOLIVAR</option>
                <option value="B02">PASEO GASPAN MDO PE CD BOLIVAR</option>
                <option value="B10">URB GUAIPARO SAN FELIX</option>
                <option value="B11">VIA RIO CLARO SAN FELIX</option>
                <option value="B12">SECTOR UD-14S SAN FELIX </option>
                <option value="B20">URB LOS OLIVOS PTO ORDAZ</option>
                <option value="B22">UNARE</option>
                <option value="B30">CALLE CUYUHI UPATA</option>
                <option value="B40">FRENTE ALTAVISTA SUR PTO ORDAZ</option>
                <option value="B60">FINAL CALLE GRATEU - EL CALLAO</option>
                <option value="CO1">AV MONTES DE OCA VALENCIA</option>
                <option value="C03">AV PRINCIPAL NAGUANAGUA</option>
                <option value="C05">AV L ALVARADO LA CANDELARIA</option>
                <option value="C10">CARRETERA YAGUA GUACARA</option>
                <option value="C11">C PPAL BARRIO GALLARDO S JOAQUIH</option>
                <option value="C12">C PROCER B MCAL SUCRE MARIARA</option>
                <option value="C13">AV 6 URB POCATERRA TOCUYITO</option>
                <option value="C14">UR PARAPARAL LOS GUAYOS VALENCIA</option>
                <option value="C20">FINAL CALLE PLAZA PTO CABELLO</option>
                <option value="C21">URB STA CRUZ Z IND PTO CABELLO</option>
                <option value="C22">AV PPA LA SORPRESA PTO CABELLO</option>
                <option value="C30">CARRETERA NACIONAL MORON</option>
                <option value="C40">ALTOS COLOHIA PSIQUI NAGUANAGUA</option>
                <option value="C50">AV G MOTORS Z I SUR II VALENCIA</option>
                <option value="D02">AV PRINCIPAL EL CEMENTERIO</option>
                <option value="D03">2DA TRANSVERSAL GUAICAIPURO</option>
                <option value="D04">AV SUCRE CATIA</option>
                <option value="D06">LOS JARDINES DEL VALLE</option>
                <option value="D07">AV INTERCOMUNAL ANTIMANO</option>
                <option value="D08">AV LOS SAMANES EL PARAISO</option>
                <option value="D09">AV PPAL EL CUARTEL CATIA</option>
                <option value="D10">AV M F TOVAR SAN BERNARDINO</option>
                <option value="D12">CENTRO AMB UD5 LA HACIENDA</option>
                <option value="D13">EDF MUNICIPAL MACARAO</option>
                <option value="D50">AV SOUBLETTE LA GUAIRA</option>
                <option value="D51">CALLE PRINCIPAL CARABALLEDA</option>
                <option value="D52">CALLE PRINCIPAL CARAYACA</option>
                <option value="D53">CALL PPAL LOS MANGOS NAIGUATA</option>
                <option value="D54">CIUDAD VACACIONAL LOS CARACAS</option>
                <option value="D60">CALLE LEBRUN PETARE</option>
                <option value="D70">CALLE JOSE FELIX RIVAS CHACAO</option>
                <option value="D80">C GONZALES RINCONES-LA TRINIDAD</option>
                <option value="E01">AV 5 DE JULIO BARCELONA</option>
                <option value="E10">CAMPO GUARAGUAO PTO LA CRUZ</option>
                <option value="E11">BARRIO GUANIRE PTO LA CRUZ</option>
                <option value="E20">CARRETERA VEA EL TIGRE</option>
                <option value="E30">AV INTER SEC GARZA PTO LA CRUZ</option>
                <option value="E40">AV VENEZUELA - ANACO</option>
                <option value="F01">CALLE FEDERACION CORO</option>
                <option value="F10">C RAFAEL GONZALEZ PTO FIJO</option>
                <option value="F20">URB JUDIBANA AMUAY</option>
                <option value="F21">AV TACHIRA AV INTERCOM LAGOVEN</option>
                <option value="F30">CAMPO SHELL HOSPITAL CARDON</option>
                <option value="GOl">SECTOR SANTA ISABEL SAN JUAN</option>
                <option value="G03">URB LA MISION CALABOZO</option>
                <option value="G40">CALLE ATARRAYA - V DE LA PASCUA</option>
                <option value="HOl">CARRET A BIRUACA-SAN FERNANDO</option>
                <option value="JOl">AV CARABOBO SAN CARLOS</option>
                <option value="J30">CARRETERA NACIONAL-TINAQUILLO</option>
                <option value="K01">U PROCERES BRNAS-TURINO FE y A</option>
                <option value="LOl">AV 13 ENTRE CALLS 49 Y 50 BQTO</option>
                <option value="L10">CARRl C 4Y5 BARRIO UNION BQTO</option>
                <option value="L20">PROL A L SALLE F SISAL II BQTO</option>
                <option value="L30">CALLE CURIRAGUA - CARORA</option>
                <option value="M01">AV BERMUDEZ LOS TEQUES</option>
                <option value="M10">URB RUIZ PIMEDA GUARENAS</option>
                <option value="M15">AV PERIMETRAL CUA</option>
                <option value="M20">U LUIS TOVAR CARR STA TERESA TUY</option>
                <option value="NOl">AV 4 DE MAYO PORLAMAR</option>
                <option value="NO5">U VILLA ROSA LADO COL PORLAMAR</option>
                <option value="/01">CARRET NAC VIA LA CRUZ MATURIN</option>
                <option value="POl">AVENIDA 21 - GUANARE</option>
                <option value="P10">URB MAMANICO - ACARIGUA</option>
                <option value="ROl">FIN AV AMERICAS CERCA TERMINAL</option>
                <option value="SOl">CALLE SUCRE CUMANA</option>
                <option value="S20">CALLE CARABOO - CARUPANO</option>
                <option value="TOl">CALLE 5 ESQ CRR 8 SAN CRISTOBAL</option>
                <option value="T10">CALLE 4 PALMIRA</option>
                <option value="T20">ZONA INDUSTRIAL LA FRIA</option>
                <option value="T30">URB STA TERESA SAN CRISTOBAL</option>
                <option value="UOl">CALLE NEGRO PRIMERO TUCUPITA</option>
                <option value="WOl">AV RIO NEGRO PTO AYACUCHO</option>
                <option value="XOl">AV 19 DE ABRIL TRUJILLO</option>
                <option value="X10">FINAL CALLE 10 VALERA</option>
                <option value="Xll">URB LAS BEATRIZ VALERA</option>
                <option value="X20">EDIF CONTINENTAL C 10 VALERA</option>
                <option value="YOl">AVDA YARACUY SAN FELIPE</option>
                <option value="Y40">CARRETERA NACIONAL – CHIVACOA</option>
                <option value="Z0l">AV GUAJIRA URB SAN JACINTO</option>
                <option value="Z02">AV 7 ESQ CALLE VARGAS VERITAS</option>
                <option value="Z03">CALLE 100 SABANETA LARGA </option>
                <option value="Z04">CAMPO PARAISO LA CONCEPCION</option>
                <option value="ZO5">AV 4 NRO 71-37 - BELLA VISTA</option>
                <option value="Z07">ENTRADA DE STA CRUZ DE MARA</option>
                <option value="Z08">CTRO STA RITA CALLE LA PLANTA</option>
                <option value="Z09">AMB CABIMAS AV 32 LOS LAURELES</option>
                <option value="ZlO">AMB CIUDAD OJEDA-C STA MONICA</option>
                <option value="Z20">CTRO AUX MONS GODOY A 5 D JULI</option>
                <option value="Z21">AV F ARM CANCHANCHA DELICIAS</option>
                <option value="Z22">HOSP NORIEGA FRENTE AL LGO MBO</option>
                <option value="Z30">AV BOLIVAR - STA BARBARA ZULIA</option>
              </select>
          </div></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Turno</div></td>
          <td height="20" colspan="2"><div align="left">
              <select name="cmbturper" id="cmbturper">
                <option value="null" selected>--Seleccione--</option>
                <option value="0">Diurno</option>
                <option value="1" >Nocturno</option>
              </select>
          </div></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Horario</div></td>
          <td height="20" colspan="2"><div align="left">
              <input name="txthorper" type="text" id="txthorper"  size="48" maxlength="45" onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
      
		<tr>
          <td height="22"><div align="right">Componente Militar </div></td>
          <td colspan="2"><label>
            <input name="txtcodcom" type="text" id="txtcodcom"  size="12" maxlength="10" readonly>
            <a href="javascript: ue_buscarcomponente();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdescom" type="text" class="sin-borde" id="txtdescom"  size="60" maxlength="50" readonly>
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Rango Militar </div></td>
          <td colspan="2"><label>
            <input name="txtcodran" type="text" id="txtcodran" size="12" maxlength="10" readonly>
            <a href="javascript: ue_buscarrango();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesran" type="text" class="sin-borde" id="txtdesran"  size="60" maxlength="50" readonly>
          </label></td>
        </tr>      	 
		 <tr>
          <td height="20"><div align="right">Modo de Envio del Recibo de Pago</div></td>
          <td height="20" colspan="2">
                <div align="left">
                  <select name="cmbenviorec" id="cmbenviorec">
  			        <option value="null" selected>--Seleccione--</option>
                    <option value="I" >IPOSTEL</option>
                    <option value="D" >DOMESA</option>
                  </select>
                </div>		  </td>	
        </tr>
		<tr>
          <td height="22"><div align="right">Foto</div></td>
          <td colspan="2"><div align="left">
              <input name="btnfoto" type="button" id="btnfoto"  class="boton" onClick="javascript:ue_cargar_foto();" value="Cargar Foto">
			   <input name="hidfotper" type="hidden" id="hidfotper" size="50" maxlength="200">
          </div></td>
        </tr>
		  <tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td colspan="2"><div align="left">
              <textarea name="txtobsper" cols="80" rows="3" id="txtobsper" onKeyUp="javascript: ue_validarcomillas(this);"></textarea>
          </div></td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="3">Lugar de Nacimiento </td>
          </tr>
        <tr>
          <td height="20"><div align="right">Pais de Nacimiento </div></td>
          <td height="20" colspan="2"><select name="cmbcodpainac" id="cmbcodpainac" style="width:145px" onChange="ue_CambioPaisNac();">
                  <option value="null">Seleccione un Pais</option>
               </select></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Estado de Nacimiento </div></td>
          <td height="20" colspan="2"><select name="cmbcodestnac" id="cmbcodestnac" style="width:145px"  onclick="ue_valida_combopaisnac();">
                  <option value="null">Seleccione un Estado</option>  
               </select>
			   <input name="hidcodestnac"  type="hidden" id="hidcodestnac"  value=""></td>
        </tr>
		
        <tr>
          <td height="20" colspan="3" class="titulo-celdanew"><div align="center">Vivienda</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Tipo de Vivienda </div></td>
          <td colspan="2"><div align="left">
              <select name="cmbtipvivper" id="cmbtipvivper">
              <option value="null">--Seleccione--</option>
              <option value="1">Propia</option>
              <option value="2">Alquilada</option>
		      <option value="3">De un Familiar</option>
              <option value="4">No Tiene</option>
              </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tenencia de la Vivienda </div></td>
          <td colspan="2"><div align="left">
              <input name="txttenvivper" type="text" id="txttenvivper"  size="43" maxlength="40" onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Monto Pagado por la Vivienda </div></td>
          <td colspan="2"><div align="left">
              <input name="txtmonpagvivper" type="text" id="txtmonpagvivper"  size="23" maxlength="20" style="text-align:left" onKeyPress='return validarreal2(event,this);'>
          </div></td>
        </tr>
        <tr>
          <td height="20" colspan="3" class="titulo-celdanew"><div align="center">Cuentas</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Caja de Ahorro </div></td>
          <td colspan="2"><div align="left">
              <input name="txtcuecajahoper" type="text" id="txtcuecajahoper"  size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);" disabled="disabled">
            Tiene Caja de Ahorro
            <input name="chkcajahoper" type="checkbox" class="sin-borde" id="chkcajahoper" value="1" onClick="javascript: ue_chequear_caja_ahorro();" disabled="disabled">
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Porcentaje Caja de Ahorro </div></td>
          <td colspan="2"><div align="left">
              <input name="txtporcajahoper" type="text" id="txtporcajahoper"  size="8" maxlength="5" style="text-align:left" onKeyPress="return validarreal2(event,this);" disabled="disabled">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Ley de Pol&iacute;tica </div></td>
          <td colspan="2"><div align="left">
              <input name="txtcuelphper" type="text" id="txtcuelphper" size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);" disabled="disabled">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Fideicomiso </div></td>
          <td colspan="2"><div align="left">
              <input name="txtcuefidper" type="text" id="txtcuefidper"  size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);" disabled="disabled">
          </div></td>
        </tr>
      
        <tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="2"><div align="left">
        
          </div></td>
        </tr>
        
      </table>
          <p>&nbsp;</p>
          </p></div>
        <div>
          <p>
          
          <table width="677" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4">            </td>
        </tr>
       <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definici&oacute;n de Estudio Realizado </td>
        </tr>
		<tr>
        <td  colspan="3"><div align="right" class="sin-borde2">C&oacute;digo Personal</div></td>
        <td align="right"><div align="left">
          <input name="txtcodper8" type="text" id="txtcodper8" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
        <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">N&ordm; Expediente</div></td>
        <td align="right"><div align="left">
          <input name="txtnumexpper8" type="text" id="txtnumexpper8" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
	  <tr>
        <td  colspan="3" align="right"><div align="right" class="sin-borde2">Tipo Personal</div></td>
        <td  align="right"><div align="left"><input name="txtdestippersss8" type="text" id="txtdestippersss8" size="28" class="sin-borde" readonly></div></td>
      </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcodestrea" type="text" id="txtcodestrea"  size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tipo </div></td>
          <td colspan="3">
            <div align="left">
              <select name="cmbtipestrea" id="cmbtipestrea">
                <option value="null" selected>--Seleccione--</option>
                <option value="0" >Primaria</option>
                <option value="1" >Ciclo B&aacute;sico</option>
                <option value="2" >Ciclo Diversificado</option>
                <option value="3" >Pregrado</option>
                <option value="4" >Especializaci&oacute;n</option>
                <option value="5" >Maestr&iacute;a</option>
                <option value="6" >Post Grado</option>
                <option value="7" >Doctorado</option>
                <option value="8" >Taller</option>
                <option value="9" >Curso</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Instituto</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtinsestrea" type="text" id="txtinsestrea" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="60">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td colspan="3"><input name="txtdesestrea" type="text" id="txtdesestrea" onKeyUp="javascript: ue_validarcomillas(this);" size="63" maxlength="100"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo Obtenido </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txttitestrea" type="text" id="txttitestrea" onKeyUp="javascript: ue_validarcomillas(this);" size="63" maxlength="60">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Calificaci&oacute;n</div></td>
          <td width="136">
            <div align="left">
              <input name="txtcalestrea" type="text" id="txtcalestrea" size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
              </div></td>
			  <td width="141"><div align="right">Escala</div></td>
              <td width="251" >
                <div align="left">
                  <input name="txtescval" type="text" id="txtescval" size="25" maxlength="20" onKeyUp="javascript: ue_validarcomillas(this);">
              </div>                    </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Aprobado</div></td>
          <td colspan="3">
            <select name="cmbaprestrea" id="cmbaprestrea">
              <option value="0" >No</option>
              <option value="1" >Si</option>
            </select>          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">&Uacute;ltimo A&ntilde;o Aprobado </div></td>
          <td><input name="txtanoaprestrea" type="text" id="txtanoaprestrea"size="6" maxlength="1" onKeyUp="javascript: ue_validarnumero(this);"></td>
          <td><div align="right">Horas</div></td>
          <td><label>
            <input name="txthorestrea" type="text" id="txthorestrea" size="6" maxlength="3"  onKeyUp="javascript: ue_validarnumero(this);">
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Inicio </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtfeciniact" type="text" id="txtfeciniact" size="15" maxlength="10" readonly> <input name="reset" type="reset" onClick="return showCalendar('txtfeciniact', '%d/%m/%Y');" value=" ... " />
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Fecha de Finalizaci&oacute;n </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtfecfinact" type="text" id="txtfecfinact"  size="15" maxlength="10" readonly>
              <input name="reset2" type="reset" onClick="return showCalendar('txtfecfinact', '%d/%m/%Y');" value=" ... " />
            </div></td></tr>
        <tr>
          <td height="22"><div align="right">Fecha Grado </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtfecgraestrea" type="text" id="txtfecgraestrea" size="15" maxlength="10" readonly>
              <input name="reset3" type="reset" onClick="return showCalendar('txtfecgraestrea', '%d/%m/%Y');" value=" ... " />
            </div></td></tr>
			  <tr>
          <td height="21" colspan="4">		 </td>
          </tr>
		 <tr>
        <td height="22"><div align="left"><a href="javascript: ue_limpiar_estudios();ue_nuevo_codestudio();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nuevo Estudio</a></div></td>

        <td width="136">
			<a href="javascript: ue_guardar_estudios();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Estudio</a></td>
		  <td width="141">
			<a href="javascript: ue_eliminar_estudio();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar  Estudio</a></td>
          <td width="251"><span class="toolbar"><a href="javascript: ue_buscar_estudios();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Estudios</a></span></td>
      </tr>
      </table>
          <p>&nbsp;</p>
          </p></div>
        <div><p><table width="677" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4" class="sin-borde2"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definici&oacute;n de Trabajo Anterior </td>
        </tr>
		<tr>
        <td  colspan="3"><div align="right" class="sin-borde2">C&oacute;digo Personal</div></td>
        <td align="right"><div align="left">
          <input name="txtcodper3" type="text" id="txtcodper3" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
         <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">N&ordm; Expediente</div></td>
        <td align="right"><div align="left">
          <input name="txtnumexpper3" type="text" id="txtnumexpper3" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
	  <tr>
        <td  colspan="3" align="right"><div align="right" class="sin-borde2">Tipo Personal</div></td>
        <td  align="right"><div align="left"><input name="txtdestippersss3" type="text" id="txtdestippersss3" size="28" class="sin-borde" readonly></div></td>
      </tr>
        <tr>
          <td width="151" height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodtraant" type="text" id="txtcodtraant"  size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre Empresa </div></td>
          <td colspan="3"><div align="left">
            <input name="txtemptraant" type="text" id="txtemptraant" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="100">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">&Uacute;timo Cargo </div></td>
          <td colspan="3"><div align="left">
            <input name="txtultcartraant" type="text" id="txtultcartraant" onKeyUp="javascript: ue_validarcomillas(this);" size="63" maxlength="100">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">&Uacute;ltimo Sueldo </div></td>
          <td colspan="3"><div align="left">
            <input name="txtultsuetraant" type="text" id="txtultsuetraant" size="23" maxlength="20" style="text-align:left" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Ingreso </div></td>
          <td colspan="3"><div align="left">
            <input name="txtfecingtraant" type="text" id="txtfecingtraant" size="15" maxlength="10" readonly>
            <input name="reset4" type="reset" onBlur="calcular_tiempo_trabajado();" onClick="return showCalendar('txtfecingtraant', '%d/%m/%Y');" value=" ... " />
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Egreso </div></td>
          <td colspan="3"><div align="left">
            <input name="txtfecrettraant" type="text" id="txtfecrettraant"    size="15" maxlength="10" readonly>
            <input name="reset5" type="reset" onBlur="calcular_tiempo_trabajado();" onClick="javascript: return showCalendar('txtfecrettraant','%d/%m/%Y'); " value=" ... " />
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Empresa </div></td>
          <td colspan="3"><select name="cmbemppubtraant" id="cmbemppubtraant">
            <option value="null" selected>--Seleccione--</option>
            <option value="1">P&uacute;blica</option>
            <option value="0">Privada</option>
          </select>          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Dedicaci&oacute;n</div></td>
          <td colspan="3"><input name="txtcodded" type="text" id="txtcodded"  size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscardedicacion();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesded" type="text" class="sin-borde" id="txtdesded"  size="70" maxlength="100" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">A&ntilde;os Laborados </div></td>
          <td colspan="3"><input name="txtanolab" type="text" id="txtanolab"  size="6" maxlength="3" style="text-align:left"  readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Meses Laborados </div></td>
          <td colspan="3"><input name="txtmeslab" type="text" id="txtmeslab"  size="6" maxlength="3" style="text-align:left"  readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">D&iacute;as Laborados </div></td>
          <td colspan="3"><input name="txtdialab" type="text" id="txtdialab"  size="6" maxlength="3" style="text-align:left"  readonly></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
		
		<tr>
        <td height="22"><div align="left"><a href="javascript: ue_limpiar_trabajo();ue_nuevo_trabajo();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nueva Experiencia</a></div></td>

        <td width="152">
			<a href="javascript: ue_guardar_trabajos();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Experiencia</a></td>
		  <td width="167">
			<a href="javascript: ue_eliminar_trabajo();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar  Experiencia </a></td>
          <td width="199"><span class="toolbar"><a href="javascript: ue_buscar_trabajos();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Experiencia</a></span></td>

      </tr>
		
		

       
      </table>
          <p>&nbsp;</p>
          </p></div>
        <div><p><table width="677" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Registro de Familiar</td>
        </tr>
		<tr>
        <td  colspan="3"><div align="right" class="sin-borde2">C&oacute;digo Personal</div></td>
        <td align="right"><div align="left">
          <input name="txtcodper4" type="text" id="txtcodper4" size="28" class="sin-borde"readonly>
        </div></td>
      </tr>
         <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">N&ordm; Expediente</div></td>
        <td align="right"><div align="left">
          <input name="txtnumexpper4" type="text" id="txtnumexpper4" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
	  <tr>
        <td  colspan="3" align="right"><div align="right" class="sin-borde2">Tipo Personal</div></td>
        <td  align="right"><div align="left"><input name="txtdestippersss4" type="text" id="txtdestippersss4" size="28" class="sin-borde" readonly></div></td>
      </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcedfam" type="text" id="txtcedfam"  size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&eacute;dula</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcedula" type="text" id="txtcedula"  size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Nombre</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnomfam" type="text" id="txtnomfam" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="60">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido</div></td>
          <td colspan="3"><div align="left">
            <input name="txtapefam" type="text" id="txtapefam" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="60">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">G&eacute;nero</div></td>
          <td colspan="3"><div align="left">
            <select name="cmbsexfam" id="cmbsexfam">
              <option value="null" selected>--Seleccione--</option>
              <option value="F" >Femenino</option>
              <option value="M" >Masculino</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha Nacimiento </div></td>
          <td colspan="3"><div align="left">
            <input name="txtfecnacperfam" type="text" id="txtfecnacperfam"  size="15" maxlength="10" readonly>
            <input name="reset42" type="reset" onClick="return showCalendar('txtfecnacperfam', '%d/%m/%Y');" value=" ... " />
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nexo</div></td>
          <td colspan="3"><div align="left">
            <select name="cmbnexfam" id="cmbnexfam" >
              <option value="null" selected>--Seleccione--</option>
              <option value="C" >Conyuge</option>
              <option value="H" >Hijo</option>
              <option value="P" >Progenitor</option>
              <option value="E" >Hermano</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estudia</div></td>
          <td colspan="3">
            <div align="left">
              <input name="chkestfam" type="checkbox" class="sin-borde" id="chkestfam" value="1" >
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">HC</div></td>
          <td colspan="3"><div align="left">
            <input name="chkhcfam" type="checkbox" class="sin-borde" id="chkhcfam" value="1"   >
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">HCM (Poliza de Maternidad) </div></td>
          <td colspan="3"><div align="left">
            <input name="chkhcmfam" type="checkbox" class="sin-borde" id="chkhcmfam" value="1" onchange="javascript:ue_hcmfam();"   >
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Hijo Especial</div></td>
          <td><div align="left">
            <input name="chkhijesp" type="checkbox" class="sin-borde" id="chkhijesp" value="1" onchange="javascript:ue_checkhijo('1');"  >
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Bono Juguete</div></td>
          <td><div align="left">
            <input name="chkbonjug" type="checkbox" class="sin-borde" id="chkbonjug" value="1" onchange="javascript:ue_checkhijo('2');"  >
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
		<tr>
        <td height="22"><div align="center"><a href="javascript:ue_limpiar_familia();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nuevo Familiar</a></div></td>

        <td width="134">
			<a href="javascript: ue_guardar_familiares();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Familiar</a></td>
		  <td width="142">
			<a href="javascript: ue_eliminar_familiar();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar Familiar</a></td>
          <td width="194"><span class="toolbar"><a href="javascript: ue_buscar_familiares();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Familiares</a></span></td>
         
      </tr>
      </table>
          <p>&nbsp;</p>
		  
		  
		   </p></div>
        <div><p><table width="677" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Registro de Beneficiario </td>
        </tr>
		<tr>
        <td  colspan="3"><div align="right" class="sin-borde2">C&oacute;digo Personal</div></td>
        <td align="right"><div align="left">
          <input name="txtcodper5" type="text" id="txtcodper5" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
         <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">N&ordm; Expediente</div></td>
        <td align="right"><div align="left">
          <input name="txtnumexpper5" type="text" id="txtnumexpper5" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
	  <tr>
        <td  colspan="3" align="right"><div align="right" class="sin-borde2">Tipo Personal</div></td>
        <td  align="right"><div align="left"><input name="txtdestippersss5" type="text" id="txtdestippersss5" size="28" class="sin-borde" readonly></div></td>
      </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodben" type="text" id="txtcodben"  size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);" readonly>
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">C&eacute;dula</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcedben" type="text" id="txtcedben"  size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnomben" type="text" id="txtnomben" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="60">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido</div></td>
          <td colspan="3"><div align="left">
            <input name="txtapeben" type="text" id="txtapeben" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="60">
          </div></td>
        </tr>
		
		<tr>
          <td height="22"><div align="right">Nacionalidad</div></td>
          <td><div align="left">
            <select name="cmbnacben" id="cmbnacben">
              <option value="null" selected>--Seleccione Uno--</option>
              <option value="V" >Venezolano</option>
              <option value="E" >Extranjero</option>
            </select>
          </div></td>
        </tr>
		
		<tr>
          <td height="22"><div align="right">Direcci&oacute;n</div></td>
          <td colspan="3"><div align="left">
            <input name="txtdirben" type="text" id="txtdirben" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="60">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tel&eacute;fono</div></td>
          <td colspan="3"><div align="left">
            <input name="txttelben" type="text" id="txttelben" onKeyUp="javascript:ue_validarnumero(this);"  size="20" maxlength="60">
          </div></td>
        </tr>
		
		<tr>
           <td height="22"><div align="right">Parentesco </div></td>
           <td><div align="left">
            <select name="cmbnexben" id="cmbnexben">
              <option value="-" selected>--Sin Parentesco--</option>
              <option value="C" >Conyuge</option>
              <option value="H" >Hijo</option>
              <option value="P">Progenitor</option>
              <option value="E">Hermano</option>
            </select>
          </div></td>
        </tr>
		
      <tr>
          <td height="22"><div align="right">Tipo de Beneficiario </div></td>
          <td><div align="left">
            <select name="cmbtipben" id="cmbtipben">
              <option value="null" selected>--Seleccione Uno--</option>
              <option value="0">Pension Sobrevivientes</option>
              <option value="1">Pension Judicial</option>
			  <option value="2">Pension Alimenticia</option>
            </select>
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Expediente</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnumexpben" type="text" id="txtnumexpben" onKeyUp="javascript: ue_validarcomillas(this);"  size="40" maxlength="40">
          </div></td>
        </tr>		
        <tr>
          <td height="22"><div align="right">Porcentaje que le corresponde</div></td>
          <td colspan="3"><div align="left">
            <input name="txtporpagben" type="text" id="txtporpagben" onKeyPress="return(ue_formatonumero(this,'.',',',event))"  onBlur="javascript: ue_limpiar('0');" style="text-align:right"  size="20" >
          </div></td>
        </tr>
		
		<tr>
          <td height="22"><div align="right">Monto que le corresponde</div></td>
          <td colspan="3"><div align="left">
            <input name="txtmonpagben" type="text" id="txtmonpagben" onKeyPress="return(ue_formatonumero(this,'.',',',event))"  onBlur="javascript: ue_limpiar('1');" style="text-align:right"  size="20" >
          </div></td>
        </tr>
		
		<tr>
          <td height="22"><div align="right">Forma de Pago </div></td>
          <td><select name="cmbforpagben" id="cmbforpagben">
            <option value="null" selected>--Seleccione Uno--</option>
            <option value="0"  >Cheque</option>
            <option value="1"  >Deposito en Cuenta</option>
                    </select></td>
        </tr>
		 <tr>
          <td height="22"><div align="right">Nombre del cheque</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnomcheben" type="text" id="txtnomcheben" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="60">
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">C&eacute;dula del Autorizado</div></td>
          <td><div align="left">
            <input name="txtcedaut" type="text" id="txtcedaut"  size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
		
		 <tr>
          <td height="22"><div align="right">Banco</div></td>
          <td colspan="4"><div align="left">
            <input name="txtcodban" type="text" id="txtcodban"  size="7" maxlength="4" readonly>
            <a href="javascript: ue_buscarbanco();"><img id="banco" src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomban" type="text" class="sin-borde" id="txtnomban"  size="50" readonly>
</div></td>
        </tr>
		
		 <tr>
          <td height="22"><div align="right">Cuenta de Banco</div></td>
          <td colspan="3"><div align="left">
            <input name="txtctaban" type="text" id="txtctaban" onKeyUp="javascript: ue_validarnumero(this);"  size="30" maxlength="60">
          </div></td>
        </tr>
		
		 <tr>
          <td height="22"><div align="right">Tipo de Cuenta</div></td>
          <td><select name="cmbtipcueben" id="cmbtipcueben">
            <option value="" selected>--Seleccione Una--</option>
            <option value="A" >Ahorro</option>
            <option value="C" >Corriente</option>
            <option value="L" >Activos L&iacute;quidos</option>
          </select></td>
        </tr>
       
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
		<tr>
         <td height="22"><div align="left"><a href="javascript: ue_limpiar_beneficiario();ue_nuevo_beneficiario();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nuevo Beneficiario</a></div></td>
        <td width="172">
			<a href="javascript: ue_guardar_beneficiarios();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Beneficiario </a></td>
		  <td width="170">
			<a href="javascript: ue_eliminar_beneficiario();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar Beneficiario </a></td>
          <td width="174"><span class="toolbar"><a href="javascript: ue_buscar_beneficiarios();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Beneficiarios </a></span></td>
         
      </tr>
      </table>
          <p>&nbsp;</p>
		  
		  
          </p></div>
        
        <div><p><table width="677" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td colspan="3"><div align="center">
            
        </div></td>
      </tr>
      <tr class="titulo-ventana">
        <td height="20" colspan="4" class="titulo-ventana">Registro de Permiso</td>
      </tr>
	  <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">C&oacute;digo Personal</div></td>
        <td align="right"><div align="left">
          <input name="txtcodper2" type="text" id="txtcodper2" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">N&ordm; Expediente</div></td>
        <td align="right"><div align="left">
          <input name="txtnumexpper2" type="text" id="txtnumexpper2" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
	  <tr>
        <td  colspan="3" align="right"><div align="right" class="sin-borde2">Tipo Personal</div></td>
        <td  align="right"><div align="left"><input name="txtdestippersss2" type="text" id="txtdestippersss2" size="28" class="sin-borde" readonly></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero</div></td>
        <td colspan="2"><div align="left">
            <input name="txtnumper" type="text" id="txtnumper" onKeyUp="javascript: ue_validarnumero(this);"  size="5" maxlength="2" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha Inicio </div></td>
        <td colspan="2"><div align="left">
            <input name="txtfeciniper" type="text" id="txtfeciniper" size="15" maxlength="10" readonly>
            <input name="reset422" type="reset" onClick="return showCalendar('txtfeciniper', '%d/%m/%Y');" value=" ... " />
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha Fin</div></td>
        <td colspan="2"><div align="left">
            <input name="txtfecfinper" type="text" id="txtfecfinper"  size="15" maxlength="10" readonly>
            <input name="reset423" type="reset" onClick="return showCalendar('txtfecfinper', '%d/%m/%Y');" value=" ... " />
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero de D&iacute;as </div></td>
        <td colspan="2"><div align="left">
            <input name="txtnumdiaper" type="text" id="txtnumdiaper"  size="6" maxlength="4" style="text-align:left" onKeyUp="javascript: ue_validarnumero(this);">
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">N&uacute;mero de Horas</div></td>
        <td colspan="2"><div align="left">
            <input name="txttothorper" type="text" id="txttothorper" onKeyPress="return validarreal2(event,this);"  size="6" maxlength="5" >
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descontable de Vacaciones</div></td>
        <td colspan="2"><div align="left">
            <input name="chkafevacper" type="checkbox" class="sin-borde" id="chkafevacper" value="1" >
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Permiso Remunerado </div></td>
        <td colspan="2"><label>
          <input name="chkremper" type="checkbox" class="sin-borde" id="chkremper" value="1" >
        </label></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">Permiso No Remunerado </div></td>
        <td colspan="2"><label>
          <input name="chkremper2" type="checkbox" class="sin-borde" id="chkremper2"  value="1" >
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo</div></td>
        <td colspan="2"><div align="left">
            <select name="cmbtipper" id="cmbtipper">
              <option value="null" selected>--Seleccione--</option>
              <option value="1">Estudio</option>
              <option value="2">M&eacute;dico</option>  
			  <option value="5">Reposo</option>			  
              <option value="3">Tr&aacute;mites</option>
              <option value="4">Otro</option>			  
            </select>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Observaci&oacute;n</div></td>
        <td colspan="2"><div align="left">
            <textarea name="txtobsper1" cols="55" rows="4" id="txtobsper1"  onKeyUp="javascript: ue_validarcomillas(this);"></textarea>
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
	  <tr>
        <td height="22"><div align="left"><a href="javascript: ue_limpiar_permiso(); ue_nuevo_permiso();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nuevo Permiso</a></div></td>
        <td width="173">
			<a href="javascript: ue_guardar_permiso();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Permiso</a></td>
		  <td width="172">
			<a href="javascript: ue_eliminar_permiso();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar  Permiso </a></td>
          <td width="183"><span class="toolbar"><a href="javascript: ue_buscar_permisos();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Permisos </a></span></td>
          
      </tr>
	  
      
    </table>
          <p>&nbsp;</p>
          </p></div>

    <div><p><table width="677" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td colspan="4"><div align="center">
            
        </div></td>
      </tr>
      <tr class="titulo-ventana">
        <td height="20" colspan="5" class="titulo-ventana">Deducciones por Personal</td>
      </tr>
	  <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">C&oacute;digo Personal</div></td>
        <td align="right"><div align="left">
          <input name="txtcodper6" type="text" id="txtcodper6" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
       <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">N&ordm; Expediente</div></td>
        <td align="right"><div align="left">
          <input name="txtnumexpper6" type="text" id="txtnumexpper6" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
	  <tr>
        <td  colspan="3" align="right"><div align="right" class="sin-borde2">Tipo Personal</div></td>
        <td  align="right"><div align="left"><input name="txtdestippersss6" type="text" id="txtdestippersss6" size="28" class="sin-borde" readonly></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&oacute;digo de Deducci&oacute;n</div></td>
       <td width="169" height="28" valign="middle"><input name="txtcodtipded" type="text" id="txtcodtipded"   size="16" maxlength="15" readonly style="text-align:center" >
      <a href="javascript:catalogo_tipo_deduccion();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo de Tipo Deduccion" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
            <td colspan="2"> <input name="txtdentipded" onKeyUp="ue_validarcomillas(this);" type="text" class="sin-borde" id="txtdentipded"  size="40" maxlength="80" readonly>             </td>
      </tr>
	  
	   <tr>
        <td height="22"><div align="right">Tipo de Deducci&oacute;n</div></td>
       <td width="169" height="28" valign="middle"><input name="txtcoddettipded" type="text" id="txtcoddettipded"   size="16" maxlength="15" readonly style="text-align:center" >
      <a href="javascript:catalogo_detalle_deduccion();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo de Tipo Deduccion" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
            <td colspan="2">&nbsp;</td>
      </tr>
	  
	    <tr>
        <td height="22"><div align="right">Monto a Deducir </div></td>
        <td colspan="2"><div align="left">
           <input name="txtmontod" type="text" id="txtmontod" size="16" maxlength="10" style="text-align:right" readonly>
           </div></td>
      </tr> 
      
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3"></td>
      </tr>
      <tr>
        <td height="22"><div align="left"><a href="javascript: ue_limpiar_deduccion();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nueva Deducci&oacute;n</a></div></td>

        <td width="169">
			<a href="javascript: ue_guardar_deduccion();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Deducci&oacute;n </a></td>
		  <td width="158">
			<a href="javascript: ue_eliminar_deduccion();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar  Deducci&oacute;n </a></td>
          <td width="168"><span class="toolbar"><a href="javascript: ue_buscar_deducciones();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Deducciones</a></span></td>
          <td width="10">&nbsp;</td>
      </tr>
    
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr class="titulo-ventana">
        <td height="20" colspan="5" class="titulo-ventana">Deducciones por Familiar</td>
      </tr>
      <tr>
        <td width="162" height="22"><div align="right"></div></td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">C&eacute;dula del Familiar</div></td>
       <td width="169" height="28" valign="middle"><input name="txtcedfam1" type="text" id="txtcedfam1"   size="16" maxlength="15" readonly style="text-align:center" >
         <a href="javascript:catalogo_familiar();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo de Tipo Deduccion" name="buscartip" width="15" height="15" border="0" id="buscartip">
         <input name="hidnexfam" type="hidden" id="hidnexfam"   size="16" maxlength="15" readonly >
		 <input name="hidsexfam" type="hidden" id="hidsexfam"   size="16" maxlength="15" readonly >
         </a></td>
            <td colspan="2"> <input name="txtnomfam1"  type="text" class="sin-borde" id="txtnomfam1"  size="40" maxlength="80" readonly>             </td>
      </tr>
	  
	  
      <tr>
        <td height="22"><div align="right">C&oacute;digo de Deducci&oacute;n</div></td>
       <td width="169" height="28" valign="middle"><input name="txtcodtipded1" type="text" id="txtcodtipded1"   size="16" maxlength="15" readonly style="text-align:center" >
      <a href="javascript:catalogo_deducciones_personal();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo de Tipo Deduccion" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
            <td colspan="2"> <input name="txtdentipded1"  type="text" class="sin-borde" id="txtdentipded1"  size="40" maxlength="80" readonly>             </td>
      </tr>
	  
	  
	   <tr>
        <td height="22"><div align="right">Tipo de Deducci&oacute;n</div></td>
       <td width="169" height="28" valign="middle"><input name="txtcoddettipdedfam" type="text" id="txtcoddettipdedfam"   size="16" maxlength="15" readonly style="text-align:center" >
      <a href="javascript:catalogo_detalle_deduccion_fam();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo de Tipo Deduccion" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
            <td colspan="2">&nbsp;</td>
      </tr>
	  
      <tr>
        <td height="22"><div align="right">Monto a Deducir </div></td>
        <td colspan="2"><div align="left">
           <input name="txtmontodfam" type="text" id="txtmontodfam" size="16" maxlength="10" style="text-align:right" readonly>
           </div></td>
      </tr> 
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3"></td>
      </tr>
      <tr>
       
		<td height="22"><div align="left"><a href="javascript:ue_limpiar_deduccion_familiar();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nueva Deducci&oacute;n Familiar</a></div>		</td>
        <td >
			<a href="javascript: ue_guardar_deduccion_familiar();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Deducci&oacute;n Familiar  </a></td>
		  <td width="158">
			<a href="javascript: ue_eliminar_deduccion_familiar();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar  Deducci&oacute;n Familiar </a></td>
          <td colspan="2"><span class="toolbar"><a href="javascript: ue_buscar_deducciones_familiar();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Deducci&oacute;n Familiar</a></span></td>
      </tr>
    </table>
          <p>&nbsp;</p>
          </p></div>
	
	
	<div><p><table width="677" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td colspan="3"><div align="center">
            
        </div></td>
      </tr>
      <tr class="titulo-ventana">
        <td height="20" colspan="4" class="titulo-ventana">Registro de Premios y Felicitaciones </td>
      </tr>
	  <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">C&oacute;digo Personal</div></td>
        <td align="right"><div align="left">
          <input name="txtcodper7" type="text" id="txtcodper7" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
       <tr>
        <td  colspan="3"><div align="right" class="sin-borde2">N&ordm; Expediente</div></td>
        <td align="right"><div align="left">
          <input name="txtnumexpper7" type="text" id="txtnumexpper7" size="28" class="sin-borde" readonly>
        </div></td>
      </tr>
	  <tr>
        <td  colspan="3" align="right"><div align="right" class="sin-borde2">Tipo Personal</div></td>
        <td  align="right"><div align="left"><input name="txtdestippersss7" type="text" id="txtdestippersss7" size="28" class="sin-borde" readonly></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero</div></td>
        <td colspan="3"><div align="left">
            <input name="txtnumprem" type="text" id="txtnumprem" onKeyUp="javascript: ue_validarnumero(this);"  size="15" maxlength="10" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha</div></td>
        <td colspan="3"><div align="left">
            <input name="txtfecprem" type="text" id="txtfecprem" size="15" maxlength="10" readonly>
            <input name="reset422" type="reset" onClick="return showCalendar('txtfecprem', '%d/%m/%Y');" value=" ... " />
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="3"><div align="left">
          <input name="txtdenprem" type="text" id="txtdenprem" onKeyUp="javascript: ue_validarcomillas(this);"  size="60" maxlength="254" >
        </div></td>
      </tr>
     
      <tr>
        <td height="22"><div align="right">Motivo</div></td>
        <td colspan="3"><div align="left">
          <textarea name="txtmotivoprem" cols="58" rows="4" id="txtmotivoprem"  onKeyUp="javascript: ue_validarcomillas(this);"></textarea>
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
	  <tr>
        <td height="22"><div align="left"><a href="javascript:ue_limpiar_premio(); ue_nuevo_premio();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nueva Premici&oacute;n</a></div>			
		</td>

        <td width="167">
			<a href="javascript: ue_guardar_premio();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Premiaci&oacute;n</a></td>
		  <td width="180">
			<a href="javascript: ue_eliminar_premio();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar  Premiaci&oacute;n</a></td>
          <td width="188"><span class="toolbar"><a href="javascript: ue_buscar_premio();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Premiaci&oacute;n</a></span></td>
      </tr>
	  
      
    </table>
          <p>&nbsp;</p>
          </p></div>
        
     
    </tr>
		  <tr>
            <td width="823"><p>&nbsp;</p></td>
            
		</tr>
<p>&nbsp;</p>
  </table>
	      <p>&nbsp;</p>
		  <p>
		    <script>
(function() {
    var tabView = new YAHOO.widget.TabView('demo');
})();

            </script>

</td></tr>
</table>

   
</form>      
<p>&nbsp;</p>
</body> 

</html>