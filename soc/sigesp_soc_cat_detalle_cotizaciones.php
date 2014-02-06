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
	$io_detcot=new sigesp_soc_c_analisis_cotizacion;
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();	
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();	
	$ls_numcot=$_GET["numcot"];
	$ls_codpro=$_GET["codpro"];
	$ls_numsolcot=$_GET["numsolcot"];
	$ls_nompro=$_GET["nompro"];
	$io_detcot->uf_select_cotizacion($ls_numcot,$ls_codpro,$ls_numsolcot,$la_cotizacion,$la_dt_cotizacion);
	$la_titulos=array(1=>"Código",2=>"Denominación",3=>"Cantidad",4=>"Precio/Unid.", 5=>"Subtotal",6=>"Cargos",7=>"Total");
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
</head>
<script type="text/javascript"  src="js/funcion_soc.js" language="javascript"></script>
<body>
<form name="formulario" method="post" action="">
  <br> <br> <br>
  <table width="812" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <th colspan="8" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th width="3" scope="col">&nbsp;</th>
      <th width="237" scope="col"><div align="right">No. Cotizaci&oacute;n: </div></th>
      <th width="128" scope="col"><div align="left" class="celdas-blancas"><?php print $ls_numcot?></div></th>
      <th scope="col"><div align="right">Fecha:</div></th>
      <th scope="col"><div align="left" class="celdas-blancas"><?php print $io_funciones->uf_convertirfecmostrar($la_cotizacion["feccot"])?></div></th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col"><div align="right">Proveedor:</div></th>
      <th colspan="6" scope="col"><div align="left" class="celdas-blancas"><?php print $ls_nompro?></div></th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col"><div align="right">Observaci&oacute;n:</div></th>
      <th colspan="6" scope="col"><div align="left" class="celdas-blancas" ><?php print $la_cotizacion["obscot"]?></div></th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col"><div align="right">Forma de Pago: </div></th>
      <th scope="col"><div align="left" class="celdas-blancas"><?php print $la_cotizacion["forpagcom"]?></div></th>
      <th width="174" scope="col"><div align="right">Plazo:</div></th>
      <th scope="col"><div align="left" class="celdas-blancas"><?php print $la_cotizacion["diaentcom"]?> d&iacute;as </div></th>
      <th scope="col">&nbsp;</th>
      <th scope="col"><div align="left"></div></th>
      <th scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col"><div align="right">Iva:</div></th>
      <th scope="col"><div align="left" class="celdas-blancas"><?php print number_format($la_cotizacion["poriva"],2,",",".")?></div></th>
      <th colspan="5" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th colspan="5" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th colspan="6" scope="col">
	  <?php 
	   		if($la_cotizacion["tipsolcot"]=="B")
				$ls_titulo="Bienes";
			else
				$ls_titulo="Servicios";
	  		$io_grid->makegrid(count($la_dt_cotizacion),$la_titulos,$la_dt_cotizacion,770,$ls_titulo,"grid");
	  ?></th>
      <th width="5" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th colspan="5" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th colspan="5" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col"><div align="right">Subtotal:</div></th>
      <th width="116" scope="col"><div  align="right" class="celdas-blancas"><?php print number_format($la_cotizacion["monsubtot"],2,",",".")?></div></th>
      <th width="104" scope="col">&nbsp;</th>
      <th width="43" scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col"><div align="right">Cargos:</div></th>
      <th scope="col"><div  align="right" class="celdas-blancas"><?php print number_format($la_cotizacion["monimpcot"],2,",",".")?></div></th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col"><div align="right">Total Cotizaci&oacute;n: </div></th>
      <th scope="col"><div  align="right" class="celdas-blancas"><?php print number_format($la_cotizacion["montotcot"],2,",",".")?></div></th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
      <th colspan="5" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th colspan="8" scope="col"><div align="right"></div></th>
    </tr>
  </table>

  <div align="center"></div>
</form>      
</body>
<script type="text/javascript"  src="js/funcion_soc.js" language="javascript"></script>
<script language="JavaScript">
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>