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
require_once("class_funciones_seguridad.php");
$io_fun_seguridad=new class_funciones_seguridad();
$io_fun_seguridad->uf_load_seguridad("SSS","sigespwindow_sss_derecho_grupo.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_habilitadaSi,$ls_habilitadaNo,$ls_buscarchk,$ls_incluirchk,$ls_modificarchk,$ls_eliminarchk,$ls_administradorchk;
		global $ls_procesarchk,$ls_imprimirchk,$ls_anularchk,$ls_pantalla,$ls_nomsis,$ls_nomven,$ls_nomusu,$ls_apeusu;
		
		$ls_habilitadaSi = "";
		$ls_habilitadaNo = "";
		$ls_buscarchk="";
		$ls_incluirchk="";
		$ls_modificarchk="";
		$ls_eliminarchk="";
		$ls_procesarchk="";
		$ls_imprimirchk="";
		$ls_anularchk="";
		$ls_administradorchk="";
		$ls_pantalla="";
		$ls_nomsis="";
		$ls_nomven="";
		$ls_nomusu="";
		$ls_apeusu="";
   }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
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
	/*
	function A()
	{
		window.onerror=B
		parent.window.opener.focus();
		window.focus();
	}
	function B()
	{
		var url = document.location.href;
		partes = url.split('/');
		pagina=partes[partes.length-1];
		sistema=partes[partes.length-2];
		alert("No ha iniciado sesión para esta ventana");
		parent.location.href=url.replace(sistema+"/"+pagina,"pagina_blanco.php");
		return true;
	} 
	A();*/
</script>

