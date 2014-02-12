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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_escalageneral.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		

   		global $ls_codesc,$ls_denesc,$li_valini, $li_valfin,$ls_hidstatus,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codesc="";
		$ls_denesc="";
		$li_valini="";
		$li_valfin="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Detalles de la Escala de Evaluación";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Descripcion";
		$lo_title[3]="Valor Inicial";
		$lo_title[4]="Valor Final";
		$lo_title[5]="Agregar";
		$lo_title[6]="Eliminar";
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
		$aa_object[$ai_totrows][1]="<input name=txtcoddetesc".$ai_totrows." type=text id=txtcoddetesc".$ai_totrows." class=sin-borde size=5 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);' >";
		$aa_object[$ai_totrows][2]="<input name=txtdendetesc".$ai_totrows." type=text id=txtdendetesc".$ai_totrows." onKeyUp='ue_validarcomillas(this);' class=sin-borde size=40 >";
		$aa_object[$ai_totrows][3]="<input name=txtvalinidetesc".$ai_totrows." type=text id=txtvalinidetesc".$ai_totrows." class=sin-borde size=7 maxlength=5  onKeyPress='return validarreal2(event,this);' onChange='javascript:valida_escalaini(this,txtvalini);'>";
		$aa_object[$ai_totrows][4]="<input name=txtvalfindetesc".$ai_totrows." type=text id=txtvalfindetesc".$ai_totrows." class=sin-borde size=7 maxlength=5 onKeyPress='return validarreal2(event,this);' onChange='javascript:valida_escalafin(this,txtvalfin);'>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_delete_dt(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
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
   		global $li_coddetesc,$ls_dendetesc,$li_valinidetesc,$li_valfindetesc;

		$li_coddetesc=$_POST["txtcoddetesc".$li_i];
		$ls_dendetesc=$_POST["txtdendetesc".$li_i];
		$li_valinidetesc=$_POST["txtvalinidetesc".$li_i];
		$li_valfindetesc=$_POST["txtvalfindetesc".$li_i];
		
   }
   //--------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Escala de Evalaci&oacute;n </title>
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_escalageneral.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../../sno/js/funcion_nomina.js"></script>



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
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>
</head>

<body onLoad="javascript: ue_nuevo_codigo();">

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_escalageneral.php");
	$io_escala=new sigesp_srh_c_escalageneral("../../../../");
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
			$li_valini=$_POST["txtvalini"];
			$li_valfin=$_POST["txtvalfin"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_hidstatus=$_POST["hidstatus"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				$lo_object[$li_i][1]="<input name=txtcoddetesc".$li_i." type=text id=txtcoddetesc".$li_i." class=sin-borde size=5 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_coddetesc."'>";
				$lo_object[$li_i][2]="<input name=txtdendetesc".$li_i." type=text id=txtdendetesc".$li_i." class=sin-borde size=40  onKeyUp='ue_validarcomillas(this);' value='".$ls_dendetesc."'  >";
				$lo_object[$li_i][3]="<input name=txtvalinidetesc".$li_i." type=text id=txtvalinidetesc".$li_i." class=sin-borde size=7 maxlength=5 onKeyPress='return validarreal2(event,this);' value='".$li_valinidetesc."' onChange='javascript:valida_escalaini(this,txtvalini);'>";
				$lo_object[$li_i][4]="<input name=txtvalfindetesc".$li_i." type=text id=txtvalfindetesc".$li_i." class=sin-borde size=7 maxlength=5 onKeyPress='return validarreal2(event,this);' value='".$li_valfindetesc."' onChange='javascript:valida_escalafin(this,txtvalfin);'>";
				$lo_object[$li_i][5]="<a href=javascript:uf_agregar_dt(".$li_i."); align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][6]="<a href=javascript:uf_delete_dt(".$li_i."); align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_codesc=$_POST["txtcodesc"];
			$ls_denesc=$_POST["txtdenesc"];
			$li_valini=$_POST["txtvalini"];
			$li_valfin=$_POST["txtvalfin"];
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
					$lo_object[$li_temp][1]="<input name=txtcoddetesc".$li_temp." type=text id=txtcoddetesc".$li_temp." class=sin-borde size=5 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_coddetesc."'>";
					$lo_object[$li_temp][2]="<input name=txtdendetesc".$li_temp." type=text id=txtdendetesc".$li_temp." class=sin-borde size=40  onKeyUp='ue_validarcomillas(this);' value='".$ls_dendetesc."'>";
					$lo_object[$li_temp][3]="<input name=txtvalinidetesc".$li_temp." type=text id=txtvalinidetesc".$li_temp." class=sin-borde size=7 maxlength=5 onKeyPress='return validarreal2(event,this);' value='".$li_valinidetesc."' onChange='javascript:valida_escalaini(this,txtvalini);' >";
					$lo_object[$li_temp][4]="<input name=txtvalfindetesc".$li_temp." type=text id=txtvalfindetesc".$li_temp." class=sin-borde size=7 maxlength=5 onKeyPress='return validarreal2(event,this);' value='".$li_valfindetesc."' onChange='javascript:valida_escalafin(this,txtvalfin);'>";
					$lo_object[$li_temp][5]="<a href=javascript:uf_agregar_dt(".$li_temp."); align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
					$lo_object[$li_temp][6]="<a href=javascript:uf_delete_dt(".$li_temp."); align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";	
					
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_codesc=$_POST["txtcodesc"];
			$ls_denesc=$_POST["txtdenesc"];
			$li_valini=$_POST["txtvalini"];
			$li_valfin=$_POST["txtvalfin"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_hidstatus=$_POST["hidstatus"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_escala->uf_srh_load_escala_campos($ls_codesc,$li_totrows,$lo_object);
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
    <td height="20" colspan="3" class="titulo-celdanew">Definici&oacute;n de Escala de Evaluaci&oacute;n </td>
  </tr>
  <tr class="formato-blanco">
    <td width="86" height="19">&nbsp;</td>
    <td colspan="2"><div id="resultado" ></div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="29"><div align="right">C&oacute;digo</div></td>
    <td width="478" height="29"><input name="txtcodesc" type="text" id="txtcodesc" value="<?php print $ls_codesc?>" size="16" maxlength="15"  readonly style="text-align:center " >
        <input name="hidstatus" type="hidden" id="hidstatus" size="2"  value="<?php print $ls_hidstatus ?>">    </td>
    <td width="2" class="sin-borde"><div id="existe" class="letras-pequeÃ±as" style="display:none">
    
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Denominaci&oacute;n</div></td>
    <td height="28" colspan="2"><input name="txtdenesc" type="text" id="txtdenesc" value="<?php print $ls_denesc?>" onKeyUp="ue_validarcomillas(this);" size="60" maxlength="254"></td>
  </tr>
   
  <tr class="formato-blanco">
    <td height="28"><div align="right">Valor Inicial</div></td>
    <td height="28" colspan="2"><input name="txtvalini" type="text" id="txtvalini"  value="<?php print $li_valini?>" size="6" maxlength="5" onKeyUp="javascript: ue_validarnumero(this);"></td>
  </tr>
 
  <tr class="formato-blanco">
    <td height="28"><div align="right">Valor Final</div></td>
    <td height="28" colspan="2"><input name="txtvalfin" type="text" id="txtvalfin"  onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_valfin?>" size="6" maxlength="5" onChange="javascript: valida_escala(txtvalini,this);"></td>
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
<script language="javascript">
//Funciones de operaciones 



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_escalageneral.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}



</script> 
</html>