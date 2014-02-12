<?php
class sigesp_cxp_c_modcmpret
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $io_dscuentas;
	var $as_path;

	//----------------------------------------------------------------------------------------------------------------
	function sigesp_cxp_c_modcmpret($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_cxp_c_modcmpret
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 18/09/2007 								Fecha Última Modificación : 21/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$this->io_include=new sigesp_include();
		$this->io_conexion=$this->io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_basdatcmp=$_SESSION["la_empresa"]["basdatcmp"];
		$this->io_sqlaux="";
		$this->as_path=$as_path;
	}// end function sigesp_cxp_c_solicitudpago
	//-------------------------------------------------------------------------------------------------------------------------
	
	//-------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_cxp_c_modcmpret.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($this->io_include);
		unset($this->io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-------------------------------------------------------------------------------------------------------------------------

	function uf_load_dt_cmpret($as_numcom,$as_codret)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_dt_cmpret
		//		   Access: public
		//		 Argument: as_numcom // Número del Comprobante
		//		           as_codret // Codigo de la Retencion
		//	  Description: Función que busca los Comprobantes de Retencion
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007								Fecha Última Modificación : 21/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT DCMRT.numope,DCMRT.numfac,DCMRT.numcon,DCMRT.fecfac,DCMRT.totcmp_sin_iva,DCMRT.totcmp_con_iva,".
				"       DCMRT.basimp,DCMRT.porimp,DCMRT.totimp,DCMRT.iva_ret,DCMRT.numdoc,DCMRT.codret,DCMRT.numsop,DCMRT.numnd,".
				"       DCMRT.numnc,DCMRT.tiptrans ".
				"  FROM scb_dt_cmp_ret DCMRT ".	
				"  WHERE codemp='".$this->ls_codemp."' ".
				"  AND numcom= '".$as_numcom."' AND codret='".$as_codret."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_load_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_dt_cmpret

//-------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dt_cmpret($as_numcom,$as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo, $aa_seguridad)
	{
		 ///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dt_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante 
		//				   as_codret            // Código de la retencion
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
					"     VALUES  ('".$this->ls_codemp."','".$as_codret."','".$as_numcom."','".$ls_numope."','".$ls_fecfac."',".
					"			   '".$ls_numfac."','".$ls_numcon."','".$ls_numnd."','".$ls_numnc."','".$ls_tiptrans."',".
					"			   '".$ls_tot_cmp_sin_iva."','".$ls_tot_cmp_con_iva."','".$ls_basimp."','".$ls_porimp."',".
					"			   '".$ls_totimp."','".$ls_ivaret."','','".$ls_numsop."','','','".$ls_numdoc."','')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_insert_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
				$lb_valido=$this->uf_actualizar_estcmp($ls_numfac,$as_codigo,$as_codret,$as_probene);
				if($this->ls_basdatcmp!="")
				{
					$lb_valido=$this->uf_guardar_detallecmp_consolida($as_codret,$as_numcom,$ls_numope,$ls_fecfac,$ls_numfac,
																	  $ls_numcon,$ls_numnd,$ls_numnc,$ls_tiptrans,$ls_tot_cmp_sin_iva,
											 						  $ls_tot_cmp_con_iva,$ls_basimp,$ls_porimp,$ls_totimp,
																	  $ls_ivaret,"",$ls_numsop,"","",
																	  $ls_numdoc,"");
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_recepciones
	//-------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------
    function uf_guardar_detallecmp_consolida($as_codret,$as_numcom,$as_numope,$as_fecfac,$as_numfac,$as_numcon,$as_numnd,$as_numnc,$as_tiptrans,$as_tot_cmp_sin_iva,
											 $as_tot_cmp_con_iva,$as_basimp,$as_porimp,$as_totimp,$as_ivaret,$as_desope,$as_numsop,$as_codban,$as_ctaban,
											 $as_numdoc,$as_codope)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_crear_comprobante
		//	        Access: public
		//	      Argument: $as_codret // Codigo de la retencion,$as_numcom // Numero del comprobante
		//                  $as_fecrep // Fecha del comprobante,$as_perfiscal // perido fiscal
		//                  $as_codsujret // Codigo del proveedor o beneficiario,$as_nomsujret // Nombre del proveedor o beneficiario
		//                  $as_dirsujret // Direccion del proveedor o beneficiario ,$as_rif // RIF del proveedor o beneficiario
		//                  $as_nit // NIT del proveedor ,$as_estcmpret // Estatus del comprobante,
		//                  $as_codusu // codigo del usuario ,$as_numlic // Numero de licencia del proveedor,$as_origen 
		//     Description: Función que guarda la cabezera de un comprobante de retencion  
		//	    Creado Por: Ing. Gerardo Cordero
		//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " INSERT INTO scb_dt_cmp_ret (codemp,codret,numcom,numope,fecfac,numfac,numcon,numnd,numnc,tiptrans,".
		          "                             totcmp_sin_iva,totcmp_con_iva,basimp,porimp,totimp,iva_ret,desope,". 
				  "                              numsop,codban,ctaban,numdoc,codope) ".
				  " VALUES  ('".$this->ls_codemp."','".$as_codret."','".$as_numcom."','".$as_numope."',".
				  "          '".$as_fecfac."','".$as_numfac."','".$as_numcon."','".$as_numnd."','".$as_numnc."',".
				  "          '".$as_tiptrans."','".$as_tot_cmp_sin_iva."','".$as_tot_cmp_con_iva."','".$as_basimp."',".
				  "          '".$as_porimp."','".$as_totimp."','".$as_ivaret."','".$as_desope."','".$as_numsop."',".
				  "          '".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."')";
		$li_result=$this->io_sqlaux->execute($ls_sql);
		if($li_result===false)
		{	
			$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_guardar_detallecmp_consolida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
	}//FIN DE LA FUNCION uf_crear_comprobante
	//-------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dt_cmpret($as_numcom,$as_codret, $aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_dt_cmpret
		//		   Access: private
		//	    Arguments: as_numcom           // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error 
		//	  Description: Funcion que elimina los detalles de un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scb_dt_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numcom='".$as_numcom."'".
				"   AND codret='".$as_codret."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_delete_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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

	function uf_update_cmpret($as_numcom, $as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//                  ai_totrowrecepciones // Total de Filas Detalles del Comprobante 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error 
		//	  Description: Funcion que actualiza los detalles de un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_delete_dt_cmpret($as_numcom, $as_codret, $aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_dt_cmpret($as_numcom, $as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo, $aa_seguridad);
		}
		$ls_bdorigen=$this->uf_obtener_bdorigen($as_numcom,$as_codret);
		if($ls_bdorigen!="")
		{
			$lb_valido=$this-> uf_update_cmpret_consolida($as_numcom, $as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo,$aa_seguridad);
		}
		return $lb_valido;
	}
 
	function uf_update_cmpret_consolida($as_numcom, $as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//                  ai_totrowrecepciones // Total de Filas Detalles del Comprobante 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error 
		//	  Description: Funcion que actualiza los detalles de un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_delete_dt_cmpret_consolida($as_numcom, $as_codret, $aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_dt_cmpret_consolida($as_numcom, $as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo, $aa_seguridad);
		}
		return $lb_valido;
	}
 //------------------------------------------------------------------------------------------------------------ 
 function uf_buscar_ultimo($as_numcom,$as_codret)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_ultimo
		//		   Access: public
		//		 Argument: as_numcom // Número de comprobante
		//				   as_codret // Codigo de la retencion
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Función que busca el ultimo comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 29/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_periodo=substr($as_numcom,0,6);
		$codigo =substr($as_numcom,7,8);
		settype($codigo,'int');                            
		$codigo =$codigo + 1;                             
		settype($codigo,'string');                         
		$ls_nrocomp=$this->io_funciones->uf_cerosizquierda($codigo,8);
		$ls_numcom=$ls_periodo.$ls_nrocomp;
		
		$ls_sql="SELECT numcom".
				"  FROM scb_cmp_ret".	
				"  WHERE codemp='".$this->ls_codemp."' ".
				"  AND numcom= '".$ls_numcom."' and codret='".$as_codret."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_buscar_ultimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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

   function uf_delete_cmpret($as_numcom,$as_codret,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que elimina fisicamente la cabezera del comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_flag=$this->uf_delete_dt_cmpret($as_numcom, $as_codret, $aa_seguridad);
		if($lb_flag)
		{	
			$ls_sql="DELETE FROM scb_cmp_ret ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND numcom='".$as_numcom."'".
					"   AND codret='".$as_codret."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el comprobate ".$as_numcom." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_delete_cmpret
  
  function uf_liberar_rd($as_codded,$as_probene,$as_codprobene,$ai_totrowrecepciones)
    {
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberar_rd
		//		   Access: private
		//	    Arguments: as_codded             // Codigo de la deduccion
        //				   as_probene            // Campo que indica si se va a procesar un Proveedor o un Beneficiario
        //				   as_codprobene         // Codigo de Proveedor o Beneficiario
		//                 ai_totrowrecepciones  // Total de Filas Detalles del Comprobante 
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que cambia el estatus estcmp de la tabla cxp_rd_deducciones de 1 a 0  
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codded=="0000000001"){
		  $ls_filtro="codded IN (SELECT codded FROM sigesp_deducciones WHERE iva='1')";
		 }
		 elseif($as_codded=="0000000003")
		 {
		  $ls_filtro="codded IN (SELECT codded FROM sigesp_deducciones WHERE estretmun='1')";
		 }
		 elseif($as_codded=="0000000004")
		 {
		  	$ls_filtro="codded IN (SELECT codded FROM sigesp_deducciones WHERE retaposol='1')";
		 }
		 
		 if($as_probene=="P"){
		  $ls_filtro2="cod_pro='".$as_codprobene."'";
		 }
		 else{
		  $ls_filtro2="ced_bene='".$as_codprobene."'";
		 }
		for($li_i=1;($li_i<$ai_totrowrecepciones)&&($lb_valido);$li_i++)
		{
			$ls_numdoc=$_POST["txtnumdoc".$li_i];
			$ls_sql="UPDATE cxp_rd_deducciones ".
                    "SET estcmp='0' ".
                    "WHERE codemp='".$this->ls_codemp."'".
					" AND(".$ls_filtro.") ".
                    "AND (numrecdoc ='".$ls_numdoc."') ".
                    "AND (".$ls_filtro2.")";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_rd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		return $lb_valido;
	}// end function uf_liberar_rd
	
  function uf_liberar_recepciones($as_codded,$as_numcom,$as_probene,$as_codprobene)
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
		if($as_codded=="0000000001")
		{
			$ls_filtro="codded IN (SELECT codded FROM sigesp_deducciones WHERE iva='1')";
		}
		else
		{
			$ls_filtro="codded IN (SELECT codded FROM sigesp_deducciones WHERE estretmun='1')";
		}
		if($as_probene=="P")
		{
			$ls_filtro2="cod_pro='".$as_codprobene."'";
		}
		else
		{
			$ls_filtro2="ced_bene='".$as_codprobene."'";
		}
		$rs_data=$this->uf_load_dt_cmpret($as_numcom,$as_codded);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numrecdoc=$row["numfac"];
				$ls_sql="UPDATE cxp_rd_deducciones ".
						"   SET estcmp='0' ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND(".$ls_filtro.") ".
						"   AND (numrecdoc ='".$ls_numrecdoc."') ".
						"   AND (".$ls_filtro2.")";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_rd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		if($this->ls_basdatcmp=="")
		{
			$lb_valido=$this->uf_liberar_recepciones_consolida($as_codded,$as_numcom,$as_probene,$as_codprobene,$as_hostname);
		}
		return $lb_valido;
	}// end function uf_liberar_recepciones

  function uf_liberar_recepciones_consolida($as_codded,$as_numcom,$as_probene,$as_codprobene,&$as_hostname)
  {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberar_recepciones_consolida
		//		   Access: private
		//	    Arguments: as_codded             // Codigo de la deduccion
		//				   ls_numcom            // numero de comprobante de retencion
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que cambia el estatus estcmp de la tabla cxp_rd_deducciones de 1 a 0  
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_hostname="";
		$ls_bdorigen=$this->uf_obtener_bdorigen($as_numcom,$as_codded);
		if($ls_bdorigen!="")
		{
			$this->io_include->uf_obtener_parametros_conexion($this->as_path,$ls_bdorigen,&$as_hostname,&$as_login,&$as_password,&$as_gestor);
		}
		if($as_hostname!="")
		{
				$io_connectconsolida=$this->io_include->uf_conectar_otra_bd($as_hostname, $as_login, $as_password,$ls_bdorigen,$as_gestor);
				$this->io_sqlconsolida=new class_sql($io_connectconsolida);
				if($as_codded=="0000000001")
				{
					$ls_filtro="codded IN (SELECT codded FROM sigesp_deducciones WHERE iva='1')";
				}
				else
				{
					$ls_filtro="codded IN (SELECT codded FROM sigesp_deducciones WHERE estretmun='1')";
				}
				if($as_probene=="P")
				{
					$ls_filtro2="cod_pro='".$as_codprobene."'";
				}
				else
				{
					$ls_filtro2="ced_bene='".$as_codprobene."'";
				}
				$rs_data=$this->uf_load_dt_cmpret($as_numcom,$as_codded);
				if($rs_data===false)
				{
					$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_recepciones_consolida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					return false;
				}
				else
				{
					while($row=$this->io_sql->fetch_row($rs_data))
					{
						$ls_numrecdoc=$row["numfac"];
						$ls_sql="UPDATE cxp_rd_deducciones ".
								"   SET estcmp='0' ".
								" WHERE codemp='".$this->ls_codemp."'".
								"   AND(".$ls_filtro.") ".
								"   AND (numrecdoc ='".$ls_numrecdoc."') ".
								"   AND (".$ls_filtro2.")";
						$li_row=$this->io_sqlconsolida->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_recepciones_consolida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sqlconsolida->message)); 
						}
					}
				}
		}
		return $lb_valido;
	}// end function uf_liberar_recepciones

 	//------------------------------------------------------------------------------------------------------------ 
	function uf_obtener_bdorigen($as_numcom,$as_codret)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_bdorigen
		//		   Access: public
		//		 Argument: as_numcom // Número de comprobante
		//				   as_codret // Codigo de la retencion
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Función que busca el ultimo comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 29/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_basdatori="";
		$ls_sql="SELECT basdatori".
				"  FROM scb_cmp_ret".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numcom= '".$as_numcom."'".
				"   AND codret='".$as_codret."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_obtener_bdorigen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_basdatori=$row["basdatori"];
			}
		}   
		return $ls_basdatori;
	}// end function uf_obtener_bdorigen

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
		$ls_sql="UPDATE scb_cmp_ret ".
                "SET estcmpret='0' ".
                "WHERE (numcom ='".$as_numcom."') ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_insert_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else{
				
			  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			  $ls_evento="UPDATE";
			  $ls_descripcion ="Anulo el comprobate ".$as_numcom." Asociado a la empresa ".$this->ls_codemp;
			  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		      /////////////////////////////////         SEGURIDAD               /////////////////////////////	
		    }
			
		return $lb_valido;
	}// end function uf_anular_cmpret

	function uf_actualizar_estcmp($as_numrecdoc,$as_codprobene,$as_codded,$as_tipo)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estcmp
		//		   Access: public
		//		 Argument: $as_numrecdoc // Número de Recepcion de Documento
		//                 $as_codprobene // Codigo del proveedor o beneficiario 
		//                 $as_codret // Codigo de Retencion 
		//                 $as_tipo // Indica si el codprobene es un proveedor o un beneficiario 
		//	  Description: Función que actualiza el campo estcmp al valor 1 en la tabla cxp_rd_deducciones lo
		//                 que indica que ese item ya fue procesado en un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codded=="0000000001")
		{
			$ls_cadena=" AND codded IN (SELECT codded FROM sigesp_deducciones WHERE iva='1')";
		}
		else
		{
			$ls_cadena="AND codded IN (SELECT codded FROM sigesp_deducciones WHERE estretmun='1')";
		}
		if($as_tipo=="P"){
		   $ls_filtro="cod_pro='".$as_codprobene."'";
		 }
		 elseif($as_tipo="B"){
		   $ls_filtro="ced_bene='".$as_codprobene."'";
		 }
		$ls_sql="UPDATE cxp_rd_deducciones".
				"   SET estcmp='1'".
		        " WHERE codemp='".$this->ls_codemp."'".
				"   AND numrecdoc='".$as_numrecdoc."'". 
				"   AND ".$ls_filtro."".
				$ls_cadena;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{	
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_actualizar_estcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
    }	  
	//-------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dt_cmpret_consolida($as_numcom,$as_codret, $aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_dt_cmpret_consolida
		//		   Access: private
		//	    Arguments: as_numcom           // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error 
		//	  Description: Funcion que elimina los detalles de un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scb_dt_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numcom='".$as_numcom."'".
				"   AND codret='".$as_codret."'";
		$li_row=$this->io_sqlconsolida->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_delete_dt_cmpret_consolida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_delete_dt_cmpret_consolida
	function uf_insert_dt_cmpret_consolida($as_numcom,$as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo, $aa_seguridad)
	{
		 ///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dt_cmpret_consolida
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante 
		//				   as_codret            // Código de la retencion
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
					"     VALUES  ('".$this->ls_codemp."','".$as_codret."','".$as_numcom."','".$ls_numope."','".$ls_fecfac."',".
					"			   '".$ls_numfac."','".$ls_numcon."','".$ls_numnd."','".$ls_numnc."','".$ls_tiptrans."',".
					"			   '".$ls_tot_cmp_sin_iva."','".$ls_tot_cmp_con_iva."','".$ls_basimp."','".$ls_porimp."',".
					"			   '".$ls_totimp."','".$ls_ivaret."','','".$ls_numsop."','','','".$ls_numdoc."','')";
			$li_row=$this->io_sqlconsolida->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_insert_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				$lb_valido=$this->uf_actualizar_estcmp_consolida($ls_numfac,$as_codigo,$as_codret,$as_probene);
			}
		}
		return $lb_valido;
	}// end function uf_insert_recepciones
	//-------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------
	function uf_actualizar_estcmp_consolida($as_numrecdoc,$as_codprobene,$as_codded,$as_tipo)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estcmp
		//		   Access: public
		//		 Argument: $as_numrecdoc // Número de Recepcion de Documento
		//                 $as_codprobene // Codigo del proveedor o beneficiario 
		//                 $as_codret // Codigo de Retencion 
		//                 $as_tipo // Indica si el codprobene es un proveedor o un beneficiario 
		//	  Description: Función que actualiza el campo estcmp al valor 1 en la tabla cxp_rd_deducciones lo
		//                 que indica que ese item ya fue procesado en un comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codded=="0000000001")
		{
			$ls_cadena=" AND codded IN (SELECT codded FROM sigesp_deducciones WHERE iva='1')";
		}
		else
		{
			$ls_cadena="AND codded IN (SELECT codded FROM sigesp_deducciones WHERE estretmun='1')";
		}
		if($as_tipo=="P"){
		   $ls_filtro="cod_pro='".$as_codprobene."'";
		 }
		 elseif($as_tipo="B"){
		   $ls_filtro="ced_bene='".$as_codprobene."'";
		 }
		$ls_sql="UPDATE cxp_rd_deducciones".
				"   SET estcmp='1'".
		        " WHERE codemp='".$this->ls_codemp."'".
				"   AND numrecdoc='".$as_numrecdoc."'". 
				"   AND ".$ls_filtro."".
				$ls_cadena;
		$li_result=$this->io_sqlconsolida->execute($ls_sql);
		if($li_result===false)
		{	
			$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_actualizar_estcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
    }	  
	//-------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------
	function uf_anular_cmpret_consolida($as_codret,$as_numcom,$as_probene,$as_codigo)
    {
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_anular_cmpret_consolida
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
        //				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que coloca en estado anulado al comprobante
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_liberar_recepciones_consolida($as_codret,$as_numcom,$as_probene,$as_codigo,$as_hostname);
		if(($lb_valido)&&($as_hostname!=""))
		{
			$ls_sql="UPDATE scb_cmp_ret ".
					"SET estcmpret='0' ".
					"WHERE (numcom ='".$as_numcom."')";
			$li_row=$this->io_sqlconsolida->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_anular_cmpret_consolida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sqlconsolida->message)); 
			}
		}
		return $lb_valido;
	}// end function uf_anular_cmpret
	//-------------------------------------------------------------------------------------------------------------------------
	
}
?>