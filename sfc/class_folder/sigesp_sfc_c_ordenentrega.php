<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_ordenentrega
 // Autor:       - Ing. Nelson Barraez
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla siv_orden_entrega.
 // Fecha:       - 07/09/2010
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_ordenentrega
{
	 var $io_funcion;
	 var $io_msgc;
	 var $io_sql;
	 var $datoemp;
	 var $io_msg;
	function sigesp_sfc_c_ordenentrega()
	{
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("sigesp_sob_c_funciones_sob.php"); /** Se toma la funcion de convertir cadena a caracteres **/
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->funsob     = new sigesp_sob_c_funciones_sob();
		$this->seguridad  = new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		$io_include       = new sigesp_include();
		$io_connect       = $io_include->uf_conectar();
		$this->io_sql     = new class_sql($io_connect);
		$this->datoemp    = $_SESSION["la_empresa"];
		$this->io_msg     = new class_mensajes();
		$io_datastore     = new class_datastore();
	}
	
	function uf_select_orden($ls_numord,$ls_codtie)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_orden
		// Parameters:  - $ls_numord( Codigo de la orden de entrega).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT * FROM siv_orden_entrega
					WHERE codemp='".$ls_codemp."' AND numord='".$ls_numord."' AND codtiend='".$ls_codtie."'";
		$rs_datauni=$this->io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_orden ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
			$this->io_sql->free_result($rs_datauni);
		}
		return $lb_valido;
	}
	
	
	function uf_select_ubicacion($ls_codtie)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_orden
		// Parameters:  - $ls_numord( Codigo de la orden de entrega).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_ubicacion="";
		$ls_sql="SELECT sigesp_estados.desest,sigesp_municipio.denmun ". 
				"  FROM sfc_tienda,sigesp_estados,sigesp_municipio ".
				" WHERE sfc_tienda.codemp='".$ls_codemp."' AND sfc_tienda.codtiend='".$ls_codtie."' AND sfc_tienda.codpai=sigesp_estados.codpai AND sfc_tienda.codest=sigesp_estados.codest ".
				"   AND sfc_tienda.codpai=sigesp_municipio.codpai AND sfc_tienda.codest=sigesp_municipio.codest AND sfc_tienda.codmun=sigesp_municipio.codmun "	;
		$rs_datauni=$this->io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_ubicacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$ls_ubicacion=$row["desest"]." - ".$row["denmun"];
			}
			$this->io_sql->free_result($rs_datauni);
		}		
		return $ls_ubicacion;
	}
	
	function uf_existe_orden_entrega($as_codtie,$as_numordent)
	{
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido=false;
		$ls_sql=" SELECT codordent ". 
				"   FROM siv_orden_entrega ".
				"  WHERE codemp='".$ls_codemp."' ".
				"    AND codtiend='".$as_codtie."' ".
				"    AND codordent='".$as_numordent."'";
		$rs_datauni=$this->io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_existe_orden_entrega ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_datauni);
		}		
		return $lb_valido;
	}						  
									  
	function uf_guardar_orden_entrega($as_codtie,$as_codcaja,$as_numordent,$as_numconordent,$adt_fecemiord,$as_numordcom,$as_numorddes,$as_numguiasada,$as_codcli,
									 $as_codptocol,$as_percontptocol,$as_telcontptocol,$as_dirptocol,$as_observacion,$adt_fecdesordent,$as_codtransp,
									 $adec_descuentos,$as_pagotransp,$as_placaveh,$as_placabatea,$adt_fecdevins,$as_codusu,$as_codestordent,$as_codmotordent,$adec_monfle,
									 $adec_monexe,$adec_monexo,$adec_monbasimp,$adec_total)									 
	{
		if(!$this->uf_existe_orden_entrega($as_codtie,$as_numordent))
		{
			$lb_valido=$this->uf_insert_orden_entrega($as_codtie,$as_codcaja,$as_numordent,$as_numconordent,$adt_fecemiord,$as_numordcom,$as_numorddes,$as_numguiasada,$as_codcli,
													 $as_codptocol,$as_percontptocol,$as_telcontptocol,$as_dirptocol,$as_observacion,$adt_fecdesordent,$as_codtransp,
													 $adec_descuentos,$as_pagotransp,$as_placaveh,$as_placabatea,$adt_fecdevins,$as_codusu,$as_codestordent,$as_codmotordent,$adec_monfle,
													 $adec_monexe,$adec_monexo,$adec_monbasimp,$adec_total);
	 	}
		else
		{
			$lb_valido=$this->uf_update_orden_entrega($as_codtie,$as_codcaja,$as_numordent,$as_numconordent,$adt_fecemiord,$as_numordcom,$as_numorddes,$as_numguiasada,$as_codcli,
													 $as_codptocol,$as_percontptocol,$as_telcontptocol,$as_dirptocol,$as_observacion,$adt_fecdesordent,$as_codtransp,
													 $adec_descuentos,$as_pagotransp,$as_placaveh,$as_placabatea,$adt_fecdevins,$as_codusu,$as_codestordent,$as_codmotordent,$adec_monfle,
													 $adec_monexe,$adec_monexo,$adec_monbasimp,$adec_total,$as_codempleado);
		}
		return $lb_valido;
	}									 
	
	function uf_insert_orden_entrega($as_codtie,$as_codcaja,$as_numordent,$as_numconordent,$adt_fecemiord,$as_numordcom,$as_numorddes,$as_numguiasada,$as_codcli,
									 $as_codptocol,$as_percontptocol,$as_telcontptocol,$as_dirptocol,$as_observacion,$adt_fecdesordent,$as_codtransp,
									 $adec_descuentos,$as_pagotransp,$as_placaveh,$as_placabatea,$adt_fecdevins,$as_codusu,$as_codestordent,$as_codmotordent,$adec_monfle,
									 $adec_monexe,$adec_monexo,$adec_monbasimp,$adec_total)
	{
		$lb_valido=false;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$adt_fecemiord=$this->io_funcion->uf_convertirdatetobd($adt_fecemiord); 
		$adt_fecdesordent=$this->io_funcion->uf_convertirdatetobd($adt_fecdesordent);
		$adt_fecdevins=$this->io_funcion->uf_convertirdatetobd($adt_fecdevins);
		$adec_descuentos=str_replace(",",".",str_replace(".","",$adec_descuentos));
		$adec_monfle=str_replace(",",".",str_replace(".","",$adec_monfle));
		$adec_monexe=str_replace(",",".",str_replace(".","",$adec_monexe));
		$adec_monexo=str_replace(",",".",str_replace(".","",$adec_monexo));
		$adec_monbasimp=str_replace(",",".",str_replace(".","",$adec_monbasimp));
		$adec_total=str_replace(",",".",str_replace(".","",$adec_total));
		
		$ls_sql="INSERT INTO siv_orden_entrega(codemp,codordent,numconordent,cod_caja,codciecaj,codtiend,codcli,codptocoldes,numorddes, ".
				"							   nunordcom,numfac,numguisad,codestordent,codmotordent,fecemi,codusu,fechordespordent, ".
				"							   codempdesordent,fechorllegordent,fechordesgordent,emppagtransp,codveh,codempcho, ".
				"							   codconveh,fecdevins,numkilrec,mondesbonreb,monfle,monexe,monexo,monbasimp,montot, ".
				"							   obsordent,estatus) ".
				" VALUES('".$ls_codemp."','".$as_numordent."','".$as_numconordent."','".$as_codcaja."','','".$as_codtie."','".$as_codcli."','".$as_codptocol."','".$as_numorddes."', ".
				" 		 '".$as_numordcom."','','".$as_numguiasada."','".$as_codestordent."','".$as_codmotordent."','".$adt_fecemiord."','".$as_codusu."','".$adt_fecdesordent."', ".
				"        '".$as_codtransp."','1900-01-01','1900-01-01','".$as_pagotransp."','".$as_placaveh."','".$as_codempleado."', ".
				"        '".$as_codempleado."','".$adt_fecdevins."',0,'".$adec_descuentos."','".$adec_monfle."','".$adec_monexe."','".$adec_monexo."','".$adec_monbasimp."','".$adec_total."','".$as_observacion."','t') ";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows==false)
		{
			$this->io_msgc="Error en uf_insert_orden_compra ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
		}
		else
		{
			$lb_valido=true;
		}		
		return $lb_valido;				 		
	}
	
	function uf_update_orden_entrega($as_codtie,$as_codcaja,$as_numordent,$as_numconordent,$adt_fecemiord,$as_numordcom,$as_numorddes,$as_numguiasada,$as_codcli,
									 $as_codptocol,$as_percontptocol,$as_telcontptocol,$as_dirptocol,$as_observacion,$adt_fecdesordent,$as_codtransp,
									 $adec_descuentos,$as_pagotransp,$as_placaveh,$as_placabatea,$adt_fecdevins,$as_codusu,$as_codestordent,$as_codmotordent,$adec_monfle,
									 $adec_monexe,$adec_monexo,$adec_monbasimp,$adec_total,$as_codempleado)
	{
		$lb_valido=false;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sql="UPDATE siv_orden_entrega SET codciecaj='".$as_codcierre."',numfac='".$as_numfac."',codestordent='".$as_codestordent."', ".
				"							  codmotordent='".$as_codmotordent."',fechordespordent='".$adt_fecdesordent."', ".
				"							  codempdesordent='".$as_codempleado."',fechorllegordent,fechordesgordent,emppagtransp,codveh,codempcho, ".
				"							  codconveh,fecdevins,numkilrec,mondesbonreb,monfle,monexe,monexo,monbasimp,montot, ".
				"							  obsordent,estatus ".
				" VALUES('".$ls_codemp."','".$as_numordent."','".$as_numconordent."','".$as_codcaja."','','".$as_codtie."','".$as_codcli."','".$as_codptocol."','".$as_numorddes."', ".
				" 		 '".$as_numordcom."','','".$as_numguiasada."','".$as_codestordent."','".$as_codmotordent."','".$adt_fecemiord."','".$as_codusu."','".$adt_fecdesordent."', ".
				"        '".$as_codtransp."','1900-01-01','1900-01-01','".$as_pagotransp."','".$as_placaveh."','' ".
				"        '','".$adt_fecdevins."',0,'".$adec_descuentos."','".$adec_monfle."','".$as_observacion."','".$as_status."') ";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows==false)
		{
			$this->io_msgc="Error en uf_update_orden_compra ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
		}		
		return $lb_valido;				 		
	}
	
	
	function uf_insert_dt_ordenentrega($as_codordent,$as_codtienda,$as_tippro,$as_codpro,$as_codart,$adec_porimp,$adec_canprodes,$adec_prepro,$ai_numlotfab,
									   $adt_fecven,$as_codalm,$adec_cospro,$as_codcaudev,$adec_candevpro)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sql="INSERT INTO siv_det_ordenentrega(codemp,codordent,codtiend,tippro,codpro,codart,porimp,canprodes,prepro,numlotfab, ".
				"								  fecven,codalm,cospro,codcaudev,candevpro) ".
				" VALUES('".$ls_codemp."','".$as_codordent."','".$as_codtienda."','".$as_tippro."','".$as_codpro."','".$as_codart."','".$adec_porimp."','".$adec_canprodes."','".$adec_prepro."','".$ai_numlotfab."',".
				"		 '".$adt_fecven."','".$as_codalm."','".$adec_cospro."','".$as_codcaudev."','".$adec_candevpro."') ";
		$li_rows=$this->io_sql->execute($ls_sql);
	//	print $ls_sql;
		if($li_rows==false)
		{
			$this->io_msgc="Error en uf_insert_orden_compra ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
		}
		else
		{
			$lb_valido=true;
		}		
		return $lb_valido;		
	}
		
	function uf_eliminar_dt_orden_entrega($as_codordent,$as_codtienda,$as_tipo,$as_codart)
	{
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql=" DELETE FROM siv_det_ordenentrega ".
				"  WHERE codemp='".$ls_codemp."' ".
				"    AND codtiend='".$as_codtie."' ".
				"    AND codordent='".$as_numordent."' ".
				"    AND tippro='".$as_tipo."'";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_eliminar_dt_orden_entrega ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
		}		
		return $lb_valido;
	}

	function uf_cargar_dt_conceptos($ai_totalconceptos,$as_codordent,$as_codtie)
	{
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT DT.*,PR.denart FROM siv_det_ordenentrega DT
					WHERE codemp='".$ls_codemp."' AND codordent='".$as_codordent."' AND codtiend='".$as_codtie."'";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_cargar_dt_conceptos ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_codart=$row["codart"];
				$ls_denart=$row["denart"];
				$ls_desalm=$row["desalm"];
				$aa_objectconcepto[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." value='".$ls_codart."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
				$aa_objectconcepto[$li_i][2]="<input name=txtdenart".$li_i." type=text id=txtdenart".$li_i." value='".$ls_denart."' class=sin-borde size=45 style= text-align:left readonly>";
				$aa_objectconcepto[$li_i][3]="<input name=txtdesalm".$li_i." type=text id=txtdesalm".$li_i." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."' >";
				$aa_objectconcepto[$li_i][4]="<input name=txtnompro".$li_i." type=text id=txtnompro".$li_i." value='".$ls_nompro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_i." type=hidden id=txtcod_pro".$li_i." value='".$ls_cod_pro."' class=sin-borde size=10 style= text-align:right readonly>";
				$aa_objectconcepto[$li_i][5]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_i." type=hidden id=txtcosto".$li_i." class=sin-borde value='".$ls_costo."' style= text-align:center readonly>";
				$aa_objectconcepto[$li_i][6]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
				$aa_objectconcepto[$li_i][7]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_calcular_total_fila('$li_i');>";
				$aa_objectconcepto[$li_i][8]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";
				// Si la factura es nueva se habilita la opcion de eliminar en edicion
				if ($as_estfaccon=="")
				{
					$aa_objectconcepto[$li_i][9]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
				}
				else
				{
					$aa_objectconcepto[$li_i][9]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;		
	}


}/*FIN DE LA CLASE */
?>
