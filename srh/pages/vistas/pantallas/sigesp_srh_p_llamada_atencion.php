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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_llamada_atencion.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_nrollam,$ls_nomtrab, $ls_fecllam, $ls_codtrab,$ls_codcartrab,$ls_uniad,$ls_des, $ls_causa, $la_causa, $ls_tipo, $la_tipo, $ls_guardar, $ls_activarcodigo,$ls_operacion,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_nrollam="";
		$ls_nomtrab="";
		$ls_fecllam="";
		$ls_codtrab="";
		$ls_codcartrab="";
		$ls_uniad="";
		$ls_causa="";
		$ls_tipo="";
		$la_causa[0]="";
		$la_causa[1]="";
		$la_tipo[0]="";
		$la_tipo[1]="";		
		$ls_des="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Detalle de Causas Amonestación / Llamada de Atención";
		$li_widthtable=600;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Denominación";
		$lo_title[3]="Buscar";
		$lo_title[4]="Agregar";
		$lo_title[5]="Eliminar";
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
		$aa_object[$ai_totrows][1]="<input name=txtcodcaullam_aten".$ai_totrows." type=text id=txtcodcaullam_aten".$ai_totrows." class=sin-borde size=15  readonly  >";
		$aa_object[$ai_totrows][2]="<input name=txtdencaullam_aten".$ai_totrows." type=text id=txtdencaullam_aten".$ai_totrows." class=sin-borde size=70  readonly>";
		$aa_object[$ai_totrows][3]="<a href=javascript:catalogo_causas(".$ai_totrows.")    align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";		
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
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
   		global $ls_codcau,$ls_dencau;

		$ls_codcau=$_POST["txtcodcaullam_aten".$li_i];
		$ls_dencau=$_POST["txtdencaullam_aten".$li_i];
	  
			
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
.Estilo25 {color: #6699CC}
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_llamada_atencion.js"></script>



</head>

<body onLoad="javascript:ue_nuevo_codigo();">

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_llamada_atencion.php");
	$io_llamada=new sigesp_srh_c_llamada_atencion("../../../../");
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
		 	$ls_nrollam=$_POST["txtnrollam"];
			$ls_nomtrab=$_POST["txtnomper"];
			$ls_fecllam=$_POST["txtfecllam"];
			$ls_uniad=$_POST["txtuniad"];
			$ls_codtrab=$_POST["txtcodper"];
			$ls_codcartrab=$_POST["txtcodcarper"];
			$ls_des=$_POST["txtdes"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;	
			$ls_tipo=$_POST["cmbtipo"];
			$ls_causa=$_POST["cmbcausa"];
			
			switch($ls_tipo)
			{
				case "1":
					$la_tipo[0]="selected";					
					break;
				case "2":
					$la_tipo[1]="selected";					
					break;
			}
			switch($ls_causa)
			{
				case "1":
					$la_causa[0]="selected";					
					break;
				case "2":
					$la_causa[1]="selected";					
					break;
			}
					
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				
				$lo_object[$li_i][1]="<input name=txtcodcaullam_aten".$li_i." type=text id=txtcodcaullam_aten".$li_i." class=sin-borde size=15  readonly value='".$ls_codcau."'>";
				$lo_object[$li_i][2]="<input name=txtdencaullam_aten".$li_i." type=text id=txtdencaullam_aten".$li_i." class=sin-borde size=70  readonly  value='".$ls_dencau."'>";
				$lo_object[$li_i][3]="<a href=javascript:catalogo_causas(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";		
				$lo_object[$li_i][4]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
						
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_nrollam=$_POST["txtnrollam"];
			$ls_nomtrab=$_POST["txtnomper"];
			$ls_fecllam=$_POST["txtfecllam"];
			$ls_uniad=$_POST["txtuniad"];
			$ls_codtrab=$_POST["txtcodper"];
			$ls_codcartrab=$_POST["txtcodcarper"];
			$ls_des=$_POST["txtdes"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			
			$ls_tipo=$_POST["cmbtipo"];
			$ls_causa=$_POST["cmbcausa"];
			
			switch($ls_tipo)
			{
				case "1":
					$la_tipo[0]="selected";					
					break;
				case "2":
					$la_tipo[1]="selected";					
					break;
			}
			switch($ls_causa)
			{
				case "1":
					$la_causa[0]="selected";					
					break;
				case "2":
					$la_causa[1]="selected";					
					break;
			}
			
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=($li_rowdelete))
				{		
					$li_temp++;			
					uf_cargar_dt($li_i);
				$lo_object[$li_temp][1]="<input name=txtcodcaullam_aten".$li_temp." type=text id=txtcodcaullam_aten".$li_temp." class=sin-borde size=15  readonly value='".$ls_codcau."'>";
				$lo_object[$li_temp][2]="<input name=txtdencaullam_aten".$li_temp." type=text id=txtdencaullam_aten".$li_temp." class=sin-borde size=70  readonly value='".$ls_dencau."'>";
				$lo_object[$li_temp][3]="<a href=javascript:catalogo_causas(".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";		
				$lo_object[$li_temp][4]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_nrollam=$_POST["txtnrollam"];
			$ls_nomtrab=$_POST["txtnomper"];
			$ls_fecllam=$_POST["txtfecllam"];
			$ls_uniad=$_POST["txtuniad"];
			$ls_codtrab=$_POST["txtcodper"];
			$ls_codcartrab=$_POST["txtcodcarper"];
			$ls_des=$_POST["txtdes"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$ls_tipo=$_POST["cmbtipo"];
			$ls_causa=$_POST["cmbcausa"];
			
			switch($ls_tipo)
			{
				case "1":
					$la_tipo[0]="selected";					
					break;
				case "2":
					$la_tipo[1]="selected";					
					break;
			}
			switch($ls_causa)
			{
				case "1":
					$la_causa[0]="selected";					
					break;
				case "2":
					$la_causa[1]="selected";					
					break;
			}
			
			$lb_valido=$io_llamada->uf_srh_load_llamada_atencion_campos($ls_nrollam,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}
	
	unset($io_llamada);
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

<p></p>
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
  <tr>
    <td width="715" height="136"> 
    <p>&nbsp;</p>
      <table width="688" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="9">Amonestaci&oacute;n / Llamada de Atenci&oacute;n</td>
        </tr>
        <tr>
          <td width="138" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Registro </div></td>
          <td height="22" colspan="4"><input name="txtnrollam" type="text" id="txtnrollam" value="<?php print $ls_nrollam?>" maxlength="15"  size="16" style="text-align:center"  readonly >
              <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		   <tr>
          <td height="22"><div align="right">Fecha </div></td>
          <td height="22" colspan="5"><input name="txtfecllam" type="text" id="txtfecllam" value="<?php print $ls_fecllam?>" maxlength="15" size="16" style="text-align:center" readonly > 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecllam', '%d/%m/%Y');" value=" ... " />           </td>
        </tr>
		 <tr class="formato-blanco">
			<td height="28" align="right">Causa</td>
			<td height="28" colspan="2"><label>
			  <select name="cmbcausa" id="cmbcausa"   >
				<option value="null" selected>--Seleccione--</option>
				<option value="1" <?php print $la_causa[0]; ?>>Amonestaci&oacute;n</option>
				<option value="2" <?php print $la_causa[1]; ?>>Llamada de Atenci&oacute;n</option>				
				</select>
		 
			</label></td>
		  </tr>
		  <tr class="formato-blanco">
			<td height="28" align="right">Tipo</td>
			<td height="28" colspan="2"><label>
			  <select name="cmbtipo" size="1" id="cmbtipo"   >
			   <option value="null">--Seleccione--</option>
			   <option value="1" <?php print $la_tipo[0]; ?>>Verbal</option>
			   <option value="2" <?php print $la_tipo[1]; ?>>Escrita</option>
			   </select>			
			</label></td>
		  </tr>
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="9">Datos del Trabajador</td>
        </tr>
		<tr>
          <td width="138" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22" colspan="4"><input name="txtcodper" type="text" id="txtcodper"  maxlength="10"  size="16" style="text-align:center" readonly value="<?php print $ls_codtrab?>" >
               <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
          <td width="14" valign="middle"> </td>
		  </tr>
		 
		       
        <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="3"><input name="txtnomper" type="text" id="txtnomper"   style="text-align:justify" size="50" readonly value="<?php print $ls_nomtrab?>" >          </tr>
         <tr>
             <td height="22"><div align="right">Cargo </div></td>
          <td height="22" colspan="2" valign="middle"><input name="txtcodcarper" type="text" id="txtcodcarper"  size="50" value="<?php print $ls_codcartrab?>" readonly>
          </a></td>
        </tr>
		 <tr>
             <td height="22"><div align="right">Gerencia </div></td>
          <td width="192" height="22" valign="middle"><input name="txtuniad" type="text" id="txtuniad" size="50" maxlength="250" value="<?php print $ls_uniad?>"></td>
           
           <td colspan="3"></a></td>
        </tr>
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		<tr class="titulo-nuevo">
          <td height="22" colspan="9">Detalles de la Amonestaci&oacute;n / LLamada de Atenci&oacute;n</td>
        </tr>
		  <tr>
          <td width="138" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Breve Descripción</div></td>
          <td height="22" colspan="7"><textarea name="txtdes" onKeyUp="ue_validarcomillas(this);" cols="83" rows="6" id="txtdes"><?php print $ls_des;?></textarea></td>
        </tr>
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        
        <tr>
         <div align="right"></div>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
		<tr>
          <td  colspan="5">
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
	  <p>&nbsp;</p>
</table>
  <p>
    <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
 
    <input name="txtcontrol" type="hidden" id="txtcontrol" value="1">
	
		    
	
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	 <input name="txtnum" type="hidden" id="txtnum" value=<? print $li_totrows?> >


  </p>

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>
