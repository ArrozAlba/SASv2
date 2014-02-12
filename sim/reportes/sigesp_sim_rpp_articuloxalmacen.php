<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Niveles de Existencia de Art&iacute;culos </title>
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
<link href="report.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css">
</head>

<body>
<?
 	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones_db.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	include     ("sigesp_sim_class_report.php");
	$ds=new class_datastore();
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	$io_fun= new class_funciones_db($con);
	$io_report= new sigesp_sim_class_report();
	$io_msg= new class_mensajes();

	if(array_key_exists("codart",$_GET))
	{
		$ls_codart=$_GET["codart"];
	}
	else
	{
		$ls_codart="";
	}
	
	if(array_key_exists("codalm",$_GET))
	{
		$ls_codalm=$_GET["codalm"];
	}
	else
	{
		$ls_codalm="";
	}
	
	if(array_key_exists("orden",$_GET))
	{
		$li_orden=$_GET["orden"];
	}
	else
	{
		$li_orden="";
	}
	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$data="";

	$li_cont=0;
	$li_page=0;
	$li_total=10;
	$li_aux=$li_total/52;

	$lb_valido=$io_report->uf_select_articuloxalmacen($ls_codemp,$ls_codalm,$ls_codart,$li_orden,&$data);
	if ($lb_valido)
	{
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		?>
		<table width="633" border="0" cellpadding="0" cellspacing="0" class="report">
			  <tr>
				<td width="136" rowspan="2"><img src="../../shared/imagebank/logo.jpg" width="132" height="50"></td>
				<td width="359" height="26" valign="baseline" class="titulo_report"><div align="center"><span class="titulo_report"></span></div></td>
				<td width="132" rowspan="2" align="right" valign="top" class="fecha_report"><? print date("d/m/Y")?></td>
			  </tr>
			  <tr>
			    <td valign="baseline" class="titulo_report"><div align="center"><strong>Niveles de Existencia de Art&iacute;culos </strong></div></td>
	      </tr>	
		</table>
		<table width="633" border="0" cellpadding="0" cellspacing="0" class="report">
		   <tr class="report">
		     <td><strong>Almac&eacute;n:</strong></td>
		     <td>&nbsp;</td>
		     <td>&nbsp;</td>
		     <td></td>
	      </tr>
		   <tr class="report">
			 <td width="159"><div align="center" style="width:300px; font-weight: bold;"></div></td>
			 <td width="176"><div align="center" style="width:240px; font-weight: bold;"><strong>Art&iacute;culo</strong></div></td>
			 <td><div align="center"><strong>Existencia</strong></div></td>
			 <td width="10"> </td>
		   </tr>
		<?
		$totrow=$ds->getRowCount("codalm");
		for($z=1;$z<=$totrow;$z++)
		{
			$ls_nomfisalm=  $data["nomfisalm"][$z];
			$ls_denart=     $data["denart"][$z];
			$ls_existencia= $data["existencia"][$z];
			?>
			   <tr>
				 <td><? print $ls_nomfisalm ?></td>
				 <td><? print $ls_denart ?></td>
				 <td align="right"><? print $ls_existencia ?></td>
			   </tr>
	<?php
			
		}  //fin  for($z=1;$z<=$totrow;$z++)
	?>
</table>

	<?
	}

	$li_total_page=ceil($li_aux);
	for($i=1;$i<=$li_total;$i++)
	{
		$li_cont=$li_cont+1;
		$ls_prueba="Numero de registro =".$i." , numero linea en pagina actual=".$li_cont ;
		
		if(($li_cont==1))
		{
			$li_page=$li_page+1;
		
		?>
	
	 <table  width="633">
	 <tr>
	 <? print "<td style=text-align:right>".$li_page." de ".$li_total_page."</td>";?>	 
	 </tr>
	 </table>
	 <br>
	  
	<?
	}
	elseif(($i==$li_total)&&($li_cont<52))
	{
		for($x=$li_cont+1;$x<=52;$x++)
		{
		}
		 ?>
		 </table>
		 <table width="633">
			 <tr>
			 <? print "<td  style=text-align:right>".$li_page." de ".$li_total_page."</td>";?>	 
			 </tr>
		 </table>
		
		 
	<?
	}
}

?> 
 

</body>
<script language="javascript">
//window.print();
</script>
</html>
