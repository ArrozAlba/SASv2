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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_bono_merito.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		
   		global $ls_codesc,$ls_denesc, $ls_tipper, $ls_codper,$ls_nomper,$ls_codtipper, $ls_dentipper, $ls_fecha,$ls_total,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codper="";
		$ls_nomper="";
		$ls_codesc="";
		$ls_denesc="";
		$ls_tipper="";
		$ls_fecha="";
		$ls_codtipper="";
		$ls_dentipper="";
		$ls_total=0;
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Detalles del Bono por Mérito del Personal";
		$li_widthtable=600;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Denominación";
		$lo_title[3]="Escala";
		$lo_title[4]="Puntaje";
		$lo_title[5]="Observación";		
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
		$aa_object[$ai_totrows][1]="<input name=txtcodpunt".$ai_totrows." type=text id=txtcodpunt".$ai_totrows." class=sin-borde size=15  readonly  >";
		$aa_object[$ai_totrows][2]="<input name=txtnombpunt".$ai_totrows." type=text id=txtnombpunt".$ai_totrows." class=sin-borde size=70  readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtvalini".$ai_totrows." type=text id=txtvalini".$ai_totrows." class=sin-borde size=7   readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtpuntos".$ai_totrows." type=text id=txtpuntos".$ai_totrows." class=sin-borde size=5 maxlength=4 onKeyUp='javascript: ue_validarnumero2(this);' onBlur= 'javascript: validar_escala (".$ai_totrows.");' onChange='javascript: ue_suma (txttotal);'>";
		$aa_object[$ai_totrows][5]="<input name=txtobs".$ai_totrows." type=text id=txtobs".$ai_totrows." class=sin-borde size=40 onKeyUp='ue_validarcomillas(this);'  >";
					
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
   		global $ls_codpunt,$ls_nombpunt,$ls_valini, $ls_obs,$li_puntos;

		$ls_codpunt=$_POST["txtcodpunt".$li_i];
		$ls_nombpunt=$_POST["txtnombpunt".$li_i];
	    $ls_obs=$_POST["txtobs".$li_i];
		$ls_valini=$_POST["txtvalini".$li_i];
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
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_bono_merito.js"></script>



</head>

<body>
<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_bono_merito.php");
	$io_bono=new sigesp_srh_c_bono_merito("../../../../");
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
			$ls_codesc=$_POST["txtcodesc"];
			$ls_denesc=$_POST["txtdenesc"];
			$ls_tipper=$_POST["txttipper"];
			$ls_codtipper=$_POST["txtcodtipper"];
			$ls_dentipper=$_POST["txtdentipper"];
			$ls_total=$_POST["txttotal"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_bono->uf_srh_load_bono_merito_campos($ls_codper,$ls_fecha,$li_totrows,$lo_object);
			break;
			
			
		case "CONSULTAR":
		    $ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_codtipper=$_POST["txtcodtipper"];
			$ls_dentipper=$_POST["txtdentipper"];
			$ls_codesc=$_POST["txtcodesc"];
			$ls_denesc=$_POST["txtdenesc"];
			$ls_tipper=$_POST["txttipper"];
			$ls_total=$_POST["txttotal"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_bono->uf_srh_consultar_items($ls_codtipper,$li_totrows,$lo_object);
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
   
</div></td>
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
		          <td height="20" colspan="9">Bono por M&eacute;rito del Personal</td>
        </tr>
		<tr>
          <td width="116"   height="22">&nbsp;</td>
          
          <td height="22" colspan="6">&nbsp;</td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22" colspan="5"><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codper?>" maxlength="10" size="16"  style="text-align:center"    readonly>  <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
          <td width="355" valign="middle"></td>
        </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="6"><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomper?>" maxlength="40" style="text-align:justify" size="45" readonly > <input name="txttipper" type="text" id="txttipper" value="<? print $ls_tipper?>" maxlength="40" style="text-align:justify" size="45" class="sin-borde2" readonly >              </td>
		  </tr>
		   <tr>
			  <td height="22" align="left"><div align="right">Tipo Personal Bono M&eacute;rito</div></td>
          <td height="22" colspan="6"><input name="txtcodtipper" type="text" id="txtcodtipper" style="text-align:center" value="<? print $ls_codtipper?>" maxlength="40"  size="5" readonly >   <a href="javascript:catalogo_tipo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Tipo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"> </a> <input name="txtdentipper" type="text" id="txtdentipper" value="<? print $ls_dentipper?>"  style="text-align:justify" class="sin-borde" size="60" readonly >           </td>
		  </tr>
		  
		  <tr>
			  <td height="30"><div align="right">Escala de Bono por Unidad Tributaria</div></td>
          <td height="22" colspan="56"><input name="txtcodesc" type="text" id="txtcodesc" value="<?php print $ls_codesc?>" size="5" maxlength="15"  readonly style="text-align:center " >   <a href="javascript:catalogo_tabla_bono();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Tipo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>  <input name="txtdenesc" type="text" id="txtdenesc" value="<?php print $ls_denesc?>" onKeyUp="ue_validarcomillas(this);" size="60" maxlength="254" class="sin-borde">        </td>
		  </tr>
		 
		 <tr>
          <td height="22"><div align="right">Fecha </div></td>
          <td height="22" colspan="5">   <input name="txtfecha" type="text" id="txtfecha" value="<? print $ls_fecha?>"size="16" style="text-align:center" readonly > <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />		   </td>
        </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">Total</div></td>
          <td height="22" colspan="6"><input name="txttotal" type="text" id="txttotal" value="<? print $ls_total?>" maxlength="5" style="text-align:center" size="5" readonly ></td>
		  </tr>
	 
		   <tr>
      <td height="28"><div align="right">
        <input name="operacion" type="hidden" id="operacion">
        <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
      </div></td>
	  <td  colspan="5" height="28"><div align="right"></div></td>
      <td height="28"  colspan="6"><a href="javascript:consultar_items();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Puntuaciones de Bono por M&eacute;rito</a>      </td>
    </tr>
	 <tr>
          <td  colspan="7">
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
</td>
</tr>
<tr><td>
<p>&nbsp;</p></td>
</tr>

</table>
  <p>
     <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
	    
	 	  
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
 <input name="hidstatus" type="hidden" id="hidstatus">
     <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
  </p>


</form>
<script language="javascript">
function ue_imprimir()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	lb_existe=f.existe.value;
	if(li_imprimir==1)
	{	
	  if(lb_existe=="TRUE")
		{		
    	//codeval=f.txtcodeval.value; 
	   	codper=f.txtcodper.value;
		//codcon=f.txtcodcon.value;
		fecha=f.txtfecha.value;
				   	pagina="../../../reporte/sigesp_srh_rpp_resultados_bonoxmerito.php?codper="+codper+"&fecha="+fecha+"";
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
	  else 
	    {
		 alert("Debe existir un documento a imprimir");
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
