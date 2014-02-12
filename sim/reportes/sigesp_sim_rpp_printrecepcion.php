<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezadopagina($as_titulo,$as_numconrec,$as_numordcom,$as_codpro,$as_denpro,$as_codalm,$as_nomfisalm,$as_fecrec,$as_obsrec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_encabezadopagina
		//		    Acess : private 
		//	    Arguments : as_titulo    // Título de la Página
		//  			    as_numconrec // numero consecituvo de recepcion
		//  			    as_numordcom // numero de orden de compra/ factura
		//  			    as_codpro    // codigo de proveedor
		//  			    as_denpro    // denominacion de proveedor
		//  			    as_codalm    // codigo de almacen
		//  			    as_nomfisalm // nombre fiscal de almacen
		//  			    as_fecrec    // fecha de recepcion
		//  			    as_obsrec    // observaciones de recepcion
		//    Description : función que imprime los encabezados por página
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 03/02/2006 							Fecha de Modificacion:  01/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<table width='633' border='0' align='center' cellpadding='0' cellspacing='0' class='report'>";
		print "  <tr>";
		print "	<td width='136'><img src='../../shared/imagebank/logo.jpg' width='132' height='50'></td>";
		print "	<td colspan=4 valign='baseline' class='titulo_report'><div align='center'><strong>".$as_titulo."</strong></div></td>";
		print "	<td colspan=4 align='right' valign='top' class='fecha_report'>".date("d/m/Y")."</td>";
		/*print "  </tr>	";
		print "  <tr>";
		print "	<td width='136' height='25'>&nbsp; </td>";
		print "	<td width='359' align='center'valign='baseline'></td>";
		print "	<td width='132' align='right' valign='top'>&nbsp;</td>";*/
		print "  </tr>	";						
		//print " <table width=930 border=0 align=center cellspacing=0 class=report>";
		print "  <tr>	";						
		print "	<td height=22 colspan=4 align=left><span  class='titulos'>ORDEN DE RECEPCI&Oacute;N:</span> <span class=data> ".$as_numconrec."</span></td>";
		print " <td align=left><span  class='titulos'>FECHA:</span><span class=data>".$as_fecrec."</span></td> ";
		print " <td width=89 align=left class=titulos></td>";
		print "  </tr>	";						
		print "  <tr>	";						
		print "  <td height=22 colspan=4 align=left><span  class='titulos'>ORDEN DE COMPRA:</span> <span  class='data'>".$as_numordcom."</span></td>";
		print "  </tr>	";						
		print "  <tr>	";						
		print "  <td height=22 colspan=4 align=left><span  class='titulos'>PROVEEDOR:</span> <span  class=data>".$as_denpro."</span></td>";
		print "  </tr>	";						
		print "  <tr>	";						
		print "	 <td height=22 colspan=6 align=left><span  class='titulos'>ALMAC&Eacute;N:</span> <span  class=data>".$as_nomfisalm."</span></td>";
		print "  </tr>	";						
		print "  <tr>	";						
		print " <td width=186 height=22 align=center bgcolor=#AC5365 class=titulos Estilo1>ART&Iacute;CULO</td>";
		print " <td width=100 align=center bgcolor=#AC5365 class=titulos Estilo1>UNIDAD</td> ";
		print "	<td width=75 align=center bgcolor=#AC5365 class=titulos Estilo1>CANTIDAD</td>";
  		print " <td width=75 align=center bgcolor=#AC5365 class=titulos Estilo1>PENDIENTES</td>";
		print "	<td width=87 align=center bgcolor=#AC5365 class=titulos Estilo1>PRECIO</td>";
		print "	<td align=center bgcolor=#AC5365 class=titulos Estilo1>TOTAL</td>";
//		print "	<td align=center bgcolor=#AC5365 class=titulos Estilo1>TOTAL</td>";
		print "  </tr>	";						

	}
	//-----------------------------------------------------------------------------------------------------------------------------------
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Entrada de Suministros a Almac&eacute;n</title>
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
	$ls_codemp=$arre["codemp"];
	$data="";

	
	if(array_key_exists("numconrec",$_GET))
	{
		$ls_numconrec=  $_GET["numconrec"];
		$ls_numordcom=  $_GET["numordcom"];
		$ls_codpro=     $_GET["codpro"];
		$ls_denpro=     $_GET["denpro"];
		$ls_codalm=     $_GET["codalm"];
		$ls_nomfisalm=  $_GET["denalm"];
		$ls_fecrec=     $_GET["fecrec"];
		$ls_obsrec=     $_GET["obsrec"];
		$ld_desde="";
		$ld_hasta="";
		$li_ordenfec="";
	}
	else
	{
		$ls_numconrec=  "";
		$ls_numordcom=  "";
		$ls_codpro=     "";
		$ls_denpro=     "";
		$ls_codalm=     "";
		$ls_denalm=     "";
		$ls_fecrec=     "";
		$ls_obsrec=     "";
		$ld_desde=      "";
		$ld_hasta=      "";
		$li_ordenfec=   "";
	}

	$lb_valido=true;
	$li_total=0;
	$la_totart=0;
	$ls_titulo="Entrada de Suministros a Almacén";
	if ($lb_valido)
	{
		$li_cont=0;//Contador de numero de lineas
		$li_page=0;//Contador de numero de paginas
		$li_maxlines=31;//Numero maximo de lineas

		$li_total_page=ceil($li_total/$li_maxlines);//Numero exacto de paginas, el metodo ceil() redondea el numero enviado a la escala siguiente
		uf_print_encabezadopagina($ls_titulo,$ls_numconrec,$ls_numordcom,$ls_codpro,$ls_denpro,$ls_codalm,$ls_nomfisalm,$ls_fecrec,$ls_obsrec);
		$lb_valido=$class_report->uf_select_dt_recepcion($ls_codemp,$ls_numordcom);
		if($lb_valido)
		{
			$li_cont=5;
			$li_totrow_res=$class_report->ds_existencia->getRowCount("codart");
			for($li_s=1;$li_s<=$li_totrow_res;$li_s++)
			{
				$li_aux=$li_cont;
				//$li_cont=$li_cont+4+$class_report->ds->data["total"][$li_i];
				if($li_cont>$li_maxlines)
				{
					$li_aux=$li_aux + 1;
					$li_page = $li_page +1;
					//$li_cont=4+$class_report->ds->data["total"][$li_i];
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
				$ls_codart=     $class_report->ds_existencia->data["codart"][$li_s];
				$ls_denart=     $class_report->ds_existencia->data["denart"][$li_s];
				$li_canart=     $class_report->ds_existencia->data["canart"][$li_s];
				$li_penart=     $class_report->ds_existencia->data["penart"][$li_s];
				$li_preuniart=  $class_report->ds_existencia->data["preuniart"][$li_s];
				$li_montotart=  $class_report->ds_existencia->data["montotart"][$li_s];						
				$ls_unidad=     $class_report->ds_existencia->data["unidad"][$li_s];
				$li_unidad=     $class_report->ds_existencia->data["unidades"][$li_s];
				if($ls_unidad=="D")
				{
					$ls_unidad="Detal";
				}
				else
				{
					$ls_unidad="Mayor";
					$li_canart=($li_canart / $li_unidad);
					$li_preuniart=($li_preuniart * $li_unidad);
				}
	?>
						  <tr>
							<td height="22" class="data"><? print substr($ls_denart,0,25); ?></td>
							<td class="data" align="center"><? print substr($ls_unidad,0,15); ?></td>
							<td class="data" align="right"><? print number_format($li_canart,2,",","."); ?></td>
							<td class="data" align="right"><? print number_format($li_penart,2,",","."); ?></td>
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
?> 
    <div align="center"></div>
</body>
<script language="javascript">
//window.print();
</script>
</html>
