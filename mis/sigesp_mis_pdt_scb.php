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
   function uf_imprimirresultados($as_numdoc,$as_codban,$as_ctaban,$as_codope)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_numdoc  // Número de Documento
		//	    		   as_codban  // Código de Banco
		//	    		   as_ctaban  // Cuenta de Banco
		//	    		   as_codope  // Código de Operación
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
		$ls_sql="SELECT codban,ctaban,numdoc,fecmov,conmov,tipo_destino,cod_pro,ced_bene,codope, ".
				"       (SELECT nomban FROM scb_banco ".
				"		  WHERE codemp = '".$ls_codemp."' ".
				"			AND codban = '".$as_codban."' ) as nomban,  ".
				"		(SELECT nompro FROM rpc_proveedor ".
				"		  WHERE rpc_proveedor.codemp = scb_movbco.codemp ".
				"           AND rpc_proveedor.cod_pro = scb_movbco.cod_pro ) as nompro, ".
				"		(SELECT nombene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = scb_movbco.codemp ".
				"           AND rpc_beneficiario.ced_bene = scb_movbco.ced_bene ) as nombene, ".
				"		(SELECT apebene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = scb_movbco.codemp ".
				"           AND rpc_beneficiario.ced_bene = scb_movbco.ced_bene ) as apebene ".
                "  FROM scb_movbco ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND codope='".$as_codope."' ".
				" GROUP BY codemp,numdoc,codban,ctaban,fecmov,conmov,tipo_destino,cod_pro,ced_bene,codope ";
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
				$ls_tipo_destino=$row["tipo_destino"];
				switch($ls_tipo_destino)
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
				$ls_codope=$row["codope"];
				$ls_tabla="scb_movbco_spg";
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
						$ls_tabla="scb_movbco_spgop";
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
				print "		<td><div align='right' class='texto-azul'>".$ls_destino."</div></td>";
				print "		<td><div align='left'>".$ls_nombre_destino."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				$ls_sql="SELECT codestpro, estcla, spg_cuenta, monto ".
						"  FROM ".$ls_tabla." ".
						" WHERE codemp='".$ls_codemp."' ".
						"   AND numdoc='".$as_numdoc."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND codope='".$as_codope."' ";
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
					print "		<td colspan='4' class='titulo-celdanew'>Detalle Presupuestario Gasto</td>";
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
				
				$ls_sql="SELECT spi_cuenta, monto, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5 ".
						"  FROM scb_movbco_spi ".
						" WHERE codemp='".$ls_codemp."' ".
						"   AND numdoc='".$as_numdoc."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND codope='".$as_codope."' ";
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql2->message)); 
				}
				else
				{
				    $ls_estmodest     = $_SESSION["la_empresa"]["estmodest"];
					$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
					$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
					$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
					$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
					$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
					$li_estpreing = $_SESSION["la_empresa"]["estpreing"];
					if ($li_estpreing==1)
					{					
						print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
						print "	<tr class='titulo-celdanew' >";
						print "		<td colspan='8'>Detalle Presupuestario de Ingreso</td>";
						print " </tr>";
						print " <tr class=titulo-celdanew>";
						print "		<td colspan='2' width='225'>Estructura Presupuestaria</td>";
						print "		<td colspan='2' width='225'>Cuenta</td>";
						print "		<td width='100'>Monto</td>";
						print "	</tr>";
					}
					else
					{
						print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
						print "	<tr>";
						print "		<td colspan='4' class='titulo-celdanew'>Detalle Presupuestario de Ingreso</td>";
						print " </tr>";
						print " <tr class=titulo-celdanew>";
						print "		<td colspan='2' width='225'>Cuenta</td>";
						print "		<td width='100'>Monto</td>";
						print "	</tr>";
					}
					$li_total=0;					
					while($row=$io_sql2->fetch_row($rs_data2))
					{
						$ls_cuenta=$row["spi_cuenta"];
						$li_total=$li_total+$row["monto"];
						$li_monto=$in_class_mis->uf_formatonumerico($row["monto"]);
						
						$ls_codestpro1    = trim(substr(substr($rs_data2->fields["codestpro1"],0,25),-$li_loncodestpro1));
                        $ls_codestpro2    = trim(substr(substr($rs_data2->fields["codestpro2"],0,25),-$li_loncodestpro2));
						$ls_codestpro3    = trim(substr(substr($rs_data2->fields["codestpro3"],0,25),-$li_loncodestpro3));
						if ($ls_estmodest==2)
						{
						  $ls_denestcla="";
						  $ls_codestpro4   = trim(substr(substr($rs_data2->fields["codestpro4"],0,25),-$li_loncodestpro4));
						  $ls_codestpro5   = trim(substr(substr($rs_data2->fields["codestpro5"],0,25),-$li_loncodestpro5));
						  $ls_programatica = $ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;			
						  }
						  else
						  {
						  	$ls_programatica = $ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3;			
						  }
																						  		
						if ($li_estpreing==1)
					    {
							print "<tr class=celdas-blancas>";
							print "<td colspan='2'  align=center width='225'>".$ls_programatica."</td>";
							print "<td colspan='2'  align=center width='225'>".$ls_cuenta."</td>";
							print "<td align=right width='100'>".$li_monto."  </td>";
							print "</tr>";	  
						}
						else
						{
							print "<tr class=celdas-blancas>";
							print "<td colspan='2'  align=center width='225'>".$ls_cuenta."</td>";
							print "<td align=right width='100'>".$li_monto."  </td>";
							print "</tr>";
						}			
					}
					if ($li_estpreing==1)
					{
						$li_total=$in_class_mis->uf_formatonumerico($li_total);
						print "	<tr class=celdas-blancas>";
						print "		<td colspan='4' width='450' align='right' class='texto-azul'>Total</td>";
						print "		<td width='100' align='right' class='texto-azul'>".$li_total."</td>";
						print " </tr>";
						print "</table>";
					}
					else
					{
						$li_total=$in_class_mis->uf_formatonumerico($li_total);
						print "	<tr class=celdas-blancas>";
						print "		<td colspan='2' width='225' align='right' class='texto-azul'>Total</td>";
						print "		<td width='100' align='right' class='texto-azul'>".$li_total."</td>";
						print " </tr>";
						print "</table>";

					}
				}
				$io_sql2->free_result($rs_data2);	

				$ls_sql="SELECT scg_cuenta, debhab, monto ".
						"  FROM scb_movbco_scg ".
						" WHERE codemp='".$ls_codemp."' ".
						"   AND numdoc='".$as_numdoc."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND codope='".$as_codope."' ".
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
						$ls_cuenta=$row["scg_cuenta"];
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
	$ls_numdoc=$in_class_mis->uf_obtenervalor_get("numdoc","");
	$ls_codban=$in_class_mis->uf_obtenervalor_get("codban","");
	$ls_ctaban=$in_class_mis->uf_obtenervalor_get("ctaban","");
	$ls_codope=$in_class_mis->uf_obtenervalor_get("codope","");
	uf_imprimirresultados($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
?>
</div>
</form>
</body>
</html>