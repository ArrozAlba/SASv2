<?php
class sigesp_snorh_c_metodobanco
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_metodobanco()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_metodobanco
		//		   Access: public (sigesp_snorh_d_metodobanco)
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_metodobanco
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_metodobanco)
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
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_metodobanco($as_codmet)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_metodobanco
		//		   Access: private
		//	    Arguments: as_codmet  // Código de Método
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el método a banco está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codmet ".
				"  FROM sno_metodobanco ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codmet='".$as_codmet."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_select_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_metodobanco
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_metodobanco($as_codmet,$as_desmet,$as_tipmet,$as_codempnom,$as_codofinom,$as_tipcuecrenom,$as_tipcuedebnom,$as_numplalph,
								   $as_numconlph,$as_suclph,$as_cuelph,$as_grulph,$as_subgrulph,$as_conlph,$as_numactlph,$as_numofifps,
								   $as_numfonfps,$as_confps,$as_nroplafps,$as_debcuelph,$as_codagelph,$as_apaposlph,$as_numconnom,
								   $as_pagtaqnom,$as_ref,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_metodobanco
		//		   Access: private
		//	    Arguments: as_codmet  // código de método
		//				   as_desmet  // descripión del método
		//				   as_tipmet  // tipo de método
		//				   as_codempnom  // código de empresa si es de nómina
		//				   as_codofinom  // código de oficina si es de nómina
		//				   as_tipcuecrenom  // tipo cuenta crédito si es de nómina
		//				   as_tipcuedebnom  // tipo de cuenta débito si es de nómina
		//				   as_numplalph  // nro de planilla si es de lph
		//				   as_numconlph  // nro de contrato si es de lph
		//				   as_suclph  // sucursal si es de lph
		//				   as_cuelph  // cuenta si es de lph
		//				   as_grulph  // grupo si es de lph
		//				   as_subgrulph  // subgrupo si es de lph
		//				   as_conlph  // contrato si es de lph
		//				   as_numactlph  // nro de archivo si es de lph
		//				   as_numofifps  // nro de oficina si es de fps
		//				   as_numfonfps // nro de fondo si es de fps
		//				   as_confps  // contrato si es de fps
		//				   as_nroplafps  // nro de plan
		//				   as_debcuelph  // debita al banco si es de lph
		//				   as_codagelph  // Código de Agencia
 		//				   as_apaposlph  // Apartado Postal
 		//				   as_numconnom  // Número de Convenio 
		//				   as_pagtaqnom  // Pago por Taquilla
		//                 as_ref   // valor que indicara si se debe autoincrementar el nçumero de referencia
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_metodobanco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_metodobanco".
		        "(codemp,codmet,desmet,tipmet,codempnom,tipcuecrenom,tipcuedebnom,numplalph,numconlph,suclph,cuelph,grulph,".
				"subgrulph,conlph,numactlph,numofifps,numfonfps,confps,nroplafps,codofinom,debcuelph,codagelph,apaposlph,numconnom,".
				"pagtaqnom, nroref) VALUES".
				"('".$this->ls_codemp."','".$as_codmet."','".$as_desmet."','".$as_tipmet."','".$as_codempnom."','".$as_tipcuecrenom."',".
				"'".$as_tipcuedebnom."','".$as_numplalph."','".$as_numconlph."','".$as_suclph."','".$as_cuelph."','".$as_grulph."',".
				"'".$as_subgrulph."','".$as_conlph."','".$as_numactlph."','".$as_numofifps."','".$as_numfonfps."','".$as_confps."',".
				"'".$as_nroplafps."','".$as_codofinom."','".$as_debcuelph."','".$as_codagelph."','".$as_apaposlph."','".$as_numconnom."',".
				"'".$as_pagtaqnom."',".$as_ref."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_insert_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Método a Banco ".$as_codmet." de tipo ".$as_tipmet;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Método Banco fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_insert_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_metodobanco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_metodobanco($as_codmet,$as_desmet,$as_tipmet,$as_codempnom,$as_codofinom,$as_tipcuecrenom,$as_tipcuedebnom,$as_numplalph,
								   $as_numconlph,$as_suclph,$as_cuelph,$as_grulph,$as_subgrulph,$as_conlph,$as_numactlph,$as_numofifps,
								   $as_numfonfps,$as_confps,$as_nroplafps,$as_debcuelph,$as_codagelph,$as_apaposlph,$as_numconnom,
								   $as_pagtaqnom,$as_ref,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_metodobanco
		//		   Access: private
		//	    Arguments: as_codmet  // código de método
		//				   as_desmet  // descripión del método
		//				   as_tipmet  // tipo de método
		//				   as_codempnom  // código de empresa si es de nómina
		//				   as_codofinom  // código de oficina si es de nómina
		//				   as_tipcuecrenom  // tipo cuenta crédito si es de nómina
		//				   as_tipcuedebnom  // tipo de cuenta débito si es de nómina
		//				   as_numplalph  // nro de planilla si es de lph
		//				   as_numconlph  // nro de contrato si es de lph
		//				   as_suclph  // sucursal si es de lph
		//				   as_cuelph  // cuenta si es de lph
		//				   as_grulph  // grupo si es de lph
		//				   as_subgrulph  // subgrupo si es de lph
		//				   as_conlph  // contrato si es de lph
		//				   as_numactlph  // nro de archivo si es de lph
		//				   as_numofifps  // nro de oficina si es de fps
		//				   as_numfonfps // nro de fondo si es de fps
		//				   as_confps  // contrato si es de fps
		//				   as_debcuelph  // debita al banco si es de lph
		//				   as_nroplafps  // nro de plan
		//				   as_codagelph  // Código de Agencia
 		//				   as_apaposlph  // Apartado Postal
 		//				   as_numconnom  // Número de Convenio 
		//				   as_pagtaqnom  // Pago por Taquilla
		//				   as_ref  // valor que indicara si se debe autoincrementar el número de referencia
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_metodobanco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_metodobanco ".
				"   SET desmet='".$as_desmet."', ".
				"		tipmet='".$as_tipmet."', ".
				"		codempnom='".$as_codempnom."', ".
				"		codofinom='".$as_codofinom."', ".
				"		tipcuecrenom='".$as_tipcuecrenom."', ".
				"		tipcuedebnom='".$as_tipcuedebnom."', ".
				"		debcuelph='".$as_debcuelph."', ".
				"		numplalph='".$as_numplalph."', ".
				"		numconlph='".$as_numconlph."', ".
				"		suclph='".$as_suclph."', ".
				"		cuelph='".$as_cuelph."', ".
				"		grulph='".$as_grulph."', ".
				"		subgrulph='".$as_subgrulph."', ".
				"		conlph='".$as_conlph."', ".
				"		numactlph='".$as_numactlph."', ".
				"		numofifps='".$as_numofifps."', ".
				"		numfonfps='".$as_numfonfps."', ".
				"		confps='".$as_confps."', ".
				"		nroplafps='".$as_nroplafps."', ".
				"		codagelph='".$as_codagelph."', ".
				"		apaposlph='".$as_apaposlph."', ".
				"		numconnom='".$as_numconnom."', ".
				"       pagtaqnom='".$as_pagtaqnom."', ".
				"       nroref='".$as_ref."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"	AND codmet='".$as_codmet."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_update_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Método a Banco ".$as_codmet." de tipo ".$as_tipmet;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Método Banco fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_update_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_metodobanco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codmet,$as_desmet,$as_tipmet,$as_codempnom,$as_codofinom,$as_tipcuecrenom,$as_tipcuedebnom,
						$as_numplalph,$as_numconlph,$as_suclph,$as_cuelph,$as_grulph,$as_subgrulph,$as_conlph,$as_numactlph,
						$as_numofifps,$as_numfonfps,$as_confps,$as_nroplafps,$as_debcuelph,$as_codagelph,$as_apaposlph,
						$as_numconnom,$as_pagtaqnom,$as_ref,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_metodobanco)
		//	    Arguments: as_codmet  // código de método
		//				   as_desmet  // descripión del método
		//				   as_tipmet  // tipo de método
		//				   as_codempnom  // código de empresa si es de nómina
		//				   as_codofinom  // código de oficina si es de nómina
		//				   as_tipcuecrenom  // tipo cuenta crédito si es de nómina
		//				   as_tipcuedebnom  // tipo de cuenta débito si es de nómina
		//				   as_numplalph  // nro de planilla si es de lph
		//				   as_numconlph  // nro de contrato si es de lph
		//				   as_suclph  // sucursal si es de lph
		//				   as_cuelph  // cuenta si es de lph
		//				   as_grulph  // grupo si es de lph
		//				   as_subgrulph  // subgrupo si es de lph
		//				   as_conlph  // contrato si es de lph
		//				   as_numactlph  // nro de archivo si es de lph
		//				   as_numofifps  // nro de oficina si es de fps
		//				   as_numfonfps // nro de fondo si es de fps
		//				   as_confps  // contrato si es de fps
		//				   as_debcuelph  // debita al banco si es de lph
		//				   as_nroplafps  // nro de plan
		//				   as_codagelph  // Código de Agencia
 		//				   as_apaposlph  // Apartado Postal
 		//				   as_numconnom  // Número de convenio
		//				   as_ref //valor que indicara si se debe autoincrementarel número de referencia
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_metodobanco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_metodobanco($as_codmet)))
				{
					$lb_valido=$this->uf_insert_metodobanco($as_codmet,$as_desmet,$as_tipmet,$as_codempnom,$as_codofinom,$as_tipcuecrenom,
															$as_tipcuedebnom,$as_numplalph,$as_numconlph,$as_suclph,$as_cuelph,
															$as_grulph,$as_subgrulph,$as_conlph,$as_numactlph,$as_numofifps,$as_numfonfps,
															$as_confps,$as_nroplafps,$as_debcuelph,$as_codagelph,$as_apaposlph,
															$as_numconnom,$as_pagtaqnom,$as_ref,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Método Banco ya existe, no lo puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_metodobanco($as_codmet)))
				{
					$lb_valido=$this->uf_update_metodobanco($as_codmet,$as_desmet,$as_tipmet,$as_codempnom,$as_codofinom,$as_tipcuecrenom,
															$as_tipcuedebnom,$as_numplalph,$as_numconlph,$as_suclph,$as_cuelph,
															$as_grulph,$as_subgrulph,$as_conlph,$as_numactlph,$as_numofifps,$as_numfonfps,
															$as_confps,$as_nroplafps,$as_debcuelph,$as_codagelph,$as_apaposlph,
															$as_numconnom,$as_pagtaqnom,$as_ref,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Método Banco no existe, no lo puede actualizar.");
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_metodobanco(&$as_existe,&$as_codmet,&$as_desmet,&$as_tipmet,&$as_codempnom,&$as_codofinom,&$as_tipcuecrenom,&$as_tipcuedebnom,
					             &$as_numplalph,&$as_numconlph,&$as_suclph,&$as_cuelph,&$as_grulph,&$as_subgrulph,&$as_conlph,&$as_numactlph,
					             &$as_numofifps,&$as_numfonfps,&$as_confps,&$as_nroplafps,&$as_debcuelph,&$as_codagelph,&$as_apaposlph,&$as_numconnom,
								 &$as_pagtaqnom,&$as_ref)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_metodobanco
		//		   Access: public (sigesp_snorh_d_metodobanco)
		//	    Arguments: as_codmet  // código de método
		//				   as_desmet  // descripión del método
		//				   as_tipmet  // tipo de método
		//				   as_codempnom  // código de empresa si es de nómina
		//				   as_codofinom  // código de oficina si es de nómina
		//				   as_tipcuecrenom  // tipo cuenta crédito si es de nómina
		//				   as_tipcuedebnom  // tipo de cuenta débito si es de nómina
		//				   as_numplalph  // nro de planilla si es de lph
		//				   as_numconlph  // nro de contrato si es de lph
		//				   as_suclph  // sucursal si es de lph
		//				   as_cuelph  // cuenta si es de lph
		//				   as_grulph  // grupo si es de lph
		//				   as_subgrulph  // subgrupo si es de lph
		//				   as_conlph  // contrato si es de lph
		//				   as_numactlph  // nro de archivo si es de lph
		//				   as_numofifps  // nro de oficina si es de fps
		//				   as_numfonfps // nro de fondo si es de fps
		//				   as_confps  // contrato si es de fps
		//				   as_nroplafps  // nro de plan
		//				   as_debcuelph  // debita al banco si es de lph
		//				   as_codagelph  // Código de Agencia
 		//				   as_apaposlph  // Apartado Postal
		//				   as_numconnom  // Número de convenio
		//				   as_pagtaqnom	 // Pago por Taquilla
		//                 as_ref  //  valor que indicara si se debe autoincrementar el número de refernecia
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que busca en la tabla sno_metodobanco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codmet, desmet, tipmet, codempnom, tipcuecrenom, tipcuedebnom, numplalph, numconlph, suclph, cuelph, ".
				"		grulph, subgrulph, conlph, numactlph, numofifps, numfonfps, confps, nroplafps, codofinom, debcuelph, ".
				"		codagelph, apaposlph, numconnom, pagtaqnom, nroref".
				"  FROM sno_metodobanco ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codmet='".$as_codmet."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_load_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_existe="TRUE";
				$as_codmet=$row["codmet"];
				$as_desmet=$row["desmet"];
				$as_tipmet=$row["tipmet"];
				$as_codempnom=$row["codempnom"];
				$as_codofinom=$row["codofinom"];
				$as_tipcuecrenom=$row["tipcuecrenom"];
				$as_tipcuedebnom=$row["tipcuedebnom"];
				$as_debcuelph=$row["debcuelph"];
				$as_numplalph=$row["numplalph"];
				$as_numconlph=$row["numconlph"];
				$as_suclph=$row["suclph"];
				$as_cuelph=$row["cuelph"];
				$as_grulph=$row["grulph"];
				$as_subgrulph=$row["subgrulph"];
				$as_conlph=$row["conlph"];
				$as_numactlph=$row["numactlph"];
				$as_numofifps=$row["numofifps"];
				$as_numfonfps=$row["numfonfps"];
				$as_confps=$row["confps"];
				$as_nroplafps=$row["nroplafps"];
				$as_codagelph=$row["codagelph"];
 				$as_apaposlph=$row["apaposlph"];
				$as_numconnom=$row["numconnom"];
				$as_pagtaqnom=$row["pagtaqnom"];
				$as_ref=$row["nroref"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_metodobanco
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_metodobanco($as_codmet,$as_tipmet,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_metodobanco
		//		   Access: public (sigesp_snorh_d_metodobanco)
		//	    Arguments: as_codmet  // código de método
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que busca en la tabla sno_metodobanco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
		        "  FROM sno_metodobanco ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codmet='".$as_codmet."'";
				
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_delete_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Método a Banco ".$as_codmet." de tipo ".$as_tipmet;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Método Banco fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_delete_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_metodobanco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_metodobanco_nomina($as_codmet,$as_tipmet,&$as_codempnom,&$as_codofinom,&$as_tipcuecrenom,&$as_tipcuedebnom,&$as_numconnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_metodobanco_nomina
		//		   Access: public (sigesp_sno_c_metodo_banco_1)
		//	    Arguments: as_codmet  // código de método
		//				   as_tipmet  // tipo de método
		//				   as_codempnom  // código de empresa si es de nómina
		//				   as_codofinom  // código de oficina si es de nómina
		//				   as_tipcuecrenom  // tipo cuenta crédito si es de nómina
		//				   as_tipcuedebnom  // tipo de cuenta débito si es de nómina
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que busca en la tabla sno_metodobanco los métodos de nómina y se trae el valor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codempnom, codofinom, tipcuecrenom, tipcuedebnom, numconnom ".
				"  FROM sno_metodobanco ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codmet='".$as_codmet."'".
				"   AND tipmet='".$as_tipmet."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_load_metodobanco_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codempnom=$row["codempnom"];
				$as_codofinom=$row["codofinom"];
				$as_tipcuecrenom=$row["tipcuecrenom"];
				$as_tipcuedebnom=$row["tipcuedebnom"];
				$as_numconnom=$row["numconnom"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_metodobanco_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_metodobanco_lph($as_desmet,$as_tipmet,&$as_debcuelph,&$as_numplalph,&$as_numconlph,&$as_suclph,&$as_cuelph,
									 &$as_grulph,&$as_subgrulph,&$as_conlph,&$as_numactlph,&$as_codagelph,&$as_apaposlph)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_metodobanco_lph
		//		   Access: public (sigesp_sno_c_metodo_lph)
		//	    Arguments: as_codmet  // código de método
		//				   as_tipmet  // tipo de método
		//				   as_debcuelph  // Débito a cuenta 
		//				   as_numplalph  // Número de planilla
		//				   as_numconlph  // número de contrato
		//				   as_suclph  // Sucursal
		//				   as_cuelph  // cuenta
		//				   as_grulph  // grupo
		//				   as_subgrulph  // subgrupo
		//				   as_conlph  // Contrato
		//				   as_numactlph  // Número de Archivo
		//				   as_codagelph  // Código de Agencia
		//				   as_apaposlph  // Apartado Postal
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que busca en la tabla sno_metodobanco los métodos de nómina y se trae el valor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT debcuelph, numplalph, numconlph, suclph, cuelph, grulph, subgrulph, conlph, numactlph, codagelph, apaposlph ".
				"  FROM sno_metodobanco ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND desmet='".$as_desmet."'".
				"   AND tipmet='".$as_tipmet."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_load_metodobanco_lph ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_debcuelph=$row["debcuelph"];
				$as_numplalph=$row["numplalph"];
				$as_numconlph=$row["numconlph"];
				$as_suclph=$row["suclph"];
				$as_cuelph=$row["cuelph"];
				$as_grulph=$row["grulph"];
				$as_subgrulph=$row["subgrulph"];
				$as_conlph=$row["conlph"];
				$as_numactlph=$row["numactlph"];
				$as_codagelph=$row["codagelph"];
				$as_apaposlph=$row["apaposlph"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_metodobanco_lph
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_campo_lph($as_desmet,$as_tipmet,$as_campo,$as_valor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_campo_lph
		//		   Access: private
		//	    Arguments: as_desmet  // descripión del método
		//				   as_tipmet  // tipo de método
		//				   as_campo  // campo que se quiere actualizar
		//				   as_valor  // valor que con el que se quiere actualizar
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_metodobanco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_metodobanco ".
				"   SET ".$as_campo."=".$as_valor." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND desmet='".$as_desmet."'".
				"   AND tipmet='".$as_tipmet."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_update_metodobanco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{	
			$this->io_sql->commit();
		}
		return $lb_valido;
	}// end function uf_update_campo_lph
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_metodobanco_fps($as_desmet,$as_tipmet,&$as_numofifps,&$as_numfonfps,&$as_confps,&$as_nroplafps)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_metodobanco_fps
		//		   Access: public (sigesp_sno_c_metodo_lph)
		//	    Arguments: as_codmet  // código de método
		//				   as_tipmet  // tipo de método
		//				   as_numofifps  // Débito a cuenta 
		//				   as_numfonfps  // Número de planilla
		//				   as_confps  // número de contrato
		//				   as_nroplafps  // Sucursal
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que busca en la tabla sno_metodobanco los métodos de fps y se trae el valor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numofifps, numfonfps, confps, nroplafps ".
				"  FROM sno_metodobanco ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND desmet='".$as_desmet."'".
				"   AND tipmet='".$as_tipmet."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Método Banco MÉTODO->uf_load_metodobanco_fps ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_numofifps=$row["numofifps"];
				$as_numfonfps=$row["numfonfps"];
				$as_confps=$row["confps"];
				$as_nroplafps=$row["nroplafps"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_metodobanco_fps
	//-----------------------------------------------------------------------------------------------------------------------------------	
}
?>