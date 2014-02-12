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
   function uf_imprimirresultados($as_archivo,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban)
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
		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../shared/class_folder/class_funciones_xml.php");
		$io_xml=new class_funciones_xml();		

		$ls_titulo="";
		$li_len1=0;
		$li_len2=0;
		$li_len3=0;
		$li_len4=0;
		$li_len5=0;
		$in_class_mis->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$la_data=$io_xml->uf_cargar_rpc_beneficiario($as_archivo);
		$li_total=count($la_data);
		for($i=1;$i<=$li_total;$i++)
		{
			$ls_cedbene=$la_data[$i]["ced_bene"];
			$ls_nombene=$la_data[$i]["nombene"];
			$ls_apebene=$la_data[$i]["apebene"];
			$ls_destino="Beneficiario";
			$ls_nombre_destino=$ls_cedbene." - ".$ls_apebene.", ".$ls_apebene;
			$ls_operacion="COMPROMISO";
		}
		print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
		print "	<tr>";
		print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
		print " </tr>";
		print "</table>";
		print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
		print "  <tr>";
		print "		<td width='100'><div align='right' class='texto-azul'>Nro de Credito</div></td>";
		print "		<td width='350'><div align='left'>".$as_comprobante."</div></td>";
		print "  </tr>";
		print "  <tr>";
		print "		<td><div align='right' class='texto-azul'>".$ls_destino."</div></td>";
		print "		<td><div align='left'>".$ls_nombre_destino."</div></td>";
		print "  </tr>";
		print "  <tr>";
		print "		<td><div align='right' class='texto-azul'>Contabilizaci&oacute;n </div></td>";
		print "		<td><div align='left'>".$ls_operacion."</div></td>";
		print "  </tr>";
		print "  <tr>";
		print "		<td><div align='right' class='texto-azul'></div></td>";
		print "		<td><div align='left'></div></td>";
		print "  </tr>";
		print "</table>";
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
		$la_data=$io_xml->uf_cargar_spg_dt_cmp($as_archivo,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban);
		$li_totaldata=count($la_data);
		$li_total=0;
		for($i=1;$i<=$li_totaldata;$i++)
		{
			$ls_codestpro1=$la_data[$i]["codestpro1"];
			$ls_codestpro2=$la_data[$i]["codestpro2"];
			$ls_codestpro3=$la_data[$i]["codestpro3"];
			$ls_codestpro4=$la_data[$i]["codestpro4"];
			$ls_codestpro5=$la_data[$i]["codestpro5"];			  
			$ls_estcla=$la_data[$i]["estcla"];			  
			$ls_spg_cuenta=$la_data[$i]["spg_cuenta"];			
			$ldec_monto=$la_data[$i]["monto"];

			$li_total=$li_total+$ldec_monto;
			$li_monto=$in_class_mis->uf_formatonumerico($ldec_monto);
			$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
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
			print "<td align=center width='100'>".$ls_spg_cuenta."</td>";
			print "<td align=right width='100'>".$li_monto."  </td>";
			print "</tr>";			
		}
		$li_total=$in_class_mis->uf_formatonumerico($li_total);
		print "	<tr class=celdas-blancas>";
		print "		<td colspan='3' align='right' class='texto-azul'>Total</td>";
		print "		<td width='100' align='right' class='texto-azul'>".$li_total."</td>";
		print " </tr>";
		print "</table>";
		print "<br><br>";	
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
	$ls_archivo=$in_class_mis->uf_obtenervalor_get("archivo","");
	$ls_codemp=$in_class_mis->uf_obtenervalor_get("codemp","");
	$ls_procede=$in_class_mis->uf_obtenervalor_get("procede","");
	$ls_comprobante=$in_class_mis->uf_obtenervalor_get("comprobante","");
	$ld_fecha=$in_class_mis->uf_obtenervalor_get("fecha","");
	$ls_codban=$in_class_mis->uf_obtenervalor_get("codban","");
	$ls_ctaban=$in_class_mis->uf_obtenervalor_get("ctaban","");
	uf_imprimirresultados($ls_archivo,$ls_codemp,$ls_procede,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);
?>
</div>
</form>
</body>
</html>