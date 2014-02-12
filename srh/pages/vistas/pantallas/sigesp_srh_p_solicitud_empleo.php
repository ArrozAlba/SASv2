<?php
	session_start();
  	unset($_SESSION["parametros"]);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_solicitud_empleo.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
$io_fun_nomina=new class_funciones_nomina();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>SIGESP - Sistema Integrado de Gesti&oacute;n para Entes del Sector P&uacute;blico</title>



<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #f3f3f3;
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
.Estilo25 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_solicitud_empleo.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_cat_solicitud_empleo.js"></script>



</head>

<body onLoad="javascript:ue_nuevo_codigo();">

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo25">Sistema de Recursos Humanos</td>
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
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../public/imagenes/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../public/imagenes/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../public/imagenes/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../public/imagenes/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php

	

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		}

	
	
	//
?>

<p>
  
</p>
<p>&nbsp;</p>
<form name="form1" id="form1" method="post" action=""  >
  <p><?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  </p>
 <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
  <table width="715" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="715" height="136" >
  </p>
 
  <p>&nbsp;</p>
  <table width="688" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="6">Solicitud de Empleo</td>
        </tr>
        <tr>
          <td width="84" height="22">&nbsp;</td>
          <td width="155" height="22">&nbsp;</td>
          <td width="120" height="22">&nbsp;</td>
          <td height="22" colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Solicitud </div></td>
          <td height="22"><input name="txtnrosol" type="text" id="txtnrosol" value="<? print $ls_nrosol?>" maxlength="15" style="text-align:center"  readonly >
              <input name="hidstatus" type="hidden" id="hidstatus"></td>
          <td height="22"><div align="right">Fecha  Solicitud</div></td>
          <td height="22" colspan="2"><input name="txtfecsol" type="text" id="txtfecsol" value="<? print $ls_fecsol?>" maxlength="15" style="text-align:justify" readonly> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecsol', '%d/%m/%Y');" value=" ... " />
          </td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nombres</div></td>
          <td height="22"><input name="txtnomper" type="text" id="txtnomper" onKeyUp="ue_validarcomillas(this);" value="<? print $ls_nomper?>" maxlength="30" style="text-align:justify" ></td>
          <td height="22"><div align="right">Apellidos</div></td>
          <td height="22" colspan="2"><input name="txtapeper" type="text" id="txtapeper" onKeyUp="ue_validarcomillas(this);" value="<? print $ls_apeper?>" maxlength="30" style="text-align:justify" ></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Cedula</div></td>
          <td height="22"><input name="txtcedper" type="text" id="txtcedper" value="<? print $ls_cedper?>" maxlength="10" style="text-align:justify"    onKeyUp="javascript: ue_validarnumero(this);"  onBlur="javascript: ue_chequear_cedula();"></td>
          <td height="22"><div align="right">Fecha Nac.</div></td>
          <td height="22" colspan="2"><input name="txtfecnacper" type="text" id="txtfecnacper" value="<? print $ls_fecnacper?>" maxlength="15" style="text-align:justify"  readonly> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecnacper', '%d/%m/%Y');" value=" ... " />
          </td>
        </tr>
		<tr>
		     <td height="22" align="left"><div align="right">Nacionalidad</div></td>
         <td height="22" bordercolor="0"><select name="cmbnacper" id="cmbnacper">
              <option value="">--Seleccione--</option>
              <option value="V"  <?php if($ls_nacper=="V"){ print "selected";} ?>>Venezolano</option>
              <option value="E"  <?php if($ls_nacper=="E"){ print "selected";} ?>>Extrajero</option>
            </select>          </td>
		
          <td height="22"><div align="right">Lugar Nacimiento </div></td>
		  
          <td height="22" colspan="2"><select name="cmbcodpai"   id="cmbcodpai" onChange="ue_cambiopais();">
              <option value="" >--Seleccione un Pais--</option>
            </select>          </td> </tr>
			 <tr>
          <td height="22" align="left"></td>
          <td height="22"></td>
          <td height="22"></td>
          <td height="22" colspan="2"><select name="cmbcodest"   id="cmbcodest" onclick="valida_cmbcodest();" onChange="ue_cambioestado();">
              <option value="">--Seleccione un Estado--</option>
            </select>      <input name="hidcodest"  type="hidden" id="hidcodest"  value="">    </td>
			
        <tr>
          <td height="22" align="left"></td>
          <td height="22"></td>
          <td height="22"></td>
          <td height="22" colspan="2"><select name="cmbcodmun" id="cmbcodmun"  onclick="valida_cmbcodmun();"  onChange="ue_cambiomunicipio();"     >
              <option value="">--Seleccione un Municipio--</option>
            </select>         
			<input name="hidcodmun"  type="hidden" id="hidcodmun"  value="">
		  </td>
        </tr>
        <tr>
          <td height="22" align="left"></td>
          <td height="22"></td>
          <td height="22"></td>
          <td height="22" colspan="2"><select name="cmbcodpar" id="cmbcodpar"  onclick="valida_cmbcodpar();">
              <option value="">--Seleccione una Parroquia--</option>
            </select>         
			<input name="hidcodpar"  type="hidden" id="hidcodpar"  value="">
		  </td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Peso</div></td>
          <td height="22" bordercolor="0"><input name="txtpesper" type="text" id="txtpesper" value="<?php print $li_pesper;?>" size="8" maxlength="5" style="text-align:left" onKeyPress='return validarreal2(event,this);'>          </td>
          <td height="22"><div align="right">Estatura</div></td>
          <td height="22" colspan="2"> <input name="txtestaper" type="text" id="txtestaper" value="<?php print $li_estaper;?>" size="8" maxlength="5" style="text-align:left" onKeyPress='return validarreal2(event,this);'>
          </select></td>
        </tr>
		
        <tr>
          <td height="22" align="left"><div align="right">G&eacute;nero</div></td>
          <td height="22" bordercolor="0"><select name="cmbsexper" id="cmbsexper">
              <option value="">--Seleccione--</option>
              <option value="F"  <?php if($ls_sexper=="F"){ print "selected";} ?>>Femenino</option>
              <option value="M"  <?php if($ls_sexper=="M"){ print "selected";} ?>>Masculino</option>
            </select>          </td>
          <td height="22"><div align="right">Estado Civil</div></td>
          <td height="22" colspan="2"><select name="cmbedocivper" id="cmbedocivper">
              <option value= "">--Seleccione--</option>
              <option value="S" <?php if($ls_edocivper=="S"){ print "selected";} ?>> Soltero </option>
              <option value="C" <?php if($ls_edocivper=="C"){ print "selected";} ?>> Casado </option>
              <option value="V" <?php if($ls_edocivper=="V"){ print "selected";} ?>> Viudo </option>
              <option value="D" <?php if($ls_edocivper=="D"){ print "selected";} ?>>Divorciado </option>
              <option value="O" <?php if($ls_edocivper=="O"){ print "selected";} ?>>Concubino </option>
          </select></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Carga Familiar </div></td>
          <td height="22"><input name="txtcarfam" type="text" id="txtcarfam" value="<? print $ls_carfam?>" maxlength="5" style="text-align:justify" size="7"  onKeyUp="javascript: ue_validarnumero(this);" ></td>
          <td height="22"><div align="right">Profesi&oacute;n </div></td>
          <td width="75" height="22" valign="middle"><input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codpro ?>" size="5" readonly>
          <a href="javascript:catalogo_profesion();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Profesion" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
          <td width="244" valign="middle"><input name="txtdespro" type="text" class="sin-borde" id="txtdespro" value="<?php print $ls_despro?>" size="40"  readonly></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Telf. hab.</div></td>
          <td height="22"><input name="txttelhabper" type="text" id="txttelhabper" value="<? print $ls_telhabper?>" maxlength="15" style="text-align:justify" onKeyUp="javascript: ue_validarnumero(this);"></td>
          <td height="22"><div align="right">Telf. M&oacute;vil</div></td>
          <td height="22" colspan="2"><input name="txttelmovper" type="text" id="txttelmovper" style="text-align:justify" onKeyUp="javascript: ue_validarnumero(this);" value="<? print $ls_telmovper?>"></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">E-mail</div></td>
          <td height="22" colspan="2"><input name="txtcoreleper" type="text" id="txtcoreleper" onKeyUp="ue_validarcomillas(this);" value="<? print $ls_email?>" maxlength="100" size="40"  style="text-align:justify"></td>
		  </tr>
		   
        <tr>
          <td height="22" align="left"><div align="right">Direcci&oacute;n </div></td>
          <td height="22" colspan="4"><input name="txtdirper" type="text" id="txtdirper" onKeyUp="ue_validarcomillas(this);" style="text-align:justify" value="<? print $ls_dirper?>" size="86" maxlength="256"></td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Nivel Acd&eacute;mico</div></td>
          <td height="22" bordercolor="0"><select name="cmbnivacaper" id="cmbnivacaper">
              <option value="" selected>--Seleccione--</option>
              <option value="1" <?php if($ls_nivacaper=="1"){ print "selected";} ?>>Primaria</option>
              <option value="2" <?php if($ls_nivacaper=="2"){ print "selected";} ?>>Bachiller</option>
		      <option value="3" <?php if($ls_nivacaper=="3"){ print "selected";} ?>>T&eacute;cnico Superior</option>
              <option value="4" <?php if($ls_nivacaper=="4"){ print "selected";} ?>>Universitario</option>
			  <option value="5" <?php if($ls_nivacaper=="5"){ print "selected";} ?>>Maestr&iacute;a</option>
              <option value="6" <?php if($ls_nivacaper=="6"){ print "selected";} ?>>Postgrado</option>
			  <option value="7" <?php if($ls_nivacaper=="7"){ print "selected";} ?>>Doctorado</option>
            </select>          </td>
		</tr>
        <tr>
          <td height="22" align="left"><div align="right">Competencia o Perfil</div></td>
          <td height="22" colspan="4"><textarea name="txtcomsol" cols="83" id="txtcomsol" onKeyUp="ue_validarcomillas(this);" ><? print $ls_comsol; ?></textarea></td>
        </tr>
        <tr >
          <td height="22"><div align="right">Nivel Seleccion </div></td>
          <td height="22" valign="middle"><input name="txtcodniv" type="text" id="txtcodniv" value="<?php print $ls_codniv ?>" size="16" maxlength="15"  readonly>
            <a href="javascript:catalogo_nivel();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Nivel de Selecciòn" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
          <td height="22" colspan="3"><input name="txtdenniv" type="text" class="sin-borde" id="txtdenniv" value="<?php print $ls_denniv?>" size="50"  readonly></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22">&nbsp;</td>
          
        </tr>
		<tr>
          <td colspan="5">
		  	<div align="center">
			<?php
			//		$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
			//		unset($io_grid);
			?>
			  </div> 
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			  <input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
			</p>			</td>		  
          </tr>
        
      </table>
      <p>&nbsp;</p>
      
 
</table>
<p>
 
    <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
	 <input type="hidden" name="hidguardar2" id="hidguardar2">
	  <input type="hidden" name="txtcontrol" id="txtcontrol" value="<? print $ls_control;?>">
    	    <input name="hidcontrol3" type="hidden" id="hidcontrol3" value="">
    <input name="operacion" type="hidden" id="operacion">
	 <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
	<input name="hidcontrol" type="hidden" id="hidcontrol" value="3">
	<input name="hidcodestnac"  type="hidden" id="hidcodestnac"  value="">

  </p>
</form>


</body>


</html>



