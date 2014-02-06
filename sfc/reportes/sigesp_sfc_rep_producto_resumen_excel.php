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


	   if (array_key_exists("fechaemi",$_POST))
	   {
	     $ls_fechadesde=$_POST["fechaemi"];
	   }
    else
	   {
	     $ls_fechadesde=$_GET["fechaemi"];
	   }
	   if (array_key_exists("fechaemi2",$_POST))
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

function redondeado ($numero, $decimales)
{
   $factor = pow(10, $decimales);
   return (round($numero*$factor)/$factor);
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
		$lo_archivo = tempnam("/home/production/tmp", "listado_productosResumen.xls");
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

			$ls_fecha=date('d/m/Y');

            $ls_sql=$_GET['sql'];

//print $ls_sql;
			$ls_fecemi=$_GET["fecemi"];
			$ls_fecemi2=$_GET["fecemi2"];
			$ls_opcion=$_GET["opcion"];



		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_sql2="Select dentie from sfc_tienda where codtiend='".$ls_codtie."' ";
		$rs_data=$io_sql->select($ls_sql2);
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

					for($t=0;$t<=$totrowt;$t++)
					{
						$ls_dentie=$io_datastore4->getValue("dentie",$t);
					}
				}
			}


		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "LISTADO DE PRODUCTOS VENDIDOS(RESUMEN) ".$ls_dentie;
		$ls_nombemp = "CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A";


			$lo_encabezado= &$lo_libro->addformat();
			$lo_encabezado->set_bold();
			$lo_encabezado->set_color('white');
			$lo_encabezado->set_fg_color('silver');
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
			$lo_titulo->set_font("Verdana");
			$lo_titulo->set_fg_color('green');
			$lo_titulo->set_border_color('white');
			$lo_titulo->set_top(10);
			$lo_titulo->set_bottom(6);
			$lo_titulo->set_right(6);
			$lo_titulo->set_left(6);
			$lo_titulo->set_align('center');
			$lo_titulo->set_size('12');


			$lo_total= &$lo_libro->addformat();
			$lo_total->set_bold();
			$lo_total->set_font("Verdana");
			$lo_total->set_align('right');
			$lo_total->set_size('9');

			$lo_resumen= &$lo_libro->addformat();
			$lo_resumen->set_bold();
			$lo_resumen->set_font("Verdana");
			$lo_resumen->set_fg_color('green');
			$lo_resumen->set_border_color('black');
			$lo_resumen->set_top(6);
			$lo_resumen->set_bottom(6);
			$lo_resumen->set_right(6);
			$lo_resumen->set_left(6);
			$lo_resumen->set_align('right');
			$lo_resumen->set_size('12');

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


			$lo_hoja->set_column(0,0,15);
			$lo_hoja->set_column(1,1,40);
			$lo_hoja->set_column(2,4,20);
			/*$lo_hoja->set_column(3,3,20);
			$lo_hoja->set_column(4,4,20);*/


			$lo_hoja->merge_cells(0, 0, 0, 7,$lo_encabezado);
			$lo_hoja->write(0,0,$ls_nombemp,$lo_encabezado);
			$lo_hoja->write_blank(0,0,$border2);
			$lo_hoja->write_blank(0,1,$border2);
			$lo_hoja->write_blank(0,2,$border2);
			$lo_hoja->write_blank(0,3,$border2);
			$lo_hoja->write_blank(0,4,$border2);


			$lo_hoja->merge_cells(1, 0, 1, 7,$lo_encabezado);
			$lo_hoja->write(1,0,$ls_dentie,$lo_encabezado);
			$lo_hoja->write_blank(1,0,$border2);
			$lo_hoja->write_blank(1,1,$border2);
			$lo_hoja->write_blank(1,2,$border2);
			$lo_hoja->write_blank(1,3,$border2);
			$lo_hoja->write_blank(1,4,$border2);


			$lo_hoja->merge_cells(2, 0,2, 7,$lo_encabezado);
			$lo_hoja->write(2,0,$ls_titulo,$lo_encabezado);
			$lo_hoja->write_blank(2,0,$border2);
			$lo_hoja->write_blank(2,1,$border2);
			$lo_hoja->write_blank(2,2,$border2);
			$lo_hoja->write_blank(2,3,$border2);
			$lo_hoja->write_blank(2,4,$border2);


			$as_fechadesde = substr($ls_fechadesde,8,2).'/'.substr($ls_fechadesde,5,2).'/'.substr($ls_fechadesde,0,4);
			$as_fechahasta = substr($ls_fechahasta,8,2).'/'.substr($ls_fechahasta,5,2).'/'.substr($ls_fechahasta,0,4);

			//$lo_hoja->write(3,1,"PERIODO ".$as_fechadesde." AL ".$as_fechahasta,$lo_encabezado);
			$lo_hoja->set_column(3,1,15);
			$lo_hoja->write(3,1,"Fecha de Emision: ".$ls_fecha,$lo_dataleft);
			$ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);

