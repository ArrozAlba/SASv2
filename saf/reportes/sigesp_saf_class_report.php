<?php
class sigesp_saf_class_report
{
	var $obj="";
	var $io_sql;
	var $ds;
	var $ds_detalle;
	var $siginc;
	var $con;

	function sigesp_saf_class_report()
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
		$this->ds_detcontable=new class_datastore();
	}


	function uf_saf_load_movimiento($as_codemp,$as_cmpmov,$ad_fecdes,$ad_fechas,$as_tipcau,$as_coddes,$as_codhas,$ai_orden)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_movimiento
	//	           Access:   public
	//  		Arguments:   as_codemp  // codigo de empresa
	//  			         as_cmpmov  // comprobante de movimiento
	//  			         ad_fecdes  // fecha de inicio del periodo de busqueda
	//  			         ad_fechas  // fecha de cierre del periodo de busqueda
	//  			         as_tipcau  // tipo de la causa de movimiento
	//  			         as_coddes  // inicio de parametro de busqueda (codigo de activo)
	//  			         as_codhas  // cierre de parametro de busqueda (codigo de activo)
	//  			         ai_orden   // parametro que indica el orden de los resultados del reporte
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los datos maestros de un movimientos
	//         Creado por:   Ing. Luis Anibal Lang
	//     Modificado por:   Ing. Yozelin Barragan           
	//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 09/06/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		if((!empty($ad_fecdes))&&(!empty($ad_fechas)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
			$ls_sqlint=" AND saf_movimiento.feccmp >='". $ld_auxdesde ."'".
					   " AND saf_movimiento.feccmp <='". $ld_auxhasta ."'";
		}
		if(!empty($as_cmpmov))
		{
			$ls_sqlint= $ls_sqlint." AND saf_movimiento.cmpmov='".$as_cmpmov."'";
		}
		if(($as_coddes!="")&&($as_codhas!=""))
		{
			$ls_sqlint=$ls_sqlint." AND saf_dt_movimiento.codact >= '".$as_coddes."'".
								  " AND saf_dt_movimiento.codact <= '".$as_codhas."' ";
		}
		$ls_sql="SELECT saf_movimiento.cmpmov,saf_movimiento.codcau,saf_movimiento.descmp,saf_movimiento.feccmp,saf_causas.dencau".
				"  FROM saf_movimiento,saf_causas,saf_dt_movimiento".
				" WHERE saf_movimiento.codcau=saf_causas.codcau".
				"   AND saf_causas.tipcau='".$as_tipcau."'".
				"   AND saf_causas.estcat='".$ls_estcat."'".
				"   AND saf_movimiento.codemp='".$as_codemp."'".
				"   AND saf_dt_movimiento.cmpmov=saf_movimiento.cmpmov".$ls_sqlint.
				" GROUP BY saf_movimiento.cmpmov,saf_movimiento.codcau,saf_movimiento.descmp,saf_causas.dencau,".
				"          saf_movimiento.feccmp";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY saf_movimiento.cmpmov ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY saf_movimiento.cmpmov DESC";
			}
		}
		$rs_data=$this->io_sql->select($ls_sql);
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}// fin function uf_saf_load_movimiento

	function uf_saf_load_dt_movimiento($as_codemp,$as_cmpmov,$as_codcau)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_movimiento
	//	           Access:   public
	//  		Arguments:   as_codemp  // codigo de empresa
	//  			         as_cmpmov  // comprobante de movimiento
	//  			         as_codcau  // codigo de causa de movimiento
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles de un movimiento
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 09/06/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sql=" SELECT saf_dt_movimiento.*,".
				"        (SELECT denact".
				"           FROM saf_activo".
				"          WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact".
				"  FROM saf_dt_movimiento".
				" WHERE codemp='".$as_codemp."' ".
				"   AND cmpmov='".$as_cmpmov."' ".
				"   AND codcau='".$as_codcau."' ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}// fin function uf_saf_load_dt_movimiento

	function uf_saf_load_dt_contable($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_contable
	//	           Access:   public
	//  		Arguments:   as_codemp  // codigo de empresa
	//  			         as_cmpmov  // comprobante de movimiento
	//  			         as_codcau  // codigo de causa de movimiento
	//  			         ad_feccmp  // fecha del comprobante 
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles contables de un movimiento
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 09/06/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_contable".
				" WHERE codemp='". $as_codemp ."'".
				"   AND cmpmov='". $as_cmpmov ."'".
				"   AND codcau='". $as_codcau ."'".
				"   AND feccmp='". $ad_feccmp ."'";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_contable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detcontable->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_dt_contable

	function uf_siv_load_dt_movreasignacion($as_codemp,$as_cmpmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_movreasignacion
		//         Access: private
		//      Argumento: as_codemp    // codigo de empresa
		//  			   as_cmpmov    // No de comprobante de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles asociados a una reasignacion de activos en la tabla saf_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/04/2006							Fecha Última Modificación : 10/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sql="SELECT saf_dt_traslado.*, saf_dt_movimiento.desmov,saf_dt_movimiento.monact,".
				"      (SELECT feccmp".
				"         FROM saf_movimiento".
				"        WHERE saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov".
				"          AND saf_movimiento.codemp=saf_dt_movimiento.codemp) AS feccmp,".
				"      (SELECT descmp".
				"         FROM saf_movimiento".
				"        WHERE saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov ".
				"          AND saf_movimiento.codemp=saf_dt_movimiento.codemp) AS descmp,".
				"      (SELECT codcau".
				"         FROM saf_movimiento".
				"        WHERE saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov".
				"          AND saf_movimiento.codemp=saf_dt_movimiento.codemp) AS codcau,".
				"      (SELECT dencau".
				"         FROM saf_causas,saf_movimiento".
				"        WHERE saf_causas.codcau=saf_movimiento.codcau".
				"          AND saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov AND saf_causas.estcat='".$ls_estcat."') AS dencau,".
				"      (SELECT denact".
				"         FROM saf_activo".
				"        WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact".
				"  FROM saf_dt_traslado,saf_dt_movimiento".
				" WHERE saf_dt_traslado.cmpmov=saf_dt_movimiento.cmpmov".
				"   AND saf_dt_traslado.codact=saf_dt_movimiento.codact".
				"   AND saf_dt_traslado.ideact=saf_dt_movimiento.ideact".
				"   AND saf_dt_movimiento.codemp='". $as_codemp ."'".
				"   AND saf_dt_movimiento.cmpmov='". $as_cmpmov ."'";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->reporte MÉTODO->uf_siv_load_dt_movreasignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
		}//else
		return $lb_valido;
	} // end function uf_siv_load_dt_movreasignacion

	function uf_saf_load_modificacion($as_codemp,$as_cmpmov,$as_coddes,$as_codhas,$ad_fecdes,$ad_fechas,$ai_orden)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_modificacion
	//	           Access:   public
	//  		Arguments:   as_codemp  // codigo de empresa
	//  			         as_cmpmov  // comprobante de movimiento
	//  			         ad_fecdes  // fecha de inicio del periodo de busqueda
	//  			         ad_fechas  // fecha de cierre del periodo de busqueda
	//  			         as_coddes  // inicio de parametro de busqueda (codigo de activo)
	//  			         as_codhas  // cierre de parametro de busqueda (codigo de activo)
	//  			         ai_orden   // parametro que indica el orden de los resultados del reporte
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los datos maestros de un movimientos
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 09/06/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sql="SELECT saf_movimiento.*,".
				"       (SELECT codact".
				"          FROM saf_partes".
				"         WHERE saf_movimiento.cmpmov=saf_partes.cmpmov".
				"           AND saf_movimiento.codemp=saf_partes.codemp".
				"         GROUP BY cmpmov,codact) AS codact,".
				"       (SELECT ideact".
				"          FROM saf_partes".
				"         WHERE saf_movimiento.cmpmov=saf_partes.cmpmov".
				"           AND saf_movimiento.codemp=saf_partes.codemp".
				"         GROUP BY cmpmov,ideact) AS ideact,".
				"       (SELECT dencau".
				"          FROM saf_causas".
				"         WHERE saf_movimiento.codcau=saf_causas.codcau AND saf_causas.estcat='".$ls_estcat."') AS dencau,".
				"       (SELECT denact".
				"          FROM saf_activo,saf_partes".
				"         WHERE saf_activo.codact=saf_partes.codact".
				"           AND saf_partes.cmpmov=saf_movimiento.cmpmov".
				"         GROUP BY cmpmov,denact) AS denact".
				"  FROM saf_movimiento,saf_dt_movimiento".
				" WHERE saf_movimiento.cmpmov IN (SELECT cmpmov FROM saf_partes GROUP BY cmpmov)".
				"   AND saf_movimiento.cmpmov=saf_dt_movimiento.cmpmov".
				"   AND saf_movimiento.codemp='".$as_codemp."'";
		if($as_cmpmov)
		{
			$ls_sql=$ls_sql." AND saf_movimiento.cmpmov= '".$as_cmpmov."'";
		}
		if(($as_coddes!="")&&($as_codhas!=""))
		{
			$ls_sql=$ls_sql." AND saf_dt_movimiento.codact >= '".$as_coddes."'".
							" AND saf_dt_movimiento.codact <= '".$as_codhas."' ";
		}
		if((!empty($ad_fecdes))&&(!empty($ad_fechas)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
			$ls_sql=$ls_sql." AND saf_movimiento.feccmp >='". $ld_auxdesde ."'".
							" AND saf_movimiento.feccmp <='". $ld_auxhasta ."'";
		}
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY saf_movimiento.cmpmov";
			}
			else
			{
				$ls_sql=$ls_sql."ORDER BY saf_dt_movimiento.codact";
			}
		}

		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			print($this->io_sql->message);
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_modificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}// fin function uf_saf_load_modificacion

	function uf_saf_load_dt_modificacion($as_codemp,$as_cmpmov,$as_codact,$as_ideact)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_modificacion
	//	           Access:   public
	//  		Arguments:   as_codemp  // codigo de empresa
	//  			         as_cmpmov  // comprobante de movimiento
	//  			         as_codact  // codigo de activo
	//  			         as_ideact  // identificador de activo
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles de un movimiento
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 09/06/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_partes".
				" WHERE codemp='". $as_codemp ."'".
				"   AND cmpmov='". $as_cmpmov ."'".
				"   AND codact='". $as_codact ."'".
				"   AND ideact='". $as_ideact ."'";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_modificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}// fin function uf_saf_load_dt_modificacion

	function uf_saf_load_activos($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta,$as_status,$as_codrespri,
								 $as_codresuso,$as_coduniadm)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_activos
		//	           Access:   public
		//  		Arguments:   $as_codemp    // codigo de empresa
		//  			         $ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         $ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         $ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         $as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         $as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//  			         $as_status    // estatus del activo 
		//  			         $as_codrespri // codigo de responsable primario
		//  			         $as_codresuso // codigo de responsable por uso
		//  			         $as_coduniadm // codigo de uniadad ejecutora
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte de activos fijos, en base a los parametros indicados
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   14/06/2006							Fecha de Ultima Modificación:   07/11/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT saf_activo.codact,saf_activo.denact,saf_activo.maract,saf_activo.modact,saf_activo.feccmpact,".
				"       saf_activo.costo".
				"  FROM saf_activo,saf_dta".
				" WHERE saf_activo.codemp='".$as_codemp."'".
				"   AND saf_activo.codemp=saf_dta.codemp".
				"   AND saf_activo.codact=saf_dta.codact";

		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sql=$ls_sql." AND saf_activo.codact >='". $as_coddesde ."'".
						    " AND saf_activo.codact <='". $as_codhasta ."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sql=$ls_sql." AND saf_activo.feccmpact >='". $ld_auxdesde ."'".
							" AND saf_activo.feccmpact <='". $ld_auxhasta ."'";
		}
		if(!empty($as_codrespri))
		{
			$ls_sql=$ls_sql." AND saf_dta.codrespri='".$as_codrespri."'";
		}
		if(!empty($as_codresuso))
		{
			$ls_sql=$ls_sql." AND saf_dta.codres='".$as_codresuso."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_sql=$ls_sql." AND saf_dta.coduniadm='".$as_coduniadm."'";
		}
		if($ai_ordenact==0)
		{$ls_order="codact";}
		else
		{$ls_order="denact";}
		if($as_status==1){$ls_sql=$ls_sql." AND saf_dta.estact='R'";}
		if($as_status==2){$ls_sql=$ls_sql." AND saf_dta.estact='I'";}
		if($as_status==3){$ls_sql=$ls_sql." AND saf_dta.estact='A'";}
		if($as_status==4){$ls_sql=$ls_sql." AND saf_dta.estact='M'";}
		if($as_status==5){$ls_sql=$ls_sql." AND saf_dta.estact='C'";}
		if($as_status==6){$ls_sql=$ls_sql." AND saf_dta.estact='D'";}
		
		$ls_sql=$ls_sql." GROUP BY saf_activo.codact,saf_activo.denact,saf_activo.maract,saf_activo.modact,saf_activo.feccmpact,".
				        "          saf_activo.costo".
						" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_activos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_activos
	
	function uf_saf_select_dt_activo($as_codemp,$as_codact,$as_status)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:  uf_saf_select_dt_activo
		//	           Access:  public
		//  		Arguments:  as_codemp     // codigo de empresa
		//  			        as_codact    // codigo de activo
		//  			        as_status    // parametro de busqueda. Estatus del activo
		//	         Returns :  Retorna un Booleano
		//	      Description:  Función que se encarga de obtener los detalles de un activo
		//         Creado por:  Ing. Luis Anibal Lang           
		//   Fecha de Cracion:  14/06/2006							Fecha de Ultima Modificación: 14/06/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->con);
		$ls_sqlest="";
		if($as_status==1){$ls_sqlest=" AND saf_dta.estact='R'";}
		if($as_status==2){$ls_sqlest=" AND saf_dta.estact='I'";}
		if($as_status==3){$ls_sqlest=" AND saf_dta.estact='A'";}
		if($as_status==4){$ls_sqlest=" AND saf_dta.estact='M'";}
		if($as_status==5){$ls_sqlest=" AND saf_dta.estact='C'";}
		if($as_status==6){$ls_sqlest=" AND saf_dta.estact='D'";}
		$ls_sql="SELECT saf_dta.ideact,saf_dta.seract,saf_dta.idchapa,saf_dta.fecincact,saf_dta.fecdesact,saf_dta.estact,".
				"       (SELECT nomper FROM sno_personal ".
				"         WHERE saf_dta.codrespri=sno_personal.codper) AS nomrespri,".
				"       (SELECT apeper FROM sno_personal ".
				"         WHERE saf_dta.codrespri=sno_personal.codper) AS aperespri,".
				"       (SELECT nomper FROM sno_personal ".
				"         WHERE saf_dta.codres=sno_personal.codper) AS nomres,".
				"       (SELECT apeper FROM sno_personal ".
				"         WHERE saf_dta.codres=sno_personal.codper) AS aperes,".
				"       (SELECT denuniadm FROM spg_unidadadministrativa ".
				"         WHERE saf_dta.coduniadm=spg_unidadadministrativa.coduniadm) AS denuniadm".
				"  FROM saf_dta".
				" WHERE saf_dta.codemp='". $as_codemp ."'".
				$ls_sqlest.
				"   AND saf_dta.codact='". $as_codact ."'";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_select_dt_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
	} //fin  function uf_saf_select_dt_activo

	function uf_saf_load_depactivos($as_codemp,$ai_ordenact,$as_coddesde,$as_codhasta)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_depactivos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte de depreciacion de activos fijos
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   14/06/2006							Fecha de Ultima Modificación:   15/06/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlcod="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlcod=" AND codact >='". $as_coddesde ."' AND codact <='". $as_codhasta ."'";
		}
		
		if($ai_ordenact==0)
		{
			$ls_order="codact";
		}
		else
		{
			$ls_order="denact";
		}
		$ls_sql="SELECT saf_depreciacion.codact,saf_depreciacion.ideact,".
				"      (SELECT denact FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS denact,".
				"      (SELECT costo FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS costo,".
				"      (SELECT vidautil FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS vidautil,".
				"      (SELECT cossal FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS cossal,".
				"      (SELECT feccmpact FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS feccmpact,".
				"      (SELECT fecincact FROM saf_dta".
				"        WHERE saf_dta.codact=saf_depreciacion.codact".
				"          AND saf_dta.ideact=saf_depreciacion.ideact".
				"          AND saf_dta.codemp=saf_depreciacion.codemp".
				"        GROUP BY saf_dta.codemp,saf_depreciacion.ideact,saf_depreciacion.codact,saf_dta.fecincact) AS fecincact".
				"  FROM saf_depreciacion".
				" WHERE codemp='".$as_codemp."'".
				$ls_sqlcod.
				" GROUP BY saf_depreciacion.codemp,saf_depreciacion.codact,saf_depreciacion.ideact".
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_depactivos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_depactivos
	
	function uf_saf_select_dt_depactivo($as_codemp,$as_codact,$as_ideact)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:  uf_saf_select_dt_depactivo
		//	           Access:  public
		//  		Arguments:  as_codemp  // codigo de empresa
		//  			        as_codact  // codigo de activo
		//  			        as_ideact  // identificador de activo
		//	         Returns :  Retorna un Booleano
		//	      Description:  Función que se encarga de obtener los detalles de un activo
		//         Creado por:  Ing. Luis Anibal Lang           
		//   Fecha de Cracion:  14/06/2006							Fecha de Ultima Modificación: 14/06/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->con);
		$ls_sqlest="";
		$ls_sql="SELECT * FROM saf_depreciacion".
				" WHERE codemp='".$as_codemp."'".
				" AND codact='".$as_codact."'".
				" AND ideact='".$as_ideact."'";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_select_dt_depactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
	} //fin  function uf_saf_select_dt_depactivo
	
	
	function uf_saf_load_depmensual($as_codemp,$ai_ordenact,$ad_fecdep)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_depactivos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_fecdep    // fecha de busqueda de la depreciacion
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte de depreciacion de activos fijos
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   07/08/2006							Fecha de Ultima Modificación:   07/08/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ad_fecdep=$this->io_funcion->uf_convertirdatetobd($ad_fecdep);
		if($ai_ordenact==0)
		{
			$ls_order="codact";
		}
		else
		{
			$ls_order="denact";
		}
		$ls_sql="SELECT saf_depreciacion.codact,saf_depreciacion.ideact,saf_depreciacion.mondepmen,saf_depreciacion.mondepacu,".
				"      (SELECT denact FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS denact,".
				"      (SELECT costo FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS costo,".
				"      (SELECT vidautil FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS vidautil,".
				"      (SELECT cossal FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS cossal,".
				"      (SELECT feccmpact FROM saf_activo".
				"        WHERE saf_activo.codact=saf_depreciacion.codact".
				"          AND saf_activo.codemp=saf_depreciacion.codemp) AS feccmpact,".
				"      (SELECT fecincact FROM saf_dta".
				"        WHERE saf_dta.codact=saf_depreciacion.codact".
				"          AND saf_dta.ideact=saf_depreciacion.ideact".
				"          AND saf_dta.codemp=saf_depreciacion.codemp".
				"        GROUP BY saf_depreciacion.ideact,saf_depreciacion.codact,saf_dta.fecincact) AS fecincact".
				"  FROM saf_depreciacion".
				" WHERE codemp='".$as_codemp."'".
				"   AND fecdep='".$ad_fecdep."'".
				" GROUP BY codemp,codact,ideact,mondepmen,mondepacu".
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_depactivos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_depactivos

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////                  Listado de Catalogo SIGECOF                            //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_sigecof($as_codemp,$ai_ordenact,$as_coddesde,$as_codhasta)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_sigecof
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el listado de SIGECOF
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   06/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlcod="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlcod=" WHERE catalogo >='". $as_coddesde ."' AND catalogo <='". $as_codhasta ."'";
		}
		
		if($ai_ordenact==0)
		{
			$ls_order="catalogo";
		}
		else
		{
			$ls_order="dencat";
		}
		$ls_sql="SELECT * FROM saf_catalogo".
				$ls_sqlcod.
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_sigecof ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_sigecof

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////                  Listado de Activos Fijos                               //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_defactivos($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta,$as_codresuso)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_defactivos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el listado de activos fijos, en base a los parametros indicados
		//         Creado por:   Ing. Luis Anibal Lang  
		//    Modificacdo por:   Ing. Yozelin Barragan      
		//   Fecha de Cracion:   25/09/2006							Fecha de Ultima Modificación: 03/09/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND saf_activo.codact >='".$as_coddesde."'".
					   " AND saf_activo.codact <='".$as_codhasta."'";
		}
		
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=$ls_sqlint." AND saf_activo.feccmpact >='". $ld_auxdesde ."'".
							      " AND saf_activo.feccmpact <='". $ld_auxhasta ."'";
		}
		if($ai_ordenact==0)
		{
			$ls_order="saf_activo.codact";
		}
		else
		{
			$ls_order="saf_activo.denact";
		}
		if(!empty($as_codresuso))
		{
			$ls_sqlint=$ls_sqlint."saf_dta.codres='".$as_codresuso."'  AND ";
		}
		$ls_sql=" SELECT saf_activo.codact,saf_activo.denact,saf_activo.maract,saf_activo.modact, saf_dta.estact, ".
                "        saf_activo.catalogo,saf_activo.costo,saf_activo.feccmpact,saf_dta.codres, ".
				"        (SELECT nomper FROM sno_personal WHERE saf_dta.codres=sno_personal.codper) AS nomres, ".
				"        (SELECT apeper FROM sno_personal WHERE saf_dta.codres=sno_personal.codper) AS aperes  ".
				" FROM   saf_activo, saf_dta ".
				" WHERE  saf_activo.codemp='".$as_codemp."'  AND ".
				"        saf_dta.codemp=saf_activo.codemp    AND ".
				"        saf_dta.codact=saf_activo.codact  ".$ls_sqlint.
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_defactivos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_defactivos
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////                  Comprobante de Incorporacion                           //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_unidadadministrativas($as_codemp,$as_coduniadm,&$as_denuniadm)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_unidadadministrativas
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_coduniadm // codigo de unidad administrativa
		//  			         as_denuniadm // denominacion de la unidad administrativa
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener la denominacion de una unidad ejecutora
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   26/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT denuniadm".
				" FROM spg_unidadadministrativa".
				" WHERE codemp='".$as_codemp."'".
				" AND   coduniadm='".$as_coduniadm."'";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_unidadadministrativas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_denuniadm= $row["denuniadm"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_unidadadministrativas

	function uf_saf_load_responsable($as_codemp,$as_codper,&$as_nomper,&$as_cedper,&$as_cargo)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_responsable
		//	           Access:   public
		//  		Arguments:   as_codemp // codigo de empresa
		//  			         as_codper // codigo de personal
		//  			         as_nomper // nombre del personal
		//  			         as_cedper // cedula del personal
		//  			         as_cargo  // cargo del personal
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener el nombre de un personal
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   14/06/2006							Fecha de Ultima Modificación:   14/06/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT sno_personal.codper,sno_personal.cedper,sno_personal.nomper,sno_personal.apeper,".
				" CASE  sno_nomina.racnom WHEN 0 then sno_cargo.descar ELSE sno_asignacioncargo.denasicar END AS cargo".
			    " FROM  sno_personal, sno_personalnomina, sno_nomina, sno_cargo, sno_asignacioncargo ".
			 	" WHERE sno_personal.codemp='". $as_codemp ."'".
			    " AND sno_personal.codper='". $as_codper ."'".
			    " AND sno_nomina.espnom=0".
			    " AND sno_personal.codemp = sno_personalnomina.codemp".
			    " AND sno_personal.codper = sno_personalnomina.codper".
			    " AND sno_personalnomina.codemp = sno_nomina.codemp".
			    " AND sno_personalnomina.codnom = sno_nomina.codnom".
			    " AND sno_personalnomina.codemp = sno_cargo.codemp".
			    " AND sno_personalnomina.codnom = sno_cargo.codnom".
			    " AND sno_personalnomina.codcar = sno_cargo.codcar".
			    " AND sno_personalnomina.codemp = sno_asignacioncargo.codemp".
			    " AND sno_personalnomina.codnom = sno_asignacioncargo.codnom".
			    " AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_responsable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_nomper= $row["apeper"].", ".$row["nomper"];
				$as_cedper= $row["cedper"];
				$as_cargo= $row["cargo"];
			}
			else
			{
				$this->io_msg->message("El personal no tiene cargo asociado");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_responsable

	function uf_saf_load_dt_compmovimiento($as_codemp,$as_cmpmov,$as_codres)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_compmovimiento
	//	           Access:   public
	//  		Arguments:   as_codemp  // codigo de empresa
	//  			         as_cmpmov  // comprobante de movimiento
	//  			         as_codres  // codigo de responsable primario
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles de un movimiento
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   26/09/2006						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_int="";
		$this->io_sql=new class_sql($this->con);
		if($as_codres!="")
		{
			$ls_int= $ls_int." AND   saf_dta.codrespri='".$as_codres."'";
		}
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sql=" SELECT saf_dt_movimiento.codemp,saf_dt_movimiento.cmpmov,saf_dt_movimiento.codact,".
				"        saf_dt_movimiento.ideact,saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp,".
				"        COUNT(saf_dt_movimiento.ideact) AS cantidad,".
				"       (SELECT dencau FROM saf_causas".
				" 	      WHERE saf_causas.codcau=saf_dt_movimiento.codcau AND estcat='".$ls_estcat."') AS dencau,".
				"       (SELECT coduniadm FROM saf_dta".
				" 	      WHERE saf_dta.codact=saf_dt_movimiento.codact".
				"           AND saf_dta.ideact=saf_dt_movimiento.ideact) AS coduniadm,".
				"       (SELECT denact FROM saf_activo".
				" 	      WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact,".
				"       (SELECT catalogo FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS catalogo,".
				"       (SELECT costo FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS costo".
				"  FROM saf_dt_movimiento,saf_dta".
				" WHERE saf_dt_movimiento.codemp='".$as_codemp."' ".
				"   AND saf_dt_movimiento.cmpmov='".$as_cmpmov."' ".
				$ls_int.
				"   AND saf_dta.codemp=saf_dt_movimiento.codemp".
				"   AND saf_dta.codact=saf_dt_movimiento.codact".
				"   AND saf_dta.ideact=saf_dt_movimiento.ideact".
				" GROUP BY saf_dt_movimiento.codemp,saf_dt_movimiento.codact, ".
				"          saf_dt_movimiento.ideact,saf_dt_movimiento.cmpmov, ".
				"          saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_compmovimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_dt_compmovimiento
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////                  Comprobante de Incorporacion                           //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_dt_compreasignacion($as_codemp,$as_cmpmov,$as_codrespri,$as_codresuso)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_compreasignacion
	//	           Access:   public
	//  		Arguments:   as_codemp     // codigo de empresa
	//  			         as_cmpmov     // comprobante de movimiento
	//  			         as_codrespri  // codigo de responsable primario
	//  			         as_codresuso  // codigo de responsable por uso
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles de un movimiento
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   28/09/2006						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_int="";
		$this->io_sql=new class_sql($this->con);
		if($as_codrespri!="")
		{
			$ls_int= $ls_int." AND   saf_dta.codrespri='".$as_codrespri."'";
		}
		if($as_codresuso!="")
		{
			$ls_int= $ls_int." AND   saf_dta.codres='".$as_codresuso."'";
		}
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sql=" SELECT saf_dt_movimiento.codemp,saf_dt_movimiento.cmpmov,saf_dt_movimiento.codact,".
				"        saf_dt_movimiento.ideact,saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp,".
				"        COUNT(saf_dt_movimiento.ideact) AS cantidad,".
				"       (SELECT dencau FROM saf_causas".
				" 	      WHERE saf_causas.codcau=saf_dt_movimiento.codcau AND estcat='".$ls_estcat."') AS dencau,".
				"       (SELECT coduniadm FROM saf_dta".
				" 	      WHERE saf_dta.codact=saf_dt_movimiento.codact".
				"           AND saf_dta.ideact=saf_dt_movimiento.ideact) AS coduniadm,".
				"       (SELECT denact FROM saf_activo".
				" 	      WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact,".
				"       (SELECT catalogo FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS catalogo,".
				"       (SELECT codpai FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS codpai,".
				"       (SELECT codest FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS codest,".
				"       (SELECT codmun FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS codmun,".
				"       (SELECT costo FROM saf_activo".
				"         WHERE saf_activo.codact=saf_dt_movimiento.codact) AS costo".
				"  FROM saf_dt_movimiento,saf_dta".
				" WHERE saf_dt_movimiento.codemp='".$as_codemp."' ".
				"   AND saf_dt_movimiento.cmpmov='".$as_cmpmov."' ".
				$ls_int.
				"   AND saf_dta.codemp=saf_dt_movimiento.codemp".
				"   AND saf_dta.codact=saf_dt_movimiento.codact".
				"   AND saf_dta.ideact=saf_dt_movimiento.ideact".
				" GROUP BY saf_dt_movimiento.codemp,saf_dt_movimiento.codact,saf_dt_movimiento.ideact,saf_dt_movimiento.cmpmov,saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_compreasignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_saf_load_dt_compreasignacion
	
	function uf_saf_load_ubicacion($as_codpai,$as_codest,$as_codmun,&$as_denpai,&$as_denest,&$as_denmun)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_ubicacion
		//	           Access:   public
		//  		Arguments:   as_codpai // codigo de pais
		//  			         as_codest // codigo de estado
		//  			         as_codmun // codigo de municipio
		//  			         as_denpai // denominacion de pais
		//  			         as_denest // denominacion de estado
		//  			         as_denmun // denominacion de municipio
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener las denominaciones de un pais, estado y municipio
		//         Creado por:   Ing. Luis Anibal Lang           
		//   Fecha de Cracion:   28/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT sigesp_municipio.denmun,".
				" (SELECT desest FROM sigesp_estados".
				"   WHERE sigesp_municipio.codpai=sigesp_estados.codpai".
				"   AND   sigesp_estados.codest='". $as_codest ."') AS denest,".
				" (SELECT despai FROM sigesp_pais".
				"   WHERE sigesp_municipio.codpai=sigesp_pais.codpai) AS denpai".
				" FROM  sigesp_municipio".
				" WHERE sigesp_municipio.codpai='".$as_codpai."'".
				" AND   sigesp_municipio.codest='".$as_codest."'".
				" AND   sigesp_municipio.codmun='".$as_codmun."'";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_ubicacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_denpai= $row["denpai"];
				$as_denest= $row["denest"];
				$as_denmun= $row["denmun"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_ubicacion
	
	function  uf_saf_select_last_date($as_codemp,$as_codact,$as_ideact,$ad_fecdep,$ai_mondepmen,$ai_mondepano,$ai_mondepacu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_last_date
		//         Access: public  
		//      Argumento: $as_codigo       //codigo de rotulacion
		//                 $as_denominacion //denominacion de la rotulacion
		//                 $as_empleo       //empleo de la rotulacion
		//                 $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo rotulacion en la tabla saf_rotulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_depreciacion (codemp, codact, ideact, fecdep, mondepmen, mondepano, mondepacu, estcon) ".
				  " VALUES('".$as_codemp."','".$as_codact."','".$as_ideact."','".$ad_fecdep."','".$ai_mondepmen."',".
				  "        '".$ai_mondepano."','".$ai_mondepacu."','0')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->rotulacion MÉTODO->uf_saf_select_last_date ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin uf_saf_insert_rotulacion

   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_valor_config($as_codemp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_valor_config
		//		   Access: public
		//	    Arguments: 
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		// Modificado por: Ing. Yozelin Barragan            
		// Fecha Creación: 21/05/2007 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="SELECT * ".
	   		    "  FROM sigesp_config ".
			    " WHERE codemp='".$as_codemp."' ".
			    "   AND codsis='SAF' ".
			    "   AND seccion='CATEGORIA' ".
			    "   AND entry='TIPO-CATEGORIA-CSG-CGR' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->articulo ->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_valor=trim($row["value"]);
				$lb_valido=true; 
			}
			else
			{
				$li_valor="0";
			}
		}
		return $li_valor;
	}// end function uf_select_config
   //----------------------------------------------------------------------------------------------------------------------------------
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                RELACION DE BIENES MUEBLES FALTANTES - FORMULARIO BM-3 DE LA CGR
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function uf_saf_load_relbiemuefal($as_codemp,$as_coduniadm,$as_cmpmov_desde,$as_cmpmov_hasta,$ad_desde,$ad_hasta,$ai_orden)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_relbiemuefal
	//	           Access:   public
	//  		Arguments:   as_codemp       // Codigo de empresa
	//  			         as_coduniadm    // Codigo de la unidad administrativa que posee el bien
	//                       as_cmpmov_desde // Nro. de Comprobante de Movimiento Inicial
	//                       as_cmpmov_hasta // Nro. de Comprobante de Movimiento Final
	//  			         ad_desde        // Fecha de Inicio de la generacion de los movimientos
	//  			         ad_hasta        // Fecha tope de la generacion de los movimientos
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los comprobantes de desincoprporacion por concepto de
	//                       Bienes Muebles faltantes (060)
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:   13/12/2007					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $ls_sqlint="";
  $this->io_sql=new class_sql($this->con);
  
  if (($ad_desde!="")&&($ad_hasta!=""))
  {
  $ad_desde = $this->io_funcion->uf_convertirdatetobd($ad_desde);
  $ad_hasta = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
  $ls_sqlint = $ls_sqlint." and saf_movimiento.feccmp >= '".$ad_desde."' and saf_movimiento.feccmp <= '".$ad_hasta."'";
  }
  
  if (($as_cmpmov_desde!="")&&($as_cmpmov_hasta!=""))
  {
  $ls_sqlint = $ls_sqlint." and saf_movimiento.cmpmov >= '".$as_cmpmov_desde."' and saf_movimiento.cmpmov <= '".$as_cmpmov_hasta."'"; 
  }
  
  if ($as_coduniadm != "")
  {
  $ls_sqlint = $ls_sqlint." and saf_movimiento.coduniadm = '".$as_coduniadm."'";
  }
       
					 
  $ls_sql= "Select saf_movimiento.cmpmov,
                   saf_movimiento.feccmp,
				   saf_movimiento.coduniadm from saf_movimiento
where saf_movimiento.codemp = '".$as_codemp."'
      and saf_movimiento.codcau = '060'
      and saf_movimiento.estcat = 2 ".$ls_sqlint."
       order by saf_movimiento.cmpmov ";
   $rs_data=$this->io_sql->select($ls_sql);
    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_relbiemuefal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;	   
  
} // fin de la function uf_saf_load_relbiemuefal

