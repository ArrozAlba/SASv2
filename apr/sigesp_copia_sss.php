<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_sss.php                                	                			  //    
// Description : Procesa la copia de datos del modulo de seguridad									  //
////////////////////////////////////////////////////////////////////////////////////////////////////////

class sigesp_copia_sss {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_database_target;
	
function sigesp_copia_sss()
{
	$ld_fecha=date("_d-m-Y");
	$ls_nombrearchivo="";
	$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_sss_result_".$ld_fecha.".txt";
	$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");

	$this->ls_database_source = $_SESSION["ls_database"];
	$this->ls_database_target = $_SESSION["ls_data_des"];
	require_once("class_folder/class_validacion.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	
	$this->io_validacion  = new class_validacion();
	$this->io_mensajes    = new class_mensajes();
	$io_conect			  = new sigesp_include();
	$io_conexion_origen   = $io_conect->uf_conectar();
	$io_conexion_destino  = $io_conect->uf_conectar($this->ls_database_target);
	
	$this->io_sql_origen  = new class_sql($io_conexion_origen);
	$this->io_sql_destino = new class_sql($io_conexion_destino);
	$this->io_rcbsf		  = new sigesp_c_reconvertir_monedabsf();
    $io_msg				  = new class_mensajes();
  }


function ue_copiar_sss_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_empresa();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_sistemas();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_eventos();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_monedas();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_banco_sigecof();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_procedencias();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_catalogo_milco();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_sigesp_config();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_usuarios();
	   } 
   if ($lb_valido)
	  {	
		$lb_valido = $this->uf_copiar_grupos();
	  }  
   if ($lb_valido)
	  {	
		$lb_valido = $this->uf_copiar_derechos_grupos();
	  }  
   if ($lb_valido)
	  {	
		$lb_valido = $this->uf_copiar_sistemas_ventanas();
	  }  
   if ($lb_valido)
	  {	
		$lb_valido = $this->uf_copiar_permisos_internos();
	  }  
   if ($lb_valido)
	  {	
		$lb_valido = $this->uf_copiar_derechos_usuarios();
	  }  
   if ($lb_valido)
	  {	
		$lb_valido = $this->uf_copiar_usuarios_grupos();
	  }  
   if ($lb_valido)
      {	
 	    $lb_valido = $this->uf_copiar_sigesp_expediente();
	  } 
   if ($lb_valido)
      {	
 	    $lb_valido = $this->uf_copiar_sigesp_poliza();
	  } 
   if ($lb_valido)
      {	
 	    $lb_valido = $this->uf_copiar_sigesp_dt_expediente();
	  } 
   if ($lb_valido)
	  {	
 	    $lb_valido = $this->uf_copiar_sigesp_dt_poliza();
	  } 

   if ($lb_valido)
	  {
	    $this->io_mensajes->message("La data de Seguridad se copió correctamente.");
		$ls_cadena="La data de Seguridad se copió correctamente.\r\n";
		if ($this->lo_archivo)			
		   {
			 @fwrite($this->lo_archivo,$ls_cadena);
		   }
	  }
	else
	  {
	    $this->io_mensajes->message("Ocurrió un error al copiar la data de Seguridad. Verifique el archivo txt."); 
	  }
  if ($lb_valido)
	 {
	   $this->io_sql_destino->commit();
	   $this->io_validacion->uf_insert_sistema_apertura('SSS');
	 }
  else
	 {
	   $this->io_sql_destino->rollback();	
	 }
  return $lb_valido;	
}

