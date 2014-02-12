<?php
class sigesp_sno_class_report_historicobsf
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_class_report_historicobsf()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_class_report_historicobsf
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->DS_detalle=new class_datastore();
		$this->DS_detalle2=new class_datastore();
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
        $this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
        $this->ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$this->li_rac=$_SESSION["la_nomina"]["racnom"];
	}// end function sigesp_sno_class_report_historicobsf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_valor="";
		$ls_sql="SELECT value ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report Contable MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["value"];
				$li_i=$li_i+1;
			}
			if($li_i==0)
			{
				$lb_valido=$this->uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				if ($lb_valido)
				{
					$ls_valor=$this->uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return rtrim($ls_valor);
	}// end function uf_select_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="DELETE ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report Contable MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			switch ($as_tipo)
			{
				case "C"://Caracter
					$valor = $as_valor;
					break;

				case "D"://Double
					$as_valor=str_replace(".","",$as_valor);
					$as_valor=str_replace(",",".",$as_valor);
					$valor = $as_valor;
					break;

				case "B"://Boolean
					$valor = $as_valor;
					break;

				case "I"://Integer
					$valor = intval($as_valor);
					break;
			}
			$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type)VALUES ".
					"('".$this->ls_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$valor."','".$as_tipo."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Report Contable MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	}// end function uf_insert_config	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_prenomina_personal($as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_prenomina_personal
		//         Access: public (desde la clase sigesp_sno_rpp_prenomina)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_orden // Orde a mostrar en el reporte		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que se le calculó la prenómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= "AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper,sno_personal.nomper, sno_personal.apeper ".
				"  FROM sno_personal, sno_thpersonalnomina, sno_thprenomina, sno_thconcepto ".
				" WHERE sno_thprenomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thprenomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thprenomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thprenomina.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio." ".
				"   AND sno_thpersonalnomina.codemp = sno_thprenomina.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thprenomina.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thprenomina.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thprenomina.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thprenomina.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_personal.codemp ".
				"   AND sno_thpersonalnomina.codper = sno_personal.codper ".
				"   AND sno_thprenomina.codemp = sno_thconcepto.codemp ".
				"   AND sno_thprenomina.codnom = sno_thconcepto.codnom ".
				"   AND sno_thprenomina.anocur = sno_thconcepto.anocur ".
				"   AND sno_thprenomina.codperi = sno_thconcepto.codperi ".
				"   AND sno_thprenomina.codconc = sno_thconcepto.codconc ".
				" GROUP BY sno_personal.codper,sno_personal.nomper, sno_personal.apeper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_prenomina_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_prenomina_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_prenomina_conceptopersonal($as_codper,$as_conceptocero,$as_conceptop2)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_prenomina_conceptopersonal
		//         Access: public (desde la clase sigesp_sno_rpp_prenomina)  
		//	    Arguments: as_codper // Código de Personal
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos cuyo valor es cero
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados al personal que se le calculó la prenómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "AND sno_thprenomina.valprenom<>0 ";
		}
		if(empty($as_conceptop2))
		{
			$ls_criterio = $ls_criterio." AND (sno_thprenomina.tipprenom<>'P2' AND sno_thprenomina.tipprenom<>'V4' AND sno_thprenomina.tipprenom<>'W4')";
		}
		$ls_sql="SELECT sno_thprenomina.codconc, sno_thconcepto.nomcon, sno_thprenomina.tipprenom, (sno_thprenomina.valprenomaux) AS valprenom, ".
				"		(sno_thprenomina.valhisaux) AS valhis ".
				"  FROM sno_thprenomina, sno_thconcepto ".
				" WHERE sno_thprenomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thprenomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thprenomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thprenomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thprenomina.codper='".$as_codper."' ".
				"     ".$ls_criterio.
				"   AND sno_thprenomina.codemp = sno_thconcepto.codemp ".
				"   AND sno_thprenomina.codnom = sno_thconcepto.codnom ".
				"   AND sno_thprenomina.anocur = sno_thconcepto.anocur ".
				"   AND sno_thprenomina.codperi = sno_thconcepto.codperi ".
				"   AND sno_thprenomina.codconc = sno_thconcepto.codconc ".
				" ORDER BY sno_thprenomina.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_prenomina_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_prenomina_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonomina_personal($as_codperdes,$as_codperhas,$as_conceptocero,$as_conceptoreporte,$as_conceptop2,$as_codubifis,
									$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonomina_personal
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos cuyo valor es cero
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos tipo reporte
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/02/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_criteriounion="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
			$ls_criteriounion=" AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
			$ls_criteriounion = $ls_criteriounion."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_codubifis))
		{
			$ls_criterio= $ls_criterio." AND sno_thpersonalnomina.codubifis='".$as_codubifis."'";
			$ls_criteriounion = $ls_criteriounion." AND sno_thpersonalnomina.codubifis='".$as_codubifis."'";
		}
		else
		{
			if(!empty($as_codest))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codpai='".$as_codpai."'";
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codest='".$as_codest."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codpai='".$as_codpai."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codest='".$as_codest."'";
			}
			if(!empty($as_codmun))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
			}
			if(!empty($as_codpar))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
			}
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3')";
			}
		}
		if(empty($as_orden))
		{
			$ls_orden=" ORDER BY sno_personal.codper ";
		}
		else
		{
			switch($as_orden)
			{
				case "1": // Ordena por unidad administrativa
					$ls_orden=" ORDER BY minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, codper ";
					break;

				case "2": // Ordena por Código de personal
					$ls_orden=" ORDER BY sno_personal.codper ";
					break;

				case "3": // Ordena por Apellido de personal
					$ls_orden=" ORDER BY sno_personal.apeper ";
					break;

				case "4": // Ordena por Nombre de personal
					$ls_orden=" ORDER BY sno_personal.nomper ";
					break;
			}
		}
		if($this->li_rac=="1") // Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_thasignacioncargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp = sno_thasignacioncargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thasignacioncargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thasignacioncargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thasignacioncargo.codperi ".
				       "           AND sno_thpersonalnomina.codasicar = sno_thasignacioncargo.codasicar) as descar ";
		}
		else // No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_thcargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp = sno_thcargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thcargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thcargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thcargo.codperi ".
				       "           AND sno_thpersonalnomina.codcar = sno_thcargo.codcar) as descar ";
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
					  "		sno_thpersonalnomina.codcueban, sno_thunidadadmin.desuniadm, sno_thunidadadmin.codprouniadm, MAX(sno_thpersonalnomina.sueperaux) AS sueper, ".
					  "		sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, ".
					  "		MAX(sno_thpersonalnomina.codgra) AS codgra, MAX(sno_personal.nacper) AS nacper, MAX(sno_ubicacionfisica.desubifis) AS desubifis, ".
					  "		  (SELECT desest FROM sigesp_estados ".
					  "			WHERE sigesp_estados.codpai = sno_ubicacionfisica.codpai ".
					  "			 AND sigesp_estados.codest = sno_ubicacionfisica.codest) AS desest, ".
					  "		  (SELECT denmun FROM sigesp_municipio ".
					  "			WHERE sigesp_municipio.codpai = sno_ubicacionfisica.codpai ".
					  "			 AND sigesp_municipio.codest = sno_ubicacionfisica.codest ".
					  "			 AND sigesp_municipio.codmun = sno_ubicacionfisica.codmun) AS denmun, ".
					  "		  (SELECT denpar FROM sigesp_parroquia ".
					  "			WHERE sigesp_parroquia.codpai = sno_ubicacionfisica.codpai ".
					  "			 AND sigesp_parroquia.codest = sno_ubicacionfisica.codest ".
					  "			 AND sigesp_parroquia.codmun = sno_ubicacionfisica.codmun ".
					  "			 AND sigesp_parroquia.codpar = sno_ubicacionfisica.codpar) AS denpar, ".
					  "".$ls_descar.
					  "  FROM sno_personal, sno_thpersonalnomina, sno_thsalida, sno_thunidadadmin, sno_ubicacionfisica  ".
					  " WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
					  "   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
					  "   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
					  "   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
					  "   AND sno_thsalida.codconc='".$ls_vac_codconvac."' ".
					  "   ".$ls_criteriounion.
					  "   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					  "   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					  "   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					  "   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					  "   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					  "   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
					  "   AND sno_personal.codper = sno_thpersonalnomina.codper ".
					  "   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
					  "   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
					  "   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
					  "   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
					  "   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
					  "   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
					  "   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
					  "   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
					  "   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
					  "   AND sno_ubicacionfisica.codemp = sno_thpersonalnomina.codemp ".
					  "	  AND sno_ubicacionfisica.codubifis = sno_thpersonalnomina.codubifis ".
					  " GROUP BY sno_thpersonalnomina.codemp, sno_thpersonalnomina.codnom, sno_thpersonalnomina.anocur,sno_thpersonalnomina.codperi, ".
					  "		   sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
				  	  "		   sno_personal.fecingper, sno_thpersonalnomina.codcar, sno_thpersonalnomina.codasicar, ".
					  "		   sno_thpersonalnomina.codcueban, sno_thunidadadmin.desuniadm, sno_thunidadadmin.codprouniadm, ".
					  "        sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
					  "    	   sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, sno_ubicacionfisica.codpai, ".
					  "        sno_ubicacionfisica.codest,sno_ubicacionfisica.codmun,sno_ubicacionfisica.codpar  ";
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		sno_thpersonalnomina.codcueban, sno_thunidadadmin.desuniadm, sno_thunidadadmin.codprouniadm, MAX(sno_thpersonalnomina.sueperaux) AS sueper, ".
			    "		sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, ".
				"		MAX(sno_thpersonalnomina.codgra) AS codgra, MAX(sno_personal.nacper) AS nacper, MAX(sno_ubicacionfisica.desubifis) AS desubifis, ".
				"		  (SELECT desest FROM sigesp_estados ".
				"			WHERE sigesp_estados.codpai = sno_ubicacionfisica.codpai ".
				"			 AND sigesp_estados.codest = sno_ubicacionfisica.codest) AS desest, ".
				"		  (SELECT denmun FROM sigesp_municipio ".
				"			WHERE sigesp_municipio.codpai = sno_ubicacionfisica.codpai ".
				"			 AND sigesp_municipio.codest = sno_ubicacionfisica.codest ".
				"			 AND sigesp_municipio.codmun = sno_ubicacionfisica.codmun) AS denmun, ".
				"		  (SELECT denpar FROM sigesp_parroquia ".
				"			WHERE sigesp_parroquia.codpai = sno_ubicacionfisica.codpai ".
				"			 AND sigesp_parroquia.codest = sno_ubicacionfisica.codest ".
				"			 AND sigesp_parroquia.codmun = sno_ubicacionfisica.codmun ".
				"			 AND sigesp_parroquia.codpar = sno_ubicacionfisica.codpar) AS denpar, ".
				"  ".$ls_descar.
				"  FROM sno_personal, sno_thpersonalnomina, sno_thsalida, sno_thunidadadmin, sno_ubicacionfisica ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio." ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_thpersonalnomina.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
			    "   AND sno_ubicacionfisica.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_ubicacionfisica.codubifis = sno_thpersonalnomina.codubifis ".
				" GROUP BY sno_thpersonalnomina.codemp, sno_thpersonalnomina.codnom, sno_thpersonalnomina.anocur,sno_thpersonalnomina.codperi, ".
				"		   sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
				"		   sno_personal.fecingper, sno_thpersonalnomina.codcar, sno_thpersonalnomina.codasicar, ".
				"		   sno_thpersonalnomina.codcueban, sno_thunidadadmin.desuniadm, sno_thunidadadmin.codprouniadm, ".
				"          sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
				"    	   sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, sno_ubicacionfisica.codpai, ".
				"        sno_ubicacionfisica.codest,sno_ubicacionfisica.codmun,sno_ubicacionfisica.codpar  ".
				"   ".$ls_union.
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_pagonomina_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonomina_conceptopersonal($as_codper,$as_conceptocero,$as_tituloconcepto,$as_conceptoreporte,$as_conceptop2)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonomina_conceptopersonal
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: as_codper // Código del personal que se desea buscar la salida
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos en cero
		//	  			   as_tituloconcepto // criterio que me indica si se desea mostrar el título del concepto ó el nombre
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos tipo reporte
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados al personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_campo="sno_thconcepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_thconcepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3')";
			}
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".					  
					  "SELECT sno_thconcepto.codconc, ".$ls_campo." as nomcon, sno_thsalida.valsalaux AS valsal, sno_thsalida.tipsal ".
				      "  FROM sno_thsalida, sno_thconcepto, sno_thpersonalnomina ".
	 	 		      " WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				      "   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				      "   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				      "   AND sno_thsalida.codperi='".$this->ls_peractnom."'".
				      "   AND sno_thsalida.codper='".$as_codper."'".
				      "   AND sno_thsalida.codconc='".$ls_vac_codconvac."'".
				      "   AND sno_thpersonalnomina.staper = '2' ".
				      "   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				      "   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				      "   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				      "   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				      "   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					  "   AND sno_thsalida.codemp = sno_thpersonalnomina.codemp ".
					  "   AND sno_thsalida.codnom = sno_thpersonalnomina.codnom ".
					  "   AND sno_thsalida.codper = sno_thpersonalnomina.codper ";
		}
		$ls_sql="SELECT sno_thconcepto.codconc, ".$ls_campo." as nomcon, sno_thsalida.valsalaux AS valsal, sno_thsalida.tipsal ".
				"  FROM sno_thsalida, sno_thconcepto ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."'".
				"   AND sno_thsalida.codper='".$as_codper."'".
				"   ".$ls_criterio.
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   ".$ls_union.
				" ORDER BY codconc, tipsal ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_pagonomina_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonomina_concepto_excel($as_tituloconcepto,$as_sigcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonomina_concepto_excel
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: as_codper // Código del personal que se desea buscar la salida
		//	  			   as_tituloconcepto // criterio que me indica si se desea mostrar el título del concepto ó el nombre
		//	  			   as_tipsal // Tipo de salida que voy a reportar
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados al personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_campo="nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "titcon";
		}
		$ls_sql="SELECT codconc, ".$ls_campo." as nomcon ".
				"  FROM sno_thconcepto ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   ".$as_sigcon." ".
				"   AND codconc IN (SELECT codconc FROM sno_thsalida WHERE codemp='".$this->ls_codemp."' AND codnom='".$this->ls_codnom."')".
				" ORDER BY codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_concepto_excel ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_pagonomina_conceptopersonal_excel
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonomina_conceptopersonal_excel($as_codper,$as_tituloconcepto,$as_tipsal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonomina_conceptopersonal_excel
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: as_codper // Código del personal que se desea buscar la salida
		//	  			   as_tituloconcepto // criterio que me indica si se desea mostrar el título del concepto ó el nombre
		//	  			   as_tipsal // Tipo de salida que voy a reportar
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados al personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->DS_detalle->reset_ds();
		$lb_valido=true;
		$ls_criterio="";
		$ls_campo="sno_thconcepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_thconcepto.titcon";
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_thconcepto.codconc, MAX(".$ls_campo.") as nomcon, SUM(sno_thsalida.valsalaux) as valsal, MAX(sno_thsalida.tipsal) AS tipsal ".
					  "  FROM sno_thsalida, sno_thconcepto, sno_thpersonalnomina ".
					  " WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
					  "   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
					  "   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
					  "   AND sno_thsalida.codperi='".$this->ls_peractnom."'".
					  "   AND sno_thsalida.codper='".$as_codper."'".
					  "   AND sno_thsalida.codconc='".$ls_vac_codconvac."'".
					  "   AND sno_thpersonalnomina.staper = '2' ".
					  "   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
					  "   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
					  "   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
					  "   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
					  "   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					  "   AND sno_thsalida.codemp = sno_thpersonalnomina.codemp ".
					  "   AND sno_thsalida.codnom = sno_thpersonalnomina.codnom ".
					  "   AND sno_thsalida.anocur = sno_thpersonalnomina.anocur ".
					  "   AND sno_thsalida.codperi = sno_thpersonalnomina.codperi ".
					  "   AND sno_thsalida.codper = sno_thpersonalnomina.codper ".
					  " GROUP BY sno_thconcepto.codconc ";
		}
		$ls_sql="SELECT sno_thconcepto.codconc, MAX(".$ls_campo.") as nomcon, SUM(sno_thsalida.valsalaux) as valsal, MAX(sno_thsalida.tipsal) AS tipsal ".
				"  FROM sno_thconcepto, sno_thsalida ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."'".
				"   AND sno_thsalida.codper='".$as_codper."'".
				"   ".$as_tipsal.
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				" GROUP BY sno_thconcepto.codconc ".
				"   ".$ls_union.
				" ORDER BY codconc, tipsal ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_conceptopersonal_excel ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_pagonomina_conceptopersonal_excel
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonomina_prestamoamortizado($as_codper,$as_concepto,&$as_valor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonomina_prestamoamortizado
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: as_codper // Código del personal que se desea buscar el prestamo
		//	  			   as_concepto // código del concepto 
		//	  			   as_valor // Valor del Amortizado
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los prestamos asociados a estas personas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_valor="";
		$lb_valido=true;
		$ls_sql="SELECT monamopre ".
				"  FROM sno_thprestamos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$this->ls_anocurnom."' ".
				"   AND codperi='".$this->ls_peractnom."'".
				"   AND codconc='".$as_concepto."' ".				
				"   AND codper='".$as_codper."'".
				"   AND stapre=1";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_total=0;
			$lb_entro=false;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_total=$ls_total+$row["monamopre"];
				$lb_entro=true;
			}
			if($lb_entro)
			{
				$as_valor=number_format($ls_total,2,",",".");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_pagonomina_prestamoamortizado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_recibopago_personal($as_codperdes,$as_codperhas,$as_coduniadm,$as_conceptocero,$as_conceptop2,$as_conceptoreporte,
									$as_codubifis,$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibopago_personal
		//         Access: public (desde la clase sigesp_sno_r_recibopago)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_coduniadm // Código de la unidad administrativa	  
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos de tipo reporte
		//	  			   as_orden // Orde a mostrar en el reporte		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= "AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio=$ls_criterio."   AND sno_thpersonalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio=$ls_criterio."   AND sno_thpersonalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_thpersonalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_thpersonalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_thpersonalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		if(!empty($as_conceptop2))
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4' OR ".
											"  	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ";
			}
		}
		else
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"  	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3')";
			}
		}
		if(!empty($as_codubifis))
		{
			$ls_criterio= $ls_criterio." AND sno_thpersonalnomina.codubifis='".$as_codubifis."'";
			$ls_criteriounion = $ls_criteriounion." AND sno_thpersonalnomina.codubifis='".$as_codubifis."'";
		}
		else
		{
			if(!empty($as_codest))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codpai='".$as_codpai."'";
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codest='".$as_codest."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codpai='".$as_codpai."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codest='".$as_codest."'";
			}
			if(!empty($as_codmun))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
			}
			if(!empty($as_codpar))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
			}
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		if($this->li_rac=="1")// Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_thasignacioncargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp = sno_thasignacioncargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thasignacioncargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thasignacioncargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thasignacioncargo.codperi ".
				       "           AND sno_thpersonalnomina.codasicar = sno_thasignacioncargo.codasicar) as descar ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_thcargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp = sno_thcargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thcargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thcargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thcargo.codperi ".
				       "           AND sno_thpersonalnomina.codcar = sno_thcargo.codcar) as descar ";
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.nacper, ".
				"		sno_thpersonalnomina.codcueban, sno_personal.fecingper, sum(sno_thsalida.valsalaux) as total, sno_thunidadadmin.desuniadm,".
				"		sno_thunidadadmin.minorguniadm,sno_thunidadadmin.ofiuniadm,sno_thunidadadmin.uniuniadm,sno_thunidadadmin.depuniadm,".
				"		sno_thunidadadmin.prouniadm, MAX(sno_thpersonalnomina.sueperaux) AS sueper,  MAX(sno_thpersonalnomina.pagbanper) AS pagbanper, ".
				"		MAX(sno_thpersonalnomina.pagefeper) AS pagefeper, MAX(sno_ubicacionfisica.desubifis) AS desubifis,  ".
				"		  (SELECT desest FROM sigesp_estados ".
				"			WHERE sigesp_estados.codpai = sno_ubicacionfisica.codpai ".
				"			 AND sigesp_estados.codest = sno_ubicacionfisica.codest) AS desest, ".
				"		  (SELECT denmun FROM sigesp_municipio ".
				"			WHERE sigesp_municipio.codpai = sno_ubicacionfisica.codpai ".
				"			 AND sigesp_municipio.codest = sno_ubicacionfisica.codest ".
				"			 AND sigesp_municipio.codmun = sno_ubicacionfisica.codmun) AS denmun, ".
				"		  (SELECT denpar FROM sigesp_parroquia ".
				"			WHERE sigesp_parroquia.codpai = sno_ubicacionfisica.codpai ".
				"			 AND sigesp_parroquia.codest = sno_ubicacionfisica.codest ".
				"			 AND sigesp_parroquia.codmun = sno_ubicacionfisica.codmun ".
				"			 AND sigesp_parroquia.codpar = sno_ubicacionfisica.codpar) AS denpar, ".
				"		(SELECT nomban FROM scb_banco ".
				"		   WHERE scb_banco.codemp = sno_thpersonalnomina.codemp ".
				" 			 AND scb_banco.codban = sno_thpersonalnomina.codban) AS banco,".
				$ls_descar.
				"  FROM sno_personal, sno_thpersonalnomina, sno_thsalida, sno_thunidadadmin, sno_ubicacionfisica ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal<>'P2' AND  sno_thsalida.tipsal<>'V4' AND sno_thsalida.tipsal<>'W4') ".
				"   ".$ls_criterio." ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_ubicacionfisica.codemp ".
				"   AND sno_thpersonalnomina.codubifis = sno_ubicacionfisica.codubifis ".
				"   AND sno_thpersonalnomina.codemp = sno_personal.codemp ".
				"   AND sno_thpersonalnomina.codper = sno_personal.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				" GROUP BY sno_thpersonalnomina.codemp, sno_thpersonalnomina.codnom, sno_thpersonalnomina.anocur, sno_thpersonalnomina.codperi, ".
				"		   sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
				"		   sno_personal.nacper,  sno_thpersonalnomina.codcueban, sno_personal.fecingper, ".
				"		   sno_thunidadadmin.desuniadm, sno_thpersonalnomina.codasicar, sno_thpersonalnomina.codcar, ".
				"		   sno_thpersonalnomina.codban, sno_thunidadadmin.minorguniadm,sno_thunidadadmin.ofiuniadm, ".
				"		   sno_thunidadadmin.uniuniadm,sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, sno_ubicacionfisica.codpai,  ".
				"          sno_ubicacionfisica.codest,sno_ubicacionfisica.codmun,sno_ubicacionfisica.codpar ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_recibopago_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_recibopago_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_recibopago_conceptopersonal($as_codper,$as_conceptocero,$as_conceptop2,$as_conceptoreporte,$as_tituloconcepto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibopago_conceptopersonal
		//         Access: public (desde la clase sigesp_sno_rpp_recibopago)  
		//	    Arguments: as_codper // Código del personal que se desea buscar la salida
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos cuyo valor es cero
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos de tipo reporte
		//	  			   as_tituloconcepto // criterio que me indica si se desea mostrar los títulos de los conceptos
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados al personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_campo="sno_thconcepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_thconcepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "   AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_conceptop2))
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4' OR ".
											"  	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ";
			}
		}
		else
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"  	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3')";
			}
		}
		$ls_sql="SELECT sno_thconcepto.codconc, ".$ls_campo." as nomcon, (sno_thsalida.valsalaux) AS valsal, sno_thsalida.tipsal ".
				"  FROM sno_thsalida, sno_thconcepto ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."'".
				"   AND sno_thsalida.codper='".$as_codper."'".
				"   ".$ls_criterio.
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				" ORDER BY sno_thsalida.tipsal ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_recibopago_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_recibopago_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoconcepto_conceptos($as_codconcdes,$as_codconchas,$as_codperdes,$as_codperhas,$as_coduniadm,$as_conceptocero)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoconcepto_conceptos
		//         Access: public (desde la clase sigesp_sno_rpp_listadoconceptos)  
		//	    Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//				   as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_coduniadm // Código de la unidad administrativa que se desea filtrar
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se calcularon en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codconcdes))
		{
			$ls_criterio= "AND sno_thconcepto.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thconcepto.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		$ls_sql="SELECT sno_thconcepto.codconc, sno_thconcepto.nomcon, count(sno_thsalida.codper) as total, sum(sno_thsalida.valsalaux) as monto ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
				"		 sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
				"		 sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3') ".
				"   ".$ls_criterio." ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				" GROUP BY sno_thconcepto.codconc, sno_thconcepto.nomcon ".
				" ORDER BY sno_thconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoconcepto_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadoconcepto_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoconcepto_personalconcepto($as_codconc,$as_codperdes,$as_codperhas,$as_conceptocero,$as_coduniadm,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoconcepto_personalconcepto
		//		   Access: public (desde la clase sigesp_sno_rpp_listadonomina)  
		//	    Arguments: as_codconc // Código del concepto del que se desea busca el personal
		//				   as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal asociado al concepto que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= "AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		if($this->li_rac=="1")// Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_thasignacioncargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp = sno_thasignacioncargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thasignacioncargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thasignacioncargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thasignacioncargo.codperi ".
				       "           AND sno_thpersonalnomina.codasicar = sno_thasignacioncargo.codasicar) as descar ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_thcargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp = sno_thcargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thcargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thcargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thcargo.codperi ".
				       "           AND sno_thpersonalnomina.codcar = sno_thcargo.codcar) as descar ";
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, (sno_thsalida.valsalaux) AS valsal, ".$ls_descar.
				"  FROM sno_personal, sno_thpersonalnomina, sno_thsalida ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thsalida.codconc='".$as_codconc."' ".
				"   AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
				"		 sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
				"		 sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3') ".
				"   ".$ls_criterio." ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_thpersonalnomina.codper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoconcepto_personalconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;

	}// end function uf_listadoconcepto_personalconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadopersonalcheque_unidad($as_codban,$as_suspendidos)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadopersonalcheque_unidad
		//		   Access: public (desde la clase sigesp_sno_rpp_listadopersonalcheque)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que cobran con cheque
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_thpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_thpersonalnomina.staper='1' OR sno_thpersonalnomina.staper='2')";
		}
		$ls_sql="SELECT sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
				"   	sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, sno_thunidadadmin.desuniadm ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thresumen ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thpersonalnomina.pagefeper='1' ".
				"     ".$ls_criterio.
				"	AND sno_thpersonalnomina.codemp = sno_thresumen.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thresumen.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thresumen.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thresumen.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thresumen.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				" GROUP BY sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
				"   	    sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, sno_thunidadadmin.desuniadm ".
				" ORDER BY sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
				"   	    sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadopersonalcheque_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadopersonalcheque_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadopersonalcheque_personal($as_codban,$as_minorguniadm,$as_ofiuniadm,$as_uniuniadm,$as_depuniadm,
											   $as_prouniadm,$as_suspendidos,$as_quincena,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadopersonalcheque_personal
		//		   Access: public (desde la clase sigesp_sno_rpp_listadopersonalcheque)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_minorguniadm // Código del Ministerio ú Organismo
		//	    		   as_ofiuniadm // Código de la Oficina
		//	    		   as_uniuniadm // Código de la Unidad
		//	    		   as_depuniadm // Código del departamento
		//	    		   as_prouniadm // Código del programa
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // quincena que se quiere mostrar
		//	  			   as_orden // Orden del reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco y la unidad administrativa
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="(sno_thresumen.priquiresaux) as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="(sno_thresumen.segquiresaux) as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="(sno_thresumen.monnetresaux) as monnetres";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_thpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_thpersonalnomina.staper='1' OR sno_thpersonalnomina.staper='2')";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código del Personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido del Personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre del Personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula del Personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".$ls_monto." ".
				"  FROM sno_personal, sno_thpersonalnomina, sno_thresumen ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thpersonalnomina.pagefeper='1' ".
				"	AND sno_thpersonalnomina.minorguniadm = '".$as_minorguniadm."' ".
				"   AND sno_thpersonalnomina.ofiuniadm = '".$as_ofiuniadm."' ".
				"   AND sno_thpersonalnomina.uniuniadm = '".$as_uniuniadm."' ".
				"   AND sno_thpersonalnomina.depuniadm = '".$as_depuniadm."' ".
				"   AND sno_thpersonalnomina.prouniadm = '".$as_prouniadm."' ".
				"	".$ls_criterio.
				"	AND sno_thpersonalnomina.codemp = sno_thresumen.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thresumen.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thresumen.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thresumen.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thresumen.codper ".
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"	AND sno_personal.codper = sno_thpersonalnomina.codper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadopersonalcheque_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadopersonalcheque_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_banco($as_codban,$as_suspendidos,$as_sc_cuenta,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_banco
		//		   Access: public (desde la clase sigesp_sno_rpp_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_sc_cuenta // cuenta contable del banco
		//	    		   as_ctaban // cuenta del banco
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del banco seleccionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_thpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_thpersonalnomina.staper='1' OR sno_thpersonalnomina.staper='2')";
		}
		$ls_sql="SELECT scb_banco.codban, scb_banco.nomban ".
				"  FROM sno_thpersonalnomina, sno_thresumen, scb_banco  ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thpersonalnomina.pagbanper=1 OR sno_thpersonalnomina.pagtaqper=1) ".
				"   AND sno_thresumen.monnetres > 0".
				"   ".$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_thresumen.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thresumen.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thresumen.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thresumen.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thresumen.codper ".
				"   AND sno_thpersonalnomina.codemp = scb_banco.codemp ".
				"   AND sno_thpersonalnomina.codban = scb_banco.codban ".
				" GROUP BY scb_banco.codban, scb_banco.nomban ".
				" ORDER BY scb_banco.nomban ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_banco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
				$lb_valido=$this->uf_update_banco($as_codban,$as_sc_cuenta,$as_ctaban);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadobanco_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_banco($as_codban,$as_sc_cuenta,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_banco
		//		   Access: private
		//	    Arguments: as_codban  // código de cargo
		//	    		   as_sc_cuenta // cuenta contable del banco
		//	    		   as_ctaban // cuenta del banco
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza si se generó el listado al banco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_banco ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codban='".$as_codban."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_update_banco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			$ls_sql="INSERT INTO sno_banco(codemp,codnom,codperi,codban,codcueban,codcuecon) VALUES ('".$this->ls_codemp."',".
					"'".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codban."','".$as_ctaban."','".$as_sc_cuenta."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_update_banco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	}// end function uf_update_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_personal($as_codban,$as_suspendidos,$as_tipcueban,$as_quincena,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_personal
		//		   Access: public (desde la clase sigesp_sno_rpp_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_tipcueban // tipo de cuenta bancaria (Ahorro,  Corriente, Activos liquidos)
		//	  			   as_quincena // Quincena para el cual se quiere filtrar
		//	  			   as_orden // Orden del reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="(sno_thresumen.priquiresaux) as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="(sno_thresumen.segquiresaux) as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="(sno_thresumen.monnetresaux) as monnetres";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_thpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_thpersonalnomina.staper='1' OR sno_thpersonalnomina.staper='2')";
		}
		switch($as_tipcueban)
		{
			case "A": // Cuenta de Ahorro
				$ls_criterio = $ls_criterio." AND sno_thpersonalnomina.tipcuebanper='A' ";
				break;
				
			case "C": // Cuenta corriente
				$ls_criterio = $ls_criterio." AND sno_thpersonalnomina.tipcuebanper='C' ";
				break;

			case "L": // Cuenta Activos Líquidos
				$ls_criterio = $ls_criterio." AND sno_thpersonalnomina.tipcuebanper='L' ";
				break;
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código del Personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido del Personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre del Personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula del Personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".$ls_monto.", sno_thpersonalnomina.codcueban  ".
				"  FROM sno_personal, sno_thpersonalnomina, sno_thresumen  ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thpersonalnomina.pagbanper=1 ".
				"   AND sno_thpersonalnomina.pagefeper=0 ".
				"   AND sno_thpersonalnomina.pagtaqper=0 ".
				"   AND sno_thresumen.monnetres > 0 ".
				"	".$ls_criterio.
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"	AND sno_personal.codper = sno_thpersonalnomina.codper ".
				"	AND sno_thpersonalnomina.codemp = sno_thresumen.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thresumen.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thresumen.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thresumen.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thresumen.codper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadobanco_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobancotaquilla_personal($as_codban,$as_suspendidos,$as_quincena,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobancotaquilla_personal
		//		   Access: public (desde la clase sigesp_sno_rpp_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_tipcueban // tipo de cuenta bancaria (Ahorro,  Corriente, Activos liquidos)
		//	  			   as_quincena // Quincena para el cual se quiere filtrar
		//	  			   as_orden // Orden del reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_thresumen.priquiresaux as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_thresumen.segquiresaux as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_thresumen.monnetresaux as monnetres";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_thpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_thpersonalnomina.staper='1' OR sno_thpersonalnomina.staper='2')";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código del Personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido del Personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre del Personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula del Personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".$ls_monto.", sno_thpersonalnomina.codcueban  ".
				"  FROM sno_personal, sno_thpersonalnomina, sno_thresumen  ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thpersonalnomina.pagbanper=0 ".
				"   AND sno_thpersonalnomina.pagefeper=0 ".
				"   AND sno_thpersonalnomina.pagtaqper=1 ".
				"   AND sno_thresumen.monnetres > 0 ".
				"	".$ls_criterio.
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"	AND sno_personal.codper = sno_thpersonalnomina.codper ".
				"	AND sno_thpersonalnomina.codemp = sno_thresumen.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thresumen.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thresumen.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thresumen.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thresumen.codper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobancotaquilla_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadobancotaquilla_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_aportepatronal_personal($as_codconc,$as_conceptocero,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_aportepatronal_personal
		//		   Access: public (desde la clase sigesp_sno_rpp_listadonomina)  
		//	    Arguments: as_codconc // Código del concepto del que se desea busca el personal
		//	  			   as_conceptocero // concepto cero
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el concepto	de tipo aporte patronal 
		//				   y se calculó en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/04/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codconc))
		{
			$ls_criterio = $ls_criterio." AND sno_thsalida.codconc='".$as_codconc."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_thsalida.valsal<>0 ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_thpersonalnomina.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, count(sno_personal.cedper) as total, ".
				"       (SELECT SUM(valsalaux) ".
				"		   FROM sno_thsalida ".
				"   	  WHERE (sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR sno_thsalida.tipsal='Q1') ".
				$ls_criterio.
				"           AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   		AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   		AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   		AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   		AND sno_thpersonalnomina.codper = sno_thsalida.codper) as personal, ".
				"       (SELECT SUM(valsalaux) ".
				"		   FROM sno_thsalida ".
				"   	  WHERE (sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4' OR sno_thsalida.tipsal='Q2') ".
				$ls_criterio.
				"           AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   		AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   		AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   		AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   		AND sno_thpersonalnomina.codper = sno_thsalida.codper) as patron ".
				"  FROM sno_personal, sno_thpersonalnomina, sno_thsalida ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				$ls_criterio.
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_thpersonalnomina.codper ".
				"	AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"	AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"	AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"	AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"	AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				" GROUP BY sno_thpersonalnomina.codemp, sno_thpersonalnomina.codnom, sno_thpersonalnomina.anocur, sno_thpersonalnomina.codperi, ".
				"		   sno_thpersonalnomina.codper, sno_personal.cedper, sno_personal.apeper, ".
				"		   sno_personal.nomper, sno_thsalida.codemp, sno_thsalida.codnom, sno_thsalida.codperi, sno_thsalida.codper   ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_aportepatronal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);			
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_aportepatronal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_resumenconcepto_conceptos($as_codconcdes,$as_codconchas,$as_aportepatronal,$as_conceptocero)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_resumenconcepto_conceptos
		//         Access: public (desde la clase sigesp_sno_rpp_resumenconceptos)  
		//	    Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//				   as_aportepatronal // criterio que me indica si se quiere mostrar el aporte patronal
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se calcularon en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codconcdes))
		{
			$ls_criterio= "AND sno_thconcepto.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thconcepto.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_aportepatronal))
		{
			$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
										"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
										"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
										"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4')";
		}
		else
		{
			$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
										"      sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
										"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3')";
		}
		$ls_sql="SELECT sno_thconcepto.codconc, sno_thconcepto.nomcon, sno_thsalida.tipsal, sum(sno_thsalida.valsalaux) as monto ".
				"  FROM sno_thsalida, sno_thconcepto ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio." ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				" GROUP BY sno_thconcepto.codconc, sno_thsalida.tipsal, sno_thconcepto.nomcon ".
				" ORDER BY sno_thconcepto.codconc, sno_thsalida.tipsal ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_resumenconcepto_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_resumenconcepto_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_resumenconceptounidad_unidad($as_codconcdes,$as_codconchas,$as_coduniadm,$as_conceptocero)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_resumenconceptounidad_unidad
		//         Access: public (desde la clase sigesp_sno_r_resumenconceptounidad)  
		//	    Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//				   as_coduniadm // Código de la unidad administrativa 
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las unidades administrativas asociadas a los conceptos	
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/04/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codconcdes))
		{
			$ls_criterio= "AND sno_thsalida.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thsalida.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_minorguniadm=substr($as_coduniadm,0,4);
			$ls_ofiuniadm=substr($as_coduniadm,5,2);
			$ls_uniuniadm=substr($as_coduniadm,8,2);
			$ls_depuniadm=substr($as_coduniadm,11,2);
			$ls_prouniadm=substr($as_coduniadm,14,2);
			$ls_criterio = $ls_criterio."   AND sno_thpersonalnomina.minorguniadm = '".$ls_minorguniadm."' ".
										"   AND sno_thpersonalnomina.ofiuniadm = '".$ls_ofiuniadm."' ".
										"   AND sno_thpersonalnomina.uniuniadm = '".$ls_uniuniadm."' ".
										"   AND sno_thpersonalnomina.depuniadm = '".$ls_depuniadm."' ".
										"   AND sno_thpersonalnomina.prouniadm = '".$ls_prouniadm."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
		}
		$ls_sql="SELECT sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, sno_thunidadadmin.depuniadm, ".
				"		sno_thunidadadmin.prouniadm, sno_thunidadadmin.desuniadm ".
				"  FROM sno_thsalida, sno_thpersonalnomina, sno_thunidadadmin ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
				"        sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
				"        sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
				"	     sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ".
				"   ".$ls_criterio." ".
				"   AND sno_thsalida.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_thsalida.codnom = sno_thpersonalnomina.codnom ".
				"   AND sno_thsalida.codper = sno_thpersonalnomina.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				" GROUP BY sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, sno_thunidadadmin.depuniadm, ".
				"		sno_thunidadadmin.prouniadm, sno_thunidadadmin.desuniadm ".
				" ORDER BY sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, sno_thunidadadmin.depuniadm, ".
				"		sno_thunidadadmin.prouniadm";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_resumenconceptounidad_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_resumenconceptounidad_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_resumenconceptounidad_concepto($as_codconcdes,$as_codconchas,$as_coduniadm,$as_conceptocero,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_resumenconceptounidad_concepto
		//         Access: public (desde la clase sigesp_sno_r_resumenconceptounidad)  
		//	    Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//				   as_coduniadm // Código de la unidad administrativa 
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//	  			   as_orden // Orden del reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados a la unidad administrativa
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/04/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,5,2);
		$ls_uniuniadm=substr($as_coduniadm,8,2);
		$ls_depuniadm=substr($as_coduniadm,11,2);
		$ls_prouniadm=substr($as_coduniadm,14,2);
		if(!empty($as_codconcdes))
		{
			$ls_criterio= "AND sno_thsalida.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thsalida.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Tipo de Salida y Código del Concepto
				$ls_orden="ORDER BY sno_thsalida.tipsal, sno_thconcepto.codconc ";
				break;

			case "2": // Ordena por Tipo de Salida y descripción del Concepto
				$ls_orden="ORDER BY sno_thsalida.tipsal,  sno_thconcepto.nomcon ";
				break;
		}
		$ls_sql="SELECT sno_thconcepto.codconc, sno_thconcepto.nomcon, sno_thsalida.tipsal, sum(sno_thsalida.valsalaux) as monto ".
				"  FROM sno_thsalida, sno_thpersonalnomina, sno_thconcepto ".
				" WHERE sno_thpersonalnomina.minorguniadm = '".$ls_minorguniadm."' ".
				"   AND sno_thpersonalnomina.ofiuniadm = '".$ls_ofiuniadm."' ".
				"   AND sno_thpersonalnomina.uniuniadm = '".$ls_uniuniadm."' ".
				"   AND sno_thpersonalnomina.depuniadm = '".$ls_depuniadm."' ".
				"   AND sno_thpersonalnomina.prouniadm = '".$ls_prouniadm."' ".
				"   AND sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
				"        sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
				"        sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
				"	     sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ".
				"   ".$ls_criterio." ".
				"   AND sno_thsalida.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_thsalida.codnom = sno_thpersonalnomina.codnom ".
				"   AND sno_thsalida.anocur = sno_thpersonalnomina.anocur ".
				"   AND sno_thsalida.codperi = sno_thpersonalnomina.codperi ".
				"   AND sno_thsalida.codper = sno_thpersonalnomina.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				" GROUP BY sno_thconcepto.codconc, sno_thsalida.tipsal, sno_thconcepto.nomcon ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_resumenconceptounidad_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_resumenconceptounidad_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cuadrenomina_periodo_previo(&$ai_anoprev,&$ai_periprev)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cuadrenomina_periodo_previo
		//		   Access: public
		//	    Arguments: ai_anoprev // Año Previo
		//                 ai_periprev // periodo previo          
		//	      Returns: lb_valido True si se ejecuto correctamente la funación y false si hubo error
		//	  Description: función que busca la información del período previo a la nómina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_anoprev=$_SESSION["la_nomina"]["anocurnom"];
		$ai_periprev=(intval($_SESSION["la_nomina"]["peractnom"])-1);
		if($ai_periprev<1)
		{
			$ai_anoprev=(intval($ai_anoprev)-1);
			$ls_sql="SELECT numpernom ".
					"  FROM sno_hnomina ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND anocurnom='".$ai_anoprev."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_cuadrenomina_periodo_previo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_periprev=$row["numpernom"];
				}
				if($ai_periprev<1)
				{
					$ai_periprev="0";
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		$ai_periprev=str_pad($ai_periprev,3,"0",0);
      	return ($lb_valido);  
    }// end function uf_cuadrenomina_periodo_previo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cuadrenomina_concepto($as_codconcdes,$as_codconchas,$as_conceptocero)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cuadrenomina_concepto
		//         Access: public (desde la clase sigesp_sno_r_cuadrenomina)  
		//	    Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_hcriterio="";
		$li_anoprev="";
		$li_periprev="";
		if(!empty($as_codconcdes))
		{
			$ls_criterio= "AND sno_thsalida.codconc>='".$as_codconcdes."'";
			$ls_hcriterio= "AND sno_hsalida.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thsalida.codconc<='".$as_codconchas."'";
			$ls_hcriterio= $ls_hcriterio."   AND sno_hsalida.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
			$ls_hcriterio = $ls_hcriterio."   AND sno_hsalida.valsal<>0 ";
		}
		$lb_valido=$this->uf_cuadrenomina_periodo_previo($li_anoprev,$li_periprev);
		$ls_sql="SELECT sno_thsalida.codconc, sno_thconcepto.nomcon, sno_thsalida.tipsal, sum(COALESCE(sno_thsalida.valsalaux,0)) as actual, ".
				"		COALESCE((SELECT sum(COALESCE(sno_hsalida.valsalaux,0)) as previo ".
				"		   			FROM sno_hsalida ".
				"		 		   WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"					 AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"					 AND sno_hsalida.anocur='".$li_anoprev."' ".
				"					 AND sno_hsalida.codperi='".$li_periprev."' ".
				"   				 AND (sno_hsalida.tipsal='A' OR  sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1')".
				"					 ".$ls_hcriterio.
				"   				 AND sno_hsalida.codconc=sno_thsalida.codconc ".
				"   				 AND sno_hsalida.tipsal=sno_thsalida.tipsal ".
				" 				   GROUP BY sno_hsalida.codconc, sno_hsalida.tipsal),0) as previo ".
				"  FROM sno_thsalida, sno_thconcepto ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal='A' OR  sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1')".
				"   ".$ls_criterio." ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				" GROUP BY sno_thsalida.codconc, sno_thsalida.tipsal, sno_thconcepto.nomcon ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadrenomina_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_cuadrenomina_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_monejetipocargo_programado()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_monejetipocargo_programado
		//         Access: public (desde la clase sigesp_snorh_rpp_monejetipocargo)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql="SELECT sno_programacionreporte.codrep, sno_programacionreporte.codded, sno_programacionreporte.codtipper, ".
				"		(SELECT desded FROM  sno_dedicacion ".
				"	 	  WHERE sno_programacionreporte.codemp = sno_dedicacion.codemp ".
				"			AND sno_programacionreporte.codded = sno_dedicacion.codded) as desded, ".
				"		(SELECT destipper FROM  sno_tipopersonal ".
				"	 	  WHERE sno_programacionreporte.codemp = sno_tipopersonal.codemp ".
				"			AND sno_programacionreporte.codded = sno_tipopersonal.codded ".
				"			AND sno_programacionreporte.codtipper = sno_tipopersonal.codtipper) as destipper ".
				"  FROM sno_programacionreporte ".
				" WHERE sno_programacionreporte.codemp = '".$this->ls_codemp."'".
				"   AND sno_programacionreporte.codrep = '0711'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_monejetipocargo_programado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_monejetipocargo_programado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_monejetipocargo_real($as_codded,$as_codtipper,&$ai_cargoreal,&$ai_montoreal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_monejetipocargo_real
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0711)  
		//	    Arguments: as_codded // código de dedicación
		//	   			   as_codtipper // código de tipo de personal
		//	   			   ai_cargoreal // Cargo Real
		//	   			   ai_montoreal // Monto Real
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_groupcargos="";
		$ls_groupmontos="";
		if($as_codtipper=="0000")
		{
			$ls_criterio=" AND sno_thpersonalnomina.codded='".$as_codded."'";
			$ls_groupcargos=" GROUP BY sno_thpersonalnomina.codper, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
			$ls_groupmontos=" GROUP BY sno_thpersonalnomina.codded ";
		}
		else
		{
			$ls_criterio=" AND sno_thpersonalnomina.codded='".$as_codded."'".
						 " AND sno_thpersonalnomina.codtipper='".$as_codtipper."'";
			$ls_groupcargos=" GROUP BY sno_thpersonalnomina.codper, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
			$ls_groupmontos=" GROUP BY sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		}

		$ls_sql="SELECT sno_thpersonalnomina.codper ".
				"  FROM sno_thpersonalnomina, sno_thperiodo, sno_thnomina ".
				" WHERE sno_thpersonalnomina.codemp = '".$this->ls_codemp."'".
				"   AND sno_thpersonalnomina.codnom = '".$this->ls_codnom."'".
				"   AND sno_thpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
				"   AND sno_thpersonalnomina.codperi = '".$this->ls_peractnom."'".
				"   ".$ls_criterio.
				"   AND sno_thnomina.tipnom <> 7 ".
				"   AND sno_thnomina.espnom = 0 ".
				"   AND sno_thnomina.ctnom = 0 ".
				"   AND sno_thnomina.codemp = sno_thperiodo.codemp ".
				"   AND sno_thnomina.codnom = sno_thperiodo.codnom ".
				"	AND sno_thnomina.anocurnom = sno_thperiodo.anocur ".
				"   AND sno_thnomina.peractnom = sno_thperiodo.codperi ".
				"   AND sno_thpersonalnomina.codemp = sno_thperiodo.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thperiodo.codnom ".
				"	AND sno_thpersonalnomina.anocur = sno_thperiodo.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thperiodo.codperi ".
				$ls_groupcargos;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0711_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_cargoreal=$ai_cargoreal+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			$ls_sql="SELECT sum(sno_thsalida.valsalaux) as monto ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_thperiodo, sno_thnomina ".
					" WHERE sno_thpersonalnomina.codemp = '".$this->ls_codemp."'".
					"   AND sno_thpersonalnomina.codnom = '".$this->ls_codnom."'".
					"   AND sno_thpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
					"   AND sno_thpersonalnomina.codperi = '".$this->ls_peractnom."'".
					$ls_criterio.
					"   AND sno_thsalida.tipsal = 'A' ".
					"   AND sno_thnomina.tipnom <> 7 ".
					"   AND sno_thnomina.codemp = sno_thperiodo.codemp ".
					"   AND sno_thnomina.codnom = sno_thperiodo.codnom ".
					"	AND sno_thnomina.anocurnom = sno_thperiodo.anocur ".
					"   AND sno_thnomina.peractnom = sno_thperiodo.codperi ".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"	AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND sno_thpersonalnomina.codemp = sno_thperiodo.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thperiodo.codnom ".
					"	AND sno_thpersonalnomina.anocur = sno_thperiodo.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thperiodo.codperi ".
					$ls_groupmontos;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0711_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_montoreal=$row["monto"];
				}
				$this->io_sql->free_result($rs_data);
			}
		}		
		return $lb_valido;
	}// end function uf_monejetipocargo_real
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_monejepensionado_programado()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_monejepensionado_programado
		//         Access: public (desde la clase sigesp_snorh_rpp_monejepensionado)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql="SELECT sno_programacionreporte.codrep, sno_programacionreporte.codded, sno_programacionreporte.codtipper ".
				"  FROM sno_programacionreporte ".
				" WHERE sno_programacionreporte.codemp = '".$this->ls_codemp."'".
				"   AND sno_programacionreporte.codrep = '0712'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_monejepensionado_programado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_monejepensionado_programado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_monejepensionado_real($as_catjub,$as_conjub,&$ai_cargoreal,&$ai_montoreal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_monejepensionado_real
		//         Access: public (desde la clase sigesp_snorh_rpp_monejepensionado)  
		//	    Arguments: as_catjub // Categoría de Jubilación
		//	   			   as_conjub // Condición de Jubilación
		//	   			   ai_cargoreal // Cargo Real
		//	   			   ai_montoreal // Monto Real
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_groupcargos="";
		$ls_groupmontos="";
		if($as_conjub=="0000")
		{
			$ls_criterio=" AND sno_thpersonalnomina.catjub='".$as_catjub."'";
			$ls_groupcargos=" GROUP BY sno_thpersonalnomina.codper, sno_thpersonalnomina.catjub, sno_thpersonalnomina.conjub ";
			$ls_groupmontos=" GROUP BY sno_thpersonalnomina.catjub ";
		}
		else
		{
			$ls_criterio=" AND sno_thpersonalnomina.catjub='".$as_catjub."'".
						 " AND sno_thpersonalnomina.conjub='".$as_conjub."'";
			$ls_groupcargos=" GROUP BY sno_thpersonalnomina.codper, sno_thpersonalnomina.catjub, sno_thpersonalnomina.conjub ";
			$ls_groupmontos=" GROUP BY sno_thpersonalnomina.catjub, sno_thpersonalnomina.conjub ";
		}
		$ls_sql="SELECT sno_thpersonalnomina.codper ".
				"  FROM sno_thpersonalnomina, sno_thperiodo, sno_thnomina ".
				" WHERE sno_thpersonalnomina.codemp = '".$this->ls_codemp."'".
				"   AND sno_thpersonalnomina.codnom = '".$this->ls_codnom."'".
				"   AND sno_thpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
				"   AND sno_thpersonalnomina.codperi = '".$this->ls_peractnom."'".
				"   AND sno_thnomina.tipnom = 7 ".
				"   AND sno_thnomina.espnom = 0 ".
				"   AND sno_thnomina.ctnom = 0 ".
				$ls_criterio.
				"   AND sno_thnomina.codemp = sno_thperiodo.codemp ".
				"   AND sno_thnomina.codnom = sno_thperiodo.codnom ".
				"	AND sno_thnomina.anocurnom = sno_thperiodo.anocur ".
				"   AND sno_thnomina.peractnom = sno_thperiodo.codperi ".
				"   AND sno_thpersonalnomina.codemp = sno_thperiodo.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thperiodo.codnom ".
				"	AND sno_thpersonalnomina.anocur = sno_thperiodo.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thperiodo.codperi ".
				$ls_groupcargos;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_monejepensionado_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_cargoreal=$ai_cargoreal+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			$ls_sql="SELECT sum(sno_hsalida.valsalaux) as monto ".
					"  FROM sno_thpersonalnomina, sno_hsalida, sno_thperiodo, sno_thnomina ".
					" WHERE sno_thpersonalnomina.codemp = '".$this->ls_codemp."'".
					"   AND sno_thpersonalnomina.codnom = '".$this->ls_codnom."'".
					"   AND sno_thpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
					"   AND sno_thperiodo.codperi = '".$this->ls_peractnom."'".
					$ls_criterio.
					"   AND sno_thnomina.tipnom = 7 ".
					"   AND sno_thnomina.espnom = 0 ".
					"   AND sno_thnomina.ctnom = 0 ".
					"   AND sno_hsalida.tipsal = 'A' ".
					"   AND sno_thnomina.codemp = sno_thperiodo.codemp ".
					"   AND sno_thnomina.codnom = sno_thperiodo.codnom ".
					"	AND sno_thnomina.anocurnom = sno_thperiodo.anocur ".
					"   AND sno_thnomina.peractnom = sno_thperiodo.codperi ".
					"   AND sno_thpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_hsalida.codnom ".
					"	AND sno_thpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_thpersonalnomina.codemp = sno_thperiodo.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thperiodo.codnom ".
					"	AND sno_thpersonalnomina.anocur = sno_thperiodo.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thperiodo.codperi ".
					$ls_groupmontos;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_monejepensionado_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_montoreal=$row["monto"];
				}
				$this->io_sql->free_result($rs_data);
			}
		}		
		return $lb_valido;
	}// end function uf_monejepensionado_real
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_relacionvacacion_personal($as_codper,$as_codvac,$as_conceptocero)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_relacionvacacion_personal
		//         Access: public (desde la clase sigesp_sno_rpp_relacionvacacion)  
		//	    Arguments: as_codper // Código del personal 
		//	  			   as_codvac // Código de la vacación 
		//	  			   as_conceptocero // si se desean mostrar los conceptos en cero
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que sale de vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "AND sno_thsalida.valsal<>0 ";
		}
		if($this->li_rac=="1")// Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_thasignacioncargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp = sno_thasignacioncargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thasignacioncargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thasignacioncargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thasignacioncargo.codperi ".
				       "           AND sno_thpersonalnomina.codasicar = sno_thasignacioncargo.codasicar) as descar ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_thcargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp = sno_thcargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thcargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thcargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thcargo.codperi ".
				       "           AND sno_thpersonalnomina.codcar = sno_thcargo.codcar) as descar ";
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		sno_thunidadadmin.desuniadm, (sno_thvacacpersonal.sueintvacaux) as sueintvac, sno_thvacacpersonal.fecdisvac, ".
				"		sno_thvacacpersonal.fecreivac, sno_thvacacpersonal.diavac, sno_thvacacpersonal.codvac, ".$ls_descar.
				"  FROM sno_personal, sno_thpersonalnomina, sno_thunidadadmin, sno_thvacacpersonal, sno_thsalida  ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thvacacpersonal.codper='".$as_codper."' ".
				"   AND sno_thvacacpersonal.codvac='".$as_codvac."' ".
				$ls_criterio.
				"   AND ((sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'V4') ".
				"    OR (sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'W3' OR sno_thsalida.tipsal = 'W4')) ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_personal.codemp ".
				"   AND sno_thpersonalnomina.codper = sno_personal.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND sno_thpersonalnomina.codemp = sno_thvacacpersonal.codemp ".
				"   AND sno_thpersonalnomina.codper = sno_thvacacpersonal.codper ".
				" GROUP BY sno_thpersonalnomina.anocur, sno_thpersonalnomina.codperi, sno_personal.codper, sno_thvacacpersonal.codvac, ".
				"		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, sno_thunidadadmin.desuniadm, ".
				"		   sno_thvacacpersonal.sueintvac, sno_thvacacpersonal.fecdisvac, sno_thvacacpersonal.fecreivac, sno_thvacacpersonal.diavac ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_relacionvacacion_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_relacionvacacion_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_relacionvacacion_concepto($as_codper,$as_codvac,$as_conceptocero,$as_tituloconcepto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_relacionvacacion_concepto
		//         Access: public (desde la clase sigesp_sno_rpp_relacionvacacion)  
		//	    Arguments: as_codper // Código del personal 
		//	  			   as_codvac // Código de vacación
		//	  			   as_conceptocero // si se desean mostrar los conceptos en cero
		//	  			   as_tituloconcepto // si se desea mostrar el nombre del concepto ó el título
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que sale de vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_campo="sno_thconcepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_thconcepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "AND sno_thsalida.valsal<>0 ";
		}
		$ls_sql="SELECT sno_thconcepto.codconc, ".$ls_campo." as nomcon, (sno_thsalida.valsalaux) AS valsal, ".
				"		sno_thsalida.tipsal, sno_thvacacpersonal.persalvac, sno_thvacacpersonal.peringvac ".
				"  FROM sno_thpersonalnomina, sno_thconcepto, sno_thsalida, sno_thvacacpersonal ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thpersonalnomina.codper='".$as_codper."' ".
				"   AND sno_thvacacpersonal.codvac='".$as_codvac."' ".
				$ls_criterio.
				"   AND ((sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'V4') ".
				"    OR (sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'W3' OR sno_thsalida.tipsal = 'W4')) ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_thvacacpersonal.codemp ".
				"   AND sno_thpersonalnomina.anocur = sno_thvacacpersonal.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thvacacpersonal.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thvacacpersonal.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_relacionvacacion_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_relacionvacacion_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_programacionvacaciones_personal($as_estvac,$ad_fecdisdes,$ad_fecdishas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_programacionvacaciones_personal
		//         Access: public (desde la clase sigesp_sno_rpp_resumenconceptos)  
		//	    Arguments: as_estvac // Estatus de las vacaciones
		//				   ad_fecdisdes // Fecha de Disfrute Desde
		//				   ad_fecdishas // Fecha de Disfrute Hasta
		//	  			   as_orden // Orden de la salida
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las vacaciones programadas del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_estvac))
		{
			$ls_criterio= "AND sno_thvacacpersonal.stavac = ".$as_estvac."";
		}
		else
		{
			$ls_criterio= "AND (sno_thvacacpersonal.stavac = 1 OR sno_thvacacpersonal.stavac = 2) ";
		}
		if(!empty($ad_fecdisdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thvacacpersonal.fecdisvac>='".$this->io_funciones->uf_convertirdatetobd($ad_fecdisdes)."'";
		}
		if(!empty($ad_fecdishas))
		{
			$ls_criterio = $ls_criterio."   AND sno_thvacacpersonal.fecdisvac<='".$this->io_funciones->uf_convertirdatetobd($ad_fecdishas)."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de Personal 
				$ls_orden="ORDER BY sno_personal.codper, sno_thvacacpersonal.codvac ";
				break;

			case "2": // Ordena por Apellido de Personal
				$ls_orden="ORDER BY sno_personal.apeper, sno_thvacacpersonal.codvac ";
				break;

			case "3": // Ordena por Nombre de Personal
				$ls_orden="ORDER BY sno_personal.nomper, sno_thvacacpersonal.codvac ";
				break;

			case "4": // Ordena por Fecha de Vencimiento
				$ls_orden="ORDER BY sno_thvacacpersonal.fecvenvac, sno_thvacacpersonal.codvac ";
				break;

			case "5": // Ordena por Fecha de Disfrute
				$ls_orden="ORDER BY sno_thvacacpersonal.fecdisvac, sno_thvacacpersonal.codvac ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.apeper, sno_personal.nomper, sno_thvacacpersonal.codvac, ".
		        "		sno_thvacacpersonal.fecvenvac, sno_thvacacpersonal.fecdisvac, sno_thvacacpersonal.stavac ".
 				"  FROM sno_personal, sno_thpersonalnomina, sno_thvacacpersonal ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_thpersonalnomina.codper ".
				"   AND sno_personal.codemp = sno_thvacacpersonal.codemp ".
				"   AND sno_personal.codper = sno_thvacacpersonal.codper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_programacionvacaciones_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_programacionvacaciones_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadofirmas($as_codperdes,$as_codperhas,$as_personalcero,$as_quincena,$as_tipopago,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadofirmas
		//		   Access: public (desde la clase sigesp_sno_rpp_listadofirmas)  
		//	    Arguments: as_codperdes // Código del personal Desde
		//	    		   as_codperhas // código del personal Hasta
		//	    		   as_personalcero // Si se quiere filtrar por el personal con monto cero
		//	    		   as_quincena // si se busca a toto del personal ó solo los activos
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas para que firmen lo que se les pago
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/11/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= "AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
		}
		switch($as_tipopago)
		{
			case "1": // Pago en efectivo
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagefeper=1 ";
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagbanper=0 ";
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagtaqper=0 ";
				break;
				
			case "2": // Pago en banco
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagefeper=0 ";
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagbanper=1 ";
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagtaqper=0 ";
				break;
				
			case "3": // Pago por taquilla
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagefeper=0 ";
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagbanper=0 ";
				$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.pagtaqper=1 ";
				break;
		}
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="(sno_thresumen.priquiresaux) as monnetres";
				if(!empty($as_personalcero))
				{
					$ls_criterio = $ls_criterio."AND sno_thresumen.priquires<>0 ";
				}
				break;

			case 2: // Segunda Quincena
				$ls_monto="(sno_thresumen.segquiresaux) as monnetres";
				if(!empty($as_personalcero))
				{
					$ls_criterio = $ls_criterio."AND sno_thresumen.segquires<>0 ";
				}
				break;

			case 3: // Mes Completo
				$ls_monto="(sno_thresumen.monnetresaux) as monnetres";
				if(!empty($as_personalcero))
				{
					$ls_criterio = $ls_criterio."AND sno_thresumen.monnetres<>0 ";
				}
				break;
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código del Personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido del Personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre del Personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, ".$ls_monto.
				"  FROM sno_personal, sno_thpersonalnomina,  sno_thresumen ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				$ls_criterio. 
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_thpersonalnomina.codper ".
				"	AND sno_thpersonalnomina.codemp = sno_thresumen.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thresumen.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thresumen.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thresumen.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thresumen.codper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadofirmas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadofirmas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoprestamo_conceptos($as_codconcdes,$as_codconchas,$as_codperdes,$as_codperhas,
										  $as_codtippredes,$as_codtipprehas,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoprestamo_conceptos
		//         Access: public (desde la clase sigesp_sno_rpp_listadoprestamo)  
		//	    Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//				   as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_codtippredes // Código del tipo de prestamo desde
		//	  			   as_codtipprehas // Código del tipo de prestamo hasta
		//	  			   as_estatus // Estatus del prestamo
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se tienen asociados prestamos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codconcdes))
		{
			$ls_criterio= "AND sno_thprestamos.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codper<='".$as_codperhas."'";
		}
		if(!empty($as_codtippredes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codtippre>='".$as_codtippredes."'";
		}
		if(!empty($as_codtipprehas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codtippre<='".$as_codtipprehas."'";
		}
		if(!empty($as_estatus))
		{
			$ls_criterio = $ls_criterio."   AND sno_thprestamos.stapre='".$as_estatus."' ";
		}
		$ls_sql="SELECT sno_thprestamos.codconc, sno_thconcepto.nomcon ".
				"  FROM sno_thprestamos, sno_thconcepto ".
				" WHERE sno_thprestamos.codemp='".$this->ls_codemp."' ".
				"   AND sno_thprestamos.codnom='".$this->ls_codnom."' ".
				"   AND sno_thprestamos.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thprestamos.codperi='".$this->ls_peractnom."' ".
				$ls_criterio.
				"   AND sno_thprestamos.codemp = sno_thconcepto.codemp ".
				"   AND sno_thprestamos.codnom = sno_thconcepto.codnom ".
				"   AND sno_thprestamos.anocur = sno_thconcepto.anocur ".
				"   AND sno_thprestamos.codperi = sno_thconcepto.codperi ".
				"   AND sno_thprestamos.codconc = sno_thconcepto.codconc ".
				" GROUP BY sno_thprestamos.codconc, sno_thconcepto.nomcon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoprestamo_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadoprestamo_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoprestamo_personalconcepto($as_codconc,$as_codperdes,$as_codperhas,
										         $as_codtippredes,$as_codtipprehas,$as_estatus,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoprestamo_personalconcepto
		//		   Access: public (desde la clase sigesp_sno_rpp_listadoprestamo)  
		//	    Arguments: as_codconc // Código del concepto del que se desea busca el personal
		//				   as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_codtippredes // Código del tipo de prestamo desde
		//	  			   as_codtipprehas // Código del tipo de prestamo hasta
		//	  			   as_estatus // Estatus del prestamo
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal asociado al concepto que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);	
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codper<='".$as_codperhas."'";
		}
		if(!empty($as_codtippredes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codtippre>='".$as_codtippredes."'";
		}
		if(!empty($as_codtipprehas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codtippre<='".$as_codtipprehas."'";
		}
		if(!empty($as_estatus))
		{
			$ls_criterio = $ls_criterio."   AND sno_thprestamos.stapre='".$as_estatus."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_thprestamos.codper, sno_personal.nomper, sno_personal.apeper, sno_thtipoprestamo.destippre, ".
			    "		sno_thprestamos.fecpre, (sno_thprestamos.monpreaux) AS monpre, (sno_thprestamos.monamopreaux) AS monamopre, ".
				"		sno_thprestamos.stapre, ".
				"		(SELECT COUNT(codper) FROM sno_thprestamosperiodo ".
				"         WHERE sno_thprestamosperiodo.estcuo = 0 ".
				"			AND sno_thprestamos.codemp = sno_thprestamosperiodo.codemp ".
				" 			AND sno_thprestamos.codnom = sno_thprestamosperiodo.codnom ".
				"			AND sno_thprestamos.anocur = sno_thprestamosperiodo.anocur ".
				"			AND sno_thprestamos.codperi = sno_thprestamosperiodo.codperi ".
				"			AND sno_thprestamos.codper = sno_thprestamosperiodo.codper ".
				"			AND sno_thprestamos.numpre = sno_thprestamosperiodo.numpre ".
				"			AND sno_thprestamos.codtippre = sno_thprestamosperiodo.codtippre) AS numcuopre ".
			    "  FROM sno_thprestamos, sno_personal, sno_thtipoprestamo ".
			    " WHERE sno_thprestamos.codemp='".$this->ls_codemp."' ".
			    "   AND sno_thprestamos.codnom='".$this->ls_codnom."' ".
			    "   AND sno_thprestamos.anocur='".$this->ls_anocurnom."' ".
			    "   AND sno_thprestamos.codperi='".$this->ls_peractnom."' ".
				"	AND sno_thprestamos.codconc='".$as_codconc."' ".
				$ls_criterio.
			    "   AND sno_thprestamos.codemp = sno_personal.codemp ".
			    "   AND sno_thprestamos.codper = sno_personal.codper ".
			    "   AND sno_thprestamos.codemp = sno_thtipoprestamo.codemp ".
			    "   AND sno_thprestamos.codnom = sno_thtipoprestamo.codnom ".
			    "   AND sno_thprestamos.anocur = sno_thtipoprestamo.anocur ".
			    "   AND sno_thprestamos.codperi = sno_thtipoprestamo.codperi ".
			    "   AND sno_thprestamos.codtippre = sno_thtipoprestamo.codtippre ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoprestamo_personalconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadoprestamo_personalconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_detalleprestamo_personal($as_codconcdes,$as_codconchas,$as_codperdes,$as_codperhas,
										  $as_codtippredes,$as_codtipprehas,$as_estatus,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_detalleprestamo_personal
		//         Access: public (desde la clase sigesp_sno_rpp_detalleoprestamo)  
		//	    Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//				   as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_codtippredes // Código del tipo de prestamo desde
		//	  			   as_codtipprehas // Código del tipo de prestamo hasta
		//	  			   as_estatus // Estatus del prestamo
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que se tienen asociados prestamos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codconcdes))
		{
			$ls_criterio= "AND sno_thprestamos.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codper<='".$as_codperhas."'";
		}
		if(!empty($as_codtippredes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codtippre>='".$as_codtippredes."'";
		}
		if(!empty($as_codtipprehas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thprestamos.codtippre<='".$as_codtipprehas."'";
		}
		if(!empty($as_estatus))
		{
			$ls_criterio = $ls_criterio."   AND sno_thprestamos.stapre='".$as_estatus."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden=" ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden=" ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden=" ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden=" ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_thprestamos.codper, sno_thprestamos.numpre, sno_thprestamos.codtippre, sno_thprestamos.codconc, ".
				"		(sno_thprestamos.monpreaux) AS monpre, sno_thprestamos.numcuopre, (sno_thprestamos.monamopreaux) AS monamopre, ".
				"		sno_thprestamos.stapre, ".
				"		sno_thprestamos.fecpre, sno_thprestamos.perinipre, sno_personal.nomper, sno_personal.apeper, ".
				"		sno_thconcepto.nomcon, sno_thtipoprestamo.destippre, sno_personal.cedper, sno_personal.fecingper ".
				"  FROM sno_thprestamos, sno_personal, sno_thconcepto, sno_thtipoprestamo ".
				" WHERE sno_thprestamos.codemp='".$this->ls_codemp."' ".
				"   AND sno_thprestamos.codnom='".$this->ls_codnom."' ".
				"   AND sno_thprestamos.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thprestamos.codperi='".$this->ls_peractnom."' ".
				$ls_criterio.
				"   AND sno_thprestamos.codemp = sno_personal.codemp ".
				"   AND sno_thprestamos.codper = sno_personal.codper ".
				"   AND sno_thprestamos.codemp = sno_thconcepto.codemp ".
				"   AND sno_thprestamos.codnom = sno_thconcepto.codnom ".
				"   AND sno_thprestamos.anocur = sno_thconcepto.anocur ".
				"   AND sno_thprestamos.codperi = sno_thconcepto.codperi ".
				"   AND sno_thprestamos.codconc = sno_thconcepto.codconc ".
				"   AND sno_thprestamos.codemp = sno_thtipoprestamo.codemp ".
				"   AND sno_thprestamos.codnom = sno_thtipoprestamo.codnom ".
				"   AND sno_thprestamos.anocur = sno_thtipoprestamo.anocur ".
				"   AND sno_thprestamos.codperi = sno_thtipoprestamo.codperi ".
				"   AND sno_thprestamos.codtippre = sno_thtipoprestamo.codtippre ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_detalleprestamo_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_detalleprestamo_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_detalleprestamo_cuotas($as_codper,$ai_numpre,$as_codtippre)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_detalleprestamo_cuotas
		//         Access: public (desde la clase sigesp_sno_rpp_detalleoprestamo)  
		//	    Arguments: as_codper // Código del personal
		//				   ai_numpre // Número del Prestamo
		//				   as_codtippre // Código del tipo de prestamo
		//				   as_codconc // Código de concepto
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que se tienen asociados prestamos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numcuo, percob, feciniper, fecfinper, (moncuoaux) as moncuo, estcuo ".
				"  FROM sno_thprestamosperiodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$this->ls_anocurnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codper='".$as_codper."' ".
				"   AND numpre='".$ai_numpre."' ".
				"   AND codtippre='".$as_codtippre."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_detalleprestamo_cuotas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->reset_ds();
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_detalleprestamo_cuotas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_detalleprestamo_amortizado($as_codper,$ai_numpre,$as_codtippre)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_detalleprestamo_amortizado
		//         Access: public (desde la clase sigesp_sno_rpp_detalleoprestamo)  
		//	    Arguments: as_codper // Código del personal
		//				   ai_numpre // Número del Prestamo
		//				   as_codtippre // Código del tipo de prestamo
		//				   as_codconc // Código de concepto
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que se tienen asociados prestamos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/12/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numamo, peramo, fecamo, (monamoaux) AS monamo, desamo ".
				"  FROM sno_thprestamosamortizado ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$this->ls_anocurnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codper='".$as_codper."' ".
				"   AND numpre='".$ai_numpre."' ".
				"   AND codtippre='".$as_codtippre."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_detalleprestamo_amortizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->reset_ds();
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_detalleprestamo_amortizado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoproyecto_proyectos($as_codproydes,$as_codproyhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoproyecto_proyectos
		//         Access: public (desde la clase sigesp_sno_rpp_listadoproyecto)  
		//	    Arguments: as_codproydes // Código del proyecto donde se empieza a filtrar
		//				   as_codproyhas // Código del proyecto donde se termina de filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se calcularon en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codproydes))
		{
			$ls_criterio= "AND sno_thproyecto.codproy>='".$as_codproydes."'";
		}
		if(!empty($as_codproyhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thproyecto.codproy<='".$as_codproyhas."'";
		}
		$ls_sql="SELECT sno_thproyecto.codproy, MAX(sno_thproyecto.nomproy) AS nomproy, count(sno_thproyectopersonal.codper) as total, ".
				"		sum(sno_thproyectopersonal.pordiames*100) as monto ".
				"  FROM sno_thproyectopersonal, sno_thproyecto ".
				" WHERE sno_thproyectopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_thproyectopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_thproyectopersonal.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thproyectopersonal.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				" GROUP BY sno_thproyecto.codproy  ".
				" ORDER BY sno_thproyecto.codproy ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoproyecto_proyectos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadoproyecto_proyectos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoproyecto_proyectospersonal($as_codproy,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoproyecto_proyectospersonal
		//		   Access: public (desde la clase sigesp_sno_rpp_listadoproyecto)  
		//	    Arguments: as_codproy // Código del proyecto del que se desea busca el personal
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal asociado al proyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);	
		$lb_valido=true;
		$ls_orden="";
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		if($this->li_rac=="1")// Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_thasignacioncargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_thpersonalnomina.codemp = sno_thasignacioncargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thasignacioncargo.codnom ".
				       "           AND sno_thpersonalnomina.codasicar = sno_thasignacioncargo.codasicar) as descar ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_thcargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_thpersonalnomina.codemp = sno_thcargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thcargo.codnom ".
				       "           AND sno_thpersonalnomina.codcar = sno_thcargo.codcar) as descar ";
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, (sno_thproyectopersonal.pordiames*100) AS pordiames, ".$ls_descar.
				"  FROM sno_personal, sno_thpersonalnomina, sno_thproyectopersonal ".
				" WHERE sno_thproyectopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_thproyectopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_thproyectopersonal.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thproyectopersonal.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thproyectopersonal.codproy='".$as_codproy."' ".
				"   AND sno_thpersonalnomina.codemp = sno_thproyectopersonal.codemp ".
				"   AND sno_thpersonalnomina.anocur = sno_thproyectopersonal.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thproyectopersonal.codperi ".
				"   AND sno_thpersonalnomina.codnom = sno_thproyectopersonal.codnom ".
				"   AND sno_thpersonalnomina.codper = sno_thproyectopersonal.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_personal.codemp ".
				"   AND sno_thpersonalnomina.codper = sno_personal.codper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoproyecto_proyectospersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadoproyecto_proyectospersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoproyectopersonal_personal($as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoproyectopersonal_personal
		//         Access: public (desde la clase sigesp_sno_rpp_listadoproyecto)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//				   as_codperhas // Código del personal donde se termina de filtrar
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que tiene asociado proyectos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= "AND sno_thproyectopersonal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thproyectopersonal.codper<='".$as_codperhas."'";
		}
		$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
				"		count(sno_thproyectopersonal.codproy) as total, sum(sno_thproyectopersonal.pordiames*100) as monto ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_personal ".
				" WHERE sno_thproyectopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_thproyectopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_thproyectopersonal.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thproyectopersonal.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND sno_thproyectopersonal.codemp = sno_personal.codemp ".
				"   AND sno_thproyectopersonal.codper = sno_personal.codper ".
				" GROUP BY sno_personal.codper  ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoproyectopersonal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadoproyectopersonal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoproyectopersonal_proyecto($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoproyectopersonal_proyecto
		//         Access: public (desde la clase sigesp_sno_rpp_listadoproyecto)  
		//	    Arguments: as_codper // Código del personal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los proyectos asociados al personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_sql="SELECT sno_thproyecto.codproy, sno_thproyecto.nomproy, (sno_thproyectopersonal.pordiames*100) AS pordiames ".
				"  FROM sno_thproyectopersonal, sno_thproyecto ".
				" WHERE sno_thproyectopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_thproyectopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_thproyectopersonal.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thproyectopersonal.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thproyectopersonal.codper='".$as_codper."' ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				" ORDER BY sno_thproyecto.codproy ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoproyectopersonal_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadoproyectopersonal_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonominaunidad_unidad($as_codperdes,$as_codperhas,$as_conceptocero,$as_conceptoreporte,$as_conceptop2,
										  $as_coduniadmdes,$as_coduniadmhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonominaunidad_unidad
		//         Access: public (desde la clase sigesp_sno_rpp_pagonominaunidadadmin)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos cuyo valor es cero
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos tipo reporte
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	    		   as_coduniadmdes // Código de Unidad Administrativa donde se empieza a filtrar
		//	  			   as_coduniadmhas // Código de Unidad Administrativa donde se termina de filtrar		  
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las unidades administrativas del personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_criteriounion="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
			$ls_criteriounion=" AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
			$ls_criteriounion = $ls_criteriounion."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_coduniadmdes))
		{
			$ls_criterio= $ls_criterio." AND sno_thpersonalnomina.minorguniadm>='".substr($as_coduniadmdes,0,4)."'".
						  			   " AND sno_thpersonalnomina.ofiuniadm>='".substr($as_coduniadmdes,5,2)."' ".
						               " AND sno_thpersonalnomina.uniuniadm>='".substr($as_coduniadmdes,8,2)."' ".
						               " AND sno_thpersonalnomina.depuniadm>='".substr($as_coduniadmdes,11,2)."' ".
						               " AND sno_thpersonalnomina.prouniadm>='".substr($as_coduniadmdes,14,2)."' ";
			$ls_criteriounion= $ls_criteriounion." AND sno_thpersonalnomina.minorguniadm>='".substr($as_coduniadmdes,0,4)."'".
						  	   					 " AND sno_thpersonalnomina.ofiuniadm>='".substr($as_coduniadmdes,5,2)."' ".
						       					 " AND sno_thpersonalnomina.uniuniadm>='".substr($as_coduniadmdes,8,2)."' ".
						       					 " AND sno_thpersonalnomina.depuniadm>='".substr($as_coduniadmdes,11,2)."' ".
						       					 " AND sno_thpersonalnomina.prouniadm>='".substr($as_coduniadmdes,14,2)."' ";
		}
		if(!empty($as_coduniadmhas))
		{
			$ls_criterio= $ls_criterio." AND sno_thpersonalnomina.minorguniadm<='".substr($as_coduniadmhas,0,4)."'".
						  			   " AND sno_thpersonalnomina.ofiuniadm<='".substr($as_coduniadmhas,5,2)."' ".
						               " AND sno_thpersonalnomina.uniuniadm<='".substr($as_coduniadmdes,8,2)."' ".
						               " AND sno_thpersonalnomina.depuniadm<='".substr($as_coduniadmhas,11,2)."' ".
						               " AND sno_thpersonalnomina.prouniadm<='".substr($as_coduniadmhas,14,2)."' ";
			$ls_criteriounion= $ls_criteriounion." AND sno_thpersonalnomina.minorguniadm<='".substr($as_coduniadmhas,0,4)."'".
						  	   					 " AND sno_thpersonalnomina.ofiuniadm<='".substr($as_coduniadmhas,5,2)."' ".
						       					 " AND sno_thpersonalnomina.uniuniadm<='".substr($as_coduniadmhas,8,2)."' ".
						       					 " AND sno_thpersonalnomina.depuniadm<='".substr($as_coduniadmhas,11,2)."' ".
						       					 " AND sno_thpersonalnomina.prouniadm<='".substr($as_coduniadmhas,14,2)."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3')";
			}
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
					  "    	  sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, MAX(sno_thunidadadmin.desuniadm) AS desuniadm ".
					  "  FROM sno_thpersonalnomina, sno_thsalida, sno_thunidadadmin ".
					  " WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
					  "   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
					  "   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
					  "   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
					  "	  AND sno_thpersonalnomina.staper = '2' ".
					  "   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					  "   AND sno_thsalida.codconc='".$ls_vac_codconvac."' ".
					  "   ".$ls_criteriounion.
					  "   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					  "   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					  "   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					  "   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					  "   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					  "   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
					  "   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
					  "   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
					  "   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
					  "   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
					  "   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
					  "   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
					  "   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
					  " GROUP BY sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
					  "		   sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm ";
		}
		$ls_sql="SELECT sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm,sno_thunidadadmin.depuniadm,  ".
				"    	sno_thunidadadmin.prouniadm, MAX(sno_thunidadadmin.desuniadm) AS desuniadm   ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thunidadadmin ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				" GROUP BY sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
				"		   sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm  ".
				"   ".$ls_union.
				" ORDER BY sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
				"		   sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonominaunidad_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_pagonominaunidad_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonominaunidad_personal($as_codperdes,$as_codperhas,$as_conceptocero,$as_conceptoreporte,$as_conceptop2,
										  $as_minorguniadm,$as_ofiuniadm,$as_uniuniadm,$as_depuniadm,$as_prouniadm,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonominaunidad_personal
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos cuyo valor es cero
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos tipo reporte
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	    		   as_minorguniadm // Código de la unidad
		//	   			   as_ofiuniadm // Código de la unidad
		//	   			   as_uniuniadm // Código de la unidad
		//	   			   as_depuniadm // Código de la unidad
		//	   			   as_prouniadm // Código de la unidad
		//	   			   as_desuniadm // Descripción de la unidad
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_criteriounion="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
			$ls_criteriounion=" AND sno_thpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
			$ls_criteriounion = $ls_criteriounion."   AND sno_thpersonalnomina.codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_thpersonalnomina.minorguniadm='".$as_minorguniadm."'".
								   " AND sno_thpersonalnomina.ofiuniadm='".$as_ofiuniadm."' ".
								   " AND sno_thpersonalnomina.uniuniadm='".$as_uniuniadm."' ".
								   " AND sno_thpersonalnomina.depuniadm='".$as_depuniadm."' ".
								   " AND sno_thpersonalnomina.prouniadm='".$as_prouniadm."' ";
		$ls_criteriounion= $ls_criteriounion." AND sno_thpersonalnomina.minorguniadm='".$as_minorguniadm."'".
											 " AND sno_thpersonalnomina.ofiuniadm='".$as_ofiuniadm."' ".
											 " AND sno_thpersonalnomina.uniuniadm='".$as_uniuniadm."' ".
											 " AND sno_thpersonalnomina.depuniadm='".$as_depuniadm."' ".
											 " AND sno_thpersonalnomina.prouniadm='".$as_prouniadm."' ";
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
							   				"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3')";
			}
		}
		if(empty($as_orden))
		{
			$ls_orden=" ORDER BY sno_personal.codper ";
		}
		else
		{
			switch($as_orden)
			{
				case "1": // Ordena por Código de personal
					$ls_orden=" ORDER BY codper ";
					break;

				case "2": // Ordena por Apellido de personal
					$ls_orden=" ORDER BY apeper ";
					break;

				case "3": // Ordena por Nombre de personal
					$ls_orden=" ORDER BY nomper ";
					break;
			}
		}
		if($this->li_rac=="1") // Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_thasignacioncargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_thpersonalnomina.codemp = sno_thasignacioncargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thasignacioncargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thasignacioncargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thasignacioncargo.codperi ".
				       "           AND sno_thpersonalnomina.codasicar = sno_thasignacioncargo.codasicar) as descar ";
		}
		else // No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_thcargo ".
					   "   	     WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_thpersonalnomina.codemp = sno_thcargo.codemp ".
					   "		   AND sno_thpersonalnomina.codnom = sno_thcargo.codnom ".
					   "		   AND sno_thpersonalnomina.anocur = sno_thcargo.anocur ".
					   "		   AND sno_thpersonalnomina.codperi = sno_thcargo.codperi ".
				       "           AND sno_thpersonalnomina.codcar = sno_thcargo.codcar) as descar ";
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_thpersonalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
					  "   	  sno_thpersonalnomina.codcueban, sno_thunidadadmin.desuniadm, sno_thunidadadmin.codprouniadm, MAX(sno_thpersonalnomina.sueperaux) AS sueper, ".
					  "		  sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, ".
					  "		  MAX(sno_thpersonalnomina.codgra) AS codgra, ".
  					  $ls_descar.
					  "  FROM sno_personal, sno_thpersonalnomina, sno_thsalida, sno_thunidadadmin ".
					  " WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
					  "   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
					  "   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
					  "   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
					  "	  AND sno_thpersonalnomina.staper = '2' ".
					  "   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					  "   AND sno_thsalida.codconc='".$ls_vac_codconvac."' ".
					  "   ".$ls_criteriounion.
					  "   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					  "   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					  "   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					  "   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					  "   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					  "   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
					  "   AND sno_personal.codper = sno_thpersonalnomina.codper ".
					  "   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
					  "   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
					  "   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
					  "   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
					  "   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
					  "   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
					  "   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
					  "   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
					  " GROUP BY sno_thpersonalnomina.codemp, sno_thpersonalnomina.codnom, sno_thpersonalnomina.codper, ".
					  "		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
					  "		   sno_thpersonalnomina.codcueban, sno_thunidadadmin.desuniadm, sno_thunidadadmin.desuniadm, ".
					  "		   sno_thunidadadmin.codprouniadm, sno_thpersonalnomina.codcar, sno_thpersonalnomina.codasicar, ".
					  "		   sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
					  "    	   sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm ";
		}
		$ls_sql="SELECT sno_thpersonalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		sno_thpersonalnomina.codcueban, sno_thunidadadmin.desuniadm, sno_thunidadadmin.codprouniadm, MAX(sno_thpersonalnomina.sueperaux) AS sueper, ".
				"		sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm, ".
			    "		  MAX(sno_thpersonalnomina.codgra) AS codgra, ".
				$ls_descar.
				"  FROM sno_personal, sno_thpersonalnomina, sno_thsalida, sno_thunidadadmin ".
				" WHERE sno_thpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_thpersonalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_thpersonalnomina.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thpersonalnomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_personal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_thpersonalnomina.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				" GROUP BY sno_thpersonalnomina.codemp, sno_thpersonalnomina.codnom, sno_thpersonalnomina.codper, ".
				"		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		   sno_thpersonalnomina.codcueban, sno_thunidadadmin.desuniadm, sno_thunidadadmin.desuniadm, ".
				"		   sno_thunidadadmin.codprouniadm, sno_thpersonalnomina.codcar, sno_thpersonalnomina.codasicar, ".
				"		   sno_thunidadadmin.minorguniadm, sno_thunidadadmin.ofiuniadm, sno_thunidadadmin.uniuniadm, ".
			    "    	   sno_thunidadadmin.depuniadm, sno_thunidadadmin.prouniadm ".
				"   ".$ls_union.
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_pagonominaunidad_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonominaunidad_conceptopersonal($as_codper,$as_conceptocero,$as_tituloconcepto,$as_conceptoreporte,$as_conceptop2)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonominaunidad_conceptopersonal
		//         Access: public (desde la clase sigesp_sno_rpp_pagonominaunidadadmin)  
		//	    Arguments: as_codper // Código del personal que se desea buscar la salida
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos en cero
		//	  			   as_tituloconcepto // criterio que me indica si se desea mostrar el título del concepto ó el nombre
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos tipo reporte
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados al personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_campo="sno_thconcepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_thconcepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "AND sno_thsalida.valsal<>0 ";
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3' OR ".
											"	   sno_thsalida.tipsal='P2' OR sno_thsalida.tipsal='V4' OR sno_thsalida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_thsalida.tipsal='A' OR sno_thsalida.tipsal='V1' OR sno_thsalida.tipsal='W1' OR ".
											"	   sno_thsalida.tipsal='D' OR sno_thsalida.tipsal='V2' OR sno_thsalida.tipsal='W2' OR ".
											"      sno_thsalida.tipsal='P1' OR sno_thsalida.tipsal='V3' OR sno_thsalida.tipsal='W3')";
			}
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_thconcepto.codconc, ".$ls_campo." as nomcon, (sno_thsalida.valsalaux) AS valsal, sno_thsalida.tipsal ".
					  "  FROM sno_thsalida, sno_thconcepto, sno_thpersonalnomina ".
					  " WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
					  "   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
					  "   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
					  "   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					  "   AND sno_thsalida.codper='".$as_codper."'".
					  "   AND sno_thsalida.codconc='".$ls_vac_codconvac."'".
					  "   AND sno_thpersonalnomina.staper = '2' ".
					  "   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
					  "   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
					  "   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
					  "   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
					  "   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					  "   AND sno_thsalida.codemp = sno_thpersonalnomina.codemp ".
					  "   AND sno_thsalida.codnom = sno_thpersonalnomina.codnom ".
					  "   AND sno_thsalida.anocur = sno_thpersonalnomina.anocur ".
					  "   AND sno_thsalida.codperi = sno_thpersonalnomina.codperi ".
					  "   AND sno_thsalida.codper = sno_thpersonalnomina.codper ";
		}
		$ls_sql="SELECT sno_thconcepto.codconc, ".$ls_campo." as nomcon,  (sno_thsalida.valsalaux) AS valsal, sno_thsalida.tipsal ".
				"  FROM sno_thsalida, sno_thconcepto ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thsalida.codper='".$as_codper."'".
				"   ".$ls_criterio.
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   ".$ls_union.
				" ORDER BY sno_thconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonominaunidad_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle2->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_pagonominaunidad_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>