function uf_saf_load_dt_relbiemuefal($as_codemp,$as_coduniadm,$as_cmpmov,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta,$as_grupo,$as_subgrupo,$as_seccion,$ai_orden)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_relbiemuefal
	//	           Access:   public
	//  		Arguments:   as_codemp     // Codigo de empresa
	//  			         as_coduniadm  // Codigo de la unidad administrativa que posee el bien
	//  			         ad_mes        // Mes de la generacion de los movimientos
	//  			         ad_anno       // Año de la generacion de los movimientos
	//                       as_coddesde   // Codigo del Activo Inicial
	//                       as_codhasta   // Codigo del Activo Final
	//                       as_grupo      // Codigo de Grupo
	//                       as_subgrupo     // Codigo del SubGrupo
	//                       as_seccion        // Codigo de la Seccion         
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene el detalle de los comprobantes de desincoprporacion por concepto de
	//                       Bienes Muebles faltantes (060)
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:   12/12/2007					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $ls_sqlint="";
  $this->io_sql=new class_sql($this->con);
					 
		$ls_sql= "SELECT saf_dt_movimiento.codact,
       (SELECT codgru FROM saf_activo
	  WHERE saf_activo.codact=saf_dt_movimiento.codact) AS grupo,
       (SELECT codsubgru FROM saf_activo
	  WHERE saf_activo.codact=saf_dt_movimiento.codact) AS subgrupo,
       (SELECT codsec FROM saf_activo
	  WHERE saf_activo.codact=saf_dt_movimiento.codact) AS seccion,
       saf_dt_movimiento.ideact,
       saf_dt_movimiento.feccmp,
       saf_dta.seract,
       (SELECT denact FROM saf_activo
	  WHERE saf_activo.codact=saf_dt_movimiento.codact) AS denact,
       (SELECT maract FROM saf_activo
	  WHERE saf_activo.codact=saf_dt_movimiento.codact) AS marca,
       (SELECT modact FROM saf_activo
	  WHERE saf_activo.codact=saf_dt_movimiento.codact) AS modelo,  
       COUNT(saf_dt_movimiento.ideact) AS cantidad,
       (SELECT dencau FROM saf_causas
	  WHERE saf_causas.codcau=saf_dt_movimiento.codcau and estcat = 2) AS dencau,
       (SELECT coduniadm FROM saf_dta
	  WHERE saf_dta.codact=saf_dt_movimiento.codact
	    AND saf_dta.ideact=saf_dt_movimiento.ideact) AS coduniadm,
       (SELECT catalogo FROM saf_activo
	  WHERE saf_activo.codact=saf_dt_movimiento.codact) AS catalogo,
       (SELECT costo FROM saf_activo
	  WHERE saf_activo.codact=saf_dt_movimiento.codact) AS costo
