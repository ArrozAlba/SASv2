<?Php
/******************************************/
/* FECHA: 13/08/2007                      */ 
/* AUTOR: ING. ZULHEYMAR RODRÍGUEZ             */         
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
$io_pdf->numerar_paginas(6);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$reporte->add_titulo("center",22,15,"LISTADO DE COTIZACIONES");
$ls_fecha=date('d/m/Y  g:i:s a');
$reporte->add_titulo("left",34,7,"Fecha emisión: ".$ls_fecha);
            $ls_sql=$_GET['sql'];            
			$ls_fecemi=$_GET["fecemi"];
			$ls_fecemi2=$_GET["fecemi2"];
		    $ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);
			//print $ls_sql;
			$rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{			  
				$io_msg->message("No hay Nada que Reportar");
			}
			else
			{ 
			   $la_cotizacion=$io_sql->obtener_datos($rs_datauni);
	        	if ($la_cotizacion)
				{
			   $li_cuotas=(count($la_cotizacion,COUNT_RECURSIVE)/count($la_cotizacion)) - 1;
			   if ($ls_fecemi<>"%/%")
				{	
				$ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
	  $ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";
	  			$reporte->add_titulo("center",34,8,"Fecha desde: ".$ls_fecemi."   Hasta: ".$ls_fecemi2);
				}			
				$total=0;
				for($i=0;$i<$li_cuotas;$i++)
				{
				 $ls_fecemi3="".substr($la_cotizacion["feccot"][$i+1],8,2)."/".substr( $la_cotizacion["feccot"][$i+1],5,2)."/".substr( $la_cotizacion["feccot"][$i+1],0,4)."";
	 			 $la_datos[$i]["<b>No. COTIZACIÓN</b>"]= strtoupper($la_cotizacion["numcot"][$i+1]);
				 $la_datos[$i]["<b>FECHA</b>"]= $ls_fecemi3;
				 $la_datos[$i]["<b>R.I.F.</b>"]= $la_cotizacion["cedcli"][$i+1];
				 $la_datos[$i]["<b>NOMBRE CLIENTE</b>"]= strtoupper($la_cotizacion["razcli"][$i+1]);
				 $la_datos[$i]["<b>CAJERO</b>"]= strtoupper($la_cotizacion["codusu"][$i+1]);
				  if ($la_cotizacion["estcot"][$i+1]=='E')
				 {
				 $la_datos[$i]["<b>ESTADO</b>"]='EMITIDA';
				 }else if ($la_cotizacion["estcot"][$i+1]=='F')
				 {
				 $la_datos[$i]["<b>ESTADO</b>"]='FACTURADA';
				 }			 			 
				 
				
				 
				 $la_datos[$i]["<b>MONTO</b>"]= number_format($la_cotizacion["monto"][$i+1],2,',','.');
				  $total=$total+$la_cotizacion["monto"][$i+1];
				 }
				 $total=number_format($total,2,',','.');					
				$io_pdf->ezSetY(440);        
				$la_anchos_col = array(40,18,18,65,18,25,30);
				$la_justificaciones = array('center','center','center','left','center','center','center');		
				$la_opciones = array(  "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(-1,$la_datos,$la_opciones);
				$la_datos2[0]["totales"]= "<b>TOTAL GENERAL Bs.</b>";
			  
			   // datos para la segunda columna
			   //$la_titulos2[0]["2"]="";
			  	$la_datos2[0]["resultados"]= '<b>'.$total.'</b>';     // SUBTOTAL
			
			  //print $li_cuotas;
				//$io_pdf->ezSetY(550-$li_cuotas*20);
				$la_anchos_col = array(43,30);
				$la_justificaciones = array('left','right');
				// titulos de la primera y segunda columna respectivamente
				$la_titulos2[0]["1"]="";
				$la_titulos2[0]["2"]="";
				
							
				$la_opciones2 = array(  "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,									   
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);			   
				$io_pdf->add_tabla2(140,$la_datos2,$la_opciones2); 					   
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
