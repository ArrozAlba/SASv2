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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_evaluacion_pasantias.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_nropas,$ls_nompas,$ls_cedpas, $ls_feceval, $ls_estado, $la_estado,$li_res,$ls_obs, $ls_guardar,$ls_activarcodigo,$ls_operacion,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_nropas="";
		$ls_nompas="";
		$ls_cedpas="";
		$ls_feceval="";
		$ls_guardar="";
		$ls_estado="";
		$la_estado[0]="";
		$la_estado[1]="";
		$li_res=0;
		$ls_obs="";
		$ls_activarcodigo="";
		$ls_titletable="Detalle de Evaluación de Pasantía";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Meta Propuesta";
		$lo_title[2]="Observación Meta";
		$lo_title[3]="Valor";
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
		$aa_object[$ai_totrows][1]="<textarea name=txtmetap".$ai_totrows."  cols=45 rows=3 id=txtmetap".$ai_totrows." class=sin-borde  onKeyUp='ue_validarcomillas(this);' ></textarea>";
		$aa_object[$ai_totrows][2]="<textarea name=txtobsm".$ai_totrows."  cols=45 rows=3 id=txtobsm".$ai_totrows." class=sin-borde onKeyUp='ue_validarcomillas(this);'></textarea>";
		$aa_object[$ai_totrows][3]="<textarea name=txtvalor".$ai_totrows." cols=7 rows=3  id=txtvalor".$ai_totrows." class=sin-borde onKeyPress='return validarreal2(event,this);' onChange='javascript: ue_suma(txtres);' ></textarea>";
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.",txtres,txtvalor".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
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
   		global $ls_metap,$ls_obsm,$li_valor;

		$ls_metap=$_POST["txtmetap".$li_i];
		$ls_obsm=$_POST["txtobsm".$li_i];
	    $li_valor=$_POST["txtvalor".$li_i];
			
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_evaluacion_pasantia.js"></script>

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

