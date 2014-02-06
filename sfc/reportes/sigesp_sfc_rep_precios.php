<?Php
/******************************************/
/* FECHA: 12/12/2007                      */
/* AUTOR: ING. Rosmary Linarez            */
/******************************************/
session_start();
require_once("sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(10,10,10,10);
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_sql2=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$reporte->add_titulo("center",22,11,"LISTADO DE PRECIOS");
$ls_fecha=date('d/m/Y');
$reporte->add_titulo("left",34,7,"Fecha de emision: ".$ls_fecha);
$io_pdf->numerar_paginas(6);
$ls_car=$_GET["sql"];
$ls_codtie=$_GET["codtie"];
$ls_codemp=$_GET["codemp"];
$ls_ordenarpor=$_GET["ordenpor"];
$ls_orden=$_GET["orden"];
$ls_dencla=$_GET["dencla"];
$ls_denpro=$_GET["denpro"];

$ls_tienda_desde=$_GET["codtienddesde"];
$ls_tienda_hasta=$_GET["codtiendhasta"];

if($ls_tienda_desde == $ls_tienda_hasta){
	$filtalm = " AND aa.codalm ilike '%".$ls_tienda_desde."' ";
}else{
	$filtalm = " AND aa.codalm BETWEEN '000000".$ls_tienda_desde."' AND '000000".$ls_tienda_hasta."' ";
}

$ls_denpro=str_replace("/","",$ls_denpro);
$ls_dencla=str_replace("/","",$ls_dencla);



//if ($ls_ordenarpor=="cl.dencla")
if ($ls_ordenarpor=="a.denart")
{
	$ls_ordenarpor=substr($ls_ordenarpor,2,strlen($ls_ordenarpor));
//print $ls_ordenarpor;
}
elseif ($ls_ordenarpor=="cl.dencla")
{
$ls_ordenarpor="codcla";
//print $ls_ordenarpor;
}

function sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,$alias_tabla,$ls_codtie) {

$add_sql = '';
if ($ls_tienda_desde=='') {

$add_sql = "$alias_tabla.codtiend='$ls_codtie'";

}else {

$add_sql = "$alias_tabla.codtiend  BETWEEN '$ls_tienda_desde' AND '$ls_tienda_hasta'";

}

return $add_sql;
}


