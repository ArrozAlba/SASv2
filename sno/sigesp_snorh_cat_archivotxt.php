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
	function uf_print($as_codarch, $as_denarch, $as_tipo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codarch  // Código del archivo txt
		//				   as_denarch  // Denominación del archivo txt
		//				   as_tipo  // Tipo de Llamada del catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/11/2007  								Fecha Última Modificación : 
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
		
		$ls_criterio="";
		if ($as_tipo=="IMPORTAR")
		{
			$ls_criterio=" AND tiparch ='I' ";
		}
		elseif ($as_tipo=="EXPORTAR")
		{
			$ls_criterio=" AND tiparch ='E' ";
		}
		
		
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=340>Denominación</td>";
		print "<td width=100>Tipo Archivo</td>";
		print "</tr>";
		$ls_sql="SELECT * ".
				"  FROM sno_archivotxt ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codarch like '".$as_codarch."'".
				"   AND denarch like '".$as_denarch."'".$ls_criterio.				
				" ORDER BY codarch ";
				
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codarch=$row["codarch"];
				$ls_denarch=$row["denarch"];
				$ls_tiparch=$row["tiparch"];
				$ls_acumon=$row["acumon"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codarch','$ls_denarch','$ls_tiparch','$ls_acumon');\">".$ls_codarch."</a></td>";
						print "<td>".$ls_denarch."</td>";
						if ($ls_tiparch=='I')
						{
							print "<td>Importar Datos</td>";
						}
						else
						{
							print "<td>Exportar Datos</td>";
						}
						print "</tr>";
						break;
					case "IMPORTAR":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarimportar('$ls_codarch','$ls_denarch','$ls_acumon');\">".$ls_codarch."</a></td>";
						print "<td>".$ls_denarch."</td>";
						if ($ls_tiparch=='I')
						{
							print "<td>Importar Datos</td>";
						}
						else
						{
							print "<td>Exportar Datos</td>";
						}
						print "</tr>";
						break;
						
						case "EXPORTAR":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarimportar('$ls_codarch','$ls_denarch','$ls_acumon');\">".$ls_codarch."</a></td>";
						print "<td>".$ls_denarch."</td>";
						if ($ls_tiparch=='I')
						{
							print "<td>Importar Datos</td>";
						}
						else
						{
							print "<td>Exportar Datos</td>";
						}
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
<title>Cat&aacute;logo de Archivos txt</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Archivos txt </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodarch" type="text" id="txtcodarch" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdenarch" type="text" id="txtdenarch" size="30" maxlength="120" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codarch="%".$_POST["txtcodarch"]."%";
		$ls_denarch="%".$_POST["txtdenarch"]."%";
		uf_print($ls_codarch, $ls_denarch, $ls_tipo);
	}
	else
	{
		$ls_codarch="%%";
		$ls_denarch="%%";
		uf_print($ls_codarch, $ls_denarch, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codarch,denarch,tiparch,acumon)
{
	opener.document.form1.txtcodarch.value=codarch;
	opener.document.form1.txtcodarch.readOnly=true;
	opener.document.form1.txtdenarch.value=denarch;
	opener.document.form1.cmbtiparch.value=tiparch;
	if ((acumon=='1')&&(tiparch!='E'))
	{
		opener.document.form1.chkacumon.checked=true;
	}
	
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.action="sigesp_snorh_d_archivostxt.php";
	opener.document.form1.existe.value="TRUE";			
	opener.document.form1.submit();	
	close();
}

function aceptarimportar(codarch,denarch,acumon)
{
	opener.document.form1.txtcodarch.value=codarch;
	opener.document.form1.txtcodarch.readOnly=true;
	opener.document.form1.txtdenarch.value=denarch;
	opener.document.form1.txtdenarch.readOnly=true;
	opener.document.form1.hidacumon.value=acumon;
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
  	f.action="sigesp_snorh_cat_archivotxt.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>