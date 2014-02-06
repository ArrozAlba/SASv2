<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	
   //----------------------------------------------------------------------------------------------------------------------------
   function uf_imprimirresultados($as_mes,$as_ano)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_mes  // Mes de la Depreciación
		//	    		   as_ano  // Año de la Depreciación
		//	  Description: Función que Imprime los detalles del comprobante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/10/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $in_class_mis;
		
		require_once("../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($con);
		require_once("../shared/class_folder/class_sql.php");
		$io_sql2=new class_sql($con);
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sql="SELECT SUM(mondepmen) AS monto  ".
				"  FROM saf_depreciacion ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND SUBSTR(fecdep,1,4) = '".$as_ano."' ".
				"   AND SUBSTR(fecdep,6,2) = '".$as_mes."' ".
				" GROUP BY SUBSTR(fecdep,1,4), SUBSTR(fecdep,6,2) ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_comprobante=str_pad($as_mes.$as_ano,15,"0",0);
				$ls_descripcion="DEPRECIACIÓN DE LOS ACTIVOS FIJOS CORRESPONDIENTES AL AÑO ".$as_ano." MES DE ".strtoupper($io_fecha->uf_load_nombre_mes($as_mes));
				$li_monto=number_format($row["monto"],2,",",".");
				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Comprobante</div></td>";
				print "		<td width='350'><div align='left'>".$ls_comprobante."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Descripci&oacute;n </div></td>";
				print "		<td><div align='justify'>".$ls_descripcion."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Monto Total</div></td>";
				print "		<td><div align='left'> ".$li_monto." </div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Contabilizaci&oacute;n </div></td>";
				print "		<td><div align='left'> COMPROMETE, CAUSA Y PAGA </div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				$ls_sql="SELECT SUM(saf_depreciacion.mondepmen) AS monto, saf_activo.codestpro1, saf_activo.codestpro2, ".
						"		saf_activo.codestpro3, saf_activo.codestpro4, saf_activo.codestpro5, saf_activo.estcla, saf_activo.spg_cuenta_dep ".
						"  FROM saf_depreciacion, saf_activo ".
						" WHERE saf_depreciacion.codemp='".$ls_codemp."' ".
						"   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."' ".
						"   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ".
						"   AND saf_depreciacion.codemp = saf_activo.codemp	".
						"   AND saf_depreciacion.codact = saf_activo.codact ".
						" GROUP BY saf_activo.codestpro1, saf_activo.codestpro2, saf_activo.codestpro3, saf_activo.codestpro4, ".
						"		   saf_activo.codestpro5, saf_activo.estcla, saf_activo.spg_cuenta_dep ";
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql2->message)); 
				}
				else
				{
					$ls_titulo="";
					$li_len1=0;
					$li_len2=0;
					$li_len3=0;
					$li_len4=0;
					$li_len5=0;
					$in_class_mis->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
					print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
					print "	<tr>";
					print "		<td colspan='4' class='titulo-celdanew'>Detalle Presupuestario</td>";
					print " </tr>";
					print " <tr class=titulo-celdanew>";
					print "		<td width='150'>".$ls_titulo."</td>";
					print "		<td width='100'>Estatus</td>";
					print "		<td width='100'>Cuenta</td>";
					print "		<td width='100'>Monto</td>";
					print "	</tr>";
					$li_total=0;
					while($row=$io_sql2->fetch_row($rs_data2))
					{
						$ls_cuenta=$row["spg_cuenta_dep"];
						$li_total=$li_total+$row["monto"];
						$li_monto=$in_class_mis->uf_formatonumerico($row["monto"]);
						$ls_codestpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
						$ls_estcla=$row["estcla"];
						$ls_programatica="";
						$ls_estatus="";
						$in_class_mis->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
						switch($ls_estcla)
						{
							case "A":
								$ls_estatus="Acción";
								break;
							case "P":
								$ls_estatus="Proyecto";
								break;
						}
						print "<tr class=celdas-blancas>";
						print "<td align=center width='150'>".$ls_programatica."</td>";
						print "<td align=center width='100'>".$ls_estatus."</td>";
						print "<td align=center width='100'>".$ls_cuenta."</td>";
						print "<td align=right width='100'>".$li_monto."  </td>";
						print "</tr>";			
					}
					$li_total=$in_class_mis->uf_formatonumerico($li_total);
					print "	<tr class=celdas-blancas>";
					print "		<td colspan='3' align='right' class='texto-azul'>Total</td>";
					print "		<td width='100' align='right' class='texto-azul'>".$li_total."</td>";
					print " </tr>";
					print "</table>";
				}
				$io_sql2->free_result($rs_data2);	
				$ls_sql="SELECT SUM(saf_depreciacion.mondepmen) AS monto, 'D' AS operacion, spg_cuentas.sc_cuenta ".
						"  FROM saf_depreciacion,saf_activo, spg_cuentas ".
						" WHERE saf_depreciacion.codemp='".$ls_codemp."' ".
						"   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."' ".
						"   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ".
						"   AND saf_depreciacion.codemp = saf_activo.codemp ".
						"   AND saf_depreciacion.codact = saf_activo.codact ".
						"   AND saf_activo.codemp = spg_cuentas.codemp ".
						"   AND saf_activo.codestpro1 = spg_cuentas.codestpro1 ".
						"   AND saf_activo.codestpro2 = spg_cuentas.codestpro2 ".
						"   AND saf_activo.codestpro3 = spg_cuentas.codestpro3 ".
						"   AND saf_activo.codestpro4 = spg_cuentas.codestpro4 ".
						"   AND saf_activo.codestpro5 = spg_cuentas.codestpro5 ".
						"   AND saf_activo.spg_cuenta_dep = spg_cuentas.spg_cuenta ".
						" GROUP BY spg_cuentas.sc_cuenta ".
						" UNION ".
						"SELECT SUM(saf_depreciacion.mondepmen) AS monto, 'H' AS operacion, saf_activo.sc_cuenta ".
						"  FROM saf_depreciacion,saf_activo, spg_ep1 ".
						" WHERE saf_depreciacion.codemp='".$ls_codemp."' ".
						"   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."' ".
						"   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ".
						"   AND spg_ep1.estint=0".
						"   AND saf_depreciacion.codemp = saf_activo.codemp ".
						"   AND saf_depreciacion.codact = saf_activo.codact ".
						"   AND saf_activo.codemp=spg_ep1.codemp".
						"   AND saf_activo.codestpro1=spg_ep1.codestpro1".
						"   AND saf_activo.estcla=spg_ep1.estcla".
						" GROUP BY saf_activo.sc_cuenta".
						" UNION ".
						"SELECT SUM(saf_depreciacion.mondepmen) AS monto, 'H' AS operacion, trim(spg_ep1.sc_cuenta) as sc_cuenta ".
						"  FROM saf_depreciacion,saf_activo, spg_ep1 ".
						" WHERE saf_depreciacion.codemp='".$ls_codemp."' ".
						"   AND SUBSTR(saf_depreciacion.fecdep,1,4) = '".$as_ano."' ".
						"   AND SUBSTR(saf_depreciacion.fecdep,6,2) = '".$as_mes."' ".
						"   AND spg_ep1.estint=1".
						"   AND saf_depreciacion.codemp = saf_activo.codemp ".
						"   AND saf_depreciacion.codact = saf_activo.codact ".
						"   AND saf_activo.codemp=spg_ep1.codemp".
						"   AND saf_activo.codestpro1=spg_ep1.codestpro1".
						"   AND saf_activo.estcla=spg_ep1.estcla".
						" GROUP BY spg_ep1.sc_cuenta";
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql2->message)); 
				}
				else
				{
					$li_total_deb=0;
					$li_total_hab=0;
					print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
					print "	<tr>";
					print "		<td colspan='3' class='titulo-celdanew'>Detalle Contable</td>";
					print " </tr>";
					print " <tr class=titulo-celdanew>";
					print "		<td width='100'>Cuenta</td>";
					print "		<td width='100'>Debe</td>";
					print "		<td width='100'>Haber</td>";
					print "	</tr>";
					while($row=$io_sql2->fetch_row($rs_data2))
					{
						$ls_cuenta=$row["sc_cuenta"];
						$li_monto=$row["monto"];
						$ls_debhab=$row["operacion"];
						switch($ls_debhab)
						{
							case "D":
								$li_debe=$li_monto;
								$li_debe=$in_class_mis->uf_formatonumerico($li_debe);
								$li_haber="0,00";
								$li_total_deb=$li_total_deb+$li_monto;
								break;
							case "H":
								$li_debe="0,00";
								$li_haber=$li_monto;
								$li_haber=$in_class_mis->uf_formatonumerico($li_haber);
								$li_total_hab=$li_total_hab+$li_monto;
								break;
						}
						print "<tr class=celdas-blancas>";
						print "<td align=center width='100'>".$ls_cuenta."</td>";
						print "<td align=right width='100'>".$li_debe."</td>";
						print "<td align=right width='100'>".$li_haber."</td>";
						print "</tr>";			
					}
					$li_total_deb=$in_class_mis->uf_formatonumerico($li_total_deb);
					$li_total_hab=$in_class_mis->uf_formatonumerico($li_total_hab);
					print "	<tr>";
					print "		<td align=right class='texto-azul'>Total</td>";
					print "		<td align=right class='texto-azul'>".$li_total_deb."</td>";
					print "		<td align=right class='texto-azul'>".$li_total_hab."</td>";
					print " </tr>";
					print "</table>";
				}
				$io_sql2->free_result($rs_data2);
				print "<br><br>";
			}
		}
		$io_sql->free_result($rs_data);	
   }
   //----------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title>Detalle Comprobante</title>
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
<?php
	require_once("class_folder/class_funciones_mis.php");
	$in_class_mis=new class_funciones_mis();
	$ls_mes=$in_class_mis->uf_obtenervalor_get("mes","");
	$ls_ano=$in_class_mis->uf_obtenervalor_get("ano","");
	uf_imprimirresultados($ls_mes,$ls_ano);
?>
</div>
</form>
</body>
</html>