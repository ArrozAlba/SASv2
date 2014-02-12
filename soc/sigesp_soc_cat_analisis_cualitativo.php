<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	require_once("class_folder/sigesp_soc_c_analisis_cotizacion.php");
	$io_analisis=new sigesp_soc_c_analisis_cotizacion();
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();	
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();	
	$li_totalcotizaciones=$_GET["totalcotizaciones"];
	$io_analisis->uf_analisis_cualitativo($la_valores);	
	if(count($la_valores)==0)
	{
		print "<script language=JavaScript>";
		print "alert('Los proveedores de las cotizaciones seleccionadas no tienen parámetros de calificación en común');";
		print "close();";
		print "</script>";	
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de Cotizaci&oacute;n</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript"  src="js/funcion_soc.js" language="javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
</head>

<body>
<form name="formulario" method="post" action="">
  <br> <br> <br>
  <table height="107" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" style=" overflow:scroll">
    <tr class="titulo-nuevo">
      <th width="512" height="13" colspan="8" scope="col">
      <div align="center">An&aacute;lisis Cualitativo </div></th>
    </tr>
    <tr>
      <th height="28" colspan="8" scope="col"><div align="center">
        <br><br><br>
		<table border="1">
          <tr class="titulo-celda">
            <td width="106">Calificador</td>
            <?php
				for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
				{
					$ls_nompro=$_GET["nompro".$li_i];
					print "<td width=100>".$ls_nompro."</td>";
				}
			?>           
          </tr>
          <?php 
		        if (!empty($la_valores))
				   {
					 $li_totalcalificadores=count($la_valores[$_GET["nompro1"]]);
					 for ($li_i=0;$li_i<$li_totalcalificadores;$li_i++)
					     {
						   print "<tr>";
						   $la_calificador=array_keys($la_valores[$_GET["nompro1"]]);//Como todos tienen los mismos calificadores, tomo como referencia el primer proveedor
						   $ls_calificador=$la_calificador[$li_i];
						   print "<td class=titulo-celda>".$ls_calificador."</td>";//Imprimo el nombre del calificador
						   for ($li_j=1;$li_j<=$li_totalcotizaciones;$li_j++)
						       {
							     $ls_codpro=$_GET["codpro".$li_j];
							     $ls_valor=$la_valores[$_GET["nompro".$li_j]][$ls_calificador];	
							     print "<td class=formato-blanco width=100><div align=center><b>".$ls_valor."</b></div></td>";						
						       }
						   print "</tr>";         
					     }  
				   } 
		  ?>		        
        </table>
      </div>
      <div align="center"></div></th>
    </tr>
    <tr>
      <th height="25" colspan="8" scope="col">&nbsp;</th>
    </tr>
  </table>

  <div align="center"></div>
</form>      
</body>
<script language="JavaScript">
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>