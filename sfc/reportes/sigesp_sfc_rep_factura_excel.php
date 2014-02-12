<?php
    session_start();
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "close();";
		 print "</script>";
	   }


	   if (array_key_exists("fechaemi",$_GET))
	   {
	     $ls_fechadesde=$_POST["fechaemi"];
	   }
    else
	   {
	     $ls_fechadesde=$_GET["fechaemi"];
	   }
	   if (array_key_exists("fechaemi2",$_GET))
	   {
	     $ls_fechahasta=$_POST["fechaemi2"];
	   }
    else
	   {
	     $ls_fechahasta=$_GET["fechaemi2"];
	   }

	//print $ls_fechadesde;//-----------------------------------------------------------------------------------------------------------------------------------
	/*function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 22/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;

		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_cuentas.php",$ls_descripcion);
		return $lb_valido;
	}*/
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio
		//	Description: Este m�todo realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scg;

		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/home/production/tmp", "listado_factura.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("sigesp_sfc_c_reportes.php");
		$reporte = new sigesp_sfc_c_reportes('LETTER','landscape','REPORTE 1');
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_datastore.php");

		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_in    = new sigesp_include();
		$con      = $io_in->uf_conectar();

		$io_datastore= new class_datastore();

		$io_msg=new class_mensajes();
		$io_sql=new class_sql($con);
		$io_data=new class_datastore();

		$io_sql   = new class_sql($con);
		$io_sql2   = new class_sql($con);
		$io_sql3   = new class_sql($con);
		$io_sql5   = new class_sql($con);
		$io_sql9   = new class_sql($con);
		$io_sql10   = new class_sql($con);
		$io_datastore= new class_datastore();
		$io_datastore4= new class_datastore();
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_msg=new class_mensajes();
		$io_sql=new class_sql($io_connect);
		$io_data=new class_datastore();
		$io_funcion=new class_funciones();

		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();

		$ia_niveles_scg[0]="";
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
		//---------------------------------------------------------------------------------------------------------------------------
		//Par�metros para Filtar el Reporte
		$ls_desde=$_GET["desde"];
		$ls_hasta=$_GET["hasta"];
		$ls_fecha=date('d/m/Y');





			$ls_fecha=date('d/m/Y');

            $ls_sql=$_GET['sql'];
            $ls_sql2=$_GET['sql2'];

			$ls_fecemi=$_GET["fecemi"];
			$ls_fecemi2=$_GET["fecemi2"];
			$ls_opcion=$_GET["opcion"];



		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_sqlT="Select dentie from sfc_tienda where codtiend='".$ls_codtie."' ";
		$rs_data=$io_sql->select($ls_sqlT);
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


			$ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);

			$ls_sql2=str_replace("\\","",$ls_sql2);
			$ls_sql2=str_replace("/","",$ls_sql2);
