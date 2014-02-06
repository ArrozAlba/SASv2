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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_resultados_evaluacion_aspirante.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_resultados_evaluacion_aspirante.js"></script>



</head>

<body>

<?php 
    require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();

    global $ls_codper, $ls_nomper, $ls_codcon, $ls_descon, $ls_fecha, $li_pun1,$li_pun2, $li_pun3,$li_total, $ls_guardar,$ls_operacion,$ls_existe;
    $ls_codper="";
	$ls_nomper="";
	$ls_codcon="";
	$ls_descon="";
	$ls_fecha="";
	$li_pun1="";
	$li_pun2="";
	$li_pun3="";
	$li_total="";
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	$ls_existe=$io_fun_nomina->uf_obtenerexiste();
	require_once("../../../class_folder/dao/sigesp_srh_c_resultados_evaluacion_aspirante.php");
	$io_res=new sigesp_srh_c_resultados_evaluacion_aspirante("../../../../");
	
	switch ($ls_operacion) 
	{ 
	    case "CONSULTAR":
		 	$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_codcon=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_guardar=$_POST["hidguardar"];
			$lb_valido=$io_res->uf_srh_consultarresultados_evaluacion_aspirante($ls_codper,$ls_codcon,$li_pun1,$li_pun2, $li_pun3,$li_total );
			
			break;
				
	}
	
	unset($io_res);
?>




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
	 <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir();"><img src="../../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
   
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
      <table width="707" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="9">Resultados de Evaluaci&oacute;n de Aspirantes</td>
        </tr>
        <tr>
          <td width="222" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
             <td height="22"><div align="right">Concurso </div></td>
          <td width="150" height="22"  valign="middle"><input name="txtcodcon" type="text" id="txtcodcon" value="<?php print $ls_codcon?>" size="16" maxlength="15" style="text-align:center"  readonly>
          <a href="javascript:catalogo_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Nivel de Selecciòn" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>            </td>
          <td height="22" colspan="2" valign="middle"><input name="txtdescon" type="text" class="sin-borde" id="txtdescon" value="<?php print $ls_descon?>" size="65" readonly /></td>
        </tr>
       <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Aspirante</div></td>
          <td height="22" ><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codper?>" maxlength="10" size="16"  style="text-align:center"    readonly >  
        <a href="javascript:catalogo_persona_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td> 
	     
	    
    </tr>
    <tr>
      <td height="22" align="left"><div align="right">Nombre</div></td>
      <td height="22" colspan="6"><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomper?>" maxlength="40" style="text-align:justify" size="45" readonly >      </td>
	  </tr>		  				       
          
	
		   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="144" align="right"><a href="javascript: Limpiar_Datos();"> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar Datos</a></td>
    <td width="236"><a href="javascript: ue_chequear_codigo();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Datos</a></td>
  </tr>
			
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="9">Datos de la Evaluaci&oacute;n</td>
        </tr>
		<tr>
          <td width="222" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
          <td height="22"><div align="right">Fecha Registro Resultados</div></td>
          <td height="22" colspan="5">   <input name="txtfecha" type="text" id="txtfecha" value="<? print $ls_fecha?>" size="16"style="text-align:center" readonly > <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " / >		   </td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Resultado Evaluaci&oacute;n Requisitos Minimos</div></td>
          <td height="22" colspan="4"><input name="txtpunreqmin" type="text" id="txtpunreqmin" style="text-align:justify"  size="5" maxlength="5" readonly  value="<? print $li_pun1?>"></td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Resultado Evaluaci&oacute;n Psicol&oacute;gica</div></td>
          <td height="22" colspan="4"><input name="txtpunevalpsi" type="text" id="txtpunevalpsi" style="text-align:justify"  size="5" maxlength="5" readonly  value="<? print $li_pun2?>"></td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Resultado Entrevista T&eacute;cnica</div></td>
          <td height="22" colspan="4"><input name="txtpunenttec" type="text" id="txtpunenttec" style="text-align:justify"  size="5" maxlength="5" readonly  value="<? print $li_pun3?>"></td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Resultado Total Evaluaci&oacute;n Aspirante</div></td>
          <td height="22" colspan="4"><input name="txttoteva" type="text" id="txttoteva" style="text-align:justify"  size="5" maxlength="5" value="<? print $li_total?>" readonly></td>
        </tr>
         <tr>
          <td height="22" align="left"><div align="right">Conclusi&oacute;n </div></td>
          <td height="22" colspan="4"><textarea name="txtconclu" cols="86" rows="5" id="txtconclu"  onKeyUp="ue_validarcomillas(this);" style="text-align:justify"></textarea></td>
        </tr>
     	<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
</table>
  <p>
      <input name="hidcontrol" type="hidden" id="hidcontrol" value="2">
	 <input name="hidcontrol3" type="hidden" id="hidcontrol3" value="3">
	 <input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
    <input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input type="hidden" name="hidguardar" id="hidguardar" value="<?php print $ls_guardar;?>">
	<input name="operacion" type="hidden" id="operacion">
	 <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
 
  </p>

</form>

<script language="javascript">
function ue_imprimir()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;

	if(li_imprimir==1)
	{	
     	codper=f.txtcodper.value;
		codcon=f.txtcodcon.value;
		fecha=f.txtfecha.value;
		punreqmin=f.txtpunreqmin.value;
		punevapsi=f.txtpunevalpsi.value;
		punenttec=f.txtpunenttec.value;
		total=f.txttoteva.value;
		conclusion=f.txtconclu.value;
		
		if ((codper=="") && (total==""))
		{
		   alert ('Debe seleccionar un registro del catálogo para generar el reporte');
		}
		else
		{		
		
		pagina="../../../reporte/sigesp_srh_rpp_resultados_evaluacion.php?codper="+codper+"&codcon="+codcon+"&fecha="+fecha+"&total="+total+"&conclusion="+conclusion+"&punreqmin="+punreqmin+"&punevapsi="+punevapsi+"&punenttec="+punenttec+"";
		window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
	}
	
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}
</script>
</body>


</html>


