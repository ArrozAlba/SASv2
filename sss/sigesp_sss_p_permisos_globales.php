<?php
session_start();
ini_set('max_execution_time ','0');
ini_set('memory_limit','32M');
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_seguridad.php");
$io_fun_seguridad=new class_funciones_seguridad();
$io_fun_seguridad->uf_load_seguridad("SSS","sigesp_c_permisos_globales.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Permisos por Sistemas </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
-->
</head>
<?php
	require_once("sigesp_sss_c_permisos_globales.php");
	$io_sss= new sigesp_sss_c_permisos_globales();
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
	$ls_usuario="";
	$la_ventanas="";
	if (array_key_exists("operacion",$_POST))
	{	
		$ls_operacion=$_POST["operacion"];
		$ls_usugrup=$_POST["rbusugrup"];	
		if($ls_usugrup=='U')	
		{
			$ls_chkusuario="checked";
			$ls_chkgrupo="";
			$ls_usuario=$_POST["cmbusuarios"];	
		}
		else
		{
			$ls_chkusuario="";
			$ls_chkgrupo="checked";	
			$ls_grupo=$_POST["cmbgrupos"];
		}
	}
	else
	{
		$ls_operacion="";
		$ls_usugrup='U';
		$ls_chkusuario="checked";
		$ls_chkgrupo="";
	}
	if (array_key_exists("hidsistema",$_POST))
	{	
		$ls_sistema=$_POST["hidsistema"];
	}
	$ls_sistemas=$io_fun_seguridad->uf_obtenervalor("cmbsistemas","");
	$ls_codintper="---------------------------------";
	$ls_auxcodintper="---------------------------------";
	$io_sss->uf_llenar_combo_sistemas($la_sistemas);
	if($ls_operacion=="GUARDAR")
	{
		$ls_sistemas=$_POST["cmbsistemas"];
		$li_administrador=0;
		$li_visible=0;
		$li_enabled=1;

		if(array_key_exists("chkbuscar",$_POST))
		{$li_leer=1;}else{$li_leer=0;}

		if(array_key_exists("chkincluir",$_POST))
		{$li_incluir=1;}else{$li_incluir=0;}
		
		if(array_key_exists("chkmodificar",$_POST))
		{$li_cambiar=1;}else{$li_cambiar=0;}

		if(array_key_exists("chkeliminar",$_POST))
		{$li_eliminar=1;}else{$li_eliminar=0;}

		if(array_key_exists("chkanular",$_POST))
		{$li_anular=1;}else{$li_anular=0;}

		if(array_key_exists("chkimprimir",$_POST))
		{$li_imprimir=1;}else{$li_imprimir=0;}

		if(array_key_exists("chkprocesar",$_POST))
		{$li_ejecutar=1;}else{$li_ejecutar=0;}
		if($li_ejecutar==0 && $li_imprimir==0 && $li_anular==0 && $li_eliminar==0 && $li_cambiar==0 && $li_incluir==0 && $li_leer==0)
		{
			$li_enabled=0;
		}
		$io_sss->io_sql->begin_transaction();
		$lb_existe=$io_sss->uf_select_sistemas($ls_sistemas,$la_ventanas);
		if($lb_existe)
		{
			if($ls_usugrup=='U')//Agregado para diferenciar si se asignan permisos por grupo o por usuario
			{
				if(($ls_sistemas=="SNO")||($ls_sistemas=="SPG"))
				{
					$lb_existe=$io_sss->uf_select_permisos_internos($ls_codemp,$ls_sistemas,$ls_usuario,$la_codintper);
					if($lb_existe)
					{
						$li_totalcodigos=count($la_codintper["codintper"]);
						for($li_j=1;$li_j<=$li_totalcodigos;$li_j++)
						{
							$ls_codintper=$la_codintper["codintper"][$li_j];
							$li_total=count($la_ventanas["nomven"]);
							for($li_i=1;$li_i<=$li_total;$li_i++)
							{		
								$ls_nomven=$la_ventanas["nomven"][$li_i];
								$lb_valido=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_sistemas,$ls_nomven,$ls_codintper);	
								if ($lb_valido)
								{
									$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_sistemas,$ls_nomven,$li_visible,
																					  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																					  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,
																					  $ls_codintper,$la_seguridad);
								}
								else
								{
									$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_sistemas,$ls_nomven,$li_visible,
																					  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																					  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,
																					  $ls_codintper,$la_seguridad);
								}
	
	/*							$lb_existe=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_sistemas,$ls_nomven,$ls_auxcodintper);	
								if (!$lb_existe)
								{
									$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_sistemas,$ls_nomven,$li_visible,
																					  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																					  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,
																					  $ls_auxcodintper,$la_seguridad);
								}
	*/							if(!$lb_valido)
								{
									break;
								}
							}
							
						}
					}
					else
					{
						if($ls_sistemas=='SNO')
						{$io_msg->message("El usuario no tiene ninguna Nomina asociada");}
						if($ls_sistemas=='SPG')
						{$io_msg->message("El usuario no tiene ninguna Estructura Presupuestaria asociada");}
						$lb_valido=false;
					}
				
				}
				else
				{
					$li_total=count($la_ventanas["nomven"]);
					$lb_valido=$io_sss->uf_sss_insert_permisos_internos($ls_codemp,$ls_usuario,$ls_sistemas,$ls_codintper,$la_seguridad);
					if($lb_valido)
					{
						for($li_i=1;$li_i<=$li_total;$li_i++)
						{		
							$ls_nomven=$la_ventanas["nomven"][$li_i];
							$lb_existe=$io_sss->uf_select_derechos_usuarios($ls_codemp,$ls_usuario,$ls_sistemas,$ls_nomven,$ls_codintper);	
							if ($lb_existe)
							{
								$lb_valido=$io_sss->uf_sss_update_derecho_usuario($ls_codemp,$ls_usuario,$ls_sistemas,$ls_nomven,$li_visible,
																				  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																				  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,
																				  $ls_codintper,$la_seguridad);
							}
							else
							{
								$lb_valido=$io_sss->uf_sss_insert_derecho_usuario($ls_codemp,$ls_usuario,$ls_sistemas,$ls_nomven,$li_visible,
																				  $li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																				  $li_imprimir,$li_administrador,$li_anular,$li_ejecutar,
																				  $ls_codintper,$la_seguridad);
							}
							if(!$lb_valido)
							{
								break;
							}
						}
					}
				}
				if($lb_valido)
				{
					$io_sss->uf_sss_insert_seguridad($ls_codemp,$ls_usuario,$ls_sistemas,$la_seguridad);
					$io_msg->message("Los permisos por sistema fueron procesados.");
					$io_sss->io_sql->commit();
				}
				else
				{
					$io_msg->message("No se procesaron permisos por sistemas.");
					$io_sss->io_sql->rollback();
				}
			}
			elseif($ls_usugrup='G')//Agregado para el manejo por grupos
			{
				$li_total=count($la_ventanas["nomven"]);
				for($li_i=1;$li_i<=$li_total;$li_i++)
				{		
					$ls_nomven=$la_ventanas["nomven"][$li_i];
					$lb_existe=$io_sss->uf_select_derechos_grupo($ls_codemp,$ls_grupo,$ls_sistemas,$ls_nomven);	
					if ($lb_existe)
					{
						$lb_valido=$io_sss->uf_sss_update_derecho_grupo($ls_codemp,$ls_grupo,$ls_sistemas,$ls_nomven,$li_visible,
																		$li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		$li_imprimir,$li_administrador,$li_anular,$li_ejecutar,
																		$la_seguridad);																								 
					}
					else
					{
						$lb_valido=$io_sss->uf_sss_insert_derecho_grupo($ls_codemp,$ls_grupo,$ls_sistemas,$ls_nomven,$li_visible,
																		$li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																		$li_imprimir,$li_administrador,$li_anular,$li_ejecutar,
																		$la_seguridad);
					}
					if(!$lb_valido)
					{
						
						break;
					}
				}
				if($lb_valido)
				{
					$io_sss->uf_sss_insert_seguridad($ls_codemp,$ls_grupo,$ls_sistemas,$la_seguridad);					
					$io_msg->message("Los permisos por sistema al grupo $ls_grupo fueron procesados.");
					$io_sss->io_sql->commit();
				}
				else
				{
					$io_msg->message("No se procesaron permisos por sistemas al grupo $ls_grupo.");
					$io_sss->io_sql->rollback();
				}
			}				
		}
		else
		{
			$io_msg->message("No existe el sistema.");
		}
	}
