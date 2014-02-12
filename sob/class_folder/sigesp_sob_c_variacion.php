<?Php
class sigesp_sob_c_variacion
{
  var $io_funcsob;
  var $io_function;
  var $la_empresa;
  var $io_sql;
  var $is_msg;

function sigesp_sob_c_variacion()
{

	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$this->io_funcsob=new sigesp_sob_c_funciones_sob();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$this->io_sql=new class_sql($io_connect);	
	$this->io_function=new class_funciones();
	$this->io_msg=new class_mensajes();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->la_empresa=$_SESSION["la_empresa"]; 
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$this->io_keygen= new sigesp_c_generar_consecutivo();

}

	function uf_select_newcodigo($as_codcon,&$as_codvar)
   {
		/***************************************************************************************/
		/*	Function:	    uf_select_contrato                                                 */    
		/*	Description:	Funcion que se encarga de asignar un nuevo codigo a la valuacion   */    
		/*  Fecha:          17/04/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codvar
                 FROM sob_variacioncontrato 
                 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' ORDER BY codvar DESC ";
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
				$li_codval=$row["codvar"];
				settype($li_codval,'int');
				$li_codval=$li_codval+1;
				settype($li_codval,'string');
		        $as_codvar=$this->io_function->uf_cerosizquierda($li_codval,3);
			}
			else
			{
				$as_codvar="001";
			}
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
		$ls_sql="SELECT apo.codpar,p.nompar,u.nomuni,apo.preparasi,(apo.canparobrasi-apo.canasipareje)+apo.canvarpar AS canxeje,c.codasi,a.codobr".
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
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_select_partidasasignadas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
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
	
	function uf_select_variacion($as_codvar,$as_codcon)
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
		$ls_sql="SELECT codvar
				 FROM sob_variacioncontrato
			     WHERE codemp='".$ls_codemp."' AND codvar='".$as_codvar."' AND codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_select_variacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
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
	
	function uf_guardar_variacion(&$as_codvar,$as_codcon,$as_tipvar,$as_motvar,$ad_fecha,$as_monto,$as_chk,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_guardar_asignacion                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_act=true;
		$lb_execute=true;
		$lb_valido=false;
		if($as_chk=="C")
		{
			$lb_valido=$this->uf_update_variacion($as_codvar,$as_codcon,$as_tipvar,$as_motvar,$ad_fecha,$as_monto,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_insert_variacion(&$as_codvar,$as_codcon,$as_tipvar,$as_motvar,$ad_fecha,$as_monto,$aa_seguridad);
		}
/*		$lb_flag=$this->uf_select_variacion($as_codvar,$as_codcon);
		$ld_monto=$this->io_funcsob->uf_convertir_cadenanumero($as_monto);
		$ls_codemp=$this->la_empresa["codemp"];
		if(!$lb_flag)
		{	
	     $ls_sql="INSERT INTO sob_variacioncontrato(codemp,codvar,codcon,tipvar,motvar,fecvar,monto,estvar)
		         VALUES ('".$ls_codemp."','".$as_codvar."','".$as_codcon."','".$as_tipvar."','".$as_motvar."','".$ad_fecha."',".$ld_monto.",1)";
		 $this->io_msg->message("Registro Incluido");	 		
		}
		else
		{
		  $lb_val=$this->uf_select_estado($as_codvar,$as_codcon,&$ls_estasi);
		  
		  if(($ls_estasi==1)||($ls_estasi==6))
		   {
		    $lb_act=false; 
		    $ls_sql=" UPDATE sob_variacioncontrato 
				    SET tipvar='".$as_tipvar."',motvar='".$as_motvar."',fecvar='".$ad_fecha."',monto=".$ld_monto.",estvar=6 
					WHERE codemp='".$ls_codemp."' AND codvar='".$as_codvar."' AND codcon='".$as_codcon."'";	
		    $this->io_msg->message("Registro Actualizado");		    
		   }
		   else
		   {
		     $this->io_msg->message("Esta Variacion no puede ser modificada");
		     $lb_execute=false;
		   } 				 
		}	
		if($lb_execute)
		{
		 
		 $this->io_sql->begin_transaction();	
		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		  {			
			print "Error en metodo uf_guardar_variacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
			print($ls_sql);
		  }
		  else
		  {
			if($li_row>0)
			{
				/************    SEGURIDAD    *************		
				if($lb_act)
				{
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Variación ".$as_codvar.", de monto ".$as_monto." Asociada a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);  
				}
				else
				{
				  $ls_evento="UPDATE";
				  $ls_descripcion ="Actualizó la Variación ".$as_codvar.", de monto ".$as_monto." Asociada a la Empresa ".$ls_codemp;
				  $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				}
				/*************************************************
				$this->io_sql->commit();
				$lb_valido=true;				
			}
			else
			{
				
				$this->io_sql->rollback();
			}
		
		  }  
		}*/
	    return $lb_valido;
	}
	function uf_insert_variacion(&$as_codvar,$as_codcon,$as_tipvar,$as_motvar,$ad_fecha,$as_monto,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$ls_codemp=$this->la_empresa["codemp"];
		$ld_monto=$this->io_funcsob->uf_convertir_cadenanumero($as_monto);
		$lb_valido=false;
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("SOB","sob_variacioncontrato","codvar","SOBVAR",3,"","codcon",$as_codcon,&$as_codvar);
		$ls_sql="INSERT INTO sob_variacioncontrato(codemp,codvar,codcon,tipvar,motvar,fecvar,monto,estvar,estapr)".
		    	"  VALUES ('".$ls_codemp."','".$as_codvar."','".$as_codcon."','".$as_tipvar."','".$as_motvar."','".$ad_fecha."',".$ld_monto.",1,'0')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_variacion(&$as_codvar,$as_codcon,$as_tipvar,$as_motvar,$ad_fecha,$as_monto,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_insert_variacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Variación ".$as_codvar.", de monto ".$as_monto." Asociada a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);  
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}
	
	
	function uf_update_variacion($as_codvar,$as_codcon,$as_tipvar,$as_motvar,$ad_fecha,$as_monto,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=false;
		$ld_monto=$this->io_funcsob->uf_convertir_cadenanumero($as_monto);
		$lb_val=$this->uf_select_estado($as_codvar,$as_codcon,&$ls_estasi);
		if(($ls_estasi==1)||($ls_estasi==6))
		{
			$ls_sql="UPDATE sob_variacioncontrato ".
					"   SET tipvar='".$as_tipvar."',motvar='".$as_motvar."',fecvar='".$ad_fecha."',monto=".$ld_monto.",estvar=6". 
					" WHERE codemp='".$ls_codemp."'".
					"   AND codvar='".$as_codvar."'".
					"   AND codcon='".$as_codcon."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{			
				$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_insert_variacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la Variación ".$as_codvar.", de monto ".$as_monto." Asociada a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		else
		{
			$this->io_msg->message("Esta Variacion no puede ser modificada");
		} 				 
		return $lb_valido;
	}
	
	
	function uf_select_allpartidas($as_codvar,$as_codasi,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT apo.codemp,apo.codasi,apo.codobr,apo.codpar,p.nompar,u.nomuni,((apo.canparobrasi-apo.canasipareje)+apo.canvarpar) AS canorigi,".
				"       vp.cantidad_nueva as cannue,apo.preparasi,vp.precio_nuevo as prenue".
				"  FROM sob_asignacionpartidaobra apo LEFT JOIN sob_variacionpartida vp ON ((apo.codpar=vp.codpar)".
				"                                    AND (apo.codemp=vp.codemp) AND (apo.codasi=vp.codasi)".
				" 									 AND vp.codvar='".$as_codvar."'),sob_partida p,sob_unidad u ".
				" WHERE apo.codasi='".$as_codasi."'".
				"   AND apo.codemp='".$ls_codemp."'".
				"   AND p.codemp='".$ls_codemp."'".
				"   AND u.codemp='".$ls_codemp."'".
				"   AND apo.codpar=p.codpar".
				"   AND p.coduni=u.coduni".
				" ORDER BY apo.codpar ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_select_allpartidas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_select_partidas($as_codvar,$as_codcon,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT  vp.codemp,vp.codvar,vp.codcon,vp.codpar,p.nompar,u.nomuni,vp.cantidad_anterior as canant,vp.cantidad_nueva as cannue,vp.precio_anterior as preant,vp.precio_nuevo as prenue
                 FROM sob_variacionpartida vp, sob_partida p, sob_unidad u
                 WHERE vp.codemp='".$ls_codemp."' AND u.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."'  AND vp.codvar='".$as_codvar."' AND vp.codcon='".$as_codcon."' AND vp.codpar=p.codpar AND p.coduni=u.coduni";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_select_partidas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
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
	
	
	function uf_guardar_dtpartidas($as_codvar,$as_codcon,$as_codobr,$as_codpar,$as_codasi,$as_cantant,$as_cantnew,$as_preant,$as_prenew,$aa_seguridad)
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
		$ld_cantant=$this->io_funcsob->uf_convertir_cadenanumero($as_cantant);
		$ld_cantnew=$this->io_funcsob->uf_convertir_cadenanumero($as_cantnew);
		$ld_preant=$this->io_funcsob->uf_convertir_cadenanumero($as_preant);
		$ld_prenew=$this->io_funcsob->uf_convertir_cadenanumero($as_prenew);
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="INSERT INTO sob_variacionpartida (codemp,codobr,codpar,codasi,codvar,codcon,cantidad_anterior,cantidad_nueva,precio_anterior,precio_nuevo)".
		        "  VALUES ('".$ls_codemp."','".$as_codobr."','".$as_codpar."','".$as_codasi."','".$as_codvar."','".$as_codcon."',".
				"           ".$ld_cantant.",".$ld_cantnew.",".$ld_preant.",".$ld_prenew.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_guardar_dtpartidas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			     /************    SEGURIDAD    **************/		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Partida ".$as_codpar.", Detalle de la Variacion ".$as_codvar." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/	
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dtpartidas($as_codvar,$as_codcon,$as_codpar,$aa_seguridad)
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
		$ls_sql="DELETE FROM sob_variacionpartida".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codvar='".$as_codvar."'".
				"   AND codcon='".$as_codcon."'".
				"   AND codpar='".$as_codpar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_delete_dtpartidas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Partida ".$as_codpar.",Detalle de la Variacion ".$as_codvar." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
		}
		return $lb_valido;	
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_partidavariacion($as_codvar,$as_codcon,$as_codpar,$as_cannew,$as_prenew,$aa_seguridad)
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
		$ld_cannew=$this->io_funcsob->uf_convertir_cadenanumero($as_cannew);
		$ld_prenew=$this->io_funcsob->uf_convertir_cadenanumero($as_prenew);
		$ls_sql="UPDATE sob_variacionpartida".
				"   SET cannue='".$ld_cannew."',prenue='".$ld_prenew."'".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codvar='".$as_codvar."'".
				"   AND codcon='".$as_codcon."'".
				"   AND codpar='".$as_codpar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_guardar_dtpartidas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la partida ".$as_codpar.", Detalle de la Variacion ".$as_codvar." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	  }	
	//-----------------------------------------------------------------------------------------------------------------------------------
	  
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_varanterior($as_codasi,$as_codobr,$as_codpar,&$ad_canvar)
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
		$ls_sql="SELECT canvarpar".
		        "  FROM sob_asignacionpartidaobra".
			    " WHERE codemp='".$ls_codemp."'".
				"   AND codasi='".$as_codasi."'".
				"   AND codobr='".$as_codobr."'".
				"   AND codpar='".$as_codpar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_select_varanterior ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ad_canvar=$la_row["canvarpar"];
			}
		}		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_actprecioscantidades($as_codobr,$as_codasi,$as_codpar,$as_cannew,$as_prenew,$ad_oldcan,$aa_seguridad)
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
		$ld_cannew=$this->io_funcsob->uf_convertir_cadenanumero($as_cannew);
		$ld_prenew=$this->io_funcsob->uf_convertir_cadenanumero($as_prenew);
		$lb_valido=$this->uf_select_varanterior($as_codasi,$as_codobr,$as_codpar,&$ld_canvar);
		if($lb_valido)
		{
			if($ad_oldcan!=0)
			{
				$ld_temp=$ld_canvar-$ad_oldcan;
				$ld_cant=$ld_cannew+$ld_temp;   
			}
			else
			{
				$ld_cant=$ld_cannew+$ld_canvar;
			}
			if($ld_prenew!=0)
			{
				$ls_sql="UPDATE sob_asignacionpartidaobra ".
						"   SET canvarpar='".$ld_cant."',preparasi='".$ld_prenew."'".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codasi='".$as_codasi."'".
						"   AND codobr='".$as_codobr."'".
						"   AND codpar='".$as_codpar."'";   
			}
			else
			{
				$ls_sql="UPDATE sob_asignacionpartidaobra".
						"   SET canvarpar='".$ld_cant."'".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codasi='".$as_codasi."'".
						"   AND codobr='".$as_codobr."'".
						"   AND codpar='".$as_codpar."'"; 
			}		
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{			
				$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_update_actprecioscantidades ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
					/*************    SEGURIDAD    **************/		 
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la cantidad de la partida ".$as_codpar.", Detalle de la asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
					   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
					/**********************************************/
					$lb_valido=true;
			}		
		}
		return $lb_valido;
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_dtpartidas($as_codvar,$as_codcon,$aa_partidasnuevas,$ai_totalfilas,$aa_seguridad)
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
		$lb_valido=$this->uf_select_partidas($as_codvar,$as_codcon,$la_partidasviejas,$li_totalviejas);
		if($lb_valido)
		{
			$li_totalnuevas=$ai_totalfilas;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				$lb_existe=false;
				$lb_update=false;
				for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
				{
					if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codvar"][$li_j] == $as_codvar) && ($la_partidasviejas["codcon"][$li_j] == $as_codcon) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
					{
					  if($la_partidasviejas["cannue"][$li_j] != $this->io_funcsob->uf_convertir_cadenanumero($aa_partidasnuevas["cantnew"][$li_i]))
						{
						  $lb_update=true;
						}
						$lb_existe = true;
					}				
					
				}
				if (!$lb_existe)
				{
					if($lb_valido)
					{
						$lb_valido=$this->uf_guardar_dtpartidas($as_codvar,$as_codcon,$aa_partidasnuevas["codobr"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["cantant"][$li_i],$aa_partidasnuevas["cantnew"][$li_i],$aa_partidasnuevas["preant"][$li_i],$aa_partidasnuevas["prenew"][$li_i],$aa_seguridad);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_actprecioscantidades($aa_partidasnuevas["codobr"][$li_i],$aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cantnew"][$li_i],$aa_partidasnuevas["prenew"][$li_i],0,$aa_seguridad);
					}
				}
				if($lb_update)
				{
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_partidavariacion($as_codvar,$as_codcon,$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cantnew"][$li_i],$aa_partidasnuevas["prenew"][$li_i],$aa_seguridad);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_actprecioscantidades($aa_partidasnuevas["codobr"][$li_i],$aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cantnew"][$li_i],$aa_partidasnuevas["prenew"][$li_i],$la_partidasviejas["canant"][$li_i],$aa_seguridad);  	
					}
				}
			}
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				$lb_existe=false;
				for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
				{
					if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codvar"][$li_j] == $as_codvar) && ($la_partidasviejas["codcon"][$li_j] == $as_codcon) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
					{
					  $lb_existe = true;
					}				
					
				}
				if($lb_valido)
				{
					if (!$lb_existe)
					{
						$lb_valido=$this->uf_delete_dtpartidas($as_codvar,$as_codcon,$la_partidasviejas["codpar"][$li_j],$aa_seguridad);
					}
				}
			}
		}
		return $lb_valido;
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estado($as_codvar,$as_codcon,$ai_estatus,$aa_seguridad)
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
		$ls_sql="UPDATE sob_variacioncontrato
		         SET estvar=".$ai_estatus."
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codvar='".$as_codvar."'";		
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
			$ls_descripcion ="Anulo la Variacion ".$as_codvar." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			}
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;		
	}
	function uf_select_estado($as_codvar,$as_codcon,&$estasi)
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
		$ls_sql="SELECT estvar
		         FROM  sob_variacioncontrato
		         WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codvar='".$as_codvar."'";		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$estasi=$la_row["estvar"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_cuentas($as_codvar,$as_codcon,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT  cv.*,(asignado-(comprometido+precomprometido)+aumento-disminucion) AS disponible 
		         FROM sob_cuentavariacion cv,spg_cuentas c
				 WHERE cv.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND cv.codvar='".$as_codvar."' AND cv.codcon='".$as_codcon."' AND cv.codestpro1=c.codestpro1 AND cv.codestpro2=c.codestpro2 AND cv.codestpro3=c.codestpro3 AND cv.codestpro4=c.codestpro4 AND cv.codestpro5=c.codestpro5 AND cv.spg_cuenta=c.spg_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_select_cuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
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
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_load_cuentas($as_codcon,&$rs_data)
	{
	    /***************************************************************************************/
	    /*	Function:	    uf_load_cuentas                                              */    
	    /* Access:			public                                                             */ 
	    /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
	    /*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=true;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sob_cuentasasignacion.codestpro1,sob_cuentasasignacion.codestpro2,sob_cuentasasignacion.codestpro3,".
				"       sob_cuentasasignacion.codestpro4,sob_cuentasasignacion.codestpro5,sob_cuentasasignacion.estcla,".
				"       sob_cuentasasignacion.spg_cuenta,sob_cuentasasignacion.monto,".
				"      (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion)".
				"         FROM spg_cuentas".
				"        WHERE sob_cuentasasignacion.codemp=spg_cuentas.codemp".
				"          AND sob_cuentasasignacion.spg_cuenta=spg_cuentas.spg_cuenta".
				"          AND sob_cuentasasignacion.codestpro1=spg_cuentas.codestpro1".
				"          AND sob_cuentasasignacion.codestpro2=spg_cuentas.codestpro2".
				"          AND sob_cuentasasignacion.codestpro3=spg_cuentas.codestpro3".
				"          AND sob_cuentasasignacion.codestpro4=spg_cuentas.codestpro4".
				"          AND sob_cuentasasignacion.codestpro5=spg_cuentas.codestpro5".
				"          AND sob_cuentasasignacion.estcla=spg_cuentas.estcla) AS disponible".
		        "  FROM sob_contrato,sob_cuentasasignacion".
				" WHERE sob_contrato.codemp='".$ls_codemp."'".
				"   AND sob_contrato.codcon='".$as_codcon."'".
				"   AND sob_contrato.codemp=sob_cuentasasignacion.codemp".
				"   AND sob_contrato.codasi=sob_cuentasasignacion.codasi";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_select_cuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_dtcuentas($as_codvar,$as_codcon,$as_codest1,$as_codest2,$as_codest3,$as_codest4,$as_codest5,$as_codcue,$ad_moncue,$as_estcla,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		if($as_estcla=="ACCION")
		{
			$as_estcla="A";
		}
		else
		{
			$as_estcla="P";
		}
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_monto=$this->io_funcsob->uf_convertir_cadenanumero($ad_moncue);
		$ls_sql="INSERT INTO sob_cuentavariacion (codemp,codvar,codcon,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,monto,estcla)
		         VALUES ('".$ls_codemp."','".$as_codvar."','".$as_codcon."','".$as_codest1."','".$as_codest2."','".$as_codest3."','".$as_codest4."','".$as_codest5."','".$as_codcue."','".$ad_monto."','".$as_estcla."')";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{ print $this->io_sql->message;
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_guardar_dtcuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
				/************    SEGURIDAD    **************/		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Cuenta ".$as_codcue.", Detalle de la Variacion ".$as_codvar." del Contrato ".$as_codcon." Asociados a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dtcuentas($as_codvar,$as_codcon,$as_codest1,$as_codest2,$as_codest3,$as_codest4,$as_codest5,$as_codcue,$aa_seguridad)
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
		$ls_sql="DELETE FROM sob_cuentavariacion".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codvar='".$as_codvar."'".
				"   AND codcon='".$as_codcon."'".
				"   AND spg_cuenta='".$as_codcue."'".
				"   AND codestpro1='".$as_codest1."'".
				"   AND codestpro2='".$as_codest2."'".
				"   AND codestpro3='".$as_codest3."'".
				"   AND codestpro4='".$as_codest4."'".
				"   AND codestpro5='".$as_codest5."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_delete_dtcuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Cuenta ".$as_codcue.",Detalle de la Variacion ".$as_codvar." del Contrato ".$as_codcon." Asociados a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
		}
		return $lb_valido;	
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_update_cuentavariacion($as_codvar,$as_codcon,$as_codcue,$as_codest1,$as_codest2,$as_codest3,$as_codest4,$as_codest5,$ad_monpar,$as_estcla,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		if($as_estcla=="ACCION")
		{
			$as_estcla="A";
		}
		else
		{
			$as_estcla="P";
		}
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_monto=$this->io_funcsob->uf_convertir_cadenanumero($ad_monpar);
		$ls_sql="UPDATE sob_cuentavariacion".
				"   SET monto='".$ad_monto."'".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codvar='".$as_codvar."'".
				"   AND codcon='".$as_codcon."'".
				"   AND spg_cuenta='".$as_codcue."'".
				"   AND codestpro1='".$as_codest1."'".
				"   AND codestpro2='".$as_codest2."'".
				"   AND codestpro3='".$as_codest3."'".
				"   AND codestpro4='".$as_codest4."'".
				"   AND codestpro5='".$as_codest5."'".
				"   AND estcla='".$as_estcla."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_guardar_dtcuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el monto de la cuenta ".$as_codcue.", Detalle de la Variacion ".$as_codvar." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_dtcuentas($as_codvar,$as_codcon,$aa_cuentasnuevas,$ai_totalfilas,$aa_seguridad)
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
		$lb_valido=$this->uf_select_cuentas($as_codvar,$as_codcon,$la_cuentasviejas,$li_totalviejas);
		if($lb_valido)
		{
			$li_totalnuevas=$ai_totalfilas;		
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if($aa_cuentasnuevas["estcla"][$li_i]=="ACCION")
				{
					$ls_estcla="A";
				}
				else
				{
					$ls_estcla="P";
				}
				$lb_existe=false;
				$lb_update=false;
				for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
				{
					if( ($la_cuentasviejas["codemp"][$li_j] == $ls_codemp) && ($la_cuentasviejas["codvar"][$li_j] == $as_codvar) && ($la_cuentasviejas["codcon"][$li_j] == $as_codcon) && ($la_cuentasviejas["spg_cuenta"][$li_j] == $aa_cuentasnuevas["codcue"][$li_i]) && ($la_cuentasviejas["codestpro1"][$li_j] == $aa_cuentasnuevas["codest1"][$li_i]) &&  ($la_cuentasviejas["codestpro2"][$li_j] == $aa_cuentasnuevas["codest2"][$li_i]) &&  ($la_cuentasviejas["codestpro3"][$li_j] == $aa_cuentasnuevas["codest3"][$li_i]) && ($la_cuentasviejas["codestpro4"][$li_j] == $aa_cuentasnuevas["codest4"][$li_i]) && ($la_cuentasviejas["codestpro5"][$li_j] == $aa_cuentasnuevas["codest5"][$li_i]) && ($la_cuentasviejas["estcla"][$li_j] == $ls_estcla))
					{
						if ($la_cuentasviejas["monto"][$li_j] != $aa_cuentasnuevas["moncue"][$li_i])
						{
							$lb_update=true;
						}
					
						$lb_existe = true;
					}				
					
				}
				if($lb_valido)
				{
					if (!$lb_existe)
					{
						$lb_valido=$this->uf_guardar_dtcuentas($as_codvar,$as_codcon,$aa_cuentasnuevas["codest1"][$li_i],$aa_cuentasnuevas["codest2"][$li_i],$aa_cuentasnuevas["codest3"][$li_i],$aa_cuentasnuevas["codest4"][$li_i],$aa_cuentasnuevas["codest5"][$li_i],$aa_cuentasnuevas["codcue"][$li_i],$aa_cuentasnuevas["moncue"][$li_i],$aa_cuentasnuevas["estcla"][$li_i],$aa_seguridad);		
					}
					if($lb_valido)
					{
						if	($lb_update)
						{
							$lb_valido=$this->uf_update_cuentavariacion($as_codvar,$as_codcon,$aa_cuentasnuevas["codcue"][$li_i],$aa_cuentasnuevas["codest1"][$li_i],$aa_cuentasnuevas["codest2"][$li_i],$aa_cuentasnuevas["codest3"][$li_i],$aa_cuentasnuevas["codest4"][$li_i],$aa_cuentasnuevas["codest5"][$li_i],$aa_cuentasnuevas["moncue"][$li_i],$aa_cuentasnuevas["estcla"][$li_i],$aa_seguridad);
						}		
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
						if( ($la_cuentasviejas["codemp"][$li_j] == $ls_codemp) && ($la_cuentasviejas["codvar"][$li_j] == $as_codvar)  && ($la_cuentasviejas["codcon"][$li_j] == $as_codcon)&& ($la_cuentasviejas["spg_cuenta"][$li_j] == $aa_cuentasnuevas["codcue"][$li_i]) && ($la_cuentasviejas["codestpro1"][$li_j] == $aa_cuentasnuevas["codest1"][$li_i]) &&  ($la_cuentasviejas["codestpro2"][$li_j] == $aa_cuentasnuevas["codest2"][$li_i]) &&  ($la_cuentasviejas["codestpro3"][$li_j] == $aa_cuentasnuevas["codest3"][$li_i]) && ($la_cuentasviejas["codestpro4"][$li_j] == $aa_cuentasnuevas["codest4"][$li_i]) && ($la_cuentasviejas["codestpro5"][$li_j] == $aa_cuentasnuevas["codest5"][$li_i]))
						{
							
							$lb_existe = true;
						}				
						
					}
					if (!$lb_existe)
					{
						$lb_valido=$this->uf_delete_dtcuentas($as_codvar,$as_codcon,$la_cuentasviejas["codestpro1"][$li_j],$la_cuentasviejas["codestpro2"][$li_j],$la_cuentasviejas["codestpro3"][$li_j],$la_cuentasviejas["codestpro4"][$li_j],$la_cuentasviejas["codestpro5"][$li_j],$la_cuentasviejas["spg_cuenta"][$li_j],$aa_seguridad);
					}			
				}
			}
		}
		return $lb_valido;
	}
	function uf_update_montocontrato($as_codcon,$as_monto,$aa_seguridad)
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
		$ld_monto=$this->io_funcsob->uf_convertir_cadenanumero($as_monto);
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="UPDATE sob_contrato".
		        "   SET monreacon=".$ld_monto."".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codcon='".$as_codcon."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Solicitud MÉTODO->uf_update_montocontrato ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		   	/*************    SEGURIDAD    **************/		 
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo el monto real del contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
		}
		return $lb_valido;		
	}
	
}
?>
