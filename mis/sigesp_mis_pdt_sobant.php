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
   function uf_imprimirresultados($as_codcon,$as_codant)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_codcom  // Número de Comprobante
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
		$ls_group="";
		$ls_criterio="";
		$ls_sql="SELECT sob_anticipo.sc_cuenta AS cuentaanticipo, rpc_proveedor.sc_cuenta AS cuentaproveedor, rpc_proveedor.cod_pro, rpc_proveedor.nompro, ".
				"		sob_anticipo.monto ".
				"  FROM sob_anticipo, sob_contrato, sob_asignacion, rpc_proveedor  ".
				" WHERE sob_anticipo.codemp='".$ls_codemp."' ".
				"   AND sob_anticipo.codant='".$as_codant."'".
				"   AND sob_anticipo.codcon='".$as_codcon."'".
				"   AND sob_anticipo.codemp=sob_contrato.codemp ".
				"   AND sob_anticipo.codcon=sob_contrato.codcon ".
				"   AND sob_asignacion.codemp=sob_contrato.codemp ".
				"   AND sob_asignacion.codasi=sob_contrato.codasi ".
				"   AND rpc_proveedor.codemp=sob_asignacion.codemp ".
				"   AND rpc_proveedor.cod_pro=sob_asignacion.cod_pro ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_nombre_destino=$row["cod_pro"]." - ".$row["nompro"];
				$ls_cuentaanticipo=$row["cuentaanticipo"];
				$ls_cuentaproveedor=$row["cuentaproveedor"];
				$li_monto=number_format($row["monto"],2,",",".");
				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Contrato</div></td>";
				print "		<td width='350'><div align='left'>".$as_codcon."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Anticipo</div></td>";
				print "		<td width='350'><div align='left'>".$as_codant."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Proveedor</div></td>";
				print "		<td><div align='left'>".$ls_nombre_destino."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
				print "	<tr>";
				print "		<td colspan='3' class='titulo-celdanew'>Detalle Contable</td>";
				print " </tr>";
				print " <tr class=titulo-celdanew>";
				print "		<td width='250'>Cuenta Contable</td>";
				print "		<td width='100'>Debe</td>";
				print "		<td width='100'>Haber</td>";
				print "	</tr>";
				print "<tr>";			
				print "		<tr class=celdas-blancas>";
				print "		<td align=center width='250'>".$ls_cuentaanticipo."</td>";
				print "		<td align=center width='100'>".$li_monto."</td>";
				print "		<td align=right width='100'>0,00</td>";
				print "</tr>";			
				print "<tr>";			
				print "		<tr class=celdas-blancas>";
				print "		<td align=center width='250'>".$ls_cuentaproveedor."</td>";
				print "		<td align=center width='100'>0,00</td>";
				print "		<td align=right width='100'>".$li_monto."</td>";
				print "</tr>";			
				print "</table>";
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
	$ls_codcon=$in_class_mis->uf_obtenervalor_get("codcon","");
	$ls_codant=$in_class_mis->uf_obtenervalor_get("codant","");
	uf_imprimirresultados($ls_codcon,$ls_codant);
?>
</div>
</form>
</body>
</html>