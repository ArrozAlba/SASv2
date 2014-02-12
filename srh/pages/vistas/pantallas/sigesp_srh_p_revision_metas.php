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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_revision_metas.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_codper, $ls_fecreg,$ls_nomper,$ls_carper, $ls_fecha,  $ls_fecini, $ls_fecfin, $ls_nroreg, $ls_obs, $ls_codeval,$ls_deneval, $li_total, $ls_codeva, $ls_nomeva, $ls_careva,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codper="";
		$ls_nomper="";
		$ls_carper="";
		$ls_fecha="";
		$ls_fecini="";
		$ls_fecfin="";
		$ls_nroreg="";
		$li_total=0;
		$ls_fecreg="";
		$ls_codeval="";
		$ls_deneval="";
		$ls_codeva="";
		$ls_nomeva="";
		$ls_careva="";
		$ls_obs="";
		$ls_activarcodigo="";
		$ls_titletable="Metas de Personal";
		$li_widthtable=600;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Meta";
		$lo_title[3]="Ejecución (dd/mm/aaaaa)";
		$lo_title[4]="Evaluación (puntos)";
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
		$aa_object[$ai_totrows][1]="<textarea name=txtcodmet".$ai_totrows."  cols=5 rows=3 id=txtcodmet".$ai_totrows." class=sin-borde readonly></textarea>";
		$aa_object[$ai_totrows][2]="<textarea name=txtmeta".$ai_totrows."    cols=50 rows=3 id=txtmeta".$ai_totrows."  class=sin-borde readonly > </textarea>";
		$aa_object[$ai_totrows][3]="<textarea name=txtfeceje".$ai_totrows."  cols=8 rows=3 id=txfeceje".$ai_totrows."  class=sin-borde > </textarea>";
		$aa_object[$ai_totrows][4]="<textarea name=txtevalmet".$ai_totrows." cols=5 rows=3 id=txtevalmet".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);'  onChange='javascript: ue_sumar(txttotal);'> </textarea>";
		$aa_object[$ai_totrows][5]="<textarea name=txtobsmet".$ai_totrows."  cols=25 rows=3 id=txtobsmet".$ai_totrows." class=sin-borde></textarea>";
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
.Estilo14 {color: #006699; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
.Estilo20 {font-size: 10px}
.Estilo21 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo24 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; }
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_revision_metas.js"></script>

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

