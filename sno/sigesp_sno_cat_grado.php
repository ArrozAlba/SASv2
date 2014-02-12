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
   function uf_print($as_codtab, $as_codpas, $as_codgra, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codtab  // código de la tabla
		//			       as_codpas  // fila de la tabla
		//				   as_codgra  // Columna de la tabla
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=120>Grado</td>";
		print "<td width=120>Paso</td>";
		print "<td width=130>Monto Salario</td>";
		print "<td width=130>Monto Compensación</td>";
		print "</tr>";
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CAST(codgra AS UNSIGNED), moncomgra ";
				break;
			case "POSTGRES":
				$ls_cadena="CASE WHEN INT8(codgra)=0 THEN codgra ELSE CAST(codgra AS INT2) END, moncomgra ";
				break;					
			case "INFORMIX":
				$ls_cadena="CAST(codgra AS SMALLINT), moncomgra ";
				break;					
		}
		$ls_sql="SELECT codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra ".
				"  FROM sno_grado ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codnom='".$ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codpas<>'00'".
				"   AND codgra<>'00'".
				"   AND codpas like '".$as_codpas."'".
				"   AND codgra like '".$as_codgra."'".
				" ORDER BY codemp, codnom, codtab, codgra, moncomgra";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codgra=trim($row["codgra"]);
				$ls_codpas=trim($row["codpas"]);
				$li_monsalgra=$row["monsalgra"];
				$li_moncomgra=$row["moncomgra"];
				$li_monsalgra=$io_fun_nomina->uf_formatonumerico($li_monsalgra);
				$li_moncomgra=$io_fun_nomina->uf_formatonumerico($li_moncomgra);

				switch ($as_tipo)
				{									
					case "asignacioncargo":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacioncargo('$ls_codpas','$ls_codgra','$li_monsalgra','$li_moncomgra');\">".$ls_codgra."</a></td>";
						print "<td>".$ls_codpas."</td>";
						print "<td>".$li_monsalgra."</td>";
						print "<td>".$li_moncomgra."</td>";
						print "</tr>";
						break;

					case "asignacion":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacion('$ls_codpas','$ls_codgra','$li_monsalgra');\">".$ls_codgra."</a></td>";
						print "<td>".$ls_codpas."</td>";
						print "<td>".$li_monsalgra."</td>";
						print "<td>".$li_moncomgra."</td>";
						print "</tr>";
						break;

					case "asignacionpersonal":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacionpersonal('$ls_codpas','$ls_codgra','$li_monsalgra');\">".$ls_codgra."</a></td>";
						print "<td>".$ls_codpas."</td>";
						print "<td>".$li_monsalgra."</td>";
						print "<td>".$li_moncomgra."</td>";
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
<title>Cat&aacute;logo de Grado</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Grado</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">Paso</div></td>
        <td width="431"><div align="left">
          <input name="txtcodpas" type="text" id="txtcodpas" size="30" maxlength="20" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Grado</div></td>
        <td><div align="left">
          <input name="txtcodgra" type="text" id="txtcodgra" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_tabla=$io_fun_nomina->uf_obtenervalor_get("tab","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codpas="%".$_POST["txtcodpas"]."%";
		$ls_codgra="%".$_POST["txtcodgra"]."%";
		uf_print($ls_tabla, $ls_codpas, $ls_codgra, $ls_tipo);
	}
	else
	{
		$ls_codpas="%%";
		$ls_codgra="%%";
		uf_print($ls_tabla, $ls_codpas, $ls_codgra, $ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptarasignacioncargo(codpas,codgra,monsalgra,moncomgra)
{
	opener.document.form1.txtcodpas.value=codpas;
	opener.document.form1.txtcodpas.readOnly=true;
    opener.document.form1.txtcodgra.value=codgra;
	opener.document.form1.txtcodgra.readOnly=true;
    opener.document.form1.txtmonsalgra.value=monsalgra;
	opener.document.form1.txtmonsalgra.readOnly=true;
    opener.document.form1.txtmoncomgra.value=moncomgra;
	opener.document.form1.txtmoncomgra.readOnly=true;
	close();
}

function aceptarasignacion(codpas,codgra,monsalgra)
{
	opener.document.form1.txtcodpas.value=codpas;
	opener.document.form1.txtcodpas.readOnly=true;
    opener.document.form1.txtcodgra.value=codgra;
	opener.document.form1.txtcodgra.readOnly=true;
	opener.document.form1.txtsueper.value=monsalgra;
	close();
}

function aceptarasignacionpersonal(codpas,codgra,monsalgra)
{
	opener.document.form1.txtcodpas.value=codpas;
	opener.document.form1.txtcodpas.readOnly=true;
    opener.document.form1.txtcodgra.value=codgra;
	opener.document.form1.txtcodgra.readOnly=true;
	opener.document.form1.txtsueper.value=monsalgra;
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
  	f.action="sigesp_sno_cat_grado.php?tipo=<?PHP print $ls_tipo;?>&tab=<?PHP print $ls_tabla;?>";
  	f.submit();
}
</script>
</html>