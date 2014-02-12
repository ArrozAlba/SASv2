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
		$lo_archivo = tempnam("/tmp", "COLOCACION_VENTAS_LINEA.xls");

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("sigesp_sfc_c_reportes.php");
		$reporte = new sigesp_sfc_c_reportes('LETTER','landscape','REPORTE 1');
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_datastore.php");

		$io_in    = new sigesp_include();
		$io_connect      = $io_in->uf_conectar();
		require_once("sigesp_sfc_c_formatoscva.php");
		$io_formato_cva = new sigesp_sfc_c_formatoscva($io_connect);

		$io_sql   = new class_sql($io_connect);
		$io_sql_bd=new class_sql($io_connect);

		$io_datastore4= new class_datastore();
		$io_datastore5= new class_datastore();


		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();

		$ia_niveles_scg[0]="";
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//---------------------------------------------------------------------------------------------------------------------------
	//Par�metros para Filtar el Reporte
		$lb_valido = $io_formato_cva->uf_select_colocacionlineas($as_fechadesde,$as_fechahasta);
		if ($lb_valido)
		{
			$lo_libro = &new writeexcel_workbookbig($lo_archivo);
			$lo_hoja = &$lo_libro->addworksheet();

		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "COLOCACIONES SEMANALES POR LINEA DE PRODUCTOS";
		$ls_nombemp = "CVA-ECISA";
		$ls_codtie=$_SESSION["ls_codtienda"];
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
					$io_datastore5->data=$la_agrotienda;
					$totrowt=$io_datastore5->getRowCount("dentie");
					//print_r ($la_agrotienda);

						//print "Paso".$totrowt;
					for($t=0;$t<=$totrowt;$t++)
					{


						$ls_dentie=$io_datastore5->getValue("dentie",$t);
					}
				}
			}


		//print $ls_titulo;
		$ls_fecha=date('d/m/Y  h:m:s');
				# Create another border format. Note you could use copy() here.
			$border2 =&$lo_libro->addformat();
			$border2->set_color('white');
			$border2->set_bold();
			$border2->set_pattern(0x1);
			$border2->set_fg_color('gray');
			$border2->set_border_color('black');
			$border2->set_top(6);
			$border2->set_bottom(6);
			$border2->set_right(6);
			$border2->set_left(6);
			$border2->set_align('center');
			$border2->set_align('vcenter');
			$border2->set_merge(); # This is the key feature


			$lo_encabezado= &$lo_libro->addformat();
			$lo_encabezado->set_bold();
			$lo_encabezado->set_color('white');
			$lo_encabezado->set_fg_color('gray');
			$lo_encabezado->set_border_color('white');
			$lo_encabezado->set_top(6);
			$lo_encabezado->set_bottom(6);
			$lo_encabezado->set_right(6);
			$lo_encabezado->set_left(6);
			$lo_encabezado->set_font("Verdana");
			$lo_encabezado->set_align('center');
			$lo_encabezado->set_size('12');
			$lo_encabezado->set_merge();


			$lo_titulo= &$lo_libro->addformat();
			$lo_titulo->set_bold();
			$lo_titulo->set_fg_color('yellow');
			$lo_titulo->set_border_color('black');
			$lo_titulo->set_top(6);
			$lo_titulo->set_bottom(6);
			$lo_titulo->set_right(6);
			$lo_titulo->set_left(6);
			$lo_titulo->set_font("Verdana");
			$lo_titulo->set_align('center');
			$lo_titulo->set_size('10');

			$lo_total= &$lo_libro->addformat(array(num_format => '#,##0.00'));
			$lo_total->set_bold();
			$lo_total->set_pattern(0x1);
			$lo_total->set_font("Verdana");
			$lo_total->set_fg_color('yellow');
			$lo_total->set_top(6);
			$lo_total->set_bottom(6);
			$lo_total->set_right(6);
			$lo_total->set_left(6);
			$lo_total->set_border_color('black');
			$lo_total->set_align('center');
			$lo_total->set_size('12');
			$lo_total->set_merge();

			$lo_parte= &$lo_libro->addformat();
			$lo_parte->set_bold();
			$lo_parte->set_font("Verdana");
			$lo_parte->set_align('left');
			$lo_parte->set_size('9');


			$lo_datacenter= &$lo_libro->addformat();
			$lo_datacenter->set_font("Verdana");
			$lo_datacenter->set_align('center');
			$lo_datacenter->set_size('9');

			$lo_dataleft= &$lo_libro->addformat();
			$lo_dataleft->set_text_wrap();
			$lo_dataleft->set_font("Verdana");
			$lo_dataleft->set_align('left');
			$lo_dataleft->set_size('9');

			$lo_dataright= &$lo_libro->addformat();
			$lo_dataright->set_bold();
			$lo_dataright->set_font("Verdana");
			$lo_dataright->set_align('right');
			$lo_dataright->set_size('9');

			$lo_hoja->merge_cells(0, 0, 0, 4,$lo_titulo);
			$lo_hoja->write(0, 0, $ls_titulo."  ".$ls_dentie, $lo_encabezado);
			$lo_hoja->write_blank(0,1,$border2);
			$lo_hoja->write_blank(0,2,$border2);
			$lo_hoja->write_blank(0,3,$border2);
			$lo_hoja->write_blank(0,4,$border2);


			$lo_hoja->merge_cells(1, 0, 2, 0,$lo_titulo);
			$lo_hoja->set_column(0,0, 20);
			$lo_hoja->write(1, 0, "LINEA DE PRODUCTO",$lo_titulo);
			$lo_hoja->write_blank(2,0,$border2);

			$lo_hoja->merge_cells(1, 1, 2, 1,$lo_titulo);
			$lo_hoja->set_column(1,1,22);
			$lo_hoja->write(1, 1, "SUBLINEA DE PRODUCTO",$lo_titulo);
			$lo_hoja->write_blank(2,1,$border2);

			$lo_hoja->merge_cells(1, 2, 2, 2,$lo_titulo);
			$lo_hoja->set_column(1,2,25);
			$lo_hoja->write(1, 2, "PRODUCTO",$lo_titulo);
			$lo_hoja->write_blank(2,2,$border2);

			$lo_hoja->merge_cells(1, 3, 2,3,$lo_titulo);
			$lo_hoja->set_column(1,3,8);
			$lo_hoja->write(1, 3, "UNIDAD DE MEDIDA",$lo_titulo);
			$lo_hoja->write_blank(2,3,$border2);

			$lo_hoja->set_column(1,4,50);
			$lo_hoja->write(1, 4, "TIENDA",$lo_titulo);
			$lo_hoja->set_column(2,4,50);
			$lo_hoja->write(2, 4, $ls_dentie,$lo_titulo);



			$li_row=2;