<style type="text/css">
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
-->
</style></head>
<?php	

	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("sigesp_sss_c_derechousuarios.php");
	$io_sss= new sigesp_sss_c_derechousuarios();
	require_once("../shared/class_folder/class_sql.php");
	$ls_codusu=   $_SESSION["la_ususeg"];
	$ls_codemp=   $_SESSION["la_empresa"]["codemp"];
	$ls_codsis=   $_SESSION["la_sistema"]["sistema"];
	$ls_usugrup=   $_SESSION["la_tipo_usugrup"];
	uf_limpiarvariables();
	if($ls_usugrup=='U')
	{
		$lb_existe=$io_sss->uf_sss_load_usuario($ls_codusu,$ls_nomusu,$ls_apeusu);
	}
	else
	{
		$lb_existe=$io_sss->uf_sss_load_grupo($ls_codusu,$ls_nomusu,$ls_apeusu);
	}
	$ls_nomsis=$io_fun_seguridad->uf_obtenervalor("txtnombresis","");
	if($ls_nomsis=="")
	{
		$lb_existe=$io_sss->uf_sss_load_sistema($ls_codsis,$ls_nomsis);
	}

	if (array_key_exists("operacion",$_POST))
	{	
		$ls_operacion=$_POST["operacion"];
		$ls_pantalla= $_POST["txtpantalla"];
		$ls_nomven=   $_POST["txtnombrefisico"];
		$ls_codintper="---------------------------------";
		$la_codintper[1]= "";
		if($ls_usugrup=='U')
		{
			$lb_existe=$io_sss->uf_sss_load_permisos($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,$li_enabled,$li_leer,$li_incluir,
												 	$li_cambiar,$li_eliminar,$li_imprimir,$li_anular,$li_ejecutar,$li_administrador);
		}
		else
		{
			$lb_existe=$io_sss->uf_sss_load_permisos_grupo($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,$li_enabled,$li_leer,$li_incluir,
												 		$li_cambiar,$li_eliminar,$li_imprimir,$li_anular,$li_ejecutar,$li_administrador);
		}
		if($lb_existe)
		{
			if ($li_enabled=="0")
			{
				$ls_habilitadaNo = "checked";
				$ls_habilitadaSi = "";
			}
			else
			{
				$ls_habilitadaSi = "checked";
				$ls_habilitadaNo = "";
			}
			if($li_leer==1)    { $ls_buscarchk = "checked";}
			else               { $ls_buscarchk ="";}
			if($li_incluir==1) {$ls_incluirchk = "checked";}
			else               {$ls_incluirchk ="";}
			if($li_cambiar==1) { $ls_modificarchk = "checked"; }
			else               { $ls_modificarchk ="";	}
			if($li_eliminar==1){ $ls_eliminarchk = "checked"; }
			else               { $ls_eliminarchk ="";	}
			if($li_imprimir==1){ $ls_imprimirchk = "checked"; }
			else               { $ls_imprimirchk ="";	}
			if($li_anular==1)  { $ls_anularchk = "checked"; }
			else               { $ls_anularchk ="";	}
			if($li_ejecutar==1){ $ls_procesarchk = "checked"; }
			else               { $ls_procesarchk ="";	}
			if($li_administrador==1){ $ls_administradorchk = "checked"; }
			else                    { $ls_administradorchk ="";	}
		}
		else
		{
			$ls_habilitadaNo = "checked";
			$ls_habilitadaSi = "";
		}
	}
	else
	{
		$ls_operacion="";
	}

	if ($ls_operacion=="GUARDAR")
	{
		if( ($ls_codsis=="")||($ls_pantalla==""))
		{
			$io_msg->message("No se ha seleccionado ninguna pantalla");
			uf_limpiarvariables();
		}
		else
		{
			$lb_existe=$io_sss->uf_sss_select_sistema($ls_codemp,$ls_codsis);
			if($lb_existe)
			{
				if(array_key_exists("radioenable",$_POST))
				{
					$li_habilitada=$_POST["radioenable"];
					$li_visible="0";
					$li_leer=     $io_fun_seguridad->uf_obtenervalor("chkbuscar",0);
					$li_incluir=  $io_fun_seguridad->uf_obtenervalor("chkincluir",0);
					$li_cambiar=  $io_fun_seguridad->uf_obtenervalor("chkmodificar",0);
					$li_eliminar= $io_fun_seguridad->uf_obtenervalor("chkeliminar",0);
					$li_anular=   $io_fun_seguridad->uf_obtenervalor("chkanular",0);
					$li_imprimir= $io_fun_seguridad->uf_obtenervalor("chkimprimir",0);
					$li_ejecutar= $io_fun_seguridad->uf_obtenervalor("chkprocesar",0);
					$li_administrador= $io_fun_seguridad->uf_obtenervalor("chkadministrador",0);
					if(($ls_codsis=="SNO")||($ls_codsis=="SPG"))
					{
						$lb_existe=$io_sss->uf_select_permisos_internos($ls_codemp,$ls_codsis,$ls_codusu,$la_codintper,$ls_usugrup);
						if($lb_existe)
						{
							$li_totalcodigos=count($la_codintper);
							for($li_j=1;$li_j<=$li_totalcodigos;$li_j++)
							{
								$ls_codintper=$la_codintper[$li_j];
								if($ls_codintper!="")
								{
									if($ls_usugrup=='U')
									{
										$lb_existe=$io_sss->uf_sss_select_derecho_usuario($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																					  $ls_codintper);
										if($lb_existe)
										{
											$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																							  $li_visible,$li_habilitada,$li_leer,$li_incluir,
																							  $li_cambiar,$li_eliminar,$li_imprimir,
																							  $li_administrador,$li_anular,$li_ejecutar,
																							  $ls_codintper,$la_seguridad);
										}
										else
										{
											$lb_existe=$io_sss->uf_sss_select_ventana($ls_codsis,$ls_nomven);
											if(!$lb_existe)
											{
												$ls_descripcion="";
												$lb_valido=$io_sss->uf_sss_insert_ventana($ls_codsis,$ls_nomven,$ls_pantalla,$ls_descripcion);
											}//end->else si existe ventana
											$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																							  $li_visible,$li_habilitada,$li_leer,$li_incluir,
																							  $li_cambiar,$li_eliminar,$li_imprimir,
																							  $li_administrador,$li_anular,$li_ejecutar,
																							  $ls_codintper,$la_seguridad);
										}//end->else si existe usuario
									}
									else
									{
										$lb_existe=$io_sss->uf_sss_select_derecho_grupo($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven);
										if($lb_existe)
										{
											$lb_valido=$io_sss->uf_sss_update_derecho_grupo($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																							  $li_visible,$li_habilitada,$li_leer,$li_incluir,
																							  $li_cambiar,$li_eliminar,$li_imprimir,
																							  $li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
										}
										else
										{
											$lb_existe=$io_sss->uf_sss_select_ventana($ls_codsis,$ls_nomven);
											if(!$lb_existe)
											{
												$ls_descripcion="";
												$lb_valido=$io_sss->uf_sss_insert_ventana($ls_codsis,$ls_nomven,$ls_pantalla,$ls_descripcion);
											}//end->else si existe ventana
											$lb_valido=$io_sss->uf_sss_insert_derecho_grupo($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																							  $li_visible,$li_habilitada,$li_leer,$li_incluir,
																							  $li_cambiar,$li_eliminar,$li_imprimir,
																							  $li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
										}//end->else si existe usuario
									}
								}
							}
						}
						else
						{
							if($ls_codsis=='SNO')
							{$io_msg->message("El usuario o grupo no tiene ninguna Nomina asociada");}
							if($ls_codsis=='SPG')
							{$io_msg->message("El usuario o grupo no tiene ninguna Estructura Presupuestaria asociada");}
							$lb_valido=false;
						}
					}
					else
					{
						//$lb_valido=$io_sss->uf_sss_insert_permisos_internos($ls_codemp,$ls_codusu,$ls_codsis,$ls_codintper,
						//													$la_seguridad,$ls_usugrup);
						/*if($lb_valido)
						{*/
							
							if($ls_usugrup=='U')
							{
								$lb_valido=$io_sss->uf_sss_insert_permisos_internos($ls_codemp,$ls_codusu,$ls_codsis,$ls_codintper,
																			$la_seguridad,$ls_usugrup);
								$lb_existe=$io_sss->uf_sss_select_derecho_usuario($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																			  $ls_codintper);
								if($lb_existe)
								{
									$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																					  $li_visible,$li_habilitada,$li_leer,$li_incluir,
																					  $li_cambiar,$li_eliminar,$li_imprimir,
																					  $li_administrador,$li_anular,$li_ejecutar,
																					  $ls_codintper,$la_seguridad);
								}
								else
								{
									$lb_existe=$io_sss->uf_sss_select_ventana($ls_codsis,$ls_nomven);
									if(!$lb_existe)
									{
										$ls_descripcion="";
										$lb_valido=$io_sss->uf_sss_insert_ventana($ls_codsis,$ls_nomven,$ls_pantalla,$ls_descripcion);
									}//end->else si existe ventana
									$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																					  $li_visible,$li_habilitada,$li_leer,$li_incluir,
																					  $li_cambiar,$li_eliminar,$li_imprimir,
																					  $li_administrador,$li_anular,$li_ejecutar,
																					  $ls_codintper,$la_seguridad);
								}//end->else si existe usuario
							}
							else
							{
								$lb_existe=$io_sss->uf_sss_select_derecho_grupo($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven);
								if($lb_existe)
								{
									$lb_valido=$io_sss->uf_sss_update_derecho_grupo($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																					  $li_visible,$li_habilitada,$li_leer,$li_incluir,
																					  $li_cambiar,$li_eliminar,$li_imprimir,
																					  $li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
								}
								else
								{
									$lb_existe=$io_sss->uf_sss_select_ventana($ls_codsis,$ls_nomven);
									if(!$lb_existe)
									{
										$ls_descripcion="";
										$lb_valido=$io_sss->uf_sss_insert_ventana($ls_codsis,$ls_nomven,$ls_pantalla,$ls_descripcion);
									}//end->else si existe ventana
									$lb_valido=$io_sss->uf_sss_insert_derecho_grupo($ls_codemp,$ls_codusu,$ls_codsis,$ls_nomven,
																					  $li_visible,$li_habilitada,$li_leer,$li_incluir,
																					  $li_cambiar,$li_eliminar,$li_imprimir,
																					  $li_administrador,$li_anular,$li_ejecutar,$la_seguridad);
								}//end->else si existe usuario
							}							
						//}
					}
					if($lb_valido)
					{
						$io_msg->message("El permiso fue procesado");
						uf_limpiarvariables();
	
					}
					else
					{
						$io_msg->message("No se pudo procesar el permiso");
					}
				}
			}
			else
			{
				$io_msg->message("No existe el sistema");
			}				
		}
	}