FROM saf_dt_movimiento,saf_dta
   WHERE saf_dt_movimiento.codemp='".$as_codemp."'
   AND saf_dt_movimiento.cmpmov='".$as_cmpmov."'
   AND saf_dta.codemp=saf_dt_movimiento.codemp
   AND saf_dta.codact=saf_dt_movimiento.codact
   AND saf_dta.ideact=saf_dt_movimiento.ideact
   AND saf_dt_movimiento.estcat = 2
   AND saf_dt_movimiento.codcau = '060'
GROUP BY saf_dt_movimiento.codemp,saf_dt_movimiento.codact, 
	 saf_dt_movimiento.ideact,saf_dt_movimiento.cmpmov, 
	 saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp,
	 saf_dt_movimiento.estcat,saf_dta.seract  ";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY saf_dt_movimiento.ideact ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY 8 DESC";
			}
		}	
   $rs_data=$this->io_sql->select($ls_sql);
    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_relbiemuefal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;	   
  
} // fin de la function uf_saf_load_dt_relbiemuefal
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                RESUMEN DE LA CUENTA DE BIENES MUEBLES - FORMULARIO BM-4 DE LA CGR
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uf_saf_load_existencia($as_codemp,$as_coduniadm,$ad_mes,$ad_anno,$as_coddesde,$as_codhasta,$ai_orden)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_existencia
	//	           Access:   public
	//  		Arguments:   as_codemp     // Codigo de empresa
	//  			         as_coduniadm  // Codigo de la unidad administrativa que posee el bien
	//  			         ad_mes        // Mes de la generacion de los movimientos
	//  			         ad_anno       // Año de la generacion de los movimientos
	//                       as_coddesde   // Codigo del Activo Inicial
	//                       as_codhasta   // Codigo del Activo Final
	//                       as_grupo      // Codigo de Grupo
	//                       as_subgru     // Codigo del SubGrupo
	//                       as_sec        // Codigo de la Seccion         
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los totales de incorporaciones y desincorporaciones de bienes en el mes y año    //                        indicado
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:   11/12/2007					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $ls_sqlint="";
  $this->io_sql=new class_sql($this->con);
					   
	if((!empty($ad_mes))&&(!empty($ad_anno)))
		{
		 $ls_sqlint = $ls_sqlint. " AND substr(a.feccmp, 6,2) = '".str_pad($ad_mes,2,'0',0)."'"." 
                                    AND substr(a.feccmp, 1,4) = '".$ad_anno."'";
		}
		
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
		  $ls_sqlint = $ls_sqlint. " AND c.codact >= '".$as_coddesde."' and c.codact <= '".$as_codhasta."'"; 
		}
		
		if (!empty($as_coduniadm))
		{
		  $ls_sqlint = $ls_sqlint." AND c.coduniadm = '".$as_coduniadm."'";
		}   
		
		$ls_sql= "Select a.codact,c.estact as estatus,
                         count(a.ideact)*d.costo as tot_exi_mes
                  from saf_dt_movimiento a
                  Join saf_movimiento b on b.codemp = a.codemp 
                                       and b.cmpmov = a.cmpmov
                                       and b.codcau = a.codcau
                                       and b.feccmp = a.feccmp
                                       and b.estcat = 2
                  Join saf_dta c on c.codact = a.codact
                                and c.ideact = a.ideact
                                and c.codemp = a.codemp
                                and (c.estact = 'I' or c.estact = 'D')
                  Join saf_activo d on d.codact = a.codact
       left outer Join saf_grupo  e on e.codgru = d.codgru
       left outer Join saf_subgrupo f on f.codgru = e.codgru
       left outer Join saf_seccion g on g.codgru = f.codgru                     
where a.codemp = '".$as_codemp."'".$ls_sqlint."
group by a.codact,c.ideact,a.cmpmov,a.codemp,a.feccmp, d.denact,
         c.estact,d.codgru,d.codsubgru,d.codsec, a.codcau, d.costo, b.descmp ";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY c.ideact ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY d.denact DESC";
			}
		}	
   $rs_data=$this->io_sql->select($ls_sql);
    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_relmovbienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;	   
  
} // fin de la function uf_saf_load_dt_relmovbienes

function uf_saf_load_dt_resctabiemue_desinc($as_codemp,$as_coduniadm,$ad_fecini,$ad_fecfin,$as_coddesde,$as_codhasta,$ai_orden)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_resctabiemue_desinc
	//	           Access:   public
	//  		Arguments:   as_codemp     // Codigo de empresa
	//  			         as_coduniadm  // Codigo de la unidad administrativa que posee el bien
	//  			         as_fecini     // Fecha inicio de generacion del movimiento del bien
	//  			         as_fecfin     // Fecha final de generacion del movimiento del bien
	//                       as_coddesde   // Codigo del Activo Inicial
	//                       as_codhasta   // Codigo del Activo Final       
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene el detalle de las desincorporaciones en el mes excepto el 060
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:   10/12/2007					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $ls_sqlint="";
  $this->io_sql=new class_sql($this->con);
					   
	if((!empty($ad_fecini))&&(!empty($ad_fecfin)))
		{
	     $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecini);
		 $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fecfin);
		 $ls_sqlint = $ls_sqlint. " AND a.feccmp >= '".$ld_auxdesde."' and a.feccmp <= '".  $ld_auxhasta."'";
		}
		
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
		  $ls_sqlint = $ls_sqlint. " AND c.codact >= '".$as_coddesde."' and c.codact <= '".$as_codhasta."'"; 
		}
		
		if (!empty($as_coduniadm))
		{
		  $ls_sqlint = $ls_sqlint." AND c.coduniadm = '".$as_coduniadm."'";
		}   
		
		$ls_sql= "Select a.codact,
                         count(a.ideact)*d.costo as tot_desinc_no_060
                  from saf_dt_movimiento a
                  Join saf_movimiento b on b.codemp = a.codemp 
                                       and b.cmpmov = a.cmpmov
                                       and b.codcau <> '060'
                                       and b.feccmp = a.feccmp
                                       and b.estcat = 2
                  Join saf_dta c on c.codact = a.codact
                                and c.ideact = a.ideact
                                and c.codemp = a.codemp
                                and (c.estact = 'D')
                  Join saf_activo d on d.codact = a.codact
       left outer Join saf_grupo  e on e.codgru = d.codgru
       left outer Join saf_subgrupo f on f.codgru = e.codgru
       left outer Join saf_seccion g on g.codgru = f.codgru                     
where a.codemp = '".$as_codemp."'".$ls_sqlint."
group by a.codact,c.ideact,a.cmpmov,a.codemp,a.feccmp, d.denact,
         c.estact,d.codgru,d.codsubgru,d.codsec, a.codcau, d.costo, b.descmp ";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY c.ideact ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY d.denact DESC";
			}
		}	
   $rs_data=$this->io_sql->select($ls_sql);
    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_relmovbienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;	   
  
} // fin de la function uf_saf_load_dt_resctabiemue_desinc

function uf_saf_load_dt_resctabiemue_desinc_060($as_codemp,$as_coduniadm,$ad_fecini,$ad_fecfin,$as_coddesde,$as_codhasta,$ai_orden)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_resctabiemue_desinc_060
	//	           Access:   public
	//  		Arguments:   as_codemp     // Codigo de empresa
	//  			         as_coduniadm  // Codigo de la unidad administrativa que posee el bien
	//  			         as_fecini     // Fecha inicio de generacion del movimiento del bien
	//  			         as_fecfin     // Fecha final de generacion del movimiento del bien
	//                       as_coddesde   // Codigo del Activo Inicial
	//                       as_codhasta   // Codigo del Activo Final       
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene el detalle de las desincorporaciones con causa 060
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:   04/12/2007					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $ls_sqlint="";
  $this->io_sql=new class_sql($this->con);
					   
	if((!empty($ad_fecini))&&(!empty($ad_fecfin)))
		{
	     $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecini);
		 $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fecfin);
		 $ls_sqlint = $ls_sqlint. " AND a.feccmp >= '".$ld_auxdesde."' and a.feccmp <= '".  $ld_auxhasta."'";
		}
		
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
		  $ls_sqlint = $ls_sqlint. " AND c.codact >= '".$as_coddesde."' and c.codact <= '".$as_codhasta."'"; 
		}
		
		if (!empty($as_coduniadm))
		{
		  $ls_sqlint = $ls_sqlint." AND c.coduniadm = '".$as_coduniadm."'";
		}   
		
		$ls_sql= "Select a.codact,
                         count(a.ideact)*d.costo as tot_desinc_060
                  from saf_dt_movimiento a
                  Join saf_movimiento b on b.codemp = a.codemp 
                                       and b.cmpmov = a.cmpmov
                                       and b.codcau = '060'
                                       and b.feccmp = a.feccmp
                                       and b.estcat = 2
                  Join saf_dta c on c.codact = a.codact
                                and c.ideact = a.ideact
                                and c.codemp = a.codemp
                                and (c.estact = 'D')
                  Join saf_activo d on d.codact = a.codact
       left outer Join saf_grupo  e on e.codgru = d.codgru
       left outer Join saf_subgrupo f on f.codgru = e.codgru
       left outer Join saf_seccion g on g.codgru = f.codgru                     