function uf_copiar_empresa()
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_copiar_empresa
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
		//	   Creado Por: 
		// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp,nombre,titulo,sigemp,direccion,telemp,faxemp,email,website,m01,m02,m03,m04,m05,m06,m07,m08,m09,".
				"		m10,m11,m12,periodo,vali_nivel,esttipcont,formpre,formcont,formplan,formspi,activo,pasivo,ingreso,gasto,".
				"       resultado,capital,c_resultad,c_resultan,orden_d,orden_h,soc_gastos,soc_servic,gerente,jefe_compr,activo_h,".
				"		pasivo_h,resultado_h,ingreso_f,gasto_f,ingreso_p,gasto_p,logo,numniv,nomestpro1,nomestpro2,nomestpro3,".
				"		nomestpro4,nomestpro5,estvaltra,rifemp,nitemp,estemp,ciuemp,zonpos,estmodape,estdesiva,estprecom,estmodsepsoc,".
				"		codorgsig,socbieser,estmodest,salinipro,salinieje,numordcom,numordser,numsolpag,nomorgads,numlicemp,modageret,".
				"		nomres,concomiva,cedben,nomben,scctaben,estmodiva,activo_t,pasivo_t,resultado_t,c_financiera,c_fiscal,".
				"		diacadche,codasiona,loncodestpro1,loncodestpro2,loncodestpro3,loncodestpro4,loncodestpro5,candeccon,".
				"		tipconmon,redconmon,conrecdoc".
				" FROM sigesp_empresa";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		{  
			$lb_valido=false;
			$ls_cadena="Problema al Copiar Empresa.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{			
			$li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			while($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ls_codemp = $row["codemp"];
				if (!empty($ls_codemp))
				{
                	$ls_nombre= $this->io_validacion->uf_valida_texto($row["nombre"],0,100,"SIGESP");
					$ls_titulo= $this->io_validacion->uf_valida_texto($row["titulo"],0,100,"SIGESP");
					$ls_sigemp= $this->io_validacion->uf_valida_texto($row["sigemp"],0,50,"");
					$ls_diremp= $this->io_validacion->uf_valida_texto($row["direccion"],0,254,"");
					$ls_telemp= $this->io_validacion->uf_valida_texto($row["telemp"],0,20,"");
					$ls_faxemp= $this->io_validacion->uf_valida_texto($row["faxemp"],0,18,"");
					$ls_email= $this->io_validacion->uf_valida_texto($row["email"],0,100,"");
					$ls_website= $this->io_validacion->uf_valida_texto($row["website"],0,100,"");
					$li_m01= $this->io_validacion->uf_valida_monto($row["m01"],0);
					$li_m02= $this->io_validacion->uf_valida_monto($row["m02"],0);
					$li_m03= $this->io_validacion->uf_valida_monto($row["m03"],0);
					$li_m04= $this->io_validacion->uf_valida_monto($row["m04"],0);
					$li_m05= $this->io_validacion->uf_valida_monto($row["m05"],0);
		            $li_m06= $this->io_validacion->uf_valida_monto($row["m06"],0);
					$li_m07= $this->io_validacion->uf_valida_monto($row["m07"],0);
					$li_m08= $this->io_validacion->uf_valida_monto($row["m08"],0);
					$li_m09= $this->io_validacion->uf_valida_monto($row["m09"],0);
					$li_m10= $this->io_validacion->uf_valida_monto($row["m10"],0);
					$li_m11= $this->io_validacion->uf_valida_monto($row["m11"],0);
					$li_m12= $this->io_validacion->uf_valida_monto($row["m12"],0);
					$ls_periodo= $this->io_validacion->uf_valida_fecha($row["periodo"],'1900-01-01');
					$li_valniv = $this->io_validacion->uf_valida_monto($row["vali_nivel"],0);
					$li_esttipcont = $this->io_validacion->uf_valida_monto($row["esttipcont"],0);
					$ls_formpre = $this->io_validacion->uf_valida_texto(trim($row["formpre"]),0,30,"999-99-99-99");
					$ls_formcont = $this->io_validacion->uf_valida_texto(trim($row["formcont"]),0,30,"999-99-99-99");
					$ls_formplan = $this->io_validacion->uf_valida_texto(trim($row["formplan"]),0,30,"999-99-99-99");
					$ls_formspi = $this->io_validacion->uf_valida_texto(trim($row["formspi"]),0,30,"999-99-99-99");
					$li_activo = $this->io_validacion->uf_valida_texto(trim($row["activo"]),0,3,"0");
					$li_pasivo = $this->io_validacion->uf_valida_texto(trim($row["pasivo"]),0,3,"0");
					$li_ingreso = $this->io_validacion->uf_valida_texto(trim($row["ingreso"]),0,3,"0");
					$li_gasto = $this->io_validacion->uf_valida_texto(trim($row["gasto"]),0,3,"0");
					$li_resultado = $this->io_validacion->uf_valida_texto(trim($row["resultado"]),0,3,"0");
					$li_capital = $this->io_validacion->uf_valida_texto(trim($row["capital"]),0,3,"0");
					$ls_ctares = $this->io_validacion->uf_valida_texto(trim($row["c_resultad"]),0,25,"0");
					$ls_cueres = $this->io_validacion->uf_valida_texto(trim($row["c_resultan"]),0,25,"0");
					$ls_orden_d = $this->io_validacion->uf_valida_texto(trim($row["orden_d"]),0,3,"0");
					$ls_orden_h = $this->io_validacion->uf_valida_texto(trim($row["orden_h"]),0,3,"0");
					$ls_socgas = $this->io_validacion->uf_valida_texto(trim($row["soc_gastos"]),0,100,"0");
					$ls_socser = $this->io_validacion->uf_valida_texto(trim($row["soc_servic"]),0,100,"0");
					$ls_nomger = $this->io_validacion->uf_valida_texto(trim($row["gerente"]),0,50,"");
					$ls_jefcom = $this->io_validacion->uf_valida_texto(trim($row["jefe_compr"]),0,50,"");
					$ls_activo_h = $this->io_validacion->uf_valida_texto(trim($row["activo_h"]),0,3,"0");
					$ls_pasivo_h = $this->io_validacion->uf_valida_texto(trim($row["pasivo_h"]),0,3,"0");
					$ls_resultado_h  = $this->io_validacion->uf_valida_texto(trim($row["resultado_h"]),0,3,"0");
					$ls_ingreso_f = $this->io_validacion->uf_valida_texto(trim($row["ingreso_f"]),0,3,"0");
					$ls_gasto_f = $this->io_validacion->uf_valida_texto(trim($row["gasto_f"]),0,3,"0");
					$ls_ingreso_p = $this->io_validacion->uf_valida_texto(trim($row["ingreso_p"]),0,3,"0");
					$ls_gasto_p = $this->io_validacion->uf_valida_texto(trim($row["gasto_p"]),0,3,"0");
					$ls_logo = $this->io_validacion->uf_valida_texto(trim($row["logo"]),0,500,"logo.jpg");
					$li_numniv = $this->io_validacion->uf_valida_monto($row["numniv"],0);
					$ls_nomestpro1 = $this->io_validacion->uf_valida_texto($row["nomestpro1"],0,40,"-");
					$ls_nomestpro2 = $this->io_validacion->uf_valida_texto($row["nomestpro2"],0,40,"-");
					$ls_nomestpro3 = $this->io_validacion->uf_valida_texto($row["nomestpro3"],0,40,"-");
					$ls_nomestpro4 = $this->io_validacion->uf_valida_texto($row["nomestpro4"],0,40,"-");
					$ls_nomestpro5 = $this->io_validacion->uf_valida_texto($row["nomestpro5"],0,40,"-");
					$ls_estvaltra = $this->io_validacion->uf_valida_monto($row["estvaltra"],0);
					$ls_rifemp = $this->io_validacion->uf_valida_texto(trim($row["rifemp"]),0,15,"");
					$ls_nitemp = $this->io_validacion->uf_valida_texto(trim($row["nitemp"]),0,15,"");
					$ls_estemp = $this->io_validacion->uf_valida_texto($row["estemp"],0,50,"");
					$ls_ciuemp = $this->io_validacion->uf_valida_texto($row["ciuemp"],0,50,"");
					$ls_zonpos = $this->io_validacion->uf_valida_texto($row["zonpos"],0,5,"");
					$ls_estmodape = $this->io_validacion->uf_valida_monto($row["estmodape"],0);
					$ls_estdesiva = $this->io_validacion->uf_valida_monto($row["estdesiva"],0);
					$ls_estprecom = $this->io_validacion->uf_valida_monto($row["estprecom"],0);
					$ls_estmodsepsoc = $this->io_validacion->uf_valida_monto($row["estmodsepsoc"],0);
					$ls_codorgsig = $this->io_validacion->uf_valida_texto(trim($row["codorgsig"]),0,5,"");
					$ls_socbieser = $this->io_validacion->uf_valida_monto($row["socbieser"],0);
					$li_estmodest = $this->io_validacion->uf_valida_monto($row["estmodest"],0);
					$ld_salinipro = $this->io_validacion->uf_valida_monto($row["salinipro"],0);
	 	            $ld_salinieje = $this->io_validacion->uf_valida_monto($row["salinieje"],0);
					$ls_numordcom = $this->io_validacion->uf_valida_texto($row["numordcom"],0,15,"000000000000000");
					$ls_numordser = $this->io_validacion->uf_valida_texto($row["numordser"],0,15,"000000000000000");
					$ls_numsolpag = $this->io_validacion->uf_valida_texto($row["numsolpag"],0,15,"000000000000000");
					$ls_nomorgads = $this->io_validacion->uf_valida_texto($row["nomorgads"],0,254,"");
					$ls_numlicemp = $this->io_validacion->uf_valida_texto($row["numlicemp"],0,25,"");
					$ls_modageret = $this->io_validacion->uf_valida_texto($row["modageret"],0,1,"");
					$ls_nomres    = $this->io_validacion->uf_valida_texto($row["nomres"],0,20,"");
					$ls_concomiva = $this->io_validacion->uf_valida_texto($row["concomiva"],0,6,"");
					$ls_cedben    = $this->io_validacion->uf_valida_texto(trim($row["cedben"]),0,10,"");
					$ls_nomben    = $this->io_validacion->uf_valida_texto(trim($row["nomben"]),0,100,"");
					$ls_scctaben  = $this->io_validacion->uf_valida_texto(trim($row["scctaben"]),0,25,"");
					$ls_estmodiva =  $this->io_validacion->uf_valida_monto($row["estmodiva"],0);
					$ls_activot   = $this->io_validacion->uf_valida_texto(trim($row["activo_t"]),0,3,"0");
					$ls_pasivot   = $this->io_validacion->uf_valida_texto(trim($row["pasivo_t"]),0,3,"0");
					$ls_resultadot= $this->io_validacion->uf_valida_texto(trim($row["resultado_t"]),0,3,"0");
					$ls_ctafin    = $this->io_validacion->uf_valida_texto(trim($row["c_financiera"]),0,25,"0");
					$ls_ctafis    = $this->io_validacion->uf_valida_texto(trim($row["c_fiscal"]),0,25,"0");
					$ls_diacadche= $this->io_validacion->uf_valida_texto(trim($row["diacadche"]),0,3,"0");
					$ls_codasiona = $this->io_validacion->uf_valida_texto(trim($row["codasiona"]),0,3,"");
					$li_loncodestpro1 = $this->io_validacion->uf_valida_monto($row["loncodestpro1"],0);
					$li_loncodestpro2 = $this->io_validacion->uf_valida_monto($row["loncodestpro2"],0);
					$li_loncodestpro3 = $this->io_validacion->uf_valida_monto($row["loncodestpro3"],0);
					$li_loncodestpro4 = $this->io_validacion->uf_valida_monto($row["loncodestpro4"],0);
					$li_loncodestpro5 = $this->io_validacion->uf_valida_monto($row["loncodestpro5"],0);
					$li_candeccon    = 2;
					$ls_tipconmon    = 0;
					$li_redconmon    = 1;
					$ls_conrecdoc= $this->io_validacion->uf_valida_texto(trim($row["conrecdoc"]),0,1,"0");
					$ls_sql="INSERT INTO sigesp_empresa (codemp,nombre,titulo,sigemp,direccion,telemp,faxemp,email,website,m01,".
							"                            m02,m03,m04,m05,m06,m07,m08,m09,m10,m11,m12,periodo,vali_nivel,esttipcont,".
							"                 		     formpre,formcont,formplan,formspi,activo,pasivo,ingreso,gasto,resultado,".
							"       					 capital,c_resultad,c_resultan,orden_d,orden_h,soc_gastos,soc_servic,gerente,".
							"							 jefe_compr,activo_h,pasivo_h,resultado_h,ingreso_f,gasto_f,ingreso_p,gasto_p,".
							"							 logo,numniv,nomestpro1,nomestpro2,nomestpro3,nomestpro4,nomestpro5,estvaltra,".
							"							 rifemp,nitemp,estemp,ciuemp,zonpos,estmodape,estdesiva,estprecom,estmodsepsoc,".
							"							 codorgsig,socbieser,estmodest,salinipro,salinieje,numordcom,numordser,numsolpag,".
							"							 nomorgads,numlicemp,modageret,nomres,concomiva,cedben,nomben,scctaben,estmodiva,".
							"							 activo_t,pasivo_t,resultado_t,c_financiera,c_fiscal,diacadche,codasiona,".
							"                            loncodestpro1,loncodestpro2,loncodestpro3,loncodestpro4,loncodestpro5,candeccon,".
							"		                     tipconmon,redconmon,conrecdoc".
							" VALUES ('".$ls_codemp."','".$ls_nombre."','".$ls_titulo."','".$ls_sigemp."','".$ls_diremp."',".
							"		  '".$ls_telemp."','".$ls_faxemp."','".$ls_email."','".$ls_website."',".$li_m01.",".$li_m02.",".
							"          ".$li_m03.",".$li_m04.",".$li_m05.",".$li_m06.",".$li_m07.",".$li_m08.",".$li_m09.",".$li_m10.",".
							"		   ".$li_m11.",".$li_m12.",'".$ls_periodo."',".$li_valniv.",".$li_esttipcont.",'".$ls_formpre."',".
							"		  '".$ls_formcont."','".$ls_formplan."','".$ls_formspi."','".$li_activo."','".$li_pasivo."',".
							"		  '".$li_ingreso."','".$li_gasto."','".$li_resultado."','".$li_capital."','".$ls_ctares."',".
							"         '".$ls_cueres."','".$ls_orden_d."','".$ls_orden_h."','".$ls_socgas."','".$ls_socser."',".
							"		  '".$ls_nomger."','".$ls_jefcom."','".$ls_activo_h."','".$ls_pasivo_h."','".$ls_resultado_h."',".
						 	"		  '".$ls_ingreso_f."','".$ls_gasto_f."','".$ls_ingreso_p."','".$ls_gasto_p."','".$ls_logo."',".
							"		   ".$li_numniv.",'".$ls_nomestpro1."','".$ls_nomestpro2."','".$ls_nomestpro3."','".$ls_nomestpro4."',".
							"		  '".$ls_nomestpro5."',".$ls_estvaltra.",'".$ls_rifemp."','".$ls_nitemp."','".$ls_estemp."',".
							"		  '".$ls_ciuemp."','".$ls_zonpos."',".$ls_estmodape.",".$ls_estdesiva.",".$ls_estprecom.",".
							"		   ".$ls_estmodsepsoc.",'".$ls_codorgsig."',".$ls_socbieser.",".$li_estmodest.",".$ld_salinipro.",".
							"		   ".$ld_salinieje.",'".$ls_numordcom."','".$ls_numordser."','".$ls_numsolpag."','".$ls_nomorgads."',".
							"		  '".$ls_numlicemp."','".$ls_modageret."','".$ls_nomres."','".$ls_concomiva."','".$ls_cedben."',".
							"		  '".$ls_nomben."','".$ls_scctaben."',".$ls_estmodiva.",'".$ls_activot."','".$ls_pasivot."',".
							"		  '".$ls_resultadot."','".$ls_ctafin."','".$ls_ctafis."','".$ls_diacadche."','".$ls_codasiona."',".
							"		  '".$ls_loncodestpro1."','".$ls_loncodestpro2."','".$ls_loncodestpro3."','".$ls_loncodestpro4."',".
							"		  '".$ls_loncodestpro5."','".$li_candeccon."',".$ls_tipconmon.",".$li_redconmon.",'".$ls_conrecdoc."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if ($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Empresa.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
					}
					else
					{ 
						$li_total_insert++;
					}
				}
				else
				{
					$ls_cadena="Hay data inconsistente Empresa.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
	    	$ls_cadena=		   "//*****************************************************************//\r\n";
	    	$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_empresa Registros ".$li_total_select." \r\n";
	    	$ls_cadena=$ls_cadena."   Tabla Destino sigesp_empresa Registros ".$li_total_insert." \r\n";
	    	$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
	    	if ($this->lo_archivo)			
		   	{
		    	@fwrite($this->lo_archivo,$ls_cadena);
		   	}
		}
		return $lb_valido;
	}// end function uf_copiar_empresa
	
