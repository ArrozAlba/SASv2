<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_sc_cuenta, $as_denominacion, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_sc_cuenta  // Código de cuenta
		//				   as_denominacion  // Denominación
		//				   as_tipo  // Tipo de Llamada del catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
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
		if(array_key_exists("la_nomina",$_SESSION))
		{
			$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$ls_codnom="0000";
		}
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100>Código</td>";
		print "<td width=400>Denominación</td>";
		print "</tr>";
		$ls_sql="SELECT sc_cuenta, denominacion ".
				"  FROM scg_cuentas ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND status='C'".
				"   AND sc_cuenta like '".$as_sc_cuenta."'".
				"   AND denominacion like '".$as_denominacion."'".
			    " ORDER BY sc_cuenta";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_sc_cuenta=trim($row["sc_cuenta"]);
				$ls_denominacion=$row["denominacion"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_sc_cuenta','$ls_denominacion');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
					break;
					case "RD":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_rd('$ls_sc_cuenta','$ls_denominacion');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
					break;
				}
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
		unset($ls_codnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Contables</title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cuentas Contables </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">N&uacute;mero</div></td>
        <td width="431"><div align="left">
          <input name="txtsc_cuenta" type="text" id="txtsc_cuenta" size="30" maxlength="25" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdenominacion" type="text" id="txtdenominacion" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_viaticos.php");
	$io_fun_viaticos=new class_funciones_viaticos();
	$ls_operacion =$io_fun_viaticos->uf_obteneroperacion();
	$ls_tipo=$io_fun_viaticos->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_sc_cuenta="%".$_POST["txtsc_cuenta"]."%";
		$ls_denominacion="%".$_POST["txtdenominacion"]."%";
		uf_print($ls_sc_cuenta, $ls_denominacion, $ls_tipo);
	}
	else
	{
		$ls_sc_cuenta="%%";
		$ls_denominacion="%%";
		uf_print($ls_sc_cuenta, $ls_denominacion, $ls_tipo);
	}
	unset($io_fun_viaticos);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(sc_cuenta,denominacion)
{
	opener.document.form1.txtscben.value=sc_cuenta;
    opener.document.form1.txtdenscben.value=denominacion;
	close();
}

function aceptar_rd(sc_cuenta,denominacion)
{
	opener.document.form1.txtscbenrd.value=sc_cuenta;
    opener.document.form1.txtdenscbenrd.value=denominacion;
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

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_scv_cat_cuentacontable.php?tipo=<?PHP print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
