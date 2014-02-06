<?Php
/***************************************************************************************/
/*	Clase:	        Valuacion                                                         */    
/*  Fecha:          25/03/2006                                                         */        
/*	Autor:          GERARDO CORDERO		                                               */     
/***************************************************************************************/
class sigesp_sob_c_valuacion
{
 var $io_funcsob;
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $is_msg;

	function sigesp_sob_c_valuacion()
	{
	
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("class_folder/sigesp_sob_c_funciones_sob.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_datastore.php");
		$this->io_ds_spgcuentas=new class_datastore(); // Datastored de cuentas presupuestarias
		$this->io_ds_scgcuentas=new class_datastore(); // Datastored de cuentas contables
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcsob=new sigesp_sob_c_funciones_sob();
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$this->io_sql=new class_sql($io_connect);	
		$this->io_function=new class_funciones();
		$this->io_msg=new class_mensajes();
		$this->la_empresa=$_SESSION["la_empresa"];
		$this->ls_codemp=$this->la_empresa["codemp"];
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
	}
	
	
    function uf_select_valuacion($as_codval,$as_codcon)
	{
		/***************************************************************************************/
		/*	Function:	    uf_select_asignacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codval
				 FROM sob_valuacion
			     WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}
	
	function uf_guardar_valuacion(&$as_codval,$as_codcon,$ad_fecha,$ad_fecinival,$ad_fecfinval,$as_obsval,$ai_amoval,$as_obsamoval,$ai_amoantval,
								  $ai_amototval,$ai_amoresval,$ai_basimpval,$ai_montotval,$ai_subtotpar,$ai_totreten,$ai_subtot,$aa_seguridad,$as_chk)
	{
		/***************************************************************************************/
		/*	Function:	    uf_guardar_asignacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		/*$lb_act=true;
		$lb_execute=true;*/
		$lb_valido=false;
		if($as_chk=="C")
		{
			$lb_valido=$this->uf_update_valuacion($as_codval,$as_codcon,$ad_fecha,$ad_fecinival,$ad_fecfinval,$as_obsval,$ai_amoval,$as_obsamoval,$ai_amoantval,
								  				  $ai_amototval,$ai_amoresval,$ai_basimpval,$ai_montotval,$ai_subtotpar,$ai_totreten,$ai_subtot,$aa_seguridad,$as_chk);
		}
		else
		{
			$lb_valido=$this->uf_insert_valuacion(&$as_codval,$as_codcon,$ad_fecha,$ad_fecinival,$ad_fecfinval,$as_obsval,$ai_amoval,$as_obsamoval,$ai_amoantval,
								  				  $ai_amototval,$ai_amoresval,$ai_basimpval,$ai_montotval,$ai_subtotpar,$ai_totreten,$ai_subtot,$aa_seguridad,$as_chk);
		}
/*		$lb_flag=$this->uf_select_valuacion($as_codval,$as_codcon);
		$ld_amoval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoval);
		$ld_amoantval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoantval);
		$ld_amototval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amototval);
		$ld_amoresval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoresval);
		$ld_basimpval=$this->io_funcsob->uf_convertir_cadenanumero($ai_basimpval);
		$ld_montotval=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotval);
		$ld_subtotpar=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtotpar);
		$ld_totreten=$this->io_funcsob->uf_convertir_cadenanumero($ai_totreten);
		$ld_subtot=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtot);
		$ls_codemp=$this->la_empresa["codemp"];
		if(!$lb_flag)
		{	
			$ls_sql="INSERT INTO sob_valuacion(codemp,codval,codcon,fecha,fecinival,fecfinval,obsval,amoval,obsamoval,amoantval,amototval,amoresval,estval,".
					"						   basimpval,montotval,subtotpar,totreten,subtot)".
					" VALUES ('".$ls_codemp."','".$as_codval."','".$as_codcon."','".$ad_fecha."','".$ad_fecinival."','".$ad_fecfinval."','".$as_obsval."',".
					"		   ".$ld_amoval.",'".$as_obsamoval."',".$ld_amoantval.",".$ld_amototval.",".$ld_amoresval.",1,".$ld_basimpval.",".$ld_montotval.",".
					"          ".$ld_subtotpar.",".$ld_totreten.",".$ld_subtot.")";
			$this->io_msg->message("Registro Incluido");	 		
		}
		else
		{
		  $lb_val=$this->uf_select_estado($as_codval,&$ls_estasi);
		  
		  if(($ls_estasi==1)||($ls_estasi==6))
		   {
		    $lb_act=false; 
		    $ls_sql="UPDATE sob_valuacion ".
				    "   SET codcon='".$as_codcon."',fecha='".$ad_fecha."',fecinival='".$ad_fecinival."',fecfinval='".$ad_fecfinval."',obsval='".$as_obsval."',".
					"       amoval='".$ld_amoval."',obsamoval='".$as_obsamoval."',amoantval=".$ld_amoantval.",amototval=".$ld_amototval.",amoresval=".$ld_amoresval.",".
					"       basimpval=".$ld_basimpval.",montotval=".$ld_montotval.",subtotpar=".$ld_subtotpar.",totreten=".$ld_totreten.",subtot=".$ld_subtot.",".
					"       estval=6".
					" WHERE codemp='".$ls_codemp."'".
					"   AND codval='".$as_codval."'";	
		    $this->io_msg->message("Registro Actualizado");		    
		   }
		   else
		   {
		     $this->io_msg->message("Esta Valuacion no puede ser modificada");
		     $lb_execute=false;
		   } 				 
		}	
		if($lb_execute)
		{
		 
		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		  {			
			print "Error en metodo uf_guardar_valuacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  }
		  else
		  {
			if($li_row>0)
			{
				if($lb_act)
				{
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Valuación ".$as_codval.", de monto ".$ai_montotval." Asociada a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);  
				}
				else
				{
				  $ls_evento="UPDATE";
				  $ls_descripcion ="Actualizó la Valuación ".$as_codval.", de monto ".$ai_montotval." Asociada a la Empresa ".$ls_codemp;
				  $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				}
				$lb_valido=true;				
			}
		  }  
		}*/
	    return $lb_valido;
	}
	
