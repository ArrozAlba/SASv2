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
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
$io_pdf->numerar_paginas(6); 

$reporte->add_titulo("center",10,15,"DIRECTORIO DE CLIENTES");
$ls_fecha=date('d/m/Y,  g:i:s a');

$reporte->add_titulo("left",25,7,"Fecha emisión: ".$ls_fecha);
            $ls_sql=$_GET["sql"];
		    $ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);
			$rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{			  
				//$io_msg->message("No hay registros");
			}
			else
			{ 
			   $la_producto=$io_sql->obtener_datos($rs_datauni);
	        }						
                if ($la_producto)
				{
				$li_cuotas=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;			   
				for($i=0;$i<$li_cuotas;$i++)
				{
				 $la_datos[$i]["<b>CODIGO</b>"]= $la_producto["cedcli"][$i+1];
				 $la_datos[$i]["<b>NOMBRE/ DIRECCION</b>"]= $la_producto["razcli"][$i+1]." 
				  ".$la_producto["dircli"][$i+1];
				 //$la_datos[$i]["<b>DIRECCIÓN</b>"]= $la_producto["dircli"][$i+1];
				 $la_datos[$i]["<b>TELÉFONO</b>"]= strtoupper($la_producto["telcli"][$i+1]);
				 				 
				}				
				$io_pdf->ezSetY(620);        
				$la_anchos_col = array(27,80,30);
				$la_justificaciones = array('center','left','center');							  
				$la_opciones = array(  "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(8,$la_datos,$la_opciones);
$io_pdf->ezStream();
}else{
$io_msg->message("No hay Nada que Reportar");
}
?>