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

	    if (array_key_exists("fecemi",$_POST))
	   {
	     $ls_fechadesde=$_POST["fecemi"];
	   }
    else
	   {
	     $ls_fechadesde=$_GET["fecemi"];
	   }
	   if (array_key_exists("fecemi2",$_POST))
	   {
	     $ls_fechahasta=$_POST["fecemi2"];
	   }
    else
	   {
	     $ls_fechahasta=$_GET["fecemi2"];
	   }

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
		$lo_archivo = tempnam("/tmp", "resumengralventa.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte

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



		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha=new class_fecha();
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_msg=new class_mensajes();
		//require_once("../class_funciones_scg.php");
		//$io_fun_scg=new class_funciones_scg();
		$ia_niveles_scg[0]="";
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//---------------------------------------------------------------------------------------------------------------------------
	//Par�metros para Filtar el Reporte

		$arremp      = $_SESSION["la_empresa"];
    	$ls_codemp   = $arremp["codemp"];
		$ls_titulo     = "RESUMEN GENERAL DE VENTAS POR PRODUCTOS";
		$ls_nombemp = "CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A";
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


		//print $ls_titulo;
		$ls_fecemi=date('d/m/Y');



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
			//$lo_hoja->set_column(3,3,70);
			$lo_hoja->write(0,3,$ls_nombemp,$lo_encabezado);
			$lo_hoja->write(1,3,$ls_dentie,$lo_encabezado);
			$lo_hoja->write(2,3,$ls_titulo,$lo_encabezado);
			$lo_hoja->write(4,0,"Fecha Emision: ".$ls_fecemi,$lo_encabezado);




			$lo_hoja->set_column(5,0,30);
			$lo_hoja->write(5, 0, "CODIGO",$lo_titulo);
			$lo_hoja->set_column(5,1,40);
			$lo_hoja->write(5, 1, "DESCRIPCION",$lo_titulo);
			$lo_hoja->set_column(5,2,20);
			$lo_hoja->write(5, 2, "CANTIDAD",$lo_titulo);
			$lo_hoja->set_column(5,3,23);
			$lo_hoja->write(5, 3, "PRECIO Unit.",$lo_titulo);
			$lo_hoja->set_column(5,4,23);
			$lo_hoja->write(5, 4, "TOTAL VENTAS",$lo_titulo);
			$lo_hoja->set_column(5,5,23);
			$lo_hoja->write(5, 5, "COSTO PROMEDIO",$lo_titulo);
			$lo_hoja->set_column(5,6,23);
			$lo_hoja->write(5, 6, "COSTO TOTAL",$lo_titulo);
			$lo_hoja->set_column(5,7,23);
			$lo_hoja->write(5, 7, "UTILIDAD",$lo_titulo);
			$lo_hoja->set_column(5,8,23);
			$lo_hoja->write(5, 8, "% UTILIDAD",$lo_titulo);


			$li_row=5;


		$ls_sql=$_GET["sql"];
		//print $ls_sql;

$ls_sql=str_replace("\\","",$ls_sql);
$ls_sql=str_replace("/","",$ls_sql);
$ls_longitud=strlen($ls_sql);
$ls_posicion=strpos($ls_sql,";");
$ls_sql=substr($ls_sql,0,$ls_posicion+1);

//print $ls_sql;
	$rs_datauni=$io_sql->select($ls_sql);

if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay Nada que Reportar");
}
else
 {
   $la_producto=$io_sql->obtener_datos($rs_datauni);
	if ($la_producto){

	$li_cuotas =$io_sql->num_rows($rs_datauni);
	//print $li_cuotas;

	$la_total=0;
	if ($ls_fechadesde<>"%/%")
	{
	  $ls_fechadesde="".substr( $ls_fechadesde,8,2)."/".substr( $ls_fechadesde,5,2)."/".substr( $ls_fechadesde,0,4)."";
	  $ls_fechahasta="".substr( $ls_fechahasta,8,2)."/".substr( $ls_fechahasta,5,2)."/".substr( $ls_fechahasta,0,4)."";
	  $lo_hoja->write(3,3,"Fecha desde: ".$ls_fechadesde." Hasta ".$ls_fechahasta,$lo_encabezado);
	}
	for($i=0;$i<$li_cuotas;$i++)
	{
	// $la_datos[$i]["<b>Nº</b>"]= $i;
	 $ls_codigo= strtoupper($la_producto["codart"][$i+1]);
	 $ls_denominacion= strtoupper($la_producto["denart"][$i+1]);
	 $li_cantidad=$la_producto["cantidad"][$i+1];
	 $li_precioiva=$la_producto["prepro"][$i+1]+($la_producto["prepro"][$i+1]*$la_producto["porimp"][$i+1]/100);
	 $ls_preciouni=$li_precioiva;
	 $ls_totalvta=($li_cantidad*$ls_preciouni);
	 $li_totalgral=($li_cantidad*$ls_preciouni);
	 $li_costopro=$la_producto["cosproart"][$i+1];
	 $ls_costototal=$la_producto["cosproart"][$i+1]*$li_cantidad;
	 $li_costototal=number_format($ls_costototal,2, ',', '.');
	 $li_utilidad=number_format($ls_totalvta-$ls_costototal,2,',','.');
	 $ls_utilidad=$ls_totalvta-$ls_costototal;
//print $ls_totalvta."<br>";
	if ($ls_totalvta==0)
	 $li_porcutilidad=0;
	 else
	$li_porcutilidad=number_format((($ls_utilidad*100)/$ls_totalvta),2,',','.');



	 $la_total= $la_total+($ls_totalvta);

	$li_totacum=$li_totacum+$ls_totalvta;
	$li_acumcosto=$li_acumcosto+$li_costopro;
	$li_acumcostotot=$li_acumcostotot+$ls_costototal;
	$li_acumuti=$li_acumuti+ $ls_utilidad;
	$li_acumporcutil=$li_acumporcutil+$li_porcutilidad;


	 $li_cantidad= number_format($la_producto["cantidad"][$i+1],2, ',', '.');
	 $ls_preciouni= number_format($ls_preciouni,2, ',', '.');
	 $li_totalgral=number_format($li_totalgral,2, ',', '.');
	 $li_costopro=number_format($li_costopro,2, ',', '.');
	 $sumatotal=$sumatotal+$la_producto["cantidad"][$i+1];




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
				$lo_hoja->write($li_row, 0, $ls_codigo , $lo_dataleft);
				$lo_hoja->write($li_row, 1, $ls_denominacion, $lo_dataleft);
				$lo_hoja->write($li_row, 2, $li_cantidad, $lo_dataright);
				$lo_hoja->write($li_row, 3, $ls_preciouni, $lo_dataright);
				$lo_hoja->write($li_row, 4, $li_totalgral , $lo_dataright);
				$lo_hoja->write($li_row, 5, $li_costopro  , $lo_dataright);
				$lo_hoja->write($li_row, 6, $li_costototal, $lo_dataright);
				$lo_hoja->write($li_row, 7, $li_utilidad ,$lo_dataright);
				$lo_hoja->write($li_row, 8, $li_porcutilidad , $lo_dataright);

		}

		$li_pos=$li_row+1;
		$lo_hoja->write($li_pos, 3, "TOTAL ", $lo_encabezado);
		$lo_hoja->write($li_pos, 4, $li_totacum , $lo_dataright);
		$lo_hoja->write($li_pos, 5, $li_acumcosto  , $lo_dataright);
		$lo_hoja->write($li_pos, 6, $li_acumcostotot, $lo_dataright);
		$lo_hoja->write($li_pos, 7, $li_acumuti ,$lo_dataright);
		$lo_hoja->write($li_pos, 8, $li_acumporcutil , $lo_dataright);


			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"resumengralventa.xls\"");
			header("Content-Disposition: inline; filename=\"resumengralventa.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			//print(" close();");
			print("</script>");

		}
		}
?>