?>
<body>
<div align="center"></div>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_seguridad->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"parent.location.href='sigespwindow_blank.php'");
	unset($io_fun_seguridad);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="561" height="195" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="559" height="13" class="titulo-celda">Opciones del Men&uacute; </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="154"><table width="509" height="210" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="13" colspan="10"align="center" class="titulo-celda">Propiedades del <?php if($ls_usugrup=='U'){print "Usuario ";}else {print "Grupo ";}print $ls_nomusu ." ". $ls_apeusu ?></td>
        </tr>
        <tr>
          <td height="18"></td>
          <td width="89"><input name="txtusuario" type="hidden" class="sin-borde" id="txtusuario3" value="<?php print $ls_codusu ?>" size="15">
            <input name="txttipousugrup" type="hidden" id="txttipousugrup" value="<?php print $ls_usugrup;?>"></td>
          <td colspan="2"><input name="operacion" type="hidden" id="operacion4">
            <input name="txtradio" type="hidden" id="txtradio4">
            <input name="txtnombrefisico" type="hidden" id="txtnombrefisico4" value="<?php print $ls_nomven ?>"></td>
          <td width="99">&nbsp;                </td>
          <td colspan="2"><div align="right"></div></td>
          <td colspan="2"><input name="valor" type="hidden" id="valor3"></td>
          <td width="58">&nbsp;</td>
        </tr>
        <tr>
          <td width="28" height="18">&nbsp;</td>
          <td><div align="right">Sistema</div></td>
          <td height="22" colspan="5">              
            <div align="left">
              <input name="txtnombresis" type="text" id="txtnombresis" value="<?php print $ls_nomsis ?>" size="35">
            </div>            
            <div align="left">              </div></td>
          <td colspan="2"><input name="txtsistema" type="hidden" id="txtsistema" value="<?php print $ls_codsis ?>" size="10"></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="18">&nbsp;</td>
          <td><div align="right">Pantalla</div></td>
          <td height="22" colspan="7"><input name="txtpantalla" type="text" id="txtpantalla3" value="<?php print $ls_pantalla ?>" size="50" readonly="true"></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="13">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="13" colspan="10" align="center" class="titulo-celda">Derechos</td>
        </tr>
        <tr>
          <td height="13">&nbsp;</td>
          <td colspan="5"></td>
          <td colspan="4"></td>
          </tr>
        <tr>
          <td height="13">&nbsp;</td>
          <td height="22" colspan="5"><div align="center">&iquest;Tiene acceso a la pantalla? 
                
                S&iacute;
                <input name="radioenable" type="radio" class="sin-borde" value="1" <?php echo $ls_habilitadaSi ?>>
        No
        <input name="radioenable" type="radio" class="sin-borde" value="0" <?php echo $ls_habilitadaNo ?>>
          </div></td>
          <td colspan="4">&nbsp;</td>
          </tr>
        <tr>
          <td height="13">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td height="13" colspan="10" align="center" class="titulo-celda">Permisos</td>
          </tr>
        <tr>
          <td height="13" colspan="10"><table width="387" border="0" align="center">
              <tr>
                <td height="22"><div align="right">Todos
                    <input name="chkall" type="checkbox" class="sin-borde" id="chkall" onClick="javascript: ue_checkall();" value="1">
                    </div>                </td>
                <td height="22"><div align="right">Buscar
                  <input name="chkbuscar" type="checkbox" class="sin-borde" id="chkbuscar" value="1" <?php echo $ls_buscarchk; ?>>
                </div></td>
                <td height="22"><div align="right">Incluir
                  <input name="chkincluir" type="checkbox" class="sin-borde" id="chkincluir" value="1" <?php echo $ls_incluirchk; ?>>
                </div></td>
                <td height="22"><div align="right">Modificar
                  <input name="chkmodificar" type="checkbox" class="sin-borde" id="chkmodificar" value="1" <?php echo $ls_modificarchk; ?>>
                </div></td>
                </tr>
              <tr>
                <td width="92" height="22"><div align="right">Eliminar
                  <input name="chkeliminar" type="checkbox" class="sin-borde" id="chkeliminar" value="1" <?php echo $ls_eliminarchk; ?>>
                </div></td>
                <td width="92" height="22"><div align="right">Procesar
                  <input name="chkprocesar" type="checkbox" class="sin-borde" id="chkprocesar" value="1" <?php echo $ls_procesarchk; ?>>
                </div></td>
                <td width="92" height="22"><div align="right">Imprimir
                  <input name="chkimprimir" type="checkbox" class="sin-borde" id="chkimprimir" value="1" <?php echo $ls_imprimirchk; ?>>
                </div></td>
                <td width="93" height="22"><div align="right">Anular
                  <input name="chkanular" type="checkbox" class="sin-borde" id="chkanular" value="1" <?php echo $ls_anularchk; ?>>
                </div></td>
                </tr>
                    </table>            </td>
     	  </tr>
        <tr>
          <td height="13">&nbsp;</td>
          <td>&nbsp;</td>
          <td width="34">&nbsp;</td>
          <td width="172">
		  <?php
		  if(($ls_codsis=="SOC")||($ls_codsis=="SEP"))
		  {
		  ?>
		  	<div align="right">Modificaci&oacute;n Administrador            
            	<input name="chkadministrador" type="checkbox" class="sin-borde" id="chkadministrador" value="1" <?php echo $ls_administradorchk; ?>>
          	</div>
		  <?php
		  }
		  ?>
		  </td>
          <td colspan="6"><div align="right" class="sin-borde" ><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar</a></div></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
  
