<?php
session_start();
include("../modelo/sigespwindow_blank_mod.php");
require_once ("../../shared/graficas/libchart/classes/libchart.php");

$fn = $_POST[fn];

if ($fn =='montoPorEstado') {
    
    $rs = cargarMontosPorEstado ();
    echo (generarDatosGrafica($rs));
}else if  ($fn =='ventasPorAno') {
    $rs = cargarVentasPorMes ();
    echo (generarDatosVentasMes($rs));


}

function generarDatosGrafica ($rs) {
         $chart = new PieChart(750, 500);
         $dataSet = new XYDataSet();
         
         while($row = pg_fetch_array($rs))    {

             $montoFormat = number_format($row[montobruto],2, ',', '.');
             $monto = $row[montobruto];
             $dataSet->addPoint(new Point(utf8_decode($row[desest])." (BsF. $montoFormat) ", $monto));
             
        }
        
         
	 $chart->setDataSet($dataSet);
        $ano = date("Y");
	$chart->setTitle("Ingresos Por Estado (Facturas Canceladas) año $ano");
	$chart->render("../../shared/graficas/generated/ventasporestado.png");
        $html = '<img alt="Ventas Por Estado" src="../shared/graficas/generated/ventasporestado.png"/>';
        
  return $html;
  //return $data;
}

function generarDatosVentasMes ($rs) {
         $chart = new VerticalBarChart(750, 500);
         $dataSet = new XYDataSet();

         while($row = pg_fetch_array($rs))    {

             //$montoFormat = number_format($row[montobruto],2, ',', '.');
             $monto = $row[montobruto]/1000000;
             $montoFormat = number_format($monto,3, '.', '');
             $dataSet->addPoint(new Point("$row[mes]", $montoFormat));

        }

	$chart->setDataSet($dataSet);
        $ano = date("Y");
	$chart->setTitle("Ventas Por Mes En el año  $ano (En Millones de BsF.)");
	$chart->render("../../shared/graficas/generated/ventaspormes.png");
        $html = '<img alt="Ventas Por Estado" src="../shared/graficas/generated/ventaspormes.png"/>';

  return $html;
  //return $data;
}

?>