<body>

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_revision_metas.php");
	$io_rev=new sigesp_srh_c_revision_metas("../../../../");
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
			$ls_carper=$_POST["txtcodcarper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_fecini=$_POST["txtfecini"];
			$ls_obs=$_POST["txtobs"];
			$ls_fecfin=$_POST["txtfecfin"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_careva=$_POST["txtcodcareva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_fecreg=$_POST["txtfecreg"];
			$ls_codeval=$_POST["txtcodeval"];
    		$ls_deneval=$_POST["txtdeneval"];
			$li_total=$_POST["txttotal"];			
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_rev-> uf_srh_load_revision_metas_campos($ls_nroreg,$ls_fecha,$li_totrows,$lo_object);
			break;
			
	 case "CONSULTAR":
		 	$ls_nroreg=$_POST["txtnroreg"];
		 	$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_carper=$_POST["txtcodcarper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_fecini=$_POST["txtfecini"];
			$ls_obs=$_POST["txtobs"];
			$ls_fecfin=$_POST["txtfecfin"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_careva=$_POST["txtcodcareva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_fecreg=$_POST["txtfecreg"];
			$ls_codeval=$_POST["txtcodeval"];
    		$ls_deneval=$_POST["txtdeneval"];
			$li_total=$_POST["txttotal"];			
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";;
			$lb_valido=$io_rev->uf_srh_consultar_revision_metas($ls_nroreg,$li_totrows,$lo_object);
			
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Recursos Humanos</td>
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
      <table width="686" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="12">Revisi&oacute;n de Metas de Personal</td>
        </tr>
		 <tr class="titulo-celda">
		          <td height="22" colspan="11">Informaci&oacute;n del Registro de las Metas de Personal</td>
        <tr>
          <td width="81" height="22">&nbsp;</td>
          
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Registro</div></td>
          <td height="22"  colspan="3"><input name="txtnroreg" type="text" id="txtnroreg" value="<? print $ls_nroreg?>" maxlength="10" size="16"  style="text-align:center"     readonly >  
          <a href="javascript:catalogo_registro_metas();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Registro de Metas</a> </td>
		</tr>
		<tr>
		 <td height="22"><div align="right">Fecha Registro</div></td>
          <td height="22" colspan="2"><input name="txtfecreg" type="text" id="txtfecreg"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecreg?>">            </td>
		  </tr>
		<tr>
		<td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
		<td height="22" colspan="4" valign="middle"><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codper?>"  style="text-align:center" size="16" readonly  > </td>
        </tr>
		
		<tr>
		<td height="22" align="left"><div align="right">Nombre</div></td>
		<td height="22" colspan="4" valign="middle"><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomper?>" maxlength="40" style="text-align:justify" size="50" readonly> </td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Cargo</div></td>
          <td height="22" colspan="4"><input name="txtcodcarper" type="text" id="txtcodcarper"   size="50" style="text-align:justify"  value="<?php print $ls_carper?>" readonly>
           </td>
		 </tr>

		<tr>
		 
		  <td height="22"><div align="right">Del</div></td>
          <td height="22" colspan="2"><input name="txtfecini" type="text" id="txtfecini"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecini?>">            </td> 			
          <td width="155"  height="22"><div align="right">Al</div></td>
          <td width="108"><input name="txtfecfin" type="text" id="txtfecfin"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecfin?>">           </td>
		   
		  
		  </tr>
			 <tr>
          <td height="22" align="left"><div align="right">Observaci&oacute;n </div></td>
          <td height="22" colspan="5"><textarea name="txtobs" cols="86" rows="5" id="txtobs" style="text-align:justify"  readonly ><?php print $ls_obs?></textarea></td>
        </tr>
		<tr>
		<td height="22"><div align="right"></div></td>
		<td width="114" height="22"><div align="right"></div></td>
		<td width="0" height="22"><div align="right"></div></td>
	  	 <td width="155" height="22">&nbsp;</td>
	  	<td height="22"><div align="right"><a href="javascript: Limpiar_Datos();"><img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar Datos </a></div></td>
	    <td width="192" height="22"><a href="javascript: Consultar();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Datos</a></td>
	    <td width="22" colspan="2">&nbsp;</td>
		</tr>
		
	   <tr>
          <td height="22" colspan="7">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="11">Datos de la Revisi&oacute;n</td>
        </tr>
		<tr>
          <td height="22">&nbsp;</td>
          <td height="22" colspan="8">&nbsp;</td>
        </tr> 
		 <tr>
		 <td height="22"><div align="right">Fecha Revisi&oacute;n</div></td>
          <td height="22" colspan="5"><input name="txtfecha" type="text" id="txtfecha" size="16"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		
	     <tr>
           <td height="28"><div align="right"> Tipo de Evaluaci&oacute;n</div></td>
          <td height="28"  colspan="6"><input name="txtcodeval" type="text" id="txtcodeval" value="<?php print $ls_codeval ?>" size="16" maxlength="15"  style="text-align:center" readonly>
          <a href="javascript: catalogo_evaluacion();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo  Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
          <input name="txtdeneval" type="text" class="sin-borde" id="txtdeneval" value="<?php print $ls_deneval ?>" size="50
          " maxlength="80" readonly>
          </td>
         </tr>
    		 
		
		  	  <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Evaluador</div></td>
          <td height="22" colspan="6"><input name="txtcodeva" type="text" id="txtcodeva" maxlength="10" size ="16" style="text-align:center" value="<?php print $ls_codeva?>" readonly>  <a href="javascript:catalogo_evaluador();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
              <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		   <tr>
		  <td height="22" align="left"><div align="right">Nombre Evaluador</div></td>
          <td height="22" colspan="6"><input name="txtnomeva" type="text" id="txtnomeva"  size="50" style="text-align:justify" readonly value="<?php print $ls_nomeva?>">           </td>
	      </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">Cargo Evaluador</div></td>
          <td height="22" colspan="6"><input name="txtcodcareva" type="text" id="txtcodcareva"  size="50" style="text-align:justify" readonly value="<?php print $ls_careva?>">           </td>
	      </tr>	  
		   <tr>
	  <td height="22" align="left"><div align="right">Resultado Evaluaci&oacute;n</div></td>
	  <td height="22" colspan="4"><input name="txttotal" type="text" id="txttotal" maxlength=3 size="7" style="text-align:center"   value="<?php print $li_total?>" readonly >
            </td>
	      </tr>
 
		
		<tr>
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		<tr>
          <td><div align="right"></div></td>
          <td colspan="2"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
		<tr>
          <td colspan="7">
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
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="">
	 <input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
	   	     
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	 


  </p>

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>


