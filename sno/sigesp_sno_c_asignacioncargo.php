<?php
class sigesp_sno_c_asignacioncargo
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personalnomina;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_asignacioncargo()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_asignacioncargo
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
	}// end function sigesp_sno_c_asignacioncargo
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
									   $as_codded,$as_codtipper,$ai_numvacasicar,$ai_numocuasicar,$as_codproasicar,$as_estcla,$aa_seguridad)
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
				"prouniadm,claasicar,codtab,codpas,codgra,codded,codtipper,numvacasicar,numocuasicar,codproasicar,estcla) VALUES ".
				"('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codasicar."','".$as_denasicar."','".$ls_minorguniadm."',".
				"'".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."','".$ls_prouniadm."','".$as_claasicar."','".$as_codtab."',".
				"'".$as_codpas."','".$as_codgra."','".$as_codded."','".$as_codtipper."',".$ai_numvacasicar.",".$ai_numocuasicar.",".
				"'".$as_codproasicar."','".$as_estcla."')";
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
									   $as_codpasant,$as_codgraant,$as_codtabant,$ai_monsalgra,$as_estcla,$aa_seguridad)
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
				"       estcla       = '".$as_estcla."' ".
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
				if($_SESSION["la_nomina"]["racnom"]=="1")
				{
					$lb_valido=$this->uf_update_personalnomina($as_codasicar,$as_coduniadm,$as_codtab,$as_codpas,$as_codgra,
															   $as_codded,$as_codtipper,$as_coduniadmant,$as_codpasant,
															   $as_codgraant,$as_codtabant,$ai_monsalgra,$aa_seguridad);
				}
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
						$as_codtabant,$ai_monsalgra,$as_estcla,$aa_seguridad)
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
		//				   ai_monsalgra  // Código del Salario del tabulador
		//				   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda la asignación de cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ai_monsalgra=str_replace(".","",$ai_monsalgra);
		$ai_monsalgra=str_replace(",",".",$ai_monsalgra);
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_asignacioncargo("codasicar",$as_codasicar,"1")))
				{
					$lb_valido=$this->uf_insert_asignacioncargo($as_codasicar,$as_denasicar,$as_coduniadm,$as_claasicar,$as_codtab,
																$as_codpas,$as_codgra,$as_codded,$as_codtipper,$ai_numvacasicar,
																$ai_numocuasicar,$as_codproasicar,$as_estcla,$aa_seguridad);
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
																$as_codgraant,$as_codtabant,$ai_monsalgra,$as_estcla,$aa_seguridad);
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
    function uf_delete_asignacioncargo($as_codasicar,$aa_seguridad)
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
        if ($this->io_personalnomina->uf_select_personalnomina("codasicar",$as_codasicar,"1")===false)   
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
			$this->io_mensajes->message("No se puede eliminar la Asignación de Cargo, existe personal asociado a esta.");
		}       
		return $lb_valido;
    }// end function uf_delete_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>