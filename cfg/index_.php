<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
// validación de los release necesarios para que funcione la definicion de sigesp_empresa.
require_once("../shared/class_folder/sigesp_release.php");
$io_release= new sigesp_release();
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','modageret');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   }
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','nomres');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','concomiva');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','scctaben');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estmodiva');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','activo_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','pasivo_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','resultado_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','c_financiera');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','c_fiscal');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;

if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','codasiona');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
   
	$lb_valido=true;
	if ($lb_valido)
	{
		 $lb_valido=$io_release->io_function_db->uf_select_column('soc_tiposervicio','codmil');	
		 if ($lb_valido==false)
		 {
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
		 }
	}
	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sigesp_unidad_tributaria');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008.1.38");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','nroivss');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_52");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','nomrep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_53");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','cedrep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_54");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','telfrep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_55");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','cargorep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_73");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_banco','codsudeban');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_91");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_deducciones','tipopers');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_93");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estretiva');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_97");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
  $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('cxp_documento','tipodocanti');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_98");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}

	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('cxp_clasificador_rd','sc_cuenta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_29");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
    $lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','clactacon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_28");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estempcon','codaltemp');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_31");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sigesp_consolidacion');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_35");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spg_ep1','estint','sc_cuenta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_40");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','basdatcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_48");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spg_cuentas','scgctaint');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_52");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estcamemp');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_53");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scg_casa_presu');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_60");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estparsindis');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_61");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','basdatcmp');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_62");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_tiposolicitud','estayueco');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_73");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_ctrl_numero','estcompscg');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_80");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','confinstr');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_81");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estintcred');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_83");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_plan_unico_re','status');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_89");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','ctaspgced');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2008_3_02");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estmanant');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_cheques','orden');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2008_3_20");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_cheques','codusu');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2008_3_21");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_colocacion','ced_bene');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2008_3_30");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scb_dt_colocacion');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2008_3_37");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estpreing');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2008_3_46");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		
	}
   $lb_valido=true;
   if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','concommun');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_49");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('spi_cuentas_estructuras');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_45");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spi_cuentas_estructuras','previsto');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_48");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$tamano1=$io_release->io_function_db->uf_tamano_type_columna('sigesp_ctrl_numero','codusu');
		if ($tamano1=="10")
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_58");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$tamano1=$io_release->io_function_db->uf_tamano_type_columna('sigesp_empresa','confiva');
		if ($tamano1=="10")
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_68 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scb_casamientoconcepto');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_84");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','casconmov');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_85");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('spg_tipomodificacion');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_86");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_moneda','imamon');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_03");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_moneda','tascamaux');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_04");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_moneda','tascam');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_05");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sigesp_dt_moneda');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_06");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spg_ep3','estreradi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_87");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estmodprog');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_26");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_cmp_md','codtipmodpre');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_28");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','confi_ch');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_4_29");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_table('spg_dt_fuentefinanciamiento');
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_3_80");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sigesp_correo');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_32");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scb_tipofondo');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('soc_servicios','codunimed');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_moneda','abrmon');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_34");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','ctaresact');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_03");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','ctaresant');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_04");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','dedconproben');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_09");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_consolidacion','codestpro2');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_13");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_consolidacion','codestpro3');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_14");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_consolidacion','codestpro4');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_15");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_consolidacion','codestpro5');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_16");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		   switch($_SESSION["ls_gestor"])
		   {
				case "MYSQLT":
					   $lb_existe = $io_release->io_function_db->uf_select_type_columna('sigesp_consolidacion','estcla','varchar');					
				 break;
					   
				case "POSTGRES":
					   $lb_existe = $io_release->io_function_db->uf_select_type_columna('sigesp_consolidacion','estcla','character varying');   								
				break;  				  
			}
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_17");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_existe = $io_release->uf_select_config('CFG','RELEASE','2009_4_18');		
		 if (!$lb_existe)
		    {
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_18");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_existe = $io_release->uf_select_config('CFG','RELEASE','2009_4_19');		
		 if (!$lb_existe)
		    {
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_19");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estaprsep');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_23");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','sujpasesp');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_24");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_conceptoretencion','codemp');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_25");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_deducciones','codconret');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_26");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
	$lb_valido=true;
	if ($lb_valido)
	   {
		 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','bloanu');	
		 if ($lb_valido==false)
			{
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_29");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
			}
	   }
unset($io_release);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<head>
<title>SIGESP - Módulo de Configuración</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>
<frameset rows="*" cols="166,*" framespacing="0" frameborder="NO" border="0">
	  <frame src="left.php" name="leftFrame" scrolling="YES" noresize>
	  <frame src="main.php" name="mainFrame">
  </frameset>
<noframes><body>
</body>
</noframes>
</html>