<?PHP
class sigesp_copia_sno_historico
{
	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $io_sql;
		
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_copia_sno_historico()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_copia_sno_historico
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("class_folder/class_validacion.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");/////agregado el 06/12/2007
		
		$this->ls_database_source = $_SESSION["ls_database"];
		$this->ls_database_target = $_SESSION["ls_data_des"];
		$this->io_mensajes        = new class_mensajes();		
		$this->io_funciones       = new class_funciones();
		$this->io_validacion      = new class_validacion();
		$this->io_fecha           = new class_fecha();
		$io_conect	              = new sigesp_include();
		$io_conexion_origen       = $io_conect->uf_conectar();
		$io_conexion_destino       = $io_conect->uf_conectar($this->ls_database_target);
		$this->io_sql_origen      = new class_sql($io_conexion_origen);
		$this->io_sql_destino 	  = new class_sql($io_conexion_destino);
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo         = "resultado/".$_SESSION["ls_data_des"]."_sno_historico_result_".$ld_fecha.".txt";
		$this->lo_archivo         = @fopen("$ls_nombrearchivo","a+");
		$this->ls_codemp          = $_SESSION["la_empresa"]["codemp"];
		$this->io_rcbsf			  = new sigesp_c_reconvertir_monedabsf(); 
		$this->li_candeccon= 4;
		$this->	li_tipconmon= 1;
		$this->li_redconmon=1000;
	}// end function sigesp_copia_sno_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public 
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($this->io_sql);
		unset($this->io_sql_origen);	
		unset($this->io_sql_destino);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_data($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi,$ad_fecininom)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_data
		//		   Access: public
		//	   Creado Por: Ing. Yesenia Moreno
		//    Description: función que busca en cada una de las tablas de nómina del origen y las pasa a las tablas de nómina del destino
		// Fecha Creación: 16/06/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		set_time_limit(0);
		//------------------------------------------- Insertar datos históricos de la nómina -----------------------------------------
		$ls_sql="SELECT codnom,anocur,codperi ".
				"  FROM sno_hperiodo ".
				" WHERE codnom = '".$as_codnom."' ".
				"	AND anocur = '".$ai_anocurnom."' ".
				"	AND codperi = '".$as_codperiadi."' ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
 			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la data histórica.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			while(($row=$this->io_sql_origen->fetch_row($io_recordset))&&($lb_valido))
			{
				if(($as_codnom!="")&&($as_codperiadi!="")&&($ai_anocurnom!=""))
				{
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hnomina($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi,$ad_fecininom);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hperiodo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hsubnomina($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hcargo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_htabulador($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hgrado($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_hprimagrado($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_hasignacioncargo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hunidadadministrativa($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hproyecto($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hproyectopersonal($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hpersonalnomina($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hvacacionespersonal($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hconstante($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hconstantepersonal($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hconcepto($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hconceptopersonal($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_hconceptovacacion($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_hprimaconcepto($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_htipoprestamo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_hprestamo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_hprestamoperiodo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_hprenomina($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_hsalida($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_hresumen($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi);
					}
				}
			}
		}
		//------------------------------------------------------- Fin del convertidor ----------------------------------------------------
		if($lb_valido)
		{	
			$this->io_mensajes->message("La data de nómina históricos se importó correctamente.");
			$ls_cadena="La data de nómina históricos se importó correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al importar la data de nómina históricos. Verifique el archivo txt."); 
		}
		return $lb_valido;
	}// end function uf_convertir_data
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hnomina($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi,$ad_fecininom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hnomina
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hnominas y los inserta en sno_hnomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, desnom, tippernom, despernom, anocurnom, fecininom, peractnom, numpernom, tipnom, subnom, ".
				"		racnom, adenom, espnom, ctnom, ctmetnom, diabonvacnom, diareivacnom, diainivacnom, diatopvacnom, diaincvacnom, ".
				"		consulnom, descomnom, codpronom, codbennom, conaponom, cueconnom, notdebnom, numvounom, recdocnom, tipdocnom, ".
				"		recdocapo, tipdocapo, perresnom, conpernom, conpronom, titrepnom, codorgcestic ".
				"  FROM sno_hnomina ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocurnom ='".$ai_anocurnom."'".
				"   AND peractnom = '".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Nóminas Históricas.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_desnom=$this->io_validacion->uf_valida_texto($row["desnom"],0,100,"");
				$ls_tippernom=$this->io_validacion->uf_valida_texto($row["tippernom"],0,1,"");
				$ls_despernom=$this->io_validacion->uf_valida_texto($row["despernom"],0,20,"");
				$ls_anocurnom=$as_anocur;				
				$ld_fecininom=$ad_fecininom;//$this->io_validacion->uf_valida_fecha($row["fecininom"],"");
				$ls_peractnom=$as_codperi;
				$li_numpernom=$this->io_validacion->uf_valida_monto($row["numpernom"],0); 
				$li_tipnom=$this->io_validacion->uf_valida_monto($row["tipnom"],0);
				$ls_subnom=$this->io_validacion->uf_valida_texto($row["subnom"],0,1,"0");
				$ls_racnom=$this->io_validacion->uf_valida_texto($row["racnom"],0,1,"0");
				$ls_adenom=$this->io_validacion->uf_valida_texto($row["adenom"],0,1,"0");
				$ls_espnom=$this->io_validacion->uf_valida_texto($row["espnom"],0,1,"0");
				$ls_ctnom=$this->io_validacion->uf_valida_texto($row["ctnom"],0,1,"0");
				$ls_ctmetnom=$this->io_validacion->uf_valida_texto($row["ctmetnom"],0,2,"");
				$li_diabonvacnom=$this->io_validacion->uf_valida_monto($row["diabonvacnom"],0);
				$li_diareivacnom=$this->io_validacion->uf_valida_monto($row["diareivacnom"],0);
				$li_diainivacnom=$this->io_validacion->uf_valida_monto($row["diainivacnom"],0);
				$li_diatopvacnom=$this->io_validacion->uf_valida_monto($row["diatopvacnom"],0);
				$li_diaincvacnom=$this->io_validacion->uf_valida_monto($row["diaincvacnom"],0);
				$ls_consulnom=$this->io_validacion->uf_valida_texto($row["consulnom"],0,50,"OCP");
				$ls_descomnom=$this->io_validacion->uf_valida_texto($row["descomnom"],0,1,"");
				$ls_codpronom=$this->io_validacion->uf_valida_texto($row["codpronom"],0,10,"----------");
				$ls_codbennom=$this->io_validacion->uf_valida_texto($row["codbennom"],0,10,"--------");
				$ls_conaponom=$this->io_validacion->uf_valida_texto($row["conaponom"],0,50,"OCP");
				$ls_cueconnom=$this->io_validacion->uf_valida_texto($row["cueconnom"],0,25,"");
				$ls_notdebnom=$this->io_validacion->uf_valida_texto($row["notdebnom"],0,1,"0");
				$ls_numvounom=$this->io_validacion->uf_valida_texto($row["numvounom"],0,1,"0");
				$ls_recdocnom=$this->io_validacion->uf_valida_texto($row["recdocnom"],0,1,"0");
				$ls_tipdocnom=$this->io_validacion->uf_valida_texto($row["tipdocnom"],0,5,"");
				$ls_recdocapo=$this->io_validacion->uf_valida_texto($row["recdocapo"],0,1,"0");
				$ls_tipdocapo=$this->io_validacion->uf_valida_texto($row["tipdocapo"],0,5,"");
				$ls_perresnom=$this->io_validacion->uf_valida_texto($row["perresnom"],0,3,"000");
				$ls_conpernom=$this->io_validacion->uf_valida_texto($row["conpernom"],0,1,"");
				$ls_conpronom=$this->io_validacion->uf_valida_texto($row["conpronom"],0,1,"");
				$ls_titrepnom=$this->io_validacion->uf_valida_texto($row["titrepnom"],0,50,"");
				$ls_codorgcestic=$this->io_validacion->uf_valida_texto($row["codorgcestic"],0,4,"");
				if(($ls_codnom!="")&&($ls_anocurnom!="")&&($ls_peractnom!=""))
				{
					$ls_sql="INSERT INTO sno_hnomina(codemp,codnom,desnom,tippernom,despernom,anocurnom,fecininom,peractnom,numpernom,tipnom,".
							"			 subnom,racnom,adenom,espnom,ctnom,ctmetnom,diabonvacnom,diareivacnom,diainivacnom,diatopvacnom,".
							"			 diaincvacnom,consulnom,descomnom,codpronom,codbennom,conaponom,cueconnom,notdebnom,numvounom,".
							"			 perresnom,recdocnom,recdocapo,tipdocnom,tipdocapo, conpernom, conpronom, titrepnom, codorgcestic)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_desnom."','".$ls_tippernom."','".$ls_despernom."',".
							"			  '".$ls_anocurnom."','".$ld_fecininom."','".$ls_peractnom."',".$li_numpernom.",".$li_tipnom.",".
							"			  ".$ls_subnom.",".$ls_racnom.",'".$ls_adenom."','".$ls_espnom."','".$ls_ctnom."','".$ls_ctmetnom."',".
							"			  ".$li_diabonvacnom.",".$li_diareivacnom.",".$li_diainivacnom.",".$li_diatopvacnom.",".$li_diaincvacnom.",".
							"			  '".$ls_consulnom."','".$ls_descomnom."','".$ls_codpronom."','".$ls_codbennom."','".$ls_conaponom."',".
							"			  '".$ls_cueconnom."','".$ls_notdebnom."','".$ls_numvounom."','".$ls_perresnom."','".$ls_recdocnom."',".
							"			  '".$ls_recdocapo."','".$ls_tipdocnom."','".$ls_tipdocapo."','".$ls_conpernom."','".$ls_conpronom."',".
							"			  '".$ls_titrepnom."','".$ls_codorgcestic."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Nóminas Históricas.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
						else
						{
							$li_total_insert++;
						}
					}
				}
				else
				{
					$ls_cadena="Hay data inconsistente en las Nóminas Históricas.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Año Curso ".$ls_anocurnom.",Perído Actual ".$ls_peractnom." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$ls_codnom." Año ".$ls_anocurnom.",Período ".$ls_peractnom." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hnomina Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hnomina Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hperiodo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hperiodo
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hperiodos y los inserta en sno_hperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, fecdesper, fechasper, cerper, totper, conper, apoconper, obsper, peradi ".
				"  FROM sno_hperiodo ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Períodos Históricos.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codperi=$as_codperi;
				$ld_fecdesper=$this->io_validacion->uf_valida_fecha($row["fecdesper"],"");
				$ld_fechasper=$this->io_validacion->uf_valida_fecha($row["fechasper"],"");
				$li_cerper=$this->io_validacion->uf_valida_monto($row["cerper"],0);
				$li_totper=$this->io_validacion->uf_valida_monto($row["totper"],0);
				$li_conper=$this->io_validacion->uf_valida_monto($row["conper"],0); 
				$li_apoconper=$this->io_validacion->uf_valida_monto($row["apoconper"],0);  
				$ls_obsper=$row["obsper"]; 
				$ls_anocur=$as_anocur;
				$li_peradi=$this->io_validacion->uf_valida_monto($row["peradi"],0);  
				$li_totper=$this->io_rcbsf->uf_convertir_monedabsf($li_totper,2,1,1000,1);
				$li_totperaux=$this->io_validacion->uf_valida_monto($row["totper"],0);  
				if(($ls_codnom!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hperiodo(codemp,codnom,codperi,fecdesper,fechasper,cerper,totper,conper,apoconper,obsper,anocur, peradi, totperaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codperi."','".$ld_fecdesper."','".$ld_fechasper."',".
							"			  ".$li_cerper.",".$li_totper.",".$li_conper.",".$li_apoconper.",'".$ls_obsper."','".$ls_anocur."',".
							"			  ".$li_peradi.",".$li_totperaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Períodos Históricos.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
						else
						{
							$li_total_insert++;
						}
					}
				}
				else
				{
					$ls_cadena="Hay data inconsistente en los Períodos Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hperiodo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hperiodo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hsubnomina($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hsubnomina
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hsubnom y los inserta en sno_hsubnomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codsubnom, anocur, codperi, dessubnom ".
				"  FROM sno_hsubnomina ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi = '".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Subnóminas Históricas.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codsubnom=$this->io_validacion->uf_valida_texto($row["codsubnom"],0,10,"");
				$ls_dessubnom=$this->io_validacion->uf_valida_texto($row["dessubnom"],0,60,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				if(($ls_codnom!="")&&($ls_codsubnom!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hsubnomina(codemp,codnom,codsubnom,dessubnom,anocur,codperi)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codsubnom."','".$ls_dessubnom."',".
							"			  '".$ls_anocur."','".$ls_codperi."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Subnóminas Históricas.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Subnóminas Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Subnómina ".$ls_codsubnom.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sn_hsubnom Registros     ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hsubnomina Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hsubnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hcargo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hcargo
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hcargos y los inserta en sno_hcargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codcar, descar ".
				"  FROM sno_hcargo ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el cargo Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codcar=$this->io_validacion->uf_valida_texto($row["codcar"],0,10,"");
				$ls_descar=$this->io_validacion->uf_valida_texto($row["descar"],0,100,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				if(($ls_codnom!="")&&($ls_codcar!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hcargo(codemp,codnom,codcar,descar,anocur,codperi)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codcar."','".$ls_descar."',".
							"			  '".$ls_anocur."','".$ls_codperi."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el cargo Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Cargos Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Cargo ".$ls_codcar.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hcargo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hcargo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hcargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_htabulador($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_htabulador
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_htablas y los inserta en sno_htabulador
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codtab, destab, maxpasgra ".
				"  FROM sno_htabulador ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el tabulador Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"");
				$ls_destab=$this->io_validacion->uf_valida_texto($row["destab"],0,100,"");
				$li_maxpasgra=$this->io_validacion->uf_valida_monto($row["maxpasgra"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				if(($ls_codnom!="")&&($ls_codtab!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_htabulador(codemp,codnom,codtab,destab,anocur,codperi, maxpasgra)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codtab."','".$ls_destab."',".
							"			  '".$ls_anocur."','".$ls_codperi."',".$li_maxpasgra.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el tabulador Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Tabuladores Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Tabulador ".$ls_codtab.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_htabulador Registros     ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_htabulador Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_htabulador
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hgrado($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hgrado
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hgrados y los inserta en sno_hgrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codtab, codpas, codgra, monsalgra, moncomgra ".
				"  FROM sno_hgrado ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el grado Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"");
				$ls_codpas=$this->io_validacion->uf_valida_texto($row["codpas"],0,15,"");
				$ls_codgra=$this->io_validacion->uf_valida_texto($row["codgra"],0,15,"");
				$li_monsalgra=$this->io_validacion->uf_valida_monto($row["monsalgra"],0);
				$li_moncomgra=$this->io_validacion->uf_valida_monto($row["moncomgra"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_monsalgra=$this->io_rcbsf->uf_convertir_monedabsf($li_monsalgra,2,1,1000,1);
				$li_moncomgra=$this->io_rcbsf->uf_convertir_monedabsf($li_moncomgra,2,1,1000,1);
				$li_monsalgraaux=$this->io_validacion->uf_valida_monto($row["monsalgra"],0);
				$li_moncomgraaux=$this->io_validacion->uf_valida_monto($row["moncomgra"],0);
				if(($ls_codnom!="")&&($ls_codtab!="")&&($ls_codpas!="")&&($ls_codgra!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hgrado(codemp,codnom,codtab,codpas,codgra,monsalgra,moncomgra,anocur,codperi, moncomgraaux, monsalgraaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codtab."','".$ls_codpas."','".$ls_codgra."',".
							"			   ".$li_monsalgra.",".$li_moncomgra.",'".$ls_anocur."','".$ls_codperi."',".$li_moncomgraaux.",".$li_monsalgraaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el grado Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Grados Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Tabulador ".$ls_codtab.",Código Paso ".$ls_codpas.", Código Grado ".$ls_codgra.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hgrado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hgrado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hgrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprimagrado($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprimagrado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_grados y los inserta en sno_grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codtab, codpas, codgra, codpri, despri, monpri ".
				"  FROM sno_hprimagrado ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$li_total_select=0;
		$li_total_insert=0;
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la prima grado Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"");
				$ls_codpas=$this->io_validacion->uf_valida_texto($row["codpas"],0,15,"");
				$ls_codgra=$this->io_validacion->uf_valida_texto($row["codgra"],0,15,"");
				$ls_codpri=$this->io_validacion->uf_valida_texto($row["codpri"],0,15,"");
				$ls_despri=$this->io_validacion->uf_valida_texto($row["despri"],0,100,"");
				$li_monpri=$this->io_validacion->uf_valida_monto($row["monpri"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_monpri=$this->io_rcbsf->uf_convertir_monedabsf($li_monpri,2,1,1000,1);
				$li_monpriaux=$this->io_validacion->uf_valida_monto($row["monpri"],0);
				if(($ls_codnom!="")&&($ls_codtab!="")&&($ls_codpas!="")&&($ls_codgra!="")&&($ls_codpri!=""))
				{
					$ls_sql="INSERT INTO sno_hprimagrado(codemp,codnom,codtab,codpas,codgra,codpri,despri,monpri,anocur,codperi,monpriaux)".
							" VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codtab."','".$ls_codpas."','".$ls_codgra."',".
							" '".$ls_codpri."','".$ls_despri."',".$li_monpri.",'".$ls_anocur."','".$ls_codperi."',".$li_monpriaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la prima grado Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la prima grado Histórico. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Tabulador ".$ls_codtab.",Código Paso ".$ls_codpas.",Código Grado ".$ls_codgra." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hprimagrado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hprimagrado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hprimagrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hasignacioncargo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hasignacioncargo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_rac y los inserta en sno_asignacioncargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codasicar, denasicar, claasicar, minorguniadm, ofiuniadm, uniuniadm, depuniadm, ".
				"		prouniadm, codtab, codpas, codgra, codded, codtipper, numvacasicar, numocuasicar, codproasicar ".
				"  FROM sno_hasignacioncargo ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la asignación de cargo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codasicar=$this->io_validacion->uf_valida_texto($row["codasicar"],0,7,"");
				$ls_denasicar=$this->io_validacion->uf_valida_texto($row["denasicar"],0,100,"");
				$ls_claasicar=$this->io_validacion->uf_valida_texto($row["claasicar"],0,5,"");
				$ls_minorguniadm=$this->io_validacion->uf_valida_texto($row["minorguniadm"],0,4,"");
				$ls_ofiuniadm=$this->io_validacion->uf_valida_texto($row["ofiuniadm"],0,2,"");
				$ls_uniuniadm=$this->io_validacion->uf_valida_texto($row["uniuniadm"],0,2,"");
				$ls_depuniadm=$this->io_validacion->uf_valida_texto($row["depuniadm"],0,2,"");
				$ls_prouniadm=$this->io_validacion->uf_valida_texto($row["prouniadm"],0,2,"");
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"");
				$ls_codpas=$this->io_validacion->uf_valida_texto($row["codpas"],0,2,"");
				$ls_codgra=$this->io_validacion->uf_valida_texto($row["codgra"],0,2,"");
				$ls_codded=$this->io_validacion->uf_valida_texto($row["codded"],0,3,"");
				$ls_codtipper=$this->io_validacion->uf_valida_texto($row["codtipper"],0,4,"");
				$li_numvacasicar=$this->io_validacion->uf_valida_monto($row["numvacasicar"],0);
				$li_numocuasicar=$this->io_validacion->uf_valida_monto($row["numocuasicar"],0);
				$ls_codproasicar=$this->io_validacion->uf_valida_texto($row["codproasicar"],0,33,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				if(($ls_codnom!="")&&($ls_codasicar!=""))
				{
					$ls_sql="INSERT INTO sno_hasignacioncargo(codemp,codnom,codasicar,denasicar,claasicar,minorguniadm,ofiuniadm, ".
							"			 uniuniadm,depuniadm,prouniadm,codtab,codpas,codgra,codded,codtipper,numvacasicar,numocuasicar, ".
							"			 codproasicar,anocur,codperi)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codasicar."','".$ls_denasicar."','".$ls_claasicar."',".
							"			  '".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."','".$ls_prouniadm."',".
							"			  '".$ls_codtab."','".$ls_codpas."','".$ls_codgra."','".$ls_codded."','".$ls_codtipper."',".
							"			  ".$li_numvacasicar.",".$li_numocuasicar.",'".$ls_codproasicar."','".$ls_anocur."','".$ls_codperi."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la asignación de cargo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Asignación de Cargo. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Asignación de Cargo ".$ls_codasicar." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_asignacioncargo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_asignacioncargo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hasignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hunidadadministrativa($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hunidadadministrativa
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hubiad y los inserta en sno_hunidadadmin
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, desuniadm, codprouniadm ".
				"  FROM sno_hunidadadmin ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Unidad Administrativa Histórica.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_minorguniadm=$this->io_validacion->uf_valida_texto($row["minorguniadm"],0,4,"0000");
				$ls_ofiuniadm=$this->io_validacion->uf_valida_texto($row["ofiuniadm"],0,2,"00");
				$ls_uniuniadm=$this->io_validacion->uf_valida_texto($row["uniuniadm"],0,2,"00");
				$ls_depuniadm=$this->io_validacion->uf_valida_texto($row["depuniadm"],0,2,"00");
				$ls_prouniadm=$this->io_validacion->uf_valida_texto($row["prouniadm"],0,2,"00");
				$ls_desuniadm=$this->io_validacion->uf_valida_texto($row["desuniadm"],0,100,"");
				$ls_codprouniadm=$this->io_validacion->uf_valida_texto($row["codprouniadm"],0,33,"");
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				if(($ls_minorguniadm!="")&&($ls_ofiuniadm!="")&&($ls_uniuniadm!="")&&($ls_depuniadm!="")&&($ls_prouniadm!="")&&($ls_codnom!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hunidadadmin(codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,desuniadm,".
							"			 codprouniadm,codnom,anocur,codperi)".
							"VALUES('".$ls_codemp."','".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."',".
							"'".$ls_prouniadm."','".$ls_desuniadm."','".$ls_codprouniadm."','".$ls_codnom."','".$ls_anocur."','".$ls_codperi."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Unidad Administrativa Histórica.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Unidades Administrativas Históricas.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Unidad Administrativa ".$ls_minorguniadm.$ls_ofiuniadm.$ls_uniuniadm.$ls_depuniadm.$ls_prouniadm.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sn_hubiad Registros        ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hunidadadmin Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		return $lb_valido;
	}// end function uf_insert_hunidadadministrativa
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hproyecto($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hproyecto
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_hproyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codproy, nomproy, estproproy ".
				"  FROM sno_hproyecto ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el Proyecto Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp	= $row["codemp"]; 
				$ls_codproy	= $this->io_validacion->uf_valida_texto($row["codproy"],0,10,"");
				$ls_nomproy	= $this->io_validacion->uf_valida_texto($row["nomproy"],0,50,"");
				$ls_estproproy = $this->io_validacion->uf_valida_texto($row["estproproy"],0,33,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				if($ls_codproy!="")
				{
					$ls_sql="INSERT INTO sno_hproyecto (codemp, codproy, nomproy, estproproy, anocur, codperi) ".
							"VALUES('".$ls_codemp."','".$ls_codproy."','".$ls_nomproy."','".$ls_estproproy."','".$ls_anocur."','".$ls_codperi."')";
					$rs_data = $this->io_sql_destino->execute($ls_sql);
					if($rs_data===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el Proyecto Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Proyecto Histórico.\r\n";
					$ls_cadena=$ls_cadena."Código Proyecto ".$ls_codproy." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hproyecto Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hproyecto Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		return $lb_valido;
	}// end function uf_insert_hproyecto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hproyectopersonal($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hproyectopersonal
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hubiad y los inserta en sno_hunidadadmin
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codproy, codper, totdiaper, totdiames, pordiames ".
				"  FROM sno_hproyectopersonal ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los proyectos por personal Histórico.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			   $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			   while(($row=$this->io_sql_origen->fetch_row($io_recordset))&&$lb_valido)
			   {
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codproy=$this->io_validacion->uf_valida_texto($row["codproy"],0,10,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_totdiaper=$this->io_validacion->uf_valida_monto($row["totdiaper"],0);
				$li_totdiames=$this->io_validacion->uf_valida_monto($row["totdiames"],0);
				$li_pordiames=$this->io_validacion->uf_valida_monto($row["pordiames"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				if(($ls_codnom!="")&&($ls_codproy!=""))
				{
					$ls_sql="INSERT INTO sno_hproyectopersonal(codemp, codnom, codproy, codper, totdiaper, totdiames, pordiames, anocur, codperi)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codproy."','".$ls_codper."',".$li_totdiaper.",".
							"			  ".$li_totdiames.",".$li_pordiames.",'".$ls_anocur."','".$ls_codperi."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los proyectos por personal histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los proyectos por personal histórico. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Proyecto ".$ls_codproy." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hproyectopersonal Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hproyectopersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hproyectopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hpersonalnomina($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hpersonalnomina
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hpers_rac y los inserta en sno_hpersonalnomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, horper, minorguniadm, ".
				"		ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, codcueban, tipcuebanper, codcar, ".
				"		fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, codtabvac, sueintper, ".
				"		pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, codcladoc, codubifis, ".
				"		tipcestic, conjub, catjub, codclavia, codunirac, pagtaqper ".
				"  FROM sno_hpersonalnomina ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el personal nomina Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codsubnom=$this->io_validacion->uf_valida_texto($row["codsubnom"],0,10,"0000000000");
				$ls_codasicar=$this->io_validacion->uf_valida_texto($row["codasicar"],0,7,"0000000");
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"00000000000000000000");
				$ls_codgra=$this->io_validacion->uf_valida_texto($row["codgra"],0,2,"00");
				$ls_codpas=$this->io_validacion->uf_valida_texto($row["codpas"],0,2,"00");
				$li_sueper=$this->io_validacion->uf_valida_monto($row["sueper"],0);
				$li_horper=$this->io_validacion->uf_valida_monto($row["horper"],0);				
				$ls_minorguniadm=$this->io_validacion->uf_valida_texto($row["minorguniadm"],0,4,"0000");
				$ls_ofiuniadm=$this->io_validacion->uf_valida_texto($row["ofiuniadm"],0,2,"00");
				$ls_uniuniadm=$this->io_validacion->uf_valida_texto($row["uniuniadm"],0,2,"00");
				$ls_depuniadm=$this->io_validacion->uf_valida_texto($row["depuniadm"],0,2,"00");
				$ls_prouniadm=$this->io_validacion->uf_valida_texto($row["prouniadm"],0,2,"00");
				$li_pagbanper=$this->io_validacion->uf_valida_monto($row["pagbanper"],0);
				$ls_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_codcueban=$this->io_validacion->uf_valida_texto($row["codcueban"],0,25,"");
				$ls_tipcuebanper=$this->io_validacion->uf_valida_texto($row["tipcuebanper"],0,1,"");
				$ls_codcar=$this->io_validacion->uf_valida_texto($row["codcar"],0,10,"0000000000");
				$ld_fecingper=$this->io_validacion->uf_valida_fecha($row["fecingper"],"1900-01-01");
				$ls_staper=$this->io_validacion->uf_valida_texto($row["staper"],0,1,"1");
				$ls_cueaboper=$this->io_validacion->uf_valida_texto($row["cueaboper"],0,25,"");
				$ld_fecculcontr=$this->io_validacion->uf_valida_fecha($row["fecculcontr"],"1900-01-01");
				$ls_codded=$this->io_validacion->uf_valida_texto($row["codded"],0,3,"000");
				$ls_codtipper=$this->io_validacion->uf_valida_texto($row["codtipper"],0,4,"0000");
				$ls_quivacper=$this->io_validacion->uf_valida_texto($row["quivacper"],0,1,"0");
				$ls_codtabvac=$this->io_validacion->uf_valida_texto($row["codtabvac"],0,2,"00");
				$li_sueintper=$this->io_validacion->uf_valida_monto($row["sueintper"],0);
				$li_pagefeper=$this->io_validacion->uf_valida_monto($row["pagefeper"],0);
				$li_sueproper=$this->io_validacion->uf_valida_monto($row["sueproper"],0);
				$ls_codage=$this->io_validacion->uf_valida_texto($row["codage"],0,10,"");
				$ld_fecegrper=$this->io_validacion->uf_valida_fecha($row["fecegrper"],"1900-01-01");				
				$ld_fecsusper=$this->io_validacion->uf_valida_fecha($row["fecsusper"],"1900-01-01");
				$ls_cauegrper=$this->io_validacion->uf_valida_texto($row["cauegrper"],0,254,"");
				$ls_codescdoc=$this->io_validacion->uf_valida_texto($row["codescdoc"],0,4,"0000");
				$ls_codcladoc=$this->io_validacion->uf_valida_texto($row["codcladoc"],0,4,"0000");
				$ls_codubifis=$this->io_validacion->uf_valida_texto($row["codubifis"],0,4,"0000");
				$ls_tipcestic=$this->io_validacion->uf_valida_texto($row["tipcestic"],0,2,"");
				$ls_conjub=$this->io_validacion->uf_valida_texto($row["conjub"],0,4,"");
				$ls_catjub=$this->io_validacion->uf_valida_texto($row["catjub"],0,3,"");
				$ls_codclavia=$this->io_validacion->uf_valida_texto($row["codclavia"],0,1,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$ls_codunirac=$this->io_validacion->uf_valida_texto($row["codunirac"],0,10,"");
				$li_pagtaqper=$this->io_validacion->uf_valida_monto($row["pagtaqper"],0);
				$li_sueper=$this->io_rcbsf->uf_convertir_monedabsf($li_sueper,2,1,1000,1);
				$li_sueintper=$this->io_rcbsf->uf_convertir_monedabsf($li_sueintper,2,1,1000,1);
				$li_sueproper=$this->io_rcbsf->uf_convertir_monedabsf($li_sueproper,2,1,1000,1);
				$li_sueperaux=$this->io_validacion->uf_valida_monto($row["sueper"],0);
				$li_sueintperaux=$this->io_validacion->uf_valida_monto($row["sueintper"],0);
				$li_sueproperaux=$this->io_validacion->uf_valida_monto($row["sueproper"],0);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hpersonalnomina(codemp,codnom,codper,codsubnom,codasicar,codtab,codgra,codpas,sueper,horper,".
							"			 minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,pagbanper,codban,codcueban,tipcuebanper,".
							"			 codcar,fecingper,staper,cueaboper,fecculcontr,codded,codtipper,quivacper,codtabvac,sueintper,".
							"			 pagefeper,sueproper,codage,fecegrper,fecsusper,cauegrper,codescdoc,codcladoc,codubifis,tipcestic,".
							"			 anocur,codperi,conjub,catjub,codclavia, sueperaux, sueintperaux, sueproperaux, codunirac, pagtaqper)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ls_codsubnom."','".$ls_codasicar."',".
							"			 '".$ls_codtab."','".$ls_codgra."','".$ls_codpas."',".$li_sueper.",".$li_horper.",'".$ls_minorguniadm."',".
							"			 '".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."','".$ls_prouniadm."',".$li_pagbanper.",".
							"			 '".$ls_codban."','".$ls_codcueban."','".$ls_tipcuebanper."','".$ls_codcar."','".$ld_fecingper."',".
							"			 '".$ls_staper."','".$ls_cueaboper."','".$ld_fecculcontr."','".$ls_codded."','".$ls_codtipper."',".
							"			 '".$ls_quivacper."','".$ls_codtabvac."',".$li_sueintper.",".$li_pagefeper.",".$li_sueproper.",".
							"			 '".$ls_codage."','".$ld_fecegrper."','".$ld_fecsusper."','".$ls_cauegrper."','".$ls_codescdoc."',".
							"			 '".$ls_codcladoc."','".$ls_codubifis."','".$ls_tipcestic."','".$ls_anocur."','".$ls_codperi."',".
							"			 '".$ls_conjub."','".$ls_catjub."','".$ls_codclavia."',".$li_sueperaux.",".$li_sueintperaux.",".
							"			 ".$li_sueproperaux.",'".$ls_codunirac."',".$li_pagtaqper.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el personal nomina Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Personal Nómina Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hpersonalnomina Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hpersonalnomina Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hpersonalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hvacacionespersonal($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hvacacionespersonal
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hvac_pers y los inserta en sno_hvacacpersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codvac, fecvenvac, fecdisvac, fecreivac, diavac, stavac, sueintbonvac, sueintvac, ".
				"		diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, quisalvac, quireivac, diaadivac, ".
				"		diaadibon, diafer, sabdom, diapag, pagcan, periodo_1, cod_1, nro_dias_1, Monto_1, periodo_2, cod_2, nro_dias_2, ".
				"		Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, Monto_4, periodo_5, ".
				"		cod_5, nro_dias_5, Monto_5 ".
				"  FROM sno_hvacacpersonal ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las vacaciones del personal Histórica.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_codvac=$this->io_validacion->uf_valida_monto($row["codvac"],0);
				$ld_fecvenvac=$this->io_validacion->uf_valida_fecha($row["fecvenvac"],"");
				$ld_fecdisvac=$this->io_validacion->uf_valida_fecha($row["fecdisvac"],"");
				$ld_fecreivac=$this->io_validacion->uf_valida_fecha($row["fecreivac"],"");
				$li_diavac=$this->io_validacion->uf_valida_monto($row["diavac"],0);
				$li_stavac=$this->io_validacion->uf_valida_monto($row["stavac"],1);
				$li_sueintbonvac=$this->io_validacion->uf_valida_monto($row["sueintbonvac"],0); 
				$li_sueintvac=$this->io_validacion->uf_valida_monto($row["sueintvac"],0);
				$li_diabonvac=$this->io_validacion->uf_valida_monto($row["diabonvac"],0);
				$ls_obsvac=$this->io_validacion->uf_valida_texto($row["obsvac"],0,254,"");
				$li_diapenvac=$this->io_validacion->uf_valida_monto($row["diapenvac"],0);
				$ls_persalvac=$this->io_validacion->uf_valida_texto($row["persalvac"],0,3,"");
				$ls_peringvac=$this->io_validacion->uf_valida_texto($row["peringvac"],0,3,"");
				$li_dianorvac=$this->io_validacion->uf_valida_monto($row["dianorvac"],0);
				$li_quisalvac=$this->io_validacion->uf_valida_monto($row["quisalvac"],0); 
				$li_quireivac=$this->io_validacion->uf_valida_monto($row["quireivac"],0);
				$li_diaadivac=$this->io_validacion->uf_valida_monto($row["diaadivac"],0);
				$li_diaadibon=$this->io_validacion->uf_valida_monto($row["diaadibon"],0);
				$li_diafer=$this->io_validacion->uf_valida_monto($row["diafer"],0);
				$li_sabdom=$this->io_validacion->uf_valida_monto($row["sabdom"],0);
				$li_diapag=$this->io_validacion->uf_valida_monto($row["diapag"],0);
				$li_pagcan=$this->io_validacion->uf_valida_monto($row["pagcan"],0);
				$ls_periodo_1=$this->io_validacion->uf_valida_texto($row["periodo_1"],0,3,"");
				$ls_cod_1=$this->io_validacion->uf_valida_texto($row["cod_1"],0,10,"");
				$li_nro_dias_1=$this->io_validacion->uf_valida_monto($row["nro_dias_1"],0); 
				$li_Monto_1=$this->io_validacion->uf_valida_monto($row["Monto_1"],0); 
				$ls_periodo_2=$this->io_validacion->uf_valida_texto($row["periodo_2"],0,3,"");
				$ls_cod_2=$this->io_validacion->uf_valida_texto($row["cod_2"],0,10,"");
				$li_nro_dias_2=$this->io_validacion->uf_valida_monto($row["nro_dias_2"],0);  
				$li_Monto_2=$this->io_validacion->uf_valida_monto($row["Monto_2"],0); 
				$ls_periodo_3=$this->io_validacion->uf_valida_texto($row["periodo_3"],0,3,"");
				$ls_cod_3=$this->io_validacion->uf_valida_texto($row["cod_3"],0,10,"");
				$li_nro_dias_3=$this->io_validacion->uf_valida_monto($row["nro_dias_3"],0);  
				$li_Monto_3=$this->io_validacion->uf_valida_monto($row["Monto_3"],0); 
				$ls_periodo_4=$this->io_validacion->uf_valida_texto($row["periodo_4"],0,3,"");
				$ls_cod_4=$this->io_validacion->uf_valida_texto($row["cod_4"],0,10,"");
				$li_nro_dias_4=$this->io_validacion->uf_valida_monto($row["nro_dias_4"],0); 
				$li_Monto_4=$this->io_validacion->uf_valida_monto($row["Monto_4"],0); 
				$ls_periodo_5=$this->io_validacion->uf_valida_texto($row["periodo_5"],0,3,"");
				$ls_cod_5=$this->io_validacion->uf_valida_texto($row["cod_5"],0,10,"");
				$li_nro_dias_5=$this->io_validacion->uf_valida_monto($row["nro_dias_5"],0); 
				$li_Monto_5=$this->io_validacion->uf_valida_monto($row["Monto_5"],0); 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_sueintbonvac=$this->io_rcbsf->uf_convertir_monedabsf($li_sueintbonvac,2,1,1000,1);
				$li_sueintvac=$this->io_rcbsf->uf_convertir_monedabsf($li_sueintvac,2,1,1000,1);
				$li_Monto_1=$this->io_rcbsf->uf_convertir_monedabsf($li_Monto_1,2,1,1000,1);
				$li_Monto_2=$this->io_rcbsf->uf_convertir_monedabsf($li_Monto_2,2,1,1000,1);
				$li_Monto_3=$this->io_rcbsf->uf_convertir_monedabsf($li_Monto_3,2,1,1000,1);
				$li_Monto_4=$this->io_rcbsf->uf_convertir_monedabsf($li_Monto_4,2,1,1000,1);
				$li_Monto_5=$this->io_rcbsf->uf_convertir_monedabsf($li_Monto_5,2,1,1000,1);
				$li_sueintbonvacaux=$this->io_validacion->uf_valida_monto($row["sueintbonvac"],0); 
				$li_sueintvacaux=$this->io_validacion->uf_valida_monto($row["sueintvac"],0);
				$li_Monto_1aux=$this->io_validacion->uf_valida_monto($row["monto_1"],0); 
				$li_Monto_2aux=$this->io_validacion->uf_valida_monto($row["monto_2"],0); 
				$li_Monto_3aux=$this->io_validacion->uf_valida_monto($row["monto_3"],0); 
				$li_Monto_4aux=$this->io_validacion->uf_valida_monto($row["monto_4"],0); 
				$li_Monto_5aux=$this->io_validacion->uf_valida_monto($row["monto_5"],0); 
				if(($ls_codper!="")&&($li_codvac!=0)&&($ld_fecvenvac!="")&&($ld_fecdisvac!="")&&($ld_fecreivac!="")&&($ls_codperi!="")&&($ls_anocur!="")&&($ls_codnom!=""))
				{
					$ls_sql="INSERT INTO sno_hvacacpersonal(codemp,codper,codvac,fecvenvac,fecdisvac,fecreivac,diavac,stavac,sueintbonvac,".
							"			 sueintvac,diabonvac,obsvac,diapenvac,persalvac,peringvac,dianorvac,quisalvac,quireivac,diaadivac,".
							"			 diaadibon,diafer,sabdom,diapag,pagcan,periodo_1,cod_1,nro_dias_1,Monto_1,periodo_2,cod_2,nro_dias_2,".
							"			 Monto_2,periodo_3,cod_3,nro_dias_3,Monto_3,periodo_4,cod_4,nro_dias_4,Monto_4,periodo_5,cod_5,".
							"			 nro_dias_5,Monto_5,codnom,anocur,codperi, sueintbonvacaux, sueintvacaux, monto_1aux, monto_2aux, monto_3aux, monto_4aux, monto_5aux)".
							"     VALUES ('".$ls_codemp."','".$ls_codper."',".$li_codvac.",'".$ld_fecvenvac."','".$ld_fecdisvac."','".$ld_fecreivac."',".
							"			  ".$li_diavac.",".$li_stavac.",".$li_sueintbonvac.",".$li_sueintvac.",".$li_diabonvac.",'".$ls_obsvac."',".
							"			  ".$li_diapenvac.",'".$ls_persalvac."','".$ls_peringvac."',".$li_dianorvac.",".$li_quisalvac.",".$li_quireivac.",".
							"			  ".$li_diaadivac.",".$li_diaadibon.",".$li_diafer.",".$li_sabdom.",".$li_diapag.",".$li_pagcan.",".
							"			  '".$ls_periodo_1."','".$ls_cod_1."',".$li_nro_dias_1.",".$li_Monto_1.",'".$ls_periodo_2."',".
							"			  '".$ls_cod_2."',".$li_nro_dias_2.",".$li_Monto_2.",'".$ls_periodo_3."','".$ls_cod_3."',".$li_nro_dias_3.",".
							"			  ".$li_Monto_3.",'".$ls_periodo_4."','".$ls_cod_4."',".$li_nro_dias_4.",".$li_Monto_4.",'".$ls_periodo_5."',".
							"			  '".$ls_cod_5."',".$li_nro_dias_5.",".$li_Monto_5.",'".$ls_codnom."','".$ls_anocur."','".$ls_codperi."',".
							"			  ".$li_sueintbonvacaux.",".$li_sueintvacaux.",".$li_Monto_1aux.",".$li_Monto_2aux.",".$li_Monto_3aux.",".
							"			  ".$li_Monto_4aux.",".$li_Monto_5aux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las vacaciones del personal Histórica.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Vacaciones Históricas.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Código Vacación ".$li_codvac.",Fecha Vencimiento ".$ld_fecvenvac.",Fecha Disfrute ".$ld_fecdisvac.",Fecha Reintegro ".$ld_fecreivac.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hvacacpersonal Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hvacacpersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hvacacionespersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconstante($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconstante
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_const y los inserta en sno_constante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon, conespseg ".
				"  FROM sno_hconstante ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la constante Histórica.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codcons=$this->io_validacion->uf_valida_texto($row["codcons"],0,10,"");
				$ls_nomcon=$this->io_validacion->uf_valida_texto($row["nomcon"],0,30,"");
				$ls_unicon=$this->io_validacion->uf_valida_texto($row["unicon"],0,10,"");
				$li_equcon=$this->io_validacion->uf_valida_monto($row["equcon"],0);
				$li_topcon=$this->io_validacion->uf_valida_monto($row["topcon"],0);
				$li_valcon=$this->io_validacion->uf_valida_monto($row["valcon"],0);
				$li_reicon=$this->io_validacion->uf_valida_monto($row["reicon"],0);
				$ls_tipnumcon=$this->io_validacion->uf_valida_texto($row["tipnumcon"],0,1,"0");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$ls_conespseg=$this->io_validacion->uf_valida_texto($row["conespseg"],0,1,"0");
				$li_equconaux=$this->io_validacion->uf_valida_monto($row["equcon"],0);
				$li_topconaux=$this->io_validacion->uf_valida_monto($row["topcon"],0);
				$li_valconaux=$this->io_validacion->uf_valida_monto($row["valcon"],0);
				//$li_equcon=$this->io_rcbsf->uf_convertir_monedabsf($li_equcon,2,1,1000,1);
				//$li_topcon=$this->io_rcbsf->uf_convertir_monedabsf($li_topcon,2,1,1000,1);
				//$li_valcon=$this->io_rcbsf->uf_convertir_monedabsf($li_valcon,2,1,1000,1);
				if(($ls_codnom!="")&&($ls_codcons!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hconstante(codemp,codnom,codcons,nomcon,unicon,equcon,topcon,valcon,reicon,tipnumcon,".
							"anocur, codperi, equconaux, topconaux, valconaux, conespseg) VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codcons."',".
							"'".$ls_nomcon."','".$ls_unicon."',".$li_equcon.",".$li_topcon.",".$li_valcon.",".$li_reicon.",'".$ls_tipnumcon."',".
							"'".$ls_anocur."','".$ls_codperi."',".$li_equconaux.",".$li_topconaux.",".$li_valconaux.",'".$ls_conespseg."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la constante Histórica.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Constantes Históricas.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Constante ".$ls_codcons.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hconstante Registros      ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hconstante Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hconstante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconstantepersonal($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconstantepersonal
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_const_pers y los inserta en sno_constante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codcons, moncon ".
				"  FROM sno_hconstantepersonal ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la constante por personal Histórica.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codcons=$this->io_validacion->uf_valida_texto($row["codcons"],0,10,"");
				$li_moncon=$this->io_validacion->uf_valida_monto($row["moncon"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_monconaux=$this->io_validacion->uf_valida_monto($row["moncon"],0);
				//$li_moncon=$this->io_rcbsf->uf_convertir_monedabsf($li_moncon,2,1,1000,1);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codcons!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hconstantepersonal(codemp,codnom,codper,codcons,moncon,anocur,codperi, monconaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ls_codcons."',".$li_moncon.",'".$ls_anocur."',".
							"			  '".$ls_codperi."',".$li_monconaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la constante personal Histórica.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Constantes Personal Históricas.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Constante ".$ls_codcons.",Código Personal ".$ls_codper.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hconstantepersonal Registros         ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hconstantepersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hconstantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconcepto($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconcepto
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hconce y los inserta en sno_hconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, nomcon, titcon, sigcon, forcon, glocon, acumaxcon, valmincon, valmaxcon, concon, ".
				"		cueprecon, cueconcon, aplisrcon, sueintcon, sueintvaccon, conprenom, intprocon, codpro, forpatcon, ".
				"		cueprepatcon, cueconpatcon, titretempcon, titretpatcon, valminpatcon, valmaxpatcon, codprov, cedben, ".
				"		aplarccon, conprocon ".
				"  FROM sno_hconcepto ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el concepto Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");
				$ls_nomcon=$this->io_validacion->uf_valida_texto($row["nomcon"],0,30,"");
				$ls_titcon=$this->io_validacion->uf_valida_texto($row["titcon"],0,254,"");
				$ls_sigcon=$this->io_validacion->uf_valida_texto($row["sigcon"],0,1,"");
				$ls_forcon=$this->io_validacion->uf_valida_texto($row["forcon"],0,500,"");
				$li_glocon=$this->io_validacion->uf_valida_monto($row["glocon"],0);
				$li_acumaxcon=$this->io_validacion->uf_valida_monto($row["acumaxcon"],0);
				$li_valmincon=$this->io_validacion->uf_valida_monto($row["valmincon"],0);
				$li_valmaxcon=$this->io_validacion->uf_valida_monto($row["valmaxcon"],0);
				$ls_concon=$this->io_validacion->uf_valida_texto($row["concon"],0,500,"");
				$ls_cueprecon=$this->io_validacion->uf_valida_texto($row["cueprecon"],0,25,"");
				$ls_cueconcon=$this->io_validacion->uf_valida_texto($row["cueconcon"],0,25,"");
				$li_aplisrcon=$this->io_validacion->uf_valida_monto($row["aplisrcon"],0);
				$li_sueintcon=$this->io_validacion->uf_valida_monto($row["sueintcon"],0);
				$li_sueintvaccon=$this->io_validacion->uf_valida_monto($row["sueintvaccon"],0);
				$li_conprenom=$this->io_validacion->uf_valida_monto($row["conprenom"],1);
				$ls_intprocon=$this->io_validacion->uf_valida_texto($row["intprocon"],0,1,"0");
				$ls_codpro=$this->io_validacion->uf_valida_texto($row["codpro"],0,33,"");
				$ls_forpatcon=$this->io_validacion->uf_valida_texto($row["forpatcon"],0,500,""); 
				$ls_cueprepatcon=$this->io_validacion->uf_valida_texto($row["cueprepatcon"],0,25,"");
				$ls_cueconpatcon=$this->io_validacion->uf_valida_texto($row["cueconpatcon"],0,25,"");
				$ls_titretempcon=$this->io_validacion->uf_valida_texto($row["titretempcon"],0,10,"");
				$ls_titretpatcon=$this->io_validacion->uf_valida_texto($row["titretpatcon"],0,10,"");
				$li_valminpatcon=$this->io_validacion->uf_valida_monto($row["valminpatcon"],0);
				$li_valmaxpatcon=$this->io_validacion->uf_valida_monto($row["valmaxpatcon"],0);				
				$ls_codprov=$this->io_validacion->uf_valida_texto($row["codprov"],0,10,"----------");
				$ls_codprov = $this->io_funciones->uf_cerosizquierda($ls_codprov,10);
				$ls_cedben=$this->io_validacion->uf_valida_texto($row["cedben"],0,10,"----------");
				$li_aplarccon=$this->io_validacion->uf_valida_texto($row["aplarccon"],0,1,"0");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$ls_conprocon=$this->io_validacion->uf_valida_texto($row["conprocon"],0,1,"0");
				$li_acumaxcon=$this->io_rcbsf->uf_convertir_monedabsf($li_acumaxcon,2,1,1000,1);
				$li_valmincon=$this->io_rcbsf->uf_convertir_monedabsf($li_valmincon,2,1,1000,1);
				$li_valmaxcon=$this->io_rcbsf->uf_convertir_monedabsf($li_valmaxcon,2,1,1000,1);
				$li_valminpatcon=$this->io_rcbsf->uf_convertir_monedabsf($li_valminpatcon,2,1,1000,1);
				$li_valmaxpatcon=$this->io_rcbsf->uf_convertir_monedabsf($li_valmaxpatcon,2,1,1000,1);
				$li_acumaxconaux=$this->io_validacion->uf_valida_monto($row["acumaxcon"],0);
				$li_valminconaux=$this->io_validacion->uf_valida_monto($row["valmincon"],0);
				$li_valmaxconaux=$this->io_validacion->uf_valida_monto($row["valmaxcon"],0);
				$li_valminpatconaux=$this->io_validacion->uf_valida_monto($row["valminpatcon"],0);
				$li_valmaxpatconaux=$this->io_validacion->uf_valida_monto($row["valmaxpatcon"],0);				
				if(($ls_codnom!="")&&($ls_codconc!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hconcepto(codemp,codnom,codconc,nomcon,titcon,sigcon,forcon,glocon,acumaxcon,valmincon,".
							"			 valmaxcon,concon,cueprecon,cueconcon,aplisrcon,sueintcon,sueintvaccon,conprenom,intprocon,".
							"			 codpro,forpatcon,cueprepatcon,cueconpatcon,titretempcon,titretpatcon,valminpatcon,valmaxpatcon,".
							"			 codprov,cedben,aplarccon,anocur,codperi, acumaxconaux, valminconaux, valmaxconaux, valminpatconaux, ".
							"			 valmaxpatconaux, conprocon)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codconc."','".$ls_nomcon."','".$ls_titcon."','".$ls_sigcon."',".
							"			  '".$ls_forcon."',".$li_glocon.",".$li_acumaxcon.",".$li_valmincon.",".$li_valmaxcon.",'".$ls_concon."',".
							"			  '".$ls_cueprecon."','".$ls_cueconcon."',".$li_aplisrcon.",".$li_sueintcon.",".$li_sueintvaccon.",".
							"			  ".$li_conprenom.",'".$ls_intprocon."','".$ls_codpro."','".$ls_forpatcon."','".$ls_cueprepatcon."',".
							"			  '".$ls_cueconpatcon."','".$ls_titretempcon."','".$ls_titretpatcon."',".$li_valminpatcon.",".
							"			  ".$li_valmaxpatcon.",'".$ls_codprov."','".$ls_cedben."',".$li_aplarccon.",'".$ls_anocur."',".
							"			  '".$ls_codperi."',".$li_acumaxconaux.", ".$li_valminconaux.",".$li_valmaxconaux.",".
							"			  ".$li_valminpatconaux.",".$li_valmaxpatconaux.",'".$ls_conprocon."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el concepto Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Conceptos Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Concepto ".$ls_codconc.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sn_hconce Registros         ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hconcepto Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconceptopersonal($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconceptopersonal
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hconce_pers y los inserta en sno_hconceptopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat ".
				"  FROM sno_hconceptopersonal ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el concepto personal Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");
				$li_aplcon=$this->io_validacion->uf_valida_monto($row["aplcon"],0);
				$li_valcon=$this->io_validacion->uf_valida_monto($row["valcon"],0);
				$li_acuemp=$this->io_validacion->uf_valida_monto($row["acuemp"],0);
				$li_acuiniemp=$this->io_validacion->uf_valida_monto($row["acuiniemp"],0);
				$li_acupat=$this->io_validacion->uf_valida_monto($row["acupat"],0);
				$li_acuinipat=$this->io_validacion->uf_valida_monto($row["acuinipat"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_valcon=$this->io_rcbsf->uf_convertir_monedabsf($li_valcon,2,1,1000,1);
				$li_acuemp=$this->io_rcbsf->uf_convertir_monedabsf($li_acuemp,2,1,1000,1);
				$li_acuiniemp=$this->io_rcbsf->uf_convertir_monedabsf($li_acuiniemp,2,1,1000,1);
				$li_acupat=$this->io_rcbsf->uf_convertir_monedabsf($li_acupat,2,1,1000,1);
				$li_acuinipat=$this->io_rcbsf->uf_convertir_monedabsf($li_acuinipat,2,1,1000,1);
				$li_valconaux=$this->io_validacion->uf_valida_monto($row["valcon"],0);
				$li_acuempaux=$this->io_validacion->uf_valida_monto($row["acuemp"],0);
				$li_acuiniempaux=$this->io_validacion->uf_valida_monto($row["acuiniemp"],0);
				$li_acupataux=$this->io_validacion->uf_valida_monto($row["acupat"],0);
				$li_acuinipataux=$this->io_validacion->uf_valida_monto($row["acuinipat"],0);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codconc!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hconceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,".
							"acupat,acuinipat,anocur,codperi, valconaux, acuempaux, acuiniempaux, acupataux, acuinipataux) VALUES ".
							"('".$ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ls_codconc."',".$li_aplcon.",".$li_valcon.",".$li_acuemp.",".
							"".$li_acuiniemp.",".$li_acupat.",".$li_acuinipat.",'".$ls_anocur."','".$ls_codperi."',".$li_acuinipataux.",".
							"".$li_acupataux.",".$li_acuiniempaux.",".$li_acuempaux.",".$li_valconaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el concepto personal Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Conceptos Personal Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Conceptos ".$ls_codconc.",Código Personal ".$ls_codper.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hconceptopersonal Registros        ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hconceptopersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hconceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconceptovacacion($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconceptovacacion
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hvac_conce y los inserta en sno_hconceptovacacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, forsalvac, acumaxsalvac, minsalvac, maxsalvac, consalvac, forpatsalvac, ".
				"		minpatsalvac, maxpatsalvac, forreivac, acumaxreivac, minreivac, maxreivac, conreivac, forpatreivac, ".
				"		minpatreivac, maxpatreivac".
				"  FROM sno_hconceptovacacion ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el concepto vacación Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");
				$ls_forsalvac=$this->io_validacion->uf_valida_texto($row["forsalvac"],0,254,"");
				$li_acumaxsalvac=$this->io_validacion->uf_valida_monto($row["acumaxsalvac"],0);
				$li_minsalvac=$this->io_validacion->uf_valida_monto($row["minsalvac"],0);
				$li_maxsalvac=$this->io_validacion->uf_valida_monto($row["maxsalvac"],0);
				$ls_consalvac=$this->io_validacion->uf_valida_texto($row["consalvac"],0,254,"");
				$ls_forpatsalvac=$this->io_validacion->uf_valida_texto($row["forpatsalvac"],0,254,"");
				$li_minpatsalvac=$this->io_validacion->uf_valida_monto($row["minpatsalvac"],0);
				$li_maxpatsalvac=$this->io_validacion->uf_valida_monto($row["maxpatsalvac"],0);
				$ls_forreivac=$this->io_validacion->uf_valida_texto($row["forreivac"],0,254,"");
				$li_acumaxreivac=$this->io_validacion->uf_valida_monto($row["acumaxreivac"],0);
				$li_minreivac=$this->io_validacion->uf_valida_monto($row["minreivac"],0);
				$li_maxreivac=$this->io_validacion->uf_valida_monto($row["maxreivac"],0);
				$ls_conreivac=$this->io_validacion->uf_valida_texto($row["conreivac"],0,254,"");
				$ls_forpatreivac=$this->io_validacion->uf_valida_texto($row["forpatreivac"],0,254,"");
				$li_minpatreivac=$this->io_validacion->uf_valida_monto($row["minpatreivac"],0);
				$li_maxpatreivac=$this->io_validacion->uf_valida_monto($row["maxpatreivac"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_acumaxsalvac=$this->io_rcbsf->uf_convertir_monedabsf($li_acumaxsalvac,2,1,1000,1);
				$li_minsalvac=$this->io_rcbsf->uf_convertir_monedabsf($li_minsalvac,2,1,1000,1);
				$li_maxsalvac=$this->io_rcbsf->uf_convertir_monedabsf($li_maxsalvac,2,1,1000,1);
				$li_minpatsalvac=$this->io_rcbsf->uf_convertir_monedabsf($li_minpatsalvac,2,1,1000,1);
				$li_maxpatsalvac=$this->io_rcbsf->uf_convertir_monedabsf($li_maxpatsalvac,2,1,1000,1);
				$li_acumaxreivac=$this->io_rcbsf->uf_convertir_monedabsf($li_acumaxreivac,2,1,1000,1);
				$li_minreivac=$this->io_rcbsf->uf_convertir_monedabsf($li_minreivac,2,1,1000,1);
				$li_maxreivac=$this->io_rcbsf->uf_convertir_monedabsf($li_maxreivac,2,1,1000,1);
				$li_minpatreivac=$this->io_rcbsf->uf_convertir_monedabsf($li_minpatreivac,2,1,1000,1);
				$li_maxpatreivac=$this->io_rcbsf->uf_convertir_monedabsf($li_maxpatreivac,2,1,1000,1);
				$li_acumaxsalvacaux=$this->io_validacion->uf_valida_monto($row["acumaxsalvac"],0);
				$li_minsalvacaux=$this->io_validacion->uf_valida_monto($row["minsalvac"],0);
				$li_maxsalvacaux=$this->io_validacion->uf_valida_monto($row["maxsalvac"],0);
				$li_minpatsalvacaux=$this->io_validacion->uf_valida_monto($row["minpatsalvac"],0);
				$li_maxpatsalvacaux=$this->io_validacion->uf_valida_monto($row["maxpatsalvac"],0);
				$li_acumaxreivacaux=$this->io_validacion->uf_valida_monto($row["acumaxreivac"],0);
				$li_minreivacaux=$this->io_validacion->uf_valida_monto($row["minreivac"],0);
				$li_maxreivacaux=$this->io_validacion->uf_valida_monto($row["maxreivac"],0);
				$li_minpatreivacaux=$this->io_validacion->uf_valida_monto($row["minpatreivac"],0);
				$li_maxpatreivacaux=$this->io_validacion->uf_valida_monto($row["maxpatreivac"],0);
				if(($ls_codnom!="")&&($ls_codconc!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hconceptovacacion(codemp,codnom,codconc,forsalvac,acumaxsalvac,minsalvac,maxsalvac,".
							"			 consalvac,forpatsalvac,minpatsalvac,maxpatsalvac,forreivac,acumaxreivac,minreivac,maxreivac,".
							"			 conreivac,forpatreivac,minpatreivac,maxpatreivac,anocur,codperi, acumaxsalvacaux, minsalvacaux, ".
							"			 maxsalvacaux, minpatsalvacaux, maxpatsalvacaux, acumaxreivacaux, minreivacaux, maxreivacaux, ".
							"			 minpatreivacaux, maxpatreivacaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codconc."','".$ls_forsalvac."',".$li_acumaxsalvac.",".
							"			  ".$li_minsalvac.",".$li_maxsalvac.",'".$ls_consalvac."','".$ls_forpatsalvac."',".
							"			  ".$li_minpatsalvac.",".$li_maxpatsalvac.",'".$ls_forreivac."',".$li_acumaxreivac.",".$li_minreivac.",".
							"			  ".$li_maxreivac.",'".$ls_conreivac."','".$ls_forpatreivac."',".$li_minpatreivac.",".$li_maxpatreivac.",".
							"			  '".$ls_anocur."','".$ls_codperi."',".$li_acumaxsalvacaux.",".$li_minsalvacaux.",".$li_maxpatsalvacaux.",".
							"			  ".$li_minpatsalvacaux.",".$li_maxpatsalvacaux.",".$li_acumaxreivacaux.",".$li_minreivacaux.",".$li_maxreivacaux.",".
							"			  ".$li_minpatreivacaux.",".$li_maxpatreivacaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el concepto vacación Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Conceptos Vacación Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Conceptos ".$ls_codconc.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hconceptovacacion Registros         ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hconceptovacacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hconceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprimaconcepto($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprimaconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_mpc y los inserta en sno_primaconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, anopri, valpri  ".
				"  FROM sno_hprimaconcepto ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la prima concepto Histórica.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");
				$li_anopri=$this->io_validacion->uf_valida_monto($row["anopri"],0);
				$li_valpri=$this->io_validacion->uf_valida_monto($row["valpri"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_valpri=$this->io_rcbsf->uf_convertir_monedabsf($li_valpri,2,1,1000,1);
				$li_valpriaux=$this->io_validacion->uf_valida_monto($row["valpri"],0);
				if(($ls_codnom!="")&&($ls_codconc!="")&&($li_anopri!=0))
				{
					$ls_sql="INSERT INTO sno_hprimaconcepto(codemp,codnom,codconc,anopri,valpri,anocur,codperi, valpriaux)".
							" VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codconc."',".$li_anopri.",".$li_valpri.",".
							"'".$ls_anocur."','".$ls_codperi."',".$li_valpriaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la prima concepto.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Prima Concepto Histórica. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Concepto ".$ls_codconc.",Año Prima ".$li_anopri." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hprimaconcepto Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hprimaconcepto Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hprimaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_htipoprestamo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_htipoprestamo
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hprest y los inserta en sno_htipoprestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codtippre, destippre ".
				"  FROM sno_htipoprestamo ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el tipo de prestamo Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codtippre=$this->io_validacion->uf_valida_texto($row["codtippre"],0,10,"");
				$ls_destippre=$this->io_validacion->uf_valida_texto($row["destippre"],0,100,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				if(($ls_codnom!="")&&($ls_codtippre!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_htipoprestamo(codemp,codnom,codtippre,destippre,anocur,codperi)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codtippre."','".$ls_destippre."','".$ls_anocur."','".$ls_codperi."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el tipo de prestamo Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Tipos de Prestamo Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Tipo Prestamo ".$ls_codtippre.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_htipoprestamo Registros         ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_htipoprestamo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_htipoprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprestamo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprestamo
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hprepe y los inserta en sno_hprestamos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, numpre, codtippre, codconc, monpre, numcuopre, perinipre, monamopre, stapre, ".
				"		fecpre, obsrecpre, obssuspre ".
				"  FROM sno_hprestamos ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el prestamo Histórico.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$li_i=0;
		   $li_total_select = $this->io_sql_origen->num_rows($io_recordset);
		   while($row=$this->io_sql_origen->fetch_row($io_recordset))
		   {
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_numpre=$this->io_validacion->uf_valida_monto($row["numpre"],0);
				$ls_codtippre=$this->io_validacion->uf_valida_texto($row["codtippre"],0,10,"");
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");
				$li_monpre=$this->io_validacion->uf_valida_monto($row["monpre"],0);
				$li_numcuopre=$this->io_validacion->uf_valida_monto($row["numcuopre"],0);
				$ls_perinipre=$this->io_validacion->uf_valida_texto($row["perinipre"],0,3,"");
				$li_monamopre=$this->io_validacion->uf_valida_monto($row["monamopre"],0);
				$li_stapre=$this->io_validacion->uf_valida_monto($row["stapre"],0);
				$ld_fecpre=$this->io_validacion->uf_valida_fecha($row["fecpre"],"1900-01-01");
				$ls_obsrecpre=$this->io_validacion->uf_valida_texto($row["obsrecpre"],0,5000,"");
				$ls_obssuspre=$this->io_validacion->uf_valida_texto($row["obssuspre"],0,5000,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_monpre=$this->io_rcbsf->uf_convertir_monedabsf($li_monpre,2,1,1000,1);
				$li_monamopre=$this->io_rcbsf->uf_convertir_monedabsf($li_monamopre,2,1,1000,1);
				$li_monpreaux=$this->io_validacion->uf_valida_monto($row["monpre"],0);
				$li_monamopreaux=$this->io_validacion->uf_valida_monto($row["monamopre"],0);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codtippre!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
	
					$ls_sql="INSERT INTO sno_hprestamos(codemp,codnom,codper,numpre,codtippre,codconc,monpre,numcuopre,perinipre,".
							"			 monamopre,stapre,fecpre,obsrecpre,obssuspre,anocur,codperi, monpreaux, monamopreaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."',".$li_numpre.",'".$ls_codtippre."',".
							"			  '".$ls_codconc."',".$li_monpre.",".$li_numcuopre.",'".$ls_perinipre."',".$li_monamopre.",".
							"			  ".$li_stapre.",'".$ld_fecpre."','".$ls_obsrecpre."','".$ls_obssuspre."','".$ls_anocur."',".
							"			  '".$ls_codperi."',".$li_monpreaux.",".$li_monamopreaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar  el prestamo Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Prestamo Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Código Tipo Prestamo ".$ls_codtippre.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hprestamos Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hprestamos Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprestamoperiodo($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprestamoperiodo
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hprepeper y los inserta en sno_hprestamosperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, numpre, codtippre, numcuo, percob, feciniper, fecfinper, moncuo, estcuo ".
				"  FROM sno_hprestamosperiodo ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el prestamo periodo Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_numpre=$this->io_validacion->uf_valida_monto($row["numpre"],0);
				$ls_codtippre=$this->io_validacion->uf_valida_texto($row["codtippre"],0,10,"");
				$li_numcuo=$this->io_validacion->uf_valida_monto($row["numcuo"],0);
				$ls_percob=$this->io_validacion->uf_valida_texto($row["percob"],0,3,"");
				$ld_feciniper=$this->io_validacion->uf_valida_fecha($row["feciniper"],"1900-01-01");
				$ld_fecfinper=$this->io_validacion->uf_valida_fecha($row["fecfinper"],"1900-01-01");
				$li_moncuo=$this->io_validacion->uf_valida_monto($row["moncuo"],0);
				$li_estcuo=$this->io_validacion->uf_valida_monto($row["estcuo"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_moncuo=$this->io_rcbsf->uf_convertir_monedabsf($li_moncuo,2,1,1000,1);
				$li_moncuoaux=$this->io_validacion->uf_valida_monto($row["moncuo"],0);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codtippre!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hprestamosperiodo(codemp,codnom,codper,numpre,codtippre,numcuo,percob,feciniper,".
							"fecfinper,moncuo,estcuo,anocur,codperi, moncuoaux) VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."',".
							"".$li_numpre.",'".$ls_codtippre."',".$li_numcuo.",'".$ls_percob."','".$ld_feciniper."','".$ld_fecfinper."',".
							"".$li_moncuo.",".$li_estcuo.",'".$ls_anocur."','".$ls_codperi."',".$li_moncuoaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar  el prestamo periodo Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Prestamos Perído Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Código Tipo Prestamo ".$ls_codtippre.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hprestamosperiodo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hprestamosperiodo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hprestamoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprestamoamortizado($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprestamoamortizado
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de uf_insert_hprestamoamortizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, anocur, codperi, numamo, peramo, fecamo, monamo, desamo ".
				"  FROM sno_hprestamosamortizado ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el prestamo amortizado Histórico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_numpre=$this->io_validacion->uf_valida_monto($row["numpre"],0);
				$ls_codtippre=$this->io_validacion->uf_valida_texto($row["codtippre"],0,10,"");
				$li_numamo=$this->io_validacion->uf_valida_monto($row["numamo"],0);
				$ls_peramo=$this->io_validacion->uf_valida_texto($row["peramo"],0,3,"");
				$ld_fecamo=$this->io_validacion->uf_valida_fecha($row["fecamo"],"1900-01-01");
				$li_monamo=$this->io_validacion->uf_valida_monto($row["monamo"],0);
				$ls_desamo=$this->io_validacion->uf_valida_texto($row["desamo"],0,8000,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_monamo=$this->io_rcbsf->uf_convertir_monedabsf($li_monamo,2,1,1000,1);
				$li_monamoaux=$this->io_validacion->uf_valida_monto($row["monamo"],0);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codtippre!="")&&($ls_codperi!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_prestamosamortizado(codemp, codnom, codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo, ".
							"			 anocur,codperi, monamoaux) ".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."',".$li_numpre.",'".$ls_codtippre."',".$li_numamo.",".
							"			  '".$ls_peramo."','".$ld_fecamo."',".$li_monamo.",'".$ls_desamo."','".$ls_anocur."','".$ls_codperi."',".
							"			   ".$li_monamoaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar  el prestamo amortizado Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Prestamos amortizado Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Código Tipo Prestamo ".$ls_codtippre.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hprestamosamortizado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hprestamosamortizado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hprestamoamortizado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprenomina($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprenomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_prenomina y los inserta en sno_prenomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, codconc, tipprenom, valprenom, valhis ".				
				"  FROM sno_hprenomina ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la prenomina histórica.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				//$ls_codperi=$this->io_validacion->uf_valida_texto($row["codperi"],0,3,"");
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");				
				$ls_tipprenom=$this->io_validacion->uf_valida_texto($row["tipprenom"],0,2,"");				
				$li_valprenom=$this->io_validacion->uf_valida_monto($row["valprenom"],0);
				$li_valhis=$this->io_validacion->uf_valida_monto($row["valhis"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_valprenom=$this->io_rcbsf->uf_convertir_monedabsf($li_valprenom,2,1,1000,1);
				$li_valhis=$this->io_rcbsf->uf_convertir_monedabsf($li_valhis,2,1,1000,1);
				$li_valprenomaux=$this->io_validacion->uf_valida_monto($row["valprenom"],0);
				$li_valhisaux=$this->io_validacion->uf_valida_monto($row["valhis"],0);

				if(($ls_codnom!="")&&($ls_codperi!="")&&($ls_codper!="")&&($ls_codconc!="")&&($ls_tipprenom!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hprenomina(codemp, codnom, codper, codperi, codconc, tipprenom, valprenom, valhis, anocur, ".
							"			 valprenomaux, valhisaux) ".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ls_codperi."',".$ls_codconc.",".
							"			  '".$ls_tipprenom."',".$li_valprenom.",".$li_valhis.",'".$ls_anocur."',".$li_valprenomaux.",".$li_valhisaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la prenomina Histórica.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Prenómina. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",código Periodo ".$ls_codperi.", Concepto ".$ls_codconc."\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hprenomina Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hprenomina Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hprenomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hsalida($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hsalida
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hsalida y los inserta en sno_hsalida
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, codconc, tipsal, valsal, monacusal, salsal ".
				"  FROM sno_hsalida ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la salida Histórica.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				//$ls_codperi=$this->io_validacion->uf_valida_texto($row["codperi"],0,3,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");
				$ls_tipsal=$this->io_validacion->uf_valida_monto($row["tipsal"],0,2,"");
				$li_valsal=$this->io_validacion->uf_valida_monto($row["valsal"],0);
				$li_monacusal=$this->io_validacion->uf_valida_monto($row["monacusal"],0);
				$li_salsal=$this->io_validacion->uf_valida_monto($row["salsal"],0);
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_valsal=$this->io_rcbsf->uf_convertir_monedabsf($li_valsal,2,1,1000,1);
				$li_monacusal=$this->io_rcbsf->uf_convertir_monedabsf($li_monacusal,2,1,1000,1);
				$li_salsal=$this->io_rcbsf->uf_convertir_monedabsf($li_salsal,2,1,1000,1);
				$li_valsalaux=$this->io_validacion->uf_valida_monto($row["salsal"],0);
				$li_monacusalaux=$this->io_validacion->uf_valida_monto($row["monacusal"],0);
				$li_salsalaux=$this->io_validacion->uf_valida_monto($row["monamo"],0);
				if(($ls_codnom!="")&&($ls_codperi!="")&&($ls_codper!="")&&($ls_codconc!="")&&($ls_tipsal!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hsalida(codemp,codnom,codperi,codper,codconc,tipsal,valsal,monacusal,salsal,anocur, valsalaux, ".
							"			 monacusalaux, salsalaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codperi."','".$ls_codper."','".$ls_codconc."',".
							"			  '".$ls_tipsal."',".$li_valsal.",".$li_monacusal.",".$li_salsal.",'".$ls_anocur."',".$li_valsalaux.",".
							"			  ".$li_monacusalaux.",".$li_salsalaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la salida Histórica.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Salidas Históricas.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Código Concepto ".$ls_codconc.",Tipo Salida ".$ls_tipsal.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hsalida Registros  ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hsalida Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hsalida
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hresumen($as_codnom,$as_codnomnuevo,$ai_anocurnom,$as_anocur,$as_codperiadi,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hresumen
		//		   Access: private
		//		 Argument: as_codnom // Código de Nómina
		//				   as_anocur // Año en curso
		//				   as_codperi //  Código de Período
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_hresumen y los inserta en sno_hresumen
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, asires, dedres, apoempres, apopatres, priquires, segquires, monnetres, ".
				"		notres ".				
				"  FROM sno_hresumen ".
				" WHERE codnom ='".$as_codnom."'".
				"   AND anocur ='".$ai_anocurnom."'".
				"   AND codperi ='".$as_codperiadi."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el resumen Histórico .\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				//$ls_codperi=$this->io_validacion->uf_valida_texto($row["codperi"],0,3,"");
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_asires=$this->io_validacion->uf_valida_monto($row["asires"],0);
				$li_dedres=$this->io_validacion->uf_valida_monto($row["dedres"],0);
				$li_apoempres=$this->io_validacion->uf_valida_monto($row["apoempres"],0);
				$li_apopatres=$this->io_validacion->uf_valida_monto($row["apopatres"],0);
				$li_priquires=$this->io_validacion->uf_valida_monto($row["priquires"],0);
				$li_segquires=$this->io_validacion->uf_valida_monto($row["segquires"],0);
				$li_monnetres=$this->io_validacion->uf_valida_monto($row["monnetres"],0);
				$ls_notres=$this->io_validacion->uf_valida_texto($row["notres"],0,1000,"");
				$ls_anocur=$as_anocur;
				$ls_codperi=$as_codperi;
				$li_asires=$this->io_rcbsf->uf_convertir_monedabsf($li_asires,2,1,1000,1);
				$li_dedres=$this->io_rcbsf->uf_convertir_monedabsf($li_dedres,2,1,1000,1);
				$li_apoempres=$this->io_rcbsf->uf_convertir_monedabsf($li_apoempres,2,1,1000,1);
				$li_apopatres=$this->io_rcbsf->uf_convertir_monedabsf($li_apopatres,2,1,1000,1);
				$li_priquires=$this->io_rcbsf->uf_convertir_monedabsf($li_priquires,2,1,1000,1);
				$li_segquires=$this->io_rcbsf->uf_convertir_monedabsf($li_segquires,2,1,1000,1);
				$li_monnetres=$this->io_rcbsf->uf_convertir_monedabsf($li_monnetres,2,1,1000,1);
				$li_asiresaux=$this->io_validacion->uf_valida_monto($row["asires"],0);
				$li_dedresaux=$this->io_validacion->uf_valida_monto($row["dedres"],0);
				$li_apoempresaux=$this->io_validacion->uf_valida_monto($row["apoempres"],0);
				$li_apopatresaux=$this->io_validacion->uf_valida_monto($row["apopatres"],0);
				$li_priquiresaux=$this->io_validacion->uf_valida_monto($row["priquires"],0);
				$li_segquiresaux=$this->io_validacion->uf_valida_monto($row["segquires"],0);
				$li_monnetresaux=$this->io_validacion->uf_valida_monto($row["monnetres"],0);
				if(($ls_codnom!="")&&($ls_codperi!="")&&($ls_codper!="")&&($ls_anocur!=""))
				{
					$ls_sql="INSERT INTO sno_hresumen(codemp,codnom,codperi,codper,asires,dedres,apoempres,apopatres,priquires,".
							"			 segquires,monnetres,notres,anocur, asiresaux, dedresaux, apoempresaux, apopatresaux, ".
							"			 priquiresaux, segquiresaux, monnetresaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codperi."','".$ls_codper."',".$li_asires.",".
							"			  ".$li_dedres.",".$li_apoempres.",".$li_apopatres.",".$li_priquires.",".$li_segquires.",".
							"  			  ".$li_monnetres.",'".$ls_notres."','".$ls_anocur."',".$li_asiresaux.",".
							"			  ".$li_dedresaux.",".$li_apoempresaux.",".$li_apopatresaux.",".$li_priquiresaux.",".$li_segquiresaux.",".
							"  			  ".$li_monnetresaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el resumen Histórico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Resumen Históricos.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Año Curso ".$ls_anocur.",Perído Actual ".$ls_codperi." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Nómina ".$as_codnom." Año ".$as_anocur.",Período ".$as_codperi." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_hresumen Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_hresumen Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_hresumen
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>