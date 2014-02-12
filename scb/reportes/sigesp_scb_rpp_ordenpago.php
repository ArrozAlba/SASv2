<?php
	session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("X-LIGHTTPD-SID: ".session_id()); 

	//--------------Declaraciones e Inicializaciones-----------------------//
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_sigesp_int.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../../shared/class_folder/class_sigesp_int_spg.php");
	$int_spg=new class_sigesp_int_spg();
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	$io_sql=new class_sql($con);	
	require_once("sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	$io_funciones=new class_funciones();				
	$ds_voucher=new class_datastore();	
	$ds_dt_scg=new class_datastore();				
	$ds_dt_spg=new class_datastore();
	//Instancio a la clase de conversión de numeros a letras.
	include("../../shared/class_folder/cnumero_letra.php");
	$numalet= new cnumero_letra();
	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_numdoc=$_GET["numdoc"];
	$ls_codope=$_GET["codope"];	
	$ls_numdocres =$_GET["numdocres"];
	$ls_fecdocres =$_GET["fecdocres"];
	$ls_nrocontrol=$_GET["nrocontrol"];
	$ls_desope=$_GET["desope"];
	$ls_chevau="";
	
	$data=$class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	$ds_voucher->data=$data;
	$li_totprenom=0;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$li_totant=0;
	$ls_numdoc=$ds_voucher->getValue("numdoc",1);
	$ls_codban_tesoreria=$ds_voucher->getValue("codban",1);
	$ls_agno=date("Y");	
	$ls_ctaban_tesoreria=$ds_voucher->getValue("ctaban",1);
	$ls_chevau=$ds_voucher->getValue("chevau",1);
	$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ds_voucher->getValue("fecmov",1));
	$ls_nomproben=$ds_voucher->getValue("nomproben",1);
	$ls_solicitudes=$class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
	$ls_conmov=$ds_voucher->getValue("conmov",1);
	$ldec_monret=$ds_voucher->getValue("monret",1);
	$ldec_monto=$ds_voucher->getValue("monto",1);
	$ldec_total=$ldec_monto-$ldec_monret;
	$ls_coduniadm=$ds_voucher->getValue("coduniadmsig",1);
	$ls_nomuniadm=$class_report->uf_select_data($io_sql,"SELECT * FROM spg_ministerio_ua WHERE coduac ='".$ls_coduniadm."' AND codemp='".$ls_codemp."'","denuac");
	$ls_codestpro=$ds_voucher->getValue("codestprosig",1);
	$rs_estpro=$class_report->uf_select_rowdata($io_sql,"SELECT * FROM spg_ep1 WHERE codestpro1 ='".$ls_codestpro."' AND codemp='".$ls_codemp."'");
	$ls_estcla_estpro=$rs_estpro["estcla"];
	$ls_denestpro1=$rs_estpro["denestpro1"];
	$ls_codpro=$ds_voucher->getValue("cod_pro",1);
	$ls_cedben=$ds_voucher->getValue("ced_bene",1);
	$ls_tipodestino=$ds_voucher->getValue("tipo_destino",1);
	$ls_tipdocres=$ds_voucher->getValue("tipdocressig",1);
	$ls_numdocres=$ds_voucher->getValue("numdocressig",1);
	$ld_fecordpag=$io_funciones->uf_convertirfecmostrar($ds_voucher->getValue("fecordpagsig",1));
	$ls_fte_financiamiento=$ds_voucher->getValue("codfuefin",1);
	$ls_nomfuefin=$class_report->uf_select_data($io_sql,"SELECT * FROM sigesp_fuentefinanciamiento WHERE codfuefin ='".$ls_fte_financiamiento."' AND codemp='".$ls_codemp."'","denfuefin");
	$ls_forpag=$ds_voucher->getValue("forpagsig",1);
	if($ls_forpag=='D')
	{$ls_denforpag='Directa';}
	else
	{$ls_denforpag='Avance';}
	$ls_mediopago=$ds_voucher->getValue("medpagsig",1);
	switch ($ls_mediopago){
		case 1:
			$ls_denmedpag="Abono en Cuenta";
			break;
		case 2:
			$ls_denmedpag="Transferencia";
			break;
		case 3:
			$ls_denmedpag="Carta de Crédito";
			break;			
		case 4:
			$ls_denmedpag="Título o Bono de la Deuda Pública";
			break;
		case 5:
			$ls_denmedpag="Efectívo";
			break;
	}	
	if($ls_tipodestino=='P')
	{
		$rs_proven=$class_report->uf_select_rowdata($io_sql,"SELECT * FROM rpc_proveedor WHERE cod_pro ='".$ls_codpro."' AND codemp='".$ls_codemp."'");
		$ls_nomproben=$rs_proven["nompro"];
		$ls_rif=$rs_proven["rifpro"];
	}
	else
	{
		$rs_proven=$class_report->uf_select_rowdata($io_sql,"SELECT * FROM rpc_beneficiario WHERE ced_bene ='".$ls_cedben."' AND codemp='".$ls_codemp."'");
		$ls_nomproben=$rs_proven["nombene"].$rs_proven["apebene"];
		$ls_rif=$rs_proven["rifben"];
	}
	$rs_dt_op=$class_report->uf_select_rowdata($io_sql,"SELECT * FROM scb_dt_op WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban_tesoreria."' AND ctaban='".$ls_ctaban_tesoreria."' AND numdoc='".$ls_numdoc."' AND codope='OP'");
	if(!empty($rs_dt_op))
	{
		$ls_codban=$rs_dt_op["codbanbene"];//Banco del proveedor al que se hizo el pago
		$ls_ctaban=$rs_dt_op["ctabanbene"];//Cuenta Bancaria del proveedor al que se hizo el pago
		$ls_nomban=$rs_dt_op["nombanbene"];//Nombre Banco del proveedor al que se hizo el pago
		$ls_codbanaut=$rs_dt_op["codbanaut"];//Banco del autorizado al cobro	
		$ls_ctabanaut=$rs_dt_op["ctabanaut"];//Cuenta del autorizado al cobro	 
		$ls_nombanaut=$rs_dt_op["nombanaut"];//Nombre del banco del autorizado	  
		$ls_nombenaut=$rs_dt_op["nombenaut"];//Nombre del autorizado			
		$ls_rifaut=$rs_dt_op["rifbenaut"];//Nombre del autorizado				   
	}
	else
	{
		$ls_codban="";
		$ls_ctaban="";
		$ls_nomban="";
		$ls_codbanaut="";
		$ls_ctabanaut="";
		$ls_nombanaut="";
		$ls_nombenaut="";
		$ls_rifaut="";
	}
	$ls_nomban=$class_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
	//Asigno el monto a la clase numero-letras para la conversion.
	$ls_monto=$numalet->uf_convertir_letra($ldec_total,'','');
	$ds_dt_spg->data=$class_report->uf_cargar_dt_spgop($ls_numdoc,$ls_codban_tesoreria,$ls_ctaban_tesoreria,$ls_codope);
	$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban_tesoreria,$ls_ctaban_tesoreria,$ls_codope);

	$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
	$li_totrow_scg=$ds_dt_scg->getRowCount("scg_cuenta");	
	$io_msg      = new class_mensajes();
	$io_pdf = new class_pdf("LETTER","portrait");
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
	//$io_pdf->numerar_paginas(10);
	$io_pdf->set_margenes(0,0,0,0);
	//-------------------------------------------------//
	
	//------------Data de la Orden de Pago-----------------// 
	$lb_valido=true;
	//------------------------------------------------//
