<?php
	session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("X-LIGHTTPD-SID: ".session_id()); 
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_leer_archivo($as_archivo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_leer_archivo
		//		   Access: private 
		//	    Arguments: as_archivo //  ruta donde se encuentra el archivo
		//    Description: función que lee un archivo de texto y lo mete en una cadena
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/10/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_texto = file($as_archivo);
		$li_tamano = sizeof($ls_texto);
		$ls_textocompleto="";
		for($li_i=0;$li_i<$li_tamano;$li_i++)
		{
			$ls_textocompleto=$ls_textocompleto.$ls_texto[$li_i];
		}
		return $ls_textocompleto;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_reemplazar($la_data,&$as_cuerpo)
	{
		$la_claves=array_keys($la_data);
		for($li_i=0;$li_i<count($la_claves);$li_i++)
		{
			$as_cuerpo=str_replace("@".$la_claves[$li_i]."@",$la_data[$la_claves[$li_i]],$as_cuerpo);
		}
	}

	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	$io_sql=new class_sql($con);	
	$io_funciones=new class_funciones();				
	$io_msg      = new class_mensajes();
	require_once("sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	$ds_voucher=new class_datastore();	
	$ds_dt_scg=new class_datastore();				
	$ds_dt_spg=new class_datastore();
	//Instancio a la clase de conversi� de numeros a letras.
	require_once("../../shared/class_folder/cnumero_letra.php");
	$numalet= new cnumero_letra();	
	//imprime numero con los cambios
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codigo=$_GET["codigo"];	
	$ls_fecha_corta=date("d/m/Y");
	$lb_valido=$class_report->uf_formato_cartaorden($ls_codigo,$la_data);

	if(!array_key_exists("opener",$_GET))
	{
		$ls_codban	  = $_GET["codban"];
		$ls_ctaban	  = $_GET["ctaban"];
		$ls_numdoc	  = $_GET["numdoc"];	
		$ls_tipproben =	$_GET["tipproben"];
		$la_cartaorden=$class_report->uf_select_cartaorden($ls_numdoc,$ls_codban,$ls_ctaban);
		if((!$lb_valido) || (count ($la_cartaorden)==0))
		{
			$io_msg->message("Error en reporte !!!");		
			print "<script>";
			print "close();";
			print "</script>";
		}	
		$ls_original=$la_data["archrtf"][1];
		$ls_archivo="../cartaorden/original/".$ls_original;
		$ls_copia=substr($ls_original,0,strrpos($ls_original,"."));
		$ls_salida="../cartaorden/copia/".$ls_copia."-".$_SESSION["la_logusr"].".rtf";
		$ls_contenido="";
		$ls_contenido=uf_leer_archivo($ls_archivo);
		$la_matriz=explode("sectd",$ls_contenido);
		$ls_cabecera=$la_matriz[0]."sectd";
		
		$li_inicio=strlen($ls_cabecera);
		$li_final=strrpos($ls_contenido,"}");
		$li_longitud=$li_final-$li_inicio;
		$ls_nuevocuerpo=substr($ls_contenido,$li_inicio,$li_longitud);
		$ls_punt=fopen($ls_salida,"w");
		fputs($ls_punt,$ls_cabecera);
		$ls_cuerpo=$ls_nuevocuerpo;
		$la_campo["banco"]			= $la_cartaorden["nomban"][1];
		$la_campo["ciudad"]			= $_SESSION["la_empresa"]["ciuemp"];
		$la_campo["fecha"]			= $ls_fecha_corta;
		$la_campo["gerente"]		= $la_cartaorden["gerban"][1];
		$la_campo["cartaorden"]		= $la_cartaorden["numcarord"][1];
		$la_campo["documento"]		= $la_cartaorden["numdoc"][1];
		$la_campo["cuentabancaria"] = $la_cartaorden["ctaban"][1];
		$la_campo["monto"]			= number_format($la_cartaorden["monto"][1],2,",",".");
		$la_campo["montoletras"]	= $numalet->uf_convertir_letra($la_cartaorden["monto"][1],'','');;
		$la_campo["tipocuenta"]		= $la_cartaorden["nomtipcta"][1];
		$la_campo["empresa"]		= $_SESSION["la_empresa"]["nombre"];
		uf_reemplazar($la_campo,$ls_cuerpo);			
		fputs($ls_punt,$ls_cuerpo);
		fputs($ls_punt,"}");
		fclose($ls_punt);
		@chmod($ls_salida,0755);
		if($lb_valido) // Si no ocurrio ningún error
		{
			header ("Content-Disposition: attachment; filename=".$ls_copia."-".$_SESSION["la_logusr"].".rtf\n\n");
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($ls_salida));
			readfile($ls_salida);
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	
?> 