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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_revisiones_odi.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_codper,$ls_nomper,$ls_carper, $ls_fecha, $ls_fecini, $ls_fecfin, $ls_nroreg, $ls_careva, $ls_nomeva, $ls_rev, $ls_obj, $ls_codeva,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codper="";
		$ls_nomper="";
		$ls_carper="";
		$ls_fecha="";
		$ls_fecini="";
		$ls_rev="";
		$ls_fecfin="";
		$ls_nroreg="";
		$ls_codeva="";
		$ls_nomeva="";
		$ls_careva="";
		$ls_obj="";
		$ls_activarcodigo="";
		$ls_titletable="Revisión de Objetivos de Desempeño Individual (O.D.I)";
		$li_widthtable=647;
		$ls_nametable="grid";
		$lo_title[1]="Objetivo de Desempeño Individual";
		$lo_title[2]="Peso";
		$lo_title[3]="Estado del Objetivo";
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
		$aa_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=47 rows=3 id=txtodi".$ai_totrows." class=sin-borde readonly></textarea>";
		$aa_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde readonly > </textarea>";
		$aa_object[$ai_totrows][3]="<select name=cmbobs".$ai_totrows." id=cmbobs".$ai_totrows.">
              <option value='' selected>--Seleccione--</option>
              <option value='1' >En Proceso</option>
              <option value='2' >Alcanzado</option>
              <option value='3' >No Alcanzado</option>
            </select>";
	;			
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_revisiones_odi.js"></script>

<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 475; 
		}
		if(window.event.keyCode == 475){ return false;} 
		} 
	}
</script>


</head>

