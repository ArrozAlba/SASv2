<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print($as_codigo, $as_nombre, $as_direccion, $as_tipo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: as_codigo  // Código del proveedor
		//				 as_nombre // Nombre del proveedor
		//				 as_direccion // Dirección del proveedor
		//				 as_tipo  // Tipo de Llamada del catálogo
		//	Description: Función que obtiene e imprime los resultados de la busqueda
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_mis;
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td>Código</td>";
		print "<td>Nombre del Proveedor</td>";
		print "</tr>";
        $ls_sql="SELECT cod_pro,nompro,sc_cuenta,rifpro FROM rpc_proveedor  ".
                " WHERE codemp = '".$ls_codemp."' ".
				"   AND cod_pro <> '----------' ".
				"   AND estprov = 0 ".
				"   AND cod_pro like '".$as_codigo."' ".
				"   AND nompro like '".$as_nombre."' ".
				"   AND dirpro like '".$as_direccion."' ". 
                " ORDER BY cod_pro "  ;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_rifpro=$row["rifpro"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
				print "<td>".$ls_nompro."</td>";
				print "</tr>";			
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Proveedores</title>
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Proveedores</td>
    </tr>
  </table>
<form name="form1" method="post" action="">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="64" height="22"><div align="right">C&oacute;digo:</div></td>
        <td><div align="left">
          <input name="txtcodigo" type="text" id="txtcodigo" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div>          <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre:</div></td>
        <td><div align="left">
          <input name="txtnombre" type="text" id="txtnombre" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      <tr>
        <td height="22"><div align="right">Direcci&oacute;n:</div></td>
        <td><div align="left">
          <input name="txtdireccion" type="text" id="txtdireccion" onKeyPress="javascript: ue_mostrar(this,event);">      
        </div></td>
    <tr>
      <td height="22">&nbsp;</td>
      <td>    <input name="operacion" type="hidden" id="operacion">

	  <div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a> </div></td>
  </table> 
	<br>
<?php
	require_once("class_folder/class_funciones_mis.php");
	$io_fun_mis=new class_funciones_mis();
	$ls_operacion =$io_fun_mis->uf_obteneroperacion();
	$ls_tipo=$io_fun_mis->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codigo="%".$_POST["txtcodigo"]."%";
		$ls_nombre="%".$_POST["txtnombre"]."%";
		$ls_direccion="%".$_POST["txtdireccion"]."%";
		uf_print($ls_codigo, $ls_nombre, $ls_direccion, $ls_tipo);
	}
	unset($io_fun_mis);
?>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codpro,nompro)
{
	opener.document.form1.txtcodigo.value=codpro;
	opener.document.form1.txtnombre.value=nompro;
	close();
}
  
function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search()
{
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_mis_cat_proveedor.php";
    f.submit();
}
</script>
</html>