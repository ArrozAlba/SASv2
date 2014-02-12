<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
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
$reporte->add_titulo("center",18,15,"LISTADO DE TIENDAS");
$ls_fecha=date('d/m/Y');
$io_pdf->numerar_paginas(6);
$reporte->add_titulo("left",34,7,"Fecha emision: ".$ls_fecha);
			$ls_sql=$_GET["sql"];
		    $ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);
			//print $ls_sql;
			$rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				 $li_cuotas=$io_sql->num_rows($rs_datauni);
			   $la_producto=$io_sql->obtener_datos($rs_datauni);

	        }
			if ($la_producto)
			{
			//$li_cuota=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;
			// $li_cuotas=$io_sql->num_rows($la_producto);
			   $io_pdf->ezSetDy(-37);
			   //print $li_cuota."FIL  ".$li_cuotas;
				for($i=0;$i<$li_cuotas;$i++)
				{

						$la_datos=array(array('name'=>'<b>CODIGO: </b>  '.$la_producto["codtie"][$i+1].'      '),array ('name'=>'<b>DESCRIPCION: </b>  '.$la_producto["dentie"][$i+1]),array('name'=>'<b>DIRECCION:</b> '.$la_producto["dirtie"][$i+1].'      '),array ('name'=>'<b>TELEFONO: </b>  '.$la_producto["teltie"][$i+1]),array('name'=>'<b>PAIS: </b> '.$la_producto["despai"][$i+1].' '),array('name'=>'<b>ESTADO: </b> '.$la_producto["desest"][$i+1]));
						$la_columna=array('name'=>'');
						$la_presen=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' =>10, // Tama�o de Letras
								 'lineCol'=>array(0,0,0), // Mostrar L�neas
								 'showLines'=>1, // Mostrar L�neas
								 'shaded'=>0,
								 'xOrientation'=>'center', // Orientaci�n de la tabla
								 'width'=>670, // Ancho de la tabla
								 'maxWidth'=>670); // Ancho M�ximo de la tabla

						$io_pdf->line(100,40,670,40);

						$io_pdf->ezTable($la_datos,$la_columna,'',$la_presen);

						$io_pdf->ezSetDy(-8);

					$io_pdf->ezSetDy(-8);
				}
					$io_pdf->ezSetY(440);





/*******************************************************************************************************************************/
 $io_pdf->ezStream();
}
else//if $la_producto no trae nada
{
$io_msg->message("No hay Nada que Reportar");
}
?>
