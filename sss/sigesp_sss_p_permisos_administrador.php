<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_seguridad.php");
$io_fun_activo=new class_funciones_seguridad();
$io_fun_activo->uf_load_seguridad("SSS","sigesp_c_permisos_globales.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
{	
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Permisos de Administrador del Sistema</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<div ID="waitDiv" style="position:absolute;left:300;top:300;visibility:hidden"> 
<table cellpadding="6" cellspacing="0" border="1" bgcolor="#000000" bordercolor="#FFFFFF"> 
<tr><td align=center> 
<font color="#ffffff" face="Verdana" size="4">Cargando página...</font> 
</td> 
</tr></table> 
</div> 
</head>
<?php
	
	require_once("sigesp_sss_c_permisos_administrador.php");
	$io_sss= new sigesp_sss_c_permisos_administrador();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();

	$ls_buscarchk="";
	$ls_incluirchk="";
	$ls_modificarchk="";
	$ls_eliminarchk="";
	$ls_procesarchk="";
	$ls_imprimirchk="";
	$ls_anularchk="";
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	if (array_key_exists("hidsistema",$_POST))
	{	
		$ls_sistema=$_POST["hidsistema"];
	}
	$io_sss->uf_llenar_combo_usuarios($la_usuarios);
	$ls_sistemas="";
	$ls_usuario="";
	$la_ventanas="";
	if($ls_operacion=="GUARDAR")
	{
		$ls_usuario=$_POST["cmbusuarios"];
		$li_administrador=0;
		$li_visible=0;
		$li_enabled=1;

		$li_leer=1;
		$li_incluir=1;
		$li_cambiar=1;
		$li_eliminar=1;
		$li_anular=1;
		$li_imprimir=1;
		$li_ejecutar=1;

		
		$lb_existe=$io_sss->uf_select_sistemasventanas($la_ventanas);
		if($lb_existe)
		{
			$li_total=count($la_ventanas["nomven"]);
			//$li_total=$li_total/2;
			if($li_total<=250)
			{
				for($li_i=1;$li_i<=$li_total;$li_i++)
				{		
					$ls_codsis=$la_ventanas["codsis"][$li_i];
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven);	
					if ($lb_valido)
					{
						$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					else
					{
						$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					if(!$lb_valido)
					{break;}
				}
				if($lb_valido)
				{
					$io_msg->message("Los permisos de administrador fueron procesados.");
				}
				else
				{
					$io_msg->message("No se procesaron permisos de administrador.");
				}
			}
			if(($li_total>250)&&($li_total<500))
			{
				for($li_i=1;$li_i<=250;$li_i++)
				{		
					$ls_codsis=$la_ventanas["codsis"][$li_i];
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven);	
					if ($lb_valido)
					{
						$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					else
					{
						$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					if(!$lb_valido)
					{break;}
				}
				for($li_i=251;$li_i<=$li_total;$li_i++)
				{		
					$ls_codsis=$la_ventanas["codsis"][$li_i];
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven);	
					if ($lb_valido)
					{
						$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					else
					{
						$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					if(!$lb_valido)
					{break;}
				}
				if($lb_valido)
				{
					$io_msg->message("Los permisos de administrador fueron procesados.");
				}
				else
				{
					$io_msg->message("No se procesaron permisos de administrador.");
				}
				for($li_i=1;$li_i<=150;$li_i++)
				{		
					$ls_codsis=$la_ventanas["codsis"][$li_i];
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven);	
					if ($lb_valido)
					{
	/*					$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
	*/				}
					else
					{
	/*					$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
	*/				}
					if(!$lb_valido)
					{break;}
				}
				if($lb_valido)
				{
					$io_msg->message("Los permisos de administrador fueron procesados.");
				}
				else
				{
					$io_msg->message("No se procesaron permisos de administrador.");
				}
			}
			if(($li_total>500)&&($li_total<750))
			{
				for($li_i=1;$li_i<=250;$li_i++)
				{		
					$ls_codsis=$la_ventanas["codsis"][$li_i];
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven);	
					if ($lb_valido)
					{
						$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					else
					{
						$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					if(!$lb_valido)
					{break;}
				}
				for($li_i=251;$li_i<=500;$li_i++)
				{		
					$ls_codsis=$la_ventanas["codsis"][$li_i];
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven);	
					if ($lb_valido)
					{
						$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					else
					{
						$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					if(!$lb_valido)
					{break;}
				}
				for($li_i=501;$li_i<=$li_total;$li_i++)
				{		
					$ls_codsis=$la_ventanas["codsis"][$li_i];
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven);	
					if ($lb_valido)
					{
						$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					else
					{
						$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
					}
					if(!$lb_valido)
					{break;}
				}
				if($lb_valido)
				{
					$io_msg->message("Los permisos de administrador fueron procesados.");
				}
				else
				{
					$io_msg->message("No se procesaron permisos de administrador.");
				}
				for($li_i=1;$li_i<=150;$li_i++)
				{		
					$ls_codsis=$la_ventanas["codsis"][$li_i];
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven);	
					if ($lb_valido)
					{
	/*					$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
	*/				}
					else
					{
	/*					$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_codsis,$ls_nomven,$li_visible,
																		  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
	*/				}
					if(!$lb_valido)
					{break;}
				}
				if($lb_valido)
				{
					$io_msg->message("Los permisos de administrador fueron procesados.");
				}
				else
				{
					$io_msg->message("No se procesaron permisos de administrador.");
				}
			}
		}
		else
		{
			$io_msg->message("No existe registro de ventanas.");
		}
	}
?>
<body>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <div align="center"><br>
  </div>
  <div align="center">
    <table width="38%" height="32" border="0" cellpadding="0" cellspacing="0"  class="formato-blanco">
      <tr >
        <td height="17"  class="titulo-ventana"><span class="titulo-celdanew">Permisos de Administrador del Sistema</span></td>
      </tr>
      <tr>
        <td height="13"><table width="383" height="86" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="19" colspan="4"><input name="hidsist" type="hidden" id="hidsist3" value="<?php print $ls_sistemas?>" size="6">
              <input name="operacion" type="hidden" id="operacion">              </td>
            </tr>
          <tr>
            <td width="102" height="19"><div align="right">Usuario</div></td>
            <td height="22" colspan="3" align="left"><?php  $io_sss->uf_pintar_combo_usuarios($la_usuarios,$ls_usuario);?>
              <div align="center"></div>
              <div align="center"></div></td>
          </tr>
          <tr>
            <td height="22" colspan="4">
			</td>
            </tr>
          <tr>
            <td height="23">&nbsp;</td>
            <td width="87">&nbsp;</td>
            <td width="126">&nbsp;</td>
            <td width="68"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools15/ejecutar.gif" width="15" height="15" border="0">Procesar</a></td>
          </tr>
        </table></td>
      </tr>
    </table>
  </div>
  <div align="center"></div>
  <p>&nbsp;</p>
</form>
</body>
<script language="JavaScript">
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		ls_usuario = f.cmbusuarios.value;
		if(ls_usuario!="---")
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sss_p_permisos_administrador.php";
			f.submit();
		}
		else
		{
			alert("Debe indicar Usuario y Sistema.");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
</script>

</html>