?>
<body>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_seguridad->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_seguridad);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <div align="center"><br>
  </div>
  <div align="center">
    <table width="47%" height="32" border="0" cellpadding="0" cellspacing="0"  class="formato-blanco">
      <tr >
        <td height="17"  class="titulo-celda">Permisos por Sistemas</td>
      </tr>
      <tr>
        <td height="13"><table width="458" height="197" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="19"><input name="hidsist" type="hidden" id="hidsist3" value="<?php print $ls_sistemas?>" size="6">
              <input name="operacion" type="hidden" id="operacion"></td>
            <td height="19" colspan="3">              Usuario
              <input name="rbusugrup" type="radio" value="U" <?php print $ls_chkusuario;?> onChange="javascript:uf_verificar_usugrup();"> 
              Grupo
              <input name="rbusugrup" type="radio" value="G" <?php print $ls_chkgrupo;?> onChange="javascript:uf_verificar_usugrup();"></td>
            </tr>
          <tr>
            <td width="121" height="18"><div align="right">Sistema</div></td>
            <td height="24" colspan="3" align="left"><?php $io_sss->uf_pintar_combo_sistemas($la_sistemas,$ls_sistemas);?>
               <div align="center"></div></td>
          </tr>
          <tr>
            <td height="19"><div align="right">Usuario/Grupo</div></td>
            <td height="24" colspan="3" align="left"><?php  if($ls_usugrup=='U'){$io_sss->uf_llenar_combo_usuarios($la_usuarios);$io_sss->uf_pintar_combo_usuarios($la_usuarios,$ls_usuario);}else{	$io_sss->uf_llenar_combo_grupos($la_grupos);$io_sss->uf_pintar_combo_grupos($la_grupos,$ls_grupo);}?>
              <div align="center"></div>
              <div align="center"></div></td>
          </tr>
          <tr>
            <td height="21" colspan="4" class="titulo-celda">Permisos</td>
            </tr>
          <tr>
            <td colspan="4"><table width="378" border="0" align="center">
              <tr>
                <td height="22"><div align="right">Todos
                    <input name="chkall" type="checkbox" class="sin-borde" id="chkall" onClick="javascript: ue_checkall();" value="1" onChange="">
