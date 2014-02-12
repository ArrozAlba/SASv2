<?php
class sigesp_rpc_class_reportbsf
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rpc_class_reportbsf($conn)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rpc_class_reportbsf
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: 
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funcion = new class_funciones();
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		$this->io_msg= new class_mensajes();		
	}// end function sigesp_rpc_class_reportbsf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_beneficiario($ai_orden,$as_cedula1,$as_cedula2,&$lb_valido) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_beneficiario
		//         Access: public 
		//	    Arguments: ai_orden // Orden del reportes
		//	  			   as_cedula1 // Rango de cédula Desde		  
		//	  			   as_cedula2 // Rango de cédula Hasta	  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del beneficiario
		//	   Creado Por: 
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$lb_valido=false;
		switch($ai_orden)
		{
			case "0":
				$ls_orden="ced_bene";
				break;
			case "1":
				$ls_orden="nombene";
				break;
			default:
				$ls_orden="apebene";
				break;
		}
		if((empty($as_cedula1)) || (empty($as_cedula2)))
		{
			$as_cedula1="0000000000";
			$as_cedula2="9999999999";
		}
		$ls_sql="SELECT * ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND ced_bene BETWEEN '".$as_cedula1."' AND '".$as_cedula2."'".
				" ORDER BY ".$ls_orden." ASC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido = false;
			$this->io_msg->message("CLASE->Report MÉTODO->uf_prenomina_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{	  
			$li_numrows = $this->io_sql->num_rows($rs_data);	   
			if($li_numrows>0)
			{
				$lb_valido=true;
			}
		}	
		return $rs_data;         
	}// end function uf_select_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_proveedores($as_codemp,$ai_orden,$as_codprov1,$as_codprov2,&$lb_valido) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_proveedores
		//         Access: public 
		//	    Arguments: as_codemp // código de Empresa
		//	    		   ai_orden // Orden del reportes
		//	  			   as_codprov1 // Rango de proveedor Desde		  
		//	  			   as_codprov2 // Rango de proveedor Hasta	  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del Proveedor
		//	   Creado Por: 
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		switch($ai_orden)
		{
			case "0":
				$ls_orden="P.cod_pro";
				break;
			default:
				$ls_orden="P.nompro";
				break;
		}
		if((empty($as_codprov1)) || (empty($as_codprov2)))
		{
			$as_codprov1="0000000000";
			$as_codprov2="9999999999";			
		}
		$ls_sql="SELECT P.codemp, P.cod_pro, P.nompro, P.dirpro, P.telpro, P.faxpro, P.nacpro, P.rifpro, P.nitpro, P.fecreg, ".
				"		P.sc_cuenta, P.obspro, P.estpro, P.estcon, P.estaso, P.ocei_fec_reg, P.ocei_no_reg, P.cedrep, ".
				"		P.nomreppro, P.emailrep, P.carrep, P.registro, P.nro_reg, P.tomo_reg, P.folreg, P.fecregmod, P.regmod, ".
				"		P.nummod, P.tommod, P.folmod, P.inspector, P.foto, P.codbansig, P.codban, P.codmon, P.codtipoorg, P.codesp, ".
				"		P.ctaban, P.numlic, P.fecvenrnc, P.numregsso, P.fecvensso, P.numregince, P.fecvenince, P.estprov, P.pagweb, ".
				"		P.email, P.codpai, P.codest, P.codmun, P.codpar, P.graemp, P.tipconpro, P.capitalaux as capital, P.monmaxaux as monmax,".
				"       C.despai as pais, sigesp_estados.desest AS estado ".
				"  FROM rpc_proveedor P, sigesp_pais C , sigesp_estados ".
				" WHERE P.codemp='".$as_codemp."'  ".
				"   AND P.cod_pro BETWEEN '".$as_codprov1."' AND '".$as_codprov2."' ".
				"   AND P.codpai=C.codpai ".
				"   AND P.codpai=sigesp_estados.codpai ".
				"   AND P.codest=sigesp_estados.codest ".
				" ORDER BY ".$ls_orden." ASC ";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_rpc_class_reportbsf; METODO->uf_select_proveedores; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$li_numrows=$this->io_sql->num_rows($rs_data);	   
			if ($li_numrows>0)
			{
				$lb_valido=true;
			}
		}
		if($lb_valido)
		{
			return $rs_data;         
		}
	}// end function uf_select_proveedores
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_especialidadproveedor($as_codprov,&$lb_valido) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_especialidadproveedor
		//         Access: public 
		//	    Arguments: as_codprov // código del Proveedor
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las especialidades del Proveedor
		//	   Creado Por: 
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_especialidad="";
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$ls_sql="SELECT rpc_especialidad.codesp, rpc_especialidad.denesp  ".
				"  FROM rpc_espexprov, rpc_especialidad ".
				" WHERE rpc_espexprov.codemp='".$ls_codemp."'  ".
				"   AND rpc_espexprov.cod_pro = '".$as_codprov."' ".
				"   AND rpc_espexprov.codesp <> '---' ".
				"	AND rpc_espexprov.codesp=rpc_especialidad.codesp ".
				" ORDER BY rpc_especialidad.denesp ASC ";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_rpc_class_reportbsf; METODO->uf_select_proveedores; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_especialidad=$ls_especialidad.", ".$row["codesp"]." - ".$row["denesp"];
			}
			$ls_especialidad=substr($ls_especialidad,1,strlen($ls_especialidad)-1);
		}
		return $ls_especialidad;         
	}// end function uf_load_especialidadproveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_proveedores($as_codemp,$ai_orden,$as_tipo,$as_codprov1,$as_codprov2,$as_codigoesp,&$lb_valido) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_proveedores
		//         Access: public 
		//	    Arguments: as_codemp // código de Empresa
		//	    		   ai_orden // Orden del reportes
		//	    		   as_tipo // Tipo de Proveedor
		//	  			   as_codprov1 // Rango de proveedor Desde		  
		//	  			   as_codprov2 // Rango de proveedor Hasta	  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del Proveedor
		//	   Creado Por: 
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		switch($ai_orden)
		{
			case "0":
				$ls_orden="cod_pro";
				break;
			default:
				$ls_orden="nompro";
				break;
		}
		if($as_codigoesp=="---")
		{
			$as_codigoesp="";
		}
		
		if($as_tipo=="P")
		{
			$ls_categoria="rpc_proveedor.estpro ";
		}
		else
		{
			$ls_categoria="rpc_proveedor.estcon ";
		}		
		if((empty($as_codprov1)) || (empty($as_codprov2)))
		{
			$as_codprov1="0000000000";
			$as_codprov2="9999999999";			
		}
		$ls_sql="SELECT rpc_proveedor.cod_pro, MAX(rpc_proveedor.nompro) AS nompro, MAX(rpc_proveedor.rifpro) AS rifpro, ".
				"		MAX(rpc_proveedor.nitpro) AS nitpro, MAX(rpc_proveedor.telpro) AS telpro, MAX(rpc_proveedor.dirpro) AS dirpro, ".
				"		MAX(rpc_proveedor.ocei_no_reg) AS ocei_no_reg, MAX(rpc_proveedor.obspro) AS obspro ".
				"  FROM rpc_proveedor, rpc_espexprov  ".
				" WHERE rpc_proveedor.codemp='".$as_codemp."' ".
				"   AND rpc_proveedor.cod_pro BETWEEN '".$as_codprov1."' AND '".$as_codprov2."' ".
				"   AND rpc_espexprov.codesp IN (SELECT rpc_espexprov.codesp ".
				"								   FROM rpc_espexprov, rpc_especialidad ".
				"							   	  WHERE rpc_espexprov.codesp like '%".$as_codigoesp."%' ".
				"   								AND rpc_proveedor.codemp = rpc_espexprov.codemp ".
				"   								AND rpc_proveedor.cod_pro = rpc_espexprov.cod_pro ".
				"   								AND rpc_espexprov.codesp = rpc_especialidad.codesp) ".
				"   AND ".$ls_categoria." = 1 ". 
				" GROUP BY rpc_proveedor.cod_pro ";
				" ORDER BY ".$ls_orden." ASC ";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		}
		else
		{
			$li_numrows=$this->io_sql->num_rows($rs_data);	   
			if ($li_numrows>0)
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				if ($this->io_sql->message!="")
				{                               
					$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));		 
				}           
			}	
		}
		if ($lb_valido)
		{
			return $rs_data;         
		}
	}// end function uf_load_especialidadproveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_documentosproveedores($as_codemp,$as_codprov,$aa_documentos,$as_estatus,&$lb_valido) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_documentosproveedores
		//         Access: public 
		//	    Arguments: as_codemp // código de Empresa
		//	    		   as_codprov // Código de Proveedor
		//	  			   aa_documentos // Arreglo de Documentos a Imprimir
		//	  			   as_estatus //  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los documentos del proveedor
		//	   Creado Por: 
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$li_total=count($aa_documentos);
		$ls_criterio="";
		if($as_estatus=="1")
		{
			$li_total=2;
		}
		if($li_total>1)
		{
			if($as_estatus=="0")
			{
				for($li_i=1;$li_i<$li_total;$li_i++)
				{
					if ($li_i==1)
					{
						$ls_criterio=$ls_criterio." AND ( rpc_documentos.coddoc = '".$aa_documentos[$li_i]."'";
					}
					else
					{
						$ls_criterio=$ls_criterio." OR rpc_documentos.coddoc = '".$aa_documentos[$li_i]."'";			
					}
				}
				$ls_criterio=$ls_criterio.")";
			}
			$ls_sql = "SELECT rpc_documentos.dendoc, rpc_docxprov.fecrecdoc, rpc_docxprov.fecvendoc, ".
					  "       rpc_docxprov.estdoc, rpc_docxprov.estorig ".
					  "  FROM rpc_docxprov, rpc_documentos ".
					  " WHERE rpc_docxprov.codemp = '".$as_codemp."' ".
					  "   AND rpc_docxprov.cod_pro = '".$as_codprov."' ".
					  $ls_criterio.
					  "   AND rpc_docxprov.coddoc = rpc_documentos.coddoc ";
			$rs_data = $this->io_sql->select($ls_sql);
			if ($rs_data===false)
			{
				$this->io_msg->message("CLASE->sigesp_rpc_class_reportbsf; METODO->uf_select_documentosproveedores; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$li_numrows=$this->io_sql->num_rows($rs_data);	 
				if ($li_numrows>0)
				{
					$lb_valido=true;
				}
			}
			if ($lb_valido)
			{
				return $rs_data;         
			}
		}
		else
		{
			$lb_valido=false;
		}
	}// end function uf_select_documentosproveedores
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>