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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_registro_ascenso.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_registro_ascenso.js"></script>



</head>

<body onLoad="javascript: ue_nuevo_codigo();">
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
	
?>
<p>&nbsp;</p>
<form name="form1" method="post" action=""  >
 <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
  <table width="715" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="715" height="136"><p>
      <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    </p>
      <p>&nbsp; </p>
      <table width="668" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="11">Registro de Postulados para Ascenso</td>
        </tr>
        <tr>
          <td width="186" height="22">&nbsp;</td>
          
          <td height="22" colspan="7">&nbsp;</td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Nro. Registro</div></td>
          <td height="22" colspan="4"><input name="txtnroreg" type="text" id="txtnroreg"  maxlength="15"  size="16" style="text-align:center"  readonly >
              <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		  <tr>
          <td height="22"><div align="right">Fecha </div></td>
          <td height="22" colspan="5"><input name="txtfecreg" type="text" id="txtfecreg"  maxlength="15" size="16" style="text-align:center" readonly > 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecreg', '%d/%m/%Y');" value=" ... " />           </td>
        </tr>
		
          <tr>
             <td height="22"><div align="right">Concurso </div></td>
          <td width="156" height="22"  valign="middle"><input name="txtcodcon" type="text" id="txtcodcon" size="16" maxlength="15" style="text-align:center" readonly>
            <a href="javascript:catalogo_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Nivel de Selecciòn" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>            </td>
          
          <td width="300"  valign="middle"><input name="txtdescon" type="text" class="sin-borde" id="txtdescon"  size="50" maxlength="80" readonly></td>
          <td height="22" colspan="2" valign="middle">&nbsp;</td>
          </tr>
		     <tr>
			  <td height="22" align="left"><div align="right">Cargo </div></td>
          <td height="22" colspan="8"><input name="txtdescar" type="text" id="txtdescar" maxlength="40" style="text-align:justify" size="50" readonly >               </td>
		  </tr>
		<!--  <tr>
			  <td height="22" align="left"><div align="right">Ubicaci&oacute;n del Cargo </div></td>
          <td height="22" colspan="8"><input name="txtubicar" type="text" id="txtubicar" maxlength="40" style="text-align:justify" size="30" readonly >               </td>
		  </tr>-->
		   <tr>
          <td height="22" align="left"><div align="right">Requisitos M&iacute;nimos del Cargo</div></td>
          <td height="22" colspan="8"><textarea name="txtreqmin" cols="83" onKeyUp="ue_validarcomillas(this);" rows="6" id="txtreqmin"></textarea></td>
        </tr>
		 <tr>
		   <td height="22" colspan="7">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="11">Datos del Postulado</td>
        </tr>
		 <tr>
		   <td height="22" colspan="7">&nbsp;</td>
        </tr>
		<tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22" colspan="6"><input name="txtcodper" type="text" id="txtcodper"  maxlength="10" size="16"  style="text-align:center"    readonly >  <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
          <td width="14" valign="middle"></td>
        </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="7"><input name="txtnomper" type="text" id="txtnomper"  style="text-align:justify" size="50" readonly >               </td>
		  </tr>
		<tr>
		<tr>
			  <td height="22" align="left"><div align="right">Cargo Actual</div></td>
          <td height="22" colspan="7"><input name="txtcaract" type="text" id="txtcaract"   style="text-align:justify" size="50" readonly >               </td>
		  </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">Fecha Ingreso</div></td>
          <td height="22" colspan="7"><input name="txtfecing" type="text" id="txtfecing"  style="text-align:justify" size="30" readonly >               </td>
		  </tr>
		  
		 <!--  <tr>
			  <td height="22" align="left"><div align="right">Departamento de Adscripci&oacute;n </div></td>
          <td height="22" colspan="8"><input name="txtdep" type="text" id="txtdep"  style="text-align:justify" size="30" readonly >               </td>
		  </tr>-->
		  
		  <tr>
          <td height="22" align="left"><div align="right">Observaci&oacute;n</div></td>
          <td height="22" colspan="8"><textarea name="txtobs" onKeyUp="ue_validarcomillas(this);" cols="83" rows="6" id="txtobs"> </textarea></td>
        </tr>
		  
		<tr>
          <td height="22" colspan="7">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Datos del Supervisor</td>
        </tr>
        <tr>
          <td width="186" height="22">&nbsp;</td>
          
          <td height="22" colspan="6">&nbsp;</td>
        </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">C&oacute;digo Supervisor</div></td>
          <td height="22" colspan="5"><input name="txtcodsup" type="text" id="txtcodsup" maxlength="10" style="text-align:center"    readonly >   <a href="javascript:catalogo_supervisor();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
		  </tr>
		 		       
        <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="4"><input name="txtnomsup" type="text" id="txtnomsup" maxlength="50" style="text-align:justify" size="50" readonly >            </td>
          <td width="161" valign="middle"> </td>
        </tr>
         <tr>
             <td height="22"><div align="right">Cargo </div></td>
          <td height="22" colspan="3" valign="middle"><input name="txtcodcarsup" type="text" id="txtcodcarsup"  size="50" readonly>          </td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Opini&oacute;n del Supervisor</div></td>
          <td height="22" colspan="8"><textarea name="txtopi"  onKeyUp="ue_validarcomillas(this);" cols="83" rows="6" id="txtopi"> </textarea></td>
        </tr>
		 <tr>
		   <td height="22" colspan="7">&nbsp;</td>
        </tr>
      </table>	 
      <p>&nbsp;</p>
     
 </td> 
</table>
  <p>
    <input type="hidden" id="hidguardar">
	 <input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
	 <input name="hidstatus" type="hidden" id="hidstatus">
 	 <input name="hidcontrol2" type="hidden" id="hidcontrol2" value="3">
     <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
		    
  </p>

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>