</div></td>
                <td height="22"><div align="right">Buscar
                    <input name="chkbuscar" type="checkbox" class="sin-borde" id="chkbuscar" value="1" <?php echo $ls_buscarchk ?>>
</div></td>
                <td height="22"><div align="right">Incluir
                    <input name="chkincluir" type="checkbox" class="sin-borde" id="chkincluir" value="1" <?php echo $ls_incluirchk ?>>
</div></td>
                <td height="22"><div align="right">Modificar
                    <input name="chkmodificar" type="checkbox" class="sin-borde" id="chkmodificar" value="1" <?php echo $ls_modificarchk ?>>
</div></td>
                </tr>
              <tr>
                <td width="85" height="22"><div align="right">Eliminar
                    <input name="chkeliminar" type="checkbox" class="sin-borde" id="chkeliminar" value="1" <?php echo $ls_eliminarchk ?>>
</div></td>
                <td width="85" height="22"><div align="right">Procesar
                    <input name="chkprocesar" type="checkbox" class="sin-borde" id="chkprocesar" value="1" <?php echo $ls_procesarchk ?>>
</div></td>
                <td width="85" height="22"><div align="right">Imprimir
                    <input name="chkimprimir" type="checkbox" class="sin-borde" id="chkimprimir" value="1" <?php echo $ls_imprimirchk ?>>
