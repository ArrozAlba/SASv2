<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
/******************************************/
session_start();
require_once("sigesp_sfc_c_reportes.php");
//require_once("../../shared/class_folder/ezpdf/class.pdf.php");

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
$reporte->add_titulo("center",10,15,"LISTADO DE FACTURAS");
$ls_fecha=date('d/m/Y');
$reporte->add_titulo("left",16,7,"Fecha emision: ".$ls_fecha);
            $ls_sql=$_GET['sql'];
            $ls_sql2=$_GET['sql2'];
			$ls_fecemi=$_GET["fecemi"];
			$ls_fecemi2=$_GET["fecemi2"];
			$ls_opcion=$_GET["opcion"];

			$ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);

			$ls_sql2=str_replace("\\","",$ls_sql2);
			$ls_sql2=str_replace("/","",$ls_sql2);

//print $ls_sql;
//print "<br>22222222".$ls_sql2;

			$rs_datauni=$io_sql->select($ls_sql);
			$rs_datauni2=$io_sql->select($ls_sql2);
			$rows2 =$io_sql->num_rows($rs_datauni2);

			$rows =$io_sql->num_rows($rs_datauni);

$i=0;

/*print $rs_datauni;
print "<br>".$rs_datauni2;*/


			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay Nada que Reportar");
				print $io_sql->message;
			}
			else
			{


				if ($ls_sql<>"")
				   	   $la_factura=$io_sql->obtener_datos($rs_datauni);
//print_r($la_factura);
//print "-----------------------<br>";
	          if($rows2>0)
			   {
			   		$la_facturacred=$io_sql->obtener_datos($rs_datauni2);		   	
		   	   }

	       // print_r($la_facturacred);



	        	if (($la_factura) or ($la_facturacred))
				{

				if ($la_factura)
				{
				   $li_cuotas=(count($la_factura,COUNT_RECURSIVE)/count($la_factura)) - 1;
//print $li_cuotas."facturaaaaaaaaaaaa<br>";

				   if ($ls_fecemi<>"%/%")
					{
						$ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
			  			$ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";
			  			$reporte->add_titulo("right",34,7,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2);

					}
					$total=0;
					$subtotal=0;
					$iva=0;


					for($i=0;$i<$rows;$i++)
					{
						$ls_fecemi3="".substr($la_factura["fecemi"][$i+1],8,2)."/".substr( $la_factura["fecemi"][$i+1],5,2)."/".substr( $la_factura["fecemi"][$i+1],0,4)."";
						if($ls_opcion=="resumen")
						{
							 $la_datos[$i]["<b>FECHA</b>"]= $ls_fecemi3;
							 $la_datos[$i]["<b>No. FACTURA</b>"]= strtoupper($la_factura["numfac"][$i+1]);
							 $la_datos[$i]["<b>R.I.F.</b>"]= $la_factura["cedcli"][$i+1];
							 $la_datos[$i]["<b>NOMBRE CLIENTE</b>"]= strtoupper($la_factura["razcli"][$i+1]);
							 $la_datos[$i]["<b>CAJERO</b>"]= strtoupper($la_factura["codusu"][$i+1]);
							 $la_datos[$i]["<b>MONTO</b>"]= number_format($la_factura["montotot"][$i+1],2,',','.');
							  $total=$total+$la_factura["montotot"][$i+1];
							 if ($la_factura["estfaccon"][$i+1]=='E')
							 {
								 $la_datos[$i]["<b>STATUS</b>"]='EMITIDA';
								 $totalemi=$totalemi+$la_factura["montotot"][$i+1];
							 }
							 else if ($la_factura["estfaccon"][$i+1]=='C')
							 {
								 $la_monto = $reporte->uf_calcular_montocobradofac(strtoupper($la_factura["numfac"][$i+1]),$la_factura["montotot"][$i+1]);

								 if($la_monto==-1)
								 {
								 	if($la_factura["montopar"][$i+1]<0)
								 	{
								 		$totalcan=$totalcan+$la_factura["montotot"][$i+1];
								 		$la_datos[$i]["<b>STATUS</b>"]='CANCELADA';
								 	}
									else
									{
									 	$totalcob=$totalcob+$la_factura["montotot"][$i+1];
									 	$la_datos[$i]["<b>STATUS</b>"]='POR COBRAR';
									}
								 }else
								 {
								 	if($la_monto==0){
								 		$totalcan=$totalcan+$la_factura["montotot"][$i+1];
								 		$la_datos[$i]["<b>STATUS</b>"]='CANCELADA';
								 	}
								 }

							 }else if ($la_factura["estfaccon"][$i+1]=='N')
							 {
								 $la_datos[$i]["<b>STATUS</b>"]='POR COBRAR';
								 $totalcob=$totalcob+$la_factura["monto"][$i+1];
							 }
							 else if ($la_factura["estfaccon"][$i+1]=='P')
							 {
								 $la_datos[$i]["<b>STATUS</b>"]='POR COBRAR';
								 $totalcob=$totalcob+$la_factura["montotot"][$i+1];
							 }
							 else if ($la_factura["estfaccon"][$i+1]=='A')
							 {
								 $la_datos[$i]["<b>STATUS</b>"]='ANULADA';
								 $totalanu=$totalanu+$la_factura["montotot"][$i+1];
							 }
						}
						elseif($ls_opcion=="detalles")
						{
							 $la_datos[$i]["<b>FECHA</b>"]= $ls_fecemi3;
							 $la_datos[$i]["<b>No. FACTURA</b>"]= strtoupper($la_factura["numfac"][$i+1]);
							 $la_datos[$i]["<b>R.I.F.</b>"]= $la_factura["cedcli"][$i+1];
							 $la_datos[$i]["<b>NOMBRE CLIENTE</b>"]= strtoupper($la_factura["razcli"][$i+1]);
							 $la_datos[$i]["<b>CAJERO</b>"]= strtoupper($la_factura["codusu"][$i+1]);
                             if($la_factura["numfac"][$i+1+1]==$la_factura["numfac"][$i+1])// and (($la_factura["montotot"][$i+1])<($la_factura["montotot"][$i+1+1])))
                             {
//print "pase 1111";
                             	if(($la_factura["montotot"][$i+1])>=($la_factura["montotot"][$i+1+1]))
                             	{

                             		if ($la_factura["montopar"][$i+1]<0)
                             		{
                             			$la_datos[$i]["<b>SUBTOTAL</b>"]= number_format(($la_factura["monto"][$i+1] - $la_factura["montoret"][$i+1]),2,',','.') ;
										$la_datos[$i]["<b>IVA</b>"]= number_format($la_factura["montoret"][$i+1],2,',','.');
										$la_datos[$i]["<b>MONTO</b>"]= number_format($la_factura["monto"][$i+1],2,',','.');
							 			$total=$total+$la_factura["monto"][$i+1];
									 	$subtotal=$subtotal+($la_factura["monto"][$i+1]-$la_factura["montoret"][$i+1]);
									}
									else
									{
										$la_datos[$i]["<b>SUBTOTAL</b>"]= number_format(($la_factura["montotot"][$i+1]- $la_factura["montoret"][$i+1]),2,',','.');
                             			$la_datos[$i]["<b>IVA</b>"]= number_format($la_factura["montoret"][$i+1],2,',','.');
                             			$la_datos[$i]["<b>MONTO</b>"]= number_format($la_factura["montotot"][$i+1],2,',','.');
							  			$total=$total+$la_factura["montotot"][$i+1];

										$subtotal=$subtotal+($la_factura["montotot"][$i+1]-$la_factura["montoret"][$i+1]);

									}

                             	}
                             	else
                             	{
                             		//print "pase";

                             		if ($la_factura["montopar"][$i+1]<0)
                             		{
                             			$la_datos[$i]["<b>SUBTOTAL</b>"]= number_format(($la_factura["monto"][$i+1] - $la_factura["montoret"][$i+1]),2,',','.') ;
										$la_datos[$i]["<b>IVA</b>"]= number_format($la_factura["montoret"][$i+1],2,',','.');
										 $la_datos[$i]["<b>MONTO</b>"]= number_format($la_factura["monto"][$i+1],2,',','.');
							 			 $total=$total+$la_factura["monto"][$i+1];

										$subtotal=$subtotal+($la_factura["monto"][$i+1]-$la_factura["montoret"][$i+1]);


                             		}
									else
									{
										$la_datos[$i]["<b>SUBTOTAL</b>"]= number_format(($la_factura["montotot"][$i+1]- $la_factura["montoret"][$i+1]),2,',','.');
                             			$la_datos[$i]["<b>IVA</b>"]= number_format($la_factura["montoret"][$i+1],2,',','.');
                             			 $la_datos[$i]["<b>MONTO</b>"]= number_format($la_factura["montotot"][$i+1],2,',','.');
							  			$total=$total+$la_factura["montotot"][$i+1];

										$subtotal=$subtotal+($la_factura["montotot"][$i+1]-$la_factura["montoret"][$i+1]);

									}


                             	}


                             }
							else
							{
									if ($la_factura["montopar"][$i+1]<0)
                             		{
                             			$la_datos[$i]["<b>SUBTOTAL</b>"]= number_format(($la_factura["monto"][$i+1] - $la_factura["montoret"][$i+1]),2,',','.') ;
										$la_datos[$i]["<b>IVA</b>"]= number_format($la_factura["montoret"][$i+1],2,',','.');
										 $la_datos[$i]["<b>MONTO</b>"]= number_format($la_factura["monto"][$i+1],2,',','.');
							 			 $total=$total+$la_factura["monto"][$i+1];

										$subtotal=$subtotal+($la_factura["monto"][$i+1]-$la_factura["montoret"][$i+1]);

                             		}
									else
									{
										$la_datos[$i]["<b>SUBTOTAL</b>"]= number_format(($la_factura["montotot"][$i+1]- $la_factura["montoret"][$i+1]),2,',','.');
                             			$la_datos[$i]["<b>IVA</b>"]= number_format($la_factura["montoret"][$i+1],2,',','.');
                             			 $la_datos[$i]["<b>MONTO</b>"]= number_format($la_factura["montotot"][$i+1],2,',','.');
							  			$total=$total+$la_factura["montotot"][$i+1];

										$subtotal=$subtotal+($la_factura["montotot"][$i+1]-$la_factura["montoret"][$i+1]);

									}
							}



							 $iva=$iva+$la_factura["montoret"][$i+1];


							 if ($la_factura["estfaccon"][$i+1]=='E')
							 {
							 $la_datos[$i]["<b>STATUS</b>"]='EMITIDA';
							 $totalemi=$totalemi+$la_factura["monto"][$i+1];
							 }
							 else if ($la_factura["estfaccon"][$i+1]=='C')
							 {
							 //$la_datos[$i]["<b>STATUS</b>"]='CANCELADA';
							 //$totalcan=$totalcan+$la_factura["monto"][$i+1];
							 $la_monto = $reporte->uf_calcular_montocobradofac(strtoupper($la_factura["numfac"][$i+1]),$la_factura["montotot"][$i+1]);
//print $la_monto."<br>";
								 if($la_monto==-1)
								 {
								 	if($la_factura["montopar"][$i+1]<0)
								 	{
								 		$totalcan=$totalcan+$la_factura["montotot"][$i+1];
								 		$la_datos[$i]["<b>STATUS</b>"]='CANCELADA';
								 	}
									else
									{
									 	$totalcob=$totalcob+$la_factura["montotot"][$i+1];
									 	$la_datos[$i]["<b>STATUS</b>"]='POR COBRAR';
									}

								 }else
								 {
								 	if($la_monto==0)
								 	{
								 		$totalcan=$totalcan+$la_factura["montotot"][$i+1];
								 		$la_datos[$i]["<b>STATUS</b>"]='CANCELADA';
								 	}
								 }
							 }
							 else if ($la_factura["estfaccon"][$i+1]=='N')
							 {
							 $la_datos[$i]["<b>STATUS</b>"]='POR COBRAR';
							 $totalcob=$totalcob+$la_factura["montotot"][$i+1];
							 }
							 else if ($la_factura["estfaccon"][$i+1]=='P')
							 {
							 $la_datos[$i]["<b>STATUS</b>"]='POR COBRAR';
							 $totalcob=$totalcob+$la_factura["montotot"][$i+1];
							 }
							 else if ($la_factura["estfaccon"][$i+1]=='A')
							 {
							 $la_datos[$i]["<b>STATUS</b>"]='ANULADA';
							 $totalanu=$totalanu+$la_factura["montotot"][$i+1];

							 }


						}

					 }
				}


/*************************  FACTURAS A CREDITOS ******************************/

					if($la_facturacred)
					{
					 	$li_faccred=(count($la_facturacred,COUNT_RECURSIVE)/count($la_facturacred)) - 1;

//print "PASEEEEE".$li_faccred;
						//$j=$i;
if($i<>0)
 $c=$i;
 else
 $c=-1;

//print $c;
					 	for($j=0;$j<$rows2;$j++)
						{
						$c++;
							 $ls_fecemi3="".substr($la_facturacred["fecemi"][$j+1],8,2)."/".substr( $la_facturacred["fecemi"][$j+1],5,2)."/".substr( $la_facturacred["fecemi"][$j+1],0,4)."";
							if($ls_opcion=="resumen")
							{
								 $la_datos[$c]["<b>FECHA</b>"]= $ls_fecemi3;
								 $la_datos[$c]["<b>No. FACTURA</b>"]= strtoupper($la_facturacred["numfac"][$j+1]);
								 $la_datos[$c]["<b>R.I.F.</b>"]= $la_facturacred["cedcli"][$j+1];
								 $la_datos[$c]["<b>NOMBRE CLIENTE</b>"]= strtoupper($la_facturacred["razcli"][$j+1]);
								 $la_datos[$c]["<b>CAJERO</b>"]= strtoupper($la_facturacred["codusu"][$j+1]);
								 $la_datos[$c]["<b>MONTO</b>"]= number_format($la_facturacred["montotot"][$j+1],2,',','.');
								 $total=$total+$la_facturacred["montotot"][$j+1];

								if ($la_facturacred["estfaccon"][$j+1]=='E')
								 {
								 $la_datos[$c]["<b>STATUS</b>"]='EMITIDA';
								 $totalemi=$totalemi+$la_facturacred["monto"][$j+1];
								 }
								 else if ($la_facturacred["estfaccon"][$j+1]=='C')
								 {
								 //$la_datos[$i]["<b>STATUS</b>"]='CANCELADA';
								 //$totalcan=$totalcan+$la_factura["monto"][$i+1];
								 $la_monto = $reporte->uf_calcular_montocobradofac(strtoupper($la_facturacred["numfac"][$j+1]),$la_facturacred["montotot"][$j+1]);
	//print $la_monto."<br>";
									 if($la_monto==-1)
									 {
										if($la_factura["montopar"][$i+1]<0)
										{
											$totalcan=$totalcan+$la_facturacred["montotot"][$j+1];
											$la_datos[$c]["<b>STATUS</b>"]='CANCELADA';
										}
										else
										{
											$totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
											$la_datos[$c]["<b>STATUS</b>"]='POR COBRAR';
										}
	
									 }else
									 {
										if($la_monto==0)
										{
											$totalcan=$totalcan+$la_facturacred["montotot"][$j+1];
											$la_datos[$c]["<b>STATUS</b>"]='CANCELADA';
										}
									 }
								 }
								 else if ($la_facturacred["estfaccon"][$j+1]=='N')
								 {
								 $la_datos[$c]["<b>STATUS</b>"]='POR COBRAR';
								 $totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
								 }
								 else if ($la_facturacred["estfaccon"][$j+1]=='P')
								 {
								 $la_datos[$c]["<b>STATUS</b>"]='POR COBRAR';
								 $totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
								 }
								 else if ($la_facturacred["estfaccon"][$j+1]=='A')
								 {
								 $la_datos[$c]["<b>STATUS</b>"]='ANULADA';
								 $totalanu=$totalanu+$la_facturacred["montotot"][$j+1];
	
								 }
								$totalcob=$totalcob+$la_facturacred["montotot"][$j+1];

							}
							elseif($ls_opcion=="detalles")
							{


								 $la_datos[$c]["<b>FECHA</b>"]= $ls_fecemi3;
								 $la_datos[$c]["<b>No. FACTURA</b>"]= strtoupper($la_facturacred["numfac"][$j+1]);
								 $la_datos[$c]["<b>R.I.F.</b>"]= $la_facturacred["cedcli"][$j+1];
								 $la_datos[$c]["<b>NOMBRE CLIENTE</b>"]= strtoupper($la_facturacred["razcli"][$j+1]);
								 $la_datos[$c]["<b>CAJERO</b>"]= strtoupper($la_facturacred["codusu"][$j+1]);
								 $la_datos[$c]["<b>SUBTOTAL</b>"]= number_format(($la_facturacred["montotot"][$j+1]- $la_facturacred["montoret"][$j+1]),2,',','.') ;

								 $la_datos[$c]["<b>IVA</b>"]= number_format($la_facturacred["montoret"][$j+1],2,',','.');
								 $iva=$iva+$la_facturacred["montoret"][$j+1];
								 $la_datos[$c]["<b>MONTO</b>"]= number_format($la_facturacred["montotot"][$j+1],2,',','.');
								  $total=$total+$la_facturacred["montotot"][$j+1];

								$subtotal=$subtotal+($la_facturacred["montotot"][$j+1]-$la_facturacred["montoret"][$j+1]);



							 if ($la_facturacred["estfaccon"][$j+1]=='E')
							 {
							 $la_datos[$c]["<b>STATUS</b>"]='EMITIDA';
							 $totalemi=$totalemi+$la_facturacred["monto"][$j+1];
							 }
							 else if ($la_facturacred["estfaccon"][$j+1]=='C')
							 {
							 //$la_datos[$i]["<b>STATUS</b>"]='CANCELADA';
							 //$totalcan=$totalcan+$la_factura["monto"][$i+1];
							 $la_monto = $reporte->uf_calcular_montocobradofac(strtoupper($la_facturacred["numfac"][$j+1]),$la_facturacred["montotot"][$j+1]);
//print $la_monto."<br>";
								 if($la_monto==-1)
								 {
								 	if($la_factura["montopar"][$i+1]<0)
								 	{
								 		$totalcan=$totalcan+$la_facturacred["montotot"][$j+1];
								 		$la_datos[$c]["<b>STATUS</b>"]='CANCELADA';
								 	}
									else
									{
									 	$totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
									 	$la_datos[$c]["<b>STATUS</b>"]='POR COBRAR';
									}

								 }else
								 {
								 	if($la_monto==0)
								 	{
								 		$totalcan=$totalcan+$la_facturacred["montotot"][$j+1];
								 		$la_datos[$c]["<b>STATUS</b>"]='CANCELADA';
								 	}
								 }
							 }
							 else if ($la_facturacred["estfaccon"][$j+1]=='N')
							 {
							 $la_datos[$c]["<b>STATUS</b>"]='POR COBRAR';
							 $totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
							 }
							 else if ($la_facturacred["estfaccon"][$j+1]=='P')
							 {
							 $la_datos[$c]["<b>STATUS</b>"]='POR COBRAR';
							 $totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
							 }
							 else if ($la_facturacred["estfaccon"][$j+1]=='A')
							 {
							 $la_datos[$c]["<b>STATUS</b>"]='ANULADA';
							 $totalanu=$totalanu+$la_facturacred["montotot"][$j+1];

							 }


								/* $la_datos[$c]["<b>STATUS</b>"]='POR COBRAR';
								 $totalcob=$totalcob+$la_facturacred["montotot"][$j+1];*/
							}
						}
					}

/*print "CCCCC".$c;
print_r($la_datos);*/
					 $subtotal=number_format($subtotal,2,',','.');
					 $iva=number_format($iva,2,',','.');
					 $totalemi=$total-$totalanu;
					 $total=number_format($total,2,',','.');
					 $io_pdf->ezSetY(500);

					if($ls_opcion=="resumen")
					{
						$la_titulos[0]["<b>FECHA</b>"]="<b>FECHA</b>";
						$la_titulos[0]["<b>No. FACTURA</b>"]="<b>No. FACTURA</b>";
						$la_titulos[0]["<b>R.I.F.</b>"]="<b>R.I.F.</b>";
						$la_titulos[0]["<b>NOMBRE CLIENTE</b>"]= "<b>NOMBRE CLIENTE</b>";
						$la_titulos[0]["<b>CAJERO</b>"]="<b>CAJERO</b>";
						$la_titulos[0]["<b>MONTO</b>"]= "<b>MONTO</b>";
						$la_titulos[0]["<b>STATUS</b>"]="<b>STATUS</b>";

						$la_anchos_col = array(20,40,18,65,30,30,30);
						$la_justificaciones = array('center','center','center','left','right','right','center');
						$la_opciones = array(  "color_fondo" => array(255,255,255),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla(-5,$la_titulos,$la_opciones);
						$io_pdf->add_tabla(-5,$la_datos,$la_opciones);

						$la_datos2[0]["totales"]= "<b>TOTAL GENERAL Bs</b>";

					   // datos para la segunda columna
					   //$la_titulos2[0]["2"]="";
						$la_datos2[0]["resultados"]= '<b>'.$total.'</b>';     // SUBTOTAL


						$la_anchos_col = array(30,30);
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
						$io_pdf->add_tabla2(138,$la_datos2,$la_opciones2);


					}
					elseif($ls_opcion=="detalles")
					{
						//print $li_faccred;
						$la_titulos[0]["<b>FECHA</b>"]="<b>FECHA</b>";
						$la_titulos[0]["<b>No. FACTURA</b>"]="<b>No. FACTURA</b>";
						$la_titulos[0]["<b>R.I.F.</b>"]="<b>R.I.F.</b>";
						$la_titulos[0]["<b>NOMBRE CLIENTE</b>"]= "<b>NOMBRE CLIENTE</b>";
						$la_titulos[0]["<b>CAJERO</b>"]="<b>CAJERO</b>";
						$la_titulos[0]["<b>SUB-TOTAL</b>"]= "<b>SUB-TOTAL</b>";
						$la_titulos[0]["<b>IVA</b>"]= "<b>IVA</b>";
						$la_titulos[0]["<b>MONTO</b>"]= "<b>MONTO</b>";
						$la_titulos[0]["<b>STATUS</b>"]="<b>STATUS</b>";

						$la_anchos_col = array(20,40,18,65,25,25,18,25,25);
						$la_justificaciones = array('center','center','center','left','right','right','right','right','center');
						$la_opciones = array(  "color_fondo" => array(255,255,255),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla(-25,$la_titulos,$la_opciones);
						$io_pdf->add_tabla(-25,$la_datos,$la_opciones);

						$la_datos2[0]["totales"]= "<b>TOTAL GENERAL Bs</b>";

					   // datos para la segunda columna
					   //$la_titulos2[0]["2"]="";
						$la_datos2[0]["resultadossub"]= '<b>'.$subtotal.'</b>';     // SUBTOTAL
					    $la_datos2[0]["resultadosiva"]= '<b>'.$iva.'</b>';
						$la_datos2[0]["resultadostot"]= '<b>'.$total.'</b>';
						//$la_datos2[3]["resultados"]= '<b>'.$total.'</b>';
					  //print $li_cuotas;
						//$io_pdf->ezSetY(550-$li_cuotas*20);
						$la_anchos_col = array(25,25,18,25);
						$la_justificaciones = array('left','right','right','right');
						// titulos de la primera y segunda columna respectivamente
						$la_titulos2[0]["1"]="";
						$la_titulos2[0]["2"]="";
//print_r($la_datos2);

						$la_opciones2 = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 7,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla2(119.3,$la_datos2,$la_opciones2);

				}


		//$io_pdf->add_line(5);
			$totalcob=number_format($totalcob,2,',','.');
			$totalcan=number_format($totalcan,2,',','.');
			$totalemi=number_format($totalemi,2,',','.');
	   	    $totalanu=number_format($totalanu,2,',','.');
			$la_data3[0] = array('name1'=>'RESUMEN FACTURAS','name2'=>'');
			$la_data3[1] = array('name1'=>'Total Facturas Por Cobrar ','name2'=>$totalcob);
		    $la_data3[2] = array('name1'=>'Total Facturas Canceladas ','name2'=>$totalcan);
		    $la_data3[3] = array('name1'=>'Total Facturas Emitidas ','name2'=>$totalemi);
/*		  	$la_data3[5] = array('name1'=>'Total Facturas Anuladas ','name2'=>$totalanu);
		    $la_data3[4] = array('name1'=>'Total Facturas Emitidas ','name2'=>$total);
*/
			$la_columna = array('name1'=>'','name2'=>'');
			$la_justificaciones = array('left','right','left');
			$la_config  = array('showHeadings'=>0, // Mostrar encabezados
							    'fontSize' =>7, // Tama�o de Letras
							   'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
							   'showLines'=>1, // Mostrar L�neas
							   'shaded'=>2, // Sombra entre l�neas
							   'width'=>970, // Ancho de la tabla
							   'maxWidth'=>970, // Ancho M�ximo de la tabla
							   "alineacion_col"=>$la_justificaciones,
							   'cols'=>array('name0'=>array('width'=>256,'showLines'=>1),
							                 'name1'=>array('width'=>256),
											 'name2'=>array('width'=>20)));

			$io_pdf->add_tabla2(8,$la_data3,$la_config);






	$io_pdf->ezStream();

}else
{
$io_msg->message("No hay Nada que Reportar");
}
}

?>