where a.codemp = '".$as_codemp."'".$ls_sqlint."
group by a.codact,c.ideact,a.cmpmov,a.codemp,a.feccmp, d.denact,
         c.estact,d.codgru,d.codsubgru,d.codsec, a.codcau, d.costo, b.descmp ";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY c.ideact ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY d.denact DESC";
			}
		}	
   $rs_data=$this->io_sql->select($ls_sql);
    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_resctabiemue_desinc_060 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;	   
  
} // fin de la function uf_saf_load_dt_resctabiemue_desinc_060

function uf_saf_load_dt_resctabiemue_inc($as_codemp,$as_coduniadm,$ad_fecini,$ad_fecfin,$as_coddesde,$as_codhasta,$ai_orden)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_resctabiemue_inc
	//	           Access:   public
	//  		Arguments:   as_codemp     // Codigo de empresa
	//  			         as_coduniadm  // Codigo de la unidad administrativa que posee el bien
	//  			         as_fecini     // Fecha inicio de generacion del movimiento del bien
	//  			         as_fecfin     // Fecha final de generacion del movimiento del bien
	//                       as_coddesde   // Codigo del Activo Inicial
	//                       as_codhasta   // Codigo del Activo Final       
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene el detalle de las incorporaciones en el mes
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:   04/12/2007					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $ls_sqlint="";
  $this->io_sql=new class_sql($this->con);
					   
	if((!empty($ad_fecini))&&(!empty($ad_fecfin)))
		{
	     $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecini);
		 $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fecfin);
		 $ls_sqlint = $ls_sqlint. " AND a.feccmp >= '".$ld_auxdesde."' and a.feccmp <= '".  $ld_auxhasta."'";
		}
		
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
		  $ls_sqlint = $ls_sqlint. " AND c.codact >= '".$as_coddesde."' and c.codact <= '".$as_codhasta."'"; 
		}
		
		if (!empty($as_coduniadm))
		{
		  $ls_sqlint = $ls_sqlint." AND c.coduniadm = '".$as_coduniadm."'";
		}   
		
		$ls_sql= "Select a.codact,
                         count(a.ideact)*d.costo as tot_inc_mes
                  from saf_dt_movimiento a
                  Join saf_movimiento b on b.codemp = a.codemp 
                                       and b.cmpmov = a.cmpmov
                                       and b.codcau = a.codcau
                                       and b.feccmp = a.feccmp
                                       and b.estcat = 2
                  Join saf_dta c on c.codact = a.codact
                                and c.ideact = a.ideact
                                and c.codemp = a.codemp
                                and (c.estact = 'I')
                  Join saf_activo d on d.codact = a.codact
       left outer Join saf_grupo  e on e.codgru = d.codgru
       left outer Join saf_subgrupo f on f.codgru = e.codgru
       left outer Join saf_seccion g on g.codgru = f.codgru                     
where a.codemp = '".$as_codemp."'".$ls_sqlint."
group by a.codact,c.ideact,a.cmpmov,a.codemp,a.feccmp, d.denact,
         c.estact,d.codgru,d.codsubgru,d.codsec, a.codcau, d.costo, b.descmp ";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY c.ideact ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY d.denact DESC";
			}
		}
   $rs_data=$this->io_sql->select($ls_sql);
    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_relmovbienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;	   
  
} // fin de la function uf_saf_load_dt_resctabiemue_inc

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                RELACION DE MOVIMIENTOS DE BIENES MUEBLES - FORMULARIO BM-2 DE LA CGR
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uf_saf_load_dt_relmovbienes($as_codemp,$as_coduniadm,$ad_fecini,$ad_fecfin,$as_coddesde,$as_codhasta,$as_grupo,$as_subgru,$as_sec, $ai_orden)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_dt_relmovbienes
	//	           Access:   public
	//  		Arguments:   as_codemp     // Codigo de empresa
	//  			         as_coduniadm  // Codigo de la unidad administrativa que posee el bien
	//  			         as_fecini     // Fecha inicio de generacion del movimiento del bien
	//  			         as_fecfin     // Fecha final de generacion del movimiento del bien
	//                       as_coddesde   // Codigo del Activo Inicial
	//                       as_codhasta   // Codigo del Activo Final
	//                       as_grupo      // Codigo de Grupo
	//                       as_subgru     // Codigo del SubGrupo
	//                       as_sec        // Codigo de la Seccion         
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles de los movimientos de los bienes muebles
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:   04/12/2007					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $ls_sqlint="";
  $this->io_sql=new class_sql($this->con);
					   
	if((!empty($ad_fecini))&&(!empty($ad_fecfin)))
		{
	     $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecini);
		 $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fecfin);
		 $ls_sqlint = $ls_sqlint. " AND a.feccmp >= '".$ld_auxdesde."' and a.feccmp <= '".  $ld_auxhasta."'";
		}
		
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
		  $ls_sqlint = $ls_sqlint. " AND c.codact >= '".$as_coddesde."' and c.codact <= '".$as_codhasta."'"; 
		}
		
		if (!empty($as_coduniadm))
		{
		  $ls_sqlint = $ls_sqlint." AND c.coduniadm = '".$as_coduniadm."'";
		}   
		
		if(!empty($as_grupo) && !empty($as_subgru) && !empty($as_sec))
		{
		  $ls_sqlint= $ls_sqlint." AND d.codgru='".$as_grupo."' and d.codsubgru='".$as_subgru."' and d.codsec='".$as_sec."'";
		}
		$ls_sql= "Select a.codact,
                         d.codgru,
                         d.codsubgru,
                         d.codsec,
                         a.codcau,
                         c.ideact, 
                         count(a.ideact) as cantidad,
                         d.denact,
                         a.cmpmov,
                         a.feccmp,
                         b.descmp, 
                         a.codemp,  
                         c.estact as estatus,
                         count(a.ideact)*d.costo as total
                  from saf_dt_movimiento a
                  Join saf_movimiento b on b.codemp = a.codemp 
                                       and b.cmpmov = a.cmpmov
                                       and b.codcau = a.codcau
                                       and b.feccmp = a.feccmp
                                       and b.estcat = 2
                  Join saf_dta c on c.codact = a.codact
                                and c.ideact = a.ideact
                                and c.codemp = a.codemp
                                and (c.estact = 'I' or c.estact = 'D')
                  Join saf_activo d on d.codact = a.codact
       left outer Join saf_grupo  e on e.codgru = d.codgru
       left outer Join saf_subgrupo f on f.codgru = e.codgru
       left outer Join saf_seccion g on g.codgru = f.codgru                     
where a.codemp = '".$as_codemp."'".$ls_sqlint."
group by a.codact,c.ideact,a.cmpmov,a.codemp,a.feccmp, d.denact,
         c.estact,d.codgru,d.codsubgru,d.codsec, a.codcau, d.costo, b.descmp ";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY c.ideact ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY d.denact DESC";
			}
		}	
   $rs_data=$this->io_sql->select($ls_sql);
    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_relmovbienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;	   
  
} // fin de la function uf_saf_load_dt_relmovbienes

	function uf_saf_load_invgenbie($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta,$ai_grupo,$ai_subgrupo,$ai_seccion)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_invgenbie
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//  			         as_grupo     // codigo de grupo del activo
		//  			         as_subgrupo  // codigo de subgrupo del activo
		//  			         as_seccion   // codigo de seccion del activo
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte del Inventario General de Bienes
		//         Creado por:   Ing. Arnaldo Suarez     
		//   Fecha de Cracion:   17/12/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND saf_activo.codact >='".$as_coddesde."'".
					   " AND saf_activo.codact <='".$as_codhasta."'";
		}
		if((!empty($as_grupo))&&(!empty($as_subgrupo))&&(!empty($as_seccion)))
		{
			$ls_sqlint=$ls_sqlint." AND saf_activo.codgru ='".$as_grupo."'".
					   " AND saf_activo.codsubgru ='".$as_subgrupo."'".
					   " AND saf_activo.codsec ='".$as_seccion."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=$ls_sqlint." AND saf_activo.feccmpact >='". $ld_auxdesde ."'".
							      " AND saf_activo.feccmpact <='". $ld_auxhasta ."'";
		}
		if($ai_ordenact==0)
		{
			$ls_order="saf_activo.codact";
		}
		else
		{
			$ls_order="saf_activo.denact";
		}
		
		$ls_sql=" SELECT saf_activo.codact,
                  saf_activo.denact,
                  CASE WHEN saf_dta.estact = 'I' THEN 'INCORPORADO'
                     ELSE
                        CASE WHEN saf_dta.estact = 'R' THEN 'REGISTRADO'
                           ELSE
                              CASE WHEN saf_dta.estact = 'D' THEN 'DESINCORPORADO'
                                 ELSE
                                   CASE WHEN saf_dta.estact = 'M' THEN 'MODIFICADO'
                                      ELSE
                                         CASE WHEN saf_dta.estact = 'C' THEN 'CONTABILIZADO'
                                         END
                                   END            
                              END
                        END 
                  END as estact,
                  saf_activo.maract,
                  saf_activo.modact,
                  saf_dta.seract, saf_activo.costo as costo 
                  FROM   saf_activo, saf_dta  ".
				" WHERE  saf_activo.codemp='".$as_codemp."'  AND ".
				"        saf_dta.codemp=saf_activo.codemp    AND ".
				"        saf_dta.codact=saf_activo.codact  ".$ls_sqlint.
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_invgenbie ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_invgenbie

function uf_saf_load_resbiegru($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta,$as_grupo,$as_subgrupo,$as_seccion)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_resbiegru
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//  			         as_grupo     // codigo de grupo del activo
		//  			         as_subgrupo  // codigo de subgrupo del activo
		//  			         as_seccion   // codigo de seccion del activo
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte del Resumen de Bienes por Grupo
		//         Creado por:   Ing. Arnaldo Suarez     
		//   Fecha de Cracion:   17/12/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND activo.codact >='".$as_coddesde."'".
					   " AND activo.codact <='".$as_codhasta."'";
		}
		
		if(!empty($as_grupo))
		{
		  $ls_sqlint=$ls_sqlint." AND activo.codgru ='".$as_grupo."'";
		}
		
		if(!empty($as_subgrupo))
		{
		 $ls_sqlint=$ls_sqlint." AND activo.codsubgru ='".$as_subgrupo."'";
		}
		
		if(!empty($as_seccion))
		{
		 $ls_sqlint=$ls_sqlint." AND activo.codsec ='".$as_seccion."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=$ls_sqlint." AND activo.feccmpact >='". $ld_auxdesde ."'".
							      " AND activo.feccmpact <='". $ld_auxhasta ."'";
		}
		
		$ls_sql=" Select distinct(activo.codgru), grupo.dengru
                      from saf_activo activo
                  Join saf_grupo grupo on grupo.codgru = activo.codgru   
                  Join saf_subgrupo subgrupo on subgrupo.codsubgru = activo.codsubgru and 
				                                subgrupo.codgru = activo.codgru
                  Join saf_seccion seccion on seccion.codsec = activo.codsec and 
                                              subgrupo.codgru = activo.codgru and 
                                              seccion.codgru = activo.codgru
                  where activo.codemp = '".$as_codemp."'".$ls_sqlint.
				" order by activo.codgru";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_resbiegru ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_resbiegru	
	
function uf_saf_load_dt_resbiegru($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta,$as_grupo,$as_subgrupo,$as_seccion)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_dt_resbiegru
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//  			         as_grupo     // codigo de grupo del activo
		//  			         as_subgrupo  // codigo de subgrupo del activo
		//  			         as_seccion   // codigo de seccion del activo
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte del Resumen de Bienes por Grupo
		//         Creado por:   Ing. Arnaldo Suarez     
		//   Fecha de Cracion:   17/12/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND a.codact >='".$as_coddesde."'".
					   " AND a.codact <='".$as_codhasta."'";
		}
		
		if(!empty($as_grupo))
		{
		  $ls_sqlint=$ls_sqlint." AND b.codgru ='".$as_grupo."'";
		}
		
		if(!empty($as_subgrupo))
		{
		 $ls_sqlint=$ls_sqlint." AND b.codsubgru ='".$as_subgrupo."'";
		}
		
		if(!empty($as_seccion))
		{
		 $ls_sqlint=$ls_sqlint." AND b.codsec ='".$as_seccion."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=$ls_sqlint." AND b.feccmpact >='". $ld_auxdesde ."'".
							      " AND b.feccmpact <='". $ld_auxhasta ."'";
		}
		if($ai_ordenact==0)
		{
			$ls_order="b.codsubgru";
		}
		else
		{
			$ls_order="d.densubgru";
		}
		
		$ls_sql=" Select a.codact, 
                         a.ideact,
                         b.codgru,
						 c.dengru,
                         b.codsubgru,
						 d.densubgru,
                         b.codsec,
						 e.densec,
                         count(a.ideact) as cantidad,
						 (count(a.ideact)*b.costo) as total 
                         from saf_dta a 
                  Join saf_activo b on b.codact = a.codact and b.codemp = a.codemp
                  left outer Join saf_grupo  c on c.codgru = b.codgru
                  left outer Join saf_subgrupo d on d.codsubgru = b.codsubgru and d.codgru = b.codgru
                  left outer Join saf_seccion e on e.codsec = b.codsec and e.codsubgru = d.codsubgru and e.codgru = c.codgru".
				" WHERE  a.codemp='".$as_codemp."'".$ls_sqlint.
				" GROUP BY a.codact, a.ideact, b.codgru,
                           c.dengru, b.codsubgru, d.densubgru,
                           b.codsec, e.densec, b.costo".
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_resbiegru ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_dt_resbiegru
	
	
function uf_saf_load_incdesinc($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta,$as_grupo,$as_subgrupo,$as_seccion,$as_coduniadm)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_incdesinc
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			         as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//  			         as_grupo     // codigo de grupo del activo
		//  			         as_subgrupo  // codigo de subgrupo del activo
		//  			         as_seccion   // codigo de seccion del activo
		//                       as_coduniadm // codigo de la unidad administrativa que posee el bien
		//	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte de Incorporaciones y               
		//                       Desincorporaciones
		//         Creado por:   Ing. Arnaldo Suarez     
		//   Fecha de Cracion:   17/12/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND activo.codact >='".$as_coddesde."'".
					   " AND activo.codact <='".$as_codhasta."'";
		}
		
		if(!empty($as_grupo))
		{
		  $ls_sqlint=$ls_sqlint." AND activo.codgru ='".$as_grupo."'";
		}
		
		if(!empty($as_subgrupo))
		{
		 $ls_sqlint=$ls_sqlint." AND activo.codsubgru ='".$as_subgrupo."'";
		}
		
		if(!empty($as_seccion))
		{
		 $ls_sqlint=$ls_sqlint." AND activo.codsec ='".$as_seccion."'";
		}
		
		if(!empty($as_coduniadm))
		{
		 $ls_sqlint=$ls_sqlint." AND dta.coduniadm ='".$as_coduniadm."'";
		}
		
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=$ls_sqlint." AND activo.feccmpact >='". $ld_auxdesde ."'".
							      " AND activo.feccmpact <='". $ld_auxhasta ."'";
		}
		
		$ls_sql=" Select activo.codact, 
		                 CASE WHEN dta.estact = 'I' THEN 
                            count(dta.ideact)*activo.costo 
                         END as tot_inc,
                         CASE WHEN dta.estact = 'D' THEN 
                            count(dta.ideact)*activo.costo 
                         END as tot_desinc
				  from saf_dta dta
                  Join saf_activo activo on activo.codact = dta.codact
                     where (dta.estact = 'I' or dta.estact = 'D') and activo.codemp = '".$as_codemp."'".$ls_sqlint.
				 " group by activo.codact,dta.estact,activo.costo";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_resbiegru ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_incdesinc	
	