<body >

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_revisiones_odi.php");
	$io_rev=new sigesp_srh_c_revisiones_odi("../../../../");
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
		 	$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_fecini=$_POST["txtfecini1"];
			$ls_carper=$_POST["txtcodcarper"];
			$ls_rev=$_POST["txtrev"];
			$ls_fecfin=$_POST["txtfecfin1"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_careva=$_POST["txtcareva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_rev-> uf_srh_load_revisiones_odi_campos($ls_nroreg,$ls_fecha,$ls_rev,$li_totrows,$lo_object);
			break;
			
	 case "CONSULTAR":
	   	    $ls_nroreg=$_POST["txtnroreg"];
		 	$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_carper=$_POST["txtcodcarper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_fecini=$_POST["txtfecini1"];
			$ls_rev=$_POST["txtrev"];
			$ls_fecfin=$_POST["txtfecfin1"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_careva=$_POST["txtcareva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_rev->uf_srh_consultar_revisiones_odi($ls_nroreg,$ls_fecha,$li_totrows,$lo_object,$ls_fecini,$ls_fecfin,$ls_rev,$ls_codper,$ls_nomper, $ls_carper, $ls_codeva, $ls_nomeva, $ls_careva);
			/*if ($lb_valido) {
			  $lb_chequear=$io_rev->uf_srh_chequear_permisos ($ls_nroreg, $ls_fecini,$ls_fecfin) ;
			  if (!$lb_chequear) {
			     echo '<script>';
				 echo ' alert("No se puede realizar la revisión. Los días de Reposo y/o Permisos exceden el período de evaluación.");';
				echo '</script>';
				uf_agregarlineablanca($lo_object,1);		
				$li_totrows=1;
				$ls_fecini="";
				$ls_fecfin="";
				$ls_rev="";
				$ls_codper="";
				$ls_nomper="";
				$ls_carper="";
				$ls_codeva="";
				$ls_nomeva="";
				$ls_careva="";		
			  }
			}*/
			
			break;		
	
	}
	
	unset($io_rev);
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
          <td height="22" colspan="12">Seguimiento de los Objetivos de Desempe&ntilde;o Individual</td>
        </tr>
        <tr>
          <td width="61" height="22">&nbsp;</td>
          
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Registro</div></td>
          <td height="22"  colspan="5"><input name="txtnroreg" type="text" id="txtnroreg"  size="16" style="text-align:center" value="<?php print $ls_nroreg?>" readonly>  
            <a href="javascript:catalogo_odi();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo ODI" name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Registro de ODI </a> </td>
		<tr>
		 <td height="22"><div align="right">Fecha Revisi&oacute;n</div></td>
          <td height="22" colspan="5"><input name="txtfecha" type="text" id="txtfecha" size="16"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		<tr>
		<td height="22"><div align="right"></div></td>
		<td width="16" height="22"><div align="right"></div></td>
		<td width="78" height="22"><div align="right"></div></td>
	  	 <td width="179" height="22"><div align="right"></div></td>
	  	<td height="22"><div align="right"><a href="javascript: Limpiar_Datos();"><img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar Datos </a></div></td>
	    <td height="22"><a href="javascript: Consultar();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Datos</a></td>
	    <td colspan="2">&nbsp;</td>
		</tr>
		<tr>
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="11">Informaci&oacute;n del Registro de Objetivos de Desempe&ntilde;o Individual</td>
        </tr>
		<tr>
          <td width="61" height="22">&nbsp;</td>
          
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22" colspan="4"><input name="txtcodper" type="text" id="txtcodper" maxlength="10" size="16" style="text-align:center" value="<?php print $ls_codper?>" readonly>  
              <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		   <tr>
		  <td height="22" align="left"><div align="right">Nombre </div></td>
          <td height="22" colspan="4"><input name="txtnomper" type="text" id="txtnomper"  size="50" style="text-align:justify" readonly value="<?php print $ls_nomper?>">           </td>
	      </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">Cargo </div></td>
          <td height="22" colspan="4"><input name="txtcodcarper" type="text" id="txtcodcarper"  size="50" style="text-align:justify" readonly value="<?php print $ls_carper?>"></td>
	      </tr>
		<tr>
		 <td height="22"><div align="right">Del</div></td>
          <td height="22" colspan="2"><input name="txtfecini1" type="text" id="txtfecini1"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecini?>">            </td> 			
          <td width="179"  height="22"><div align="right">
            Al</div></td>
          <td width="129"><input name="txtfecfin1" type="text" id="txtfecfin1"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecfin?>">           </td>
		   
		   <td width="282"><input name="txtrev" type="text" id="txtrev" value="<? print $ls_rev?>" maxlength="40" style="text-align:justify" size="47" readonly  class="sin-borde"></td>
		   <td height="22" colspan="3" >&nbsp;</td>
          <td width="2"   height="22" colspan="2">&nbsp;</td>
	    </tr>
		  <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Evaluador</div></td>
          <td height="22" colspan="6"><input name="txtcodeva" type="text" id="txtcodeva" maxlength="10" size ="16" style="text-align:center" value="<?php print $ls_codeva?>"  readonly>             </td>
	      </tr>
		   <tr>
		  <td height="22" align="left"><div align="right">Nombre Evaluador</div></td>
          <td height="22" colspan="6"><input name="txtnomeva" type="text" id="txtnomeva"  size="50" style="text-align:justify" readonly value="<?php print $ls_nomeva?>">           </td>
	      </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">Cargo Evaluador</div></td>
          <td height="22" colspan="6"><input name="txtcareva" type="text" id="txtcareva"  size="50" style="text-align:justify" readonly value="<?php print $ls_careva?>">           </td>
	      </tr>	  
		  
			
	   <tr>
          <td height="22" colspan="7">&nbsp;</td>
        </tr>
		  
		
		<tr>
          <td><div align="right"></div></td>
          <td colspan="2"><input name="operacion" type="hidden" id="operacion">
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
		<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="1">
	    
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	 


  </p>

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>

<script>

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		nroreg=f.txtnroreg.value;
		fecini=f.txtfecini1.value;
		fecfin=f.txtfecfin1.value;
		rev=f.txtrev.value;
		pagina="../../../reporte/sigesp_srh_rpp_lote_odi.php?nroreg="+nroreg+"&fecini="+fecini+"&fecfin="+fecfin+"&rev="+rev;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		
		
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaciÃƒÂ³n");
   	}		
}


</script>
</html>