if($lb_valido)
{
 
 //-------------Marco de la Pagina y encabezado----------------------//
 $io_pdf->add_rectangulo(25,254.3,166,241.2,array(255,255,255));//marco 
 $io_pdf->add_linea(25,22,191,22,2);//Linea 1
 $io_pdf->add_texto(27,14,6,"<b>REPUBLICA BOLIVARIANA DE VENEZUELA\n              MINISTERIO DE FINANZAS</b>");
 $li_ancho=$io_pdf->getTextWidth(8,"ORDEN DE PAGO");//Calculando posicion del Titulo
 $io_pdf->convertir_valor_px_mm($li_ancho);
 $li_pos=(215.9-$li_ancho)/2;
 $io_pdf->add_texto($li_pos,14,8,"<b>ORDEN DE PAGO</b>");//Titulo
 $io_pdf->add_texto(160,13,6,"<b>Nro:</b>         ".$ls_numdoc);//Nro
 $io_pdf->add_texto(160,18,6,"<b>Fecha:</b>    ".$ld_fecmov);//Fecha
 //--------------------------Fila 1-------------------------------------//
 $la_data=array();//Primera fila del encabezado
 $la_data[0]["1"]="Año   <b>".$ls_agno."</b>                       Organismo:     <b>".$ls_codemp."</b>    ".$ls_nomemp;
 $la_data[1]["1"]="Unidad Administradora:   <b>".$ls_coduniadm."</b>  ".$ls_nomuniadm;
 $la_data[2]["1"]="Ubicación Geográfica:     Región";
 $la_data[3]["1"]="Municipio                                                                     Ciudad";
 $la_anchos_col = array(160);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>0);
 $io_pdf->ezSetY(729);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
  //--------------------------Fila 2-------------------------------------//
 $li_altousado=$io_pdf->get_alto_usado();
 if($li_altousado<42) $li_altousado=42;
 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
 $io_pdf->add_tabla(-110,array(""),$la_opciones);
 $io_pdf->add_linea(25,$li_altousado,191,$li_altousado,2);//Linea 2
 $la_data=array();//segunda fila del encabezado
 if($ls_estcla_estpro=='P')
 {
	 $la_data[0]["1"]="Código de la Acción Centralizada:                                Tipo de Acción Centralizada: ";
	 $la_data[1]["1"]="Código del Proyecto:   <b>".$ls_codestpro."</b>            Nombre del Proyecto: ".$ls_denestpro1;
 }
 else
 {
 	$la_data[0]["1"]="Código de la Acción Centralizada: <b>".$ls_codestpro."</b>        Tipo de Acción Centralizada: ".$ls_denestpro1;
	$la_data[1]["1"]="Código del Proyecto:                                        Nombre del Proyecto: ";
 }
 $la_anchos_col = array(160);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>0);
 $io_pdf->ezSetDy(-7);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