function uf_copiar_sistemas()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sistemas
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codsis, nomsis
					 FROM sss_sistemas";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Sistemas.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codsis = $row["codsis"];
				    if (!empty($ls_codsis))
				       {
                         $ls_nomsis = $this->io_validacion->uf_valida_texto($row["nomsis"],0,150,"");
					     $ls_sql = "INSERT INTO sss_sistemas (codsis,nomsis) VALUES ('".$ls_codsis."','".$ls_nomsis."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Sistemas .\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Sistemas.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sss_sistemas Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sss_sistemas Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_sistemas
	
function uf_copiar_eventos()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_eventos
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT evento, deseve
					 FROM sss_eventos";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Eventos.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codeve = $this->io_validacion->uf_valida_texto($row["evento"],0,10,"");
				    if (!empty($ls_codeve))
				       {
                         $ls_deseve = $this->io_validacion->uf_valida_texto($row["deseve"],0,100,"");
					     $ls_sql = "INSERT INTO sss_eventos (evento, deseve) VALUES ('".$ls_codeve."','".$ls_deseve."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Eventos.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Eventos.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sss_eventos Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sss_eventos Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_eventos	
	
function uf_copiar_procedencias()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_procedencias
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT procede, codsis, opeproc, desproc
					 FROM sigesp_procedencias";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Procedencias.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codsis = $this->io_validacion->uf_valida_texto($row["codsis"],0,3,"");
				    if (!empty($ls_codsis))
				       {
                         $ls_procede = $this->io_validacion->uf_valida_texto($row["procede"],0,6,"");
						 $ls_opepro  = $this->io_validacion->uf_valida_texto($row["opeproc"],0,3,"-");
						 $ls_despro  = $this->io_validacion->uf_valida_texto($row["desproc"],0,100,"-");
					     $ls_sql = "INSERT INTO sigesp_procedencias (procede, codsis, opeproc, desproc) VALUES ('".$ls_procede."','".$ls_codsis."','".$ls_opepro."','".$ls_despro."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Procedencias .\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Procedencias.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_procedencias Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_procedencias Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_procedencias	

function uf_copiar_usuarios()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_usuarios
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codusu, cedusu, nomusu, apeusu, pwdusu, telusu, nota, actusu, blkusu, admusu, ultingusu, fotousu
					 FROM sss_usuarios";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Usuarios.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $row["codemp"];
				    if (!empty($ls_codemp))
				       {
                         $ls_codusu    = $this->io_validacion->uf_valida_texto(trim($row["codusu"]),0,30,"-");
					     $ls_cedusu    = $this->io_validacion->uf_valida_texto(trim($row["cedusu"]),0,8,"");
					     $ls_nomusu    = $this->io_validacion->uf_valida_texto(rtrim($row["nomusu"]),0,100,"-");
					     $ls_apeusu    = $this->io_validacion->uf_valida_texto($row["apeusu"],0,50,"-");
					     $ls_pwdusu    = $this->io_validacion->uf_valida_texto($row["pwdusu"],0,50,"-");
					     $ls_telusu    = $this->io_validacion->uf_valida_texto(trim($row["telusu"]),0,20,"");
					     $ls_nota      = $this->io_validacion->uf_valida_texto($row["nota"],0,8000,"");
					     $ls_actusu    = $this->io_validacion->uf_valida_monto($row["actusu"],0);
					     $ls_blkusu    = $this->io_validacion->uf_valida_monto($row["blkusu"],0);
					     $ls_admusu    = $this->io_validacion->uf_valida_monto($row["admusu"],0);
					     $ls_ultingusu = $this->io_validacion->uf_valida_fecha($row["ultingusu"],"1900-01-01");
					     $ls_fotousu   = $this->io_validacion->uf_valida_texto($row["fotousu"],0,254,"");
					     
						 $ls_sql = "INSERT INTO sss_usuarios (codemp,codusu,cedusu,nomusu,apeusu,pwdusu,telusu,nota,actusu,blkusu,admusu,ultingusu,fotousu) 
								                      VALUES ('".$ls_codemp."','".$ls_codusu."','".$ls_cedusu."','".$ls_nomusu."','".$ls_apeusu."','".$ls_pwdusu."',
													          '".$ls_telusu."','".$ls_nota."',".$ls_actusu.",".$ls_blkusu.",".$ls_admusu.",'".$ls_ultingusu."','".$ls_fotousu."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if ($li_row===false)
					   {
						 $lb_valido=false;
						 $ls_cadena="Error al Insertar Usuarios .\r\n".$this->io_sql_destino->message."\r\n";
						 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
						 if ($this->lo_archivo)			
						    {
							  @fwrite($this->lo_archivo,$ls_cadena);
						    }
					   }
					 else
					   { 
					     $li_total_insert++;
					   }
				}
				else
				{
					$ls_cadena="Hay data inconsistente en Usuarios.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
	    $ls_cadena=		   "//*****************************************************************//\r\n";
	    $ls_cadena=$ls_cadena."   Tabla Origen  sss_usuarios Registros ".$li_total_select." \r\n";
	    $ls_cadena=$ls_cadena."   Tabla Destino sss_usuarios Registros ".$li_total_insert." \r\n";
	    $ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
	    if ($this->lo_archivo)			
		   {
		     @fwrite($this->lo_archivo,$ls_cadena);
		   }
		}
		return $lb_valido;
	}// end function uf_copiar_usuarios

function uf_copiar_grupos()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_grupos
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, nomgru, nota
					 FROM sss_grupos";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Grupos.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $row["codemp"];
				    if (!empty($ls_codemp))
				       {
                         $ls_nomgru = $this->io_validacion->uf_valida_texto($row["nomgru"],0,60,"-");
					     $ls_nota   = $this->io_validacion->uf_valida_texto($row["nota"],0,8000,"");
					     $ls_sql = "INSERT INTO sss_grupos (codemp, nomgru, nota) VALUES ('".$ls_codemp."','".$ls_nomgru."','".$ls_nota."')";
					     $li_row=$this->io_sql_destino->execute($ls_sql);
						 if ($li_row===false)
					        {
						      $lb_valido=false;
						      $ls_cadena="Error al Insertar Grupos .\r\n".$this->io_sql_destino->message."\r\n";
						      $ls_cadena=$ls_cadena.$ls_sql."\r\n";
						      if ($this->lo_archivo)			
						         {
							       @fwrite($this->lo_archivo,$ls_cadena);
						         }
					        }
						 else
						   { 
							 $li_total_insert++;
						   }
				       }
				    else
				       {
						 $ls_cadena="Hay data inconsistente en Grupos.\r\n";
						 if ($this->lo_archivo)			
						    {
							  @fwrite($this->lo_archivo,$ls_cadena);
						    }
				       }
			      } 
			$ls_cadena=		   "//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sss_grupos Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sss_grupos Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			   {
				 @fwrite($this->lo_archivo,$ls_cadena);
			   }
		}
		return $lb_valido;
	}// end function uf_copiar_usuarios

