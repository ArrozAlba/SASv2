<?PHP
    session_start(); 	  
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();";
		print "close();";
		print "</script>";		
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($ls_banco,$ls_mes,$ls_anio,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 21/11/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		 $la_data=array();
		 $la_anchos_col=array();
		 $la_justificaciones=array();
		 $la_opciones=array();	
		 $la_data[0]["1"]="<b>CONCILIACION BANCARIA\n$ls_banco\nMES DE ".strtoupper($ls_mes)." $ls_anio</b>";
		 $la_data[0]["2"]="";	
		
		 $li_ancho=$_SESSION["ls_width"];
		 $li_alto=$_SESSION["ls_height"];
		 $io_pdf->convertir_valor_px_mm($li_ancho);
		 $io_pdf->convertir_valor_px_mm($li_alto);

		 $la_anchos_col = array(130,$li_ancho+4);
		 $la_justificaciones = array("center","center");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 11,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>6,
							   "margen_vertical"=>(($li_alto)/2));
		 $io_pdf->add_tabla(10,$la_data,$la_opciones);
		 $li_alto_usado=$io_pdf->get_alto_disponible();
		 $io_pdf->convertir_valor_mm_px($li_alto_usado);
		 $li_ancho=$_SESSION["ls_width"];
		 $io_pdf->convertir_valor_px_mm($li_ancho);         
		 $io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],405,680,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		 //------------Fecha--------------------------------//
		 $la_data=array();
		 $la_anchos_col=array();
		 $la_justificaciones=array();
		 $la_opciones=array();
		 
		 $la_data[0]["1"]="<b>FECHA: ".strtoupper($ls_mes)." $ls_anio</b>";
		 $la_anchos_col = array($li_ancho+4);
		 $la_justificaciones = array("center");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1,
							   "margen_vertical"=>2);
		 $io_pdf->add_tabla(138.3,$la_data,$la_opciones);
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_banco,$ls_ctaban,$ls_nomtipcta,$ldec_saldoant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ls_numdoc : Numero de documento
		//	    		   ls_nomban : Nombre del banco
		//				   ls_cbtan  : Cuenta del banco
		//				   ls_chevau : Voucher del cheuqe
		//				   ls_nomproben: Nombre del proveedor o beneficiario
		//				   ls_solicitudes: Solicitudes canceladas con el cheque					  
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los datos basicos del cheque
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 22/11/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
		 $la_data=array();
		 $la_anchos_col=array();
		 $la_justificaciones=array();
		 $la_opciones=array();		 
		 $la_data[0]["1"]="<b>CUENTA ".strtoupper($ls_nomtipcta)." Nº $ls_ctaban</b>";
		 $la_anchos_col = array(110);
		 $la_justificaciones = array("left");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 10,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>0,
							   "margen_vertical"=>2);
		 $io_pdf->add_tabla(10,$la_data,$la_opciones);
		 
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_saldo_libro ($ls_fecha,$ls_saldo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_saldo_libro
		//		    Acess: private 
		//	    Arguments: ls_fecha // Fecha del reporte
		//	   			   io_pdf // Objeto PDF
		//				   ls_saldo //saldo a imprimir 			
		//    Description: función que imprime el saldo segun libro
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 21/11/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 /*-------------------------Fecha--------------*/
		 $io_pdf->ezSetDy(-10);
		 $li_pos=$io_pdf->y;
		 $la_data=array();
		 $la_anchos_col=array();
		 $la_justificaciones=array();
		 $la_opciones=array();		 
		 $la_data[0]["1"]="<b>SALDO SEGUN LIBRO AL $ls_fecha</b>";
		 $la_anchos_col = array(110);
		 $la_justificaciones = array("left");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 10,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>0,
							   "margen_vertical"=>2);
		 $io_pdf->add_tabla(10,$la_data,$la_opciones);
		 /*-------------------------saldo--------------*/
		 $io_pdf->ezSetY($li_pos);			
		 $la_data=array();
		 $la_anchos_col=array();
		 $la_justificaciones=array();
		 $la_opciones=array();		 
		 $la_data[0]["1"]="<b>$ls_saldo</b>";
		 $la_anchos_col = array(56);
		 $la_justificaciones = array("right");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>2);
		 $io_pdf->add_tabla(139,$la_data,$la_opciones);
		 
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 22/11/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $io_pdf->ezSetDy(-15);
		 $la_opciones=array('left'=>28);
		 $la_titulos[0]["1"]="<b>FECHA</b>";
		 $la_titulos[0]["2"]="<b>DOCUMENTO</b>";
		 $la_titulos[0]["3"]="<b>PROVEEDOR/BENEFICIARIO</b>";
		 $la_titulos[0]["4"]="<b>MONTO</b>";		
		 $la_anchos_col = array(20,35,90,40);
		 $la_justificaciones = array("center","center","left","right");
		 $la_justificaciones_titulos = array("center","center","center","center");
		 $la_opciones_data = array("color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 9,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>2,
								   "margen_vertical"=>1);
		$la_opciones_titulos = array("color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 8,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones_titulos,
								   "margen_horizontal"=>2,
								   "margen_vertical"=>1);
		 for($li_i=0;$li_i<count($la_data);$li_i++)
		 {
		 	$la_data_aux=array();
			$la_data_aux=$la_data[$li_i]["data"];
			$ls_nombre=$la_data[$li_i]["nombre"];
			$ls_tipo=$la_data[$li_i]["tipo"];
			if(count($la_data_aux)>0)
			{
				$io_pdf->ezText("<b>".$ls_tipo."     ".$ls_nombre."</b>",9,$la_opciones);
				$io_pdf->add_tabla(10,$la_titulos,$la_opciones_titulos);
				$io_pdf->ezSetDy(-0.5);	 		
				$io_pdf->add_tabla(10,$la_data_aux,$la_opciones_data);	
				$io_pdf->ezSetDy(-15);	 	
				//------Imprimiendo el total----------------
				$li_total=0;
				for($li_k=0;$li_k<count($la_data_aux);$li_k++)
				{
					$li_total=$li_total+uf_convertir($la_data_aux[$li_k]["monto"]);
				}
				//$io_pdf->ezSetDy(+30);	
				$la_opciones_2=array('left'=>250);
				$li_pos=$io_pdf->y;
				$io_pdf->ezText("<b>TOTAL $ls_nombre EN TRANSITO</b>",9,$la_opciones_2);//
				$io_pdf->y=$li_pos;
				if($ls_tipo=="MAS")
					$la_data_monto[0]["1"]="<b>".number_format($li_total,2,",",".")."</b>";					
				else
					$la_data_monto[0]["1"]="<b>".number_format($li_total,2,",",".")."</b>";				
				$la_justificaciones = array();
				$la_anchos_col = array();
				$la_justificaciones = array("right");
				$la_anchos_col = array(40);
				$la_opciones_monto = array("color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 9,
								   "lineas"=>0,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>2,
								   "margen_vertical"=>1);
				$io_pdf->add_tabla(155,$la_data_monto,$la_opciones_monto);
				$io_pdf->ezSetDy(-15);		
			}	
			
		 }
		 
		
	}// end function uf_print_detalle
	//------------------------------------------------------------------------------------------------
	function uf_print_saldo_banco ($ls_fecha,$ls_saldo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_saldo_libro
		//		    Acess: private 
		//	    Arguments: ls_fecha // Fecha del reporte
		//	   			   io_pdf // Objeto PDF
		//				   ls_saldo //saldo a imprimir 			
		//    Description: función que imprime el saldo segun libro
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 21/11/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 /*-------------------------Fecha--------------*/
		 $io_pdf->ezSetDy(-10);
		 $li_pos=$io_pdf->y;
		 $la_data=array();
		 $la_anchos_col=array();
		 $la_justificaciones=array();
		 $la_opciones=array();		 
		 $la_data[0]["1"]="<b>SALDO SEGUN BANCO AL $ls_fecha</b>";
		 $la_anchos_col = array(110);
		 $la_justificaciones = array("left");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 10,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>0,
							   "margen_vertical"=>2);
		 $io_pdf->add_tabla(10,$la_data,$la_opciones);
		 /*-------------------------saldo--------------*/
		 $io_pdf->ezSetY($li_pos);			
		 $la_data=array();
		 $la_anchos_col=array();
		 $la_justificaciones=array();
		 $la_opciones=array();		 
		 $la_data[0]["1"]="<b>$ls_saldo</b>";
		 $la_anchos_col = array(56);
		 $la_justificaciones = array("right");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>2);
		 $io_pdf->add_tabla(139,$la_data,$la_opciones);
		 
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_autorizacion(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_autorizacion
		//		    Acess: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función el final del voucher 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $io_pie = $io_pdf->openObject();
		$io_pdf->line(30,80,550,80);
		$io_pdf->line(30,20,550,20);
		$io_pdf->line(30,80,30,20);
		$io_pdf->line(550,80,550,20);
		$io_pdf->addText(40,70,9,'<b>Elaborado por:</b>'); // Agregar el título
		$io_pdf->line(200,80,200,20);
		$io_pdf->addText(220,70,9,'<b>Revisado por </b>'); // Agregar el título
		$io_pdf->line(400,80,400,20);
		$io_pdf->addText(420,70,9,'<b>Aprobado por </b>'); // Agregar el título
		$io_pdf->closeObject();
		$io_pdf->addObject($io_pie,'all');
	}	
	
	function uf_convertir($ls_numero)
	{
		$ls_numero=str_replace(".","",$ls_numero);
		$ls_numero=str_replace(",",".",$ls_numero);
		return $ls_numero;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("scb_report_conciliacion.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once('../../shared/class_folder/class_fecha.php');	
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_datastore.php");	

	require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_monedabsf=new sigesp_c_reconvertir_monedabsf();				

	$in			  = new sigesp_include();
	$con		  =	$in->uf_conectar();
	$io_sql		  = new class_sql($con);	
	$io_report	  = new scb_report_conciliacion($con);
	$io_funciones = new class_funciones();	
	$io_fecha     = new class_fecha();		
	$ds_concil	  = new class_datastore();	
	$io_fecha	  = new class_fecha();
	
	$ls_codemp      = $_SESSION["la_empresa"]["codemp"];
	$ls_codban      = $_GET["codban"];
	$ls_nomban      = $_GET["nomban"];
	$ls_ctaban      = $_GET["ctaban"];
	$ls_mesano      = $_GET["mesano"];		
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if ($ls_tiporeporte==1)
	   {
		 require_once("scb_report_conciliacionbsf.php");
		 $io_report = new scb_report_conciliacionbsf($con);
		 $ls_tipbol = 'Bs.F.';
		$ldec_salseglib = $_GET["salseglib"];
		$ldec_salsegbco = $_GET["salsegbco"];	 
		$ldec_salseglib=$io_monedabsf->uf_convertir_monedabsf($ldec_salseglib,$_SESSION["la_empresa"]["candeccon"],$_SESSION["la_empresa"]["tipconmon"],1000,$_SESSION["la_empresa"]["redconmon"]);
		$ldec_salsegbco=$io_monedabsf->uf_convertir_monedabsf($ldec_salsegbco,$_SESSION["la_empresa"]["candeccon"],$_SESSION["la_empresa"]["tipconmon"],1000,$_SESSION["la_empresa"]["redconmon"]);
	   }
	 else
	 {
		$ldec_salseglib = $_GET["salseglib"];
		$ldec_salsegbco = $_GET["salsegbco"];	 
	 }
	$data=$io_report->uf_obtener_mov_conciliacion($ls_mesano,$ls_codban,$ls_ctaban,$ldec_salseglib,&$ldec_salsegbco);
	$ls_tipo_cuenta=$io_report->uf_tipo_cuenta($ls_codban,$ls_ctaban);
	$ds_concil->data=$data;
	error_reporting(E_ALL);
	set_time_limit(1800);
	$li_totrow=$ds_concil->getRowCount("numdoc");
	if(($data===false))
	{
		?>
		<script language="javascript">
			alert("Error al buscar datos de la conciliación");
			close();
		</script>
		<?php
	}	
	$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->set_margenes(20,30,3,3); // Configuración de los margenes en centímetros
	$ld_fechasta=$io_fecha->uf_last_day(substr($ls_mesano,0,2),substr($ls_mesano,2,4));	
	$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ls_mesano,0,2));
	$ls_anio=substr($ls_mesano,2,4);
	uf_print_encabezado_pagina($ls_nomban,$ls_mes,$ls_anio,&$io_pdf); //Se imprime la tabla de la cabecera
 	$li_temp=1;
	uf_print_cabecera($ls_nomban,$ls_ctaban,$ls_tipo_cuenta,'',$io_pdf); // Se imprime la cuenta del reporte
	uf_print_saldo_libro ($ld_fechasta,number_format($ldec_salseglib,2,",","."),&$io_pdf);// Se imprime el saldo segun libro	
	$la_data=array();
	$la_data_cheque_mas      = array();
	$la_data_cheque_menos    = array();
	$la_data_nota_deb_mas    = array();
	$la_data_nota_deb_menos  = array();
	$la_data_nota_cred_mas   = array();
	$la_data_nota_cred_menos = array();
	$la_data_retiro_mas      = array();
	$la_data_retiro_menos    = array();
	$la_data_deposito_mas    = array();
	$la_data_deposito_menos  = array();
	$li_temp_cheque_mas      = 0;
	$li_temp_cheque_menos    = 0;
	$li_temp_nota_deb_mas    = 0;
	$li_temp_nota_deb_menos  = 0;
	$li_temp_nota_cred_mas   = 0;
	$li_temp_nota_cred_menos = 0;
	$li_temp_retiro_mas      = 0;
	$li_temp_retiro_menos    = 0;
	$li_temp_deposito_mas    = 0;
	$li_temp_deposito_menos  = 0;
	
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		$li_temp=$li_temp+1;

		$li_totprenom = 0;
		$ldec_mondeb  = 0;
		$ldec_monhab  = 0;
		$li_totant    = 0;		
		$ls_tipo      = $ds_concil->getValue("tipo",$li_i);
		$ls_suma      = $ds_concil->getValue("suma",$li_i);
		$ls_codope    = $ds_concil->getValue("codope",$li_i);
		$ls_numdoc    = $ds_concil->getValue("numdoc",$li_i);
		$ls_nomproben = $ds_concil->getValue("nomproben",$li_i);
		$ld_fecmov    = $ds_concil->getValue("fecmov",$li_i);
		$ldec_monto   = $ds_concil->getValue("monto",$li_i);
		$ld_fecmov    = $io_funciones->uf_convertirfecmostrar($ld_fecmov);
		$ls_item      = $ls_numdoc."  ".$ls_nomproben."   ".$ld_fecmov;
		if($ls_suma=='+')//En caso que sean mas
		{
			switch($ls_codope)
			{
				case "CH":
					$la_data_cheque_mas[$li_temp_cheque_mas]["fecha"]=$ld_fecmov;
					$la_data_cheque_mas[$li_temp_cheque_mas]["numdoc"]=$ls_numdoc;
					$la_data_cheque_mas[$li_temp_cheque_mas]["nombre"]=strtoupper($ls_nomproben);
					$la_data_cheque_mas[$li_temp_cheque_mas]["monto"]=number_format($ldec_monto,2,",",".");					
					$li_temp_cheque_mas++;
				break;
				case "ND":
					$la_data_nota_deb_mas[$li_temp_nota_deb_mas]["fecha"]=$ld_fecmov;
					$la_data_nota_deb_mas[$li_temp_nota_deb_mas]["numdoc"]=$ls_numdoc;
					$la_data_nota_deb_mas[$li_temp_nota_deb_mas]["nombre"]=strtoupper($ls_nomproben);
					$la_data_nota_deb_mas[$li_temp_nota_deb_mas]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_nota_deb_mas++;
				break;
				case "NC":
					$la_data_nota_cred_mas[$li_temp_nota_cred_mas]["fecha"]=$ld_fecmov;
					$la_data_nota_cred_mas[$li_temp_nota_cred_mas]["numdoc"]=$ls_numdoc;
					$la_data_nota_cred_mas[$li_temp_nota_cred_mas]["nombre"]=strtoupper($ls_nomproben);
					$la_data_nota_cred_mas[$li_temp_nota_cred_mas]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_nota_cred_mas++;
				break;
				case "RE":
					$la_data_retiro_mas[$li_temp_retiro_mas]["fecha"]=$ld_fecmov;
					$la_data_retiro_mas[$li_temp_retiro_mas]["numdoc"]=$ls_numdoc;
					$la_data_retiro_mas[$li_temp_retiro_mas]["nombre"]=strtoupper($ls_nomproben);
					$la_data_retiro_mas[$li_temp_retiro_mas]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_retiro_mas++;
				break;
				case "DP":
					$la_data_deposito_mas[$li_temp_deposito_mas]["fecha"]=$ld_fecmov;
					$la_data_deposito_mas[$li_temp_deposito_mas]["numdoc"]=$ls_numdoc;
					$la_data_deposito_mas[$li_temp_deposito_mas]["nombre"]=strtoupper($ls_nomproben);
					$la_data_deposito_mas[$li_temp_deposito_mas]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_deposito_mas++;
				break;
			}
		}
		else//en caso de que sean menos
		{
				switch($ls_codope)
			{
				case "CH":
					$la_data_cheque_menos[$li_temp_cheque_menos]["fecha"]=$ld_fecmov;
					$la_data_cheque_menos[$li_temp_cheque_menos]["numdoc"]=$ls_numdoc;
					$la_data_cheque_menos[$li_temp_cheque_menos]["nombre"]=strtoupper($ls_nomproben);
					$la_data_cheque_menos[$li_temp_cheque_menos]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_cheque_menos++;
				break;
				case "ND":
					$la_data_nota_deb_menos[$li_temp_nota_deb_menos]["fecha"]=$ld_fecmov;
					$la_data_nota_deb_menos[$li_temp_nota_deb_menos]["numdoc"]=$ls_numdoc;
					$la_data_nota_deb_menos[$li_temp_nota_deb_menos]["nombre"]=strtoupper($ls_nomproben);
					$la_data_nota_deb_menos[$li_temp_nota_deb_menos]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_nota_deb_menos++;
				break;
				case "NC":
					$la_data_nota_cred_menos[$li_temp_nota_cred_menos]["fecha"]=$ld_fecmov;
					$la_data_nota_cred_menos[$li_temp_nota_cred_menos]["numdoc"]=$ls_numdoc;
					$la_data_nota_cred_menos[$li_temp_nota_cred_menos]["nombre"]=strtoupper($ls_nomproben);
					$la_data_nota_cred_menos[$li_temp_nota_cred_menos]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_nota_cred_menos++;
				break;
				case "RE":
					$la_data_retiro_menos[$li_temp_retiro_menos]["fecha"]=$ld_fecmov;
					$la_data_retiro_menos[$li_temp_retiro_menos]["numdoc"]=$ls_numdoc;
					$la_data_retiro_menos[$li_temp_retiro_menos]["nombre"]=strtoupper($ls_nomproben);
					$la_data_retiro_menos[$li_temp_retiro_menos]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_retiro_menos++;
				break;
				case "DP":
					$la_data_deposito_menos[$li_temp_deposito_menos]["fecha"]=$ld_fecmov;
					$la_data_deposito_menos[$li_temp_deposito_menos]["numdoc"]=$ls_numdoc;
					$la_data_deposito_menos[$li_temp_deposito_menos]["nombre"]=strtoupper($ls_nomproben);
					$la_data_deposito_menos[$li_temp_deposito_menos]["monto"]=number_format($ldec_monto,2,",",".");	
					$li_temp_deposito_menos++;
				break;
			}
		}
	}
	$la_data=array();
	$la_data=array(array('nombre'=>'CHEQUES','tipo'=>'MAS','data'=>$la_data_cheque_mas),
				   array('nombre'=>'CHEQUES','tipo'=>'MENOS','data'=>$la_data_cheque_menos),
				   array('nombre'=>'NOTAS DE DEBITO','tipo'=>'MAS','data'=>$la_data_nota_deb_mas),
            	   array('nombre'=>'NOTAS DE DEBITO','tipo'=>'MENOS','data'=>$la_data_nota_deb_menos),
				   array('nombre'=>'NOTAS DE CREDITO','tipo'=>'MAS','data'=>$la_data_nota_cred_mas),
				   array('nombre'=>'NOTAS DE CREDITO','tipo'=>'MENOS','data'=>$la_data_nota_cred_menos),
				   array('nombre'=>'RETIROS','tipo'=>'MAS','data'=>$la_data_retiro_mas),
				   array('nombre'=>'RETIROS','tipo'=>'MENOS','data'=>$la_data_retiro_menos),
				   array('nombre'=>'DEPOSITOS','tipo'=>'MAS','data'=>$la_data_deposito_mas),
				   array('nombre'=>'DEPOSITOS','tipo'=>'MENOS','data'=>$la_data_deposito_menos));
	uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle	
	uf_print_saldo_banco ($ld_fechasta,number_format($ldec_salsegbco,2,",","."),&$io_pdf);
	uf_print_autorizacion($io_pdf);
	$io_pdf->ezStream();
	unset($io_report);
	unset($io_funciones);	
	unset($la_data);
	unset($io_pdf);
?> 