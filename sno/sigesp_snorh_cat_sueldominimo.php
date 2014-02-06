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
   function uf_print($as_codsuemin, $as_gacsuemin, $as_decsuemin, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codsuemin  // Código del sueldo minimo
		//				   as_gacsuemin  // Gaceta
		//				   as_decsuemin  // Decreto
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/02/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		print "<td width=100>Código</td>";
		print "<td width=100>Año</td>";
		print "<td width=100>Gaceta</td>";
		print "<td width=100>Decreto</td>";
		print "<td width=100>Monto</td>";
		print "</tr>";
		$ls_sql="SELECT codemp, codsuemin, anosuemin, gacsuemin, decsuemin, fecvigsuemin, monsuemin, obssuemin ".
				"  FROM sno_sueldominimo ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codsuemin like '".$as_codsuemin."' ".
				"   AND gacsuemin like '".$as_gacsuemin."'".
				"   AND decsuemin like '".$as_decsuemin."'".
				" ORDER BY codsuemin, anosuemin ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codsuemin=$row["codsuemin"];
				$li_anosuemin=$row["anosuemin"];
				$ls_gacsuemin=$row["gacsuemin"];
				$ls_decsuemin=$row["decsuemin"];
				$ld_fecvigsuemin=$io_funciones->uf_formatovalidofecha($row["fecvigsuemin"]);
				$ld_fecvigsuemin=$io_funciones->uf_convertirfecmostrar($ld_fecvigsuemin);
				$li_monsuemin=$io_fun_nomina->uf_formatonumerico($row["monsuemin"]);
				$ls_obssuemin=$row["obssuemin"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codsuemin','$li_anosuemin','$ls_gacsuemin','$ls_decsuemin','$ld_fecvigsuemin',";
						print "'$li_monsuemin','$ls_obssuemin');\">".$ls_codsuemin."</a></td>";
						print "<td>".$li_anosuemin."</td>";
						print "<td>".$ls_gacsuemin."</td>";
						print "<td>".$ls_decsuemin."</td>";
						print "<td>".$li_monsuemin."</td>";
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
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Sueldo M&iacute;nimo</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Sueldo M&iacute;nimo </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodsuemin" type="text" id="txtcodsuemin" size="30" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div></td>
        <td><div align="left">
			<input name="txtgacsuemin" type="text" id="txtgacsuemin" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">		
		</div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div></td>
        <td><div align="left">
			<input name="txtdecsuemin" type="text" id="txtdecsuemin" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">				
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
	if($ls_operacion=="BUSCAR")
	{
		$ls_codsuemin="%".$_POST["txtcodsuemin"]."%";
		$ls_gacsuemin="%".$_POST["txtgacsuemin"]."%";
		$ls_decsuemin="%".$_POST["txtdecsuemin"]."%";
		uf_print($ls_codsuemin, $ls_gacsuemin, $ls_decsuemin, $ls_tipo);
	}
	else
	{
		$ls_codsuemin="%%";
		$ls_gacsuemin="%%";
		$ls_decsuemin="%%";
		uf_print($ls_codsuemin, $ls_gacsuemin, $ls_decsuemin, $ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codsuemin,anosuemin,gacsuemin,decsuemin,fecvigsuemin,monsuemin,obssuemin)
{
	opener.document.form1.txtcodsuemin.value=codsuemin;
	opener.document.form1.txtcodsuemin.readOnly=true;
    opener.document.form1.txtanosuemin.value=anosuemin;
    opener.document.form1.txtgacsuemin.value=gacsuemin;
    opener.document.form1.txtdecsuemin.value=decsuemin;
    opener.document.form1.txtfecvigsuemin.value=fecvigsuemin;
    opener.document.form1.txtmonsuemin.value=monsuemin;
    opener.document.form1.txtobssuemin.value=obssuemin;	
	opener.document.form1.existe.value="TRUE";
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
  	f.action="sigesp_snorh_cat_sueldominimo.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
