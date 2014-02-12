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
   function uf_imprimirresultados($as_numordcom,$as_operacion)
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
		$ls_sql="SELECT numordcom, estcondat, fecordcom, obscom, fecaprord, cod_pro, ".
				"		(SELECT nompro FROM rpc_proveedor ".
				"		  WHERE rpc_proveedor.codemp = soc_ordencompra.codemp ".
				"           AND rpc_proveedor.cod_pro = soc_ordencompra.cod_pro ) as nompro ".
                "  FROM soc_ordencompra ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND numordcom = '".$as_numordcom."'".
				"   AND estcondat = '".$as_operacion."' ".
				"   AND estapro = 1 ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numordcom=$row["numordcom"];
				$ls_estcondat=$row["estcondat"];
				$ls_estatus="";
				switch($ls_estcondat)
				{
					case "-":
						$ls_estatus="Bienes/Servicios";
						break;
					case "B":
						$ls_estatus="Bienes";
						break;
					case "S":
						$ls_estatus="Servicios";
						break;
				}

				$ls_obscom=$row["obscom"];
				$ls_codprov=$row["cod_pro"];
				$ls_nomprov=$row["nompro"];
				$ld_fecordcom = $io_funciones->uf_convertirfecmostrar($row["fecordcom"]);
				$ld_fecaprord = $io_funciones->uf_convertirfecmostrar($row["fecaprord"]);
				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Número</div></td>";
				print "		<td width='350'><div align='left'>".$ls_numordcom."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Tipo </div></td>";
				print "		<td><div align='justify'>".$ls_estatus."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Fecha</div></td>";
				print "		<td><div align='left'>".$ld_fecordcom."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Fecha de Aprobación</div></td>";
				print "		<td><div align='left'>".$ld_fecaprord."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Proveedor</div></td>";
				print "		<td><div align='left'>".$ls_codprov." - ".$ls_nomprov."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, spg_cuenta, monto ".
						"  FROM soc_cuentagasto ".
						" WHERE codemp='".$ls_codemp."' ".
						"   AND numordcom='".$as_numordcom."' ".
						"   AND estcondat='".$as_operacion."' ";
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
	$ls_numordcom=$in_class_mis->uf_obtenervalor_get("numordcom","");
	$ls_operacion=$in_class_mis->uf_obtenervalor_get("operacion","");
	uf_imprimirresultados($ls_numordcom,$ls_operacion);
?>
</div>
</form>
</body>
</html>