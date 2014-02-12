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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_evaluacion_ascenso.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		
   		global $ls_nroreg, $ls_fecreg, $ls_codper,$ls_nomper,$ls_caract,$ls_descar, $ls_nroreg, $ls_deneval, $ls_fecha, $li_res,$ls_obs,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre,$ls_codeval;
	 	$ls_nroreg="";
		$ls_codeval="";
		$ls_fecreg="";
		$ls_codper="";
		$ls_nomper="";
		$ls_caract="";
		$ls_descar="";
		$ls_fecha="";
		$ls_nroreg="";
   		$ls_deneval="";
		$li_res=0;
		$ls_obs="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Resultados de Sistema de Méritos para Ascenso (Baremo)";
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
		$aa_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." class=sin-borde size=60  readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtvalor".$ai_totrows." type=text id=txtvalor".$ai_totrows." class=sin-borde maxlength=3 size=6
readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=6   onKeyUp='javascript: ue_validarnumero(this);' onChange='javascript:valida_puntos(this,txtvalor".$ai_totrows."); ue_suma(txtres);' >";
	
   }
   
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_evaluacion_ascenso.js"></script>



</head>

<body>
<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_evaluacion_ascenso.php");
	$io_eval=new sigesp_srh_c_evaluacion_ascenso("../../../../");
	require_once("../../../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "CONSULTAR":
	   	    $ls_nroreg=$_POST["txtnroreg"];
			$ls_fecreg=$_POST["txtfecreg"];
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_caract=$_POST["txtcaract"];
			$ls_descar=$_POST["txtdescar"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_obs=$_POST["txtobs"];
			$li_res=$_POST["txtres"];
			$ls_codeval=$_POST["txtcodeval"];
			$ls_deneval=$_POST["txtdeneval"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_eval->uf_srh_consultar_items($ls_codeval,$li_totrows,$lo_object);
			break;	
			
		case "BUSCARDETALLE":
		    $ls_nroreg=$_POST["txtnroreg"];
			$ls_fecreg=$_POST["txtfecreg"];
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_caract=$_POST["txtcaract"];
			$ls_descar=$_POST["txtdescar"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_obs=$_POST["txtobs"];
			$li_res=$_POST["txtres"];
			$ls_codeval=$_POST["txtcodeval"];
			$ls_deneval=$_POST["txtdeneval"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_eval->uf_srh_load_evaluacion_ascenso_campos($ls_nroreg,$ls_fecha,$li_totrows,$lo_object);
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
<p>
  
</p>
<p>&nbsp;</p>
<form name="form1" method="post" action=""  ><div >
 <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>

<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

<table width="715" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr><td width="715" height="136">
  <table width="662" height="240" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
          <tr class="titulo-nuevo">
		          <td height="20" colspan="2">Registro de Resultados de Evaluaci&oacute;n para Ascenso</td>
        </tr>
		 <tr class="titulo-celda">
		          <td height="22" colspan="11">Informaci&oacute;n del Registro de Postulaci&oacute;n para Ascenso</td>
        <tr>
		<tr>
          <td width="81"   height="22">&nbsp;</td>
          
          <td height="22" colspan="2">&nbsp;</td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Nro. Registro</div></td>
          <td height="22"  ><input name="txtnroreg" type="text" id="txtnroreg" value="<? print $ls_nroreg?>" maxlength="10" size="16"  style="text-align:center"     readonly >  <a href="javascript:catalogo_registro_ascenso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Ascenso" name="buscartip" width="15" height="15" border="0" id="buscartip"> Buscar Registro de Ascenso</a> </td>
		</tr>
	
		<tr>
		 <td height="22"><div align="right">Fecha Registro</div></td>
          <td height="22" colspan="2"><input name="txtfecreg" type="text" id="txtfecreg"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecreg?>">            </td>
	    </tr>
		 <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22" ><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codper?>" maxlength="10" size="16"  style="text-align:center"     readonly > </td>
        </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" ><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomper?>" maxlength="40" style="text-align:justify" size="45" readonly >               </td>
		  </tr>
				      
		
		  <tr>
             <td height="22"><div align="right">Cargo Actual </div></td>
          <td width="577" height="22"  ><input name="txtcaract" type="text" id="txtcaract" value="<?php print $ls_caract ?>" size="35" readonly style="text-align:justify" >
           </td>
        <tr>
             <td height="22"><div align="right">Cargo Ascenso</div></td>
          <td width="577" height="22"  ><input name="txtdescar" type="text" id="txtdescar" value="<?php print $ls_descar ?>" size="35" readonly style="text-align:justify" >
          </td>
	  
	   <tr>
          <td height="22" colspan="2">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="2">Datos de la Evaluaci&oacute;n</td>
        </tr>
		<tr>
          <td height="22">&nbsp;</td>
          <td height="22" colspan="2">&nbsp;</td>
        </tr> 
			<tr>
      <td height="28"><div align="right"> Tipo de Evaluaci&oacute;n</div></td>
      <td height="28"  colspan="6"><input name="txtcodeval" type="text" id="txtcodeval" value="<?php print $ls_codeval ?>" size="16" maxlength="15"  style="text-align:center" readonly>
          <a href="javascript: catalogo_evaluacion();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Items Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
          <input name="txtdeneval" type="text" class="sin-borde" id="txtdeneval" value="<?php print $ls_deneval ?>" size="50
          " maxlength="80" readonly>
      </td>
    </tr>
		  <tr>
      <td height="28"><div align="right"></td>
	  
	  
      <td height="28"  ><div align="center"><a href="javascript:consultar_items();"> <img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Items de Evaluaci&oacute;n</a></div> 
      </td>
    </tr>
		 <tr>
          <td height="22"><div align="right">Fecha Evaluaci&oacute;n</div></td>
          <td height="22" >   <input name="txtfecha" type="text" id="txtfecha" value="<? print $ls_fecha?>" size="16" style="text-align:center" readonly > <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />		   </td>
        </tr>
		  <tr>
             <td height="22"><div align="right">Resultado</div></td>
          <td width="577" height="22"  valign="middle"><input name="txtres" type="text" id="txtres" value="<?php print $li_res ?>" size="5" maxlength="5" style="text-align:center" readonly>
          </td>
       </tr>
		 <tr>
		
		 <tr>
          <td height="22" align="left"><div align="right">Observaci&oacute;n </div></td>
          <td height="22" ><textarea name="txtobs" cols="86" rows="5" id="txtobs" onKeyUp="ue_validarcomillas(this);" style="text-align:justify"  ><?php print $ls_obs?></textarea></td>
        </tr> 
	  <tr>
		 <td ></td>
	  </tr>
	 	<tr >
         <div align="right"></div>
          <td td height="22">
		    <input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
	 <tr>
          <td  colspan="2">
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
     
</table>
  <p>
   
     <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
	    
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
 <input name="hidstatus" type="hidden" id="hidstatus">
     <input name="hidcontrol" type="hidden" id="hidcontrol" value="2">
	  <input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
  </p>


</form>


</body>


</html>





<body>
</body>
</html>
