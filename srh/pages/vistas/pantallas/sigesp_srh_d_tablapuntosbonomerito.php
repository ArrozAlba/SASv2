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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tablapuntosbonomerito.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_codesc,$ls_denesc,$ls_codtipper, $ls_dentipper,$li_valunitri,$ls_hidstatus,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codesc="";
		$ls_denesc="";
		$ls_codtipper="";
		$ls_dentipper="";
		$li_valunitri="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Detalles de la Puntuación por Unidad Tributaria";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="Promedio de Puntos";
		$lo_title[2]="Unidad Tributaria";
		$lo_title[3]="Monto en Bs.";
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
		$punto='"."';
		$coma='","';
		
		$aa_object[$ai_totrows][1]="<input name=txtprompun".$ai_totrows." type=text id=txtprompun".$ai_totrows." class=sin-borde size=15 maxlength=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  >";
		$aa_object[$ai_totrows][2]="<input name=txtunitri".$ai_totrows." type=text id=txtunitri".$ai_totrows." size=15 maxlength=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  class=sin-borde >";
		$aa_object[$ai_totrows][3]="<input name=txtmonbs".$ai_totrows." type=text id=txtmonbs".$ai_totrows." class=sin-borde size=10 readonly onFocus='javascript: multiplicar(".$ai_totrows.");'   >";
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
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 27/11/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_prompun,$li_unitri,$li_monbs;

		$li_prompun=$_POST["txtprompun".$li_i];
		$li_unitri=$_POST["txtunitri".$li_i];
		$li_monbs=$_POST["txtmonbs".$li_i];
	
   }
   //--------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Bono por M&eacute;rito seg&uacute;n Unidad Tributaria </title>
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

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_tablapuntosbonomerito.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../../shared/js/number_format.js"></script>




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
<style type="text/css">
<!--
.style1 {color: #EBEBEB}
.Estilo1 {
	color: #6699CC;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 12px;
}
-->
</style>
</head>

<body onLoad="javascript: ue_nuevo_codigo();">

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_tablapuntosbonomerito.php");
	$io_puntos=new sigesp_srh_c_tablapuntosbonomerito("../../../../");
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
		 	$ls_codesc=$_POST["txtcodesc"];
			$ls_denesc=$_POST["txtdenesc"];
			$ls_codtipper=$_POST["txtcodtipper"];
			$ls_dentipper=$_POST["txtdentipper"];
			$li_valunitri=$_POST["txtvaluntri"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_hidstatus=$_POST["hidstatus"];
			$ls_activarcodigo="readOnly";
			$punto='"."';
			$coma='","';
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				$lo_object[$li_i][1]="<input name=txtprompun".$li_i." type=text id=txtprompun".$li_i." class=sin-borde size=15 maxlength=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  value=".$li_prompun." >";
				$lo_object[$li_i][2]="<input name=txtunitri".$li_i." type=text id=txtunitri".$li_i." size=15 maxlength=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'   class=sin-borde value=".$li_unitri." >";
				$lo_object[$li_i][3]="<input name=txtmonbs".$li_i." type=text id=txtmonbs".$li_i." class=sin-borde size=10   readonly value=".$li_monbs." onFocus='javascript: multiplicar(".$li_i.");'>";
				$lo_object[$li_i][4]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_codesc=$_POST["txtcodesc"];
			$ls_denesc=$_POST["txtdenesc"];
			$ls_codtipper=$_POST["txtcodtipper"];
			$ls_dentipper=$_POST["txtdentipper"];
			$li_valunitri=$_POST["txtvaluntri"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_hidstatus=$_POST["hidstatus"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			$punto='"."';
			$coma='","';
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=($li_rowdelete))
				{		
					$li_temp++;			
					uf_cargar_dt($li_i);
					$lo_object[$li_temp][1]="<input name=txtprompun".$li_temp." type=text id=txtprompun".$li_temp." class=sin-borde size=15 maxlength=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  value=".$li_prompun." >";
					$lo_object[$li_temp][2]="<input name=txtunitri".$li_temp." type=text id=txtunitri".$li_temp." size=15 maxlength=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'   class=sin-borde value=".$li_unitri." >";
					$lo_object[$li_temp][3]="<input name=txtmonbs".$li_temp." type=text id=txtmonbs".$li_temp." class=sin-borde size=10 onFocus='javascript: multiplicar(".$li_temp.");' readonly value= ".$li_monbs." >";
					$lo_object[$li_temp][4]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
					$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
					
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_codesc=$_POST["txtcodesc"];
			$ls_denesc=$_POST["txtdenesc"];
			$ls_codtipper=$_POST["txtcodtipper"];
			$ls_dentipper=$_POST["txtdentipper"];
			$li_valunitri=$_POST["txtvaluntri"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_hidstatus=$_POST["hidstatus"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_puntos->uf_srh_load_puntos_campos($ls_codesc,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}

?>





<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../../shared/imagebank/header.jpg" width="778" height="40"></td>
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
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
 <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
 <table width="600" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
 	 <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
 	 <tr>
    <td height="20" colspan="3" class="titulo-celdanew">Definici&oacute;n de Bono por M&eacute;rito seg&uacute;n Unidad Tributaria </td>
  </tr>
  <tr class="formato-blanco">
    <td width="150" height="19">&nbsp;</td>
    <td colspan="2"><div id="resultado" ></div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="29"><div align="right">C&oacute;digo</div></td>
    <td width="409" height="29"><input name="txtcodesc" type="text" id="txtcodesc" value="<?php print $ls_codesc?>" size="5" maxlength="15"  readonly style="text-align:center " >
        <input name="hidstatus" type="hidden" id="hidstatus" size="2"  value="<?php print $ls_hidstatus ?>">    </td>
    <td width="7" class="sin-borde"><div id="existe" class="letras-pequeÃ±as" style="display:none">
    
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Denominaci&oacute;n</div></td>
    <td height="28" colspan="2"><input name="txtdenesc" type="text" id="txtdenesc" value="<?php print $ls_denesc?>" onKeyUp="ue_validarcomillas(this);" size="60" maxlength="254"></td>
  </tr>
   
 <tr class="formato-blanco">
    <td height="28"><div align="right">Tipo Personal</div></td>
    <td height="28" colspan="2"><input name="txtcodtipper" type="text" id="txtcodtipper"    size="5" maxlength="3" value="<?php print $ls_codtipper?>" style="text-align:center" readonly> <a href="javascript:catalogo_tipo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Factores de Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
      <input name="txtdentipper" type="text" class="sin-borde" id="txtdentipper" size="60s" maxlength="80" readonly value="<?php print $ls_dentipper?>">
    </td>
          
		</tr>
 
  <tr class="formato-blanco">
    <td height="28"><div align="right">Valor Unidad Tributaria Presupuestada</div></td>
    <td height="28" colspan="2"><input name="txtvaluntri" type="text" id="txtvalunitri"  value="<?php print $li_valunitri?>" size="15" onKeyPress="return(ue_formatonumero(this,'.',',',event))"></td>
  </tr>
   <tr class="formato-blanco">
    <td width="150" height="19">&nbsp;</td>
    <td colspan="2"><div id="resultado" ></div></td>
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
			  <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
			  <input name="hidcontrol3" type="hidden" id="hidcontrol3" value="">
			</p>			</td>		  
          </tr>
		 
		  
      </table>
      <tr>
		  <td  >&nbsp;</td> 
		  </tr>
		   <tr>
		  <td  >&nbsp;</td> 
		  </tr>
  </table>
</div>
<div align="center"></div>
<p align="center" class="style1" id="mostrar" style="font:#EBEBEB" ></p>
</body>

</html>