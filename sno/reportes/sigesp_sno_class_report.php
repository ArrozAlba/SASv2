<?php
class sigesp_sno_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_class_report()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_class_report
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
		$this->DS_asigna=new class_datastore();
		$this->DS_pension=new class_datastore();
		$this->DS_pension2=new class_datastore();		
		$this->rs_data="";
		$this->rs_data_detalle="";
		$this->rs_data_detalle2="";
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
        $this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$this->li_rac=$_SESSION["la_nomina"]["racnom"];
	}// end function sigesp_sno_class_report
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
	function uf_prenomina_personal($as_codperdes,$as_codperhas,$as_subnomdes,$as_subnomhas,$as_orden)
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
			$ls_criterio= "AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
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
				"  FROM sno_personal, sno_personalnomina, sno_prenomina, sno_concepto ".
				" WHERE sno_prenomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_prenomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_prenomina.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio." ".
				"   AND sno_prenomina.codemp = sno_concepto.codemp ".
				"   AND sno_prenomina.codnom = sno_concepto.codnom ".
				"   AND sno_prenomina.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_prenomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_prenomina.codnom ".
				"   AND sno_personalnomina.codper = sno_prenomina.codper ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				" GROUP BY sno_personal.codper, sno_personal.nomper, sno_personal.apeper ".
				"   ".$ls_orden;
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_prenomina_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
			$ls_criterio = "AND sno_prenomina.valprenom <> 0 ";
		}
		if(empty($as_conceptop2))
		{
			$ls_criterio = $ls_criterio." AND (sno_prenomina.tipprenom<>'P2' AND sno_prenomina.tipprenom<>'V4' AND sno_prenomina.tipprenom<>'W4')";
		}
		$ls_sql="SELECT sno_prenomina.codconc, sno_concepto.nomcon, sno_prenomina.tipprenom, sno_prenomina.valprenom, sno_prenomina.valhis ".
				"  FROM sno_prenomina, sno_concepto ".
				" WHERE sno_prenomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_prenomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_prenomina.codperi='".$this->ls_peractnom."' ".
				"   AND sno_prenomina.codper='".$as_codper."' ".
				"     ".$ls_criterio.
				"   AND sno_prenomina.codemp = sno_concepto.codemp ".
				"   AND sno_prenomina.codnom = sno_concepto.codnom ".
				"   AND sno_prenomina.codconc = sno_concepto.codconc ".
				" ORDER BY sno_prenomina.codconc ";
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_prenomina_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_prenomina_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonomina_personal ($as_codperdes,$as_codperhas,$as_conceptocero,$as_conceptoreporte,$as_conceptop2,$as_codubifis,
										$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_subnomdes,$as_subnomhas,$as_orden)
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
		// Fecha Creación: 01/02/2006 						Fecha Última Modificación :		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_criteriounion="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personalnomina.codper>='".$as_codperdes."'";
			$ls_criteriounion=" AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
			$ls_criteriounion = $ls_criteriounion."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
			$ls_criteriounion= $ls_criteriounion."    AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
			$ls_criteriounion= $ls_criteriounion."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_codubifis))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codubifis='".$as_codubifis."'";
			$ls_criteriounion = $ls_criteriounion." AND sno_personalnomina.codubifis='".$as_codubifis."'";
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
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codpar='".$as_codpar."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codpar='".$as_codpar."'";
			}
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR ".
											"	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
			}
		}
		if(empty($as_orden))
		{
			$ls_orden=" ORDER BY codper ";
		}
		else
		{
			switch($as_orden)
			{
				case "1": // Ordena por unidad administrativa
					$ls_orden=" ORDER BY minorguniadm, ofiuniadm, uniuniadm, ".
							  "    	     depuniadm, prouniadm, codper ";
					break;

				case "2": // Ordena por Código de personal
					$ls_orden=" ORDER BY codper ";
					break;

				case "3": // Ordena por Apellido de personal
					$ls_orden=" ORDER BY apeper ";
					break;

				case "4": // Ordena por Nombre de personal
					$ls_orden=" ORDER BY nomper ";
					break;
			}
		}
		if($this->li_rac=="1") // Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar ";
		}
		else // No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ";
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_personalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
					  "		  sno_personal.fecingper, sno_personalnomina.fecculcontr, sno_personalnomina.fecingper as fecingnom,".
					  "       sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_personal.fecegrper, ".
					  "       sno_personal.fecsitu, sno_personal.fecnacper, MAX(sno_personalnomina.descasicar) AS descasicar, ".
					  "		  sno_unidadadmin.codprouniadm, MAX(sno_personalnomina.sueper) AS sueper, ".
					  "		  sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
					  "       sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, MAX(sno_personalnomina.codgra) AS codgra, ".
					  "       MAX(sno_personal.nacper) AS nacper,  ".
					  "       MAX(sno_ubicacionfisica.desubifis) AS desubifis,".
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
					  "		  (SELECT SUM(asires) FROM sno_resumen ".
					  "			WHERE sno_resumen.codemp = sno_salida.codemp ".
					  "			 AND sno_resumen.codnom = sno_salida.codnom ".
					  "			 AND sno_resumen.codperi = sno_salida.codperi) AS totalasignacion, ".
					  "		  (SELECT SUM(dedres + apoempres) FROM sno_resumen ".
					  "			WHERE sno_resumen.codemp = sno_salida.codemp ".
					  "			 AND sno_resumen.codnom = sno_salida.codnom ".
					  "			 AND sno_resumen.codperi = sno_salida.codperi) AS totaldeduccion, ".
					  "		  (SELECT SUM(apopatres) FROM sno_resumen ".
					  "			WHERE sno_resumen.codemp = sno_salida.codemp ".
					  "			 AND sno_resumen.codnom = sno_salida.codnom ".
					  "			 AND sno_resumen.codperi = sno_salida.codperi) AS totalaporte, ".
					  "		 (SELECT sno_componente.descom FROM sno_componente ".
					  "        WHERE sno_componente.codemp='".$this->ls_codemp."'".
					  "          AND sno_componente.codcom=sno_personal.codcom) AS dencom, ".
					  "		 (SELECT sno_rango.desran FROM sno_rango ".
					  "        WHERE sno_rango.codemp='".$this->ls_codemp."'".
					  "          AND sno_rango.codcom=sno_personal.codcom".
					  "          AND sno_rango.codran=sno_personal.codran) AS denran, MAX(sno_personal.situacion) AS situacion, ".
					  "        (SELECT sno_causales.dencausa FROM sno_causales WHERE sno_causales.codemp='".$this->ls_codemp."'".
				      "            AND sno_causales.codcausa=sno_personal.codcausa) AS dencausa, ".
  					  $ls_descar.
					  "  FROM sno_personal, sno_personalnomina, sno_salida, sno_unidadadmin, sno_ubicacionfisica ".
					  " WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					  "   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					  "	  AND sno_personalnomina.staper = '2' ".
					  "   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					  "   AND sno_salida.codconc='".$ls_vac_codconvac."' ".
					  "   ".$ls_criteriounion.
					  "   AND sno_personalnomina.codemp = sno_salida.codemp ".
					  "   AND sno_personalnomina.codnom = sno_salida.codnom ".
					  "   AND sno_personalnomina.codper = sno_salida.codper ".
					  "   AND sno_personal.codemp = sno_personalnomina.codemp ".
					  "   AND sno_personal.codper = sno_personalnomina.codper ".
					  "   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
					  "   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
					  "   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
					  "   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
					  "   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
					  "   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
					  "   AND sno_ubicacionfisica.codemp = sno_personalnomina.codemp ".
					  "	  AND sno_ubicacionfisica.codubifis = sno_personalnomina.codubifis ".
					  " GROUP BY sno_personalnomina.codemp, sno_salida.codemp, sno_personalnomina.codnom, sno_salida.codnom, sno_salida.codperi, sno_personalnomina.codper, ".
					  "		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
					  "        sno_personalnomina.fecculcontr, sno_personalnomina.fecingper, ".
					  "		   sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_unidadadmin.desuniadm, ".
					  "		   sno_unidadadmin.codprouniadm, sno_personalnomina.codcar, sno_personalnomina.codasicar, ".
					  "		   sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
					  "    	   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_ubicacionfisica.codpai, ".
					  "        sno_ubicacionfisica.codest,sno_ubicacionfisica.codmun,sno_ubicacionfisica.codpar, ".
					  "        sno_personal.codcom,sno_personal.codran, sno_personal.cauegrper, sno_personal.codcausa,".
					  "        sno_personal.fecegrper, sno_personal.fecsitu, sno_personal.fecnacper ";
		}
		$ls_sql="SELECT sno_personalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
				"		sno_personal.fecingper, sno_personalnomina.fecculcontr, sno_personalnomina.fecingper as fecingnom, ".
				"       sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_personal.fecegrper, sno_personal.fecsitu, ".
				"       sno_personal.fecnacper, MAX(sno_personalnomina.descasicar) AS descasicar, ".
				"		sno_unidadadmin.codprouniadm, MAX(sno_personalnomina.sueper) AS sueper, sno_unidadadmin.minorguniadm, ".
				"		sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, ".
				"       MAX(sno_personalnomina.codgra) AS codgra, MAX(sno_personal.nacper) AS nacper, ".
				"       MAX(sno_ubicacionfisica.desubifis) AS desubifis, ".
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
				"		  (SELECT SUM(asires) FROM sno_resumen ".
				"			WHERE sno_resumen.codemp = sno_salida.codemp ".
				"			 AND sno_resumen.codnom = sno_salida.codnom ".
				"			 AND sno_resumen.codperi = sno_salida.codperi) AS totalasignacion, ".
				"		  (SELECT SUM(dedres + apoempres) FROM sno_resumen ".
				"			WHERE sno_resumen.codemp = sno_salida.codemp ".
				"			 AND sno_resumen.codnom = sno_salida.codnom ".
				"			 AND sno_resumen.codperi = sno_salida.codperi) AS totaldeduccion, ".
				"		  (SELECT SUM(apopatres) FROM sno_resumen ".
				"			WHERE sno_resumen.codemp = sno_salida.codemp ".
				"			 AND sno_resumen.codnom = sno_salida.codnom ".
				"			 AND sno_resumen.codperi = sno_salida.codperi) AS totalaporte, ".
			    "		 (SELECT sno_componente.descom FROM sno_componente ".
				"          WHERE sno_componente.codemp='".$this->ls_codemp."'".
				"            AND sno_componente.codcom=sno_personal.codcom) AS dencom, ".
				"		 (SELECT sno_rango.desran FROM sno_rango ".
			    "        WHERE sno_rango.codemp='".$this->ls_codemp."'".
				"          AND sno_rango.codcom=sno_personal.codcom".
				"          AND sno_rango.codran=sno_personal.codran) AS denran, MAX(sno_personal.situacion) AS situacion, ".
				"        (SELECT sno_causales.dencausa FROM sno_causales WHERE sno_causales.codemp='".$this->ls_codemp."'".
				"            AND sno_causales.codcausa=sno_personal.codcausa) AS dencausa, ".
				$ls_descar.
				"  FROM sno_personal, sno_personalnomina, sno_salida, sno_unidadadmin, sno_ubicacionfisica ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_ubicacionfisica.codemp = sno_personalnomina.codemp ".				
				"	AND sno_ubicacionfisica.codubifis = sno_personalnomina.codubifis ".
				" GROUP BY sno_personalnomina.codemp, sno_salida.codemp, sno_personalnomina.codnom, sno_salida.codnom, sno_salida.codperi, sno_personalnomina.codper, ".
				"		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"          sno_personalnomina.fecculcontr, sno_personalnomina.fecingper, ".
				"		   sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_unidadadmin.desuniadm, ".
				"		   sno_unidadadmin.codprouniadm, sno_personalnomina.codcar, sno_personalnomina.codasicar, ".
				"		   sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
			    "    	   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_ubicacionfisica.codpai,  ".
				"          sno_ubicacionfisica.codest,sno_ubicacionfisica.codmun,sno_ubicacionfisica.codpar,".
				"          sno_personal.codcom,sno_personal.codran, sno_personal.codcausa, ".
				"          sno_personal.fecegrper, sno_personal.fecsitu, sno_personal.fecnacper ".
				"   ".$ls_union.
				"   ".$ls_orden;  
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
				print $this->io_sql->message;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_pagonomina_personal

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonomina_personal_pensionado($as_codperdes,$as_codperhas,$as_conceptocero,$as_conceptoreporte,
	                                           $as_conceptop2,$as_codubifis,
									           $as_codpai,$as_codest,$as_codmun,$as_codpar,$as_subnomdes,$as_subnomhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pagonomina_personal_pensionado
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos cuyo valor es cero
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos tipo reporte
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que se le calculó la nómina
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/09/2008 							Fecha Última Modificación :		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_criteriounion="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personalnomina.codper>='".$as_codperdes."'";
			$ls_criteriounion=" AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
			$ls_criteriounion = $ls_criteriounion."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
			$ls_criteriounion= $ls_criteriounion."    AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
			$ls_criteriounion= $ls_criteriounion."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_codubifis))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codubifis='".$as_codubifis."'";
			$ls_criteriounion = $ls_criteriounion." AND sno_personalnomina.codubifis='".$as_codubifis."'";
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
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codpar='".$as_codpar."'";
				$ls_criteriounion = $ls_criteriounion." AND sno_ubicacionfisica.codpar='".$as_codpar."'";
			}
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR ".
											"	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
			}
		}
		if(empty($as_orden))
		{
			$ls_orden=" ORDER BY codper ";
		}
		else
		{
			switch($as_orden)
			{
				case "1": // Ordena por unidad administrativa
					$ls_orden=" ORDER BY minorguniadm, ofiuniadm, uniuniadm, ".
							  "    	     depuniadm, prouniadm, codper ";
					break;

				case "2": // Ordena por Código de personal
					$ls_orden=" ORDER BY codper ";
					break;

				case "3": // Ordena por Apellido de personal
					$ls_orden=" ORDER BY apeper ";
					break;

				case "4": // Ordena por Nombre de personal
					$ls_orden=" ORDER BY nomper ";
					break;
			}
		}
		if($this->li_rac=="1") // Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar ";
		}
		else // No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ";
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_personalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
					  "		  sno_personal.fecingper, sno_personalnomina.fecculcontr, sno_personalnomina.fecingper as fecingnom,".
					  "       sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_personal.fecegrper, ".
					  "       sno_personal.fecsitu, sno_personal.fecnacper, ".
					  "		  sno_unidadadmin.codprouniadm, MAX(sno_personalnomina.sueper) AS sueper, ".
					  "		  sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
					  "       sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, MAX(sno_personalnomina.codgra) AS codgra, ".
					  "       MAX(sno_personal.nacper) AS nacper,  ".
					  "       MAX(sno_ubicacionfisica.desubifis) AS desubifis,".
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
					  "		  (SELECT SUM(asires) FROM sno_resumen ".
					  "			WHERE sno_resumen.codemp = sno_salida.codemp ".
					  "			 AND sno_resumen.codnom = sno_salida.codnom ".
					  "			 AND sno_resumen.codperi = sno_salida.codperi) AS totalasignacion, ".
					  "		  (SELECT SUM(dedres + apoempres) FROM sno_resumen ".
					  "			WHERE sno_resumen.codemp = sno_salida.codemp ".
					  "			 AND sno_resumen.codnom = sno_salida.codnom ".
					  "			 AND sno_resumen.codperi = sno_salida.codperi) AS totaldeduccion, ".
					  "		  (SELECT SUM(apopatres) FROM sno_resumen ".
					  "			WHERE sno_resumen.codemp = sno_salida.codemp ".
					  "			 AND sno_resumen.codnom = sno_salida.codnom ".
					  "			 AND sno_resumen.codperi = sno_salida.codperi) AS totalaporte, ".
					  "		 (SELECT sno_componente.descom FROM sno_componente ".
					  "        WHERE sno_componente.codemp='".$this->ls_codemp."'".
					  "          AND sno_componente.codcom=sno_personal.codcom) AS dencom, ".
					  "		 (SELECT sno_rango.desran FROM sno_rango ".
					  "        WHERE sno_rango.codemp='".$this->ls_codemp."'".
					  "          AND sno_rango.codcom=sno_personal.codcom".
					  "          AND sno_rango.codran=sno_personal.codran) AS denran, sno_personal.situacion, ".
					  "        (SELECT sno_causales.dencausa FROM sno_causales WHERE sno_causales.codemp='".$this->ls_codemp."'".
				      "            AND sno_causales.codcausa=sno_personal.codcausa) AS dencausa, ".
  					  $ls_descar.
					  "  FROM sno_personal, sno_personalnomina, sno_salida, sno_unidadadmin, sno_ubicacionfisica ".
					  " WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					  "   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					  "	  AND sno_personalnomina.staper = '2' ".
					  "   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					  "   AND sno_salida.codconc='".$ls_vac_codconvac."' ".
					  "   ".$ls_criteriounion.
					  "   AND sno_personalnomina.codemp = sno_salida.codemp ".
					  "   AND sno_personalnomina.codnom = sno_salida.codnom ".
					  "   AND sno_personalnomina.codper = sno_salida.codper ".
					  "   AND sno_personal.codemp = sno_personalnomina.codemp ".
					  "   AND sno_personal.codper = sno_personalnomina.codper ".
					  "   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
					  "   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
					  "   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
					  "   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
					  "   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
					  "   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
					  "   AND sno_ubicacionfisica.codemp = sno_personalnomina.codemp ".
					  "	  AND sno_ubicacionfisica.codubifis = sno_personalnomina.codubifis ".
					  " GROUP BY sno_personalnomina.codemp, sno_salida.codemp, sno_personalnomina.codnom, sno_salida.codnom, sno_salida.codperi, sno_personalnomina.codper, ".
					  "		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
					  "        sno_personalnomina.fecculcontr, sno_personalnomina.fecingper, ".
					  "		   sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_unidadadmin.desuniadm, ".
					  "		   sno_unidadadmin.codprouniadm, sno_personalnomina.codcar, sno_personalnomina.codasicar, ".
					  "		   sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
					  "    	   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_ubicacionfisica.codpai, ".
					  "        sno_ubicacionfisica.codest,sno_ubicacionfisica.codmun,sno_ubicacionfisica.codpar, ".
					  "        sno_personal.codcom,sno_personal.codran, sno_personal.cauegrper, sno_personal.codcausa,".
					  "        sno_personal.fecegrper, sno_personal.fecsitu, sno_personal.fecnacper ";
		}
		$ls_sql="SELECT sno_personalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
				"		sno_personal.fecingper, sno_personalnomina.fecculcontr, sno_personalnomina.fecingper as fecingnom, ".
				"       sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_personal.fecegrper, sno_personal.fecsitu, ".
				"       sno_personal.fecnacper, ".
				"		sno_unidadadmin.codprouniadm, MAX(sno_personalnomina.sueper) AS sueper, sno_unidadadmin.minorguniadm, ".
				"		sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, ".
				"       MAX(sno_personalnomina.codgra) AS codgra, MAX(sno_personal.nacper) AS nacper, ".
				"       MAX(sno_ubicacionfisica.desubifis) AS desubifis, ".
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
				"		  (SELECT SUM(asires) FROM sno_resumen ".
				"			WHERE sno_resumen.codemp = sno_salida.codemp ".
				"			 AND sno_resumen.codnom = sno_salida.codnom ".
				"			 AND sno_resumen.codperi = sno_salida.codperi) AS totalasignacion, ".
				"		  (SELECT SUM(dedres + apoempres) FROM sno_resumen ".
				"			WHERE sno_resumen.codemp = sno_salida.codemp ".
				"			 AND sno_resumen.codnom = sno_salida.codnom ".
				"			 AND sno_resumen.codperi = sno_salida.codperi) AS totaldeduccion, ".
				"		  (SELECT SUM(apopatres) FROM sno_resumen ".
				"			WHERE sno_resumen.codemp = sno_salida.codemp ".
				"			 AND sno_resumen.codnom = sno_salida.codnom ".
				"			 AND sno_resumen.codperi = sno_salida.codperi) AS totalaporte, ".
			    "		 (SELECT sno_componente.descom FROM sno_componente ".
				"          WHERE sno_componente.codemp='".$this->ls_codemp."'".
				"            AND sno_componente.codcom=sno_personal.codcom) AS dencom, ".
				"		 (SELECT sno_rango.desran FROM sno_rango ".
			    "        WHERE sno_rango.codemp='".$this->ls_codemp."'".
				"          AND sno_rango.codcom=sno_personal.codcom".
				"          AND sno_rango.codran=sno_personal.codran) AS denran, sno_personal.situacion, ".
				"        (SELECT sno_causales.dencausa FROM sno_causales WHERE sno_causales.codemp='".$this->ls_codemp."'".
				"            AND sno_causales.codcausa=sno_personal.codcausa) AS dencausa, ".
				$ls_descar.
				"  FROM sno_personal, sno_personalnomina, sno_salida, sno_unidadadmin, sno_ubicacionfisica ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_ubicacionfisica.codemp = sno_personalnomina.codemp ".				
				"	AND sno_ubicacionfisica.codubifis = sno_personalnomina.codubifis ".
				" GROUP BY sno_personalnomina.codemp, sno_salida.codemp, sno_personalnomina.codnom, sno_salida.codnom, sno_salida.codperi, sno_personalnomina.codper, ".
				"		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"          sno_personalnomina.fecculcontr, sno_personalnomina.fecingper, ".
				"		   sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_unidadadmin.desuniadm, ".
				"		   sno_unidadadmin.codprouniadm, sno_personalnomina.codcar, sno_personalnomina.codasicar, ".
				"		   sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
			    "    	   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_ubicacionfisica.codpai,  ".
				"          sno_ubicacionfisica.codest,sno_ubicacionfisica.codmun,sno_ubicacionfisica.codpar,".
				"          sno_personal.codcom,sno_personal.codran, sno_personal.codcausa, ".
				"          sno_personal.fecegrper, sno_personal.situacion, sno_personal.fecsitu, sno_personal.fecnacper ".
				"   ".$ls_union.
				"   ".$ls_orden;  
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_personal_pensionado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_pagonomina_personal_pensionado
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
		$ls_campo="sno_concepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_concepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR ".
											"	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
			}
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_concepto.codconc, ".$ls_campo." as nomcon, sno_salida.valsal, sno_salida.tipsal, sno_concepto.frevarcon, sno_concepto.repconsunicon,sno_concepto.consunicon ".
					  "  FROM sno_salida, sno_concepto, sno_personalnomina ".
					  " WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					  "   AND sno_salida.codnom='".$this->ls_codnom."' ".
					  "   AND sno_salida.codperi='".$this->ls_peractnom."'".
					  "   AND sno_salida.codper='".$as_codper."'".
					  "   AND sno_salida.codconc='".$ls_vac_codconvac."'".
					  "   AND sno_personalnomina.staper = '2' ".
					  "   AND sno_salida.codemp = sno_concepto.codemp ".
					  "   AND sno_salida.codnom = sno_concepto.codnom ".
					  "   AND sno_salida.codconc = sno_concepto.codconc ".
					  "   AND sno_salida.codemp = sno_personalnomina.codemp ".
					  "   AND sno_salida.codnom = sno_personalnomina.codnom ".
					  "   AND sno_salida.codper = sno_personalnomina.codper ";
		}
		$ls_sql="SELECT sno_concepto.codconc, ".$ls_campo." as nomcon, sno_salida.valsal, sno_salida.tipsal, sno_concepto.frevarcon, sno_concepto.repconsunicon,sno_concepto.consunicon ".
				"  FROM sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."'".
				"   AND sno_salida.codper='".$as_codper."'".
				"   ".$ls_criterio.
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   ".$ls_union.
				" ORDER BY codconc, tipsal  ";
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   ".$as_sigcon." ".
				"   AND codconc IN (SELECT codconc FROM sno_salida WHERE codemp='".$this->ls_codemp."' AND codnom='".$this->ls_codnom."')".
				" ORDER BY codconc "; 
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_concepto_excel ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
		$ls_campo="sno_concepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_concepto.titcon";
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_concepto.codconc, MAX(".$ls_campo.") as nomcon, SUM(sno_salida.valsal) as valsal, MAX(sno_salida.tipsal) AS tipsal ".
					  "  FROM sno_salida, sno_concepto, sno_personalnomina ".
					  " WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					  "   AND sno_salida.codnom='".$this->ls_codnom."' ".
					  "   AND sno_salida.codperi='".$this->ls_peractnom."'".
					  "   AND sno_salida.codper='".$as_codper."'".
					  "   AND sno_salida.codconc='".$ls_vac_codconvac."'".
					  "   AND sno_personalnomina.staper = '2' ".
					  "   AND sno_salida.codemp = sno_concepto.codemp ".
					  "   AND sno_salida.codnom = sno_concepto.codnom ".
					  "   AND sno_salida.codconc = sno_concepto.codconc ".
					  "   AND sno_salida.codemp = sno_personalnomina.codemp ".
					  "   AND sno_salida.codnom = sno_personalnomina.codnom ".
					  "   AND sno_salida.codper = sno_personalnomina.codper ".
					  " GROUP BY sno_concepto.codconc ";
		}
		$ls_sql="SELECT sno_concepto.codconc, MAX(".$ls_campo.") as nomcon, SUM(sno_salida.valsal) as valsal, MAX(sno_salida.tipsal) AS tipsal ".
				"  FROM sno_concepto, sno_salida ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."'".
				"   AND sno_salida.codper='".$as_codper."'".
				"   ".$as_tipsal.
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				" GROUP BY sno_concepto.codconc ".
				"   ".$ls_union.
				" ORDER BY codconc, tipsal ";
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_conceptopersonal_excel ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
				"  FROM sno_prestamos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
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
	function uf_listadoconcepto_conceptos($as_codconcdes,$as_codconchas,$as_codperdes,$as_codperhas,$as_coduniadm,$as_conceptocero,
										  $as_subnomdes,$as_subnomhas,$as_codente)
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
			$ls_criterio= "AND sno_concepto.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_concepto.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		if(!empty($as_codente))
		{
			$ls_criterio= $ls_criterio." AND sno_concepto.codente='".$as_codente."'";
		}
		$ls_sql="SELECT sno_concepto.codconc, sno_concepto.nomcon, count(sno_salida.codper) as total, sum(sno_salida.valsal) as monto ".
				"  FROM sno_personalnomina, sno_salida, sno_concepto, sno_resumen ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0 ".
				"   ".$ls_criterio.
				"   AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
				"		 sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
				"		 sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3') ".
				"   AND sno_salida.codemp = sno_resumen.codemp ".
				"   AND sno_salida.codnom = sno_resumen.codnom ".
				"   AND sno_salida.codperi = sno_resumen.codperi ".
				"   AND sno_salida.codper = sno_resumen.codper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				" GROUP BY sno_concepto.codconc, sno_concepto.nomcon  ".
				" ORDER BY sno_concepto.codconc ";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoconcepto_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadoconcepto_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoconcepto_personalconcepto($as_codconc,$as_codperdes,$as_codperhas,$as_conceptocero,$as_coduniadm,$as_subnomdes,$as_subnomhas,$as_orden)
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
		$this->io_sql=new class_sql($this->io_conexion);	
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= "AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY  sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY  sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;
		}
		if($this->li_rac=="1")// Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar  ";
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, sno_salida.valsal, ".$ls_descar.	          
				"  FROM sno_personal, sno_personalnomina, sno_salida                                                                      ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.codconc='".$as_codconc."' ".
				"   ".$ls_criterio.
				"   AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
				"		 sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
				"		 sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3') ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"   ".$ls_orden; 
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoconcepto_personalconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;

	}// end function uf_listadoconcepto_personalconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadoconcepto_personalmilitar($as_codconc,$as_codperdes,$as_codperhas,$as_conceptocero,$as_coduniadm,$as_subnomdes,$as_subnomhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoconcepto_personalmilitar
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
		$this->io_sql=new class_sql($this->io_conexion);	
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= "AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY  sno_personal.codcom,sno_personal.codran,sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.codcom,sno_personal.codran,sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY  sno_personal.codcom,sno_personal.codran,sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.codcom,sno_personal.codran,sno_personal.codper ";
				break;
		}
		if($this->li_rac=="1")// Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar, ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar, ";
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, sno_salida.valsal, ".$ls_descar.
		        "       sno_componente.descom, sno_rango.desran                                         ".	        
				"  FROM sno_personal                                                                      ".
                "  JOIN sno_personalnomina ON (sno_personal.codemp=sno_personal.codemp                    ".
                "                         AND sno_personalnomina.codper=sno_personal.codper)              ".
                "  JOIN sno_salida ON (sno_personalnomina.codemp = sno_salida.codemp                      ".
		        "                  AND sno_personalnomina.codnom = sno_salida.codnom                      ". 
		        "                  AND sno_personalnomina.codper = sno_salida.codper )                    ".
                "  LEFT JOIN sno_componente ON (sno_componente.codemp=sno_componente.codemp               ".
                "                          AND sno_componente.codcom=sno_personal.codcom)                 ".
                "  LEFT JOIN sno_rango ON (sno_rango.codemp=sno_personal.codemp                           ".
                "   AND sno_rango.codcom=sno_personal.codcom                                              ".
                "   AND sno_rango.codran=sno_personal.codran)                                             ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.codconc='".$as_codconc."' ".
				"   ".$ls_criterio.
				"   AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
				"		 sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
				"		 sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3') ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"   ".$ls_orden; 
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadoconcepto_personalmilitar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;

	}// end function uf_listadoconcepto_personalmilitar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_aportepatronal_personal($as_codconc,$as_conceptocero,$as_subnomdes,$as_subnomhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_aportepatronal_personal
		//		   Access: public (desde la clase sigesp_sno_rpp_listadonomina)  
		//	    Arguments: as_codconc // Código del concepto del que se desea busca el personal
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
		$ls_group=",";
		if(!empty($as_codconc))
		{
			$ls_criterio = $ls_criterio." AND sno_salida.codconc='".$as_codconc."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
			$ls_group=",sno_personalnomina.codsubnom,";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
			$ls_group=",sno_personalnomina.codsubnom,";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personalnomina.codper ";
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
				"       (SELECT SUM(valsal) ".
				"		   FROM sno_salida ".
				"   	  WHERE sno_salida.codperi='".$this->ls_peractnom."' ".
				"   		AND (sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR sno_salida.tipsal='Q1') ".
				"			".$ls_criterio.
				"           AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   		AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   		AND sno_personalnomina.codper = sno_salida.codper) as personal, ".
				"       (SELECT SUM(valsal) ".
				"		   FROM sno_salida ".
				"   	  WHERE sno_salida.codperi='".$this->ls_peractnom."' ".
				"   		AND (sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR sno_salida.tipsal='Q2') ".
				"			".$ls_criterio.
				"			AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   		AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   		AND sno_personalnomina.codper = sno_salida.codper) as patron ".
				"  FROM sno_personal, sno_personalnomina, sno_salida ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')  ".
				"  	AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"	".$ls_criterio.
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"	AND sno_personalnomina.codemp = sno_salida.codemp ".
				"	AND sno_personalnomina.codnom = sno_salida.codnom ".
				"	AND sno_personalnomina.codper = sno_salida.codper ".
				" GROUP BY sno_personalnomina.codemp,sno_personalnomina.codnom ".$ls_group." sno_personalnomina.codper, ".
				"		   sno_personal.cedper, sno_personal.apeper, sno_personal.nomper ".
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
	function uf_resumenconcepto_conceptos($as_codconcdes,$as_codconchas,$as_aportepatronal,$as_conceptocero,$as_subnomdes,$as_subnomhas,&$rs_data)
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
			$ls_criterio= "AND sno_concepto.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_concepto.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_aportepatronal))
		{
			$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
										"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
										"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
										"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4')";
		}
		else
		{
			$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
										"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
										"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
		}
		$ls_sql="SELECT sno_concepto.codconc, MAX(sno_concepto.nomcon) AS nomcon, sno_salida.tipsal, sum(sno_salida.valsal) as monto, ".
				"		COUNT(sno_salida.codper) AS total, MAX(sno_concepto.cueprecon) AS cueprecon, MAX(sno_concepto.cueprepatcon) AS cueprepatcon  ".
				"  FROM sno_salida, sno_concepto, sno_personalnomina ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_salida.codemp = sno_personalnomina.codemp ".
				"   AND sno_salida.codnom = sno_personalnomina.codnom ".
				"   AND sno_salida.codper = sno_personalnomina.codper ".
				" GROUP BY sno_concepto.codconc, sno_salida.tipsal ".
				" ORDER BY sno_concepto.codconc, sno_salida.tipsal ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_resumenconcepto_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_resumenconcepto_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_resumenconceptounidad_unidad($as_codconcdes,$as_codconchas,$as_coduniadm,$as_conceptocero,$as_subnomdes,$as_subnomhas)
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
			$ls_criterio= "   AND sno_salida.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_salida.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_minorguniadm=substr($as_coduniadm,0,4);
			$ls_ofiuniadm=substr($as_coduniadm,5,2);
			$ls_uniuniadm=substr($as_coduniadm,8,2);
			$ls_depuniadm=substr($as_coduniadm,11,2);
			$ls_prouniadm=substr($as_coduniadm,14,2);
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.minorguniadm = '".$ls_minorguniadm."' ".
										"   AND sno_personalnomina.ofiuniadm = '".$ls_ofiuniadm."' ".
										"   AND sno_personalnomina.uniuniadm = '".$ls_uniuniadm."' ".
										"   AND sno_personalnomina.depuniadm = '".$ls_depuniadm."' ".
										"   AND sno_personalnomina.prouniadm = '".$ls_prouniadm."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		$ls_sql="SELECT sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, sno_unidadadmin.depuniadm, ".
				"		sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm ".
				"  FROM sno_salida, sno_personalnomina, sno_unidadadmin ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
				"        sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
				"        sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
				"	     sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ".
				"   AND sno_salida.codemp = sno_personalnomina.codemp ".
				"   AND sno_salida.codnom = sno_personalnomina.codnom ".
				"   AND sno_salida.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, sno_unidadadmin.depuniadm, ".
				"		sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm ".
				" ORDER BY sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, sno_unidadadmin.depuniadm, ".
				"		sno_unidadadmin.prouniadm ";
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
	function uf_resumenconceptounidad_concepto($as_codconcdes,$as_codconchas,$as_coduniadm,$as_conceptocero,$as_subnomdes,$as_subnomhas,$as_orden)
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
			$ls_criterio= "AND sno_salida.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_salida.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Tipo de Salida y Código del Concepto
				$ls_orden="ORDER BY sno_salida.tipsal, sno_concepto.codconc ";
				break;

			case "2": // Ordena por Tipo de Salida y descripción del Concepto
				$ls_orden="ORDER BY sno_salida.tipsal,  sno_concepto.nomcon ";
				break;
		}
		$ls_sql="SELECT sno_concepto.codconc, MAX(sno_concepto.nomcon) AS nomcon, sno_salida.tipsal, sum(sno_salida.valsal) as monto, ".
				"		COUNT(sno_salida.codper) AS total, MAX(sno_concepto.cueprecon) AS cueprecon, MAX(sno_concepto.cueprepatcon) AS cueprepatcon  ".
				"  FROM sno_salida, sno_personalnomina, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_personalnomina.minorguniadm = '".$ls_minorguniadm."' ".
				"   AND sno_personalnomina.ofiuniadm = '".$ls_ofiuniadm."' ".
				"   AND sno_personalnomina.uniuniadm = '".$ls_uniuniadm."' ".
				"   AND sno_personalnomina.depuniadm = '".$ls_depuniadm."' ".
				"   AND sno_personalnomina.prouniadm = '".$ls_prouniadm."' ".
				"   ".$ls_criterio.
				"   AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
				"        sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
				"        sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
				"	     sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ".
				"   AND sno_salida.codemp = sno_personalnomina.codemp ".
				"   AND sno_salida.codnom = sno_personalnomina.codnom ".
				"   AND sno_salida.codper = sno_personalnomina.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				" GROUP BY sno_concepto.codconc, sno_salida.tipsal ".
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
	function uf_cuadrenomina_concepto($as_codconcdes,$as_codconchas,$as_conceptocero,$as_subnomdes,$as_subnomhas)
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
			$ls_criterio= "AND sno_salida.codconc>='".$as_codconcdes."'";
			$ls_hcriterio= "AND sno_hsalida.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_salida.codconc<='".$as_codconchas."'";
			$ls_hcriterio= $ls_hcriterio."   AND sno_hsalida.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
			$ls_hcriterio = $ls_hcriterio."   AND sno_hsalida.valsal<>0 ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
			$ls_hcriterio= $ls_hcriterio."   AND sno_hpersonalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
			$ls_hcriterio= $ls_hcriterio."   AND sno_hpersonalnomina.codsubnom<='".$as_subnomhas."'";
		}
		$lb_valido=$this->uf_cuadrenomina_periodo_previo($li_anoprev,$li_periprev);
		$ls_sql="SELECT sno_salida.codconc, sno_concepto.nomcon, sno_salida.tipsal, sum(COALESCE(sno_salida.valsal,0)) as actual, ".
				"		COALESCE((SELECT sum(COALESCE(sno_hsalida.valsal,0)) as previo ".
				"		   			FROM sno_hsalida,sno_hpersonalnomina ".
				"		 		   WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"					 AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"					 AND sno_hsalida.anocur='".$li_anoprev."' ".
				"					 AND sno_hsalida.codperi='".$li_periprev."' ".
				"   				 AND (sno_hsalida.tipsal='A' OR  sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1') ".
				"					 ".$ls_hcriterio.
				"   				 AND sno_hsalida.codemp=sno_salida.codemp ".
				"   				 AND sno_hsalida.codnom=sno_salida.codnom ".
				"   				 AND sno_hsalida.codconc=sno_salida.codconc ".
				"   				 AND sno_hsalida.tipsal=sno_salida.tipsal ".
				"   				 AND sno_hsalida.codemp = sno_hpersonalnomina.codemp ".
				"  					 AND sno_hsalida.codnom = sno_hpersonalnomina.codnom ".
				"  					 AND sno_hsalida.anocur = sno_hpersonalnomina.anocur ".
				"  					 AND sno_hsalida.codperi = sno_hpersonalnomina.codperi ".
				"   				 AND sno_hsalida.codper = sno_hpersonalnomina.codper ".
				" 				   GROUP BY sno_hsalida.codconc, sno_hsalida.tipsal),0) as previo ".
				"  FROM sno_salida, sno_concepto, sno_personalnomina ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal='A' OR  sno_salida.tipsal='V1' OR sno_salida.tipsal='W1') ".
				"   ".$ls_criterio.
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_salida.codemp = sno_personalnomina.codemp ".
				"   AND sno_salida.codnom = sno_personalnomina.codnom ".
				"   AND sno_salida.codper = sno_personalnomina.codper ".
				" GROUP BY sno_salida.codemp, sno_salida.codnom, sno_salida.codconc, sno_salida.tipsal, sno_concepto.nomcon ";
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
	function uf_listadopersonalcheque_unidad($as_codban,$as_suspendidos,$as_subnomdes,$as_subnomhas)
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
			$ls_criterio = $ls_criterio." AND sno_personalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		$ls_sql="SELECT sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
				"   	sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_resumen ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND sno_personalnomina.pagefeper=1 ".
				"   AND sno_personalnomina.pagbanper=0 ".
				"   AND sno_personalnomina.pagtaqper=0 ".
				"   AND sno_resumen.monnetres > 0 ".
				$ls_criterio. 
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"	AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				" GROUP BY sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
				"   	    sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm  ".
				" ORDER BY sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
				"   	    sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm ";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadopersonalcheque_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadopersonalcheque_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadopersonalcheque_personal($as_codban,$as_minorguniadm,$as_ofiuniadm,$as_uniuniadm,$as_depuniadm,
											   $as_prouniadm,$as_suspendidos,$as_quincena,$as_subnomdes,$as_subnomhas,$as_orden)
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
				$ls_monto="sno_resumen.priquires as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres as monnetres";
				break;
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_personalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
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
				"  FROM sno_personal, sno_personalnomina, sno_resumen ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_personalnomina.pagefeper='1' ".
				"   AND sno_personalnomina.pagbanper='0' ".
				"   AND sno_personalnomina.pagtaqper='0' ".
				"   AND sno_resumen.monnetres > 0 ".
				"	AND sno_personalnomina.minorguniadm = '".$as_minorguniadm."' ".
				"   AND sno_personalnomina.ofiuniadm = '".$as_ofiuniadm."' ".
				"   AND sno_personalnomina.uniuniadm = '".$as_uniuniadm."' ".
				"   AND sno_personalnomina.depuniadm = '".$as_depuniadm."' ".
				"   AND sno_personalnomina.prouniadm = '".$as_prouniadm."' ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				$ls_criterio.
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"	AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				$ls_orden;
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadopersonalcheque_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadopersonalcheque_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_banco($as_codban,$as_suspendidos,$as_sc_cuenta,$as_ctaban,$as_subnomdes,$as_subnomhas,$as_codperdes,$as_codperhas,$pago_otros_bancos='')
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
		if(!empty($as_codban) && (empty($pago_otros_bancos) || $pago_otros_bancos===false))
		{
			$ls_criterio = $ls_criterio." AND sno_personalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		$ls_sql="SELECT scb_banco.codban, scb_banco.nomban ".
				"  FROM sno_personalnomina, sno_resumen, scb_banco  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND (sno_personalnomina.pagbanper=1 OR sno_personalnomina.pagtaqper=1)".
				"   AND sno_personalnomina.pagefeper=0 ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"   AND sno_personalnomina.codemp = scb_banco.codemp ".
				"   AND sno_personalnomina.codban = scb_banco.codban ".
				" GROUP BY scb_banco.codban, scb_banco.nomban ".
				" ORDER BY scb_banco.nomban ";
		$this->rs_data=$this->io_sql->select($ls_sql);		
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_banco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if(!$this->rs_data->EOF)
			{
				$lb_valido=$this->uf_update_banco($as_codban,$as_sc_cuenta,$as_ctaban);	
			}
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
				"   AND codban='".$as_codban."' ";
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
	function uf_listadobanco_personal($as_codban,$as_suspendidos,$as_tipcueban,$as_quincena,$as_subnomdes,$as_subnomhas,$as_codperdes,$as_codperhas,$as_orden)
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
				$ls_monto="sno_resumen.priquires as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres as monnetres";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_personalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		switch($as_tipcueban)
		{
			case "A": // Cuenta de Ahorro
				$ls_criterio = $ls_criterio." AND sno_personalnomina.tipcuebanper='A' ";
				break;
				
			case "C": // Cuenta corriente
				$ls_criterio = $ls_criterio." AND sno_personalnomina.tipcuebanper='C' ";
				break;

			case "L": // Cuenta Activos Líquidos
				$ls_criterio = $ls_criterio." AND sno_personalnomina.tipcuebanper='L' ";
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
				
			case "5": // Ordena por Rango del Personal
				$ls_orden="ORDER BY  sno_personal.codran, sno_personal.codcom DESC";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".$ls_monto.", sno_personalnomina.codcueban, sno_personal.codran, ".
				 "		 (SELECT sno_rango.desran FROM sno_rango ".
				 "        WHERE sno_rango.codemp='".$this->ls_codemp."'".
				 "          AND sno_rango.codcom=sno_personal.codcom".
				 "          AND sno_rango.codran=sno_personal.codran) AS denran ".
				"  FROM sno_personal, sno_personalnomina, sno_resumen  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_personalnomina.pagbanper=1 ".
				"   AND sno_personalnomina.pagefeper=0 ".
				"   AND sno_personalnomina.pagtaqper=0 ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0 ".
				$ls_criterio.
				"	AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				$ls_orden;
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobanco_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobancotaquilla_personal($as_codban,$as_suspendidos,$as_quincena,$as_subnomdes,$as_subnomhas,$as_codperdes,$as_codperhas,$as_orden)
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
				$ls_monto="sno_resumen.priquires as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres as monnetres";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_personalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
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
				
			case "5": // Ordena por Rango del Personal
				$ls_orden="ORDER BY  sno_personal.codran, sno_personal.codcom DESC";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".$ls_monto.", sno_personalnomina.codcueban, sno_personal.codran, ".
				 "		 (SELECT sno_rango.desran FROM sno_rango ".
				 "        WHERE sno_rango.codemp='".$this->ls_codemp."'".
				 "          AND sno_rango.codcom=sno_personal.codcom".
				 "          AND sno_rango.codran=sno_personal.codran) AS denran ".
				 "  FROM sno_personal, sno_personalnomina, sno_resumen  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_personalnomina.pagbanper=0 ".
				"   AND sno_personalnomina.pagefeper=0 ".
				"   AND sno_personalnomina.pagtaqper=1 ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0 ".
				$ls_criterio.
				"	AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				$ls_orden;
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobancotaquilla_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobancotaquilla_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_recibopago_personal($as_codperdes,$as_codperhas,$as_coduniadm,$as_conceptocero,$as_conceptop2,$as_conceptoreporte,
									$as_codubifis,$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_subnomdes,$as_subnomhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibopago_personal
		//         Access: public (desde la clase sigesp_sno_r_recibopago)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_coduniadm // Código de la unidad administrativa	  
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
			$ls_criterio= "	AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_personalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_codubifis))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codubifis='".$as_codubifis."'";
		}
		else
		{
			if(!empty($as_codest))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codpai='".$as_codpai."'";
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codest='".$as_codest."'";
			}
			if(!empty($as_codmun))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codmun='".$as_codmun."'";
			}
			if(!empty($as_codpar))
			{
				$ls_criterio= $ls_criterio." AND sno_ubicacionfisica.codpar='".$as_codpar."'";
			}
		}

		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_conceptop2))
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR ".
											"  	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ";
			}
		}
		else
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"  	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
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
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar ";
					   
			$ls_codcar="       (SELECT   codasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as codcar, ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ";
					   
			$ls_codcar="       (SELECT codcar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as codcar, ";
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.nacper,".
				"		sno_personalnomina.codcueban, sno_personalnomina.tipcuebanper, sno_personal.fecingper, ".
				"       sno_personal.fecegrper, sno_personalnomina.fecingper as fecingnom,sno_personal.codorg, ".
				"       sno_personal.fecleypen, sum(sno_salida.valsal) as total, sno_unidadadmin.desuniadm, ".
				"	    sno_unidadadmin.minorguniadm,sno_unidadadmin.ofiuniadm,sno_unidadadmin.uniuniadm,sno_personal.dirper,".
				"       sno_unidadadmin.depuniadm,sno_tipopersonal.destipper,".
				"		sno_unidadadmin.prouniadm, MAX(sno_personalnomina.sueper) AS sueper, ".
				"       MAX(sno_personalnomina.pagbanper) AS pagbanper, ".
				"		MAX(sno_personalnomina.pagefeper) AS pagefeper, MAX(sno_ubicacionfisica.desubifis) AS desubifis,  ".
				"		MAX(sno_personalnomina.descasicar) AS descasicar, ".
				"		  (SELECT tipnom FROM sno_nomina ".
				"			WHERE sno_personalnomina.codnom = sno_nomina.codnom ".
				"			 AND sno_personalnomina.codemp = sno_nomina.codemp) AS tiponom, ".
				"		  (SELECT suemin FROM sno_clasificacionobrero ".
				"			WHERE sno_clasificacionobrero.grado = sno_personalnomina.grado ".
				"			 AND sno_clasificacionobrero.codemp = sno_personalnomina.codemp) AS sueobr, ".
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
				"		   WHERE scb_banco.codemp = sno_personalnomina.codemp ".
				" 			 AND scb_banco.codban = sno_personalnomina.codban) AS banco,".
				"		(SELECT  nomage FROM scb_agencias ".
				"		   WHERE scb_agencias.codemp = sno_personalnomina.codemp ".
				" 			 AND scb_agencias.codban = sno_personalnomina.codban ".
				"            AND scb_agencias.codage = sno_personalnomina.codage) AS agencia,".
				"       (SELECT sno_categoria_rango.descat FROM sno_rango, sno_categoria_rango   ".
                "         WHERE sno_rango.codemp=sno_personal.codemp                             ".
                "           AND sno_rango.codcom=sno_personal.codcom                             ".
                "     AND sno_rango.codran=sno_personal.codran                                   ".
                "     AND sno_categoria_rango.codcat=sno_rango.codcat) AS descat,                ".
				"		".$ls_codcar.$ls_descar.				
				"  FROM sno_personal, sno_personalnomina, sno_salida, sno_unidadadmin, sno_ubicacionfisica, sno_tipopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal<>'P2' AND sno_salida.tipsal<>'V4' AND sno_salida.tipsal<>'W4' ) ".
				"   ".$ls_criterio." ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				"   AND sno_personalnomina.codemp = sno_ubicacionfisica.codemp ".
				"   AND sno_personalnomina.codubifis = sno_ubicacionfisica.codubifis ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_tipopersonal.codtipper = sno_personalnomina.codtipper ".
				" GROUP BY sno_personal.codemp,sno_personal.codcom, sno_personal.codran, ".
			    "          sno_personalnomina.codemp, sno_personalnomina.codnom, sno_personal.codper, sno_personal.cedper, ".
				"		   sno_personal.nomper, sno_personal.apeper, sno_personal.nacper, sno_personalnomina.codcueban, ".
				"		   sno_personalnomina.tipcuebanper, sno_personal.fecingper, sno_personal.fecegrper, sno_personal.fecleypen,".
				"          sno_unidadadmin.desuniadm,".
				"          sno_personalnomina.codcar, sno_personalnomina.codban, ".
				"   	   sno_unidadadmin.minorguniadm,sno_unidadadmin.ofiuniadm,sno_unidadadmin.uniuniadm,sno_personal.dirper,".
				"          sno_unidadadmin.depuniadm,sno_tipopersonal.destipper, ".
				"		   sno_unidadadmin.prouniadm,sno_personalnomina.codasicar, sno_ubicacionfisica.codpai,  ".
				"          sno_ubicacionfisica.codest,sno_ubicacionfisica.codmun,sno_ubicacionfisica.codpar,sno_personalnomina.codage, ".
				"         sno_personalnomina.fecingper,sno_personal.codorg,sueobr,tiponom ".
				"   ".$ls_orden;
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_recibopago_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_recibopago_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_recibopago_conceptopersonal($as_codper,$as_conceptocero,$as_conceptop2,$as_conceptoreporte,$as_tituloconcepto,$as_quincena)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibopago_conceptopersonal
		//         Access: public (desde la clase sigesp_sno_rpp_recibopago)  
		//	    Arguments: as_codper // Código del personal que se desea buscar la salida
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos cuyo valor es cero
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos de tipo reporte
		//	  			   as_tituloconcepto // criterio que me indica si se desea mostrar los títulos de los conceptos
		//	  			   as_quincena // Quincena que se esta reportando
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados al personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_campo="sno_concepto.nomcon";
		$ls_campomonto=" sno_salida.valsal ";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_concepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "   AND sno_salida.valsal<>0 ";
		}
		if(($_SESSION["la_nomina"]["divcon"]==1)&&($_SESSION["la_nomina"]["tippernom"]==2))
		{
			if($as_quincena!="3")
			{
				$ls_criterio = $ls_criterio."   AND (sno_concepto.quirepcon = '".$as_quincena."' ".
											"	 OR  sno_concepto.quirepcon = '3')";
				switch($as_quincena)
				{
					case "1":
						$ls_campomonto=" sno_salida.priquisal as valsal ";
						break;
					case "2":
						$ls_campomonto=" sno_salida.segquisal as valsal ";
						break;
				}
			}
		}
		if(!empty($as_conceptop2))
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR ".
											"  	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ";
			}
		}
		else
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"  	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
			}
		}
		$ls_sql="SELECT sno_concepto.codconc, ".$ls_campo." as nomcon, ".$ls_campomonto.", sno_salida.tipsal, abs(sno_conceptopersonal.acuemp) AS acuemp, ".
				"		abs(sno_conceptopersonal.acupat) AS acupat, sno_concepto.repacucon, sno_concepto.repconsunicon, sno_concepto.consunicon, ".
				"		(SELECT moncon FROM sno_constantepersonal ".
				"		  WHERE sno_concepto.repconsunicon='1' ".
				"			AND sno_constantepersonal.codper = '".$as_codper."' ".
				"			AND sno_constantepersonal.codemp = sno_concepto.codemp ".
				"			AND sno_constantepersonal.codnom = sno_concepto.codnom ".
				"			AND sno_constantepersonal.codcons = sno_concepto.consunicon ) AS unidad ".
				"  FROM sno_salida, sno_concepto, sno_conceptopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."'".
				"   AND sno_salida.codper='".$as_codper."'".
				"   ".$ls_criterio.
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_salida.codemp = sno_conceptopersonal.codemp ".
				"   AND sno_salida.codnom = sno_conceptopersonal.codnom ".
				"   AND sno_salida.codconc = sno_conceptopersonal.codconc ".
				"   AND sno_salida.codper = sno_conceptopersonal.codper ".
				" ORDER BY sno_concepto.codconc ";
			
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_recibopago_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_recibopago_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_relacionvacacion_personal($as_codper,$as_codvac,$as_conceptocero,&$rs_data)
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
			$ls_criterio = "AND sno_salida.valsal<>0 ";
		}
		if($this->li_rac=="1")// Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ";
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		sno_unidadadmin.desuniadm, sno_vacacpersonal.sueintvac, sno_vacacpersonal.fecdisvac, ".
				"		sno_vacacpersonal.fecreivac, sno_vacacpersonal.diavac, sno_vacacpersonal.codvac, ".$ls_descar.
				"       ,sno_vacacpersonal.dianorvac, sno_vacacpersonal.persalvac, sno_vacacpersonal.peringvac, ".
				"       sno_vacacpersonal.quisalvac, sno_vacacpersonal.quireivac, sno_vacacpersonal.diabonvac, ".
				"       sno_vacacpersonal.sabdom,sno_vacacpersonal.diafer,sno_vacacpersonal.obsvac,sno_vacacpersonal.diaadibon,".
				"       sno_vacacpersonal.diapenvac, sno_vacacpersonal.diapervac,sno_vacacpersonal.diaadivac ".
				"  FROM sno_personal, sno_personalnomina, sno_unidadadmin, sno_vacacpersonal, sno_salida  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_vacacpersonal.codper='".$as_codper."' ".
				"   AND sno_vacacpersonal.codvac='".$as_codvac."' ".
				"   AND ((sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'V4') ".
				"    OR (sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'W3' OR sno_salida.tipsal = 'W4')) ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_vacacpersonal.codemp ".
				"   AND sno_personalnomina.codper = sno_vacacpersonal.codper ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				" GROUP BY sno_personalnomina.codemp, sno_personalnomina.codnom, sno_personal.codper, sno_personal.cedper, ".
				"		   sno_vacacpersonal.codvac,  sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		   sno_unidadadmin.desuniadm, sno_vacacpersonal.sueintvac, sno_vacacpersonal.fecdisvac, ".
				"		   sno_vacacpersonal.fecreivac, sno_vacacpersonal.diavac, sno_personalnomina.codcar, sno_personalnomina.codasicar,sno_vacacpersonal.dianorvac, sno_vacacpersonal.persalvac, sno_vacacpersonal.peringvac, ".
				"       sno_vacacpersonal.quisalvac, sno_vacacpersonal.quireivac, sno_vacacpersonal.diabonvac, ".
				"       sno_vacacpersonal.sabdom, sno_vacacpersonal.diafer, sno_vacacpersonal.obsvac, ".
				"       sno_vacacpersonal.diapenvac, sno_vacacpersonal.diapervac, sno_vacacpersonal.diaadibon, ".
				"       sno_vacacpersonal.diaadivac ";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_relacionvacacion_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
		$ls_campo="sno_concepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_concepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "AND sno_salida.valsal<>0 ";
		}
		$ls_sql="SELECT sno_concepto.codconc, ".$ls_campo." as nomcon, sno_salida.valsal, ".
				"		sno_salida.tipsal, sno_vacacpersonal.persalvac, sno_vacacpersonal.peringvac ".
				"  FROM sno_personalnomina, sno_concepto, sno_salida, sno_vacacpersonal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_personalnomina.codper='".$as_codper."' ".
				"   AND sno_vacacpersonal.codvac='".$as_codvac."' ".
				"   AND ((sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'V4') ".
				"    OR (sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'W3' OR sno_salida.tipsal = 'W4')) ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personalnomina.codemp = sno_vacacpersonal.codemp ".
				"   AND sno_personalnomina.codper = sno_vacacpersonal.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ";
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
	function uf_programacionvacaciones_personal($as_estvac,$ad_fecdisdes,$ad_fecdishas,$as_subnomdes,$as_subnomhas,$as_orden)
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
			$ls_criterio= "AND sno_vacacpersonal.stavac = ".$as_estvac."";
		}
		else
		{
			$ls_criterio= "AND ( sno_vacacpersonal.stavac = 1 OR sno_vacacpersonal.stavac = 2) ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($ad_fecdisdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_vacacpersonal.fecdisvac>='".$this->io_funciones->uf_convertirdatetobd($ad_fecdisdes)."'";
		}
		if(!empty($ad_fecdishas))
		{
			$ls_criterio = $ls_criterio."   AND sno_vacacpersonal.fecdisvac<='".$this->io_funciones->uf_convertirdatetobd($ad_fecdishas)."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de Personal 
				$ls_orden="ORDER BY sno_personal.codper, sno_vacacpersonal.codvac ";
				break;

			case "2": // Ordena por Apellido de Personal
				$ls_orden="ORDER BY sno_personal.apeper, sno_vacacpersonal.codvac ";
				break;

			case "3": // Ordena por Nombre de Personal
				$ls_orden="ORDER BY sno_personal.nomper, sno_vacacpersonal.codvac ";
				break;

			case "4": // Ordena por Fecha de Vencimiento
				$ls_orden="ORDER BY sno_vacacpersonal.fecvenvac, sno_vacacpersonal.codvac ";
				break;

			case "5": // Ordena por Fecha de Disfrute
				$ls_orden="ORDER BY sno_vacacpersonal.fecdisvac, sno_vacacpersonal.codvac ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.apeper, sno_personal.nomper, sno_vacacpersonal.codvac, ".
		        "		sno_vacacpersonal.fecvenvac, sno_vacacpersonal.fecdisvac, sno_vacacpersonal.stavac ".
 				"  FROM sno_personal, sno_personalnomina, sno_vacacpersonal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personal.codemp = sno_vacacpersonal.codemp ".
				"   AND sno_personal.codper = sno_vacacpersonal.codper ".
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
	function uf_listadofirmas($as_codperdes,$as_codperhas,$as_personalcero,$as_quincena,$as_tipopago,$as_coduniadm,$as_subnomdes,$as_subnomhas,$as_orden)
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
			$ls_criterio= "AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		switch($as_tipopago)
		{
			case "1": // Pago en efectivo
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagefeper=1 ";
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagbanper=0 ";
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagtaqper=0 ";
				break;
				
			case "2": // Pago en banco
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagefeper=0 ";
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagbanper=1 ";
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagtaqper=0 ";
				break;
				
			case "3": // Pago por taquilla
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagefeper=0 ";
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagbanper=0 ";
				$ls_criterio= $ls_criterio."   AND sno_personalnomina.pagtaqper=1 ";
				break;
		}
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_resumen.priquires as monnetres";
				if(!empty($as_personalcero))
				{
					$ls_criterio = $ls_criterio."AND sno_resumen.priquires<>0 ";
				}
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires as monnetres";
				if(!empty($as_personalcero))
				{
					$ls_criterio = $ls_criterio."AND sno_resumen.segquires<>0 ";
				}
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres as monnetres";
				if(!empty($as_personalcero))
				{
					$ls_criterio = $ls_criterio."AND sno_resumen.monnetres<>0 ";
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
				"  FROM sno_personal, sno_personalnomina,  sno_resumen ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='".$this->ls_peractnom."' ".
				$ls_criterio. 
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"	AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
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
										  $as_codtippredes,$as_codtipprehas,$as_subnomdes,$as_subnomhas,$as_estatus)
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
			$ls_criterio= "AND sno_prestamos.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codper<='".$as_codperhas."'";
		}
		if(!empty($as_codtippredes))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codtippre>='".$as_codtippredes."'";
		}
		if(!empty($as_codtipprehas))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codtippre<='".$as_codtipprehas."'";
		}
		if(!empty($as_estatus))
		{
			$ls_criterio = $ls_criterio."   AND sno_prestamos.stapre='".$as_estatus."' ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		$ls_sql="SELECT sno_prestamos.codconc, sno_concepto.nomcon ".
				"  FROM sno_prestamos, sno_concepto, sno_personalnomina ".
				" WHERE sno_prestamos.codemp='".$this->ls_codemp."' ".
				"   AND sno_prestamos.codnom='".$this->ls_codnom."' ".
				$ls_criterio.
				"   AND sno_prestamos.codemp = sno_concepto.codemp ".
				"   AND sno_prestamos.codnom = sno_concepto.codnom ".
				"   AND sno_prestamos.codconc = sno_concepto.codconc ".
				"   AND sno_prestamos.codemp = sno_personalnomina.codemp ".
				"   AND sno_prestamos.codnom = sno_personalnomina.codnom ".
				"   AND sno_prestamos.codper = sno_personalnomina.codper ".
				" GROUP BY sno_prestamos.codconc, sno_concepto.nomcon";
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
										         $as_codtippredes,$as_codtipprehas,$as_estatus,$as_subnomdes,$as_subnomhas,$as_orden)
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
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codper<='".$as_codperhas."'";
		}
		if(!empty($as_codtippredes))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codtippre>='".$as_codtippredes."'";
		}
		if(!empty($as_codtipprehas))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codtippre<='".$as_codtipprehas."'";
		}
		if(!empty($as_estatus))
		{
			$ls_criterio = $ls_criterio."   AND sno_prestamos.stapre='".$as_estatus."' ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
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
		$ls_sql="SELECT sno_prestamos.codper, sno_personal.nomper, sno_personal.apeper, sno_tipoprestamo.destippre, ".
			    "		sno_prestamos.fecpre, sno_prestamos.monpre,  sno_prestamos.monamopre, sno_prestamos.stapre, ".
				"		(SELECT COUNT(codper) FROM sno_prestamosperiodo ".
				"         WHERE sno_prestamosperiodo.estcuo = 0 ".
				"			AND sno_prestamos.codemp = sno_prestamosperiodo.codemp ".
				" 			AND sno_prestamos.codnom = sno_prestamosperiodo.codnom ".
				"			AND sno_prestamos.codper = sno_prestamosperiodo.codper ".
				"			AND sno_prestamos.numpre = sno_prestamosperiodo.numpre ".
				"			AND sno_prestamos.codtippre = sno_prestamosperiodo.codtippre) AS numcuopre ".
			    "  FROM sno_prestamos, sno_personal, sno_tipoprestamo, sno_personalnomina ".
			    " WHERE sno_prestamos.codemp='".$this->ls_codemp."' ".
			    "   AND sno_prestamos.codnom='".$this->ls_codnom."' ".
				"	AND sno_prestamos.codconc='".$as_codconc."' ".
				$ls_criterio.
			    "   AND sno_prestamos.codemp = sno_personal.codemp ".
			    "   AND sno_prestamos.codper = sno_personal.codper ".
			    "   AND sno_prestamos.codemp = sno_personalnomina.codemp ".
			    "   AND sno_prestamos.codnom = sno_personalnomina.codnom ".
			    "   AND sno_prestamos.codper = sno_personalnomina.codper ".
			    "   AND sno_prestamos.codemp = sno_tipoprestamo.codemp ".
			    "   AND sno_prestamos.codnom = sno_tipoprestamo.codnom ".
			    "   AND sno_prestamos.codtippre = sno_tipoprestamo.codtippre ".
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
										  $as_codtippredes,$as_codtipprehas,$as_estatus,$as_subnomdes,$as_subnomhas,$as_orden)
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
			$ls_criterio= "AND sno_prestamos.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codper<='".$as_codperhas."'";
		}
		if(!empty($as_codtippredes))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codtippre>='".$as_codtippredes."'";
		}
		if(!empty($as_codtipprehas))
		{
			$ls_criterio= $ls_criterio."   AND sno_prestamos.codtippre<='".$as_codtipprehas."'";
		}
		if(!empty($as_estatus))
		{
			$ls_criterio = $ls_criterio."   AND sno_prestamos.stapre='".$as_estatus."' ";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
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
		$ls_sql="SELECT sno_prestamos.codper, sno_prestamos.numpre, sno_prestamos.codtippre, sno_prestamos.codconc, ".
				"		sno_prestamos.monpre, sno_prestamos.numcuopre, sno_prestamos.monamopre, sno_prestamos.stapre, ".
				"		sno_prestamos.fecpre, sno_prestamos.perinipre, sno_personal.nomper, sno_personal.apeper, ".
				"		sno_concepto.nomcon, sno_tipoprestamo.destippre, sno_personal.cedper, sno_personal.fecingper ".
				"  FROM sno_prestamos, sno_personal, sno_concepto, sno_tipoprestamo, sno_personalnomina ".
				" WHERE sno_prestamos.codemp='".$this->ls_codemp."' ".
				"   AND sno_prestamos.codnom='".$this->ls_codnom."' ".
				$ls_criterio.
				"   AND sno_prestamos.codemp = sno_personal.codemp ".
				"   AND sno_prestamos.codper = sno_personal.codper ".
				"   AND sno_prestamos.codemp = sno_personalnomina.codemp ".
				"   AND sno_prestamos.codnom = sno_personalnomina.codnom ".
				"   AND sno_prestamos.codper = sno_personalnomina.codper ".
				"   AND sno_prestamos.codemp = sno_concepto.codemp ".
				"   AND sno_prestamos.codnom = sno_concepto.codnom ".
				"   AND sno_prestamos.codconc = sno_concepto.codconc ".
				"   AND sno_prestamos.codemp = sno_tipoprestamo.codemp ".
				"   AND sno_prestamos.codnom = sno_tipoprestamo.codnom ".
				"   AND sno_prestamos.codtippre = sno_tipoprestamo.codtippre ".
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
		$ls_sql="SELECT numcuo, percob, feciniper, fecfinper, moncuo, estcuo ".
				"  FROM sno_prestamosperiodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
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
		$ls_sql="SELECT numamo, peramo, fecamo, monamo, desamo ".
				"  FROM sno_prestamosamortizado ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
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
	function uf_listadoproyecto_proyectos($as_codproydes,$as_codproyhas,$as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadoproyecto_proyectos
		//         Access: public (desde la clase sigesp_sno_rpp_listadoproyecto)  
		//	    Arguments: as_codproydes // Código del proyecto donde se empieza a filtrar
		//				   as_codproyhas // Código del proyecto donde se termina de filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los proyectos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codproydes))
		{
			$ls_criterio= "AND sno_proyecto.codproy>='".$as_codproydes."'";
		}
		if(!empty($as_codproyhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_proyecto.codproy<='".$as_codproyhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		$ls_sql="SELECT sno_proyecto.codproy, MAX(sno_proyecto.nomproy) AS nomproy, count(sno_proyectopersonal.codper) as total, ".
				"		sum(sno_proyectopersonal.pordiames*100) as monto ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_personalnomina ".
				" WHERE sno_proyectopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_proyectopersonal.codnom='".$this->ls_codnom."' ".
				"   ".$ls_criterio.
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND sno_proyectopersonal.codemp = sno_personalnomina.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_personalnomina.codnom ".
				"   AND sno_proyectopersonal.codper = sno_personalnomina.codper ".
				" GROUP BY sno_proyecto.codproy  ".
				" ORDER BY sno_proyecto.codproy ";
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
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar ";
		}
		else// No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ";
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, (sno_proyectopersonal.pordiames*100) AS pordiames, ".$ls_descar.
				"  FROM sno_personal, sno_personalnomina, sno_proyectopersonal ".
				" WHERE sno_proyectopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_proyectopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_proyectopersonal.codproy='".$as_codproy."' ".
				"   AND sno_personalnomina.codemp = sno_proyectopersonal.codemp ".
				"   AND sno_personalnomina.codnom = sno_proyectopersonal.codnom ".
				"   AND sno_personalnomina.codper = sno_proyectopersonal.codper ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
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
	function uf_listadoproyectopersonal_personal($as_codperdes,$as_codperhas,$as_subnomdes,$as_subnomhas,$as_orden)
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
			$ls_criterio= "AND sno_proyectopersonal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_proyectopersonal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
				"		count(sno_proyectopersonal.codproy) as total, sum(sno_proyectopersonal.pordiames*100) as monto ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_personal, sno_personalnomina ".
				" WHERE sno_proyectopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_proyectopersonal.codnom='".$this->ls_codnom."' ".
				"   ".$ls_criterio.
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND sno_proyectopersonal.codemp = sno_personalnomina.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_personalnomina.codnom ".
				"   AND sno_proyectopersonal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
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
		$ls_sql="SELECT sno_proyecto.codproy, sno_proyecto.nomproy, (sno_proyectopersonal.pordiames*100) AS pordiames ".
				"  FROM sno_proyectopersonal, sno_proyecto ".
				" WHERE sno_proyectopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_proyectopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_proyectopersonal.codper='".$as_codper."' ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				" ORDER BY sno_proyecto.codproy ";
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
										  $as_coduniadmdes,$as_coduniadmhas,$as_subnomdes,$as_subnomhas)
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
			$ls_criterio= " AND sno_personalnomina.codper>='".$as_codperdes."'";
			$ls_criteriounion=" AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
			$ls_criteriounion = $ls_criteriounion."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
			$ls_criteriounion= $ls_criteriounion."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
			$ls_criteriounion= $ls_criteriounion."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		if(!empty($as_coduniadmdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.minorguniadm>='".substr($as_coduniadmdes,0,4)."'".
						  			   " AND sno_personalnomina.ofiuniadm>='".substr($as_coduniadmdes,5,2)."' ".
						               " AND sno_personalnomina.uniuniadm>='".substr($as_coduniadmdes,8,2)."' ".
						               " AND sno_personalnomina.depuniadm>='".substr($as_coduniadmdes,11,2)."' ".
						               " AND sno_personalnomina.prouniadm>='".substr($as_coduniadmdes,14,2)."' ";
			$ls_criteriounion= $ls_criteriounion." AND sno_personalnomina.minorguniadm>='".substr($as_coduniadmdes,0,4)."'".
						  	   					 " AND sno_personalnomina.ofiuniadm>='".substr($as_coduniadmdes,5,2)."' ".
						       					 " AND sno_personalnomina.uniuniadm>='".substr($as_coduniadmdes,8,2)."' ".
						       					 " AND sno_personalnomina.depuniadm>='".substr($as_coduniadmdes,11,2)."' ".
						       					 " AND sno_personalnomina.prouniadm>='".substr($as_coduniadmdes,14,2)."' ";
		}
		if(!empty($as_coduniadmhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.minorguniadm<='".substr($as_coduniadmhas,0,4)."'".
						  			   " AND sno_personalnomina.ofiuniadm<='".substr($as_coduniadmhas,5,2)."' ".
						               " AND sno_personalnomina.uniuniadm<='".substr($as_coduniadmhas,8,2)."' ".
						               " AND sno_personalnomina.depuniadm<='".substr($as_coduniadmhas,11,2)."' ".
						               " AND sno_personalnomina.prouniadm<='".substr($as_coduniadmhas,14,2)."' ";
			$ls_criteriounion= $ls_criteriounion." AND sno_personalnomina.minorguniadm<='".substr($as_coduniadmhas,0,4)."'".
						  	   					 " AND sno_personalnomina.ofiuniadm<='".substr($as_coduniadmhas,5,2)."' ".
						       					 " AND sno_personalnomina.uniuniadm<='".substr($as_coduniadmhas,8,2)."' ".
						       					 " AND sno_personalnomina.depuniadm<='".substr($as_coduniadmhas,11,2)."' ".
						       					 " AND sno_personalnomina.prouniadm<='".substr($as_coduniadmhas,14,2)."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR ".
											"	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
			}
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
					  "    	  sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm ".
					  "  FROM sno_personalnomina, sno_salida, sno_unidadadmin ".
					  " WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					  "   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					  "	  AND sno_personalnomina.staper = '2' ".
					  "   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					  "   AND sno_salida.codconc='".$ls_vac_codconvac."' ".
					  "   ".$ls_criteriounion.
					  "   AND sno_personalnomina.codemp = sno_salida.codemp ".
					  "   AND sno_personalnomina.codnom = sno_salida.codnom ".
					  "   AND sno_personalnomina.codper = sno_salida.codper ".
					  "   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
					  "   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
					  "   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
					  "   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
					  "   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
					  "   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
					  " GROUP BY sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
					  "		   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm  ";
		}
		$ls_sql="SELECT sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm,sno_unidadadmin.depuniadm,  ".
				"    	sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm  ".
				"  FROM sno_personalnomina, sno_salida, sno_unidadadmin ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
				"		   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm  ".
				"   ".$ls_union.
				" ORDER BY sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
				"		   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm ";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonominaunidad_unidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_pagonominaunidad_unidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pagonominaunidad_personal($as_codperdes,$as_codperhas,$as_conceptocero,$as_conceptoreporte,$as_conceptop2,
										  $as_minorguniadm,$as_ofiuniadm,$as_uniuniadm,$as_depuniadm,$as_prouniadm,
										  $as_subnomdes,$as_subnomhas,$as_orden)
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
			$ls_criterio= " AND sno_personalnomina.codper>='".$as_codperdes."'";
			$ls_criteriounion=" AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
			$ls_criteriounion = $ls_criteriounion."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
			$ls_criteriounion= $ls_criteriounion."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
			$ls_criteriounion= $ls_criteriounion."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personalnomina.minorguniadm='".$as_minorguniadm."'".
								   " AND sno_personalnomina.ofiuniadm='".$as_ofiuniadm."' ".
								   " AND sno_personalnomina.uniuniadm='".$as_uniuniadm."' ".
								   " AND sno_personalnomina.depuniadm='".$as_depuniadm."' ".
								   " AND sno_personalnomina.prouniadm='".$as_prouniadm."' ";
		$ls_criteriounion= $ls_criteriounion." AND sno_personalnomina.minorguniadm='".$as_minorguniadm."'".
											 " AND sno_personalnomina.ofiuniadm='".$as_ofiuniadm."' ".
											 " AND sno_personalnomina.uniuniadm='".$as_uniuniadm."' ".
											 " AND sno_personalnomina.depuniadm='".$as_depuniadm."' ".
											 " AND sno_personalnomina.prouniadm='".$as_prouniadm."' ";
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR ".
											"	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
							   				"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
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
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar ";
		}
		else // No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ";
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_personalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
					  "   	  sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_unidadadmin.codprouniadm, MAX(sno_personalnomina.sueper) AS sueper, ".
					  "		  sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, ".
					  "		  MAX(sno_personalnomina.codgra) AS codgra, ".
  					  $ls_descar.
					  "  FROM sno_personal, sno_personalnomina, sno_salida, sno_unidadadmin ".
					  " WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					  "   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					  "	  AND sno_personalnomina.staper = '2' ".
					  "   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					  "   AND sno_salida.codconc='".$ls_vac_codconvac."' ".
					  "   ".$ls_criteriounion.
					  "   AND sno_personalnomina.codemp = sno_salida.codemp ".
					  "   AND sno_personalnomina.codnom = sno_salida.codnom ".
					  "   AND sno_personalnomina.codper = sno_salida.codper ".
					  "   AND sno_personal.codemp = sno_personalnomina.codemp ".
					  "   AND sno_personal.codper = sno_personalnomina.codper ".
					  "   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
					  "   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
					  "   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
					  "   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
					  "   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
					  "   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
					  " GROUP BY sno_personalnomina.codemp, sno_personalnomina.codnom, sno_personalnomina.codper, ".
					  "		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
					  "		   sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_unidadadmin.desuniadm, ".
					  "		   sno_unidadadmin.codprouniadm, sno_personalnomina.codcar, sno_personalnomina.codasicar, ".
					  "		   sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
					  "    	   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm ";
		}
		$ls_sql="SELECT sno_personalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_unidadadmin.codprouniadm, MAX(sno_personalnomina.sueper) AS sueper, ".
				"		sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm, ".
			    "		  MAX(sno_personalnomina.codgra) AS codgra, ".
				$ls_descar.
				"  FROM sno_personal, sno_personalnomina, sno_salida, sno_unidadadmin ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   ".$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_personalnomina.codemp, sno_personalnomina.codnom, sno_personalnomina.codper, ".
				"		   sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		   sno_personalnomina.codcueban, sno_unidadadmin.desuniadm, sno_unidadadmin.desuniadm, ".
				"		   sno_unidadadmin.codprouniadm, sno_personalnomina.codcar, sno_personalnomina.codasicar, ".
				"		   sno_unidadadmin.minorguniadm, sno_unidadadmin.ofiuniadm, sno_unidadadmin.uniuniadm, ".
			    "    	   sno_unidadadmin.depuniadm, sno_unidadadmin.prouniadm ".
				"   ".$ls_union.
				"   ".$ls_orden;
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonomina_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
		$ls_campo="sno_concepto.nomcon";
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_concepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = "AND sno_salida.valsal<>0 ";
		}
		if(!empty($as_conceptoreporte))
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4' OR ".
											"	   sno_salida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='R')";
			}
		}
		else
		{
			if(!empty($as_conceptop2))
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3' OR ".
											"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4') ";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
											"	   sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
											"      sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3')";
			}
		}
		$ls_union="";
		$li_vac_reportar=trim($this->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$ls_union="UNION ".
					  "SELECT sno_concepto.codconc, ".$ls_campo." as nomcon, sno_salida.valsal, sno_salida.tipsal ".
					  "  FROM sno_salida, sno_concepto, sno_personalnomina ".
					  " WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					  "   AND sno_salida.codnom='".$this->ls_codnom."' ".
					  "   AND sno_salida.codperi='".$this->ls_peractnom."'".
					  "   AND sno_salida.codper='".$as_codper."'".
					  "   AND sno_salida.codconc='".$ls_vac_codconvac."'".
					  "   AND sno_personalnomina.staper = '2' ".
					  "   AND sno_salida.codemp = sno_concepto.codemp ".
					  "   AND sno_salida.codnom = sno_concepto.codnom ".
					  "   AND sno_salida.codconc = sno_concepto.codconc ".
					  "   AND sno_salida.codemp = sno_personalnomina.codemp ".
					  "   AND sno_salida.codnom = sno_personalnomina.codnom ".
					  "   AND sno_salida.codper = sno_personalnomina.codper ";
		}
		$ls_sql="SELECT sno_concepto.codconc, ".$ls_campo." as nomcon, sno_salida.valsal, sno_salida.tipsal ".
				"  FROM sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."'".
				"   AND sno_salida.codper='".$as_codper."'".
				"   ".$ls_criterio.
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   ".$ls_union.
				" ORDER BY sno_concepto.codconc ";
		$this->rs_data_detalle2=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle2===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pagonominaunidad_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_pagonominaunidad_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobeneficiario_personal($as_codperdes,$as_codperhas,$as_quincena,$as_codban,$as_subnomdes,$as_subnomhas,$as_orden,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobeneficiario_personal
		//         Access: public (desde la clase sigesp_sno_rpp_listadoconceptos)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_quincena // Quincena para el cual se quiere filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="MAX(sno_resumen.monnetres) as monnetres";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto=" MAX(sno_resumen.priquires) as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="MAX(sno_resumen.segquires) as monnetres";
				break;
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
				"		".$ls_monto." ".
				"  FROM sno_personalnomina, sno_salida, sno_personal, sno_resumen, sno_beneficiario ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_beneficiario.codban='".$as_codban."' ".
				"   AND sno_salida.valsal<>0 ".
				"   ".$ls_criterio.
				"   AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
				"		 sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
				"		 sno_salida.tipsal='P1' OR sno_salida.tipsal='V3' OR sno_salida.tipsal='W3') ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"	AND sno_beneficiario.codemp = sno_personal.codemp ".
				"	AND sno_beneficiario.codper = sno_personal.codper ".
				" GROUP BY sno_personal.codper  ".
				" ORDER BY sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobeneficiario_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobeneficiario_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobeneficiario_beneficiario($as_codper,$as_codban,$as_tipben,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobeneficiario_beneficiario
		//         Access: public (desde la clase sigesp_sno_rpp_listadobeneficiario)  
		//	    Arguments: as_codper // Código del personal 
		//	  			   as_codban // Código del banco  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, porpagben, monpagben, codban, ctaban, forpagben,  ".
				"       (SELECT scb_banco.nomban FROM scb_banco WHERE scb_banco.codemp =  sno_beneficiario.codemp ".
				"			AND scb_banco.codban = sno_beneficiario.codban) AS nomban ".
				"  FROM sno_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND codban='".$as_codban."' ".				
				$as_tipben.
				" ORDER BY cedben ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobeneficiario_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobeneficiario_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listado_asignaciocargo($as_coddes,$as_codhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_asignaciocargo
		//         Access: public (desde la clase sigesp_sno_rpp_prenomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los cargos asigandos por nómina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];			
		if(!empty($as_coddes))
		{
		  if (!empty($as_codhas))
		   {
		     	$ls_criterio = " and sno_asignacioncargo.codasicar BETWEEN '".$as_coddes."' and '".$as_codhas."'";
		   }
		}		
		
		switch($as_orden)
			{
				case "1": // Ordena por Código de Asignación de Cargo
					$ls_orden=" ORDER BY sno_asignacioncargo.codasicar ";
					break;

				case "2": // Ordena por el Nombre de la Asignación de Cargo
					$ls_orden=" ORDER BY sno_asignacioncargo.denasicar ";
					break;

				
			}
			
				$ls_sql=" SELECT sno_asignacioncargo.codasicar, sno_asignacioncargo.codnom,sno_asignacioncargo.denasicar, ". 
		        		" sno_asignacioncargo.codtab, ".
       					" sno_asignacioncargo.codgra, sno_asignacioncargo.codpas, sno_asignacioncargo.grado, ". 
       					" sno_unidadadmin.minorguniadm,sno_unidadadmin.ofiuniadm,sno_unidadadmin.uniuniadm, ".
						" sno_unidadadmin.depuniadm, ".
               		    " sno_unidadadmin.prouniadm, sno_unidadadmin.desuniadm, ".
               		    " sno_tabulador.destab,sno_asignacioncargo.numvacasicar, ".
                		" (SELECT count (sno_personalnomina.codasicar) from sno_personalnomina ".  
                		"         WHERE sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
                		"         AND sno_personalnomina.codnom=sno_asignacioncargo.codnom ".
                		"         AND sno_personalnomina.codemp=sno_asignacioncargo.codemp) as ocupado ".
                		" FROM sno_asignacioncargo   ".
                		" JOIN sno_unidadadmin on (sno_asignacioncargo.codemp=sno_unidadadmin.codemp
				                           AND sno_asignacioncargo.uniuniadm=sno_unidadadmin.uniuniadm
										   AND sno_asignacioncargo.minorguniadm=sno_unidadadmin.minorguniadm
										   AND sno_asignacioncargo.ofiuniadm=sno_unidadadmin.ofiuniadm
										   AND sno_asignacioncargo.depuniadm=sno_unidadadmin.depuniadm
										   AND sno_asignacioncargo.prouniadm=sno_unidadadmin.prouniadm) ".
       					" JOIN sno_tabulador on (sno_asignacioncargo.codtab=sno_tabulador.codtab
										 AND sno_asignacioncargo.codemp=sno_tabulador.codemp
										 AND sno_asignacioncargo.codnom=sno_tabulador.codnom) ".
   						" WHERE sno_asignacioncargo.codnom='".$ls_codnom."'". 
						"   and  sno_asignacioncargo.codemp='".$ls_codemp."'".$ls_criterio.$ls_orden; 
       
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listado_asignaciocargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_asigna->data=$this->io_sql->obtener_datos($rs_data);		
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
	
	//--------------------------------------------------------------------------------------------------------------------------------	
	function uf_recibo_nomina_oficiales($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibo_nomina_oficiales
		//         Access: public (desde la clase sigesp_sno_rpp_prenomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la informaciòn del personal oficial
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 14/05/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];			
			
			    $ls_sql=" SELECT sno_personalpension.codemp, sno_personalpension.codnom, sno_personalpension.codper, ".
						"	     sno_personalpension.suebasper, sno_personalpension.pritraper, sno_personalpension.pridesper, ". 
						"	     sno_personalpension.prianoserper, sno_personalpension.prinoascper, ".
						"	     sno_personalpension.priespper, sno_personalpension.priproper, sno_personalpension.subtotper, ".
						"	     sno_personalpension.porpenper, sno_personalpension.monpenper, ".
						"	   (select sno_personal.nomper from sno_personal where codper=sno_personalpension.codper) as nomper,".
						"	   (select sno_personal.apeper from sno_personal where ".
						" sno_personal.codper=sno_personalpension.codper)  as apeper, ".
						"	   (select sno_personal.cedper from sno_personal  ".
						"      where sno_personal.codper=sno_personalpension.codper) as cedper, ".
						"	   (select sno_personal.fecingper from sno_personal ".
						"	   where sno_personal.codper=sno_personalpension.codper) as fecingper, ".
						"	   (select sno_personal.fecleypen from sno_personal ".
						"	   where sno_personal.codper=sno_personalpension.codper) as fecleypen, ".
						"	   (select sno_personalnomina.fecingper from sno_personalnomina ".
						"       where sno_personalnomina.codper=sno_personalpension.codper ".
						"       and sno_personalnomina.codnom='".$ls_codnom."') as fecingnom, ".
						"	    sno_componente.descom, sno_rango.desran, ".
						"      (SELECT sno_categoria_rango.descat FROM sno_categoria_rango    ".
						"        WHERE sno_categoria_rango.codemp=sno_rango.codemp            ".
						"          AND sno_categoria_rango.codcat=sno_rango.codcat) as descat ".
						"  FROM sno_personalpension ".
						"  JOIN sno_personal ON (sno_personal.codemp=sno_personalpension.codemp ".
						"				   AND  sno_personal.codper=sno_personalpension.codper) ".
						"  LEFT JOIN sno_componente ON (sno_componente.codemp= sno_personal.codemp ".
						"						   AND sno_componente.codcom= sno_personal.codcom) ".
						"  LEFT JOIN sno_rango ON (sno_rango.codemp=sno_personal.codemp ".
						"					 AND  sno_rango.codcom=sno_personal.codcom  ".
						"					 AND  sno_rango.codran=sno_personal.codran) ".
						" WHERE sno_personalpension.codemp='".$ls_codemp."'".
						" AND	sno_personalpension.codper='".$as_codper."'".
						" AND	sno_personalpension.codnom='".$ls_codnom."'";       
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_recibo_nomina_oficiales ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_recibo_nomina_oficiales
	//--------------------------------------------------------------------------------------------------------------------------------	

	//--------------------------------------------------------------------------------------------------------------------------------	
	function uf_recibo_nomina_oficiales_2($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibo_nomina_oficiales_2
		//         Access: public (desde la clase sigesp_sno_rpp_prenomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la informaciòn del personal oficial
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 14/05/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];			
			
			    $ls_sql=" SELECT sno_personalpension.codemp, sno_personalpension.codnom, sno_personalpension.codper, ".
						"	     sno_personalpension.suebasper, sno_personalpension.pritraper, sno_personalpension.pridesper, ". 
						"	     sno_personalpension.prianoserper, sno_personalpension.prinoascper, ".
						"	     sno_personalpension.priespper, sno_personalpension.priproper, sno_personalpension.subtotper, ".
						"	     sno_personalpension.porpenper, sno_personalpension.monpenper, ".
						"	   (select sno_personal.nomper from sno_personal where codper=sno_personalpension.codper) as nomper,".
						"	   (select sno_personal.apeper from sno_personal where ".
						" sno_personal.codper=sno_personalpension.codper)  as apeper, ".
						"	   (select sno_personal.cedper from sno_personal  ".
						"      where sno_personal.codper=sno_personalpension.codper) as cedper, ".
						"	   (select sno_personal.fecingper from sno_personal ".
						"	   where sno_personal.codper=sno_personalpension.codper) as fecingper, ".
				"	   (select sno_personal.fecleypen from sno_personal ".
						"	   where sno_personal.codper=sno_personalpension.codper) as fecleypen, ".
						"	   (select sno_personalnomina.fecingper from sno_personalnomina ".
						"       where sno_personalnomina.codper=sno_personalpension.codper ".
						"       and sno_personalnomina.codnom='".$ls_codnom."') as fecingnom, ".
						"	    sno_componente.descom, sno_rango.desran, ".
						"      (SELECT sno_categoria_rango.descat FROM sno_categoria_rango    ".
						"        WHERE sno_categoria_rango.codemp=sno_rango.codemp            ".
						"          AND sno_categoria_rango.codcat=sno_rango.codcat) as descat ".
						"  FROM sno_personalpension ".
						"  JOIN sno_personal ON (sno_personal.codemp=sno_personalpension.codemp ".
						"				   AND  sno_personal.codper=sno_personalpension.codper) ".
						"  LEFT JOIN sno_componente ON (sno_componente.codemp= sno_personal.codemp ".
						"						   AND sno_componente.codcom= sno_personal.codcom) ".
						"  LEFT JOIN sno_rango ON (sno_rango.codemp=sno_personal.codemp ".
						"					 AND  sno_rango.codcom=sno_personal.codcom  ".
						"					 AND  sno_rango.codran=sno_personal.codran) ".
						" WHERE sno_personalpension.codemp='".$ls_codemp."'".
						" AND	sno_personalpension.codper='".$as_codper."'".
						" AND	sno_personalpension.codnom='".$ls_codnom."'";       
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_recibo_nomina_oficiales_2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_recibo_nomina_oficiales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
    function uf_seleccionar_quincenas($as_codper,&$as_priqui,&$as_segqui)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibo_nomina_oficiales
		//         Access: public (desde la clase sigesp_sno_rpp_recibopago_ipsfa)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la informaciòn de la primera y segunda quincena de la nomina de una persona
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 21/05/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];			
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"]; 	
		
				$ls_sql=" SELECT priquires, segquires         ".
				        " FROM sno_resumen                    ".
						" WHERE sno_resumen.codemp='".$ls_codemp."'         ". 
						" AND sno_resumen.codper='".$as_codper."'  ".
						" AND sno_resumen.codperi='".$ls_peractnom."'       ".
						" AND sno_resumen.codnom='".$ls_codnom."'       ";  
       
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_seleccionar_quincenas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_priqui=$row["priquires"];
				$as_segqui=$row["segquires"];		
			}
			else
			{
				$lb_valido=false;
				$as_priqui="";
				$as_priqui="";	
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_seleccionar_quincenas
   //--------------------------------------------------------------------------------------------------------------------------------
   
   //--------------------------------------------------------------------------------------------------------------------------------
   function uf_obtener_valor_concepto($as_codper,$as_concepto,&$as_valor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_obtener_valor_concepto
		//         Access: public (desde la clase sigesp_sno_rpp_recibopago_ipsfa)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la informaciòn de la primera y segunda quincena de la nomina de una persona
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 21/05/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];			
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"]; 	
		
				$ls_sql=" SELECT sno_concepto.codconc, sno_concepto.titcon as nomcon, sno_salida.valsal  ".
						"	FROM sno_salida, sno_concepto ".
						"		WHERE sno_salida.codemp='".$ls_codemp."' ". 
						"		AND sno_salida.codnom='".$ls_codnom."'  ". 
						"		AND sno_salida.codperi='".$ls_peractnom."' ". 
						"		AND sno_concepto.codconc='".$as_concepto."' ".
						"		AND sno_salida.codper='".$as_codper."' ". 
						"		AND sno_salida.valsal<>0 ".
						"		AND sno_salida.codemp = sno_concepto.codemp ".
						"		AND sno_salida.codnom = sno_concepto.codnom ".
						"		AND sno_salida.codconc = sno_concepto.codconc ".
						"		ORDER BY sno_concepto.codconc   ";  
       
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_obtener_valor_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_valor=$row["valsal"];
						
			}
			else
			{
				$lb_valido=false;
				$as_valor=0;				
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_obtener_valor_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------------
		
	//-----------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_beneficiarios($as_codbendes, $as_codbenhas, $as_codperdes, $as_codperhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_beneficiarios
		//         Access: public (desde la clase sigesp_sno_rpp_recibopago_beneficiario)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la informaciòn de los beneficiarios
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 26/06/2008								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if (($as_codperdes!="")&&($as_codperhas!=""))		
		{
			$ls_criterio="   AND codper BETWEEN '".$as_codperdes."' AND '".$as_codperhas."'";
		}
		if (($as_codbendes!="")&&($as_codbenhas!=""))		
		{
			$ls_criterio=$ls_criterio. "   AND codben BETWEEN '".$as_codbendes."' AND '".$as_codbenhas."'";  
		}
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];			
		$ls_sql=" SELECT sno_beneficiario.codper, sno_beneficiario.codben,  sno_beneficiario.cedben,         ".
                "        sno_beneficiario.nomben, sno_beneficiario.apeben,  sno_beneficiario.porpagben,      ".
                "        sno_beneficiario.codban, sno_beneficiario.ctaban,  sno_beneficiario.tipcueben,      ".
				"        sno_beneficiario.nexben, sno_beneficiario.nomcheben, sno_beneficiario.cedaut,       ".
				"        (SELECT sno_personal.fecnacper FROM sno_personal ".
				"          WHERE sno_personal.codemp='".$ls_codemp."'".
				"            AND sno_personal.cedper=sno_beneficiario.cedben) as fecnacben,        ".
				"        (SELECT scb_banco.nomban FROM scb_banco WHERE scb_banco.codemp='".$ls_codemp."'     ".
				"            AND scb_banco.codban=sno_beneficiario.codban) AS banco                          ".
                " FROM sno_beneficiario                                                                      ".
                " WHERE sno_beneficiario.codemp='".$ls_codemp."'".$ls_criterio.
				" ORDER BY sno_beneficiario.codper, sno_beneficiario.codben";           
		$this->rs_data_detalle2=$this->io_sql->select($ls_sql);
		if($this->rs_data_detalle2===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_buscar_beneficiarios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_buscar_beneficiarios
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
    function uf_cuadre_concepto_pensiones($as_codconcdes,$as_codconchas,$as_conceptocero,$as_subnomdes,$as_subnomhas,$fecha,$criteriodefecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cuadre_concepto_pensiones
		//         Access: public (desde la clase sigesp_sno_r_cuadrenomina_pensiones)  
		//	    Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se calculó la nómina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/07/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";		
		$ls_criteriopersonalnomina="";		
		$ls_criterio= $ls_criterio."	     ON sno_salida.codemp='".$this->ls_codemp."'           ".
								   "		AND sno_salida.codnom='".$this->ls_codnom."'      ".
								   "		AND sno_salida.codperi='".$this->ls_peractnom."'  ";
		if(!empty($as_codconcdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_salida.codconc>='".$as_codconcdes."'";			
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_salida.codconc<='".$as_codconchas."'";			
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";			
		}
		if(!empty($as_aportepatronal))
		{
			$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".
										"      sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".		
										"	   sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4')";
		}
		else
		{
			$ls_criterio = $ls_criterio." AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1')";
		}		
		if(!empty($as_subnomdes))
		{
			$ls_criteriopersonalnomina= $ls_criteriopersonalnomina."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";			
		}
		if(!empty($as_subnomhas))
		{
			$ls_criteriopersonalnomina= $ls_criteriopersonalnomina."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";			
		}
		$ls_sql="SELECT  sno_concepto.codconc, MAX(sno_concepto.nomcon) AS nomcon, sno_salida.tipsal, sum(sno_salida.valsal) as monto, COUNT(sno_salida.codper) AS total						    ".	
				"  FROM sno_salida ".
				" INNER JOIN sno_concepto ".
				"  ".$ls_criterio.
				"   AND sno_salida.codemp = sno_concepto.codemp                     ".
				"	AND sno_salida.codnom = sno_concepto.codnom                     ".
				"	AND sno_salida.codconc = sno_concepto.codconc                   ".
				" INNER JOIN (sno_personalnomina ".
				"           INNER JOIN sno_personal  ". 
				"		       ON  ".$criteriodefecha.
				"             AND sno_personal.codemp = sno_personalnomina.codemp   ".
				"			  AND sno_personal.codper = sno_personalnomina.codper)  ".
				"	".$ls_criterio.
				"        AND sno_salida.codemp = sno_personalnomina.codemp               ".
				"		 AND sno_salida.codnom = sno_personalnomina.codnom               ".
				"		 AND sno_salida.codper = sno_personalnomina.codper               ".
				$ls_criteriopersonalnomina.
				" GROUP BY sno_concepto.codconc, sno_salida.tipsal  ".
				" ORDER BY sno_concepto.codconc, sno_salida.tipsal                "; 
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadre_concepto_pensiones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end uf_cuadrenomina_concepto_pensiones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_categorias_rango()
    {    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_buscar_categorias_rango
        //         Access: public (desde la clase sigesp_sno_rpp_netos_deduc)  
        //        Arguments: 
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la informaciòn de las categorias de rango
        //       Creado Por: Ing. Jennifer Rivero
        // Fecha Creación: 30/07/2008                                 Fecha Última Modificación :          
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;            
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];            
                    
        $ls_sql="SELECT sno_categoria_rango.codcat, sno_categoria_rango.descat  ".                
                "  FROM sno_categoria_rango  ".                
                " WHERE sno_categoria_rango.codemp='".$ls_codemp."' ".
                " ORDER BY sno_categoria_rango.codcat ";  
        $this->rs_data=$this->io_sql->select($ls_sql);
        if($this->rs_data===false)
        {
            $this->io_mensajes->message("CLASE->Report MÉTODO->uf_buscar_categorias_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
            $lb_valido=false;
        }
        return $lb_valido;
    }// end function uf_buscar_categorias_rango
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_conceptos_netos_deduc($as_codconcdes,$as_codconchas,$as_codente)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_buscar_conceptos_netos_deduc
        //         Access: public (desde la clase sigesp_sno_rpp_netos_deduc)  
        //        Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
        //                   as_codconchas // Código del concepto donde se termina de filtrar
        //                     as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la información de los conceptos que se calculó 
        //                 la nómina agrupados por categorias militar
        //       Creado Por: Ing. María Beatriz Unda
        // Fecha Creación: 11/03/2009                                 Fecha Última Modificación :  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
        $ls_criterio="";        
        if(!empty($as_codconcdes))
        {
            $ls_criterio= $ls_criterio."   AND sno_concepto.codconc>='".$as_codconcdes."'";            
        }
        if(!empty($as_codconchas))
        {
            $ls_criterio= $ls_criterio."   AND sno_concepto.codconc<='".$as_codconchas."'";            
        }
        if(!empty($as_codente))
        {
            $ls_criterio= $ls_criterio."   AND sno_concepto.codente='".$as_codente."'";            
        }       
        $ls_sql="SELECT sno_concepto.codconc,sno_concepto.sigcon,sno_concepto.nomcon	  ".                                 
                "  FROM sno_concepto								      ".                
                " WHERE sno_concepto.codemp='".$this->ls_codemp."'       ".
                "   AND sno_concepto.codnom='".$this->ls_codnom."'       ".
				$ls_criterio.				         
				" ORDER BY sigcon, codconc							  ";			 
		$this->rs_data_concepto=$this->io_sql->select($ls_sql);
		if($this->rs_data_concepto===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadre_concepto_neto_deduc_excel ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;  
    } // fin de uf_cuadre_concepto_neto_deduc_excel
	//------------------------------------------------------------------------------------------------------------------------------------
    
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_cuadre_concepto_neto_deduc($as_codconcdes,$as_codconchas,$as_conceptocero,$as_subnomdes,$as_subnomhas,$as_codcat,$as_codente)
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_cuadre_concepto_neto_deduc
        //         Access: public (desde la clase sigesp_sno_rpp_netos_deduc)  
        //        Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
        //                   as_codconchas // Código del concepto donde se termina de filtrar
        //                     as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la información de los conceptos que se calculó 
        //                 la nómina agrupados por categorias militar
        //       Creado Por: Ing. Jennifer Rivero
        // Fecha Creación: 30/07/2008                                 Fecha Última Modificación :  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
        $ls_criterio="";        
        if(!empty($as_codconcdes))
        {
            $ls_criterio= "AND sno_salida.codconc>='".$as_codconcdes."'";            
        }
        if(!empty($as_codconchas))
        {
            $ls_criterio= $ls_criterio."   AND sno_salida.codconc<='".$as_codconchas."'";            
        }
        if(!empty($as_conceptocero))
        {
            $ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";            
        }
        if(!empty($as_subnomdes))
        {
            $ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";            
        }
        if(!empty($as_subnomhas))
        {
            $ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";            
        }       
        if(!empty($as_codente))
        {
            $ls_criterio= $ls_criterio."   AND sno_concepto.codente='".$as_codente."'";            
        }       
        $ls_sql="SELECT sno_concepto.codconc, MAX(sno_concepto.nomcon) AS nomcon, sno_salida.tipsal, sum(sno_salida.valsal) as monto, COUNT(sno_salida.codper) AS total, '' AS cedben                            ".                    
                "  FROM sno_salida, sno_concepto, sno_personalnomina, sno_personal, sno_rango, sno_categoria_rango, sno_componente, sno_resumen  ".
                " WHERE sno_salida.codemp='".$this->ls_codemp."'                     ".
                "   AND sno_salida.codnom='".$this->ls_codnom."'                     ".
                "   AND sno_salida.codperi='".$this->ls_peractnom."'                 ".
                $ls_criterio.
				"   AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".    
                "        sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
				"         sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4')  ".                     
                "   AND sno_resumen.monnetres > 0                ".
                "   AND sno_salida.codemp = sno_resumen.codemp                      ".
                "   AND sno_salida.codnom = sno_resumen.codnom                      ".
                "   AND sno_salida.codperi = sno_resumen.codperi                    ".
                "   AND sno_salida.codper = sno_resumen.codper                    ".
				"   AND sno_categoria_rango.codcat = '".$as_codcat."'	".
				"	AND sno_categoria_rango.codemp = sno_rango.codemp   ".
				"   AND sno_categoria_rango.codcat = sno_rango.codcat  ".
				"   AND sno_rango.codemp =  sno_personal.codemp	 ".
				"  	AND sno_rango.codcom = sno_personal.codcom	 ".
				"  	AND sno_rango.codran = sno_personal.codran	 ".
				"   AND sno_componente.codemp =  sno_personal.codemp	 ".
				"  	AND sno_componente.codcom = sno_personal.codcom	 ".
                "   AND sno_personal.codemp = sno_personalnomina.codemp            ".
                "   AND sno_personal.codper = sno_personalnomina.codper          ".                
                "   AND sno_salida.codemp = sno_concepto.codemp                      ".
                "   AND sno_salida.codnom = sno_concepto.codnom                      ".
                "   AND sno_salida.codconc = sno_concepto.codconc                    ".
                "   AND sno_salida.codemp = sno_personalnomina.codemp                ".
                "   AND sno_salida.codnom = sno_personalnomina.codnom                ".
                "   AND sno_salida.codper = sno_personalnomina.codper               ".
                " GROUP BY sno_concepto.codconc, sno_salida.tipsal                 ".                
				" ORDER BY codconc, tipsal										  ";
        $this->rs_data_detalle=$this->io_sql->select($ls_sql);
        if($this->rs_data_detalle===false)
        {
            $this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadre_concepto_neto_deduc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
            $lb_valido=false;
        }
		 return $lb_valido;  
    } // fin de uf_cuadre_concepto_neto_deduc
	//------------------------------------------------------------------------------------------------------------------------------------
		
	
	function uf_cuadre_concepto_neto_deduc_ipsfa($as_codconcdes,$as_codconchas,$as_conceptocero,
                                           $as_subnomdes,$as_subnomhas, $as_codcat,$conceptos_ipsfa='')
    {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_cuadre_concepto_neto_deduc
        //         Access: public (desde la clase sigesp_sno_rpp_netos_deduc)  
        //        Arguments: as_codconcdes // Código del concepto donde se empieza a filtrar
        //                   as_codconchas // Código del concepto donde se termina de filtrar
        //                     as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la información de los conceptos que se calculó 
        //                 la nómina agrupados por categorias militar
        //       Creado Por: Ing. Jennifer Rivero
        // Fecha Creación: 30/07/2008                                 Fecha Última Modificación :  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
        $ls_criterio="";        
        if(!empty($as_codconcdes))
        {
            $ls_criterio= "AND sno_salida.codconc>='".$as_codconcdes."'";            
        }
        if(!empty($as_codconchas))
        {
            $ls_criterio= $ls_criterio."   AND sno_salida.codconc<='".$as_codconchas."'";            
        }
        if(!empty($as_conceptocero))
        {
            $ls_criterio = $ls_criterio."   AND sno_salida.valsal<>0 ";            
        }
        if(!empty($as_subnomdes))
        {
            $ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";            
        }
        /*
		if($as_codcat=='')
        {
            $ls_criterio= $ls_criterio."   AND (sno_personal.codran='' OR  sno_personal.codcom = '' OR sno_rango.codcat IS NULL) ";            
        }
		else{
			$ls_criterio = $ls_criterio." AND sno_categoria_rango.codcat = '".$as_codcat."'";
		
		}
		*/
		 if(!empty($as_subnomhas))
        {
            $ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";            
        }         
        if(!empty($conceptos_ipsfa))
		{
			switch($conceptos_ipsfa){
			
						case 'CABISOFAC':
								$ls_criterio= $ls_criterio."  AND sno_concepto.codconc IN('0000000450','0000000444','0000000491','0000000526','0000000528','0000000552')";
								break;
						
						case 'INVERSORA_HORIZONTE':
								$ls_criterio= $ls_criterio."  AND sno_concepto.codconc IN('0000000435','0000000614','0000000615','0000000616','0000000698') ";
								break;
						
						case 'VIVIENDA_GUARNICION':
								$ls_criterio= $ls_criterio."  AND sno_concepto.codconc IN('0000000472','0000000524','0000000547','0000000682','0000000689',
																						   '0000000691','0000000692','0000000693','0000000696','0000000697',
																						   '0000000708','0000000714','0000000711','0000000723','0000000724',
																						   '0000000725') ";
								break;
						
						case 'SEGUROS_HORIZONTE':
								$ls_criterio= $ls_criterio."  AND sno_concepto.codconc IN('0000000420','0000000421','0000000422','0000000423','0000000424','0000000435',
																							   	   '0000000445','0000000446','0000000458','0000000463','0000000464','0000000465',
																								   '0000000473','0000000478','0000000480','0000000481','0000000483','0000000486',
																								   '0000000494','0000000546','0000000582') ";
								break;
					
					    case 'SISA':
								$ls_criterio= $ls_criterio."  AND sno_concepto.codconc IN('0000000001','0000000536','0000000538','0000000660','0000000661') ";
								break;
			
			}
			
		}
        $ls_sql="       SELECT sno_concepto.codconc, sno_rango.codcat, MAX(sno_concepto.nomcon) AS nomcon,  ".
                "            sno_salida.tipsal, sum(sno_salida.valsal) as monto,          ".
                "            COUNT(sno_salida.codper) AS total                            ".                    
                "       FROM sno_salida                                                   ".               
				"		INNER JOIN sno_concepto ON (sno_salida.codemp = sno_concepto.codemp 
			   										AND sno_salida.codnom = sno_concepto.codnom 
                            						AND sno_salida.codconc = sno_concepto.codconc)  
						INNER JOIN sno_personalnomina ON (sno_salida.codemp = sno_personalnomina.codemp 
														  AND sno_salida.codnom = sno_personalnomina.codnom 
														  AND sno_salida.codper = sno_personalnomina.codper )
						INNER JOIN sno_personal ON (sno_personal.codemp = sno_personalnomina.codemp 
										 			AND sno_personal.codper = sno_personalnomina.codper )
						INNER JOIN sno_resumen ON (sno_resumen.codper = sno_personalnomina.codper
												  AND  sno_salida.codnom = sno_resumen.codnom
						                          AND sno_salida.codemp = sno_resumen.codemp) ". 
				"       LEFT JOIN  sno_rango ON  (sno_rango.codemp = sno_personal.codemp  AND sno_rango.codcom= sno_personal.codcom AND sno_rango.codran = sno_personal.codran) ".
				"       LEFT JOIN  sno_categoria_rango ON (sno_categoria_rango.codemp = sno_personal.codemp AND sno_rango.codcat = sno_categoria_rango.codcat) ".
				"       LEFT JOIN  sno_componente ON (sno_componente.codemp = sno_personal.codemp  AND sno_componente.codcom = sno_personal.codcom) ". 
                "       WHERE sno_salida.codemp='".$this->ls_codemp."'                     ".
                "       AND sno_salida.codnom='".$this->ls_codnom."'                     ".
                "       AND sno_salida.codperi='".$this->ls_peractnom."'                 ".
				//"       AND sno_resumen.monnetres>0                                      ".
               	$ls_criterio.
				"       AND (sno_salida.tipsal='A' OR sno_salida.tipsal='V1' OR sno_salida.tipsal='W1' OR ".    
                "            sno_salida.tipsal='D' OR sno_salida.tipsal='V2' OR sno_salida.tipsal='W2' OR ".
				"            sno_salida.tipsal='P2' OR sno_salida.tipsal='V4' OR sno_salida.tipsal='W4')  ".
                "       GROUP BY sno_concepto.codconc, sno_salida.tipsal,sno_rango.codcat                 ".                
                "       ORDER BY sno_salida.tipsal, sno_concepto.codconc,sno_rango.codcat                 ";  
        //print $ls_sql."<br>";
        $this->rs_data_detalle=$this->io_sql->select($ls_sql);
        if($this->rs_data_detalle===false)
        {
            $this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadre_concepto_neto_deduc ERROR->".
                                        $this->io_funciones->uf_convertirmsg($this->io_sql->message));
            $lb_valido=false;
        }
       
		 return $lb_valido;  
    } // fin de uf_cuadre_concepto_neto_deduc

	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_componentes_rangos($as_codconcdes,$as_codconchas,$as_codrandes,$as_codranhas,$as_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_componentes_rangos
        //         Access: public (desde la clase sigesp_sno_rpp_asigancion_com_ran)  
        //      Arguments: 
        //        Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: 
        //     Creado Por: Ing. Jennifer Rivero
        // Fecha Creación: 05/08/2008                                 Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if (($as_codconcdes!="")&&($as_codconchas!=""))
		{
			$ls_criterio=" AND sno_componente.codcom between '".$as_codconcdes."' AND '".$as_codconchas."' ";
		}
		if (($as_codrandes!="")&&($as_codranhas!=""))
		{
			$ls_criterio=$ls_criterio." AND sno_rango.codran between '".$as_codrandes."' AND '".$as_codranhas."' ";
		}
		switch ($as_tipo)
		{
			case "RANGO":
				$ls_sql="SELECT sno_componente.codcom, sno_componente.descom, sno_rango.codran, sno_rango.desran, ".
						"       sno_categoria_rango.codcat, sno_categoria_rango.descat                            ".
						"  FROM sno_componente                                                                    ".
						"  INNER JOIN sno_rango ".
						"     ON sno_rango.codemp=sno_componente.codemp                              ".
						"    AND sno_rango.codcom=sno_componente.codcom                              ".
						"  INNER JOIN sno_categoria_rango ".
						"     ON sno_categoria_rango.codemp=sno_rango.codemp               ".
						"	  AND  sno_categoria_rango.codcat=sno_rango.codcat              ".
						"  WHERE sno_componente.codemp='".$this->ls_codemp."'".
						$ls_criterio.
						"  ORDER BY sno_componente.codcom , sno_rango.codran, sno_categoria_rango.codcat "; 
				break;
			case "CATEGORIA":
				$ls_sql="SELECT sno_componente.codcom, sno_categoria_rango.codcat, MAX(sno_componente.descom) AS descom, ".
						"		MAX(sno_categoria_rango.descat) AS descat                            ".
						"  FROM sno_componente                                                                    ".
						"  INNER JOIN sno_rango ".
						"     ON sno_rango.codemp=sno_componente.codemp                              ".
						"    AND sno_rango.codcom=sno_componente.codcom                              ".
						"  INNER JOIN sno_categoria_rango ".
						"     ON sno_categoria_rango.codemp=sno_rango.codemp               ".
						"	  AND  sno_categoria_rango.codcat=sno_rango.codcat              ".
						"  WHERE sno_componente.codemp='".$this->ls_codemp."'".
						$ls_criterio.
						"  GROUP BY sno_componente.codcom, sno_categoria_rango.codcat  ". 
						"  ORDER BY sno_componente.codcom, sno_categoria_rango.codcat "; 
				break;
		}
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_componentes_rangos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}//FIN uf_componentes_rangos()
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_asignacion_componente_rango($as_codcom,$as_codran,$as_codcat,$as_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_asignacion_componente
        //         Access: public (desde la clase sigesp_sno_rpp_asigancion_com_ran)  
        //      Arguments: 
        //        Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: 
        //     Creado Por: Ing. Jennifer Rivero
        // Fecha Creación: 06/08/2008                                 Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;	 
		$ls_criterio="";
		if ($as_tipo=='1')
		{
			$ls_criterio=" AND (sno_personal.fecleypen<'1995-07-13' ".
						 " AND sno_personal.fecleypen<>'1900-01-01')  ";      
		}
		elseif ($as_tipo=='2')
		{
			$ls_criterio=" AND (sno_personal.fecleypen>='1995-07-13' ".
						 " AND sno_personal.fecleypen<>'1900-01-01')  ";
		}
		if (!empty($as_codran))
		{
			$ls_criterio=$ls_criterio."	  AND sno_rango.codran='".$as_codran."' ";
		}
		$ls_sql="  SELECT count(sno_resumen.codper) as personas, sum(sno_resumen.asires) as monto ".
				"	 FROM sno_resumen, sno_personalnomina, sno_personal, sno_rango, sno_categoria_rango, sno_componente ". 
				"	WHERE sno_resumen.codemp='".$this->ls_codemp."'                                                     ". 
				"	  AND sno_resumen.codnom='".$this->ls_codnom."'                                                     ". 
				"	  AND sno_resumen.codperi='".$this->ls_peractnom."'                                                 ". 
				"	  AND sno_resumen.asires > 0                                                                         ".
				"	  AND sno_categoria_rango.codcat='".$as_codcat."'                                                  ".
				"	  AND sno_componente.codcom='".$as_codcom."'                                                       ".  
				$ls_criterio.
				"	  AND sno_resumen.codemp = sno_personalnomina.codemp                                                ".
				"	  AND sno_resumen.codnom = sno_personalnomina.codnom                                                ".
				"	  AND sno_resumen.codper = sno_personalnomina.codper                                                ".
				"     AND sno_personal.codemp = sno_personalnomina.codemp                                              ".
				"	  AND sno_personal.codper = sno_personalnomina.codper                                              ".
				"	  AND sno_componente.codemp= sno_personal.codemp                                                   ".
				"	  AND sno_componente.codcom= sno_personal.codcom                                                   ".
				"	  AND sno_rango.codemp = sno_personal.codemp                                                       ".  
				"	  AND sno_rango.codran = sno_personal.codran                                                       ".
				"	  AND sno_rango.codcom = sno_personal.codcom                                                       ".
				" 	  AND sno_categoria_rango.codemp = sno_rango.codemp                                                ".
				" 	  AND sno_categoria_rango.codcat = sno_rango.codcat                                                ".
				"   GROUP BY sno_personalnomina.codemp, sno_personalnomina.codnom ".
				"	ORDER BY sno_personalnomina.codnom                                                                         ";
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);	
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_asignacion_componente_rango ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}//FIN uf_asignacion_componente_rango()
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_asignacion_componente_rango_detallado($as_codcom,$as_codran,$as_codcat,$as_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_asignacion_componente_rango_detallado
        //         Access: public (desde la clase sigesp_sno_rpp_asigancion_com_ran)  
        //      Arguments: 
        //        Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: 
        //     Creado Por: Ing. Jennifer Rivero
        // Fecha Creación: 06/08/2008                                 Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;	 
		$ls_criterio="";
		if ($as_tipo=='1')
		{
			$ls_criterio=" AND (sno_personal.fecleypen<'1995-07-13' ".
						 " AND sno_personal.fecleypen<>'1900-01-01')  ";      
		}
		elseif ($as_tipo=='2')
		{
			$ls_criterio=" AND (sno_personal.fecleypen>='1995-07-13' ".
						 " AND sno_personal.fecleypen<>'1900-01-01')  ";
		}
			$ls_sql=" SELECT sum(sno_resumen.asires) as monto, sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper,           ". 
					"           sno_personalnomina.codemp, sno_personalnomina.codcar, sno_personalnomina.codnom, sno_personalnomina.codasicar,                                                               ". 
					"           (SELECT descar FROM sno_cargo                                                               ". 
					"                WHERE sno_personalnomina.codemp = sno_cargo.codemp                                     ". 
					"                  AND sno_personalnomina.codnom = sno_cargo.codnom                                     ". 
					"                  AND sno_personalnomina.codcar = sno_cargo.codcar) as descar,                         ". 
					"           (SELECT denasicar FROM sno_asignacioncargo                                                  ". 
					"                WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp                           ". 
					"                  AND sno_personalnomina.codnom = sno_asignacioncargo.codnom                           ". 
					"                  AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as denasicar                   ". 
					"   FROM sno_resumen, sno_personalnomina, sno_personal, sno_rango, sno_categoria_rango, sno_componente ". 
					"  WHERE sno_resumen.codemp='".$this->ls_codemp."'                                                     ". 
					"	 AND sno_resumen.codnom='".$this->ls_codnom."'                                                     ". 
					"	 AND sno_resumen.codperi='".$this->ls_peractnom."'                                                 ". 
					"	 AND sno_resumen.asires > 0                                                                         ".
					"	 AND sno_categoria_rango.codcat='".$as_codcat."'                                                  ".
					"	 AND sno_rango.codran='".$as_codran."'                                                            ".
					"	 AND sno_componente.codcom='".$as_codcom."'                                                       ".  
					$ls_criterio.
					"	 AND sno_resumen.codemp = sno_personalnomina.codemp                                                ".
					"	 AND sno_resumen.codnom = sno_personalnomina.codnom                                                ".
					"	 AND sno_resumen.codper = sno_personalnomina.codper                                                ".
					"    AND sno_personal.codemp = sno_personalnomina.codemp                                              ".
					"	 AND sno_personal.codper = sno_personalnomina.codper                                              ".
					"	 AND sno_componente.codemp= sno_personal.codemp                                                   ".
					"	 AND sno_componente.codcom= sno_personal.codcom                                                   ".
					"	 AND sno_rango.codemp = sno_personal.codemp                                                       ".  
					"	 AND sno_rango.codran = sno_personal.codran                                                       ".
					"	 AND sno_rango.codcom = sno_personal.codcom                                                       ".
					" 	 AND sno_categoria_rango.codemp = sno_rango.codemp                                                ".
					" 	 AND sno_categoria_rango.codcat = sno_rango.codcat                                                ".
					"  GROUP BY sno_personal.codper,sno_personal.cedper, sno_personal.nomper,             ". 
					"       sno_personal.apeper, sno_personalnomina.codemp, sno_personalnomina.codcar,                    ". 
					"       sno_personalnomina.codnom, sno_personalnomina.codasicar                                       ". 
					"    ORDER BY sno_personal.codper                                                                         "; 
		$this->rs_data_detalle=$this->io_sql->select($ls_sql);	
		if($this->rs_data_detalle===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_asignacion_componente_rango_detallado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			print $this->io_sql->message;
		}

		return $lb_valido;
	}//FIN uf_asignacion_componente_rango_detallado()
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_codigos_unico_rac($as_codasicar,&$rs_data)
    {        
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_buscar_codigos_unico_rac
        //        Arguments: 
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la informaciòn de las códigos unicos asociados a una asignación de cargo
        //       Creado Por: Ing. María Beatriz Unda
        // Fecha Creación: 03/11/2008                                 Fecha Última Modificación :          
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;            
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];            
                    
        $ls_sql=" SELECT codunirac, estcodunirac    ".                
                "  FROM sno_codigounicorac ".                
                "  WHERE sno_codigounicorac.codemp='".$ls_codemp."'  ".
				"	  AND sno_codigounicorac.codnom='".$this->ls_codnom."' ".
				"	  AND sno_codigounicorac.codasicar='".$as_codasicar."' ".
                "  ORDER BY codunirac";  
       
        $rs_data=$this->io_sql->select($ls_sql);
        if($rs_data===false)
        {
            $this->io_mensajes->message("CLASE->Report MÉTODO->uf_buscar_codigos_unico_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
            $lb_valido=false;
        }
        return $lb_valido;
    }// end function uf_buscar_codigos_unico_rac
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_cuotas ($as_codcon,$as_codper,&$as_cuota)
    {   
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_buscar_cuotas
        //        Arguments: 
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la informaciòn de las cuotas asociadas a las constantes de los conceptos 
        //       Creado Por: Ing. María Beatriz Unda
        // Fecha Creación: 08/12/2008                                 Fecha Última Modificación :          
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$as_cuota="";            
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];            
        $ls_sql=" SELECT moncon, montopcon   ".                
                "   FROM sno_constantepersonal ".                
                "  WHERE sno_constantepersonal.codemp='".$ls_codemp."'  ".
				"	 AND sno_constantepersonal.codnom='".$this->ls_codnom."' ".
				"	 AND sno_constantepersonal.codcons='".$as_codcon."' ".
				"	 AND sno_constantepersonal.codper='".$as_codper."' "; 
        $rs_data=$this->io_sql->select($ls_sql);
        if($rs_data===false)
        {
            $this->io_mensajes->message("CLASE->Report MÉTODO->uf_buscar_cuotas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
            $lb_valido=false;
        }
		else
		{
			while(!$rs_data->EOF)
			{
				 $as_cuota=$rs_data->fields["moncon"]."/".$rs_data->fields["montopcon"];
				 $rs_data->MoveNext();
			}
		}
        return $lb_valido;
    }// end function uf_buscar_cuotas
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_buscar_cuotas_general ($as_codcon,$as_codper,&$as_cuota)
    {   
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_buscar_cuotas
        //        Arguments: 
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la informaciòn de las cuotas asociadas a las constantes de los conceptos 
        //       Creado Por: Ing. María Beatriz Unda
        // Fecha Creación: 08/12/2008                                 Fecha Última Modificación :          
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$as_cuota="";            
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];            
        $ls_sql=" SELECT moncon, montopcon   ".                
                "   FROM sno_constantepersonal ".                
                "  WHERE sno_constantepersonal.codemp='".$ls_codemp."'  ".
				"	 AND sno_constantepersonal.codnom='".$this->ls_codnom."' ".
				"	 AND sno_constantepersonal.codcons='".$as_codcon."' ".
				"	 AND sno_constantepersonal.codper='".$as_codper."' "; 
        $rs_data=$this->io_sql->select($ls_sql);
        if($rs_data===false)
        {
            $this->io_mensajes->message("CLASE->Report MÉTODO->uf_buscar_cuotas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
            $lb_valido=false;
        }
		else
		{
			while(!$rs_data->EOF)
			{
				 $as_cuota=$rs_data->fields["montopcon"];
				 $rs_data->MoveNext();
			}
		}
        return $lb_valido;
    }// end function uf_buscar_cuotas
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_deduccion_mayor_asignacion ($as_codper)
    {        
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_select_deduccion_mayor_asignacion
        //        Arguments: 
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la informaciòn de las personas que tienen asignaciones mayor a las deducciones
        //       Creado Por: Ing. María Beatriz Unda
        // Fecha Creación: 10/12/2008                                 Fecha Última Modificación :          
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
				
        $ls_sql=" SELECT sno_salida.codper, ".
				" 		 (SELECT SUM(ABS(sno_salida.valsal)) FROM sno_salida  ".
				"   	   WHERE sno_salida.codemp='".$this->ls_codemp."' ".
                "            AND sno_salida.codnom='".$this->ls_codnom."' ".
				"            AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"            AND sno_salida.codper='".$as_codper."' ".
                "            AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'R'))  AS asignacion, ".
				"        (SELECT SUM(ABS(sno_salida.valsal)) FROM sno_salida  ".
				"          WHERE sno_salida.codemp='".$this->ls_codemp."' ".
                "            AND sno_salida.codnom='".$this->ls_codnom."' ".
				"            AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"            AND sno_salida.codper='".$as_codper."' ".
                "            AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )) AS deduccion,  ".
				"        (SELECT SUM(ABS(sno_salida.valsal)) FROM sno_salida  ".
				"          WHERE sno_salida.codemp='".$this->ls_codemp."' ".
                "            AND sno_salida.codnom='".$this->ls_codnom."' ".
				"            AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"            AND sno_salida.codper='".$as_codper."' ".
                "            AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')) AS aporte ". 
				"  FROM sno_salida  ".			
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.codper='".$as_codper."' ".
				" GROUP BY sno_salida.codper ".
				" ORDER BY sno_salida.codper "; 
        $this->rs_data_detalle=$this->io_sql->select($ls_sql);
        if($this->rs_data_detalle===false)
        {
            $this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_deduccion_mayor_asignacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
            $lb_valido=false;
        }
		else
		{
			if ($this->rs_data_detalle->RecordCount()==0)
			{
				$lb_valido=false;
			}
		}
        return $lb_valido;
    }// end function uf_select_deduccion_mayor_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_personal_deduccion_mayor_asignacion ($as_codperdes,$as_codperhas)
    {    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_select_personal_deduccion_mayor_asignacion
        //        Arguments: as_codperdes // código de personal desde
		//                   as_codperhas // código de personal hasta
        //          Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
        //    Description: función que busca la informaciòn de las personas que tienen asignaciones mayor a las deducciones
        //       Creado Por: Ing. María Beatriz Unda
        // Fecha Creación: 10/12/2008                                 Fecha Última Modificación :          
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_resumen.codper>='".$as_codperdes."'";
			
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_resumen.codper<='".$as_codperhas."'";
			
		}
        $ls_sql="SELECT sno_resumen.codper, sno_personal.nomper, sno_personal.apeper ".				
				"  FROM sno_resumen, sno_personal ".			
				" WHERE sno_resumen.codemp='".$this->ls_codemp."' ".
				"   AND sno_resumen.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='".$this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres<=0 ".
				$ls_criterio.
				"   AND sno_resumen.codemp= sno_personal.codemp ".
				"   AND sno_resumen.codper= sno_personal.codper ".
				" GROUP BY sno_resumen.codper, sno_personal.nomper, sno_personal.apeper  ".
				" ORDER BY sno_resumen.codper "; 
        $this->rs_data=$this->io_sql->select($ls_sql);
        if($this->rs_data===false)
        {
            $this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_personal_deduccion_mayor_asignacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
            $lb_valido=false;
        }
		else
		{
			if ($this->rs_data->RecordCount()==0)
			{
				$lb_valido=false;
			}
		}
        return $lb_valido;
    }// end function uf_select_personal_deduccion_mayor_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_encargaduria($as_codencdes,$as_codenchas,$as_estenc,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_encargaduria
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/01/2009 								Fecha Última Modificación :  		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codences))
		{
			$ls_criterio= $ls_criterio." AND sno_encargaduria.codenc>='".$as_codencdes."'";
		}
		if(!empty($as_codenchas))
		{
			$ls_criterio= $ls_criterio." AND sno_encargaduria.codenc<='".$as_codenchas."'";
		}
		switch ($as_estenc)
		{
			case "1" :
				$ls_criterio= $ls_criterio." AND sno_encargaduria.estenc='1'";
			break;
			case "2" :
				$ls_criterio= $ls_criterio." AND sno_encargaduria.estenc='2'";
			break;			
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de Encargaduría
				$ls_orden="ORDER BY sno_encargaduria.codenc ";
				break;

			case "2": // Ordena por Fecha de Inicio de la Encargaduria
				$ls_orden="ORDER BY sno_encargaduria.fecinienc ";
				break;

			case "3": // Ordena por Fecha de Finalización de la Encargaduria
				$ls_orden="ORDER BY sno_encargaduria.fecfinenc ";
				break;
		}
		$ls_sql="SELECT sno_personalnomina.codsubnom, sno_personalnomina.codasicar, sno_personalnomina.codtab, ".
				"		sno_personalnomina.codgra, sno_personalnomina.codpas, sno_personal,nomper, sno_personal.apeper,  ".
				"		sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, ".
				"       sno_personalnomina.depuniadm, sno_personalnomina.codunirac, sno_subnomina.dessubnom, ".
				"      sno_unidadadmin.desuniadm,".
				"		sno_personalnomina.prouniadm,  sno_personalnomina.codcar,  sno_personalnomina.coddep, ".
				"       (SELECT desnom FROM sno_nomina                 ".
				"         WHERE sno_nomina.codemp=sno_encargaduria.codemp             ".
				"           AND sno_nomina.codnom=sno_encargaduria.codnomperenc) AS desnomenc, ".							
				"       (SELECT srh_departamento.coddep FROM srh_departamento                 ".
				"         WHERE srh_departamento.codemp=sno_personalnomina.codemp             ".
				"           AND srh_departamento.coddep=sno_personalnomina.coddep) AS dendep, ".
				"		(SELECT descar FROM sno_cargo ".
				"		   WHERE sno_cargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_cargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_cargo.codcar = sno_personalnomina.codcar) as descar, ".
				"		(SELECT denasicar FROM sno_asignacioncargo ".
				"		   WHERE sno_asignacioncargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_asignacioncargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_asignacioncargo.codasicar = sno_personalnomina.codasicar) as denasicar, ".
				"		(SELECT destab FROM sno_tabulador ".
				"		   WHERE sno_tabulador.codemp = sno_personalnomina.codemp ".
				"			 AND sno_tabulador.codnom = sno_personalnomina.codnom ".
				"			 AND sno_tabulador.codtab = sno_personalnomina.codtab) as destab, ".			
				"		(SELECT nomper FROM sno_personal ".
				"		   WHERE sno_encargaduria.codemp = sno_personal.codemp ".
				"			 AND sno_encargaduria.codperenc = sno_personal.codper) as nomperenc, ".				
				"		(SELECT apeper FROM sno_personal ".
				"		   WHERE sno_encargaduria.codemp = sno_personal.codemp ".
				"			 AND sno_encargaduria.codperenc = sno_personal.codper) as apeperenc, ".				
				"   sno_encargaduria.codenc, sno_encargaduria.fecinienc, sno_encargaduria.fecfinenc, ".
				"   sno_encargaduria.codper, sno_encargaduria.codperenc, sno_encargaduria.codnomperenc, ".
				"   sno_encargaduria.estenc, sno_encargaduria.obsenc,sno_encargaduria.estsuspernom ".	
				"  FROM sno_personalnomina, sno_encargaduria,sno_unidadadmin,sno_personal,sno_subnomina ".			
				" 	WHERE sno_encargaduria.codemp = '".$this->ls_codemp."'".
				"   AND sno_encargaduria.codnom = '".$this->ls_codnom."' ".$ls_criterio.							
				"   AND sno_personalnomina.codemp = sno_encargaduria.codemp ".
				"   AND sno_personalnomina.codnom = sno_encargaduria.codnom ".
				"   AND sno_personalnomina.codper = sno_encargaduria.codper ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				"   AND sno_personalnomina.codemp = sno_subnomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_subnomina.codnom ".
				"	AND sno_personalnomina.codsubnom = sno_subnomina.codsubnom ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".$ls_orden;
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_select_encargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_datos_nomina_personal_encargado($as_codnomenc,$as_codperenc,&$as_coduniracenc,&$as_codsubnomenc,&$as_dessubnomenc,&$as_codasicarenc,&$as_denasicarenc,&$as_codtabenc,&$as_destabenc,&$as_codpasenc,&$as_codgraenc,&$as_codcarenc,&$as_descarenc,&$as_coduniadmenc,&$as_desuniadmenc,&$as_gradoenc,&$as_coddepenc,&$as_dendepenc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_datos_nomina_personal_encargado
		//		   Access: private
		//	    Arguments: as_codnomenc // código de nómina del personal encargado
		//                 as_codperenc // código del personal encargado
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que busca la información de la nómina del personal encargado
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/01/2009								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT  sno_personalnomina.codsubnom, sno_personalnomina.codasicar, sno_personalnomina.codtab, ".
				"		sno_personalnomina.codgra, sno_personalnomina.codpas, sno_personalnomina.codunirac,  ".
				"		sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm, sno_subnomina.dessubnom,sno_unidadadmin.desuniadm, ".
				"		sno_personalnomina.prouniadm,  sno_personalnomina.codcar,  sno_personalnomina.coddep, ".			
				"       (SELECT srh_departamento.coddep FROM srh_departamento                 ".
				"         WHERE srh_departamento.codemp=sno_personalnomina.codemp             ".
				"           AND srh_departamento.coddep=sno_personalnomina.coddep) AS dendep, ".
				"		(SELECT descar FROM sno_cargo ".
				"		   WHERE sno_cargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_cargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_cargo.codcar = sno_personalnomina.codcar) as descar, ".
				"		(SELECT denasicar FROM sno_asignacioncargo ".
				"		   WHERE sno_asignacioncargo.codemp = sno_personalnomina.codemp ".
				"			 AND sno_asignacioncargo.codnom = sno_personalnomina.codnom ".
				"			 AND sno_asignacioncargo.codasicar = sno_personalnomina.codasicar) as denasicar, ".
				"		(SELECT destab FROM sno_tabulador ".
				"		   WHERE sno_tabulador.codemp = sno_personalnomina.codemp ".
				"			 AND sno_tabulador.codnom = sno_personalnomina.codnom ".
				"			 AND sno_tabulador.codtab = sno_personalnomina.codtab) as destab ".				
				"  FROM sno_personalnomina, sno_unidadadmin,sno_subnomina ".			
				" 	WHERE sno_personalnomina.codemp = '".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom = '".$as_codnomenc."' ".
				"   AND sno_personalnomina.codper =  '".$as_codperenc."' ".		
				"   AND sno_personalnomina.codemp = sno_subnomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_subnomina.codnom ".
				"	AND sno_personalnomina.codsubnom = sno_subnomina.codsubnom ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_load_datos_nomina_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$as_coduniracenc=$rs_data->fields["codunirac"];
				$as_codsubnomenc=$rs_data->fields["codsubnom"];
				$as_dessubnomenc=$rs_data->fields["dessubnom"];
				$as_codasicarenc=$rs_data->fields["codasicar"];
				$as_denasicarenc=$rs_data->fields["denasicar"];
				$as_codcarenc=$rs_data->fields["codcar"];
				$as_descarenc=$rs_data->fields["descar"];
				$as_codtabenc=$rs_data->fields["codtab"];
				$as_destabenc=$rs_data->fields["destab"];
				$as_codgraenc=$rs_data->fields["codgra"];
				$as_codpasenc=$rs_data->fields["codpas"];								
				$as_coduniadmenc=$rs_data->fields["minorguniadm"]."-".$rs_data->fields["ofiuniadm"]."-".$rs_data->fields["uniuniadm"]."-".$rs_data->fields["depuniadm"]."-".$rs_data->fields["prouniadm"];			
				$as_desuniadmenc=$rs_data->fields["desuniadm"];				
				$as_coddepenc=$rs_data->fields["coddep"];
				$as_dendepenc=$rs_data->fields["dendep"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_datos_nomina_personal_encargado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobeneficiario_beneficiario_cheque($as_codper,$as_codban,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobeneficiario_beneficiario_cheque
		//         Access: public (desde la clase sigesp_sno_rpp_listadobeneficiario)  
		//	    Arguments: as_codper // Código del personal 
		//	  			   as_codban // Código del banco  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, porpagben, monpagben, codban, ctaban, forpagben,cedaut, numexpben,  ".
				"       (SELECT scb_banco.nomban FROM scb_banco WHERE scb_banco.codemp =  sno_beneficiario.codemp ".
				"			AND scb_banco.codban = sno_beneficiario.codban) AS nomban ".
				"  FROM sno_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				//"   AND codban='".$as_codban."' ".
				"   AND forpagben='0' ".
				" ORDER BY cedben "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobeneficiario_beneficiario_cheque ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobeneficiario_beneficiario_cheque
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_pago_por_bancos($as_codbandes,$as_codbanhas,$as_quincena,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_pago_por_bancos
		//         Access: public (desde la clase sigesp_sno_rpp_listadoconceptos)  
		//	    Arguments: as_codbandes // Código del banco donde se empieza a filtrar
		//	  			   as_codnahas // Código del banco donde se termina de filtrar		  
		//	  			   as_orden // Criterio de Orden
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del monto pagado por cada banco
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 09/01/2009								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="SUM(sno_resumen.priquires) as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="SUM(sno_resumen.segquires) as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="SUM(sno_resumen.monnetres) as monnetres";
				break;
		}		
		if(!empty($as_codbandes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codban>='".$as_codbandes."'";
		}
		if(!empty($as_codbanhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codban<='".$as_codbanhas."'";
		}
		
		switch($as_orden)
		{
			case "1": // Ordena por Código del Banco
				$ls_orden="ORDER BY codban ";
				break;

			case "2": // Ordena por Nombre del Banco
				$ls_orden="ORDER BY scb_banco.nomban ";
	 	}
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.codban, MAX(scb_banco.nomban) as nomban, ".
		        " 		MAX(sno_personalnomina.pagbanper) AS pagbanper, MAX(sno_personalnomina.tipcuebanper) AS tipcuebanper, ".
				"		MAX(sno_personalnomina.pagtaqper) AS pagtaqper, ".$ls_monto." ".
				"  FROM sno_personalnomina, scb_banco, sno_resumen ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='".$this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0 ".
				"   AND ((sno_personalnomina.pagbanper = 1 AND sno_personalnomina.pagtaqper = 0  ".
				"   AND (sno_personalnomina.tipcuebanper = 'A' OR sno_personalnomina.tipcuebanper = 'C' OR sno_personalnomina.tipcuebanper = 'L')) ".
				"    OR  (sno_personalnomina.pagbanper = 0 AND sno_personalnomina.pagtaqper = 1)) ".
				"   AND sno_personalnomina.pagefeper = 0 ".
				"   ".$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"   AND scb_banco.codemp = sno_personalnomina.codemp ".
				"   AND scb_banco.codban = sno_personalnomina.codban ".
				" GROUP BY sno_personalnomina.codban, sno_personalnomina.codper, scb_banco.nomban ".
				" UNION ".
				"SELECT sno_personalnomina.codper, '---' AS codban, 'ERROR EN BANCO' as nomban, ".
		        " 		MAX(sno_personalnomina.pagbanper) AS pagbanper, 'A' AS tipcuebanper, ".
				"		MAX(sno_personalnomina.pagtaqper) AS pagtaqper, ".$ls_monto." ".
				"  FROM sno_personalnomina, sno_resumen ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='".$this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0 ".
				"   AND sno_personalnomina.pagbanper = 1 ".
				"   AND sno_personalnomina.pagefeper = 0 ".
				"   AND (sno_personalnomina.codban='' ".
				"    OR (sno_personalnomina.codban<>'' AND sno_personalnomina.tipcuebanper = '') ".
				"    OR sno_personalnomina.codban NOT IN (SELECT codban FROM scb_banco)) ".
				"   ".$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				" GROUP BY sno_personalnomina.codban, sno_personalnomina.codper ".
				"  ".$ls_orden;
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_pago_por_bancos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_pago_por_bancos
	//----------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_ubicacion_fisica($as_codorg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_ubicacion_fisica
		//		   Access: public
		//	  Description: Función que obtiene ela ubicacion física del personal según el organigrama
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 09/01/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		$ls_ubifis="";
		$ls_sql="SELECT codorg, desorg, nivorg, padorg ".				
				"  FROM srh_organigrama ".
				" WHERE srh_organigrama.codemp='".$this->ls_codemp."' ".
				"   AND srh_organigrama.codorg='".$as_codorg."' ".
				"   AND srh_organigrama.codorg <> '----------' ";	
											
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			$lb_hay=$rs_data->RecordCount();
			$li_i=1;
			while(!$rs_data->EOF)
			{
				$ls_codorg=$rs_data->fields["codorg"];
				$ls_desorg=$rs_data->fields["desorg"];
				$ls_nivorg=$rs_data->fields["nivorg"];					
				$ls_padorg=$rs_data->fields["padorg"];
				$la_data[$li_i]=array('cod'=>$ls_codorg,'des'=>$ls_desorg);				
				if ($ls_nivorg<>0)
				{
					for($i=$ls_nivorg;($i>0);$i--)
					{
						$ls_codorgsup=$ls_padorg;
						$this->uf_buscar_padre($ls_codorgsup,$ls_despadorg,$ls_nivpadorg,$ls_padorg);
						$li_i=$li_i+1;
						$la_data[$li_i]=array('cod'=>$ls_codorgsup,'des'=>$ls_despadorg);
					}
				}	
				for($j=$li_i;$j>0;$j--)
				{
					if ($j==$li_i)
					{
						$ls_ubifis=$la_data[$j]['des'];
					}
					else
					{						
						$ls_ubifis=$ls_ubifis.' - '.$la_data[$j]['des'];
					}
				}							
			
				$rs_data->MoveNext();
			}
		}
		return $ls_ubifis;
   }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_padre($as_codorg,&$as_desorg,&$as_nivorg,&$as_padorg)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:uf_buscar_padre
		//		   Access: public
		//	  Description: Función que obtiene e imprime los conceptos a pagar por encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 05/01/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lb_valido=true;
		$ls_sql="SELECT codorg, desorg, nivorg, padorg ".				
				"  FROM srh_organigrama ".
				" WHERE srh_organigrama.codemp='".$ls_codemp."' ".
				"   AND srh_organigrama.codorg='".$as_codorg."' ".
				"   AND srh_organigrama.codorg <> '----------' ";	
		$rs_data2=$this->io_sql->select($ls_sql);
		if($rs_data2===false)
		{
			$this->io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while(!$rs_data2->EOF)
			{
				$ls_codorg=$rs_data2->fields["codorg"];
				$as_desorg=$rs_data2->fields["desorg"];
				$as_nivorg=$rs_data2->fields["nivorg"];					
				$as_padorg=$rs_data2->fields["padorg"];
				$rs_data2->MoveNext();
			}
		}
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	///-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_deducciones($as_codnom, $as_codperi,$as_codconc)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_deducciones
		//		   Access: public
		//	  Description: Función que obtiene e imprime los conceptos deduccion
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 21/01/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lb_valido=true;
		$ls_monto=0;
		$ls_sql="  SELECT sno_concepto.codconc, MAX(sno_concepto.nomcon) AS nomcon,
                          sum(sno_salida.valsal) as monto
				    FROM sno_salida, sno_concepto, sno_personalnomina, sno_personal 
				   WHERE sno_salida.codemp='".$ls_codemp."' 
					 AND sno_salida.codnom='".$as_codnom."' 
					 AND sno_salida.codperi='".$as_codperi."' 
					 AND sno_salida.valsal<>0 
					 AND (sno_salida.tipsal='D') 
					 AND (sno_concepto.codconc='".$as_codconc."')
					 AND sno_salida.codemp = sno_concepto.codemp 
					 AND sno_salida.codnom = sno_concepto.codnom 
					 AND sno_salida.codconc = sno_concepto.codconc 
					 AND sno_salida.codemp = sno_personalnomina.codemp 
					 AND sno_salida.codnom = sno_personalnomina.codnom 
					 AND sno_salida.codper = sno_personalnomina.codper 
					 AND sno_personal.codemp = sno_personalnomina.codemp 
					 AND sno_personal.codper = sno_personalnomina.codper 
				 GROUP BY sno_concepto.codconc, sno_salida.tipsal 
				 ORDER BY sno_concepto.codconc, sno_salida.tipsal";
		$rs_data3=$this->io_sql->select($ls_sql);
		if($rs_data3===false)
		{
			$this->io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{			
			while(!$rs_data3->EOF)
			{
				$ls_codconc=$rs_data3->fields["codconc"];
				$ls_nomcom=$rs_data3->fields["nomcon"];
				$ls_monto=$rs_data3->fields["monto"];				
				$rs_data3->MoveNext();
			}
		}
		return $ls_monto;	
	}///  fin uf_buscar_deducciones
	///-----------------------------------------------------------------------------------------------------------------------------------

	///------------------------------------------------------------------------------------------------------------------------------------
    function uf_deduccion_categorias($as_codnom, $as_codperi)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_deducciones
		//		   Access: public
		//	  Description: Función que obtiene e imprime los conceptos deduccion
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 21/01/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$lb_valido=true;
		$ls_sql="SELECT sum(sno_salida.valsal) as monto       
				  FROM sno_salida, sno_concepto, sno_personalnomina, sno_personal, sno_rango, sno_categoria_rango 
				  WHERE sno_salida.codemp='".$ls_codemp."' 
					AND sno_salida.codnom='".$as_codnom."'
					AND sno_salida.codperi='".$as_codperi."' 
					AND sno_salida.valsal<>0 
					AND (sno_salida.tipsal='D') 
					AND (sno_salida.codconc='0000000410' or sno_salida.codconc='0000000411' or sno_salida.codconc='0000000413')  
					AND sno_salida.codemp = sno_concepto.codemp 
					AND sno_salida.codnom = sno_concepto.codnom 
					AND sno_salida.codconc = sno_concepto.codconc 
					AND sno_salida.codemp = sno_personalnomina.codemp 
					AND sno_salida.codnom = sno_personalnomina.codnom 
					AND sno_salida.codper = sno_personalnomina.codper 
					AND sno_personal.codemp = sno_personalnomina.codemp 
					AND sno_personal.codper = sno_personalnomina.codper 
					AND sno_rango.codemp = sno_personal.codemp 
					AND sno_rango.codran = sno_personal.codran 
					AND sno_rango.codcom = sno_personal.codcom 
					AND sno_categoria_rango.codemp = sno_rango.codemp 
					AND sno_categoria_rango.codcat = sno_rango.codcat ";
		$rs_data4=$this->io_sql->select($ls_sql);
		if($rs_data4===false)
		{
			$this->io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{			
			while(!$rs_data4->EOF)
			{
				$ls_monto=$rs_data4->fields["monto"];				
				$rs_data4->MoveNext();
			}
		}
		return $ls_monto;		
	}// fin uf_deduccion_categorias
	//-------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_personal_nomina_exterior($as_codperdes,$as_codperhas,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_personal_nomina_exterior
		//         Access: public (desde la clase sigesp_sno_rpp_listadobeneficiario)  
		//	    Arguments: as_codper // Código del personal 
		//	  			   as_codban // Código del banco  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."    AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}

		$ls_sql="SELECT cedper, nomper, apeper,SUM(sno_salida.valsal) as monto   ".
			"  FROM sno_personal, sno_salida,sno_personalnomina ".
			" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
			"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
			"   AND sno_personalnomina.codemp=sno_personal.codemp ".
			"   AND sno_personalnomina.codper=sno_personal.codper ".
			"   AND sno_personalnomina.codemp=sno_salida.codemp ".	
			"   AND sno_personalnomina.codnom=sno_salida.codnom ".
			"   AND sno_personalnomina.codper=sno_salida.codper ".
            "   AND sno_personalnomina.codban='011' ".
			"   GROUP BY cedper, nomper, apeper ".
			" ORDER BY cedper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_personal_nomina_exterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_personal_nomina_exterior
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobeneficiario_banco($as_codperdes,$as_codperhas,$as_quincena,$as_codban,$as_subnomdes,$as_subnomhas,$as_orden,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobeneficiario_banco
		//         Access: public (desde la clase sigesp_sno_rpp_listadoconceptos)  
		//	    Arguments: as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_quincena // Quincena para el cual se quiere filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/02/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		$ls_sql="SELECT sno_personalnomina.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personalnomina.sueper ".
				"  FROM sno_personalnomina, sno_beneficiario, sno_personal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".$ls_criterio.
				"   AND sno_personalnomina.codban='".$as_codban."'  ".
				"	AND sno_beneficiario.codemp = sno_personalnomina.codemp ".
				"	AND sno_beneficiario.codper = sno_personalnomina.codper ".
				"	AND sno_personalnomina.codemp = sno_personal.codemp ".
				"	AND sno_personalnomina.codper = sno_personal.codper ".
				" ORDER BY sno_personalnomina.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobeneficiario_banco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobeneficiario_banco
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_resumen_pagosterceros($as_codentdes,$as_codenthas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_resumen_pagosterceros
		//         Access: public (desde la clase sigesp_sno_rpp_resumenconceptos)  
		//	    Arguments: as_codentdes // Código del ente donde se empieza a filtrar
		//				   as_codenthas // Código del ente donde se termina de filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se asocian a un ente
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/02/2009 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codentdes))
		{
			$ls_criterio= "  AND sno_entes.codigo_ente>='".$as_codentdes."'";
		}
		if(!empty($as_codenthas))
		{
			$ls_criterio= $ls_criterio."   AND sno_entes.codigo_ente<='".$as_codenthas."'";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código del Ente
				$ls_orden="ORDER BY sno_entes.codigo_ente ";
				break;

			case "2": // Ordena por Nombre del Ente
				$ls_orden="ORDER BY  sno_entes.descripcion_ente ";
	 	}
		$ls_sql="SELECT sno_entes.codigo_ente, sno_entes.descripcion_ente AS ente, sno_entes.porcentaje_ente  AS porc, ".
		        "  		sum(sno_salida.valsal) as monto ".			
				"  FROM sno_entes, sno_concepto, sno_salida ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".			
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".				
				"   AND sno_entes.codigo_ente = sno_concepto.codente ".
				$ls_criterio.			
				" GROUP BY sno_entes.codigo_ente,sno_entes.descripcion_ente,sno_entes.porcentaje_ente ".$ls_orden;
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_resumen_pagosterceros ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_resumen_pagosterceros
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