function uf_copiar_derechos_grupos()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_derechos_grupos
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, nomgru, codsis, nomven, visible, enabled, leer, incluir, cambiar, eliminar, imprimir, administrativo, anular, ejecutar
					 FROM sss_derechos_grupos";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Derechos Grupos.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codsis = $row["codsis"];
				    if (!empty($ls_codsis))
				       {
                         $ls_nomgru  = $this->io_validacion->uf_valida_texto($row["nomgru"],0,60,"-");
					     $ls_codsis  = $this->io_validacion->uf_valida_texto($row["codsis"],0,3,"-");
						 $ls_nomven  = $this->io_validacion->uf_valida_texto($row["nomven"],0,80,"-");
						 $li_visible = $this->io_validacion->uf_valida_monto($row["visible"],0);
						 $li_enabled = $this->io_validacion->uf_valida_monto($row["enabled"],0);
						 $li_leer    = $this->io_validacion->uf_valida_monto($row["leer"],0);
						 $li_incluir = $this->io_validacion->uf_valida_monto($row["incluir"],0);
						 $li_cambiar = $this->io_validacion->uf_valida_monto($row["cambiar"],0);
						 $li_eliminar = $this->io_validacion->uf_valida_monto($row["eliminar"],0);
						 $li_imprimir = $this->io_validacion->uf_valida_monto($row["imprimir"],0);
						 $li_administrativo = $this->io_validacion->uf_valida_monto($row["administrativo"],0);
						 $li_anular = $this->io_validacion->uf_valida_monto($row["anular"],0);
						 $li_ejecutar = $this->io_validacion->uf_valida_monto($row["ejecutar"],0);
						 
						 $ls_sql = "INSERT INTO sss_derechos_grupos (codemp, nomgru, codsis, nomven, visible, enabled, leer, incluir, cambiar, eliminar, imprimir, administrativo, anular, ejecutar) 
						                 VALUES ('".$ls_codemp."','".$ls_nomgru."','".$ls_codsis."','".$ls_nomven."',".$li_visible.",".$li_enabled.",".$li_leer.",".$li_incluir.",".$li_cambiar.",".$li_eliminar.",".$li_imprimir.",".$li_administrativo.",".$li_anular.",".$li_ejecutar.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Sistemas .\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Derechos Grupos.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sss_derechos_grupos Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sss_derechos_grupos Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_derechos_grupos

function uf_copiar_sistemas_ventanas()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_derechos_grupos
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codsis, nomven, titven, desven
					 FROM sss_sistemas_ventanas";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Sistemas Ventanas .\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codsis = $row["codsis"];
				    if (!empty($ls_codsis))
				       {
						 $ls_nomven  = $this->io_validacion->uf_valida_texto($row["nomven"],0,80,"-");
                         $ls_titven  = $this->io_validacion->uf_valida_texto($row["titven"],0,80,"-");
					     $ls_desven  = $this->io_validacion->uf_valida_texto($row["desven"],0,254,"-");
						 
						 $ls_sql = "INSERT INTO sss_sistemas_ventanas (codsis, nomven, titven, desven) VALUES ('".$ls_codsis."','".$ls_nomven."','".$ls_titven."','".$ls_desven."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Sistemas Ventanas .\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Sistemas Ventanas.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sss_sistemas_ventanas Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sss_sistemas_ventanas Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_sistemas_ventanas

function uf_copiar_permisos_internos()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_permisos_internos
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codusu, codsis, codintper
					 FROM sss_permisos_internos";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Permisos Internos.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $row["codemp"];
				    if (!empty($ls_codemp))
				       {
						 $ls_codusu    = $this->io_validacion->uf_valida_texto($row["codusu"],0,30,"-");
                         $ls_codsis    = $this->io_validacion->uf_valida_texto($row["codsis"],0,3,"-");
					     $ls_codintper = $this->io_validacion->uf_valida_texto($row["codintper"],0,33,"-");
						 
						 $ls_sql = "INSERT INTO sss_permisos_internos (codemp, codusu, codsis, codintper) VALUES ('".$ls_codemp."','".$ls_codusu."','".$ls_codsis."','".$ls_codintper."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Permisos Internos.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Permisos Internos.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sss_permisos_internos Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sss_permisos_internos Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_permisos_internos

function uf_copiar_derechos_usuarios()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_derechos_usuarios
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codusu, codsis, nomven, codintper, visible, enabled, leer, incluir, cambiar, eliminar, imprimir, administrativo, anular, ejecutar
					 FROM sss_derechos_usuarios";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Derechos Usuarios.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $row["codemp"];
				    if (!empty($ls_codemp))
				       {
                         $ls_codusu  = $this->io_validacion->uf_valida_texto($row["codusu"],0,30,"-");
					     $ls_codsis  = $this->io_validacion->uf_valida_texto($row["codsis"],0,3,"-");
						 $ls_nomven  = $this->io_validacion->uf_valida_texto($row["nomven"],0,80,"-");
						 $ls_codintper = $this->io_validacion->uf_valida_texto($row["codintper"],0,80,"-");
						 $li_visible = $this->io_validacion->uf_valida_monto($row["visible"],0);
						 $li_enabled = $this->io_validacion->uf_valida_monto($row["enabled"],0);
						 $li_leer    = $this->io_validacion->uf_valida_monto($row["leer"],0);
						 $li_incluir = $this->io_validacion->uf_valida_monto($row["incluir"],0);
						 $li_cambiar = $this->io_validacion->uf_valida_monto($row["cambiar"],0);
						 $li_eliminar = $this->io_validacion->uf_valida_monto($row["eliminar"],0);
						 $li_imprimir = $this->io_validacion->uf_valida_monto($row["imprimir"],0);
						 $li_administrativo = $this->io_validacion->uf_valida_monto($row["administrativo"],0);
						 $li_anular = $this->io_validacion->uf_valida_monto($row["anular"],0);
						 $li_ejecutar = $this->io_validacion->uf_valida_monto($row["ejecutar"],0);
						 
						 $ls_sql = "INSERT INTO sss_derechos_usuarios (codemp, codusu, codsis, nomven, codintper, visible, enabled, leer, incluir, cambiar, eliminar, imprimir, administrativo, anular, ejecutar) 
						                 VALUES ('".$ls_codemp."','".$ls_codusu."','".$ls_codsis."','".$ls_nomven."','".$ls_codintper."',".$li_visible.",".$li_enabled.",".$li_leer.",".$li_incluir.",".$li_cambiar.",".$li_eliminar.",".$li_imprimir.",".$li_administrativo.",".$li_anular.",".$li_ejecutar.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Derechos Usuarios.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Derechos Usuarios.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sss_derechos_usuarios Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sss_derechos_usuarios Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_derechos_usuarios

function uf_copiar_usuarios_grupos()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_usuarios_grupos
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, nomgru, codusu
					 FROM sss_usuarios_en_grupos";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Usuarios en Grupos.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $row["codemp"];
				    if (!empty($ls_codemp))
				       {
                         $ls_nomgru  = $this->io_validacion->uf_valida_texto($row["nomgru"],0,60,"-");
						 $ls_codusu  = $this->io_validacion->uf_valida_texto($row["codusu"],0,30,"-");
						 					 
						 $ls_sql = "INSERT INTO sss_usuarios_en_grupos (codemp, nomgru, codusu) VALUES ('".$ls_codemp."','".$ls_nomgru."','".$ls_codusu."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Usuarios en Grupos.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Usuarios en Grupos.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sss_usuarios_en_grupos Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sss_usuarios_en_grupos Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_usuarios_grupos

function uf_copiar_monedas()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_monedas
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codmon, denmon, imamon, codpai, tascam, estmonpri
					 FROM sigesp_moneda";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Monedas.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_destino->fetch_row($io_recordset))
			      {
				    $ls_codmon = $this->io_validacion->uf_valida_texto($row["codmon"],0,3,"-");
				    if (!empty($ls_codmon))
				       {
                         $ls_denmon    = $this->io_validacion->uf_valida_texto($row["denmon"],0,25,"-");
						 $ls_imamon    = $this->io_validacion->uf_valida_texto($row["imamon"],0,6,"-");
						 $ls_codpai    = $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"-");
						 $ld_tascamaux = $this->io_validacion->uf_valida_monto($row["tascam"],0);
						 $ld_tascam    = $this->io_rcbsf->uf_convertir_monedabsf($ld_tascamaux,4,1,1000,1);
						 $ld_estmonpri = $this->io_validacion->uf_valida_monto($row["estmonpri"],0);
						 
					     
						 $ls_sql = "INSERT INTO sigesp_moneda (codmon, denmon, imamon, codpai, tascam, estmonpri, tascamaux) 
						                               VALUES ('".$ls_codmon."','".$ls_denmon."','".$ls_imamon."','".$ls_codpai."',".$ld_tascam.",".$ld_estmonpri.",".$ld_tascamaux.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Monedas.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Monedas.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_moneda Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_moneda Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_monedas

function uf_copiar_banco_sigecof()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_banco_sigecof
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codbansig, denbansig
					 FROM sigesp_banco_sigecof";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Monedas.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codbansig = $this->io_validacion->uf_valida_texto($row["codbansig"],0,3,"-");
				    if (!empty($ls_codbansig))
				       {
                         $ls_denbansig = $this->io_validacion->uf_valida_texto($row["denbansig"],0,80,"-");
					     
						 $ls_sql = "INSERT INTO sigesp_banco_sigecof (codbansig, denbansig) 
						                               VALUES ('".$ls_codbansig."','".$ls_denbansig."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Banco De Sigecof.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Banco SIGESCOF.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_banco_sigecof Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_banco_sigecof Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_banco_sigecof

function uf_copiar_catalogo_milco()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_catalogo_milco
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codmil, denmil
					 FROM sigesp_catalogo_milco";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Catalogo Milco.\r\n".$this->io_sql_origen->message."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codmil = $this->io_validacion->uf_valida_texto($row["codmil"],0,15,"---------------");
				    if (!empty($ls_codmil))
				       {
                         $ls_denmil = $this->io_validacion->uf_valida_texto($row["denmil"],0,100,"---seleccione---");
					     
						 $ls_sql = "INSERT INTO sigesp_catalogo_milco (codmil, denmil) 
						                               VALUES ('".$ls_codmil."','".$ls_denmil."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Catalogo Milco.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Catalogo Milco.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_catalogo_milco Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_catalogo_milco Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_catalogo_milco

function uf_copiar_comunidad()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_comunidad
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codpai, codest, codmun, codpar, codcom, nomcom
					 FROM sigesp_comunidad";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Comunidad.\r\n".$this->io_sql_origen->message."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codpai = $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"---");
				    if (!empty($ls_codpai))
				       {
                         $ls_codest = $this->io_validacion->uf_valida_texto($row["codest"],0,3,"---");
						 $ls_codmun = $this->io_validacion->uf_valida_texto($row["codmun"],0,3,"---");
						 $ls_codpar = $this->io_validacion->uf_valida_texto($row["codpar"],0,3,"---");
						 $ls_codcom = $this->io_validacion->uf_valida_texto($row["codcom"],0,3,"---");
						 $ls_nomcom = $this->io_validacion->uf_valida_texto($row["nomcom"],0,80,"-");
					     
						 $ls_sql = "INSERT INTO sigesp_comunidad (codpai, codest, codmun, codpar, codcom, nomcom) 
						                               VALUES ('".$ls_codpai."','".$ls_codest."','".$ls_codmun."','".$ls_codpar."','".$ls_codcom."','".$ls_nomcom."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Comunidad.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Comunidad.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_comunidad Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_comunidad Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_comunidad

function uf_copiar_sigesp_config()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sigesp_config
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codsis, seccion, entry, type, value
					 FROM sigesp_config";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Sigesp Config.\r\n".$this->io_sql_origen->messages."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				    if (!empty($ls_codemp))
				       {
                         $ls_codsis  = $this->io_validacion->uf_valida_texto($row["codsis"],0,3,"");
						 $ls_seccion = $this->io_validacion->uf_valida_texto($row["seccion"],0,60,"");
						 $ls_entry   = $this->io_validacion->uf_valida_texto($row["entry"],0,60,"");
						 $ls_type    = $this->io_validacion->uf_valida_texto($row["type"],0,1,"");
						 $ls_value   = $this->io_validacion->uf_valida_texto($row["value"],0,254,"");
					     
						 $ls_sql = "INSERT INTO sigesp_config (codemp, codsis, seccion, entry, type, value) 
						                               VALUES ('".$ls_codemp."','".$ls_codsis."','".$ls_seccion."','".$ls_entry."','".$ls_type."','".$ls_value."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Sigesp Config.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Sigesp Config.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_config Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_config Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_sigesp_config

function uf_copiar_sigesp_control_numero()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sigesp_control_numero
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codsis, procede, id, prefijo, nro_inicial, nro_final, maxlen, nro_actual, codusu, estact
					 FROM sigesp_ctrl_numero";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Sigesp Control Numero.\r\n".$this->io_sql_origen->message."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				    if (!empty($ls_codemp))
				       {
                         $ls_codsis  = $this->io_validacion->uf_valida_texto($row["codsis"],0,3,"---");
						 $ls_procede = $this->io_validacion->uf_valida_texto($row["procede"],0,6,"-");
						 $ls_codusu  = $this->io_validacion->uf_valida_texto($row["codusu"],0,10,"-");
						 $ls_id      = $this->io_validacion->uf_valida_texto($row["id"],0,4,"-");
						 $ls_prefijo = $this->io_validacion->uf_valida_texto($row["prefijo"],0,6,"------");
						 $li_nroini  = $this->io_validacion->uf_valida_monto($row["nro_inicial"],0);
						 $li_nrofin  = $this->io_validacion->uf_valida_monto($row["nro_final"],0);
						 $li_maxlen  = $this->io_validacion->uf_valida_monto($row["maxlen"],0);
					     $ls_nroact  = $this->io_validacion->uf_valida_texto($row["nro_actual"],0,15,"---------------");
						 $li_estact  = $this->io_validacion->uf_valida_monto($row["maxlen"],0);
						 
						 $ls_sql = "INSERT INTO sigesp_ctrl_numero (codemp, codsis, procede, id, prefijo, nro_inicial, nro_final, maxlen, nro_actual, codusu, estact) 
						                               VALUES ('".$ls_codemp."','".$ls_codsis."','".$ls_procede."','".$ls_id."','".$ls_prefijo."',".$li_nroini.",".$li_nrofin.",".$li_maxlen.",'".$ls_nroact."','".$ls_codusu."',".$li_estact.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Sigesp Control Numero.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Sigesp Control Numero.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_ctrl_numero Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_ctrl_numero Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_sigesp_control_numero

function uf_copiar_sigesp_expediente()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sigesp_expediente
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, numexp, ced_bene, cod_pro
					 FROM sigesp_expediente";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Sigesp Expediente.\r\n".$this->io_sql_origen->message."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				    if (!empty($ls_codemp))
				       {
                         $ls_numexp = $this->io_validacion->uf_valida_texto($row["numexp"],0,15,"---------------");
						 $ls_cedben = $this->io_validacion->uf_valida_texto($row["ced_bene"],0,10,"----------");
						 $ls_codpro = $this->io_validacion->uf_valida_texto($row["cod_pro"],0,10,"----------");
						 
						 $ls_sql = "INSERT INTO sigesp_expediente (codemp, numexp, ced_bene, cod_pro) 
						                               VALUES ('".$ls_codemp."','".$ls_numexp."','".$ls_cedben."','".$ls_codpro."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Sigesp Expediente.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Sigesp Expediente.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_expediente Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_expediente Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_sigesp_expediente

function uf_copiar_sigesp_dt_expediente()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sigesp_dt_expediente
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, numexp, numcom, procede_ori, procede_des, fecha, hora, status, nomusu
					 FROM sigesp_dt_expediente";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Sigesp Detalle Expediente.\r\n".$this->io_sql_origen->message."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				    if (!empty($ls_codemp))
				       {
                         $ls_numexp = $this->io_validacion->uf_valida_texto($row["numexp"],0,15,"-");
						 $ls_numcom = $this->io_validacion->uf_valida_texto($row["numcom"],0,15,"-");
						 $ls_proori = $this->io_validacion->uf_valida_texto($row["procede_ori"],0,6,"-");
						 $ls_prodes = $this->io_validacion->uf_valida_texto($row["procede_des"],0,6,"-");
						 $ls_fecexp = $this->io_validacion->uf_valida_fecha($row["fecha"],'1900-01-01');
						 $li_horexp  = $this->io_validacion->uf_valida_texto($row["hora"],0,8,"00:00");
						 $li_estexp  = $this->io_validacion->uf_valida_monto($row["status"],0);
						 $ls_nomusu  = $this->io_validacion->uf_valida_texto($row["nomusu"],0,60,"-");
						 
						 $ls_sql = "INSERT INTO sigesp_dt_expediente (codemp, numexp, numcom, procede_ori, procede_des, fecha, hora, status, nomusu) 
						                               VALUES ('".$ls_codemp."','".$ls_numexp."','".$ls_numcom."','".$ls_proori."','".$ls_prodes."','".$ls_fecexp."','".$li_horexp."',".$li_estexp.",'".$ls_nomusu."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Sigesp Detalle Expediente.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Sigesp Detalle Expediente.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_dt_expediente Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_dt_expediente Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_sigesp_dt_expediente

function uf_copiar_sigesp_poliza()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sigesp_poliza
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, numpolcon, estatus
					 FROM sigesp_poliza";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Poliza Contable.\r\n".$this->io_sql_origen->message."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				    if (!empty($ls_codemp))
				       {
						 $li_numpolcon = $this->io_validacion->uf_valida_monto($row["numpolcon"],0);
						 $li_estpolcon = $this->io_validacion->uf_valida_monto($row["estatus"],0);
						 
						 $ls_sql = "INSERT INTO sigesp_poliza (codemp, numpolcon, estatus) VALUES ('".$ls_codemp."',".$li_numpolcon.",".$li_estpolcon.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Poliza Contable.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Poliza Contable.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_poliza Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_poliza Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_sigesp_poliza

function uf_copiar_sigesp_dt_poliza()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sigesp_dt_poliza
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, numpolcon, procede, fecha, usuario
					 FROM sigesp_dt_poliza";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Sigesp Detalle Poliza.\r\n".$this->io_sql_origen->message."\r\n";
			 $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		else
		   {			
		     $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
			      {
				    $ls_codemp = $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				    if (!empty($ls_codemp))
				       {
						 $ls_numpolcon = $this->io_validacion->uf_valida_monto($row["numpolcon"],0);
						 $ls_procede   = $this->io_validacion->uf_valida_texto($row["procede"],0,6,"-");
						 $ls_fecpol    = $this->io_validacion->uf_valida_fecha($row["fecha"],'1900-01-01');
						 $ls_nomusu    = $this->io_validacion->uf_valida_texto($row["nomusu"],0,50,"-");
						 
						 $ls_sql = "INSERT INTO sigesp_dt_poliza (codemp, numpolcon, procede, fecha, usuario) 
						                               VALUES ('".$ls_codemp."','".$ls_numpolcon."','".$ls_procede."','".$ls_fecpol."','".$ls_nomusu."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Sigesp Detalle Poliza.\r\n".$this->io_sql_destino->message."\r\n";
							  $ls_cadena=$ls_cadena.$ls_sql."\r\n";
							  if ($this->lo_archivo)			
								 { 
								   @fwrite($this->lo_archivo,$ls_cadena);
								 }
						    }
						  else
							{ 
							  $li_total_insert++;
							}
				       }
				    else
				       {
					     $ls_cadena="Hay data inconsistente en Sigesp Detalle Poliza.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  sigesp_dt_poliza Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino sigesp_dt_poliza Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_sigesp_dt_poliza

function ue_limpiar_sss_basico()
{
	$lb_valido=true;
	
	$this->io_sql_destino->begin_transaction();
	//------------------------------------ Borrar tablas de Seguridad -----------------------------------------
	if($lb_valido)
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_registro_eventos');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_derechos_usuarios');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_derechos_grupos');
		}
		if($lb_valido)			
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_usuarios_en_grupos');
		}					
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_grupos');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_permisos_internos');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_sistemas_ventanas');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_config');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_dt_expediente');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_dt_poliza');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_expediente');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_poliza');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_procedencias');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_catalogo_milco');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_banco_sigecof');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_moneda');			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_eventos');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_sistemas');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sss_usuarios');
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_empresa');
		}	
		
	if($lb_valido)  	
	{
		$this->io_mensajes->message("La data de Seguridad se borró correctamente.");
		$ls_cadena="La data de Seguridad se borró correctamente.\r\n";
		if ($this->lo_archivo)
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		$this->io_mensajes->message("Ocurrió un error al borrar la data de Seguridad. Verifique el archivo txt."); 
	}

	if ($lb_valido)
	{
		$this->io_sql_destino->commit();
	}
	else
	{
		$this->io_sql_destino->rollback();	
	}
	
	return $lb_valido;
}

function uf_limpiar_tabla($as_tabla)
{			
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_limpiar_tabla
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Borra la data de la tabla especificada en la base de datos destino
//				   $as_condicion se agrega por si es necesario algún filtro en la consulta
//	   Creado Por: 
// Fecha Creación: 15/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="DELETE FROM ".$as_tabla;

		$io_recordset=$this->io_sql_destino->execute($ls_sql);

		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Borrar la tabla".$as_tabla.".\r\n".$this->io_sql_destino->message."\r\n";
			print $this->io_sql_destino->message;
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$ls_cadena = "//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla  ".$as_tabla."  Blanqueada  \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
				@fwrite($this->lo_archivo,$as_tabla." \r\n ");
			}
		}		
		return $lb_valido;
	}// end function uf_limpiar_tabla
}
?>