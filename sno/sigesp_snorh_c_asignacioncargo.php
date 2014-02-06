<?php
class sigesp_snorh_c_asignacioncargo
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personalnomina;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_asignacioncargo()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_asignacioncargo
		//		   Access: public (sigesp_sno_d_asignacioncargo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
		require_once("sigesp_sno_c_personalnomina.php");
		$this->io_personalnomina=new sigesp_sno_c_personalnomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$this->ls_codnom="0000";
		}
	}// end function sigesp_snorh_c_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_asignacioncargo)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_personalnomina);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_asignacioncargo($as_campo,$as_valor,$as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_asignacioncargo
		//		   Access: public (sigesp_snorh_c_dedicacion)
		//	    Arguments: as_campo  // campo por el cual se quiere filtrar
		//	               as_valor  // valor del campo
		//	               as_tipo  // tipo de llamado si requiere que filtre por la nómina ó nó
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la asignación de cargo está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT ".$as_campo." ".
				"  FROM sno_asignacioncargo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND ".$as_campo."='".$as_valor."' ";
		if($as_tipo=="1")// Requiere que se filtre por la nómina
		{
			$ls_sql=$ls_sql."   AND codnom='".$this->ls_codnom."'";
		}		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_select_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_existe;
	}// end function uf_select_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_asignacioncargo($as_codasicar,$as_denasicar,$as_coduniadm,$as_claasicar,$as_codtab,$as_codpas,$as_codgra,
									   $as_codded,$as_codtipper,$ai_numvacasicar,$ai_numocuasicar,$as_codproasicar,$as_estcla,
									   $as_codgraobrero,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_asignacioncargo
		//		   Access: private
		//	    Arguments: as_codasicar  // código de la asignación de cargo
		//				   as_denasicar  // Denominación 
		//				   as_coduniadm  // código de la unidad administrativa
		//				   as_claasicar  // clase
		//				   as_codtab  // Código de Tabla
		//				   as_codpas  // Código de Paso
		//				   as_codgra  // Código de Grado
		//				   as_codded  // Código de Dedicación del personal
		//				   as_codtipper  // Código de Tipo de Personal
		//				   ai_numvacasicar  // Número de Vacantes
		//				   ai_numocuasicar  // Número de puestos ocupado
		//				   as_codproasicar  // Código Programático
		//				   as_codgraobrero // grado del obrero
		//				   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la asignación de cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,5,2);
		$ls_uniuniadm=substr($as_coduniadm,8,2);
		$ls_depuniadm=substr($as_coduniadm,11,2);
		$ls_prouniadm=substr($as_coduniadm,14,2);
		$ls_sql="INSERT INTO sno_asignacioncargo (codemp,codnom,codasicar,denasicar,minorguniadm,ofiuniadm,uniuniadm,depuniadm, ".
				"prouniadm,claasicar,codtab,codpas,codgra,codded,codtipper,numvacasicar,numocuasicar,codproasicar,estcla, grado) VALUES ".
				"('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codasicar."','".$as_denasicar."','".$ls_minorguniadm."',".
				"'".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."','".$ls_prouniadm."','".$as_claasicar."','".$as_codtab."',".
				"'".$as_codpas."','".$as_codgra."','".$as_codded."','".$as_codtipper."',".$ai_numvacasicar.",".$ai_numocuasicar.",".
				"'".$as_codproasicar."','".$as_estcla."','".$as_codgraobrero."')"; 
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_insert_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Asignación de Cargo ".$as_codasicar." asociada a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Asignación de Cargo fue registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_insert_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_asignacioncargo($as_codasicar,$as_denasicar,$as_coduniadm,$as_claasicar,$as_codtab,$as_codpas,$as_codgra,
									   $as_codded,$as_codtipper,$ai_numvacasicar,$ai_numocuasicar,$as_codproasicar,$as_coduniadmant,
									   $as_codpasant,$as_codgraant,$as_codtabant,$ai_monsalgra,$as_estcla,
									   $as_codgraobrero,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_asignacioncargo
		//		   Access: private
		//	    Arguments: as_codasicar  // código de la asignación de cargo
		//				   as_denasicar  // Denominación 
		//				   as_coduniadm  // código de la unidad administrativa
		//				   as_claasicar  // clase
		//				   as_codtab  // Código de Tabla
		//				   as_codpas  // Código de Paso
		//				   as_codgra  // Código de Grado
		//				   as_codded  // Código de Dedicación del personal
		//				   as_codtipper  // Código de Tipo de Personal
		//				   ai_numvacasicar  // Número de Vacantes
		//				   ai_numocuasicar  // Número de puestos ocupado
		//				   as_codproasicar  // Código Programático
		//				   as_coduniadmant  // Código de unidad administrativa anterior
		//				   as_codpasant  // Código de Paso anterior
		//				   as_codgraant  // Código de Grado anterior
		//				   as_codtabant  // Código de Tabulador anterior
		//				   ai_monsalgra  // Código del Salario del tabulador
		//				   $as_codgraobrero // grado del obrero
		//				   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza la asignación de cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,5,2);
		$ls_uniuniadm=substr($as_coduniadm,8,2);
		$ls_depuniadm=substr($as_coduniadm,11,2);
		$ls_prouniadm=substr($as_coduniadm,14,2);
		$ls_sql="UPDATE sno_asignacioncargo ".
				"	SET denasicar = '".$as_denasicar."', ".
				"		minorguniadm = '".$ls_minorguniadm."', ".
				"		ofiuniadm = '".$ls_ofiuniadm."', ".
				"		uniuniadm = '".$ls_uniuniadm."', ".
				"		depuniadm = '".$ls_depuniadm."', ".
				"		prouniadm = '".$ls_prouniadm."', ".
				"		claasicar = '".$as_claasicar."', ".
				"		codtab = '".$as_codtab."', ".
				"		codpas = '".$as_codpas."', ".
				"		codgra = '".$as_codgra."', ".
				"		codded = '".$as_codded."', ".
				"		codtipper = '".$as_codtipper."', ".
				"		codproasicar = '".$as_codproasicar."', ".
				"		numvacasicar = ".$ai_numvacasicar.", ".
				"		numocuasicar = ".$ai_numocuasicar.", ".
				"       estcla       = '".$as_estcla."', ".
				"       grado       = '".$as_codgraobrero."' ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codasicar = '".$as_codasicar."' "; 
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_update_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Asignación de Cargo ".$as_codasicar." asociada a la nómina ".$this->ls_codnom;
			$lb_valido=$this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_personalnomina($as_codasicar,$as_coduniadm,$as_codtab,$as_codpas,$as_codgra,
														   $as_codded,$as_codtipper,$as_coduniadmant,$as_codpasant,
														   $as_codgraant,$as_codtabant,$ai_monsalgra,$aa_seguridad);
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Asignación de Cargo fue actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_update_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalnomina($as_codasicar,$as_coduniadm,$as_codtab,$as_codpas,$as_codgra,$as_codded,$as_codtipper,
	 								  $as_coduniadmant,$as_codpasant,$as_codgraant,$as_codtabant,$ai_monsalgra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personalnomina
		//		   Access: private
		//	    Arguments: as_codasicar  // código de Asignación de cargo
		//				   as_coduniadm  // Código de Unidad Administrativa
		//				   as_codtab  // Código de Tabulador
		//				   as_codpas  // código de Paso
		//				   as_codgra  // Código de Grado
		//				   as_codded  // Código de Dedicación
		//				   as_codtipper  // Código de Tipo de Personal
		//				   as_coduniadmant // Codigo de la unidad administrativa Anterior
		//				   as_codpasant  // Código de Paso anterior
		//				   as_codgraant  // Código de Grado anterior
		//				   as_codtabant  // Código de Tabulador anterior
		//				   ai_monsalgra  // Código del Salario del tabulador
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el sueldo en personal nómina a todo personal que tenga asociada esa tabla, paso y grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,5,2);
		$ls_uniuniadm=substr($as_coduniadm,8,2);
		$ls_depuniadm=substr($as_coduniadm,11,2);
		$ls_prouniadm=substr($as_coduniadm,14,2);
		$ls_minorguniadmant=substr($as_coduniadmant,0,4);
		$ls_ofiuniadmant=substr($as_coduniadmant,5,2);
		$ls_uniuniadmant=substr($as_coduniadmant,8,2);
		$ls_depuniadmant=substr($as_coduniadmant,11,2);
		$ls_prouniadmant=substr($as_coduniadmant,14,2);
		$lb_actualizar=false;
		if($as_codtabant!=$as_codtab)
		{
			$ls_sql="UPDATE sno_personalnomina ".
					"	SET codtab = '".$as_codtab."', ".
					"		codpas = '".$as_codpas."', ".
					"		codgra = '".$as_codgra."', ".
					"		codded = '".$as_codded."', ".
					"		codtipper = '".$as_codtipper."', ".
					"		sueper = ".$ai_monsalgra." ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND codnom = '".$this->ls_codnom."' ".
					"   AND codasicar = '".$as_codasicar."' ";
		}
		else
		{
			$lb_actualizar=true;
			$ls_sql="UPDATE sno_personalnomina ".
					"	SET codtab = '".$as_codtab."', ".
					"		codded = '".$as_codded."', ".
					"		codtipper = '".$as_codtipper."' ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND codnom = '".$this->ls_codnom."' ".
					"   AND codasicar = '".$as_codasicar."' ";
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		} 		
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_personalnomina ".
					"	SET minorguniadm = '".$ls_minorguniadm."', ".
					"		ofiuniadm = '".$ls_ofiuniadm."', ".
					"		uniuniadm = '".$ls_uniuniadm."', ".
					"		depuniadm = '".$ls_depuniadm."', ".
					"		prouniadm = '".$ls_prouniadm."' ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codasicar='".$as_codasicar."' ".
					"	AND minorguniadm = '".$ls_minorguniadmant."' ".
					"	AND	ofiuniadm = '".$ls_ofiuniadmant."' ".
					"	AND	uniuniadm = '".$ls_uniuniadmant."' ".
					"	AND	depuniadm = '".$ls_depuniadmant."' ".
					"	AND	prouniadm = '".$ls_prouniadmant."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			} 		
		}
		if(($lb_valido)&&($lb_actualizar))
		{
			$ls_sql="UPDATE sno_personalnomina ".
					"	SET codpas = '".$as_codpas."', ".
					"		codgra = '".$as_codgra."', ".
					"		sueper = ".$ai_monsalgra." ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND codnom = '".$this->ls_codnom."' ".
					"   AND codasicar = '".$as_codasicar."' ".
					"   AND codtab = '".$as_codtab."' ".
					"	AND codpas = '".$as_codpasant."' ".
					"	AND	codgra = '".$as_codgraant."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			} 		
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó Unidad administrativa, Tabla, Paso, Grado, Dedicación y Tipo Personal ".
							 " del personal nómina que esté asociado a la unidad administrativa ".$as_coduniadm.", nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codasicar,$as_denasicar,$as_coduniadm,$as_claasicar,$as_codtab,$as_codpas,$as_codgra,$as_codded,
						$as_codtipper,$ai_numvacasicar,$ai_numocuasicar,$as_codproasicar,$as_coduniadmant,$as_codpasant,$as_codgraant,
						$as_codtabant,$ai_monsalgra,$as_estcla,$as_codnom,$as_codgraobrero,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_d_asignacioncargo)
		//	    Arguments: as_codasicar  // código de la asignación de cargo
		//				   as_denasicar  // Denominación 
		//				   as_coduniadm  // código de la unidad administrativa
		//				   as_claasicar  // clase
		//				   as_codtab  // Código de Tabla
		//				   as_codpas  // Código de Paso
		//				   as_codgra  // Código de Grado
		//				   as_codded  // Código de Dedicación del personal
		//				   as_codtipper  // Código de Tipo de Personal
		//				   ai_numvacasicar  // Número de Vacantes
		//				   ai_numocuasicar  // Número de puestos ocupado
		//				   as_codproasicar  // Código Programático
		//				   as_coduniadmant  // Código de la unidad administrativa anterior
		//				   as_codpasant  // Código de Paso anterior
		//				   as_codgraant  // Código de Grado anterior
		//				   as_codtabant  // Código de Tabulador anterior
		//				   ai_monsalgra  // Código del Salario del tabulador,
		//				   as_codgraobrero // grado del obrero
		//				   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda la asignación de cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ai_monsalgra=str_replace(".","",$ai_monsalgra);
		$ai_monsalgra=str_replace(",",".",$ai_monsalgra);
		$this->ls_codnom=$as_codnom;
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_asignacioncargo("codasicar",$as_codasicar,"1")))
				{
					$lb_valido=$this->uf_insert_asignacioncargo($as_codasicar,$as_denasicar,$as_coduniadm,$as_claasicar,$as_codtab,
																$as_codpas,$as_codgra,$as_codded,$as_codtipper,$ai_numvacasicar,
																$ai_numocuasicar,$as_codproasicar,$as_estcla,
																$as_codgraobrero,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Asignación de Cargo ya existe, no la puede incluir");
				}
				break;
				
			case "TRUE":
				if(($this->uf_select_asignacioncargo("codasicar",$as_codasicar,"1")))
				{   
					$lb_valido=$this->uf_update_asignacioncargo($as_codasicar,$as_denasicar,$as_coduniadm,$as_claasicar,$as_codtab,
																$as_codpas,$as_codgra,$as_codded,$as_codtipper,$ai_numvacasicar,
																$ai_numocuasicar,$as_codproasicar,$as_coduniadmant,$as_codpasant,
																$as_codgraant,$as_codtabant,$ai_monsalgra,$as_estcla,
																$as_codgraobrero,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Asignación de Cargo no existe, no la puede actualizar");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_asignacioncargo($as_codasicar,$as_codnom,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_asignacioncargo
		//		   Access: public (sigesp_sno_d_asignacioncargo)
		//	    Arguments: as_codasicar  // código de la asignación de cargo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina la asignación de cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->ls_codnom=$as_codnom;
		$_SESSION["la_nomina"]["codnom"]=$as_codnom;
		$_SESSION["la_nomina"]["anocurnom"]="1";
		$_SESSION["la_nomina"]["peractnom"]="1";
        if (($this->io_personalnomina->uf_select_personalnomina("codasicar",$as_codasicar,"1")===false)&&
		    ($this->uf_select_codigo_unico_rac_eliminar("codasicar",$as_codasicar,"sno_codigounicorac")===false))   
		{
			$ls_sql="DELETE ".
					"  FROM sno_asignacioncargo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codasicar='".$as_codasicar."' ";
					
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_delete_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));				
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Asignación de Cargo ".$as_codasicar." asociada a la nómina ".$this->ls_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Asignación de Cargo fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_delete_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar la Asignación de Cargo, existe personal o códigos únicos de rac asociados a ella.");
		}       
		unset($_SESSION["la_nomina"]);
		return $lb_valido;
    }// end function uf_delete_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_cargarnomina($as_codnom)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargarnomina
		//		   Access: private
		//	  Description: Función que obtiene todas las nóminas y las carga en un 
		//				   combo para seleccionarlas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		switch($as_codnom)
		{
			case "":
				$ls_selected="selected";
				$ls_disabled="";
				break;
			default:
				$ls_selected="";
				$ls_disabled="disabled";
				break;
		}
		$ls_sql="SELECT sno_nomina.codnom, MAX(sno_nomina.desnom) AS desnom, MAX(sno_nomina.tipnom) AS tipnom, MAX(sno_nomina.racnom) AS racnom, MAX(sno_nomina.racobrnom) AS racobrnom  ".
				"  FROM sno_nomina, sss_permisos_internos ".
				" WHERE sno_nomina.codemp='".$this->ls_codemp."'".
				"   AND sss_permisos_internos.codsis='SNO'".
				"   AND sss_permisos_internos.codusu='".$_SESSION["la_logusr"]."'".
				"   AND sno_nomina.codemp = sss_permisos_internos.codemp ".
				"   AND sno_nomina.codnom = sss_permisos_internos.codintper ".
				" GROUP BY sno_nomina.codnom   ".
				" ORDER BY sno_nomina.codnom  "; 
		$rs_data=$this->io_sql->select($ls_sql);
       	print "<select name='cmbnomina' id='cmbnomina' style='width:380px' ".$ls_disabled.">";
        print " <option value='' ".$ls_selected.">--Seleccione Una--</option>";
		if($rs_data===false)
		{
        	$io_mensajes->message("Clase->Seleccionar Nómina Método->uf_cargarnomina Error->".$io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codnom=$row["codnom"];
				$ls_desnom=$row["desnom"];
				$as_tipnom=$row["tipnom"]; 
				$as_racnom=$row["racnom"]; 
				$as_racobrnom=$row["racobrnom"]; 
				$ls_selected="";
				if($as_codnom==$ls_codnom)
				{
					$ls_selected="selected";
				}
            	print "<option value='".$ls_codnom."' ".$ls_selected." onClick=javascript:ue_select_nom('$as_tipnom','$as_racnom','$as_racobrnom') >".$ls_codnom."-".$ls_desnom."</option>";				
			}
			$this->io_sql->free_result($rs_data);
		}
       	print "</select>";
		print "<input name='txtcodnom' type='hidden' id='txtcodnom' value='".$as_codnom."'>";
   }
  //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_codigo_unico_rac($as_codasicar,$as_codnom,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_codigo_unico_rac
		//	    Arguments: as_codasicar  // código de la asignacion de cargo
		//                 as_codnom    //  código de la nómina
		//				   ai_totrows  // total de fila
		//				   ao_object  // arreglo de objetos
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene todas las códigos únicos de rac de la asignación de cargo seleccionada
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->ls_codnom=$as_codnom;
		$ls_sql="SELECT * ".
				"  FROM sno_codigounicorac ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$as_codnom."'".
				"   AND codasicar='".$as_codasicar."'";				
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_load_codigo_unico_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codunirac=$row["codunirac"];				
				if ($row["estcodunirac"]=='1')
				{
					$ls_estcodunirac="OCUPADO";
				}
				else
				{
					$ls_estcodunirac="DISPONIBLE";
				}
				
				$ao_object[$ai_totrows][1]="<input name=txtcodunirac".$ai_totrows." type=text id=txtcodunirac".$ai_totrows." class=sin-borde size=10 maxlength=10 onKeyUp='javascript: ue_validarnumero(this);' value='".$ls_codunirac."' readOnly>";
				$ao_object[$ai_totrows][2]="<input name=txtestcod".$ai_totrows." type=text id=txtestcod".$ai_totrows." class=sin-borde size=10 maxlength=20 value='".$ls_estcodunirac."' readOnly>";
				$ao_object[$ai_totrows][3]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
			}
			$ai_totrows=$ai_totrows+1;
			$ao_object[$ai_totrows][1]="<input name=txtcodunirac".$ai_totrows." type=text id=txtcodunirac".$ai_totrows." class=sin-borde size=10 maxlength=10 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][2]="<input name=txtestcod".$ai_totrows." type=text id=txtestcod".$ai_totrows." class=sin-borde size=10 maxlength=20 readOnly>";
			$ao_object[$ai_totrows][3]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
			$ao_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_codigo_unico_rac


