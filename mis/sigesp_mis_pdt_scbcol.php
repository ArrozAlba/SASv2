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
   function uf_imprimirresultados($as_numdoc,$as_codban,$as_ctaban,$as_codope,$as_numcol)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_numdoc  // Número de Documento
		//	    		   as_codban  // Código de Banco
		//	    		   as_ctaban  // Cuenta de Banco
		//	    		   as_codope  // Código de Operación
		//	    		   as_numcol  // Número de Colocación
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
		$ls_sql="SELECT codban,ctaban,numdoc,fecmovcol,conmov,codope, ".
				"       (SELECT nomban FROM scb_banco ".
				"		  WHERE codemp = '".$ls_codemp."' ".
				"			AND codban = '".$as_codban."' ) as nomban ".
                "  FROM scb_movcol ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND codope='".$as_codope."' ".
				"	AND numcol='".$as_numcol."' ".
				" GROUP BY codemp,codban,ctaban,numdoc,fecmovcol,conmov,codope ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codban=$row["codban"];
				$ls_nomban=$row["nomban"];
				$ls_conmov=$row["conmov"];
				$ls_codope=$row["codope"];
				switch($ls_codope)
				{
					case "ND":
						$ls_codope="NOTA DE DÉBITO";
						break;	
					case "NC":
						$ls_codope="NOTA DE CRÉDITO";
						break;
					case "CH":
						$ls_codope="CHEQUE";
						break;
					case "DP":
						$ls_codope="DEPÓSITO";
						break;
					case "RE":
						$ls_codope="RETIRO";
						break;
					case "OP":
						$ls_codope="ORDEN DE PAGO DIRECTA";
						break;
				}
				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Comprobante</div></td>";
				print "		<td width='350'><div align='left'>".$as_numdoc."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Descripci&oacute;n </div></td>";
				print "		<td><div align='justify'>".$ls_conmov."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Operaci&oacute;n </div></td>";
				print "		<td><div align='left'>".$ls_codope."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Banco </div></td>";
				print "		<td><div align='left'>".$ls_codban." - ".$ls_nomban."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				$ls_sql="SELECT sc_cuenta, debhab, monto ".
						"  FROM scb_movcol_scg ".
						" WHERE codemp='".$ls_codemp."' ".
						"   AND numdoc='".$as_numdoc."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND codope='".$as_codope."' ".
						"	AND numcol='".$as_numcol."' ".
						" ORDER BY  debhab ";
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
	$ls_numcol=$in_class_mis->uf_obtenervalor_get("numcol","");
	$ls_numdoc=$in_class_mis->uf_obtenervalor_get("numdoc","");
	$ls_codban=$in_class_mis->uf_obtenervalor_get("codban","");
	$ls_ctaban=$in_class_mis->uf_obtenervalor_get("ctaban","");
	$ls_codope=$in_class_mis->uf_obtenervalor_get("codope","");
	uf_imprimirresultados($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_numcol);
?>
</div>
</form>
</body>
</html>