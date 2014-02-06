<?php
class sigesp_ins_c_cambioiva
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_ins_c_cambioiva()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_ins_c_cambioiva
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once("../shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_cxp_c_solicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_cxp_p_recepcion.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($ai_poraliant,$ai_pornueali,$as_formula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	    Arguments: as_existe    // Fecha de Solicitud
		//				   as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda el tipo de fondo en avance.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$ai_poraliant=str_replace(".","",$ai_poraliant);
		$ai_poraliant=str_replace(",",".",$ai_poraliant);
		$ai_pornueali=str_replace(".","",$ai_pornueali);
		$ai_pornueali=str_replace(",",".",$ai_pornueali);
		
		$ls_codigo="CAMBIOIVA_".$ai_poraliant."_".$ai_pornueali;
		$ls_codsis="INS";
		$ls_seccion="RELEASE";
		$lb_valido=$this->uf_verificar_cambioiva($ls_codigo,$ls_codsis,$ls_seccion,$lb_ejecutado);
		if(($lb_valido)&&(!$lb_ejecutado))
		{
			$this->io_sql->begin_transaction();
			$this->uf_crear_tablas();
			$lb_valido=$this->uf_procesar_cambiobienes($ai_poraliant,$ai_pornueali,$as_formula,$aa_seguridad);
			if($lb_valido)
			{
				$lb_valido=$this->uf_procesar_cambioservicios($ai_poraliant,$ai_pornueali,$as_formula,$aa_seguridad);
				if($lb_valido)
				{
					$lb_valido=$this->uf_procesar_cambioconceptos($ai_poraliant,$ai_pornueali,$as_formula,$aa_seguridad);
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_config($ls_codigo,$ls_codsis,$ls_seccion);
					}
				}
			}
			if($lb_valido==true)
			{
				$this->io_sql->commit();
				$this->io_mensajes->message("El cambio de alicuota se ha procesado exitosamente.");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_mensajes->message("Ha ocurrido un error en el cambio de alicuota");
			}
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_cambioiva($as_codigo,$as_codsis,$as_seccion,&$ab_ejecutado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_cambioiva
		//		   Access: private
		//	    Arguments: as_codigo // Codigo 
		//				   as_codsis // Codigo de Sistema
		//				   as_seccion // Seccion
		//				   ab_ejecutado // Indica si la operacion ha sido ejecutada anteriormente
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que veifica si ya se ha procesado el cambio de alicuota del IVA
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ab_ejecutado=false;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sql="SELECT * ".
			    "  FROM sigesp_config ".
			    " WHERE codemp='".$ls_codemp."' ".
			    "   AND codsis='".$as_codsis."' ".
			    "   AND seccion='".$as_seccion."' ".
			    "   AND entry='".$as_codigo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_verificar_cambioiva ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ab_ejecutado=true;
			}
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_codigo,$as_codsis,$as_seccion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: private
		//	    Arguments: as_codsis // Codigo de Sistema
		//				   as_seccion // Seccion
		//				   as_entry // Entry
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que veifica si ya se ha procesado el cambio de alicuota del IVA
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lb_valido=true;
		$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type)".
				"     VALUES ('".$ls_codemp."','".$as_codsis."','".$as_seccion."','".$as_codigo."','1','C')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_tablas()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_tablas
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que veifica si ya se ha procesado el cambio de alicuota del IVA
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_release.php");
		$io_release= new sigesp_release();
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_histcargosservicios','codemp');
		if($lb_valido==false)
		{
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQLT":
				   $ls_sql="CREATE TABLE sigesp_histcargosservicios (".
						   "             codemp CHAR(4) NOT NULL,".
						   "             codser CHAR(10) NOT NULL,".
						   "             codcarant CHAR(5) NOT NULL,".
						   "             fecregcar DATETIME NOT NULL,".
						   "             codcaract CHAR(5) NOT NULL,".
						   "             codestpro VARCHAR(125),".
						   "             spg_cuenta VARCHAR(25),".
						   "  PRIMARY KEY(codemp, codser, codcarant, fecregcar, codcaract)".
						   ")".
						   " ENGINE = InnoDB";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_crear_tablas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						$lb_valido=false;
					}
						   
				   break;
				   
				case "POSTGRES":
				   $ls_sql="CREATE TABLE sigesp_histcargosservicios (".
						   "             codemp char(4) NOT NULL,".
						   "             codser char(10) NOT NULL,".
						   "             codcarant char(5) NOT NULL,".
						   "             fecregcar date NOT NULL,".
						   "             codcaract char(5) NOT NULL,".
						   "             codestpro varchar(125),".
						   "             spg_cuenta varchar(25),".
						   "  PRIMARY KEY(codemp, codart, codcarant, fecregcar, codcaract)".
						   ")WITHOUT OIDS;";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{ 
						$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_crear_tablas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						$lb_valido=false;
					}
				   break;
			}	
		}
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_histcargosarticulos','codemp');
		if($lb_valido==false)
		{
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQLT":
					   $ls_sql="CREATE TABLE sigesp_histcargosarticulos (".
							   "             codemp CHAR(4) NOT NULL,".
							   "             codart CHAR(20) NOT NULL,".
							   "             codcarant CHAR(5) NOT NULL,".
							   "             fecregcar DATETIME NOT NULL,".
							   "             codcaract CHAR(5) NOT NULL,".
							   "             codestpro VARCHAR(125),".
							   "             spg_cuenta VARCHAR(25),".
							   "  PRIMARY KEY(codemp, codart, codcarant, fecregcar, codcaract)".
							   ")".
							   " ENGINE = InnoDB";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{ 
							$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_crear_tablas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
							$lb_valido=false;
						}
							   
					   break;
					   
					case "POSTGRES":
					   $ls_sql="CREATE TABLE sigesp_histcargosarticulos (".
							   "             codemp char(4) NOT NULL,".
							   "             codart char(20) NOT NULL,".
							   "             codcarant char(5) NOT NULL,".
							   "             fecregcar date NOT NULL,".
							   "             codcaract char(5) NOT NULL,".
							   "             codestpro varchar(125),".
							   "             spg_cuenta varchar(25),".
							   "  PRIMARY KEY(codemp, codart, codcarant, fecregcar, codcaract)".
							   ")WITHOUT OIDS;";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{ 
							$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_crear_tablas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
							$lb_valido=false;
						}
					   break;
				}	
		}		
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_histcargosconceptos','codemp');
		if($lb_valido==false)
		{
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQLT":
					   $ls_sql="CREATE TABLE sigesp_histcargosconceptos (".
							   "             codemp CHAR(4) NOT NULL,".
							   "             codconsep CHAR(20) NOT NULL,".
							   "             codcarant CHAR(5) NOT NULL,".
							   "             fecregcar DATETIME NOT NULL,".
							   "             codcaract CHAR(5) NOT NULL,".
							   "             codestpro VARCHAR(125),".
							   "             spg_cuenta VARCHAR(25),".
							   "  PRIMARY KEY(codemp, codconsep, codcarant, fecregcar, codcaract)".
							   ")".
							   " ENGINE = InnoDB";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{ 
							$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_crear_tablas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
							$lb_valido=false;
						}
							   
					   break;
					   
					case "POSTGRES":
					   $ls_sql="CREATE TABLE sigesp_histcargosconceptos (".
							   "             codemp char(4) NOT NULL,".
							   "             codconsep char(20) NOT NULL,".
							   "             codcarant char(5) NOT NULL,".
							   "             fecregcar date NOT NULL,".
							   "             codcaract char(5) NOT NULL,".
							   "             codestpro varchar(125),".
							   "             spg_cuenta varchar(25),".
							   "  PRIMARY KEY(codemp, codconsep, codcarant, fecregcar, codcaract)".
							   ")WITHOUT OIDS;";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_crear_tablas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
							$lb_valido=false;
						}
					   break;
				}	
		}		
		unset($io_release);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cambiobienes($ai_poraliant,$ai_pornueali,$as_formula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cambiobienes
		//		   Access: private
		//	    Arguments: as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de fondo en avance
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_keygen= new sigesp_c_generar_consecutivo();
		$ls_sql="SELECT codcar, dencar, codestpro, spg_cuenta, porcar, estlibcom,estcla".
				"  FROM sigesp_cargos".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND porcar=".$ai_poraliant."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cambiobienes1 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_pornuealiaux=number_format($ai_pornueali,2,',','.');
				$ls_dencar="IVA ".$ai_pornuealiaux." %";
				$ls_codestpro=$row["codestpro"];
				$ls_estcla=$row["estcla"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_estlibcom=$row["estlibcom"];
				$ls_newcodcar= $io_keygen->uf_generar_numero_nuevo("INS","sigesp_cargos","codcar","INSCAR",5,"","","");
				$lb_valido=$this->uf_insert_cargos($ls_newcodcar,$ls_dencar,$ls_codestpro,$ls_spgcuenta,
												   $ai_pornueali,$ls_estlibcom,$as_formula,$ls_estcla,$aa_seguridad);
						
			}
		}
		
		$ls_sql="SELECT siv_cargosarticulo.codart, siv_cargosarticulo.codcar,sigesp_cargos.codestpro,".
				"       sigesp_cargos.spg_cuenta,sigesp_cargos.estcla,sigesp_cargos.estlibcom".
				"  FROM siv_cargosarticulo,sigesp_cargos".
				" WHERE siv_cargosarticulo.codemp='".$this->ls_codemp."'".
				"   AND siv_cargosarticulo.codemp=sigesp_cargos.codemp".
				"   AND siv_cargosarticulo.codcar=sigesp_cargos.codcar".
				"   AND sigesp_cargos.porcar=".$ai_poraliant."";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cambiobienes2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$ld_dateaux=date("Y-m-d");
			while ((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codart=$rs_data->fields["codart"];
				$ls_codcarant=$rs_data->fields["codcar"];
				$ls_codestpro=$rs_data->fields["codestpro"];
				$ls_estcla=$rs_data->fields["estcla"];
				$ls_spgcuenta=$rs_data->fields["spg_cuenta"];
				$ls_sql="SELECT codcar".
						"  FROM sigesp_cargos".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codestpro='".$ls_codestpro."'".
						"   AND estcla='".$ls_estcla."'".
						"   AND spg_cuenta='".$ls_spgcuenta."'".
						"   AND porcar=".$ai_pornueali.""; 
				$li_row=$this->io_sql->execute($ls_sql);
				$rs_datacar=$this->io_sql->select($ls_sql);
				if($rs_datacar===false)
				{
					$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cambiobienes3 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$lb_valido=false;break;
				}
				else
				{
					if($rowcargos=$this->io_sql->fetch_row($rs_datacar))
					{
						$ls_codcaract=$rowcargos["codcar"];
						$lb_valido=$this->uf_procesar_cargosarticulo($this->ls_codemp,$ls_codart,$ls_codcarant,$ld_dateaux,$ls_codcaract,
															 $ls_codestpro,$ls_spgcuenta,$aa_seguridad);
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_newcodcar,$as_dencar,$as_codestpro,$as_spgcuenta,$as_porcar,$as_estlibcom,
							   $as_formula,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: 
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el nuevo cargo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sigesp_cargos(codemp, codcar, dencar, codestpro, spg_cuenta, porcar, estlibcom, formula,estcla)".
				"     VALUES ('".$this->ls_codemp."', '".$as_newcodcar."', '".$as_dencar."', '".$as_codestpro."', '".$as_spgcuenta."', ".
				"             ".$as_porcar.", '".$as_estlibcom."', '".$as_formula."', '".$as_estcla."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el cargo ".$as_newcodcar." con la alicuota ".$as_porcar;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;

	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cargosarticulo($as_codemp,$as_codart,$as_codcarant,$ad_dateaux,$as_codcaract,$as_codestpro,$as_spgcuenta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: 
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el nuevo cargo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sigesp_histcargosarticulos(codemp, codart, codcarant, fecregcar, codcaract,".
				"                                       codestpro, spg_cuenta)".
				"     VALUES ('".$as_codemp."','".$as_codart."','".$as_codcarant."','".$ad_dateaux."',".
				"             '".$as_codcaract."','".$as_codestpro."','".$as_spgcuenta."')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cargosarticulo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
			break;
		}
		$ls_sql="UPDATE siv_cargosarticulo".
				"   SET codcar='".$as_codcaract."'".
				" WHERE codemp='".$as_codemp."'".
				"   AND codart='".$as_codart."'".
				"   AND codcar='".$as_codcarant."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cargosarticulo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Se realizo el proceso de cambio de Alicuota de los Bienes al cargo ".$as_codcaract;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
				
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cambioservicios($ai_poraliant,$ai_pornueali,$as_formula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cambiobienes
		//		   Access: private
		//	    Arguments: as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de fondo en avance
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT soc_serviciocargo.codser, soc_serviciocargo.codcar,sigesp_cargos.codestpro,".
				"       sigesp_cargos.spg_cuenta,sigesp_cargos.estlibcom".
				"  FROM soc_serviciocargo,sigesp_cargos".
				" WHERE soc_serviciocargo.codemp='".$this->ls_codemp."'".
				"   AND soc_serviciocargo.codemp=sigesp_cargos.codemp".
				"   AND soc_serviciocargo.codcar=sigesp_cargos.codcar".
				"   AND sigesp_cargos.porcar=".$ai_poraliant."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cambioservicios1 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$ld_dateaux=date("Y-m-d");
			while ((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codser=$rs_data->fields["codser"];
				$ls_codcarant=$rs_data->fields["codcar"];
				$ls_codestpro=$rs_data->fields["codestpro"];
				$ls_spgcuenta=$rs_data->fields["spg_cuenta"];
				$ls_sql="SELECT codcar".
						"  FROM sigesp_cargos".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codestpro='".$ls_codestpro."'".
						"   AND spg_cuenta='".$ls_spgcuenta."'".
						"   AND porcar=".$ai_pornueali."";
				$rs_datacar=$this->io_sql->select($ls_sql);
				if($rs_datacar===false)
				{
					$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cambioservicios2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$lb_valido=false;
				}
				else
				{
					if($rowcargos=$this->io_sql->fetch_row($rs_datacar))
					{
						$ls_codcaract=$rowcargos["codcar"];
						$lb_valido=$this->uf_procesar_cargosserv($this->ls_codemp,$ls_codser,$ls_codcarant,$ld_dateaux,$ls_codcaract,$ls_codestpro,$ls_spgcuenta,$aa_seguridad);
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cargosserv($as_codemp,$ls_codser,$as_codcarant,$ad_dateaux,$as_codcaract,$as_codestpro,$as_spgcuenta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cambiobienes
		//		   Access: private
		//	    Arguments: as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de fondo en avance
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sigesp_histcargosservicios(codemp, codser, codcarant, fecregcar, codcaract,".
				"                                       codestpro, spg_cuenta)".
				"     VALUES ('".$as_codemp."','".$ls_codser."','".$as_codcarant."','".$ad_dateaux."',".
				"             '".$as_codcaract."','".$as_codestpro."','".$as_spgcuenta."')";	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cargosserv ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
			return false;
		}
		$ls_sql="UPDATE soc_serviciocargo".
				"   SET codcar='".$as_codcaract."'".
				" WHERE codemp='".$as_codemp."'".
				"   AND codser='".$ls_codser."'".
				"   AND codcar='".$as_codcarant."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cargosserv ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Se realizo el proceso de cambio de Alicuota de los Servicios al cargo ".$as_codcaract;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
				
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cambioconceptos($ai_poraliant,$ai_pornueali,$as_formula,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cambioconceptos
		//		   Access: private
		//	    Arguments: as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de fondo en avance
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_conceptocargos.codconsep, sep_conceptocargos.codcar,sigesp_cargos.codestpro,".
				"       sigesp_cargos.spg_cuenta,sigesp_cargos.estlibcom".
				"  FROM sep_conceptocargos,sigesp_cargos".
				" WHERE sep_conceptocargos.codemp='".$this->ls_codemp."'".
				"   AND sep_conceptocargos.codemp=sigesp_cargos.codemp".
				"   AND sep_conceptocargos.codcar=sigesp_cargos.codcar".
				"   AND sigesp_cargos.porcar=".$ai_poraliant."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cambioconceptos1 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$ld_dateaux=date("Y-m-d");
			while ((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codconsep=$rs_data->fields["codconsep"];
				$ls_codcarant=$rs_data->fields["codcar"];
				$ls_codestpro=$rs_data->fields["codestpro"];
				$ls_spgcuenta=$rs_data->fields["spg_cuenta"];
				$ls_sql="SELECT codcar".
						"  FROM sigesp_cargos".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codestpro='".$ls_codestpro."'".
						"   AND spg_cuenta='".$ls_spgcuenta."'".
						"   AND porcar=".$ai_pornueali."";
				$rs_datacar=$this->io_sql->select($ls_sql);
				if($rs_datacar===false)
				{
					$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cambioconceptos2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$lb_valido=false;
				}
				else
				{
					if($rowcargos=$this->io_sql->fetch_row($rs_datacar))
					{
						$ls_codcaract=$rowcargos["codcar"];
						$lb_valido=$this->uf_procesar_cargosconcep($this->ls_codemp,$ls_codconsep,$ls_codcarant,$ld_dateaux,$ls_codcaract,$ls_codestpro,$ls_spgcuenta,$aa_seguridad);
					}
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cargosconcep($as_codemp,$as_codconsep,$as_codcarant,$ad_dateaux,$as_codcaract,$as_codestpro,$as_spgcuenta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cargosconcep
		//		   Access: private
		//	    Arguments: as_codtipfon // Codigo 
		//				   as_dentipfon // Denominacion
		//				   ai_porrepfon // Porcentaje de para la Reposicion.
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de fondo en avance
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sigesp_histcargosconceptos(codemp, codconsep, codcarant, fecregcar, codcaract,".
				"                                       codestpro, spg_cuenta)".
				"     VALUES ('".$as_codemp."','".$as_codconsep."','".$as_codcarant."','".$ad_dateaux."',".
				"             '".$as_codcaract."','".$as_codestpro."','".$as_spgcuenta."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{print $this->io_sql->message;
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cargosconcep1 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
			return false;
		}
		$ls_sql="UPDATE sep_conceptocargos".
				"   SET codcar='".$as_codcaract."'".
				" WHERE codemp='".$as_codemp."'".
				"   AND codconsep='".$as_codconsep."'".
				"   AND codcar='".$as_codcarant."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->CambioIVA MÉTODO->uf_procesar_cargosconcep2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Se realizo el proceso de cambio de Alicuota de los Conceptos al cargo ".$as_codcaract;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
				
	}
	//-----------------------------------------------------------------------------------------------------------------------------------


}
?>