function uf_saf_load_biemuectacont($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_coddesde,$as_codhasta)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_biemuectacont
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         as_coddesde  // codigo de la cuenta contable de inicio
		//  			         as_codhasta  // codigo de la cuenta contable de finalizacion
        //	         Returns :   Retorna un Booleano
		//	      Description:   Funcion que se encarga de obtener los datos para el reporte de Bienes Muebles por Cuenta
		//                       Contable sea resumido o detallado               
		//         Creado por:   Ing. Arnaldo Suarez     
		//   Fecha de Cracion:   24/12/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND activo.sc_cuenta >='".trim($as_coddesde)."'".
					   " AND activo.sc_cuenta <='".trim($as_codhasta)."'";
		}
		
	     if($ai_ordenact==0)
		{
			$ls_order=" activo.sc_cuenta";
		}
		else
		{
			$ls_order=" cuenta.denominacion";
		}
		
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=$ls_sqlint." AND activo.feccmpact >='". $ld_auxdesde ."'".
							      " AND activo.feccmpact <='". $ld_auxhasta ."'";
		}
		
		$ls_sql="Select distinct(activo.sc_cuenta),cuenta.denominacion
                    from saf_activo activo 
                 Join scg_cuentas cuenta on cuenta.sc_cuenta = activo.sc_cuenta
                    where activo.codemp = '".$as_codemp."'".$ls_sqlint."".
				"order by ".$ls_order;
				
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_biemuectacont ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_biemuectacont
	
function uf_saf_load_dt_biemuectacont($as_codemp,$ai_ordenact,$ad_desde,$ad_hasta,$as_codctacont)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_dt_resbiegru
		//	           Access:   public
		//  		Arguments:   as_codemp      // codigo de empresa
		//  			         ai_ordenact    // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			         ad_desde       // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta       // fecha de cierre del intervalo de dias para la busqueda
		//  			         as_codctacont  // codigo de la cuenta contable para buscar su detalle
		//	      Description:   Funcion que se encarga de de obtener los datos para el detalle del reporte de Bienes Muebles por        //                       Cuenta Contable sea resumido o detallado               
		//         Creado por:   Ing. Arnaldo Suarez     
		//   Fecha de Cracion:   24/12/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_estcat=$this->uf_select_valor_config($as_codemp);
		$ls_sqlint="";

		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=$ls_sqlint." AND b.feccmpact >='". $ld_auxdesde ."'".
							      " AND b.feccmpact <='". $ld_auxhasta ."'";
		}
		
		if (!empty($as_codctacont))
		{
		 $ls_sqlint = $ls_sqlint." AND activo.sc_cuenta = '".trim($as_codctacont)."'";
		}
		
		if($ai_ordenact==0)
		{
			$ls_order="activo.sc_cuenta";
		}
		else
		{
			$ls_order="cuenta.denominacion";
		}
		
		$ls_sql=" Select activo.sc_cuenta,
                         cuenta.denominacion,
                         activo.codgru,
                         activo.codsubgru,
                         activo.codsec,
                         activo.codact, 
                         dta.ideact,
						 activo.denact, 
                         Count(dta.ideact) as Cantidad,
                         Count(dta.ideact)* activo.costo as Costo, 
                         activo.feccmpact as Fecha  
                 from saf_activo activo 
                 Join scg_cuentas cuenta on cuenta.sc_cuenta = activo.sc_cuenta
                 Join saf_dta dta on dta.codact = activo.codact
                 Join saf_grupo grupo on grupo.codgru = activo.codgru
                 Join saf_subgrupo subgrupo on subgrupo.codgru = activo.codgru 
                                            and subgrupo.codsubgru = activo.codsubgru
                 Join saf_seccion seccion on seccion.codgru = activo.codgru 
                                            and seccion.codsubgru = activo.codsubgru 
                                            and seccion.codsec = activo.codsec 
                 where activo.codemp = '".$as_codemp."'".$ls_sqlint."
                 group by activo.sc_cuenta,
                          activo.codact,
                          activo.codgru,
                          activo.codsubgru,
                          activo.codsec,
                          cuenta.denominacion, 
                          dta.ideact,
						  activo.denact,
                          activo.costo,
                          activo.feccmpact".
				" ORDER BY ".$ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_biemuectacont ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_dt_resbiegru			

function uf_saf_load_rendmen($as_codemp,$as_coduniadm_desde,$as_coduniadm_hasta,$ad_mes,$ad_anno,$ad_desde,$ad_hasta,$ai_orden)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_rendmen
	//	           Access:   public
	//  		Arguments:   as_codemp     // Codigo de empresa
	//  			         as_coduniadm_desde  // Codigo de la unidad administrativa que posee el bien
	//  			         as_coduniadm_hasta  // Codigo de la unidad administrativa que posee el bien
	//  			         ad_mes        // Mes de la generacion de los movimientos
	//  			         ad_anno       // Año de la generacion de los movimientos 
	//  			         ad_desde      // Fecha de Inicio de la generacion de los movimientos       
	//  			         ad_hasta      // Fecha de Inicio de la generacion de los movimientos       
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene el detalle de la Cuenta de Bienes Muebles por Unidad Administrativa
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:  02/01/2008					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido=false;
  $ls_sqlint="";
  $this->io_sql=new class_sql($this->con);
					   
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
		  $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
		  $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
		  $ls_sqlint = $ls_sqlint.  " AND (saf_dt_movimiento.feccmp >= '".$ld_auxdesde."'
                                      AND  saf_dt_movimiento.feccmp <= '".$ld_auxhasta."')"; 
		}
		
		if (!empty($as_coduniadm_desde)&&!empty($as_coduniadm_hasta))
		{
		  $ls_sqlint = $ls_sqlint." AND saf_dta.coduniadm >= '".$as_coduniadm_desde."'
		                            AND saf_dta.coduniadm <= '".$as_coduniadm_hasta."'";
		}   
		
		$ls_sql= "SELECT saf_dta.coduniadm,spg_unidadadministrativa.denuniadm,((Select (SUM(saf_dt_movimiento.monact))
FROM saf_dt_movimiento,saf_causas,saf_dta
   WHERE saf_dt_movimiento.codemp='".$as_codemp."'
   AND substr(saf_dt_movimiento.feccmp, 6,2) = '".$ad_mes."'
   AND substr(saf_dt_movimiento.feccmp, 1,4) = '".$ad_anno."'
   AND saf_dta.codemp=saf_dt_movimiento.codemp
   AND saf_dta.codact=saf_dt_movimiento.codact
   AND saf_dta.ideact=saf_dt_movimiento.ideact
   AND saf_dt_movimiento.estcat = 2
   AND saf_dt_movimiento.codcau = saf_causas.codcau
   AND saf_dt_movimiento.estcat = saf_causas.estcat
   AND saf_causas.tipcau = 'I') -
   (Select (SUM(saf_dt_movimiento.monact))
FROM saf_dt_movimiento,saf_causas,saf_dta
   WHERE saf_dt_movimiento.codemp='".$as_codemp."'
   AND substr(saf_dt_movimiento.feccmp, 6,2) = '".$ad_mes."'
   AND substr(saf_dt_movimiento.feccmp, 1,4) = '".$ad_anno."'
   AND saf_dta.codemp=saf_dt_movimiento.codemp
   AND saf_dta.codact=saf_dt_movimiento.codact
   AND saf_dta.ideact=saf_dt_movimiento.ideact
   AND saf_dt_movimiento.estcat = 2
   AND saf_dt_movimiento.codcau = saf_causas.codcau
   AND saf_dt_movimiento.estcat = saf_causas.estcat
   AND saf_causas.tipcau = 'D'
   )) as saldo_anterior,
   (Select (SUM(saf_dt_movimiento.monact))
FROM saf_dt_movimiento,saf_causas,saf_dta
   WHERE saf_dt_movimiento.codemp='".$as_codemp."'
   AND saf_dta.codemp=saf_dt_movimiento.codemp
   AND saf_dta.codact=saf_dt_movimiento.codact
   AND saf_dta.ideact=saf_dt_movimiento.ideact
   AND saf_dt_movimiento.estcat = 2
   AND saf_dt_movimiento.codcau = saf_causas.codcau
   AND saf_dt_movimiento.estcat = saf_causas.estcat
   AND saf_causas.tipcau = 'I'
   ) as tot_inc,
   ((Select (SUM(saf_dt_movimiento.monact))
FROM saf_dt_movimiento,saf_causas,saf_dta
   WHERE saf_dt_movimiento.codemp='".$as_codemp."'
   AND saf_dta.codemp=saf_dt_movimiento.codemp
   AND saf_dta.codact=saf_dt_movimiento.codact
   AND saf_dta.ideact=saf_dt_movimiento.ideact
   AND saf_dt_movimiento.estcat = 2
   AND saf_dt_movimiento.codcau <> '060'
   AND saf_dt_movimiento.codcau = saf_causas.codcau
   AND saf_dt_movimiento.estcat = saf_causas.estcat
   AND saf_causas.tipcau = 'D')
   ) as tot_desinc, 
   ((Select (SUM(saf_dt_movimiento.monact))
FROM saf_dt_movimiento,saf_causas,saf_dta
   WHERE saf_dt_movimiento.codemp='".$as_codemp."'
   AND saf_dta.codemp=saf_dt_movimiento.codemp
   AND saf_dta.codact=saf_dt_movimiento.codact
   AND saf_dta.ideact=saf_dt_movimiento.ideact
   AND saf_dt_movimiento.estcat = 2
   AND saf_dt_movimiento.codcau = '060'
   AND saf_dt_movimiento.codcau = saf_causas.codcau
   AND saf_dt_movimiento.estcat = saf_causas.estcat
   AND saf_causas.tipcau = 'D')
   ) as tot_desinc_060  
FROM saf_dt_movimiento,saf_causas,saf_dta,spg_unidadadministrativa
WHERE saf_dta.codemp = '".$as_codemp."'".$ls_sqlint."
      and saf_dta.coduniadm = spg_unidadadministrativa.coduniadm
group by saf_dta.coduniadm,spg_unidadadministrativa.denuniadm  ";
		if($ai_orden!="")
		{
			if($ai_orden==0)
			{
				$ls_sql=$ls_sql." ORDER BY saf_dta.coduniadm ASC";
			}
			else
			{
				$ls_sql=$ls_sql." ORDER BY spg_unidadadministrativa.denuniadm DESC";
			}
		}
   $rs_data=$this->io_sql->select($ls_sql);
    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_rendmen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;	   
  
} // fin de la function uf_saf_load_rendmen

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ///
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
    function uf_select_inventario_unidad($coduniadmi,$fecha1,$fecha2,$estatus, $orden, $cod1, $cod2,$grupo,$subgrupo,$seccion)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_load_rendmen
	//	           Access:   public
	//  		Arguments:   as_codemp     // Codigo de empresa
	//  			         as_coduniadm_desde  // Codigo de la unidad administrativa que posee el bien
	//  			         as_coduniadm_hasta  // Codigo de la unidad administrativa que posee el bien
	//  			         ad_mes        // Mes de la generacion de los movimientos
	//  			         ad_anno       // Año de la generacion de los movimientos 
	//  			         ad_desde      // Fecha de Inicio de la generacion de los movimientos       
	//  			         ad_hasta      // Fecha de Inicio de la generacion de los movimientos       
	//	         Returns :   Retorna un Booleano
	//    	 Description :   funcion para mostrar los bienes que se encuentarn en una unidad de trabajo dada una fecha
	//         Creado por:   Ing. Arnaldo Suárez           
	//   Fecha de Cracion:  02/01/2008					Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$_SESSION["la_empresa"]["codemp"];
	  	$lb_valido=false;
		$ls_estac="";
		$ld_orden="";
		$ld_codigo="";
		$clasifica="";
		$unidad="";
		$fecha="";
		if($coduniadmi!="")
		{
			 $unidad=" AND saf_dta.coduniadm='".$coduniadmi."'";
		}
		if (($fecha1!="") && ($fecha2!=""))
		{
			 $fecha=" AND (saf_dta.fecincact>=".$fecha1." OR saf_dta.fecincact <=".$fecha2.")".
				   " OR (saf_activo.fecregact>=".$fecha1." OR saf_activo.fecregact <=".$fecha2.")";	
		}
		
		if($estatus==1)
		{
		  $ls_estac = $ls_estac." AND saf_dta.estact ='I'";
        }
		
		if($estatus==2)
		{
		 $ls_estac = $ls_estac. " AND saf_dta.estact ='R'";
		}	
		
		if($orden==0)
		{
			$ls_orden=" ORDER BY saf_dta.ideact,saf_activo.codact";
		}
	    else
		{
			$ls_orden=" ORDER BY saf_activo.denact";
		}
		
		if($cod1!="" && $cod2!="")
		{
			 $ls_codigo=" AND (saf_activo.codact>='".$cod1."' AND saf_activo.codact<='".$cod2."')";
		}
		else
		{
			 $ls_codigo="";
		}
		
		
		if($grupo!="" && $subgrupo!="" && $seccion!="")
		{
		 	 $ls_clasifica=" AND saf_activo.codgru='".$grupo."' AND saf_activo.codsubgru='".$subgrupo."' AND saf_activo.codsec='".$seccion."'";
		}
		else
		{
		 	$ls_clasifica="";
		}		
		$ls_sql="SELECT saf_dta.codact,MAX(saf_activo.codgru) AS codgru, MAX(saf_activo.codsubgru) as codsubgru, MAX(saf_activo.codsec) AS codsec, MAX(saf_activo.codemp) as codemp,".
				"       MAX(saf_activo.denact) AS denact,MAX(saf_activo.maract)  AS maract, MAX(saf_activo.modact) AS modact, MAX(saf_activo.costo) AS costo,MAX(saf_activo.costoaux) AS costoaux,".
				"       MAX(saf_dta.seract) AS seract,MAX(saf_dta.estact) AS estact,saf_dta.ideact, '1' as cantidad,MAX(saf_dta.coduniadm) AS coduniadm,".
				"       (SELECT denuniadm FROM spg_unidadadministrativa".
				"         WHERE codemp=spg_unidadadministrativa.coduniadm AND coduniadm=spg_unidadadministrativa.coduniadm) AS denuniadm".
				"  FROM saf_activo,saf_dta".
				" WHERE saf_activo.codemp='".$ls_codemp."'".
				"   AND saf_dta.estact<>'D'".
				"   AND saf_activo.codemp=saf_dta.codemp".
				"   AND saf_activo.codact=saf_dta.codact".
				$ls_estac.$unidad.$fecha.$ls_codigo.$ls_clasifica.
				" GROUP BY saf_dta.codact,saf_dta.ideact";$ls_orden;