/************************************** for en tabla  *******************************************************************/


				$tota=$io_formato_cva->io_datastore1->getRowCount("dencla");
				$totalpro=$io_formato_cva->io_datastore1->getRowCount("denpro");
				$li_inicio_cla=3;
				$li_fin_cla=0;
				$li_inicio_sub=3;
				$li_inicio_pro=3;
				$li_fin_sub=0;
				$li_mitad=0;
				$li_total_general=0;
				$li_total_ventas=0;
				for($a=1;$a<=$tota;$a++)
				{

					if ($a<$tota)
				{
					$ls_dencla_sig=  $io_formato_cva->io_datastore1->data["dencla"][$a+1];
					$ls_densub_sig=  $io_formato_cva->io_datastore1->data["den_sub"][$a+1];
				}
										$ls_dencla= $io_formato_cva->io_datastore1->getValue("dencla",$a);
										$ls_densub=$io_formato_cva->io_datastore1->getValue("den_sub",$a);
										$ls_denpro=$io_formato_cva->io_datastore1->getValue("denpro",$a);
										$ls_denunimed=$io_formato_cva->io_datastore1->getValue("denunimed",$a);

										$ls_cant=$io_formato_cva->io_datastore1->getValue("cantidad",$a);

						/////////////////Clasificación//////////////////
				if ($ls_dencla==$ls_dencla_sig)
				{
					$li_suma_cla++;
					if ($a==$li_totrow)
					{
						$li_tot_cla=$li_tot_cla+$li_suma_cla;
						if ($li_inicio_cla==3)
						{
							$li_tot_cla=$li_tot_cla+2;
						}
						$lo_hoja->merge_cells($li_inicio_cla,0,$li_tot_cla,0);
						$lo_hoja->set_column(0,0, 23);
						$lo_hoja->write($li_inicio_cla, 0,$ls_dencla,$lo_parte);
						$li_inicio_cla=$li_tot_cla+1;
						$li_suma_cla=0;

					}
				}
				else
				{
					$li_suma_cla++;
					$li_tot_cla=$li_tot_cla+$li_suma_cla;
					if ($li_inicio_cla==3)
					{
						$li_tot_cla=$li_tot_cla+2;
					}
					$lo_hoja->merge_cells($li_inicio_cla,0,$li_tot_cla,0);
					$lo_hoja->set_column(0,0, 23);
					$lo_hoja->write($li_inicio_cla, 0,$ls_dencla,$lo_parte);
					$li_inicio_cla=$li_tot_cla+1;
					$li_suma_cla=0;
				}
				/////////////////////////////////////////////////////

				//////////////////SubClsificación///////////////////
				if ($ls_densub==$ls_densub_sig)
				{
					$li_suma++;
					if ($a==$li_totrow)
					{
						$li_tot=$li_tot+$li_suma;
						if ($li_inicio_sub==3)
						{
							$li_tot=$li_tot+2;
						}
						$lo_hoja->merge_cells($li_inicio_sub,1,$li_tot,1);
						$lo_hoja->set_column(1,1, 32);
						$lo_hoja->write($li_inicio_sub, 1,$ls_densub,$lo_parte);
						$li_inicio_sub=$li_tot+1;
						$li_suma=0;

					}
				}
				else
				{
					$li_suma++;
					$li_tot=$li_tot+$li_suma;
					if ($li_inicio_sub==3)
					{
						$li_tot=$li_tot+2;
					}
					$lo_hoja->merge_cells($li_inicio_sub,1,$li_tot,1);
					$lo_hoja->set_column(1,1, 32);
					$lo_hoja->write($li_inicio_sub, 1,$ls_densub,$lo_parte);
					$li_inicio_sub=$li_tot+1;
					$li_suma=0;
				}
				///////////////////////////////////////////////////////

				/****************************Producto**************************/
				$lo_hoja->set_column(2,2,35);
				$lo_hoja->write($li_inicio_pro, 2,$ls_denpro,$lo_dataleft);
				///////////////////////////////////////////////////////

				/*********************** Unidad de Medida ********************/
				$lo_hoja->set_column(3,3,20);
				$lo_hoja->write($li_inicio_pro, 3,$ls_denunimed,$lo_dataleft);
				///////////////////////////////////////////////////////

				/******************* CANTIDAD *****************************/
				$lo_hoja->set_column(4,4,18);
				$lo_hoja->write($li_inicio_pro, 4,$ls_cant,$lo_dataright);
				///////////////////////////////////////////////////////

				$li_totcant=$li_totcant+$ls_cant;
				$li_inicio_pro++;
				$li_row++;


				}

/********************* TOTAL  ***************************************/
				$lo_hoja->merge_cells($li_row+1, 0, 0, 3,$lo_titulo);
				$lo_hoja->write($li_row+1, 0, " TOTAL ", $lo_encabezado);
				$lo_hoja->write_blank($li_row+1,1,$border2);
				$lo_hoja->write_blank($li_row+1,2,$border2);
				$lo_hoja->write_blank($li_row+1,3,$border2);

				$lo_hoja->write($li_row+1, 4,$li_totcant,$lo_total);


				///////////////////////////////////////////////////////



				}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');");
				print(" close();");
				print("</script>");
			}


			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"COLOCACION_VENTAS_LINEA.xls\"");
			header("Content-Disposition: inline; filename=\"COLOCACION_VENTAS_LINEA.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");








?>