	function uf_insert_valuacion(&$as_codval,$as_codcon,$ad_fecha,$ad_fecinival,$ad_fecfinval,$as_obsval,$ai_amoval,$as_obsamoval,$ai_amoantval,
								  $ai_amototval,$ai_amoresval,$ai_basimpval,$ai_montotval,$ai_subtotpar,$ai_totreten,$ai_subtot,$aa_seguridad,$as_chk)
	{
		/***************************************************************************************/
		/*	Function:	    uf_insert_valuacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          26/08/08                                                         */        
		/*	Autor:          		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ld_amoval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoval);
		$ld_amoantval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoantval);
		$ld_amototval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amototval);
		$ld_amoresval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoresval);
		$ld_basimpval=$this->io_funcsob->uf_convertir_cadenanumero($ai_basimpval);
		$ld_montotval=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotval);
		$ld_subtotpar=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtotpar);
		$ld_totreten=$this->io_funcsob->uf_convertir_cadenanumero($ai_totreten);
		$ld_subtot=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtot);
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("SOB","sob_valuacion","codval","SOBVAL",3,"","codcon",$as_codcon,&$as_codval);
		$ls_sql="INSERT INTO sob_valuacion(codemp,codval,codcon,fecha,fecinival,fecfinval,obsval,amoval,obsamoval,amoantval,amototval,amoresval,estval,".
				"						   basimpval,montotval,subtotpar,totreten,subtot)".
				" VALUES ('".$ls_codemp."','".$as_codval."','".$as_codcon."','".$ad_fecha."','".$ad_fecinival."','".$ad_fecfinval."','".$as_obsval."',".
				"		   ".$ld_amoval.",'".$as_obsamoval."',".$ld_amoantval.",".$ld_amototval.",".$ld_amoresval.",1,".$ld_basimpval.",".$ld_montotval.",".
				"          ".$ld_subtotpar.",".$ld_totreten.",".$ld_subtot.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_valuacion($as_codval,$as_codcon,$ad_fecha,$ad_fecinival,$ad_fecfinval,$as_obsval,$ai_amoval,$as_obsamoval,$ai_amoantval,
								  	$ai_amototval,$ai_amoresval,$ai_basimpval,$ai_montotval,$ai_subtotpar,$ai_totreten,$ai_subtot,$aa_seguridad,$as_chk);
			}
			else
			{
				$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_insert_solicitud ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Valuación ".$as_codval.", de monto ".$ai_montotval." Asociada a la Empresa ".$ls_codemp;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		//	$this->io_msg->message("Registro Incluido");	 		
			$lb_valido=true;
		}	
		return $lb_valido;
	}

 	function uf_update_valuacion($as_codval,$as_codcon,$ad_fecha,$ad_fecinival,$ad_fecfinval,$as_obsval,$ai_amoval,$as_obsamoval,$ai_amoantval,
								  $ai_amototval,$ai_amoresval,$ai_basimpval,$ai_montotval,$ai_subtotpar,$ai_totreten,$ai_subtot,$aa_seguridad,$as_chk)
	{
		/***************************************************************************************/
		/*	Function:	    uf_update_valuacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          26/08/08                                                         */        
		/*	Autor:          		                                               */     
		/***************************************************************************************/
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=false;
		$lb_val=$this->uf_select_estado($as_codval,&$ls_estasi);
		if(($ls_estasi==1)||($ls_estasi==6))
		{
			$ld_amoval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoval);
			$ld_amoantval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoantval);
			$ld_amototval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amototval);
			$ld_amoresval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoresval);
			$ld_basimpval=$this->io_funcsob->uf_convertir_cadenanumero($ai_basimpval);
			$ld_montotval=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotval);
			$ld_subtotpar=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtotpar);
			$ld_totreten=$this->io_funcsob->uf_convertir_cadenanumero($ai_totreten);
			$ld_subtot=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtot);
			$ls_codemp=$this->la_empresa["codemp"];
			$ls_sql="UPDATE sob_valuacion ".
					"   SET codcon='".$as_codcon."',fecha='".$ad_fecha."',fecinival='".$ad_fecinival."',fecfinval='".$ad_fecfinval."',obsval='".$as_obsval."',".
					"       amoval='".$ld_amoval."',obsamoval='".$as_obsamoval."',amoantval=".$ld_amoantval.",amototval=".$ld_amototval.",amoresval=".$ld_amoresval.",".
					"       basimpval=".$ld_basimpval.",montotval=".$ld_montotval.",subtotpar=".$ld_subtotpar.",totreten=".$ld_totreten.",subtot=".$ld_subtot.",".
					"       estval=6".
					" WHERE codemp='".$ls_codemp."'".
					"   AND codval='".$as_codval."'";	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{			
				$lb_valido=false;
				$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_insert_solicitud ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la Valuación ".$as_codval.", de monto ".$ai_montotval." Asociada a la Empresa ".$ls_codemp;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			//	$this->io_msg->message("Registro Incluido");	 		
				$lb_valido=true;
			}
		}
		else
		{
			$this->io_msg->message("Esta Valuacion no puede ser modificada");
		} 				 
		return $lb_valido;
	}

    function uf_select_partidasasignadas($as_codcon,&$aa_data,&$ai_rows)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT apo.codpar,p.nompar,u.nomuni,apo.preparasi,apo.prerefparasi,(apo.canparobrasi-apo.canasipareje)+apo.canvarpar AS canxeje,".
				"       apo.canasipareje,c.codasi,a.codobr".
				"  FROM sob_contrato c, sob_asignacion a, sob_asignacionpartidaobra apo, sob_partida p, sob_unidad u".
				" WHERE c.codemp='".$ls_codemp."'".
				"   AND a.codemp='".$ls_codemp."'".
				"   AND apo.codemp='".$ls_codemp."'".
				"   AND p.codemp='".$ls_codemp."'".
				"   AND u.codemp='".$ls_codemp."'".
				"   AND c.codcon='".$as_codcon."'".
				"   AND c.codasi=a.codasi".
				"   AND a.codasi=apo.codasi".
				"   AND p.codpar=apo.codpar".
				"   AND u.coduni=p.coduni";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}	
   	function uf_select_allpartidas($as_codval,$as_codasi,&$aa_data,&$ai_rows)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT apo.codemp,apo.codasi,apo.codobr,apo.codpar,p.nompar,u.nomuni,(apo.canparobrasi-apo.canasipareje)+apo.canvarpar AS canxeje,apo.canasipareje,apo.prerefparasi,apo.preparasi,vp.canvalpar
		         FROM sob_asignacionpartidaobra apo LEFT JOIN sob_valuacionpartida vp ON ((apo.codpar=vp.codpar) AND (apo.codemp=vp.codemp) AND (apo.codasi=vp.codasi) AND vp.codval='".$as_codval."'),sob_partida p,sob_unidad u 
				 WHERE apo.codasi='".$as_codasi."' AND apo.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND u.codemp='".$ls_codemp."' AND apo.codpar=p.codpar AND p.coduni=u.coduni ORDER BY apo.codpar ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
  
   function uf_select_partidas($as_codval,$as_codcon,&$aa_data,&$ai_rows)
    {
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=true;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT vp.codemp,vp.codval,vp.codcon,vp.codpar,p.nompar,u.nomuni,vp.canvalpar,(apo.canparobrasi-apo.canasipareje)+apo.canvarpar AS canxeje,".
				"       apo.canasipareje".
				"  FROM sob_valuacionpartida vp, sob_partida p, sob_unidad u,sob_asignacionpartidaobra apo".
				" WHERE apo.codemp='".$ls_codemp."'".
				"   AND u.codemp='".$ls_codemp."'".
				"   AND p.codemp='".$ls_codemp."'".
				"   AND vp.codemp='".$ls_codemp."'".
				"   AND vp.codval='".$as_codval."'".
				"   AND vp.codcon='".$as_codcon."'".
				 "  AND vp.codpar=apo.codpar".
				 "  AND vp.codpar=p.codpar".
				 "  AND p.coduni=u.coduni";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	function uf_guardar_dtpartidas($as_codval,$as_codcon,$as_codobr,$as_codpar,$as_codasi,$ad_cantidad,$ad_prerefparasi,$ad_preparval,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_guardar_dtpartidas                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ld_cant=$this->io_funcsob->uf_convertir_cadenanumero($ad_cantidad);
		$ld_prerefparasi=$this->io_funcsob->uf_convertir_cadenanumero($ad_prerefparasi);
		$ld_preparval=$this->io_funcsob->uf_convertir_cadenanumero($ad_preparval);
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="INSERT INTO sob_valuacionpartida (codemp,codobr,codpar,codasi,codval,codcon,canvalpar,prerefparasi,preparval)
		         VALUES ('".$ls_codemp."','".$as_codobr."','".$as_codpar."','".$as_codasi."','".$as_codval."','".$as_codcon."',".$ld_cant.",".
				 "        ".$ld_prerefparasi.",".$ld_preparval.")";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_dt".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			     /************    SEGURIDAD    **************/		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Partida ".$as_codpar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/	
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	
		
	function uf_delete_dtpartidas($as_codval,$as_codcon,$as_codpar,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_valuacionpartida".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codval='".$as_codval."'".
				"   AND codcon='".$as_codcon."'".
				"   AND codpar='".$as_codpar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print"Error en metodo eliminar_dtpartidas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Partida ".$as_codpar.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
		}
		return $lb_valido;	
	}
	
	
	function uf_update_partidavaluacion($as_codval,$as_codcon,$ad_canpar,$aa_seguridad)
	{
	
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ld_cant=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
		$ls_sql="UPDATE sob_valuacionpartida
				 SET canvalpar='".ld_cant."'
				 WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."' AND codpar='".$as_codpar."'";		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_partidavaluacion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad ejecutada de la partida ".$as_codpar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();	
			}
		
		}		
		return $lb_valido;
	  }	
	function uf_update_cantidaejecutada($as_codasi,$as_codpar,$ad_canpar,$ad_caneje,$ad_oldcan,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ld_teje=0;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_canp=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
		if($ad_oldcan!=0)
		{
			$ld_tejeA=$ad_caneje-$ad_oldcan; 
			$ld_teje=$ld_tejeA+$ad_canp;
		}
		else
		{
			$ld_teje=$ad_caneje+$ad_canp; 
		}
		$ls_sql="UPDATE sob_asignacionpartidaobra".
				"   SET canasipareje=".$ld_teje."".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codasi='".$as_codasi."'".
				"   AND codpar='".$as_codpar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_cantidadejecutada ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			    /*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad ejecutada de la partida ".$as_codpar.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	 function uf_update_Actcantidaejecutada($as_codasi,$as_codpar,$ad_canpar,$ad_caneje,$aa_seguridad)
	 {
	
		$lb_valido=false;
		$ld_teje=0;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_canp=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
	    $ld_teje=$ad_caneje-$ad_canp; 
	
		$ls_sql="UPDATE sob_asignacionpartidaobra
				SET canasipareje=".$ld_teje."
				WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."' AND codpar='".$as_codpar."'";		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_Actcantidadejecutada ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
				  /*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad ejecutada de la partida ".$as_codpar.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
								$this->io_sql->commit();
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_dtpartidas($as_codval,$as_codcon,$aa_partidasnuevas,$ai_totalfilas,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_update_dtpartidas                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=false;
		$lb_valido=$this->uf_select_partidas($as_codval,$as_codcon,$la_partidasviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		if($lb_valido)
		{
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				$lb_existe=false;
				$lb_update=false;
				for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
				{
					if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codval"][$li_j] == $as_codval) && ($la_partidasviejas["codcon"][$li_j] == $as_codcon) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
					{
						if($la_partidasviejas["canvalpar"][$li_j] != $this->io_funcsob->uf_convertir_cadenanumero($aa_partidasnuevas["cant"][$li_i]))
						{
							$lb_update=true;
						}
						$lb_existe = true;
					}				
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_guardar_dtpartidas($as_codval,$as_codcon,$aa_partidasnuevas["codobr"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["cant"][$li_i],$aa_partidasnuevas["preref"][$li_i],$aa_partidasnuevas["preval"][$li_i],$aa_seguridad);
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_cantidaejecutada($aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],$aa_partidasnuevas["canteje"][$li_i],0.000,$aa_seguridad);
					}
				}
				if($lb_update)
				{
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_partidaasignacion($as_codasi,$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],$aa_partidasnuevas["pre"][$li_i],$aa_seguridad);
					}
					/*if($lb_valido)INVALIDA
					{
						$this->uf_update_cantidaasignada($aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],$aa_partidasnuevas["canteje"][$li_i],$la_partidasviejas["canparval"][$li_i],$aa_seguridad);
					}*/
				}
			}
			if($lb_valido)
			{
				for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
				{
					$lb_existe=false;
					for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
					{
						if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codval"][$li_j] == $as_codval) && ($la_partidasviejas["codcon"][$li_j] == $as_codcon) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
						{
						  $lb_existe = true;
						}				
						
					}
					if (!$lb_existe)
					{
						$lb_valido=$this->uf_delete_dtpartidas($as_codval,$as_codcon,$la_partidasviejas["codpar"][$li_j],$aa_seguridad);
					}
				}
			}
		}
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_retenciones($as_codval,$as_codcon,&$aa_data,&$ai_rows)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_select_retenciones                                               */    
     /* Access:			public                                                              */ 
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */ 
	 /*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion        */    
	 /*  Fecha:          25/03/2006                                                         */        
	 /*	Autor:          GERARDO CORDERO		                                                */     
	 /***************************************************************************************/
		$lb_valido=true;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT rvc.codemp,rvc.codcon,rvc.codval,rvc.codded,rvc.monret,rvc.montotret,d.dended,d.sc_cuenta,d.monded,d.formula".
				"  FROM sob_retencionvaluacioncontrato rvc, sigesp_deducciones d".
				" WHERE rvc.codemp='".$ls_codemp."'".
				"   AND d.codemp='".$ls_codemp."'".
				"   AND rvc.codded=d.codded".
				"   AND rvc.codcon='".$as_codcon."'".
				"   AND rvc.codval='".$as_codval."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select retenciones".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	
	
	function uf_guardar_retenciones($as_codval,$as_codcon,$as_codded,$ai_monret,$ai_montotret,$aa_seguridad)             
    { 
	    /***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ld_monret=$this->io_funcsob->uf_convertir_cadenanumero($ai_monret);
		$ld_montotret=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotret);
		$ls_sql="INSERT INTO sob_retencionvaluacioncontrato (codemp,codval,codcon,codded,monret,montotret)".
		         "  VALUES ('".$ls_codemp."','".$as_codval."','".$as_codcon."','".$as_codded."','".$ld_monret."','".$ld_montotret."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_retenciones".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			    /************    SEGURIDAD    **************/		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Retencion ".$as_codded.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	
	
	function uf_delete_retenciones($as_codval,$as_codcon,$as_codded,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_retencionvaluacioncontrato".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codval='".$as_codval."'".
				"   AND codcon='".$as_codcon."'".
				"   AND codded='".$as_codded."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print"Error en metodo eliminar_retencion".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Retencion ".$as_codded.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/	
			$lb_valido=true;
		}
		return $lb_valido;	
	}
	
	
	function uf_update_retencion($as_codval,$as_codcon,$as_codded,$ai_monret,$ai_montotret,$aa_seguridad)
	{
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ld_monret=$this->io_funcsob->uf_convertir_cadenanumero($ai_monret);
		$ld_montotret=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotret);
		$ls_sql="UPDATE sob_retencionvaluacioncontrato".
				"   SET monret=".$ld_monret.", montotret=".$ld_montotret."".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codval='".$as_codval."'".
				"   AND codded='".$as_codded."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_retencion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la retencion ".$as_codpar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	  }

	function uf_update_retenciones($as_codval,$as_codcon,$aa_retencionesnuevas,$ai_totalfilas,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$lb_update=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=$this->uf_select_retenciones($as_codval,$as_codcon,$la_retencionesviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		if($lb_valido)
		{
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				$lb_existe=false;
				for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
				{
					if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codval"][$li_j] == $as_codval) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) && ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codret"][$li_i]) )
					{
						if($la_retencionesviejas["monret"][$li_j] != $aa_retencionesnuevas["monto"][$li_i])
						{
							$lb_update=true;
						}
						$lb_existe = true;
					}				
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_guardar_retenciones($as_codval,$as_codcon,$aa_retencionesnuevas["codret"][$li_i],$aa_retencionesnuevas["monret"][$li_i],$aa_retencionesnuevas["montotret"][$li_i],$aa_seguridad);
				}
				if($lb_valido)
				{ 
					if ($lb_update)
					{
						$lb_valido=$this->uf_update_retencion($as_codval,$as_codcon,$aa_retencionesnuevas["codret"][$li_i],$aa_retencionesnuevas["monret"][$li_i],$aa_retencionesnuevas["montotret"][$li_i],$aa_seguridad);
					}
				}
			}
			if($lb_valido)
			{
				for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
				{
					$lb_existe=false;
					for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
					{
						if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codval"][$li_j] == $as_codval) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) && ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codret"][$li_i]) )
						{
							$lb_existe = true;
						}				
					}
					if (!$lb_existe)
					{
						$lb_valido=$this->uf_delete_retenciones($as_codval,$as_codcon,$la_retencionesviejas["codded"][$li_j],$aa_seguridad);
					}
				}			
			}
		}
		return $lb_valido;
	}
    function uf_select_cargos($as_codval,$as_codcon,&$aa_data,&$ai_rows)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=true;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  v.codemp,v.codcon,v.codval,v.codcar,c.dencar,v.monto,v.formula".
				"  FROM sob_cargovaluacion v, sigesp_cargos c ".
				" WHERE v.codemp='".$ls_codemp."'".
				"   AND c.codemp='".$ls_codemp."'".
				"   AND v.codval='".$as_codval."'".
				"   AND v.codcon='".$as_codcon."'".
				"   AND v.codcar=c.codcar";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	function uf_guardar_dtcargos($as_codval,$as_codcon,$as_codcar,$as_basimp,$as_monto,$as_formula,$as_codestpro,$as_spgcuenta,$as_estcla,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_monto=$this->io_funcsob->uf_convertir_cadenanumero($as_monto);
		$ad_basimp=$this->io_funcsob->uf_convertir_cadenanumero($as_basimp);
		$ls_sql="INSERT INTO sob_cargovaluacion (codemp,codcar,codval,codcon,basimp,monto,formula,codestprog,spg_cuenta,estcla)
		         VALUES ('".$ls_codemp."','".$as_codcar."','".$as_codval."','".$as_codcon."',".$ad_basimp.",".$ad_monto.",".
				 "		 '".$as_formula."','".$as_codestpro."','".$as_spgcuenta."','".$as_estcla."')";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_dt".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
		    	/************    SEGURIDAD    **************/		 
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cargo ".$as_codcar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			 	$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	}	
	function uf_delete_dtcargos($as_codval,$as_codcon,$as_codcar,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_cargovaluacion".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codval='".$as_codval."'".
				"   AND codcon='".$as_codcon."'".
				"   AND codcar='".$as_codcar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print"Error en metodo eliminar_dtpartidas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
		    /*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Cargo ".$as_codcar.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/	
			$lb_valido=true;
		}
		return $lb_valido;	
	}
	function uf_update_dtcargos($as_codval,$as_codcon,$as_basimp,$aa_cargosnuevos,$ai_totalfilas,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=$this->uf_select_cargos($as_codval,$as_codcon,$la_cargosviejos,$li_totalviejos);
		$li_totalnuevas=$ai_totalfilas;
		if($lb_valido)
		{
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				$lb_existe=false;
				for ($li_j=1;$li_j<=$li_totalviejos;$li_j++)
				{
					if( ($la_cargosviejos["codemp"][$li_j] == $ls_codemp) && ($la_cargosviejos["codval"][$li_j] == $as_codval) && ($la_cargosviejos["codcon"][$li_j] == $as_codcon) && ($la_cargosviejos["codcar"][$li_j] == $aa_cargosnuevos["codcar"][$li_i]) )
					{
						
						$lb_existe = true;
					}				
					
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_guardar_dtcargos($as_codval,$as_codcon,$aa_cargosnuevos["codcar"][$li_i],$as_basimp,$aa_cargosnuevos["monto"][$li_i],$aa_cargosnuevos["formula"][$li_i],$aa_cargosnuevos["codestpro"][$li_i],$aa_cargosnuevos["spgcuenta"][$li_i],$aa_cargosnuevos["estcla"][$li_i],$aa_seguridad);
				}
			}
			if($lb_valido)
			{
				for ($li_j=1;$li_j<=$li_totalviejos;$li_j++)
				{
					$lb_existe=false;
					for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
					{
						if( ($la_cargosviejos["codemp"][$li_j] == $ls_codemp) && ($la_cargosviejos["codval"][$li_j] == $as_codval) && ($la_cargosviejos["codcon"][$li_j] == $as_codcon) &&  ($la_cargosviejos["codcar"][$li_j] == $aa_cargosnuevos["codcar"][$li_i]) )
						{
						  $lb_existe = true;
						}				
					}
					if (!$lb_existe)
					{
						$lb_valido=$this->uf_delete_dtcargos($as_codval,$as_codcon,$la_cargosviejos["codcar"][$li_j],$aa_seguridad);
					}
				}			
			}
		}
		return $lb_valido;
	}
    function uf_update_estado($as_codval,$ai_estatus,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="UPDATE sob_valuacion
		         SET estval=".$ai_estatus."
				 WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo uf_change_estatus_asi".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
		    if($ai_estatus==3)
			{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Anulo la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			}
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;		
	}
	function uf_select_estado($as_codval,&$estasi)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT estval
		         FROM  sob_valuacion
		         WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."'";		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$estasi=$la_row["estval"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
    }
    function uf_select_anticipos($as_codcon,&$ls_anti)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT SUM(a.monto) as totant
				FROM  sob_contrato c, sob_anticipo a
				WHERE c.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."' AND c.codcon='".$as_codcon."' AND a.codcon=c.codcon";
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$ls_anti=$la_row["totant"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
   }
function uf_select_variaciones($as_codcon,&$ls_vari,$as_tipvar)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT SUM(vc.monto) as variacion
FROM  sob_contrato c, sob_variacioncontrato vc
WHERE c.codemp='".$ls_codemp."' AND vc.codemp='".$ls_codemp."' AND c.codcon='".$as_codcon."' AND vc.codcon=c.codcon and (vc.tipvar=".$as_tipvar.")";
				 		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$ls_vari=$la_row["variacion"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
   }
   function uf_select_contrato($as_codcon,&$aa_data)
   {
		/***************************************************************************************/
		/*	Function:	    uf_select_contrato                                                 */    
		/*	Description:	Funcion que se encarga de buscar en bd los datos de un contrato    */    
		/*  Fecha:          17/04/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT co.feccon,co.monto,co.estcon,ai.puncueasi,ob.desobr,co.codasi
                 FROM sob_contrato co,sob_asignacion ai,sob_obra ob
                 WHERE co.codemp='".$ls_codemp."' AND ai.codemp='".$ls_codemp."' AND ob.codemp='".$ls_codemp."' AND co.codcon='".$as_codcon."' AND ai.codasi=co.codasi AND ai.codobr=ob.codobr";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$aa_data="";
			}
		}
		return $lb_valido;
	}
   function uf_select_valanterior($as_codcon,$as_codval,&$aa_data)
   {
		/***************************************************************************************/
		/*	Function:	    uf_select_contrato                                                 */    
		/*	Description:	Funcion que se encarga de buscar en bd los datos de un contrato    */    
		/*  Fecha:          17/04/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$li_codval=$as_codval-1;
		$ls_codval=$this->io_function->uf_cerosizquierda($li_codval,3);
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT amoval,amototval,amoresval
                 FROM sob_valuacion 
                 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codval='".$ls_codval."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$aa_data="";
			}
		}
		return $lb_valido;
	}
   function uf_select_newcodigo($as_codcon,&$as_codval)
   {
		/***************************************************************************************/
		/*	Function:	    uf_select_contrato                                                 */    
		/*	Description:	Funcion que se encarga de asignar un nuevo codigo a la valuacion   */    
		/*  Fecha:          17/04/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codval
                 FROM sob_valuacion 
                 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' ORDER BY codval DESC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$li_codval=$row["codval"];
				settype($li_codval,'int');
				$li_codval=$li_codval+1;
				settype($li_codval,'string');
		        $as_codval=$this->io_function->uf_cerosizquierda($li_codval,3);
			}
			else
			{
				$as_codval="001";
			}
		}
		return $lb_valido;
	}
		function uf_ejecucion_financiera($as_codcon,&$ad_total)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_ejecucion_financiera
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar el monto total de la ejecucion financiera de un contrato
		//  Fecha:          13/06/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ad_total=0;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sum(subtotpar) as total
				 FROM sob_valuacion
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";
				 //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select montoanticipocontratos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido=false;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$ad_total=$aa_data["total"][1];
			}
			else
				$ad_total=0;			
		}	
		return $lb_valido;
	}
	function uf_amortizacion_anticipo($as_codcon,&$ad_amortizacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_amortizacion_anticipo
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar la amortizacion total acumulada para
		//					un contrato
		//  Fecha:          14/06/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ad_amortizacion=0;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT amototval 
				FROM sob_valuacion 
				WHERE fecfinval IN 
				(SELECT MAX(fecfinval) 
				FROM sob_valuacion 
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."')";
				 //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_amortizacion_anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido=false;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$ad_amortizacion=$aa_data["amototval"][1];
			}
			else
				$ad_amortizacion=0;			
		}	
		return $lb_valido;
	}

function uf_select_asignacionpartidaobra ($as_codasi,&$aa_data)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_asignacionpartidaobra
	// Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de retornar las partidas por asignacion
	//  Fecha:          22/03/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT * 
			FROM sob_asignacionpartidaobra 
			WHERE codemp='$ls_codemp' AND codasi='$as_codasi'";
	//print $ls_sql;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select_asignacionpartidaobra".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$aa_data=$this->io_sql->obtener_datos($rs_data);
		}
		else
		{
			$aa_data="";
			$lb_valido=0;
		}
	}
	return $lb_valido;
}

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tipodeduccion($as_codded)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_tipodeduccion
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar el tipo de deduccion
		//  Fecha:          22/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$li_iva=0;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT iva ".
				"  FROM sigesp_deducciones ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codded='".$as_codded."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select_asignacionpartidaobra".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_iva=$row["iva"];
			}
		}
		return $li_iva;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_contabilizado($as_codasi)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_contabilizado
		//		   Access: private
		//	    Arguments: $as_codasi // Codigo de Asignacion
 		//	      Returns: $lb_valido Indica si la asignacion esta contabilizada
		//	  Description: Verifica la contabilizacion de la asignacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_sql="SELECT estspgscg". 
				"  FROM sob_asignacion". 
				" WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"   AND sob_asignacion.codasi='".$as_codasi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select cuentacontable".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_estspgscg=$row["estspgscg"];
				if($ls_estspgscg==1)
				{
					$lb_valido=true;		
				}
			}			
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_recepcion_documentos($as_numrecdoc,$as_codtipdoc,$as_conval,$ad_fecval,$ai_montotval,$ai_totreten,$ai_totconcar,$as_codcon,
											  $ai_basimpval,$as_codasi,$as_codval,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documentos
		//		   Access: private
		//	    Arguments: $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_codtipdoc	// codigo de tipo de documento
		//				   $as_conval	    // Codigo de Valuacion
		//				   $ad_fecval  		// Fecha de Valuacion
		//				   $ai_montotval  	// Monto total de valuacion
		//				   $ai_totretten    // Monto total de retenciones
		//				   $ai_totcargos    // Monto total de cargos
		//				   $as_codcon       // Codigo del contrato
		//				   $ai_basimpval    // Base Imponible Valuacion
		//				   $as_codasi       // Codigo de Asignacion
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
        $ls_tipodestino= "P";			
		$ls_cedbene= "----------";	
		$ls_codpro=$this->uf_select_contratista($as_codcon);
		$li_totcargos=($ai_totconcar-$ai_basimpval);
		$lb_existe=$this->uf_select_recepcion($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro);
		if(!$lb_existe)
		{
			$ad_fecval= $this->io_function->uf_convertirdatetobd($ad_fecval);
			$this->io_sql->begin_transaction();	
			$ls_sql="INSERT INTO cxp_rd (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,dencondoc,fecemidoc, fecregdoc, fecvendoc,".
					"                    montotdoc, mondeddoc,moncardoc,tipproben,numref,estprodoc,procede,estlibcom,estaprord,".
					"                    fecaprord,usuaprord,estimpmun,codcla)".
					"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$ls_cedbene."',".
					"             '".$ls_codpro."','".$as_conval."','".$ad_fecval."','".$ad_fecval."','".$ad_fecval."',
					"               .$ai_montotval.",".$ai_totreten.",".$li_totcargos.",'".$ls_tipodestino."','".$as_numrecdoc."','R','SOBCON',0,0,'1900-01-01','OBRAS',0,'--')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_procesar_recepcion_documentos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_dt_recepcion_documento($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$ai_basimpval,$as_codcon,$as_codasi,$as_codval);
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_generacion_rd($as_codcon,$as_conval,$aa_seguridad);
				}
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="PROCESS";
					$ls_descripcion="Generó la Recepción de Documento de la llave contrato-valuacion <b>".$as_numrecdoc."</b>";
					$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													  $aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
			}
			if($lb_valido)
			{
				$this->io_sql->commit();	
				$this->io_msg->message("La Recepcion de Documentos se Genero con Exito.");
			}
			else
			{
				$this->io_sql->rollback();	
				$this->io_msg->message("No se Genero la Recepcion de Documentos");
			}
		}
		else
		{
			$this->io_msg->message("La Recepcion de Documentos ya Existe.");
			$lb_valido=false;
		}
		return $lb_valido;
	}  // end function uf_procesar_recepcion_documentos
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_generacion_rd($as_codcon,$as_conval,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documentos
		//		   Access: private
		//	    Arguments: $as_conant	    // descripcion del documento
		//				   $as_codcon       // Codigo del contrato
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="UPDATE sob_valuacion".
				"   SET estgenrd='1'".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcon='".$as_codcon."'".
				"   AND codval='".$as_conval."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
           	$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_update_estatus_generacion_rd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$this->io_sql->rollback();
			
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el estatus de generacion de R.D. de la Valuacion ".$as_conval." del Contrato ".$as_codcon." Asociado a la Empresa ".$this->ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contratista($as_codcon)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_contratista
		//		   Access: private
		//	    Arguments: $as_codcon    // codigo de contrato
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sob_asignacion.cod_pro". 
				"  FROM sob_contrato , sob_asignacion". 
				" WHERE sob_contrato.codemp='".$this->ls_codemp."'".
				"   AND sob_contrato.codcon='".$as_codcon."'".
				"   AND sob_contrato.codemp=sob_asignacion.codemp".
				"   AND sob_contrato.codasi=sob_asignacion.codasi";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_contratista ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$la_data=$this->io_sql->obtener_datos($rs_data);
				$ls_codpro=$row["cod_pro"];
			}			
		}	
		return $ls_codpro;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_recepcion
		//		   Access: private
		//	    Arguments: $as_numrecdoc // Numero de Recepcion de Documentos
		//                 $as_codtipdoc // Codigo de Tipo de Documento
		//                 $as_cedbene   // Cedula de Beneficiario
		//                 $as_codpro    // Codigo de Proveedor
 		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Verifica la existencia de una Recepcion de Documentos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT numrecdoc". 
				"  FROM cxp_rd". 
				" WHERE cxp_rd.codemp='".$ls_codemp."'".
				"   AND cxp_rd.numrecdoc='".$as_numrecdoc."'".
				"   AND cxp_rd.codtipdoc='".$as_codtipdoc."'".
				"   AND cxp_rd.cod_pro='".$as_codpro."'".
				"   AND cxp_rd.ced_bene='".$as_cedbene."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_recepcion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;		
			}			
		}	
		return $lb_existe;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dt_recepcion_documento($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_basimpval,$as_codcon,$as_codasi,$as_codval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documentos
		//		   Access: private
		//	    Arguments: $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_cedbene   	// Cedula de Beneficiario
		//				   $as_codpro   	// Codigo de proveedor
		//				   $as_conval	    // Codigo de Valuacion
		//				   $ad_fecval  		// Fecha de Valuacion
		//				   $ai_montotval  	// Monto total de valuacion
		//				   $ai_totretten    // Monto total de retenciones
		//				   $ai_totcargos    // Monto total de cargos
		//				   $as_codcon       // Codigo del contrato
		//				   $ai_basimpval    // Base Imponible Valuacion
		//				   $as_codasi       // Codigo de Asignacion
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True 
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_obtener_estructura($as_codasi,$as_codestpro,$as_spgcuenta,$as_estcla);
		if($lb_valido)
		{
			$ls_sql="INSERT INTO cxp_rd_spg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro,".
					"                        spg_cuenta, monto, codfuefin,estcla)".
					"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
					"             '".$as_codpro."','SOBCON','".$as_codcon."','".$as_codestpro."','".$as_spgcuenta."',".
					"               ".$ai_basimpval.",'--','".$as_estcla."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_procesar_recepcion_documentos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
				$ls_sccuenta=$this->uf_select_cuentacontable($as_spgcuenta,$as_codestpro,$as_estcla);
				$this->io_ds_scgcuentas->insertRow("sccuenta",$ls_sccuenta);
				$this->io_ds_scgcuentas->insertRow("debhab","D");
				$this->io_ds_scgcuentas->insertRow("monto",$ai_basimpval);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codval,$as_codcon);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codval,$as_codcon);
			}
		}
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_estructura($as_codasi,&$as_codestpro,&$as_spgcuenta,&$as_estcla)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_estructura
		//		   Access: private
		//	    Arguments: $as_codasi    // codigo de asignacion
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene la estructura de la asignacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_codestpro="";		
		$as_spgcuenta="";
		$lb_valido=false;		
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,estcla". 
				"  FROM sob_cuentasasignacion". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codasi='".$as_codasi."'".
				"   AND spg_cuenta NOT IN (SELECT spg_cuenta FROM sigesp_cargos GROUP BY spg_cuenta);";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_obtener_estructura ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			return false;		
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codestpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$as_spgcuenta=$row["spg_cuenta"];
				$as_estcla=$row["estcla"];
				$lb_valido=true;
			}			
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codval,$as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_estructura
		//		   Access: private
		//	    Arguments: $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_cedbene   	// Cedula de Beneficiario
		//				   $as_codval   	// Codigo de valuacion
		//				   $as_codcon   	// Codigo de proveedor
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene la estructura de la asignacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_sql="SELECT codcar,basimp,monto,formula,codestprog,spg_cuenta,estcla". 
				"  FROM sob_cargovaluacion". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codval='".$as_codval."'".
				"   AND codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_obtener_estructura ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			return false;		
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codcar=$row["codcar"];
				$li_basimp=$row["basimp"];
				$li_monto=$row["monto"];
				$ls_formula=$row["formula"];
				$ls_codestpro=$row["codestprog"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_estcla=$row["estcla"];
				$ls_porcar=$this->uf_select_porcar($ls_codcar);
				$ls_sql="INSERT INTO cxp_rd_cargos (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codcar, procede_doc, numdoccom,".
						"                           monobjret, monret, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5,".
						"							spg_cuenta, porcar, formula,estcla)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','".$ls_codcar."','SOBCON','".$as_codcon."',".$li_basimp.",".$li_monto.",".
						"             '".substr($ls_codestpro,0,25)."','".substr($ls_codestpro,25,25)."','".substr($ls_codestpro,50,25)."',".
						"             '".substr($ls_codestpro,75,25)."','".substr($ls_codestpro,100,25)."','".$ls_spgcuenta."','".$ls_porcar."',".
						"             '".$ls_formula."','".$ls_estcla."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{print $this->io_sql->message;
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_cargos_I ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{
					$this->io_ds_spgcuentas->insertRow("spgcuenta",$ls_spgcuenta);
					$this->io_ds_spgcuentas->insertRow("codestpro",$ls_codestpro);
					$this->io_ds_spgcuentas->insertRow("monto",$li_monto);
					$this->io_ds_spgcuentas->insertRow("basimp",$li_basimp);
					$this->io_ds_spgcuentas->insertRow("estcla",$ls_estcla);
				}
				$lb_valido=true;
			}	
			$this->io_ds_spgcuentas->group_by(array('0'=>'spgcuenta','1'=>'codestpro','2'=>'estcla'),array('0'=>'monto','1'=>'basimp'),'monto');
			$li_totrow=$this->io_ds_spgcuentas->getRowCount('spgcuenta');	
			for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
			{
				$ls_spgcuenta=$this->io_ds_spgcuentas->getValue('spgcuenta',$li_fila);
				$ls_codestpro=$this->io_ds_spgcuentas->getValue('codestpro',$li_fila);
				$ls_estcla=$this->io_ds_spgcuentas->getValue('estcla',$li_fila);
				$li_monto=$this->io_ds_spgcuentas->getValue('monto',$li_fila);
				$li_basimp=$this->io_ds_spgcuentas->getValue('basimp',$li_fila);
				
				$ls_sql="INSERT INTO cxp_rd_spg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro,".
						"                        spg_cuenta, monto, codfuefin,estcla)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','".$ls_codestpro."','".$ls_spgcuenta."',".
						"               ".$li_monto.",'--','".$ls_estcla."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_cargos_II ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
				else
				{
					$ls_sccuenta=$this->uf_select_cuentacontable($ls_spgcuenta,$ls_codestpro,$ls_estcla);
					$this->io_ds_scgcuentas->insertRow("sccuenta",$ls_sccuenta);
					$this->io_ds_scgcuentas->insertRow("debhab","D");
					$this->io_ds_scgcuentas->insertRow("monto",$li_monto);
					$lb_valido=true;
				}
			}
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_porcar($as_codcar)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_porcar
		//		   Access: private
		//	    Arguments: $as_codcar    // codigo de cargo
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_porcar="";		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT porcar". 
				"  FROM sigesp_cargos". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcar='".$as_codcar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_porcar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_porcar=$row["porcar"];
			}			
		}	
		return $ls_porcar;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_porded($as_codded,&$as_sccuenta)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_porded
		//		   Access: private
		//	    Arguments: $as_codcar    // codigo de cargo
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_porded="";		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT porded,sc_cuenta". 
				"  FROM sigesp_deducciones". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_porded ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_porded=$row["porded"];
				$as_sccuenta=$row["sc_cuenta"];
			}			
		}	
		return $ls_porded;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentacontable($as_spgcuenta,$as_codestpro,$as_estcla)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentacontable
		//		   Access: private
		//	    Arguments: $as_spgcuenta // Cuenta Presupuestaria
		//				   $as_codestpro // Codigo de estructura programatica
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sccuenta="";		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sc_cuenta". 
				"  FROM spg_cuentas". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND spg_cuenta='".$as_spgcuenta."'".
				"   AND estcla='".$as_estcla."'".
				"   AND codestpro1='".substr($as_codestpro,0,25)."'".
				"   AND codestpro2='".substr($as_codestpro,25,25)."'".
				"   AND codestpro3='".substr($as_codestpro,50,25)."'".
				"   AND codestpro4='".substr($as_codestpro,75,25)."'".
				"   AND codestpro5='".substr($as_codestpro,100,25)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_cuentacontable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_sccuenta=$row["sc_cuenta"];
			}			
		}	
		return $ls_sccuenta;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codval,$as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_deducciones
		//		   Access: private
		//	    Arguments: $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_cedbene   	// Cedula de Beneficiario
		//				   $as_codval   	// Codigo de valuacion
		//				   $as_codcon   	// Codigo de proveedor
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene la estructura de la asignacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_sql="SELECT codded,monret,montotret". 
				"  FROM sob_retencionvaluacioncontrato". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codval='".$as_codval."'".
				"   AND codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			return false;		
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codded=$row["codded"];
				$li_monret=$row["monret"];
				$li_montotret=$row["montotret"];
				$ls_porded=$this->uf_select_porded($ls_codded,$ls_sccuenta);
				$ls_sql="INSERT INTO cxp_rd_deducciones (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codded, procede_doc, numdoccom, monobjret,".
						" 								 monret, sc_cuenta, porded, estcmp)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','".$ls_codded."','SOBCON','".$as_codcon."',".$li_monret.",".$li_montotret.",".
						"             '".$ls_sccuenta."',".$ls_porded.",'0')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ print $this->io_sql->message;
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{
					if($li_montotret>0)
					{
						$this->io_ds_scgcuentas->insertRow("sccuenta",$ls_sccuenta);
						$this->io_ds_scgcuentas->insertRow("debhab","H");
						$this->io_ds_scgcuentas->insertRow("monto",$li_montotret);
					}
				}
				$lb_valido=true;
			}
//			print_r($this->io_ds_scgcuentas->data);
			$this->io_ds_scgcuentas->group_by(array('0'=>'sccuenta','1'=>'debhab'),array('0'=>'monto'),'monto');
			$li_totrow=$this->io_ds_scgcuentas->getRowCount('sccuenta');	
//			print "<br />";
//			print_r($this->io_ds_scgcuentas->data);
			for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
			{
				$ls_sccuenta=$this->io_ds_scgcuentas->getValue('sccuenta',$li_fila);
				$ls_debhab=$this->io_ds_scgcuentas->getValue('debhab',$li_fila);
				$li_monto=$this->io_ds_scgcuentas->getValue('monto',$li_fila);
				
				$ls_sql="INSERT INTO cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab, sc_cuenta,".
						"				 		 monto, estgenasi, estasicon)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','".$ls_debhab."','".$ls_sccuenta."',".
						"               ".$li_monto.",0,'A')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ print $this->io_sql->message;
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
			}
			$this->io_ds_scgcuentas->group_by(array('0'=>'debhab'),array('0'=>'monto'),'monto');
			$li_totdebhab=$this->io_ds_scgcuentas->getRowCount('sccuenta');	
			$li_totdeb=0;
			$li_tothab=0;
			for($li_fildebhab=1;$li_fildebhab<=$li_totdebhab;$li_fildebhab++)
			{
				$ls_debhab=$this->io_ds_scgcuentas->getValue('debhab',$li_fildebhab);
				$li_monto=$this->io_ds_scgcuentas->getValue('monto',$li_fildebhab);
				if($ls_debhab=="D")
				{$li_totdeb=$li_totdeb+$li_monto;}
				else
				{$li_tothab=$li_tothab+$li_monto;}
				
			}
			$li_totpro=($li_totdeb-$li_tothab);
			$lb_valido=$this->uf_select_cuenta_proveedor($as_codpro,$as_sccuentapro);
			if($lb_valido)
			{
				$ls_sql="INSERT INTO cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab, sc_cuenta,".
						"				 		 monto, estgenasi, estasicon)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','H','".$as_sccuentapro."',".
						"               ".$li_totpro.",0,'A')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
				else
				{
					$lb_valido=true;
				}
			}
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuenta_proveedor($as_codpro,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuenta_proveedor
		//		   Access: private
		//	    Arguments: $as_codcon    // codigo de contrato
		//                 $as_sccuenta  // Cuenta de contratista
		//                 $as_ctaant    // Cuenta de anticipo de contratista
		//	      Returns: $lb_valido Devuelve un booleano
		//	  Description: Obtiene las cuentas contables para el asiento del anticipo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sc_cuenta,sc_ctaant". 
				"  FROM rpc_proveedor". 
				" WHERE codemp='".$ls_codemp."'".
				"   AND cod_pro='".$as_codpro."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				$this->io_msg->message("CLASE->Anticipo MÉTODO->uf_select_cuenta_proveedor ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$la_data=$this->io_sql->obtener_datos($rs_data);
				$as_sccuenta=$row["sc_cuenta"];
				if($as_sccuenta!="")
				{
					$lb_valido=true;
				}
				else
				{
					$this->io_msg->message("Falta por configurar la cuenta contable del proveedor");
				}
			}			
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_partidaasignacion($as_codasi,$as_codpar,$ad_canpar,$ad_prepar,$aa_seguridad)
	{
	
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_cant=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
		$ad_ppar=$this->io_funcsob->uf_convertir_cadenanumero($ad_prepar);
		$ls_sql="UPDATE sob_asignacionpartidaobra
				SET canparobrasi=".$ad_cant.", preparasi=".$ad_ppar."
				WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."' AND codpar='".$as_codpar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_update_partidaasignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($li_row>0)
			{
				/*************    SEGURIDAD    **************/		 
				 	$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la cantidad de la partida ".$as_codpar.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				  	$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
			}
		}		
		return $lb_valido;
	}	
	
}
?>