$rs_datauni=$io_sql2->select($ls_sql);
if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay Nada que Reportar");
}
else
{
   $li_cuotas =$io_sql2->num_rows($rs_datauni);;
   $la_producto=$io_sql2->obtener_datos($rs_datauni);
	if ($la_producto)
	{


	if ($ls_fecemi<>"%/%")
	{
	  $ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
	  $ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";
	  $lo_hoja->write(3,3,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2,$lo_dalaleft);

	}


	 $lo_hoja->set_column(4,0,10);
	 $lo_hoja->write(4,0,"CODIGO ",$lo_titulo);

	 $lo_hoja->set_column(4,1,20);
	 $lo_hoja->write(4,1,"DESCRIPCION ",$lo_titulo);

	 $lo_hoja->set_column(4,2,50);
	 $lo_hoja->write(4,2,"CANTIDAD ",$lo_titulo);

	 $lo_hoja->set_column(4,3,60);
	 $lo_hoja->write(4,3,"PRECIO ",$lo_titulo);

	 $lo_hoja->set_column(4,4,20);
	 $lo_hoja->write(4,4,"SUB-TOTAL Bs. ",$lo_titulo);


	$li_ini=5;
	$la_total=0;
	for($i=0;$i<$li_cuotas;$i++)
	{

		$ls_fecha2="".substr( $la_producto["fecemi"][$i+1],8,2)."/".substr( $la_producto["fecemi"][$i+1],5,2)."/".substr( $la_producto["fecemi"][$i+1],0,4)."";


		 $lo_hoja->write($li_ini, 0 , strtoupper($la_producto["codpro"][$i+1]) ,$lo_dataright);
		 $lo_hoja->write($li_ini, 1 , strtoupper($la_producto["denpro"][$i+1]),$lo_dataleft);

		 $lo_hoja->write($li_ini, 3 , redondeado($la_producto["prepro"][$i+1],2),$lo_dataright);


		 $li_candev=$la_producto["candev"][$i+1];
		 $li_canpro=$la_producto["canpro"][$i+1];

		 if($li_candev>0)
		  {

				if($li_canpro==$ls_candev)
				{
					$li_cantvent=$la_producto["canpro"][$i+1]-$la_producto["candev"][$i+1];
				 	$li_totalvta=$la_producto["subtotal"][$i+1]+($la_producto["subtotal"][$i+1] *($la_producto["porimp"][$i+1]/100));
				 	$li_totalvtadev=$la_producto["subtotaldev"][$i+1]+($la_producto["subtotaldev"][$i+1] *($la_producto["porimp"][$i+1]/100));

				}
				else
				{
					$li_cantvent=$la_producto["canpro"][$i+1]-$la_producto["candev"][$i+1];
				 	$li_totalvtadev=$la_producto["subtotaldev"][$i+1]+($la_producto["subtotaldev"][$i+1]*($la_producto["porimp"][$i+1]/100));
				 	$li_totalvta=$la_producto["subtotal"][$i+1]+($la_producto["subtotal"][$i+1] *($la_producto["porimp"][$i+1]/100))-$li_totalvtadev;

					$la_total= $la_total+$li_totalvta;
				}
		  	}
		  	else
		  	{
					$li_cantvent=$la_producto["canpro"][$i+1];
			 		$li_totalvta=$la_producto["subtotal"][$i+1]+($la_producto["subtotal"][$i+1] *($la_producto["porimp"][$i+1]/100));

					$la_total= $la_total+$li_totalvta;
		 	}

	 $lo_hoja->write($li_ini, 2 , number_format(redondeado($li_cantvent,2),2,',','.') ,$lo_dataright);
	 $lo_hoja->write($li_ini, 4 , redondeado($li_totalvta,2),$lo_dataright);

	$li_ini++;





	 }

		 $la_total2=number_format($la_total,2,',','.');


	$lo_hoja->write($li_ini, 3 , "TOTAL VENTAS Bs. " ,$lo_titulo);
	$lo_hoja->write($li_ini, 4 , $la_total2 ,$lo_titulo);


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
			header("Content-Type: application/x-msexcel; name=\"listado_productosResumen.xls\"");
			header("Content-Disposition: inline; filename=\"listado_productosResumen.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
		}



}
?>