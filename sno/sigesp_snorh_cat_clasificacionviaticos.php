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
   function uf_print()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_print
		//		   Access : public 
		//	    Arguments : as_tipo  // Tipo de Llamada del catálogo
		//	                as_perdes  // Período desde si se quiere filtrar a partir de un período en particular
		//	  Description : Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 10/02/2006 								Fecha Última Modificación : 
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
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100>Código</td>";
		print "<td width=400>Denominación</td>";
		print "</tr>";
		$ls_sql=" SELECT * FROM scv_categorias".
		 		" WHERE codemp='".$ls_codemp."'".
		 		" ORDER BY codcat ASC ";				
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codcat=$row["codcat"];
				$ls_dencat=$row["dencat"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ls_codcat','$ls_dencat');\">".$ls_codcat."</a></td>";
				print "<td>".$ls_dencat."</td>";
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
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Clasificaci&oacute;n de Viaticos </title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Clasificaci&oacute;n de Viaticos </td>
    </tr>
  </table>
<br>
    <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	uf_print();
	unset($io_fun_nomina);
?>
</div>
</form>
</body>
<script language="JavaScript">
function aceptar(codcat,dencat)
{
	opener.document.form1.txtcodclavia.value=codcat;
	opener.document.form1.txtcodclavia.readOnly=true;
    opener.document.form1.txtdencat.value=dencat;
	opener.document.form1.txtdencat.readOnly=true;
	close();
}
</script>
</html>