/*			print "<br>SQL=".$ls_sql;
			print "<br>SQL2=".$ls_sql2;
*/			
			$rs_datauni=$io_sql->select($ls_sql);
			$rs_datauni2=$io_sql->select($ls_sql2);
			$rows2 =$io_sql->num_rows($rs_datauni2);

			$rows =$io_sql->num_rows($rs_datauni);

		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "LISTADO DE FACTURAS ".$ls_dentie;
		$ls_nombemp = $arremp["nombre"];

			$lo_encabezado= &$lo_libro->addformat();
			$lo_encabezado->set_bold();
			$lo_encabezado->set_font("Verdana");
			$lo_encabezado->set_align('center');
			$lo_encabezado->set_size('11');
			$lo_titulo= &$lo_libro->addformat();
			$lo_titulo->set_bold();
			$lo_titulo->set_font("Verdana");
			$lo_titulo->set_align('center');
			$lo_titulo->set_size('9');

			$lo_total= &$lo_libro->addformat();
			$lo_total->set_bold();
			$lo_total->set_font("Verdana");
			$lo_total->set_align('right');
			$lo_total->set_size('9');

			$lo_resumen= &$lo_libro->addformat();
			$lo_resumen->set_bold();
			$lo_resumen->set_font("Verdana");
			$lo_resumen->set_align('left');
			$lo_resumen->set_size('9');


			$lo_datacenter= &$lo_libro->addformat();
			$lo_datacenter->set_font("Verdana");
			$lo_datacenter->set_align('center');
			$lo_datacenter->set_size('9');
			$lo_dataleft= &$lo_libro->addformat();
			$lo_dataleft->set_text_wrap();
			$lo_dataleft->set_font("Verdana");
			$lo_dataleft->set_align('left');
			$lo_dataleft->set_size('9');
			$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
			$lo_dataright->set_font("Verdana");
			$lo_dataright->set_align('right');
			$lo_dataright->set_size('9');
			$lo_hoja->set_column(0,0,05);
			$lo_hoja->set_column(0,1,20);
			$lo_hoja->set_column(0,2,40);
			$lo_hoja->set_column(0,3,40);




			$lo_hoja->write(0,2,$ls_nombemp,$lo_encabezado);

			$lo_hoja->write(2,2,$ls_titulo,$lo_encabezado);

			$as_fechadesde = substr($ls_fechadesde,8,2).'/'.substr($ls_fechadesde,5,2).'/'.substr($ls_fechadesde,0,4);
			$as_fechahasta = substr($ls_fechahasta,8,2).'/'.substr($ls_fechahasta,5,2).'/'.substr($ls_fechahasta,0,4);

			//$lo_hoja->write(3,1,"PERIODO ".$as_fechadesde." AL ".$as_fechahasta,$lo_encabezado);
			$lo_hoja->set_column(3,1,15);
			$lo_hoja->write(3,1,"Fecha de Emision: ".$ls_fecha,$lo_dataleft);


			//$lo_hoja->write(2,0,$ls_sql,$lo_dataleft);

			if(($rs_datauni==false&&($io_sql->message!="")) and ($rs_datauni2==false&&($io_sql->message!="")))
			{
				$io_msg->message("No hay Nada que Reportar");
			}
			else
			{
			   $la_factura=$io_sql->obtener_datos($rs_datauni);
			   if($rows2>0)
			   {
			   		$la_facturacred=$io_sql->obtener_datos($rs_datauni2);		   	
		   	   }
	            if ($la_factura)			    
				{

			   if ($ls_fecemi<>"%/%")
				{
					$ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
					$ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";


					$lo_hoja->write(3,2,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2,$lo_dalaleft);
				}
				$total=0;
				$subtotal=0;
				$iva=0;
				$li_ini=5;




				for($i=0;$i<$rows;$i++)
				{
					$ls_fecemi3="".substr($la_factura["fecemi"][$i+1],8,2)."/".substr( $la_factura["fecemi"][$i+1],5,2)."/".substr( $la_factura["fecemi"][$i+1],0,4)."";
					if($ls_opcion=="resumen")
					{

						 $lo_hoja->set_column(4,0,10);
						 $lo_hoja->write(4,0,"FECHA ",$lo_titulo);

						 $lo_hoja->set_column(4,1,20);
						 $lo_hoja->write(4,1,"No. FACTURA ",$lo_titulo);

						 $lo_hoja->set_column(4,2,50);
						 $lo_hoja->write(4,2,"R.I.F. ",$lo_titulo);

						 $lo_hoja->set_column(4,3,60);
						 $lo_hoja->write(4,3,"NOMBRE CLIENTE ",$lo_titulo);

						  $lo_hoja->set_column(4,4,20);
						 $lo_hoja->write(4,4,"CAJERO ",$lo_titulo);

						 $lo_hoja->set_column(4,5,20);
						 $lo_hoja->write(4,5,"MONTO ",$lo_titulo);



						 $lo_hoja->write($li_ini, 0 , $ls_fecemi3 ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 1 , strtoupper($la_factura["numfac"][$i+1]) ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 2 , $la_factura["cedcli"][$i+1] ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 3 , strtoupper($la_factura["razcli"][$i+1]) ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 4 , strtoupper($la_factura["codusu"][$i+1]),$lo_dataleft);
						 $lo_hoja->write($li_ini, 5 ,  number_format($la_factura["montotot"][$i+1],2,',','.') ,$lo_dataright);


						  $total=$total+$la_factura["montotot"][$i+1];
						   $lo_hoja->set_column(4,6,30);
						 if ($la_factura["estfaccon"][$i+1]=='E')
						 {

							$lo_hoja->set_column(4,6,30);
						 	$lo_hoja->write(4,6,"STATUS ",$lo_titulo);
							$lo_hoja->write($li_ini, 6 , " EMITIDA ",$lo_dataleft);

						 	$totalemi=$totalemi+$la_factura["montotot"][$i+1];

						 }
						 else if ($la_factura["estfaccon"][$i+1]=='C')
						 {
						 	$la_monto = $reporte->uf_calcular_montocobradofac(strtoupper($la_factura["numfac"][$i+1]),$la_factura["montotot"][$i+1]);
							if($la_monto==-1)
							{
							 	$totalcob=$totalcob+$la_factura["montotot"][$i+1];
							 	$lo_hoja->set_column(4,6,30);
						 		$lo_hoja->write(4,6,"STATUS ",$lo_titulo);
								$lo_hoja->write($li_ini, 6 , " POR COBRAR ",$lo_dataleft);
							 }
							 else
							 {
							 	if($la_monto==0)
								{
							 		$totalcan=$totalcan+$la_factura["montotot"][$i+1];
							 		$lo_hoja->set_column(4,6,30);
						 			$lo_hoja->write(4,6,"STATUS ",$lo_titulo);
									$lo_hoja->write($li_ini, 6 , " CANCELADA ",$lo_dataleft);
							 	}

							 }

						 }
						 else if ($la_factura["estfaccon"][$i+1]=='N')
						 {
							 $lo_hoja->set_column(4,6,30);
						 	 $lo_hoja->write(4,6,"STATUS ",$lo_titulo);
							 $lo_hoja->write($li_ini, 6 , " POR COBRAR ",$lo_dataleft);
							 $totalcob=$totalcob+$la_factura["montotot"][$i+1];
						 }
						 else if ($la_factura["estfaccon"][$i+1]=='P')
						 {
							$lo_hoja->set_column(4,6,30);
						 	$lo_hoja->write(4,6,"STATUS ",$lo_titulo);
							$lo_hoja->write($li_ini, 6 , " POR COBRAR ",$lo_dataleft);
							 $totalcob=$totalcob+$la_factura["montotot"][$i+1];
						 }
						 else if ($la_factura["estfaccon"][$i+1]=='A')
						 {
							 $lo_hoja->set_column(4,6,30);
						 	$lo_hoja->write(4,6,"STATUS ",$lo_titulo);
							 $lo_hoja->write($li_ini, 6 , " ANULADA ",$lo_dataleft);
							 $totalanu=$totalanu+$la_factura["montotot"][$i+1];

						 }
					}
					elseif($ls_opcion=="detalles")
					{

						 $lo_hoja->set_column(4,0,10);
						 $lo_hoja->write(4,0,"FECHA ",$lo_titulo);

						 $lo_hoja->set_column(4,1,20);
						 $lo_hoja->write(4,1,"No. FACTURA ",$lo_titulo);

						 $lo_hoja->set_column(4,2,50);
						 $lo_hoja->write(4,2,"R.I.F. ",$lo_titulo);

						 $lo_hoja->set_column(4,3,60);
						 $lo_hoja->write(4,3,"NOMBRE CLIENTE ",$lo_titulo);

						  $lo_hoja->set_column(4,4,20);
						 $lo_hoja->write(4,4,"CAJERO ",$lo_titulo);

						 $lo_hoja->set_column(4,5,20);
						 $lo_hoja->write(4,5,"SUBTOTAL ",$lo_titulo);

						 $lo_hoja->set_column(4,6,20);
						 $lo_hoja->write(4,6,"IVA ",$lo_titulo);

						 $lo_hoja->set_column(4,7,20);
						 $lo_hoja->write(4,7,"MONTO ",$lo_titulo);

						 $lo_hoja->write($li_ini, 0 , $ls_fecemi3 ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 1 , strtoupper($la_factura["numfac"][$i+1]) ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 2 , $la_factura["cedcli"][$i+1] ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 3 , strtoupper($la_factura["razcli"][$i+1]) ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 4 , strtoupper($la_factura["codusu"][$i+1]),$lo_dataleft);
						 if($la_factura["numfac"][$i+1+1]==$la_factura["numfac"][$i+1])
                         {

                            if(($la_factura["montotot"][$i+1])>=($la_factura["montotot"][$i+1+1]))
                            {


                           		 if ($la_factura["montopar"][$i+1]<0)
                             		{
                             			$lo_hoja->write($li_ini, 5 ,number_format(($la_factura["monto"][$i+1] - $la_factura["montoret"][$i+1]),2,',','.'),$lo_dataright) ;
										$lo_hoja->write($li_ini, 6 ,  number_format($la_factura["montoret"][$i+1],2,',','.'),$lo_dataright);
 										$lo_hoja->write($li_ini, 7 ,  number_format($la_factura["monto"][$i+1],2,',','.'),$lo_dataright);

							 			 $total=$total+$la_factura["monto"][$i+1];

										$subtotal=$subtotal+($la_factura["monto"][$i+1]-$la_factura["montoret"][$i+1]);


                             		}
									else
									{
										$lo_hoja->write($li_ini, 5 ,  number_format(($la_factura["montotot"][$i+1]- $la_factura["montoret"][$i+1]),2,',','.'),$lo_dataright);
										$lo_hoja->write($li_ini, 6 ,  number_format($la_factura["montoret"][$i+1],2,',','.'),$lo_dataright);
										$lo_hoja->write($li_ini, 7 ,  number_format($la_factura["montotot"][$i+1],2,',','.'),$lo_dataright);

							  			$total=$total+$la_factura["montotot"][$i+1];

										$subtotal=$subtotal+($la_factura["montotot"][$i+1]-$la_factura["montoret"][$i+1]);

									}



                            }
                             else
                             {
								 if ($la_factura["montopar"][$i+1]<0)
                             		{
                             			$lo_hoja->write($li_ini, 5 ,number_format(($la_factura["monto"][$i+1] - $la_factura["montoret"][$i+1]),2,',','.'),$lo_dataright) ;
										$lo_hoja->write($li_ini, 6 ,  number_format($la_factura["montoret"][$i+1],2,',','.'),$lo_dataright);
 										$lo_hoja->write($li_ini, 7 ,  number_format($la_factura["monto"][$i+1],2,',','.'),$lo_dataright);

							 			 $total=$total+$la_factura["monto"][$i+1];

										$subtotal=$subtotal+($la_factura["monto"][$i+1]-$la_factura["montoret"][$i+1]);


                             		}
									else
									{
										$lo_hoja->write($li_ini, 5 ,  number_format(($la_factura["montotot"][$i+1]- $la_factura["montoret"][$i+1]),2,',','.'),$lo_dataright);
										$lo_hoja->write($li_ini, 6 ,  number_format($la_factura["montoret"][$i+1],2,',','.'),$lo_dataright);
										$lo_hoja->write($li_ini, 7 ,  number_format($la_factura["montotot"][$i+1],2,',','.'),$lo_dataright);

							  			$total=$total+$la_factura["montotot"][$i+1];

										$subtotal=$subtotal+($la_factura["montotot"][$i+1]-$la_factura["montoret"][$i+1]);

									}


                             }



                         }
						 else
						 {
						 	 if ($la_factura["montopar"][$i+1]<0)
                             		{
                             			$lo_hoja->write($li_ini, 5 ,number_format(($la_factura["monto"][$i+1] - $la_factura["montoret"][$i+1]),2,',','.'),$lo_dataright) ;
										$lo_hoja->write($li_ini, 6 ,  number_format($la_factura["montoret"][$i+1],2,',','.'),$lo_dataright);
 										$lo_hoja->write($li_ini, 7 ,  number_format($la_factura["monto"][$i+1],2,',','.'),$lo_dataright);

							 			 $total=$total+$la_factura["monto"][$i+1];

										$subtotal=$subtotal+($la_factura["monto"][$i+1]-$la_factura["montoret"][$i+1]);


                             		}
									else
									{
										$lo_hoja->write($li_ini, 5 ,  number_format(($la_factura["montotot"][$i+1]- $la_factura["montoret"][$i+1]),2,',','.'),$lo_dataright);
										$lo_hoja->write($li_ini, 6 ,  number_format($la_factura["montoret"][$i+1],2,',','.'),$lo_dataright);
										$lo_hoja->write($li_ini, 7 ,  number_format($la_factura["montotot"][$i+1],2,',','.'),$lo_dataright);

							  			$total=$total+$la_factura["montotot"][$i+1];

										$subtotal=$subtotal+($la_factura["montotot"][$i+1]-$la_factura["montoret"][$i+1]);

									}


						 }




						 $iva=$iva+$la_factura["montoret"][$i+1];


						 $lo_hoja->set_column(4,8,30);
						 if ($la_factura["estfaccon"][$i+1]=='E')
						 {
							$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
							$lo_hoja->write($li_ini, 8 , " EMITIDA ",$lo_dataleft);
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
								 		$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
										$lo_hoja->write($li_ini, 8 , " CANCELADA ",$lo_dataleft);

								 	}
									else
									{
									 	$totalcob=$totalcob+$la_factura["montotot"][$i+1];
									 	$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
										$lo_hoja->write($li_ini, 8 , " POR COBRAR ",$lo_dataleft);

									}
							 }
							 else
							 {
							 	if($la_monto==0)
								{
							 		$totalcan=$totalcan+$la_factura["montotot"][$i+1];
							 		$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
									$lo_hoja->write($li_ini, 8 , " CANCELADA ",$lo_dataleft);

							 	}

							 }
						 }
						 else if ($la_factura["estfaccon"][$i+1]=='N')
						 {
							$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
							$lo_hoja->write($li_ini, 8 , " POR COBRAR ",$lo_dataleft);
							$totalcob=$totalcob+$la_factura["montotot"][$i+1];
						 }
						 else if ($la_factura["estfaccon"][$i+1]=='P')
						 {
							 $lo_hoja->write(4,8,"STATUS ",$lo_titulo);
							 $lo_hoja->write($li_ini, 8 , " POR COBRAR ",$lo_dataleft);
							 $totalcob=$totalcob+$la_factura["montotot"][$i+1];
						 }
						 else if ($la_factura["estfaccon"][$i+1]=='A')
						 {
							$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
							$lo_hoja->write($li_ini, 8 , " ANULADA ",$lo_dataleft);

							 $totalanu=$totalanu+$la_factura["montotot"][$i+1];

						 }


					}

					$li_ini++;

				 }

