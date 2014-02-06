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
	function uf_print($as_codigo, $as_denominacion, $as_tipo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: as_codigo  // Código de la estructura Programática
		//				 as_denominacion // Denominación de la estructura programática
		//				 as_tipo  // Tipo de Llamada del catálogo
		//	Description: Función que obtiene e imprime los resultados de la busqueda
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
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
		print "<td>Código </td>";
		print "<td>Denominación</td>";
		print "</tr>";
		$ls_sql="SELECT codestpro1, denestpro1 ".
				"  FROM spg_ep1 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 like '".$as_codigo."' ".
				"   AND denestpro1 like '".$as_denominacion."' ".
				" ORDER BY codestpro1 ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$codigo=$row["codestpro1"];
				$denominacion=$row["denestpro1"];
				switch($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "</tr>";			
						break;
						
					case "asignacioncargo":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacioncargo('$codigo','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
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
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 1 <?php print $_SESSION["la_empresa"]["nomestpro1"] ?> </title>
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
     	 	<td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de <?php print $_SESSION["la_empresa"]["nomestpro1"] ?>  </td>
    	</tr>
  </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">Codigo</div></td>
        <td width="431"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
<?php
	require_once("class_funciones_inventario.php");
	$io_fun_nomina=new class_funciones_inventario();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codigo="%".$_POST["codigo"]."%";
		$ls_denominacion="%".$_POST["denominacion"]."%";
		uf_print($ls_codigo, $ls_denominacion, $ls_tipo);
	}
	else
	{
		$ls_codigo="%%";
		$ls_denominacion="%%";
		uf_print($ls_codigo, $ls_denominacion, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(codigo,deno)
	{
		opener.document.form1.codestpro1.value=codigo;
		opener.document.form1.denestpro1.value=deno;
		opener.document.form1.codestpro2.value="";
		opener.document.form1.denestpro2.value="";
		opener.document.form1.codestpro3.value="";
		opener.document.form1.denestpro3.value="";
		close();
	}
	function aceptarasignacioncargo(codigo,deno)
	{
		opener.document.form1.txtcodestpro1.value=codigo;
		opener.document.form1.txtdenestpro1.value=deno;
		opener.document.form1.txtcodestpro2.value="";
		opener.document.form1.txtdenestpro2.value="";
		opener.document.form1.txtcodestpro3.value="";
		opener.document.form1.txtdenestpro3.value="";
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_siv_cat_estpro1.php?tipo=<?PHP print $ls_tipo;?>";
		f.submit();
	}
</script>
</html>