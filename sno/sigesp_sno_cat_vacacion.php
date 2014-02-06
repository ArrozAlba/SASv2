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
		//	     Function: uf_print
		//		   Access: public
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/03/2006 								Fecha Última Modificación : 
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
		print "<td width=70>Código Personal</td>";
		print "<td width=350>Apellidos y Nombre</td>";
		print "<td width=80>Número de Vacación</td>";
		print "</tr>";
		$ls_sql="SELECT sno_personal.codper, sno_personal.nomper, sno_personal.apeper, sno_vacacpersonal.codvac ".
				"  FROM sno_vacacpersonal, sno_personalnomina, sno_personal, sno_salida ".
				" WHERE sno_personalnomina.codemp='".$ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$ls_codnom."' ".
				"   AND sno_vacacpersonal.stavac=2 ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"	AND sno_personal.codemp = sno_vacacpersonal.codemp ".
				"	AND sno_personal.codper = sno_vacacpersonal.codper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND (sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'V4' ".
				"    OR  sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'W3' OR sno_salida.tipsal = 'W4') ".
				" GROUP BY sno_personal.codper, sno_vacacpersonal.codvac, sno_personal.nomper, sno_personal.apeper ".
				" ORDER BY sno_personal.codper, sno_vacacpersonal.codvac ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_codvac=$row["codvac"];
				$ls_codper=$row["codper"];
				$ls_nomper=$row["apeper"].", ".$row["nomper"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$li_codvac','$ls_codper','$ls_nomper');\">".$ls_codper."</a></td>";
				print "<td>".$ls_nomper."</td>";
				print "<td>".$li_codvac."</td>";
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
<title>Cat&aacute;logo de Vacaciones Programadas</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Vacaciones Programadas </td>
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
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codvac,codper,nomper)
{
	opener.document.form1.txtcodvac.value=codvac;
	opener.document.form1.txtcodvac.readOnly=true;
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
	opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	close();
}
function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_vacacion.php";
  	f.submit();
}
</script>
</html>
