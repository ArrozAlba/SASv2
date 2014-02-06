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
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
$io_fun_viaticos->uf_load_seguridad("SCV","sigesp_scv_d_transporte.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codtra,$ls_dentra,$ls_selected,$ls_selectedmar,$ls_selectedaer,$ls_selectedter,$ls_existe;
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows;
		
		$ls_codtra="";
		$ls_dentra="";
		$ls_selected="";
		$ls_selectedmar="";
		$ls_selectedaer="";
		$ls_selectedter="";
		$ls_existe="FALSE";
		$ls_titletable="Transporte";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Denominación";
		$lo_title[3]="";
		$li_totrows=1;
   }
   function uf_agregarlineablanca(&$ao_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private 
		//      Argumento: $ai_totrows // total de filas del grid
		//  			   $ao_object  // arreglo de objetos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que pinta una linea en blanco en el grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ao_object[$ai_totrows][1]="<input  name=txtcodtra".$ai_totrows."  type=text id=txtcodtra".$ai_totrows."  class=sin-borde size=10 maxlength=3 readonly>";
		$ao_object[$ai_totrows][2]="<input  name=txtdentra".$ai_totrows."  type=text id=txtdentra".$ai_totrows."  class=sin-borde size=60 maxlength=254 readonly>";
		$ao_object[$ai_totrows][3]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
   }
   	//--------------------------------------------------------------
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Transporte</title>
<meta http-equiv="imagetoolbar" content="no"> 
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
		<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Control de Viaticos </td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("../shared/class_folder/grid_param.php");
	$in_grid=new grid_param();
	require_once("class_folder/sigesp_scv_c_transporte.php");
	$io_scv=new sigesp_scv_c_transporte();

	$ls_codemp=$_SESSION["la_empresa"]["codemp"];

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="NUEVO";
		uf_limpiarvariables();
		uf_agregarlineablanca($lo_object,1);
	}
	$lb_empresa=true;
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_codtra=$io_fun->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_transportes','codtra');
			$ls_estatus="NUEVO";
			$ls_denominacion="";
		break;
		
		case "GUARDAR";
		
		$ls_valido= false;
		$ls_readonly="";
		$ls_status=$io_fun_viaticos->uf_obtenervalor("hidstatus","");
		$ls_codtra=$io_fun_viaticos->uf_obtenervalor("txtcodtra","");
		$ls_codtiptra=$io_fun_viaticos->uf_obtenervalor("cmbcodtiptra","");
		$ls_dentra=$io_fun_viaticos->uf_obtenervalor("txtdentra","");
		$li_tartra=$io_fun_viaticos->uf_obtenervalor("txttartra","");
		$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
		$li_tartraaux= str_replace(".","",$li_tartra);
		$li_tartraaux= str_replace(",",".",$li_tartraaux);
		$ls_selected="selected";
