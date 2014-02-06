<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezadopagina($as_titulo,$ld_desde,$ld_hasta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_encabezadopagina
		//		    Acess : private 
		//	    Arguments : $as_titulo // Título de la Página
		//					$ld_desde  //  Fecha de inicio de un intervalo dado
		//					$ld_hasta  //  Fecha de cierre de un intervalo dado
		//    Description : función que imprime los encabezados por página
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 03/02/2006 							Fecha de Modificacion:  24/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<table width='633' border='0' align='center' cellpadding='0' cellspacing='0' class='report'>";
		print "  <tr>";
		print "	<td width='136'><img src='../../shared/imagebank/logo.jpg' width='132' height='50'></td>";
		print "	<td width='359' valign='baseline' class='titulo_report'><div align='center'>".$as_titulo."</div></td>";
		print "	<td width='132' align='right' valign='top' class='fecha_report'>".date("d/m/Y")."</td>";
		print "  </tr>	";
		print "  <tr>";
		print "	<td width='136' height='25'>&nbsp; </td>";
		print "	<td width='359' align='center'valign='baseline'></td>";
		print "	<td width='132' align='right' valign='top'>&nbsp;</td>";
		print "  </tr>	";						
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte de &Oacute;rdenes de Despacho</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 15px;
	margin-top: 15px;
	margin-right: 15px;
	margin-bottom: 15px;
}
-->
</style>
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
</head>