/*		$ls_sql=" Select distinct (a.codact),c.codgru, d.codsubgru, e.codsec, a.codemp, a.denact,a.maract, a.modact, a.costo,a.costoaux, b.seract,b.estact, ".
				" (SELECT coduniadm||' - '||denuniadm FROM spg_unidadadministrativa WHERE b.coduniadm=spg_unidadadministrativa.coduniadm) AS denuniadm, b.ideact, '1' as cantidad, f.denuniadm as servicio ".
                "    from saf_activo a ".
				"  join saf_dta b on (a.codact=b.codact) ".
				"  left outer join saf_grupo c on (a.codgru=c.codgru) ".
				"  left outer join saf_subgrupo d on (a.codgru=d.codgru) ". 
				"  left outer join saf_seccion e on (a.codgru=e.codgru) ".
				"  left outer join saf_unidadadministrativa f on (b.coduniadm=f.coduniadm) ".
				"     where b.estact <> 'D' ".$ls_estac.$unidad.$fecha.$ls_codigo.$ls_clasifica.$ls_orden; 
*/					
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);		
		if($rs_data===false)
		{print $this-> io_sql->message;
		 $this->io_msg->message("CLASE->Report MÉTODO->uf_select_inventario ERROR->".$this->io_funcion->uf_convertirmsg($this-> io_sql->message));
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
	}

////////////////////funcion para mostrar la adquisicion de bienes general/////////////////////////////////////////////////
///////////////////creado por la Ing. Jennifer Rivero/////////////////////////////////////////////////////////////////////////7
 function uf_select_bienes_general($codpro1,$codpro2,$codart1,$codart2,$fecha1,$fecha2,$orden)
	{
	  	$lb_valido=false;
		$this->io_sql=new class_sql($this->con);
		
		$ls_fecha= "";	
		$ls_orden= "";	
		$ls_codigo= "";		
		$ls_codpro= "";	
		$arre=$_SESSION["la_empresa"];
	    $ls_codemp=$arre["codemp"];
			
		if((!empty($fecha1))&&(!empty($fecha2)))
		{
	      $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($fecha1);
		  $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($fecha2);
		  $ls_fecha = "and a.fecincact>= '".$ld_auxdesde."' and a.fecincact <= '".$ld_auxhasta."'";
		}
				
		if($orden==0)
		{
		  $ls_orden=" order by b.cod_pro";
		}
	    else
		{
		  $ls_orden=" order by e.nompro";
		}
		
		if($codart1!="" && $codart2!="")
		{
		  $ls_codigo=" and a.codact>='".$codart1."' and a.codact<='".$codart2."'";
		}
		if($codpro1!="" && $codpro2!="")
		{
		   $ls_codpro=" and b.cod_pro>='".$codpro1."' and b.cod_pro<='".$codpro2."'";
		}
			
		$ls_sql="select MAX(a.ideact) as ideact, MAX(a.codact) as codact, MAX(b.denact) as denact,  MAX(b.codgru||' -'||b.codsubgru||'-'||b.codsec) as grupo,
                 count(ideact) as cantidad, MAX(b.costo) as costo,
                 b.cod_pro as cod_pro, MAX(e.nompro) as nompro, MAX(b.numordcom) as numordcom, MAX(f.fecordcom) as fecordcom, 
                 MAX(a.fecincact) as fecincact 
                 from saf_dta a
                 join saf_activo b on (a.codact=b.codact)
                 left join rpc_proveedor e on (b.cod_pro =e.cod_pro)
                 left join soc_ordencompra f on (b.numordcom=f.numordcom)
				 where a.codemp='".$ls_codemp."' ".$ls_fecha.$ls_codigo.$ls_codpro." group by b.cod_pro ".$ls_orden; 
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);		
		if($rs_data===false)
		{
		   $this->io_msg->message("CLASE->Report MÉTODO->uf_select_bien_general ERROR->".$this->io_funcion->uf_convertirmsg($this-> io_sql->message));
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
	}
////-------------------------------------------------------------------------------------------------------------------------------

////////////////////funcion para mostrar tipo de adquisición de bienes/////////////////////////////////////////////////
///////////////////creado por la Ing. Jennifer Rivero/////////////////////////////////////////////////////////////////////////7
 function uf_select_tipo_bienes($codpro1,$codpro2,$codart1,$codart2,$fecha1,$fecha2,$coduni,$orden)
	{
	  	$lb_valido=false;
		$this->io_sql=new class_sql($this->con);
		
		$ls_fecha= "";	
		$ls_orden= "";	
		$ls_codigo= "";		
		$ls_codpro= "";
		$ls_codunidad= "";	
		$arre=$_SESSION["la_empresa"];
	    $ls_codemp=$arre["codemp"];
			
		if((!empty($fecha1))&&(!empty($fecha2)))
		{
	      $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($fecha1);
		  $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($fecha2);
		  $ls_fecha = "(a.fecincact>= '".$ld_auxdesde."' and a.fecincact <= '".$ld_auxhasta."')";
		}
				
		if($orden==0)
		{
		  $ls_orden=" order by b.cod_pro,a.ideact,a.codact";
		}
	    else
		{
		  $ls_orden=" order by e.nompro";
		}
		
		if($codart1!="" && $codart2!="")
		{
		  $ls_codigo=" and (a.codact>='".$codart1."' and a.codact<='".$codart2."')";
		}
		if($codpro1!="" && $codpro2!="")
		{
		   $ls_codpro=" and (b.cod_pro>='".$codpro1."' and b.cod_pro<='".$codpro2."')";
		}
		
		if($coduni!="")
		{
		   $ls_codunidad=" and (a.coduniadm='".$coduni."')";
		}
			
		$ls_sql="select    a.ideact as ideact, a.codact as codact, MAX(b.denact) as denact,  MAX(b.codgru||' -'||b.codsubgru||'-'||b.codsec) as grupo,
                 MAX(b.costo) as costo,MAX(e.nompro),
                 b.cod_pro as cod_pro, MAX(b.numordcom) as numordcom, MAX(f.fecordcom) as fecordcom, 
                 MAX(b.maract) as maract, MAX(b.modact) as modact, MAX(a.seract) as seract, MAX(b.spg_cuenta_act) as spg_cuenta_act,
				 MAX(b.sc_cuenta) as sc_cuenta
                 from saf_dta a
                 join saf_activo b on (a.codact=b.codact)
                 left join rpc_proveedor e on (b.cod_pro =e.cod_pro)
                 left join soc_ordencompra f on (b.numordcom=f.numordcom)
				 left join saf_unidadadministrativa g on (a.coduniadm=g.coduniadm)
				 where a.codemp='".$ls_codemp."' ".$ls_fecha.$ls_codigo.$ls_codpro.$ls_codunidad." group by b.cod_pro, a.codact, a.ideact ".$ls_orden;        
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);		
		if($rs_data===false)
		{
		   $this->io_msg->message("CLASE->Report MÉTODO->uf_select_tipo_bienes ERROR->".$this->io_funcion->uf_convertirmsg($this-> io_sql->message));
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
	}
////-------------------------------------------------------------------------------------------------------------------------------
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////función que selecciona al proveedor de un bien///////////////////////////////////////////////////////////////////
////////////creado por la Ing. Jennifer Rivero //////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uf_select_proveedor($codpro1,$codpro2,$codart1,$codart2,$fecha1,$fecha2,$orden)
	{
	  	$lb_valido=false;
		$this->io_sql=new class_sql($this->con);
		
		$ls_fecha= "";	
		$ls_orden= "";	
		$ls_codigo= "";		
		$ls_codpro= "";	
		$arre=$_SESSION["la_empresa"];
	    $ls_codemp=$arre["codemp"];
			
		if((!empty($fecha1))&&(!empty($fecha2)))
		{
	      $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($fecha1);
		  $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($fecha2);
		  $ls_fecha = "and a.fecincact>= '".$ld_auxdesde."' and a.fecincact <= '".$ld_auxhasta."'";
		}
				
		if($orden==0)
		{
		  $ls_orden=" order by b.cod_pro";
		}
	    else
		{
		  $ls_orden=" order by e.nompro";
		}
		
		if($codart1!="" && $codart2!="")
		{
		  $ls_codigo=" and (a.codact>='".$codart1."' and a.codact<='".$codart2."')";
		}
		if($codpro1!="" && $codpro2!="")
		{
		  $ls_codpro=" and (b.cod_pro>='".$codpro1."' and b.cod_pro<='".$codpro2."')";
		}
			
		$ls_sql="select b.cod_pro as cod_pro, MAX(e.nompro) as nompro,MAX(a.ideact),MAX(a.codact)
                 from saf_dta a
                 join saf_activo b on (a.codact=b.codact)
                 left join rpc_proveedor e on (b.cod_pro =e.cod_pro)
                 left join soc_ordencompra f on (b.numordcom=f.numordcom)
				 where a.codemp='".$ls_codemp."' ".$ls_fecha.$ls_codigo.$ls_codpro." group by b.cod_pro ".$ls_orden;     
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);		
		if($rs_data===false)
		{
		   $this->io_msg->message("CLASE->Report MÉTODO->uf_select_proveedor ERROR->".$this->io_funcion->uf_convertirmsg($this-> io_sql->message));
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
	}
////-------------------------------------------------------------------------------------------------------------------------------
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////función que selecciona al proveedor y la unidad Administrativa de un bien///////////////////////////////////////////////////////////////////
////////////creado por la Ing. Jennifer Rivero //////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function uf_select_proveedor_tipo_bien($codpro1,$codpro2,$codart1,$codart2,$fecha1,$fecha2,$coduni,$orden)
	{
	  	$lb_valido=false;
		$this->io_sql=new class_sql($this->con);
		
		$ls_fecha= "";	
		$ls_orden= "";	
		$ls_codigo= "";		
		$ls_codpro= "";	
		$ls_codunidad= "";
		
		$arre=$_SESSION["la_empresa"];
	    $ls_codemp=$arre["codemp"];
			
		if((!empty($fecha1))&&(!empty($fecha2)))
		{
	      $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($fecha1);
		  $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($fecha2);
		  $ls_fecha = " and (a.fecincact>= '".$ld_auxdesde."' and a.fecincact <= '".$ld_auxhasta."')";
		}
				
		if($orden==0)
		{
		  $ls_orden=" order by b.cod_pro,a.ideact,a.codact";
		}

	    else
		{
		  $ls_orden=" order by e.nompro";
		}
		
		if($codart1!="" && $codart2!="")
		{
		  $ls_codigo=" and (a.codact>='".$codart1."' and a.codact<='".$codart2."')";
		}
		if($codpro1!="" && $codpro2!="")
		{
		  $ls_codpro=" and (b.cod_pro>='".$codpro1."' and b.cod_pro<='".$codpro2."')";
		}
		
		if($coduni!="")
		{
		  $ls_codunidad=" and (a.coduniadm='".$coduni."')";
		}
			
		$ls_sql="select (select count(p.ideact) from saf_dta p join saf_activo m on (p.codact=m.codact) join rpc_proveedor n on (m.cod_pro=n.cod_pro) where  p.coduniadm=a.coduniadm  and m.cod_pro=b.cod_pro ) as cantidad, 
                 b.cod_pro as cod_pro, e.nompro as nompro,g.coduniadm as coduniadm, g.denuniadm as denuniadm,a.ideact,a.codact,e.nompro
                 from saf_dta a
                 join saf_activo b on (a.codact=b.codact)
                 left join rpc_proveedor e on (b.cod_pro =e.cod_pro)
                 left join saf_unidadadministrativa g on (a.coduniadm=g.coduniadm)
				 where a.codemp='".$ls_codemp."' ".$ls_fecha.$ls_codigo.$ls_codpro.$ls_codunidad." group by b.cod_pro, a.coduniadm, e.nompro, g.coduniadm, g.denuniadm, a.codact, a.ideact".$ls_orden;        
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);
		if($rs_data===false)
		{
		   $this->io_msg->message("CLASE->Report MÉTODO->uf_select_proveedor_tipo_bien ERROR->".$this->io_funcion->uf_convertirmsg($this-> io_sql->message));
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
	}
////-------------------------------------------------------------------------------------------------------------------------------

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////función que selecciona el comprobante de movimiento de un bien///////////////////////////////////////////////////////////////////
////////////creado por la Ing. Jennifer Rivero //////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function uf_select_comprobante_salida_activo($fecha1,$fecha2,$cmpmov)
	{
	  	$lb_valido=false;
		$this->io_sql=new class_sql($this->con);
		
		$ls_fecha= "";	
		$ls_orden= "";	
		$ls_codigo= "";		
				
		$arre=$_SESSION["la_empresa"];
	    $ls_codemp=$arre["codemp"];
			
		if((!empty($fecha1))&&(!empty($fecha2)))
		{
	      $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($fecha1);
		  $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($fecha2);
		  $ls_fecha = " and (a.feccmp >= '".$ld_auxdesde."' and a.feccmp  <= '".$ld_auxhasta."')";
		}	
		
		if($cmpmov!="")
		{
		  $ls_codigo=" and (a.cmpmov='".$cmpmov."')";
		}
		
			
		$ls_sql="select a.codemp as codemp, a.cmpmov as cmpmov,a.feccmp as feccmp
		         from saf_movimiento a				 
				 where a.codemp='".$ls_codemp."'".$ls_fecha.$ls_codigo;        
				 
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);		
		if($rs_data===false)
		{
		   $this->io_msg->message("CLASE->Report MÉTODO->uf_select_comprobante_salida_activo ERROR->".$this->io_funcion->uf_convertirmsg($this-> io_sql->message));
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
	}
////-------------------------------------------------------------------------------------------------------------------------------