/************************  FACTURAS A CREDITO ****************/
				//$rows2 =$io_sql->num_rows($rs_datauni2);


				for($j=0;$j<$rows2;$j++)
				{
					$ls_fecemi3="".substr($la_facturacred["fecemi"][$j+1],8,2)."/".substr( $la_facturacred["fecemi"][$j+1],5,2)."/".substr( $la_facturacred["fecemi"][$j+1],0,4)."";
					if($ls_opcion=="resumen")
					{


						 $lo_hoja->write($li_ini, 0 , $ls_fecemi3 ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 1 , strtoupper($la_facturacred["numfac"][$j+1]) ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 2 , $la_facturacred["cedcli"][$j+1] ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 3 , strtoupper($la_facturacred["razcli"][$j+1]) ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 4 , strtoupper($la_facturacred["codusu"][$j+1]),$lo_dataleft);
						 $lo_hoja->write($li_ini, 5 ,  number_format($la_facturacred["montotot"][$j+1],2,',','.') ,$lo_dataright);


						  $total=$total+$la_facturacred["montotot"][$j+1];



							 $lo_hoja->write($li_ini, 6 , " POR COBRAR ",$lo_dataleft);
							 $totalcob=$totalcob+$la_facturacred["montotot"][$j+1];


					}
					elseif($ls_opcion=="detalles")
					{


						 $lo_hoja->write($li_ini, 0 , $ls_fecemi3 ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 1 , strtoupper($la_facturacred["numfac"][$j+1]) ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 2 , $la_facturacred["cedcli"][$j+1] ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 3 , strtoupper($la_facturacred["razcli"][$j+1]) ,$lo_dataleft);
						 $lo_hoja->write($li_ini, 4 , strtoupper($la_facturacred["codusu"][$j+1]),$lo_dataleft);
						 $lo_hoja->write($li_ini, 5 ,  number_format(($la_facturacred["montotot"][$j+1]- $la_facturacred["montoret"][$j+1]),2,',','.'),$lo_dataright);
						 $lo_hoja->write($li_ini, 6 ,  number_format($la_facturacred["montoret"][$j+1],2,',','.'),$lo_dataright);
						 $lo_hoja->write($li_ini, 7 ,  number_format($la_facturacred["montotot"][$j+1],2,',','.'),$lo_dataright);


						 $iva=$iva+$la_facturacred["montoret"][$j+1];
						 $total=$total+$la_facturacred["montotot"][$j+1];
  						 $subtotal=$subtotal+($la_facturacred["montotot"][$j+1]-$la_facturacred["montoret"][$j+1]);

						 $lo_hoja->set_column(4,8,30);

						if ($la_facturacred["estfaccon"][$j+1]=='E')
						 {
							$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
							$lo_hoja->write($li_ini, 8 , " EMITIDA ",$lo_dataleft);
							$totalemi=$totalemi+$la_facturacred["montotot"][$j+1];
						 }
						 else if ($la_facturacred["estfaccon"][$j+1]=='C')
						 {

						 	$la_monto = $reporte->uf_calcular_montocobradofac(strtoupper($la_facturacred["numfac"][$j+1]),$la_facturacred["montotot"][$j+1]);
							 if($la_monto==-1)
							 {

							 	if($la_facturacred["montopar"][$j+1]<0)
								 	{
								 		$totalcan=$totalcan+$la_facturacred["montotot"][$j+1];
								 		$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
										$lo_hoja->write($li_ini, 8 , " CANCELADA ",$lo_dataleft);

								 	}
									else
									{
									 	$totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
									 	$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
										$lo_hoja->write($li_ini, 8 , " POR COBRAR ",$lo_dataleft);

									}
							 }
							 else
							 {
							 	if($la_monto==0)
								{
							 		$totalcan=$totalcan+$la_facturacred["montotot"][$j+1];
							 		$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
									$lo_hoja->write($li_ini, 8 , " CANCELADA ",$lo_dataleft);

							 	}

							 }
						 }
						 else if ($la_facturacred["estfaccon"][$j+1]=='N')
						 {
							$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
							$lo_hoja->write($li_ini, 8 , " POR COBRAR ",$lo_dataleft);
							$totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
						 }
						 else if ($la_facturacred["estfaccon"][$j+1]=='P')
						 {
							 $lo_hoja->write(4,8,"STATUS ",$lo_titulo);
							 $lo_hoja->write($li_ini, 8 , " POR COBRAR ",$lo_dataleft);
							 $totalcob=$totalcob+$la_facturacred["montotot"][$j+1];
						 }
						 else if ($la_facturacred["estfaccon"][$j+1]=='A')
						 {
							$lo_hoja->write(4,8,"STATUS ",$lo_titulo);
							$lo_hoja->write($li_ini, 8 , " ANULADA ",$lo_dataleft);

							 $totalanu=$totalanu+$la_facturacred["montotot"][$j+1];

						 }

							$lo_hoja->write($li_ini, 8 , " POR COBRAR ",$lo_dataleft);
//							$totalcob=$totalcob+$la_facturacred["montotot"][$j+1];



					}

					$li_ini++;

				}




				 $subtotal=number_format($subtotal,2,',','.');
				 $iva=number_format($iva,2,',','.');
				 $total=number_format($total,2,',','.');

