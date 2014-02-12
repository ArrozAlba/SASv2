<?Php
/***************************************************************************************/
/*	Clase:	        Asignacion                                                         */    
/*  Fecha:          25/03/2006                                                         */        
/*	Autor:          GERARDO CORDERO		                                               */     
/***************************************************************************************/
class sigesp_sob_class_asignacion
{
 var $io_funcsob;
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $is_msg;

function sigesp_sob_class_asignacion()
{
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	$this->io_intspg=new class_sigesp_int_spg();		
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcsob=new sigesp_sob_c_funciones_sob();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$this->io_sql=new class_sql($io_connect);	
	$this->io_function=new class_funciones();
	$this->io_msg=new class_mensajes();
	$this->la_empresa=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$this->io_keygen= new sigesp_c_generar_consecutivo();
}
    function uf_select_asigancion($as_codasi)
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
		$ls_sql="SELECT codasi,codobr,cod_pro,cod_pro_ins,puncueasi,fecasi,obsasi,monparasi,basimpasi,montotasi
				  FROM sob_asignacion
				  WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."'";
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
	
		function uf_select_asignacion($as_codasi,&$aa_data)
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
		$ls_sql="SELECT *
				  FROM sob_asignacion
				  WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."'";
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
function uf_generar_codigo($as_codobr)
 {
		 ///////////////////////////////////////////////////////////////////////////////////////
		 //	Metodo: uf_generarcodigo
		 //	Access:  public
		 //	Returns: proximo codigo de la carta asignacion
		 //	Description: Funcion que permite generar el proximo codigo de una carta/asignacion
		 // Fecha: 27/06/2006               Fecha: 06/03/08
		 // Autor: Ing. Laura Cabré         Modificado: Ing. Carlos Zambrano
		 ///////////////////////////////////////////////////////////////////////////////////////
	
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codasi 
				FROM sob_asignacion
				WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'
				ORDER BY codasi DESC";		
		$rs_data=$this->io_sql->select($ls_sql);
		if ($row=$this->io_sql->fetch_row($rs_data))
		{ 
		  $codigo=$row["codpasi"];
		  settype($codigo,'int');                             // Asigna el tipo a la variable.
		  $codigo = $codigo + 1;                              // Le sumo uno al entero.
		  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
		  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,3);	 
		}
		else
		{
		  $codigo="1";
		  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,3);
		
		}
	
	  return $ls_codigo;	
 }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function uf_load_dt_obra ($as_codemp,$as_codobr)
 {
        /***************************************************************************************/
		/*	Function:	    uf_load_dt_obra                                                    */    
		/*  Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si se consiguieron datos con dichos filtros  */ 
		/*	Description:	función que se encarga de cargar las partidas asignadas a la obra  */    
		/*  Fecha:          23/10/2007                                                         */        
		/*	Autor:          Ing. Carlos Zambrano                                               */     
		/***************************************************************************************/
  $lb_valido = true;
  $ls_sql = "SELECT sob_partidaobra.codpar,sob_partida.nompar,sob_unidad.nomuni,sob_partida.prepar,sob_partidaobra.canparobr,
                    sob_partidaobra.canparasi,sob_partidaobra.canpareje,sob_partida.despar
			   FROM sob_partidaobra, sob_partida, sob_unidad
			  WHERE sob_partidaobra.codemp = '".$as_codemp."' 
			    AND sob_partidaobra.codobr = '".$as_codobr."' 
			    AND sob_partidaobra.codemp=sob_partida.codemp
			    AND sob_partidaobra.codpar=sob_partida.codpar
				AND sob_partida.codemp=sob_unidad.codemp
			    AND sob_partida.coduni=sob_unidad.coduni
				ORDER BY sob_partidaobra.codpar ASC";
 //print "sql = >".$ls_sql.'<br>';
  $rs_data= $this->io_sql->select($ls_sql);
  if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido = false;
		}
   return $rs_data;		  
 }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	function uf_guardar_asignacion(&$as_codasi,$as_codobr,$as_cod_pro,$as_cod_pro_ins,$as_puncueasi,$ad_fecasi,$as_obsasi,
								   $ai_monparasi,$ai_basimpasi,$ai_montotasi,$aa_seguridad,$as_estseg)
	{
		/***************************************************************************************/
		/*	Function:	    uf_guardar_asignacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		
		$lb_execute=true;
		$lb_valido=false;
		$lb_flag=$this->uf_select_asigancion($as_codasi);
		$ls_codemp=$this->la_empresa["codemp"];
		if($as_estseg!="C")
		{	
		 $this->io_keygen->uf_verificar_numero_generado("SOB","sob_asignacion","codasi","SOBASI",6,"","","",&$as_codasi);
	     $ls_sql="INSERT INTO sob_asignacion(codemp,codasi,codobr,cod_pro,cod_pro_ins,puncueasi,fecasi,obsasi,monparasi,basimpasi,montotasi,estasi,estapr)
		         VALUES ('".$ls_codemp."','".$as_codasi."','".$as_codobr."','".$as_cod_pro."','".$as_cod_pro_ins."','".$as_puncueasi."',".
				 "       '".$ad_fecasi."','".$as_obsasi."',".$ai_monparasi.",".$ai_basimpasi.",".$ai_montotasi.",1,0)";
		 				 		
		}
		else
		{
		  $lb_val=$this->uf_select_estado($as_codasi,&$ls_estasi);
		  if(($ls_estasi==1)||($ls_estasi==6))
		   {
		    $ls_sql=" UPDATE sob_asignacion 
				    SET codobr='".$as_codobr."',cod_pro='".$as_cod_pro."',cod_pro_ins='".$as_cod_pro_ins."',
				        puncueasi='".$as_puncueasi."',fecasi='".$ad_fecasi."',obsasi='".$as_obsasi."',
				        monparasi=".$monpar.",basimpasi=".$basimp.",montotasi=".$montot.",estasi=6
				   WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."'";
		   }
		   else
		   {
		     $this->io_msg->message("Esta Asignaciòn no puede ser modificada");
		     $lb_execute=false;
		   } 				 
		}	
		if($lb_execute)
		{
		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		 {			
			if($as_estseg!="C")
			{
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_guardar_asignacion(&$as_codasi,$as_codobr,$as_cod_pro,$as_cod_pro_ins,$as_puncueasi,$ad_fecasi,$as_obsasi,
								                            $ai_monparasi,$ai_basimpasi,$ai_montotasi,$aa_seguridad,$as_estseg);
				}
				else
				{
					$this->io_msg->message("CLASE->Obra MÉTODO->uf_guardar_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
				}
		
			}
			else
			{
				$this->io_msg->message("CLASE->Obra MÉTODO->uf_guardar_asignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			}
		  }
		  else
		  {
		    /************    SEGURIDAD    **************/		
			if($as_estseg!="C")
			{
			  $ls_evento="INSERT";
			  $ls_descripcion ="Insertó la Asignación ".$as_codasi.", de monto ".$montot." Asociada a la Empresa ".$ls_codemp;
			   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);  
			}
			else
			{
			  $ls_evento="UPDATE";
			  $ls_descripcion ="Actualizó la Asignación ".$as_codasi.", de monto ".$montot." Asociada a la Empresa ".$ls_codemp;
			  $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			}
			$lb_valido=true;
		  }  
		}
	    return $lb_valido;
	}
	function uf_select_partidasobra($as_codobr,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT p.codpar,s.nompar,u.nomuni,s.prepar,p.canparobr-p.canparasi as canxeje,p.canparasi
             FROM sob_partida s,sob_unidad u,sob_partidaobra p
             WHERE s.codemp='".$ls_codemp."' AND s.coduni=u.coduni AND s.codpar=p.codpar AND p.codobr='".$as_codobr."' ORDER BY s.codpar ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			$lb_valido=true;
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
    function uf_select_partidas($as_codasi,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT  apo.codemp,apo.codpar,apo.codasi,p.nompar,u.nomuni,apo.canparobrasi,apo.preparasi,apo.prerefparasi,(po.canparobr-po.canparasi) AS canttot,po.canparasi
				 FROM sob_asignacionpartidaobra apo, sob_partida p, sob_unidad u, sob_partidaobra po
				 WHERE apo.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND u.codemp='".$ls_codemp."' AND po.codemp='".$ls_codemp."' AND apo.codasi='".$as_codasi."' AND apo.codpar=p.codpar AND p.coduni=u.coduni AND po.codpar=p.codpar ORDER BY apo.codpar ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			$lb_valido=true;
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
	function uf_select_allpartidas($as_codobr,$as_codasi,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT apo.codasi,po.codpar,p.nompar,u.nomuni,p.prepar,apo.preparasi,(po.canparobr-po.canparasi) AS canttot,apo.canparobrasi,po.canparasi
                 FROM sob_partidaobra po LEFT JOIN sob_asignacionpartidaobra apo ON ((po.codpar=apo.codpar)AND (po.codemp=apo.codemp) AND (apo.codasi='".$as_codasi."')), sob_partida p, sob_unidad u
                 WHERE  po.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND u.codemp='".$ls_codemp."' AND po.codobr='".$as_codobr."' AND po.codpar=p.codpar AND p.coduni= u.coduni ORDER BY po.codpar ASC";
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

	function uf_guardar_dtpartidas($as_codobr,$as_codpar,$as_codasi,$ad_canpar,$ad_prerefpar,$ad_prepar,$aa_seguridad)
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
		$ad_cant=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
		$ad_prefpar=$this->io_funcsob->uf_convertir_cadenanumero($ad_prerefpar);
		$ad_ppar=$this->io_funcsob->uf_convertir_cadenanumero($ad_prepar);
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="INSERT INTO sob_asignacionpartidaobra (codemp,codobr,codpar,codasi,canparobrasi,preparasi,prerefparasi,canasipareje,canvarpar)
		         VALUES ('".$ls_codemp."','".$as_codobr."','".$as_codpar."','".$as_codasi."',".$ad_cant.",".$ad_ppar.",".$ad_prefpar.",0,0)";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_guardar_dtpartidas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				 /************    SEGURIDAD    **************/		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Partida ".$as_codpar.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/		
				$lb_valido=true;
		}		
		return $lb_valido;
	}
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
				/*************    SEGURIDAD    **************/		 
				 	$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la cantidad de la partida ".$as_codpar.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				  	$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	}	
	function uf_delete_dtpartidas($as_codasi,$as_codpar,$aa_seguridad)
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
		$ls_sql="DELETE FROM sob_asignacionpartidaobra
					WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."' AND codpar='".$as_codpar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_delete_dtpartidas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Partida ".$as_codpar.",Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/		 
			$lb_valido=true;
		}
		return $lb_valido;	
	}
	function uf_update_cantidaasignada($as_codobr,$as_codpar,$ad_canpar,$ad_caneje,$ad_oldcan,$aa_seguridad)
	{
	
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
		
				
		$ls_sql="UPDATE sob_partidaobra
				SET canparasi=".$ld_teje."
				WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codpar='".$as_codpar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_update_cantidaasignada ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad asiganada de la partida ".$as_codpar.", Detalle de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	function uf_update_dtpartidas($as_codasi,$as_codobr,$aa_partidasnuevas,$ai_totalfilas,$aa_seguridad)
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
		$lb_valido=true;
		$lb_existe=$this->uf_select_partidas($as_codasi,$la_partidasviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		if($lb_existe)
		{
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				$lb_existe=false;
				$lb_update=false;
				for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
				{
					if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codasi"][$li_j] == $as_codasi) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
					{
					  if(($la_partidasviejas["canparobrasi"][$li_j] != $this->io_funcsob->uf_convertir_cadenanumero($aa_partidasnuevas["cant"][$li_i]))||($la_partidasviejas["preparasi"][$li_j] != $this->io_funcsob->uf_convertir_cadenanumero($aa_partidasnuevas["pre"][$li_i])))
						{
						  $lb_update=true;
						}
						$lb_existe = true;
					}				
					
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_guardar_dtpartidas($as_codobr,$aa_partidasnuevas["codpar"][$li_i],$as_codasi,$aa_partidasnuevas["cant"][$li_i],
															$aa_partidasnuevas["preref"][$li_i],$aa_partidasnuevas["pre"][$li_i],$aa_seguridad);
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_cantidaasignada($as_codobr,$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],
																	$aa_partidasnuevas["canteje"][$li_i],0.000,$aa_seguridad);
				}	}
				if($lb_update)
				{
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_partidaasignacion($as_codasi,$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],
														   			  $aa_partidasnuevas["pre"][$li_i],$aa_seguridad);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_cantidaasignada($as_codobr,$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],
														 			$aa_partidasnuevas["canteje"][$li_i],$la_partidasviejas["canparobrasi"][$li_i],$aa_seguridad);
					}
				}
				if(!$lb_valido)
				{return false;}
			}
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				$lb_existe=false;
				for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
				{
					if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codasi"][$li_j] == $as_codasi) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
					{
					  $lb_existe = true;
					}				
					
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_delete_dtpartidas($as_codasi,$la_partidasviejas["codpar"][$li_j],$aa_seguridad);
					if(!$lb_valido)
					{return false;}
				}
			}			
		}
		return $lb_valido;
	}	
    function uf_select_cargos($as_codasi,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT  a.codemp,a.codasi,a.codcar,c.dencar,a.monto,a.formula
				 FROM sob_cargoasignacion a, sigesp_cargos c 
				 WHERE a.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND a.codasi='".$as_codasi."' AND a.codcar=c.codcar";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			$lb_valido=true;
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
	function uf_guardar_dtcargos($as_codasi,$as_codcar,$as_basimp,$as_monto,$as_formula,$as_codestpro,$as_spgcta,$as_estcla,$aa_seguridad)
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
		$ad_basimp=$this->io_funcsob->uf_convertir_cadenanumero($as_basimp);
		//$ad_monto=$this->io_funcsob->uf_convertir_cadenanumero($as_monto);
		$ls_sql="INSERT INTO sob_cargoasignacion (codemp, codasi, codcar, basimp, monto, formula, codestprog, spg_cuenta, estcla)
		         VALUES ('".$ls_codemp."','".$as_codasi."','".$as_codcar."',".$ad_basimp.",".$as_monto.",'".$as_formula."','".$as_codestpro."','".$as_spgcta."','".$as_estcla."')";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_guardar_dtcargos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				/************    SEGURIDAD    **************/		 
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cargo ".$as_codcar.", Detalle de la Asigancion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	}	
	function uf_delete_dtcargos($as_codasi,$as_codcar,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_delete_dtcargos                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_cargoasignacion
					WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."' AND codcar='".$as_codcar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_delete_dtcargos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Cargo ".$as_codcar.",Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/	
			$lb_valido=true;
		}
		return $lb_valido;	
	
	}
	function uf_update_dtcargos($as_codasi,$as_basimp,$aa_cargosnuevos,$ai_totalfilas,$aa_seguridad)
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
		$lb_existe=$this->uf_select_cargos($as_codasi,$la_cargosviejos,$li_totalviejos);
		$li_totalnuevas=$ai_totalfilas;
		if($lb_existe)
		{
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				$lb_existe=false;
				for ($li_j=1;$li_j<=$li_totalviejos;$li_j++)
				{
					if( ($la_cargosviejos["codemp"][$li_j] == $ls_codemp) && ($la_cargosviejos["codasi"][$li_j] == $as_codasi) &&  ($la_cargosviejos["codcar"][$li_j] == $aa_cargosnuevos["codcar"][$li_i]) )
					{
						
						$lb_existe = true;
					}				
					
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_guardar_dtcargos($as_codasi,$aa_cargosnuevos["codcar"][$li_i],$aa_cargosnuevos["basimp"][$li_i],$aa_cargosnuevos["moncar"][$li_i],$aa_cargosnuevos["formula"][$li_i],$aa_cargosnuevos["codestpro"][$li_i],$aa_cargosnuevos["spgcuenta"][$li_i],$aa_cargosnuevos["estcla"][$li_i],$aa_seguridad);
					if(!$lb_valido)
					{return false;}
				}
			}
			
			for ($li_j=1;$li_j<=$li_totalviejos;$li_j++)
			{
				$lb_existe=false;
				for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
				{
					if( ($la_cargosviejos["codemp"][$li_j] == $ls_codemp) && ($la_cargosviejos["codasi"][$li_j] == $as_codasi) &&  ($la_cargosviejos["codcar"][$li_j] == $aa_cargosnuevos["codcar"][$li_i]) )
					{
					  $lb_existe = true;
					}				
					
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_delete_dtcargos($as_codasi,$la_cargosviejos["codcar"][$li_j],$aa_seguridad);
					if(!$lb_valido)
					{return false;}
				}
			}
		}	
		return $lb_valido;		
	}
    function uf_select_cuentas($as_codasi,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT  ca.*,(asignado-(comprometido+precomprometido)+aumento-disminucion) AS disponible 
		         FROM sob_cuentasasignacion ca,spg_cuentas c
				 WHERE ca.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND ca.codasi='".$as_codasi."' AND ca.codestpro1=c.codestpro1 AND ca.codestpro2=c.codestpro2 AND ca.codestpro3=c.codestpro3 AND ca.codestpro4=c.codestpro4 AND ca.codestpro5=c.codestpro5 AND ca.spg_cuenta=c.spg_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			$lb_valido=true;
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
	function uf_select_cuentaspto($as_codpto,$as_codobr,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT  ca.*,(asignado-(comprometido+precomprometido)+aumento-disminucion) AS disponible 
		         FROM sob_cuentapuntodecuenta ca,spg_cuentas c
				 WHERE ca.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND ca.codpuncue='".$as_codpto."' AND ca.codobr='".$as_codobr."' AND ca.codestpro1=c.codestpro1 AND ca.codestpro2=c.codestpro2 AND ca.codestpro3=c.codestpro3 AND ca.codestpro4=c.codestpro4 AND ca.codestpro5=c.codestpro5 AND ca.spg_cuenta=c.spg_cuenta";
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
	function uf_guardar_dtcuentas($as_codasi,$as_codest1,$as_codest2,$as_codest3,$as_codest4,$as_codest5,$as_codcue,$ad_moncue,$as_estcla,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/*  Access:			public                                                             */ 
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
		$ls_sql="INSERT INTO sob_cuentasasignacion (codemp,codasi,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,monto,estcla)".
		        " VALUES ('".$ls_codemp."','".$as_codasi."','".$as_codest1."','".$as_codest2."','".$as_codest3."','".$as_codest4."','".$as_codest5."',".
				"         '".$as_codcue."','".$ad_monto."','".$as_estcla."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{		print $this->io_sql->message;
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_guardar_dtcuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				/************    SEGURIDAD    **************/		 
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Cuenta ".$as_codcue.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	}	
	function uf_delete_dtcuentas($as_codasi,$as_codest1,$as_codest2,$as_codest3,$as_codest4,$as_codest5,$as_codcue,$aa_seguridad)
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
		$ls_sql="DELETE FROM sob_cuentasasignacion
				 WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."' AND spg_cuenta='".$as_codcue."' AND codestpro1='".$as_codest1."' AND codestpro2='".$as_codest2."' AND codestpro3='".$as_codest3."' AND codestpro4='".$as_codest4."' AND codestpro5='".$as_codest5."' AND spg_cuenta='".$as_codcue."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_delete_dtcuentas ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Cuenta ".$as_codcue.",Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
		}
		return $lb_valido;	
	
	}
    function uf_update_cuentasasignacion($as_codasi,$as_codcue,$as_codest1,$as_codest2,$as_codest3,$as_codest4,$as_codest5,$ad_monpar,$as_estcla,$aa_seguridad)
	{
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
		$ls_sql="UPDATE sob_cuentasasignacion".
				"   SET monto='".$ad_monto."'".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codasi='".$as_codasi."'".
				"   AND spg_cuenta='".$as_codcue."'".
				"   AND codestpro1='".$as_codest1."'".
				"   AND codestpro2='".$as_codest2."'".
				"   AND codestpro3='".$as_codest3."'".
				"   AND codestpro4='".$as_codest4."'".
				"   AND codestpro5='".$as_codest5."'".
				"   AND estcla='".$as_estcla."'".
				"   AND spg_cuenta='".$as_codcue."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_update_cuentasasignacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el monto de la cuenta ".$as_codcue.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	function uf_update_dtcuentas($as_codasi,$aa_cuentasnuevas,$ai_totalfilas,$aa_seguridad)
	{
		$lb_valido=true;
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_existe=$this->uf_select_cuentas($as_codasi,$la_cuentasviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;	
		if($lb_existe)
		{	
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				$lb_existe=false;
				$lb_update=false;
				if($aa_cuentasnuevas["estcla"][$li_i]=="ACCION")
				{
					$ls_estcla="A";
				}
				else
				{
					$ls_estcla="P";
				}
				for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
				{
					if( ($la_cuentasviejas["codemp"][$li_j] == $ls_codemp) && ($la_cuentasviejas["codasi"][$li_j] == $as_codasi) && ($la_cuentasviejas["spg_cuenta"][$li_j] == $aa_cuentasnuevas["codcue"][$li_i]) && ($la_cuentasviejas["codestpro1"][$li_j] == $aa_cuentasnuevas["codest1"][$li_i]) &&  ($la_cuentasviejas["codestpro2"][$li_j] == $aa_cuentasnuevas["codest2"][$li_i]) &&  ($la_cuentasviejas["codestpro3"][$li_j] == $aa_cuentasnuevas["codest3"][$li_i]) && ($la_cuentasviejas["codestpro4"][$li_j] == $aa_cuentasnuevas["codest4"][$li_i]) && ($la_cuentasviejas["codestpro5"][$li_j] == $aa_cuentasnuevas["codest5"][$li_i]) && ($la_cuentasviejas["estcla"][$li_j] == $ls_estcla))
					{
						if ($la_cuentasviejas["monto"][$li_j] != $aa_cuentasnuevas["moncue"][$li_i])
						{
							$lb_update=true;
						}
						$lb_existe = true;
					}				
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_guardar_dtcuentas($as_codasi,$aa_cuentasnuevas["codest1"][$li_i],$aa_cuentasnuevas["codest2"][$li_i],$aa_cuentasnuevas["codest3"][$li_i],
														   $aa_cuentasnuevas["codest4"][$li_i],$aa_cuentasnuevas["codest5"][$li_i],$aa_cuentasnuevas["codcue"][$li_i],
														   $aa_cuentasnuevas["moncue"][$li_i],$aa_cuentasnuevas["estcla"][$li_i],$aa_seguridad);		
				}
				if	($lb_update)
				{
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_cuentasasignacion($as_codasi,$aa_cuentasnuevas["codcue"][$li_i],$aa_cuentasnuevas["codest1"][$li_i],
														   $aa_cuentasnuevas["codest2"][$li_i],$aa_cuentasnuevas["codest3"][$li_i],$aa_cuentasnuevas["codest4"][$li_i],
														   $aa_cuentasnuevas["codest5"][$li_i],$aa_cuentasnuevas["moncue"][$li_i],$aa_cuentasnuevas["estcla"][$li_i],$aa_seguridad);
					}
				}		
				if(!$lb_valido)
				{return false;}
			}
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				$lb_existe=false;
				for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
				{
					if( ($la_cuentasviejas["codemp"][$li_j] == $ls_codemp) && ($la_cuentasviejas["codasi"][$li_j] == $as_codasi) && ($la_cuentasviejas["spg_cuenta"][$li_j] == $aa_cuentasnuevas["codcue"][$li_i]) && ($la_cuentasviejas["codestpro1"][$li_j] == $aa_cuentasnuevas["codest1"][$li_i]) &&  ($la_cuentasviejas["codestpro2"][$li_j] == $aa_cuentasnuevas["codest2"][$li_i]) &&  ($la_cuentasviejas["codestpro3"][$li_j] == $aa_cuentasnuevas["codest3"][$li_i]) && ($la_cuentasviejas["codestpro4"][$li_j] == $aa_cuentasnuevas["codest4"][$li_i]) && ($la_cuentasviejas["codestpro5"][$li_j] == $aa_cuentasnuevas["codest5"][$li_i]))
					{
						$lb_existe = true;
					}				
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_delete_dtcuentas($as_codasi,$la_cuentasviejas["codestpro1"][$li_j],$la_cuentasviejas["codestpro2"][$li_j],$la_cuentasviejas["codestpro3"][$li_j],$la_cuentasviejas["codestpro4"][$li_j],$la_cuentasviejas["codestpro5"][$li_j],$la_cuentasviejas["spg_cuenta"][$li_j],$aa_seguridad);
				}
				if(!$lb_valido)
				{return false;}
			}
		}	
		return $lb_valido;	
	}
	
	
	
	function uf_llenarcombo_pais(&$aa_data)
	{
	/***************************************************************************************/
	/*	Function:	    uf_change_estatus_asi                                              */    
	/*  Access:			public                                                             */ 
	/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
	/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
	/*  Fecha:          25/03/2006                                                         */        
	/*	Autor:          GERARDO CORDERO		                                               */     
	/***************************************************************************************/
		$lb_valido=false;
		$ls_sql=" SELECT codpai AS codpai,despai AS despai
				 FROM sigesp_pais
				 ORDER BY codpai ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		//$li_numrows=$this->io_sql->num_rows($ar_data);	   
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
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
    

function uf_llenarcombo_estado($aa_codpai,&$aa_data)
	{
	/***************************************************************************************/
	/*	Function:	    uf_change_estatus_asi                                              */    
	/*  Access:			public                                                             */ 
	/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
	/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
	/*  Fecha:          25/03/2006                                                         */        
	/*	Autor:          GERARDO CORDERO		                                               */     
	/***************************************************************************************/
		$lb_valido=false;
		$ls_sql=" SELECT codest AS codest,desest AS desest
				 FROM sigesp_estados
				 WHERE codpai='$aa_codpai' ORDER BY codest ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		//$li_numrows=$this->io_sql->num_rows($ar_data);	   
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
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
    
	function uf_update_estado($as_codasi,$ai_estatus,$aa_seguridad)
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
		$ls_sql="UPDATE sob_asignacion
		         SET estasi=".$ai_estatus."
				 WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_update_estadoobra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($ai_estatus==3)
			{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Anulo la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				/********************************************/
			}
			$lb_valido=true;
		}
		return $lb_valido;		
	}
	function uf_select_estado($as_codasi,&$estasi)
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
		$ls_sql="SELECT estasi
		         FROM  sob_asignacion
		         WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."'";		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$estasi=$la_row["estasi"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
   }
	function uf_buscar_inspector($as_codasi,&$ls_insp)
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
		$ls_sql="SELECT p.nompro
		         FROM  sob_asignacion a, rpc_proveedor p
		         WHERE a.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND codasi='".$as_codasi."' AND a.cod_pro_ins=p.cod_pro";
				 		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$ls_insp=$la_row["nompro"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
   }
   function uf_select_obra($as_codobr,&$aa_data)
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
		$ls_sql="SELECT o.staobr,o.monto,o.feciniobr,o.fecfinobr,e.desest,m.denmun,pa.denpar,co.nomcom
				FROM sob_obra o, sigesp_estados e, sigesp_municipio m, sigesp_parroquia pa,sigesp_comunidad co
				WHERE o.codemp='".$ls_codemp."' AND o.codobr='".$as_codobr."' AND o.codest=co.codest AND o.codmun=co.codmun 
				AND o.codpar=co.codpar AND o.codcom=co.codcom  AND o.codest=pa.codest AND o.codmun=pa.codmun
				AND o.codpar=pa.codpar AND o.codest=m.codest AND o.codmun=m.codmun AND o.codest=e.codest";
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
      function uf_select_ptocuenta($as_codpto,&$aa_data)
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
		$ls_sql="SELECT pc.cod_pro,p.nompro,p.tipconpro
                 FROM sob_puntodecuenta pc, rpc_proveedor p
                 WHERE p.codemp='".$ls_codemp."' AND pc.codpuncue='".$as_codpto."' AND pc.codemp=p.codemp  AND pc.cod_pro=p.cod_pro ";
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
   function uf_select_montoasignado($as_codobr,&$monasi)
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
		$ls_sql="SELECT SUM(montotasi) AS monasi
                 FROM  sob_asignacion
                 WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'";		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$monasi=$la_row["monasi"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
   }
	function uf_update_estadoobra($as_codobr,$ai_estado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_estado
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de actualizar el estado de la obra
		//  Fecha:          24/03/2006
		//	Autor:          Ing. Laura Cabré				
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="UPDATE sob_obra
					 SET staobr='".$ai_estado."'
					 WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_update_estadoobra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	function uf_update_actcantidad($as_codobr,$as_codpar,$ad_canpar,$ad_caneje,$aa_seguridad)
	{
		$lb_valido=false;
		$ld_teje=0;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_canp=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
        $ld_teje=$ad_caneje-$ad_canp; 
		$ls_sql="UPDATE sob_partidaobra
				SET canparasi=".$ld_teje."
				WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codpar='".$as_codpar."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Obra MÉTODO->uf_update_estadoobra ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad asiganada de la partida ".$as_codpar.", Detalle de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas($aa_cuentas)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numsol // Número de solicitud
		//				   as_estsol  // Estatus de la solicitud
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ls_codestpro1=$la_cuentas["codest1"][$li_i];
			$ls_codestpro2=$la_cuentas["codest2"][$li_i];
			$ls_codestpro3=$la_cuentas["codest3"][$li_i];
			$ls_codestpro4=$la_cuentas["codest4"][$li_i];
			$ls_codestpro5=$la_cuentas["codest5"][$li_i];
			$ls_estcla=$la_cuentas["estcla"][$li_i];
			$ls_spg_cuenta=$la_cuentas["codcue"][$li_i];	
			$li_monto=$la_cuentas["moncue"][$li_i];
			$estprog[0]=$row["codestpro1"];
			$estprog[1]=$row["codestpro2"];
			$estprog[2]=$row["codestpro3"];
			$estprog[3]=$row["codestpro4"];
			$estprog[4]=$row["codestpro5"];
			$estprog[5]=$row["estcla"];
			$lb_valido=$this->io_intspg->uf_spg_saldo_select($this->ls_codemp, $estprog, $ls_spg_cuenta, &$ls_status, &$adec_asignado, 
													   &$adec_aumento,&$adec_disminucion,&$adec_precomprometido,
													   &$adec_comprometido,&$adec_causado,&$adec_pagado);
			$li_disponibilidad=($adec_asignado-($adec_comprometido+$adec_precomprometido)+$adec_aumento-$adec_disminucion);
//			if($li_existe>0)
//			{
				if($li_monto>$li_disponibilidad)
				{
					$li_monto=number_format($li_monto,2,",",".");
					$li_disponibilidad=number_format($li_disponibilidad,2,",",".");
					$this->io_msg->message("No hay Disponibilidad en la cuenta ".$ls_spg_cuenta." Disponible=[".$li_disponibilidad."] Cuenta=[".$li_monto."]"); 
				}
/*			}
			else
			{
				$lb_existe = false;
				$this->io_mensajes->message("La cuenta ".$ls_spg_cuenta." No Existe en la Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.""); 
			}*/
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	  
}
?>
