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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_requisitos_minimos.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
$io_fun_nomina=new class_funciones_nomina();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

 function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
   		global $ls_codper,$ls_codeval,$ls_deneval,$ls_nomper,$ls_concurso,$ls_descon, $ls_fecha, $li_res, $ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codper="";
		$ls_nomper="";
		$ls_concurso="";
		$ls_descon="";
		$ls_fecha="";
		$ls_codeval="";
    	$ls_deneval="";
		$ls_guardar="";
		$li_res=0;
		$ls_activarcodigo="";
		$ls_titletable="Items de Evaluación de Requisitos Mínimos";
		$li_widthtable=600;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Denominación";
		$lo_title[3]="Puntaje Requerido";
		$lo_title[4]="Puntaje Obtenido";
		
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15  readonly  >";
		$aa_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." class=sin-borde size=70  readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtvalor".$ai_totrows." type=text id=txtvalor".$ai_totrows." class=sin-borde maxlength=3 size=8
readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=8   onKeyUp='javascript: ue_validarnumero(this);' onChange='javascript:valida_puntos(this,txtvalor".$ai_totrows."); ue_suma(txtres);'>";
		
   }
   //--------------------------------------------------------------

 //--------------------------------------------------------------
  function uf_cargar_dt($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codite,$ls_denite,$li_valor,$li_puntos;

		$ls_codite=$_POST["txtcodite".$li_i];
		$ls_denite=$_POST["txtdenite".$li_i];
	    $li_valor=$_POST["txtvalor".$li_i];
		$li_puntos=$_POST["txtpuntos".$li_i];
			
   }
   //--------------------------------------------------------------



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
	font-size: 12px;
	color: #6699CC;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_requisitos_minimos.js"></script>



</head>

<body>
<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_requisitos_minimos.php");
	$io_req=new sigesp_srh_c_requisitos_minimos("../../../../");
	require_once("../../../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

			
		case "BUSCARDETALLE":
		 	$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_concurso=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_codeval=$_POST["txtcodeval"];
    		$ls_deneval=$_POST["txtdeneval"];
			$li_res=$_POST["txtres"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_req->uf_srh_load_requisitos_minimos_campos($ls_codper,$ls_fecha,$li_totrows,$lo_object);
			break;
	
	
	 case "CONSULTAR":
	   	    $ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_concurso=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_codeval=$_POST["txtcodeval"];
    		$ls_deneval=$_POST["txtdeneval"];
			$li_res=$_POST["txtres"];
			$ls_guardar=$_POST["hidguardar"];
			$lb_valido=$io_req->uf_srh_consultar_items($ls_codeval,$li_totrows,$lo_object);
			break;	
	
	}
	unset($io_req);
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
?>
<p>
  
</p>
<p>&nbsp;</p>
<form name="form1" method="post" action=""  ><div >


<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
<table width="715" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr><td width="715" height="136"><table width="688" height="240" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
    <tr class="titulo-nuevo">
      <td height="20" colspan="9">Evaluaci&oacute;n de Requisitos M&iacute;nimos de Aspirantes</td>
    </tr>
    <tr>
      <td width="133"   height="22">&nbsp;</td>
      <td height="22" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="28"><div align="right"> Tipo de Evaluaci&oacute;n</div></td>
      <td height="28"  colspan="6"><input name="txtcodeval" type="text" id="txtcodeval" value="<?php print $ls_codeval ?>" size="16" maxlength="15"  style="text-align:center" readonly>
          <a href="javascript: catalogo_evaluacion();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo  Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
          <input name="txtdeneval" type="text" class="sin-borde" id="txtdeneval" value="<?php print $ls_deneval ?>" size="50
          " maxlength="80" readonly>      </td>
    </tr>
	
	  <tr>
      <td height="28"><div align="right"></div></td>
	  <td width="63" height="28"><div align="right"></div></td>
	  <td width="67" height="28"><div align="right"></div></td>
	  <td width="132" height="28"><div align="right"></div></td>
	  <td width="205" height="28"><a href="javascript:consultar_items();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Items de Evaluaci&oacute;n</a></td>
    </tr>
	 <tr>
      <td height="22"><div align="right">Concurso </div></td>
      <td  height="22"  valign="middle" colspan="6"><input name="txtcodcon" type="text" id="txtcodcon" value="<?php print $ls_concurso ?>" size="16" maxlength="15" style="text-align:center" readonly>
          <a href="javascript:catalogo_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Nivel de Selecciòn" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
          <input name="txtdescon" type="text" class="sin-borde" id="txtdescon" size="50" maxlength="80" readonly value="<?php print $ls_descon ?>" /></td>
      <td width="64" height="22" colspan="2" valign="middle">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="left"><div align="right">C&oacute;digo Aspirante</div></td>
      <td height="22" colspan="3"><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codper?>" maxlength="10" size="16"  style="text-align:center"   readonly >  <a href="javascript:catalogo_persona_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td> 
	  
    </tr>
    <tr>
      <td height="22" align="left"><div align="right">Nombre</div></td>
      <td height="22" colspan="6"><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomper?>" maxlength="40" style="text-align:justify" size="45" readonly >      </td>
    </tr>   
    <tr>
      <td height="22"><div align="right">Fecha Evaluaci&oacute;n </div></td>
      <td height="22" colspan="5"><input name="txtfecha" type="text" id="txtfecha" value="<? print $ls_fecha?>" size="16" style="text-align:center" readonly >
          <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " onBlur="javascript: ue_chequear_codigo();">      </td>
	  <tr>
	  <td height="22" align="left"><div align="right">Resultado Evaluaci&oacute;n</div></td>
	  <td height="22" colspan="4"><input name="txtres" type="text" id="txtres" maxlength=3 size="7" style="text-align:center"  onKeyUp="javascript: ue_validarnumero(this);"  value="<?php print $li_res?>" readonly >            </td>
	      </tr>
 
    <tr>
      <td ></td>
    </tr>
    <tr >
      <div align="right"></div>
      <td td height="22"><input name="operacion" type="hidden" id="operacion">
          <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
    </tr>
	
          <td  colspan="9">
		  	<div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			</p>			</td>		  
          </tr>
		 
      <p>&nbsp;</p>
     <tr>
          <td height="22">&nbsp;<input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly></td>
          <td colspan="4"> <div align="right"></div></td>
        </tr>
    <tr>
</table>
  <p>
     <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
	    	  
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
    <input name="hidstatus" type="hidden" id="hidstatus">
	<input name="txtfechaaper" type="hidden" id="txtfechaaper" >
     <input name="hidcontrol" type="hidden" id="hidcontrol" value="5">
	 <input name="hidcontrol3" type="hidden" id="hidcontrol3" value="3">
	 <input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
  </p>


</form>

</body>


</html>



