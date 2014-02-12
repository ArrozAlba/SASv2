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
   function uf_imprimirresultados($ai_numpre,$as_codper,$as_codtippre,$as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_imprimirresultados
		//	Arguments:    ai_numpre  // Número de Prestamo
		//				  as_codper  // Código de Personal
		//				  as_codtippre  // Código del Tipo de Prestamo
		//				  as_tipo  // Tipo de Llamada del catálogo
		//	Description:  Función que obtiene e imprime los resultados de la busqueda
		//////////////////////////////////////////////////////////////////////////////
		global $in_class_nom;
		
		include("../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$msg=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$SQL=new class_sql($con);
		require_once("../shared/class_folder/class_funciones.php");
		$fun=new class_funciones();
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];

		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=50>Cuota</td>";
		print "<td width=100>Período</td>";
		print "<td width=100>Fecha Inicio</td>";
		print "<td width=100>Fecha Fin</td>";
		print "<td width=100>Monto</td>";
		print "<td width=50>Cancelada</td>";
		print "</tr>";
		$ls_sql="SELECT numcuo, percob, feciniper, fecfinper, moncuo, estcuo ".
				"  FROM sno_prestamosperiodo ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codnom='".$ls_codnom."' ".
				"   AND codper='".$as_codper."'".
				"   AND codtippre='".$as_codtippre."'".
				"   AND numpre=".$ai_numpre."".
				" ORDER BY numcuo ";
		$rs_pre=$SQL->select($ls_sql);
		$li_i=0;
		while($row=$SQL->fetch_row($rs_pre))
		{
			$li_i=$li_i+1;
			$li_numcuo=$row["numcuo"];
			$ls_percob=$row["percob"];
			$ld_feciniper=$fun->uf_formatovalidofecha($row["feciniper"]);
			$ld_fecfinper=$fun->uf_formatovalidofecha($row["fecfinper"]);
			$ld_feciniper=$fun->uf_convertirfecmostrar($ld_feciniper);
			$ld_fecfinper=$fun->uf_convertirfecmostrar($ld_fecfinper);
			$li_cuopre=$in_class_nom->uf_formatonumerico($row["moncuo"]);
			if($row["estcuo"]==1)
			{
				$ls_estpre="checked";
			}
			else
			{
				$ls_estpre="";
			}

			switch ($as_tipo)
			{
				case "":
					if($li_i%2!=0)
					{
						$ls_color="class=celdas-blancas";
					}
					else
					{
						$ls_color="class=celdas-azules";
					}				
					print "<tr ".$ls_color.">";
					print "<td align='center'>".$li_numcuo."</td>";
					print "<td align='center'>".$ls_percob."</td>";
					print "<td align='center'>".$ld_feciniper."</td>";
					print "<td align='center'>".$ld_fecfinper."</td>";
					print "<td align='right'>".$li_cuopre."</td>";
					print "<td align='center'><input name='chk".$li_numcuo."' type='checkbox' ".$ls_estpre." disabled></td>";
					print "</tr>";			
					break;
			}
		}
		print "</table>";
		$SQL->free_result($rs_pre);	
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Cuotas de Prestamo</title>
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
  <table width="500" height="20" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="496" colspan="2" class="titulo-ventana">Cuotas de Prestamo </td>
    </tr>
  </table>
<br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$in_class_nom=new class_funciones_nomina();
	$ls_tipo=$in_class_nom->uf_obtenertipo();
	$li_numpre=$in_class_nom->uf_obtenervalor_get("numpre","0");
	$ls_codper=$in_class_nom->uf_obtenervalor_get("codper","0");
	$ls_codtippre=$in_class_nom->uf_obtenervalor_get("codtippre","0");
	uf_imprimirresultados($li_numpre,$ls_codper,$ls_codtippre,$ls_tipo);
?>
</div>
</form>
</body>
<script language="JavaScript">
function aceptar(spg_cuenta,denominacion)
{
	opener.document.form1.txtcuepre.value=spg_cuenta;
    opener.document.form1.txtdencuepre.value=denominacion;
	close();
}

function aceptarpatronal(spg_cuenta,denominacion)
{
	opener.document.form1.txtcueprepat.value=spg_cuenta;
    opener.document.form1.txtdencueprepat.value=denominacion;
	close();
}
</script>
</html>