<body>
<?
	require_once("sigesp_sim_class_report.php");
	$class_report = new sigesp_sim_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funcion=new class_funciones();				
	require_once("../../shared/class_folder/class_mensajes.php");
	$io_msg = new class_mensajes();

	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["CodEmp"];
	$data="";

	
	if(array_key_exists("numorddes",$_GET))
	{
		$ls_numorddes=$_GET["numorddes"];
		$ls_numsol=$_GET["numsol"];
		$ls_coduniadm=$_GET["coduniadm"];
		$ls_denunidam=$_GET["denunidam"];
		$ld_fecdes=$_GET["fecdes"];
		$ls_obsdes=$_GET["obsdes"];
		print $ls_numorddes.$ls_numsol.$ls_coduniadm.$ls_denunidam.$ld_fecdes.$ls_obsdes;
	}
	else
	{
		$ls_numorddes="";
	}

	$li_total=0;
	$la_totart=0;
	$ls_titulo="Órden de Despacho";
	?>
						<table width="930" border="0" align="center" cellspacing="0" class="report">
					  <tr>
						<td height="22" colspan="4" align="left" class="titulos">ORDEN DE DESPACHO: <span  class="data"><? print $ls_numorddes; ?></span></td>
					    <td colspan="2" align="left" class="titulos">FECHA:<span class="data"><? print $ls_fecdes; ?></span></td>
					    <td width="89" align="left" class="titulos">&nbsp;</td>
					  </tr>
					  <tr>
						<td height="22" colspan="4" align="left" class="titulos">UNIDAD ADMINISTRATIVA:</td>
					    <td align="left" class="titulos">&nbsp;</td>
					    <td width="97" align="left" class="titulos">&nbsp;</td>
					    <td align="left" class="titulos">&nbsp;</td>
					  </tr>
					  <tr>
						<td height="22" colspan="7" align="left" class="titulos">OBSERVACI&Oacute;N:</td>
				      </tr>
					  <tr>
						<td width="186" height="22" align="center" bgcolor="#AC5365" class="titulos Estilo1">ART&Iacute;CULO</td>
						<td width="252" align="center" bgcolor="#AC5365" class="titulos Estilo1">ALMAC&Eacute;N</td>
						<td width="110" align="center" bgcolor="#AC5365" class="titulos Estilo1">UNIDAD</td>
						<td width="75" align="center" bgcolor="#AC5365" class="titulos Estilo1">SOLICITADA</td>
						<td width="87" align="center" bgcolor="#AC5365" class="titulos Estilo1">DESPACHADA</td>
						<td align="center" bgcolor="#AC5365" class="titulos Estilo1">PRECIO</td>
						<td align="center" bgcolor="#AC5365" class="titulos Estilo1">TOTAL</td>
					  </tr>

	<?
	if ($lb_valido)
	{
		$li_cont=0;//Contador de numero de lineas
		$li_page=0;//Contador de numero de paginas
		$li_maxlines=48;//Numero maximo de lineas
		$li_total_page=ceil($li_total/$li_maxlines);//Numero exacto de paginas, el metodo ceil() redondea el numero enviado a la escala siguiente
		//$lb_valido=$class_report->uf_select_despachos($ls_codemp,$ld_desde,$ld_hasta,$li_ordenfec);
		if($lb_valido)
		{
			//$class_report->ds->data=$la_data;
			$li_totrow=$class_report->ds->getRowCount("numorddes");
			uf_print_encabezadopagina($ls_titulo,$ld_desde,$ld_hasta);
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				if($ls_numorddes!="")
				{
					$li_aux=$li_cont;
					$li_cont=$li_cont+3+$class_report->ds->data["total"][$li_i];
					if($li_cont>$li_maxlines)
					{
						$li_aux=$li_aux + 1;
						$li_page = $li_page +1;
						$li_cont=3+$class_report->ds->data["total"][$li_i];
						print "<table  width='633' class='report' align='center'>";
						for($li_j=$li_aux;$li_j<=$li_maxlines;$li_j++)
						{
							print "<tr>";
							print "	<td colspan='6'>&nbsp;</td>";
							print "</tr>";
						}
						print "<td style=text-align:right>".$li_page." de ".$li_total_page."</td>"; 
						print "</table>";
						print "</table>";
						print "<br>";			
						uf_print_encabezadopagina($ls_titulo,$ld_desde,$ld_hasta);
					}
				
	?>
					 
	<?	
				} // fin  if($ls_articulo!="")
				$la_dataexistencia=0;
				$lb_valido=$class_report->uf_select_dt_despacho($ls_codemp,$ls_numorddes,$ld_desde,$ld_hasta,$li_ordenfec);
				if($lb_valido)
				{
					//$class_report->ds_existencia->data=$la_dataexistencia;
					$li_totent=0;
					$li_totsal=0;
					$li_totrow_res=$class_report->ds_existencia->getRowCount("codart");
					for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
					{
						$ls_codart=     $class_report->ds_existencia->data["codart"][$li_s];
						$ls_denart=     $class_report->ds_existencia->data["denart"][$li_s];
						$ls_nomfisalm=  $class_report->ds_existencia->data["nomfisalm"][$li_s];
						$li_canart=     $class_report->ds_existencia->data["canart"][$li_s];
						$li_cansol=     $class_report->ds_existencia->data["canorisolsep"][$li_s];
						$li_preuniart=  $class_report->ds_existencia->data["preuniart"][$li_s];
						$li_montotart=  $class_report->ds_existencia->data["montotart"][$li_s];
						
	?>
						  <tr>
							<td height="22" class="data"><? print substr($ls_denart,0,25); ?></td>
							<td class="data"><? print substr($ls_nomfisalm,0,30);; ?></td>
							<td class="data">&nbsp;</td>
							<td class="data">&nbsp;</td>
							<td class="data" align="right"><? print number_format($li_canart,2,",","."); ?></td>
							<td class="data" align="right"><? print number_format($li_preuniart,2,",","."); ?></td>
							<td class="data" align="right"><? print number_format($li_montotart,2,",","."); ?></td>
						  </tr>
	<?
					}// fin for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
					$class_report->ds_existencia->resetds("codart");
	?>
					  <tr>
						<td colspan="8"></td>
					  </tr>
	<?				
				}// fin if($lb_valido)
	?>		  		  
</table>
	<?		
			//print $li_totent;
			}// fin for($li_i=1;$li_i<=$li_totrow;$li_i++) 
			$class_report->ds->resetds("nummov");
			if($li_cont<=$li_maxlines)
			{
				$li_cont=$li_cont + 1;
				$li_page= $li_page +1;
				print "<table  width='633' class='report' align='center'>";
				for($li_j=$li_cont;$li_j<=$li_maxlines;$li_j++)
				{
					print "<tr>";
					print "	<td colspan='6'>&nbsp;</td>";
					print "</tr>";
				}
				print "<td style=text-align:right>".$li_page." de ".$li_total_page."</td>"; 
				print "</table>";
				print "</table>";
				print "<br>";			
			}			
		}// fin  if($lb_valido)
	}// fin  if($lb_valido)
?> 
    <div align="center"></div>
</body>
<script language="javascript">
//window.print();
</script>
</html>
