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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_solicitud_adiestramiento.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_nroreg,$ls_fecsol, $ls_des,$ls_codsol, $ls_nomsol,$ls_codunivi, $ls_denunivi, $ls_codprov, $ls_denprov,$ls_fecini, $ls_fecfin, $ls_durhras, $ls_costo, $ls_obs, $ls_obj, $ls_est, $ls_are,$ls_activarcodigo,$ls_guardar,$ls_operacion,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_nroreg="";
		$ls_fecsol="";
		$ls_des="";
		$ls_codsol="";
		$ls_nomsol="";
		$ls_codunivi="";
		$ls_denunivi="";
		$ls_codprov="";
		$ls_denprov="";
		$ls_fecini="";
		$ls_fecfin="";
		$ls_durhras="";
		$ls_costo="";
		$ls_obs="";
		$ls_est="";
		$ls_are="";
		$ls_obj="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Personal a Registrar para el Adiestramiento";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Nombre y Apellido";
		$lo_title[3]="Cargo";
		$lo_title[4]="Departamento";
		$lo_title[5]="Buscar";
		$lo_title[6]="Agregar";
		$lo_title[7]="Eliminar";
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
		$aa_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows." class=sin-borde size=15  readonly  maxlength=10>";
		$aa_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde size=35 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde  size=20>";
		$aa_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  size=30>";
		$aa_object[$ai_totrows][5]="<a href=javascript:catalogo_personal(".$ai_totrows.");   align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";	
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
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
   		global $ls_codper,$ls_nomper,$ls_carper, $ls_dep;

		$ls_codper=$_POST["txtcodper".$li_i];
		$ls_nomper=$_POST["txtnomper".$li_i];
	    $ls_carper=$_POST["txtcarper".$li_i];
		$ls_dep=$_POST["txtdep".$li_i];
			
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_solicitud_adiestramiento.js"></script>



</head>

