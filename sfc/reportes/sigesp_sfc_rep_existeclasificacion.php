<?Php
/******************************************/
/* FECHA: 12/12/2007                      */
/* AUTOR: ING. Rosmary Linarez            */
/******************************************/
session_start();
require_once("sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','landscape','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','landscape');
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
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$reporte->add_titulo("center",18,11,"LISTADO DE EXISTENCIAS DE ARTICULOS POR CLASIFICACION ");
$reporte->add_titulo("center",24,10,"CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A ");


$ls_codtie=$_SESSION["ls_codtienda"];
		 if ($ls_codtie='0001')
		{
			$ls_dentie="SEDE CENTRAL";

		}
		else
		{
			$ls_sql="Select dentie from sfc_tienda where codtie='".$ls_codtie."' ";
			$rs_data=$io_sql->select($ls_sql);
			if($rs_data==false&&($io_sql->message!=""))
			{
				//$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_data))
				{
					$la_agrotienda=$io_sql->obtener_datos($rs_data);
					$io_datastore4->data=$la_agrotienda;
					$totrowt=$io_datastore4->getRowCount("dentie");
					//print_r ($la_agrotienda);

						//print "Paso".$totrowt;
					for($t=0;$t<=$totrowt;$t++)
					{


						$ls_dentie=$io_datastore4->getValue("dentie",$t);
					}
				}
			}
		}
$reporte->add_titulo("center",30,10,$ls_dentie);
$ls_fecha=date('d/m/Y');
$reporte->add_titulo("left",35,7,"Fecha de emision: ".$ls_fecha);
$io_pdf->numerar_paginas(6);
$ls_sql=$_GET["sql"];
//print $ls_sql;
$ls_sql=str_replace("\\","",$ls_sql);
$ls_sql=str_replace("/","",$ls_sql);
$ls_longitud=strlen($ls_sql);
$ls_posicion=strpos($ls_sql,";");
$ls_sql=substr($ls_sql,0,$ls_posicion+1);
//print $ls_sql;

$rs_datauni=$io_sql->select($ls_sql);
if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay Registros");
}
else
 {
   $la_producto=$io_sql->obtener_datos($rs_datauni);
	if ($la_producto){
	$li_cuotas=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;

	for($i=0;$i<$li_cuotas;$i++)
	{


	 $ls_existencia=strtoupper($la_producto["existencia"][$i+1]);


	 if ($ls_existencia==0)
	 {

	$ls_codpro=strtoupper($la_producto["codart"][$i+1]);


		$la_datos[$i]["<b>PRODUCTO</b>"]= strtoupper($la_producto["denart"][$i+1])."   ".$la_producto["denunimed"][$i+1];
		$la_datos[$i]["<b>C�DIGO</b>"]= strtoupper($la_producto["codart"][$i+1]);
		$la_datos[$i]["<b>EXISTENCIA</b>"]=number_format(strtoupper($la_producto["existencia"][$i+1]),2,',','.');
		$la_datos[$i]["<b>COSTO Bs.</b>"]=number_format(strtoupper($la_producto["ultcosart"][$i+1]),2,',','.');
		$la_datos[$i]["<b>PRECIO VENTA Bs.</b>"]=number_format(strtoupper($la_producto["preven"][$i+1]),2,',','.');
		$la_datos[$i]["<b>CLASIFICACION</b>"]=$la_producto["dencla"][$i+1];
		$la_datos[$i]["<b>SUB-CLASIFICACION</b>"]=$la_producto["den_sub"][$i+1];
		 $ls_imp=trim($la_producto["codcar"][$i+1]);


		 if ($ls_imp=="")
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


		 $la_datos[$i]["<b>PRODUCTO</b>"]= strtoupper($la_producto["denart"][$i+1])."   ".$la_producto["denunimed"][$i+1];
		 $la_datos[$i]["<b>C�DIGO</b>"]= strtoupper($la_producto["codart"][$i+1]);
		 $la_datos[$i]["<b>EXISTENCIA</b>"]=number_format(strtoupper($la_producto["existencia"][$i+1]),2,',','.');
		$la_datos[$i]["<b>COSTO Bs.</b>"]=number_format(strtoupper($la_producto["ultcosart"][$i+1]),2,',','.');
		$la_datos[$i]["<b>PRECIO VENTA Bs.</b>"]=number_format(strtoupper($la_producto["preven"][$i+1]),2,',','.');
		$la_datos[$i]["<b>CLASIFICACION</b>"]=$la_producto["dencla"][$i+1];
		$la_datos[$i]["<b>SUB-CLASIFICACION</b>"]=$la_producto["den_sub"][$i+1];

		 $ls_imp=trim($la_producto["codcar"][$i+1]);

		 if ($ls_imp=="")
		 {

			$la_datos[$i]["<b>IMP.</b>"]="EXE";

		 }

		else
		 {

			$la_datos[$i]["<b>IMP.</b>"]="IVA";
		 }

	}

	}

	$io_pdf->ezSetY(440);
	$la_anchos_col = array(75,37,21,18,18,30,61,13);
	$la_justificaciones = array('left','right','right','right','right','left','left','center');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 8,
						   "lineas"=>1,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>1);
	$io_pdf->add_tabla(-29,$la_datos,$la_opciones);


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
