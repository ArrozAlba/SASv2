<?php 
///////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_nomina_nomina.php                                                 			  //    
// Description : Procesa la copia de datos del modulo de nomina										  //
////////////////////////////////////////////////////////////////////////////////////////////////////////

class sigesp_copia_sno {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $io_sql;
	var $li_candeccon;
	var $li_tipconmon;
	var $li_redconmon;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_copia_sno ()
	{
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("class_folder/class_validacion.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("sigesp_copia_sno_historico.php");
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
		$this->sno_historico 	  = new sigesp_copia_sno_historico();
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo         = "resultado/".$_SESSION["ls_data_des"]."_sno_result_".$ld_fecha.".txt";
		$this->lo_archivo         = @fopen("$ls_nombrearchivo","a+");
		$this->ls_codemp          = $_SESSION["la_empresa"]["codemp"];
		$this->io_rcbsf			  = new sigesp_c_reconvertir_monedabsf(); 
		$this->li_candeccon= 4;
		$this->	li_tipconmon= 1;
		$this->li_redconmon=1000;
	}// end function 
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_nomina(&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_nomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que busca las nóminas en la base de datos origen
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total_select=0;
		$li_total_insert=0;
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, desnom ".
				"  FROM sno_nomina ".
				" ORDER BY codnom ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Nóminas.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$ai_totrows=0;
			while(($row=$this->io_sql_origen->fetch_row($io_recordset))&&$lb_valido)
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codemp=$row["codemp"]; 
				$ls_codnom=$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_desnom=$this->io_validacion->uf_valida_texto($row["desnom"],0,100,"");
				$ao_object[$ai_totrows][1]="<input name=txtcodemp".$ai_totrows."    type=text     id=txtcodemp".$ai_totrows."    class=sin-borde size=6  maxlength=4   value='".$ls_codemp."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodnom".$ai_totrows."    type=text     id=txtcodnom".$ai_totrows."    class=sin-borde size=6  maxlength=4   value='".$ls_codnom."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdesnom".$ai_totrows."    type=text     id=txtdesnom".$ai_totrows."    class=sin-borde size=80 maxlength=100 value='".$ls_desnom."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcodnomnue".$ai_totrows." type=text     id=txtcodnomnue".$ai_totrows." class=sin-borde size=6  maxlength=4   value='".$ls_codnom."' onKeyUp=ue_validarnumero(this); onBlur=ue_rellenarcampo(this,4);>";
				$ao_object[$ai_totrows][5]="<input name=chkpasardata".$ai_totrows." type=checkbox id=chkpasardata".$ai_totrows." value=1>";
			}
		}		
		return $lb_valido;
	}// end function uf_select_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function ue_copiar_nomina_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		//------------------------------------ Insertar datos básicos fuera de la nómina -----------------------------------------
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_profesion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_unidadadministrativa();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_proyecto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_constanciatrabajo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_cestaticket();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_cestaticketunidad();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_tablavacacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_tablavacacionperiodo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_dedicacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_tipopersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_escaladocente();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_clasificaciondocente();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_ubicacionfisica();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_componente();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_rango();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_personal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_personalisr();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_personalestudios();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_personaltrabajos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_familiar();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_permisos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_beneficiario();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_fideicomiso();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_vacaciones();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_ipasme_dependencias();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_ipasme_afiliado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_ipasme_beneficiario();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_archivotxt();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_archivotxtcampo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_metodobanco();
		}
		$li_total=$_POST["totrownomina"];
		for($li_i=1;($li_i<=$li_total)&&($lb_valido);$li_i++)
		{
			$li_pasar=0;			
			if(array_key_exists("chkpasardata".$li_i,$_POST))
			{
				$li_pasar=$_POST["chkpasardata".$li_i];
				if($li_pasar==1)
				{
					$ls_codemp=$_POST["txtcodemp".$li_i];
					$ls_codnom=$_POST["txtcodnom".$li_i];
					$ls_codnomnuevo=$_POST["txtcodnomnue".$li_i];
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_nomina($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}				
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_periodos($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_subnomina($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_cargo($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_tabulador($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_grado($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_primagrado($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_asignacioncargo($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_personalnomina($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_proyectopersonal($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_constante($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_constantepersonal($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_concepto($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_conceptopersonal($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_conceptovacacion($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_primaconcepto($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_tipoprestamo($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_prestamo($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_prestamoperiodo($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_prestamoamortizado($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{	
						$lb_valido=$this->uf_insert_fideicomisoperiodo($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_historicos_adicionales($ls_codemp,$ls_codnom,$ls_codnomnuevo);
					}
				}
			}
		}
		////////////////////////////////////////////////////////////
		if($lb_valido)
		{	
			$this->io_mensajes->message("La data de nómina se copió correctamente.");
			$ls_cadena="La data de nómina se copió correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al copiar la data de nómina. Verifique el archivo txt."); 
		}
		if ($lb_valido)
		{
			$this->io_validacion->uf_insert_sistema_apertura('SNO');
			$this->io_sql_destino->commit();
		}
		else
		{
			$this->io_sql_destino->rollback();	
		}
		return $lb_valido;
	}// end function uf_select_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_profesion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_profesion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_profesion y los inserta en sno_profesión
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codpro, despro FROM sno_profesion ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la profesión.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codpro = $this->io_validacion->uf_valida_texto($row["codpro"],0,3,"");
				$ls_despro = $this->io_validacion->uf_valida_texto($row["despro"],0,120,"");
				if($ls_codpro!="")
				{
					$ls_sql="INSERT INTO sno_profesion(codemp,codpro,despro) VALUES ('".$ls_codemp."','".$ls_codpro."','".$ls_despro."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la profesión.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Profesiones.\r\n";
					$ls_cadena=$ls_cadena."Código Profesión ".$ls_codpro." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_profesion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_profesion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_profesion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_unidadadministrativa()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_unidadadministrativa
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_ubiad y los inserta en sno_unidadadmin
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, desuniadm, codprouniadm, codproviauniadm ".
				"  FROM sno_unidadadmin ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Unidad Administrativa.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp			= $row["codemp"]; 
				$ls_minorguniadm	= $this->io_validacion->uf_valida_texto($row["minorguniadm"],0,4,"0000");
				$ls_ofiuniadm		= $this->io_validacion->uf_valida_texto($row["ofiuniadm"],0,2,"00");
				$ls_uniuniadm		= $this->io_validacion->uf_valida_texto($row["uniuniadm"],0,2,"00");
				$ls_depuniadm		= $this->io_validacion->uf_valida_texto($row["depuniadm"],0,2,"00");
				$ls_prouniadm		= $this->io_validacion->uf_valida_texto($row["prouniadm"],0,2,"00");
				$ls_desuniadm		= $this->io_validacion->uf_valida_texto($row["desuniadm"],0,100,"");
				$ls_codprouniadm    = $this->io_validacion->uf_valida_texto($row["codprouniadm"],0,33,"");
				$ls_codproviauniadm = $this->io_validacion->uf_valida_texto($row["codproviauniadm"],0,33,"");
				if(strlen($ls_codprouniadm)==10)
				{
					$ls_programatica = str_pad(substr($ls_codprouniadm,0,2),20,"0",0);
					$ls_programatica = $ls_programatica.str_pad(substr($ls_codprouniadm,2,2),6,"0",0);	
					$ls_programatica = $ls_programatica.str_pad(substr($ls_codprouniadm,4,2),3,"0",0);	
					$ls_programatica = $ls_programatica.str_pad(substr($ls_codprouniadm,6,2),2,"0",0);	
					$ls_programatica = $ls_programatica.str_pad(substr($ls_codprouniadm,8,2),2,"0",0);	
					$ls_codprouniadm = $ls_programatica;
				}
				if(strlen($ls_codproviauniadm)==10)
				{
					$ls_programatica	= str_pad(substr($ls_codproviauniadm,0,2),20,"0",0);
					$ls_programatica	= $ls_programatica.str_pad(substr($ls_codproviauniadm,2,2),6,"0",0);	
					$ls_programatica	= $ls_programatica.str_pad(substr($ls_codproviauniadm,4,2),3,"0",0);	
					$ls_programatica	= $ls_programatica.str_pad(substr($ls_codproviauniadm,6,2),2,"0",0);	
					$ls_programatica	= $ls_programatica.str_pad(substr($ls_codproviauniadm,8,2),2,"0",0);	
					$ls_codproviauniadm = $ls_programatica;
				}
				if(($ls_minorguniadm!="")&&($ls_ofiuniadm!="")&&($ls_uniuniadm!="")&&($ls_depuniadm!="")&&($ls_prouniadm!=""))
				{
					$ls_sql="INSERT INTO sno_unidadadmin (codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,desuniadm,".
							"							  codprouniadm,codproviauniadm)           ".
							"VALUES('".$ls_codemp."','".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."',".
							"'".$ls_prouniadm."','".$ls_desuniadm."','".$ls_codprouniadm."','".$ls_codproviauniadm."')";
					$rs_data = $this->io_sql_destino->execute($ls_sql);
					if($rs_data===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Unidad Administrativa.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Unidades Administrativas.\r\n";
					$ls_cadena=$ls_cadena."Código Unidad Administrativa ".$ls_minorguniadm."-".$ls_ofiuniadm."-".$ls_uniuniadm."-".$ls_depuniadm."-".$ls_prouniadm." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_unidadadmin Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_unidadadmin Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		return $lb_valido;
	}// end function uf_insert_unidadadministrativa
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_proyecto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_proyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codproy, nomproy, estproproy ".
				"  FROM sno_proyecto ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el Proyecto.\r\n".$this->io_sql_origen->message."\r\n";
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
				if($ls_codproy!="")
				{
					$ls_sql="INSERT INTO sno_proyecto (codemp, codproy, nomproy, estproproy) ".
							"VALUES('".$ls_codemp."','".$ls_codproy."','".$ls_nomproy."','".$ls_estproproy."')";
					$rs_data = $this->io_sql_destino->execute($ls_sql);
					if($rs_data===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el Proyecto.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Proyecto.\r\n";
					$ls_cadena=$ls_cadena."Código Proyecto ".$ls_codproy." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_proyecto Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_proyecto Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		return $lb_valido;
	}// end function uf_insert_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_diaferiado()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_diaferiado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_feriados y los inserta en sno_diaferiado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, fecfer, nomfer FROM sno_diaferiado";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los días feriados.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ld_fecfer = $this->io_validacion->uf_valida_fecha($row["fecfer"],"");
				$ls_nomfer = $this->io_validacion->uf_valida_texto($row["nomfer"],0,120,"");
				if($ld_fecfer!="")
				{
					$ls_sql="INSERT INTO sno_diaferiado(codemp,fecfer,nomfer) VALUES('".$ls_codemp."','".$ld_fecfer."','".$ls_nomfer."')";
					$rs_data=$this->io_sql_destino->execute($ls_sql);
					if($rs_data===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los días feriados.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Días Feriados.\r\n";
					$ls_cadena=$ls_cadena."Fecha Feriado ".$ld_fecfer." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_diaferiado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_diaferiado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_diaferiado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constanciatrabajo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constanciatrabajo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_constanciatrabajo y los inserta en sno_constanciatrabajo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codcont, descont, concont, tamletcont, intlincont, marinfcont, marsupcont, titcont, piepagcont, ".
						   "       tamletpiecont, arcrtfcont ".
						   "  FROM sno_constanciatrabajo ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las constancias de trabajo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp	   = $row["codemp"]; 
				$ls_codcont	   = $this->io_validacion->uf_valida_texto($row["codcont"],0,3,"");
				$ls_descont	   = $this->io_validacion->uf_valida_texto($row["descont"],0,254,"");
				$ls_concont	   = $this->io_validacion->uf_valida_texto($row["concont"],0,8000,"");
				$li_tamletcont = $this->io_validacion->uf_valida_monto($row["tamletcont"],0);
				$li_intlincont = $this->io_validacion->uf_valida_monto($row["intlincont"],0);
				$li_marinfcont = $this->io_validacion->uf_valida_monto($row["marinfcont"],0);
				$li_marsupcont = $this->io_validacion->uf_valida_monto($row["marsupcont"],0);
				$ls_titcont	   = $this->io_validacion->uf_valida_texto($row["titcont"],0,8000,"");
				$ls_piepagcont	   = $this->io_validacion->uf_valida_texto($row["piepagcont"],0,8000,"");
				$li_tamletpiecont = $this->io_validacion->uf_valida_monto($row["tamletpiecont"],0);
				$ls_arcrtfcont	   = $this->io_validacion->uf_valida_texto($row["arcrtfcont"],0,50,"");
				if($ls_codcont!="")
				{
					$ls_sql="INSERT INTO sno_constanciatrabajo(codemp, codcont, descont, concont, tamletcont, intlincont, marinfcont, marsupcont, ".
							"								   titcont, piepagcont, tamletpiecont, arcrtfcont) ".
							"VALUES('".$ls_codemp."','".$ls_codcont."','".$ls_descont."','".$ls_concont."',".$li_tamletcont.",".$li_intlincont.",".
							"".$li_marinfcont.",".$li_marsupcont.",'".$ls_titcont."','".$ls_piepagcont."',".$li_tamletpiecont.",'".$ls_arcrtfcont."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las constancias de trabajo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las constancias de trabajo.\r\n";
					$ls_cadena=$ls_cadena."Código de Constancia ".$ls_codcont." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_constanciatrabajo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_constanciatrabajo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_constanciatrabajo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cestaticket()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cestaticket
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_cestic y los inserta en sno_cestaticket
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codcestic, dencestic, moncestic, metcestic, codcli, codprod, punent ".
				"  FROM sno_cestaticket ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Cesta Ticket.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codcestic=$this->io_validacion->uf_valida_texto($row["codcestic"],0,2,"");
				$ls_dencestic=$this->io_validacion->uf_valida_texto($row["dencestic"],0,100,"");
				$li_moncestic=$this->io_validacion->uf_valida_monto($row["moncestic"],0);
				$li_metcestic=$this->io_validacion->uf_valida_monto($row["metcestic"],0);
				$ls_codcli=$this->io_validacion->uf_valida_texto($row["codcli"],0,15,"");
				$ls_codprod=$this->io_validacion->uf_valida_texto($row["codprod"],0,15,"");
				$ls_punent=$this->io_validacion->uf_valida_texto($row["punent"],0,15,"");
				$li_moncestic=$this->io_rcbsf->uf_convertir_monedabsf($li_moncestic,2,1,1000,1);
				$li_moncesticaux=$this->io_validacion->uf_valida_monto($row["moncestic"],0);
				if($ls_codcestic!="")
				{
					$ls_sql="INSERT INTO sno_cestaticket(codemp,codcestic,dencestic,moncestic,metcestic, codcli, codprod, punent, moncesticaux) ".
							"VALUES('".$ls_codemp."','".$ls_codcestic."','".$ls_dencestic."',".$li_moncestic.",".$li_metcestic.",'".$ls_codcli."',".
							"'".$ls_codprod."','".$ls_punent."',".$li_moncesticaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Cesta Ticket.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Cesta Ticket.\r\n";
					$ls_cadena=$ls_cadena."Código Cesta Ticket ".$ls_codcestic." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_cestaticket Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_cestaticket Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_cestaticket
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cestaticketunidad()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cestaticketunidad
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_feriados y los inserta en sno_diaferiado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, codcestic, est1cestic, est2cestic ".
				"  FROM sno_cestaticunidadadm  ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Cesta Ticket Unidad.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp       = $row["codemp"]; 
				$ls_minorguniadm = $this->io_validacion->uf_valida_texto($row["minorguniadm"],0,4,"");
				$ls_ofiuniadm	 = $this->io_validacion->uf_valida_texto($row["ofiuniadm"],0,2,"");
				$ls_uniuniadm	 = $this->io_validacion->uf_valida_texto($row["uniuniadm"],0,2,"");
				$ls_depuniadm	 = $this->io_validacion->uf_valida_texto($row["depuniadm"],0,2,"");
				$ls_prouniadm	 = $this->io_validacion->uf_valida_texto($row["prouniadm"],0,2,"");
				$ls_codcestic	 = $this->io_validacion->uf_valida_texto($row["codcestic"],0,2,"");
				$ls_est1cestic   = $this->io_validacion->uf_valida_texto($row["est1cestic"],27,5,"");
				$ls_est2cestic   = $this->io_validacion->uf_valida_texto($row["est2cestic"],12,20,"");
				if(($ls_codcestic!="")&&($ls_minorguniadm!="")&&($ls_ofiuniadm!="")&&($ls_uniuniadm!="")&&($ls_depuniadm!="")&&($ls_prouniadm!=""))
				{
					$ls_sql="INSERT INTO sno_cestaticunidadadm(codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,codcestic,".
							"est1cestic,est2cestic)VALUES('".$ls_codemp."','".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."',".
							"'".$ls_depuniadm."','".$ls_prouniadm."','".$ls_codcestic."','".$ls_est1cestic."','".$ls_est2cestic."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Cesta Ticket Unidad.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Cesta Ticket Unidad Administrativa.\r\n";
					$ls_cadena=$ls_cadena."Código Cesta Ticket ".$ls_codcestic." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_cestaticunidadadm Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_cestaticunidadadm Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_cestaticketunidad
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tablavacacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tablavacacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_tb_vac y los inserta en sno_tablavacacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codtabvac, dentabvac, pertabvac, adequitabvac, aderettabvac, bonauttabvac, anoserpre ".
				"  FROM sno_tablavacacion ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Vacación.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp		 = $row["codemp"]; 
				$ls_codtabvac	 = $this->io_validacion->uf_valida_texto($row["codtabvac"],0,2,"");
				$ls_dentabvac	 = $this->io_validacion->uf_valida_texto($row["dentabvac"],0,120,"");
				$ls_pertabvac	 = $this->io_validacion->uf_valida_texto($row["pertabvac"],0,1,"1");
				$li_adequitabvac = $this->io_validacion->uf_valida_monto($row["adequitabvac"],0);
				$li_aderettabvac = $this->io_validacion->uf_valida_monto($row["aderettabvac"],0);
				$li_bonauttabvac = $this->io_validacion->uf_valida_monto($row["bonauttabvac"],0);
				$li_anoserpre = $this->io_validacion->uf_valida_monto($row["anoserpre"],0);
				if($ls_codtabvac!="")
				{
					$ls_sql="INSERT INTO sno_tablavacacion(codemp, codtabvac, dentabvac, pertabvac, adequitabvac, aderettabvac, bonauttabvac, anoserpre)".
							"VALUES('".$ls_codemp."','".$ls_codtabvac."','".$ls_dentabvac."','".$ls_pertabvac."',".$li_adequitabvac.",".
							"".$li_aderettabvac.",".$li_bonauttabvac.",".$li_anoserpre.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Vacación.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Tabla de Vacaciones.\r\n";
					$ls_cadena=$ls_cadena."Código Tabla Vacación ".$ls_codtabvac." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_tablavacacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_tablavacacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		return $lb_valido;
	}// end function uf_insert_tablavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tablavacacionperiodo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tablavacacionperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_tb_vac_det y los inserta en sno_tablavacperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codtabvac, lappervac, diadisvac, diabonvac, diaadidisvac, diaadibonvac ".
				"  FROM sno_tablavacperiodo ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Vacación Período.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp		 = $row["codemp"]; 
				$ls_codtabvac	 = $this->io_validacion->uf_valida_texto($row["codtabvac"],0,2,"");
				$li_lappervac	 = $this->io_validacion->uf_valida_monto($row["lappervac"],0);
				$li_diadisvac	 = $this->io_validacion->uf_valida_monto($row["diadisvac"],0);
				$li_diabonvac	 = $this->io_validacion->uf_valida_monto($row["diabonvac"],0);
				$li_diaadidisvac = $this->io_validacion->uf_valida_monto($row["diaadidisvac"],0);
				$li_diaadibonvac = $this->io_validacion->uf_valida_monto($row["diaadibonvac"],0);
				if($ls_codtabvac!="")
				{
					$ls_sql="INSERT INTO sno_tablavacperiodo(codemp,codtabvac,lappervac,diadisvac,diabonvac,diaadidisvac,diaadibonvac)".
							"VALUES('".$ls_codemp."','".$ls_codtabvac."',".$li_lappervac.",".$li_diadisvac.",".$li_diabonvac.",".
							"".$li_diaadidisvac.",".$li_diaadibonvac.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Vacación Período.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Tabla de Vacaciones Período.\r\n";
					$ls_cadena=$ls_cadena."Código Tabla Vacación ".$ls_codtabvac.", Código Periodo ".$li_lappervac." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_tablavacperiodo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_tablavacperiodo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_tablavacacionperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dedicacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dedicacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_dedicacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codded, desded ".
				"  FROM sno_dedicacion ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Dedicación.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codded=$this->io_validacion->uf_valida_texto($row["codded"],0,3,"");
				$ls_desded=$this->io_validacion->uf_valida_texto($row["desded"],0,100,"");
				if($ls_codded!="")
				{
					$ls_sql="INSERT INTO sno_dedicacion(codemp,codded,desded) VALUES('".$ls_codemp."','".$ls_codded."','".$ls_desded."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Dedicación.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Dedicación.\r\n";
					$ls_cadena=$ls_cadena."Código Dedicacion ".$ls_codded." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_dedicacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_dedicacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_dedicacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tipopersonal()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipopersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_tipopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codded, codtipper, destipper ".
				"  FROM sno_tipopersonal ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Tipo de Personal.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp    = $row["codemp"]; 
				$ls_codded    = $this->io_validacion->uf_valida_texto($row["codded"],0,3,"");
				$ls_codtipper = $this->io_validacion->uf_valida_texto($row["codtipper"],0,4,"");
				$ls_destipper = $this->io_validacion->uf_valida_texto($row["destipper"],0,100,"");
				if(($ls_codded!="")&&($ls_codtipper!=""))
				{
					$ls_sql="INSERT INTO sno_tipopersonal(codemp,codded,codtipper,destipper)".
							"VALUES('".$ls_codemp."','".$ls_codded."','".$ls_codtipper."','".$ls_destipper."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Tipo de Personal.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en Tipo de Personal.\r\n";
					$ls_cadena=$ls_cadena."Código Tabla Tipo de Personal Dedicación ".$ls_codded.", Tipo de Personal ".$ls_codtipper." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_tipopersonal Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_tipopersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_tipopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_escaladocente()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_escaladocente
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_escaladocente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codescdoc, desescdoc, tipescdoc ".
				"  FROM  sno_escaladocente ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Escala Docente.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp    = $row["codemp"]; 
				$ls_codescdoc = $this->io_validacion->uf_valida_texto($row["codescdoc"],0,4,"");
				$ls_desescdoc = $this->io_validacion->uf_valida_texto($row["desescdoc"],0,100,"");
				$ls_tipescdoc = $this->io_validacion->uf_valida_texto($row["tipescdoc"],0,1,"");
				if($ls_codescdoc!="")
				{
					$ls_sql="INSERT INTO sno_escaladocente(codemp,codescdoc,desescdoc,tipescdoc)VALUES".
							"('".$ls_codemp."','".$ls_codescdoc."','".$ls_desescdoc."','".$ls_tipescdoc."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Escala Docente.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en Escala Docente.\r\n";
					$ls_cadena=$ls_cadena."Código Escala Docente  ".$ls_codescdoc." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_escaladocente Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_escaladocente Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
	
		return $lb_valido;
	}// end function uf_insert_escaladocente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_clasificaciondocente()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_clasificaciondocente
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_clasificaciondocente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codescdoc, codcladoc, descladoc, tiesercladoc, suesupcladoc, suedircladoc, suedoccladoc ".
				"  FROM sno_clasificaciondocente ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Clasificación Docente.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp       = $row["codemp"]; 
				$ls_codescdoc    = $this->io_validacion->uf_valida_texto($row["codescdoc"],0,4,"");
				$ls_codcladoc    = $this->io_validacion->uf_valida_texto($row["codcladoc"],0,4,"");
				$ls_descladoc    = $this->io_validacion->uf_valida_texto($row["descladoc"],0,100,"");
				$ls_tiesercladoc = $this->io_validacion->uf_valida_texto($row["tiesercladoc"],0,100,"");
				$li_suesupcladoc = $this->io_validacion->uf_valida_monto($row["suesupcladoc"],0);
				$li_suedircladoc = $this->io_validacion->uf_valida_monto($row["suedircladoc"],0);
				$li_suedoccladoc = $this->io_validacion->uf_valida_monto($row["suedoccladoc"],0);
				$li_suesupcladoc=$this->io_rcbsf->uf_convertir_monedabsf($li_suesupcladoc,2,1,1000,1);
				$li_suedircladoc=$this->io_rcbsf->uf_convertir_monedabsf($li_suedircladoc,2,1,1000,1);
				$li_suedoccladoc=$this->io_rcbsf->uf_convertir_monedabsf($li_suedoccladoc,2,1,1000,1);
				$li_suesupcladocaux = $this->io_validacion->uf_valida_monto($row["suesupcladoc"],0);
				$li_suedircladocaux = $this->io_validacion->uf_valida_monto($row["suedircladoc"],0);
				$li_suedoccladocaux = $this->io_validacion->uf_valida_monto($row["suedoccladoc"],0);
				if(($ls_codescdoc!="")&&($ls_codcladoc!=""))
				{
					$ls_sql="INSERT INTO sno_clasificaciondocente(codemp, codescdoc, codcladoc, descladoc, tiesercladoc, suesupcladoc, ".
							"suedircladoc, suedoccladoc, suesupcladocaux, suedircladocaux, suedoccladocaux) VALUES ".
							"('".$ls_codemp."','".$ls_codescdoc."','".$ls_codcladoc."','".$ls_descladoc."','".$ls_tiesercladoc."',".
							"".$li_suesupcladoc.",".$li_suedircladoc.",".$li_suedoccladoc.",".$li_suesupcladocaux.",".$li_suedircladocaux.",".
							"".$li_suedoccladocaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Clasificación Docente.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las tablas de Clasificación Docente.\r\n";
					$ls_cadena=$ls_cadena."Código Escala Docente  ".$ls_codescdoc." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_clasificaciondocente Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_clasificaciondocente Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_clasificaciondocente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_ubicacionfisica()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ubicacionfisica
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_ubicacionfisica
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codubifis, desubifis, codpai, codest, codmun, codpar, dirubifis ".
				"  FROM sno_ubicacionfisica ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Ubicación Física.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp    = $row["codemp"]; 
				$ls_codubifis = $this->io_validacion->uf_valida_texto($row["codubifis"],0,4,"");
				$ls_desubifis = $this->io_validacion->uf_valida_texto($row["desubifis"],0,100,"");
				$ls_codpai = $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				$ls_codest = $this->io_validacion->uf_valida_texto($row["codest"],0,3,"");
				$ls_codmun = $this->io_validacion->uf_valida_texto($row["codmun"],0,3,"");
				$ls_codpar = $this->io_validacion->uf_valida_texto($row["codpar"],0,3,"");
				$ls_dirubifis = $this->io_validacion->uf_valida_texto($row["dirubifis"],0,200,"");

				if($ls_codubifis!="")
				{
					$ls_sql="INSERT INTO sno_ubicacionfisica(codemp, codubifis, desubifis, codpai, codest, codmun, codpar, dirubifis) ".
							"VALUES ('".$ls_codemp."','".$ls_codubifis."','".$ls_desubifis."','".$ls_codpai."','".$ls_codest."','".$ls_codmun."',".
							"'".$ls_codpar."','".$ls_dirubifis."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Ubicación Física.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Ubicación Física.\r\n";
					$ls_cadena=$ls_cadena."Código Ubicación Física  ".$ls_codubifis." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_ubicacionfisica Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_ubicacionfisica Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_ubicacionfisica
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_componente()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_componente
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_componente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codcom, descom ".
				"  FROM sno_componente ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Componente.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp    = $row["codemp"]; 
				$ls_codcom = $this->io_validacion->uf_valida_texto($row["codcom"],0,10,"");
				$ls_descom = $this->io_validacion->uf_valida_texto($row["descom"],0,100,"");
				if($ls_codcom!="")
				{
					$ls_sql="INSERT INTO sno_componente(codemp, codcom, descom)VALUES('".$ls_codemp."','".$ls_codcom."','".$ls_descom."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Componente.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el componente.\r\n";
					$ls_cadena=$ls_cadena."Código componente  ".$ls_codcom." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_componente Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_componente Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_componente
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_rango()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_rango
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_rango
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codcom, codran, desran ".
				"  FROM sno_rango ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Tablas de Componente.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp    = $row["codemp"]; 
				$ls_codcom = $this->io_validacion->uf_valida_texto($row["codcom"],0,10,"");
				$ls_codran = $this->io_validacion->uf_valida_texto($row["codran"],0,100,"");
				$ls_desran = $this->io_validacion->uf_valida_texto($row["desran"],0,100,"");
				if($ls_codran!="")
				{
					$ls_sql="INSERT INTO sno_rango(codemp, codcom, codran, desran)VALUES('".$ls_codemp."','".$ls_codcom."','".$ls_codran."','".$ls_desran."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Tablas de Rango.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el rango.\r\n";
					$ls_cadena=$ls_cadena."Código rango  ".$ls_codran." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_rango Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_rango Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_rango
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personal()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_personal y los inserta en sno_personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, cedper, nomper, apeper, dirper, fecnacper, edocivper, telhabper, telmovper, sexper, ".
				"		estaper, pesper, codpro, nivacaper, catper, cajahoper, numhijper, contraper, tipvivper, tenvivper, ".
				"		monpagvivper, ingbrumen, cuecajahoper, cuelphper, cuefidper, fecingadmpubper, vacper, porisrper, ".
				"		fecingper, anoservpreper, cedbenper, fecegrper, estper, fotper, codpai, codest, codmun, codpar, ".
				"		obsper, cauegrper, obsegrper, nacper, coreleper, cenmedper, turper, horper, hcmper, tipsanper, ".
				"		codcom, codran, numexpper, codpainac, codestnac ".
				"  FROM sno_personal ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el Personal.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_cedper=$this->io_validacion->uf_valida_texto($row["cedper"],0,10,"");
				$ls_nomper=$this->io_validacion->uf_valida_texto($row["nomper"],0,60,"");
				$ls_apeper=$this->io_validacion->uf_valida_texto($row["apeper"],0,60,""); 
				$ls_dirper=$this->io_validacion->uf_valida_texto($row["dirper"],0,254,""); 
				$ld_fecnacper=$this->io_validacion->uf_valida_fecha($row["fecnacper"],"1950-01-01");
				$ls_edocivper=$this->io_validacion->uf_valida_texto($row["edocivper"],0,1,"S");
				$ls_telhabper=$this->io_validacion->uf_valida_texto($row["telhabper"],0,15,""); 
				$ls_telmovper=$this->io_validacion->uf_valida_texto($row["telmovper"],0,15,"");
				$ls_sexper=$this->io_validacion->uf_valida_texto($row["sexper"],0,1,"");
				$li_estaper=$this->io_validacion->uf_valida_monto($row["estaper"],0);
				$li_pesper=$this->io_validacion->uf_valida_monto($row["pesper"],0); 
				$ls_codpro=$this->io_validacion->uf_valida_texto($row["codpro"],0,3,"000");
				$ls_nivacaper=$this->io_validacion->uf_valida_texto($row["nivacaper"],0,1,"0");
				$ls_catper=$this->io_validacion->uf_valida_texto($row["catper"],0,20,"");
				$ls_cajahoper=$this->io_validacion->uf_valida_texto($row["cajahoper"],0,1,"0"); 
				$li_numhijper=$this->io_validacion->uf_valida_monto($row["numhijper"],0);
				$ls_contraper=$this->io_validacion->uf_valida_texto($row["contraper"],0,1,"");
				$li_tipvivper=$this->io_validacion->uf_valida_monto($row["tipvivper"],2);				
				$ls_tenvivper=$this->io_validacion->uf_valida_texto($row["tenvivper"],0,40,""); 
				$li_monpagvivper=$this->io_validacion->uf_valida_monto($row["monpagvivper"],0);
				$li_ingbrumen=$this->io_validacion->uf_valida_monto($row["ingbrumen"],0);
				$ls_cuecajahoper=$this->io_validacion->uf_valida_texto($row["cuecajahoper"],0,25,"");
				$ls_cuelphper=$this->io_validacion->uf_valida_texto($row["cuelphper"],0,25,""); 
				$ls_cuefidper=$this->io_validacion->uf_valida_texto($row["cuefidper"],0,25,"");
				$ld_fecingadmpubper=$this->io_validacion->uf_valida_fecha($row["fecingadmpubper"],"");
				$ls_vacper=$this->io_validacion->uf_valida_texto($row["vacper"],0,1,"");
				$li_porisrper=$this->io_validacion->uf_valida_monto($row["porisrper"],0); 
				$ld_fecingper=$this->io_validacion->uf_valida_fecha($row["fecingper"],"");
				$li_anoservpreper=$this->io_validacion->uf_valida_monto($row["anoservpreper"],0); 
				$ls_cedbenper=$this->io_validacion->uf_valida_texto($row["cedbenper"],0,8,"");
				$ld_fecegrper=$this->io_validacion->uf_valida_fecha($row["fecegrper"],"1900-01-01");
				$ls_estper=$this->io_validacion->uf_valida_texto($row["estper"],0,1,"1");
				$ls_fotper=$this->io_validacion->uf_valida_texto($row["fotper"],0,200,"blanco.jpg");
				$ls_codpai = $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				$ls_codest = $this->io_validacion->uf_valida_texto($row["codest"],0,3,"");
				$ls_codmun = $this->io_validacion->uf_valida_texto($row["codmun"],0,3,"");
				$ls_codpar = $this->io_validacion->uf_valida_texto($row["codpar"],0,3,"");
				$ls_obsper    = $this->io_validacion->uf_valida_texto($row["obsper"],0,254,"");
				$ls_cauegrper = $this->io_validacion->uf_valida_texto($row["cauegrper"],0,1,""); 
				$ls_obsegrper = $this->io_validacion->uf_valida_texto($row["obsegrper"],0,254,"");
				$ls_nacper    = $this->io_validacion->uf_valida_texto($row["nacper"],0,1,"");
				$ls_coreleper = $this->io_validacion->uf_valida_texto($row["coreleper"],0,100,"");
				$ls_cenmedper = $this->io_validacion->uf_valida_texto($row["cenmedper"],0,3,"");
				$ls_turper    = $this->io_validacion->uf_valida_texto($row["turper"],0,1,"");
				$ls_horper = $this->io_validacion->uf_valida_texto($row["horper"],0,45,""); 
				$ls_hcmper = $this->io_validacion->uf_valida_texto($row["hcmper"],0,1,"");
				$ls_tipsanper    = $this->io_validacion->uf_valida_texto($row["tipsanper"],0,10,"");
				$li_monpagvivperaux=$this->io_validacion->uf_valida_monto($row["monpagvivper"],0); 
				$li_ingbrumenaux=$this->io_validacion->uf_valida_monto($row["ingbrumen"],0); 
				$ls_codcom = $this->io_validacion->uf_valida_texto($row["codcom"],0,10,"");
				$ls_codran = $this->io_validacion->uf_valida_texto($row["codran"],0,10,"");
				$ls_numexpper = $this->io_validacion->uf_valida_texto($row["numexpper"],0,20,"");
				$ls_codpainac = $this->io_validacion->uf_valida_texto($row["codpainac"],0,3,"");
				$ls_codestnac = $this->io_validacion->uf_valida_texto($row["codestnac"],0,3,"");
				if(($ls_codper!="")&&($ls_cedper!="")&&($ld_fecingadmpubper!="")&&($ld_fecingper!=""))
				{
					$ls_sql="INSERT INTO sno_personal(codemp,codper,cedper,nomper,apeper,dirper,fecnacper,edocivper,telhabper,telmovper,".
							"			 sexper,estaper,pesper,codpro,nivacaper,catper,cajahoper,numhijper,contraper,tipvivper,tenvivper,".
							"			 monpagvivper,ingbrumen,cuecajahoper,cuelphper,cuefidper,fecingadmpubper,vacper,porisrper,fecingper,".
							"			 anoservpreper,cedbenper,fecegrper,estper,fotper,codpai,codest,codmun,codpar,obsper,cauegrper,obsegrper,".
							"			 nacper,coreleper,cenmedper,turper, horper, hcmper, tipsanper, monpagvivperaux, ingbrumenaux, codcom, ".
							"			 codran, numexpper, codpainac, codestnac)".
							"     VALUES ('".$ls_codemp."','".$ls_codper."','".$ls_cedper."','".$ls_nomper."','".$ls_apeper."','".$ls_dirper."',".
							"			  '".$ld_fecnacper."','".$ls_edocivper."','".$ls_telhabper."','".$ls_telmovper."','".$ls_sexper."',".
							"			  ".$li_estaper.",".$li_pesper.",'".$ls_codpro."','".$ls_nivacaper."','".$ls_catper."','".$ls_cajahoper."',".
							"			  ".$li_numhijper.",'".$ls_contraper."',".$li_tipvivper.",'".$ls_tenvivper."',".$li_monpagvivper.",".
							"			  ".$li_ingbrumen.",'".$ls_cuecajahoper."','".$ls_cuelphper."','".$ls_cuefidper."','".$ld_fecingadmpubper."',".
							"			  '".$ls_vacper."',".$li_porisrper.",'".$ld_fecingper."',".$li_anoservpreper.",'".$ls_cedbenper."',".
							"			  '".$ld_fecegrper."','".$ls_estper."','".$ls_fotper."','".$ls_codpai."','".$ls_codest."','".$ls_codmun."',".
							"			  '".$ls_codpar."','".$ls_obsper."','".$ls_cauegrper."','".$ls_obsegrper."','".$ls_nacper."','".$ls_coreleper."',".
							"			  '".$ls_cenmedper."','".$ls_turper."','".$ls_horper."','".$ls_hcmper."','".$ls_tipsanper."',".$li_monpagvivperaux.",".
							"			  ".$li_ingbrumenaux.",'".$ls_codcom."','".$ls_codran."','".$ls_numexpper."','".$ls_codpainac."','".$ls_codestnac."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el Personal.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Personal. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper.",Cédula Personal ".$ls_cedper.",Fecha Ingreso Adm Pública ".$ld_fecingadmpubper.", Fecha Ingreso ".$ld_fecingper."  \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_personal Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_personal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personalisr()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personalisr
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_personalisr
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, codisr, porisr ".
				"  FROM sno_personalisr ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el isr del Personal.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codper = $this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codisr = $this->io_validacion->uf_valida_texto($row["codisr"],0,2,"");
				$li_porisr = $this->io_validacion->uf_valida_monto($row["porisr"],0);
				if(($ls_codper!="")&&($li_codestrea!=""))
				{
					$ls_sql="INSERT INTO sno_personalisr(codemp, codper, codisr, porisr)".
							"     VALUES ('".$ls_codemp."','".$ls_codper."','".$ls_codisr."',".$li_porisr.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el isr del Personal.\r\n".$this->io_sql_destino->message."\r\n".$ls_sql;
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
					$ls_cadena="Hay data inconsistente en el isr del Personal. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper.", Código IRS".$ls_codisr."  \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_personalisr Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_personalisr Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_personalisr
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personalestudios()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personalestudios
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_personal y los inserta en sno_estudiorealizado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, codestrea, tipestrea, insestrea, desestrea, titestrea, calestrea, fecgraestrea, ".
				"		escval, feciniact, fecfinact, soladi, aprestrea, anoaprestrea, horestrea ".
				"  FROM sno_estudiorealizado ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Estudios del Personal.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp		 = $row["codemp"]; 
				$ls_codper		 = $this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_codestrea	 = $this->io_validacion->uf_valida_monto($row["codestrea"],0);
				$ls_tipestrea    = $this->io_validacion->uf_valida_texto($row["tipestrea"],0,2,"");
				$ls_insestrea    = $this->io_validacion->uf_valida_texto($row["insestrea"],0,254,"");
				$ls_desestrea    = $this->io_validacion->uf_valida_texto($row["desestrea"],0,254,"");
				$ls_titestrea    = $this->io_validacion->uf_valida_texto($row["titestrea"],0,254,"");
				$li_calestrea    = $this->io_validacion->uf_valida_monto($row["calestrea"],0);
				$ld_fecgraestrea = $this->io_validacion->uf_valida_fecha($row["fecgraestrea"],"1900-01-01");
				$ls_escval       = $this->io_validacion->uf_valida_texto($row["escval"],0,20,""); 
				$ld_feciniact    = $this->io_validacion->uf_valida_fecha($row["feciniact"],"1900-01-01");
				$ld_fecfinact    = $this->io_validacion->uf_valida_fecha($row["fecfinact"],"1900-01-01");
				$ls_soladi		 = $this->io_validacion->uf_valida_texto($row["soladi"],0,10,""); 
				$ls_aprestrea	 = $this->io_validacion->uf_valida_texto($row["aprestrea"],0,1,""); 
				$ls_anoaprestrea = $this->io_validacion->uf_valida_texto($row["anoaprestrea"],0,1,""); 
				$ls_horestrea	 = $this->io_validacion->uf_valida_texto($row["horestrea"],0,3,""); 
				if(($ls_codper!="")&&($li_codestrea!=""))
				{
					$ls_sql="INSERT INTO sno_estudiorealizado(codemp, codper, codestrea, tipestrea, insestrea, desestrea, titestrea, ".
							" calestrea, fecgraestrea, escval, feciniact, fecfinact, soladi, aprestrea, anoaprestrea, horestrea )".
							" VALUES ('".$ls_codemp."','".$ls_codper."',".$li_codestrea.",'".$ls_tipestrea."','".$ls_insestrea."',".
							"'".$ls_desestrea."','".$ls_titestrea."',".$li_calestrea.",'".$ld_fecgraestrea."', '".$ls_escval."',".
							"'".$ld_feciniact."','".$ld_fecfinact."','".$ls_soladi."','".$ls_aprestrea."','".$ls_anoaprestrea."','".$ls_horestrea."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Estudios del Personal.\r\n".$this->io_sql_destino->message."\r\n".$ls_sql;
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
					$ls_cadena="Hay data inconsistente en el Estudio Realizado. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper.", Código Estudio".$li_codestrea."  \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_estudiorealizado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_estudiorealizado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_personalestudios
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personaltrabajos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personaltrabajos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_personal y los inserta en sno_trabajoanterior
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, codtraant, emptraant, ultcartraant, ultsuetraant, fecingtraant, fecrettraant, emppubtraant, ".
				"		codded, anolab, meslab, dialab ".
				"  FROM sno_trabajoanterior ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Trabajos Anteriores del Personal.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp       = $row["codemp"]; 
				$ls_codper       = $this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_codtraant    = $this->io_validacion->uf_valida_monto($row["codtraant"],0); 
				$ls_emptraant    = $this->io_validacion->uf_valida_texto($row["emptraant"],0,100,""); 
				$ls_ultcartraant = $this->io_validacion->uf_valida_texto($row["ultcartraant"],0,100,"");  
				$li_ultsuetraant = $this->io_validacion->uf_valida_monto($row["ultsuetraant"],0); 
				$ld_fecingtraant = $this->io_validacion->uf_valida_fecha($row["fecingtraant"],"1900-01-01");
				$ld_fecrettraant = $this->io_validacion->uf_valida_fecha($row["fecrettraant"],"1900-01-01");
				$ls_emppubtraant = $this->io_validacion->uf_valida_texto($row["emppubtraant"],0,1,"0");  
				$ls_codded = $this->io_validacion->uf_valida_texto($row["codded"],0,3,"0");  
				$li_anolab = $this->io_validacion->uf_valida_monto($row["anolab"],0);  
				$li_meslab = $this->io_validacion->uf_valida_monto($row["meslab"],0);  
				$li_dialab = $this->io_validacion->uf_valida_monto($row["dialab"],0);  
				$li_ultsuetraantaux = $this->io_validacion->uf_valida_monto($row["ultsuetraant"],0);  

				if(($ls_codper!="")&&($li_codtraant!=""))
				{
					$ls_sql="INSERT INTO sno_trabajoanterior(codemp, codper, codtraant, emptraant, ultcartraant, ultsuetraant, fecingtraant, ".
							"fecrettraant, emppubtraant,codded, anolab, meslab, dialab, ultsuetraantaux) VALUES ('".$ls_codemp."','".$ls_codper."',".
							"".$li_codtraant.",'".$ls_emptraant."','".$ls_ultcartraant."',".$li_ultsuetraant.",'".$ld_fecingtraant."',".
							"'".$ld_fecrettraant."','".$ls_emppubtraant."','".$ls_codded."',".$li_anolab.",".$li_meslab.",".$li_dialab.",".
							"".$li_ultsuetraantaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Trabajos Anteriores del Personal.\r\n".$this->io_sql_destino->message."\r\n".$ls_sql;
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
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_trabajoanterior Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_trabajoanterior Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_personaltrabajos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_familiar()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_familiar
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_familia y los inserta en sno_familiar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, cedfam, nomfam, apefam, sexfam, fecnacfam, nexfam, estfam, hcfam, hcmfam ".
				"  FROM sno_familiar ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los familiares.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_cedfam=$this->io_validacion->uf_valida_texto($row["cedfam"],0,10,"");
				$ls_nomfam=$this->io_validacion->uf_valida_texto($row["nomfam"],0,60,"");
				$ls_apefam=$this->io_validacion->uf_valida_texto($row["apefam"],0,60,"");
				$ls_sexfam=$this->io_validacion->uf_valida_texto($row["sexfam"],0,1,"");
				$ld_fecnacfam=$this->io_validacion->uf_valida_fecha($row["fecnacfam"],"1900-01-01");
				$ls_nexfam=$this->io_validacion->uf_valida_texto($row["nexfam"],0,1,"");
				$ls_estfam=$this->io_validacion->uf_valida_texto($row["estfam"],0,1,"");
				$ls_hcfam=$this->io_validacion->uf_valida_texto($row["hcfam"],0,1,"");
				$ls_hcmfam=$this->io_validacion->uf_valida_texto($row["hcmfam"],0,1,"");
				if(($ls_codper!="")&&($ls_cedfam!=""))
				{
					$ls_sql="INSERT INTO sno_familiar(codemp,codper,cedfam,nomfam,apefam,sexfam,fecnacfam,nexfam,estfam, hcfam, hcmfam)".
							" VALUES ('".$ls_codemp."','".$ls_codper."','".$ls_cedfam."','".$ls_nomfam."','".$ls_apefam."','".$ls_sexfam."',".
							"'".$ld_fecnacfam."','".$ls_nexfam."','".$ls_estfam."','".$ls_hcfam."','".$ls_hcmfam."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los familiares.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Familiares del Personal. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper.",Cédula Familiar ".$ls_cedfam." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_familiar Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_familiar Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_familiar
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_permisos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_permisos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_pers_permisos y los inserta en sno_permiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, numper, feciniper, fecfinper, numdiaper, afevacper, tipper, obsper, remper ".
				"  FROM sno_permiso ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los permisos.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_numper=$this->io_validacion->uf_valida_monto($row["numper"],0);
				$ld_feciniper=$this->io_validacion->uf_valida_fecha($row["feciniper"],"1900-01-01");
				$ld_fecfinper=$this->io_validacion->uf_valida_fecha($row["fecfinper"],"1900-01-01");
				$li_numdiaper=$this->io_validacion->uf_valida_monto($row["numdiaper"],0);
				$li_afevacper=$this->io_validacion->uf_valida_monto($row["afevacper"],0);
				$li_tipper=$this->io_validacion->uf_valida_monto($row["tipper"],4);
				$ls_obsper=$this->io_validacion->uf_valida_texto($row["obsper"],0,254,"");
				$ls_remper=$this->io_validacion->uf_valida_texto($row["remper"],0,1,"");
				if(($ls_codper!="")&&($li_numper!=0))
				{
					$ls_sql="INSERT INTO sno_permiso(codemp,codper,numper,feciniper,fecfinper,numdiaper,afevacper,tipper,obsper,remper)".
							"     VALUES ('".$ls_codemp."','".$ls_codper."',".$li_numper.",'".$ld_feciniper."','".$ld_fecfinper."',".
							"			  ".$li_numdiaper.",".$li_afevacper.",".$li_tipper.",'".$ls_obsper."','".$ls_remper."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los permisos.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Permisos del Personal. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper.",Número de Permiso ".$li_numper." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_permiso Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_permiso Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_permisos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_beneficiario()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_beneficiario
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, porpagben, monpagben, codban, ".
				"		ctaban, sc_cuenta, forpagben, nacben, tipcueben ".
				"  FROM sno_beneficiario ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los beneficiarios.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codben=$this->io_validacion->uf_valida_texto($row["codben"],0,10,"");
				$ls_cedben=$this->io_validacion->uf_valida_texto($row["cedben"],0,10,"");
				$ls_nomben=$this->io_validacion->uf_valida_texto($row["nomben"],0,50,"");
				$ls_apeben=$this->io_validacion->uf_valida_texto($row["apeben"],0,50,"");
				$ls_dirben=$this->io_validacion->uf_valida_texto($row["dirben"],0,100,"");
				$ls_telben=$this->io_validacion->uf_valida_texto($row["telben"],0,80,"");
				$ls_tipben=$this->io_validacion->uf_valida_texto($row["tipben"],0,1,"");
				$ls_nomcheben=$this->io_validacion->uf_valida_texto($row["nomcheben"],0,150,"");
				$li_porpagben=$this->io_validacion->uf_valida_monto($row["porpagben"],0);
				$li_monpagben=$this->io_validacion->uf_valida_monto($row["monpagben"],0);
				$ls_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_ctaban=$this->io_validacion->uf_valida_texto($row["ctaban"],0,25,"");
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ls_forpagben=$this->io_validacion->uf_valida_texto($row["forpagben"],0,1,"");
				$ls_nacben=$this->io_validacion->uf_valida_texto($row["nacben"],0,1,"");
				$ls_tipcueben=$this->io_validacion->uf_valida_texto($row["tipcueben"],0,1,"");
				if(($ls_codper!="")&&($ls_codben!=""))
				{
					$ls_sql="INSERT INTO sno_beneficiario(codemp, codper, codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, porpagben, ".
							"monpagben, codban, ctaban, sc_cuenta, forpagben, nacben, tipcueben) VALUES ('".$ls_codemp."','".$ls_codper."','".$ls_codben."',".
							"'".$ls_cedben."','".$ls_nomben."','".$ls_apeben."','".$ls_dirben."','".$ls_telben."','".$ls_tipben."',".
							"'".$ls_nomcheben."',".$li_porpagben.",".$li_monpagben.",'".$ls_codban."','".$ls_ctaban."','".$ls_sc_cuenta."',".
							"'".$ls_forpagben."','".$ls_nacben."','".$ls_tipcueben."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los beneficiarios.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los beneficiarios. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper.",Número de Beneficiario ".$ls_codben." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_beneficiario Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_beneficiario Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_fideicomiso()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_fideicomiso
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_fps y los inserta en sno_permiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, codfid, ficfid, ubifid, cuefid, fecingfid, capfid, capantcom ".
				"  FROM sno_fideicomiso ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el fideicomiso.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codfid=$this->io_validacion->uf_valida_texto($row["codfid"],0,10,"");
				$ls_ficfid=$this->io_validacion->uf_valida_texto($row["ficfid"],0,10,"");
				$ls_ubifid=$this->io_validacion->uf_valida_texto($row["ubifid"],0,10,"");
				$ls_cuefid=$this->io_validacion->uf_valida_texto($row["cuefid"],0,25,"");
				$ld_fecingfid=$this->io_validacion->uf_valida_fecha($row["fecingfid"],"1900-01-01");
				$ls_capfid=$this->io_validacion->uf_valida_texto($row["capfid"],0,1,"");
				$ls_capantcom=$this->io_validacion->uf_valida_texto($row["capantcom"],0,1,"");
				if($ls_codper!="")
				{
					$ls_sql="INSERT INTO sno_fideicomiso(codemp,codper,codfid,ficfid,ubifid,cuefid,fecingfid,capfid,capantcom)".
							" VALUES ('".$ls_codemp."','".$ls_codper."','".$ls_codfid."','".$ls_ficfid."','".$ls_ubifid."',".
							"'".$ls_cuefid."','".$ld_fecingfid."','".$ls_capfid."','".$ls_capantcom."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el fideicomiso.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Fideicomiso del Personal. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_fideicomiso Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_fideicomiso Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_fideicomiso
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_vacaciones()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_vacaciones
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_vac_pers y los inserta en sno_vacacpersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, codvac, fecvenvac, fecdisvac, fecreivac, diavac, stavac, sueintbonvac, sueintvac, ".
				"		diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, quisalvac, quireivac, diaadivac, ".
				"		diaadibon, diafer, sabdom, diapag, pagcan, periodo_1, cod_1, nro_dias_1, Monto_1, periodo_2, cod_2, nro_dias_2, ".
				"		Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, Monto_4, periodo_5, ".
				"		cod_5, nro_dias_5, Monto_5 ".
				"  FROM sno_vacacpersonal ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las vacaciones del personal.\r\n".$this->io_sql_origen->message."\r\n";
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
				$li_Monto_1=$this->io_validacion->uf_valida_monto($row["monto_1"],0); 
				$ls_periodo_2=$this->io_validacion->uf_valida_texto($row["periodo_2"],0,3,"");
				$ls_cod_2=$this->io_validacion->uf_valida_texto($row["cod_2"],0,10,"");
				$li_nro_dias_2=$this->io_validacion->uf_valida_monto($row["nro_dias_2"],0);  
				$li_Monto_2=$this->io_validacion->uf_valida_monto($row["monto_2"],0); 
				$ls_periodo_3=$this->io_validacion->uf_valida_texto($row["periodo_3"],0,3,"");
				$ls_cod_3=$this->io_validacion->uf_valida_texto($row["cod_3"],0,10,"");
				$li_nro_dias_3=$this->io_validacion->uf_valida_monto($row["nro_dias_3"],0);  
				$li_Monto_3=$this->io_validacion->uf_valida_monto($row["monto_3"],0); 
				$ls_periodo_4=$this->io_validacion->uf_valida_texto($row["periodo_4"],0,3,"");
				$ls_cod_4=$this->io_validacion->uf_valida_texto($row["cod_4"],0,10,"");
				$li_nro_dias_4=$this->io_validacion->uf_valida_monto($row["nro_dias_4"],0); 
				$li_Monto_4=$this->io_validacion->uf_valida_monto($row["monto_4"],0); 
				$ls_periodo_5=$this->io_validacion->uf_valida_texto($row["periodo_5"],0,3,"");
				$ls_cod_5=$this->io_validacion->uf_valida_texto($row["cod_5"],0,10,"");
				$li_nro_dias_5=$this->io_validacion->uf_valida_monto($row["nro_dias_5"],0); 
				$li_Monto_5=$this->io_validacion->uf_valida_monto($row["monto_5"],0); 
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
				if(($ls_codper!="")&&($li_codvac!=0)&&($ld_fecvenvac!="")&&($ld_fecdisvac!="")&&($ld_fecreivac!=""))
				{
					$ls_sql="INSERT INTO sno_vacacpersonal(codemp,codper,codvac,fecvenvac,fecdisvac,fecreivac,diavac,stavac,sueintbonvac,".
							"			 sueintvac,diabonvac,obsvac,diapenvac,persalvac,peringvac,dianorvac,quisalvac,quireivac,diaadivac,".
							"			 diaadibon,diafer,sabdom,diapag,pagcan,periodo_1,cod_1,nro_dias_1,Monto_1,periodo_2,cod_2,nro_dias_2,".
							"			 Monto_2,periodo_3,cod_3,nro_dias_3,Monto_3,periodo_4,cod_4,nro_dias_4,Monto_4,periodo_5,cod_5,".
							"			 nro_dias_5,Monto_5, sueintbonvacaux, sueintvacaux, monto_1aux, monto_2aux, monto_3aux, monto_4aux, monto_5aux)".
							"     VALUES ('".$ls_codemp."','".$ls_codper."',".$li_codvac.",'".$ld_fecvenvac."','".$ld_fecdisvac."','".$ld_fecreivac."',".
							"			  ".$li_diavac.",".$li_stavac.",".$li_sueintbonvac.",".$li_sueintvac.",".$li_diabonvac.",'".$ls_obsvac."',".
							"			  ".$li_diapenvac.",'".$ls_persalvac."','".$ls_peringvac."',".$li_dianorvac.",".$li_quisalvac.",".$li_quireivac.",".
							"			  ".$li_diaadivac.",".$li_diaadibon.",".$li_diafer.",".$li_sabdom.",".$li_diapag.",".$li_pagcan.",".
							"			  '".$ls_periodo_1."','".$ls_cod_1."',".$li_nro_dias_1.",".$li_Monto_1.",'".$ls_periodo_2."',".
							"			  '".$ls_cod_2."',".$li_nro_dias_2.",".$li_Monto_2.",'".$ls_periodo_3."','".$ls_cod_3."',".$li_nro_dias_3.",".
							"			  ".$li_Monto_3.",'".$ls_periodo_4."','".$ls_cod_4."',".$li_nro_dias_4.",".$li_Monto_4.",'".$ls_periodo_5."',".
							"			  '".$ls_cod_5."',".$li_nro_dias_5.",".$li_Monto_5.",".$li_sueintbonvacaux.",".$li_sueintvacaux.",".
							"			   ".$li_Monto_1aux.",".$li_Monto_2aux.",".$li_Monto_3aux.",".$li_Monto_4aux.",".$li_Monto_5aux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las vacaciones del personal.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Vacaciones del Personal. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper.",Código Vacación ".$li_codvac.",Fecha Vencimiento ".$ld_fecvenvac.",Fecha Disfrute ".$ld_fecdisvac.", Fecha Reintegro ".$ld_fecreivac." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_vacacpersonal Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_vacacpersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_vacaciones
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_ipasme_dependencias()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ipasme_dependencias
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_ipasme_dependencias y los inserta en sno_ipasme_dependencias
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, coddep, desdep, entdep, mundep, locdep ".
				"  FROM sno_ipasme_dependencias ";				
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las dependencias del ipasme.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_coddep = $this->io_validacion->uf_valida_texto($row["coddep"],0,11,"");
				$ls_desdep = $this->io_validacion->uf_valida_texto($row["desdep"],0,70,"");
				$ls_entdep = $this->io_validacion->uf_valida_texto($row["entdep"],0,2,"");
				$ls_mundep = $this->io_validacion->uf_valida_texto($row["mundep"],0,3,"");
				$ls_locdep = $this->io_validacion->uf_valida_texto($row["locdep"],0,3,"");
				if($ls_coddep!="")
				{
					$ls_sql="INSERT INTO sno_ipasme_dependencias(codemp,coddep,desdep,entdep,mundep,locdep) VALUES ".
							"('".$ls_codemp."','".$ls_coddep."','".$ls_desdep."','".$ls_entdep."','".$ls_mundep."','".$ls_locdep."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las dependencias del ipasme.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las dependencias del ipasme. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper.",Código Dependencia ".$ls_coddep." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_ipasme_dependencias Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_ipasme_dependencias Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_ipasme_dependencias
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_ipasme_afiliado()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ipasme_afiliado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_ipasme_afiliado y los inserta en sno_ipasme_afiliado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, tiptraafi, coddep, actlabafi, tipafiafi, codban, cuebanafi, tipcueafi, codent, codmun, ".
				"		codloc, urbafi, aveafi, nomresafi, numresafi, pisafi, zonafi ".
				"  FROM sno_ipasme_afiliado ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los afiliados del ipasme.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_tiptraafi=$this->io_validacion->uf_valida_texto($row["tiptraafi"],0,1,"");
				$ls_coddep=$this->io_validacion->uf_valida_texto($row["coddep"],0,11,"");
				$ls_actlabafi=$this->io_validacion->uf_valida_texto($row["actlabafi"],0,1,"");
				$ls_tipafiafi=$this->io_validacion->uf_valida_texto($row["tipafiafi"],0,1,"");
				$ls_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_cuebanafi=$this->io_validacion->uf_valida_texto($row["cuebanafi"],0,25,"");
				$ls_tipcueafi=$this->io_validacion->uf_valida_texto($row["tipcueafi"],0,1,"");
				$ls_codent=$this->io_validacion->uf_valida_texto($row["codent"],0,2,"");
				$ls_codmun=$this->io_validacion->uf_valida_texto($row["codmun"],0,3,"");
				$ls_codloc=$this->io_validacion->uf_valida_texto($row["codloc"],0,3,"");
				$ls_urbafi=$this->io_validacion->uf_valida_texto($row["urbafi"],0,30,"");
				$ls_aveafi=$this->io_validacion->uf_valida_texto($row["aveafi"],0,30,"");
				$ls_nomresafi=$this->io_validacion->uf_valida_texto($row["nomresafi"],0,30,"");
				$ls_numresafi=$this->io_validacion->uf_valida_texto($row["numresafi"],0,5,"");
				$ls_pisafi=$this->io_validacion->uf_valida_texto($row["pisafi"],0,2,"");
				$ls_zonafi=$this->io_validacion->uf_valida_texto($row["zonafi"],0,5,"");				
				if($ls_codper!="")
				{
					$ls_sql="INSERT INTO sno_ipasme_afiliado(codemp,codper,tiptraafi,coddep,actlabafi,tipafiafi,codban,cuebanafi,".
							"tipcueafi,codent,codmun,codloc,urbafi,aveafi,nomresafi,numresafi,pisafi,zonafi) VALUES ('".$ls_codemp."',".
							"'".$ls_codper."','".$ls_tiptraafi."','".$ls_coddep."','".$ls_actlabafi."','".$ls_tipafiafi."',".
							"'".$ls_codban."','".$ls_cuebanafi."','".$ls_tipcueafi."','".$ls_codent."','".$ls_codmun."',".
							"'".$ls_codloc."','".$ls_urbafi."','".$ls_aveafi."','".$ls_nomresafi."','".$ls_numresafi."',".
							"'".$ls_pisafi."','".$ls_zonafi."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los afiliados del ipasme.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los afiliados del ipasme. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_ipasme_afiliado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_ipasme_afiliado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_ipasme_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_ipasme_beneficiario()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ipasme_beneficiario
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_ipasme_beneficiario y los inserta en sno_ipasme_beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codper, codben, cedben, prinomben, segnomben, priapeben, segapeben, tiptraben, codpare, ".
				"		nacben, sexben, fecnacben, estcivben, fecfalben, codban, numcueben, tipcueben ".
				"  FROM sno_ipasme_beneficiario ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los beneficiarios del ipasme.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_codben=$this->io_validacion->uf_valida_monto($row["codben"],0);
				$ls_cedben=$this->io_validacion->uf_valida_texto($row["cedben"],0,10,"");
				$ls_prinomben=$this->io_validacion->uf_valida_texto($row["prinomben"],0,15,"");
				$ls_segnomben=$this->io_validacion->uf_valida_texto($row["segnomben"],0,15,"");
				$ls_priapeben=$this->io_validacion->uf_valida_texto($row["priapeben"],0,15,"");
				$ls_segapeben=$this->io_validacion->uf_valida_texto($row["segapeben"],0,15,"");
				$ls_tiptraben=$this->io_validacion->uf_valida_texto($row["tiptraben"],0,1,"");
				$ls_codpare=$this->io_validacion->uf_valida_texto($row["codpare"],0,2,"");
				$ls_nacben=$this->io_validacion->uf_valida_texto($row["nacben"],0,1,"");
				$ls_sexben=$this->io_validacion->uf_valida_texto($row["sexben"],0,1,"");
				$ld_fecnacben=$this->io_validacion->uf_valida_fecha($row["fecnacben"],"1900-01-01");
				$ls_estcivben=$this->io_validacion->uf_valida_texto($row["estcivben"],0,1,"");
				$ld_fecfalben=$this->io_validacion->uf_valida_fecha($row["fecfalben"],"1900-01-01");
				$ls_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_numcueben=$this->io_validacion->uf_valida_texto($row["numcueben"],0,25,"");
				$ls_tipcueben=$this->io_validacion->uf_valida_texto($row["tipcueben"],0,1,"");				
				if($ls_codper!="")
				{
					$ls_sql="INSERT INTO sno_ipasme_beneficiario(codemp,codper,codben,cedben,prinomben,segnomben,priapeben,segapeben,".
							"tiptraben,codpare,nacben, sexben, fecnacben, estcivben, fecfalben, codban, numcueben, tipcueben) ".
							"VALUES ('".$ls_codemp."','".$ls_codper."',".$li_codben.",'".$ls_cedben."','".$ls_prinomben."',".
							"'".$ls_segnomben."','".$ls_priapeben."','".$ls_segapeben."','".$ls_tiptraben."','".$ls_codpare."',".
							"'".$ls_nacben."','".$ls_sexben."','".$ld_fecnacben."','".$ls_estcivben."','".$ld_fecfalben."',".
							"'".$ls_codban."','".$ls_numcueben."','".$ls_tipcueben."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los beneficiarios del ipasme.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los beneficiarios del ipasme. \r\n";
					$ls_cadena=$ls_cadena."Código Personal ".$ls_codper." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_ipasme_beneficiario Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_ipasme_beneficiario Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_ipasme_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_archivotxt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_archivotxt
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_ipasme_beneficiario y los inserta en sno_ipasme_beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codarch, denarch ".
				"  FROM sno_archivotxt ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los archivos txt.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codarch=$this->io_validacion->uf_valida_texto($row["codarch"],0,4,"");
				$ls_denarch=$this->io_validacion->uf_valida_texto($row["denarch"],0,120,"");
				if($ls_codemp!="")
				{
					$ls_sql="INSERT INTO sno_archivotxt(codemp, codarch, denarch) ".
							"VALUES ('".$ls_codemp."','".$ls_codarch."','".$ls_denarch."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los archivos txt.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los archivos txt. \r\n";
					$ls_cadena=$ls_cadena."Código Archivo ".$ls_codarch." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_archivotxt Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_archivotxt Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_archivotxt
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_archivotxtcampo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_archivotxtcampo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_archivotxtcampo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codarch, codcam, descam, inicam, loncam, edicam, clacam, actcam, cricam, tabrelcam, iterelcam, tipcam ".
				"  FROM sno_archivotxtcampo ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los campos de los archivos txt.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codarch=$this->io_validacion->uf_valida_texto($row["codarch"],0,4,"");
				$li_codcam=$this->io_validacion->uf_valida_monto($row["codcam"],0);
				$ls_descam=$this->io_validacion->uf_valida_texto($row["descam"],0,20,"");
				$li_inicam=$this->io_validacion->uf_valida_monto($row["inicam"],0);
				$li_loncam=$this->io_validacion->uf_valida_monto($row["loncam"],0);
				$ls_edicam=$this->io_validacion->uf_valida_texto($row["edicam"],0,1,"");
				$ls_clacam=$this->io_validacion->uf_valida_texto($row["clacam"],0,1,"");
				$ls_actcam=$this->io_validacion->uf_valida_texto($row["actcam"],0,1,"");
				$ls_cricam=$this->io_validacion->uf_valida_texto($row["cricam"],0,255,"");
				$ls_tabrelcam=$this->io_validacion->uf_valida_texto($row["tabrelcam"],0,50,"");
				$ls_iterelcam=$this->io_validacion->uf_valida_texto($row["iterelcam"],0,30,"");
				$ls_tipcam=$this->io_validacion->uf_valida_texto($row["tipcam"],0,1,"");
				if($ls_codemp!="")
				{
					$ls_sql="INSERT INTO sno_archivotxtcampo(codemp, codarch, codcam, descam, inicam, loncam, edicam, clacam, actcam, cricam, ".
							"tabrelcam, iterelcam, tipcam) VALUES ('".$ls_codemp."','".$ls_codarch."',".$li_codcam.",'".$ls_descam."',".
							"".$li_inicam.",".$li_loncam.",'".$ls_edicam."','".$ls_clacam."','".$ls_actcam."','".$ls_cricam."','".$ls_tabrelcam."',".
							"'".$ls_iterelcam."','".$ls_tipcam."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los campos de los archivos txt.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los campos de los archivos txt. \r\n";
					$ls_cadena=$ls_cadena."Código Archivo ".$ls_codarch." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_archivotxtcampo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_archivotxtcampo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_archivotxtcampo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_metodobanco()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_metodobanco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sno_metodobanco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codmet, desmet, tipmet, codempnom, tipcuecrenom, tipcuedebnom, codofinom, debcuelph, codagelph, apaposlph, ".
				"		numplalph, numconlph, suclph, cuelph, grulph, subgrulph, conlph, numactlph, numofifps, numfonfps, confps, nroplafps, ".
				"		numconnom, pagtaqnom ".
				"  FROM sno_metodobanco ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los métodos a banco.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codmet=$this->io_validacion->uf_valida_texto($row["codmet"],0,4,"");
				$ls_desmet=$this->io_validacion->uf_valida_texto($row["desmet"],0,100,"");
				$ls_tipmet=$this->io_validacion->uf_valida_texto($row["tipmet"],0,1,"");
				$ls_codempnom=$this->io_validacion->uf_valida_texto($row["codempnom"],0,10,"");
				$ls_tipcuecrenom=$this->io_validacion->uf_valida_texto($row["tipcuecrenom"],0,2,"");
				$ls_tipcuedebnom=$this->io_validacion->uf_valida_texto($row["tipcuedebnom"],0,2,"");
				$ls_codofinom=$this->io_validacion->uf_valida_texto($row["codofinom"],0,5,"");
				$ls_debcuelph=$this->io_validacion->uf_valida_texto($row["debcuelph"],0,1,"");
				$ls_codagelph=$this->io_validacion->uf_valida_texto($row["codagelph"],0,3,"");
				$ls_apaposlph=$this->io_validacion->uf_valida_texto($row["apaposlph"],0,8,"");
				$ls_numplalph=$this->io_validacion->uf_valida_texto($row["numplalph"],0,15,"");
				$ls_numconlph=$this->io_validacion->uf_valida_texto($row["numconlph"],0,10,"");
				$ls_suclph=$this->io_validacion->uf_valida_texto($row["suclph"],0,5,"");
				$ls_cuelph=$this->io_validacion->uf_valida_texto($row["cuelph"],0,25,"");
				$ls_grulph=$this->io_validacion->uf_valida_texto($row["grulph"],0,10,"");
				$ls_subgrulph=$this->io_validacion->uf_valida_texto($row["subgrulph"],0,5,"");
				$ls_conlph=$this->io_validacion->uf_valida_texto($row["conlph"],0,15,"");
				$ls_numactlph=$this->io_validacion->uf_valida_texto($row["numactlph"],0,10,"");
				$ls_numofifps=$this->io_validacion->uf_valida_texto($row["numofifps"],0,5,"");
				$ls_numfonfps=$this->io_validacion->uf_valida_texto($row["numfonfps"],0,10,"");
				$ls_confps=$this->io_validacion->uf_valida_texto($row["confps"],0,10,"");
				$ls_nroplafps=$this->io_validacion->uf_valida_texto($row["nroplafps"],0,10,"");
				$ls_numconnom=$this->io_validacion->uf_valida_texto($row["numconnom"],0,8,"");
				$ls_pagtaqnom=$this->io_validacion->uf_valida_texto($row["pagtaqnom"],0,1,"");
				if($ls_codmet!="")
				{
					$ls_sql="INSERT INTO sno_metodobanco(codemp, codmet, desmet, tipmet, codempnom, tipcuecrenom, tipcuedebnom, codofinom, ".
							"debcuelph, codagelph, apaposlph, numplalph, numconlph, suclph, cuelph, grulph, subgrulph, conlph, numactlph, ".
							"numofifps, numfonfps, confps, nroplafps, numconnom, pagtaqnom)  VALUES ('".$ls_codemp."','".$ls_codmet."',".
							"'".$ls_desmet."','".$ls_tipmet."','".$ls_codempnom."','".$ls_tipcuecrenom."','".$ls_tipcuedebnom."',".
							"'".$ls_codofinom."','".$ls_debcuelph."','".$ls_codagelph."','".$ls_apaposlph."','".$ls_numplalph."',".
							"'".$ls_numconlph."','".$ls_suclph."','".$ls_cuelph."','".$ls_grulph."','".$ls_subgrulph."','".$ls_conlph."',".
							"'".$ls_numactlph."','".$ls_numofifps."','".$ls_numfonfps."','".$ls_confps."','".$ls_nroplafps."','".$ls_numconnom."',".
							"'".$ls_pagtaqnom."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Métodos a banco.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los metodos a banco. \r\n";
					$ls_cadena=$ls_cadena."Código Método ".$ls_codmet." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_metodobanco Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_metodobanco Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_metodobanco
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_nomina($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_nomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_nominas y los inserta en sno_nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total_select=0;
		$li_total_insert=0;
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, desnom, tippernom, despernom, anocurnom, fecininom, peractnom, numpernom, tipnom, subnom, ".
				"		racnom, adenom, espnom, ctnom, ctmetnom, diabonvacnom, diareivacnom, diainivacnom, diatopvacnom, diaincvacnom, ".
				"		consulnom, descomnom, codpronom, codbennom, conaponom, cueconnom, notdebnom, numvounom, recdocnom, tipdocnom, ".
				"		recdocapo, tipdocapo, perresnom, conpernom, conpronom, titrepnom, codorgcestic ".
				"  FROM sno_nomina ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND codnom='".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Nóminas.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_desnom=$this->io_validacion->uf_valida_texto($row["desnom"],0,100,"");
				$ls_tippernom=$this->io_validacion->uf_valida_texto($row["tippernom"],0,1,"");
				$ls_despernom=$this->io_validacion->uf_valida_texto($row["despernom"],0,20,"");
				$ls_anocurnom=$row["anocurnom"];				
				$ld_fecininom=$this->io_validacion->uf_valida_fecha($row["fecininom"],"");
				$ls_peractnom=$this->io_validacion->uf_valida_texto($row["peractnom"],0,3,"");
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
				$ls_codpronom=str_pad(trim($ls_codpronom),10,"0",0);
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
				if(($ls_codnom!="")&&($ld_fecininom!="")&&($ls_peractnom!=""))
				{
					$ls_sql="INSERT INTO sno_nomina(codemp,codnom,desnom,tippernom,despernom,anocurnom,fecininom,peractnom,numpernom,tipnom,".
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
						$ls_cadena="Error al Insertar las Nóminas.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Nóminas.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Fecha Inicio ".$ld_fecininom.", Período Actual ".$ls_peractnom." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_nomina Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_nomina Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_subnomina($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_subnomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_subnom y los inserta en sno_subnomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codsubnom, dessubnom FROM sno_subnomina ".
				" WHERE codemp ='".$as_codemp."'".
				"   AND codnom ='".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Subnóminas.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp    = $row["codemp"]; 
				$ls_codnom    = $as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codsubnom = $this->io_validacion->uf_valida_texto($row["codsubnom"],0,10,"");
				$ls_dessubnom = $this->io_validacion->uf_valida_texto($row["dessubnom"],0,60,"");
				if(($ls_codnom!="")&&($ls_codsubnom!=""))
				{
					$ls_sql="INSERT INTO sno_subnomina(codemp,codnom,codsubnom,dessubnom)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codsubnom."','".$ls_dessubnom."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Subnóminas.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Subnóminas.\r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Subnómina ".$ls_codsubnom." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_subnomina Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_subnomina Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_subnomina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargo($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_cargos y los inserta en sno_cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codcar, descar ".
				"  FROM sno_cargo ".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";				
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el cargo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codnom = $as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codcar = $this->io_validacion->uf_valida_texto($row["codcar"],0,10,"");
				$ls_descar = $this->io_validacion->uf_valida_texto($row["descar"],0,100,"");
				if(($ls_codnom!="")&&($ls_codcar!=""))
				{
					$ls_sql="INSERT INTO sno_cargo(codemp,codnom,codcar,descar)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codcar."','".$ls_descar."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el cargo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Cargo. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Cargo ".$ls_codcar." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_cargo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_cargo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tabulador($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tabulador
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_tablas y los inserta en sno_tabulador
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codtab, destab, maxpasgra ".
				"  FROM sno_tabulador ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codnom='".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el tabulador.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"");
				$ls_destab=$this->io_validacion->uf_valida_texto($row["destab"],0,100,"");
				$li_maxpasgra=$this->io_validacion->uf_valida_monto($row["maxpasgra"],0);
				if(($ls_codnom!="")&&($ls_codtab!=""))
				{
					$ls_sql="INSERT INTO sno_tabulador(codemp,codnom,codtab,destab, maxpasgra)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codtab."','".$ls_destab."',".$li_maxpasgra.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el tabulador.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Tabulador. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Tabulador ".$ls_codtab." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_tabulador Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_tabulador Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_tabulador
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_grado($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_grado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_grados y los inserta en sno_grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra ".
				"  FROM sno_grado ".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";
		$li_total_select=0;
		$li_total_insert=0;
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el grado.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"");
				$ls_codpas=$this->io_validacion->uf_valida_texto($row["codpas"],0,15,"");
				$ls_codgra=$this->io_validacion->uf_valida_texto($row["codgra"],0,15,"");
				$li_monsalgra=$this->io_validacion->uf_valida_monto($row["monsalgra"],0);
				$li_moncomgra=$this->io_validacion->uf_valida_monto($row["moncomgra"],0);
				$li_monsalgra=$this->io_rcbsf->uf_convertir_monedabsf($li_monsalgra,2,1,1000,1);
				$li_moncomgra=$this->io_rcbsf->uf_convertir_monedabsf($li_moncomgra,2,1,1000,1);
				$li_monsalgraaux=$this->io_validacion->uf_valida_monto($row["monsalgra"],0);
				$li_moncomgraaux=$this->io_validacion->uf_valida_monto($row["moncomgra"],0);
				if(($ls_codnom!="")&&($ls_codtab!="")&&($ls_codpas!="")&&($ls_codgra!=""))
				{
					$ls_sql="INSERT INTO sno_grado(codemp,codnom,codtab,codpas,codgra,monsalgra,moncomgra, moncomgraaux, monsalgraaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codtab."','".$ls_codpas."','".$ls_codgra."',".
							"			   ".$li_monsalgra.",".$li_moncomgra.",".$li_monsalgraaux.",".$li_moncomgraaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el grado.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los grados. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Tabulador ".$ls_codtab.",Código Paso ".$ls_codpas.",Código Grado ".$ls_codgra." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_grado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_grado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_grado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primagrado($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_primagrado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_grados y los inserta en sno_grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codtab, codpas, codgra, codpri, despri, monpri ".
				"  FROM sno_primagrado ".
				"  WHERE codemp = '".$as_codemp."'".
				"    AND codnom = '".$as_codnom."'";
		$li_total_select=0;
		$li_total_insert=0;
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la prima grado.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"");
				$ls_codpas=$this->io_validacion->uf_valida_texto($row["codpas"],0,15,"");
				$ls_codgra=$this->io_validacion->uf_valida_texto($row["codgra"],0,15,"");
				$ls_codpri=$this->io_validacion->uf_valida_texto($row["codpri"],0,15,"");
				$ls_despri=$this->io_validacion->uf_valida_texto($row["despri"],0,100,"");
				$li_monpri=$this->io_validacion->uf_valida_monto($row["monpri"],0);
				$li_monpri=$this->io_rcbsf->uf_convertir_monedabsf($li_monpri,2,1,1000,1);
				$li_monpriaux=$this->io_validacion->uf_valida_monto($row["monpri"],0);
				if(($ls_codnom!="")&&($ls_codtab!="")&&($ls_codpas!="")&&($ls_codgra!="")&&($ls_codpri!=""))
				{
					$ls_sql="INSERT INTO sno_primagrado(codemp,codnom,codtab,codpas,codgra,codpri,despri,monpri,monpriaux)".
							" VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codtab."','".$ls_codpas."','".$ls_codgra."',".
							" '".$ls_codpri."','".$ls_despri."',".$li_monpri.",".$li_monpriaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la prima grado.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la prima grado. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Tabulador ".$ls_codtab.",Código Paso ".$ls_codpas.",Código Grado ".$ls_codgra." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_primagrado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_primagrado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_primagrado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_asignacioncargo($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_asignacioncargo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_rac y los inserta en sno_asignacioncargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codasicar, denasicar, claasicar, minorguniadm, ofiuniadm, uniuniadm, depuniadm, ".
				"		prouniadm, codtab, codpas, codgra, codded, codtipper, numvacasicar, numocuasicar, codproasicar ".
				"  FROM sno_asignacioncargo ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codnom='".$as_codnom."'";
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
			   while(($row=$this->io_sql_origen->fetch_row($io_recordset))&&$lb_valido)
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
				$ls_codpas=$this->io_validacion->uf_valida_texto($row["codpas"],0,15,"");
				$ls_codgra=$this->io_validacion->uf_valida_texto($row["codgra"],0,15,"");
				$ls_codded=$this->io_validacion->uf_valida_texto($row["codded"],0,3,"");
				$ls_codtipper=$this->io_validacion->uf_valida_texto($row["codtipper"],0,4,"");
				$li_numvacasicar=$this->io_validacion->uf_valida_monto($row["numvacasicar"],0);
				$li_numocuasicar=$this->io_validacion->uf_valida_monto($row["numocuasicar"],0);
				$ls_codproasicar=$this->io_validacion->uf_valida_texto($row["codproasicar"],0,33,"");
				if(($ls_codnom!="")&&($ls_codasicar!=""))
				{
					$ls_sql="INSERT INTO sno_asignacioncargo(codemp,codnom,codasicar,denasicar,claasicar,minorguniadm,ofiuniadm, ".
							"			 uniuniadm,depuniadm,prouniadm,codtab,codpas,codgra,codded,codtipper,numvacasicar,numocuasicar, ".
							"			 codproasicar)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codasicar."','".$ls_denasicar."','".$ls_claasicar."',".
							"			  '".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."','".$ls_prouniadm."',".
							"			  '".$ls_codtab."','".$ls_codpas."','".$ls_codgra."','".$ls_codded."','".$ls_codtipper."',".
							"			  ".$li_numvacasicar.",".$li_numocuasicar.",'".$ls_codproasicar."')";
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
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_asignacioncargo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_asignacioncargo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personalnomina($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personalnomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_pers_rac y los inserta en sno_personalnomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, horper, minorguniadm, ".
				"		ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, codcueban, tipcuebanper, codcar, ".
				"		fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, codtabvac, sueintper, ".
				"		pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, codcladoc, codubifis, ".
				"		tipcestic, conjub, catjub, codclavia, codunirac, pagtaqper ".
				"  FROM sno_personalnomina".
				" WHERE codemp= '".$as_codemp."' ".
				"   AND codnom= '".$as_codnom."' ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el personal nomina.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codsubnom=$this->io_validacion->uf_valida_texto($row["codsubnom"],0,10,"0000000000");
				$ls_codasicar=$this->io_validacion->uf_valida_texto($row["codasicar"],0,7,"0000000");
				$ls_codtab=$this->io_validacion->uf_valida_texto($row["codtab"],0,20,"00000000000000000000");
				$ls_codgra=$this->io_validacion->uf_valida_texto($row["codgra"],0,15,"00");
				$ls_codpas=$this->io_validacion->uf_valida_texto($row["codpas"],0,15,"00");
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
				$ls_codunirac=$this->io_validacion->uf_valida_texto($row["codunirac"],0,10,"");
				$li_pagtaqper=$this->io_validacion->uf_valida_monto($row["pagtaqper"],0);
				$li_sueper=$this->io_rcbsf->uf_convertir_monedabsf($li_sueper,2,1,1000,1);
				$li_sueintper=$this->io_rcbsf->uf_convertir_monedabsf($li_sueintper,2,1,1000,1);
				$li_sueproper=$this->io_rcbsf->uf_convertir_monedabsf($li_sueproper,2,1,1000,1);
				$li_sueperaux=$this->io_validacion->uf_valida_monto($row["sueper"],0);
				$li_sueintperaux=$this->io_validacion->uf_valida_monto($row["sueintper"],0);
				$li_sueproperaux=$this->io_validacion->uf_valida_monto($row["sueproper"],0);
				if(($ls_codnom!="")&&($ls_codper!=""))
				{
					$ls_sql="INSERT INTO sno_personalnomina(codemp,codnom,codper,codsubnom,codasicar,codtab,codgra,codpas,sueper,horper,".
							"			 minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,pagbanper,codban,codcueban,tipcuebanper,".
							"			 codcar,fecingper,staper,cueaboper,fecculcontr,codded,codtipper,quivacper,codtabvac,sueintper,".
							"			 pagefeper,sueproper,codage,fecegrper,fecsusper,cauegrper,codescdoc,codcladoc,codubifis,tipcestic, ".
							"			 conjub,catjub,codclavia, sueperaux, sueintperaux, sueproperaux, codunirac, pagtaqper)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ls_codsubnom."','".$ls_codasicar."',".
							"			 '".$ls_codtab."','".$ls_codgra."','".$ls_codpas."',".$li_sueper.",".$li_horper.",'".$ls_minorguniadm."',".
							"			 '".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."','".$ls_prouniadm."',".$li_pagbanper.",".
							"			 '".$ls_codban."','".$ls_codcueban."','".$ls_tipcuebanper."','".$ls_codcar."','".$ld_fecingper."',".
							"			 '".$ls_staper."','".$ls_cueaboper."','".$ld_fecculcontr."','".$ls_codded."','".$ls_codtipper."',".
							"			 '".$ls_quivacper."','".$ls_codtabvac."',".$li_sueintper.",".$li_pagefeper.",".$li_sueproper.",".
							"			 '".$ls_codage."','".$ld_fecegrper."','".$ld_fecsusper."','".$ls_cauegrper."','".$ls_codescdoc."',".
							"			 '".$ls_codcladoc."','".$ls_codubifis."','".$ls_tipcestic."','".$ls_conjub."','".$ls_catjub."',".
							"			 '".$ls_codclavia."',".$li_sueperaux.",".$li_sueintperaux.",".$li_sueproperaux.",'".$ls_codunirac."',".
							"			 ".$li_pagtaqper.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el personal nomina.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Personal Nómina. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_personalnomina Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_personalnomina Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_proyectopersonal($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_proyectopersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_const y los inserta en sno_constante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codproy, codper, totdiaper, totdiames, pordiames ".
				"  FROM sno_proyectopersonal ".
				" WHERE codemp = '".$as_codemp."' ".
				"   AND codnom = '".$as_codnom."' ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los proyectos por personal.\r\n".$this->io_sql_origen->message."\r\n";
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

				if(($ls_codnom!="")&&($ls_codproy!=""))
				{
					$ls_sql="INSERT INTO sno_proyectopersonal(codemp, codnom, codproy, codper, totdiaper, totdiames, pordiames)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codproy."','".$ls_codper."',".$li_totdiaper.",".
							"			  ".$li_totdiames.",".$li_pordiames.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los proyectos por personal.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los proyectos por personal. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Proyecto ".$ls_codproy." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_proyectopersonal Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_proyectopersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_proyectopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constante($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constante
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_const y los inserta en sno_constante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon, conespseg ".
				"  FROM sno_constante ".
				" WHERE codemp = '".$as_codemp."' ".
				"   AND codnom = '".$as_codnom."' ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la constante.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codcons=$this->io_validacion->uf_valida_texto($row["codcons"],0,10,"");
				$ls_nomcon=$this->io_validacion->uf_valida_texto($row["nomcon"],0,30,"");
				$ls_unicon=$this->io_validacion->uf_valida_texto($row["unicon"],0,10,"");
				$li_equcon=$this->io_validacion->uf_valida_monto($row["equcon"],0);
				$li_topcon=$this->io_validacion->uf_valida_monto($row["topcon"],0);
				$li_valcon=$this->io_validacion->uf_valida_monto($row["valcon"],0);
				$li_reicon=$this->io_validacion->uf_valida_monto($row["reicon"],0);
				$ls_tipnumcon=$this->io_validacion->uf_valida_texto($row["tipnumcon"],0,1,"0");
				$ls_conespseg=$this->io_validacion->uf_valida_texto($row["conespseg"],0,1,"0");
				$li_equconaux=$this->io_validacion->uf_valida_monto($row["equcon"],0);
				$li_topconaux=$this->io_validacion->uf_valida_monto($row["topcon"],0);
				$li_valconaux=$this->io_validacion->uf_valida_monto($row["valcon"],0);
				//$li_equcon=$this->io_rcbsf->uf_convertir_monedabsf($li_equcon,2,1,1000,1);
				//$li_topcon=$this->io_rcbsf->uf_convertir_monedabsf($li_topcon,2,1,1000,1);
				//$li_valcon=$this->io_rcbsf->uf_convertir_monedabsf($li_valcon,2,1,1000,1);

				if(($ls_codnom!="")&&($ls_codcons!=""))
				{
					$ls_sql="INSERT INTO sno_constante(codemp,codnom,codcons,nomcon,unicon,equcon,topcon,valcon,reicon,tipnumcon, equconaux, ".
							"			 topconaux, valconaux, conespseg)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codcons."','".$ls_nomcon."','".$ls_unicon."',".
							"			  ".$li_equcon.",".$li_topcon.",".$li_valcon.",".$li_reicon.",'".$ls_tipnumcon."',".$li_equconaux.",".
							"			  ".$li_topconaux.",".$li_valconaux.",'".$ls_conespseg."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la constante.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Constante. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Constante ".$ls_codcons." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_constante Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_constante Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_constante
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constantepersonal($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constantepersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_const_pers y los inserta en sno_constante
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, codcons, moncon ".
				"  FROM sno_constantepersonal ".
				" WHERE codemp = '".$as_codemp."' ".
				"   AND codnom = '".$as_codnom."' ";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la constante.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codcons=$this->io_validacion->uf_valida_texto($row["codcons"],0,10,"");
				$li_moncon=$this->io_validacion->uf_valida_monto($row["moncon"],0);
				$li_monconaux=$this->io_validacion->uf_valida_monto($row["moncon"],0);
				//$li_moncon=$this->io_rcbsf->uf_convertir_monedabsf($li_moncon,2,1,1000,1);

				if(($ls_codnom!="")&&($ls_codcons!=""))
				{
					$ls_sql="INSERT INTO sno_constantepersonal(codemp, codnom, codper, codcons, moncon, monconaux) VALUES ".
							"('".$ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ls_codcons."',".$li_moncon.",".$li_monconaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la constante personal.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Constante personal. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Constante ".$ls_codcons." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_constantepersonal Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_constantepersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_concepto($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_concepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_conce y los inserta en sno_concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codconc, nomcon, titcon, sigcon, forcon, glocon, acumaxcon, valmincon, valmaxcon, concon, ".
				"		cueprecon, cueconcon, aplisrcon, sueintcon, sueintvaccon, conprenom, intprocon, codpro, forpatcon, ".
				"		cueprepatcon, cueconpatcon, titretempcon, titretpatcon, valminpatcon, valmaxpatcon, codprov, cedben, aplarccon, conprocon ".
				"  FROM sno_concepto ".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el concepto.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codprov=str_pad(trim($ls_codprov),10,"0",0);
				$ls_cedben=$this->io_validacion->uf_valida_texto($row["cedben"],0,10,"----------");
				$li_aplarccon=$this->io_validacion->uf_valida_texto($row["aplarccon"],0,1,"0");
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
				if(($ls_codnom!="")&&($ls_codconc!=""))
				{
					$ls_sql="INSERT INTO sno_concepto(codemp,codnom,codconc,nomcon,titcon,sigcon,forcon,glocon,acumaxcon,valmincon,".
							"			 valmaxcon,concon,cueprecon,cueconcon,aplisrcon,sueintcon,sueintvaccon,conprenom,intprocon,".
							"			 codpro,forpatcon,cueprepatcon,cueconpatcon,titretempcon,titretpatcon,valminpatcon,valmaxpatcon,".
							"			 codprov,cedben,aplarccon,acumaxconaux, valminconaux, valmaxconaux, valminpatconaux, valmaxpatconaux, ".
							"			 conprocon)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codconc."','".$ls_nomcon."','".$ls_titcon."','".$ls_sigcon."',".
							"			  '".$ls_forcon."',".$li_glocon.",".$li_acumaxcon.",".$li_valmincon.",".$li_valmaxcon.",'".$ls_concon."',".
							"			  '".$ls_cueprecon."','".$ls_cueconcon."',".$li_aplisrcon.",".$li_sueintcon.",".$li_sueintvaccon.",".
							"			  ".$li_conprenom.",'".$ls_intprocon."','".$ls_codpro."','".$ls_forpatcon."','".$ls_cueprepatcon."',".
							"			  '".$ls_cueconpatcon."','".$ls_titretempcon."','".$ls_titretpatcon."',".$li_valminpatcon.",".
							"			  ".$li_valmaxpatcon.",'".$ls_codprov."','".$ls_cedben."',".$li_aplarccon.",".$li_acumaxconaux.",".
							"			  ".$li_valminconaux.",".$li_valmaxconaux.",".$li_valminpatconaux.",".$li_valmaxpatconaux.",'".$ls_conprocon."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el concepto.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Concepto. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Concepto ".$ls_codconc." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_concepto Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_concepto Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptopersonal($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptopersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_conce_pers y los inserta en sno_conceptopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat ".
				"  FROM sno_conceptopersonal".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el concepto personal.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");
				$li_aplcon=$this->io_validacion->uf_valida_monto($row["aplcon"],0);
				$li_valcon=$this->io_validacion->uf_valida_monto($row["valcon"],0);
				$li_acuemp=$this->io_validacion->uf_valida_monto($row["acuemp"],0);
				$li_acuiniemp=$this->io_validacion->uf_valida_monto($row["acuiniemp"],0);
				$li_acupat=$this->io_validacion->uf_valida_monto($row["acupat"],0);
				$li_acuinipat=$this->io_validacion->uf_valida_monto($row["acuinipat"],0);
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
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codconc!=""))
				{
					$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat, ".
							"			 acuinipataux, acupataux, acuiniempaux, acuempaux, valconaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ls_codconc."',".$li_aplcon.",".$li_valcon.",".
							"			  ".$li_acuemp.",".$li_acuiniemp.",".$li_acupat.",".$li_acuinipat.",".$li_acuinipataux.",".$li_acupataux.",".
							"			  ".$li_acuiniempaux.",".$li_acuempaux.",".$li_valconaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el concepto personal.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Concepto Personal. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Concepto ".$ls_codconc.",Código Personal ".$ls_codper." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_conceptopersonal Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_conceptopersonal Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptovacacion($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptovacacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_vac_conce y los inserta en sno_conceptovacacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codconc, forsalvac, acumaxsalvac, minsalvac, maxsalvac, consalvac, forpatsalvac, ".
				"		minpatsalvac, maxpatsalvac, forreivac, acumaxreivac, minreivac, maxreivac, conreivac, forpatreivac, ".
				"		minpatreivac, maxpatreivac ".
				"  FROM sno_conceptovacacion ".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el concepto vacación.\r\n".$this->io_sql_origen->message."\r\n";
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
				if(($ls_codnom!="")&&($ls_codconc!=""))
				{
					$ls_sql="INSERT INTO sno_conceptovacacion(codemp,codnom,codconc,forsalvac,acumaxsalvac,minsalvac,maxsalvac,".
							"			 consalvac,forpatsalvac,minpatsalvac,maxpatsalvac,forreivac,acumaxreivac,minreivac,maxreivac,".
							"			 conreivac,forpatreivac,minpatreivac,maxpatreivac, acumaxsalvacaux, minsalvacaux, maxsalvacaux, ".
							"			 minpatsalvacaux, maxpatsalvacaux, acumaxreivacaux, minreivacaux, maxreivacaux, minpatreivacaux, ".
							"			 maxpatreivacaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codconc."','".$ls_forsalvac."',".$li_acumaxsalvac.",".
							"			  ".$li_minsalvac.",".$li_maxsalvac.",'".$ls_consalvac."','".$ls_forpatsalvac."',".
							"			  ".$li_minpatsalvac.",".$li_maxpatsalvac.",'".$ls_forreivac."',".$li_acumaxreivac.",".$li_minreivac.",".
							"			  ".$li_maxreivac.",'".$ls_conreivac."','".$ls_forpatreivac."',".$li_minpatreivac.",".$li_maxpatreivac.",".
							"			  ".$li_acumaxsalvacaux.",".$li_minsalvacaux.",".$li_maxpatsalvacaux.",".$li_minpatsalvacaux.",".
							"			  ".$li_maxpatsalvacaux.",".$li_acumaxreivacaux.",".$li_minreivacaux.",".$li_maxreivacaux.",".
							"			  ".$li_minpatreivacaux.",".$li_maxpatreivacaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el concepto vacación.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Concepto Vacación. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Concepto ".$ls_codconc." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_conceptovacacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_conceptovacacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_conceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primaconcepto($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_primaconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_mpc y los inserta en sno_primaconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codconc, anopri, valpri ".
				"  FROM sno_primaconcepto ".
				" WHERE codemp = '".$as_codemp."' ".
				"   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la prima concepto.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codconc=$this->io_validacion->uf_valida_texto($row["codconc"],0,10,"");
				$li_anopri=$this->io_validacion->uf_valida_monto($row["anopri"],0);
				$li_valpri=$this->io_validacion->uf_valida_monto($row["valpri"],0);
				$li_valpri=$this->io_rcbsf->uf_convertir_monedabsf($li_valpri,2,1,1000,1);
				$li_valpriaux=$this->io_validacion->uf_valida_monto($row["valpri"],0);
				if(($ls_codnom!="")&&($ls_codconc!="")&&($li_anopri!=0))
				{
					$ls_sql="INSERT INTO sno_primaconcepto(codemp,codnom,codconc,anopri,valpri, valpriaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codconc."',".$li_anopri.",".$li_valpri.",".$li_valpriaux.")";
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
					$ls_cadena="Hay data inconsistente en la Prima Concepto. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Concepto ".$ls_codconc.",Año Prima ".$li_anopri." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_primaconcepto Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_primaconcepto Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_primaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tipoprestamo($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipoprestamo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_prest y los inserta en sno_tipoprestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codnom, codtippre, destippre FROM sno_tipoprestamo".
						   " WHERE codemp = '".$as_codemp."'".
						   "   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el tipo de prestamo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp    = $row["codemp"];
				$ls_codnom    = $as_codnomnuevo;//$this->io_validacion->uf_valida_texto($row["codnom"],0,4,"");
				$ls_codtippre = $this->io_validacion->uf_valida_texto($row["codtippre"],0,10,"");
				$ls_destippre = $this->io_validacion->uf_valida_texto($row["destippre"],0,100,"");
				if(($ls_codnom!="")&&($ls_codtippre!=""))
				{
					$ls_sql="INSERT INTO sno_tipoprestamo(codemp,codnom,codtippre,destippre)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codtippre."','".$ls_destippre."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el tipo de prestamo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Tipo Prestamo. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Tipo Prestamo ".$ls_codtippre." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_tipoprestamo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_tipoprestamo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_tipoprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prestamo($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_prepe y los inserta en sno_prestamos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, codconc, monpre, numcuopre, perinipre, monamopre, stapre, ".
				"		fecpre, obsrecpre, obssuspre ".
				"  FROM sno_prestamos".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el prestamo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$li_monpre=$this->io_rcbsf->uf_convertir_monedabsf($li_monpre,2,1,1000,1);
				$li_monamopre=$this->io_rcbsf->uf_convertir_monedabsf($li_monamopre,2,1,1000,1);
				$li_monpreaux=$this->io_validacion->uf_valida_monto($row["monpre"],0);
				$li_monamopreaux=$this->io_validacion->uf_valida_monto($row["monamopre"],0);

				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codtippre!=""))
				{
					$ls_sql="INSERT INTO sno_prestamos(codemp,codnom,codper,numpre,codtippre,codconc,monpre,numcuopre,perinipre,".
							"			 monamopre,stapre,fecpre,obsrecpre,obssuspre, monpreaux, monamopreaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."',".$li_numpre.",'".$ls_codtippre."',".
							"			  '".$ls_codconc."',".$li_monpre.",".$li_numcuopre.",'".$ls_perinipre."',".$li_monamopre.",".
							"			  ".$li_stapre.",'".$ld_fecpre."','".$ls_obsrecpre."','".$ls_obssuspre."',".$li_monpreaux.",".$li_monamopreaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar  el prestamo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Prestamo. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Código Tipo Prestamo ".$ls_codtippre." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_prestamos Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_prestamos Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prestamoperiodo($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamoperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_prepeper y los inserta en sno_prestamosperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, numcuo, percob, feciniper, fecfinper, moncuo, estcuo ".
				"  FROM sno_prestamosperiodo".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el prestamo periodo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_numpre=$this->io_validacion->uf_valida_monto($row["numpre"],0);
				$ls_codtippre=$this->io_validacion->uf_valida_texto($row["codtippre"],0,10,"");
				$li_numcuo=$this->io_validacion->uf_valida_monto($row["numcuo"],0);
				$ls_percob=$this->io_validacion->uf_valida_texto($row["percob"],0,3,"");
				$ld_feciniper=$this->io_validacion->uf_valida_fecha($row["feciniper"],"1900-01-01");
				$ld_fecfinper=$this->io_validacion->uf_valida_fecha($row["fecfinper"],"1900-01-01");
				$li_moncuo=$this->io_validacion->uf_valida_monto($row["moncuo"],0);
				$li_estcuo=$this->io_validacion->uf_valida_monto($row["estcuo"],0);
				$li_moncuo=$this->io_rcbsf->uf_convertir_monedabsf($li_moncuo,2,1,1000,1);
				$li_moncuoaux=$this->io_validacion->uf_valida_monto($row["moncuo"],0);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codtippre!=""))
				{
					$ls_sql="INSERT INTO sno_prestamosperiodo(codemp,codnom,codper,numpre,codtippre,numcuo,percob,feciniper,fecfinper,".
							"			 moncuo,estcuo, moncuoaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."',".$li_numpre.",'".$ls_codtippre."',".$li_numcuo.",".
							"			  '".$ls_percob."','".$ld_feciniper."','".$ld_fecfinper."',".$li_moncuo.",".$li_estcuo.",".$li_moncuoaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar  el prestamo periodo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Prestamo Período. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Código Tipo Prestamo ".$ls_codtippre." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_prestamosperiodo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_prestamosperiodo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_prestamoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prestamoamortizado($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamoamortizado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_prepeper y los inserta en sno_prestamosperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo ".
				"  FROM sno_prestamosamortizado ".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el amortizado del prestamo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$li_numpre=$this->io_validacion->uf_valida_monto($row["numpre"],0);
				$ls_codtippre=$this->io_validacion->uf_valida_texto($row["codtippre"],0,10,"");
				$li_numamo=$this->io_validacion->uf_valida_monto($row["numamo"],0);
				$ls_peramo=$this->io_validacion->uf_valida_texto($row["peramo"],0,3,"");
				$ld_fecamo=$this->io_validacion->uf_valida_fecha($row["fecamo"],"1900-01-01");
				$li_monamo=$this->io_validacion->uf_valida_monto($row["monamo"],0);
				$ls_desamo=$this->io_validacion->uf_valida_texto($row["desamo"],0,8000,"");
				$li_monamo=$this->io_rcbsf->uf_convertir_monedabsf($li_monamo,2,1,1000,1);
				$li_monamoaux=$this->io_validacion->uf_valida_monto($row["monamo"],0);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_codtippre!=""))
				{
					$ls_sql="INSERT INTO sno_prestamosamortizado(codemp, codnom, codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo, monamoaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."',".$li_numpre.",'".$ls_codtippre."',".$li_numamo.",".
							"			  '".$ls_peramo."','".$ld_fecamo."',".$li_monamo.",'".$ls_desamo."',".$li_monamoaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el amortizado del prestamo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente el amortizado del prestamo \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Código Tipo Prestamo ".$ls_codtippre." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_prestamosamortizado Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_prestamosamortizado Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_prestamoamortizado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_insert_periodos($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		$lb_valido=true;
		$ld_fecini=$this->io_funciones->uf_convertirdatetobd($_POST["txtfechainicio"]);
		$ld_fecinisem=$this->io_funciones->uf_convertirdatetobd($_POST["txtfecinisem"]);
		$ld_periodo="";
		$ls_sql="SELECT substr(periodo,1,4) as periodo ".
				"  FROM sigesp_empresa ".
				" WHERE codemp='".$as_codemp."'";
		$rs_data=$this->io_sql_destino->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido = false;
			$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
		}
		else
		{
			if ($row=$this->io_sql_origen->fetch_row($rs_data))
			{
				$ld_periodo = $row["periodo"];
			}
		}
		unset($rs_data);
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_nomina ".
					"  SET anocurnom ='".$ld_periodo."', ".
					"      fecininom ='".$ld_fecinisem."',".
					"      peractnom='001'  ".
					"WHERE codemp='".$this->ls_codemp."'  ".
					"  AND tippernom=0 ".
					"  AND codnom = '".$as_codnomnuevo."'";  
			$li_numrow=$this->io_sql_destino->select($ls_sql);
			if($li_numrow===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
			}
		}	 
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_nomina ".
					"   SET anocurnom ='".$ld_periodo."', ".
					"       fecininom ='".$ld_fecini."', ".
					"       peractnom='001'  ".
					" WHERE codemp='".$this->ls_codemp."'  ".
					"   AND tippernom<>0 ".
					"   AND codnom = '".$as_codnomnuevo."'";  	
			$li_numrow=$this->io_sql_destino->select($ls_sql);
			if($li_numrow===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
			}
		}	 
		if($lb_valido)
		{
			$ls_sql="SELECT codnom,tippernom FROM sno_nomina ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom = '".$as_codnom."'";
			$rs_data=$this->io_sql_origen->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido = false;
				$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
			}
			else
			{
				while(($row=$this->io_sql_origen->fetch_row($rs_data))&&( $lb_valido))
				{
					$ls_codnom    = $as_codnomnuevo;//$row["codnom"];
					$li_tippernom = $row["tippernom"];
					if ($li_tippernom=='0')
					{
						$ld_fecininom = $ld_fecinisem;
					}
					else
					{
						$ld_fecininom = $ld_fecini;
					}
					$lb_valido=$this->uf_sno_fill_periodo($li_tippernom,$ld_fecininom,$ls_codnom);	   
				}
			}
		}
		return $lb_valido;
	}//end fucntion uf_insert_periodos();
	//-----------------------------------------------------------------------------------------------------------------------------------		
	
	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_sno_fill_periodo($as_tippernom,$ad_fecininom,$as_codnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_sno_fill_periodo
		//	Arguments:    $as_tippernom //  tipo de periodo (quincenal,semanal,anual,semanal) 
		//                $adt_fecininom  //	 fecha del inicio de la nomina 
		//                $as_codigo  //  codigo de la nomina 
		//	Returns:	  $lb_valido True si genero los periodos False en caso contrario
		//	Description:  Funcion que genera los periodos por nomina al momento de guardar la nomina 
		/////////////////////////////////////////////////////////////////////////////////////////////
		switch($as_tippernom)
		{
			case "0": // Semanal
				$li_periodos=52;
				break;
			case "1": // Quincenal
				$li_periodos=24;
				break;
			case "2": // Mensual
				$li_periodos=12;
				break;
			case "3": // Anual
				$li_periodos=1;
				break;
		}
		$li_per=(365/$li_periodos);
		$li_lenper=round($li_per,0);
		$lb_valido=true;
		$ldt_fecha=$ad_fecininom;
		$ld_anoinicial=substr($ad_fecininom,0,4);
		for($i=1;($i<=$li_periodos)&&($lb_valido);$i++)
		{
			$ls_codperi= $this->io_funciones->uf_cerosizquierda($i,3);
			$ldt_fec   = $this->uf_final_periodo($ldt_fecha,$li_lenper);
			$ldt_final = $this->io_funciones->uf_convertirdatetobd($ldt_fec);
			$li_totper=0;
			$li_cerper=0;
			$li_conper=0;
			$li_apoconper=0;
			$ls_obsper="";
			$ls_sql="INSERT INTO sno_periodo(codemp,codnom,codperi,fecdesper,fechasper,totper,cerper,conper,apoconper,obsper) ".
					"  VALUES ('".$this->ls_codemp."','".$as_codnom."','".$ls_codperi."','".$ldt_fecha."','".$ldt_final."', ".
					" ".$li_totper.",".$li_cerper.",".$li_conper.",".$li_apoconper.",'".$ls_obsper."')";
			$li_row=$this->io_sql_destino->execute($ls_sql);
			if(($li_row===false))
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Nómina MÉTODO->uf_sno_fill_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
			}
			else
			{
				$lb_valido=true;
				$ldt_final=$this->io_funciones->uf_convertirfecmostrar($ldt_final);
				$ldt_fecha=$this->uf_suma_fechas($ldt_final,1);
				$ldt_fecha=$this->io_funciones->uf_convertirdatetobd($ldt_fecha);
			}
			if($as_tippernom!=0)
			{
				$ld_anoactual=substr($ldt_fecha,0,4);
				if($ld_anoinicial!=$ld_anoactual)
				{
					break;
				}
			}
		}
		if($lb_valido)
		{
			$ls_sql="INSERT INTO sno_periodo(codemp,codnom,codperi,fecdesper,fechasper,totper,cerper,conper,apoconper,obsper) ".
					" VALUES ('".$this->ls_codemp."','".$as_codnom."','000','1900-01-01','1900-01-01',0,0,0,0,'Periodo Nulo')";
			$li_row=$this->io_sql_destino->execute($ls_sql);
			if(($li_row===false))
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Nómina MÉTODO->uf_sno_fill_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
			}
		}
		return $lb_valido;
	}// end function uf_sno_fill_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_final_periodo($adt_fecha,$ai_lenper)
	{   ///////////////////////////////////////////////////////////////////
		//	Function:     uf_final_periodo
		//	Arguments:    $adt_fecha //  fecha del periodo
		//                $ai_lenper //	cantidad de dias del periodo         
		//	Returns:	  $ld_final // fecha final del periodo
		//	Description:  calcula la fecha final del periodo  
		/////////////////////////////////////////////////////////////////////
		$ldt_fecha=$this->io_funciones->uf_convertirfecmostrar($adt_fecha);
		if ((($ai_lenper==15)&&(substr($ldt_fecha,0,2)=="16"))||($ai_lenper==30))
		{
			$ld_final = $this->io_fecha->uf_last_day(substr($ldt_fecha,3,2),substr($ldt_fecha,6,4));
		}
		else
		{
			$li_dias=($ai_lenper-1);
			$ld_final = $this->uf_suma_fechas($ldt_fecha,$li_dias);
		}
		if($ai_lenper==365)
		{
			$ld_final="31/12/".substr($adt_fecha,0,4);
		}
		return $ld_final;
	}// end function uf_final_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_suma_fechas($ad_fecha,$ai_ndias)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suma_fechas
		//		   Access: public
		//	    Arguments: ad_fecha // Fecha a la que se desa sumar
		//                 ai_ndias // Cantidad de dias a sumar          
		//	      Returns: nuevafecha-> variable date
		//	  Description: suma una cantidad de dias pasado por parametros  a una fecha pasada por parametros 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($ai_ndias>0)
		{
			$dia=substr($ad_fecha,0,2);      
			$mes=substr($ad_fecha,3,2);      
			$anio=substr($ad_fecha,6,4);      
			$ultimo_dia=date("d",mktime(0, 0, 0,$mes+1,0,$anio));
			$dias_adelanto=$ai_ndias;
			$siguiente=$dia+$dias_adelanto;
			if($ultimo_dia<$siguiente)
			{        
				$dia_final=$siguiente-$ultimo_dia;
				$mes++;         
				if($mes=='13')
				{            
					$anio++;
					$mes='01';        
				}      
				$fecha_final=str_pad($dia_final,2,"0",0).'/'.str_pad($mes,2,"0",0).'/'.$anio; 
			}
			else   
			{         
				$fecha_final=str_pad($siguiente,2,"0",0).'/'.str_pad($mes,2,"0",0).'/'.$anio; 
			} 
		}
		else
		{
			$fecha_final=$ad_fecha;
		}
		return $fecha_final;
	}// end function uf_suma_fechas	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_fideicomisoperiodo($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_fideicomisoperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_fps_dt y los inserta en sno_fideiperiodo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="SELECT codemp, codnom, codper, anocurper, mescurper, bonvacper, bonfinper, sueintper, apoper, bonextper, diafid, diaadi ".
				"  FROM sno_fideiperiodo ".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codnom = '".$as_codnom."'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el fideicomiso período.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codper=$this->io_validacion->uf_valida_texto($row["codper"],0,10,"");
				$ls_anocurper=$this->io_validacion->uf_valida_texto($row["anocurper"],0,4,"");
				$li_mescurper=$this->io_validacion->uf_valida_monto($row["mescurper"],0);
				$li_bonvacper=$this->io_validacion->uf_valida_monto($row["bonvacper"],0);
				$li_bonfinper=$this->io_validacion->uf_valida_monto($row["bonfinper"],0);
				$li_sueintper=$this->io_validacion->uf_valida_monto($row["sueintper"],0);
				$li_apoper=$this->io_validacion->uf_valida_monto($row["apoper"],0);
				$li_bonextper=$this->io_validacion->uf_valida_monto($row["bonextper"],0);
				$li_diafid=$this->io_validacion->uf_valida_monto($row["diafid"],0);
				$li_diaadi=$this->io_validacion->uf_valida_monto($row["diaadi"],0);
				$li_bonvacper=$this->io_rcbsf->uf_convertir_monedabsf($li_bonvacper,2,1,1000,1);
				$li_bonfinper=$this->io_rcbsf->uf_convertir_monedabsf($li_bonfinper,2,1,1000,1);
				$li_sueintper=$this->io_rcbsf->uf_convertir_monedabsf($li_sueintper,2,1,1000,1);
				$li_apoper=$this->io_rcbsf->uf_convertir_monedabsf($li_apoper,2,1,1000,1);
				$li_bonextper=$this->io_rcbsf->uf_convertir_monedabsf($li_bonextper,2,1,1000,1);
				$li_bonvacperaux=$this->io_validacion->uf_valida_monto($row["bonvacper"],0);
				$li_bonfinperaux=$this->io_validacion->uf_valida_monto($row["bonfinper"],0);
				$li_sueintperaux=$this->io_validacion->uf_valida_monto($row["sueintper"],0);
				$li_apoperaux=$this->io_validacion->uf_valida_monto($row["apoper"],0);
				$li_bonextperaux=$this->io_validacion->uf_valida_monto($row["bonextper"],0);
				if(($ls_codnom!="")&&($ls_codper!="")&&($ls_anocurper!="")&&($li_mescurper!=0))
				{
					$ls_sql="INSERT INTO sno_fideiperiodo(codemp,codnom,codper,anocurper,mescurper,bonvacper,bonfinper,sueintper,apoper,bonextper,".
							"			 diafid, diaadi, bonvacperaux, bonfinperaux, sueintperaux, apoperaux, bonextperaux)".
							"     VALUES ('".$ls_codemp."','".$ls_codnom."','".$ls_codper."','".$ls_anocurper."',".$li_mescurper.",".
							"			  ".$li_bonvacper.",".$li_bonfinper.",".$li_sueintper.",".$li_apoper.",".$li_bonextper.",".
							"			  ".$li_diafid.",".$li_diaadi.",".$li_bonvacperaux.",".$li_bonfinperaux.",".$li_sueintperaux.",".
							"			  ".$li_apoperaux.",".$li_bonextperaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el fideicomiso período.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el Fideicomiso Período. \r\n";
					$ls_cadena=$ls_cadena."Código Nómina ".$ls_codnom.",Código Personal ".$ls_codper.",Año en curso ".$ld_anocurfid.",Mes en curso ".$li_mescurper." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sno_fideiperiodo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sno_fideiperiodo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_fideicomisoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historicos_adicionales($as_codemp,$as_codnom,$as_codnomnuevo)
	{
		$lb_valido = true;
		$ls_sql="SELECT sno_nomina.codnom, sno_periodo.fecdesper, sno_periodo.fechasper, sno_periodo.codperi, sno_periodo.cerper, sno_periodo.totper, ".
				"		sno_nomina.anocurnom ".
				"  FROM sno_nomina, sno_periodo ".
				" WHERE sno_periodo.peradi = 1 ".
				"   AND sno_nomina.codnom = '".$as_codnom."' ".
				"   AND sno_nomina.codemp = sno_periodo.codemp ".
				"   AND sno_nomina.codnom = sno_periodo.codnom ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido = false;
			$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
		}
		else
		{
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&( $lb_valido))
			{
				$ls_codnom=$as_codnomnuevo; //$row["codnom"];
				$li_anocurnom=$row["anocurnom"];
				$li_cerper=$row["cerper"];
				$li_totper=$row["totper"];
				$ld_fecdesper=$row["fecdesper"];
				$ld_fechasper=$row["fechasper"];
				$ls_codperiadi=$row["codperi"];
				if($li_cerper==1)
				{
					$ls_sql="SELECT codnom, codperi ".
							"  FROM sno_periodo ".
							" WHERE sno_periodo.codnom='".$ls_codnom."' ".
							"   AND sno_periodo.fecdesper='".$ld_fecdesper."' ".
							"   AND sno_periodo.fechasper='".$ld_fechasper."'";
					$rs_data2=$this->io_sql_destino->select($ls_sql);
					if ($rs_data2===false)
					{
						$lb_valido = false;
						$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
					}
					else
					{
						if(($row=$this->io_sql_origen->fetch_row($rs_data2))&&( $lb_valido))
						{
							$ls_codperi=$row["codperi"];
							$li_totper=$this->io_rcbsf->uf_convertir_monedabsf($li_totper,2,1,1000,1);
							$ls_sql="UPDATE sno_periodo ".
									"	SET cerper = 1,  ".
									"       totper = ".$li_totper.", ".
									"       conper = 0, ".
									"       apoconper = 0 ".
									" WHERE codnom='".$ls_codnom."' ".
									"   AND codperi='".$ls_codperi."' ".
									"   AND fecdesper='".$ld_fecdesper."' ".
									"   AND fechasper='".$ld_fechasper."'";
							$li_row=$this->io_sql_destino->select($ls_sql);
							if ($li_row===false)
							{
								$lb_valido = false;
								$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
							}
							if($lb_valido)
							{
								$ls_sql="UPDATE sno_nomina ".
										"	SET peractnom = '".str_pad(($ls_codperi+1),3,"0",0)."' ".
										" WHERE codnom='".$ls_codnom."' ";
								$li_row=$this->io_sql_destino->select($ls_sql);
								if ($li_row===false)
								{
									$lb_valido = false;
									$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
								}
							}						
							if($lb_valido)
							{
								$ls_sql="SELECT fecininom ".
										"  FROM sno_nomina ".
										" WHERE sno_nomina.codnom='".$ls_codnom."' ";
								$rs_data3=$this->io_sql_destino->select($ls_sql);
								if ($rs_data3===false)
								{
									$lb_valido = false;
									$this->io_msg->message("CLASE->sigesp_copia_sno METODO->uf_insert_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql_destino->message)); 
								}
								else
								{
									if(($row=$this->io_sql_origen->fetch_row($rs_data3))&&( $lb_valido))
									{
										$ld_fecininom= $row["fecininom"];
									}
								}
							}
							if($lb_valido)
							{
								$ls_anocur=substr($ld_fechasper,0,4);
								$lb_valido=$this->sno_historico->uf_convertir_data($as_codnom,$as_codnomnuevo,$li_anocurnom,$ls_anocur,$ls_codperiadi,$ls_codperi,$ld_fecininom);
							}						
						}
					}
				}
			}
		}
		return $lb_valido;
	}//end fucntion uf_insert_periodos();
	//-----------------------------------------------------------------------------------------------------------------------------------

	// Funciones para el borrado de tablas en nomina
	function ue_limpiar_nomina_basico()
	{
		$lb_valido=true;
		//------------------------------------ Borrar tablas de nomina -----------------------------------------
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hresumen','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hsalida','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hprenomina','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hprestamosperiodo','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hprestamosamortizado','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hprestamos','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_htipoprestamo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hprimaconcepto','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hconceptovacacion','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hproyectopersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hconceptopersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hconcepto','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hconstantepersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hconstante','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hvacacpersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hpersonalnomina','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hunidadadmin','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hproyecto','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hasignacioncargo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hprimagrado','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hgrado','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_htabulador','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hcargo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hsubnomina','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hperiodo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_hnomina','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_resumen','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_salida','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_prenomina','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_prestamosperiodo','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_prestamosamortizado','');
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_prestamos','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_banco','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_tipoprestamo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_primaconcepto','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_conceptovacacion','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_proyectopersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_conceptopersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_concepto','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_constantepersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_constante','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_personalnomina','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_asignacioncargo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_primagrado','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_grado','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_tabulador','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_cargo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_fideiperiodo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_fideiconfigurable','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_ipasme_beneficiario','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_ipasme_afiliado','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_ipasme_dependencias','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_vacacpersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_fideicomiso','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_permiso','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_familiar','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_trabajoanterior','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_estudiorealizado','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_personalisr','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_beneficiario','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_personal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_subnomina','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_dt_scg','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_dt_spg','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_periodo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_nomina','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_ubicacionfisica','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_clasificaciondocente','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_escaladocente','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_programacionreporte','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_tipopersonal','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_dedicacion','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_tablavacperiodo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_tablavacacion','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_cestaticunidadadm','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_cestaticket','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_constanciatrabajo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_diaferiado','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_unidadadmin','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_proyecto','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_profesion','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_rango','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_componente','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_archivotxtcampo','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_archivotxt','');
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('sno_metodobanco','');
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("La data de nómina se borró correctamente.");
			$ls_cadena="La data de nómina se borró correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al limpiar la data de nómina. Verifique el archivo txt."); 
		}
		return $lb_valido;
	}
		
	function uf_limpiar_tabla($as_tabla,$as_condicion)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiar_tabla
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_profesion y los inserta en sno_profesión
		//	   Creado Por: Ing. Yesenia Moreno                       Modificado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/06/2006.
		// Fecha Última Modificación : 	10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "DELETE FROM ".$as_tabla." ".$as_condicion;
		$io_recordset    = $this->io_sql_destino->execute($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Borrar la tabla ".$as_tabla.".\r\n".$this->io_sql_destino->message."\r\n";
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