////////////////////funcion para mostrar tipo de adquisición de bienes/////////////////////////////////////////////////
///////////////////creado por la Ing. Jennifer Rivero/////////////////////////////////////////////////////////////////////////7
 function uf_select_movimientos_bien($fecha1,$fecha2,$cmpmov,$causa,$orden)
	{
	  	$lb_valido=false;
		$this->io_sql=new class_sql($this->con);
		
		$ls_fecha= "";	
		$ls_orden= "";	
		$ls_codigo= "";		
		
		$arre=$_SESSION["la_empresa"];
	    $ls_codemp=$arre["codemp"];
			
		if((!empty($fecha1))&&(!empty($fecha2)))
		{
	      $ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($fecha1);
		  $ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($fecha2);
		  $ls_fecha = " and (a.feccmp >= '".$ld_auxdesde."' and a.feccmp  <= '".$ld_auxhasta."')";
		}
				
		if($orden==0)
		{
		  $ls_orden=" order by b.codact";
		}
	    else
		{
		  $ls_orden=" order by b.codact, e.denact";
		}	
	
		if($cmpmov!="")
		{
		   $ls_codigo=" and (a.cmpmov='".$cmpmov."' and b.codcau='".$causa."')";
		}		
			
		$ls_sql="select a.codemp as codemp, a.cmpmov as cmpmov,a.feccmp as feccmp, b.codact as codact, e.denact as denact, e.maract as maract, 
                e.codgru||'-'||e.codsubgru||'-'||e.codsec as grupo, f.seract as seract, f.coduniadm as coduniadm, g.denuniadm as denuniadm,
				h.dencau as dencau, a.fecentact  as fecentact,
				a.codrespri as codrespri, a.codresuso as codresuso,
				(select cedper from sno_personal where codper=a.codrespri ) as cedrespri,
				(select cedper from sno_personal where codper=a.codresuso ) as cedresuso,
				(select nomper  from sno_personal where codper=a.codrespri ) as nomrespri,
				(select nomper  from sno_personal where codper=a.codresuso) as nomresuso,
				(select apeper  from sno_personal where codper=a.codrespri ) as aperespri,
				(select  apeper from sno_personal where codper=a.codresuso) as aperesuso,
				(SELECT  n.descar FROM sno_personalnomina m  join sno_cargo n on (m.codcar=n.codcar) and (m.codnom=n.codnom)  where m.codper=a.codrespri and m.staper='1') as cargopri,
				(SELECT  n.descar FROM sno_personalnomina m  join sno_cargo n on (m.codcar=n.codcar) and (m.codnom=n.codnom)  where m.codper=a.codresuso and m.staper='1') as cargouso
					from saf_movimiento a
					join saf_dt_movimiento b on (a.cmpmov= b.cmpmov)
					left join saf_activo e on (b.codact=e.codact) 
					left join saf_dta f on (b.codact=f.codact)
					left join saf_unidadadministrativa g on (f.coduniadm=g.coduniadm)
					left join saf_causas h on (b.codcau=h.codcau) 
					where a.codemp='".$ls_codemp."' ".$ls_fecha.$ls_codigo.$ls_orden;        
				//print $ls_sql;
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);		
		if($rs_data===false)
		{
		   $this->io_msg->message("CLASE->Report MÉTODO->uf_select_movimientos_bien ERROR->".$this->io_funcion->uf_convertirmsg($this-> io_sql->message));
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
	}
////-------------------------------------------------------------------------------------------------------------------------------

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////función que selecciona el comprobante de movimiento de un bien///////////////////////////////////////////////////////////////////
////////////creado por la Ing. Jennifer Rivero //////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function uf_select_personal_confrome($codper)
	{
	  	$lb_valido=false;
		$this->io_sql=new class_sql($this->con);
		
		$arre=$_SESSION["la_empresa"];
	    $ls_codemp=$arre["codemp"];
			
		$ls_sql="select  a.codper, a.nomper as nombre, a.apeper as apellido, a.cedper as cedula,
		        (SELECT  n.descar FROM sno_personalnomina m  join sno_cargo n on (m.codcar=n.codcar) and (m.codnom=n.codnom)  where m.codper=a.codper and m.staper='1') as cargo
		         from sno_personal a				 
				 where a.codemp='".$ls_codemp."' and codper='".$codper."'";      
				//print $ls_sql;
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);		
		if($rs_data===false)
		{
		   $this->io_msg->message("CLASE->Report MÉTODO->uf_select_personal_confrome ERROR->".$this->io_funcion->uf_convertirmsg($this-> io_sql->message));
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
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       Inventaio de Bienes por Unidad Organizativa                          //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_load_bienes_uniadm ($as_codemp,$ai_ordenact,$as_coddesde,$as_codhasta,$as_coduniadmdesde,$as_coduniadmhasta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_saf_load_bienes_uniadm
		//	           Access: public
		//  		Arguments: as_codemp    // codigo de empresa
		//  			       ai_ordenact  // parametro por el cuan se vana ordenar los resultados de la consulta
		//  			       as_coddesde  // codigo de activo de inicio del intervalo para la busqueda
		//  			       as_codhasta  // codigo de activo de fin del intervalo para la busqueda
		//                     as_coduniadmdesde // codigo de la unidad administrativa de inicio del intervalo para la busqueda
		//  			       as_coduniadmhasta // codigo de la unidad administrativa  de fin del intervalo para la busqueda
		//	         Returns : Retorna un Booleano
		//	      Description: Funcion que se encarga de obtener los bienes pertenecientes a una unidad administrativa
		//         Creado por: Ing. María Beatriz Unda 
		//   Fecha de Cracion: 25/06/2008						Fecha de Ultima Modificación: 	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=false;
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND saf_activo.codact >='".$as_coddesde."'".
					   " AND saf_activo.codact <='".$as_codhasta."'";
		}
		
		if((!empty($as_coduniadmdesde))&&(!empty($as_coduniadmhasta)))
		{
			$ls_sqlint=$ls_sqlint." AND saf_dta.coduniadm >='".trim ($as_coduniadmdesde)."'".
							      " AND saf_dta.coduniadm <='".trim ($as_coduniadmhasta)."'";
		}
		if($ai_ordenact==0)
		{
			$ls_order="saf_dta.coduniadm, saf_activo.codact";
		}
		else
		{
			$ls_order="saf_dta.coduniadm, saf_activo.denact";
		}
		
		$ls_sql=" SELECT saf_dta.codact, saf_dta.coduniadm, saf_activo.denact, saf_activo.codgru, saf_activo.codsubgru, ".
                "        saf_activo.codsec,saf_activo.costo, spg_unidadadministrativa.denuniadm ".				
				" FROM   saf_activo, saf_dta, spg_unidadadministrativa ".
				" WHERE  saf_activo.codemp='".$as_codemp."'  AND ".
				"        saf_dta.codemp=saf_activo.codemp    AND ".	
				"        spg_unidadadministrativa.codemp='".$as_codemp."'  AND ".
				"        spg_unidadadministrativa.coduniadm=saf_dta.coduniadm    AND ".				
				"        saf_dta.codact=saf_activo.codact  ".$ls_sqlint.
				" ORDER BY ".$ls_order."";			
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_bienes_uniadm ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin uf_saf_load_bienes_uniadm

	function uf_saf_buscar_prestamo($as_codemp,$as_cmpres,$as_coduniadmcede,$as_coduniadmrece,$ad_fecenacta,
	                                $as_codresced,$as_codreserec,$as_codper)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_buscar_prestamo
	//	           Access:   public
	//  		Arguments:   as_codemp     // código de empresa
	//  			         as_cmpres     // comprobante de préstamo
	//  			         as_coduniadmcede  // código de la unidad cedente
	//  			         as_coduniadmrece  // código de la unidad receptora
	//                       ad_fecenacta      // fecha del comprobante 
	//                       as_codresced     // código del responsable de la unidad cedente
	//                       as_codreserec    // código del responsable de la unidad receptora
	//                       as_codper        // código del testigo  
	//
	//
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles de un movimiento
	//         Creado por:   Ing.Gloriely Fréitez          
	//   Fecha de Cracion:   24/04/2008						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		
		$ls_sql=" Select dt_prestamo.codact,count(dt_prestamo.codact) as cantidad, MAX(activo.denact) as denact, MAX(activo.costo) as costo,".
		        " ((MAX(activo.costo))*(count(dt_prestamo.codact))) as total ".
         		"  FROM saf_dt_prestamo dt_prestamo".
				" Join saf_prestamo prestamo On prestamo.codemp = dt_prestamo.codemp and ".
				"   prestamo.cmppre = dt_prestamo.cmppre and ".
				"   prestamo.coduniced = dt_prestamo.coduniced and ".
				"   prestamo.codunirec = dt_prestamo.codunirec and".
				"   prestamo.fecpreact = dt_prestamo.fecpreact".
				" Join saf_activo activo On  activo.codemp = dt_prestamo.codemp and ".
				"     activo.codact = dt_prestamo.codact".
				" WHERE dt_prestamo.cmppre ='".$as_cmpres."' ".
				" GROUP BY dt_prestamo.codact ";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_buscar_prestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 
	}// fin function uf_saf_buscar_prestamo
	
	function uf_saf_buscarcargos_reponcedente($as_codemp,$as_cmpres,$as_codresced)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_buscarcargos_reponcedente
	//	           Access:   public
	//  		Arguments:   as_codemp     // código de empresa
	//  			         as_cmpres     // comprobante de préstamo
	//                       as_codresced     // código del responsable de la unidad cedente
	//
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que la cédula y el cargo del reponsable cedente
	//         Creado por:   Ing.Gloriely Fréitez          
	//   Fecha de Cracion:   24/04/2008						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		
		$ls_sql=" select nomper, apeper, cedper, sno_nomina.racnom, MAX(sno_cargo.descar)as descar, MAX(sno_asignacioncargo.denasicar)as denasicar".
         		"  from saf_prestamo, sno_personal, sno_nomina, sno_personalnomina, sno_cargo, sno_asignacioncargo".
				" WHERE  saf_prestamo.codemp= '".$as_codemp."'".
				"  AND sno_nomina.espnom='0'".
				"  AND saf_prestamo.cmppre='".$as_cmpres."'".
				"  AND saf_prestamo.codemp = sno_personal.codemp ".
				"  AND  saf_prestamo.codresced ='".$as_codresced."'".
				"  AND  saf_prestamo.codresced = sno_personal.codper".
				"  AND  sno_personal.codemp = sno_personalnomina.codemp".
				"  AND  sno_personal.codper = sno_personalnomina.codper".
				"  AND  sno_personalnomina.codemp = sno_nomina.codemp".
				"  AND  sno_personalnomina.codnom = sno_nomina.codnom ".
				" AND  sno_personalnomina.codemp = sno_cargo.codemp ".
				" AND  sno_personalnomina.codnom = sno_cargo.codnom ".
				" AND  sno_personalnomina.codcar = sno_cargo.codcar ".
				" AND  sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
				" AND  sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				" AND  sno_personalnomina.codasicar = sno_asignacioncargo.codasicar ".
				" GROUP by sno_personal.nomper,sno_personal.apeper,sno_personal.cedper,sno_nomina.racnom,sno_cargo.descar,sno_asignacioncargo.denasicar,sno_cargo.codcar";
		
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_buscarcargos_reponcedente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 
	}// fin function  uf_saf_buscarcargos_reponcedente

	function uf_saf_buscarcargos_reponreceptor($as_codemp,$as_cmpres,$as_codreserec)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_buscarcargos_reponreceptor
	//	           Access:   public
	//  		Arguments:   as_codemp     // código de empresa
	//  			         as_cmpres     // comprobante de préstamo
	//                       as_codresrec     // código del responsable de la unidad receptor
	//         
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que la cédula y el cargo del reponsable receptor
	//         Creado por:   Ing.Gloriely Fréitez          
	//   Fecha de Cracion:   24/04/2008						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		
		$ls_sql=" select nomper, apeper, cedper, sno_nomina.racnom, MAX(sno_cargo.descar)as descar, MAX(sno_asignacioncargo.denasicar)as denasicar".
         		"  from saf_prestamo, sno_personal, sno_nomina, sno_personalnomina, sno_cargo, sno_asignacioncargo".
				" WHERE  saf_prestamo.codemp= '".$as_codemp."'".
				"  AND sno_nomina.espnom='0'".
				"  AND saf_prestamo.cmppre='".$as_cmpres."'".
				"  AND saf_prestamo.codemp = sno_personal.codemp ".
				"  AND  saf_prestamo.codresrec ='".$as_codreserec."'".
				"  AND  saf_prestamo.codresrec = sno_personal.codper".
				"  AND  sno_personal.codemp = sno_personalnomina.codemp".
				"  AND  sno_personal.codper = sno_personalnomina.codper".
				"  AND  sno_personalnomina.codemp = sno_nomina.codemp".
				"  AND  sno_personalnomina.codnom = sno_nomina.codnom ".
				" AND  sno_personalnomina.codemp = sno_cargo.codemp ".
				" AND  sno_personalnomina.codnom = sno_cargo.codnom ".
				" AND  sno_personalnomina.codcar = sno_cargo.codcar ".
				" AND  sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
				" AND  sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				" AND  sno_personalnomina.codasicar = sno_asignacioncargo.codasicar ".
				" GROUP by sno_personal.nomper,sno_personal.apeper,sno_personal.cedper,sno_nomina.racnom,sno_cargo.descar,sno_asignacioncargo.denasicar,sno_cargo.codcar";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_buscar_prestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 
	}// fin function  uf_saf_buscarcargos_reponreceptor

	function uf_saf_buscarcargos_repontestigo($as_codemp,$as_cmpres,$as_codper)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_buscarcargos_reponreceptor
	//	           Access:   public
	//  		Arguments:   as_codemp     // código de empresa
	//  			         as_cmpres     // comprobante de préstamo
	//                       as_codper     // código del testigo
	//         
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que la cédula y el cargo del testigo.
	//         Creado por:   Ing.Gloriely Fréitez          
	//   Fecha de Cracion:   24/04/2008						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		
		$ls_sql=" select nomper, apeper, cedper, sno_nomina.racnom, MAX(sno_cargo.descar)as descar, MAX(sno_asignacioncargo.denasicar)as denasicar".
         		"  from saf_prestamo, sno_personal, sno_nomina, sno_personalnomina, sno_cargo, sno_asignacioncargo".
				" WHERE  saf_prestamo.codemp= '".$as_codemp."'".
				"  AND sno_nomina.espnom='0'".
				"  AND saf_prestamo.cmppre='".$as_cmpres."'".
				"  AND saf_prestamo.codemp = sno_personal.codemp ".
				"  AND  saf_prestamo.codtespre='".$as_codper."'".
				"  AND  saf_prestamo.codtespre = sno_personal.codper".
				"  AND  sno_personal.codemp = sno_personalnomina.codemp".
				"  AND  sno_personal.codper = sno_personalnomina.codper".
				"  AND  sno_personalnomina.codemp = sno_nomina.codemp".
				"  AND  sno_personalnomina.codnom = sno_nomina.codnom ".
				" AND  sno_personalnomina.codemp = sno_cargo.codemp ".
				" AND  sno_personalnomina.codnom = sno_cargo.codnom ".
				" AND  sno_personalnomina.codcar = sno_cargo.codcar ".
				" AND  sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
				" AND  sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				" AND  sno_personalnomina.codasicar = sno_asignacioncargo.codasicar ".
				" GROUP by sno_personal.nomper,sno_personal.apeper,sno_personal.cedper,sno_nomina.racnom,sno_cargo.descar,sno_asignacioncargo.denasicar,sno_cargo.codcar";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_buscar_prestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 
	}// fin function  uf_saf_buscarcargos_reponreceptor
	
	function uf_saf_buscar_autorización($as_codemp,$as_cmpsal,$as_coduniadmcede,$ad_fechauto,$as_codprov,
	                                     $as_cedrepre,$as_concepto,$ad_fecent,$ad_fecdevo,$as_obser)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_buscar_autorización
	//	           Access:   public
	//  		Arguments:   as_codemp     // código de empresa
	//  			         as_cmpsal     // número de la autorización
	//  			         as_coduniadmcede  // código de la unidad cedente
	//  			         ad_fechauto   // fecha de al autorización
	//                       as_codprov     // código del proveedor
	//                       as_cedrepre     // cédula del responsable de la empresa quien recibe
	//                       as_concepto    // concepto de la autorización
	//                       ad_fecent        // fecha de entrega  
	//                       ad_fecent       // fecha de entrega
	//                       ad_fecent       // observación de la autorización 
	//
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que busca los datos de la autorización
	//         Creado por:   Ing.Gloriely Fréitez          
	//   Fecha de Cracion:   30/04/2008						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
