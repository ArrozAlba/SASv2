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

	    if (array_key_exists("desde",$_POST))
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
	   }

	   $ls_tienda_desde = $_GET["agro_desde"];
	   $ls_tienda_hasta = $_GET["agro_hasta"];


function sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,$alias_tabla,$ls_codtie) {

$add_sql = '';
if ($ls_tienda_desde=='') {

$add_sql = "$alias_tabla.codtiend='$ls_codtie'";

}else {

$add_sql = "$alias_tabla.codtiend  BETWEEN '$ls_tienda_desde' AND '$ls_tienda_hasta'";

}

return $add_sql;
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
		$lo_archivo = tempnam("/home/production/tmp", "libroventa.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	/*	require_once("sigesp_scg_reporte.php");
		$io_report = new sigesp_sfc_reporte();*/
		require_once("sigesp_sfc_c_libroventa.php");
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

		$io_report= new sigesp_sfc_c_libroventa($con);

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
		$ls_titulo     = "Libro de Ventas";
		$ls_nombemp = "CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A";
		$ls_codtie=$_SESSION["ls_codtienda"];


		$ls_sql="Select dentie from sfc_tienda t where  ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'t',$ls_codtie)." ";
        // $ls_sql="Select dentie from sfc_tienda t where codtie = '0002'";


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

					for($t=0;$t<=$totrowt;$t++)
					{


						$ls_dentie=$io_datastore4->getValue("dentie",$t);
					}
				}
			}


		//print $ls_titulo;
		$ls_mes        = $io_fecha->uf_load_nombre_mes($li_mes);
		$li_lastday    = $io_fecha->uf_last_day($li_mes,$ls_ano);
		$li_lastday    = substr($li_lastday,0,2);

		$as_fechadesde = $ls_fechadesde;
		$as_fechahasta = $ls_fechahasta;

	$as_fechadesde = substr($ls_fechadesde,6,4).'-'.substr($ls_fechadesde,3,2).'-'.substr($ls_fechadesde,0,2);
	$as_fechahasta = substr($ls_fechahasta,6,4).'-'.substr($ls_fechahasta,3,2).'-'.substr($ls_fechahasta,0,2);

		$ls_rango = "DESDE: ".$ls_fechadesde."     "."HASTA: ".$ls_fechahasta." ";
		$ls_cuenta_desde=$as_fechadesde;
		$ls_cuenta_hasta=$as_fechahasta;

	   $lb_valido = $io_report->uf_load_libro_ventas($as_fechadesde,$as_fechahasta,$ls_tienda_desde,$ls_tienda_hasta); // Cargar el DS con los datos de la cabecera del reporte
	//---------------------------------------------------------------------------------------------------------------------------
	// Impresi�n de la informaci�n encontrada en caso de que exista
		if($lb_valido==false) // Existe alg�n error � no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');");
			print(" close();");
			print("</script>");
		}
		else // Imprimimos el reporte
		{


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
			$lo_hoja->write(3,3,$ls_rango,$lo_encabezado);

			$lo_hoja->set_column(0,0,4);
			$lo_hoja->write(5, 0, "Nº",$lo_titulo);
			$lo_hoja->set_column(1,1,11);
			$lo_hoja->write(5, 1, "Fecha",$lo_titulo);
			$lo_hoja->set_column(2,2,12);
			$lo_hoja->write(5, 2, "RIF",$lo_titulo);
			$lo_hoja->write(5, 3, "Nombre o Razón Social del Comprador",$lo_titulo);
			$lo_hoja->set_column(4,7,18);
			$lo_hoja->write(5, 4, "Nº Factura",$lo_titulo);
			$lo_hoja->write(5, 5, "Nº Control",$lo_titulo);
			$lo_hoja->write(5, 6, "Nº Nota de Débito",$lo_titulo);
			$lo_hoja->write(5, 7, "Nº Nota de Crédito",$lo_titulo);

			$lo_hoja->set_column(8,8,13);
			$lo_hoja->write(5, 8, "Transacción",$lo_titulo);
			$lo_hoja->set_column(9,9,22);
			$lo_hoja->write(5, 9, "Nº Fact. Afect.",$lo_titulo);

			$lo_hoja->set_column(10,11,25);
			$lo_hoja->write(5, 10, "Total Ventas con IVA",$lo_titulo);
			$lo_hoja->write(5, 11, "Ventas Int. No Grabadas",$lo_titulo);

			$lo_hoja->set_column(12,12,20);
			$lo_hoja->write(5, 12, "Base Imponible 8%",$lo_titulo);
			$lo_hoja->set_column(13,13,10);
			$lo_hoja->write(5, 13, "8%",$lo_titulo);
			$lo_hoja->set_column(14,14,20);
			$lo_hoja->write(5, 14, "Impuesto 8%",$lo_titulo);

			$lo_hoja->set_column(15,15,20);
			$lo_hoja->write(5, 15, "Base Imponible Alicuota Gral.",$lo_titulo);
			$lo_hoja->set_column(16,16,10);
			$lo_hoja->write(5, 16, "Alicuota Gral.",$lo_titulo);
			$lo_hoja->set_column(17,17,20);
			$lo_hoja->write(5, 17, "Impuesto Alicuota Gral.",$lo_titulo);

			$lo_hoja->set_column(18,19,25);
			$lo_hoja->write(5, 18, "Impuesto IVA%",$lo_titulo);
			$lo_hoja->write(5, 19, "IVA Retenido",$lo_titulo);

			$li_row=5;


			 $li_totrow = $io_report->ds_libroventa->getRowCount("numfac");
			 $li_totrow       = $io_report->ds_libroventa->getRowCount("numfac");
			 $ld_totbasimp    = 0;
			 $ld_totcomsiniva = 0;
			 $ld_totvenconiva = 0;
			 $ld_totimpuestos = 0;
			 $ld_totimp8      = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 8%.
			 $ld_totimp12     = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 14%.
			 $ld_totimp25     = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 25%.

			 $ld_totbasimp8   = 0;
			 $ld_totbasimp12  = 0;

			 $totalVentasiva = 0;
			 $totalVentasint = 0;
			 $totalBasimp    = 0;
			 $totalImpiva    = 0;
			 $totalIvaper    = 0;
			 $ld_montodev    =0;
			 $ld_totaldedu8  =0;
			 $ld_totaldedu12  =0;
			 $ls_notacre="";
			 $ld_totalmontoret=0;
			 $ld_totbasimp12f=0;
			 $ld_totbasimp12d=0;
			 $ld_totbasimp8f=0;
			 $ld_totbasimp8d=0;

			 $ld_baseimp12    =0;
			 $ld_baseimp12s    =0;
			 $ld_baseimp8s    =0;
			 $ld_deduccion12  =0;
			 $ls_porimp12     =0;
			 $ld_deduccion12s=0;
			 $ld_deduccion8s=0;
			 $ld_baseimp8    =0;
			 $ld_deduccion8  =0;
			 $ls_porimp8     =0;
			 $ld_montoret=0;
			$li_n=0;


			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{

				$ld_sinderiva=0;
					$ld_sinderivaA=0;


			   $ld_monconiva=0;
			   $ld_monret=0;
			   $li_totant     = 0;
			  // $ls_numrecdoc  = substr($io_report->ds_libroventa->data["numfac"][$li_i],4,1)."-".substr($io_report->ds_libroventa->data["numfac"][$li_i],19,25);

			   $lb_existe     = false;
			   $ls_numfac     = $io_report->ds_libroventa->data["numfac"][$li_i];
			   $ls_fecemidoc  = $io_report->ds_libroventa->data["fecemi"][$li_i];
			   $ls_fecemidoc  = substr($ls_fecemidoc,8,2).'/'.substr($ls_fecemidoc,5,2).'/'.substr($ls_fecemidoc,0,4);
			   $ls_tipproben  = $io_report->ds_libroventa->data["numcot"][$li_i];
			   $ls_codtie     = $io_report->ds_libroventa->data["codtiend"][$li_i];
			   $ls_cedbene    = $io_report->ds_libroventa->data["codcli"][$li_i];
			   $ls_estfac     = $io_report->ds_libroventa->data["estfaccon"][$li_i];
			   $ls_rif        = $io_report->ds_libroventa->data["cedcli"][$li_i];
			   $ls_apebene    = $io_report->ds_libroventa->data["razcli"][$li_i];
			   $ls_nombre     = $ls_apebene;
			   $ld_montotdoc = $io_report->ds_libroventa->data["monto"][$li_i];
			   $ls_numcontrol = substr($io_report->ds_libroventa->data["numcon"][$li_i],18,7);


                           $ld_sinderiva = number_format($io_report->ds_libroventa->data["exe"][$li_i],2,',','.');
                           $ld_baseimp8  = number_format($io_report->ds_libroventa->data["base8"][$li_i],2,',','.');
                           $ls_porimp8 = number_format($io_report->ds_libroventa->data["iva8"][$li_i],2,',','.');
                           $ld_baseimp12  = number_format($io_report->ds_libroventa->data["base12"][$li_i],2,',','.');
                           $ls_porimp12 = number_format($io_report->ds_libroventa->data["iva12"][$li_i],2,',','.');

                           $ld_sinderivaA = $io_report->ds_libroventa->data["exe"][$li_i];
                           $ld_base8  = $io_report->ds_libroventa->data["base8"][$li_i];
                           $ls_iva8 = $io_report->ds_libroventa->data["iva8"][$li_i];
                           $ld_base12  = $io_report->ds_libroventa->data["base12"][$li_i];
                           $ls_iva12 = $io_report->ds_libroventa->data["iva12"][$li_i];

                           $ld_tot8 = $ld_base8 + $ls_iva8;
                           $ld_deduccion8 = number_format($ls_iva8,2,',','.');

                           $ld_tot12 = $ld_base12 + $ls_iva12;
                           $ld_deduccion12 = number_format($ls_iva12,2,',','.');


				 if($ls_estfac=='A')
			   {
			   		$ls_tiptran="03-Anul";
					$ls_numfacafec="";
					//$ld_montotdoc=0; $ld_montotdoc
					$ls_esta="N";


			   }
			   elseif(($ls_estfac=='N') or ($ls_estfac=='P') or ($ls_estfac=='C') )
			   {
			   		$ls_tiptran="01-Reg";
					$ls_numfacafec="";
					$ls_esta="R";

			   }

			$ls_serie= substr($io_report->ds_libroventa->data["numfac"][$li_i],6,1);
			if ($ls_serie<>"0")
				$ls_numrecdoc  = substr($io_report->ds_libroventa->data["numfac"][$li_i],4,3)."-".substr($io_report->ds_libroventa->data["numfac"][$li_i],19,25);
			else
				$ls_numrecdoc  = substr($io_report->ds_libroventa->data["numfac"][$li_i],4,2)."-".substr($io_report->ds_libroventa->data["numfac"][$li_i],19,25);



				$li_totfil=0;

				$ls_notacre="";
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
				//$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, $li_n , $lo_datacenter);
				$lo_hoja->write($li_row, 1, $ls_fecemidoc, $lo_dataleft);
				$lo_hoja->write($li_row, 2, $ls_rif , $lo_dataleft);
				$lo_hoja->write($li_row, 3, $ls_nombre, $lo_dataleft);
				$lo_hoja->write($li_row, 4, $ls_numrecdoc , $lo_datacenter);
				$lo_hoja->write($li_row, 5, $ls_numcontrol  , $lo_datacenter);
				$lo_hoja->write($li_row, 6, $ls_notacre, $lo_datacenter);
				$lo_hoja->write($li_row, 7, $ls_notacre , $lo_datacenter);
				$lo_hoja->write($li_row, 8, $ls_tiptran, $lo_datacenter);

                                $ld_totalmontoret=$ld_totalmontoret+$ld_montoret;
                                $ld_monconiva = $ld_montotdoc;

			  /////////  TOTALES GENERALES   //////////////
			   $ld_totmonconiva=$ld_monconiva;

			   $ld_totalimpuesto = $ld_deduccion8s+$ld_deduccion12s;

                           $ld_totalimpuestopan = redondeado($ld_totalimpuesto,2);
			   $ld_baseimp8pan = redondeado($ld_baseimp8s,2);
			   $ld_deduccion12s = redondeado($ld_deduccion12s,2);

			  

			   $totalImpiva= $totalImpiva+ $ld_totalimpuesto;
			   //$ld_baseimp8       = redondeado($ld_baseimp8,2);
			   //$ld_baseimp12       = redondeado($ld_baseimp12,2);
			   $ld_montoret       = redondeado($ld_montoret,2);
			   //$ld_deduccion8    = redondeado($ld_deduccion8,2);
			   //$ld_deduccion12    = redondeado($ld_deduccion12,2);
			   //$ld_monconiva    = redondeado($ld_monconiva,2);

			   $ld_total        = 0;
			   //$ls_porimp8 = redondeado($ls_porimp8,2);
			   $ld_totalimpuesto = redondeado($ld_totalimpuesto,2);

                           $ld_deduccion8s = $ls_iva8;
                           $ld_deduccion12s = $ls_iva12;
                          $ld_baseimp12s=$ld_baseimp12s + $ld_base12;
                          $ld_baseimp8s=$ld_baseimp8s + $ld_base8;
			  $ld_totbasimp12f = $ld_totbasimp12f + $ld_base12;
			  $ld_totbasimp8f  = $ld_totbasimp8f + $ld_base8;
			  $ld_totaldedu8   = $ld_totaldedu8 + $ls_iva8 ;
			  $ld_totaldedu12  = $ld_totaldedu12 + $ls_iva12;

                          /*
                                $ld_totbasimp8f = $ld_totbasimp8f + $ld_base8;
                                $ld_totaldedu8    = $ld_totaldedu8 + $ls_iva8 ;
                                $ld_totbasimp12f = $ld_totbasimp12f + $ld_base12;
                                $ld_totaldedu12    = $ld_totaldedu12 + $ls_iva12;

                           */

			 	$lo_hoja->write($li_row, 9, $ls_numfacafec , $lo_datacenter);
				$lo_hoja->write($li_row, 10, $ld_monconiva, $lo_dataright);
				$lo_hoja->write($li_row, 11, $ld_sinderiva , $lo_dataright);
				$lo_hoja->write($li_row, 12, $ld_baseimp8, $lo_dataright);
				$lo_hoja->write($li_row, 13, $ls_porimp8 , $lo_datacenter);
				$lo_hoja->write($li_row, 14, $ld_deduccion8  , $lo_dataright);
				$lo_hoja->write($li_row, 15, $ld_baseimp12, $lo_dataright);
				$lo_hoja->write($li_row, 16, $ls_porimp12 , $lo_datacenter);
				$lo_hoja->write($li_row, 17, $ld_deduccion12, $lo_dataright);
				$lo_hoja->write($li_row, 18, $ld_totalimpuesto ,$lo_dataright);
				$lo_hoja->write($li_row, 19, $ld_montoret, $lo_dataright);



			$totalVentasiva = $totalVentasiva +  $ld_totmonconiva;
			$totalVentasint = $totalVentasint + $ld_sinderivaA;
 /*$ls_cadena="Select dt.porimp,f.numfac,SUM(dt.canpro*dt.prepro) as baseimponible,SUM((dt.canpro*dt.prepro)*dt.porimp/100) as deduccion " .
		"from sfc_factura f,sfc_detfactura dt where f.codemp='".$ls_codemp."' and ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)."" .
		" and dt.numfac='".$ls_numfac."' and f.numfac=dt.numfac and substring(cast(f.fecemi as varchar),0,11) BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."'" .
		" GROUP by f.numfac,dt.porimp,f.fecemi  ORDER BY f.numfac,f.fecemi ASC";

		$ls_notacre="";
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				//$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					$la_tienda=$io_sql->obtener_datos($rs_datauni);
					$io_datastore3->data=$la_tienda;
					$totrow=$io_datastore3->getRowCount("numfac");

					//print_r ($la_tienda);

					 $ld_baseimp12    =0;
					 $ld_deduccion12  =0;
					 $ls_porimp12     =0;
					 $ld_baseimp12s   = 0;
					 $ld_baseimp8s   = 0;
					 $ld_baseimp8    =0;
					 $ld_deduccion8  =0;
					 $ld_deduccion8s  =0;
					 $ld_deduccion12s  =0;
					 $ls_porimp8     =0;
					 $ld_montoret=0;


					for($z=1;$z<=$totrow;$z++)
					{


						$ls_codcli=$io_datastore3->getValue("numfac",$z);
			  			$ld_porimp = $io_datastore3->getValue("porimp",$z);
						$ld_baseimp    =$io_datastore3->getValue("baseimponible",$z);
						$ld_deduccion = $io_datastore3->getValue("deduccion",$z);



					if((($ld_porimp==12) or ($ld_porimp==9))and ($ld_baseimp8!=0) )
						{
							$ld_baseimp12   =$ld_baseimp12+$ld_baseimp;
							$ld_baseimp12s=$ld_baseimp12s + $ld_baseimp;
							$ld_deduccion12 = $ld_deduccion12+$ld_deduccion;
							$ld_deduccion12s =$ld_deduccion12s+$ld_deduccion;
							if(($ld_porimp==12) or ($ld_porimp==9))
							  $ls_porimp12=number_format($ld_porimp,2,',','.')."-".number_format($io_datastore3->getValue("porimp",$z-1),2,',','.');
							//$ls_porimp12=redondeado($ld_porimp,2)."-".redondeado($io_datastore3->getValue("porimp",$z-1),2);

						}

					elseif((($ld_porimp==12) or ($ld_porimp==9)) and ($ld_baseimp8==0))
					{
							$ld_baseimp12   =$ld_baseimp12+$ld_baseimp;
							$ld_baseimp12s=$ld_baseimp12s + $ld_baseimp;
							$ld_deduccion12 = $ld_deduccion12+$ld_deduccion;
							$ld_deduccion12s =$ld_deduccion12s+$ld_deduccion;

							if(($ld_porimp==12) or ($ld_porimp==9))
							    $ls_porimp12=number_format($ld_porimp,2,',','.')."-".number_format($io_datastore3->getValue("porimp",$z-1),2,',','.');
								//$ls_porimp12=redondeado($ld_porimp,2)."-".redondeado($io_datastore3->getValue("porimp",$z-1),2);

							$ls_porimp8=0;
							$ld_baseimp8=0;
							$ld_baseimp8s=0;
							$ld_deduccion8=0;
							$ld_deduccion8s=0;


					}
					if(($ld_porimp==8) and ($ld_baseimp12!=0))
					{
							$ld_baseimp8   =$ld_baseimp;
							$ld_baseimp8s=$ld_baseimp;
							$ld_deduccion8 =$ld_deduccion;
							$ld_deduccion8s =$ld_deduccion;
							$ls_porimp8=$ld_porimp;



					}
					elseif(($ld_porimp==8) and ($ld_baseimp12==0))
					{
							$ld_baseimp8   =$ld_baseimp;
							$ld_baseimp8s=$ld_baseimp;
							$ld_deduccion8 =$ld_deduccion;
							$ld_deduccion8s =$ld_deduccion;
							$ls_porimp8=$ld_porimp;

							$ls_porimp12=0;
							$ld_baseimp12=0;
							$ld_baseimp12s=0;
							$ld_deduccion12=0;
							$ld_deduccion12s=0;
					}





					if($ld_porimp==0) //and ($ld_basimp12==0))
					{


							if ($ld_baseimp12==0)
							{
								$ls_porimp12=0;
								$ld_baseimp12=0;
								$ld_baseimp12s=0;
								$ld_deduccion12=0;
								$ld_deduccion12s=0;
							}
							if($ld_baseimp8==0)
							{
								$ls_porimp8=0;
								$ld_baseimp8=0;
								$ld_baseimp8s=0;
								$ld_deduccion8=0;
								$ld_deduccion8s=0;
							}

					}



				if($ls_esta=="N")
			   {

					$ld_porimp =0;
						$ld_baseimp    =0;
						$ld_deduccion = 0;


					$ld_monconiva=0;
					$ld_sinderiva=0;
					$ld_sinderivaA=0;
					$ld_deduccion8=0;
					$ld_deduccion12=0;

					$ld_baseimp12=0;
					$ld_baseimp8=0;
					$ls_porimp8=0;
					$ls_porimp12=0;
					$ld_deduccion8=0;
					$ld_deduccion12=0;
					$ld_deduccion8s=0;
					$ld_deduccion12s=0;

					$ld_baseimp12s=0;
					$ld_baseimp8s=0;

			   }
			   elseif($ls_esta=="R")
			   {
			   		 $ld_monconiva = $ld_montotdoc;
					//$ld_sinderiva = $ld_montotdoc;

				}


			$ls_sql3="Select cf.numcob as cod,c.montoret from sfc_cobro_cliente c, sfc_dt_cobrocliente cf where cf.codemp='".$ls_codemp."' and ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'cf',$ls_codtie)." and cf.codcli='".$ls_cedbene."' and cf.numfac='".$ls_numfac."'  and c.numcob=cf.numcob ORDER BY c.feccob ASC";


			$arr_cobro=$io_sql3->select($ls_sql3);
			if($arr_cobro==false&&($io_sql2->message!=""))
			{
				$ld_montoret=0;
			}
			else
			{

				if($row=$io_sql3->fetch_row($arr_cobro))
				{


					$la_cobro=$io_sql3->obtener_datos($arr_cobro);


					$io_datastore4->data=$la_cobro;
					$totrow1=$io_datastore4->getRowCount("cod");

					for($j=1;$j<=$totrow1;$j++)
					{

								$ld_montoret=$io_datastore4->getValue("montoret",$j);

				   }
			   }
			 }


			if($ld_porimp==0)
			{
					 $ld_sinderiva=  $ld_baseimp;
					 $ld_sinderivaA= $ld_sinderiva;

					// $ld_sinderiva= number_format($ld_sinderiva,2,',','.');
					$ld_sinderiva= redondeado($ld_sinderiva,2);
			}
			else
			{

				if($ld_sinderiva=="0,00")
				{
					//$ld_sinderiva= number_format($ld_sinderiva,2,',','.');
					$ld_sinderiva= redondeado($ld_sinderiva,2);
				}
				else
				{
					$ld_sinderiva= $ld_sinderiva;
				}

			}


			  $ld_totalmontoret=$ld_totalmontoret+$ld_montoret;


			  /////////  TOTALES GENERALES   //////////////
			   $ld_totmonconiva=$ld_monconiva;

			   $ld_totalimpuesto = $ld_deduccion8s+$ld_deduccion12s;
			   $ld_totalimpuestopan = redondeado($ld_totalimpuesto,2);
			   $ld_baseimp8pan = redondeado($ld_baseimp8s,2);
			   $ld_deduccion12s = redondeado($ld_deduccion12s,2);

			  }

			   $totalImpiva= $totalImpiva+ $ld_totalimpuesto;
			   $ld_baseimp8       = redondeado($ld_baseimp8,2);
			   $ld_baseimp12       = redondeado($ld_baseimp12,2);
			   $ld_montoret       = redondeado($ld_montoret,2);
			   $ld_deduccion8    = redondeado($ld_deduccion8,2);
			   $ld_deduccion12    = redondeado($ld_deduccion12,2);
			   $ld_monconiva    = redondeado($ld_monconiva,2);

			   $ld_total        = 0;
			   $ls_porimp8 = redondeado($ls_porimp8,2);
			   $ld_totalimpuesto = redondeado($ld_totalimpuesto,2);


			  $ld_totbasimp12f = $ld_totbasimp12f + $ld_baseimp12s;
			  $ld_totbasimp8f  = $ld_totbasimp8f + $ld_baseimp8s;
			  $ld_totaldedu8   = $ld_totaldedu8 + $ld_deduccion8s ;
			  $ld_totaldedu12  = $ld_totaldedu12 + $ld_deduccion12s;


			 	$lo_hoja->write($li_row, 9, $ls_numfacafec , $lo_datacenter);
				$lo_hoja->write($li_row, 10, $ld_monconiva, $lo_dataright);
				$lo_hoja->write($li_row, 11, $ld_sinderiva , $lo_dataright);
				$lo_hoja->write($li_row, 12, $ld_baseimp8, $lo_dataright);
				$lo_hoja->write($li_row, 13, $ls_porimp8 , $lo_datacenter);
				$lo_hoja->write($li_row, 14, $ld_deduccion8  , $lo_dataright);
				$lo_hoja->write($li_row, 15, $ld_baseimp12, $lo_dataright);
				$lo_hoja->write($li_row, 16, $ls_porimp12 , $lo_datacenter);
				$lo_hoja->write($li_row, 17, $ld_deduccion12, $lo_dataright);
				$lo_hoja->write($li_row, 18, $ld_totalimpuesto ,$lo_dataright);
				$lo_hoja->write($li_row, 19, $ld_montoret, $lo_dataright);



			$totalVentasiva = $totalVentasiva +  $ld_totmonconiva;
			$totalVentasint = $totalVentasint + $ld_sinderivaA;

}}*/

			}

		/*******************  CHEQUEAR DEVOLUCIONES DE LAS FACTURAS *********************/
$li_n=$li_n;


/*$ls_sql= "Select DISTINCT(d.numfac),d.coddev,d.numcon,d.codemp,d.codtiend,substring(d.fecdev,0,11) as fecdev,sfc_cliente.cedcli,sfc_cliente.razcli" .
		" from sfc_devolucion d,sfc_cliente,sfc_factura f where d.codemp='".$ls_codemp."' " .
		" and ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'d',$ls_codtie)." and d.numfac=f.numfac and sfc_cliente.codcli=f.codcli " .
		" and substring(d.fecdev,0,11) BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' ORDER BY fecdev ASC";*/

$ls_sql="Select DISTINCT(d.numfac),d.coddev,d.numcon,d.codemp,d.codtiend,substring(cast(d.fecdev as varchar),0,11) as fecdev,sfc_cliente.cedcli,sfc_cliente.razcli " .
		"from sfc_devolucion d,sfc_cliente,sfc_factura f where d.codemp='".$ls_codemp."' and " .
		" ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'d',$ls_codtie)." and d.numfac=f.numfac and sfc_cliente.codcli=f.codcli " .
		"and substring(cast(d.fecdev as varchar),0,11) BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."'  ORDER BY fecdev ASC";

$arr_devol=$io_sql5->select($ls_sql);
			if($arr_devol==false&&($io_sql5->message!=""))
			{

			}
			else
			{

				if($row=$io_sql5->fetch_row($arr_devol))
				{

					$la_devol=$io_sql5->obtener_datos($arr_devol);

					$io_datastore5->data=$la_devol;
					$totrow3=$io_datastore5->getRowCount("coddev");

				for($d=1;$d<=$totrow3;$d++)
					{


						$ls_coddevol=$io_datastore5->getValue("coddev",$d);
						$ls_rifd=$io_datastore5->getValue("cedcli",$d);
						$ls_nombred=$io_datastore5->getValue("razcli",$d);
						$ls_numfacd=$io_datastore5->getValue("numfac",$d);
						$ls_numcondev=$io_datastore5->getValue("numcon",$d);
						$ls_fecemidoc  = $io_datastore5->getValue("fecdev",$d);
			   			$ls_fecemidoc  = substr($ls_fecemidoc,8,2).'/'.substr($ls_fecemidoc,5,2).'/'.substr($ls_fecemidoc,0,4);
						$ls_tiptran="01-Reg";
						$ls_numrecdoc="";

						$ls_serie= substr($io_datastore5->getValue("numfac",$d),6,1);

			  			if ($ls_serie<>"0")
			   				$ls_numfacafec=substr($io_datastore5->getValue("numfac",$d),4,3)."-".substr($io_datastore5->getValue("numfac",$d),19,25);
						else
							$ls_numfacafec=substr($io_datastore5->getValue("numfac",$d),4,2)."-".substr($io_datastore5->getValue("numfac",$d),19,25);

						$ls_numcontrol=substr($ls_numcondev,12,25);



$ls_sql2="Select dt.coddev as coddev,dt.porimp,SUM(dt.candev*dt.precio) + ((dt.candev*dt.precio)*porimp/100) as montodev,(dt.candev*dt.precio) as baseimponible,SUM((dt.candev*dt.precio)*dt.porimp/100) as deduccion from sfc_detdevolucion dt where dt.codemp='".$ls_codemp."' and ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'dt',$ls_codtie)." and dt.coddev='".$ls_coddevol."'  GROUP by dt.porimp,dt.coddev,dt.candev,dt.precio ORDER BY dt.coddev ASC";


//$ls_cadena="Select dt.porimp,f.numfac,SUM(dt.canpro*dt.prepro) as baseimponible,SUM((dt.canpro*dt.prepro)*dt.porimp/100) as deduccion from sfc_factura f,sfc_detfactura dt where f.codemp='".$ls_codemp."' and f.codtiend='".$ls_codtie."' and dt.numfac='".$ls_numfac."' and f.numfac=dt.numfac and f.fecemi BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' GROUP by f.numfac,dt.porimp,f.fecemi  ORDER BY f.fecemi ASC";

//$ls_sql2="Select dt.coddev as cod,dt.porimp,f.numfac,(dt.candev*dt.precio)+ ((dt.candev*dt.precio)*porimp/100) as montodev,(dt.candev*dt.precio) as baseimponible,(dt.candev*dt.precio)*dt.porimp/100 as deduccion from sfc_devolucion f,sfc_detdevolucion dt where f.codemp='".$ls_codemp."' and f.codtiend='".$ls_codtie."' and dt.coddev=f.coddev and f.fecdev BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."'  ORDER BY f.fecdev ASC";

		//print $ls_sql2;
			$arr_devo=$io_sql2->select($ls_sql2);
			if($arr_devo==false&&($io_sql2->message!=""))
			{

			}
			else
			{

				if($row=$io_sql2->fetch_row($arr_devo))
				{

					$la_devo=$io_sql2->obtener_datos($arr_devo);

					$io_datastore2->data=$la_devo;
					$totrow2=$io_datastore2->getRowCount("coddev");

					 $ld_baseimp12    =0;
					 $ld_deduccion12  =0;
					 $ld_deduccion12s  =0;
					 $ls_porimp12     =0;

					 $ld_baseimp8    =0;
					 $ld_deduccion8  =0;
					 $ld_deduccion8s  =0;
					 $ls_porimp8     =0;
					 $ld_baseimp12s=0;
					 $ld_baseimp8s=0;
					 $ld_montotdoc =0;
					 $ld_sinderiva=0;
					 $ld_sinderivaA=0;


					for($i=1;$i<=$totrow2;$i++)
					{

						$ls_coddev=$io_datastore2->getValue("coddev",$i);
						$ld_montodev  = $io_datastore2->getValue("montodev",$i);
						$ld_porimp = $io_datastore2->getValue("porimp",$i);
						$ld_baseimp    =$io_datastore2->getValue("baseimponible",$i);
						$ld_deduccion = $io_datastore2->getValue("deduccion",$i);
						$ls_serie= substr($ls_coddev,6,1);

			  			if ($ls_serie<>"0")
			   				$ls_notacre=substr($ls_coddev,4,3)."-".substr($ls_coddev,19,25);
						else
							$ls_notacre=substr($ls_coddev,4,1)."-".substr($ls_coddev,19,25);


						$ld_montotdoc = $ld_montotdoc + $ld_montodev;


					if((($ld_porimp==12)  or ($ld_porimp==9)) and ($ld_baseimp8!=0))
						{


							$ld_baseimp12   =$ld_baseimp12+$ld_baseimp;
							$ld_baseimp12s=$ld_baseimp12s + $ld_baseimp;
							$ld_deduccion12 = $ld_deduccion12+$ld_deduccion;
							$ld_deduccion12s =$ld_deduccion12s+$ld_deduccion;
							if(($ld_porimp==12) or ($ld_porimp==9))
							  $ls_porimp12=number_format($ld_porimp,2,',','.')."-".number_format($io_datastore2->getValue("porimp",$i-1),2,',','.');
							 // $ls_porimp12=redondeado($ld_porimp,2)."-".redondeado($io_datastore2->getValue("porimp",$i-1),2);


						}

					elseif((($ld_porimp==12) or ($ld_porimp==9)) and ($ld_baseimp8==0) )
					{



							$ld_baseimp12   =$ld_baseimp12+$ld_baseimp;
							$ld_baseimp12s=$ld_baseimp12s + $ld_baseimp;
							$ld_deduccion12 = $ld_deduccion12+$ld_deduccion;
							$ld_deduccion12s =$ld_deduccion12s+$ld_deduccion;
							if(($ld_porimp==12) or ($ld_porimp==9))
							  $ls_porimp12=number_format($ld_porimp,2,',','.')."-".number_format($io_datastore2->getValue("porimp",$i-1),2,',','.');
							  //$ls_porimp12=redondeado($ld_porimp,2)."-".redondeado($io_datastore2->getValue("porimp",$i-1),2);


//print $ls_porimp12."--IMP<br>";
							$ls_porimp8=0;
							$ld_baseimp8=0;
							$ld_baseimp8s =0;
							$ld_deduccion8=0;
							$ld_deduccion8s=0;



					}

					if(($ld_porimp==8) and ($ld_baseimp12!=0))
					{
							$ld_baseimp8   ="-".$ld_baseimp;
							$ld_baseimp8s  =$ld_baseimp;
							$ld_deduccion8 ="-".$ld_deduccion;
							$ld_deduccion8s=$ld_deduccion;
							$ls_porimp8=$ld_porimp;




					}
					elseif(($ld_porimp==8) and ($ld_baseimp12==0))
					{
							$ld_baseimp8   ="-".$ld_baseimp;
							$ld_baseimp8s  =$ld_baseimp;
							$ld_deduccion8 ="-".$ld_deduccion;
							$ld_deduccion8s=$ld_deduccion;
							$ls_porimp8=$ld_porimp;

							$ls_porimp12=0;
							$ld_baseimp12=0;
							$ld_baseimp12s  =0;
					 		$ld_deduccion12=0;
							$ld_deduccion12s=0;

					}


					if($ld_porimp==0)
					{


							if($ld_baseimp12==0)
							{
								$ls_porimp12=0;
								$ld_baseimp12=0;
								$ld_baseimp12s  =0;
								$ld_deduccion12=0;
								$ld_deduccion12s=0;

							}
							if($ld_baseimp8==0)
							{
								$ls_porimp8=0;
								$ld_baseimp8=0;
								$ld_beseimp8s  =0;
								$ld_deduccion8=0;
								$ld_deduccion8s=0;

							}




					}


			if($ld_porimp==0)
			{
				$ld_sinderiva=$ld_baseimp;

				$ld_sinderivaA= $ld_baseimp;

				//$ld_sinderiva="-".number_format($ld_sinderiva,2,',','.');
				$ld_sinderiva="-".redondeado($ld_sinderiva,2);
			}
			else
			{


				if($ld_sinderiva=="0,00")
				{
					//$ld_sinderiva= number_format($ld_sinderiva,2,',','.');
					$ld_sinderiva= redondeado($ld_sinderiva,2);
				}
				else
				{
					$ld_sinderiva= $ld_sinderiva;}

				}



			 $ld_monconiva = "-".$ld_montotdoc;



			  /////////  TOTALES GENERALES   //////////////


			   $ld_totalimpuesto = $ld_deduccion8s+$ld_deduccion12s;


			 $t=$t+$i+$li_i+$d;



			}
			   $totalImpiva= $totalImpiva - $ld_totalimpuesto;

			  /* $ld_baseimp8       = number_format($ld_baseimp8,2,',','.');
			   $ld_baseimp12       = number_format($ld_baseimp12,2,',','.');

			   $ld_deduccion8    = number_format($ld_deduccion8,2,',','.');
			   $ld_deduccion12    = number_format($ld_deduccion12,2,',','.');

			   $ld_monconiva    = number_format($ld_monconiva,2,',','.');

			   $ls_porimp8 = number_format($ls_porimp8,2,',','.');

			   $ld_totalimpuesto = number_format($ld_totalimpuesto,2,',','.');*/

			   $ld_baseimp8       = redondeado($ld_baseimp8,2);
			   $ld_baseimp12       = redondeado($ld_baseimp12,2);

			   $ld_deduccion8    = redondeado($ld_deduccion8,2);
			   $ld_deduccion12    = redondeado($ld_deduccion12,2);

			   $ld_monconiva    = redondeado($ld_monconiva,2);

			   $ls_porimp8 = redondeado($ls_porimp8,2);

			   $ld_totalimpuesto = redondeado($ld_totalimpuesto,2);



			   $ld_totaldedu8    = $ld_totaldedu8 - $ld_deduccion8s ;
			   $ld_totaldedu12    = $ld_totaldedu12 - $ld_deduccion12s;


			$ld_totbasimp12d = $ld_totbasimp12d + $ld_baseimp12s;
			$ld_totbasimp8d = $ld_totbasimp8d + $ld_baseimp8s;
		    $ld_totalimp=substr($ld_totalimpuesto,0,1);

			if($ld_totalimpuesto=="0,00")
			{
			$ld_totalimpuesto="0,00";
			}else
			  {$ld_totalimpuesto='-'.$ld_totalimpuesto;}
			  $li_n++;
				$li_row=$li_row+1;
				$lo_hoja->write($li_row, 0, $li_n , $lo_datacenter);
				$lo_hoja->write($li_row, 1, $ls_fecemidoc, $lo_dataleft);
				$lo_hoja->write($li_row, 2, $ls_rifd , $lo_dataleft);
				$lo_hoja->write($li_row, 3, $ls_nombred, $lo_dataleft);
				$lo_hoja->write($li_row, 4, $ls_numrecdoc , $lo_datacenter);
				$lo_hoja->write($li_row, 5, $ls_numcontrol  , $lo_datacenter);
				$lo_hoja->write($li_row, 6, '', $lo_datacenter);
				$lo_hoja->write($li_row, 7, $ls_notacre , $lo_datacenter);
				$lo_hoja->write($li_row, 8, $ls_tiptran, $lo_datacenter);

				$lo_hoja->write($li_row, 9, $ls_numfacafec , $lo_datacenter);
				$lo_hoja->write($li_row, 10, $ld_monconiva, $lo_dataright);
				$lo_hoja->write($li_row, 11, $ld_sinderiva , $lo_dataright);
				$lo_hoja->write($li_row, 12, $ld_baseimp8, $lo_dataright);
				$lo_hoja->write($li_row, 13, $ls_porimp8 , $lo_datacenter);
				$lo_hoja->write($li_row, 14, $ld_deduccion8  , $lo_dataright);
				$lo_hoja->write($li_row, 15, "-".$ld_baseimp12, $lo_dataright);
				$lo_hoja->write($li_row, 16, $ls_porimp12 , $lo_datacenter);
				$lo_hoja->write($li_row, 17, "-".$ld_deduccion12, $lo_dataright);
				$lo_hoja->write($li_row, 18, $ld_totalimpuesto ,$lo_dataright);
				$lo_hoja->write($li_row, 19, '0,00', $lo_dataright);



			$totalVentasiva = $totalVentasiva - $ld_montotdoc ;
			 $totalVentasint = $totalVentasint - $ld_sinderivaA;
				}

			}
			}


			}


			}

$ld_totbasimp8 = $ld_totbasimp8f - $ld_totbasimp8d;
$ld_totbasimp12 = $ld_totbasimp12f - $ld_totbasimp12d;



		 	/* $totalVentasiva = number_format($totalVentasiva,2,',','.');
			 $totalVentasint = number_format($totalVentasint,2,',','.');
			 $totalImpiva    = number_format($totalImpiva,2,',','.');
			 $totalIvaper    = number_format($totalIvaper ,2,',','.');
			 $ld_totbasimp12 =  number_format($ld_totbasimp12,2,',','.');
			 $ld_totbasimp8 =  number_format($ld_totbasimp8,2,',','.');

			 $ld_totaldedu8 = number_format($ld_totaldedu8,2,',','.');
			 $ld_totaldedu12 = number_format($ld_totaldedu12,2,',','.');
			 $ld_totalmontoret = number_format($ld_totalmontoret,2,',','.');*/

			 $totalVentasiva = redondeado($totalVentasiva,2);
			 $totalVentasint = redondeado($totalVentasint,2);
			 $totalImpiva    = redondeado($totalImpiva,2);
			 $totalIvaper    = redondeado($totalIvaper ,2);
			 $ld_totbasimp12 =  redondeado($ld_totbasimp12,2);
			 $ld_totbasimp8 =  redondeado($ld_totbasimp8,2);

			 $ld_totaldedu8 = redondeado($ld_totaldedu8,2);
			 $ld_totaldedu12 = redondeado($ld_totaldedu12,2);
			 $ld_totalmontoret = redondeado($ld_totalmontoret,2);

		$li_row=$li_row+1;
		$lo_hoja->write($li_row, 9, "TOTAL GENERAL", $lo_titulo);
		$lo_hoja->write($li_row, 10, $totalVentasiva, $lo_total);
		$lo_hoja->write($li_row, 11, $totalVentasint, $lo_total);
		$lo_hoja->write($li_row, 12, $ld_totbasimp8, $lo_total);
		$lo_hoja->write($li_row, 13, '', $lo_total);
		$lo_hoja->write($li_row, 14, $ld_totaldedu8, $lo_total);
		$lo_hoja->write($li_row, 15, $ld_totbasimp12, $lo_total);
		$lo_hoja->write($li_row, 16, '', $lo_total);
		$lo_hoja->write($li_row, 17, $ld_totaldedu12, $lo_total);
		$lo_hoja->write($li_row, 18, $totalImpiva, $lo_total);
		$lo_hoja->write($li_row, 19, $ld_totalmontoret, $lo_total);


		$ld_totgenadi    = ($ld_totimp8+$ld_totimp12);//Total Compras Internas Afectadas en Alicuota Reducida. Impuestos al 14% y 25%.
		  $ld_basimpga     = ($ld_totbasimp8+$ld_totbasimp12);//Total Base Imponible Compras Internas Afectadas en Alicuota General + Adicional(14% y 25%).
		  $ld_totimpred    = ($ld_totimp8);//Total Compras Internas Afectadas en Alicuota Reducida. Impuestos al 8%.
		  $ld_totimpred    = redondeado($ld_totimpred,2);
		  $ld_basimpga     = redondeado($ld_basimpga,2);
		  $ld_totcomsiniva = redondeado($ld_totcomsiniva,2);
		  $ld_totvenconiva = redondeado($ld_totvenconiva,2);
		  $ld_totbasimp    = redondeado($ld_totbasimp,2);
		  $ld_totimpuestos = redondeado($ld_totimpuestos,2);
		  $ld_totgenadi    = redondeado($ld_totgenadi,2);


		$li_row=$li_row+4;

		$lo_hoja->write($li_row, 3, "RESUMEN", $lo_titulo);
		$lo_hoja->write($li_row+1, 3, "Total de Ventas: Incluyendo IVA", $lo_resumen);
		$lo_hoja->write($li_row+2, 3, "Ventas Exentas", $lo_resumen);
		$lo_hoja->write($li_row+3, 3, "Ventas Gravables as 8,00%", $lo_resumen);
		$lo_hoja->write($li_row+4, 3, "IVA Cobrado al 8,00%", $lo_resumen);
		$lo_hoja->write($li_row+5, 3, "Ventas gravables Alicuota Gral.", $lo_resumen);
		$lo_hoja->write($li_row+6, 3, "IVA Cobrado Alicuota Gral.", $lo_resumen);
		$lo_hoja->write($li_row+7, 3, "Total IVA Cobrado", $lo_resumen);

		$lo_hoja->write($li_row, 4, '', $lo_titulo);
		$lo_hoja->write($li_row+1, 4,  $totalVentasiva, $lo_dataright);
		$lo_hoja->write($li_row+2, 4, $totalVentasint, $lo_dataright);
		$lo_hoja->write($li_row+3, 4, $ld_totbasimp8, $lo_dataright);
		$lo_hoja->write($li_row+4, 4, $ld_totaldedu8, $lo_dataright);
		$lo_hoja->write($li_row+5, 4, $ld_totbasimp12, $lo_dataright);
		$lo_hoja->write($li_row+6, 4, $ld_totaldedu12, $lo_dataright);
		$lo_hoja->write($li_row+7, 4,  $totalImpiva, $lo_dataright);


			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"libroventa.xls\"");
			header("Content-Disposition: inline; filename=\"libroventa.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
                        print("<script language=JavaScript>");
			print(" close();");
			print("</script>");
		}
?>