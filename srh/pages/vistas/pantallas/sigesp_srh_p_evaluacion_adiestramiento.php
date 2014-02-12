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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_evaluacion_adiestramiento.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_nroreg,$ls_fecsol, $ls_des,$ls_codsol, $ls_nomsol,$ls_uniad, $ls_denuniad, $ls_codprov, $ls_denprov,$ls_fecini, $ls_fecfin, $ls_durhras, $ls_costo, $ls_obs, $ls_obseval, $ls_fecha,$ls_activarcodigo,$ls_guardar,$ls_operacion,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_nroreg="";
		$ls_fecsol="";
		$ls_des="";
		$ls_codsol="";
		$ls_nomsol="";
		$ls_uniad="";
		$ls_denuniad="";
		$ls_codprov="";
		$ls_denprov="";
		$ls_fecini="";
		$ls_fecfin="";
		$ls_durhras="";
		$ls_costo="";
		$ls_obs="";
		$ls_fecha="";
		$ls_obseval="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Personal a Evaluar Adiestramiento";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Nombre y Apellido";
		$lo_title[3]="Cargo";
		$lo_title[4]="Departamento";
		$lo_title[5]="Asistencia";
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
		$aa_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows." class=sin-borde size=15 readonly  maxlength=10>";
		$aa_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde size=35 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde  size=20 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  size=30 readonly>";
		$aa_object[$ai_totrows][5]="<input type=checkbox name=asistencia".$ai_totrows." id=asistencia".$ai_totrows." value='si'>";
		
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_evaluacion_adiestramiento.js"></script>



</head>

