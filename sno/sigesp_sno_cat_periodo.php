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
   function uf_print($as_tipo,$as_perdes)
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
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
        $ld_peractnom=$_SESSION["la_nomina"]["peractnom"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=10>Período</td>";
		print "<td width=200>Fecha de Inicio</td>";
		print "<td width=200>Fecha de Finalización</td>";
		print "</tr>";
		$ls_sql="SELECT codperi, fecdesper, fechasper ".
				"  FROM sno_periodo ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codnom='".$ls_codnom."'".
				"   AND codperi <> '000' ".
				"   AND cerper=0";				
		if($as_perdes!="")
		{
			$ls_sql=$ls_sql."   AND codperi>='".$ld_peractnom."'".
							"   AND codperi>='".$as_perdes."'";
		}
		if($as_tipo=="prestamo")
		{
			$ls_sql=$ls_sql."   AND codperi>='".$ld_peractnom."'";
		}
		$ls_sql=$ls_sql." ORDER BY codperi ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codperi=$row["codperi"];
				$ld_fecdesper=$io_funciones->uf_formatovalidofecha($row["fecdesper"]);
				$ld_fechasper=$io_funciones->uf_formatovalidofecha($row["fechasper"]);
				$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($ld_fecdesper);
				$ld_fechasper=$io_funciones->uf_convertirfecmostrar($ld_fechasper);
				
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codperi','$ld_fecdesper','$ld_fechasper');\">".$ls_codperi."</a></td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;
	
					case "prestamo":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codperi','$ld_fecdesper','$ld_fechasper');\">".$ls_codperi."</a></td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;
	
					case "pressusdesde":// es llamado desde sigesp_sno_p_prestamossuspender.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_prestsus_desde('$ls_codperi','$ld_fecdesper','$ld_fechasper');\">".$ls_codperi."</a></td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
						print "</tr>";			
						break;
	
					case "pressushasta":// es llamado desde sigesp_sno_p_prestamossuspender.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_prestsus_hasta('$ls_codperi','$ld_fecdesper','$ld_fechasper');\">".$ls_codperi."</a></td>";
						print "<td>".$ld_fecdesper."</td>";
						print "<td>".$ld_fechasper."</td>";
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
		unset($ld_peractnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Per&iacute;odos</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Per&iacute;odos </td>
    </tr>
  </table>
<br>
    <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_perdes=$io_fun_nomina->uf_obtenervalor_get("perdes","");
	uf_print($ls_tipo,$ls_perdes);
	unset($io_fun_nomina);
?>
</div>
</form>
</body>
<script language="JavaScript">
function aceptar(codperi,fecdesper,fechasper)
{
	opener.document.form1.txtperinipre.value=codperi;
	opener.document.form1.txtperinipre.readOnly=true;
    opener.document.form1.txtfecdesper.value=fecdesper;
	opener.document.form1.txtfecdesper.readOnly=true;
    opener.document.form1.txtfechasper.value=fechasper;
	opener.document.form1.txtfechasper.readOnly=true;
	close();
}

function aceptar_prestsus_desde(codperi,fecdesper,fechasper)
{
	opener.document.form1.txtperdes.value=codperi;
	opener.document.form1.txtperdes.readOnly=true;
    opener.document.form1.txtfecdes1.value=fecdesper;
	opener.document.form1.txtfecdes1.readOnly=true;
    opener.document.form1.txtfechas1.value=fechasper;
	opener.document.form1.txtfechas1.readOnly=true;
	close();
}

function aceptar_prestsus_hasta(codperi,fecdesper,fechasper)
{
	opener.document.form1.txtperhas.value=codperi;
	opener.document.form1.txtperhas.readOnly=true;
    opener.document.form1.txtfecdes2.value=fecdesper;
	opener.document.form1.txtfecdes2.readOnly=true;
    opener.document.form1.txtfechas2.value=fechasper;
	opener.document.form1.txtfechas2.readOnly=true;
	close();
}
</script>
</html>
