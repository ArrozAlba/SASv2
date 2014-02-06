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
require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_d_configuracion.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_selected,$ls_selectedfifo,$ls_selectedlifo,$ls_selectedcpp,$ls_checksig;
		global $ls_checknum,$ls_checkcont,$ls_checkalfnum;
		
		$ls_selected="";
		$ls_selectedfifo="";
		$ls_selectedlifo="";
		$ls_selectedcpp="";
		$ls_checksig="";
		$ls_checknum="";
		$ls_checkcont="";
		$ls_checkalfnum="";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Configuraci&oacute;n de Inventario</title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14px;
	color: #6699CC;
}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Inventario</span></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
   <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=  new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("sigesp_sim_c_configuracion.php");
	$io_siv= new sigesp_sim_c_configuracion();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("class_funciones_inventario.php");
	$io_funciones_inventario= new class_funciones_inventario();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="NUEVO";
		$ls_readonly="readonly";
	}
	
	
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$li_estnum="";
			$lb_existe= $io_siv->uf_sim_load_configuracion($ls_metodo,$li_estcatsig,$li_estnum);
			if($lb_existe)
			{
				$ls_metodo=trim($ls_metodo);
				switch ($ls_metodo)
				{
					case"":
						$ls_selected="selected";
					break;
					case"FIFO":
						$ls_selectedfifo="selected";
					break;
					case"LIFO":
						$ls_selectedlifo="selected";
					break;
					case"CPP":
						$ls_selectedcpp="selected";
					break;
				}
				if($li_estcatsig==1)
				{$ls_checksig="checked";}
				if($li_estnum==1)
				{$ls_checknum="checked";}
				else
				{$ls_checkalfnum="checked";}
			}
			else
			{$ls_selected="selected";}
			
			$lb_existe= $io_siv->uf_sim_load_configuraciondespacho($ls_codemp,$li_estcont);
			if($lb_existe)
			{
				if($li_estcont==1)
				{$ls_checkcont="checked";}
			}
		break;
		
		case "GUARDAR";
			uf_limpiarvariables();
			$ls_valido= false;
			$li_estcatsig=$io_funciones_inventario->uf_obtenervalor("chksigecof",0);
			$ls_metodo=$io_funciones_inventario->uf_obtenervalor("cmbmetodo","");
			$li_estnum=$io_funciones_inventario->uf_obtenervalor("rdcodigo","");
			$li_estcont=$io_funciones_inventario->uf_obtenervalor("chkcontabilizar",0);
			switch ($ls_metodo)
			{
				case"":
					$ls_selected="selected";
				break;
				case"FIFO":
					$ls_selectedfifo="selected";
				break;
				case"LIFO":
					$ls_selectedlifo="selected";
				break;
				case"CPP":
					$ls_selectedcpp="selected";
				break;
			}
			if($li_estcatsig==1)
			{$ls_checksig="checked";}
			if($li_estnum==1)
			{$ls_checknum="checked";}
			else
			{$ls_checkalfnum="checked";}
			if($li_estcont==1)
			{$ls_checkcont="checked";}
			$ls_id="1";
			$ls_status=true;
			
			if($ls_metodo=="--")
			{$io_msg->message("Debe seleccionar un Método");}
			else
			{
				if ($ls_status)
				{
					$lb_existe= $io_siv->uf_sim_select_configuracion($ls_id,$ls_metodo);
					if ($lb_existe)
					{
						$ld_date= date("d/m/Y");
						$lb_existe=$io_siv->uf_sim_select_articulos($ls_codemp);
						if($lb_existe)
						{
							$lb_existe=$io_siv->uf_sim_select_movimientos($ls_codemp);
							if($lb_existe)
							{
								$lb_pervalido=$io_fec->uf_valida_fecha_periodo($ld_date,$ls_codemp);
								if(!$lb_pervalido)
								{
									$lb_valido=$io_siv->uf_sim_procesar_configuracion($ls_id,$ls_metodo,$li_estcatsig,$li_estnum,$la_seguridad);
									if($lb_valido)
									{$io_msg->message("La configuración de inventario fue actualizada.");}	
									else
									{$io_msg->message("La configuración de inventario no pudo ser actualizada");}
								}
								else
								{$io_msg->message("Ya existe un metodo de Inventario para este periodo");}
									
							}
							else
							{
								$lb_valido=$io_siv->uf_sim_procesar_configuracion($ls_id,$ls_metodo,$li_estcatsig,$li_estnum,$la_seguridad);
								if($lb_valido)
								{$io_msg->message("La configuración de inventario fue actualizada.");}	
								else
								{$io_msg->message("La configuración de inventario no pudo ser actualizada");}
							}
						}
						else
						{
							$lb_valido=$io_siv->uf_sim_procesar_configuracion($ls_id,$ls_metodo,$li_estcatsig,$li_estnum,$la_seguridad);
			
							if($lb_valido)
							{$io_msg->message("La configuración de inventario fue actualizada.");}	
							else
							{$io_msg->message("La configuración de inventario no pudo ser actualizada");}
						}
					}
					else
					{
						$lb_valido=$io_siv->uf_sim_procesar_configuracion($ls_id,$ls_metodo,$li_estcatsig,$li_estnum,$la_seguridad);
						if($lb_valido)
						{$io_msg->message("La configuración de inventario fue actualizada.");}	
						else
						{$io_msg->message("La configuración de inventario no pudo ser actualizada");}
					}
				}
				$lb_valido=$io_siv->uf_sim_procesar_configuraciondespacho($ls_codemp,$li_estcont,$la_seguridad);
				if($lb_valido)
				{$io_msg->message("El estaus de contabilización de despacho ha sido actualizado");}
				else
				{$io_msg->message("No se pudo actualizar el estaus de contabilización de despacho");}
			}
		break;

	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="512" height="134" border="0" class="formato-blanco">
    <tr>
      <td width="538" height="130"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="454" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="2" class="titulo-ventana">Configuraci&oacute;n de Inventario</td>
  </tr>
  <tr class="formato-blanco">
    <td width="135" height="13">&nbsp;</td>
    <td>
      <div align="left"></div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">M&eacute;todo de Inventario</div></td>
    <td><div align="left">
      <select name="cmbmetodo" id="cmbmetodo">
        
        <option value="--" <?php print $ls_selected ?>>-- Seleccione Uno --</option>
        <option value="FIFO" <?php print $ls_selectedfifo ?>>FIFO - PEPS</option>
        <option value="LIFO" <?php print $ls_selectedlifo ?>>LIFO - UEPS</option>
        <option value="CPP" <?php print $ls_selectedcpp ?>>Costo Promedio Ponderado</option>
      </select>
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Codificaci&oacute;n de Productos </div></td>
    <td><div align="left">
      <input name="rdcodigo" type="radio" class="sin-borde" value="0" <?php print $ls_checkalfnum ?>>
      Alfanum&eacute;rico
      <input name="rdcodigo" type="radio" class="sin-borde" value="1" <?php print $ls_checknum ?>>
      Num&eacute;rico</div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22">&nbsp;</td>
    <td>
      <p align="left">
        <input name="chksigecof" type="checkbox" class="sin-borde" id="chksigecof" value="1" <?php print $ls_checksig ?>>
Usar Cat&aacute;logo SIGECOF </p>    </td>
    </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right"></div></td>
    <td><div align="left">
      <input name="chkcontabilizar" type="checkbox" class="sin-borde" id="chkcontabilizar" value="1" <?php print $ls_checkcont ?>>
     Contabilizar Despachos </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
function ue_mostrar(ls_id,ls_accion)
{
	f=document.form1;
	if(ls_accion==1)
	{
		document.getElementById(ls_id).style.visibility="visible";
	}
	else
	{
		document.getElementById(ls_id).style.visibility="hidden";	
	}
}

//Funciones de operaciones sobre el comprobante
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_sim_d_configuracion.php";
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		if(f.rdcodigo[0].checked==true)
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sim_d_configuracion.php";
			f.submit();
		}
		else
		{
		    alert("Debe tildar una opcion Alfanumerico ó Numerico");
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