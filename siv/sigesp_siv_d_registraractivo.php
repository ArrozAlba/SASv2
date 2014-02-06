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
$io_fun_inventario=new class_funciones_inventario();
$io_fun_inventario->uf_load_seguridad("SIV","sigesp_siv_d_articulo.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definir Activo Fijo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css"  rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<!--<script language="javascript">
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
</script>-->
</head>

<body>
<br>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	require_once("sigesp_siv_c_articulo.php");
	$io_siv= new sigesp_siv_c_articulo();	

	$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	$ls_operacion=$io_fun_inventario->uf_obteneroperacion();
	$ls_rbtipocat=$io_siv->uf_select_valor_config($ls_codemp);
	$li_catalogo=$io_siv->uf_siv_select_catalogo($li_estnum,$li_estcmp);
	if($ls_rbtipocat==0)
	{
		$io_msg->message("Debe realizar la configuracion de Activos Fijos");
		print "<script language=JavaScript>";
		print "close();" ;
		print "</script>";
	}
	else
	{
		switch ($ls_rbtipocat) 
		{
			case '0':
		        uf_limpiarvariables();
				/*$ls_codart="";
				$ls_denart="";
				$ls_sigecof="";
				$ls_densigecof="";
				$ls_spgcta="";
				$lb_existe="";*/
			break;
			
			case '1':
				 $ls_rbcsc="checked";
				 $ls_rbcgr="";
			break;
			
			case '2':
				$ls_rbcgr="checked";
				$ls_rbcsc="";
			break;
		}
	}

	switch ($ls_operacion)
	{
		case "NUEVO":
		$ls_codact="";			
			$ls_codart=$io_fun_inventario->uf_obtenervalor_get("codart","");
			$ls_denart=$io_fun_inventario->uf_obtenervalor_get("denart","");
			$ls_sigecof=$io_fun_inventario->uf_obtenervalor_get("sigecof","");
			$ls_densigecof=$io_fun_inventario->uf_obtenervalor_get("densigecof","");
			$ls_spgcta=$io_fun_inventario->uf_obtenervalor_get("spg_cta","");
			$lb_existe=$io_siv->uf_saf_select_estactivo($ls_codart);
			if($lb_existe)
			{
				$io_msg->message("Ya el Articulo tiene su Activo Correspondiente");
				print "<script language=JavaScript>";
				print "close();" ;
				print "</script>";
			}			
			
		break;
		
		case "BUSCAR":
				$ls_codact=$io_fun_inventario->uf_obtenervalor("txtcodact","");
				$ls_codart=$io_fun_inventario->uf_obtenervalor("txtcodart","");
				$ls_denart=$io_fun_inventario->uf_obtenervalor("txtdenart","");
				$ls_sigecof=$io_fun_inventario->uf_obtenervalor("txtcodcatsig","");
				$ls_densigecof=$io_fun_inventario->uf_obtenervalor("txtdencatsig","");
				$ls_spgcta=$io_fun_inventario->uf_obtenervalor("txtspg_cuenta","");
				$ls_codact=str_pad($ls_codact,15,"0",0); 
				$lb_valor=$io_siv->uf_buscarcodigoactivo($ls_codact); 
				if ($lb_valor!="")
				{
					$io_msg->message("Existe un Activo con este código");
				}
		break;
		
		case "GUARDAR":
			$ls_codact=$io_fun_inventario->uf_obtenervalor("txtcodact","");
			$ls_codact=str_pad($ls_codact,15,"0",0); 
			$ls_codart=$io_fun_inventario->uf_obtenervalor("txtcodart","");
			$ls_denart=$io_fun_inventario->uf_obtenervalor("txtdenart","");
			$li_cosact=$io_fun_inventario->uf_obtenervalor("txtcosto","");
			$ls_codcatsig=$io_fun_inventario->uf_obtenervalor("txtcodcatsig","");		
			$ls_codgru=$io_fun_inventario->uf_obtenervalor("txtcodgru","");
			$ls_codsubgru=$io_fun_inventario->uf_obtenervalor("txtcodsubgru","");
			$ls_spgcuenta=$io_fun_inventario->uf_obtenervalor("txtspg_cuenta","");
			$ls_codsec=$io_fun_inventario->uf_obtenervalor("txtcodsec","");
			$li_cosact=    str_replace(".","",$li_cosact);
			$li_cosact=    str_replace(",",".",$li_cosact);
			$lb_valor=$io_siv->uf_buscarcodigoactivo($ls_codact); 
			if ($lb_valor!="")
			{
				$io_msg->message("Existe un Activo con este código");
			}
			if ($lb_valor=="")
			{
				$lb_valido=$io_siv->uf_saf_insert_activo($ls_codart,$ls_codact,$ls_denart,$li_cosact,$ls_codcatsig,
				                                         $ls_codgru,$ls_codsubgru,$ls_codsec,$ls_spgcuenta,$la_seguridad);	
			}
			if(($lb_valido)&&($lb_valor==""))
			{
				$io_msg->message("EL activo ".$ls_codact." se Registro correctamente");
			}
			else
			{
				$io_msg->message("No se pudo registrar el Activo");
			}
			print "<script language=JavaScript>";
			print "close();" ;
			print "</script>";
		break;
	}
	

?>
<form name="form1" method="post" action="">
  <table width="576" height="239" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="13" colspan="2" class="titulo-ventana">Definir Activo Fijo</td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" colspan="2">
        <div align="left">
          <input name="txtdenart" type="text" class="sin-borde2" id="txtdenart" value="<?php print $ls_denart?>" size="65" readonly="true">
          <input name="txtcodart" type="hidden" id="txtcodart" value="<?php print $ls_codart ?>">
          <input name="txttipocat" type="hidden" id="txttipocat" value="<?php print $ls_tipocat; ?>">
          <input name="hidstatus" type="hidden" id="hidstatus">
          <input name="hidrbtipocat" type="hidden" id="hidrbtipocat" value="<?php print $ls_rbtipocat ?>">
        </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" align="center"><div align="center"></div></td>
      <td width="443" height="22" align="center"><div align="left">
        <input name="rbtipocat" type="radio" class="sin-borde" value="CSC" <?php print $ls_rbcsc; ?> disabled="disabled">
Cat&aacute;logo SIGECOF
<input name="rbtipocat" type="radio" class="sin-borde" value="CGR" <?php print $ls_rbcgr; ?> disabled="disabled">
Categor&iacute;a CGR </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" align="center"><div align="right">C&oacute;digo</div></td>
      <td height="22" align="center"><div align="left">
        <input name="txtcodact" type="text" id="txtcodact" value="<?php print $ls_codact; ?>" 
		  onBlur="javascript:uf_buscarcodigo();" size="20"  maxlength="15" style="text-align:center">
      </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" align="center"><div align="right">Costo</div></td>
      <td height="22" align="center"><div align="left">
        <input name="txtcosto" type="text" id="txtcosto" style="text-align:right"  onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="0,00" size="15">
      </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" align="center"><div align="right">SIGECOF</div></td>
      <td height="22" align="center"><div align="left">
        <input name="txtcodcatsig" type="text" id="txtcodcatsig" size="18" maxlength="15" readonly value="<?php print $ls_sigecof ?>">
        <a href="javascript: ue_sigecof();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdencatsig" type="text" class="sin-borde" id="txtdencatsig" size="45" readonly value="<?php print $ls_densigecof ?>">
      </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" align="center"><div align="right">Grupo</div></td>
      <td height="22" align="center"><div align="left">
        <input name="txtcodgru" type="text" id="txtcodgru" size="8"  style="text-align:center" readonly>
        <a href="javascript: ue_grupo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdengru" type="text" class="sin-borde" id="txtdengru" size="45" readonly>
      </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" align="center"><div align="right">Sub-Grupo</div></td>
      <td height="22" align="center"><div align="left">
        <input name="txtcodsubgru" type="text" id="txtcodsubgru" size="8"  style="text-align:center" readonly>
        <a href="javascript: ue_subgrupo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdensubgru" type="text" class="sin-borde" id="txtdensubgru" size="45" readonly>
      </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" align="center"><div align="right">Secci&oacute;n</div></td>
      <td height="22" align="center"><div align="left">
        <input name="txtcodsec" type="text" id="txtcodsec" size="8"  style="text-align:center" readonly>
        <a href="javascript: ue_seccion();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdensec" type="text" class="sin-borde" id="txtdensec" size="45" readonly>
      </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" align="center"><div align="right">Cuenta Presupuestaria </div></td>
      <td height="22" align="center"><div align="left">
        <input name="txtspg_cuenta" type="text" id="txtspg_cuenta" size="20"  style="text-align:center" readonly value="<?php print $ls_spgcta ?>">
		<?php
			  	if($li_catalogo!=1)
			{?>
        <a href="javascript: ue_cataspg();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
		   <?php
			  }
		?>
        <input name="txtspgdenominacion" type="text" class="sin-borde" id="txtspgdenominacion" size="45" readonly >
      </div></td>
    </tr>
    <tr class="formato-blanco">
      <td width="131" height="28"><div align="right">
        <input name="operacion" type="hidden" id="operacion">
      </div></td>
      <td height="22"><div align="right"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();">Guardar</a><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a> </div></td>
    </tr>
  </table>
</form>      
</body>
<script language="JavaScript">

function ue_sigecof()
{
    f=document.form1;
	if(f.rbtipocat[0].checked==true)
	{
		ls_tipo="ACTIVOS";
		window.open("sigesp_siv_cat_sigecof.php?tipo="+ls_tipo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
    }
	else
	{
		alert("La Configuracion de Activos no permite esta opcion");	
	}
}

function ue_cataspg()
{
	ls_tipo="ACTIVOSFIJOS";
	window.open("sigesp_siv_cat_ctasspg.php?tipo="+ls_tipo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_grupo()
{
	f=document.form1;
	if(f.rbtipocat[1].checked==true)
	{
		ls_tipo="ACTIVOS";
		window.open("../saf/sigesp_saf_cat_grupo.php?tipo="+ls_tipo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("La Configuracion de Activos no permite esta opcion");	
	}
}

function ue_subgrupo()
{
	f=document.form1;
	if(f.rbtipocat[1].checked==true)
	{
		f=document.form1;
		ls_tipo="ACTIVOS";
		ls_codgru=f.txtcodgru.value;
		ls_dengru=f.txtdengru.value;
		window.open("../saf/sigesp_saf_cat_subgrupo.php?tipo="+ls_tipo+"&txtcodgru="+ls_codgru+"&txtdengru="+ls_dengru+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("La Configuracion de Activos no permite esta opcion");	
	}
}

function ue_seccion()
{
	f=document.form1;
	if(f.rbtipocat[1].checked==true)
	{
		f=document.form1;
		ls_tipo="ACTIVOS";
		ls_codgru=f.txtcodgru.value;
		ls_dengru=f.txtdengru.value;
		ls_codsubgru=f.txtcodsubgru.value;
		ls_densubgru=f.txtdensubgru.value;
		window.open("../saf/sigesp_saf_cat_seccion.php?tipo="+ls_tipo+"&txtcodgru="+ls_codgru+"&txtdengru="+ls_dengru+"&txtcodsubgru="+ls_codsubgru+"&txtdensubgru="+ls_densubgru+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("La Configuracion de Activos no permite esta opcion");	
	}
}

function ue_guardar()
{
	f=document.form1;
	lb_valido=false;
	ls_codact=f.txtcodact.value;
	li_cosact=f.txtcosto.value;
	ls_codcatsig=f.txtcodcatsig.value;
	ls_codgru=f.txtcodgru.value;
	ls_codsubgru=f.txtcodsubgru.value;
	ls_codsec=f.txtcodsec.value;
	ls_spgcuenta=f.txtspg_cuenta.value;
	if(f.rbtipocat[1].checked==true)
	{
		if((ls_codgru!="")&&(ls_codsubgru!="")&&(ls_codsec!=""))
		{
			lb_valido=true;
		}
	}
	if(f.rbtipocat[0].checked==true)
	{
		if(ls_codcatsig!="")
		{
			lb_valido=true;
		}
	}
	if((lb_valido)&&(ls_spgcuenta!=""))
	{
		f.operacion.value="GUARDAR"
		f.action="sigesp_siv_d_registraractivo.php";
		f.submit();
	}
	else
	{
		alert ("Favor complete los datos deacuerdo a la configuracion de Activos Fijos indicada");
	}
}

function ue_cancelar()
{
	window.close();
}

function uf_buscarcodigo()
{
 	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_siv_d_registraractivo.php";
	f.submit();
}

</script>
</html>
