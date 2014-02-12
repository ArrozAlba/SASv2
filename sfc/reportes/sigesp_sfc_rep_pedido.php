<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODRÍGUEZ        */
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
require_once("../../shared/class_folder/class_fecha.php");
$io_fecha=new class_fecha();
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
$io_pdf->numerar_paginas(6);
$reporte->add_titulo("center",10,11,"PEDIDO DE ARTICULOS");
            $ls_sql=$_GET["sql"];
			$ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);
		    $rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
			   $la_pedido=$io_sql->obtener_datos($rs_datauni);
	        }
				$reporte->add_titulo("right",25,7,"No. ".$la_pedido["numpedido"][1]);
				$ls_fecha="".substr($la_pedido["fecpedido"][1],8,2)."/".substr($la_pedido["fecpedido"][1],5,2)."/".substr($la_pedido["fecpedido"][1],0,4)."";
				$reporte->add_titulo("right",28,7,"Fecha: ".$ls_fecha);
				$reporte->add_titulo("left",34,7,"Agrotienda: ".$la_pedido["dentie"][1]);
				$reporte->add_titulo("left",37,7,"Unidad Ejecutora: ".$la_pedido["coduniadm"][1]."     ".$la_pedido["denuniadm"][1]);
				$reporte->add_titulo("left",41,7,"Dirección: ".$la_pedido["dirtie"][1]);

				$reporte->add_titulo("right",47,7,"Asesor: ".$la_pedido["nomusu"][1]);
				$reporte->add_titulo("left",44,7,"Teléfonos: ".$la_pedido["teltie"][1]);
				$reporte->add_titulo("left",47,7,"Rif: ".$la_pedido["riftie"][1]);
				$li_cuotas=(count($la_pedido,COUNT_RECURSIVE)/count($la_pedido)) - 1;
			    $la_total=0;
			    $la_iva=0;
				for($i=0;$i<$li_cuotas;$i++)
				{
		  	     $la_datos[$i]["<b>Código</b>"]= $la_pedido["codart"][$i+1];
				 $la_datos[$i]["<b>Descripción</b>"]= $la_pedido["denart"][$i+1];
				 $la_datos[$i]["<b>Cantidad</b>"]= number_format($la_pedido["cantped"][$i+1],2, ',', '.');
			  	}
				$io_pdf->ezSetY(550);
				$la_anchos_col = array(35,80,20,20,20);
				$la_justificaciones = array('center','left','right','right','right');
				$la_opciones = array(  "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(15,$la_datos,$la_opciones);
			  	$reporte->add_titulo("left",215,6,"OBSERVACIÓN: ".$la_pedido["obspedido"][1]);




$io_pdf->ezStream();


?>