//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_codigo_unico_rac(&$as_codasicar,$as_codunirac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_codigo_unico_rac
		//		   Access: private
		//	    Arguments: as_codnom // código de la nómina
		//				   as_codasicar  // código de la asignación de cargo
		//				   as_codunirac  // código de único de rac
		//	      Returns: lb_existe True si se encontro ó False si no se encontró
		//	  Description: Funcion que verifica si el código único de rac está registrado
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	
		$as_codasicar="";
		$ls_sql="SELECT codunirac,codasicar ".
				"  FROM sno_codigounicorac ".
				" WHERE codemp='".$this->ls_codemp."'".				
				"   AND codunirac='".$as_codunirac."'";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_select_codigo_unico_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codasicar=$row["codasicar"];
				
			}
			else
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_codigo_unico_rac
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_codigo_unico_rac($as_codnom,$as_codasicar,$as_codunirac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_codigo_unico_rac
		//		   Access: private
		//	    Arguments: as_codnom // código de la nómina
		//				   as_codasicar  // código de la asignación de cargo
		//				   as_codunirac  // código de único de rac
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de sno_codigounicorac
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_codigounicorac".
				"(codemp,codnom,codasicar,codunirac,estcodunirac)VALUES('".$this->ls_codemp."','".$as_codnom."',".
				"'".$as_codasicar."','".$as_codunirac."','0')";
				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_insert_codigo_unico_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el codigo unico de rac ".$as_codunirac." de la asignacion de cardo ".$as_codasicar."  ".
			                  " asociado a la nómina ".$as_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_codigo_unico_rac
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_codigo_unico_rac($as_codnom,$as_codasicar,$as_codunirac,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_codigo_unico_rac
		//		   Access: private
		//	    Arguments: as_codnom // código de la nómina
		//				   as_codasicar  // código de la asignación de cargo
		//				   as_codunirac  // código de único de rac
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina los códigos únicos de rac de la tabla sno_codigounicorac
			//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		 if ($this->uf_select_codigo_unico_rac_eliminar("codunirac",$as_codunirac,"sno_personalnomina")===false)   
		{
			$this->ls_codnom=$as_codnom;
			$ls_sql="DELETE ".
					"  FROM sno_codigounicorac ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'".
					"   AND codasicar='".$as_codasicar."'".
					"   AND codunirac='".$as_codunirac."'";
									
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_delete_codigo_unico_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el codigo unico de rac ".$as_codunirac." de la asignacion de cardo ".$as_codasicar."  ".
								  " asociado a la nómina ".$as_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Código Único fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_delete_codigo_unico_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}			
		}
		else
		{
			$this->io_mensajes->message("El Código Único no se puede eliminar porque está asociado a un personal.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_delete_codigo_unico_rac
	//-----------------------------------------------------------------------------------------------------------------------------------	

//-----------------------------------------------------------------------------------------------------------------------------------	
	
    function uf_update_estatus_codigo_unico_rac($as_codper,$as_valor,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_codigo_unico_rac
		//		   Access: private
		//	    Arguments: as_codper // código del personal
		//                 as_valor  // valor para actualizar
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que actualiza el estatus de  los códigos únicos de rac de la tabla sno_codigounicorac
		//                 al egeresar un personal      
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_codigounicorac ".
				"   SET estcodunirac='".$as_valor."' ".
				" WHERE codemp='".$this->ls_codemp."' ".								
				"   AND codunirac IN (SELECT sno_personalnomina.codunirac ".
				"					   FROM sno_personalnomina ".
				" 						WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"                         AND sno_personalnomina.codper='".$as_codper."' ".
				"                         AND sno_personalnomina.codemp=sno_codigounicorac.codemp ".
				"                         AND sno_personalnomina.codnom=sno_codigounicorac.codnom ".
				"                         AND sno_personalnomina.codasicar=sno_codigounicorac.codasicar ".
				"                         AND sno_personalnomina.codunirac=sno_codigounicorac.codunirac)";
					
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_update_estcodunirac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="UPDATE";
						$ls_descripcion ="Actualizó el estado de los códigos único de RAC asociados a la asignación de cargo asociado a la nómina ".$this->ls_codnom;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					
				}
		
		return $lb_valido;
	}// end function uf_update_estatus_codigo_unico_rac
	//-----------------------------------------------------------------------------------------------------------------------------------	
   function uf_select_estatus_codigo_unico_rac($as_codunirac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_estatus_codigo_unico_rac
		//		   Access: private
		//	    Arguments: as_codnom // código de la nómina
		//				   as_codasicar  // código de la asignación de cargo
		//				   as_codunirac  // código de único de rac
		//	      Returns: lb_existe True si se encontro ó False si no se encontró
		//	  Description: Funcion que verifica si el estado del código único de rac
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	
		$ls_estcodunirac="";
		$ls_sql="SELECT estcodunirac ".
				"  FROM sno_codigounicorac ".
				" WHERE codemp='".$this->ls_codemp."'".				
				"   AND codunirac='".$as_codunirac."'";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_select_estatus_codigo_unico_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_estcodunirac=$row["estcodunirac"];
				
			}
			else
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ls_estcodunirac;
	}// end function uf_select_estatus_codigo_unico_rac
	//-----------------------------------------------------------------------------------------------------------------------------------		

	function uf_select_codigo_unico_rac_eliminar($as_campo,$as_valor,$as_tabla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_codigo_unico_rac_eliminar
		//		   Access: private
		//	    Arguments: as_campo // campo con el cual se va a seleccionar el codunirac
		//				   as_valor  // valor con el cual se va a comparar el campo
		//                 as_tabla // tabla donde se va a buscar el codunirac
		//	      Returns: lb_existe True si se encontro ó False si no se encontró
		//	  Description: Funcion que verifica si el código único de rac está registrado
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	
		$as_codasicar="";
		$ls_sql="SELECT ".$as_campo." ".
				"  FROM ".$as_tabla." ".
				" WHERE codemp='".$this->ls_codemp."'".				
				"   AND ".$as_campo."='".$as_valor."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Asignación Cargo MÉTODO->uf_select_codigo_unico_rac_eliminar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codasicar=$row["codasicar"];
				
			}
			else
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_codigo_unico_rac_eliminar
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	
}//fin de la clase sigesp_snorh_c_asignacioncargo
?>