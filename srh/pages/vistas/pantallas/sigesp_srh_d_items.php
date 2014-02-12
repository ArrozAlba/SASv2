<?php
session_start();
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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_items.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
$io_fun_nomina=new class_funciones_nomina();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 27/11/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

   		global $ls_codeval,$ls_deneval, $ls_codasp,$ls_denasp,$ls_hidstatus,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codeval="";
		$ls_deneval="";
	 	$ls_codasp="";
		$ls_denasp="";
		$ls_hidstatus="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Items de Evaluación";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Descripcion";
		$lo_title[3]="Valor";
		$lo_title[4]="Guardar";
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
		$aa_object[$ai_totrows][1]="<input name=txtcodite".$ai_totrows." type=text id=txtcodite".$ai_totrows." class=sin-borde size=15 maxlength=15  onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: generar_codigo(".$ai_totrows.");'>";
		$aa_object[$ai_totrows][2]="<input name=txtdenite".$ai_totrows." type=text id=txtdenite".$ai_totrows." onKeyUp='ue_validarcomillas(this);' class=sin-borde size=70 >";
		$aa_object[$ai_totrows][3]="<input name=txtvalor".$ai_totrows." class=sin-borde  type=text id=txtvalor".$ai_totrows." onKeyPress='return validarreal2(event,this);' size=8 maxlength=5>";		
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools/grabar.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
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
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 27/11/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_codite,$ls_denite, $li_valor;

		$li_codite=$_POST["txtcodite".$li_i];
		$ls_denite=$_POST["txtdenite".$li_i];
		$li_valor=$_POST["txtvalor".$li_i];
		
		
   }
   //--------------------------------------------------------------


?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Items de Evaluaci&oacute;n </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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
.Estilo1 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_items.js"></script>


</head>

<body>

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_items.php");
	$io_item=new sigesp_srh_c_items("../../../../");
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
		 	$ls_codeval=$_POST["txtcodeval"];
			$ls_deneval=$_POST["txtdeneval"];
			$ls_codasp=$_POST["txtcodasp"];
			$ls_denasp=$_POST["txtdenasp"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_hidstatus=$_POST["hidstatus"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				
				$lo_object[$li_i][1]="<input name=txtcodite".$li_i." type=text id=txtcodite".$li_i." class=sin-borde size=15 maxlength=15  onKeyUp='javascript: ue_validarnumero(this);'  value='".$li_codite."' onBlur='javascript: generar_codigo(".$li_i.");' readonly>";
				$lo_object[$li_i][2]="<input name=txtdenite".$li_i." type=text id=txtdenite".$li_i." onKeyUp='ue_validarcomillas(this);' class=sin-borde size=70  value='".$ls_denite."'>";
				$lo_object[$li_i][3]="<input name=txtvalor".$li_i." class=sin-borde  type=text id=txtvalor".$li_i." onKeyPress='return validarreal2(event,this);' size=8 maxlength=5 value='".$li_valor."'>";		
				$lo_object[$li_i][4]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools/grabar.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
				
			
			}
			
			uf_agregarlineablanca($lo_object,$li_totrows);
		
			break;

		case "ELIMINARDETALLE":
		 	$ls_codeval=$_POST["txtcodeval"];
			$ls_deneval=$_POST["txtdeneval"];
			$ls_codasp=$_POST["txtcodasp"];
			$ls_denasp=$_POST["txtdenasp"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_hidstatus=$_POST["hidstatus"];
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
					$lo_object[$li_temp][1]="<input name=txtcodite".$li_temp." type=text id=txtcodite".$li_temp." class=sin-borde size=15 maxlength=15  onKeyUp='javascript: ue_validarnumero(this);'  value='".$li_codite."' onBlur='javascript: generar_codigo(".$li_temp.");' readonly>";
				$lo_object[$li_temp][2]="<input name=txtdenite".$li_temp." type=text id=txtdenite".$li_temp." onKeyUp='ue_validarcomillas(this);' class=sin-borde size=70  value='".$ls_denite."'>";
				$lo_object[$li_temp][3]="<input name=txtvalor".$li_temp." class=sin-borde  type=text id=txtvalor".$li_temp." onKeyPress='return validarreal2(event,this);' size=8 maxlength=5 value='".$li_valor."'>";		
				$lo_object[$li_temp][4]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools/grabar.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
					
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_codeval=$_POST["txtcodeval"];
			$ls_deneval=$_POST["txtdeneval"];
			$ls_codasp=$_POST["txtcodasp"];
			$ls_denasp=$_POST["txtdenasp"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_hidstatus=$_POST["hidstatus"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_item->uf_srh_load_items_campos($ls_codeval, $ls_codasp,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}

?>


<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Recursos Humanos</td>
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
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../public/imagenes/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
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
<div align="center">
  <table width="672" height="178" border="0" class="formato-blanco">
    <tr>
      <td width="679" height="174"><div align="left">
          <form name="form1" method="post" action="">
            <p>
              <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
</p>
            <p>&nbsp;            </p>
            <table width="599" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="20" colspan="3" class="titulo-celdanew">Definici&oacute;n de Items de Evaluaci&oacute;n</td>
  </tr>
  <tr class="formato-blanco">
    <td width="159" height="19">&nbsp;</td>
    <td colspan="2"><div id="resultado" ></div></td>
  </tr>
    
   <tr >
          <td height="22"><div align="right">Tipo de Evaluaci&oacute;n </div></td>
          <td width="140" height="22" valign="middle"><input name="txtcodeval" type="text" id="txtcodeval"  size="16" maxlength="15"   value="<?php print $ls_codeval?>" readonly><input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_hidstatus?>">
            <a href="javascript:catalogo_tipoevaluacion();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
          <td width="300" height="22"><input name="txtdeneval" type="text" class="sin-borde" id="txtdeneval"  size="50" value="<?php print $ls_deneval?>" readonly></td>
        </tr>
		  <tr >
          <td height="22"><div align="right">Aspecto de  Evaluaci&oacute;n</div></td>
          <td height="22" valign="middle"><input name="txtcodasp" type="text" id="txtcodasp"  size="16" maxlength="15" value="<?php print $ls_codasp?>" readonly>
            <a href="javascript:catalogo_aspectos_items();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
          <td height="22"><input name="txtdenasp" type="text" class="sin-borde" id="txtdenasp"  value="<?php print $ls_denasp?>" size="50"  readonly></td>
        </tr>
		<tr class="formato-blanco">
    <td width="94" height="28" align="right"></td>
    <td  height="28" valign="middle"></td>
        <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"> </td>
  </tr>
   <tr>
          <td colspan="3">
		  	<div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			  <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
		
			</p>			</td>		  
          </tr>
</table>



<input name="hidcontrol" type="hidden" id="hidcontrol" value="3">
<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">

          </form>
      </div> <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</body>

</html>