</form>
  <script language="JavaScript"> 
   var valor = 0; 

	function ue_guardar()
	{
		f=document.form1;
		li_cambiar=f.cambiar.value;
		if(li_cambiar==1)
		{	
			f.operacion.value="GUARDAR";
			f.action="principal_sss_p_derechousuario.php";
			f.submit();	
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}

   function actualizaValor(oRad)
   { 
		valor = oRad.value; 
		parent.mainFrame.document.form1.txtradio.value=valor;
   } 

   function leeValor()
   { 
		with (document.frm) 
		 res.value = valor; 
   } 
   
	//--------------------------------------------------------
	//	Función que al momento de marcar el checkbox de "Todos" se marcan
	//	todos los demas checkbox y se desmarcan en caso contrario
	//--------------------------------------------------------
	function ue_checkall()
	{
		f=document.form1;
		if(f.chkall.checked==true)
		{
			f.chkincluir.checked= true;
			f.chkbuscar.checked= true;
			f.chkmodificar.checked= true;
			f.chkeliminar.checked= true;
			f.chkprocesar.checked= true;
			f.chkimprimir.checked= true;
			f.chkanular.checked= true;
			codsis=f.txtsistema.value;
			if((codsis=="SOC")||(codsis=="SEP"))
			{
				f.chkadministrador.checked= true;
			}

		}
		else
		{
			f.chkincluir.checked= false;
			f.chkbuscar.checked= false;
			f.chkmodificar.checked= false;
			f.chkeliminar.checked= false;
			f.chkprocesar.checked= false;
			f.chkimprimir.checked= false;
			f.chkanular.checked= false;
			if((codsis=="SOC")||(codsis=="SEP"))
			{
				f.chkadministrador.checked= false;
			}
		}
	}

  </script>
</body>

</html>