<body onLoad="javascript:ue_nuevo_codigo();">
<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_solicitud_adiestramiento.php");
	$io_eval=new sigesp_srh_c_solicitud_adiestramiento("../../../../");
	require_once("../../../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "AGREGARDETALLE":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecsol=$_POST["txtfecsol"];
			$ls_des=$_POST["txtdes"];
			$ls_codsol=$_POST["txtcodper"];
			$ls_nomsol=$_POST["txtnomper"];
			$ls_codunivi=$_POST["txtcodunivi"];
			$ls_denunivi=$_POST["txtdenunivi"];
			$ls_codprov=$_POST["txtcodprov"];
			$ls_denprov=$_POST["txtdenprov"];
			$ls_fecini=$_POST["txtfecini"];
			$ls_fecfin=$_POST["txtfecfin"];
			$ls_durhras=$_POST["txtdurhras"];
			$ls_costo=$_POST["txtcosto"];
			$ls_obs=$_POST["txtobs"];
			$ls_est=$_POST["txtest"];
			$ls_are=$_POST["txtare"];
			$ls_obj=$_POST["txtobj"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				
				$lo_object[$li_i][1]="<input name=txtcodper".$li_i." type=text id=txtcodper".$li_i." class=sin-borde size=15  maxlength=10 readonly value='".$ls_codper."' >";
				$lo_object[$li_i][2]="<input name=txtnomper".$li_i." type=text id=txtnomper".$li_i." class=sin-borde size=35 value='".$ls_nomper."' readonly>";
				$lo_object[$li_i][3]="<input name=txtcarper".$li_i." type=text id=txtcarper".$li_i." class=sin-borde  size=20 value='".$ls_carper."'>";
				$lo_object[$li_i][4]="<input name=txtdep".$li_i." type=text id=txtdep".$li_i." class=sin-borde  size=30 value='".$ls_dep."'>";
				$lo_object[$li_i][5]="<a href=javascript:catalogo_personal(".$li_i.");   align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecsol=$_POST["txtfecsol"];
			$ls_des=$_POST["txtdes"];
			$ls_codsol=$_POST["txtcodper"];
			$ls_nomsol=$_POST["txtnomper"];
			$ls_codunivi=$_POST["txtcodunivi"];
			$ls_denunivi=$_POST["txtdenunivi"];
			$ls_codprov=$_POST["txtcodprov"];
			$ls_denprov=$_POST["txtdenprov"];
			$ls_fecini=$_POST["txtfecini"];
			$ls_fecfin=$_POST["txtfecfin"];
			$ls_durhras=$_POST["txtdurhras"];
			$ls_costo=$_POST["txtcosto"];
			$ls_obs=$_POST["txtobs"];
			$ls_est=$_POST["txtest"];
			$ls_are=$_POST["txtare"];
			$ls_obj=$_POST["txtobj"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=($li_rowdelete))
				{		
					$li_temp++;			
					uf_cargar_dt($li_i);
				    $lo_object[$li_temp][1]="<input name=txtcodper".$li_temp." type=text id=txtcodper".$li_temp." class=sin-borde size=15 maxlength=10 readonly value='".$ls_codper."'>";
					$lo_object[$li_temp][2]="<input name=txtnomper".$li_temp." type=text id=txtnomper".$li_temp." class=sin-borde size=35 readonly value='".$ls_nomper."'>";
				    $lo_object[$li_temp][3]="<input name=txtcarper".$li_temp." type=text id=txtcarper".$li_temp." class=sin-borde size=20 value='".$ls_carper."'>";
				    $lo_object[$li_temp][4]="<input name=txtdep".$li_temp." type=text id=txtdep".$li_temp." class=sin-borde  size=30 value='".$ls_dep."'>";
					$lo_object[$li_temp][5]="<a href=javascript:catalogo_personal(".$li_temp.");   align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";
				    $lo_object[$li_temp][6]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
		            $lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";					
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecsol=$_POST["txtfecsol"];
			$ls_des=$_POST["txtdes"];
			$ls_codsol=$_POST["txtcodper"];
			$ls_nomsol=$_POST["txtnomper"];
			$ls_codunivi=$_POST["txtcodunivi"];
			$ls_denunivi=$_POST["txtdenunivi"];
			$ls_codprov=$_POST["txtcodprov"];
			$ls_denprov=$_POST["txtdenprov"];
			$ls_fecini=$_POST["txtfecini"];
			$ls_fecfin=$_POST["txtfecfin"];
			$ls_durhras=$_POST["txtdurhras"];
			$ls_costo=$_POST["txtcosto"];
			$ls_obs=$_POST["txtobs"];
			$ls_est=$_POST["txtest"];
			$ls_are=$_POST["txtare"];
			$ls_obj=$_POST["txtobj"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_eval->uf_srh_load_solicitud_adiestramiento_campos($ls_nroreg,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}
	
	unset($io_eval);
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
      <table width="609" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="4">Registro de Adiestramiento</td>
        </tr>
        <tr>
          <td width="91" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Solicitud</div></td>
          <td height="22" colspan="4"><input name="txtnroreg" type="text" id="txtnroreg" maxlength="15" style="text-align:center" value="<? print $ls_nroreg?>" readonly size="16">      <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		   <tr>
		 <td height="22"><div align="right">Fecha  Solicitud</div></td>
          <td height="22" colspan="3"><input name="txtfecsol" type="text" id="txtfecsol"  maxlength="15" size="16" style="text-align:center" datepicker="true" readonly value="<? print $ls_fecsol?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecsol', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		<tr>
          <td height="22" align="left"><div align="right">Descripci&oacute;n del Adiestramiento</div></td>
          <td height="22" colspan="4"><textarea name="txtdes" cols="70" rows="2" id="txtdes" onKeyUp="ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_des?></textarea></td>
        </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Solicitante</div></td>
          <td width="143" height="22" ><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codsol?>"  size="16"  style="text-align:center"     readonly >  <a href="javascript:catalogo_solicitante();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
           <td height="22" colspan="4" valign="middle"><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomsol?>" maxlength="40" style="text-align:justify" size="47" readonly  class="sin-borde"> </td>
        </tr>
		 <tr>
		  <td height="22" align="left"><div align="right">Unidad VIPLADIN</div></td>
          <td width="143" height="22" ><input name="txtcodunivi" type="text" id="txtcodunivi" value="<? print $ls_codunivi?>" size="16"  style="text-align:center"    readonly >  <a href="javascript:catalogo_unidad();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Unidad Administrativa" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
           <td height="22" colspan="4" valign="middle"><input name="txtdenunivi" type="text" id="txtdenunivi" value="<? print $ls_denunivi?>" maxlength="40" style="text-align:justify" size="47" readonly  class="sin-borde"> </td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Proveedor</div></td>
          <td width="143" height="22" ><input name="txtcodprov" type="text" id="txtcodprov" value="<? print $ls_codprov?>" maxlength="10" size="16"  style="text-align:center"     onKeyUp="javascript: ue_validarnumero(this);" >  <a href="javascript:catalogo_proveedor();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Proveedor" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
           <td height="22" colspan="4" valign="middle"><input name="txtdenprov" type="text" id="txtdenprov" value="<? print $ls_denprov?>" maxlength="40" style="text-align:justify" size="47" readonly  class="sin-borde"> </td>
        </tr>
		 
		<tr>
          <td width="91" height="22">&nbsp;</td>
          <td height="22" >&nbsp;</td>
        </tr>
		<tr class="titulo-nuevo">
          <td height="22" colspan="4">Per&iacute;odo de Adiestramieno</td>
        </tr>
        <tr>
          <td width="91" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
		 <td height="22"><div align="right">Inicio</div></td>
          <td height="22" width="143"><input name="txtfecini" type="text" id="txtfecini"  maxlength="15" size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecini?>">
            <input name="reset2" type="reset" onClick="return showCalendar('txtfecini', '%d/%m/%Y');" value=" ... " /></td> 			
        
          <td width="59"  height="22"><div align="right">            Fin</div></td>
          <td width="308"><input name="txtfecfin" type="text" id="txtfecfin"  maxlength="15"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecfin?>">       <input name="reset" type="reset" onClick="return showCalendar('txtfecfin', '%d/%m/%Y');" value=" ... " />        </td>
		  </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Duración en horas</div></td>
          <td height="22" colspan="4"><input name="txtdurhras" type="text" id="txtdurhras" size="5" maxlength="5" style="text-align:justify" value="<? print $ls_durhras?>" onKeyUp="javascript: ue_validarnumero(this);">     
		  </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Costo</div></td>
          <td height="22" colspan="4"><input name="txtcosto" type="text" id="txtcosto" maxlength="15" style="text-align:justify" value="<? print $ls_costo?>" onKeyPress="return(ue_formatonumero(this,'.', ',',event));" >     
	      </tr>
		  <tr>
		  <td width="91" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr class="titulo-nuevo">
          <td height="22" colspan="4">Detalles del Adiestramieno</td>
        </tr>
        <tr>
          <td width="91" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
		  </tr>
		<!--  <tr>
          <td height="22" align="left"><div align="right">Estado Adiestramiento</div></td>
          <td height="22" bordercolor="0"><select name="cmbstatus" id="cmbstatus">
              <option value="null">--Seleccione--</option>
              <option value="Registrado"    >Registrado</option>
			  <option value="En Ejecución"  >En Ejecución</option>
              <option value="Concluido" >Concluido</option>
			  <option value="Cancelado" >Cancelado</option>
            </select>          </td>
          
        </tr> -->
		   <tr>
		  <td height="22" align="left"><div align="right">&Aacute;reas o Contenidos a ser atendidos</div></td>
	  <td height="22" colspan="4"><textarea name="txtare" cols="70" rows="3" id="txtare" onKeyUp="ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_are?></textarea>            </td>
		  </tr>
		   <tr>
		  <td height="22" align="left"><div align="right">Objetivos del Adiestramiento</div></td>
	  <td height="22" colspan="4"><textarea name="txtobj" cols="70" rows="3" id="txtobj" onKeyUp="ue_validarcomillas(this);" style="text-align:justify"><?php print $ls_obj?></textarea>            </td>
		  </tr>
		    <tr>
		  <td height="22" align="left"><div align="right">Estrategia de Capacitaci&oacute;n</div></td>
	  <td height="22" colspan="4"><textarea name="txtest" cols="70" rows="2" onKeyUp="ue_validarcomillas(this);" id="txtest" style="text-align:justify"><?php print $ls_est?></textarea>            </td>
		  </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">Observaci&oacute;n</div></td>
	  <td height="22" colspan="4"><textarea name="txtobs" cols="70" rows="3" onKeyUp="ue_validarcomillas(this);" id="txtobs" style="text-align:justify"><?php print $ls_obs?></textarea>            </td>
	      </tr>
		 
		<tr>
          <td height="22" >&nbsp;</td>
        </tr>
		<tr >
          
          <td>
		   <input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
		<tr>
          <td colspan="6">
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
		
		
		
      </table>	 
      <p>&nbsp;</p>
     
 </td> 
</table>
	    
   <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
  <p>
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">

	<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="">
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">

  </p>

</form>


</body>


</html>


