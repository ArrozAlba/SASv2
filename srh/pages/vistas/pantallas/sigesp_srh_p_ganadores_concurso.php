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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_ganadores_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_codcon,$ls_descon,$ls_fecha, $ls_obj,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codcon="";
		$ls_descon="";
		$ls_fecha="";
		$ls_activarcodigo="";
		$ls_titletable="Panticipantes del Concurso";
		$li_widthtable=647;
		$ls_nametable="grid";
		$lo_title[1]="Código Personal";
		$lo_title[2]="Nombre y Apellido";
		$lo_title[3]="Tipo Personal";		
		$lo_title[4]="Puntaje Obtenido";
		$lo_title[5]="Posicion";
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
		
		
		
		$aa_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." size=10 id=txtcodper".$ai_totrows." class=sin-borde readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows."  size=45    id=txtnomper".$ai_totrows."  class=sin-borde readonly >";
		$aa_object[$ai_totrows][3]="<input name=txttipoper".$ai_totrows." size=10 id=txttipooper".$ai_totrows." class=sin-borde readonly>";
		$aa_object[$ai_totrows][4]="<input name=txttotal".$ai_totrows." size=5 id=txttotal".$ai_totrows." class=sin-borde readonly>";
		$aa_object[$ai_totrows][5]="<input name=txtposi".$ai_totrows." size=16 id=txtposi".$ai_totrows." onKeyUp='ue_validarcomillas(this);' class=sin-borde>";
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_ganadores_concurso.js"></script>

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
	require_once("../../../class_folder/dao/sigesp_srh_c_ganadores_concurso.php");
	$io_gana=new sigesp_srh_c_ganadores_concurso("../../../../");
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
			
		 	$ls_codcon=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_gana-> uf_srh_load_ganadores_concurso_campos($ls_codcon,$li_totrows,$lo_object);
			break;
			
	 case "CONSULTAR":
		 	$ls_codcon=$_POST["txtcodcon"];
			$ls_descon=$_POST["txtdescon"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_guardar=$_POST["hidguardar"];
			$lb_valido=$io_gana->uf_srh_consultar_ganadores_concurso($ls_codcon,$li_totrows,$lo_object);
			
			break;		
	
	}
	
	unset($io_gana);
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
          <td height="22" colspan="12">Ganadores de Concurso</td>
        </tr>
        <tr>
          <td width="118" height="22">&nbsp;</td>
          
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Concurso</div></td>
          <td width="155" height="22"  ><input name="txtcodcon" type="text" id="txtcodcon" value="<? print $ls_codcon?>" maxlength="10" size="16"  style="text-align:center"    readonly>   <a href="javascript:catalogo_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Concurso" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
		  <td>
            <input name="txtdescon" type="text" id="txtdescon" value="<? print $ls_descon?>" maxlength="40" style="text-align:justify" size="47" readonly  class="sin-borde">
            <input name="hidstatus" type="hidden" id="hidstatus">     </td>
			   </tr>
		
		<tr>
		<td height="22"><div align="right"></div></td>
		<td height="22"><div align="right"></div></td>
		<td width="282" height="22"><div align="right">
		  <div align="right">
            <div align="right"><a href="javascript: Limpiar_Datos();"><img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar Datos </a></div>
		    </div>
		</div></td>
	  	 <td width="125" height="22"><a href="javascript: Consultar();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Datos</a></td>
	  	 
		</tr>
		<tr>
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="11">Participantes del Concurso</td>
        </tr>
		
		<tr>
          <td width="118" height="22">&nbsp;</td>
          
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
			<tr>
		 <td height="22"><div align="right">Fecha Registro</div></td>
          <td height="22" colspan="5"><input name="txtfecha" type="text" id="txtfecha" size="16"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />          </td>
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
          <td colspan="8">
		  	<div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>
		  	<p>
			  <input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
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

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>


