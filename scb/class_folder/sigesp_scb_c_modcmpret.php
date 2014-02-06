<?php
class sigesp_scb_c_modcmpret
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $io_dscuentas;

	//----------------------------------------------------------------------------------------------------------------
	function sigesp_scb_c_modcmpret($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_scb_c_modcmpret
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 18/09/2007 								Fecha Última Modificación : 21/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_cxp_c_solicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_scb_c_modcmpret.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
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

	function uf_load_dt_cmpret($as_numcom)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_dt_cmpret
		//		   Access: public
		//		 Argument: as_numcom // Número del Comprobante
		//	  Description: Función que busca los Comprobantes de Retención.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 30/06/2007		   Fecha Última Modificación : 30/06/2007.
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numope,numfac,numcon,fecfac,totcmp_sin_iva,totcmp_con_iva,basimp,porimp,totimp,iva_ret,numdoc,codret,
		                numsop,numnd,numnc,tiptrans
				   FROM scb_dt_cmp_ret
				  WHERE codemp = '".$this->ls_codemp."'
				    AND numcom = '".$as_numcom."'
					AND codret = '0000000001'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_scb_c_modcmpret.php;MÉTODO->uf_load_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_dt_cmpret

    //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dt_cmpret($as_numcom,$ai_totrowrecepciones,$as_probene,$as_codigo,$aa_seguridad)
	{
		 ///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dt_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante 
		//				   ai_totrowrecepciones // Total de Filas Detalles del Comprobante
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los detalles del comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowrecepciones)&&($lb_valido);$li_i++)
		{
			$ls_numope=$_POST["txtnumope".$li_i];
			$ls_fecfac=$this->io_funciones->uf_convertirdatetobd($_POST["txtfecfac".$li_i]);
			$ls_numfac=$_POST["txtnumfac".$li_i];
			$ls_numcon=$_POST["txtnumcon".$li_i];
			$ls_numnd=$_POST["txtnumnd".$li_i];
			$ls_numnc=$_POST["txtnumnc".$li_i];
			$ls_tiptrans=$_POST["txttiptrans".$li_i];
			$ls_tot_cmp_sin_iva=$_POST["txttotsiniva".$li_i];
			$ls_tot_cmp_sin_iva=str_replace(".","",$ls_tot_cmp_sin_iva);
			$ls_tot_cmp_sin_iva=str_replace(",",".",$ls_tot_cmp_sin_iva);
			$ls_tot_cmp_con_iva=$_POST["txttotconiva".$li_i];
			$ls_tot_cmp_con_iva=str_replace(".","",$ls_tot_cmp_con_iva);
			$ls_tot_cmp_con_iva=str_replace(",",".",$ls_tot_cmp_con_iva);
			$ls_basimp=$_POST["txtbasimp".$li_i];
			$ls_basimp=str_replace(".","",$ls_basimp);
			$ls_basimp=str_replace(",",".",$ls_basimp);
			$ls_porimp=$_POST["txtporimp".$li_i];
			$ls_porimp=str_replace(".","",$ls_porimp);
			$ls_porimp=str_replace(",",".",$ls_porimp);
			$ls_totimp=$_POST["txttotimp".$li_i];
			$ls_totimp=str_replace(".","",$ls_totimp);
			$ls_totimp=str_replace(",",".",$ls_totimp);
			$ls_ivaret=$_POST["txtivaret".$li_i];
			$ls_ivaret=str_replace(".","",$ls_ivaret);
			$ls_ivaret=str_replace(",",".",$ls_ivaret);
			$ls_numsop=$_POST["txtnumsop".$li_i];
			$ls_numdoc=$_POST["txtnumdoc".$li_i];
			$li_porret=$_POST["txtporret".$li_i];
						
			$ls_sql="INSERT INTO scb_dt_cmp_ret (codemp,codret,numcom,numope,fecfac,numfac,numcon,numnd,numnc,tiptrans,".
					"							 totcmp_sin_iva,totcmp_con_iva,basimp,porimp,totimp,iva_ret,desope,numsop,codban,".
					"							 ctaban,numdoc,codope)".
					"     VALUES  ('".$this->ls_codemp."','0000000001','".$as_numcom."','".$ls_numope."','".$ls_fecfac."',".
					"			   '".$ls_numfac."','".$ls_numcon."','".$ls_numnd."','".$ls_numnc."','".$ls_tiptrans."',".
					"			   '".$ls_tot_cmp_sin_iva."','".$ls_tot_cmp_con_iva."','".$ls_basimp."','".$li_porret."',".
					"			   '".$ls_totimp."','".$ls_ivaret."','','".$ls_numsop."','','','".$ls_numdoc."','')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->sigesp_scb_c_modcmpret.php;MÉTODO->uf_insert_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////           SEGURIDAD             /////////////////////////////////	
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Detalle ".$ls_numope." del comprobate ".$as_numcom.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////           SEGURIDAD             /////////////////////////////////	
				$lb_valido=$this->uf_actualizar_estcmp($ls_numfac,$as_codigo,$as_probene);
		    }
		}
		return $lb_valido;
	}// end function uf_insert_recepciones

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dt_cmpret($as_numcom,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_dt_cmpret
		//		   Access: private
		//	    Arguments: as_numcom           // Número del Comprobante
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error 
		//	  Description: Funcion que elimina los detalles de un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scb_dt_cmp_ret
				  WHERE codemp='".$this->ls_codemp."'
				    AND numcom='".trim($as_numcom)."'
				    AND codret='0000000001'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->sigesp_scb_c_modcmpret.php;MÉTODO->uf_delete_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó los Detalle del comprobate ".$as_numcom." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_recepciones

	function uf_update_cmpret($as_numcom,$ai_totrowrecepciones,$as_probene,$as_codigo,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//                 ai_totrowrecepciones // Total de Filas Detalles del Comprobante 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error 
		//	  Description: Funcion que actualiza los detalles de un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_delete_dt_cmpret($as_numcom,$aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_dt_cmpret($as_numcom,$ai_totrowrecepciones,$as_probene,$as_codigo,$aa_seguridad);
		}
		return $lb_valido;
	}
 
    //------------------------------------------------------------------------------------------------------------ 
    function uf_buscar_ultimo($as_numcom)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_ultimo
		//		   Access: public
		//		 Argument: as_numcom // Número de comprobante
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Función que busca el ultimo comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 29/04/2007		   Fecha Última Modificación : 30/06/2008.
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_periodo=substr($as_numcom,0,6);
		$codigo =substr($as_numcom,7,8);
		settype($codigo,'int');                            
		$codigo =$codigo + 1;                             
		settype($codigo,'string');                         
		$ls_nrocomp=$this->io_funciones->uf_cerosizquierda($codigo,8);
		$ls_numcom=$ls_periodo.$ls_nrocomp;
		
		$ls_sql="SELECT numcom
				   FROM scb_cmp_ret
				   WHERE codemp='".$this->ls_codemp."'
				     AND numcom= '".$ls_numcom."' 
				     AND codret='0000000001'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){
			$this->io_mensajes->message("CLASE->sigesp_scb_c_modcmpret.php;MÉTODO->uf_buscar_ultimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else{
		    
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=false;
			}
		}   
		
		return $lb_valido;
		
	}// end function uf_buscar_ultimo

    //----------------------------------------------------------------------------------------------------------------
    function uf_delete_cmpret($as_numcom,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que elimina fisicamente la cabezera del comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_flag=$this->uf_delete_dt_cmpret($as_numcom,$aa_seguridad);
		if($lb_flag)
		{	
			$ls_sql="DELETE FROM scb_cmp_ret ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND numcom='".$as_numcom."'".
					"   AND codret='0000000001'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->sigesp_scb_c_modcmpret.php;MÉTODO->uf_delete_cmpret;ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el comprobante ".$as_numcom." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_delete_cmpret  

	function uf_anular_cmpret($as_numcom,$aa_seguridad)
    {
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_anular_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
        //				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que coloca en estado anulado al comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="UPDATE scb_cmp_ret
                    SET estcmpret=0
                  WHERE numcom ='".$as_numcom."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->sigesp_scb_c_modcmpret.php;MÉTODO->uf_insert_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else{
				
			  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			  $ls_evento="UPDATE";
			  $ls_descripcion ="Anulo el comprobante ".$as_numcom." Asociado a la empresa ".$this->ls_codemp;
			  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		      /////////////////////////////////         SEGURIDAD               /////////////////////////////	
		    }			
		return $lb_valido;
	}// end function uf_anular_cmpret

	function uf_actualizar_estcmp($as_numrecdoc,$as_codprobene,$as_tipproben)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estcmp
		//		   Access: public
		//		 Argument: $as_numrecdoc  // Número de Recepcion de Documento
		//                 $as_codprobene // Codigo del proveedor o beneficiario 
		//                 $as_tipproben  // Indica si el codprobene es un proveedor o un beneficiario 
		//	  Description: Función que actualiza el campo estcmp al valor 1 en la tabla cxp_rd_deducciones lo
		//                 que indica que ese item ya fue procesado en un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_tipproben=="P"){
		   $ls_filtro="cod_pro='".$as_codprobene."'";
		 }
		 elseif($as_tipproben="B"){
		   $ls_filtro="ced_bene='".$as_codprobene."'";
		 }
		$ls_sql="UPDATE cxp_rd_deducciones
				    SET estcmp='1'
		          WHERE codemp='".$this->ls_codemp."'
				    AND numrecdoc='".$as_numrecdoc."' 
					AND codded IN (SELECT codded FROM sigesp_deducciones WHERE iva='1')
				    AND $ls_filtro";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{	
				$this->io_mensajes->message("CLASE->sigesp_scb_c_modcmpret.php;MÉTODO->uf_actualizar_estcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
    }	
	
    function uf_liberar_recepciones($as_numcom,$as_tipproben,$as_codprobene)
    {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberar_recepciones
		//		   Access: private
		//	    Arguments: as_codded             // Codigo de la deduccion
		//				   ls_numcom            // numero de comprobante de retencion
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que cambia el estatus estcmp de la tabla cxp_rd_deducciones de 1 a 0  
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($as_tipproben=="P")
		   {
		     $ls_filtro2="cod_pro='".$as_codprobene."' AND ced_bene='----------'";
	  	   }
		else
		   {
			 $ls_filtro2="ced_bene='".$as_codprobene."' AND cod_pro='----------'";
		   }
		$rs_data=$this->uf_load_dt_cmpret($as_numcom);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_scb_c_modcmpret.php;MÉTODO->uf_liberar_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numrecdoc=$row["numfac"];
				$ls_sql="UPDATE cxp_rd_deducciones
						    SET estcmp='0'
						  WHERE codemp='".$this->ls_codemp."'
						    AND codded IN (SELECT codded FROM sigesp_deducciones WHERE iva=1 AND islr=0 AND estretmun=0 AND otras=0)
						    AND numrecdoc ='".$ls_numrecdoc."'
						    AND $ls_filtro2";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_rd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		return $lb_valido;
	}// end function uf_liberar_recepciones

    function uf_liberar_rd($as_tipproben,$as_codprobene,$ai_totrowrecepciones)
    {
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberar_rd
		//		   Access: private
		//	    Arguments: as_tipproben          // Campo que indica si se va a procesar un Proveedor o un Beneficiario
        //				   as_codprobene         // Codigo de Proveedor o Beneficiario
		//                 ai_totrowrecepciones  // Total de Filas Detalles del Comprobante 
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que cambia el estatus estcmp de la tabla cxp_rd_deducciones de 1 a 0  
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($as_tipproben=="P")
		   {
		     $ls_filtro2="cod_pro='".$as_codprobene."' AND ced_bene='----------'";
		   }
		else
		   {
		     $ls_filtro2="ced_bene='".$as_codprobene."' AND cod_pro='----------'";
		   }
		for ($li_i=1;($li_i<$ai_totrowrecepciones)&&($lb_valido);$li_i++)
		    {
			  $ls_numdoc=$_POST["txtnumdoc".$li_i];
			  $ls_sql = "UPDATE cxp_rd_deducciones
                            SET estcmp='0'
                          WHERE codemp='".$this->ls_codemp."'
					        AND codded IN (SELECT codded FROM sigesp_deducciones WHERE iva=1 AND islr=0 AND estretmun=0 AND otras=0)
                            AND numrecdoc ='".$ls_numdoc."'
                            AND $ls_filtro2";
			  $li_row=$this->io_sql->execute($ls_sql);
			  if ($li_row===false)
			     {
				   $lb_valido=false;
				   $this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_rd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			     }
		    }
		return $lb_valido;
	}// end function uf_liberar_rd
}
?>