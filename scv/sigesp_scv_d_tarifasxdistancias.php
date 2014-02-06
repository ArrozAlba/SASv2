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
$io_fun_viaticos->uf_load_seguridad("SCV","sigesp_scv_d_tarifasxdistancias.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_codtar,$ls_dentar,$li_kmsdes,$li_kmshas,$li_montar,$ls_estatus,$ls_existe;

		$ls_codtar="";
		$ls_dentar="";
		$li_kmsdes="0,00";
		$li_kmshas="0,00";
		$li_montar="0,00";
		$ls_estatus="";
		$ls_existe="FALSE";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Tarifas por Distancias</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo6 {
	font-size: 11px;
	color: #6699CC;
}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="7" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" class="cd-menu">
		<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7"><span class="Estilo6">Control de Viaticos </span></td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="7" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td width="21" height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
    <td width="657" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php 
require_once("../shared/class_folder/sigesp_include.php");
$io_conect= new sigesp_include();
$conn=      $io_conect->uf_conectar();
require_once("../shared/class_folder/class_sql.php");
$io_sql= new class_sql($conn);
require_once("../shared/class_folder/class_datastore.php");
$io_dsclas= new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$io_funcion= new class_funciones();
require_once("../shared/class_folder/class_funciones_db.php");
$io_fundb= new class_funciones_db($conn);
require_once("../shared/class_folder/class_mensajes.php");
$io_msg= new class_mensajes();
require_once("class_folder/sigesp_scv_c_tarifasxdistancias.php");
$io_scv= new sigesp_scv_c_tarifasxdistancias($conn);
$ls_codemp=$_SESSION["la_empresa"]["codemp"];
$lb_existe= "";
uf_limpiarvariables();
$ls_operacion=$io_fun_viaticos->uf_obteneroperacion();
$lb_empresa= true;

switch ($ls_operacion) 
{
	case "NUEVO":
		$ls_codtar= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_tarifakms','codtar');
		if(empty($ls_codtar))
		{
			$io_msg->message($io_fundb->is_msg_error);
		}
	break;
	case "GUARDAR":
		$ls_codtar= $io_fun_viaticos->uf_obtenervalor("txtcodtar","");
		$ls_dentar= $io_fun_viaticos->uf_obtenervalor("txtdentar","");
		$li_kmsdes= $io_fun_viaticos->uf_obtenervalor("txtkmsdes","");
		$li_kmshas= $io_fun_viaticos->uf_obtenervalor("txtkmshas","");
		$li_montar= $io_fun_viaticos->uf_obtenervalor("txtmontar","");
		$ls_estatus=$io_fun_viaticos->uf_obtenervalor("hidestatus","");
		$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
		$li_kmsdes= str_replace(".","",$li_kmsdes);
		$li_kmsdes= str_replace(",",".",$li_kmsdes);
		$li_kmshas= str_replace(".","",$li_kmshas);
		$li_kmshas= str_replace(",",".",$li_kmshas);
		$li_montar= str_replace(".","",$li_montar);
		$li_montar= str_replace(",",".",$li_montar);
		$lb_existe=$io_scv->uf_scv_select_tarifa($ls_codemp,$ls_codtar);
		if ($ls_estatus=="C")
		{
			$lb_valido=$io_scv->uf_scv_update_tarifa($ls_codemp,$ls_codtar,$ls_dentar,$li_kmsdes,$li_kmshas,$li_montar,
													 $la_seguridad); 
			if($lb_valido)
			{
				$io_msg->message("La Tarifa fue actualizada");
				uf_limpiarvariables();
				$ls_codtar= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_tarifakms','codtar');
			}	
			else
			{
				$io_msg->message("La Tarifa no pudo ser actualizada");
			}
		}
		else
		{
			if ($ls_existe=="TRUE")
			{
				$io_msg->message("La Tarifa ya existe"); 
			}
			else
			{
				$lb_valido=$io_scv->uf_scv_insert_tarifa($ls_codemp,$ls_codtar,$ls_dentar,$li_kmsdes,$li_kmshas,$li_montar,
														 $la_seguridad);

				if ($lb_valido)
				{
					$io_msg->message("La Tarifa fue registrada");
					uf_limpiarvariables();
					$ls_codtar= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_tarifakms','codtar');
				}
				else
				{
					$io_msg->message("No se pudo registrar la Tarifa");
				}
			
			}
		}
	break;
	case "ELIMINAR":
		$ls_codtar=$io_fun_viaticos->uf_obtenervalor("txtcodtar","");
		$lb_existe=$io_scv->uf_scv_select_tarifa($ls_codemp,$ls_codtar);
		if ($lb_existe)
		{
			$lb_valido=$io_scv->uf_scv_delete_tarifa($ls_codemp,$ls_codtar,$la_seguridad);
			if ($lb_valido)
			{
				$io_msg->message("La Tarifa fue Eliminada"); 
				uf_limpiarvariables();
				$ls_codtar= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_tarifakms','codtar');
			}
			else
			{
				$io_msg->message($io_scv->is_msg_error);
				$io_msg->message("No se pudo eliminar la Tarifa"); 
				uf_limpiarvariables();
				$ls_codtar= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_tarifakms','codtar');
			}
		}
		else
		{
			$io_msg->message("La Tarifa No Existe");
			uf_limpiarvariables();
			$ls_codtar= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_tarifakms','codtar');
		}	 
	break;
}
?>
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"></font></p>
<p align="center">&nbsp;</p>
<form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_viaticos->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_viaticos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="519" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="517" height="170"><div align="center">
        <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
          <tr class="titulo-celdanew">
            <td height="22" colspan="2" class="titulo-ventana">Definici&oacute;n de Tarifas por Distancias </td>
          </tr>
          <tr>
            <td height="22" ><div align="right"></div></td>
            <td height="22" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
			                 <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
          </tr>
          <tr>
            <td width="122" height="22" ><div align="right">C&oacute;digo</div></td>
            <td width="346" height="22" ><input name="txtcodtar" type="text" id="txtcodtar" value="<?php print  $ls_codtar; ?>" size="8" maxlength="4" onKeyPress="return keyRestrict(event,'1234567890');" style="text-align:center" onBlur="javascript: ue_rellenarcampo(this,4);" readonly>
            <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Denominaci&oacute;n</div></td>
            <td height="22"><input name="txtdentar" id="txtdentar" value="<?php print $ls_dentar; ?>" type="text" size="60" maxlength="100" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-');"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Desde</div></td>
            <td height="22"><input name="txtkmsdes" type="text" id="txtkmsdes" value="<?php print number_format($li_kmsdes,2,",","."); ?>" size="10" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right">
              Kms.</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Hasta</div></td>
            <td height="22"><input name="txtkmshas" type="text" id="txtkmshas" value="<?php print number_format($li_kmshas,2,",","."); ?>" size="10" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right">
              Kms.</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Monto</div></td>
            <td height="22"><input name="txtmontar" type="text" id="txtmontar" value="<?php print number_format($li_montar,2,",","."); ?>" size="14" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right"></td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
  </table>
  </div>
    </table>
  </div>
</form>
</body>

<script language="JavaScript">

function ue_nuevo()
{
   f=document.form1;
   li_incluir=f.incluir.value;	
   if(li_incluir==1)
   {			 
	  f.operacion.value="NUEVO";
	  f.action="sigesp_scv_d_tarifasxdistancias.php";
	  f.submit();
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
	evento=    f.hidestatus.value;
	if(((evento=="")&&(li_incluir==1))||(evento=="C")&&(li_cambiar==1))
	{  	
		with (document.form1)
		{
			if (campo_requerido(txtcodtar,"El codigo debe estar lleno")==false)
			{
				txtcodtar.focus();
			}
			else
			{
				if (campo_requerido(txtdentar,"La denominacion debe estar llena")==false)
				{
					txtdentar.focus();
				}
				else
				{
				    kmdes = txtkmsdes.value;
				    kmhas = txtkmshas.value;
				    kmdes = kmdes.replace ('.','');
				    kmdes = kmdes.replace (',','.');
				    kmhas = kmhas.replace ('.','');
				    kmhas = kmhas.replace (',','.');	
				    if (kmdes>kmhas)
					{
					  alert('Rango de kilometros inválido'); 
					}
					else
					{
						f=document.form1;
						f.operacion.value="GUARDAR";
						f.action="sigesp_scv_d_tarifasxdistancias.php";
						f.submit();
					}
				}
			}
		}	
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
	   if (f.txtcodtar.value=="")
	   {
		 alert("No ha seleccionado ningún registro para eliminar");
	   }
		else
		{
			if (confirm("¿Esta seguro de eliminar este registro?"))
			{ 
				f=document.form1;
				f.operacion.value="ELIMINAR";
				f.action="sigesp_scv_d_tarifasxdistancias.php";
				f.submit();
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

		
function campo_requerido(field,mensaje)
{
	with (field) 
	{
		if (value==null||value=="")
		{
			alert(mensaje);
			return false;
		}
		else
		{
			return true;
		}
	}
}
		
		
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
		 f.operacion.value="";		
		 ls_destino="DEFINICION";
		 pagina="sigesp_scv_cat_tarifasxdistancias.php?destino="+ls_destino+"";
		 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,left=50,top=50,resizable=yes,location=no");
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