//--------------------------Fila 3-------------------------------------//
 $li_altousado=$io_pdf->get_alto_usado();
 if($li_altousado<53) $li_altousado=53;
 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
 $io_pdf->add_tabla(-110,array(""),$la_opciones);
 $io_pdf->add_linea(25,$li_altousado,191,$li_altousado,2);//Linea 3
 $la_data=array();//tercera fila del encabezado 
 $la_data[0]["1"]="R.I.F.:  ".$ls_rif."                                                                                        Nombre del Beneficiario:\n";
 $la_data[1]["1"]="Abonese en Banco:    <b>".$ls_codban."</b>    ".$ls_nomban."\n";
 $la_data[2]["1"]="La cantidad de : ".$ls_monto;
 $la_data[0]["2"]=$ls_nomproben;
 $la_data[1]["2"]=" Cta. Nro. ".$ls_ctaban;
 $la_data[2]["2"]="";
 
 $la_anchos_col = array(100,60);
 $la_justificaciones = array("left","left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>0);
 $io_pdf->ezSetDy(-7);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
 //------------------------------------------------// 
 //--------------------------Fila 4-------------------------------------//
 $li_altousado=$io_pdf->get_alto_usado();
 if($li_altousado<74) $li_altousado=74;
 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
 $io_pdf->add_tabla(-110,array(""),$la_opciones);
 $io_pdf->add_linea(25,$li_altousado,191,$li_altousado,2);//Linea 4
 $la_data=array();//cuarta fila (titulo) 
 $la_data[0]["1"]="Documento de Respaldo"; 
 $la_anchos_col = array(160);
 $la_justificaciones = array("center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0);
 $io_pdf->ezSetDy(-6);
 $io_pdf->add_tabla(28,$la_data,$la_opciones);
 
 $la_data[0]["1"]="Tipo de Registro: Causado";
 $la_data[0]["2"]="Tipo:  <b>".$ls_tipdocres."</b>";
 $la_data[0]["3"]="Nro: <b>".$ls_numdocres."</b>";
 $la_data[0]["4"]="Fecha de Pago:  <b>".$ld_fecordpag."</b>";
 $la_anchos_col = array(40,40,40,40);
 $la_justificaciones = array("center","center","center","lecenterft");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones);
 $io_pdf->add_tabla(28,$la_data,$la_opciones);
 $li_altousado=$io_pdf->get_alto_usado();
 if($li_altousado<84) $li_altousado=84;
 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
 $io_pdf->add_tabla(-110,array(""),$la_opciones);
 $io_pdf->add_linea(25,$li_altousado,191,$li_altousado,0.1);//Linea 5
 
 $la_data=array();
 $la_data[0]["1"]="Fuente de Financiamiento\n<b>$ls_fte_financiamiento</b>   ".$ls_nomfuefin;
 $la_data[0]["2"]="Orígen\nMonto Ley";
 $la_data[0]["3"]="Tipo de Pago\n ".$ls_denforpag;
 $la_data[0]["4"]="  Medio de Pago\n<b>$ls_mediopago</b>    ".$ls_denmedpag;
 $la_anchos_col = array(40,40,40,40);
 $la_justificaciones = array("center","center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0);
 $io_pdf->ezSetDy(-4);
 $io_pdf->add_tabla(28,$la_data,$la_opciones);
 
  //-----------------------Datos del Detalle-------------------------//
 $la_data=array();
 $la_data[0]["1"]="<b>Imputación Presupuestaria</b>\n";
 $la_data[0]["2"]="<b>Denominación</b>";
 $la_data[0]["3"]="<b>Monto Bs.</b>";
 $la_anchos_col = array(41,93,31.75);
 $la_justificaciones = array("center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1);
  $io_pdf->ezSetDy(-4);
  $io_pdf->add_tabla(26.9,$la_data,$la_opciones);
  
 $li_altousado=$io_pdf->get_alto_usado(); 
 $la_data=array();
 $la_data[0]["1"]="<b>AE</b>";
 $la_data[0]["2"]="<b>UEL</b>";
 $la_data[0]["3"]="<b>PA</b>";
 $la_data[0]["4"]="<b>GE</b>";
 $la_data[0]["5"]="<b>ES</b>";
 $la_data[0]["6"]="<b>SE</b>";
 $la_anchos_col = array(8,13,4,4,4,8.04);
 $la_justificaciones = array("center","center","center","center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1);
  $io_pdf->ezSetDy(6.97);
  $io_pdf->add_tabla(25.1,$la_data,$la_opciones);  
  //-----------------------------------------------------------------------//
  //----------Tabla con la data, con un maximo de 30 filas, si son mas todo se descuadra--------------//
  $li_maxfilas=15;
  $li_totalfilas=$li_totrow_spg;//(count($la_datapartidas, COUNT_RECURSIVE) / count($la_datapartidas)) - 1;
  $la_data=array();
  if($li_totalfilas<=30)
  {
  	$li_totrow=$li_totalfilas;
  }
  else
  {
  	$li_totrow=30;
  }

  $int_spg->uf_init_niveles();
  $arr_spg=$int_spg->ia_niveles_spg;
  function uf_obtener_seccion_cuenta($ls_spg_cuenta,$desde,$hasta)
  {
  	  $ls_seccion="";
	  for($li=$desde;$li<=$hasta;$li++)
	  {
	  	 $ls_seccion=$ls_seccion.$ls_spg_cuenta{$li};	
	  }
	  return $ls_seccion;
  }

  for($li_i=0;$li_i<$li_totrow;$li_i++)
  {
		$li_row=$li_i+1;
		$la_data[$li_i]["ae"]=substr($ds_dt_spg->getValue("estpro",$li_row),20,6);
		$la_data[$li_i]["uel"]=$ds_dt_spg->getValue("coduniadm",$li_row);
		$ls_spg_cuenta=$ds_dt_spg->getValue("spg_cuenta",$li_row);
	    $li_len=strlen($ls_spg_cuenta);
		$la_data[$li_i]["pa"]=uf_obtener_seccion_cuenta($ls_spg_cuenta,0,$arr_spg[1]);
		$la_data[$li_i]["ge"]=uf_obtener_seccion_cuenta($ls_spg_cuenta,$arr_spg[1]+1,$arr_spg[2]);
		$la_data[$li_i]["es"]=uf_obtener_seccion_cuenta($ls_spg_cuenta,$arr_spg[2]+1,$arr_spg[3]);
		$la_data[$li_i]["se"]=uf_obtener_seccion_cuenta($ls_spg_cuenta,$arr_spg[3]+1,$arr_spg[4]);
		$la_data[$li_i]["denominacion"]=$ds_dt_spg->getValue("denominacion",$li_row);
		$la_data[$li_i]["monto"]=number_format($ds_dt_spg->getValue("monto",$li_row),2,",",".");
  }
  $la_anchos_col = array(8,13,4,4,4,8.04,93,31.7);  
  $la_indices_claves=array("ae","uel","pa","ge","es","se","denominacion","monto");
  $la_filasusadas=$io_pdf->obtener_lineas_por_fila(6,$la_anchos_col,$la_data,$la_indices_claves,0);
  $li_totalfilasusadas=array_sum($la_filasusadas);
  if($li_totalfilasusadas<($li_maxfilas*2))
  {
		for($li_i=$li_totalfilas;$li_i<$li_maxfilas*2;$li_i++)
		{
			$la_data[$li_i]["ae"]="";
			$la_data[$li_i]["uel"]="";
			$la_data[$li_i]["pa"]="";
			$la_data[$li_i]["ge"]="";
			$la_data[$li_i]["es"]="";
			$la_data[$li_i]["se"]="";
			$la_data[$li_i]["denominacion"]="";
			$la_data[$li_i]["monto"]="";
		}    
 }
 
 $ldec_totretenciones=0;
 $li_totret=0;
 for($li_x=1;$li_x<=$li_totrow_scg;$li_x++)
 {
 	$ls_codded=$ds_dt_scg->getValue("codded",$li_x);
	$ls_debhab=$ds_dt_scg->getValue("debhab",$li_x);
	$ldec_monto_ret=$ds_dt_scg->getValue("monto",$li_x);
	if(($ls_codded!='00000')&&($ls_debhab=='H'))
	{
		$li_totret++;
		$ldec_totretenciones=$ldec_totretenciones+$ldec_monto_ret;
	}
 }

  $la_opciones=array();
  $la_justificaciones = array("center","center","center","center","center","center","left","right");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones);
  $io_pdf->add_tabla(25.1,$la_data,$la_opciones); 
  //-----------------------------------------------------------------------//
  //------------------------------Totales----------------------------//
  $la_data=array();
  $la_data[0]["1"]="<b>R.C.P. Nro</b>";
  $la_data[0]["2"]="Total Bs.";
  $la_data[0]["3"]=number_format($ldec_monto,2,",",".");
  $la_data[1]["1"]="\nConcepto de Pago/Observaciones\n\n";
  $la_data[1]["2"]="";
  $la_data[1]["3"]="";
  $la_anchos_col = array(109,25,31.7);
  $la_opciones=array();
  $la_justificaciones = array("center","center","right");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					   "alineacion_col"=>$la_justificaciones);
  $io_pdf->add_tabla(26.9,$la_data,$la_opciones); 
  
  $la_data=array();//Tablita interna que contiene los datos de retenciones y neto a pagar
  $la_data[0]["1"]="Retenciones($li_totret)\n";
  $la_data[0]["2"]= number_format($ldec_totretenciones,2,",",".");
  $la_data[1]["1"]="Neto a Pagar Bs.";
  $la_data[1]["2"]=number_format($ldec_monto-$ldec_totretenciones,2,",",".");
  $la_anchos_col = array(25,31.7);
  $la_opciones=array();
  $la_justificaciones = array("center","right");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "lineas"=>0);
  $io_pdf->ezSetDy(29);
  $io_pdf->add_tabla(135.9,$la_data,$la_opciones); 
  $li_alto=$io_pdf->get_alto_usado();  
  $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1); 
  $io_pdf->add_tabla(-110,array(""),$la_opciones);  
  $io_pdf->add_linea(134.2,$li_alto-2.5,191,$li_alto-2.5,0.1);//Linea entre retenciones y neto a pagar
  
  
  //------------------------Pie de pagina----------------------------------------//
  $la_data=array();//Tabla con el concepto u observacion
  $la_data[0]["1"]=$ls_conmov;
  $la_indices_claves=array("1");
  $la_anchos_col = array(165.7);
  $la_filasusadas=$io_pdf->obtener_lineas_por_fila(6,$la_anchos_col,$la_data,$la_indices_claves,0.2);
  if($la_filasusadas[0]<8)
  {
  		$ls_espacios="";
		for($li_i=$la_filasusadas[0]+1;$li_i<=8;$li_i++)
		{
		 	$ls_espacios=$ls_espacios."\n";   
		} 
		$la_data[0]["1"]=$la_data[0]["1"].$ls_espacios;
  }
  $la_opciones=array();
  $la_justificaciones = array("left");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0.5,
					   "margen_horizontal"=>1);
  $io_pdf->ezSetDy(-3);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones); 
  
  $la_data=array();//Tablita Autorizado
  $la_data[0]["1"]="<b>Autorizado:</b>";
  
  $la_anchos_col = array(164);
  $la_opciones=array();
  $la_justificaciones = array("left");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0.5,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones);
  
  $la_data=array();//Tablita Abonese en banco
  $la_data[0]["1"]=$ls_nombenaut;
  $la_data[0]["2"]="<b>R.I.F.:</b>".$ls_rifaut;
  $la_data[1]["1"]="<b>Abónese en Banco:</b>".$ls_codbanaut."   ".$ls_nombanaut;
  $la_data[1]["2"]="<b>Cta. Nro.:</b>".$ls_ctabanaut;
  $la_anchos_col = array(100,64);
  $la_opciones=array();
  $la_justificaciones = array("left","left");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0.5,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones); 
  
    
  $la_data=array();//Tablita para uso de la tesoreria nacional
  $la_data[0]["1"]="<b>\nPara uso de la Tesorería Nacional</b>";
  $la_anchos_col = array(165);
  $la_opciones=array();
  $la_justificaciones = array("center");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0.5,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones);
  $la_data=array();
  $la_data[0]["1"]="<b>Cta.Cte. TN: Banco  \n</b>";
  $la_data[0]["2"]="<b>Nro.</b>";
  $la_data[0]["3"]="<b>Fecha:</b>";
  $la_anchos_col = array(45,75,44);
  $la_opciones=array();
  $la_justificaciones = array("left","left","left");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>2,
					   "margen_horizontal"=>0.5,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones);
  
  $la_data=array();//Tabla firmas autorizadas
  $la_data[0]["1"]="<b>Firmas Autorizadas</b>";
  $la_anchos_col = array(165);
  $la_opciones=array();
  $la_justificaciones = array("center");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones);
  $la_data=array();//Tabla firmas autorizadas
  $la_data[0]["1"]="<b>\n\n\n\n\nDirector Administración\n</b>";
  $la_data[0]["2"]="<b>\n\n\n\n\nOrdenador de Pago\n</b>";
  $la_data[0]["3"]="<b>\n\n\n\n\nContraloría General\n</b>";
  $la_data[0]["4"]="<b>\n\n\n\n\nOficina Nacional del Tesoro\n</b>";
  $la_anchos_col = array(41.5,41.5,41.5,41.3);
  $la_opciones=array();  
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "margen_vertical"=>2,
					   "margen_horizontal"=>0,
					   "lineas"=>2,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1);
  $io_pdf->add_tabla(25.1,$la_data,$la_opciones); 
  
  //--------------Lineas ornamentales del Pie de pagina----------------------//
  $li_puntero=$io_pdf->get_alto_usado(); 
  $li_alto=$io_pdf->get_alto_usado();  
  $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1); 
  $io_pdf->add_tabla(-110,array(""),$la_opciones);  
  $io_pdf->add_linea(25,$li_puntero-33,191,$li_puntero-33,2);
  $io_pdf->add_linea(25,$li_puntero-34,191,$li_puntero-34,2);
  $io_pdf->add_linea(25,$li_puntero-24,191,$li_puntero-24,2);
  $io_pdf->add_linea(25,$li_puntero-25,191,$li_puntero-25,2);
  $io_pdf->add_linea(25,$li_puntero-50,191,$li_puntero-50,2);
  $io_pdf->add_linea(31,$li_puntero-6,62,$li_puntero-6,0.1);
  $io_pdf->add_linea(74,$li_puntero-6,100.5,$li_puntero-6,0.1);
  $io_pdf->add_linea(116,$li_puntero-6,142,$li_puntero-6,0.1);
  $io_pdf->add_linea(153,$li_puntero-6,188,$li_puntero-6,0.1);
  

  
  //---------------------------------------------------------//  
  //$io_pdf->ezStream();
 }
 else
 {
 	$io_msg->message("Error al cargar la data!!!");
	print"<script> ";
	print"close();";
	print"</script>";  
 } 
 
 //***********************************************pintar_retenciones*************************************************************
 //******************************************************************************************************************************

 //------------Data de la Orden de Pago-----------------// 
 $lb_valido=true;
 //------------------------------------------------//