<body>
<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_evaluacion_adiestramiento.php");
	$io_eval=new sigesp_srh_c_evaluacion_adiestramiento("../../../../");
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
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecsol=$_POST["txtfecsol"];
			$ls_des=$_POST["txtdes"];
			$ls_codsol=$_POST["txtcodper"];
			$ls_nomsol=$_POST["txtnomper"];
			$ls_denuniad=$_POST["txtdenuniad"];
			$ls_codprov=$_POST["txtcodprov"];
			$ls_denprov=$_POST["txtdenprov"];
			$ls_fecini=$_POST["txtfecfin"];
			$ls_fecfin=$_POST["txtfecini"];
			$ls_durhras=$_POST["txtdurhras"];
			$ls_costo=$_POST["txtcosto"];
			$ls_obs=$_POST["txtobs"];
			$ls_est=$_POST["txtest"];
			$ls_are=$_POST["txtare"];
			$ls_obj=$_POST["txtobj"];
			$ls_obseval=$_POST["txtobseval"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_eval->uf_srh_load_evaluacion_adiestramiento_campos($ls_nroreg, $ls_fecha,$li_totrows,$lo_object);
			break;

	
	 case "CONSULTAR":
		 	$ls_nroreg=$_POST["txtnroreg"];
			$ls_fecsol=$_POST["txtfecsol"];
			$ls_des=$_POST["txtdes"];
			$ls_codsol=$_POST["txtcodper"];
			$ls_nomsol=$_POST["txtnomper"];
			$ls_denuniad=$_POST["txtdenuniad"];
			$ls_codprov=$_POST["txtcodprov"];
			$ls_denprov=$_POST["txtdenprov"];
			$ls_fecini=$_POST["txtfecfin"];
			$ls_fecfin=$_POST["txtfecini"];
			$ls_durhras=$_POST["txtdurhras"];
			$ls_costo=$_POST["txtcosto"];
			$ls_obs=$_POST["txtobs"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_eval->uf_srh_consultar_evaluacion_adiestramiento($ls_nroreg,$li_totrows,$lo_object);
			
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
      <table width="673" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="9">Evaluaci&oacute;n de Adiestramiento</td>
        </tr>
		 <tr class="titulo-celda">
		          <td height="22" colspan="11">Informaci&oacute;n del Registro de Adiestramiento</td>
        <tr>
        <tr>
          <td width="116" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Solicitud</div></td>
          <td height="22" colspan="4"><input name="txtnroreg" type="text" id="txtnroreg" size="16" style="text-align:center" value="<? print $ls_nroreg?>" onKeyUp="javascript: ue_validarnumero(this);" readonly>      <input name="hidstatus" type="hidden" id="hidstatus"> <a href="javascript:catalogo_adiestramiento();"> <img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Registro de Adiestramiento</a> </td>
	      </tr>
		   <tr>
		 <td height="22"><div align="right">Fecha  Solicitud</div></td>
          <td height="22" colspan="3"><input name="txtfecsol" type="text" id="txtfecsol"  size="16" style="text-align:center" datepicker="true" readonly value="<? print $ls_fecsol?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecsol', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		<tr>
          <td height="22" align="left"><div align="right">Descripci&oacute;n del Adiestramiento</div></td>
          <td height="22" colspan="4"><textarea name="txtdes" cols="86" rows="2" id="txtdes" style="text-align:justify"  readonly><?php print $ls_des?></textarea></td>
        </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Solicitante</div></td>
          <td width="118" height="22" ><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codsol?>" maxlength="10" size="16"  style="text-align:center"  readonly   onKeyUp="javascript: ue_validarnumero(this);" >  </td>
           <td height="22" colspan="4" valign="middle"><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomsol?>" maxlength="40" style="text-align:justify" size="47" readonly  class="sin-borde"> </td>
        </tr>
		 <tr>
		  <td height="22" align="left"><div align="right">Unidad Solicitante</div></td>
          <td  colspan="4" height="22" ><input name="txtdenuniad" type="text" id="txtdenuniad" value="<? print $ls_denuniad?>" maxlength="40" style="text-align:justify" size="47" readonly> </td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Proveedor</div></td>
          <td width="118" height="22" ><input name="txtcodprov" type="text" id="txtcodprov" value="<? print $ls_codprov?>" maxlength="10" size="16"  style="text-align:center"  readonly   > </td>
           <td height="22" colspan="4" valign="middle"><input name="txtdenprov" type="text" id="txtdenprov" value="<? print $ls_denprov?>" maxlength="40" style="text-align:justify" size="47" readonly  class="sin-borde"> </td>
        </tr>
		<tr>
		 <td height="22"><div align="right">Del</div></td>
          <td height="22" colspan="2"><input name="txtfecini" type="text" id="txtfecini"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecini?>">
           			
        
          <td width="134"  height="22"><div align="right">Al</div></td>
          <td width="278"><input name="txtfecfin" type="text" id="txtfecfin"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecfin?>">         </td>
		  </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Duración en horas</div></td>
          <td height="22" colspan="5"><input name="txtdurhras" type="text" id="txtdurhras" size="5" maxlength="5" style="text-align:justify" value="<? print $ls_durhras?>" readonly >     
		  </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Costo</div></td>
          <td height="22" colspan="5"><input name="txtcosto" type="text" id="txtcosto" size="16" style="text-align:justify" value="<? print $ls_costo?>" readonly>     
	      </tr>
		   <tr>
		  <td height="22" align="left"><div align="right">Observaci&oacute;n</div></td>
	  <td height="22" colspan="5"><textarea name="txtobs" cols="86" rows="3" id="txtobs"  readonly style="text-align:justify"><?php print $ls_obs?></textarea>            </td>
	      </tr>
		  <tr>
		<td height="22"><div align="right"></div></td>
		<td width="118" height="22"><div align="right"></div></td>
        <td width="10" height="22">&nbsp;</td>
	  	
	  	<td height="22"><div align="right"><a href="javascript: Limpiar_Datos();"><img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar Datos </a></div></td>
	    <td width="278" height="22"><a href="javascript: Consultar();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Datos</a></td>
	    <td width="5" colspan="2">&nbsp;</td>
		</tr>
		  <tr>
		  <td width="116" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr class="titulo-nuevo">
          <td height="22" colspan="9">Detalles de la Evaluaci&oacute;n de Adiestramiento</td>
        </tr>
        <tr>
          <td width="116" height="22">&nbsp;</td>
          <td height="22" colspan="5">&nbsp;</td>
		  </tr>
		  <tr>
		 <td height="22"><div align="right">Fecha Evaluaci&oacute;n</div></td>
          <td height="22" colspan="5"><input name="txtfecha" type="text" id="txtfecha" size="16"  style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		
		  <tr>
		  <td height="22" align="left"><div align="right">Observaci&oacute;n Evaluaci&oacute;n</div></td>
	  <td height="22" colspan="5"><textarea name="txtobseval" cols="86" rows="5" id="txtobseval"  style="text-align:justify"><?php print $ls_obseval?></textarea>            </td>
	      </tr>
		 
		<tr>
          <td height="22" >&nbsp;</td>
        </tr>
		<tr >
          <td><div align="right"></div></td>
          <td>
		   <input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
		<tr>
          <td colspan="8">
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
	<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
		    
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">

  </p>

</form>


</body>


</html>