$li_tot=$li_ini+3;
				if($ls_opcion=="resumen")
				{

					$lo_hoja->write($li_ini, 4,"TOTAL GENERAL Bs. ",$lo_titulo);
					$lo_hoja->write($li_ini, 5 , $total , $lo_dataright);

				}


				elseif($ls_opcion=="detalles")
				{

				//$lo_hoja->write(1, 1 , $ls_opcion."  --  ".$li_ini , $lo_dataright);
					$lo_hoja->set_column(4,4,20);
					$lo_hoja->write($li_ini, 4,"TOTAL GENERAL Bs. ",$lo_titulo);
					$lo_hoja->set_column(4,5,20);
					$lo_hoja->write($li_ini, 5 , $subtotal , $lo_dataright);
					$lo_hoja->set_column(4,6,20);
					$lo_hoja->write($li_ini, 6 , $iva , $lo_dataright);
					$lo_hoja->set_column(4,7,20);
					$lo_hoja->write($li_ini, 7 , $total , $lo_dataright);



				}
}

	//$io_pdf->add_line(5);
		$totalcob=number_format($totalcob,2,',','.');
		$totalcan=number_format($totalcan,2,',','.');
		$totalemi=number_format($totalemi,2,',','.');
   	    $totalanu=number_format($totalanu,2,',','.');

		$li_resumen=$li_ini+6;



			$lo_hoja->write($li_resumen, 1,"RESUMEN FACTURAS ",$lo_titulo);
			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 , "Total Facturas Por Cobrar " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $totalcob , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"Total Facturas Canceladas " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $totalcan , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"Total Facturas Anuladas " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $totalanu , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"Total Facturas Emitidas " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $total , $lo_dataright);


						for($li=$li_total;$li>1;$li--)
				{
					$li_ant=$ia_niveles_scg[$li-1];
					$li_act=$ia_niveles_scg[$li];
					$li_fila=$li_act-$li_ant;
					$li_len=strlen($ls_cuenta);
					$li_totfil=$li_totfil+$li_fila;
					$li_inicio=$li_len-$li_totfil;

				}

			$li_n++;
			$li_fila=$ia_niveles_scg[1]+1;
			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"listado_factura.xls\"");
			header("Content-Disposition: inline; filename=\"listado_factura.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
	}

?>