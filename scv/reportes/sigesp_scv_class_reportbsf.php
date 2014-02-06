<?php

class sigesp_scv_class_reportbsf
{
	var $obj="";
	var $io_sql;
	var $ds;
	var $ds_detalle;
	var $siginc;
	var $con;

	function sigesp_scv_class_reportbsf()
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
		$this->ds=new class_datastore();
		$this->ds_detalle=new class_datastore();
		$this->ds_detpersonal=new class_datastore();
		$this->ds_detcontable=new class_datastore();
		$this->ds_detpresup=new class_datastore();
		$this->ds_solicitud=new class_datastore();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////         Funciones del formato de salida de la solicitud de viaticos          ///////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_select_solicitudviaticos($as_codemp,$as_codsolvia,$ad_fecdes,$ad_fechas,$as_codsoldes,$as_codsolhas,
										 $as_coduniadm,$as_codper,$ai_orden)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudviaticos
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsolvia  // numero de la solicitud de viaticos
		//  			       $ad_fecdes     // fecha de inicio del periodo de busqueda
		//  			       $ad_fechas     // fecha de cierre del periodo de busqueda
		//  			       $as_codsoldes  // numero de la solicitud de viaticos Desde
		//  			       $as_codsolhas  // numero de la solicitud de viaticos Hasta
		//  			       $as_coduniadm  // codigo de unidad ejecutora
		//  			       $as_codper     // codigo de personal / beneficiario
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de la busqueda de un maestro de solititud de viaticos
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 29/11/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT scv_solicitudviatico.codsolvia, MAX(scv_solicitudviatico.codmis) AS codmis, MAX(scv_solicitudviatico.codrut) AS codrut, ".
		        "       MAX(scv_solicitudviatico.coduniadm) AS coduniadm, MAX(scv_solicitudviatico.fecsalvia) AS fecsalvia, MAX(scv_solicitudviatico.fecregvia) AS fecregvia, ".
				"       MAX(scv_solicitudviatico.fecsolvia) AS fecsolvia, MAX(scv_solicitudviatico.numdiavia) AS numdiavia, MAX(scv_solicitudviatico.solviaext) AS solviaext, ".
				"       MAX(scv_solicitudviatico.obssolvia) AS obssolvia, MAX(scv_solicitudviatico.estsolvia) AS estsolvia, MAX(scv_solicitudviatico.monsolviaaux) AS monsolvia,".
				"       (SELECT denmis".
				"          FROM scv_misiones".
				"         WHERE scv_solicitudviatico.codemp=scv_misiones.codemp".
				"           AND scv_solicitudviatico.codmis=scv_misiones.codmis) AS denmis, ".
				"       (SELECT desrut".
				"          FROM scv_rutas".
				"         WHERE scv_solicitudviatico.codemp=scv_rutas.codemp".
				"           AND scv_solicitudviatico.codrut=scv_rutas.codrut".
				"         GROUP BY codrut,desrut) AS desrut, ".
				"       (SELECT denuniadm".
				"          FROM spg_unidadadministrativa".
				"         WHERE scv_solicitudviatico.codemp=spg_unidadadministrativa.codemp".
				"           AND scv_solicitudviatico.coduniadm=spg_unidadadministrativa.coduniadm) AS denuniadm ".
				"  FROM scv_solicitudviatico,scv_dt_personal".
				" WHERE scv_solicitudviatico.codemp='". $as_codemp ."'".
				"   AND scv_solicitudviatico.codemp=scv_dt_personal.codemp".
				"   AND scv_solicitudviatico.codsolvia=scv_dt_personal.codsolvia";
		if(!empty($as_codsolvia))
		{
			$ls_sql=$ls_sql."   AND scv_solicitudviatico.codsolvia='". $as_codsolvia ."'";
		}
		if((!empty($ad_fecdes))&&(!empty($ad_fechas)))
		{
			$ad_fecdes=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
			$ad_fechas=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
			$ls_sql=$ls_sql." AND scv_solicitudviatico.fecsolvia >= '".$ad_fecdes."'".
							" AND scv_solicitudviatico.fecsolvia <='".$ad_fechas."'";
		}
		if((!empty($as_codsoldes))&&(!empty($as_codsolhas)))
		{
			$ls_sql=$ls_sql."   AND scv_solicitudviatico.codsolvia>='".$as_codsoldes."' ".
					  		"   AND scv_solicitudviatico.codsolvia<='".$as_codsolhas."' ";
		}
		if(!empty($as_coduniadm))
		{
			$ls_sql=$ls_sql." AND scv_solicitudviatico.coduniadm='".$as_coduniadm."'";
		}
		if(!empty($as_codper))
		{
			$ls_sql=$ls_sql." AND scv_dt_personal.codper='".$as_codper."'";
		}
		$ls_sql=$ls_sql." GROUP BY scv_solicitudviatico.codsolvia";
		if(!empty($ai_orden))
		{
			if($ai_orden==1)
				$ls_sql=$ls_sql." ORDER BY scv_solicitudviatico.fecsolvia DESC";
			else
				$ls_sql=$ls_sql." ORDER BY scv_solicitudviatico.fecsolvia ";
				
		}
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsfbsf MÉTODO->uf_select_solicitudviaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_solicitudviaticos

	function uf_select_dt_asignaciones($as_codemp,$as_codsolvia,$ad_fecdes,$ad_fechas,$ai_orden)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_asignaciones
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsolvia  // codigo de la solicitud de viaticos
		//  			       $ad_fecdes     // fecha de inicio del periodo de busqueda
		//  			       $ad_fechas     // fecha de cierre del periodo de busqueda
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar las asignaciones de una solicitud de viaticos
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT scv_dt_asignaciones.codemp, scv_dt_asignaciones.codsolvia, scv_dt_asignaciones.codasi,".
				 "       scv_dt_asignaciones.proasi, scv_dt_asignaciones.canasi, ".
				 "       (CASE scv_dt_asignaciones.proasi".
				 "        WHEN 'TVS' THEN (SELECT scv_tarifas.dentar".
				 "                           FROM scv_tarifas".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_tarifas.codemp".
				 " 							  AND scv_dt_asignaciones.codasi=scv_tarifas.codtar)".
				 "        WHEN 'TRP' THEN (SELECT scv_transportes.dentra".
				 "                           FROM scv_transportes".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_transportes.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_transportes.codtra)".
				 "        WHEN 'TOA' THEN (SELECT scv_otrasasignaciones.denotrasi".
				 "                           FROM scv_otrasasignaciones".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_otrasasignaciones.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_otrasasignaciones.codotrasi)".
				 "		  ELSE (SELECT scv_tarifakms.dentar".
				 "                FROM scv_tarifakms".
				 "               WHERE scv_dt_asignaciones.codemp=scv_tarifakms.codemp".
				 "                 AND scv_dt_asignaciones.codasi=scv_tarifakms.codtar) END) AS denasi".
				 "  FROM scv_solicitudviatico,scv_dt_asignaciones".
				 " WHERE scv_solicitudviatico.codemp='".$as_codemp."'".
				 "   AND scv_solicitudviatico.codsolvia='".$as_codsolvia."'".
				 "   AND scv_solicitudviatico.codsolvia=scv_dt_asignaciones.codsolvia";
		
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsfbsf MÉTODO->uf_select_dt_asignaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_dt_asignaciones

	function uf_select_dt_personal($as_codemp,$as_codsolvia,$ad_fecdes,$ad_fechas,$ai_orden,&$lb_existe)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_personal
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsolvia  // codigo de la solicitud de viaticos
		//  			       $ad_fecdes     // fecha de inicio del periodo de busqueda
		//  			       $ad_fechas     // fecha de cierre del periodo de busqueda
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar las asignaciones de una solicitud de viaticos
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_scv_select_categoria_personal($as_codemp,$as_codsolvia);
		if($lb_existe)
		{
			$ls_sql="SELECT (CASE sno_nomina.racnom WHEN 1 THEN sno_asignacioncargo.denasicar ELSE sno_cargo.descar END) AS cargo,".
					"       scv_dt_personal.codclavia,sno_personalnomina.codper,".
					"		(SELECT nomper FROM sno_personal".
					"  		  WHERE sno_personal.codper=sno_personalnomina.codper) as nomper,".
					"		(SELECT apeper FROM sno_personal".
					"   	  WHERE sno_personal.codper=sno_personalnomina.codper) as apeper,".
					"		(SELECT cedper FROM sno_personal".
					"		  WHERE sno_personal.codper=sno_personalnomina.codper) as cedper".
					"  FROM sno_personalnomina, sno_nomina, sno_cargo, sno_asignacioncargo,sno_personal,scv_dt_personal".
					" WHERE scv_dt_personal.codemp='".$as_codemp."'".
					"   AND scv_dt_personal.codsolvia='".$as_codsolvia."'".
					"   AND scv_dt_personal.codemp=sno_personal.codemp".
					"   AND scv_dt_personal.codper=sno_personal.codper".
					"   AND sno_nomina.espnom=0".
					"   AND sno_personalnomina.codemp = sno_nomina.codemp".
					"   AND sno_personalnomina.codnom = sno_nomina.codnom".
					"   AND sno_personalnomina.codper = sno_personal.codper".
					"   AND sno_personalnomina.codemp = sno_cargo.codemp".
					"   AND sno_personalnomina.codnom = sno_cargo.codnom".
					"   AND sno_personalnomina.codcar = sno_cargo.codcar".
					"   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp".
					"   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom".
					"   AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar".
					" GROUP BY sno_personalnomina.codper".
					" ORDER BY sno_personalnomina.codper,codclavia";
		}
		else
		{
			$ls_sql="SELECT scv_dt_personal.codper,rpc_beneficiario.ced_bene,".
					"       (SELECT nombene ".
					"          FROM rpc_beneficiario".
					"         WHERE scv_dt_personal.codemp=rpc_beneficiario.codemp".
					"           AND scv_dt_personal.codper=rpc_beneficiario.ced_bene) AS nombene,".
					"       (SELECT apebene ".
					"          FROM rpc_beneficiario".
					"         WHERE scv_dt_personal.codemp=rpc_beneficiario.codemp".
					"           AND scv_dt_personal.codper=rpc_beneficiario.ced_bene) AS apebene".
					"  FROM scv_dt_personal,rpc_beneficiario".
					" WHERE scv_dt_personal.codemp='".$as_codemp."'".
					"   AND scv_dt_personal.codsolvia='".$as_codsolvia."'".
					"   AND scv_dt_personal.codper=rpc_beneficiario.ced_bene";
		}
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsfbsf MÉTODO->uf_select_dt_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detpersonal->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_dt_personal

	function uf_select_dt_spg($as_codemp,$as_codsolvia)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_spg
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsolvia  // codigo de la solicitud de viaticos
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar las asignaciones de una solicitud de viaticos
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT codemp,codsolvia, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, montoaux AS monto, spg_cuenta".
				 "  FROM scv_dt_spg ".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND codsolvia='".$as_codsolvia."'";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsf MÉTODO->uf_select_dt_spg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detpresup->data=$data;
				$this->ds_detpresup->group_by(array('0'=>'codemp','1'=>'codsolvia'),array('0'=>'monto'),'monto');	
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_dt_spg

	function uf_select_dt_scg($as_codemp,$as_codsolvia)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_scg
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsolvia  // codigo de la solicitud de viaticos
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar las asignaciones de una solicitud de viaticos
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT codemp, codsolvia, sc_cuenta, debhab, montoaux AS monto".
				 "  FROM scv_dt_scg".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND codsolvia='".$as_codsolvia."'";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsf MÉTODO->uf_select_dt_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detcontable->data=$data;
				$this->ds_detcontable->group_by(array('0'=>'codemp','1'=>'codsolvia','2'=>'sc_cuenta','3'=>'debhab'),array('0'=>'monto'),'monto');	
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_dt_scg

	function uf_scv_select_categoria_personal($as_codemp,$as_codsolvia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_categoria_personal
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codclavia".
		        "  FROM scv_dt_personal".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_categoria_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codclavia=$row["codclavia"];
				if($ls_codclavia!="")
				{$lb_valido=true;}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_categoria_personal

	function uf_select_solicitudpago_personal($as_codemp,$as_codsoldes,$as_codsolhas,$ad_fecdes,$ad_fechas,$ai_orden,$as_codsolvia)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago_personal
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsoldes  // numero de la solicitud de viaticos Desde
		//  			       $as_codsolhas  // numero de la solicitud de viaticos Hasta
		//  			       $ad_fecdes     // fecha de inicio del periodo de busqueda
		//  			       $ad_fechas     // fecha de cierre del periodo de busqueda
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//  			       $as_codsolvia  // codigo de solicitud de viaticos
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de la busqueda del personal asociado a una solicitud de viatico
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 29/11/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_criterio="";
		if((!empty($as_codsoldes))&&(!empty($as_codsolhas)))
		{
			$ls_criterio=$ls_criterio."   AND scv_solicitudviatico.codsolvia>='".$as_codsoldes."' ".
					  				  "   AND scv_solicitudviatico.codsolvia<='".$as_codsolhas."' ";
		}
		if((!empty($ad_fecdes))&&(!empty($ad_fechas)))
		{
			$ad_fecdes=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
			$ad_fechas=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
			$ls_criterio=$ls_criterio." AND scv_solicitudviatico.fecsolvia >= '".$ad_fecdes."'".
									  " AND scv_solicitudviatico.fecsolvia <='".$ad_fechas."'";
		}
		if(!empty($as_codsolvia))
		{
			$ls_criterio=$ls_criterio."   AND scv_solicitudviatico.codsolvia='".$as_codsolvia."' ";
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_unidadadmin.desuniadm, ".
				"		sno_personal.telhabper,sno_personal.telmovper,sno_personalnomina.sueper,".
				"       sno_personalnomina.codcueban, sno_personalnomina.tipcuebanper, sno_dedicacion.desded, ".
				"       sno_tipopersonal.destipper, scv_dt_personal.codclavia, scv_solicitudviatico.fecsolvia,scv_solicitudviatico.fecsalvia, ".
				"       scv_solicitudviatico.fecregvia, scv_solicitudviatico.fecsolvia, scv_solicitudviatico.numdiavia, scv_misiones.denmis, ".
				"		scv_solicitudviatico.codsolvia,scv_solicitudviatico.solviaext,scv_solicitudviatico.obssolvia, sno_nomina.racnom, ".
				"       (SELECT COUNT(codper) FROM scv_dt_personal ".
				"		  WHERE scv_dt_personal.codemp = scv_solicitudviatico.codemp  ".
				"			AND scv_dt_personal.codsolvia = scv_solicitudviatico.codsolvia ) AS acompanante,  ".
			    "       (CASE sno_nomina.racnom ".
			    "        WHEN '1' THEN (SELECT denasicar FROM sno_asignacioncargo ".
			    "   	                 WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
			    "		                   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
			    "           			   AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar)".
				"        WHEN '0' THEN (SELECT descar FROM sno_cargo ".
				"   	                 WHERE sno_personalnomina.codemp = sno_cargo.codemp ".
				"		                   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				"                          AND sno_personalnomina.codcar = sno_cargo.codcar) END) AS cargo".
				"  FROM scv_dt_personal, sno_personal, sno_personalnomina, sno_nomina, sno_unidadadmin, sno_dedicacion, ".
				"       sno_tipopersonal, scv_solicitudviatico, scv_misiones ".
				" WHERE scv_solicitudviatico.codemp='".$as_codemp."' ".
				$ls_criterio.
				"   AND sno_nomina.espnom = 0 ".
				"   AND sno_personalnomina.staper = 1 ".
				"   AND scv_dt_personal.codemp = scv_solicitudviatico.codemp ".
				"   AND scv_dt_personal.codsolvia = scv_solicitudviatico.codsolvia ".
				"   AND scv_solicitudviatico.codemp = scv_misiones.codemp ".
				"   AND scv_solicitudviatico.codmis = scv_misiones.codmis ".
				"   AND scv_dt_personal.codemp = sno_personal.codemp ".
				"   AND scv_dt_personal.codper = sno_personal.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				" GROUP BY sno_personal.cedper ";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows==0)
		{
			$this->io_sql->free_result($rs_data);
			$ls_sql="SELECT rpc_beneficiario.ced_bene AS cedper, rpc_beneficiario.nombene AS nomper, rpc_beneficiario.apebene AS apeper, '' AS desuniadm, ".
					"		rpc_beneficiario.telbene AS telhabper,'' AS telmovper,'' AS sueper,".
					"       '' AS codcueban, '' AS tipcuebanper, '' AS desded, ".
					"       '' AS destipper, '' AS codclavia, scv_solicitudviatico.fecsalvia,scv_solicitudviatico.fecsolvia, ".
					"       scv_solicitudviatico.fecregvia, scv_solicitudviatico.numdiavia, scv_misiones.denmis, ".
					"		scv_solicitudviatico.codsolvia,scv_solicitudviatico.solviaext,scv_solicitudviatico.obssolvia, '' AS racnom, ".
					"       (SELECT COUNT(codper) FROM scv_dt_personal ".
					"		  WHERE scv_dt_personal.codemp = scv_solicitudviatico.codemp  ".
					"			AND scv_dt_personal.codsolvia = scv_solicitudviatico.codsolvia ) AS acompanante,  ".
					"       '' AS cargo".
					"  FROM scv_dt_personal,  scv_solicitudviatico, scv_misiones, rpc_beneficiario ".
					" WHERE scv_solicitudviatico.codemp='".$as_codemp."' ".
					$ls_criterio.
					"   AND scv_dt_personal.codemp = scv_solicitudviatico.codemp ".
					"   AND scv_dt_personal.codsolvia = scv_solicitudviatico.codsolvia ".
					"   AND scv_solicitudviatico.codemp = scv_misiones.codemp ".
					"   AND scv_solicitudviatico.codmis = scv_misiones.codmis ".
					"   AND scv_dt_personal.codemp = rpc_beneficiario.codemp ".
					"   AND scv_dt_personal.codper = rpc_beneficiario.ced_bene ".
					" GROUP BY rpc_beneficiario.ced_bene ";
			$rs_data=$this->io_sql->select($ls_sql);
			$li_numrows=$this->io_sql->num_rows($rs_data);	
		}
	//	print $ls_sql."<br><br><br>";
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsf MÉTODO->uf_select_solicitudpago_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_solicitud->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_pagosolicitud_personal

	function uf_select_solicitudpago_beneficiario($as_codemp,$as_codsoldes,$as_codsolhas,$ad_fecdes,$ad_fechas,$ai_orden,$as_codsolvia)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago_beneficiario
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsoldes  // numero de la solicitud de viaticos Desde
		//  			       $as_codsolhas  // numero de la solicitud de viaticos Hasta
		//  			       $ad_fecdes     // fecha de inicio del periodo de busqueda
		//  			       $ad_fechas     // fecha de cierre del periodo de busqueda
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//  			       $as_codsolvia  // codigo de solicitud de viaticos
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de la busqueda del personal asociado a una solicitud de viatico
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 29/11/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_criterio="";
		if((!empty($as_codsoldes))&&(!empty($as_codsolhas)))
		{
			$ls_criterio=$ls_criterio."   AND scv_solicitudviatico.codsolvia>='".$as_codsoldes."' ".
					  				  "   AND scv_solicitudviatico.codsolvia<='".$as_codsolhas."' ";
		}
		if((!empty($ad_fecdes))&&(!empty($ad_fechas)))
		{
			$ad_fecdes=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
			$ad_fechas=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
			$ls_criterio=$ls_criterio." AND scv_solicitudviatico.fecsolvia >= '".$ad_fecdes."'".
									  " AND scv_solicitudviatico.fecsolvia <='".$ad_fechas."'";
		}
		if(!empty($as_codsolvia))
		{
			$ls_criterio=$ls_criterio."   AND scv_solicitudviatico.codsolvia='".$as_codsolvia."' ";
		}
		$ls_sql="SELECT rpc_beneficiario.ced_bene AS cedper, rpc_beneficiario.nombene AS nomper, rpc_beneficiario.apebene AS apeper, '' AS desuniadm, ".
				"		rpc_beneficiario.telbene AS telhabper,'' AS telmovper,'' AS sueper,".
				"       '' AS codcueban, '' AS tipcuebanper, '' AS desded, ".
				"       '' AS destipper, '' AS codclavia, scv_solicitudviatico.fecsalvia, ".
				"       scv_solicitudviatico.fecregvia,scv_solicitudviatico.fecsolvia, scv_solicitudviatico.numdiavia, scv_misiones.denmis, ".
				"		scv_solicitudviatico.codsolvia,scv_solicitudviatico.solviaext,scv_solicitudviatico.obssolvia, '' AS racnom, ".
				"       (SELECT COUNT(codper) FROM scv_dt_personal ".
				"		  WHERE scv_dt_personal.codemp = scv_solicitudviatico.codemp  ".
				"			AND scv_dt_personal.codsolvia = scv_solicitudviatico.codsolvia ) AS acompanante,  ".
			    "       '' AS cargo".
				"  FROM scv_dt_personal,  scv_solicitudviatico, scv_misiones, rpc_beneficiario ".
				" WHERE scv_solicitudviatico.codemp='".$as_codemp."' ".
				$ls_criterio.
				"   AND scv_dt_personal.codemp = scv_solicitudviatico.codemp ".
				"   AND scv_dt_personal.codsolvia = scv_solicitudviatico.codsolvia ".
				"   AND scv_solicitudviatico.codemp = scv_misiones.codemp ".
				"   AND scv_solicitudviatico.codmis = scv_misiones.codmis ".
				"   AND scv_dt_personal.codemp = rpc_beneficiario.codemp ".
				"   AND scv_dt_personal.codper = rpc_beneficiario.ced_bene ".
				" GROUP BY rpc_beneficiario.ced_bene ";
			//	print $ls_sql;
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsf MÉTODO->uf_select_solicitudpago_beneficiario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_solicitudpago_beneficiario

	function uf_select_solicitudpago_asignaciones($as_codemp,$as_codsolvia)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago_asignaciones
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsolvia  // codigo de la solicitud de viaticos
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar las asignaciones de una solicitud de viaticos
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT scv_dt_asignaciones.codemp, scv_dt_asignaciones.codsolvia, scv_dt_asignaciones.codasi,".
				 "       scv_dt_asignaciones.proasi, scv_dt_asignaciones.canasi, ".
				 "       (CASE scv_dt_asignaciones.proasi ".
				 "        WHEN 'TVS' THEN (SELECT scv_tarifas.dentar".
				 "                           FROM scv_tarifas".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_tarifas.codemp".
				 " 							  AND scv_dt_asignaciones.codasi=scv_tarifas.codtar)".
				 "        WHEN 'TRP' THEN (SELECT scv_transportes.dentra".
				 "                           FROM scv_transportes".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_transportes.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_transportes.codtra)".
				 "        WHEN 'TOA' THEN (SELECT scv_otrasasignaciones.denotrasi".
				 "                           FROM scv_otrasasignaciones".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_otrasasignaciones.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_otrasasignaciones.codotrasi)".
				 "		  ELSE (SELECT scv_tarifakms.dentar".
				 "                FROM scv_tarifakms".
				 "               WHERE scv_dt_asignaciones.codemp=scv_tarifakms.codemp".
				 "                 AND scv_dt_asignaciones.codasi=scv_tarifakms.codtar) END) AS denasi, ".
				 "       (CASE scv_dt_asignaciones.proasi ".
				 "        WHEN 'TVS' THEN (SELECT scv_tarifas.monbolaux".
				 "                           FROM scv_tarifas".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_tarifas.codemp".
				 " 							  AND scv_dt_asignaciones.codasi=scv_tarifas.codtar)".
				 "        WHEN 'TRP' THEN (SELECT scv_transportes.tartraaux".
				 "                           FROM scv_transportes".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_transportes.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_transportes.codtra)".
				 "        WHEN 'TOA' THEN (SELECT scv_otrasasignaciones.tarotrasiaux".
				 "                           FROM scv_otrasasignaciones".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_otrasasignaciones.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_otrasasignaciones.codotrasi)".
				 "		  ELSE (SELECT scv_tarifakms.montaraux".
				 "                FROM scv_tarifakms".
				 "               WHERE scv_dt_asignaciones.codemp=scv_tarifakms.codemp".
				 "                 AND scv_dt_asignaciones.codasi=scv_tarifakms.codtar) END) AS monto ".
				 "  FROM scv_solicitudviatico,scv_dt_asignaciones".
				 " WHERE scv_solicitudviatico.codemp='".$as_codemp."'".
				 "   AND scv_solicitudviatico.codsolvia='".$as_codsolvia."'".
				 "   AND scv_solicitudviatico.codsolvia=scv_dt_asignaciones.codsolvia";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsf MÉTODO->uf_select_dt_asignaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_solicitudpago_asignaciones

	function uf_select_solicitudpago_spg($as_codemp,$as_codsolvia)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago_spg
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsolvia  // codigo de la solicitud de viaticos
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar las asignaciones de una solicitud de viaticos
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT codestpro1, codestpro2, spg_cuenta, ".
				 "       (SELECT denestpro1 ".
				 "			FROM spg_ep1 ".
				 "         WHERE spg_ep1.codestpro1 = scv_dt_spg.codestpro1) AS denestpro1, ".
				 "       (SELECT denestpro2 ".
				 "			FROM spg_ep2 ".
				 "         WHERE spg_ep2.codestpro1 = scv_dt_spg.codestpro1 ".
				 "			 AND spg_ep2.codestpro2 = scv_dt_spg.codestpro2) AS denestpro2 ".
				 "  FROM scv_dt_spg ".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND codsolvia='".$as_codsolvia."'".
				 " GROUP BY spg_cuenta ";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsf MÉTODO->uf_select_solicitudpago_spg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detpresup->data=$data;
				$this->ds_detpresup->group_by(array('0'=>'codemp','1'=>'codsolvia'),array('0'=>'monto'),'monto');	
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_solicitudpago_spg

	function uf_select_ruta($as_codemp,$as_codsolvia)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_ruta
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codsolvia  // codigo de la solicitud de viaticos
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar la rutaque presenta 
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_denrut="";
		$ls_sql= "SELECT scv_rutas.desrut ".
				 "  FROM scv_rutas,scv_solicitudviatico ".
				 " WHERE scv_solicitudviatico.codemp='".$as_codemp."'".
				 "   AND scv_solicitudviatico.codsolvia='".$as_codsolvia."'".
				 "   AND scv_solicitudviatico.codemp=scv_rutas.codemp".
				 "   AND scv_solicitudviatico.codrut=scv_rutas.codrut".
				 " GROUP BY scv_rutas.desrut ";
	    $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		//	$this->io_msg->message("ERROR");
			$this->io_msg->message("CLASE->Reportbsf MÉTODO->uf_select_ruta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_denrut=$row["desrut"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_denrut; 
	} //fin  function uf_select_ruta
	
	function uf_scv_load_codigopersonal($as_codemp,$as_cedper,&$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_categoria_personal
		//         Access: public 
		//      Argumento: $as_codemp  // codigo de empresa
		//  			   $as_cedper  // cedula de personal
		//  			   $as_codper  // codigo de personal
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene el codigo de un personal dado su cedula
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/04/2007								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codper".
		        "  FROM sno_personal".
				" WHERE codemp='". $as_codemp ."'".
				"   AND cedper='". $as_cedper ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_load_codigopersonal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codper=$row["codper"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_load_codigopersonal

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_listadosolicitudes($ad_fecregdes,$ad_fecreghas,$as_coduniadm,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_listadosolicitudes
		//         Access: public  
		//	    Arguments: ad_fecregdes // Inicio del Intervalo de Fecha del Reporte
		//	    		   ad_fecreghas // Fin del Intervalo de Fecha del Reporte
		//	    		   as_coduniadm // Codigo de unidad administrativa
		//	    		   as_orden     // Variable de orden
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Funcion que busca la informacion basica de los beneficiarios de un viatico en un 
		//                 lapso de tiempo indicado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funcion->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND scv_solicitudviatico.fecsolvia>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funcion->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND scv_solicitudviatico.fecsolvia<='".$ad_fecreghas."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio=$ls_criterio. "  AND scv_solicitudviatico.coduniadm='".$as_coduniadm."'";
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadenaben="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				$ls_cadenaper="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadenaben="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				$ls_cadenaper="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
		$ls_sql="SELECT scv_solicitudviatico.codsolvia,scv_solicitudviatico.fecsalvia,scv_solicitudviatico.fecregvia,scv_rutas.desrut,scv_dt_personal.codper,".
				"       (CASE scv_dt_personal.codclavia WHEN '' THEN (SELECT ".$ls_cadenaben." ".
				"                                                       FROM rpc_beneficiario".
				"                                                      WHERE rpc_beneficiario.codemp=scv_dt_personal.codemp".
				"                                                        AND rpc_beneficiario.ced_bene=scv_dt_personal.codper)".
				"                                       ELSE (SELECT ".$ls_cadenaper." ".
				"                                               FROM sno_personal".
				"                                              WHERE sno_personal.codemp=scv_dt_personal.codemp".
				"                                                AND sno_personal.codper=scv_dt_personal.codper) END ) AS nombre,".
				"       (CASE scv_dt_personal.codclavia WHEN '' THEN (SELECT ced_bene".
				"                                                       FROM rpc_beneficiario".
				"                                                      WHERE rpc_beneficiario.codemp=scv_dt_personal.codemp".
				"                                                        AND rpc_beneficiario.ced_bene=scv_dt_personal.codper)".
				"                                       ELSE (SELECT cedper".
				"                                               FROM sno_personal".
				"                                              WHERE sno_personal.codemp=scv_dt_personal.codemp".
				"                                                AND sno_personal.codper=scv_dt_personal.codper) END ) AS cedula,".
				"       (SELECT montoaux".
				"          FROM scv_dt_spg".
				"         WHERE scv_dt_spg.codemp=scv_dt_personal.codemp".
				"           AND scv_dt_spg.ced_bene=cedula".
				"           AND scv_dt_spg.codsolvia=scv_dt_personal.codsolvia) AS monto".
				"  FROM scv_solicitudviatico,scv_rutas,scv_dt_personal".
				" WHERE scv_solicitudviatico.codemp='".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				"   AND scv_solicitudviatico.codemp=scv_rutas.codemp".
				"   AND scv_solicitudviatico.codrut=scv_rutas.codrut".
				"   AND scv_solicitudviatico.codemp=scv_dt_personal.codemp".
				"   AND scv_solicitudviatico.codsolvia=scv_dt_personal.codsolvia".
				" GROUP BY scv_solicitudviatico.codsolvia,scv_rutas.desrut,scv_dt_personal.codper".
				" ORDER BY ".$as_orden."";
//				print  $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Reportbsfbsf MÉTODO->uf_select_listadosolicitudes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_solicitud->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_listadosolicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------


} //fin  class sigesp_siv_class_report
?>