/*		$ad_fechauto=$this->io_funcion->uf_convertirdatetobd($ad_fechauto);
		$ld_fecent=$this->io_funcion->uf_convertirdatetobd($ld_fecent);
		$ld_fecdevo=$this->io_funcion->uf_convertirdatetobd($ld_fecdevo);*/
		$ls_sql=" Select saf_dt_autsalida.codact,saf_dt_autsalida.ideact,count(saf_dt_autsalida.codact) as cantidad, MAX(saf_activo.denact) as denact, MAX(saf_activo.costo) as costo,".
		        " ((MAX(saf_activo.costo))*(count(saf_dt_autsalida.codact))) as Total ".
         		"  FROM saf_dt_autsalida ".
				" Join saf_autsalida On saf_autsalida.codemp = saf_dt_autsalida.codemp and ".
				"   saf_autsalida.cmpsal=saf_dt_autsalida.cmpsal and ".
				"   saf_autsalida.coduniadm=saf_dt_autsalida.coduniadm and ".
				//"   saf_autsalida.codpro='".$as_codprov.'".
				"   saf_autsalida.fecaut=saf_dt_autsalida.fecaut".
				" Join saf_activo On  saf_activo.codemp = saf_dt_autsalida.codemp and ".
				"     saf_activo.codact = saf_dt_autsalida.codact".
				" WHERE saf_dt_autsalida.cmpsal='".$as_cmpsal."' ".
				" GROUP BY saf_dt_autsalida.codact,saf_dt_autsalida.ideact ";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_buscar_autorización ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 
	}// fin function uf_saf_buscar_autorización	

	function uf_saf_load_cmpentrega($as_codemp,$as_cmpent,$ad_feccmp,$as_coduniadm)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_cmpentrega
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_cmpent    // Codigo del Comprobante de Entrega
		//  			         ad_feccmp    // Fecha del Comprobante de Entrega
		//  			         as_coduniadm // Codigo de la Uniadad Administrativa
		//	         Returns :   Retorna un registro
		//	      Description:   Funcion que se encarga de obtener los datos del Comprobante de Entrega
		//         Creado por:   Ing. Arnaldo Suárez     
		//   Fecha de Cracion:   09/06/2008							
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ad_feccmp = $this->io_funcion->uf_convertirdatetobd($ad_feccmp);
		$ls_gestor = $_SESSION["ls_gestor"];
		
		if(strtoupper($ls_gestor)=="MYSQLT")
	    {
	     $ls_cadena_personal="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
	    }
	    else
	    {
	     $ls_cadena_personal="sno_personal.nomper||' '||sno_personal.apeper";
	    }
	    if(strtoupper($ls_gestor)=="MYSQLT")
	    {
	     $ls_cadena_beneficiario="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
	    }
	    else
	    {
	     $ls_cadena_beneficiario="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
	    }
	   
	    $ls_sql=" SELECT saf_entrega.*, ".
                "    (CASE tipres WHEN 'P' THEN (SELECT sno_personal.nomper||' '||sno_personal.apeper  ".
                "                                   FROM sno_personal ".
				"                                 WHERE sno_personal.codemp=saf_entrega.codemp  ".
				"                                 AND sno_personal.codper=saf_entrega.codres) ".
				"                 WHEN 'B' THEN ".
				"                               (SELECT rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene ".
				"                                   FROM rpc_beneficiario WHERE rpc_beneficiario.codemp=saf_entrega.codemp ".
				"                                 AND rpc_beneficiario.ced_bene=saf_entrega.codres) END) AS nomres, ".
				"   (CASE tipres WHEN 'P' THEN (SELECT sno_personal.cedper   ".
                "                                   FROM sno_personal        ".
				"                               WHERE sno_personal.codemp=saf_entrega.codemp  ".
				"                                 AND sno_personal.codper=saf_entrega.codres) ".
                "                WHEN 'B' THEN saf_entrega.codres END) AS cedres ,".
				"    (SELECT MAX(sno_cargo.descar)as descar ".
				"        FROM sno_personal, sno_nomina, sno_personalnomina, sno_cargo, sno_asignacioncargo ".
				"     WHERE sno_personal.codper = saf_entrega.codres ".
				"     AND  sno_nomina.espnom='0' ".
				"     AND  sno_personal.codemp = sno_personalnomina.codemp ".
				"     AND  sno_personal.codper = sno_personalnomina.codper ".
				"     AND  sno_personalnomina.codemp = sno_nomina.codemp ".
				"     AND  sno_personalnomina.codnom = sno_nomina.codnom  ".
				"     AND  sno_personalnomina.codemp = sno_cargo.codemp ".
				"     AND  sno_personalnomina.codnom = sno_cargo.codnom ".
				"     AND  sno_personalnomina.codcar = sno_cargo.codcar ".
				"     AND  sno_personalnomina.codemp = sno_asignacioncargo.codemp  ".
				"     AND  sno_personalnomina.codnom = sno_asignacioncargo.codnom  ".
				"     AND  sno_personalnomina.codasicar = sno_asignacioncargo.codasicar  ".
				"     GROUP BY sno_personal.nomper,sno_personal.apeper,sno_personal.cedper,sno_nomina.racnom,sno_cargo.descar,sno_asignacioncargo.denasicar,sno_cargo.codcar) as carres, ".
				"     (CASE tiprec WHEN 'P' THEN (SELECT sno_personal.nomper||' '||sno_personal.apeper ".
				"                                    FROM sno_personal WHERE sno_personal.codemp=saf_entrega.codemp AND sno_personal.codper=saf_entrega.codrec) ".
				"                  WHEN 'B' THEN (SELECT rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene ".
				"                                    FROM rpc_beneficiario WHERE rpc_beneficiario.codemp=saf_entrega.codemp ".
				"                                    AND rpc_beneficiario.ced_bene=saf_entrega.codrec) END) AS nomrec, ".
				"   (CASE tipres WHEN 'P' THEN (SELECT sno_personal.cedper   ".
                "                                   FROM sno_personal        ".
				"                               WHERE sno_personal.codemp=saf_entrega.codemp  ".
				"                                 AND sno_personal.codper=saf_entrega.codrec) ".
                "                WHEN 'B' THEN saf_entrega.codrec END) AS cedrec ,".
				"    (SELECT MAX(sno_cargo.descar)as descar".
				"         FROM sno_personal, sno_nomina, sno_personalnomina, sno_cargo, sno_asignacioncargo ".
				"     WHERE sno_personal.codper = saf_entrega.codrec ".
				"     AND  sno_nomina.espnom='0' ".
				"     AND  sno_personal.codemp = sno_personalnomina.codemp ".
				"     AND  sno_personal.codper = sno_personalnomina.codper ".
				"     AND  sno_personalnomina.codemp = sno_nomina.codemp ".
				"     AND  sno_personalnomina.codnom = sno_nomina.codnom ".
				"     AND  sno_personalnomina.codemp = sno_cargo.codemp  ".
				"     AND  sno_personalnomina.codnom = sno_cargo.codnom  ".
				"     AND  sno_personalnomina.codcar = sno_cargo.codcar  ".
				"     AND  sno_personalnomina.codemp = sno_asignacioncargo.codemp  ".
				"     AND  sno_personalnomina.codnom = sno_asignacioncargo.codnom  ".
				"     AND  sno_personalnomina.codasicar = sno_asignacioncargo.codasicar  ".
				"     GROUP BY sno_personal.nomper,sno_personal.apeper,sno_personal.cedper,sno_nomina.racnom,sno_cargo.descar,sno_asignacioncargo.denasicar,sno_cargo.codcar) as carrec, ".
				"     (CASE tipdes WHEN 'P' THEN (SELECT sno_personal.nomper||' '||sno_personal.apeper ".
				"                                    FROM sno_personal ".
				"                                 WHERE sno_personal.codemp=saf_entrega.codemp AND sno_personal.codper=saf_entrega.coddes) ".
				"                  WHEN 'B' THEN (SELECT rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene ".
				"                                    FROM rpc_beneficiario WHERE rpc_beneficiario.codemp=saf_entrega.codemp ".
				"                                 AND rpc_beneficiario.ced_bene=saf_entrega.coddes) END) AS nomdes, ".
				"   (CASE tipres WHEN 'P' THEN (SELECT sno_personal.cedper   ".
                "                                   FROM sno_personal        ".
				"                               WHERE sno_personal.codemp=saf_entrega.codemp  ".
				"                                 AND sno_personal.codper=saf_entrega.coddes) ".
                "                WHEN 'B' THEN saf_entrega.coddes END) AS ceddes ,".
				"    (SELECT MAX(sno_cargo.descar)as descar ".
				"        FROM sno_personal, sno_nomina, sno_personalnomina, sno_cargo, sno_asignacioncargo ".
				"     WHERE sno_personal.codper = saf_entrega.coddes ".
				"     AND  sno_nomina.espnom='0' ".
				"     AND  sno_personal.codemp = sno_personalnomina.codemp ".
				"     AND  sno_personal.codper = sno_personalnomina.codper ".
				"     AND  sno_personalnomina.codemp = sno_nomina.codemp ".
				"     AND  sno_personalnomina.codnom = sno_nomina.codnom  ".
				"     AND  sno_personalnomina.codemp = sno_cargo.codemp ".
				"     AND  sno_personalnomina.codnom = sno_cargo.codnom ".
				"     AND  sno_personalnomina.codcar = sno_cargo.codcar ".
				"     AND  sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
				"     AND  sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				"     AND  sno_personalnomina.codasicar = sno_asignacioncargo.codasicar ".
				"     GROUP BY sno_personal.nomper,sno_personal.apeper,sno_personal.cedper,sno_nomina.racnom,sno_cargo.descar,sno_asignacioncargo.denasicar,sno_cargo.codcar) as cardes, ".
				"    (SELECT denuniadm FROM spg_unidadadministrativa ".
				"     WHERE spg_unidadadministrativa.coduniadm=saf_entrega.coduniadm) as denuniadm  ".
				"     FROM saf_entrega  ".
			    " WHERE saf_entrega.codemp='".$as_codemp."'".
			    "   AND saf_entrega.feccmp = '".$ad_feccmp."' ".
			    "   AND saf_entrega.coduniadm = '".$as_coduniadm."' ".
			    "   AND saf_entrega.cmpent = '".$as_cmpent."' ";			
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_cmpentrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_cmpentrega

    function uf_saf_load_dt_cmpentrega($as_codemp,$as_cmpent,$ad_feccmp,$as_coduniadm)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_saf_load_dt_ cmpentrega
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_cmpent    // Codigo del Comprobante de Entrega
		//  			         ad_feccmp    // Fecha del Comprobante de Entrega
		//  			         as_coduniadm // Codigo de la Uniadad Administrativa
		//	         Returns :   Retorna un registro
		//	      Description:   Funcion que se encarga de obtener los datos del detalle del comprobante de entrega
		//   Fecha de Cracion:   09/06/2008	
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ad_feccmp = $this->io_funcion->uf_convertirdatetobd($ad_feccmp);
	    $ls_sql="SELECT dt_ent.codact, COUNT(dta.ideact) as cantidad, activo.denact, activo.catalogo, activo.costo ".
                "     FROM saf_dt_entrega dt_ent ".
                " JOIN saf_dta dta ON dta.codemp = dt_ent.codemp ".
                "                 AND dta.codact = dt_ent.codact ".
                "                 AND dta.ideact = dt_ent.ideact ".
                " JOIN saf_activo activo ON activo.codemp = dta.codemp ".
                "                       AND activo.codact = dta.codact".
                " WHERE dt_ent.codemp   ='". $as_codemp ."' ".
				"   AND dt_ent.cmpent   ='". $as_cmpent ."' ".
				"   AND dt_ent.coduniadm='". $as_coduniadm ."' ".
				"   AND dt_ent.feccmp   ='". $ad_feccmp ."' ".
				" GROUP BY dt_ent.codact,activo.denact, activo.catalogo, activo.costo ".				 
                " ORDER BY dt_ent.codact ";			
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_load_dt_cmpentrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_saf_load_dt_cmpentrega

    function uf_saf_buscar_registroaux($as_codemp,$as_codsigecof,$as_orden,$ad_desde,$as_hasta,$as_codactdes,
	                                 $as_codhasta,$as_codresuso)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_saf_buscar_registroaux
	//	           Access:   public
	//  		Arguments:   as_codemp     // código de empresa
	//  			         as_cmpsal     // número de la autorización
	//  			         as_coduniadmcede  // código de la unidad cedente
	//  			         ad_fechauto   // fecha de al autorización
	//                       as_codprov     // código del proveedor
	//                       as_cedrepre     // cédula del responsable de la empresa quien recibe
	//                       as_concepto    // concepto de la autorización
	//                       ad_fecent        // fecha de entrega  
	//                       ad_fecent       // fecha de entrega
	//                       ad_fecent       // observación de la autorización 
	//
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que busca los datos de la autorización
	//         Creado por:   Ing.Gloriely Fréitez          
	//   Fecha de Cracion:   30/04/2008						Fecha de Ultima Modificación:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	
		//$ld_fecdevo=$this->io_funcion->uf_convertirdatetobd($ld_fecdevo);
		
		if((!empty($ad_desde))&&(!empty($as_hasta)))
		{
	      $ad_desde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
		  $as_hasta=$this->io_funcion->uf_convertirdatetobd($as_hasta);
		  $as_fecha = " and (saf_dt_movimiento.feccmp >= '".$ad_desde."' and saf_dt_movimiento.feccmp  <= '".$as_hasta."')";
		}
				
		if($as_orden==0)
		{
		  $as_orden=" order by saf_dt_movimiento.codact";
		}
	    else
		{
		  $as_orden=" order by saf_activo.denact";
		}
		
		
		$ls_sql=" Select saf_dt_movimiento.codemp,saf_dt_movimiento.codcau,saf_dt_movimiento.feccmp,saf_dt_movimiento.codact,
		         saf_dt_movimiento.ideact,saf_dt_movimiento.monact,saf_dt_movimiento.desmov,saf_movimiento.codcau,
				 saf_movimiento.descmp,saf_movimiento.codresuso,saf_activo.denact,saf_causas.dencau,saf_causas.tipcau,saf_causas.estcat ".
		        " FROM saf_dt_movimiento,saf_movimiento,saf_activo,saf_causas".
				" WHERE saf_dt_movimiento.codemp='".$as_codemp."'     ".
				" AND saf_dt_movimiento.codemp=saf_movimiento.codemp  ".
				" AND saf_dt_movimiento.cmpmov=saf_movimiento.cmpmov  ".
				" AND saf_dt_movimiento.feccmp=saf_movimiento.feccmp  ".
				" AND saf_dt_movimiento.estcat=saf_movimiento.estcat  ".
				" AND saf_dt_movimiento.codcau=saf_movimiento.codcau  ".
				" AND saf_dt_movimiento.feccmp >= '".$ad_desde."' and saf_dt_movimiento.feccmp<= '".$as_hasta."'  ".
				" AND saf_dt_movimiento.codact >= '".$as_codactdes."' and saf_dt_movimiento.codact<= '".$as_codhasta."'  ".
				" AND saf_movimiento.codresuso= '".$as_codresuso."' ".
				" AND saf_dt_movimiento.codact=saf_activo.codact ".
				" AND saf_dt_movimiento.codcau=saf_causas.codcau ".
				" AND saf_dt_movimiento.estcat=saf_causas.estcat ".
				" $as_orden ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_saf_buscar_registroaux ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 
	}// fin function uf_saf_buscar_registroaux
} //fin  class sigesp_siv_class_report
?>