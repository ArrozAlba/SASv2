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
   function uf_imprimirresultados($as_comprobante,$as_procede,$as_fecha,$as_codban,$as_ctaban)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Documento
		//	    		   as_procede  // Procede
		//	    		   as_fecha  // Fecha
		//	    		   as_codban  // Código de Banco
		//	    		   as_ctaban  // Cuenta de Banco
		//	  Description: Función que Imprime los detalles del comprobante
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 												Fecha Última Modificación : 
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
		$ls_sql="SELECT codban, MAX(descripcion) as descripcion, ".
				"       (SELECT nomban FROM scb_banco ".
				"		  WHERE codemp = '".$ls_codemp."' ".
				"			AND codban = '".$as_codban."' ) as nomban  ".
                "  FROM mis_sigesp_banco ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND procede='".$as_procede."' ".
				"   AND fecdep='".$as_fecha."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				" GROUP BY comprobante, procede, fecdep, codban, ctaban ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$ls_codban=$rs_data->fields["codban"];
				$ls_nomban=$rs_data->fields["nomban"];
				$ls_descripcion=$rs_data->fields["descripcion"];
				$ls_codope="DEPÓSITO";
				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Comprobante</div></td>";
				print "		<td width='350'><div align='left'>".$as_comprobante."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Descripci&oacute;n </div></td>";
				print "		<td><div align='justify'>".$ls_descripcion."</div></td>";
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
				$ls_sql="SELECT sc_cuenta, monto ".
						"  FROM mis_sigesp_banco ".
						" WHERE codemp='".$ls_codemp."' ".
						"   AND comprobante='".$as_comprobante."' ".
						"   AND procede='".$as_procede."' ".
						"   AND fecdep='".$as_fecha."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND modulo='SPI' ".
						" ORDER BY sc_cuenta ";
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql2->message)); 
				}
				else
				{
					print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
					print "	<tr>";
					print "		<td colspan='2' class='titulo-celdanew'>Detalle Presupuestario Ingreso</td>";
					print " </tr>";
					print " <tr class=titulo-celdanew>";
					print "		<td width='250'>Cuenta</td>";
					print "		<td width='200'>Monto</td>";
					print "	</tr>";
					$li_total=0;
					while(!$rs_data2->EOF)
					{
						$ls_cuenta=$rs_data2->fields["sc_cuenta"];
						$li_total=$li_total+$rs_data2->fields["monto"];
						$li_monto=$in_class_mis->uf_formatonumerico($rs_data2->fields["monto"]);
						print "<tr class=celdas-blancas>";
						print "<td align=center width='250'>".$ls_cuenta."</td>";
						print "<td align=right width='200'>".$li_monto."  </td>";
						print "</tr>";		
						$rs_data2->MoveNext();	
					}
					$li_total=$in_class_mis->uf_formatonumerico($li_total);
					print "	<tr class=celdas-blancas>";
					print "		<td width='250' align='right' class='texto-azul'>Total</td>";
					print "		<td width='200' align='right' class='texto-azul'>".$li_total."</td>";
					print " </tr>";
					print "</table>";
				}				
				$ls_sql="SELECT sc_cuenta, debhab, monto ".
						"  FROM mis_sigesp_banco ".
						" WHERE codemp='".$ls_codemp."' ".
						"   AND comprobante='".$as_comprobante."' ".
						"   AND procede='".$as_procede."' ".
						"   AND fecdep='".$as_fecha."' ".
						"   AND codban='".$as_codban."' ".
						"   AND ctaban='".$as_ctaban."' ".
						"   AND modulo='SCB' ".
						" ORDER BY debhab";
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
					while(!$rs_data2->EOF)
					{
						$ls_cuenta=$rs_data2->fields["sc_cuenta"];
						$li_monto=$rs_data2->fields["monto"];
						$ls_debhab=$rs_data2->fields["debhab"];
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
						$rs_data2->MoveNext();
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
				print "<br><br>";	
			}
			$rs_data->MoveNext();
		}
   }
   //----------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
--><title>Detalle Comprobante</title>
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
	$ls_comprobante=$in_class_mis->uf_obtenervalor_get("comprobante","");
	$ls_procede=$in_class_mis->uf_obtenervalor_get("procede","");
	$ls_fecha=$in_class_mis->uf_obtenervalor_get("fecha","");
	$ls_codban=$in_class_mis->uf_obtenervalor_get("codban","");
	$ls_ctaban=$in_class_mis->uf_obtenervalor_get("ctaban","");
	uf_imprimirresultados($ls_comprobante,$ls_procede,$ls_fecha,$ls_codban,$ls_ctaban);
?>
</div>
</form>
</body>
</html>