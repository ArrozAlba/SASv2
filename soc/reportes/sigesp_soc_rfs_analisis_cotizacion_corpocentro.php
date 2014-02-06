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
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,530,80,40); // Agregar Logo
		$io_pdf->add_texto(140,5,14,"<b>ANÁLISIS DE PRECIOS</b>");
		$io_pdf->add_texto(325,18,10,"$ad_fecha");		
		$la_data[0]["1"]="<b>Nro.:</b>";
		$la_data[0]["2"]=$as_numanacot;
		$la_anchos_col = array(27,173);
		$la_justificaciones = array("left","left");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 11,
							   "lineas"        =>0,
							   "alineacion_col"=>$la_justificaciones);
		$io_pdf->ezSetDy(-50);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedores($la_cotizaciones,$io_ds_detalle,$io_ds_detallepro,$la_countcot,&$io_pdf)
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
		//print_r ($io_ds_detallepro);
		//Imprimiendo primer titulo
		$li_a=0;
		$li_b=0;
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Cotizaciones de Proveedores</b>";
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
		
		
		
		//IMPRIMIENDO EL SEGUNDO TITULO
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>CANT.</b>";
		$la_data[0]["2"]="<b>UNID.</b>";
		$la_data[0]["3"]="<b>ARTICULOS</b>";
		$la_anchos_col = array(13,13,44);
		$la_justificaciones = array("center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 5,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
							   "color_fondo"=>array(200,200,200),
							   "grosor_lineas_externas"=>0.5,
							   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-15);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		
	    unset($la_data,$la_opciones);
		for($li_i=0;$li_i<$li_totalcotizaciones;$li_i++)
		{ 
			$li_set=464.5;
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
					$la_data[0]["1"]=number_format($la_cotizaciones[$li_s+1]["canart"],2,",",".");
					$la_data[0]["2"]=$la_cotizaciones[$li_s+1]["denunimed"];
					$la_data[0]["3"]=$la_cotizaciones[$li_s+1]["denominacion"];
					
					$la_justificaciones=array();
					$la_justificaciones = array("center","center","center");
					$la_opciones = array("color_texto"     => array(0,0,0),
										   "anchos_col"    => $la_anchos_col,
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
		$io_pdf->ezSetY(420);
			
					

			
			$li_cont=0;
			$li_x=80;
			for($li_s=0;$li_s<$li_totalcotizaciones;$li_s++)
				{    
					$ls_codpro=$la_cotizaciones[$li_s+1]["cod_pro"];
					$li_findrow=$io_ds_detallepro->find("cod_pro",$ls_codpro);
					if($li_findrow>0)
					{   $li_cont++;
						//$la_data["0"][$li_s+1]=$la_cotizaciones[$li_s+1]["nompro"];
						$la_datadd=substr($la_cotizaciones[$li_s+1]["nompro"],0,410);
						$io_pdf->add_texto($li_x,34.5,5,$la_datadd);		
						//$io_pdf->addText($li_x,481,6,$la_datadd);
						$io_ds_detallepro->deleteRow("cod_pro",$li_findrow);
						$li_x=$li_x+65;
					}
					
				}
				//unset($la_data,$la_opciones);
		
         
		//IMPRIMIENDO LOS DETALLES POR PROVEEDOR
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$li_pos=80;
		$li_count=1;
		for($li_i=0;$li_i<$la_countcot;$li_i++)
		{ 
			$io_pdf->ezSetY(480);
			if (!empty($la_data))
			   {
				 $la_data=array();
			   }
				$la_data[0]["1"]="<b>PRECIO UNIDAD</b>";
				$la_data[0]["2"]="<b>PRECIO SUB TOTAL</b>";
				$la_data[0]["3"]="<b>MAS 9% IVA</b>";
				$la_data[0]["4"]="<b>PRECIO TOTAL</b>";
								
			    		
				$la_anchos_col = array(17,15,13,20,26);
				$la_justificaciones = array("center","center","center","center");
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
				for($li_s=$li_count;$li_s<=$li_a;$li_s++)
				{ $li_z++;
					$la_data[0]["1"]=number_format($la_cotizaciones[$li_s]["preuniart"],2,",",".");
					$la_data[0]["2"]=$la_cotizaciones[$li_s]["monsubart"];
					$la_data[0]["3"]=number_format($la_cotizaciones[$li_s]["moniva"],2,",",".");
					$la_data[0]["4"]=number_format($la_cotizaciones[$li_s]["montotart"],2,",",".");
												
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
				$li_count++;
				$li_a=$li_a+$li_z;
				$li_pos+=65;
		}	
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
		//print_r ($la_cotizaciones);
		
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
	function uf_print_ganadores($as_numanacot,$as_tipsolcot,$aa_ganadores,&$io_pdf)
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
				
		$io_pdf->set_margenes(100,55,0,0);
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Resumen de Proveedores Ganadores</b>";
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
		$la_data[0]["2"]="<b>Nombre</b>";
		$la_data[0]["3"]="<b>Subtotal ".$ls_bolivares."</b>";
		$la_data[0]["4"]="<b>Total Cargos ".$ls_bolivares."</b>";
		$la_data[0]["5"]="<b>Monto Total ".$ls_bolivares."</b>";

		$la_anchos_col = array(25,145,55,55,55);
		$la_justificaciones = array("center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							 "anchos_col"    => $la_anchos_col,
							 "tamano_texto"  => 10,
							 "lineas"        => 2,
							 "alineacion_col"=> $la_justificaciones,
							 "color_fondo"=>array(232,232,232),
							 "grosor_lineas_externas"=>0.5,
							 "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		$la_data=array();
		$li_totalganadores=count($aa_ganadores);
		$li_totalsubtotal=0;
		$li_totaliva=0;
		$li_totalmonto=0;
		for($li_i=0;$li_i<$li_totalganadores;$li_i++)
		{
			$ls_proveedor		= $aa_ganadores[$li_i]["cod_pro"];
			$ls_cotizacion		= $aa_ganadores[$li_i]["numcot"];
			$ls_tipo_proveedor	= $aa_ganadores[$li_i]["tipconpro"];
			$io_class_report->uf_select_items_proveedor($ls_cotizacion,$ls_proveedor,$as_numanacot,$as_tipsolcot,$la_items,$li_totrow); 
			$io_class_report->uf_calcular_montos($li_totrow,$la_items,$la_totales,$ls_tipo_proveedor);
			$la_data[$li_i]["1"]=$ls_proveedor;
			$la_data[$li_i]["2"]=$aa_ganadores[$li_i]["nompro"];
			$la_data[$li_i]["4"]=number_format($la_totales["subtotal"],2,",",".");
			$la_data[$li_i]["5"]=number_format($la_totales["totaliva"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_totales["total"],2,",",".");
			$li_totalsubtotal+=$la_totales["subtotal"];
			$li_totaliva+=$la_totales["totaliva"];
			$li_totalmonto+=$la_totales["total"];
		}
		
		//Imprimiendo columnas
	
		$la_justificaciones=array();
		$la_justificaciones = array("center","left","right","right","right");
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
		$la_data[0]["2"]="<b>".number_format($li_totalsubtotal,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";		

		$la_anchos_col = array(25,55,55,55);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
							   "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(155,$la_data,$la_opciones);
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
		
		$io_pdf->Rectangle(230.5,479,751,8); // proveedores
		$io_pdf->line(414,479,414,487);	//VERTICAL	
		$io_pdf->line(599,479,599,487);	//VERTICAL
		$io_pdf->line(784,479,784,487);	//VERTICAL
		$io_pdf->line(32,470,32,487);	//VERTICAL
		$io_pdf->addText(85,476,9,"<b>Descripción de Articulos</b>");
		
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
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_class_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
	error_reporting(E_ALL);
	set_time_limit(3000);	
	$io_pdf=new class_pdf('LEGAL','landscape'); // Instancia de la clase PDF
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
			//print_r($la_cotizaciones);
			$li_totrow=count($la_cotizaciones);
			//print $li_totrow;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$io_ds_detalle->insertRow("codigo",$la_cotizaciones[$li_i]["codigo"]);
				$io_ds_detallepro->insertRow("cod_pro",$la_cotizaciones[$li_i]["cod_pro"]);
				$io_ds_detallepro->insertRow("nompro",$la_cotizaciones[$li_i]["nompro"]);
			}
			$io_ds_detallepro->group('cod_pro');
			$io_ds_detalle->group('codigo');
			$lb_valido=$io_class_report->uf_count_cotizaciones($ls_numanacot,$ls_countcot);
			$ls_countcot=count($ls_countcot);
			if($lb_valido)
			{
				uf_print_proveedores($la_cotizaciones,$io_ds_detalle,$io_ds_detallepro,$ls_countcot,$io_pdf);
			}
			$lb_valido=$io_class_report->uf_select_items($ls_numanacot,$ls_tipsolcot,$la_items);
			if($lb_valido)
			{
				//uf_print_items($ls_tipsolcot,$la_items,$io_pdf);
				$la_ganadores=$io_class_report->uf_select_cotizacion_analisis($ls_numanacot,$ls_tipsolcot);
				uf_print_ganadores($ls_numanacot,$ls_tipsolcot,$la_ganadores,$io_pdf);
				uf_print_pie_pagina($ls_observacion,$io_pdf);
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