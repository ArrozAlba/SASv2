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
	/*if (array_key_exists("hidmes",$_POST))
	   {
	     $li_mes=$_POST["hidmes"];
	   }
    else
	   {
	     $li_mes=$_GET["hidmes"];
	   }
    if (array_key_exists("hidano",$_POST))
	   {
	     $ls_ano=$_POST["hidano"];
	   }
    else
	   {
	     $ls_ano=$_GET["hidano"];
	   }	*/

	   /* if (array_key_exists("desde",$_POST))
	   {
	     $ls_fechadesde=$_POST["desde"];
	   }
    else
	   {
	     $ls_fechadesde=$_GET["desde"];
	   }
	   if (array_key_exists("hasta",$_POST))
	   {
	     $ls_fechahasta=$_POST["hasta"];
	   }
    else
	   {
	     $ls_fechahasta=$_GET["hasta"];
	   }*/

	//-----------------------------------------------------------------------------------------------------------------------------------
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
		$lo_archivo = tempnam("/tmp", "existeclasificacion.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("sigesp_sfc_c_reportes.php");
		$reporte = new sigesp_sfc_c_reportes('LETTER','landscape','REPORTE 1');
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_datastore.php");

		$io_in    = new sigesp_include();
		$con      = $io_in->uf_conectar();
		$io_sql   = new class_sql($con);
		$io_sql2   = new class_sql($con);
		$io_sql3   = new class_sql($con);
		$io_sql5   = new class_sql($con);
		$io_datastore5= new class_datastore();
		$io_datastore4= new class_datastore();
		$io_datastore= new class_datastore();
		$io_datastore2= new class_datastore();
		$io_datastore3= new class_datastore();

		//$io_report= new sigesp_sfc_c_libroventa($con);

		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();
		//require_once("../class_funciones_scg.php");
		//$io_fun_scg=new class_funciones_scg();
		$ia_niveles_scg[0]="";
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//---------------------------------------------------------------------------------------------------------------------------
	//Par�metros para Filtar el Reporte

		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "LISTADO DE EXISTENCIAS DE ARTICULOS POR CLASIFICACION";
		$ls_nombemp = "CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A";
		$ls_codtie=$_SESSION["ls_codtienda"];
		 if ($ls_codtie='0001')
		{
			$ls_dentie="SEDE CENTRAL";

		}
		else
		{
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
		}

		//print $ls_titulo;
		$ls_fecha=date('d/m/Y');



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
			$lo_hoja->set_column(3,3,70);
			$lo_hoja->write(0,3,$ls_nombemp,$lo_encabezado);
			$lo_hoja->write(1,3,$ls_dentie,$lo_encabezado);
			$lo_hoja->write(2,3,$ls_titulo,$lo_encabezado);
			$lo_hoja->write(3,3,'Fecha de Emision:  '.$ls_fecha,$lo_encabezado);




			$lo_hoja->set_column(0,0,40);
			$lo_hoja->write(5, 0, "PRODUCTO",$lo_titulo);
			$lo_hoja->set_column(1,1,40);
			$lo_hoja->write(5, 1, "CODIGO",$lo_titulo);
			$lo_hoja->set_column(2,2,12);
			$lo_hoja->write(5, 2, "EXISTENCIA",$lo_titulo);
			$lo_hoja->set_column(2,2,12);
			$lo_hoja->write(5, 3, "COSTO Bs.",$lo_titulo);
			$lo_hoja->set_column(4,7,18);
			$lo_hoja->write(5, 4, "PRECIO VENTA Bs.",$lo_titulo);
			$lo_hoja->set_column(2,2,12);
			$lo_hoja->write(5, 5, "CLASIFICACION",$lo_titulo);
			$lo_hoja->set_column(2,2,12);
			$lo_hoja->write(5, 6, "SUBCLASIFICACION",$lo_titulo);
			$lo_hoja->set_column(2,2,12);
			$lo_hoja->write(5, 7, "IMPUESTO",$lo_titulo);


			$li_row=5;


		$ls_sql=$_GET["sql"];

$ls_sql=str_replace("\\","",$ls_sql);
$ls_sql=str_replace("/","",$ls_sql);
$ls_longitud=strlen($ls_sql);
$ls_posicion=strpos($ls_sql,";");
$ls_sql=substr($ls_sql,0,$ls_posicion+1);


	$rs_datauni=$io_sql->select($ls_sql);
if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay Registros");
}
else
 {
   $la_producto=$io_sql->obtener_datos($rs_datauni);
	if ($la_producto){
	//$li_cuotas=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;
	$li_cuotas =$io_sql->num_rows($rs_datauni);;
	for($i=0;$i<$li_cuotas;$i++)
	{


	 $ls_existencia=strtoupper($la_producto["existencia"][$i+1]);


	 if ($ls_existencia==0)
	 {

	$ls_codpro=strtoupper($la_producto["codart"][$i+1]);


		$ls_denomicacion_pro= strtoupper($la_producto["denart"][$i+1])."   ".$la_producto["denunimed"][$i+1];
		$ls_cod_pro= strtoupper($la_producto["codart"][$i+1]);
		$ls_existencia=number_format(strtoupper($la_producto["existencia"][$i+1]),2,',','.');
		$ls_ultimo_costo=number_format(strtoupper($la_producto["ultcosart"][$i+1]),2,',','.');
		$ls_precio_venta=number_format(strtoupper($la_producto["preven"][$i+1]),2,',','.');
		$ls_clasificacion=$la_producto["dencla"][$i+1];
		$ls_subclasificacion=$la_producto["den_sub"][$i+1];
		$ls_imp=trim($la_producto["codcar"][$i+1]);


		 if ($ls_imp=="")
		 {

			$ls_impuesto="EXE";

		 }

		else
		 {

			$ls_impuesto="IVA";
		 }

	 }
	 else
	 {
	 $ls_codpro=strtoupper($la_producto["codart"][$i+1]);


		$ls_denomicacion_pro= strtoupper($la_producto["denart"][$i+1])."   ".$la_producto["denunimed"][$i+1];
		$ls_cod_pro= strtoupper($la_producto["codart"][$i+1]);
		$ls_existencia=number_format(strtoupper($la_producto["existencia"][$i+1]),2,',','.');
		$ls_ultimo_costo=number_format(strtoupper($la_producto["ultcosart"][$i+1]),2,',','.');
		$ls_precio_venta=number_format(strtoupper($la_producto["preven"][$i+1]),2,',','.');
		$ls_clasificacion=$la_producto["dencla"][$i+1];
		$ls_subclasificacion=$la_producto["den_sub"][$i+1];
		$ls_imp=trim($la_producto["codcar"][$i+1]);


		 if ($ls_imp=="")
		 {

			$ls_impuesto="EXE";

		 }

		else
		 {

			$ls_impuesto="IVA";
		 }


	 }



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

				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, $ls_denomicacion_pro , $lo_dataleft);
				$lo_hoja->write($li_row, 1, $ls_cod_pro, $lo_dataright);
				$lo_hoja->write($li_row, 2, $ls_existencia, $lo_dataright);
				$lo_hoja->write($li_row, 3, $ls_ultimo_costo, $lo_dataright);
				$lo_hoja->write($li_row, 4, $ls_precio_venta , $lo_dataright);
				$lo_hoja->write($li_row, 5, $ls_clasificacion  , $lo_datacenter);
				$lo_hoja->write($li_row, 6, $ls_subclasificacion, $lo_datacenter);
				$lo_hoja->write($li_row, 7, $ls_impuesto , $lo_datacenter);

		}

			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"existeclasificacion.xls\"");
			header("Content-Disposition: inline; filename=\"existeclasificacion.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");

		}
		}
?>