if($li_totret>0)
{ 
 $io_pdf->ezNewPage();
 //-------------Marco de la Pagina y encabezado----------------------//
 $io_pdf->add_rectangulo(20,254.3,180,241.2,array(255,255,255));//marco 
 $io_pdf->add_linea(25,22,194,22,2);//Linea 1
 $io_pdf->add_texto(27,14,6,"<b>REPUBLICA BOLIVARIANA DE VENEZUELA\n              MINISTERIO DE FINANZAS</b>");
 $li_ancho=$io_pdf->getTextWidth(8,"RETENCIONES");//Calculando posicion del Titulo
 $io_pdf->convertir_valor_px_mm($li_ancho);
 $li_pos=(215.9-$li_ancho)/2;
 $io_pdf->add_texto($li_pos,14,8,"<b>RETENCIONES</b>");//Titulo
 $io_pdf->add_texto(160,13,6,"<b>Nro:</b>         ".$ls_numdoc);//Nro
 $io_pdf->add_texto(160,18,6,"<b>Fecha:</b>    ".$ld_fecmov);//Fecha
 //--------------------------Fila 1-------------------------------------//
 $la_data=array();//Primera fila del encabezado
 $la_data[0]["1"]="Año   <b>".$ls_agno."</b>                       Organismo:     <b>".$ls_codemp."</b>    ".$ls_nomemp;
 $la_anchos_col = array(160);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>0);
 $io_pdf->ezSetY(729);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
                //--------------------------Tabla-------------------------------------//
                //--------------------------------------------------------------------//
 //-----------------------------Titulos de la Tabla-------------------------------------------------//
 //-----------------(Se Agregan aparte para permitir las distintas justificaciones del texto)-------//
 $la_data=array();
 $la_data[0]["1"]="<b>Código</b>";
 $la_data[0]["2"]="<b>Descripción</b>";
 $la_data[0]["3"]="<b>Monto</b>";
 $la_anchos_col = array(22,110,38);
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "grosor_lineas_internas"=>0.5,
					   "grosor_lineas_externas"=>0.5);
 $io_pdf->ezSetY(710);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
  
  //-----------------------------Datos de la tabla-------------------------------------------------//
	$li_filas=2;//(count($la_datapartidas, COUNT_RECURSIVE) / count($la_datapartidas)) - 1;
	$la_datadetalle=array();	
	$li_z=0;
	 for($li_x=1;$li_x<=$li_totrow_scg;$li_x++)
	 {
 		$ls_codded=$ds_dt_scg->getValue("codded",$li_x);
		$ls_dended=$class_report->uf_select_data($io_sql,"SELECT * FROM sigesp_deducciones WHERE codded='".$ls_codded."'","dended");
		$ls_debhab=$ds_dt_scg->getValue("debhab",$li_x);
		$ldec_monto=$ds_dt_scg->getValue("monto",$li_x);
		if(($ls_codded!='00000')&&($ls_debhab=='H'))
		{
			$la_datadetalle[$li_z]["codigo"]=$ls_codded;
		  	$la_datadetalle[$li_z]["descripcion"]=$ls_dended;
		  	$la_datadetalle[$li_z]["monto"]=number_format($ldec_monto,2,",",".");
			$li_z++;
		}
	
	 }
	
	//-------------------Este Codigo que sigue es para permitir que se cree una
						//pagina nueva en caso de que sean demasiadas filas----------------------------//
						
  $li_i=0;  
  $la_indices=array("codigo","descripcion","monto");
  $la_anchos_col = array(22,110,38);
  $la_lineasfila=$io_pdf->obtener_lineas_por_fila(7,$la_anchos_col,$la_datadetalle,$la_indices);
  while($li_i<count($la_lineasfila))
  {	  
	  $la_aux=array();
	  $li_j=0;
	  $li_altotabla=0;
	  $li_disponible=$io_pdf->get_alto_disponible()-30;
	  while(($li_altotabla<$li_disponible) && $li_i<count($la_lineasfila))
	  {
	   		 $li_altofila=$io_pdf->get_alto_fila(7,$la_lineasfila[$li_i]);
	    	 if(($li_altofila+$li_altotabla) < $li_disponible)
	    	 {
	    	    $la_aux[$li_j]=$la_datadetalle[$li_i];
			  	$li_i++;
				$li_j++;		   
			 }
			 $li_altotabla += $li_altofila; 
			// print_r($la_aux);	 
	  }
	 $la_alineacion=array("center","left","right");	 
  	 $la_opciones = array("color_texto" => array(0,0,0),
					   "tamano_texto"=> 7,
					   "anchos_col" => $la_anchos_col,
					   "alineacion_col"=>$la_alineacion,
					   "grosor_lineas_internas"=>0.5,
					   "grosor_lineas_externas"=>0.5);
	 $io_pdf->add_tabla(26,$la_aux,$la_opciones);	
	 //break;
	 if($li_i+1<count($la_lineasfila))
	 {
		  //-------------Marco de la Pagina y encabezado----------------------//
		 $io_pdf->ezNewPage();
		 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 0.1);   
 		 $io_pdf->add_tabla(-110,array(""),$la_opciones);
		 $io_pdf->add_rectangulo(20,254.3,180,241.2,array(255,255,255));//marco 
		 $io_pdf->add_linea(25,22,194,22,2);//Linea 1
		 $io_pdf->add_texto(27,14,6,"<b>REPUBLICA BOLIVARIANA DE VENEZUELA\n              MINISTERIO DE FINANZAS</b>");
		 $li_ancho=$io_pdf->getTextWidth(8,"RETENCIONES");//Calculando posicion del Titulo
		 $io_pdf->convertir_valor_px_mm($li_ancho);
		 $li_pos=(215.9-$li_ancho)/2;
		 $io_pdf->add_texto($li_pos,14,8,"<b>RETENCIONES</b>");//Titulo
		 $io_pdf->add_texto(160,13,6,"<b>Nro:</b>         ".$ls_numdoc);//Nro
		 $io_pdf->add_texto(160,18,6,"<b>Fecha:</b>    ".$ld_fecmov);//Fecha
		 //--------------------------Fila 1-------------------------------------//
		 $la_data=array();//Primera fila del encabezado
		 $la_data[0]["1"]="Año   <b>".$ls_agno."</b>                       Organismo:     <b>".$ls_codemp."</b>    ".$ls_nomemp;
		 $la_anchos_col = array(160);
		 $la_justificaciones = array("left");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 6,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>0);
		 $io_pdf->ezSetY(729);
		 $io_pdf->add_tabla(26,$la_data,$la_opciones);
		                //--------------------------Tabla-------------------------------------//
		                //--------------------------------------------------------------------//
		 //-----------------------------Titulos de la Tabla-------------------------------------------------//
		 //-----------------(Se Agregan aparte para permitir las distintas justificaciones del texto)-------//
		 $la_data=array();
		 $la_data[0]["1"]="<b>Código</b>";
		 $la_data[0]["2"]="<b>Descripción</b>";
		 $la_data[0]["3"]="<b>Monto</b>";
		 $la_anchos_col = array(22,110,38);
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "grosor_lineas_internas"=>0.5,
							   "grosor_lineas_externas"=>0.5);
		 $io_pdf->ezSetY(710);
		 $io_pdf->add_tabla(26,$la_data,$la_opciones);
	  }
	}    
	
  //----------------------------------------Total-----------------------------------------------------------//
  $li_puntero=$io_pdf->get_alto_usado();
  $io_pdf->add_texto(143,$li_puntero+1.5,7,"<b>Total=></b>");//Total
  $la_data=array();
  $la_data[0]["1"]=number_format($ldec_totretenciones,2,",",".");
  $la_anchos_col = array(38);
  $la_alineacion=array("right");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "grosor_lineas_internas"=>0.5,
					   "grosor_lineas_externas"=>0.5,
					   "alineacion_col"=>$la_alineacion);
  $io_pdf->add_tabla(158,$la_data,$la_opciones);
  
  //---------------------------------------------------------//  
//  $io_pdf->ezStream();
 
 }
 $io_pdf->ezStream(); 
 
?>