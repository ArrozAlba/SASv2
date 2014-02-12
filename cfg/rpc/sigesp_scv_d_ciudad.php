<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("../class_folder/class_funciones_cfg.php");
$io_fun_viaticos=new class_funciones_cfg();
$io_fun_viaticos->uf_load_seguridad("CFG","sigesp_scv_d_ciudad.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Ciudades </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../scv/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="7" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" colspan="7" class="cd-menu"></td>
  </tr>
  <tr>
    <td height="13" colspan="7" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td width="21" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="../rpc/sigespwindow_blank.php"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
    <td width="657" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php 
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_scv_c_ciudad.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");

$io_conect    = new sigesp_include();
$conn		  = $io_conect->uf_conectar();
$io_sql       = new class_sql($conn);
$io_dsmun     = new class_datastore();
$io_funcion   = new class_funciones();
$io_scv       = new sigesp_scv_c_ciudad($conn);
$io_funciondb = new class_funciones_db($conn);
$io_msg       = new class_mensajes();
$io_chkrel    = new sigesp_c_check_relaciones($conn);

$lb_existe          = "";
$ls_operacion       = $io_fun_viaticos->uf_obteneroperacion();
$ls_codpai			= $io_fun_viaticos->uf_obtenervalor("cmbpais","---");
$ls_codest			= $io_fun_viaticos->uf_obtenervalor("cmbestado","---");
$ls_codciu          = $io_fun_viaticos->uf_obtenervalor("txtcodciu","");
$ls_desciu          = $io_fun_viaticos->uf_obtenervalor("txtdesciu","");
$ls_estatus         = $io_fun_viaticos->uf_obtenervalor("hidestatus","");
$lr_datos["estado"] = $ls_codest;
$lr_datos["pais"]   = $ls_codpai;
$ls_codemp          = $_SESSION["la_empresa"]["codemp"];

	switch($ls_operacion)
	{
		case "NUEVO":
			$lb_empresa= false;
			if($ls_codest!="---")
			{
				$ls_codciu= $io_scv->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_ciudades','codciu',$ls_codpai,$ls_codest);
				if(empty($ls_codciu))
				{
					$io_msg->message($io_funciondb->is_msg_error);
				}
			}
		break;
		case "GUARDAR":
			$lb_existe=$io_scv->uf_scv_select_ciudad($ls_codpai,$ls_codest,$ls_codciu);
			if($ls_estatus=="C")
			{
				if($lb_existe)
				{
					$lb_valido=$io_scv->uf_scv_update_ciudad($ls_codpai,$ls_codest,$ls_codciu,$ls_desciu,$la_seguridad);
					if($lb_valido)
					{
						$io_msg->message("La Ciudad ha sido Actualizada");
						$ls_codpai="";
						$ls_codest="";
						$ls_codciu="";
						$ls_desciu="";
					}
					else
					{
						$io_msg->message("No se ha podido Actualizar la Ciudad");
					}
				}
				else
				{
					$io_msg->message("La Ciudad no Existe");
				}
			}
			else
			{
				if(!$lb_existe)
				{
					$lb_valido=$io_scv->uf_scv_insert_ciudad($ls_codpai,$ls_codest,$ls_codciu,$ls_desciu,$la_seguridad);
					if($lb_valido)
					{
						$io_msg->message("La Ciudad ha sido Registrada");
						$ls_codpai="";
						$ls_codest="";
						$ls_codciu="";
						$ls_desciu="";
					}
					else
					{
						$io_msg->message("No se ha podido Registrar la Ciudad");
					}
				}
				else
				{
					$io_msg->message("La Ciudad ya esta Registrada");
				}
			}
			
		break;
		case "ELIMINAR":
			$lb_existe=$io_scv->uf_scv_select_ciudad($ls_codpai,$ls_codest,$ls_codciu);
			if ($lb_existe)
			   {
			     $ls_condicion = " AND (column_name='codciu' OR column_name='codciuori' OR column_name='codciudes')";//Nombre del o los campos que deseamos buscar.
	             $ls_mensaje   = "";  //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
			     $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'scv_ciudades',$ls_codciu,$ls_mensaje);//Verifica los movimientos asociados a la cuenta
			     if (!$lb_tiene)
				    {
				      $lb_valido=$io_scv->uf_scv_delete_ciudad($ls_codpai,$ls_codest,$ls_codciu,$la_seguridad);
				      if ($lb_valido)
						 {
						   $io_sql->commit();
			               $io_msg->message("Registro Eliminado !!!");
						   $ls_codpai = "";
						   $ls_codest = "";
						   $ls_codciu = "";
						   $ls_desciu = "";
						 }
					  else
						 {
						   $io_sql->rollback();
			               $io_msg->message($io_scv->is_msg_error);
						 }
			        }
			     else
				    {
					  $io_msg->message($io_chkrel->is_msg_error);
					}
			   }
			else
			   {
				 $io_msg->message("La Ciudad no Existe");
			   }
		break;
	}
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_viaticos->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='../sigespwindow_blank.php'");
	unset($io_fun_viaticos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

    <table width="524" height="208" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="522" height="206"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td height="22" colspan="2" class="titulo-ventana">Registro de Ciudades </td>
            </tr>
            <tr>
              <td width="134" height="22">&nbsp;</td>
              <td width="334" height="22"><span class="style1">
                <input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
              </span></td>
            </tr>
            <tr>
              <td height="22" align="right"><span class="style2">Pais</span></td>
              <td height="22"><?php
            //Llenar Combo Pais
            $rs_mun=$io_scv->uf_llenarcombo_pais();
          	?>
                <select name="cmbpais" id="cmbpais" onChange="javascript:uf_cambiopais();"  style="width:150px ">
          <?php
		  while($row=$io_sql->fetch_row($rs_mun))
		  {
			   $ls_codpaiaux=$row["codpai"];
			   $ls_denpai=$row["despai"];
			   if ($ls_codpaiaux==$ls_codpai)
			   {
					print "<option value='$ls_codpaiaux' selected>$ls_denpai</option>";
			   }
			   else
			   {
					print "<option value='$ls_codpaiaux'>$ls_denpai</option>";
			   }
		 } 
	     ?>
                </select>
              <input name="hidpais" type="hidden" id="hidpais" value="<?php print $ls_codpai ?>"></td>
            </tr>
            <tr>
              <td height="22" Align="right"><span class="style2">Estado</span></td>
              <td height="22"><?php
          //Llenar Combo Estado
		  $rs_mun=$io_scv->uf_load_estados($ls_codpai);
		 ?>
                <select name="cmbestado" id="cmbestado" onChange="javascript:uf_cambioestado();"  style="width:150px ">
                  <option value="---">---seleccione---</option>
                  <?php
		 while($row=$io_sql->fetch_row($rs_mun))
		 {
			   $ls_codestaux=$row["codest"];
			   $ls_denest=$row["desest"];
			   if ($ls_codestaux==$ls_codest)
			   {
					print "<option value='$ls_codestaux' selected>$ls_denest</option>";
			   }
			   else
			   {
					print "<option value='$ls_codestaux'>$ls_denest</option>";
			   }
		 } 
	     ?>
                </select>
              <input name="hidestado" type="hidden" id="hidestado" value="<?php print $ls_codest ?>"></td>
            </tr>
            <tr>
              <td height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td height="22"><input name="txtcodciu" type="text" id="txtcodciu" value="<?php print  $ls_codciu ?>" size="6" maxlength="3" onKeyPress="return keyRestrict(event,'1234567890');"  onBlur="javascript:rellenar_cad(this.value,3)" style="text-align:center ">
                  <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
              </td>
            </tr>
            <tr>
              <td height="22" align="right"><span class="style2">Denominaci&oacute;n</span></td>
              <td height="22"><input name="txtdesciu" id="txtdesciu" value="<?php print $ls_desciu ?>" type="text" size="60" maxlength="60" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',.-');"></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
  
