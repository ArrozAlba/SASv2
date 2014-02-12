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
		require_once ("../../shared/writeexcel/class.writeexcel_workbook.inc.php");
		$lo_archivo = tempnam("/tmp", "VENTAS_X_LINEAS.xls");		
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
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();
		$ia_niveles_scg[0]="";
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//---------------------------------------------------------------------------------------------------------------------------
	//Par�metros para Filtar el Reporte

		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "VENTAS POR LINEAS";
		$ls_nombemp = "CVA-ECISA";
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_dentie=$_SESSION["ls_nomtienda"];
		//print $ls_titulo;
		$ls_fecha=date('d/m/Y  h:m:s');		
		$lb_valido = $io_formato_cva->uf_select_ventas_x_lineas($as_fechadesde,$as_fechahasta);
		if($lb_valido==false) // Existe alg�n error � no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');");
			print(" close();");
			print("</script>");
		}
		else // Imprimimos el reporte
		{
			$lo_libro = &new writeexcel_workbook($lo_archivo);
			$lo_hoja = &$lo_libro->addworksheet();			
			
			# Create a border format
			$lo_encabezado= &$lo_libro->addformat();
			$lo_encabezado->set_color('white');
			$lo_encabezado->set_bold();
			$lo_encabezado->set_size(12);
			$lo_encabezado->set_font("Verdana");
			$lo_encabezado->set_fg_color('gray');
			$lo_encabezado->set_border_color('black');
			$lo_encabezado->set_top(6);
			$lo_encabezado->set_bottom(6);
			$lo_encabezado->set_right(6);
			$lo_encabezado->set_left(6);
			$lo_encabezado->set_align('left');
			$lo_encabezado->set_merge(); # This is the key feature
			
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
			$lo_titulo->set_size('9');	
			
			$lo_titulo2= &$lo_libro->addformat(array(num_format => '#,##0.00'));
			$lo_titulo2->set_bold();
			$lo_titulo2->set_fg_color('yellow');
			$lo_titulo2->set_border_color('black');
			$lo_titulo2->set_top(6);
			$lo_titulo2->set_bottom(6);
			$lo_titulo2->set_right(6);
			$lo_titulo2->set_left(6);
			$lo_titulo2->set_font("Verdana");
			$lo_titulo2->set_align('right');
			$lo_titulo2->set_size('9');	
				
			$lo_dataleft1= &$lo_libro->addformat();							
			$lo_dataleft1->set_font("Verdana");
			$lo_dataleft1->set_align('left');
			$lo_dataleft1->set_size('12');
			
			$lo_dataleft= &$lo_libro->addformat();							
			$lo_dataleft->set_font("Verdana");
			$lo_dataleft->set_align('left');
			$lo_dataleft->set_size('10');

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
		
			
		
			$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
			$lo_dataright->set_font("Verdana");
			$lo_dataright->set_bold();
			$lo_dataright->set_align('right');
			$lo_dataright->set_size('9');		
			
			$lo_hoja->merge_cells(0, 0, 0, 4,$lo_titulo);					
			$lo_hoja->write(0, 0, "VENTAS SEMANALES POR LINEA DE PRODUCTOS Y TIENDAS A NIVEL NACIONAL", $lo_encabezado);			
			$lo_hoja->write_blank(0,1,$border2);
			$lo_hoja->write_blank(0,2,$border2);
			$lo_hoja->write_blank(0,3,$border2);
			$lo_hoja->write_blank(0,4,$border2);	
					
			$lo_hoja->merge_cells(1, 0, 2, 0,$lo_titulo);	
			$lo_hoja->set_column(0,0, 50);			
			$lo_hoja->write(1, 0, "LINEA DE PRODUCTO",$lo_titulo);
			$lo_hoja->write_blank(2,0,$border2);
			
			$lo_hoja->merge_cells(1, 1, 2, 1,$lo_titulo);
			$lo_hoja->set_column(1,1,25);
			$lo_hoja->write(1, 1, "SUBLINEA DE PRODUCTO",$lo_titulo);
			$lo_hoja->write_blank(2,1,$border2);

			$lo_hoja->merge_cells(1, 2, 2, 2,$lo_titulo);
			$lo_hoja->set_column(1,2,70);
			$lo_hoja->write(1, 2, "PRODUCTO",$lo_titulo);
			$lo_hoja->write_blank(2,2,$border2);

			$lo_hoja->merge_cells(1, 3, 2,3,$lo_titulo);
			$lo_hoja->set_column(1,3,15);
			$lo_hoja->write(1, 3, "UNIDAD DE MEDIDA",$lo_titulo);
			$lo_hoja->write_blank(2,3,$border2);
			
			$lo_hoja->set_column(1,4,50);
			$lo_hoja->write(1, 4, "TIENDAS",$lo_titulo);
			$lo_hoja->set_column(2,4,50);
			$lo_hoja->write(2, 4, $ls_dentie,$lo_titulo);				
			
			$li_totrow=$io_formato_cva->io_datastore_ventas->getRowCount("dencla");				
			$li_inicio_cla=3;
			$li_fin_cla=0;
			$li_inicio_sub=3;
			$li_inicio_pro=3;
			$li_fin_sub=0;			
			$li_mitad=0;
			$li_total_general=0;
			$li_total_ventas=0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{	
				if ($li_i<$li_totrow)
				{						
					$ls_dencla_sig=  $io_formato_cva->io_datastore_ventas->data["dencla"][$li_i+1];
					$ls_densub_sig=  $io_formato_cva->io_datastore_ventas->data["den_sub"][$li_i+1];
				}	
				$ls_dencla=  $io_formato_cva->io_datastore_ventas->data["dencla"][$li_i];			
				$ls_densub=  $io_formato_cva->io_datastore_ventas->data["den_sub"][$li_i];
				$ls_denpro=  $io_formato_cva->io_datastore_ventas->data["denart"][$li_i];
				$ls_denunimed=$io_formato_cva->io_datastore_ventas->data["denunimed"][$li_i];
				$li_total_ventas=  $io_formato_cva->io_datastore_ventas->data["total_ventas"][$li_i];	
				$li_total_general=$li_total_general+$li_total_ventas;
				/////////////////Clasificación//////////////////
				if ($ls_dencla==$ls_dencla_sig)
				{
					$li_suma_cla++;	
					if ($li_i==$li_totrow)
					{
						$li_tot_cla=$li_tot_cla+$li_suma_cla;
						if ($li_inicio_cla==3)
						{
							$li_tot_cla=$li_tot_cla+2;					
						}				
						$lo_hoja->merge_cells($li_inicio_cla,0,$li_tot_cla,0);
						$lo_hoja->set_column(0,0, 50);			
						$lo_hoja->write($li_inicio_cla, 0,$ls_dencla,$lo_dataleft1);
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
					$lo_hoja->set_column(0,0, 50);			
					$lo_hoja->write($li_inicio_cla, 0,$ls_dencla,$lo_dataleft1);
					$li_inicio_cla=$li_tot_cla+1;					
					$li_suma_cla=0;								
				}		
				/////////////////////////////////////////////////////
				
				//////////////////SubClsificación///////////////////
				if ($ls_densub==$ls_densub_sig)
				{
					$li_suma++;
					if ($li_i==$li_totrow)
					{
						$li_tot=$li_tot+$li_suma;
						if ($li_inicio_sub==3)
						{
							$li_tot=$li_tot+2;					
						}				
						$lo_hoja->merge_cells($li_inicio_sub,1,$li_tot,1);
						$lo_hoja->set_column(1,1, 50);			
						$lo_hoja->write($li_inicio_sub, 1,$ls_densub,$lo_dataleft);
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
					$lo_hoja->set_column(1,1, 70);			
					$lo_hoja->write($li_inicio_sub, 1,$ls_densub,$lo_dataleft);
					$li_inicio_sub=$li_tot+1;					
					$li_suma=0;								
				}
				///////////////////////////////////////////////////////
				
				///////////////////////////Producto////////////////////
				$lo_hoja->set_column(2,2,70);
				$lo_hoja->write($li_inicio_pro, 2,$ls_denpro,$lo_dataleft);				
				///////////////////////////////////////////////////////		
				
				///////////////////////////Unidad de Medida////////////////////
				$lo_hoja->set_column(3,3,30);
				$lo_hoja->write($li_inicio_pro, 3,$ls_denunimed,$lo_dataleft);				
				///////////////////////////////////////////////////////	
				
				///////////////////////////Unidad de Medida////////////////////
				$lo_hoja->set_column(4,4,30);
				$lo_hoja->write($li_inicio_pro, 4,$li_total_ventas,$lo_dataright);				
				///////////////////////////////////////////////////////		
						
				$li_inicio_pro++;
		}
		$lo_hoja->merge_cells($li_inicio_pro,0,$li_inicio_pro,3,$lo_titulo);
		$lo_hoja->write($li_inicio_pro, 3,"TOTAL GENERAL",$lo_titulo);	
		$lo_hoja->write_blank($li_inicio_pro,0,$border2);
		$lo_hoja->write_blank($li_inicio_pro,1,$border2);
		$lo_hoja->write_blank($li_inicio_pro,2,$border2);	
		$lo_hoja->write($li_inicio_pro, 4,$li_total_general,$lo_titulo2);	
		//exit();	
			}
			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"VENTAS_X_LINEAS.xls\"");
			header("Content-Disposition: inline; filename=\"VENTAS_X_LINEAS.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");


?>
		
						