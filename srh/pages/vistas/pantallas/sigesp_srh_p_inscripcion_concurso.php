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
	require_once("../../../class_folder/utilidades/class_srh.php");
	$io_class_srh=new class_srh('../../../../');	
	$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_inscripcion_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_class_srh->uf_select_config("SRH","REPORTE","CONSTANCIA_INSCRIPCION_CONCURSO","sigesp_srh_rpp_inscripcion_concurso.php","C");
	//--------------------------------------------------------------------------------------------------------------
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Inscripci&oacute;n de Personas a Concursos</title>

<link rel="stylesheet" type="text/css" href="../../resources/css/ext-all.css" />
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_inscripcion_concurso.js"></script>
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
	color: #6699CC;
	font-size: 12px;
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
	<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
	
	<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_imprimir('<?php print $ls_reporte ?>');"><img src="../../../../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
	
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    
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
<input name="hidguardar_cur" type="hidden" id="hidguardar_cur" value="insertar">
<input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
 <input name="hidcontrol" type="hidden" id="hidcontrol" value="0">
<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="">
<input name="hidcontrol3" type="hidden" id="hidcontrol3" value="">
<input name="txttipper" type="hidden" id="txttipper" value="">
<table width="823" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
<td width="823">

  <div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Datos B&aacute;sicos</em></a></li>
        <li class="desabled"><a href="#tab2"><em>Formaci&oacute;n Acad&eacute;mica / Profesional</em></a></li>
        <li><a href="#tab3"><em>Educaci&oacute;n Informal</em></a></li>
        <li><a href="#tab4"><em>Experiencia Laboral</em></a></li>       
        <li><a href="#tab5"><em>Carga Familiar</em></a></li>
		<li><a href="#tab6"><em>Requisitos</em></a></li>
	</ul>            
    <div class="yui-content">
        <div><p><table width="701" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
		
		<tr class="titulo-nuevo">
          <td height="22" colspan="6">Datos del Concurso</td>
        </tr>
		<tr>
          <td height="22" align="left"><div align="right"></div></td>
          <td height="22"></td>
          <td height="22"><div align="right">Fecha</div></td>
          <td height="22" colspan="2"><input name="txtfecreg" type="text" id="txtfecreg" value="<?PHP print date("d/m/Y");?>" maxlength="15" style="text-align:center"  readonly> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecreg', '%d/%m/%Y');" value=" ... " />          </td>
        </tr>
		
        <tr>
        <tr class="formato-blanco">
    <td height="22"><div align="right">C&oacute;digo</div></td>
    <td height="22" colspan="3"><input name="txtcodcon" type="text" id="txtcodcon" size="11" maxlength="10"   readonly style="text-align:center ">
        <input name="hidstatus" type="hidden" id="hidstatus"> <a href="javascript:catalogo_concurso();"> <img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Concurso" name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Registro de Concurso</a>  </td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Descrpci&oacute;n</div></td>
    <td height="22" colspan="4"><input name="txtdescon" type="text" id="txtdescon"  onKeyUp="ue_validarcomillas(this);" size="95" maxlength="254"  readonly></td>
  </tr>
  
   <tr class="formato-blanco">
    <td height="22"><div align="right">Cargo</div></td>
    <td height="22"  colspan="4"><input name="txtcodcar" type="text" id="txtcodcar"  size="16" maxlength="10"  style="text-align:center"  readonly>
      <input name="txtdescar" type="text" class="sin-borde" id="txtdescar"  size="60" maxlength="80"  readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Cantidad Cargos</div> </td>
    <td height="22" valign="middle"><input name="txtcantcar" type="text" id="txtcantcar"  size="6" maxlength="5" style="text-align:center"  readonly>      </td>
            
 
 <td height="22"><div align="right">Tipo Concurso </div></td>
  <td height="22" valign="middle"><input name="txtcodtipconcur" type="text" id="txtcodtipconcur"  size="16" maxlength="20"  style="text-align:center"  readonly></td>
  </tr>
  <tr class="formato-blanco"> 
 <td height="22"><div align="right">Fecha Apertura</div></td>
  <td height="22" valign="middle"><input name="txtfechaaper" type="text" id="txtfechaaper"  size="16"   style="text-align:center" readonly > </td>
         
 
 <td height="22"><div align="right">Fecha Cierre</div></td>
  <td height="22" valign="middle"> <input name="txtfechacie" type="text" id="txtfechacie" size="16"    style="text-align:center"  readonly >  </td>           
  </tr>
  	<tr class="titulo-nuevo">
          <td height="22" colspan="6">Datos Personales</td>
        </tr>
        <tr>
       <tr>
          <td width="95" height="22" align="left"><div align="right"></div></td>
          <td width="165" height="22"> </td>
          <td width="95" height="22"></td>
          <td width="338" height="22" colspan="2"><div id="personal" style="display:none"><a href="javascript:catalogo_personal();"> <img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Registro de Personal </a></div></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nombres</div></td>
          <td height="22"><input name="txtnomper" type="text" id="txtnomper" onKeyUp="ue_validarcomillas(this);"  maxlength="30" style="text-align:justify" ></td>
          <td height="22"><div align="right">Apellidos</div></td>
          <td height="22" colspan="2"><input name="txtapeper" type="text" id="txtapeper" onKeyUp="ue_validarcomillas(this);"  maxlength="30" style="text-align:justify" ></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">C&eacute;dula/C&oacute;digo</div></td>
          <td height="22"><input name="txtcodper" type="text" id="txtcodper"  maxlength="10" style="text-align:justify"    onKeyUp="javascript: ue_validarnumero(this);"  onBlur="javascript: ue_chequear_cedula();"></td>
          <td height="22"><div align="right">Fecha Nac.</div></td>
          <td height="22" colspan="2"><input name="txtfecnacper" type="text" id="txtfecnacper" maxlength="15" style="text-align:justify"  readonly> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecnacper', '%d/%m/%Y');" value=" ... " />          </td>
        </tr>
		<tr>
		     <td height="22" align="left"><div align="right">Nacionalidad</div></td>
         <td height="22" bordercolor="0"><select name="cmbnacper" id="cmbnacper">
              <option value="null">--Seleccione--</option>
              <option value="V" >Venezolano</option>
              <option value="E" >Extrajero</option>
            </select>          </td>
		
          <td height="22"><div align="right">Lugar Nacimiento </div></td>
		  
          <td height="22" colspan="2"><select name="cmbcodpainac"   id="cmbcodpainac" onChange="ue_cambiopais();">
              <option value="null" >--Seleccione un Pais--</option>
            </select>          </td> </tr>
			 <tr>
          <td height="22" align="left"></td>
          <td height="22"></td>
          <td height="22"></td>
          <td height="22" colspan="2"><select name="cmbcodestnac"   id="cmbcodestnac" onclick="valida_cmbcodestnac();" >
              <option value="null">--Seleccione un Estado--</option>
            </select>      <input name="hidcodestnac"  type="hidden" id="hidcodestnac"  value="">    </td>
        </tr>
		
        <tr>
          <td height="22" align="left"><div align="right">G&eacute;nero</div></td>
          <td height="22" bordercolor="0"><select name="cmbsexper" id="cmbsexper">
              <option value="null">--Seleccione--</option>
              <option value="F"  >Femenino</option>
              <option value="M"  >Masculino</option>
            </select>          </td>
          <td height="22"><div align="right">Estado Civil</div></td>
          <td height="22" colspan="2"><select name="cmbedocivper" id="cmbedocivper">
              <option value= "null">--Seleccione--</option>
              <option value="S"  > Soltero </option>
              <option value="C"  > Casado </option>
              <option value="V" > Viudo </option>
              <option value="D">Divorciado </option>
              <option value="O" >Concubino </option>
          </select></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Direcci&oacute;n </div></td>
          <td height="22" colspan="4"><input name="txtdirper" type="text" id="txtdirper" onKeyUp="ue_validarcomillas(this);" style="text-align:justify" size="95" maxlength="256"></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Telf. Hab.</div></td>
          <td height="22"><input name="txttelhabper" type="text" id="txttelhabper"  maxlength="15" style="text-align:justify" onKeyUp="javascript: ue_validarnumero(this);"></td>
          <td height="22"><div align="right">Telf. M&oacute;vil</div></td>
          <td height="22" colspan="2"><input name="txttelmovper" type="text" id="txttelmovper" style="text-align:justify" onKeyUp="javascript: ue_validarnumero(this);" ></td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">E-mail</div></td>
          <td height="22" colspan="2"><input name="txtcoreleper" type="text" id="txtcoreleper" onKeyUp="ue_validarcomillas(this);"  maxlength="100" size="40"  style="text-align:justify"></td>
		  </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Nivel Acd&eacute;mico</div></td>
          <td height="22" bordercolor="0"><select name="cmbnivacaper" id="cmbnivacaper">
              <option value="" selected>--Seleccione--</option>
              <option value="1">Primaria</option>
              <option value="2">Bachiller</option>
		      <option value="3">T&eacute;cnico Superior</option>
              <option value="4">Universitario</option>
			  <option value="5">Maestr&iacute;a</option>
              <option value="6">Postgrado</option>
			  <option value="7">Doctorado</option>
            </select>		</td>
		<td height="22"><div align="right">Profesi&oacute;n </div></td>
          <td width="338" height="22" colspan="4"><input name="txtcodpro" type="text" id="txtcodpro"  size="5" readonly>
          <a href="javascript:catalogo_profesion();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Profesion" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
          <input name="txtdespro" type="text" class="sin-borde" id="txtdespro"  size="45"  readonly></td>
		 </tr>
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
          
          <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4">            </td>
        </tr>
       <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Formaci&oacute;n Acad&eacute;mica / Profesional</td>
        </tr>
		
        <tr>
          <td width="141" height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcodestper" type="text" id="txtcodestper"  size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Nivel</div></td>
          <td colspan="3">
            <div align="left">
              <select name="cmbtipestper" id="cmbtipestper">
                <option value="null" selected>--Seleccione--</option>
                <option value="0" >Primaria</option>
                <option value="1" >Ciclo B&aacute;sico</option>
                <option value="2" >Ciclo Diversificado</option>
                <option value="3" >Pregrado</option>
                <option value="4" >Especializaci&oacute;n</option>
                <option value="5" >Maestr&iacute;a</option>
                <option value="6" >Post Grado</option>
                <option value="7" >Doctorado</option>               
              </select>
              </div></td></tr>
			   <tr>
          <td height="22"><div align="right">Carrera</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcar" type="text" id="txtcar" onKeyUp="javascript: ue_validarcomillas(this);" size="63" maxlength="254">
              </div></td></tr>        
          <tr>
        <tr>
          <td height="22"><div align="right">Instituto</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtinsestper" type="text" id="txtinsestper" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="254">
              </div></td></tr>        
         <tr>
          <td height="22"><div align="right">A&ntilde;o Finalizaci&oacute;n </div></td>
          <td><input name="txtanofin" type="text" id="txtanofin" size="10" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);"></td>
		  </tr>
		  <tr>
          <td><div align="right">A&ntilde;os Aprobados</div></td>
          <td><label>
            <input name="txtanoapr" type="text" id="txtanoapr" size="6" maxlength="2"  onKeyUp="javascript: ue_validarnumero(this);">
          </label></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Titulado</div></td>
          <td colspan="3">
            <div align="left">
              <input name="chktit" type="checkbox" class="sin-borde" id="chktit" value="1" >
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
          <td width="274"><span class="toolbar"><a href="javascript: ue_buscar_estudios();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Estudios</a></span></td>
      </tr>
      </table>
          <p>&nbsp;</p>
          </p></div>
		  
		  
		  <div>
          <p>
          
          <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4">            </td>
        </tr>
       <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Educaci&oacute;n Informal (Últimos Realizados)</td>
        </tr>
		
        <tr>
          <td width="141" height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcodcur" type="text" id="txtcodcur"  size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
              </div></td></tr>
        
			   <tr>
          <td height="22"><div align="right">Curso</div></td>
          <td colspan="3">
            <div align="left">
             <input name="txtdescur" type="text" id="txtdescur" onKeyUp="javascript: ue_validarcomillas(this);" size="80" maxlength="254">
              </div></td></tr>        
          <tr>
          <tr>
          <td height="22"><div align="right">Horas</div></td>
          <td colspan="3">
            <div align="left">
              <select name="cmbhorcur" id="cmbhorcur">
                <option value="null" selected>--Seleccione--</option>
                <option value="0" >M&aacute;s de 200 Horas</option>
                <option value="1" >Entre 151 - 200 Horas</option>
                <option value="2" >Entre 101 - 150 Horas</option>
                <option value="3" >Entre  51 - 100 Horas</option>
                <option value="4" >Entre  10 -  50 Horas</option>
                <option value="5" >Otro</option>
              </select>
            </div></td></tr>
			   <tr>
       
          <td height="21" colspan="4">		 </td>
          </tr>
		 <tr>
        <td height="22"><div align="left"><a href="javascript: ue_limpiar_cursos();ue_nuevo_codcurso();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nuevo Curso </a></div></td>

        <td width="136">
			<a href="javascript: ue_guardar_cursos();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Curso</a></td>
		  <td width="141">
			<a href="javascript: ue_eliminar_cursos();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar Curso</a></td>
          <td width="274"><span class="toolbar"><a href="javascript: ue_buscar_cursos();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Cursos </a></span></td>
      </tr>
      </table>
          <p>&nbsp;</p>
          </p></div>
		  
		  
        <div><p><table width="694" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4" class="sin-borde2"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Experiencia Laboral </td>
        </tr>
		
        <tr>
          <td width="151" height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodtraant" type="text" id="txtcodtraant"  size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Empresa o Instituci&oacute;n</div></td>
          <td colspan="3"><div align="left">
            <input name="txtemptraant" type="text" id="txtemptraant" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="100">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cargo Desempe&ntilde;ado</div></td>
          <td colspan="3"><div align="left">
            <input name="txtultcartraant" type="text" id="txtultcartraant" onKeyUp="javascript: ue_validarcomillas(this);" size="63" maxlength="100">
          </div></td>
        </tr>       
        <tr>
          <td height="22"><div align="right">Fecha de Ingreso </div></td>
          <td colspan="3"><div align="left">
            <input name="txtfecingtraant" type="text" id="txtfecingtraant" size="15" maxlength="10" readonly>
            <input name="reset4" type="reset"  onClick="return showCalendar('txtfecingtraant', '%d/%m/%Y');" value=" ... " />
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Egreso </div></td>
          <td colspan="3"><div align="left">
            <input name="txtfecrettraant" type="text" id="txtfecrettraant"    size="15" maxlength="10" readonly>
            <input name="reset5" type="reset" onClick="javascript: return showCalendar('txtfecrettraant','%d/%m/%Y'); " value=" ... " />
          </div></td>
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
          <td width="216"><span class="toolbar"><a href="javascript: ue_buscar_trabajos();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Experiencia</a></span></td>

      </tr>
		
		

       
      </table>
          <p>&nbsp;</p>
          </p></div>
        <div><p><table width="699" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Carga Familiar </td>
        </tr>		
	 
        <tr>
          <td width="155"  height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodfam" type="text" id="txtcodfam"  size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">C&eacute;dula</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcedfam" type="text" id="txtcedfam" onKeyUp="javascript: ue_validarnumero(this);"  size="15" maxlength="10">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnomfam" type="text" id="txtnomfam" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="254">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido</div></td>
          <td colspan="3"><div align="left">
            <input name="txtapefam" type="text" id="txtapefam" onKeyUp="javascript: ue_validarcomillas(this);"  size="63" maxlength="254">
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
          <td height="22"><div align="right">Fecha Nacimiento</div></td>
          <td colspan="3"><div align="left">
            <input name="txtfecnacperfam" type="text" id="txtfecnacperfam"  size="15" maxlength="10" readonly>
            <input name="reset42" type="reset" onClick="return showCalendar('txtfecnacperfam', '%d/%m/%Y');" value=" ... " />
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Parentesco</div></td>
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
          <td height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
		<tr>
        <td height="22"><div align="center"><a href="javascript:ue_limpiar_familiar(); ue_nuevo_familiar(); "><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Grabar" width="20" height="20" border="0">Nuevo Familiar</a></div></td>

        <td width="178">
			<a href="javascript: ue_guardar_familiares();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Familiar</a></td>
		  <td width="142">
			<a href="javascript: ue_eliminar_familiar();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Grabar" width="20" height="20" border="0">Eliminar Familiar</a></td>
          <td width="216"><span class="toolbar"><a href="javascript: ue_buscar_familiares();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Grabar" width="20" height="20" border="0">Buscar Familiares</a></span></td>
         
      </tr>
      </table>
          <p>&nbsp;</p>
		  
		  
		   </p></div>
		   
		   
		   <div>
          <p>
          
          <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4">            </td>
        </tr>
       <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Recaudos para Participar en el Concurso</td>
        </tr>
        <tr>
             <td colspan="3">&nbsp;</td>
		 </tr>
		 <tr>
        <td width="103" height="22">&nbsp;</td>

         <td width="279">
			<a href="javascript: ue_guardar_requisitos();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar Requisitos de Concursante</a></td>
          
		  <td width="275">
			<a href="javascript: ue_buscar_requisitos();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Consultar Requisitos de Concursante</a></td>
          <td width="35">&nbsp;</td>
      </tr>
	   <tr>
             <td colspan="3">&nbsp;</td>
		 </tr>
      </table>
          <p>&nbsp;</p>
          </p></div>
    </tr>
		 
            
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