<script language="JavaScript">
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if (li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_scv_d_ciudad.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}   
}


function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidestatus.value;
	ls_codpais=f.cmbpais.value;
	ls_codest=f.cmbestado.value;
	if (ls_codpais!='---' && ls_codest!='---')
	{
		if (((lb_status=="C")&&(li_cambiar==1))||(lb_status=="")&&(li_incluir==1))
		{
			if (campo_requerido(f.txtcodciu,"Faltan campos por llenar")==false)
			{
				f.txtcodciu.focus();
			}
			else
			{ 
				if (campo_requerido(f.txtdesciu,"Faltan campos por llenar")==false)
				{
					f.txtdesciu.focus();
				}
				else
				{
					f=document.form1;
					f.operacion.value="GUARDAR";
					f.action="sigesp_scv_d_ciudad.php";
					f.submit();
				}
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operación");
		}
	}
	else
	{
		alert('Debe seleccionar una Ubicación Geográfica válida');
	}
}					
					
function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if (li_eliminar==1)
	{	
		if (f.txtcodciu.value=="")
		{
			alert("No ha seleccionado ningún registro para eliminar");
		}
		else
		{
			if (confirm("¿ Esta seguro de eliminar este registro ?"))
			{ 
				f=document.form1;
				f.operacion.value="ELIMINAR";
				f.action="sigesp_scv_d_ciudad.php";
				f.submit();
			}
		}	   
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
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
		
function rellenar_cad(cadena,longitud)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;
	
	total=longitud-lencad;
	for (i=1;i<=total;i++)
	{
		cadena_ceros=cadena_ceros+"0";
	}
	cadena=cadena_ceros+cadena;
	document.form1.txtcodciu.value=cadena;
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	{
		if (f.cmbpais.value=="---")
		{
			alert("Debe seleccionar un Pais y un Estado para ver el Catálogo de Ciudades");
		}
		else
		{
			if (f.cmbestado.value=="---")
			{
				alert("Debe seleccionar un Pais y un Estado para ver el Catálogo de Ciudades");
			}
			else 
			{   
				f.operacion.value="";
				ls_pais=f.hidpais.value;			 
				ls_estado=f.hidestado.value;			
				pagina="sigesp_scv_cat_ciudades.php?hidestado="+ls_estado+"&hidpais="+ls_pais;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
			}
		}
	}
}	   
		
function uf_cambiopais()
{
	f=document.form1;
	if (f.cmbpais.value=="s1")
	{
		f.operacion.value="";
		f.txtcodigo.value="";
		f.txtdenominacion.value=""; 
	}
	else
	{   
		f.operacion.value="pais";
	}
	f.action="sigesp_scv_d_ciudad.php";
	f.submit();
}
		
function uf_cambioestado()
{
	f=document.form1;
	if (f.cmbestado.value=="s1")
	{
		f.operacion.value="";
		f.txtcodigo.value="";
		f.txtdenominacion.value=""; 
	}
	else
	{   
		f.operacion.value="NUEVO";
	}
	f.action="sigesp_scv_d_ciudad.php";
	f.submit();  
}		
</script>
</html>