switch($ls_car){
	/********************* KEDE ACAAAAAAAAAAAAAAAA **************************/
case 1:

$ls_sql="SELECT a.codcla,a.denart, p.codart, round(cast((p.preven+(p.preven*c.porcar/100)) as numeric),3) as precio, p.codcar," .
		"aa.existencia FROM sfc_producto p,sfc_clasificacion cl,sim_articuloalmacen aa,sigesp_cargos c,sim_articulo a " .
		"WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."' AND " .
		"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp AND p.codemp=c.codemp AND p.codart=aa.codart AND p.codart=a.codart" .
		" AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=c.codemp AND aa.codemp=a.codemp AND aa.codart=a.codart" .
		" AND c.codemp=a.codemp AND p.codcar=c.codcar" .
		" UNION  SELECT a.codcla, a.denart, p.codart, (p.preven) as precio, p.codcar,aa.existencia FROM sfc_producto p,sfc_clasificacion cl," .
		"sim_articuloalmacen aa,sim_articulo a  WHERE cl.dencla ilike '%%' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp " .
		" AND p.codart=aa.codart AND p.codart=a.codart AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=a.codemp" .
		" AND aa.codart=a.codart AND  p.codcar='' ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

		//print $ls_sql."1<br>";

break;

case 2 :

$ls_sql="SELECT a.codcla,a.denart, p.codart, round(cast((p.preven+(p.preven*c.porcar/100)) as numeric),3) as precio, p.codcar," .
		"aa.existencia FROM sfc_producto p,sfc_clasificacion cl,sim_articuloalmacen aa,sigesp_cargos c,sim_articulo a " .
		"WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp AND p.codemp=c.codemp AND p.codart=aa.codart AND p.codart=a.codart" .
		" AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=c.codemp AND aa.codemp=a.codemp AND aa.codart=a.codart" .
		" AND c.codemp=a.codemp AND p.codcar=c.codcar AND aa.existencia=0" .
		" UNION  SELECT a.codcla, a.denart, p.codart, (p.preven) as precio, p.codcar,aa.existencia FROM sfc_producto p,sfc_clasificacion cl," .
		"sim_articuloalmacen aa,sim_articulo a  WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp " .
		" AND p.codart=aa.codart AND p.codart=a.codart AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=a.codemp" .
		" AND aa.codart=a.codart AND  p.codcar='' AND aa.existencia=0 ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

		//print $ls_sql."2<br>";
break;
case 3 :

$ls_sql="SELECT a.codcla,a.denart, p.codart, round(cast((p.preven+(p.preven*c.porcar/100)) as numeric),3) as precio, p.codcar," .
		"aa.existencia FROM sfc_producto p,sfc_clasificacion cl,sim_articuloalmacen aa,sigesp_cargos c,sim_articulo a " .
		"WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp AND p.codemp=c.codemp AND p.codart=aa.codart AND p.codart=a.codart" .
		" AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=c.codemp AND aa.codemp=a.codemp AND aa.codart=a.codart" .
		" AND c.codemp=a.codemp AND p.codcar=c.codcar AND aa.existencia!=0" .
		" UNION  SELECT a.codcla, a.denart, p.codart, (p.preven) as precio, p.codcar,aa.existencia FROM sfc_producto p,sfc_clasificacion cl," .
		"sim_articuloalmacen aa,sim_articulo a  WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp " .
		" AND p.codart=aa.codart AND p.codart=a.codart AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=a.codemp" .
		" AND aa.codart=a.codart AND  p.codcar='' AND aa.existencia!=0 ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

		//print $ls_sql."3<br>";
break;
case 4:
$ls_sql="SELECT a.codcla,a.denart, p.codart, round(cast((p.preven+(p.preven*c.porcar/100)) as numeric),3) as precio, p.codcar," .
		"aa.existencia FROM sfc_producto p,sfc_clasificacion cl,sim_articuloalmacen aa,sigesp_cargos c,sim_articulo a " .
		"WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp AND p.codemp=c.codemp AND p.codart=aa.codart AND p.codart=a.codart" .
		" AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=c.codemp AND aa.codemp=a.codemp AND aa.codart=a.codart" .
		" AND c.codemp=a.codemp AND p.codcar=c.codcar " .
		" UNION  SELECT a.codcla, a.denart, p.codart, (p.preven) as precio, p.codcar,aa.existencia FROM sfc_producto p,sfc_clasificacion cl," .
		"sim_articuloalmacen aa,sim_articulo a  WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp " .
		" AND p.codart=aa.codart AND p.codart=a.codart AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=a.codemp" .
		" AND aa.codart=a.codart AND  p.codcar='' ORDER BY denart ASC";

		///print $ls_sql."4<br>";
break;
case 5:

$ls_sql="SELECT a.codcla,a.denart, p.codart, round(cast((p.preven+(p.preven*c.porcar/100)) as numeric),3) as precio, p.codcar," .
		"aa.existencia FROM sfc_producto p,sfc_clasificacion cl,sim_articuloalmacen aa,sigesp_cargos c,sim_articulo a " .
		"WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp AND p.codemp=c.codemp AND p.codart=aa.codart AND p.codart=a.codart" .
		" AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=c.codemp AND aa.codemp=a.codemp AND aa.codart=a.codart" .
		" AND c.codemp=a.codemp AND p.codcar=c.codcar AND aa.existencia=0 " .
		" UNION  SELECT a.codcla, a.denart, p.codart, (p.preven) as precio, p.codcar,aa.existencia FROM sfc_producto p,sfc_clasificacion cl," .
		"sim_articuloalmacen aa,sim_articulo a  WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp " .
		" AND p.codart=aa.codart AND p.codart=a.codart AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=a.codemp" .
		" AND aa.codart=a.codart AND  p.codcar='' AND aa.existencia=0 ORDER BY denart ASC";



		///print $ls_sql."5<br>";
break;
case 6:

$ls_sql="SELECT a.codcla,a.denart, p.codart, round(cast((p.preven+(p.preven*c.porcar/100)) as numeric),3) as precio, p.codcar," .
		"aa.existencia FROM sfc_producto p,sfc_clasificacion cl,sim_articuloalmacen aa,sigesp_cargos c,sim_articulo a " .
		"WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp AND p.codemp=c.codemp AND p.codart=aa.codart AND p.codart=a.codart" .
		" AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=c.codemp AND aa.codemp=a.codemp AND aa.codart=a.codart" .
		" AND c.codemp=a.codemp AND p.codcar=c.codcar AND aa.existencia!=0 " .
		" UNION  SELECT a.codcla, a.denart, p.codart, (p.preven) as precio, p.codcar,aa.existencia FROM sfc_producto p,sfc_clasificacion cl," .
		"sim_articuloalmacen aa,sim_articulo a  WHERE cl.dencla ilike '".$ls_dencla."' AND a.codcla=cl.codcla AND p.codemp='".$ls_codemp."'" .
		" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie). $filtalm .
		" AND a.denart ilike '".$ls_denpro."' AND p.codemp=a.codemp AND p.codemp=aa.codemp " .
		" AND p.codart=aa.codart AND p.codart=a.codart AND p.codtiend=aa.codtiend AND cl.codcla=a.codcla AND aa.codemp=a.codemp" .
		" AND aa.codart=a.codart AND  p.codcar='' AND aa.existencia!=0 ORDER BY denart ASC ";




		//print $ls_sql."6<br>";
break;

}//fin del case


