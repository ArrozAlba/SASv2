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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_evaluacion_eficiencia.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_nroeval, $ls_codper,$ls_nomper,$ls_carper, $ls_accion,  $ls_codtipper, $ls_dentipper, $ls_ubicar, $ls_fecha, $ls_fecini,$ls_fecfin,$ls_codeva,$ls_nomeva,$ls_codcareva, $ls_codeval, $ls_codcarper, $ls_dencarper, $ls_deneval, $li_total, $ls_ranact,  $ls_obs, $ls_comsup,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codper="";
		$ls_nomper="";
		$ls_carper="";
		$ls_nroeval="";
		$ls_fecha="";
		$ls_codeval="";
   		$ls_deneval="";
		$ls_accion="";
		$ls_fecini="";
		$ls_fecfin="";
		$ls_codcarper="";
		$ls_dencarper="";
		$ls_dentipper="";
		$ls_codtipper="";
		$ls_ubicar="";
		$ls_codeva="";
		$ls_nomeva="";
		$ls_codcareva="";
		$ls_obs="";
		$li_total=0;
		$ls_ranact="";
		$ls_comsup="";
		$ls_activarcodigo="";
		$ls_titletable="Factores de Evaluación de Eficiencia";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Denominación";
		$lo_title[3]="Seleccione";
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
	    $aa_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=13  readonly  >";
		$aa_object[$ai_totrows][2]="<textarea name=txtdenite".$ai_totrows."    cols=80 rows=3 id=txtdenite".$ai_totrows."  class=sin-borde> </textarea>";		
		$aa_object[$ai_totrows][3]= "<input name=rdselec".$ai_totrows." type=radio class='sin-borde' style='display:none' >";
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_evaluacion_eficiencia.js"></script>

<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>


</head>