</div></td>
                <td width="85" height="22">                <div align="right">Anular
                    <input name="chkanular" type="checkbox" class="sin-borde" id="chkanular" value="1" <?php echo $ls_anularchk ?>>
                </div></td>
                </tr>
            </table></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td width="93">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td width="71">&nbsp;</td>
            <td width="171">&nbsp;</td>
            <td><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools15/ejecutar.gif" width="15" height="15" border="0">Procesar</a></td>
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
			if(f.rbusugrup[0].checked==true)
			{
				ls_usuario = f.cmbusuarios.value;
			}
			else if(f.rbusugrup[1].checked==true)
			{
				ls_usuario = f.cmbgrupos.value;
			}
			ls_sistemas= f.cmbsistemas.value;
			lb_valido=false;
			ls_codintper="";
			if(f.chkbuscar.checked==true)
			{lb_valido=true}
			if(f.chkincluir.checked==true)
			{lb_valido=true}
			if(f.chkmodificar.checked==true)
			{lb_valido=true}
			if(f.chkeliminar.checked==true)
			{lb_valido=true}
			if(f.chkprocesar.checked==true)
			{lb_valido=true}
			if(f.chkimprimir.checked==true)
			{lb_valido=true}
			if(f.chkanular.checked==true)
			{lb_valido=true}
		
			if((ls_usuario!="---")&&(ls_sistemas!="---"))
			{
				if(lb_valido)
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_sss_p_permisos_globales.php";
					f.submit();
				}
				else
				{
					if(confirm("¿Seguro desea crear accesos sin definir ningun permiso?"))
					{
						f.operacion.value="GUARDAR";
						f.action="sigesp_sss_p_permisos_globales.php";
						f.submit();
					}
				}
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
//--------------------------------------------------------
//	Función que al momento de marcar el checkbox de "Todos" se marcan
//	todos los demas checkbox y se desmarcan en caso contrario
//--------------------------------------------------------
function ue_checkall()
{
	f=document.form1;
	if(f.chkall.checked==true)
	{
		f.chkincluir.checked=true;
		f.chkbuscar.checked=true;
		f.chkmodificar.checked=true;
		f.chkeliminar.checked=true;
		f.chkprocesar.checked=true;
		f.chkimprimir.checked=true;
		f.chkanular.checked=true;
	}
	else
	{
		f.chkincluir.checked=false;
		f.chkbuscar.checked=false;
		f.chkmodificar.checked=false;
		f.chkeliminar.checked=false;
		f.chkprocesar.checked=false;
		f.chkimprimir.checked=false;
		f.chkanular.checked=false;
	}
}
 
function ue_buscarcodigo()
{
	f=document.form1;
	ls_codsis=f.cmbsistemas.value;
	if(ls_codsis=="SNO")
	{
		window.open("sigesp_sss_cat_nominas.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	if(ls_codsis=="SPG")
	{
		window.open("sigesp_sss_cat_est_prog.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_habilitar()
{
	f=document.form1;
	ls_codsis=f.cmbsistemas.value;
	if((ls_codsis="SNO")||(ls_codsis="SPG"))
	{
		f.action="sigesp_sss_p_permisos_globales.php";
		f.submit();
	}

}

function uf_verificar_usugrup(obj)
{	
	f=document.form1;
	f.submit();
}
</script>

</html>