/*$ls_sql=str_replace("\\","",$ls_sql);
$ls_sql=str_replace("/","",$ls_sql);*/
//print $ls_sql;
$rs_datauni=$io_sql->select($ls_sql);

if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay Nada que Reportar");
}
else
 {


    $la_producto=$io_sql->obtener_datos($rs_datauni);

	//print_r($la_producto);
	if ($la_producto)
	{
	$li_cuotas=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;

	for($i=0;$i<$li_cuotas;$i++)
	{


	 $ls_existencia=strtoupper($la_producto["existencia"][$i+1]);


	 if ($ls_existencia==0)
	 {

	$ls_codpro=strtoupper($la_producto["codpro"][$i+1]);
	$ls_sql1=" SELECT denunimed FROM  sim_articulo a,sim_unidadmedida u WHERE a.codart='".$ls_codpro."' AND a.codunimed=u.codunimed";
//print $ls_sql1;
	$rs_dataunimed=$io_sql->select($ls_sql1);
 		$la_unimedida=$io_sql->obtener_datos($rs_dataunimed);

		$ls_unimed=$la_unimedida["denunimed"][1];

		$la_datos[$i]["<b>PRODUCTO</b>"]= strtoupper($la_producto["denart"][$i+1])."   ".$ls_unimed;
		$la_datos[$i]["<b>CODIGO</b>"]= strtoupper($la_producto["codart"][$i+1]);
		 $la_datos[$i]["<b>PRECIO DE VENTA</b>"]=$la_producto["precio"][$i+1];
		 $ls_imp=trim($la_producto["codcar"][$i+1]);


		 if ($ls_imp=="00000")
		 {

			$la_datos[$i]["<b>IMPUESTO</b>"]="EXE";

		 }

		else
		 {

			$la_datos[$i]["<b>IMPUESTO</b>"]="IVA";
		 }

	 }

	 else
	 {
		$ls_codpro=strtoupper($la_producto["codart"][$i+1]);
	$ls_sql1=" SELECT denunimed FROM  sim_articulo a,sim_unidadmedida u WHERE a.codart='".$ls_codpro."' AND a.codunimed=u.codunimed";
//print $ls_sql1;
	$rs_dataunimed=$io_sql->select($ls_sql1);
 	$la_unimedida=$io_sql->obtener_datos($rs_dataunimed);

	$ls_unimed=$la_unimedida["denunimed"][1];

		 $la_datos[$i]["<b>PRODUCTO</b>"]= strtoupper($la_producto["denart"][$i+1])."   ".$ls_unimed;
		 $la_datos[$i]["<b>CODIGO</b>"]= strtoupper($la_producto["codart"][$i+1]);

		 $li_exi= strtoupper($la_producto["existencia"][$i+1]);
		// print $li_exi."<br>";
		 if (($li_exi==" ") or ($li_exi==0))
		 {
		  	$la_datos[$i]["<b>EXISTENCIA</b>"]="0.00";

		 }
		 else
		 {

			$la_datos[$i]["<b>EXISTENCIA</b>"]= strtoupper($la_producto["existencia"][$i+1]);
			//print "pase";

		 }

		 $la_datos[$i]["<b>PRECIO DE VENTA</b>"]=$la_producto["precio"][$i+1];
		 $ls_imp=trim($la_producto["codcar"][$i+1]);

		 if ($ls_imp=="00000")
		 {

			$la_datos[$i]["<b>IMPUESTO</b>"]="EXE";

		 }

		else
		 {

			$la_datos[$i]["<b>IMPUESTO</b>"]="IVA";
		 }

	}

	}






	$io_pdf->ezSetY(600);
	$la_anchos_col = array(80,40,22,30,20);
	$la_justificaciones = array('left','left','right','right','left');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 8,
						   "lineas"=>1,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>1);
	$io_pdf->add_tabla(-15,$la_datos,$la_opciones);


$io_pdf->ezStream();

}else{
?>
<script>
alert ("No hay Nada que Reportar");

</script>
<?php
}
}
?>