<body onLoad="javascript: ue_nuevo_codigo();">

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_evaluacion_eficiencia.php");
	$io_obj=new sigesp_srh_c_evaluacion_eficiencia("../../../../");
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
	   	    $ls_nroeval=$_POST["txtnroeval"];
		    $ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_fecini=$_POST["txtfecini1"];
			$ls_fecfin=$_POST["txtfecfin1"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_codcareva=$_POST["txtcodcareva"];
			$ls_obs=$_POST["txtobs"];
			$ls_codcarper=$_POST["txtcodcarper"];
			$ls_accion=$_POST["txtaccion"];
			$ls_comsup=$_POST["txtcomsup"];
			$li_total=$_POST["txttotal"];
			$ls_codeval=$_POST["txtcodeval"];
    		$ls_deneval=$_POST["txtdeneval"];
			$ls_ranact=$_POST["txtranact"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_obj->uf_srh_consultar_items($ls_codeval,$li_totrows,$lo_object);
			break;	
			
		case "BUSCARDETALLE":
		    $ls_nroeval=$_POST["txtnroeval"];
		    $ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_fecini=$_POST["txtfecini1"];
			$ls_fecfin=$_POST["txtfecfin1"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_codcareva=$_POST["txtcodcareva"];
			$ls_obs=$_POST["txtobs"];
			$ls_codcarper=$_POST["txtcodcarper"];
			$ls_accion=$_POST["txtaccion"];
			$ls_comsup=$_POST["txtcomsup"];
			$li_total=$_POST["txttotal"];
			$ls_codeval=$_POST["txtcodeval"];
    		$ls_deneval=$_POST["txtdeneval"];
			$ls_ranact=$_POST["txtranact"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_obj->uf_srh_load_evaluacion_eficiencia($ls_nroeval,$li_totrows,$lo_object);
			break;
			
			
	}
	
	unset($io_obj);
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
	<td class="toolbar" width="24"><div align="center"><a href="javascript: ue_print();"><img src="../../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
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
      <table width="681" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="859" height="136"><p>
      <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    </p>
      <p>&nbsp;</p>
      <table width="655" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="10">Evaluaci&oacute;n de Eficiencia</td>
        </tr>
		<tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		<tr>
		  <td height="22" align="left"><div align="right">Nro. Evaluaci&oacute;n</div></td>
          <td height="22" colspan="6"><input name="txtnroeval" type="text" id="txtnroeval" value="<? print $ls_nroeval?>" maxlength="15" style="text-align:center" size="15"  readonly>               </td>
		  </tr>
		  
		  
		    <tr> 
 <td height="28"><div align="right"> Tipo de Evaluaci&oacute;n</div></td>
  <td height="28" valign="middle" colspan="4"><input name="txtcodeval" type="text" id="txtcodeval" value="<?php print $ls_codeval ?>" size="16" maxlength="15"  style="text-align:center" readonly>
          <a href="javascript: catalogo_evaluacion();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo  Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> 
           <input name="txtdeneval" type="text" class="sin-borde" id="txtdeneval" value="<?php print $ls_deneval ?>" size="50
          " maxlength="80" readonly>          </td>
         <td colspan="3">&nbsp;</td>
    </tr>
		   <tr>
      <td height="28"><div align="right"></div></td>
	  <td width="237" height="28"><div align="right"></div></td>
      <td height="28"  colspan="6"><a href="javascript:consultar_items();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Items de Evaluaci&oacute;n</a> 
      </td>
    </tr>
		  
		  <tr>
		 <td height="22"><div align="right">Fecha Evaluaci&oacute;n</div></td>
          <td height="22" colspan="5"><input name="txtfecha" type="text" id="txtfecha" size="16"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		
		<tr>
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		
			   <tr class="titulo-nuevo">
		          <td height="22" colspan="10">Per&iacute;odo de Evaluaci&oacute;n</td>
        </tr>
		<tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		<tr>
		 <td height="22"><div align="right">Del</div></td>
          <td height="22" colspan="2"><input name="txtfecini1" type="text" id="txtfecini1"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecini?>">    <input name="reset" type="reset" onClick="return showCalendar('txtfecini1', '%d/%m/%Y');" value=" ... " />         </td> 			
          <td width="40"  height="22"><div align="right">Al</div></td>
          <td width="216"><input name="txtfecfin1" type="text" id="txtfecfin1"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecfin?>">        <input name="reset" type="reset" onClick="return showCalendar('txtfecfin1', '%d/%m/%Y');" value=" ... " />    </td>	   
	    </tr>
		
		<tr>
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="18" colspan="10">Datos del Evaluado</td>
        </tr>
        <tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
       <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td width="237" height="22" ><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codper?>" maxlength="10" size="16"  style="text-align:center" readonly>  <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
        </tr>
		
	
		
		 <tr>
			  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="6"><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomper?>"  style="text-align:justify" size="45" readonly >               </td>
		  </tr>
		  
		 
		  
		  <tr>
			  <td height="22" align="left"><div align="right">Cargo</div></td>
          <td height="22" colspan="6"><input name="txtcodcarper" type="text" id="txtcodcarper" value="<? print $ls_codcarper?>"  style="text-align:justify" size="45" readonly >
            </td>
		  </tr>	
		 
		  <tr>
         
          <td height="22" colspan="5">&nbsp;</td>
        </tr>	
		<tr>
          <td><div align="right"></div></td>
          <td width="237"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Datos del Evaluador</td>
        </tr>
        <tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="4">&nbsp;</td>
        <tr>
			  <td height="22" align="left"><div align="right">C&oacute;digo Evaluador</div></td>
          <td height="22" colspan="2"><input name="txtcodeva" type="text" id="txtcodeva"  maxlength="10" style="text-align:center"    readonly value="<? print $ls_codeva?>" >   <a href="javascript:catalogo_evaluador();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
		  </tr>
		 		       
        <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="2"><input name="txtnomeva" type="text" id="txtnomeva"   style="text-align:justify" size="50" readonly value="<? print $ls_nomeva?>">           </td>
        </tr>
         <tr>
             <td height="22"><div align="right">Cargo </div></td>
          <td height="22" colspan="5" valign="middle"><input name="txtcodcareva" type="text" id="txtcodcareva"  size="50" value="<? print $ls_codcareva?>" readonly>
            </a></td>
        </tr>
          <td height="22" colspan="4">&nbsp;</td>
        </tr>
		
		
        <tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		 <tr>
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
		
		  <tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Calificaci&oacute;n Final</td>
        </tr>
		 <tr>
          <td width="85" height="22">&nbsp;</td>
         <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
		 <td height="22" align="left"><div align="right">Total Evaluaci&oacute;n</div></td>
	       <td height="22"><input name="txttotal" type="text" id="txttotal" maxlength=3 size="7" style="text-align:center"  onKeyUp="javascript: ue_validarnumero(this);"  value="<?php print $li_total?>"  readonly></td>
	      <td width="61" height="22" ><div align="right">Rango de Actuaci&oacute;n</div></td>
	      <td height="22" colspan="2"><input name="txtranact" type="text" id="txtranact" size="40" style="text-align:justify"    value="<?php print $ls_ranact?>"   onFocus="javascript: consultar_rango_actuacion ();"  readonly></td>
	      <td width="4" height="22">&nbsp;</td>
		  </tr>
		
		  <tr>
          <td height="22" align="left"><div align="right">Comentario del Supervisor</div></td>
          <td height="22" colspan="4"><textarea name="txtcomsup" cols="86" rows="6" id="txtcomsup" style="text-align:justify"  ><?php print $ls_comsup?></textarea></td>
        </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Aspectos a Mejorar </div></td>
          <td height="22" colspan="4"><textarea name="txtobs" cols="86" rows="4" id="txtobs" style="text-align:justify"  ><?php print $ls_obs?></textarea></td>
        </tr>
		<tr>
          <td height="22" align="left"><div align="right">Acciones  </div></td>
          <td height="22" colspan="4"><textarea name="txtaccion" cols="86" rows="4" id="txtaccion" style="text-align:justify"  ><?php print $ls_accion?></textarea></td>
        </tr>
		
		<tr>
          <td width="85" height="22">&nbsp;
            <input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly></td>
         <td height="22" colspan="5">&nbsp;</td>
        </tr>
      </table>	 
      <p>&nbsp;</p>
     
 </td> 
</table>
	    
 <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
  <p>
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="2">
	<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
	 <input name="hidstatus" type="hidden" id="hidstatus">
 
  </p>


</form>


</body>

<script>

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		
		nroeval= f.txtnroeval.value;
		fecini = f.txtfecini1.value;
		fecfin = f.txtfecfin1.value;
		titulo = f.txtdeneval.value;
		
			pagina="../../../reporte/sigesp_srh_rpp_registro_evaluacion_eficiencia.php?nroeval="+nroeval+"&fecini="+fecini+"&fecfin="+fecfin+"&titulo="+titulo;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		
		
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaciÃƒÂ³n");
   	}		
}


</script>

</html>


