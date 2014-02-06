<?php
class sigesp_cxp_c_recepcion
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cxp_c_recepcion($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_cxp_c_recepcion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007 								Fecha Última Modificación : 
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
		$this->io_ds_cargos=new class_datastore(); // Datastored de cargos
		$this->io_ds_deducciones=new class_datastore(); // Datastored de Deducciones
		$this->io_ds_compromisos=new class_datastore(); // Datastored de Deducciones
		$this->io_ds_anticipos=new class_datastore(); // Datastored de Deducciones
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("class_funciones_cxp.php");
		$this->io_cxp= new class_funciones_cxp();
	}// end function sigesp_cxp_c_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_cxp_p_recepcion.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_tipodocumento($as_seleccionado,$as_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tipodocumento
		//		   Access: public
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//		 		   $as_tipo // Tipo de documento por el cual se debe filtrar
		//	  Description: Función que busca en la tabla de tipo de documento los tipos de Recepciones
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtipdoc, dentipdoc, estcon, estpre,tipodocanti ".
				"  FROM cxp_documento ".
				" ORDER BY codtipdoc ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_tipodocumento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbcodtipdoc' id='cmbcodtipdoc' onChange='javascript: ue_cambiartipodocumento();'>";
			print " <option value='-'>-- Seleccione Uno --</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codtipdoc=$row["codtipdoc"];
				$ls_dentipdoc=$row["dentipdoc"];
				$ls_estcon=$row["estcon"];
				$ls_estpre=$row["estpre"];
				$ls_tipdoc=$ls_estcon.$ls_estpre;
				switch($as_tipo)
				{
					case "C":
						if($as_seleccionado==$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre)
						{
							$ls_seleccionado="selected";
						}
						print "<option value='".$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre."' ".$ls_seleccionado.">".$ls_dentipdoc."</option>";
						break;
						
					case "D": // cuando viene de solicitud de desembolso
						if($ls_tipdoc=="11")
						{
							if($as_seleccionado==$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre)
							{
								$ls_seleccionado="selected";
							}
							print "<option value='".$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre."' ".$ls_seleccionado.">".$ls_dentipdoc."</option>";
						}
						break;
						
					default:
						if(($ls_tipdoc!="13")&&($ls_tipdoc!="14"))
						{
							if($as_seleccionado==$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre)
							{
								$ls_seleccionado="selected";
							}
							print "<option value='".$ls_codtipdoc."-".$ls_estcon."-".$ls_estpre."' ".$ls_seleccionado.">".$ls_dentipdoc."</option>";
						}
						break;
				}
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_tipodocumento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_clasificacionconcepto($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_clasificacionconcepto
		//		   Access: public
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca la clasificacion del concepto y las coloca en un combo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codcla, dencla, sc_cuenta ".
				"  FROM cxp_clasificador_rd ".
				" ORDER BY codcla ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_clasificacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			print "<select name='cmbcodcla' id='cmbcodcla' onChange='javascript: ue_agregarconcepto();'>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$ls_seleccionado="";
				$ls_codcla=$row["codcla"];
				$ls_dencla=$row["dencla"];
				if($ls_codcla!="--")
				{
					$aa_codcla[$li_i]="txt".$ls_codcla;
				}
				else
				{
					$aa_codcla[$li_i]="txtproveedor";
				}
				$aa_cuenta[$li_i]=$row["sc_cuenta"];
				if($as_seleccionado==$ls_codcla)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codcla."' ".$ls_seleccionado.">".$ls_dencla."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
			if($li_i>0)
			{
				for($li_j=1;$li_j<=$li_i;$li_j++)
				{
				 	print "<input name=".$aa_codcla[$li_j]." type=hidden id=".$aa_codcla[$li_j]." value=".$aa_cuenta[$li_j].">";
				}
			}
		}
		return $lb_valido;
	}// end function uf_load_clasificacionconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_spgcuentas($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_spgcuentas
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   as_codpro  // Código del Proveedor
		//	  Description: Función que busca las cuentas presupuestarias de una recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$rs_data=false;
		$ls_sql="SELECT cxp_rd_spg.numdoccom, cxp_rd_spg.codestpro, cxp_rd_spg.spg_cuenta, cxp_rd_spg.monto, spg_cuentas.sc_cuenta, ".
				"		cxp_rd_spg.procede_doc, cxp_rd_spg.codfuefin, cxp_rd_spg.estcla, ".
				"		(SELECT MAX(cxp_rd_cargos.codcar) FROM cxp_rd_cargos ".
				"		  WHERE cxp_rd_cargos.codemp = cxp_rd_spg.codemp ".
				"			AND trim(cxp_rd_cargos.numrecdoc) = trim(cxp_rd_spg.numrecdoc) ".
				"			AND cxp_rd_cargos.codtipdoc = cxp_rd_spg.codtipdoc ".
				"			AND trim(cxp_rd_cargos.ced_bene) = trim(cxp_rd_spg.ced_bene) ".
				"			AND cxp_rd_cargos.cod_pro = cxp_rd_spg.cod_pro ".
				"  			AND cxp_rd_cargos.codestpro1 = substr(cxp_rd_spg.codestpro,1,25) ".
				"   		AND cxp_rd_cargos.codestpro2 = substr(cxp_rd_spg.codestpro,26,25) ".
				"   		AND cxp_rd_cargos.codestpro3 = substr(cxp_rd_spg.codestpro,51,25) ".
				"   		AND cxp_rd_cargos.codestpro4 = substr(cxp_rd_spg.codestpro,76,25) ".
				"   		AND cxp_rd_cargos.codestpro5 = substr(cxp_rd_spg.codestpro,101,25) ".
				"   		AND cxp_rd_cargos.estcla = cxp_rd_spg.estcla ".
				"   		AND trim(cxp_rd_cargos.spg_cuenta) = trim(cxp_rd_spg.spg_cuenta)) AS cargo ".
				"  FROM cxp_rd_spg, spg_cuentas ".
				" WHERE cxp_rd_spg.codemp = '".$this->ls_codemp."'".
				"	AND trim(cxp_rd_spg.numrecdoc) = '".trim($as_numrecdoc)."'".
				"	AND cxp_rd_spg.codtipdoc = '".$as_codtipdoc."'".
				"	AND trim(cxp_rd_spg.ced_bene) = '".trim($as_cedbene)."'".
				"	AND trim(cxp_rd_spg.cod_pro) = '".$as_codpro."'".
				"   AND cxp_rd_spg.codemp = spg_cuentas.codemp ".
				"   AND substr(cxp_rd_spg.codestpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(cxp_rd_spg.codestpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(cxp_rd_spg.codestpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(cxp_rd_spg.codestpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(cxp_rd_spg.codestpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND cxp_rd_spg.estcla = spg_cuentas.estcla ".
				"   AND trim(cxp_rd_spg.spg_cuenta) = trim(spg_cuentas.spg_cuenta) ".
				" ORDER BY numdoccom ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_spgcuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$rs_data=false;
		}
		return $rs_data;
	}// end function uf_load_spgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sccuentas($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_sccuentas
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   as_codpro  // Código del Proveedor
		//	  Description: Función que busca las cuentas contables de una recepción de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$rs_data=false;
		$ls_sql="SELECT numdoccom, debhab, sc_cuenta, estasicon AS estatus, monto, procede_doc ".
				"  FROM cxp_rd_scg ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."'".
				"	AND codtipdoc = '".$as_codtipdoc."'".
				"	AND trim(ced_bene) = '".$as_cedbene."'".
				"	AND trim(cod_pro) = '".$as_codpro."'".
				" ORDER BY numdoccom, debhab ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_sccuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$rs_data=false;
		}
		return $rs_data;
	}// end function uf_load_sccuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   as_codpro  // Código del Proveedor
		//	  Description: Función que busca los cargos de una recepcion de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 05/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$rs_data=false;
		if ($_SESSION["la_empresa"]["confiva"]=='P')
		   {
			 $ls_sql= "SELECT cxp_rd_cargos.codcar, cxp_rd_cargos.numdoccom, cxp_rd_cargos.monobjret, cxp_rd_cargos.monret, ".
					  "		cxp_rd_cargos.codestpro1, cxp_rd_cargos.codestpro2, cxp_rd_cargos.codestpro3, cxp_rd_cargos.codestpro4, ".
				  	  "		cxp_rd_cargos.codestpro5, cxp_rd_cargos.spg_cuenta, cxp_rd_cargos.porcar, cxp_rd_cargos.formula, ".
					  "		cxp_rd_cargos.procede_doc, trim(spg_cuentas.sc_cuenta) as sc_cuenta, cxp_rd_cargos.estcla ".
					  "  FROM cxp_rd_cargos, spg_cuentas ".
					  " WHERE cxp_rd_cargos.codemp = '".$this->ls_codemp."'".
					  "	AND trim(cxp_rd_cargos.numrecdoc) = '".trim($as_numrecdoc)."'".
					  "	AND cxp_rd_cargos.codtipdoc = '".$as_codtipdoc."'".
				  	  "	AND trim(cxp_rd_cargos.ced_bene) = '".$as_cedbene."'".
					  "	AND trim(cxp_rd_cargos.cod_pro) = '".$as_codpro."'".
					  " AND cxp_rd_cargos.codemp = spg_cuentas.codemp ".
					  " AND trim(cxp_rd_cargos.spg_cuenta) = trim(spg_cuentas.spg_cuenta) ".
				  	  "	AND cxp_rd_cargos.codestpro1 = spg_cuentas.codestpro1 ".
					  "	AND cxp_rd_cargos.codestpro2 = spg_cuentas.codestpro2 ".
					  "	AND cxp_rd_cargos.codestpro3 = spg_cuentas.codestpro3 ".
					  "	AND cxp_rd_cargos.codestpro4 = spg_cuentas.codestpro4 ".
					  "	AND cxp_rd_cargos.codestpro5 = spg_cuentas.codestpro5 ".
					  "	AND cxp_rd_cargos.estcla = spg_cuentas.estcla ".
					  " ORDER BY numdoccom ";	
		   }
		elseif($_SESSION["la_empresa"]["confiva"]=='C')
		   {
		     $ls_sql = "SELECT cxp_rd_cargos.codcar, cxp_rd_cargos.numdoccom, cxp_rd_cargos.monobjret, cxp_rd_cargos.monret, cxp_rd_cargos.codestpro1, 
							   cxp_rd_cargos.codestpro2, cxp_rd_cargos.codestpro3, cxp_rd_cargos.codestpro4, cxp_rd_cargos.codestpro5, cxp_rd_cargos.spg_cuenta, 
							   cxp_rd_cargos.porcar, cxp_rd_cargos.formula, cxp_rd_cargos.procede_doc, cxp_rd_cargos.estcla, trim(scg_cuentas.sc_cuenta) as sc_cuenta 
						  FROM cxp_rd_cargos, spg_ep5, scg_cuentas
						  WHERE cxp_rd_cargos.codemp = '".$this->ls_codemp."' 
							AND trim(cxp_rd_cargos.numrecdoc) = '".trim($as_numrecdoc)."' 
							AND cxp_rd_cargos.codtipdoc = '".$as_codtipdoc."' 
							AND trim(cxp_rd_cargos.ced_bene) = '".$as_cedbene."' 
							AND trim(cxp_rd_cargos.cod_pro) = '".$as_codpro."' 
							AND cxp_rd_cargos.codemp = scg_cuentas.codemp 
							AND trim(cxp_rd_cargos.spg_cuenta) = trim(scg_cuentas.sc_cuenta)
							AND cxp_rd_cargos.codemp = spg_ep5.codemp
							AND cxp_rd_cargos.codestpro1 = spg_ep5.codestpro1 
							AND cxp_rd_cargos.codestpro2 = spg_ep5.codestpro2 
							AND cxp_rd_cargos.codestpro3 = spg_ep5.codestpro3 
							AND cxp_rd_cargos.codestpro4 = spg_ep5.codestpro4 
							AND cxp_rd_cargos.codestpro5 = spg_ep5.codestpro5 
							AND cxp_rd_cargos.estcla = spg_ep5.estcla 
						  ORDER BY numdoccom";		   
		   }
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$rs_data=false;
		}
		return $rs_data;
	}// end function uf_load_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_deducciones
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepción
		//	    		   as_codtipdoc  // Tipo de Documento
		//	    		   as_cedbene  // Cédula del Beneficiario
		//	    		   as_codpro  // Código del Proveedor
		//	  Description: Función que busca las deducciones de una recepción de documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$rs_data=false;
		$ls_sql="SELECT codded, numdoccom, monobjret, monret, sc_cuenta, porded, procede_doc,".
				"       (SELECT iva FROM sigesp_deducciones".
				"         WHERE cxp_rd_deducciones.codemp=sigesp_deducciones.codemp".
				"           AND cxp_rd_deducciones.codded=sigesp_deducciones.codded) AS iva,".
				"       (SELECT islr FROM sigesp_deducciones".
				"         WHERE cxp_rd_deducciones.codemp=sigesp_deducciones.codemp".
				"           AND cxp_rd_deducciones.codded=sigesp_deducciones.codded) AS islr".
				"  FROM cxp_rd_deducciones ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."'".
				"	AND codtipdoc = '".$as_codtipdoc."'".
				"	AND trim(ced_bene) = '".$as_cedbene."'".
				"	AND trim(cod_pro) = '".$as_codpro."'".
				" ORDER BY numdoccom ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$rs_data=false;
		}
		return $rs_data;
	}// end function uf_load_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_recepcion
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_tipodestino // Tipo de Destino (Proveedor ó Beneficiario)
		//		 		   as_codprovben // Código del Proveedor ó Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que verifica si una recepción existe ó no
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_existe=false;
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 $ls_cedbene="----------";
				 break;
			case "B":
				 $ls_codpro ="----------";
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		$ls_sql="SELECT trim(numrecdoc) as numrecdoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$ls_codpro."' ".
				"   AND trim(ced_bene) = '".trim($ls_cedbene)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_select_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
		}
		return $lb_existe;
	}// end function uf_select_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudpago($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitudpago
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_tipodestino // Tipo de Destino (Proveedor ó Beneficiario)
		//		 		   as_codprovben // Código del Proveedor ó Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que verifica si una recepción existe ó no en una solicitud de pago
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 18/07/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_existe=false;
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 $ls_cedbene="----------";
				 break;
			case "B":
				 $ls_codpro ="----------";
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		$ls_sql="SELECT trim(numrecdoc) as numrecdoc ".
				"  FROM cxp_dt_solicitudes ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$ls_codpro."' ".
				"   AND trim(ced_bene) = '".trim($ls_cedbene)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_select_solicitudpago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
		}
		return $lb_existe;
	}// end function uf_select_solicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_estatus($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,&$as_estprodoc)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_estatus
		//		   Access: public
		//		 Argument: as_numrecdoc // Número de Recepción de Documentos
		//		 		   as_tipodestino // Tipo de Destino (Proveedor ó Beneficiario)
		//		 		   as_codprovben // Código del Proveedor ó Beneficiario
		//		 		   as_codtipdoc // Código del Tipo de Documento
		//	  Description: Función que busca en la tabla de la recepcion el estatus de la misma
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/05/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 $ls_cedbene="----------";
				 break;
			case "B":
				 $ls_codpro ="----------";
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		$ls_sql="SELECT estprodoc ".
				"  FROM cxp_rd ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND trim(numrecdoc)='".trim($as_numrecdoc)."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"   AND cod_pro='".$ls_codpro."' ".
				"   AND trim(ced_bene)='".trim($ls_cedbene)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_load_estatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_estprodoc=$row["estprodoc"];
			}
		}
		return $lb_valido;
	}// end function uf_load_estatus
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codcla,$as_dencondoc,$ad_fecemidoc,$ad_fecregdoc,
								 $ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,$ai_cargos,$as_tipodestino,$as_numref,$as_procede,
								 $as_estlibcom,$as_estimpmun,$ai_totrowspg,$ai_totrowscg,$as_codfuefin,$as_codrecdoc,
								 $as_coduniadm,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
								 $as_estcla,$as_estact,$as_numordpagmin,$as_codtipfon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código de proveedor
		//				   as_codcla  // Código de Clasificación
		//				   as_dencondoc  // Concepto de la recpeción de documentos
		//				   ad_fecemidoc  // Fecha de Emisión del Documento
		//				   ad_fecregdoc  // Fecha de Recepcion de Documentos
		//				   ad_fecvendoc  // Fecha de Vencimiento del Documento
		//				   ai_totalgeneral  // Total General
		//				   ai_deducciones  // Total de Deducciones
		//				   ai_cargos  // Total de Cargos
		//				   as_tipodestino  // Tipo Destino
		//				   as_numref  // Número de Referencia
		//				   as_procede  // Procede de la recepción de documentos
		//				   as_estlibcom  // Estatus de Libro de Orden de compra
		//				   as_estimpmun  // Estatus de Impuesto Municipal
		//				   ai_totrowspg  // Total de Filas de Presupuesto
		//				   as_codfuefin  // Fuente de Financiamiento
		//				   as_codrecdoc  // Código único de Recepción de Documentos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la recepción de documentos y sus detalles
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 30/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_numrecdoc    = trim($as_numrecdoc);
		$as_cedbene      = trim($as_cedbene);
		$as_numordpagmin = trim($as_numordpagmin);
		$as_codtipfon    = trim($as_codtipfon);
		if (empty($as_numordpagmin))
		   {
		     $as_numordpagmin = '-';
		   }
		if (empty($as_codtipfon))
		   {
		     $as_codtipfon = '----';
		   }
		$lb_valido=$this->io_keygen->uf_verificar_numero_generado("CXP","cxp_rd","codrecdoc","CXPRCD",15,"","","",&$as_codrecdoc);
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codcla, dencondoc, fecemidoc, fecregdoc, ".
				"fecvendoc, montotdoc, mondeddoc, moncardoc, tipproben, numref, estprodoc, procede, estlibcom, estaprord, ".
				"fecaprord, usuaprord, estimpmun, codfuefin, codrecdoc,coduniadm, codestpro1, codestpro2, codestpro3,".
				" codestpro4, codestpro5, estcla, estact, numordpagmin, codtipfon)  VALUES ('".$this->ls_codemp."','".trim($as_numrecdoc)."','".$as_codtipdoc."', ".
				"'".trim($as_cedbene)."','".$as_codpro."','".$as_codcla."','".$as_dencondoc."','".$ad_fecemidoc."','".$ad_fecregdoc."', ".
				"'".$ad_fecvendoc."',".$ai_totalgeneral.",".$ai_deducciones.",".$ai_cargos.",'".$as_tipodestino."','".$as_numref."', ".
				"'R','".$as_procede."',".$as_estlibcom.",0,'1900-01-01','',".$as_estimpmun.",'".$as_codfuefin."',".
				"'".$as_codrecdoc."','".$as_coduniadm."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."',".
				"'".$as_codestpro4."','".$as_codestpro5."','".$as_estcla."','".$as_estact."','-','----')";	
				
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene.
							 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasspg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_tipodestino,
													   $ad_fecregdoc,$ai_totrowspg,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasscg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_totrowscg,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_historico($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fecregdoc,"R",$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Recepción de Documentos fue registrada.");
				unset($_SESSION["amortizacion"]);
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Registrar la Recepción de Documentos."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentasspg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_tipodes,$ad_fechareg,$ai_totrowspg,
								  $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentasspg
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   ai_totrowspg  // total de filas de cuentas SPG
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de presupuesto de una recepción ded documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($ai_totrowspg>1)
		{
			$lb_valido=$this->io_cxp->uf_verificar_cierre_spg("../",$ls_estciespg);
			if($ls_estciespg=="1")
			{
				$this->io_mensajes->message("Esta procesado el cierre presupuestario");
				return false;
			}
		}
		for($li_i=1;($li_i<$ai_totrowspg)&&($lb_valido);$li_i++)
		{
			$ls_nrocomp=$_POST["txtspgnrocomp".$li_i];
			$ls_programatica=$_POST["txtcodpro".$li_i];
			$ls_estcla=$_POST["txtestcla".$li_i];
			$ls_cuenta=$_POST["txtspgcuenta".$li_i];
			$ls_procede=$_POST["txtspgprocededoc".$li_i];
			$ls_codfuefin=$_POST["txtcodfuefin".$li_i];
			$li_moncue=$_POST["txtspgmonto".$li_i];
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);
			$li_monto_compromiso=0;
			$li_monto_ajuste=0;
			$li_monto_causado=0;
			$li_monto_anulado=0;
			$li_monto_recepcion=0;
			$li_monto_ordenpago=0;
			$li_monto_cargo=0;
			$li_monto_solicitud=0;
			$li_disponible=0;
			$ls_numcomanu="";
			$lb_valido=$this->uf_load_monto_comprobantes_cuenta($ls_nrocomp,$ls_procede,$as_tipodes,$as_codpro,$as_cedbene,
											   					$ad_fechareg,$ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_compromiso);
			if($lb_valido)
			{											   			
				$lb_valido=$this->uf_load_monto_ajustes_cuenta($ls_nrocomp,$ls_procede,$as_tipodes,$as_codpro,$as_cedbene,
															   $ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_ajuste);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_monto_causados_cuenta($ls_nrocomp,$ls_procede,$as_tipodes,$as_codpro,$as_cedbene,
																$ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_causado);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_comprobantes_anulados($ls_nrocomp,$as_tipodes,$as_codpro,$as_cedbene,$ad_fechareg,
																&$ls_numcomanu);
			}
			if(($lb_valido) &&($li_monto_causado>0))
			{
				$lb_valido=$this->uf_load_monto_anulados_cuenta($ls_nrocomp,$ls_procede,$as_tipodes,$as_codpro,$as_cedbene,
																$ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_anulado);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_monto_recepciones_cuenta($ls_nrocomp,$ls_procede,$ls_programatica,$ls_estcla,$ls_cuenta,
																   &$li_monto_recepcion);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_monto_ordenespago_directa_cuenta($ls_nrocomp,$ls_procede,$ls_programatica,$ls_estcla,$ls_cuenta,
																		   &$li_monto_ordenpago);
			}
/*			if($lb_valido)
			{
				$lb_valido=$this->uf_load_monto_cargos_cuenta($ls_nrocomp,$ls_procede,$ls_programatica,$ls_estcla,$ls_cuenta,&$li_monto_cargo);
			}
*/			if($lb_valido)
			{
				$li_comprometido=$li_monto_compromiso+(($li_monto_ajuste)-$li_monto_causado+$li_monto_anulado-$li_monto_recepcion);
//				$li_comprometido=$li_monto_compromiso+(($li_monto_ajuste)-$li_monto_causado+$li_monto_anulado-$li_monto_recepcion-$li_monto_cargo);
				if($li_monto_compromiso>0)
				{
					$li_disponible=$li_comprometido-$li_moncue;
					$li_disponible=number_format($li_disponible,2,'.','');
				}
				else
				{
					$li_disponible=0;
				}
				if($li_disponible>=0)
				{
					if (empty($ls_estcla))
					{
						$ls_estcla=substr($ls_programatica,5,1);
					}
					$ls_sql="INSERT INTO cxp_rd_spg (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,codestpro,".
							"spg_cuenta,monto,codfuefin,estcla) VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."',".
							"'".$as_codpro."','".$as_cedbene."','".$ls_procede."','".$ls_nrocomp."','".$ls_programatica."', ".
							"'".$ls_cuenta."',".$li_moncue.",'".$ls_codfuefin."','".$ls_estcla."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_cuentasspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion="Insertó la cuenta ".$ls_cuenta." Estructura ".$ls_programatica." a la Recepción ".$as_numrecdoc.
										" Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene."Proveedor ".$as_codpro.
										" Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					}
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("ERROR-> Se esta causando Mas de lo comprometido en la cuenta ".$ls_cuenta); 
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentasspg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentasscg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_totrowscg,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentasscg
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   ai_totrowscg  // total de filas de cuentas SCG
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de contabilidad de una recepción ded documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($ai_totrowscg>1)
		{
			$lb_valido=$this->io_cxp->uf_verificar_cierre_scg("../",$ls_estciescg);
			if($ls_estciescg=="1")
			{
				$this->io_mensajes->message("Esta procesado el cierre contable");
				return false;
			}
		}
		for($li_i=1;($li_i<$ai_totrowscg)&&($lb_valido);$li_i++)
		{
			$ls_nrocomp=$_POST["txtscgnrocomp".$li_i];
			$ls_cuenta=$_POST["txtscgcuenta".$li_i];
			$ls_debhab=$_POST["txtdebhab".$li_i];
			$ls_estatus=$_POST["txtestatus".$li_i];
			$ls_procede=$_POST["txtscgprocededoc".$li_i];
			$li_estgenasi=0;
			if($ls_debhab=="D")
			{
				$ls_cuentaanticipo=$ls_cuenta;
				$li_moncue=$_POST["txtmondeb".$li_i];					
				$li_moncue=str_replace(".","",$li_moncue);
				$li_moncue=str_replace(",",".",$li_moncue);
			}
			else
			{
				$li_moncue=$_POST["txtmonhab".$li_i];					
				$li_moncue=str_replace(".","",$li_moncue);
				$li_moncue=str_replace(",",".",$li_moncue);
			}
			$ls_sql="INSERT INTO cxp_rd_scg (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,debhab,sc_cuenta, ".
					"monto,estgenasi,estasicon) VALUES ('".$this->ls_codemp."','".$as_numrecdoc."', '".$as_codtipdoc."','".$as_codpro."', ".
					"'".$as_cedbene."','".$ls_procede."','".$ls_nrocomp."','".$ls_debhab."',".
					"'".$ls_cuenta."',".$li_moncue.",".$li_estgenasi.",'".$ls_estatus."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_cuentasscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
					
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion="Insertó la cuenta ".$ls_cuenta." a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
									" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		if((array_key_exists("amortizacion",$_SESSION)))
		{
			$lb_valido=$this->uf_update_amortizacion($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_select_tipodocumento($as_codtipdoc,&$as_tipodocanti);
			if($lb_valido)
			{
				if($as_tipodocanti=="1")
				{
					$lb_valido=$this->uf_insert_anticipo($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$li_moncue,$ls_cuentaanticipo,$aa_seguridad);
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentasscg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los cargos de una recepción ded documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(array_key_exists("cargos",$_SESSION))
		{
			$this->io_ds_cargos->data=$_SESSION["cargos"];
			$this->io_ds_cargos->group_by(array('0'=>'codcar','1'=>'nrocomp','2'=>'cuenta','3'=>'procededoc'),array('0'=>'baseimp','1'=>'monimp'),'monimp');
			$li_totrow=$this->io_ds_cargos->getRowCount('codcar');	
			for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
			{
				$ls_codcar  = $this->io_ds_cargos->getValue('codcar',$li_fila);
				$ls_nrocomp = $this->io_ds_cargos->getValue('nrocomp',$li_fila);
				$li_baseimp = $this->io_ds_cargos->getValue('baseimp',$li_fila);
				$li_monimp  = $this->io_ds_cargos->getValue('monimp',$li_fila);
				if ($_SESSION["la_empresa"]["confiva"]=='P')
				   {
					 $ls_codpro  = $this->io_ds_cargos->getValue('codpro',$li_fila);
					 $ls_estcla  = $this->io_ds_cargos->getValue('estcla',$li_fila);				
				   }
				else
				   {
				     $ls_codpro = str_pad('-',125,'-',0);
				     $ls_estcla = '-';
				   }
				$ls_cuenta  = $this->io_ds_cargos->getValue('cuenta',$li_fila);
				$ls_formula = $this->io_ds_cargos->getValue('formula',$li_fila);
				$li_porcar  = $this->io_ds_cargos->getValue('porcar',$li_fila);
				$ls_procede = $this->io_ds_cargos->getValue('procededoc',$li_fila);
				$ls_sql="INSERT INTO cxp_rd_cargos (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,codcar,procede_doc,numdoccom,".
						"monobjret,monret,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,porcar,formula,estcla) ".
					    " VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_codpro."', ".
					    "'".$as_cedbene."','".$ls_codcar."','".$ls_procede."','".$ls_nrocomp."',".$li_baseimp.",".$li_monimp.",".
					    "'".substr($ls_codpro,0,25)."','".substr($ls_codpro,25,25)."','".substr($ls_codpro,50,25)."','".substr($ls_codpro,75,25)."',".
					    "'".substr($ls_codpro,100,25)."','".$ls_cuenta."',".$li_porcar.",'".$ls_formula."','".$ls_estcla."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion="Insertó el cargo ".$ls_codcar." a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
									" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_deducciones
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las deducciones de una recepción ded documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(array_key_exists("deducciones",$_SESSION))
		{
			$this->io_ds_deducciones->data=$_SESSION["deducciones"];
			$li_totrow=$this->io_ds_deducciones->getRowCount('codded');	
			for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
			{
				$ls_codded=$this->io_ds_deducciones->getValue('codded',$li_fila);
				$ls_nrocomp=$this->io_ds_deducciones->getValue('documento',$li_fila);
				$li_monobjret=$this->io_ds_deducciones->getValue('monobjret',$li_fila);
				$li_monret=$this->io_ds_deducciones->getValue('monret',$li_fila);
				$ls_sccuenta=$this->io_ds_deducciones->getValue('sccuenta',$li_fila);
				$li_porded=$this->io_ds_deducciones->getValue('porded',$li_fila);
				$ls_procede=$this->io_ds_deducciones->getValue('procededoc',$li_fila);
				$li_monobjret=str_replace(".","",$li_monobjret);
				$li_monobjret=str_replace(",",".",$li_monobjret);
				$li_monret=str_replace(".","",$li_monret);
				$li_monret=str_replace(",",".",$li_monret);

				$ls_sql="INSERT INTO cxp_rd_deducciones (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,codded,procede_doc,numdoccom, ".
						"monobjret,monret,porded,sc_cuenta) VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."', ".
						"'".$as_codpro."','".$as_cedbene."','".$ls_codded."','".$ls_procede."','".$ls_nrocomp."',".$li_monobjret.", ".
						"".$li_monret.",".$li_porded.",'".$ls_sccuenta."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_deducciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion="Insertó la Deduccion ".$ls_codded." a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
									" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_historico($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fecregdoc,$as_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_historico
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de Recepción ded Documentos
		//				   as_codtipdoc  // Código del Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código del Proveedor
		//				   ad_fecregdoc  // Fecha de Registro de la Recepción
		//				   as_estatus  // Estatus de la recepción
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los movimientos históricos de una recepción ded documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 01/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_historico_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, fecha, estprodoc)".
				" VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."','".$as_codpro."',".
				"'".$ad_fecregdoc."','".$as_estatus."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el los históricos a la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
							" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_recepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codcla,$as_dencondoc,$ad_fecemidoc,$ad_fecregdoc,
								 $ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,$ai_cargos,$as_tipodestino,$as_numref,$as_procede,
								 $as_estlibcom,$as_estimpmun,$ai_totrowspg,$ai_totrowscg,$as_coduniadm,$as_codestpro1,$as_codestpro2,
								 $as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,$as_estact,$as_numordpagmin,$as_codtipfon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_recepcion
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código de proveedor
		//				   as_codcla  // Código de Clasificación
		//				   as_dencondoc  // Concepto de la recpeción de documentos
		//				   ad_fecemidoc  // Fecha de Emisión del Documento
		//				   ad_fecregdoc  // Fecha de Recepcion de Documentos
		//				   ad_fecvendoc  // Fecha de Vencimiento del Documento
		//				   ai_totalgeneral  // Total General
		//				   ai_deducciones  // Total de Deducciones
		//				   ai_cargos  // Total de Cargos
		//				   as_tipodestino  // Tipo Destino
		//				   as_numref  // Número de Referencia
		//				   as_procede  // Procede de la recepción de documentos
		//				   as_estlibcom  // Estatus de Libro de Orden de compra
		//				   as_estimpmun  // Estatus de Impuesto Municipal
		//				   ai_totrowspg  // Total de Filas de Presupuesto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la recepción de documentos y sus detalles
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $ls_sql="UPDATE cxp_rd ".
				"   SET dencondoc='".$as_dencondoc."', ".
				"		codcla='".$as_codcla."', ".
				"       fecemidoc='".$ad_fecemidoc."', ".
				"       fecregdoc='".$ad_fecregdoc."', ".
				"       fecvendoc='".$ad_fecvendoc."', ".
				"		montotdoc=".$ai_totalgeneral.", ".
			    "       mondeddoc=".$ai_deducciones.", ".
				"		moncardoc=".$ai_cargos.", ".
				"       numref='".$as_numref."', ".
				"		estlibcom=".$as_estlibcom.",  ".
				"		estimpmun=".$as_estimpmun.",  ".
				"		coduniadm='".$as_coduniadm."',  ".
				"		codestpro1='".$as_codestpro1."',  ".
				"		codestpro2='".$as_codestpro2."',  ".
				"		codestpro3='".$as_codestpro3."',  ".
				"		codestpro4='".$as_codestpro4."',  ".
				"		codestpro5='".$as_codestpro5."',  ".
				"		estcla='".$as_estcla."',  ".
				"		numordpagmin='-',  ".
				"		codtipfon='----'  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"	AND cod_pro='".$as_codpro."' ".
				"	AND trim(ced_bene) = '".trim($as_cedbene)."' ";		  
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_update_recepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene.
							 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$lb_valido=$this->uf_delete_detallesrecepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasspg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_tipodestino,
													   $ad_fecregdoc,$ai_totrowspg,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentasscg($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_totrowscg,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_historico($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ad_fecregdoc,"R",$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Recepción de Documentos fue actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un Error al Actualizar la Recepción de Documentos."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detallesrecepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detallesrecepcion
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código de proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina los detalles de una recepcion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_cargos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND trim(numrecdoc)='".trim($as_numrecdoc)."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"	AND cod_pro='".$as_codpro."' ".
				"	AND trim(ced_bene)='".trim($as_cedbene)."'";		  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_rd_deducciones ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND trim(numrecdoc)='".trim($as_numrecdoc)."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND trim(ced_bene)='".trim($as_cedbene)."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_rd_scg ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND trim(numrecdoc)='".trim($as_numrecdoc)."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND trim(ced_bene)='".trim($as_cedbene)."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_rd_spg ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND trim(numrecdoc)='".trim($as_numrecdoc)."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND trim(ced_bene)='".trim($as_cedbene)."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_historico_rd ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND trim(ced_bene) = '".trim($as_cedbene)."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_rd_amortizacion ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."' ".
					"	AND codtipdoc='".$as_codtipdoc."' ".
					"	AND cod_pro='".$as_codpro."' ".
					"	AND trim(ced_bene) = '".trim($as_cedbene)."'";		  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los detalles de Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene.
							 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_delete_detallesrecepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,$ad_fecregdoc,$ad_fecvendoc,
						$ad_fecemidoc,$as_codcla,$as_dencondoc,$as_procede,$ai_cargos,$ai_deducciones,$ai_totalgeneral,
						$as_numref,$as_estimpmun,$as_estlibcom,$ai_totrowspg,$ai_totrowscg,$as_codfuefin,$as_codrecdoc,
						$as_coduniadm,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
						$as_estact,$as_numordpagmin,$as_codtipfon,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_cxp_p_recepcion.php)
		//	    Arguments: as_existe  //  Si el registro exite ó si es nuevo
		//				   as_numrecdoc  // Número de recepción de documentos
		//				   as_tipodestino  // Tipo Destino
		//				   as_codprovben  // Código de proveedor ó beneficiario
		//				   as_codtipdoc  // Tipo de Documento
		//				   ad_fecregdoc  // Fecha de Recepcion de Documentos
		//				   ad_fecvendoc  // Fecha de Vencimiento del Documento
		//				   ad_fecemidoc  // Fecha de Emisión del Documento
		//				   as_codcla  // Código de Clasificación
		//				   as_dencondoc  // Concepto de la recpeción de documentos
		//				   as_procede  // Procede de la recepción de documentos
		//				   ai_cargos  // Total de Cargos
		//				   ai_deducciones  // Total de Deducciones
		//				   ai_totalgeneral  // Total General
		//				   as_numref  // Número de Referencia
		//				   as_estimpmun  // Estatus de Impuesto Municipal
		//				   as_estlibcom  // Estatus de Libro de Orden de compra
		//				   ai_totrowspg  // total de filas de Presupuesto
		//				   ai_totrowscg  // total de filas de Contabilidad
		//				   as_codfuefin // Fuente de Financiamiento
		//				   as_codrecdoc // Código único de Recepción de Documento
		//				   as_coduniadm // Código de unidad ejecutora
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 30/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_numrecdoc  = trim($as_numrecdoc);
		$as_codprovben = trim($as_codprovben);
		$as_codtipdoc=substr($as_codtipdoc,0,5);
		$lb_encontrado=$this->uf_select_recepcion($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc);
		$ad_fecregdoc=$this->io_funciones->uf_convertirdatetobd($ad_fecregdoc);
		$ad_fecvendoc=$this->io_funciones->uf_convertirdatetobd($ad_fecvendoc);
		$ad_fecemidoc=$this->io_funciones->uf_convertirdatetobd($ad_fecemidoc);
		switch($as_tipodestino)
		{
			case "P":
				 $ls_codpro=$as_codprovben;
				 $ls_cedbene="----------";
				 break;
			case "B":
				 $ls_codpro ="----------";
				 $ls_cedbene=$as_codprovben;
				 break;
		}
		if($as_coduniadm=="")
		{
			$as_coduniadm="----------";
			$as_codestpro1="-------------------------";
			$as_codestpro2="-------------------------";
			$as_codestpro3="-------------------------";
			$as_codestpro4="-------------------------";
			$as_codestpro5="-------------------------";
			$as_estcla="-";
		}
		if(($as_numordpagmin!="")&&($as_codtipfon!="") && ($as_numordpagmin!="-")&&($as_codtipfon!="----"))
		{
			$li_totmoncon=$this->uf_load_monto_consumido($as_numordpagmin,$as_codtipfon);
			$lb_valido=$this->uf_load_ordenministerio($as_numordpagmin,$as_codtipfon,$li_monordpagmin,$li_porrepfon);
			$li_monordpag=($ai_totalgeneral+$li_totmoncon);
			$li_porcons=(($li_monordpag*100)/$li_monordpagmin);
			if($li_porrepfon<$li_porcons)
			{
				$this->io_mensajes->message($this->io_fecha->is_msg_error." El monto sobrepasa el Porcentaje de reposicion.");           
				return false;
			}
		}
		switch ($as_existe)
		{
			case "FALSE":
				if(!($lb_encontrado))
				{
					$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecregdoc,$this->ls_codemp);
					if (!$lb_valido)
					{
						$this->io_mensajes->message($this->io_fecha->is_msg_error." Para la fecha de Recepción.");           
						return false;
					}                    
					$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecvendoc,$this->ls_codemp);
					if (!$lb_valido)
					{
						$this->io_mensajes->message($this->io_fecha->is_msg_error." Para la fecha de Vencimiento.");           
						return false;
					}                    
					$lb_valido=$this->uf_insert_recepcion($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$as_codcla,$as_dencondoc,
														  $ad_fecemidoc,$ad_fecregdoc,$ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,
														  $ai_cargos,$as_tipodestino,$as_numref,$as_procede,$as_estlibcom,$as_estimpmun,
														  $ai_totrowspg,$ai_totrowscg,$as_codfuefin,$as_codrecdoc,$as_coduniadm,
														  $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														  $as_codestpro5,$as_estcla,$as_estact,$as_numordpagmin,$as_codtipfon,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Recepción de Documentos ya existe, no la puede incluir.");
					return false;
				}
				break;

			case "TRUE":
				if($lb_encontrado)
				{
					$ls_estprodoc="";
					$lb_valido=$this->uf_load_estatus($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,$ls_estprodoc);
					if($ls_estprodoc!="R")
					{
						$this->io_mensajes->message("La Recepción de Documentos no se puede modificar, Tiene Movimientos.");           
						return false;
					}
					$lb_valido=$this->uf_update_recepcion($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$as_codcla,$as_dencondoc,
														  $ad_fecemidoc,$ad_fecregdoc,$ad_fecvendoc,$ai_totalgeneral,$ai_deducciones,
														  $ai_cargos,$as_tipodestino,$as_numref,$as_procede,$as_estlibcom,$as_estimpmun,
														  $ai_totrowspg,$ai_totrowscg,$as_coduniadm,$as_codestpro1,$as_codestpro2,
														  $as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,$as_estact,
														  $as_numordpagmin,$as_codtipfon,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Recepción de Documentos no existe, no la puede actualizar.");
				}
				break;
		}
		if($lb_valido)
		{
			if((array_key_exists("ls_ajuste",$_SESSION)))
			{
				if($_SESSION["ls_ajuste"]!="")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion=$_SESSION["ls_ajuste"]." Para la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$ls_cedbene.
									 "Proveedor ".$ls_codpro." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				}
			}
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete
		//		   Access: public (sigesp_cxp_p_recepcion.php)
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_tipodestino  // Tipo Destino
		//				   as_codprovben  // Código de proveedor ó beneficiario
		//				   as_codtipdoc  // Tipo de Documento
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y elimina la recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 07/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$as_codtipdoc=substr($as_codtipdoc,0,5);
		$lb_encontrado=$this->uf_select_recepcion($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc);
		if($lb_encontrado)
		{
			$lb_encontrado=$this->uf_select_solicitudpago($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc);
			if($lb_encontrado===false)
			{
				$this->io_sql->begin_transaction();				
				$lb_valido=$this->uf_load_estatus($as_numrecdoc,$as_tipodestino,$as_codprovben,$as_codtipdoc,$ls_estprodoc);
				if($ls_estprodoc!="R")
				{
					$this->io_mensajes->message("La Recepción de Documentos no se puede eliminar, Tiene Movimientos.");           
					$lb_valido=false;
				}
				switch($as_tipodestino)
				{
					case "P":
						 $ls_codpro=$as_codprovben;
						 $ls_cedbene="----------";
						 break;
					case "B":
						 $ls_codpro ="----------";
						 $ls_cedbene=$as_codprovben;
						 break;
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_delete_detallesrecepcion($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$aa_seguridad);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_reverso_anticipos($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$aa_seguridad);
				}
				if($lb_valido)
				{	
					$ls_sql="DELETE FROM cxp_rd ".
							" WHERE codemp='".$this->ls_codemp."' ".
							"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."' ".
							"	AND codtipdoc='".$as_codtipdoc."' ".
							"	AND cod_pro='".$ls_codpro."' ".
							"	AND ced_bene='".$ls_cedbene."' ";		  
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_eliminar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="DELETE";
						$ls_descripcion ="Elimino la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$ls_cedbene.
										 "Proveedor ".$ls_codpro." Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					}	
				}
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Recepción de Documentos fue eliminada.");
					//$this->io_sql->rollback();
					$this->io_sql->commit();
				}
				else
				{
					$this->io_mensajes->message("Ocurrio un Error al Eliminar la Recepción de Documentos."); 
					$this->io_sql->rollback();
				}
			}
			else
			{
				$this->io_mensajes->message("La Recepción de Documentos existe en una Solicitud de Pago.");
				$lb_valido=false;	
			}
		}
		else
		{
			$this->io_mensajes->message("La Recepción de Documentos no existe, no la puede eliminar.");
			$lb_valido=false;	
		}
		return $lb_valido;
	}// end function uf_delete
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitudes_pago($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitudes_pago
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de verificar si hay solicitudes de pago asociadas a la Recepción de Documento.
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT cxp_dt_solicitudes.codemp ".
				"  FROM cxp_dt_solicitudes,cxp_rd_spg ".
		  		" WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND trim(cxp_dt_solicitudes.numrecdoc) = '".trim($as_numrecdoc)."' ".
				"   AND cxp_dt_solicitudes.codtipdoc = '".$as_codtipdoc."' ".
		  		"   AND cxp_dt_solicitudes.cod_pro = '".$as_codpro."' ".
				"   AND cxp_dt_solicitudes.ced_bene = '".$as_cedbene."' ".
				"   AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc ".
				"   AND cxp_rd_spg.codestpro||cxp_rd_spg.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_select_solicitudes_pago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_solicitudes_pago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_comprobantes_positivos($as_tipodestino,$as_codpro,$as_cedbene,$as_fechahasta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_comprobantes_positivos
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_fechahasta  // Fecha hasta donde se van a tomar los comprobantes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CS' Compromiso simple hasta la fecha y con monto positivo.      
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_operacion="CS";
		$ls_sql="SELECT DISTINCT sigesp_cmp.procede, sigesp_cmp.comprobante, sigesp_cmp.fecha, sigesp_cmp.descripcion, ".
				"				  sigesp_cmp.total ".
				"  FROM sigesp_cmp, spg_dt_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".trim($as_codpro)."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".trim($as_cedbene)."' ".
				"   AND sigesp_cmp.fecha <= '".$as_fechahasta."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.monto > 0 ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)".
				" ORDER BY sigesp_cmp.comprobante ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_comprobantes_positivos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_comprobantes_positivos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_comprobantes_cuenta($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,
											   $as_fechahasta,$as_programatica,$as_estcla,$as_spguenta,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_comprobantes_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//	  			   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_fechahasta  // Fecha hasta donde se van a tomar los comprobantes
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CS' Compromiso simple hasta la fecha y con monto positivo.      
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_operacion="CS";
		$ls_codestpro1=substr($as_programatica,0,25);
		$ls_codestpro2=substr($as_programatica,25,25);
		$ls_codestpro3=substr($as_programatica,50,25);
		$ls_codestpro4=substr($as_programatica,75,25);
		$ls_codestpro5=substr($as_programatica,100,25);
		$ls_sql="SELECT COALESCE(SUM(spg_dt_cmp.monto),0) AS monto ".
				"  FROM sigesp_cmp, spg_dt_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND sigesp_cmp.fecha <= '".$as_fechahasta."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
  		     	"   AND spg_dt_cmp.codestpro1='".$ls_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$ls_codestpro2."' ".
			 	"   AND spg_dt_cmp.codestpro3='".$ls_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$ls_codestpro4."' ".
			 	"   AND spg_dt_cmp.codestpro5='".$ls_codestpro5."' ".
				"   AND spg_dt_cmp.estcla='".$as_estcla."' ".
				"   AND spg_dt_cmp.spg_cuenta='".$as_spguenta."' ".
				"   AND spg_dt_cmp.monto > 0 ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_comprobantes_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_monto_comprobantes_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_ajustes($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_comprobantes_positivos
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CS' Compromiso simple hasta la fecha y con monto negativo.      
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_operacion="CS";
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(SUM(spg_dt_cmp.monto),0) AS monto ".
				" FROM spg_dt_cmp, sigesp_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
				"   AND spg_dt_cmp.monto < 0 ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_comprobantes_positivos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_ajustes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_ajustes_cuenta($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,
										  $as_programatica,$as_estcla,$as_spguenta,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_ajustes_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CS' Compromiso simple hasta la fecha y con monto negativo.      
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_operacion="CS";
		$ai_monto=0;
		$lb_valido=true; 
		$ls_codestpro1=substr($as_programatica,0,25);
		$ls_codestpro2=substr($as_programatica,25,25);
		$ls_codestpro3=substr($as_programatica,50,25);
		$ls_codestpro4=substr($as_programatica,75,25);
		$ls_codestpro5=substr($as_programatica,100,25);
		$ls_sql="SELECT COALESCE(SUM(spg_dt_cmp.monto),0) AS monto ".
				" FROM spg_dt_cmp, sigesp_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
  		     	"   AND spg_dt_cmp.codestpro1='".$ls_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$ls_codestpro2."' ".
			 	"   AND spg_dt_cmp.codestpro3='".$ls_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$ls_codestpro4."' ".
			 	"   AND spg_dt_cmp.codestpro5='".$ls_codestpro5."' ".
				"   AND spg_dt_cmp.estcla='".$as_estcla."' ".
				"   AND spg_dt_cmp.spg_cuenta='".$as_spguenta."' ".
				"   AND spg_dt_cmp.monto < 0 ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_ajustes_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_ajustes_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_causados($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_causados
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'GC' Gasto Causado y 'CP' Gasto Causado y Pagado 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_operacion1="GC";
		$ls_operacion2="CP";
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(SUM(spg_dt_cmp.monto),0) AS monto ".
				" FROM spg_dt_cmp, sigesp_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_causados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_causados
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_causados_cuenta($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,
										   $as_programatica,$as_estcla,$as_spguenta,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_causados_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_fechahasta  // Fecha hasta donde se van a tomar los comprobantes
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'CG' Compromiso y Gasto Causado y 'CP' Gasto Causado y Pagado 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_operacion1="CG";
		$ls_operacion2="CP";
		$ai_monto=0;
		$lb_valido=true; 
		$ls_codestpro1=substr($as_programatica,0,25);
		$ls_codestpro2=substr($as_programatica,25,25);
		$ls_codestpro3=substr($as_programatica,50,25);
		$ls_codestpro4=substr($as_programatica,75,25);
		$ls_codestpro5=substr($as_programatica,100,25);
		$ls_sql="SELECT COALESCE(SUM(spg_dt_cmp.monto),0) AS monto ".
				" FROM spg_dt_cmp, sigesp_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
  		     	"   AND spg_dt_cmp.codestpro1='".$ls_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$ls_codestpro2."' ".
			 	"   AND spg_dt_cmp.codestpro3='".$ls_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$ls_codestpro4."' ".
			 	"   AND spg_dt_cmp.codestpro5='".$ls_codestpro5."' ".
				"   AND spg_dt_cmp.estcla='".$as_estcla."' ".
				"   AND spg_dt_cmp.spg_cuenta='".$as_spguenta."' ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_causados_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_causados_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_comprobantes_anulados($as_comprobante,$as_tipodestino,$as_codpro,$as_cedbene,$as_fechahasta,&$as_numcomanu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_comprobantes_anulados
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_fechahasta  // Fecha hasta donde se van a tomar los comprobantes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'GS' Gasto Causado hasta la fecha y con monto positivo.      
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_numcomanu="";
		$ls_operacion="GC";
		$ls_sql="SELECT DISTINCT sigesp_cmp.comprobante ".
				"  FROM sigesp_cmp, spg_dt_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND sigesp_cmp.fecha <= '".$as_fechahasta."' ".
				"   AND spg_dt_cmp.operacion='".$ls_operacion."'".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.monto > 0 ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_comprobantes_anulados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$as_numcomanu[$li_i]=$row["comprobante"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_comprobantes_anulados
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_anulados($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,&$ai_monto) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_anulados
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'GC' Gasto Causado y 'CP' compromiso simple
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// OJO VERIFICAR SENTENCIA CON UN SELECT QUE NO ENTIENDO EN EL CÓDIGO VIEJO
		$ai_monto=0;
		$lb_valido=true; 
		$ls_operacion1="GC";
		$as_procedencia='CXPSOP';
		$ls_operacion2="CP";
		$ls_sql="SELECT COALESCE(SUM(spg_dt_cmp.monto),0) AS monto ".
				" FROM spg_dt_cmp, sigesp_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_anulados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_anulados
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_anulados_cuenta($as_comprobante,$as_procedencia,$as_tipodestino,$as_codpro,$as_cedbene,
										   $as_programatica,$as_estcla,$as_spguenta,&$ai_monto) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_anulados_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_tipodestino  // Tipo Destino
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de extraer todos aquellos comprobantes asociados al proveedor y/o beneficiario 
		//				   en estatus 'GC' Gasto Causado y 'CP' compromiso simple
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// OJO VERIFICAR SENTENCIA CON UN SELECT QUE NO ENTIENDO EN EL CÓDIGO VIEJO
		$ai_monto=0;
		$lb_valido=true; 
		$ls_operacion1="GC";
		$as_procedencia='CXPSOP';
		$ls_operacion2="CP";
		$ls_codestpro1=substr($as_programatica,0,25);
		$ls_codestpro2=substr($as_programatica,25,25);
		$ls_codestpro3=substr($as_programatica,50,25);
		$ls_codestpro4=substr($as_programatica,75,25);
		$ls_codestpro5=substr($as_programatica,100,25);
		$ls_sql="SELECT COALESCE(SUM(spg_dt_cmp.monto),0) AS monto ".
				" FROM spg_dt_cmp, sigesp_cmp ".
				" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.tipo_destino='".$as_tipodestino."'".
				"	AND TRIM(sigesp_cmp.cod_pro)='".$as_codpro."'".
				"   AND TRIM(sigesp_cmp.ced_bene)='".$as_cedbene."' ".
				"   AND (spg_dt_cmp.operacion='".$ls_operacion1."' OR spg_dt_cmp.operacion='".$ls_operacion2."')".
				"   AND spg_dt_cmp.documento='".$as_comprobante."'".
				"   AND spg_dt_cmp.procede_doc='".$as_procedencia."'".
  		     	"   AND spg_dt_cmp.codestpro1='".$ls_codestpro1."' ".
				"   AND spg_dt_cmp.codestpro2='".$ls_codestpro2."' ".
			 	"   AND spg_dt_cmp.codestpro3='".$ls_codestpro3."' ".
				"   AND spg_dt_cmp.codestpro4='".$ls_codestpro4."' ".
			 	"   AND spg_dt_cmp.codestpro5='".$ls_codestpro5."' ".
				"   AND spg_dt_cmp.estcla='".$as_estcla."' ".
				"   AND spg_dt_cmp.spg_cuenta='".$as_spguenta."' ".
				"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																	UNION ".							
				"																	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_anulados_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_anulados_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_recepciones($as_comprobante,$as_procedencia,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_recepciones
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   ai_monto  // Monto de las Recepciones
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas las recepciones asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(sum(cxp_rd_spg.monto),0) AS monto ".
				"  FROM cxp_rd_spg,cxp_rd ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_spg.procede_doc='".$as_procedencia."' ".
				"	AND cxp_rd_spg.numdoccom='".$as_comprobante."' ".
				"   AND cxp_rd.codemp=cxp_rd_spg.codemp ".
				"   AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro ".
				"   AND trim(cxp_rd.ced_bene)=trim(cxp_rd_spg.ced_bene) ".
				"   AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc ".
				"   AND trim(cxp_rd.numrecdoc)=trim(cxp_rd_spg.numrecdoc) ".
				"   AND trim(cxp_rd.numrecdoc) NOT IN (SELECT trim(cxp_dt_solicitudes.numrecdoc) as numrecdoc".
				"						    	   FROM cxp_dt_solicitudes,cxp_solicitudes".
				"						   		  WHERE cxp_solicitudes.estprosol<>'E' ".
				"							 		AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp".
				"							 		AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
				"   						 		AND cxp_rd.codemp=cxp_dt_solicitudes.codemp ".
				"   						 		AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro ".
				"   					     		AND trim(cxp_rd.ced_bene)=trim(cxp_dt_solicitudes.ced_bene) ".
				"   						 		AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc ".
				"   						 		AND trim(cxp_rd.numrecdoc)=trim(cxp_dt_solicitudes.numrecdoc)) ".
				"   AND codestpro||cxp_rd_spg.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							   UNION ".							
				"							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_recepciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_recepciones_cuenta($as_comprobante,$as_procedencia,$as_programatica,$as_estcla,$as_spguenta,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_recepciones_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de las Recepciones
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas las recepciones asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(sum(cxp_rd_spg.monto),0) AS monto ".
				"  FROM cxp_rd_spg,cxp_rd ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_spg.procede_doc='".$as_procedencia."' ".
				"	AND cxp_rd_spg.numdoccom='".$as_comprobante."' ".
				"	AND cxp_rd_spg.codestpro='".$as_programatica."' ".
				"	AND cxp_rd_spg.estcla='".$as_estcla."' ".
				"	AND cxp_rd_spg.spg_cuenta='".$as_spguenta."' ".
				"   AND cxp_rd.codemp=cxp_rd_spg.codemp ".
				"   AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro ".
				"   AND trim(cxp_rd.ced_bene)=trim(cxp_rd_spg.ced_bene) ".
				"   AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc ".
				"   AND trim(cxp_rd.numrecdoc)=trim(cxp_rd_spg.numrecdoc) ".
				"   AND (cxp_rd.codemp,trim(cxp_rd.numrecdoc), cxp_rd.codtipdoc, cxp_rd.ced_bene, cxp_rd.cod_pro) ".
				"NOT IN                                                                                           ".
				"(SELECT cxp_dt_solicitudes.codemp, trim(cxp_dt_solicitudes.numrecdoc), cxp_dt_solicitudes.codtipdoc, cxp_dt_solicitudes.ced_bene, cxp_dt_solicitudes.cod_pro   ".
				"						    	   FROM cxp_dt_solicitudes,cxp_solicitudes,cxp_rd  ".
				"						   		  WHERE cxp_solicitudes.estprosol<>'E' ".
				"							 		AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp".
				"							 		AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
				"   						 		AND cxp_rd.codemp=cxp_dt_solicitudes.codemp ".
				"   						 		AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro ".
				"   					     		AND trim(cxp_rd.ced_bene)=trim(cxp_dt_solicitudes.ced_bene) ".
				"   						 		AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc ".
				"   						 		AND trim(cxp_rd.numrecdoc)=trim(cxp_dt_solicitudes.numrecdoc)) ".
				"   AND codestpro||cxp_rd_spg.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							   UNION ".							
				"							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_recepciones_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_recepciones_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_ordenespago_directa($as_comprobante,$as_procedencia,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_ordenespago_directa
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   ai_monto  // Monto de las ordenes de pago
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas las Ordenes de pago asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(sum(scb_movbco_spgop.monto),0) as monto ".
				"  FROM scb_movbco_spgop, scb_movbco ".
				" WHERE scb_movbco.codemp='".$this->ls_codemp."' ".
				"   AND scb_movbco_spgop.procede_doc='".$as_procedencia."' ".
				"	AND scb_movbco_spgop.documento='".$as_comprobante."' ".
				"   AND scb_movbco.codemp=scb_movbco_spgop.codemp ".
				"   AND scb_movbco.numdoc=scb_movbco_spgop.numdoc ".
				"   AND scb_movbco.codope=scb_movbco_spgop.codope ".
				"   AND scb_movbco.codban=scb_movbco_spgop.codban ".
				"   AND scb_movbco.ctaban=scb_movbco_spgop.ctaban ".
				"   AND codestpro||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							   UNION ".							
				"							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_ordenespago_directa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_ordenespago_directa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_ordenespago_directa_cuenta($as_comprobante,$as_procedencia,$as_programatica,$as_estcla,$as_spguenta,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_ordenespago_directa
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de las ordenes de pago
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas las Ordenes de pago asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(sum(scb_movbco_spgop.monto),0) as monto ".
				"  FROM scb_movbco_spgop, scb_movbco ".
				" WHERE scb_movbco.codemp='".$this->ls_codemp."' ".
				"   AND scb_movbco_spgop.procede_doc='".$as_procedencia."' ".
				"	AND scb_movbco_spgop.documento='".$as_comprobante."' ".
				"	AND scb_movbco_spgop.codestpro='".$as_programatica."' ".
				"	AND scb_movbco_spgop.estcla='".$as_estcla."' ".
				"	AND scb_movbco_spgop.spg_cuenta='".$as_spguenta."' ".
				"   AND scb_movbco.codemp=scb_movbco_spgop.codemp ".
				"   AND scb_movbco.numdoc=scb_movbco_spgop.numdoc ".
				"   AND scb_movbco.codope=scb_movbco_spgop.codope ".
				"   AND scb_movbco.codban=scb_movbco_spgop.codban ".
				"   AND scb_movbco.ctaban=scb_movbco_spgop.ctaban ".
				"   AND codestpro||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							   UNION ".							
				"							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_ordenespago_directa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_ordenespago_directa_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_cargos($as_comprobante,$as_procedencia,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_cargos
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   ai_monto  // Monto de las ordenes de pago
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas los Cargos asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(sum(cxp_rd_cargos.monret),0) AS monto     ".
				"  FROM cxp_rd_cargos, cxp_rd ,sigesp_cargos".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_cargos.procede_doc='".$as_procedencia."' ".
				"	AND cxp_rd_cargos.numdoccom='".$as_comprobante."' ".
				"   AND cxp_rd.codemp=cxp_rd_cargos.codemp ".
				"	AND	trim(cxp_rd.numrecdoc)=trim(cxp_rd_cargos.numrecdoc) ".
				"   AND cxp_rd.codtipdoc = cxp_rd_cargos.codtipdoc ".
				"   AND cxp_rd.cod_pro=cxp_rd_cargos.cod_pro ".
				"   AND trim(cxp_rd.ced_bene)=trim(cxp_rd_cargos.ced_bene) ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"																			    UNION ".							
				"																	 		   SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_cargos_cuenta($as_comprobante,$as_procedencia,$as_programatica,$as_estcla,$as_spguenta,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_cargos_cuenta
		//		   Access: private
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procedencia  // Procede del Comprobante
		//				   as_programatica  // Programatica
		//				   as_spguenta  // Cuenta presupuestaria
		//				   ai_monto  // Monto de las ordenes de pago
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas los Cargos asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_codestpro1=substr($as_programatica,0,25);
		$ls_codestpro2=substr($as_programatica,25,25);
		$ls_codestpro3=substr($as_programatica,50,25);
		$ls_codestpro4=substr($as_programatica,75,25);
		$ls_codestpro5=substr($as_programatica,100,25);
		$ls_sql="SELECT COALESCE(sum(cxp_rd_cargos.monret),0) AS monto     ".
				"  FROM cxp_rd_cargos, cxp_rd ".
				" WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_cargos.procede_doc='".$as_procedencia."' ".
				"	AND cxp_rd_cargos.numdoccom='".$as_comprobante."' ".
  		     	"   AND cxp_rd_cargos.codestpro1='".$ls_codestpro1."' ".
				"   AND cxp_rd_cargos.codestpro2='".$ls_codestpro2."' ".
			 	"   AND cxp_rd_cargos.codestpro3='".$ls_codestpro3."' ".
				"   AND cxp_rd_cargos.codestpro4='".$ls_codestpro4."' ".
			 	"   AND cxp_rd_cargos.codestpro5='".$ls_codestpro5."' ".
				"   AND cxp_rd_cargos.estcla='".$as_estcla."' ".
				"   AND cxp_rd_cargos.spg_cuenta='".$as_spguenta."' ".
				"   AND cxp_rd.codemp=cxp_rd_cargos.codemp ".
				"	AND	trim(cxp_rd.numrecdoc)=trim(cxp_rd_cargos.numrecdoc) ".
				"   AND cxp_rd.codtipdoc = cxp_rd_cargos.codtipdoc ".
				"   AND cxp_rd.cod_pro=cxp_rd_cargos.cod_pro ".
				"   AND trim(cxp_rd.ced_bene)=trim(cxp_rd_cargos.ced_bene) ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							  													UNION ".							
				"																			   SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_cargos_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_cargos_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_acumulado_solicitudes($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,&$ai_monto)
	{    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_acumulado_solicitudes
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numrecdoc  // Número de Recepcion
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de todas los Cargos asociadas a este comprobante y procede    
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE(SUM(cxp_dt_solicitudes.monto),0) AS monto ".
			    "  FROM cxp_rd, cxp_dt_solicitudes,cxp_rd_spg ".
			    " WHERE cxp_rd.codemp='".$this->ls_codemp."' ".
				"   AND trim(cxp_rd.numrecdoc) = '".trim($as_numrecdoc)."'  ".
				"   AND cxp_rd.codtipdoc='".$as_codtipdoc."' ".
			    "   AND cxp_rd.cod_pro='".$as_codpro."' ".
				"   AND trim(cxp_rd.ced_bene) = '".trim($as_cedbene)."'  ".
				"   AND cxp_rd.codemp=cxp_dt_solicitudes.codemp  ".
			    "   AND trim(cxp_rd.numrecdoc) = trim(cxp_dt_solicitudes.numrecdoc) ".
				"   AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
				" 	AND cxp_rd.codemp=cxp_rd_spg.codemp ".
				"   AND trim(cxp_rd.numrecdoc) = cxp_rd_spg.numrecdoc  ".
				"   AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc ".
			    "   AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro ".
				"   AND trim(cxp_rd.ced_bene) = cxp_rd_spg.ced_bene  ".
				"	AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||cxp_rd_spg.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							  										UNION ".							
				"																   SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_acumulado_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_acumulado_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_causado_anterior($as_comprobante,$as_procede,$as_spgcuenta,$as_codestpro,$as_estcla,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_causado_anterior
		//		   Access: public (sigesp_cxp_c_recepcion_ajax.php)
		//	    Arguments: as_comprobante  // Número de comprobante
		//				   as_procede  // Procede de la cuenta
		//				   as_spgcuenta  // Cuenta del movimiento
		//				   as_codestpro  // Código de Programatica
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de los montos causadoas anteriormente
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE( SUM(CASE WHEN cxp_rd_spg.monto is null THEN 0 ELSE cxp_rd_spg.monto END),0) AS monto ".
				"  FROM cxp_rd_spg, cxp_rd ".
				" WHERE cxp_rd_spg.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_spg.procede_doc='".$as_procede."' ".
				"   AND cxp_rd_spg.numdoccom='".$as_comprobante."' ".
				"   AND cxp_rd_spg.spg_cuenta='".$as_spgcuenta."' ".
				"   AND cxp_rd_spg.codestpro='".$as_codestpro."' ". 
				"   AND cxp_rd_spg.estcla='".$as_estcla."' ". 
				"   AND cxp_rd_spg.codemp=cxp_rd.codemp ".
				"   AND trim(cxp_rd_spg.numrecdoc) = trim(cxp_rd.numrecdoc) ".
				"   AND cxp_rd_spg.codtipdoc=cxp_rd.codtipdoc ".
				"   AND trim(cxp_rd_spg.ced_bene) = trim(cxp_rd.ced_bene) ".
				"   AND cxp_rd_spg.cod_pro=cxp_rd.cod_pro ".
				"   AND cxp_rd.estprodoc<>'A' ";
				/*
				"	AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||cxp_rd_spg.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"	UNION ".							
				"	SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; */
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_monto_causado_anterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_monto_causado_anterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargo_causado_anterior($as_codcar,$as_comprobante,$as_procede,$as_codestpro1,$as_codestpro2,$as_codestpro3,
											$as_codestpro4,$as_codestpro5,$as_estcla,$as_cuenta,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargo_causado_anterior
		//		   Access: public (sigesp_cxp_c_recepcion_ajax.php)
		//	    Arguments: as_codcar  // Codigo de Cargo
		//				   as_comprobante  // Numero de compromiso
		//				   as_procede  // Procede del Documento
		//				   as_codestpro1  // Código de Programatica Nivel 1
		//				   as_codestpro2  // Código de Programatica Nivel 2
		//				   as_codestpro3  // Código de Programatica Nivel 3
		//				   as_codestpro4  // Código de Programatica Nivel 4
		//				   as_codestpro5  // Código de Programatica Nivel 5
		//				   as_cuenta     // Cuenta Presupuestaria 
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de buscar la suma de los montos de los cargos causadoas anteriormente
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 21/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monto=0;
		$lb_valido=true; 
		$ls_sql="SELECT COALESCE( (SUM(CASE WHEN cxp_rd_cargos.monret IS NULL THEN 0 ELSE cxp_rd_cargos.monret END)),0) AS monto ".
				"  FROM cxp_rd_cargos, cxp_rd ".
				" WHERE cxp_rd_cargos.codemp='".$this->ls_codemp."' ".
				"   AND cxp_rd_cargos.codcar='".$as_codcar."' ".
				"   AND cxp_rd_cargos.procede_doc='".$as_procede."' ".
				"   AND cxp_rd_cargos.numdoccom='".$as_comprobante."' ".
				"   AND cxp_rd_cargos.spg_cuenta='".$as_cuenta."' ".
				"   AND cxp_rd_cargos.codestpro1='".$as_codestpro1."' ". 
				"   AND cxp_rd_cargos.codestpro2='".$as_codestpro2."' ". 
				"   AND cxp_rd_cargos.codestpro3='".$as_codestpro3."' ". 
				"   AND cxp_rd_cargos.codestpro4='".$as_codestpro4."' ". 
				"   AND cxp_rd_cargos.codestpro5='".$as_codestpro5."' ". 
				"   AND cxp_rd_cargos.estcla='".$as_estcla."' ". 
				"   AND cxp_rd_cargos.codemp=cxp_rd.codemp ".
				"   AND trim(cxp_rd_cargos.numrecdoc) = trim(cxp_rd.numrecdoc) ".
				"   AND cxp_rd_cargos.codtipdoc=cxp_rd.codtipdoc ".
				"   AND trim(cxp_rd_cargos.ced_bene)=trim(cxp_rd.ced_bene) ".
				"   AND cxp_rd_cargos.cod_pro=cxp_rd.cod_pro ".
				"   AND cxp_rd.estprodoc<>'A' ".
				"   AND cxp_rd_cargos.codestpro1||cxp_rd_cargos.codestpro2||cxp_rd_cargos.codestpro3||cxp_rd_cargos.codestpro4||cxp_rd_cargos.codestpro5||cxp_rd_cargos.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							  													UNION ".							
				"																			   SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargo_causado_anterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=$row["monto"];
			}  
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_cargo_causado_anterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_compromiso_sep($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_compromiso_sep
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 13/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_cuentagasto.numsol AS comprobante, sep_cuentagasto.codestpro1, sep_cuentagasto.codestpro2, ".
				"		sep_cuentagasto.codestpro3, sep_cuentagasto.codestpro4, sep_cuentagasto.codestpro5, ".
				"		sep_cuentagasto.spg_cuenta, sep_cuentagasto.monto, spg_cuentas.sc_cuenta, '--' AS codfuefin, sep_cuentagasto.estcla, ".
				"		(SELECT COUNT(codemp) FROM sep_solicitudcargos ".
				"		  WHERE sep_solicitudcargos.codemp = sep_cuentagasto.codemp  ".
				"			AND sep_solicitudcargos.numsol = sep_cuentagasto.numsol  ".
				"			AND sep_solicitudcargos.codestpro1 = sep_cuentagasto.codestpro1 ".
				"		    AND sep_solicitudcargos.codestpro2 = sep_cuentagasto.codestpro2 ".
				"		    AND sep_solicitudcargos.codestpro3 = sep_cuentagasto.codestpro3 ".
				"		    AND sep_solicitudcargos.codestpro4 = sep_cuentagasto.codestpro4 ".
				"		    AND sep_solicitudcargos.codestpro5 = sep_cuentagasto.codestpro5 ".
				"		    AND sep_solicitudcargos.estcla = sep_cuentagasto.estcla ".
				"			AND sep_solicitudcargos.spg_cuenta = spg_cuentas.spg_cuenta ) AS cargo, ".
				"       '' AS tipbieordcom, '' AS estint, '' AS cuentaint".
				"  FROM sep_cuentagasto, spg_cuentas ".
				" WHERE sep_cuentagasto.codemp = '".$this->ls_codemp."' ".
				"   AND sep_cuentagasto.numsol = '".$as_numsol."' ".
				"   AND sep_cuentagasto.codemp = spg_cuentas.codemp ".
				"   AND sep_cuentagasto.spg_cuenta = spg_cuentas.spg_cuenta ".
				"	AND sep_cuentagasto.codestpro1 = spg_cuentas.codestpro1 ".
				"	AND sep_cuentagasto.codestpro2 = spg_cuentas.codestpro2 ".
				"	AND sep_cuentagasto.codestpro3 = spg_cuentas.codestpro3 ".
				"	AND sep_cuentagasto.codestpro4 = spg_cuentas.codestpro4 ".
				"	AND sep_cuentagasto.codestpro5 = spg_cuentas.codestpro5 ".
				"	AND sep_cuentagasto.estcla = spg_cuentas.estcla ".
				"   AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							   UNION ".							
				"							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_compromiso_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_compromiso_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromiso_sep($as_numsol,&$ai_total_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromiso_sep
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//				   ai_total_cargos  // Suma de los cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 13/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ai_total_cargos=0;
		$ls_sql="SELECT sep_solicitudcargos.codcar, sep_solicitudcargos.numsol, sep_solicitudcargos.codestpro1, ".
				"		sep_solicitudcargos.codestpro2, sep_solicitudcargos.codestpro3, sep_solicitudcargos.codestpro4, ".
				"		sep_solicitudcargos.codestpro5, sep_solicitudcargos.spg_cuenta, sep_solicitudcargos.sc_cuenta, ".
				"		sep_solicitudcargos.formula, sep_solicitudcargos.monobjret, sep_solicitudcargos.monto, ".
				"		sigesp_cargos.porcar, sep_solicitudcargos.estcla ".
				"  FROM sep_solicitudcargos, sigesp_cargos ".
				" WHERE sep_solicitudcargos.codemp='".$this->ls_codemp."' ".
				"   AND sep_solicitudcargos.numsol='".$as_numsol."' ".
				"   AND sep_solicitudcargos.codcar=sigesp_cargos.codcar ".
				"   AND sigesp_cargos.codestpro||sigesp_cargos.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							   							   UNION ".							
				"							  							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromiso_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_existe=false;
			if(array_key_exists("cargos",$_SESSION))
			{
				$this->io_ds_cargos->data=$_SESSION["cargos"];
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$lb_existe=true;
				$ls_codcar=$row["codcar"];
  				$ls_nrocomp=$row["numsol"];
				$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_cuenta=$row["spg_cuenta"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_formula=$row["formula"];
				$ls_porcar=$row["porcar"];
				$ls_procede="SEPSPC";
				$ls_cargo="1";
				$li_original=$row["monto"];
				$li_baseimp=$row["monobjret"];
				$li_monto_anterior=0;
				//$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$ls_procede,$ls_cuenta,$ls_codpro,&$li_monto_anterior);
				$lb_valido=$this->uf_load_cargo_causado_anterior($ls_codcar,$ls_nrocomp,$ls_procede,$row["codestpro1"],
															     $row["codestpro2"],$row["codestpro3"],$row["codestpro4"],
																 $row["codestpro5"],$ls_estcla,$ls_cuenta,&$li_monto_anterior);

				$li_monimp=$row["monto"]-$li_monto_anterior;
				$ls_codfuefin="--";
				if($lb_valido)
				{
					$ai_total_cargos=$ai_total_cargos+$li_monimp;
					$this->io_ds_cargos->insertRow("codcar",$ls_codcar);			
					$this->io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
					$this->io_ds_cargos->insertRow("baseimp",$li_baseimp);			
					$this->io_ds_cargos->insertRow("monimp",$li_monimp);			
					$this->io_ds_cargos->insertRow("codpro",$ls_codpro);			
					$this->io_ds_cargos->insertRow("estcla",$ls_estcla);			
					$this->io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
					$this->io_ds_cargos->insertRow("original",$li_original);			
					$this->io_ds_cargos->insertRow("formula",$ls_formula);			
					$this->io_ds_cargos->insertRow("porcar",$ls_porcar);			
					$this->io_ds_cargos->insertRow("procededoc",$ls_procede);			
					$this->io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
					$this->io_ds_cargos->insertRow("cargo",$ls_cargo);	
					$this->io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);	
				}		
			}
			$this->io_sql->free_result($rs_data);
			if(($lb_existe)&&($lb_valido))
			{
				$_SESSION["cargos"]=$this->io_ds_cargos->data;
			}
		}		
		return $lb_valido;
	}// end function uf_load_cargos_compromiso_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargoscontable_compromiso_sep($as_numsol,&$ai_total_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargoscontable_compromiso_sep
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numsol  // Número de Solicitud
		//				   ai_total_cargos  // Suma de los cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 13/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ai_total_cargos=0;
		$ls_sql="SELECT sep_dtc_cargos.codcar, sep_dtc_cargos.numsol, sigesp_cargos.spg_cuenta, sigesp_cargos.spg_cuenta AS sc_cuenta, ".
				"		sep_dtc_cargos.formula, sep_dtc_cargos.monbasimp AS monobjret, sep_dtc_cargos.monimp AS monto, ".
				"		sigesp_cargos.porcar, 'A' AS estcla ".
				"  FROM sep_dtc_cargos, sigesp_cargos ".
				" WHERE sep_dtc_cargos.codemp='".$this->ls_codemp."' ".
				"   AND sep_dtc_cargos.numsol='".$as_numsol."' ".
				"   AND sep_dtc_cargos.codcar=sigesp_cargos.codcar"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargoscontable_compromiso_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_existe=false;
			if(array_key_exists("cargos",$_SESSION))
			{
				$this->io_ds_cargos->data=$_SESSION["cargos"];
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$lb_existe=true;
				$ls_codcar=$row["codcar"];
  				$ls_nrocomp=$row["numsol"];
				$ls_codpro="-----------------------------------------------------------------------------------------------------------------------------";
				$ls_estcla=$row["estcla"];
				$ls_cuenta=$row["spg_cuenta"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_formula=$row["formula"];
				$ls_porcar=$row["porcar"];
				$ls_procede="SEPSPC";
				$ls_cargo="1";
				$li_original=$row["monto"];
				$li_baseimp=$row["monobjret"];
				$li_monto_anterior=0;
				//$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$ls_procede,$ls_cuenta,$ls_codpro,&$li_monto_anterior);
				$lb_valido=$this->uf_load_cargo_causado_anterior($ls_codcar,$ls_nrocomp,$ls_procede,"-------------------------",
															     "-------------------------","-------------------------","-------------------------",
																 "-------------------------",$ls_estcla,$ls_cuenta,&$li_monto_anterior);

				$li_monimp=$row["monto"]-$li_monto_anterior;
				$ls_codfuefin="--";
				if($lb_valido)
				{
					$ai_total_cargos=$ai_total_cargos+$li_monimp;
					$this->io_ds_cargos->insertRow("codcar",$ls_codcar);			
					$this->io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
					$this->io_ds_cargos->insertRow("baseimp",$li_baseimp);			
					$this->io_ds_cargos->insertRow("monimp",$li_monimp);			
					$this->io_ds_cargos->insertRow("codpro",$ls_codpro);			
					$this->io_ds_cargos->insertRow("estcla",$ls_estcla);			
					$this->io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
					$this->io_ds_cargos->insertRow("original",$li_original);			
					$this->io_ds_cargos->insertRow("formula",$ls_formula);			
					$this->io_ds_cargos->insertRow("porcar",$ls_porcar);			
					$this->io_ds_cargos->insertRow("procededoc",$ls_procede);			
					$this->io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
					$this->io_ds_cargos->insertRow("cargo",$ls_cargo);	
					$this->io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);	
				}		
			}
			$this->io_sql->free_result($rs_data);
			if(($lb_existe)&&($lb_valido))
			{
				$_SESSION["cargos"]=$this->io_ds_cargos->data;
			}
		}
		return $lb_valido;
	}// end function uf_load_cargoscontable_compromiso_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_compromiso_sob($as_codcon)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_compromiso_sob
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_codcon  // Número de Contrato
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a un contrato
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 13/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codcon = substr($as_codcon,3,12);
		$ls_sql="SELECT '".$as_codcon."' AS comprobante, sob_cuentasasignacion.codestpro1, sob_cuentasasignacion.codestpro2, ".
				"		sob_cuentasasignacion.codestpro3, sob_cuentasasignacion.codestpro4, sob_cuentasasignacion.codestpro5, ".
				"		sob_cuentasasignacion.spg_cuenta, sob_cuentasasignacion.monto, spg_cuentas.sc_cuenta, '--' AS codfuefin, sob_cuentasasignacion.estcla, ".
				"		(SELECT COUNT(codemp) FROM sob_cargoasignacion ".
				"		  WHERE sob_cargoasignacion.codemp = sob_cuentasasignacion.codemp  ".
				"			AND sob_cargoasignacion.codasi = sob_cuentasasignacion.codasi  ".
				"			AND substr(sob_cargoasignacion.codestprog,1,25) = sob_cuentasasignacion.codestpro1 ".
				"		    AND substr(sob_cargoasignacion.codestprog,26,25) = sob_cuentasasignacion.codestpro2 ".
				"		    AND substr(sob_cargoasignacion.codestprog,51,25) = sob_cuentasasignacion.codestpro3 ".
				"		    AND substr(sob_cargoasignacion.codestprog,76,25) = sob_cuentasasignacion.codestpro4 ".
				"		    AND substr(sob_cargoasignacion.codestprog,101,25) = sob_cuentasasignacion.codestpro5 ".
				"		    AND sob_cargoasignacion.estcla = sob_cuentasasignacion.estcla ".
				"			AND trim(sob_cargoasignacion.spg_cuenta) = trim(spg_cuentas.spg_cuenta) ) AS cargo, ".
				"           '' AS tipbieordcom, '' AS estint, '' AS cuentaint ".
                "  FROM sob_cuentasasignacion, sob_contrato, spg_cuentas ".
                " WHERE sob_contrato.codemp='".$this->ls_codemp."' ".
				"   AND trim(sob_contrato.codcon) = '".$ls_codcon."' ".
				"   AND sob_contrato.codemp=sob_cuentasasignacion.codemp ".
				"   AND sob_contrato.codasi=sob_cuentasasignacion.codasi ".
				"   AND sob_cuentasasignacion.codemp = spg_cuentas.codemp ".
				"   AND sob_cuentasasignacion.spg_cuenta = spg_cuentas.spg_cuenta ".
				"	AND sob_cuentasasignacion.codestpro1 = spg_cuentas.codestpro1 ".
				"	AND sob_cuentasasignacion.codestpro2 = spg_cuentas.codestpro2 ".
				"	AND sob_cuentasasignacion.codestpro3 = spg_cuentas.codestpro3 ".
				"	AND sob_cuentasasignacion.codestpro4 = spg_cuentas.codestpro4 ".
				"	AND sob_cuentasasignacion.codestpro5 = spg_cuentas.codestpro5 ".
				"	AND sob_cuentasasignacion.estcla = spg_cuentas.estcla ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_compromiso_sob ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_compromiso_sob
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromiso_sob($as_codcon,&$ai_total_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromiso_sob
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_codcon  // Còdigo de contrato
		//				   ai_total_cargos  // Suma de los cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 13/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ai_total_cargos=0;
		$ls_codcon = substr($as_codcon,3,12);
		$ls_sql="SELECT sob_cargoasignacion.codcar, '".$as_codcon."' as nrocomp, substr(sob_cargoasignacion.codestprog,1,25) as codestpro1, ".
				"		substr(sob_cargoasignacion.codestprog,26,25) as codestpro2, substr(sob_cargoasignacion.codestprog,51,25) as codestpro3, ".
				"		substr(sob_cargoasignacion.codestprog,76,25) as codestpro4, substr(sob_cargoasignacion.codestprog,101,25) as codestpro5, ".
				"		sob_cargoasignacion.spg_cuenta, sob_cargoasignacion.formula, sob_cargoasignacion.basimp, sob_cargoasignacion.monto, ".
				"       sigesp_cargos.porcar, sob_cargoasignacion.estcla, ".
				"		(SELECT sc_cuenta FROM spg_cuentas ".
				"		  WHERE sob_cargoasignacion.codemp = spg_cuentas.codemp  ".
				"			AND substr(sob_cargoasignacion.codestprog,1,25) = spg_cuentas.codestpro1 ".
				"		    AND substr(sob_cargoasignacion.codestprog,26,25) = spg_cuentas.codestpro2 ".
				"		    AND substr(sob_cargoasignacion.codestprog,51,25) = spg_cuentas.codestpro3 ".
				"		    AND substr(sob_cargoasignacion.codestprog,76,25) = spg_cuentas.codestpro4 ".
				"		    AND substr(sob_cargoasignacion.codestprog,101,25) = spg_cuentas.codestpro5 ".
				"		    AND sob_cargoasignacion.estcla = spg_cuentas.estcla ".
				"			AND trim(sob_cargoasignacion.spg_cuenta) = trim(spg_cuentas.spg_cuenta) ) AS sc_cuenta ".
				"  FROM sob_cargoasignacion, sob_contrato, sigesp_cargos ".
				" WHERE sob_cargoasignacion.codemp='".$this->ls_codemp."' ".
				"   AND trim(sob_contrato.codcon) = '".$ls_codcon."' ".
				"   AND sob_contrato.codemp=sob_cargoasignacion.codemp ".
				"   AND sob_contrato.codasi=sob_cargoasignacion.codasi ".
				"   AND sob_cargoasignacion.codcar=sigesp_cargos.codcar"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromiso_sob ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_existe=false;
			if(array_key_exists("cargos",$_SESSION))
			{
				$this->io_ds_cargos->data=$_SESSION["cargos"];
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$lb_existe=true;
				$ls_codcar=$row["codcar"];
  				$ls_nrocomp=$row["nrocomp"];
				$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_cuenta=$row["spg_cuenta"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_formula=$row["formula"];
				$ls_porcar=$row["porcar"];
				$ls_procede="SOBCON";
				$ls_cargo="1";
				$li_original=$row["monto"];
				$li_baseimp=$row["basimp"];
				$li_monto_anterior=0;
				$lb_valido=$this->uf_load_cargo_causado_anterior($ls_codcar,$ls_nrocomp,$ls_procede,$row["codestpro1"],
															     $row["codestpro2"],$row["codestpro3"],$row["codestpro4"],
																 $row["codestpro5"],$ls_estcla,$ls_cuenta,&$li_monto_anterior);

				$li_monimp=$row["monto"]-$li_monto_anterior;
				$ls_codfuefin="--";
				if($lb_valido)
				{
					$ai_total_cargos=$ai_total_cargos+$li_monimp;
					$this->io_ds_cargos->insertRow("codcar",$ls_codcar);			
					$this->io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
					$this->io_ds_cargos->insertRow("baseimp",$li_baseimp);			
					$this->io_ds_cargos->insertRow("monimp",$li_monimp);			
					$this->io_ds_cargos->insertRow("codpro",$ls_codpro);			
					$this->io_ds_cargos->insertRow("estcla",$ls_estcla);			
					$this->io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
					$this->io_ds_cargos->insertRow("original",$li_original);			
					$this->io_ds_cargos->insertRow("formula",$ls_formula);			
					$this->io_ds_cargos->insertRow("porcar",$ls_porcar);			
					$this->io_ds_cargos->insertRow("procededoc",$ls_procede);			
					$this->io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
					$this->io_ds_cargos->insertRow("cargo",$ls_cargo);	
					$this->io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);	
				}		
			}
			$this->io_sql->free_result($rs_data);
			if(($lb_existe)&&($lb_valido))
			{
				$_SESSION["cargos"]=$this->io_ds_cargos->data;
			}
		}		
		return $lb_valido;
	}// end function uf_load_cargos_compromiso_sob
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_compromiso_spg($as_comprobante,$as_procede)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_compromiso_spg
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numordcom  // Número de Orden de Compra
		//	    		   as_estcondat  // estatus si la Orden de compra es de bienes ó de servicios
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a un Compromiso Simple
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_operacion='CS';
		
		$ls_sql="SELECT  spg_dt_cmp.comprobante AS comprobante, spg_dt_cmp.fecha, spg_dt_cmp.codestpro1, 
		                 spg_dt_cmp.codestpro2, spg_dt_cmp.codestpro3, spg_dt_cmp.codestpro4, spg_dt_cmp.codestpro5, 
						 spg_dt_cmp.spg_cuenta,spg_cuentas.sc_cuenta, sum(spg_dt_cmp.monto) AS monto, codfuefin AS codfuefin, spg_dt_cmp.estcla  
				  FROM   spg_dt_cmp, spg_cuentas 
				  WHERE  spg_dt_cmp.codemp     ='0001' 
				  AND    spg_dt_cmp.procede    ='".$as_procede."' 
				  AND    spg_dt_cmp.spg_cuenta =spg_cuentas.spg_cuenta 
				  AND    spg_dt_cmp.comprobante='".$as_comprobante."' 
				  AND    spg_dt_cmp.operacion  ='".$ls_operacion."' 
				  AND    spg_dt_cmp.codestpro1||spg_dt_cmp.codestpro2||spg_dt_cmp.codestpro3||spg_dt_cmp.codestpro4||spg_dt_cmp.codestpro5= spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||spg_cuentas.codestpro4||spg_cuentas.codestpro5
				GROUP BY spg_dt_cmp.comprobante, spg_dt_cmp.fecha, spg_dt_cmp.codestpro1, spg_dt_cmp.codestpro2, spg_dt_cmp.codestpro3, 
						 spg_dt_cmp.codestpro4, spg_dt_cmp.codestpro5, spg_dt_cmp.procede_doc,spg_dt_cmp.codemp,spg_dt_cmp.spg_cuenta,spg_cuentas.sc_cuenta, 
						 codfuefin, spg_dt_cmp.estcla ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_compromiso_soc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_compromiso_soc
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_compromiso_soc($as_numordcom,$as_estcondat)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_compromiso_soc
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numordcom  // Número de Orden de Compra
		//	    		   as_estcondat  // estatus si la Orden de compra es de bienes ó de servicios
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una Orden de Compra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT soc_cuentagasto.numordcom AS comprobante, soc_cuentagasto.codestpro1, soc_cuentagasto.codestpro2, ".
				"		soc_cuentagasto.codestpro3, soc_cuentagasto.codestpro4, soc_cuentagasto.codestpro5,spg_ep1.estint,spg_ep1.sc_cuenta AS cuentaint,".
				"		soc_cuentagasto.spg_cuenta, soc_cuentagasto.monto, spg_cuentas.sc_cuenta, '--' AS codfuefin, soc_cuentagasto.estcla, ".
				"		(SELECT COUNT(codemp) FROM soc_solicitudcargos ".
				"		  WHERE soc_solicitudcargos.codemp = soc_cuentagasto.codemp  ".
				"			AND soc_solicitudcargos.numordcom = soc_cuentagasto.numordcom  ".
				"			AND soc_solicitudcargos.estcondat = soc_cuentagasto.estcondat  ".
				"			AND soc_solicitudcargos.codestpro1 = soc_cuentagasto.codestpro1 ".
				"		    AND soc_solicitudcargos.codestpro2 = soc_cuentagasto.codestpro2 ".
				"		    AND soc_solicitudcargos.codestpro3 = soc_cuentagasto.codestpro3 ".
				"		    AND soc_solicitudcargos.codestpro4 = soc_cuentagasto.codestpro4 ".
				"		    AND soc_solicitudcargos.codestpro5 = soc_cuentagasto.codestpro5 ".
				"		    AND soc_solicitudcargos.estcla = soc_cuentagasto.estcla ".
				"			AND soc_solicitudcargos.spg_cuenta = spg_cuentas.spg_cuenta ) AS cargo, soc_ordencompra.tipbieordcom  ".
				"  FROM soc_cuentagasto, spg_cuentas,soc_ordencompra,spg_ep1 ".
				" WHERE soc_cuentagasto.codemp = '".$this->ls_codemp."' ".
				"   AND soc_cuentagasto.numordcom = '".$as_numordcom."' ".
				"   AND soc_cuentagasto.estcondat = '".$as_estcondat."' ".
				"   AND soc_cuentagasto.codemp = spg_cuentas.codemp ".
				"   AND soc_cuentagasto.spg_cuenta = spg_cuentas.spg_cuenta ".
				"	AND soc_cuentagasto.codestpro1 = spg_cuentas.codestpro1 ".
				"	AND soc_cuentagasto.codestpro2 = spg_cuentas.codestpro2 ".
				"	AND soc_cuentagasto.codestpro3 = spg_cuentas.codestpro3 ".
				"	AND soc_cuentagasto.codestpro4 = spg_cuentas.codestpro4 ".
				"	AND soc_cuentagasto.codestpro5 = spg_cuentas.codestpro5 ".
				"	AND soc_cuentagasto.estcla = spg_cuentas.estcla ".
				"   AND soc_cuentagasto.codemp=soc_ordencompra.codemp".
				"   AND soc_cuentagasto.numordcom=soc_ordencompra.numordcom".
				"   AND soc_cuentagasto.estcondat=soc_ordencompra.estcondat".
				"   AND soc_cuentagasto.codemp=spg_ep1.codemp".
				"   AND soc_cuentagasto.codestpro1=spg_ep1.codestpro1".
				"   AND soc_cuentagasto.estcla=spg_ep1.estcla ".
				"   AND soc_cuentagasto.codestpro1||soc_cuentagasto.codestpro2||soc_cuentagasto.codestpro3||soc_cuentagasto.codestpro4||soc_cuentagasto.codestpro5||soc_cuentagasto.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							   							   UNION ".							
				"							  							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_compromiso_soc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_compromiso_soc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromiso_soc($as_numordcom,$as_estcondat,&$ai_total_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromiso_soc
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numordcom  // Número de la Orden ded Compra
		//				   as_estcondat  // Estatus de la Orden de Compra si es de Bienes ó de Servicios
		//				   ai_total_cargos  // Suma de los cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una Orden de Compra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ai_total_cargos=0;
		$ls_sql="SELECT soc_solicitudcargos.codcar, soc_solicitudcargos.numordcom, soc_solicitudcargos.codestpro1, ".
				"		soc_solicitudcargos.codestpro2, soc_solicitudcargos.codestpro3, soc_solicitudcargos.codestpro4, ".
				"		soc_solicitudcargos.codestpro5, soc_solicitudcargos.spg_cuenta, soc_solicitudcargos.sc_cuenta, ".
				"		soc_solicitudcargos.formula, soc_solicitudcargos.monobjret, soc_solicitudcargos.monret, ".
				"		sigesp_cargos.porcar, soc_solicitudcargos.estcla ".
				"  FROM soc_solicitudcargos, sigesp_cargos ".
				" WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."' ".
				"   AND soc_solicitudcargos.numordcom='".$as_numordcom."' ".
				"   AND soc_solicitudcargos.estcondat='".$as_estcondat."' ".
				"   AND soc_solicitudcargos.codcar=sigesp_cargos.codcar ".
				"   AND soc_solicitudcargos.codestpro1||soc_solicitudcargos.codestpro2||soc_solicitudcargos.codestpro3||soc_solicitudcargos.codestpro4||soc_solicitudcargos.codestpro5||soc_solicitudcargos.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
				"							   							   UNION ".							
				"							  							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromiso_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_existe=false;
			if(array_key_exists("cargos",$_SESSION))
			{
				$this->io_ds_cargos->data=$_SESSION["cargos"];
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$lb_existe=true;
				$ls_codcar=$row["codcar"];
  				$ls_nrocomp=$row["numordcom"];
				$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_cuenta=$row["spg_cuenta"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_formula=$row["formula"];
				$ls_porcar=$row["porcar"];
				$li_monto_anterior=0;
				switch($as_estcondat)
				{
					case "B":
						$ls_procede="SOCCOC";
						break;
					case "S":
						$ls_procede="SOCCOS";
						break;
				}
				$ls_cargo="1";
				$li_original=$row["monret"];
				$li_baseimp=$row["monobjret"];
//				$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$ls_procede,$ls_cuenta,$ls_codpro,&$li_monto_anterior);
				$lb_valido=$this->uf_load_cargo_causado_anterior($ls_codcar,$ls_nrocomp,$ls_procede,$row["codestpro1"],
															     $row["codestpro2"],$row["codestpro3"],$row["codestpro4"],
																 $row["codestpro5"],$ls_estcla,$ls_cuenta,&$li_monto_anterior);
				$li_monimp=$row["monret"]-$li_monto_anterior;
				$ls_codfuefin="--";
				if($lb_valido)
				{
					$ai_total_cargos=$ai_total_cargos+$li_monimp;
					$this->io_ds_cargos->insertRow("codcar",$ls_codcar);			
					$this->io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
					$this->io_ds_cargos->insertRow("baseimp",$li_baseimp);			
					$this->io_ds_cargos->insertRow("monimp",$li_monimp);			
					$this->io_ds_cargos->insertRow("codpro",$ls_codpro);			
					$this->io_ds_cargos->insertRow("estcla",$ls_estcla);			
					$this->io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
					$this->io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
					$this->io_ds_cargos->insertRow("cargo",$ls_cargo);			
					$this->io_ds_cargos->insertRow("original",$li_original);			
					$this->io_ds_cargos->insertRow("formula",$ls_formula);			
					$this->io_ds_cargos->insertRow("porcar",$ls_porcar);			
					$this->io_ds_cargos->insertRow("procededoc",$ls_procede);			
					$this->io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);			
				}
			}
			$this->io_sql->free_result($rs_data);
			if(($lb_existe)&&($lb_valido))
			{
				$_SESSION["cargos"]=$this->io_ds_cargos->data;
			}
		}		
		return $lb_valido;
	}// end function uf_load_cargos_compromiso_soc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargoscontable_compromiso_soc($as_numordcom,$as_estcondat,&$ai_total_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargoscontable_compromiso_soc
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numordcom  // Número de la Orden ded Compra
		//				   as_estcondat  // Estatus de la Orden de Compra si es de Bienes ó de Servicios
		//				   ai_total_cargos  // Suma de los cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una Orden de Compra
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 15/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ai_total_cargos=0;
		if($as_estcondat=="B")
		{
			$ls_sql="SELECT soc_dta_cargos.codcar, soc_dta_cargos.numordcom,".
					" 		sigesp_cargos.spg_cuenta AS sc_cuenta, sigesp_cargos.spg_cuenta AS spg_cuenta, ".
					"		soc_dta_cargos.formula, soc_dta_cargos.monbasimp AS monobjret, soc_dta_cargos.monimp AS monret, ".
					"		sigesp_cargos.porcar, 'A' AS estcla ".
					"  FROM soc_dta_cargos, sigesp_cargos ".
					" WHERE soc_dta_cargos.codemp='".$this->ls_codemp."' ".
					"   AND soc_dta_cargos.numordcom='".$as_numordcom."' ".
					"   AND soc_dta_cargos.estcondat='".$as_estcondat."' ".
					"   AND soc_dta_cargos.codcar=sigesp_cargos.codcar "; 
		}
		else
		{
			$ls_sql="SELECT soc_dts_cargos.codcar, soc_dts_cargos.numordcom,".
					" 		sigesp_cargos.spg_cuenta AS sc_cuenta, sigesp_cargos.spg_cuenta AS spg_cuenta, ".
					"		soc_dts_cargos.formula, soc_dts_cargos.monbasimp AS monobjret, soc_dts_cargos.monimp AS monret, ".
					"		sigesp_cargos.porcar, 'A' AS estcla ".
					"  FROM soc_dts_cargos, sigesp_cargos ".
					" WHERE soc_dts_cargos.codemp='".$this->ls_codemp."' ".
					"   AND soc_dts_cargos.numordcom='".$as_numordcom."' ".
					"   AND soc_dts_cargos.estcondat='".$as_estcondat."' ".
					"   AND soc_dts_cargos.codcar=sigesp_cargos.codcar "; 
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{ 
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargoscontable_compromiso_soc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_existe=false;
			if(array_key_exists("cargos",$_SESSION))
			{
				$this->io_ds_cargos->data=$_SESSION["cargos"];
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$lb_existe=true;
				$ls_codcar=$row["codcar"];
  				$ls_nrocomp=$row["numordcom"];
				$ls_codpro="-----------------------------------------------------------------------------------------------------------------------------";
				$ls_estcla=$row["estcla"];
				$ls_cuenta=$row["spg_cuenta"];
				$ls_sccuenta=$row["sc_cuenta"];
				$ls_formula=$row["formula"];
				$ls_porcar=$row["porcar"];
				$li_monto_anterior=0;
				switch($as_estcondat)
				{
					case "B":
						$ls_procede="SOCCOC";
						break;
					case "S":
						$ls_procede="SOCCOS";
						break;
				}
				$ls_cargo="1";
				$li_original=$row["monret"];
				$li_baseimp=$row["monobjret"];
//				$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$ls_procede,$ls_cuenta,$ls_codpro,&$li_monto_anterior);
				$lb_valido=$this->uf_load_cargo_causado_anterior($ls_codcar,$ls_nrocomp,$ls_procede,"-------------------------",
															     "-------------------------","-------------------------","-------------------------",
																 "-------------------------",$ls_estcla,$ls_cuenta,&$li_monto_anterior);
				$li_monimp=$row["monret"]-$li_monto_anterior;
				$ls_codfuefin="--";
				if($lb_valido)
				{
					$ai_total_cargos=$ai_total_cargos+$li_monimp;
					$this->io_ds_cargos->insertRow("codcar",$ls_codcar);			
					$this->io_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
					$this->io_ds_cargos->insertRow("baseimp",$li_baseimp);			
					$this->io_ds_cargos->insertRow("monimp",$li_monimp);			
					$this->io_ds_cargos->insertRow("codpro",$ls_codpro);			
					$this->io_ds_cargos->insertRow("estcla",$ls_estcla);			
					$this->io_ds_cargos->insertRow("cuenta",$ls_cuenta);			
					$this->io_ds_cargos->insertRow("sccuenta",$ls_sccuenta);			
					$this->io_ds_cargos->insertRow("cargo",$ls_cargo);			
					$this->io_ds_cargos->insertRow("original",$li_original);			
					$this->io_ds_cargos->insertRow("formula",$ls_formula);			
					$this->io_ds_cargos->insertRow("porcar",$ls_porcar);			
					$this->io_ds_cargos->insertRow("procededoc",$ls_procede);			
					$this->io_ds_cargos->insertRow("codfuefin",$ls_codfuefin);			
				}
			}
			$this->io_sql->free_result($rs_data);
			if(($lb_existe)&&($lb_valido))
			{
				$_SESSION["cargos"]=$this->io_ds_cargos->data;
			}
		}		
		return $lb_valido;
	}// end function uf_load_cargos_compromiso_soc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_compromiso_sno($as_comprobante)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_compromiso_sno
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_comprobante  // Número de Comprobante
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a un compromiso que viene de Mómina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/05/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sigesp_cmp.comprobante, spg_dt_cmp.codestpro1, spg_dt_cmp.codestpro2, ".
				"		spg_dt_cmp.codestpro3, spg_dt_cmp.codestpro4, spg_dt_cmp.codestpro5, ".
				"		spg_dt_cmp.spg_cuenta, spg_dt_cmp.monto, spg_cuentas.sc_cuenta, '0' AS cargo, '--' AS codfuefin, spg_dt_cmp.estcla, ".
				"       '' AS tipbieordcom, '' AS estint, '' AS cuentaint".
			  	"  FROM spg_dt_cmp, sigesp_cmp, spg_cuentas  ".
			  	" WHERE sigesp_cmp.codemp='".$this->ls_codemp."' ".
				"	AND sigesp_cmp.comprobante='".$as_comprobante."' ".
				"   AND sigesp_cmp.procede='SNOCNO' ".
			  	"   AND sigesp_cmp.codemp=spg_dt_cmp.codemp ".
				"	AND sigesp_cmp.comprobante=spg_dt_cmp.comprobante ".
				"   AND sigesp_cmp.procede=spg_dt_cmp.procede ".
				"   AND sigesp_cmp.fecha=spg_dt_cmp.fecha ".
				"   AND sigesp_cmp.codban=spg_dt_cmp.codban ".
				"   AND sigesp_cmp.ctaban=spg_dt_cmp.ctaban ".
				"   AND spg_dt_cmp.codemp = spg_cuentas.codemp".
				"   AND spg_dt_cmp.spg_cuenta = spg_cuentas.spg_cuenta ".
				"	AND spg_dt_cmp.codestpro1 = spg_cuentas.codestpro1 ".
				"	AND spg_dt_cmp.codestpro2 = spg_cuentas.codestpro2 ".
				"	AND spg_dt_cmp.codestpro3 = spg_cuentas.codestpro3 ".
				"	AND spg_dt_cmp.codestpro4 = spg_cuentas.codestpro4 ".
				"	AND spg_dt_cmp.codestpro5 = spg_cuentas.codestpro5 ".
				"	AND spg_dt_cmp.estcla = spg_cuentas.estcla ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_compromiso_sno ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_compromisos->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_compromiso_sno
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromiso($as_numero,$as_procede,&$ao_ds_cargos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromiso
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numero  // Número de comprobante
		//				   as_procede  // procede del documento
		//				   ao_ds_cargos  // Datastored de Cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/08/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ls_sql="";
		switch($as_procede)
		{
			case "SOCCOC": // Orden de Compra de Bienes
				$ls_sql="SELECT soc_solicitudcargos.codcar, (soc_solicitudcargos.numordcom) AS compromiso, soc_solicitudcargos.codestpro1, ".
						"		soc_solicitudcargos.codestpro2, soc_solicitudcargos.codestpro3, soc_solicitudcargos.codestpro4, ".
						"		soc_solicitudcargos.codestpro5, soc_solicitudcargos.spg_cuenta, soc_solicitudcargos.sc_cuenta, ".
						"		soc_solicitudcargos.formula, soc_solicitudcargos.monobjret, soc_solicitudcargos.monret, sigesp_cargos.porcar, ".
						"		soc_solicitudcargos.estcla ".
						"  FROM soc_solicitudcargos, sigesp_cargos ".
						" WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND soc_solicitudcargos.numordcom='".$as_numero."' ".
						"   AND soc_solicitudcargos.estcondat='B' ".
						"   AND soc_solicitudcargos.codcar=sigesp_cargos.codcar ".
						"   AND soc_solicitudcargos.codestpro1||soc_solicitudcargos.codestpro2||soc_solicitudcargos.codestpro3||soc_solicitudcargos.codestpro4||soc_solicitudcargos.codestpro5||soc_solicitudcargos.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
						"							   							   UNION ".							
						"							  							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; 
				break;
			
			case "SOCCOS": // Orden de Compra de Servicios
				$ls_sql="SELECT soc_solicitudcargos.codcar, (soc_solicitudcargos.numordcom) AS compromiso, soc_solicitudcargos.codestpro1, ".
						"		soc_solicitudcargos.codestpro2, soc_solicitudcargos.codestpro3, soc_solicitudcargos.codestpro4, ".
						"		soc_solicitudcargos.codestpro5, soc_solicitudcargos.spg_cuenta, soc_solicitudcargos.sc_cuenta, ".
						"		soc_solicitudcargos.formula, soc_solicitudcargos.monobjret, soc_solicitudcargos.monret, sigesp_cargos.porcar, ".
						"		soc_solicitudcargos.estcla ".
						"  FROM soc_solicitudcargos, sigesp_cargos ".
						" WHERE soc_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND soc_solicitudcargos.numordcom='".$as_numero."' ".
						"   AND soc_solicitudcargos.estcondat='S' ".
						"   AND soc_solicitudcargos.codcar=sigesp_cargos.codcar".
						"   AND soc_solicitudcargos.codestpro1||soc_solicitudcargos.codestpro2||soc_solicitudcargos.codestpro3||soc_solicitudcargos.codestpro4||soc_solicitudcargos.codestpro5||soc_solicitudcargos.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
						"							   							   UNION ".							
						"							  							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; 
				break;

			case "SEPSPC": // Solicitud de Ejecución Presupuestaria
				$ls_sql="SELECT sep_solicitudcargos.codcar, (sep_solicitudcargos.numsol) AS compromiso, sep_solicitudcargos.codestpro1, ".
						"		sep_solicitudcargos.codestpro2, sep_solicitudcargos.codestpro3, sep_solicitudcargos.codestpro4, ".
						"		sep_solicitudcargos.codestpro5, sep_solicitudcargos.spg_cuenta, sep_solicitudcargos.sc_cuenta, ".
						"		sep_solicitudcargos.formula, sep_solicitudcargos.monobjret, sep_solicitudcargos.monret, sigesp_cargos.porcar, ".
						"		sep_solicitudcargos.estcla ".
						"  FROM sep_solicitudcargos, sigesp_cargos ".
						" WHERE sep_solicitudcargos.codemp='".$this->ls_codemp."' ".
						"   AND sep_solicitudcargos.numsol='".$as_numero."' ".
						"   AND sep_solicitudcargos.codcar=sigesp_cargos.codcar".
						"   AND sep_solicitudcargos.codestpro1||sep_solicitudcargos.codestpro2||sep_solicitudcargos.codestpro3||sep_solicitudcargos.codestpro4||sep_solicitudcargos.codestpro5||sep_solicitudcargos.estcla IN (SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' ".
						"							   							   UNION ".							
						"							  							  SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)"; 						 
				break;
		}
		if($ls_sql!="")
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if ($rs_data===false)
			{
				$lb_valido=false; 
				$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$lb_existe=true;
					$ls_codcar=$row["codcar"];
					$ls_nrocomp=$row["compromiso"];
					$ls_codpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
					$ls_estcla=$row["estcla"];
					$ls_cuenta=$row["spg_cuenta"];
					$li_baseimp=$row["monobjret"];
					$li_monto_anterior=0;
					$lb_valido=$this->uf_load_monto_causado_anterior($ls_nrocomp,$as_procede,$ls_cuenta,$ls_codpro,$ls_estcla,&$li_monto_anterior);
					$ls_codfuefin="--";
					$li_monimp=$row["monret"]-$li_monto_anterior;
					if($lb_valido)
					{
						$ao_ds_cargos->insertRow("codcar",$ls_codcar);			
						$ao_ds_cargos->insertRow("nrocomp",$ls_nrocomp);			
						$ao_ds_cargos->insertRow("codpro",$ls_codpro);			
						$ao_ds_cargos->insertRow("estcla",$ls_estcla);			
						$ao_ds_cargos->insertRow("cuenta",$ls_cuenta);	
						$ao_ds_cargos->insertRow("procededoc",$as_procede);	
						$ao_ds_cargos->insertRow("baseimp",$li_baseimp);	
						$ao_ds_cargos->insertRow("monimp",$li_monimp);	
						$ao_ds_cargos->insertRow("codfuefin",$ls_codfuefin);	
					}		
				}
				$this->io_sql->free_result($rs_data);
			}		
		}
		return $lb_valido;
	}// end function uf_load_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos_compromisocontable($as_numero,$as_procede,&$li_monimp)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos_compromisocontable
		//		   Access: public (sigesp_cxp_c_catalogo_ajax.php)
		//	    Arguments: as_numero  // Número de comprobante
		//				   as_procede  // procede del documento
		//				   ao_ds_cargos  // Datastored de Cargos
		//	      Returns: lb_valido True si se ejecuto el select
		//	  Description: Función que se encarga de buscar las cuentas presupuestarias asociadas a una solicitud de ejecución
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/08/2007 								Fecha Última Modificación : 27/07/2010 Ing. Nelson Barraez , se agrego filtro para estructuras presupuestarias asignadas
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ls_sql="";
		switch($as_procede)
		{
			case "SOCCOC": // Orden de Compra de Bienes
				$ls_sql="SELECT COALESCE(SUM(soc_dta_cargos.monimp),0) AS monimp".
						"  FROM soc_dta_cargos".
						" WHERE soc_dta_cargos.codemp='".$this->ls_codemp."' ".
						"   AND soc_dta_cargos.numordcom='".$as_numero."' ".
						"   AND soc_dta_cargos.estcondat='B' ".
						" GROUP BY soc_dta_cargos.numordcom"; 
				break;
			
			case "SOCCOS": // Orden de Compra de Servicios
				$ls_sql="SELECT COALESCE(SUM(soc_dts_cargos.monimp),0) AS monimp".
						"  FROM soc_dts_cargos".
						" WHERE soc_dts_cargos.codemp='".$this->ls_codemp."' ".
						"   AND soc_dts_cargos.numordcom='".$as_numero."' ".
						"   AND soc_dts_cargos.estcondat='S' ".
						" GROUP BY soc_dts_cargos.numordcom"; 
				break;

			case "SEPSPC": // Solicitud de Ejecución Presupuestaria
				$ls_sql="SELECT COALESCE(SUM(sep_dtc_cargos.monimp),0) AS monimp".
						"  FROM sep_dtc_cargos".
						" WHERE sep_dtc_cargos.codemp='".$this->ls_codemp."' ".
						"   AND sep_dtc_cargos.numsol='".$as_numero."' ".
						" GROUP BY sep_dtc_cargos.numsol"; 
				break;
		}
		if($ls_sql!="")
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if ($rs_data===false)
			{
				$lb_valido=false; 
				$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_load_cargos_compromisocontable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$lb_existe=true;
					$li_monimp=$row["monimp"];
				}
				$this->io_sql->free_result($rs_data);
			}		
		}
		return $lb_valido;
	}// end function uf_load_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tipodocumento($as_codtipdoc,&$as_tipodocanti)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tipodocumento
		//		   Access: public
		//		 Argument: $as_codtipdoc // Codigo de tipo de documento
		//	  Description: Función que busca en la tabla de tipo de documento los tipos de Recepciones
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT dentipdoc, estcon, estpre,tipodocanti ".
				"  FROM cxp_documento ".
				" WHERE codtipdoc='".$as_codtipdoc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_select_tipodocumento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_tipodocanti=$row["tipodocanti"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_tipodocumento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_anticipo($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$li_moncue,$as_cuentaanticipo,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_anticipo
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepcion
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//				   as_cuentaanticipo  // Cuenta Contable del anticipo
		//	  Description: Función que inserta en la tabla de anticipos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codamo=$this->io_keygen->uf_generar_numero_nuevo("CXP","cxp_rd_amortizacion","codamo","CXPRCD",5,"","","");
		$ls_sql="INSERT INTO cxp_rd_amortizacion (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codamo, cuenta, monamo, monsal, montotamo)".
				" VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."','".$as_codpro."',".
				"'".$ls_codamo."','".$as_cuentaanticipo."',0,".$li_moncue.",".$li_moncue.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_anticipo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el anticipo de la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
							" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_amortizaciones($as_codpro,$as_cedbene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_amortizaciones
		//		   Access: public 
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de verificar si hay solicitudes de pago asociadas a la Recepción de Documento.
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT numrecdoc,codtipdoc,monsal,montotamo,cuenta,codamo ".
				"  FROM cxp_rd_amortizacion ".
		  		" WHERE cod_pro = '".$as_codpro."' ".
				"   AND trim(ced_bene) = '".trim($as_cedbene)."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_select_amortizaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_ds_anticipos->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;
	}// end function uf_select_solicitudes_pago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_amortizacion($as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_amortizacion
		//		   Access: private
		//	    Arguments: as_codpro  // Codigo de Proveedor 
		//				   as_cedbene  // Cedula de Beneficiario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza el saldo de la amortizacion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_amortizacion=$_SESSION["amortizacion"];
		$li_totamortizacion=count($la_amortizacion["recdocant"]);
		$lb_valido=true;
		for($li_i=1;$li_i<=$li_totamortizacion;$li_i++)
		{
			$ls_numrecdoc=$la_amortizacion["recdocant"][$li_i];
			$ls_codtipdoc=$la_amortizacion["codtipdoc"][$li_i];
			$ls_codamo=$la_amortizacion["codamo"][$li_i];
			$li_monto=$la_amortizacion["monto"][$li_i];
			$lb_valido=$this->uf_insert_amortizacion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ls_codamo,$li_monto,$aa_seguridad);
			if($lb_valido)
			{
				$ls_sql="UPDATE cxp_rd_amortizacion ".
						"   SET monamo='".$li_monto."', ".
						"		monsal=(monsal-".$li_monto.") ".
						" WHERE codemp='".$this->ls_codemp."' ".
						"	AND trim(numrecdoc)='".trim($ls_numrecdoc)."' ".
						"	AND codtipdoc='".$ls_codtipdoc."' ".
						"	AND cod_pro='".$as_codpro."' ".
						"	AND trim(ced_bene) = '".trim($as_cedbene)."'".
						"   AND codamo='".$ls_codamo."' ";		  
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					return false;
					$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_update_amortizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Insertó una Amortizacion para el anticipo de la Recepción de Documentos ".$ls_numrecdoc." Tipo ".$ls_codtipdoc." Beneficiario ".$as_cedbene.
									 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}// end function uf_update_amortizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_amortizacion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codamo,$ai_monto,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_amortizacion
		//		   Access: public
		//	    Arguments: as_numrecdoc  // Número de Recepcion
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//				   ai_monto  // Monto de los ajustes
		//				   as_cuentaanticipo  // Cuenta Contable del anticipo
		//	  Description: Función que inserta en la tabla de anticipos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO cxp_dt_amortizacion (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codamo, monto)".
				" VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."','".$as_codpro."',".
				"'".$as_codamo."',".$ai_monto.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_insert_amortizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		/*	$ls_evento="INSERT";
			$ls_descripcion="Insertó el anticipo de la Recepción ".$as_numrecdoc." Tipo ".$as_codtipdoc.
							" Beneficiario ".$as_cedbene."Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);*/
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reverso_anticipos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reverso_anticipos
		//		   Access: public 
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de verificar si hay solicitudes de pago asociadas a la Recepción de Documento.
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_select_amortizacion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,&$aa_codamo,&$aa_monto);
		if(($lb_valido)&&(is_array($aa_codamo)))
		{
			$li_total=count($aa_codamo);
			for($li_i=1;$li_i<=$li_total;$li_i++)
			{
				$ls_codamo=$aa_codamo[$li_i];
				$li_monto=$aa_monto[$li_i];
				$lb_valido=$this->uf_reversar_amortizacion($ls_codamo,$li_monto,$aa_seguridad);
				if(!$lb_valido)
				{return false;}
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_amortizacion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad);
			}
		}
		return $lb_valido;
	}// end function uf_select_solicitudes_pago
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_amortizacion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,&$aa_codamo,&$aa_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_amortizacion
		//		   Access: public 
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_codpro  // Código de proveedor
		//				   as_cedbene  // Código de beneficiario
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de verificar si hay solicitudes de pago asociadas a la Recepción de Documento.
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$aa_codamo="";
		$aa_monto="";
		$ls_sql="SELECT codamo,monto ".
				"  FROM cxp_dt_amortizacion ".
		  		" WHERE codemp='".$this->ls_codemp."'".
				"   AND trim(numrecdoc)= '".trim($as_numrecdoc)."' ".
				"   AND codtipdoc= '".$as_codtipdoc."' ".
				"   AND cod_pro= '".$as_codpro."' ".
				"   AND trim(ced_bene) = '".trim($as_cedbene)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_select_amortizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i+1;
				$aa_codamo[$li_i]=$row["codamo"];
				$aa_monto[$li_i]=$row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;
	}// end function uf_select_amortizacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_amortizacion($as_codamo,$ai_monto,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_amortizacion
		//		   Access: private
		//	    Arguments: as_codpro  // Codigo de Proveedor 
		//				   as_cedbene  // Cedula de Beneficiario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza el saldo de la amortizacion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE cxp_rd_amortizacion ".
				"   SET monsal=(monsal+".$ai_monto.") ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND codamo='".$as_codamo."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			return false;
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_reversar_amortizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_reversar_amortizacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_amortizacion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detallesrecepcion
		//		   Access: private
		//	    Arguments: as_numrecdoc  // Número de recepción de documentos
		//				   as_codtipdoc  // Tipo de Documento
		//				   as_cedbene  // Cédula del Beneficiario
		//				   as_codpro  // Código de proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina los detalles de una recepcion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_dt_amortizacion ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND trim(numrecdoc) = '".trim($as_numrecdoc)."' ".
				"	AND codtipdoc='".$as_codtipdoc."' ".
				"	AND cod_pro='".$as_codpro."' ".
				"	AND ced_bene='".$as_cedbene."'";		  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Recepcion MÉTODO->uf_delete_detallesrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó la Amortización relacionada a la Recepción de Documentos ".$as_numrecdoc." Tipo ".$as_codtipdoc." Beneficiario ".$as_cedbene.
							 "Proveedor ".$as_codpro." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_delete_detallesrecepcion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_bienes($as_comprobante,&$as_tipbieordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_bienes
		//		   Access: public 
		//	    Arguments: as_comprobante  // Número de orden de compra
		//				   ab_bienes       // Indica si la orden de compra es de activos fijos
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Función que se encarga de verificar si la orden de compra es para adquirir activos fijos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$aa_codamo="";
		$aa_monto="";
		$ls_sql="SELECT tipbieordcom ".
				"  FROM soc_ordencompra ".
		  		" WHERE codemp='".$this->ls_codemp."'".
				"   AND numordcom='".$as_comprobante."' ".
				"   AND estcondat='B' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false; 
			$this->io_mensajes->message("CLASE->Recepción MÉTODO->uf_validar_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_tipbieordcom=$row["tipbieordcom"];
				if($as_tipbieordcom!="A")
				{$as_tipbieordcom="-";}
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;
	}// end function uf_validar_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_monto_consumido($as_numordpagmin,$as_codtipfon)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_monto_consumido
		//		   Access: private
		//	    Arguments: 
		//	  Description: Método que imprime el resultado de la busqueda de las Ordenes de Pago Ministerio.
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 11/02/2009.								Fecha Última Modificación : 11/02/2009.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_totmoncon = 0;//Sumatoria de los Consumos de Movimientos asociados a la Orden de Pago Ministerio.
		$ld_moncon = 0;
		$ls_aux_where="";
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_aux_where=" AND CONCAT(cxp_rd.codemp,cxp_rd.numrecdoc,cxp_rd.codtipdoc,cxp_rd.cod_pro,cxp_rd.ced_bene) ".
							   "	 NOT IN (SELECT CONCAT(cxp_rd.codemp,cxp_rd.numrecdoc,cxp_rd.codtipdoc,cxp_rd.cod_pro,cxp_rd.ced_bene)".
							   "			   FROM cxp_rd,cxp_dt_solicitudes,cxp_sol_banco".
							   "			  WHERE cxp_rd.codemp=cxp_dt_solicitudes.codemp".
							   "				AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc".
							   "				AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
							   "				AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro".
							   "				AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene".
							   "				AND cxp_dt_solicitudes.codemp=cxp_sol_banco.codemp".
							   "				AND cxp_dt_solicitudes.numsol=cxp_sol_banco.numsol) ";
				break;
			case "POSTGRES":
				$ls_aux_where =" AND cxp_rd.codemp||cxp_rd.numrecdoc||cxp_rd.codtipdoc||cxp_rd.cod_pro||cxp_rd.ced_bene".
							   "	 NOT IN (SELECT (cxp_rd.codemp||cxp_rd.numrecdoc||cxp_rd.codtipdoc||cxp_rd.cod_pro||cxp_rd.ced_bene)".
							   "			   FROM cxp_rd,cxp_dt_solicitudes,cxp_sol_banco".
							   "			  WHERE cxp_rd.codemp=cxp_dt_solicitudes.codemp".
							   "				AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc".
							   "				AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc".
							   "				AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro".
							   "				AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene".
							   "				AND cxp_dt_solicitudes.codemp=cxp_sol_banco.codemp".
							   "				AND cxp_dt_solicitudes.numsol=cxp_sol_banco.numsol) ";
				break;
		}
		$ls_sql="SELECT COALESCE(SUM(monto),0) as moncon".
				"	 FROM scb_movbco ".
				"	WHERE numordpagmin<>'-' ".
				"	  AND numordpagmin<>''".
				"	  AND numordpagmin = '".$as_numordpagmin."'".
				"	  AND codtipfon = '".$as_codtipfon."'".
				"	  AND (codope='CH' OR codope='ND')".
				"	GROUP BY numordpagmin,codtipfon".
				"	UNION".
				"  SELECT COALESCE(SUM(montotdoc),0) as moncon".
				"     FROM cxp_rd".
				"	WHERE numordpagmin = '".$as_numordpagmin."'".
				"	  AND codtipfon='".$as_codtipfon."'".
				"	  $ls_aux_where ".
				"	GROUP BY numordpagmin";
		$rs_data=$this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
		if ($rs_data===false)
		   {
		     $this->io_mensajes->uf_mensajes_ajax("Class->Recepción;Metodo->uf_load_monto_consumido","ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message),false,""); 
		     print $this->io_sql->message;
		   }
		else
		   {
			 while(!$rs_data->EOF)
			      {
				    $ld_moncon = $rs_data->fields["moncon"];
				    $ld_totmoncon += $ld_moncon;
				    $rs_data->MoveNext();
				  }
			 $this->io_sql->free_result($rs_data);
		   }
	  return $ld_totmoncon;
	}// end function uf_load_monto_consumido
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ordenministerio($as_numordpagmin,$as_codtipfon,&$ai_monordpagmin,&$ad_porrepfon)
   	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ordenministerio
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de proveedores
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ai_monordpagmin=0;
		$ad_porrepfon=0;
		$ls_sql="SELECT scb_movbco.numordpagmin,scb_movbco.monto,scb_tipofondo.porrepfon".
				"  FROM scb_movbco,scb_tipofondo".
				" WHERE scb_movbco.codemp = '".$_SESSION["la_empresa"]["codemp"]."'".
				"   AND trim(scb_movbco.numordpagmin) <>''".
				"   AND trim(scb_movbco.numordpagmin) <>'-'".
				"	AND (scb_movbco.codope = 'DP' OR scb_movbco.codope = 'NC')".
				"	AND scb_movbco.codtipfon<>'----'".
				"   AND scb_movbco.numordpagmin='".$as_numordpagmin."'".
				"	AND scb_movbco.codtipfon='".$as_codtipfon."'".
				"   AND scb_movbco.codemp=scb_tipofondo.codemp".
				"	AND scb_movbco.codtipfon=scb_tipofondo.codtipfon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->uf_mensajes_ajax("CLASE->Recepción MÉTODO->uf_load_ordenministerio","ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message),false,""); 
		}
		else
		{
			$lb_valido=true;
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monordpagmin = $rs_data->fields["monto"];//Monto Total de la Orden de Pago Ministerio.
				$ad_porrepfon    = $rs_data->fields["porrepfon"];//Porcentaje de Reposición.
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_print_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_causado_anterior($ls_codemp,$ls_comprobante,$ls_procede,$ls_spgcuenta,$ls_codestpro)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ordenministerio
		//		   Access: private
		//	    Arguments: 
		//	  Description: Función que obtiene e imprime los resultados de la busqueda de proveedores
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ldec_monto=0;
		$ls_sql="SELECT a.codemp,a.procede_doc,a.numdoccom,a.spg_cuenta,a.codestpro,a.monto  as monto
				 FROM   cxp_rd_spg a, cxp_rd b
				 WHERE  a.codemp     ='".$ls_codemp."' 
				 AND    a.procede_doc='".$ls_procede."' 
				 AND    a.numdoccom  ='".$ls_comprobante."' 
				 AND    a.numrecdoc  =b.numrecdoc 
				 AND    b.estprodoc<>'A'
				 AND    a.spg_cuenta ='".$ls_spgcuenta."' 
				 AND    a.codestpro  ='".$ls_codestpro."'  
				 GROUP BY a.codemp, a.procede_doc, a.numdoccom, a.spg_cuenta, a.codestpro, a.monto "; 

	  $rs_data=$this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	  {
		   $lb_valido=false; 
		   $this->io_msg->message("CLASE->SIGESP_CLASS_CXP; METODO->uf_causado_anterior; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	  else
	  {
		   
		   $ldec_monto=0;
		   while($row=$this->io_sql->fetch_row($rs_data))
		   {
				$ld_montoc=$row["monto"];
				$ldec_monto=$ldec_monto+$ld_montoc;
		   }
	  }
	  return $ldec_monto;	
	}

}
?>