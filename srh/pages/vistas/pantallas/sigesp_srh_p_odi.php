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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_odi.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_nroreg, $ls_codper,$ls_nomper,$ls_carper, $ls_fecha, $ls_fecini1,$ls_fecfin1,$ls_fecini2,$ls_fecfin2,$ls_codeva, $ls_nomeva, $ls_codcareva, $li_total,$ls_obj,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
		$ls_nroreg="";
	 	$ls_codper="";
		$ls_nomper="";
		$ls_carper="";
		$ls_fecha="";
		$ls_fecini1="";
		$ls_fecfin1="";
		$ls_fecini2="";
		$ls_fecfin2="";
		$ls_codeva="";
		$ls_nomeva="";
		$ls_codcareva="";
		$li_total=0;
		$ls_obj="";
		$ls_activarcodigo="";
		$ls_titletable="Registro de Objetivos de Desempeño Individual (O.D.I)";
		$li_widthtable=650;
		$ls_nametable="grid";
		$lo_title[1]="Objetivo de Desempeño Individual";
		$lo_title[2]="Peso";
		$lo_title[3]="Agregar";
		$lo_title[4]="Eliminar";
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
		$aa_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=60 rows=3 id=txtodi".$ai_totrows." class=sin-borde></textarea> <input name=txtcododi".$ai_totrows." type=hidden class=sin-borde id=txtcododi".$ai_totrows."  readonly value=".$ai_totrows.">";
		$aa_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_sumar(txttotal,this);'> </textarea>";
		$aa_object[$ai_totrows][3]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows.",txttotal,txtvalor".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
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
   		global $ls_cododi,$ls_odi,$li_valor;

		$ls_cododi=$_POST["txtcododi".$li_i];
		$ls_odi=$_POST["txtodi".$li_i];
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_odi.js"></script>

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
	require_once("../../../class_folder/dao/sigesp_srh_c_odi.php");
	$io_obj=new sigesp_srh_c_odi("../../../../");
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
			$ls_nroreg=$_POST["txtnroreg"];
		 	$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_codcareva=$_POST["txtcodcareva"];
			$ls_fecini1=$_POST["txtfecini1"];
			$ls_fecfin1=$_POST["txtfecfin1"];
			$ls_fecini2=$_POST["txtfecini2"];
			$ls_fecfin2=$_POST["txtfecfin2"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_carper=$_POST["txtcodcarper"];
			$li_total=$_POST["txttotal"];
			$ls_obj=$_POST["txtobj"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				$lo_object[$li_i][1]="<textarea name=txtodi".$li_i."  cols=60 rows=3 id=txtodi".$li_i." class=sin-borde>".$ls_odi."</textarea> <input name=txtcododi".$li_i." type=hidden class=sin-borde id=txtcododi".$li_i."  readonly value=".$ls_cododi.">";
				$lo_object[$li_i][2]="<textarea name=txtvalor".$li_i."    cols=6 rows=3 id=txtvalor".$li_i."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_sumar(txttotal,this);'>".$li_valor." </textarea>";
				$lo_object[$li_i][3]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][4]="<a href=javascript:uf_delete_dt(".$li_i.",txttotal,txtvalor".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";				
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
			$ls_nroreg=$_POST["txtnroreg"];
		 	$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_codcareva=$_POST["txtcodcareva"];
			$ls_carper=$_POST["txtcodcarper"];
			$ls_fecini1=$_POST["txtfecini1"];
			$ls_fecfin1=$_POST["txtfecfin1"];
			$ls_fecini2=$_POST["txtfecini2"];
			$ls_fecfin2=$_POST["txtfecfin2"];
			$li_total=$_POST["txttotal"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_obj=$_POST["txtobj"];
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
					$lo_object[$li_temp][1]="<textarea name=txtodi".$li_temp."  cols=60 rows=3 id=txtodi".$li_temp." class=sin-borde >".$ls_odi."</textarea> <input name=txtcododi".$li_temp." type=hidden class=sin-borde id=txtcododi".$li_temp."  readonly value=".$ls_cododi.">";
					$lo_object[$li_temp][2]="<textarea name=txtvalor".$li_temp."    cols=6 rows=3 id=txtvalor".$li_temp."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_sumar(txttotal,this);'>".$li_valor." </textarea>";
					$lo_object[$li_temp][3]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
					$lo_object[$li_temp][4]="<a href=javascript:uf_delete_dt(".$li_temp.",txttotal,txtvalor".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
			$ls_nroreg=$_POST["txtnroreg"];
		 	$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_codcareva=$_POST["txtcodcareva"];
			$ls_carper=$_POST["txtcodcarper"];
			$ls_fecini1=$_POST["txtfecini1"];
			$ls_fecfin1=$_POST["txtfecfin1"];
			$ls_fecini2=$_POST["txtfecini2"];
			$li_total=$_POST["txttotal"];
			$ls_fecfin2=$_POST["txtfecfin2"];
			$ls_obj=$_POST["txtobj"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_obj->uf_srh_load_odi_campos($ls_nroreg,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
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
          <td height="22" colspan="9">Establecimiento de los Objetivos de Desempe&ntilde;o Individual</td>
        </tr>
        <tr>
          <td width="108" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Nro.Registro</div></td>
          <td height="22" colspan="6"><input name="txtnroreg" type="text" id="txtnroreg"  size="16" style="text-align:center" value="<?php print $ls_nroreg?>" readonly></td>
	      </tr>
        <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22" colspan="4"><input name="txtcodper" type="text" id="txtcodper" maxlength="10" size="16" style="text-align:center" value="<?php print $ls_codper?>" readonly>  <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
              <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		   <tr>
		  <td height="22" align="left"><div align="right">Nombre </div></td>
          <td height="22" colspan="4"><input name="txtnomper" type="text" id="txtnomper"  size="50" style="text-align:justify" readonly value="<?php print $ls_nomper?>">           </td>
	      </tr>
		  <tr>
		  <td height="22" align="left"><div align="right">Cargo </div></td>
          <td height="22" colspan="4"><input name="txtcodcarper" type="text" id="txtcodcarper"  size="50" style="text-align:justify" readonly value="<?php print $ls_carper?>">           </td>
	      </tr>
		  <tr>
		 <td height="22"><div align="right">Fecha Registro</div></td>
          <td height="22" colspan="2"><input name="txtfecha" type="text" id="txtfecha"  size="16" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		
		 <tr>
          <td height="22" align="left"><div align="right">Objetivo Funcional de la Unidad </div></td>
          <td height="22" colspan="4"><textarea name="txtobj" cols="86"  rows="5" id="txtobj" style="text-align:justify"  ><?php print $ls_obj?></textarea></td>
        </tr>
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Datos del Evaluador</td>
        </tr>
        <tr>
          <td width="76" height="22">&nbsp;</td>
          
          <td height="22" colspan="4">&nbsp;</td>
        <tr>
			  <td height="22" align="left"><div align="right">C&oacute;digo Evaluador</div></td>
          <td height="22" colspan="2"><input name="txtcodeva" type="text" id="txtcodeva"  maxlength="10" style="text-align:center"  readonly value="<? print $ls_codeva?>" >   <a href="javascript:catalogo_evaluador();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
		  </tr>
		 		       
        <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="2"><input name="txtnomeva" type="text" id="txtnomeva"  maxlength="50" style="text-align:justify" size="50" readonly value="<? print $ls_nomeva?>">           </td>
        </tr>
         <tr>
             <td height="22"><div align="right">Cargo </div></td>
          <td height="22" colspan="4" valign="middle"><input name="txtcodcareva" type="text" size="50" id="txtcodcareva" value="<? print $ls_codcareva?>" readonly>
            </a></td>
        </tr>
		
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		<tr>
          <td><div align="right"></div></td>
          <td width="194"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="9">Primera Revisi&oacute;n</td>
        </tr>
        <tr>
          <td width="108" height="22">&nbsp;</td>
          
          <td height="22" colspan="4">&nbsp;</td>
        </tr>
		 <tr>
		 <td height="22"><div align="right">Del</div></td>
          <td height="22" colspan="2"><input name="txtfecini1" type="text" id="txtfecini1"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecini1?>"> 
          <input name="reset" type="reset" onClick="return showCalendar('txtfecini1', '%d/%m/%Y');" value=" ... " />          </td> 			<td width="72" height="22"><div align="right">Al</div></td>
          <td width="304" height="22" colspan="2"><input name="txtfecfin1" type="text" id="txtfecfin1"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecfin1?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecfin1', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		<tr>
          <td height="22" colspan="4">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="9">Segunda Revisi&oacute;n</td>
        </tr>
        <tr>
          <td width="108" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		 <tr>
		 <td height="22"><div align="right">Del</div></td>
          <td height="22" colspan="2"><input name="txtfecini2" type="text" id="txtfecini2"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecini2?>"> 
          <input name="reset" type="reset" onClick="return showCalendar('txtfecini2', '%d/%m/%Y');" value=" ... " />          </td> 			<td width="72" height="22"><div align="right">Al</div></td>
          <td width="304" height="22" colspan="2"><input name="txtfecfin2" type="text" id="txtfecfin2"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecfin2?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecfin2', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		<tr>
          <td width="108" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
          <td colspan="9">
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
		 <td height="22" align="left"><div align="right"> Total Peso</div></td>
	     <td height="22" colspan="4"><input name="txttotal" type="text" id="txttotal" maxlength=3 size="7" style="text-align:center"   value="<?php print $li_total?>" readonly >            </td>
	      </tr>
      </table>	 
      <p>&nbsp;</p>
     
 </td> 
</table>
 <input type="hidden" id="higuardar2">
 <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
  <p>
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
	<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="">
		       
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	 


  </p>

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>


