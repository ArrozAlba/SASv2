<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Estructura Presupuestaria ";
			$li_len1=20;
			$li_len2=6;
			$li_len3=3;
			$li_len4=2;
			$li_len5=2;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Estructura Programática ";
			$li_len1=2;
			$li_len2=2;
			$li_len3=2;
			$li_len4=2;
			$li_len5=2;
			break;
	}

   //--------------------------------------------------------------
   function uf_print($as_spg_cuenta, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_spg_cuenta  // Código de cuenta
		//				   as_tipo  // Tipo de Llamada del catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $ls_titulo, $li_len1, $li_len2, $li_len3, $li_len4, $li_len5;

		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("class_folder/class_funciones_viaticos.php");
		$io_fun_viaticos=new class_funciones_viaticos();
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=70>Código</td>";
		print "<td width=210>Denominación</td>";
		print "<td width=220>".$ls_titulo."</td>";
		print "</tr>";
		$ls_sql="SELECT spg_cuenta, denominacion, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5 ".
				"  FROM spg_cuentas ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND status='C'";
		$la_spg_cuenta=split(",",$as_spg_cuenta);
		$li_total=count($la_spg_cuenta);
		for($li_i=0;$li_i<$li_total;$li_i++)
		{
			if($li_i==0)
			{
				$ls_sql=$ls_sql."   AND spg_cuenta like '".$la_spg_cuenta[$li_i]."%'";
			}
			else
			{
				$ls_sql=$ls_sql."    OR spg_cuenta like '".$la_spg_cuenta[$li_i]."%'";
			}
		
		}
		$ls_sql=$ls_sql." ORDER BY spg_cuenta";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_spg_cuenta=trim($row["spg_cuenta"]);
				$ls_denominacion=$row["denominacion"];
				$ls_codest1=$row["codestpro1"];
				//$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
				$ls_codest2=$row["codestpro2"];
				//$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
				$ls_codest3=$row["codestpro3"];
				//$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
				$ls_codest4=$row["codestpro4"];
				//$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
				$ls_codest5=$row["codestpro5"];
				//$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
				$ls_codpro=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
				$io_fun_viaticos->uf_formatoprogramatica($ls_codpro,&$ls_programatica);
				switch ($as_tipo)
				{
					case "NACIONALES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_nacionales('$ls_spg_cuenta','$ls_denominacion');\">".$ls_spg_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "<td align=center>".$ls_programatica."</td>";
						print "</tr>";			
					break;

					case "INTERNACIONALES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar_internacionales('$ls_spg_cuenta','$ls_denominacion');\">".$ls_spg_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "<td align=center>".$ls_programatica."</td>";
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
<title>Cat&aacute;logo de Cuentas</title>
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
      <td width="496" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cuentas</td>
    </tr>
  </table>
<br>
<br>
<?php
	require_once("class_folder/class_funciones_viaticos.php");
	$io_fun_viaticos=new class_funciones_viaticos();
	$ls_operacion =$io_fun_viaticos->uf_obteneroperacion();
	$ls_tipo=$io_fun_viaticos->uf_obtenertipo();
	$ls_spg_cuenta="403";
	uf_print($ls_spg_cuenta, $ls_tipo);
	unset($io_fun_viaticos);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(spg_cuenta,denominacion)
{
	opener.document.form1.txtcuepre.value=spg_cuenta;
    opener.document.form1.txtdencuepre.value=denominacion;
	close();
}

function aceptar_nacionales(spg_cuenta,denominacion)
{
	opener.document.form1.txtspgnac.value=spg_cuenta;
    opener.document.form1.txtdenspgnac.value=denominacion;
	close();
}

function aceptar_internacionales(spg_cuenta,denominacion)
{
	opener.document.form1.txtspgint.value=spg_cuenta;
    opener.document.form1.txtdenspgint.value=denominacion;
	close();
}

</script>
</html>