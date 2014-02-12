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
   function uf_imprimirresultados($as_numsol)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_numsol  // Número de solicitud
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
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sql="SELECT numsol, cod_pro, ced_bene, MAX(fecope) AS fecope, MAX(desope) AS desope, ".
				"		(SELECT nompro FROM rpc_proveedor ".
				"		  WHERE rpc_proveedor.codemp = cxp_sol_dc.codemp ".
				"           AND rpc_proveedor.cod_pro = cxp_sol_dc.cod_pro ) as nompro, ".
				"		(SELECT nombene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = cxp_sol_dc.codemp ".
				"           AND rpc_beneficiario.ced_bene = cxp_sol_dc.ced_bene ) as nombene, ".
				"		(SELECT apebene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = cxp_sol_dc.codemp ".
				"           AND rpc_beneficiario.ced_bene = cxp_sol_dc.ced_bene ) as apebene ".
                "  FROM cxp_sol_dc ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
				" GROUP BY codemp, numsol, numrecdoc, codtipdoc, codope, cod_pro, ced_bene, numdc  ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numsol=$row["numsol"];
				$ld_fecope=$io_funciones->uf_convertirfecmostrar($row["fecope"]);
				$ls_desope=$row["desope"];
				$ls_codpro=$row["cod_pro"];
				$ls_cedbene=$row["ced_bene"];
				if(($ls_codpro=="----------")&&($ls_cedbene!="----------"))
				{
					$ls_tipproben="B";
				}
				if(($ls_cedbene=="----------")&&(($ls_codpro!="----------")))
				{
					$ls_tipproben="P";
				}
				if(($ls_cedbene=="----------")&&(($ls_codpro=="----------")))
				{
					$ls_tipproben="-";
				}
				switch($ls_tipproben)
				{
					case "P":
						$ls_destino="Proveedor";
						$ls_nombre_destino=$row["cod_pro"]." - ".$row["nompro"];
						break;
	
					case "B":
						$ls_destino="Beneficiario";
						$ls_nombre_destino=$row["ced_bene"]." - ".$row["apebene"].", ".$row["nombene"];
						break;

					case "-":
						$ls_destino="Ninguno";
						$ls_nombre_destino="-";
						break;
				}

				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Nro Solicitud</div></td>";
				print "		<td width='350'><div align='left'>".$ls_numsol."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Concepto </div></td>";
				print "		<td><div align='justify'>".$ls_desope."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>".$ls_destino."</div></td>";
				print "		<td><div align='left'>".$ls_nombre_destino."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Fecha de Operación </div></td>";
				print "		<td><div align='left'>".$ld_fecope."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				$ls_sql="SELECT cxp_dc_spg.codestpro, cxp_dc_spg.estcla, cxp_dc_spg.spg_cuenta, cxp_dc_spg.monto ".
						"  FROM cxp_dc_spg  ".
						" WHERE cxp_dc_spg.codemp='".$ls_codemp."' ".
						"   AND cxp_dc_spg.numsol='".$as_numsol."' ";
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					print $io_sql2->message;
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
					print "		<td colspan='4' class='titulo-celdanew'>Detalle Presupuestario de Gasto</td>";
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
						$ls_cuenta=$row["spg_cuenta"];
						$li_total=$li_total+$row["monto"];
						$li_monto=$in_class_mis->uf_formatonumerico($row["monto"]);
						$ls_codestpro=$row["codestpro"];
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

				$ls_sql="SELECT cxp_dc_scg.sc_cuenta, cxp_dc_scg.monto, cxp_dc_scg.debhab ".
						"  FROM cxp_dc_scg  ".
						" WHERE cxp_dc_scg.codemp='".$ls_codemp."' ".
						"   AND cxp_dc_scg.numsol='".$as_numsol."' ";
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
						$ls_debhab=$row["debhab"];
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
	$ls_numsol=$in_class_mis->uf_obtenervalor_get("numsol","");
	uf_imprimirresultados($ls_numsol);
?>
</div>
</form>
</body>
</html>