<body>

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_evaluacion_pasantia.php");
	$io_eval=new sigesp_srh_c_evaluacion_pasantia("../../../../");
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
		 	$ls_nropas=$_POST["txtnropas"];
			$ls_cedpas=$_POST["txtcedpas"];
			$ls_nompas=$_POST["txtnompas"];
			$ls_feceval=$_POST["txtfeceval"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_estado=$_POST["combopas"];
			switch($ls_estado)
			{
			case 'Activa':
				$la_estado[0]='Activa';
				break;
			case 'Concluida':
				$la_estado[1]='Concluida';
				break;
			}
			$li_res=$_POST["txtres"];
			$ls_obs=$_POST["txtobs"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				$lo_object[$li_i][1]="<textarea name=txtmetap".$li_i."  cols=45 rows=3 id=txtmetap".$li_i." class=sin-borde>".$ls_metap."</textarea>";
				$lo_object[$li_i][2]="<textarea name=txtobsm".$li_i."  cols=45 rows=3 id=txtobsm".$li_i." class=sin-borde>".$ls_obsm."</textarea>";
				$lo_object[$li_i][3]="<textarea name=txtvalor".$li_i." cols=7 rows=3  id=txtvalor".$li_i." class=sin-borde onKeyPress='return validarreal2(event,this);'  onChange='javascript: ue_suma(txtres);' >".$li_valor."</textarea>";
				$lo_object[$li_i][4]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.",txtres,txtvalor".$li_i.");   align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";				
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_nropas=$_POST["txtnropas"];
			$ls_cedpas=$_POST["txtcedpas"];
			$ls_nompas=$_POST["txtnompas"];
			$ls_feceval=$_POST["txtfeceval"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_estado=$_POST["combopas"];
			switch($ls_estado)
			{
			case 'Activa':
				$la_estado[0]='Activa';
				break;
			case 'Concluida':
				$la_estado[1]='Concluida';
				break;
			}
			$li_res=$_POST["txtres"];
			$ls_obs=$_POST["txtobs"];
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
					$lo_object[$li_temp][1]="<textarea name=txtmetap".$li_temp."  cols=45 rows=3 id=txtmetap".$li_temp." class=sin-borde >".$ls_metap."</textarea>";
				$lo_object[$li_temp][2]="<textarea name=txtobsm".$li_temp."  cols=45 rows=3 id=txtobsm".$li_temp." class=sin-borde >".$ls_obsm."</textarea>";
				$lo_object[$li_temp][3]="<textarea name=txtvalor".$li_temp." cols=7 rows=3  id=txtvalor".$li_temp."  class=sin-borde onKeyPress='return validarreal2(event,this);'  onChange='javascript: ue_suma(txtres);' >".$li_valor."</textarea>";
				$lo_object[$li_temp][4]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.",txtres,txtvalor".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_nropas=$_POST["txtnropas"];
			$ls_cedpas=$_POST["txtcedpas"];
			$ls_nompas=$_POST["txtnompas"];
			$ls_feceval=$_POST["txtfeceval"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_estado=$_POST["combopas"];
			switch($ls_estado)
			{
			case 'Activa':
				$la_estado[0]='Activa';
				break;
			case 'Concluida':
				$la_estado[1]='Concluida';
				break;
			}
			$li_res=$_POST["txtres"];
			$ls_obs=$_POST["txtobs"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_eval->uf_srh_load_evaluacion_pasantia_campos($ls_nropas,$ls_feceval,$li_totrows,$lo_object);
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
      <table width="688" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="9">Evaluaci&oacute;n de Pasant&iacute;a</td>
        </tr>
        <tr>
          <td width="122" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Registro</div></td>
          <td height="22" colspan="4"><input name="txtnropas" type="text" id="txtnropas" readonly maxlength="15" style="text-align:center" value="<?php print $ls_nropas?>">  <a href="javascript:catalogo_pasantia();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Pasant&iacute;as" name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Registro de Pasant&iacute;a</a>
              <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">C&eacute;dula Pasante</div></td>
          <td height="22" colspan="4"><input name="txtcedpas" type="text" id="txtcedpas"   maxlength="15" style="text-align:justify" readonly value="<?php print $ls_cedpas?>">
           </td>
		  <tr>
		  <td height="22" align="left"><div align="right">Nombre Pasante</div></td>
          <td height="22" colspan="4"><input name="txtnompas" type="text" id="txtnompas"  size="50" style="text-align:justify" readonly value="<?php print $ls_nompas?>">
           </td>
	      </tr>
		  <tr>
		 <td height="22"><div align="right">Fecha  Inicio Pasant&iacute;a</div></td>
          <td height="22" colspan="2"><input name="txtfecini" type="text" id="txtfecini"  maxlength="15"  size="16" style="text-align:center" datepicker="true" readonly> 
         </td>
	    </tr>
		  
		  <tr>
		 <td height="22"><div align="right">Fecha  Evaluaci&oacute;n</div></td>
          <td height="22" colspan="2"><input name="txtfeceval" type="text" id="txtfeceval"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_feceval?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfeceval', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		
		 <tr>
          <td height="22" align="left"><div align="right">Estado Pasant&iacute;a</div></td>
          <td height="22" bordercolor="0"><select name="combopas" id="combopas"   >
              <option value="null" >--Seleccione--</option>
              <option value="Activa"   <?php if($la_estado[0]=="Activa"){ print "selected";} ?> >Activa</option>
              <option value="Concluida" <?php if($la_estado[1]=="Concluida"){ print "selected";} ?> >Concluida</option>
            </select>          </td>
          
        </tr>
		<tr>
		
		  <td height="22" align="left"><div align="right">Resultado</div></td> 
	  <td height="22" colspan="4"><input name="txtres" type="text" id="txtres" maxlength=3 size="7" style="text-align:center"   value="<?php print $li_res?>" readonly>
            </td>
	      </tr>
        <tr>
          <td height="22" align="left"><div align="right">Observaci&oacute;n </div></td>
          <td height="22" colspan="4"><textarea name="txtobs" cols="86" rows="5" id="txtobs" onKeyUp='ue_validarcomillas(this);' style="text-align:justify"  ><?php print $ls_obs?></textarea></td>
        </tr>
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
          <td><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
		<tr>
          <td colspan="2">
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

  <input type="hidden" name="hidguardar" id="hidguardar" value="<?php print $ls_guardar?>">
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
	    
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	
 

 
</form>


</body>


</html>


