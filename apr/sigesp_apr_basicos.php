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
	require_once("class_folder/class_funciones_apr.php");
	$io_fun_apr=new class_funciones_apr();
	$io_fun_apr->uf_load_seguridad("APR","sigesp_apr_basicos.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="resultado";
	@mkdir($ls_ruta,0755);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Sistema de Apertura</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/xmlhttp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/prototype.js"></script>
</head>

<body>
<table width="780" border="0" align="center" cellpadding="1" cellspacing="0" class="contorno">
  <tr>
    <td width="570" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu2.js"></script></td>
  </tr>  
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><a href="javascript: ue_descargar('<?PHP print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_apr->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_apr);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="0" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
  </table>
  <table width="200" border="0" align="center">
    <tr>
      <td><div align="center">
        <table width="570" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">

<?php
	require_once("class_folder/class_validacion.php");
	$io_validacion=new class_validacion;
    $ls_operacion="";
	$io_validacion->uf_select_sistema_apertura("SSS",$ls_disablesss);
	$io_validacion->uf_select_sistema_apertura("RPC",$ls_disablerpc);
	$io_validacion->uf_select_sistema_apertura("SCG",$ls_disablescg);
	$io_validacion->uf_select_sistema_apertura("SIV",$ls_disablesiv);
	$io_validacion->uf_select_sistema_apertura("SEP",$ls_disablesep);
	$io_validacion->uf_select_sistema_apertura("SOC",$ls_disablesoc);
	$io_validacion->uf_select_sistema_apertura("CXP",$ls_disablecxp);
	$io_validacion->uf_select_sistema_apertura("SCB",$ls_disablescb);
	$io_validacion->uf_select_sistema_apertura("SAF",$ls_disablesaf);
	$io_validacion->uf_select_sistema_apertura("SCV",$ls_disablescv);
	$io_validacion->uf_select_sistema_apertura("SNO",$ls_disablesno);

	if(array_key_exists("operacion",$_POST)) // si existe la operación creamos las bd de origen y destino
	{
		 $ls_operacion=$_POST["operacion"];  
	}
	if ($ls_operacion!="")
	{					   			
		if (array_key_exists("chkcfg",$_POST))
		{
			  $ls_cfg=$_POST["chkcfg"];
			  if ($ls_cfg=="1")
			  {
				   require_once("sigesp_std_sigesp.php");
				   $io_cfg=new sigesp_std_sigesp;
				   $lb_valido=$io_cfg->uf_convertir_data();			
				   $ls_operacion="EJECUTADO";	
				   $io_cfg->uf_destructor();                                      
			  }
		}		
		if (array_key_exists("chkrpc",$_POST))
		{
			  $ls_rpc=$_POST["chkrpc"];
			  if ($ls_rpc=="1")
			  {
				   require_once("sigesp_std_rpc.php");
				   $io_rpc=new sigesp_std_rpc;
				   $lb_valido=$io_rpc->uf_convertir_data();			
				   $ls_operacion="EJECUTADO";	
				   $io_rpc->uf_destructor();                                      
			  }
		}		
		if (array_key_exists("chkscg",$_POST))
		{
			  $ls_scg=$_POST["chkscg"];
			 
			  if ($ls_scg=="1")
			  {
				   require_once("sigesp_std_scg.php");
				   $io_scg=new sigesp_std_scg;                    
				   $lb_valido=$io_scg->uf_convertir_data();				
				   $ls_operacion="EJECUTADO";
				   $io_scg->uf_destructor();
			  }
		}		
		if (array_key_exists("chkspg",$_POST))
		{
			  $ls_spg=$_POST["chkspg"];
			  if ($ls_spg=="1")
			  {
				   require_once("sigesp_std_spg.php");
				   $io_spg=new sigesp_std_spg;                   
				   $lb_valido=$io_spg->uf_convertir_data();		
				   $ls_operacion="EJECUTADO";		
				   $io_spg->uf_destructor();
			  }
		}		
		if (array_key_exists("chkspi",$_POST))
		{
			  $ls_spi=$_POST["chkspi"];
			  if ($ls_spi=="1")
			  {
				   require_once("sigesp_std_spi.php");
				   $io_spi=new sigesp_std_spi;
				   $lb_valido=$io_spi->uf_convertir_data();
				   $ls_operacion="EJECUTADO";				
				   $io_spi->uf_destructor();
			  }
		}
		if (array_key_exists("chksiv",$_POST))
		{
			  $ls_siv=$_POST["chksiv"];
			  if ($ls_siv=="1")
			  {
				   require_once("sigesp_std_siv.php");
				   $io_siv=new sigesp_std_siv;
				   $lb_valido=$io_siv->uf_convertir_data();	
				   $lb_valspi=$lb_valido;
				   $ls_operacion="EJECUTADO";			
				   $io_siv->uf_destructor();
			  }
		}				
		if (array_key_exists("chksep",$_POST))
		{
			  $ls_sep=$_POST["chksep"];
			  if ($ls_sep=="1")
			  {                  
				   require_once("sigesp_std_sep.php");
				   $io_sep=new sigesp_std_sep;                   
				   $lb_valido=$io_sep->uf_convertir_data();	                   
				   $ls_operacion="EJECUTADO";		
				   $io_sep->uf_destructor();
			  }
		}			 	    		
		if (array_key_exists("chksoc",$_POST))
		{
			  $ls_soc=$_POST["chksoc"];
			  if ($ls_soc=="1")
			  {
				   require_once("sigesp_std_soc.php");
				   $io_soc=new sigesp_std_soc;
				   $lb_valido=$io_soc->uf_convertir_data();	                   
				   $ls_operacion="EJECUTADO";		
				   $io_soc->uf_destructor();
			  }
		}		
		if (array_key_exists("chkcxp",$_POST))
		{
			  $ls_cxp=$_POST["chkcxp"];
			  if ($ls_cxp=="1")
			  {
				   require_once("sigesp_std_cxp.php");
				   $io_cxp=new sigesp_std_cxp;
				   $lb_valido=$io_cxp->uf_convertir_data();			
				   $ls_operacion="EJECUTADO";			
				   $io_cxp->uf_destructor();
			  }
		}		
		if (array_key_exists("chkscb",$_POST))
		{
			  $ls_scb=$_POST["chkscb"];
			  if ($ls_scb=="1")
			  {
				   require_once("sigesp_std_scb.php");
				   $io_scb=new sigesp_std_scb;
				   $lb_valido=$io_scb->uf_convertir_data();			
				   $ls_operacion="EJECUTADO";			
				   $io_scb->uf_destructor();
			  }
		}		
		if (array_key_exists("chksss",$_POST))
		{
			  $ls_sss=$_POST["chksss"];
			  if ($ls_sss=="1")
			  {
				   require_once("sigesp_std_sss.php");
				   $io_sss=new sigesp_std_sss;
				   $lb_valido=$io_sss->uf_convertir_data();				
				   $ls_operacion="EJECUTADO";		
				   $io_sss->uf_destructor();
			  }
		}		
		if (array_key_exists("chksaf",$_POST))
		{
			  $ls_saf=$_POST["chksaf"];
			  if ($ls_saf=="1")
			  {
				   require_once("sigesp_std_saf.php");
				   $io_saf=new sigesp_std_saf;
				   $lb_valido=$io_saf->uf_convertir_data();				
				   $ls_operacion="EJECUTADO";		
				   $io_saf->uf_destructor();
			  }
		}				
		if (array_key_exists("chksno",$_POST))
		{
			  $ls_sno=$_POST["chksno"];
			  if ($ls_sno=="1")
			  {
				   require_once("sigesp_std_sno.php");
				   $io_sno=new sigesp_std_sno;
				   $lb_valido=$io_sno->uf_convertir_data();		
				   $ls_operacion="EJECUTADO";				
				   $io_sno->uf_destructor();
			  }
		}		
		if (array_key_exists("chksnohist",$_POST))
		{
			  $ls_snohist=$_POST["chksnohist"];
			  if ($ls_snohist=="1")
			  {
				   require_once("sigesp_std_sno_historico.php");
				   $io_snohist=new sigesp_std_sno_historico;
				   $lb_valido=$io_snohist->uf_convertir_data();		
				   $ls_operacion="EJECUTADO";				
				   $io_snohist->uf_destructor();
			  }
		}		
	}
	//---------------------------------------------------------------------------------------------------------------------------------
	  if( ($ls_operacion=="MOSTRAR") || ($ls_operacion=="") )
	  {
	  ?>
          <tr>
            <td height="22" colspan="5" class="titulo-celdanew"><div align="center">Apertura Sistemas B&aacute;sicos </div></td>
          </tr>
  			<td height="25">
      <div align="right">
        <input name="chksss" type="checkbox" class="sin-borde" id="chksss" onClick="activar('sigesp_convierte.php','SEGURIDAD')" value="1" <?php print $ls_disablesss; ?>>
    </div></td>
      <td height="25"><div align="right"><img src="../apr/iconos/ipblock.gif" width="25" height="25" border="0"></div></td>
      <td height="25" colspan="3">Sistema de Seguridad <em>(SSS)</em></td>
  </tr>
  <tr>
    <td height="25">
     
      <div align="right">
        <input name="chkrpc" type="checkbox" class="sin-borde" id="chkrpc" onClick="activar('sigesp_convierte.php','PROVEEDORES')" value="1" <?php print $ls_disablerpc; ?>>
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/proveedores.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Registro de Proveedores y Contratistas<em> (RPC) </em></td>
  </tr>
  <tr>
    <td height="25">

      <div align="right">
        <input name="chkscg" type="checkbox" class="sin-borde" id="chkscg" onClick="activar('sigesp_convierte.php','CONTABILIDAD')" value="1"  <?php print $ls_disablescg; ?>>
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/contabilidad.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Contabilidad <em>(SCG, SPG, SPI)</em> </td>
  </tr>
  <tr>  </tr>
  <tr>
    <td height="25">

      <div align="right">
        <input name="chksaf" type="checkbox" class="sin-borde" id="chksaf" onClick="activar('sigesp_convierte.php','ACTIVOS')" value="1" <?php print $ls_disablesaf; ?>>
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/fijos.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Activo Fijo <em>(SAF) </em></td>
  </tr>
  <tr>
    <td height="25"><div align="right">
      <input name="chkcxp" type="checkbox" class="sin-borde" id="chkcxp" onClick="activar('sigesp_convierte.php','CUENTAS POR PAGAR')" value="1" <?php print $ls_disablecxp; ?>>
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/pagar.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Cuentas por Pagar <em>(CXP)</em> </td>
  </tr>
  <tr>
    <td height="25">
   
      <div align="right">
        <input name="chksiv" type="checkbox" class="sin-borde" id="chksiv" onClick="activar('sigesp_convierte.php','INVENTARIO')" value="1" <?php print $ls_disablesiv; ?> >
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/cart.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Inventario <em>(SIV) </em></td>
  </tr>
  <tr>
    <td height="25">

        <div align="right">
          <input name="chksep" type="checkbox" class="sin-borde" id="chksep" onClick="activar('sigesp_convierte.php','SOLICITUDES DE EJECUCION PRESUPUESTARIA')" value="1" <?php print $ls_disablesep; ?>>
      </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/presupuestaria.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Ejecucion Presupuestaria<em>(SEP) </em></td>
  </tr>
  <tr>
    <td height="25">

      <div align="right">
        <input name="chksoc" type="checkbox" class="sin-borde" id="chksoc" onClick="activar('sigesp_convierte.php','COMPRAS')" value="1" <?php print $ls_disablesoc; ?> >
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/compras.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Compras <em>(SOC)</em> </td>
  </tr>
  <tr>
    <td height="25">

      <div align="right">
        <input name="chkscb" type="checkbox" class="sin-borde" id="chkscb" onClick="activar('sigesp_convierte.php','BANCO')" value="1" <?php print $ls_disablescb; ?>>
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/banco.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Caja y Banco <em>(SCB) </em></td>
  </tr>
  <tr>
    <td height="25">

      <div align="right">
        <input name="chkscv" type="checkbox" class="sin-borde" id="chkscv" onClick="activar('sigesp_convierte.php','VIATICOS')" value="1" <?php print $ls_disablescv; ?>>
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/fijos.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Control de Viaticos <em>(SCV) </em></td>
  </tr>
  <tr>
    <td height="25">

      <div align="right">
        <input name="chksno" type="checkbox" class="sin-borde" id="chksno" onClick="activar('sigesp_convierte.php','NOMINA')" value="1" <?php print $ls_disablesno; ?> >
    </div></td>
    <td height="25"><div align="right"><img src="../apr/iconos/nomina.gif" width="25" height="25" border="0"></div></td>
    <td height="25" colspan="3">Sistema de Nomina (SNO) </td>
  </tr>
  <tr>
    <td height="25">&nbsp;</td>
    <td height="25">&nbsp;</td>
    <td height="25" colspan="3">&nbsp;</td>
  <?php
      } 
 ?>
  <tr>
    <td height="22" colspan="5">&nbsp;
        <div align="center">
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;?>">
          <input name="copiar" type="hidden" id="copiar" value="1">
        </div>
        <div align="right"></div></td>
  </tr>
        </table>
      </div></td>
    </tr>
  </table>
  <p align="center"><br>
  </p>
  </div>
  
<div id="prueba"> </div>

<input name="modulo" type="hidden" id="modulo">
  
</form>

<div id="pruebas" style="visibility:hidden">
	<input name="Valor" type="button" id="chkValor"  onClick="mostrarvalores()" value="Click Aqui" > <br>	
	<input name="Valor" type="button" id="chkMostrar"  onClick="activar('sigesp_convierte.php')" value="Ejc." >
</div>

<p>&nbsp;</p>
</body>

<script language="JavaScript">

function mostrarvalores()
{
	f=document.form1;
	//alert(f.chkcfg.value);
	alert('Valor del checkbox = ' + $F('chkcfg') +' tipo: ')
}

function ue_descargar(ruta)
{
	window.open("sigesp_apr_cat_directorio.php?ruta="+ruta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function activar(pagina,sigesp_modulo)
{
	f=document.form1;
	f.operacion.value="";
	f.modulo.value=sigesp_modulo;
	f.action=pagina;
	if (sigesp_modulo=="CONTABILIDAD")
	{
		f.copiar.value="0";
	}
	f.submit();
}

function ue_cerrar()
{
    f=document.form1;	
	f.operacion.value="";        
	f.action="sigespwindow_blank.php";
    f.submit();
}
</script>

</html>
