<?PHP
    session_start();//CORPOCENTRO
	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();"	;
		print "close();";
		print "</script>";
	}
	//---------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_compra;
		$ls_descripcion="Generó el Reporte Análisis de Cotización";
		$lb_valido=$io_fun_compra->uf_load_seguridad_reporte("SOC","sigesp_soc_p_analisis_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_numanacot,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el banner del reporte
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 17/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],38,530,80,40); // Agregar Logo
		$io_pdf->add_texto(80,10,12.5,"<b>ANÁLISIS DE COTIZACIONES</b>");
		$io_pdf->add_texto(190,5.5,9,"<b>NUMERO</b>");
		$io_pdf->add_texto(190,10,9,"<b>FECHA</b>");
		$io_pdf->add_texto(190,15,9,"<b>LICITACION N°</b>");
		$io_pdf->Rectangle(34,529,510,42);
		$io_pdf->Rectangle(544,529,240,42);
		$io_pdf->add_texto(240,5.5,10,"<b>$as_numanacot</b>");
		$io_pdf->add_texto(240,10,10,"$ad_fecha");

		$io_pdf->ezSetDy(-4);
		$la_data[0]["1"]="";
		$la_data[0]["2"]="";		//$as_numanacot;
		$la_anchos_col = array(27,173);
		$la_justificaciones = array("left","left");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 11,
							   "lineas"        =>0,
							   "margen_horizontal"=>6,
							   "alineacion_col"=>$la_justificaciones);
		$io_pdf->ezSetDy(-48);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedores($la_cotizaciones,$io_ds_detalle,$io_ds_detallepro,$la_countcot,$ds_contcol,$li_calculado,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_proveedores
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el el listado de  proveedores participantes
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 18/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;
		$io_ds_detalle1=new class_datastore();
		$io_ds_detallepro1=new class_datastore();
		$li_totalcotizaciones=count($la_cotizaciones);
		$io_ds_detalle1->data=$io_ds_detalle->data;
		$io_ds_detallepro1->data=$io_ds_detallepro->data;
		$li_totalproveedores=$io_ds_detallepro1->getRowCount($io_ds_detallepro);

		//Imprimiendo primer titulo
		$io_pdf->ezSetY(520);
		$li_a=0;
		$li_b=0;
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>DATOS DEL PROVEEDOR</b>";
		$la_data[0]["2"]="<b>COTIZACION 1</b>";
		$la_data[0]["3"]="<b>COTIZACION 2</b>";
		$la_data[0]["4"]="<b>COTIZACION 3</b>";
		$la_anchos_col = array(70.2,65,64.8,65);
		$la_justificaciones = array("center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 7,
							   "lineas"        => 1,
							   "margen_horizontal"=>16,
							   "margen_vertical"=>2,
							   "alineacion_col"=> $la_justificaciones,
							   "grosor_lineas_externas"=>0.5,
							   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(13.9,$la_data,$la_opciones);	//primera fila del item, color gris

		for($li_i=0;$li_i<$li_totalcotizaciones;$li_i++)
		{
			$li_set=446;
			$io_pdf->ezSetY($li_set);
			if (!empty($la_data))
			   {
			     $la_data=array();
			   }
			for($li_s=0;$li_s<$li_totalcotizaciones;$li_s++)
		    {
				$ls_codigo=$la_cotizaciones[$li_s+1]["codigo"];
				$li_findrow=$io_ds_detalle->find("codigo",$ls_codigo);
				if($li_findrow>0)
				{	$li_a++;
					$la_data[0]["1"]=$li_s+1;
					$la_data[0]["2"]=$la_cotizaciones[$li_s+1]["denominacion"];

					$la_justificaciones=array();
					$la_justificaciones = array("center","center","center");
					$la_opciones = array("color_texto"     => array(0,0,0),
										   "anchos_col"    => array(10,60),
										   "tamano_texto"  => 5,
										   "lineas"        => 2,
										   "alineacion_col"=> $la_justificaciones,
										   "grosor_lineas_externas"=>0.5,
										   "grosor_lineas_internas"=>0.5);
					$io_pdf->add_tabla(10,$la_data,$la_opciones);
				}
				$io_ds_detalle->deleteRow("codigo",$li_findrow);
			}
		}
		//IMPRIMIENDO LOS PROVEEDORES
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();


		$io_pdf->ezSetY(507);
		$la_data[0]["1"]='NOMBRE:';
		$la_data[0]["2"]=$ds_contcol[0]["nombre1"];
		$la_data[0]["3"]=$ds_contcol[1]["nombre2"];
		$la_data[0]["4"]=$ds_contcol[2]["nombre3"];
		$la_justificaciones=array();
		$la_justificaciones = array("left","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => array(70,65,65,65),
							   "tamano_texto"  => 5,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
							   "grosor_lineas_externas"=>0.5,
							   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);

		$io_pdf->ezSetY(497);
		$la_data[0]["1"]='CODIGO DE PROVEEDOR:';
		$la_data[0]["2"]=$ds_contcol[0]["rif1"];
		$la_data[0]["3"]=$ds_contcol[1]["rif2"];
		$la_data[0]["4"]=$ds_contcol[2]["rif3"];
		$la_justificaciones=array();
		$la_justificaciones = array("left","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => array(70,65,65,65),
							   "tamano_texto"  => 5,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
							   "grosor_lineas_externas"=>0.5,
							   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);

		$io_pdf->ezSetY(487);
		$la_data[0]["1"]='FECHA Y REFERENCIA DE COTIZACION:';
		$la_data[0]["2"]=$ds_contcol[0]["feccot1"];
		$la_data[0]["3"]=$ds_contcol[1]["feccot2"];
		$la_data[0]["4"]=$ds_contcol[2]["feccot3"];
		$la_justificaciones=array();
		$la_justificaciones = array("left","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => array(70,65,65,65),
							   "tamano_texto"  => 5,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
							   "grosor_lineas_externas"=>0.5,
							   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);

		$io_pdf->ezSetY(477);
		$la_data[0]["1"]='DOMICILIO FISCAL:';
		$la_data[0]["2"]=$ds_contcol[0]["dirpro1"];
		$la_data[0]["3"]=$ds_contcol[1]["dirpro2"];
		$la_data[0]["4"]=$ds_contcol[2]["dirpro3"];
		$la_justificaciones=array();
		$la_justificaciones = array("left","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => array(70,65,65,65),
							   "tamano_texto"  => 5,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
							   "grosor_lineas_externas"=>0.5,
							   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);

		$ds_linea_tot1=array("subtot1"=>0,"subtot2"=>0,"subtot3"=>0);
		$ds_linea_tot2=array("iva1"=>0,"iva2"=>0,"iva3"=>0);
		$ds_linea_tot3=array("tot1"=>0,"tot2"=>0,"tot3"=>0);
		$ds_linea_tot4=array("garantia1"=>'',"garantia2"=>'',"garantia3"=>'');
		$ds_linea_tot5=array("condp1"=>'',"condp2"=>'',"condp3"=>'');
		$ds_linea_tot6=array("dfecent1"=>'',"dfecent2"=>'',"dfecent3"=>'');
		$ds_linea_tot7=array("cumple1"=>'',"cumple2"=>'',"cumple3"=>'');
		$ds_linea_tot8=array("garantia1"=>'',"garantia2"=>'',"garantia3"=>'');

		$io_pdf->ezSetY(470);
		//IMPRIMIENDO LOS DETALLES POR PROVEEDOR
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$li_pos=80;
		$li_count=1;

		$li_colcount=0;
		for($li_i=0;$li_i<$la_countcot;$li_i++)
		{
			$io_pdf->ezSetY(455.7);
			if (!empty($la_data))
			   {
				 $la_data=array();
			   }
				$la_data[0]["1"]="<b>CANTIDAD</b>";
				$la_data[0]["2"]="<b>PRECIO UNITARIO</b>";
				$la_data[0]["3"]="<b>TOTAL</b>";

				$la_anchos_col = array(21,21,23); //21.6
				$la_justificaciones = array("center","center","center");
				$la_opciones = array("color_texto"     => array(0,0,0),
									 "anchos_col"    => $la_anchos_col,
									 "tamano_texto"  => 5,
									 "lineas"        => 2,
									 "alineacion_col"=> $la_justificaciones,
									 "color_fondo"=>array(232,232,232),
									 "grosor_lineas_externas"=>0.5,
									 "grosor_lineas_internas"=>0.5);
				$io_pdf->add_tabla($li_pos,$la_data,$la_opciones);
				unset($la_data,$la_opciones);
				$li_z=0;
				$li_sumcolprecio=0;
				$li_sumcoliva=0;
				$li_sumcoltotal=0;
				for($li_s=$li_count;$li_s<=$li_a;$li_s++)
				{ $li_z++;
					$la_data[0]["1"]=$la_cotizaciones[$li_s]["canart"];
					$la_data[0]["2"]=number_format($la_cotizaciones[$li_s]["preuniart"],2,",",".");
					$la_data[0]["3"]=number_format($la_cotizaciones[$li_s]["monsubart"],2,",",".");

					$li_sumcolprecio=$li_sumcolprecio+$la_cotizaciones[$li_s]["monsubart"];
					$li_sumcoliva=$li_sumcoliva+$la_cotizaciones[$li_s]["moniva"];
					$li_sumcoltotal=$li_sumcoltotal+$la_cotizaciones[$li_s]["montotart"];

					$la_justificaciones=array();
					$la_justificaciones = array("center","right","right","center");
					$la_opciones = array("color_texto"   => array(0,0,0),
										 "anchos_col"    => $la_anchos_col,
										 "tamano_texto"  => 5,
										 "lineas"        => 2,
										 "alineacion_col"=> $la_justificaciones,
										 "grosor_lineas_externas"=>0.5,
										 "grosor_lineas_internas"=>0.5);
					$io_pdf->add_tabla($li_pos,$la_data,$la_opciones);
					unset($la_data,$la_opciones);
					$li_count=$li_s;
				}

				if ($li_colcount==0)
				{

					$ds_linea_tot1[$li_colcount]["subtot1"]=$li_sumcolprecio;
					$ds_linea_tot2[$li_colcount]["iva1"]=$li_sumcoliva;
					$ds_linea_tot3[$li_colcount]["tot1"]=$li_sumcoltotal;

				}
				if ($li_colcount==1)
				{
					$ds_linea_tot1[$li_colcount]["subtot2"]=$li_sumcolprecio;
					$ds_linea_tot2[$li_colcount]["iva2"]=$li_sumcoliva;
					$ds_linea_tot3[$li_colcount]["tot2"]=$li_sumcoltotal;
				}
				if ($li_colcount==2)
				{
					$ds_linea_tot1[$li_colcount]["subtot3"]=$li_sumcolprecio;
					$ds_linea_tot2[$li_colcount]["iva3"]=$li_sumcoliva;
					$ds_linea_tot3[$li_colcount]["tot3"]=$li_sumcoltotal;
				}
				$li_colcount++;
				$li_count++;
				$li_a=$li_a+$li_z;
				$li_pos+=65;
		}

		//print $li_calculado.'<br>';
		//print_r($ds_linea_tot);
		//		print_r($ds_linea_tot1).'<br>';
		//		print_r($ds_linea_tot2).'<br>';
		//		print_r($ds_linea_tot3).'<br>';
	}//fin de uf_print_proveedores
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedores2($la_cotizaciones,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_proveedores
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el el listado de  proveedores participantes
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 18/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;
		$li_totalcotizaciones=count($la_cotizaciones);

		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Cotizaciones</b>";
		$la_anchos_col = array(335);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
							   "color_fondo"=>array(200,200,200),
							   "grosor_lineas_externas"=>0.5,
							   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-10);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris

		//$la_data[$li_i]["3"]=$la_cotizaciones[$li_i+1]["nompro"]; NOMBRE DEL PROVEEDOR

		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		for($li_i=0;$li_i<$li_totalcotizaciones;$li_i++)
		{
			$la_data[$li_i]["1"]="<b>Cantidad</b>";
			$la_data[$li_i]["2"]="<b>Unidad Medida</b>";
			$la_data[$li_i]["3"]="<b>Denominacion</b>";
			$la_data[$li_i]["4"]="<b>Precio/Unid. ".$ls_bolivares."</b>";
			$la_data[$li_i]["5"]="<b>Sub-Total</b>";
			$la_data[$li_i]["6"]="<b>I.V.A.</b>";
			$la_data[$li_i]["7"]="<b>Monto Total ".$ls_bolivares."</b>";
		}


			$la_anchos_col = array(15,25,40,25,25,15,30);
			$la_justificaciones = array("center","center","center","center","center","center","center");
			$la_opciones = array("color_texto"     => array(0,0,0),
								   "anchos_col"    => $la_anchos_col,
								   "tamano_texto"  => 10,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(10,$la_data,$la_opciones);


		//Imprimiendo columnas
		$la_data=array();
		for($li_i=0;$li_i<$li_totalcotizaciones;$li_i++)
		{
			$la_data[$li_i]["1"]=number_format($la_cotizaciones[$li_i+1]["canart"],2,",",".");
			$la_data[$li_i]["2"]=$la_cotizaciones[$li_i+1]["denunimed"];
			$la_data[$li_i]["3"]=$la_cotizaciones[$li_i+1]["denominacion"];
			$la_data[$li_i]["4"]=number_format($la_cotizaciones[$li_i+1]["preuniart"],2,",",".");
			$la_data[$li_i]["5"]=$la_cotizaciones[$li_i+1]["monsubart"];
			$la_data[$li_i]["6"]=number_format($la_cotizaciones[$li_i+1]["moniva"],2,",",".");
			$la_data[$li_i]["7"]=number_format($la_cotizaciones[$li_i+1]["montotart"],2,",",".");

			//$la_datacot=[$li_i]= array('1'=>$ls_cantart,'2'=>$ls_denunimed,'3'=>$ls_denominacion,'4'=>$ls_preuniart,
			//				             '5'=>$ls_monsubart,'6'=>$ls_moniva,'7'=>$ls_montotart);
		}

			$la_justificaciones=array();
			$la_justificaciones = array("center","center","left","center","right","right","center");
			$la_opciones = array("color_texto"     => array(0,0,0),
								   "anchos_col"    => $la_anchos_col,
								   "tamano_texto"  => 10,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(10,$la_data,$la_opciones);
	}//fin de uf_print_proveedores
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_items($as_tipsolcot,$la_items,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los items del analisis de cotizacion y su respectivo proveedor
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 17/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;
		$li_totalitems=count($la_items);
		//print_r ($la_items);
		if($as_tipsolcot=="B")
			$ls_item="Bienes";
		else
			$ls_item="Servicios";

		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>$ls_item</b>";
		$la_anchos_col = array(335);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(200,200,200),
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris

		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Código</b>";
		$la_data[0]["2"]="<b>Descripcion</b>";
		$la_data[0]["3"]="<b>Proveedor</b>";
		$la_data[0]["4"]="<b>Cant.</b>";
		$la_data[0]["5"]="<b>Precio/Unid. ".$ls_bolivares."</b>";
		$la_data[0]["6"]="<b>I.V.A. ".$ls_bolivares."</b>";
		$la_data[0]["7"]="<b>Monto Total ".$ls_bolivares."</b>";
		$la_data[0]["8"]="<b>Observación</b>";

		$la_anchos_col = array(25,35,75,30,35,35,35,65);
		$la_justificaciones = array("center","center","center","center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 2,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(232,232,232),
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);

		//Imprimiendo columnas
		$la_data=array();
		$li_totalprecio=0;
		$li_totaliva=0;
		$li_totalmonto=0;
		for($li_i=0;$li_i<$li_totalitems;$li_i++)
		{
			$la_data[$li_i]["1"]=trim($la_items[$li_i+1]["codigo"]);
			$la_data[$li_i]["2"]=$la_items[$li_i+1]["denominacion"];
			$la_data[$li_i]["3"]=$la_items[$li_i+1]["nompro"];
			$la_data[$li_i]["4"]=number_format($la_items[$li_i+1]["cantidad"],2,",",".");
			$la_data[$li_i]["5"]=number_format($la_items[$li_i+1]["precio"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_items[$li_i+1]["moniva"],2,",",".");
			$la_data[$li_i]["7"]=number_format($la_items[$li_i+1]["monto"],2,",",".");
			$la_data[$li_i]["8"]=$la_items[$li_i+1]["obsanacot"];
			$li_totalprecio+=$la_items[$li_i+1]["precio"];
			$li_totaliva+=$la_items[$li_i+1]["moniva"];
			$li_totalmonto+=$la_items[$li_i+1]["monto"];
		}

			$la_justificaciones=array();
			$la_justificaciones = array("center","left","left","right","right","right","right","left");
			$la_opciones = array("color_texto"     => array(0,0,0),
								   "anchos_col"    => $la_anchos_col,
								   "tamano_texto"  => 9,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
									   "grosor_lineas_externas"=>0.5,
									   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(10,$la_data,$la_opciones);

		//imprimiendo totales
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Totales ".$ls_bolivares."</b>";
		$la_data[0]["2"]="<b>".number_format($li_totalprecio,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";

		$la_anchos_col = array(30,35,35,35);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 2,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(145,$la_data,$la_opciones);
	}//fin de uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_ganadores($ds_contcol,$la_cotizaciones,$la_countcot,$io_ds_detalle,$as_numanacot,$as_tipsolcot,$aa_ganadores,$ds_linea_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 26/08/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;
		$li_totalcotizaciones=count($la_cotizaciones);
		$li_a=0;
		$ds_linea_tot1=array();
		$ds_linea_tot2=array();
		$ds_linea_tot3=array();

		$ls_codp1=$ds_contcol[0]["codpro1"];
		$ls_codp2=$ds_contcol[1]["codpro2"];
		$ls_codp3=$ds_contcol[2]["codpro3"];
		$subt1=0;
		$subt2=0;
		$subt3=0;
		$subiva1=0;
		$subiva2=0;
		$subiva3=0;
		$subtot1=0;
		$subtot2=0;
		$subtot3=0;

		for($li_i=0;$li_i<$li_totalcotizaciones;$li_i++)
		{
			if ($la_cotizaciones[$li_i+1]["cod_pro"]==$ls_codp1)
			{
				$subt1=$subt1+$la_cotizaciones[$li_i+1]["preuniart"];
				$subiva1=$subiva1+$la_cotizaciones[$li_i+1]["moniva"];
				$subtot1=$subtot1+$la_cotizaciones[$li_i+1]["montotart"];
			}
			if ($la_cotizaciones[$li_i+1]["cod_pro"]==$ls_codp2)
			{
				$subt2=$subt2+$la_cotizaciones[$li_i+1]["preuniart"];
				$subiva2=$subiva2+$la_cotizaciones[$li_i+1]["moniva"];
				$subtot2=$subtot2+$la_cotizaciones[$li_i+1]["montotart"];
			}
			if ($la_cotizaciones[$li_i+1]["cod_pro"]==$ls_codp3)
			{
				$subt3=$subt3+$la_cotizaciones[$li_i+1]["preuniart"];
				$subiva3=$subiva3+$la_cotizaciones[$li_i+1]["moniva"];
				$subtot3=$subtot3+$la_cotizaciones[$li_i+1]["montotart"];
			}
		}

		$ds_linea_tot1=array("subtot1"=>$subt1,"subtot2"=>$subt2,"subtot3"=>$subt3);
		$ds_linea_tot2=array("iva1"=>$subiva1,"iva2"=>$subiva2,"iva3"=>$subiva3);
		$ds_linea_tot3=array("tot1"=>$subtot1,"tot2"=>$subtot2,"tot3"=>$subtot3);
		$ds_linea_tot4=array("garantia1"=>'',"garantia2"=>'',"garantia3"=>'');
		$ds_linea_tot5=array("condp1"=>'',"condp2"=>'',"condp3"=>'');
		$ds_linea_tot6=array("dfecent1"=>'',"dfecent2"=>'',"dfecent3"=>'');
		$ds_linea_tot7=array("cumple1"=>'',"cumple2"=>'',"cumple3"=>'');
		$ds_linea_tot8=array("asist1"=>'',"asist2"=>'',"asist3"=>'');

		$ds_linea_tot=array($ds_linea_tot1,$ds_linea_tot2,$ds_linea_tot3,$ds_linea_tot4,$ds_linea_tot5,$ds_linea_tot6,$ds_linea_tot7,$ds_linea_tot8);

		$io_pdf->set_margenes(100,55,0,0);
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Sub Total</b>";
		$la_data[0]["2"]=number_format($ds_linea_tot[0]["subtot1"],2,",",".");
		$la_data[0]["3"]="<b>Sub Total</b>";
		$la_data[0]["4"]=number_format($ds_linea_tot[0]["subtot2"],2,",",".");
		$la_data[0]["5"]="<b>Sub Total</b>";
		$la_data[0]["6"]=number_format($ds_linea_tot[0]["subtot3"],2,",",".");
		$la_anchos_col = array(42,23,42,23,42,23);
		$la_justificaciones = array("left","center","left","center","left","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 7,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		//$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(84,$la_data,$la_opciones);

		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>IVA</b>";
		$la_data[0]["2"]=number_format($ds_linea_tot[1]["iva1"],2,",",".");
		$la_data[0]["3"]="<b>IVA</b>";
		$la_data[0]["4"]=number_format($ds_linea_tot[1]["iva2"],2,",",".");
		$la_data[0]["5"]="<b>IVA</b>";
		$la_data[0]["6"]=number_format($ds_linea_tot[1]["iva3"],2,",",".");
		$la_anchos_col = array(42,23,42,23,42,23);
		$la_justificaciones = array("left","center","left","center","left","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 7,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		//$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(84,$la_data,$la_opciones);

		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>TOTAL</b>";
		$la_data[0]["2"]=number_format($ds_linea_tot[2]["tot1"],2,",",".");
		$la_data[0]["3"]="<b>TOTAL</b>";
		$la_data[0]["4"]=number_format($ds_linea_tot[2]["tot2"],2,",",".");
		$la_data[0]["5"]="<b>TOTAL</b>";
		$la_data[0]["6"]=number_format($ds_linea_tot[2]["tot3"],2,",",".");
		$la_anchos_col = array(42,23,42,23,42,23);
		$la_justificaciones = array("left","center","left","center","left","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 7,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		//$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(84,$la_data,$la_opciones);

		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["0"]="GARANTIAS";
		$la_data[0]["1"]=$ds_linea_tot[3]["garantia1"];
		$la_data[0]["2"]=$ds_linea_tot[3]["garantia2"];
		$la_data[0]["3"]=$ds_linea_tot[3]["garantia3"];
		$la_anchos_col = array(69,65,65,65);
		$la_justificaciones = array("left","center","left","center","left","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 7,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		//$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(15,$la_data,$la_opciones);

		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["0"]="CONDICIONES DE PAGO";
		$la_data[0]["1"]=$ds_linea_tot[4]["condp1"];
		$la_data[0]["2"]=$ds_linea_tot[4]["condp2"];
		$la_data[0]["3"]=$ds_linea_tot[4]["condp3"];
		$la_anchos_col = array(69,65,65,65);
		$la_justificaciones = array("left","center","left","center","left","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 7,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		//$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(15,$la_data,$la_opciones);

		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["0"]="FECHA DE ENTREGA";
		$la_data[0]["1"]=$ds_linea_tot[5]["dfecent1"];
		$la_data[0]["2"]=$ds_linea_tot[5]["dfecent2"];
		$la_data[0]["3"]=$ds_linea_tot[5]["dfecent3"];
		$la_anchos_col = array(69,65,65,65);
		$la_justificaciones = array("left","center","left","center","left","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 7,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		//$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(15,$la_data,$la_opciones);

		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["0"]="CUMPLE CON ESPECIFICACIONES";
		$la_data[0]["1"]=$ds_linea_tot[6]["cumple1"];
		$la_data[0]["2"]=$ds_linea_tot[6]["cumple2"];
		$la_data[0]["3"]=$ds_linea_tot[6]["cumple3"];
		$la_anchos_col = array(69,65,65,65);
		$la_justificaciones = array("left","center","left","center","left","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 7,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		//$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(15,$la_data,$la_opciones);

		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["0"]="ASISTENCIA TECNICA";
		$la_data[0]["1"]=$ds_linea_tot[7]["asist1"];
		$la_data[0]["2"]=$ds_linea_tot[7]["asist2"];
		$la_data[0]["3"]=$ds_linea_tot[7]["asist3"];
		$la_anchos_col = array(69,65,65,65);
		$la_justificaciones = array("left","center","left","center","left","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 7,
							 "lineas"        => 1,
							 "alineacion_col"=> $la_justificaciones,
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		//$io_pdf->ezSetDy(-5);
		$io_pdf->add_tabla(15,$la_data,$la_opciones);


	}//fin de uf_print_detalle

	//------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_pagina($observacion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_pagina
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el pie del reporte
		//	   Creado Por: Ing. Laura Cabré                  Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 17/06/2007                 Fecha de Modificación: 01/04/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(30,100,950,70); // primer rectángulo de firmas

		$io_pdf->addText(40,160,8,"<b>OBSERVACIONES</b>");
		$io_pdf->Rectangle(30,30,950,70);
		$io_pdf->addText(40,90,8,"<b>ELABORADO Y ANALIZADO POR:</b>");
		$io_pdf->addText(40,32,8,"<b>FECHA:</b>");
		$io_pdf->line(250,30,250,100);	//VERTICAL
		$io_pdf->addText(263,90,8,"<b>REVISADO POR:</b>");
		$io_pdf->addText(263,32,8,"<b>FECHA:</b>");
		$io_pdf->line(500,30,500,100);	//VERTICAL
		$io_pdf->addText(513,90,8,"<b>AREA DE COMPRAS</b>");
		$io_pdf->addText(513,32,8,"<b>FECHA:</b>");
		$io_pdf->line(740,30,740,100);	//VERTICAL
		$io_pdf->addText(743,90,8,"<b>GCIA. ADMINISTRACION</b>");
		$io_pdf->addText(743,32,8,"<b>FECHA:</b>");


	}// end function uf_print_pie_pagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("sigesp_soc_class_report.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_datastore.php");
	$io_class_report = new sigesp_soc_class_report();
	$io_funciones    = new class_funciones();
	$io_fun_compra   = new class_funciones_soc();
	$ls_tiporeporte=$io_fun_compra->uf_obtenervalor_get("tiporeporte",1);
	$ls_bolivares="Bs.";
	$io_ds_detalle=new class_datastore();
	$io_ds_detallecot=new class_datastore();
	$io_ds_detallepro=new class_datastore();
	$io_ds_grupodetallepro=new class_datastore();

	$ds_linea_tot=array();

	$li_calculado = 0;

	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_class_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
	error_reporting(E_ALL);
	set_time_limit(3000);
	$io_pdf=new class_pdf('LETTER','landscape'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->numerar_paginas(7);
	$io_pdf->set_margenes(10,30,3,3);
	$ls_tipsolcot=$_GET["tipsolcot"];
	$ls_numanacot=$_GET["numanacot"];
	$ld_fecha=$_GET["fecha"];
	$ls_observacion=$_GET["observacion"];
	$lb_valido=uf_insert_seguridad();
	if($lb_valido)
	{
		uf_print_encabezado_pagina($ls_numanacot,$ld_fecha,$io_pdf);
		$lb_valido=$io_class_report->uf_cargar_cotizaciones_esp($ls_numanacot,$la_cotizaciones);
		if($lb_valido)
		{
			$li_totrow=count($la_cotizaciones);
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$io_ds_detalle->insertRow("codigo",$la_cotizaciones[$li_i]["codigo"]);
				$io_ds_detallepro->insertRow("cod_pro",$la_cotizaciones[$li_i]["cod_pro"]);
				$io_ds_detallepro->insertRow("nompro",$la_cotizaciones[$li_i]["nompro"]);
				$io_ds_detallepro->insertRow("dirpro",$la_cotizaciones[$li_i]["dirpro"]);
				$io_ds_detallepro->insertRow("rifpro",$la_cotizaciones[$li_i]["rifpro"]);
				$io_ds_detallepro->insertRow("feccot",$la_cotizaciones[$li_i]["feccot"]);
			}
			$io_ds_detallepro->group('cod_pro');
			$io_ds_detalle->group('codigo');

			$ds_col1=array();
			$ds_col2=array();
			$ds_col3=array();

			$li_cant_pro=$io_ds_detallepro->getRowCount('cod_pro');
			$cCol=1;
			//print_r($io_ds_detallepro);


			for ($li_f=1;$li_f<=$li_cant_pro;$li_f++)
			{
				$codpro=$io_ds_detallepro->getValue('cod_pro',$li_f );
				$nompro=$io_ds_detallepro->getValue('nompro',$li_f );
				$rifpro=$io_ds_detallepro->getValue('rifpro',$li_f );
				$dirpro=$io_ds_detallepro->getValue('dirpro',$li_f );
				$feccot=$io_ds_detallepro->getValue('feccot',$li_f );
				//$codpro=$io_ds_detallepro->getValue('codpro',$li_f );
				if ($codpro<>'')
				{
					if ($cCol==1)
					{
						$ds_col1 = array("nombre1"=>$nompro,"rif1"=>$rifpro,"dirpro1"=>$dirpro,"feccot1"=>$feccot,"codpro1"=>$codpro); //
					}
					if ($cCol==2)
					{
						$ds_col2 = array("nombre2"=>$nompro,"rif2"=>$rifpro,"dirpro2"=>$dirpro,"feccot2"=>$feccot,"codpro2"=>$codpro); //
					}
					if ($cCol==3)
					{
						$ds_col3 = array("nombre3"=>$nompro,"rif3"=>$rifpro,"dirpro3"=>$dirpro,"feccot3"=>$feccot,"codpro3"=>$codpro); //
					}
					$io_ds_grupodetallepro->insertRow("nompro",$nompro);
					$io_ds_grupodetallepro->insertRow("rifpro",$rifpro);
					$io_ds_grupodetallepro->insertRow("feccot",$feccot);
					$io_ds_grupodetallepro->insertRow("dirpro",$dirpro);

					$cCol++;
				}
			}
			//print_r($ds_col1);
			$ds_contcol=array($ds_col1,$ds_col2,$ds_col3);
			$lb_valido=$io_class_report->uf_count_cotizaciones($ls_numanacot,$ls_countcot);
			$ls_countcot=count($ls_countcot);
			if($lb_valido)
			{
				uf_print_proveedores($la_cotizaciones,$io_ds_detalle,$io_ds_detallepro,$ls_countcot,$ds_contcol,$li_calculado,$io_pdf);
			}
			$lb_valido=$io_class_report->uf_select_items($ls_numanacot,$ls_tipsolcot,$la_items);
			if($lb_valido)
			{
				//uf_print_items($ls_tipsolcot,$la_items,$io_pdf);
				$la_ganadores=$io_class_report->uf_select_cotizacion_analisis($ls_numanacot,$ls_tipsolcot);
				uf_print_ganadores($ds_contcol,$la_cotizaciones,$ls_countcot,$io_ds_detalle,$ls_numanacot,$ls_tipsolcot,$la_ganadores,$ds_linea_tot,$io_pdf);
				//uf_print_pie_pagina($ls_observacion,$io_pdf);
				$io_pdf->ezStream();
				unset($io_pdf);
			}
		}
	}
	if(!$lb_valido)
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que reportar');");
		print(" close();");
		print("</script>");
	}
?>