/*		switch ($ls_codtiptra)
		{
			case "01";
				$ls_selectedaer="selected";
			break;
			case "02";
				$ls_selectedmar="selected";
			break;
			case "03";
				$ls_selectedter="selected";
			break;
			case "";
				$ls_selected="selected";
			break;
		}*/

		if( ($ls_codtra=="")||($ls_codtiptra=="--")||($ls_dentra==""))
			{
				$io_msg->message("Debe completar todos los campos");
			}
		else
			{
				if ($ls_status=="C")
				{
					$lb_valido=$io_scv->uf_scv_update_transporte($ls_codemp,$ls_codtra,$ls_codtiptra,$ls_dentra,$la_seguridad);
					if($lb_valido)
					{
						$io_msg->message("El transporte fue actualizado");
						uf_limpiarvariables();
						$ls_codtra=$io_fun->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_transportes','codtra');
					}	
					else
					{
						$io_msg->message("El transporte no pudo ser actualizado");
					}
				}
				else
				{
					//$lb_encontrado=$io_scv->uf_scv_select_transporte($ls_codemp,$ls_codtra,$ls_codtiptra);
					if ($ls_existe=="TRUE")
					{
						$io_msg->message("El transporte ya existe"); 
					}
					else
					{
						$lb_valido=$io_scv->uf_scv_insert_transporte($ls_codemp,$ls_codtra,$ls_codtiptra,$ls_dentra,$li_tartraaux,$la_seguridad);

						if ($lb_valido)
						{
							$io_msg->message("El transporte  fue registrado.");
							uf_limpiarvariables();
							$lb_valido=$io_scv->uf_scv_load_transporte($ls_codemp,$ls_codtiptra,$li_totrows,$lo_object);
							$ls_codtra=$io_fun->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_transportes','codtra');
						}
						else
						{
							$io_msg->message("No se pudo registrar el Transporte");
						}
					
					}
				}
				
			}
		break;

		case "ELIMINAR":
			$ls_status=$io_fun_viaticos->uf_obtenervalor("hidstatus","");
			$ls_codtra=$io_fun_viaticos->uf_obtenervalor("txtcodtra","");
			$ls_codtiptra=$io_fun_viaticos->uf_obtenervalor("cmbcodtiptra","");
			$ls_status=$io_fun_viaticos->uf_obtenervalor("hidstatus","");
			$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
			if($ls_status=="C")
			{
				$lb_valido=$io_scv->uf_scv_delete_transporte($ls_codemp,$ls_codtra,$ls_codtiptra,$la_seguridad);
				if($lb_valido)
				{
					$io_msg->message("El transporte fue eliminado");
					uf_limpiarvariables();
					$ls_codtra=$io_fun->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_transportes','codtra');
					$ls_readonly="readonly";
				}	
				else
				{
					$io_msg->message($io_scv->is_msg_error);
					$io_msg->message("No se pudo eliminar el transporte");
					uf_limpiarvariables();
					$ls_codtra=$io_fun->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_transportes','codtra');
				}
			}
		break;
		
		case "CARGARLISTADO":
			uf_limpiarvariables();
			$ls_codtra=$io_fun_viaticos->uf_obtenervalor("txtcodtra","");
			$ls_codtiptra=$io_fun_viaticos->uf_obtenervalor("cmbcodtiptra","");
			$li_totrows=$io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
			switch ($ls_codtiptra)
			{
				case "01";
					$ls_selectedaer="selected";
				break;
				case "02";
					$ls_selectedmar="selected";
				break;
				case "03";
					$ls_selectedter="selected";
				break;
				case "";
					$ls_selected="selected";
				break;
			}
			if($ls_codtiptra!="")
			{
				$lb_valido=$io_scv->uf_scv_load_transporte($ls_codemp,$ls_codtiptra,$li_totrows,$lo_object);
			}
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="575" height="264" border="0" class="formato-blanco">
    <tr>
      <td width="600" height="258"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_viaticos->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_viaticos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="514" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="22" colspan="2" class="titulo-ventana">Definici&oacute;n  de Transporte </td>
  </tr>
  <tr class="formato-blanco">
    <td width="143" height="13">&nbsp;</td>
    <td width="352">&nbsp;</td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">C&oacute;digo</div></td>
    <td height="22"><div align="left">
        <input name="txtcodtra" type="text" id="txtcodtra" onBlur="javascript: ue_rellenarcampo(this,2);"  onKeyPress="return keyRestrict(event,'1234567890'); " value="<?php print $ls_codtra?>" size="8" maxlength="4" style="text-align:center " readonly>
        <input name="hidstatus" type="hidden" id="hidstatus">
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Tipo</div></td>
    <td height="22"><select name="cmbcodtiptra" id="cmbcodtiptra">
      <option value="--" <?php print $ls_selected?>>-- Seleccione --</option>
      <option value="01" <?php print $ls_selectedaer?>>A&eacute;reo</option>
      <option value="02" <?php print $ls_selectedmar?>>Mar&iacute;timo</option>
      <option value="03" <?php print $ls_selectedter?>>Terrestre</option>
    </select></td>
  </tr>
  <tr class="formato-blanco">
    <td height="19"><div align="right">Denominaci&oacute;n</div></td>
    <td rowspan="2">
      <textarea name="txtdentra" cols="55" id="txtdentra"></textarea>    </td>
  </tr>
  <tr class="formato-blanco">
    <td height="11">&nbsp;</td>
  </tr>
  <tr>
    <td height="22"><div align="right">Tarifa</div></td>
    <td height="22"><input name="txttartra" type="text" id="txttartra" size="20" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event));"></td>
  </tr>
  <tr>
    <td height="22" colspan="2"><div align="center">
      <?php
/*		$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
*/	  ?>
    </div>
      <div align="left"></div></td>
    </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
		  <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones
function ue_cata()
{
	window.open("sigesp_cat_personal.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarlistado()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{	
		f.operacion.value="CARGARLISTADO";
		f.action="sigesp_scv_d_transporte.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
 	ls_destino="DEFINICION";
	if(li_leer==1)
	{
		window.open("sigesp_scv_cat_transporte.php?destino="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_scv_d_transporte.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_actualizar(ls_codtra,ls_dentra)
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	if(li_cambiar==1)
	{
		f.txtcodtra.value=ls_codtra;
		f.txtdentra.value=ls_dentra;
		f.hidstatus.value="C";
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_scv_d_transporte.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(confirm("¿Seguro desea eliminar el Registro?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_scv_d_transporte.php";
			f.submit();
		}
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