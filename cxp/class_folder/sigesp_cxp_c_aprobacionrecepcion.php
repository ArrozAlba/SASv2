<?php
class sigesp_cxp_c_aprobacionrecepcion
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cxp_c_aprobacionrecepcion($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_cxp_c_aprobacionrecepcion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007 								Fecha Última Modificación : 
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_sep_c_aprobacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/05/2007								Fecha Última Modificación : 
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
	function uf_load_recepciones($as_numsol,$ad_fecregdes,$ad_fecreghas,$as_tipproben,$as_proben,$as_tipooperacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_recepciones
		//		   Access: public
		//		 Argument: as_numsol        // Numero de la solicitud de ejecucion presupuestaria
		//                 ad_fecregdes     // Fecha (Emision) de inicio de la Busqueda
		//                 ad_fecreghas     // Fecha (Emision) de fin de la Busqueda
		//                 as_tipproben     // tipo proveedor/ beneficiario
		//                 as_proben        // Codigo de proveedor/ beneficiario
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: Función que busca las recepciones  a aprobar o reversar aprobacion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(nombene,' ',apebene)";
				break;
			case "POSTGRES":
				$ls_cadena="nombene||' '||apebene";
				break;
			case "INFORMIX":
				$ls_cadena="nombene||' '||apebene";
				break;
		}
		$ls_sql="SELECT cxp_rd.numrecdoc,cxp_rd.fecregdoc,cxp_rd.estaprord,cxp_rd.montotdoc,cxp_rd.tipproben,".
				"       cxp_rd.cod_pro,cxp_rd.ced_bene,cxp_rd.codtipdoc,".
				"       (CASE WHEN cxp_rd.tipproben='B' THEN (SELECT ".$ls_cadena." ".
				"                                               FROM rpc_beneficiario".
				"                                              WHERE cxp_rd.codemp=rpc_beneficiario.codemp".
				"                                                AND cxp_rd.ced_bene=rpc_beneficiario.ced_bene)".
				"             WHEN cxp_rd.tipproben='P' THEN (SELECT nompro".
				"                                               FROM rpc_proveedor".
				"                                              WHERE cxp_rd.codemp=rpc_proveedor.codemp".
				"                                                AND cxp_rd.cod_pro=rpc_proveedor.cod_pro)".
				"                                       ELSE 'NINGUNO'".
				"         END) AS nombre,".
				"		(SELECT count(cxp_rd_spg.numrecdoc) ".
				"		   FROM cxp_rd_spg ".
				"		  WHERE cxp_rd.codemp=cxp_rd_spg.codemp ".
				"			AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc ".
				"			AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc ".
				"			AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro".
				"			AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene) as rowspg,".
				"		(SELECT count(cxp_rd_scg.numrecdoc) ".
				"		   FROM cxp_rd_scg ".
				"		  WHERE cxp_rd.codemp=cxp_rd_scg.codemp ".
				"			AND cxp_rd.numrecdoc=cxp_rd_scg.numrecdoc ".
				"			AND cxp_rd.codtipdoc=cxp_rd_scg.codtipdoc ".
				"			AND cxp_rd.cod_pro=cxp_rd_scg.cod_pro".
				"			AND cxp_rd.ced_bene=cxp_rd_scg.ced_bene) as rowscg ".
				"  FROM cxp_rd".
				" WHERE cxp_rd.codemp = '".$this->ls_codemp."'".
				"   AND cxp_rd.numrecdoc LIKE '".$as_numsol."' ".
				"   AND cxp_rd.fecregdoc >= '".$ad_fecregdes."' ".
				"   AND cxp_rd.fecregdoc <= '".$ad_fecreghas."' ".
				"   AND cxp_rd.estprodoc='R'".
				"   AND cxp_rd.estaprord='".$as_tipooperacion."'";
		if($as_tipproben=="B")
		{
			$ls_sql= $ls_sql." AND cxp_rd.ced_bene LIKE '".$as_proben."'";
		}
		else
		{
			$ls_sql= $ls_sql." AND cxp_rd.cod_pro LIKE'".$as_proben."'";
		}
		$ls_sql= $ls_sql." ORDER BY cxp_rd.numrecdoc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_load_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_estatus_recepcion($as_numrecdoc,$as_estsol,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_numrecdoc  //  Número de Recepcion de Documentos
		//				   as_estsol     //  Estatus de la Solicitud
		//				   as_codpro     //  Codigo de Proveedor
		//				   as_cedben     //  Codigo de Beneficiario
		//				   as_codtipdoc  //  Codigo de Tipo de Documento
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numrecdoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numrecdoc='".$as_numrecdoc."' ".
				"   AND cod_pro='".$as_codpro."' ".
				"   AND ced_bene='".$as_cedben."' ".
				"   AND codtipdoc='".$as_codtipdoc."' ".
				"   AND estaprord=".$as_estsol."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_estatus_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_validar_estatus_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_recepciones($as_numrecdoc,$as_estrd,$as_codpro,$as_cedben,$as_codtipdoc,$ad_fecaprord,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_recepciones
		//		   Access: private
		//	    Arguments: as_numrecdoc  //  Número de Recepcion de Documentos
		//                 as_estrd      //  Estatus en que se desea colocar la Recepcion de Documentos
		//                 as_codpro     //  Codigo de Proveedor
		//                 as_cedben     //  Codigo de Beneficiario
		//                 as_codtipdoc  //  Codigo de Tipo de Documento
		//                 ad_fecaprord  //  Fecha de aprobacion de la Recepcion de Documentos
		//                 aa_seguridad  //  Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que actualiza el estatus de aprobacion de la recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecaprord,$this->ls_codemp);
		if (!$lb_valido)
		{
			$this->io_mensajes->message($this->io_fecha->is_msg_error);           
			return false;
		}
		$ls_usuario=$_SESSION["la_logusr"];
		if($as_estrd==0)
		{
			$ad_fecaprsep="1900-01-01";
			$ls_usuario="";
		}
		$ad_fecaprord=$this->io_funciones->uf_convertirdatetobd($ad_fecaprord);
		$ls_sql="UPDATE cxp_rd ".
				"   SET estaprord = ".$as_estrd.", ".
				"       fecaprord = '".$ad_fecaprord."', ".
				"		usuaprord = '".$ls_usuario."' ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND numrecdoc = '".$as_numrecdoc."' ".
				"	AND cod_pro = '".$as_codpro."' ".
				"	AND ced_bene = '".$as_cedben."' ".
				"	AND codtipdoc = '".$as_codtipdoc."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_update_estatus_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			if($as_estrd==1)
			{
				$ls_descripcion ="Aprobó la Recepcion de Documentos <b>".$as_numrecdoc."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			else
			{
				$ls_descripcion ="Reversó la Recepcion de Documentos <b>".$as_numrecdoc."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_estatus_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_recepciones($as_numrecdoc,$as_codpro,$as_cedben,$as_codtipdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_recepciones
		//		   Access: public
		//		 Argument: as_numrecdoc  // Numero de la recepcion de documentos
		//                 as_codpro     //  Codigo de Proveedor
		//                 as_cedben     //  Codigo de Beneficiario
		//                 as_codtipdoc  //  Codigo de Tipo de Documento
		//	  Description: Función que verifica que una recepcion de documentos este en estatus de registro
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numrecdoc".
				"  FROM cxp_rd".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND numrecdoc = '".$as_numrecdoc."'".
				"   AND cod_pro = '".$as_codpro."'".
				"   AND ced_bene = '".$as_cedben."'".
				"   AND codtipdoc = '".$as_codtipdoc."'".
				"   AND estprodoc = 'R' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
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
	}// end function uf_validar_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>