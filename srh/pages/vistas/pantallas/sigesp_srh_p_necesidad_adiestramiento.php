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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_necesidad_adiestramiento.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_nroreg,$ls_fecha, $ls_codper, $ls_nomper,$ls_carper, $ls_nivaca,$ls_coduni, $ls_denunivi, $ls_codsup, $ls_nomsup,$ls_carsup, $ls_compe, $ls_area, $ls_obj, $ls_estcap, $ls_obs,$ls_activarcodigo,$ls_guardar,$ls_operacion,$ls_existe, $ls_existe2,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre, $li_totrows2,$ls_titletable2,$li_widthtable2,$ls_nametable2,$lo_title2;
	 	$ls_nroreg="";
		$ls_fecha="";
		$ls_codper="";
		$ls_nomper="";
		$ls_carper="";
		$ls_nivaca="";
		$ls_coduni="";
		$ls_denunivi="";
		$ls_codsup="";
		$ls_nomsup="";
		$ls_carsup="";
		$ls_compe="";
		$ls_area="";
		$ls_obj="";
		$ls_estcap="";
		$ls_obs="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$li_totrows2=$io_fun_nomina->uf_obtenervalor("totalfilas2",1);
		$ls_existe2=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca_causa(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodcauadi".$ai_totrows." type=text id=txtcodcauadi".$ai_totrows." class=sin-borde size=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtdencauadi".$ai_totrows." type=text id=txtdencauadi".$ai_totrows." class=sin-borde size=50 readonly>";	
		$aa_object[$ai_totrows][3]="<select name=cmbselcau".$ai_totrows." id=cmbselcau".$ai_totrows.">
									<option value='S'>Si</option>
									<option value='N'selected >No</option></select> ";		
				
   }
   
   
   function uf_agregarlineablanca_competencia(&$aa_object1,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object1[$ai_totrows][1]="<input name=txtcodcomp".$ai_totrows." type=text id=txtcodcomp".$ai_totrows." class=sin-borde size=15  readonly  >";
		$aa_object1[$ai_totrows][2]="<input name=txtdencomp".$ai_totrows." type=text id=txtdencomp".$ai_totrows." class=sin-borde size=50 readonly>";
		$aa_object1[$ai_totrows][3]="<select name=cmbprio".$ai_totrows." id=cmbprio".$ai_totrows.">
              <option value='0' selected>No aplica</option>
              <option value='1'>Urgente</option>
              <option value='2'>Importante</option>
              <option value='3'>Puede Esperar</option></select>";		
   }
   
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_cargar_dt_causa($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codcauadi,$ls_dencauadi,$ls_selcau, $la_selcau;

		$ls_codcauadi=$_POST["txtcodcauadi".$li_i];
		$ls_dencauadi=$_POST["txtdencauadi".$li_i];
	    $ls_selcau=$_POST["cmbselcau".$li_i];
		$la_selcau[0]="";
		$la_selcau[1]="";
		
		if (array_key_exists("cmbselcau".$li_i,$_POST))
		{
		 	$ls_selcau=$_POST["cmbselcau".$li_i];
			$la_selcau[0]="";
			$la_selcau[1]="";
		}
		else
		{
			$ls_selcau="";
			$la_selcau[0]="";
			$la_selcau[1]="";
		}
		
	   switch($ls_selcau)
		{
			case "S":
				$la_selcau[0]="selected";
				break;
			case "N":
				$la_selcau[1]="selected";
				break;
		}
				
				
   }
   
    function uf_cargar_dt_competencia($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codcomp,$ls_dencomp,$ls_prio, $la_prio;

		$ls_codcomp=$_POST["txtcodcomp".$li_i];
		$ls_dencomp=$_POST["txtdencomp".$li_i];
	    $ls_prio=$_POST["cmbprio".$li_i];
		
		if (array_key_exists("cmbprio".$li_i,$_POST))
		{
		 	$ls_prio=$_POST["cmbprio".$li_i];
			$la_prio[0]="";
			$la_prio[1]="";
			$la_prio[2]="";
			$la_prio[3]="";
		}
		else
		{
			$ls_prio="";
			$la_prio[0]="";
			$la_prio[1]="";
			$la_prio[2]="";
			$la_prio[3]="";
		}
		
	   switch($ls_prio)
		{
			case "0":
				$la_prio[0]="selected";
				break;
			case "1":
				$la_prio[1]="selected";
				break;
			case "2":
				$la_prio[2]="selected";
				break;
			case "3":
				$la_prio[3]="selected";
				break;
		}
		
				
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
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_necesidad_adiestramiento.js"></script>



</head>

<body onLoad="javascript: ue_nuevo_codigo();">
<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_necesidad_adiestramiento.php");
	$io_nec=new sigesp_srh_c_necesidad_adiestramiento("../../../../");
	
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			$li_totrows2=1;
			uf_agregarlineablanca_competencia($lo_object2,1);
			uf_agregarlineablanca_causa($lo_object,1);
			break;

		case "CONSULTAR":
			$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_carper=$_POST["txtcodcarper"];
			$ls_nivaca=$_POST["txtnivacaper"];
			$ls_coduni=$_POST["txtcodunivi"];
			$ls_denunivi=$_POST["txtdenunivi"];
			$ls_codsup=$_POST["txtcodsup"];
			$ls_nomsup=$_POST["txtnomsup"];
			$ls_carsup=$_POST["txtcodcarsup"];
			$ls_compe=$_POST["txtcompe"];
			$ls_area=$_POST["txtarea"];
			$ls_obj=$_POST["txtobj"];
			$ls_estcap=$_POST["txtestcap"];
			$ls_obs=$_POST["txtobs"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$io_nec->uf_srh_consultar_causas_adiestramiento($li_totrows,$lo_object);
			$io_nec->uf_srh_consultar_competencias_adiestramiento($li_totrows2,$lo_object2);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_carper=$_POST["txtcodcarper"];
			$ls_nivaca=$_POST["txtnivacaper"];
			$ls_coduni=$_POST["txtcodunivi"];
			$ls_denunivi=$_POST["txtdenunivi"];
			$ls_codsup=$_POST["txtcodsup"];
			$ls_nomsup=$_POST["txtnomsup"];
			$ls_carsup=$_POST["txtcodcarsup"];
			$ls_compe=$_POST["txtcompe"];
			$ls_area=$_POST["txtarea"];
			$ls_obj=$_POST["txtobj"];
			$ls_estcap=$_POST["txtestcap"];
			$ls_obs=$_POST["txtobs"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$io_nec->uf_srh_load_causas_adiestramiento($ls_nroreg,$li_totrows,$lo_object);
			$io_nec->uf_srh_load_competencias_adiestramiento($ls_nroreg,$li_totrows2,$lo_object2);
			break;
	}
	
	unset($io_nec);
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
      <p>&nbsp;</p>
      <table width="688" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="9">Detecci&oacute;n de Necesidades de Adiestramiento</td>
        </tr>
        <tr>
          <td width="150" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Registro</div></td>
          <td height="22" colspan="4"><input name="txtnroreg" type="text" id="txtnroreg" maxlength="15" style="text-align:center" value="<? print $ls_nroreg?>" readonly>      <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		  <tr>
		 <td height="22"><div align="right">Fecha Diagn&oacute;stico</div></td>
          <td height="22" colspan="2"><input name="txtfecha" type="text" id="txtfecha"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>">
            <input name="reset2" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " /></td> 			
        </tr>
		  <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo del Personal</div></td>
          <td width="329" height="22" ><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codper?>" maxlength="10" size="16"  style="text-align:center"    readonly >  <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
          </tr>
		 <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td width="329" height="22" ><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomper?>"  size="50"  style="text-align:left"   readonly  >        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Cargo</div></td>
          <td width="329" height="22" ><input name="txtcodcarper" type="text" id="txtcodcarper" value="<? print $ls_carper?>"  size="50"  style="text-align:left"  readonly   >        </tr>
		   <tr>
          <td height="22" align="left"><div align="right">Nivel Educativo</div></td>
          <td width="329" height="22" ><input name="txtnivacaper" type="text" id="txtnivacaper" value="<? print $ls_nivaca?>"  size="50"  style="text-align:left"  readonly   >        </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">Unidad Vipladin</div></td>
          <td height="22" colspan="4" ><input name="txtcodunivi" type="text" id="txtcodunivi" value="<? print $ls_coduni?>"  size="16"  style="text-align:center"    readonly >  <a href="javascript:catalogo_unidad();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Unidad Administrativa" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> <input name="txtdenunivi" type="text" id="txtdenunivi" value="<? print $ls_denunivi?>"  style="text-align:justify" size="47" readonly  class="sin-borde"></td>
           <td height="22" colspan="4" valign="middle">&nbsp;</td>
        </tr>
		<tr>
          <td width="150" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr class="titulo-nuevo">
          <td height="22" colspan="9">Datos del Supervisor</td>
        </tr>
        <tr>
          <td width="150" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">C&oacute;digo Supervisor</div></td>
          <td height="22" colspan="4"><input name="txtcodsup" type="text" id="txtcodsup" maxlength="10" style="text-align:center"    readonly  size="16" value="<?php print $ls_codsup?>">   <a href="javascript:catalogo_supervisor();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
		  </tr>
		 		       
        <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="3"><input name="txtnomsup" type="text" id="txtnomsup" maxlength="50" style="text-align:justify" size="50" readonly value="<?php print $ls_nomsup?>">            </td>
          <td width="200" valign="middle"> </td>
        </tr>
         <tr>
             <td height="22"><div align="right">Cargo </div></td>
          <td height="22" colspan="2" valign="middle"><input name="txtcodcarsup" type="text" id="txtcodcarsup" value="<?php print $ls_carsup?>" style="text-align:justify" readonly size="50">         </td>
        </tr>
		<tr>
          <td width="150" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		<tr>
          <td  height="22" colspan="9"><div align="center"><a href="javascript: Consultar();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Causas y Competencias de Adiestramiento</a></div></td>
          
        </tr>
		<tr>
          <td width="150" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr class="titulo-nuevo">
          <td height="22" colspan="9">Causas del Adiestramiento</td>
        </tr>
		 <tr>
          <td width="150" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
          <td colspan="6">
		  	<div align="center">
			<?php
				require_once("../../../../shared/class_folder/grid_param.php");
				$io_grid=new grid_param();
				$ls_titletable="Causas que originan la necesidad de Adiestramiento";
				$li_widthtable=550;
				$ls_nametable="grid";
				$lo_title[1]="Código";
				$lo_title[2]="Denominación";	
				$lo_title[3]="Selección";	
				$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				unset($io_grid);
				
			
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			</p>			</td>		  
          </tr>
				

		  <tr>
		
		<tr>
          <td width="150" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		<tr class="titulo-nuevo">
          <td height="22" colspan="9">Competencias del Adiestramiento</td>
        </tr>
		 <tr>
          <td width="150" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Competencias o Actitudes a ser Fortalecidas (relacionadas con la actividad actual o futura dento de la Organizaci&oacute;n)</div></td>
	  <td height="22" colspan="4"><textarea name="txtcompe" cols="86" rows="7" id="txtcompe"  onKeyUp="ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_compe?></textarea></td>
	      </tr>
		   <tr>
          <td width="150" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
          <td colspan="6">
		  	<div align="center">
			<?php
				require_once("../../../../shared/class_folder/grid_param.php");
				$io_grid=new grid_param();
				$ls_titletable2="Competencias Genéricas del Adiestramiento";
				$li_widthtable2=550;
				$ls_nametable2="grid2";
				$lo_title2[1]="Código";
				$lo_title2[2]="Denominación";	
				$lo_title2[3]="Prioridad";			
				$io_grid->makegrid($li_totrows2,$lo_title2,$lo_object2,$li_widthtable2,$ls_titletable2,$ls_nametable2);
				unset($io_grid);
				
			?>
			  </div>		  				</td>		  
          </tr>
		<tr>
          <td width="150" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr class="titulo-nuevo">
          <td height="22" colspan="9">Para Uso de la Secci&oacute;n de Adiestramiento</td>
        </tr>
		 <tr>
          <td width="150" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">&Aacute;reas o Contenidos a ser Atendidos</div></td>
	  <td height="22" colspan="4"><textarea name="txtarea" cols="86" rows="4" id="txtarea"  onKeyUp="ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_area?></textarea></td>
	      </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">Objetivo del Adiestramiento</div></td>
	  <td height="22" colspan="4"><textarea name="txtobj" cols="86" rows="4" id="txtobj"  onKeyUp="ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_obj?></textarea></td>
	      </tr>
		   <tr>
		  <td height="22" align="left"><div align="right">Estrategia de Capacitaci&oacute;n</div></td>
	  <td height="22" colspan="4"><textarea name="txtestcap" cols="86" rows="4" id="txtestcap"  onKeyUp="ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_estcap?></textarea></td>
	      </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">Observaci&oacute;n</div></td>
	  <td height="22" colspan="4"><textarea name="txtobs" cols="86" rows="4" id="txtobs"  onKeyUp="ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_obs?></textarea></td>
	      </tr>
		   <tr>
          <td width="150" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		
		<tr>
          <td colspan="6">
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
			    <input name="totalfilas2" type="hidden" id="totalfilas2" value="<?php print $li_totrows2;?>">
              <input name="filadelete" type="hidden" id="filadelete">
              <input name="filadelete2" type="hidden" id="filadelete2">
			</p>			</td>		  
          </tr>
      </table>	 
      <p>&nbsp;</p>
     
 </td> 
</table>

   <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
  <p>
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
	<input name="operacion" type="hidden" id="operacion">
    <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
	<input name="existe2" type="hidden" id="existe2" value="<?php print $ls_existe2;?>">
 
  </p>

</form>


</body>

<script language="javascript">
function ue_imprimir()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codper=f.txtcodper.value;
		nroreg=f.txtnroreg.value;
		codsup=f.txtcodsup.value;
		nomsup=f.txtnomsup.value;
		cargosup=f.txtcodcarsup.value;
		fecha=f.txtfecha.value;
		
		if ((codper!="")&&(codsup!=""))
		{
			pagina="../../../reporte/sigesp_srh_rpp_registro_deteccion_necesidades.php?codper="+codper+"&nroreg="+nroreg+"&codsup="+codsup+"&fecha="+fecha+"&cargosup="+cargosup+"&nomsup="+nomsup+"";
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert ('Debe seleccionar un registro del catalogo para imprimir');
		}		
     }
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}
</script>
</html>


