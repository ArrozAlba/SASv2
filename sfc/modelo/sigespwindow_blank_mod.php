<?php
session_start();
require_once ("../../shared/class_folder/sigesp_include.php");
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();

function cargarMontosPorEstado () {
   /* $orderby = ($orderby != '') ? "ORDER BY ".$orderby : '';
    $filtroFact = ($filtroFact != '') ? "AND nro_documento like '%$filtroFact%'" : '';*/
    $consulta ="
            select est.desest
                   ,sum(cast(monto as numeric(10,2))) as montobruto
            from
                    sfc_factura f
                    , sfc_cliente c
                    , sigesp_estados est
            where
                    estfaccon = 'C' 
                    AND c.codcli = f.codcli
                    AND est.codest = c.codest
            Group by est.desest ORDER BY montoBruto ASC
    ";
	
    return $rs = pg_query($consulta);
}

function cargarVentasPorMes () {
   /* $orderby = ($orderby != '') ? "ORDER BY ".$orderby : '';
    $filtroFact = ($filtroFact != '') ? "AND nro_documento like '%$filtroFact%'" : '';*/
    $fecha_inicio = date("Y").'-01'.'-01' ;
    $consulta ="
           select
                  CASE  extract(month from fecemi)
                          WHEN 1 THEN 'Ene.'
                          WHEN 2 THEN 'Feb.'
                          WHEN 3 THEN 'Mar.'
                          WHEN 4 THEN 'Abr.'
                          WHEN 5 THEN 'May.'
                          WHEN 6 THEN 'Jun.'
                          WHEN 7 THEN 'Jul.'
                          WHEN 8 THEN 'Ago.'
                          WHEN 9 THEN 'Sep.'
                          WHEN 10 THEN 'Oct.'
                          WHEN 11 THEN 'Nov'
                          WHEN 12 THEN 'Dic.'
                         END
                  as mes
                  ,sum(cast(monto as numeric(10,2))) as montobruto
            from
                    sfc_factura f


            where
                        estfaccon in ('C','N','P')
                    AND fecemi >= '$fecha_inicio'
            Group by mes,extract(month from fecemi) order by extract(month from fecemi)
    ";
    return $rs = pg_query($consulta);
}

?>
