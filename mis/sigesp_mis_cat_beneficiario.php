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
	function uf_print($as_cedbene, $as_nombene, $as_apebene, $as_tipo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: as_cedbene  // Código de la cuenta 
		//				 as_nombene // Denominación de la cuenta
		//				 as_apebene // Denominación de la cuenta
		//				 as_codban // Denominación de la cuenta
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
		print "<td>Cedula </td>";
		print "<td>Nombre del Beneficiario</td>";
		print "</tr>";
		$ls_sql="SELECT ced_bene, nombene, apebene ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND ced_bene <> '----------' ".
				"   AND ced_bene like '".$as_cedbene."' ".
				"   AND nombene like '".$as_nombene."' ".
				"   AND apebene like '".$as_apebene."' ".
				" ORDER BY ced_bene "  ;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedbene=$row["ced_bene"];
				$ls_nombene=$row["nombene"]." ".$row["apebene"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene');\">".$ls_cedbene."</a></td>";
				print "<td>".$ls_nombene."</td>";
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
<title>Catálogo Beneficiarios</title>
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
<form name="form1" method="post" action="">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Beneficiarios</td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="68" height="22"><div align="right">Cedula:</div></td>
        <td width="202"><input name="txtcedula" type="text" id="txtcedula" onKeyPress="javascript: ue_mostrar(this,event);">        </td>
        <td width="6">&nbsp;</td>
        <td width="214">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre: </div></td>
        <td><input name="txtnombre" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);"></td>
        <td>&nbsp;</td>
        <td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido: </div></td>
        <td><input name="txtapellido" type="text" id="txtapellido" onKeyPress="javascript: ue_mostrar(this,event);"></td>
        <td>&nbsp;</td>
        <td>      
      </tr>
	  <tr>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
	  </tr>
<input name="operacion" type="hidden" id="operacion"> 
</table> 

<?php
	require_once("class_folder/class_funciones_mis.php");
	$io_fun_mis=new class_funciones_mis();
	$ls_operacion =$io_fun_mis->uf_obteneroperacion();
	$ls_tipo=$io_fun_mis->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_cedbene="%".$_POST["txtcedula"]."%";
		$ls_nombene="%".$_POST["txtnombre"]."%";
		$ls_apebene="%".$_POST["txtapellido"]."%";
		uf_print($ls_cedbene, $ls_nombene, $ls_apebene, $ls_tipo);
	}
	unset($io_fun_mis);
?>
</form>      
</body>
<script language="JavaScript">
  function aceptar(cedula,nombre)
  {
    opener.document.form1.txtcodigo.value=cedula;
	opener.document.form1.txtnombre.value=nombre;
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
	  f.action="sigesp_mis_cat_beneficiario.php";
	  f.submit();
  }
</script>
</html>