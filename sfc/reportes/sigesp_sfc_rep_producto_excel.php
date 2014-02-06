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
		$lo_archivo = tempnam("/home/production/tmp", "listado_productosDetalle.xls");
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


			$ls_fecemi=$_GET["fecemi"];
			$ls_fecemi2=$_GET["fecemi2"];
			$ls_opcion=$_GET["opcion"];



		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_sql2="Select dentie from sfc_tienda where codtie='".$ls_codtie."' ";
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
					//print_r ($la_agrotienda);

						//print "Paso".$totrowt;
					for($t=0;$t<=$totrowt;$t++)
					{


						$ls_dentie=$io_datastore4->getValue("dentie",$t);
					}
				}
			}


		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "LISTADO DE PRODUCTOS VENDIDOS(DETALLES) ".$ls_dentie;
		$ls_nombemp = "CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A";

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
			$ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);
	//$lo_hoja->write(0,0, $ls_sql,$lo_encabezado);
$rs_datauni=$io_sql2->select($ls_sql);
if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay Nada que Reportar");
}
else
{
   $la_producto=$io_sql2->obtener_datos($rs_datauni);
	if ($la_producto)
	{
	$li_cuotas=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;

	if ($ls_fecemi<>"%/%")
	{
	  $ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
	  $ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";
	  $lo_hoja->write(3,4,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2,$lo_dalaleft);

	}


	 $lo_hoja->set_column(4,0,10);
	 $lo_hoja->write(4,0,"FECHA ",$lo_titulo);

	 $lo_hoja->set_column(4,1,20);
	 $lo_hoja->write(4,1,"No. FACTURA ",$lo_titulo);

	 $lo_hoja->set_column(4,2,50);
	 $lo_hoja->write(4,2,"R.I.F. ",$lo_titulo);

	 $lo_hoja->set_column(4,3,60);
	 $lo_hoja->write(4,3,"RAZÓN SOCIAL ",$lo_titulo);

	  $lo_hoja->set_column(4,4,20);
	 $lo_hoja->write(4,4,"PRODUCTO ",$lo_titulo);

	 $lo_hoja->set_column(4,5,20);
	 $lo_hoja->write(4,5,"PRECIO*UNIDAD ",$lo_titulo);

	 $lo_hoja->set_column(4,6,20);
	 $lo_hoja->write(4,6,"CANTIDAD ",$lo_titulo);

	 $lo_hoja->set_column(4,7,20);
	 $lo_hoja->write(4,7,"SUB-TOTAL Bs. ",$lo_titulo);


	$li_ini=5;
	$la_total=0;
	for($i=0;$i<$li_cuotas;$i++)
	{
		$ls_fecha2="".substr( $la_producto["fecemi"][$i+1],8,2)."/".substr( $la_producto["fecemi"][$i+1],5,2)."/".substr( $la_producto["fecemi"][$i+1],0,4)."";


		 $lo_hoja->write($li_ini, 0 , $ls_fecha2 ,$lo_dataleft);
		 $lo_hoja->write($li_ini, 1 ,  strtoupper($la_producto["numfact"][$i+1]) ,$lo_dataleft);
		 $lo_hoja->write($li_ini, 2 , $la_producto["cedcli"][$i+1] ,$lo_dataleft);
		 $lo_hoja->write($li_ini, 3 , strtoupper($la_producto["razcli"][$i+1]) ,$lo_dataleft);
		 $lo_hoja->write($li_ini, 4 , strtoupper($la_producto["denpro"][$i+1]),$lo_dataleft);
		 $lo_hoja->write($li_ini, 5 , number_format($la_producto["prepro"][$i+1],2, ',', '.') ,$lo_dataright);
		 $lo_hoja->write($li_ini, 6 , number_format($la_producto["canpro"][$i+1],2, ',', '.') ,$lo_dataright);




	 $ls_preuni=$la_producto["prepro"][$i+1];
	 $ls_cantidad=$la_producto["canpro"][$i+1];

	 $ls_subtotal=((($ls_cantidad*$ls_preuni)*$la_producto["porimp"][$i+1]/100)+($ls_cantidad*$ls_preuni));
	 $lo_hoja->write($li_ini, 7 , number_format($ls_subtotal,2, ',', '.') ,$lo_dataright);


	 $la_total= $la_total+((($ls_cantidad*$ls_preuni)*$la_producto["porimp"][$i+1]/100)+($ls_cantidad*$ls_preuni));


	 	$li_ini++;


	 }

		 $la_total2=number_format($la_total,2,',','.');


	$lo_hoja->write($li_ini, 6 , "TOTAL Bs. " ,$lo_titulo);
	$lo_hoja->write($li_ini, 7 , $la_total2 ,$lo_titulo);


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
			header("Content-Type: application/x-msexcel; name=\"listado_productosDetalle.xls\"");
			header("Content-Disposition: inline; filename=\"listado_productosDetalle.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
		}



}
?>